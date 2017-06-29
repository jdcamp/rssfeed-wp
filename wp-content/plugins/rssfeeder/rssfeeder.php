<?php
/*
Plugin Name: RSS Feeder
*/
use PicoFeed\Reader\Reader;
require 'vendor/autoload.php';
require_once 'feed-create.php';
require_once 'init.php';
require_once 'feed-update.php';
require_once 'feed-list.php';
// add_action('admin_menu', 'add_feed_menu_page');
//on install runs table_install()
register_activation_hook(__FILE__, 'table_install');
//Installs wp_feeder database
function table_install()
{
  global $wpdb;
  $table_name = $wpdb->prefix . "feeder";
  $charset_collate = $wpdb->get_charset_collate();
  $sql = "CREATE TABLE $table_name (
          title tinytext NOT NULL,
      feed_url varchar(255) DEFAULT '' NOT NULL,
      keywords varchar(255) DEFAULT '' NULL,
          PRIMARY KEY (id)
        ) $charset_collate; ";
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta($sql);
  //Register category
  wp_create_category('External Source');
}

//inserts rss url into the feed table
function add_feed_url( )
{
    global $wpdb;
    $title = $_POST[ 'title' ];
    $url   = $_POST[ 'url' ];
    $wpdb->insert( $wpdb->prefix . 'feeder', array(
         'title' => $title,
        'feed_url' => $url
    ) );
}
//checks if feed is already in the database
function is_unique_feed( $url )
{
    global $wpdb;
    $table  = $wpdb->prefix . 'feeder';
    $query = $wpdb->prepare("SELECT * FROM %s where feed_url = '%d';",$table,$url);
    $result = $wpdb->get_results( $query);
    if ( empty( $result ) ) {
        return true;
    } else {
        return false;
    }
}
//add feed menu item in the admin view
function add_rss_post_page( )
{
    add_posts_page( 'rss feeder', 'rss feeder', 'manage_options', 'rss-feeder', 'rss_form' );
    add_posts_page( 'feeds', 'feeds', 'manage_options', 'feeds-list', 'sinetiks_feeder_list', 'sinetiks_feeder_update', 'sinetiks_feeder_create' );
}
//returns true if id matched guid in database. prevents duplicate posts
function is_guid_unique( $id )
{
    global $wpdb;
    $id   = 'http://' . $id;
    $table   = $wpdb->prefix . 'posts';
    $query   = $wpdb->prepare( 'SELECT COUNT(*) FROM %s WHERE guid = "%d" LIMIT 1;', $table, $id );
    $results = $wpdb->get_var( $query );
    if ( $results > 0 ) {
        error_log( 'id dup', 0 );
        return false;
    } else {
        error_log( 'id unique', 0 );
        return true;
    }
}
//Checks a string if it contains any of the keywords. All keywords must match
function check_key_words( $sentence, $keywords )
{
    if ( empty( ( array ) $keywords ) || $keywords == NULL ) {
        return true;
    }
    foreach ( (array) $keywords as $keyword ) {
        $keyword = trim( $keyword );
        if ( stripos( $sentence, $keyword ) === false ) {
            return false;
        }
    }
    return true;
}
//trys to fetch results from PicoFeed and returns true if it can get items from feed
function is_valid_rss_url( $url )
{
    try {
        $reader   = new Reader;
        $resource = $reader->download( $url );

        $parser = $reader->getParser( $resource->getUrl(), $resource->getContent(), $resource->getEncoding() );

        $feed = $parser->execute();
        $test = $feed->getItems();
        return true;
    }
    catch ( Exception $e ) {
        return false;
    }
}
//add custom fields
function my_add_custom_fields( $post_id, $source )
{
    if ( $_POST[ 'post_type' ] == 'post' ) {
        add_post_meta( $post_id, 'source', $source, true );
    }
}
//Gets rss feeds from DB and publishes new posts from the rss feeds
function get_posts_from_feed( )
{
    global $wpdb;
    error_log( "Get Post Fired", 0 );
    //get table holding rss feed
    $table = $wpdb->prefix . 'feeder';
    $result = $wpdb->get_results( "SELECT * FROM $table" );
    foreach ( $result as $queried_feed ) {
        $my_feed  = $queried_feed->feed_url;
        $keywords = $queried_feed->keywords;
        //if there are keywords sets to an array
        if ( $keywords ) {
            $keywords = explode( ',', $keywords );
        }
        $reader   = new Reader;
        $resource = $reader->download( $my_feed );
        $parser   = $reader->getParser( $resource->getUrl(), $resource->getContent(), $resource->getEncoding() );
        $feed     = $parser->execute();
        $items    = $feed->getItems();
        foreach ( $items as $item ) {
            $title = $item->getTitle();

            $id = $item->getId();
            $author = $item->getAuthor();

            if (!get_page_by_title($title, 'OBJECT', 'post')) {
                $body = $item->getContent();
                error_log('title: ' . get_page_by_title($title, 'OBJECT', 'post'), 0);
                if (check_key_words($body, $keywords) || check_key_words($title, $keywords)) {
                    $author = $item->getAuthor();
                    $user_id = get_user_by( 'login', $author );
                    if ( !$user_id ) {
                    	$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
                    	$user_id = wp_create_user( $author, $random_password);
                    } else {
                    	$user_id = get_user_by('login', $author)->get('id');
                    }
                    $url = $item->getUrl();
                    $body = $item->getContent();
                    $tags = implode(', ',$item->getCategories());
                    $namespaces = implode(', ', $item->getNamespaces());
                    $body = $body . '<br><a href="' . $url . '"> Read Original Article Here</a><br>';
                    $date = $item->getPublishedDate();
                    $date = date_format($date, 'Y-m-d H:i:s');
                    $category = get_category_by_slug('External Source');
                    $trimmed = parse_url($url);
                    $source = str_replace('www.', "", $trimmed['host']);
                    $args = array(
                     'post_author' => $user_id,
                     'post_content' => $body,
                     'post_title' => $title,
                     'post_status' => "publish",
                     'post_type' => "post",
                     'guid' => $author->getID,
                     'post_category' => array($category->term_id),
                     'meta_input' => array(
                       '_source' => $source,
                       '_post_author' => $author
                     ),
                  );
                    if ($post_id = wp_insert_post($args)) {
                      wp_set_post_tags( $post_id, $tags, true );
                    }
                }
            }
        }
    }
}


add_filter( 'cron_schedules', 'isa_add_every_three_minutes' );
function isa_add_every_three_minutes( $schedules )
{
    $schedules[ 'every_three_minutes' ] = array(
         'interval' => 180,
        'display' => __( 'Every 3 Minutes', 'textdomain' )
    );
    return $schedules;
}

// Schedule an action if it's not already scheduled
if ( !wp_next_scheduled( 'isa_add_every_three_minutes' ) ) {
    wp_schedule_event( time(), 'every_three_minutes', 'isa_add_every_three_minutes' );
}

// Hook into that action that'll fire every three minutes
add_action( 'isa_add_every_three_minutes', 'get_posts_from_feed' );

add_action( 'save_post', 'save_original_post', 10 );

function save_original_post( $post )
{
    global $wpdb;
    $query   = $wpdb->prepare( 'SELECT wp_posts.id
    FROM wp_posts
    JOIN wp_postmeta
      ON wp_postmeta.post_id = %s
      WHERE wp_postmeta.meta_key = "_source";', $post );
    $results = $wpdb->get_results( $query );
    if ( !$results ) {
        add_post_meta( $post, '_source', 'internal' );
    }
}

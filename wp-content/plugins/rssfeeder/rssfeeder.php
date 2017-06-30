<?php
/**
* @package RSS Autoblog
*Plugin Name: RSS Autoblog
*Description: Simple auto blogger. Takes in rss feeds and posts
*Version: 0.9
*Author: Jake C & Jayeson K
*/
use PicoFeed\Reader\Reader;

require 'vendor/autoload.php';

require_once 'feed-create.php';

require_once 'init.php';

require_once 'feed-update.php';

require_once 'feed-list.php';

/**
* Adds feeder table to DB.
*/
function rssfeeder_install()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "feeder";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
      id int(11) NOT NULL AUTO_INCREMENT,
      title tinytext NOT NULL,
      feed_url varchar(255) DEFAULT '' NOT NULL,
      keywords varchar(255) DEFAULT '' NULL,
      category varchar(255) DEFAULT '' NOT NULL,
          PRIMARY KEY (id)
        ) $charset_collate; ";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql);
}

/**
* Checks if feed is already in the database
* @param string $url rss url
* @return boolean
*/
function is_unique_feed($url)
{
    global $wpdb;
    $result = $wpdb->get_results("SELECT * FROM wp_feeder where feed_url = '$url'");
    if (empty($result)) {
        return true;
    } else {
        return false;
    }
}

/**
* add feed menu item in the admin view
*/
function add_rss_post_page()
{
    add_posts_page('rss feeder', 'rss feeder', 'manage_options', 'rss-feeder', 'rss_form');
    add_posts_page('feeds', 'feeds', 'manage_options', 'feeds-list', 'feeder_feeder_list', 'feeder_feeder_update', 'feeder_feeder_create');
}


/**
* checks if post exists in DB by matching the guid or title
* @param string $id id of the rss article
* @param string $title title of the rss article
* @return boolean
*/
function is_unique_post($id, $title)
{
    global $wpdb;
    $id = 'http://' . $id;
    $table = $wpdb->prefix . 'posts';
    $query = $wpdb->prepare('SELECT COUNT(*) FROM ' . $table . ' WHERE guid = "%s" OR post_title = "%s" LIMIT 1;', $id, $title);
    $results = $wpdb->get_var($query);
    if ($results > 0) {
        return false;
    } else {
        return true;
    }
}

/**
* Checks if a keyword is in a string
* @param string $sentence
* @param array string $title title of the rss article
* @param array $keywords {
*       @type string $value,
*       @type string $value,
*       ...
*     }
* @return boolean
*/
function check_key_words($sentence, $keywords)
{
    if (empty(( array )$keywords) || $keywords == null) {
        return true;
    }

    foreach ((array)$keywords as $keyword) {
        $keyword = trim($keyword);
        if (stripos($sentence, $keyword) === false) {
            return false;
        }
    }

    return true;
}

/**
* Checks if feed is valid by trying to parse it using Reader
* @param string $url rss url
* @return boolean
*/
function is_valid_rss_url($url)
{
    try {
        $reader = new Reader;
        $resource = $reader->download($url);
        $parser = $reader->getParser($resource->getUrl(), $resource->getContent(), $resource->getEncoding());
        $feed = $parser->execute();
        $test = $feed->getItems();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

// add custom fields

function my_add_custom_fields($post_id, $source)
{
    if ($_POST['post_type'] == 'post') {
        add_post_meta($post_id, 'source', $source, true);
    }
}

/**
* Grabs feeds from the DB and uses Reader to post articles. Called by cron
*/
function get_posts_from_feed()
{
    global $wpdb;
    $table = $wpdb->prefix . 'feeder';
    $result = $wpdb->get_results("SELECT * FROM $table");
    foreach ($result as $queried_feed) {
        $my_feed = $queried_feed->feed_url;
        $keywords = $queried_feed->keywords;
        $category = $queried_feed->category;
        $category = get_term_by('name', $category, 'category');
        if ($keywords) { // if there are keywords sets to an array
            $keywords = explode(',', $keywords);
        }

        $reader = new Reader;
        $resource = $reader->download($my_feed);
        $parser = $reader->getParser($resource->getUrl(), $resource->getContent(), $resource->getEncoding());
        $feed = $parser->execute();
        $items = $feed->getItems();
        foreach ($items as $item) {
            $title = $item->getTitle();
            $id = $item->getId();
            $author = $item->getAuthor();
            if (is_unique_post($id, $title)) { //checks for duplicates
                $body = $item->getContent();
                if (check_key_words($body, $keywords) || check_key_words($title, $keywords)) { //checks for the keywords
                    $author = $item->getAuthor();
                    $user_id = get_user_by('login', $author);
                    if (!$user_id) { //creates authors if author does not exist
                        $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
                        $user_id = wp_create_user($author, $random_password);
                    } else {
                        $user_id = get_user_by('login', $author)->get('ID');
                    }

                    $url = $item->getUrl();
                    $body = $item->getContent();
                    $tags = implode(', ', $item->getCategories());
                    $body = wp_trim_words($body, 128) . '<br />
                                        <p class="feed-link">
                                          <a href="' . $url . '">
                                            <button class="btn btn-single">Read Original Article Here</button>
                                            </a>
                                        </p>
                                        <br />';
                    $date = $item->getPublishedDate();
                    $date = date_format($date, 'Y-m-d H:i:s');
                    $trimmed = parse_url($url);
                    $source = str_replace('www.', "", $trimmed['host']);
                    $args = array(
                        'post_author' => $user_id,
                        'post_content' => $body,
                        'post_title' => $title,
                        'post_status' => "publish",
                        'post_type' => "post",
                        'guid' => $id,
                        'post_category' => array(
                            $category->term_id
                        ) ,
                        'meta_input' => array(
                            '_source' => $source,
                            '_post_author' => $author
                        ) ,
                    );
                    if ($post_id = wp_insert_post($args)) {
                        wp_set_post_tags($post_id, $tags, true); //add tags to post
                    }
                }
            }
        }
    }
}

/**
* Sets custom cron timing
* @param wp built in object
*/
function isa_add_every_three_minutes($schedules)
{
    $schedules['every_three_minutes'] = array(
        'interval' => 600,
        'display' => __('Every 3 Minutes', 'textdomain')
    );
    return $schedules;
}

/**
* Sets meta _source to internal if created from GUI (posts not posted from get_posts_from_feed)
* @param int $post id of the post
*/
function save_original_post($post)
{
    global $wpdb;
    $query = $wpdb->prepare('SELECT wp_posts.id
    FROM wp_posts
    JOIN wp_postmeta
      ON wp_postmeta.post_id = %d
      WHERE wp_postmeta.meta_key = "_source";', $post);
    $results = $wpdb->get_results($query);
    if (!$results) {
        add_post_meta($post, '_source', 'internal');
    }
}

if (!wp_next_scheduled('isa_add_every_three_minutes')) {
  wp_schedule_event(time(), 'every_three_minutes', 'isa_add_every_three_minutes');
}
add_action('isa_add_every_three_minutes', 'get_posts_from_feed');
add_action('save_post', 'save_original_post', 10);
add_action('init', 'rssfeeder_install');
add_filter('cron_schedules', 'isa_add_every_three_minutes');
register_activation_hook(__FILE__, 'rssfeeder_install');

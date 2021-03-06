<?php
/**
* @package RSS Autoblog
*Plugin Name: RSS Autoblog
*Description: Simple auto blogger. Takes in rss feeds and posts
*Version: 0.9
*Author: Jake C & Jayeson K
*
* This plugin is designed to automaticly add posts from an rss feed. It is called by a cronjob and called every
* three minutes. Using WP-Crontroller plugin is recommended to change the frequency of the cronjob.
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
      category varchar(255) DEFAULT '' NOT NULL
          PRIMARY KEY (id)
        ) $charset_collate; ";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql);
}
register_activation_hook(__FILE__, 'rssfeeder_install');
add_action('init', 'rssfeeder_install');

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
    if (empty(( array )$keywords) || $keywords == null) { //if no keywords are entered return true
        return true;
    }
    //all keywords must be present in the sentence
    foreach ((array)$keywords as $keyword) {
        $keyword = trim($keyword);
        if (stripos($sentence, $keyword) === false) { //if no keyword is found return false
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

/**
* Grabs feeds from the DB and uses Reader to post articles. Called by a cronjob
*/
function get_posts_from_feed()
{
  error_log("Start get posts" , 0);
    global $wpdb; //get the DB
    $table = $wpdb->prefix . 'feeder'; //get the table
    $result = $wpdb->get_results("SELECT * FROM $table");
    foreach ($result as $queried_feed) { //grabs each row in the feed table and stores the values in variables
        $queried_url = $queried_feed->feed_url;
        $keywords = $queried_feed->keywords;
        $category = $queried_feed->category;
        $category = get_term_by('name', $category, 'category');
        if ($keywords) { // if there are keywords sets to an array
            $keywords = explode(',', $keywords);
        }
        $reader = new Reader; //Uses the reader class from picoFeed to get and parse the rss feed
        $resource = $reader->download($queried_url);
        $parser = $reader->getParser($resource->getUrl(), $resource->getContent(), $resource->getEncoding());
        $feed = $parser->execute();
        $items = $feed->getItems(); //gets each article on the rss feed
        foreach ($items as $item) {
            $title = $item->getTitle();
            $id = $item->getId();
            $author = $item->getAuthor();
            error_log("before unique if statement" , 0);
          if (/*is_unique_post($id, $title)*/true == true) { //checks for duplicates
                $body = $item->getContent();
                if (check_key_words($body, $keywords) || check_key_words($title, $keywords)) { //checks for the keywords
                    $author = $item->getAuthor();
                    $user_id = get_user_by('login', $author);
                    if (!$user_id) { //creates authors if author does not exist. Then grabs id of author from new or existing author
                        $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
                        $user_id = wp_create_user($author, $random_password);
                    } else {
                        $user_id = get_user_by('login', $author)->get('ID');
                    }

                    $url = $item->getUrl(); //url of the source
                    $body = $item->getContent();
                    $tags = implode(', ', $item->getCategories());
                    //trims the body to 128 chars and adds a link to the original article to it
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
                    $source = str_replace('www.', "", $trimmed['host']); //format the source url
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
                        'meta_input' => array(//meta data
                            '_source' => $source,
                            '_post_author' => $author
                        ) ,
                    );
                    if ($post_id = wp_insert_post($args)) {//sets post id if post is added
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
function isa_add_posts($schedules)
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
    if (!$results) { //if no posts are found add meta data to the post
        add_post_meta($post, '_source', 'internal');
    }
}

if (!wp_next_scheduled('isa_add_posts')) { //sets the schedule of the cron if one is not already set
  wp_schedule_event(time(), 'hourly', 'isa_add_posts');
}
add_action('isa_add_posts', 'get_posts_from_feed', 10);
add_action('save_post', 'save_original_post', 10); //adds save posts as an action
add_filter('cron_schedules', 'isa_add_posts');

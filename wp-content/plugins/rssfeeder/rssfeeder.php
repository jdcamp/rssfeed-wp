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

add_action('admin_menu', 'add_feed_menu_page');
//on install runs table_install()
register_activation_hook(__FILE__, 'table_install');

//Installs wp_feeder database
function table_install()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'feeder';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
		id int NOT NULL AUTO_INCREMENT,
		title tinytext NOT NULL,
		feed_url varchar(255) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

function add_feed_url()
{
    global $wpdb;
    $title = $_POST['title'];
    $url = $_POST['url'];
    $wpdb->insert(
        $wpdb->prefix . 'feeder',
        array(
            'title' => $title,
            'feed_url' => $url,
        )
    );
}
function is_duplicate_feed($url)
{
    global $wpdb;
    $result = $wpdb->get_results("SELECT * FROM wp_feeder where feed_url = '$url'");
    if (empty($result)) {
        return true;
    } else {
        return false;
    }
}

function add_rss_post_page()
{
    add_posts_page('rss feeder', 'rss feeder', 'manage_options', 'rss-feeder', 'rss_form');
    add_posts_page('feeds', 'feeds', 'manage_options', 'feeds-list', 'sinetiks_feeder_list', 'sinetiks_feeder_update', 'sinetiks_feeder_create');
}

function is_valid_rss_url($url)
{
    try {
        $reader = new Reader;
        $resource = $reader->download($url);

        $parser = $reader->getParser(
      $resource->getUrl(),
      $resource->getContent(),
      $resource->getEncoding()
    );

        $feed = $parser->execute();
        $test = $feed->getItems();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function my_add_custom_fields($post_id, $source) {
    if ($_POST['post_type'] == 'post') {
        add_post_meta($post_id, 'source', $source, true);
    }
}
function get_posts_from_feed()
{
    global $wpdb;
    $result = $wpdb->get_results("SELECT * FROM wp_feeder");
    foreach ($result as $queried_feed) {
        $my_feed = $queried_feed->feed_url;
        $reader = new Reader;
        $resource = $reader->download($my_feed);

      $parser = $reader->getParser(
      $resource->getUrl(),
      $resource->getContent(),
      $resource->getEncoding()
  );

        $feed = $parser->execute();
        $test = $feed->getItems();
  // var_dump($test);
  // for ($j=0; $j < $item_count ; $j++) {
  foreach ($test as $item) {
      // }
      $title = $item->getTitle();
      if (!get_page_by_title($title, 'OBJECT', 'post')) {
          $author = $item->getAuthor();
          $url = $item->getUrl();
          $parse = parse_url($url);
          $trimmed = $parse['host'];
          $source = str_replace('http://', '', $trimmed);
          $body = wp_trim_words($item->getContent(),$num_words = 64, $more = '<br><a href="' . $url . '"> Read More Here</a>' );
          $date = $item->getPublishedDate();
          $date = date_format($date, 'Y-m-d H:i:s');
          $id = $item->getId();
          $args = array(
              'post_author' => $author,
              'post_content' => $body,
              'post_title' => $title,
              'post_status' => "publish",
              'post_type' => "post",
              'guid' => $source,
          );
          wp_insert_post($args);
      }
    }
  }
}

function custom_meta_box_markup($object)
{
    wp_nonce_field(basename(__FILE__), "meta-box-nonce");

    ?>
        <div>
            <label for="_source">Text</label>
            <input name="_source" type="text" value="<?php echo get_post_meta($object->ID, "_source", true); ?>">
        </div>
    <?php
}

function add_custom_meta_box()
{
    add_meta_box("demo-meta-box", "Source", "custom_meta_box_markup", "post", "side", "high", $source);
}

add_action("add_meta_boxes", "add_custom_meta_box");

function save_custom_meta_box($post_id, $post, $update)
{
    if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce($_POST["meta-box-nonce"], basename(__FILE__)))
        return $post_id;

    if(!current_user_can("edit_post", $post_id))
        return $post_id;

    if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
        return $post_id;

    $slug = "post";
    if($slug != $post->post_type)
        return $post_id;

    $meta_box_text_value = "";

    if(isset($_POST["_source"]))
    {
        $meta_box_text_value = $_POST["_source"];
    }
    update_post_meta($post_id, "_source", $meta_box_text_value);

}

add_action("save_post", "save_custom_meta_box", 10, 3);


// function rss_form()
// {
//     if (isset($_POST['url'])) {
//         error_reporting(-1);
//         date_default_timezone_set('America/Los_Angeles');
//         require 'vendor/autoload.php';
//
//         $my_feed = $_POST['url'];
//         if (is_valid_rss_url($my_feed) && is_duplicate_feed($my_feed)) {
//             sinetiks_feeder_create();
//             echo $my_feed . " added";
//         } else {
//             echo "Invalid URL";
//         }
//     }
//     echo <<<EOD
//     <form class="" action="" method="post">
//         <label for="Title">Feed Title</label>
//         <input type="text" name="title" value="" required=true><br>
//         <label for="Url">Url Feed</label>
//         <input type="text" name="url" value="" required=true><br>
//         <input type="submit" name="" value="Submit">
//     </form>
// EOD;
// }

// function get_feeds()
// {
//     global $wpdb;
//     $result = $wpdb->get_results("SELECT * FROM wp_feeder");
//     foreach ($result as $print) {
//         echo '<p>'. $print->title. ":  " . $print->feed_url . '</p>';
//     }
// }

add_filter('cron_schedules', 'isa_add_every_three_minutes');
function isa_add_every_three_minutes($schedules)
{
    $schedules['every_three_minutes'] = array(
            'interval'  => 160,
            'display'   => __('Every 3 Minutes', 'textdomain')
    );
    return $schedules;
}

// Schedule an action if it's not already scheduled
if (! wp_next_scheduled('isa_add_every_three_minutes')) {
    wp_schedule_event(time(), 'every_three_minutes', 'isa_add_every_three_minutes');
}

// Hook into that action that'll fire every three minutes
add_action('isa_add_every_three_minutes', 'get_posts_from_feed');

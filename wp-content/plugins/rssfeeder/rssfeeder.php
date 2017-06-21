<?php
error_reporting(-1);
/*
Plugin Name: RSS Feeder*/

use PicoFeed\Reader\Reader;
//
//
// function elegance_referal_init()
// {
// 	if(is_page('share')){
// 		$dir = plugin_dir_path( __FILE__ );
// 		include($dir."frontend-form.php");
// 		die();
// 	}
// 	if(is_page('results-php')){
// 		$dir = plugin_dir_path( __FILE__ );
// 		include($dir."/results.php");
// 		die();
// 	}
// }
//
// add_action( 'wp', 'elegance_referal_init' );
add_action('admin_menu', 'add_rss_post_page', 'add_feed_menu_page');

function table_install() {


	global $wpdb;

	$table_name = $wpdb->prefix . 'feeder';

	$charset_collate = $wpdb->get_charset_collate();

	$sql = "CREATE TABLE $table_name (
		id int NOT NULL AUTO_INCREMENT,
		title tinytext NOT NULL,
		feed_url varchar(255) DEFAULT '' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

}

register_activation_hook( __FILE__, 'table_install' );

function add_feed_url() {
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

function add_rss_post_page() {
    add_posts_page('rss feeder', 'rss feeder', 'manage_options','rss-feeder', 'rss_form');
    add_posts_page('feeds', 'feeds', 'manage_options', 'feeds-list', 'get_feeds');
}


function rss_form() {
    global $wpdb;
    if (isset($_POST['url'])) {
        add_feed_url();
        error_reporting(-1);
        date_default_timezone_set('America/Los_Angeles');
        require 'vendor/autoload.php';

        $my_feeds = [$_POST['url']];
        for ($i = 0; $i < 1; $i++) {

            $reader = new Reader;
            $resource = $reader->download($my_feeds[$i]);

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
                if (!get_page_by_title($title, 'OBJECT', 'post') ){
                    $author = $item->getAuthor();
                    $body = $item->getContent();
                    $url = $item->getUrl();
                    $date = $item->getPublishedDate();
                    $date = date_format( $date, 'Y-m-d H:i:s');
                    $id = $item->getId();
                    $args = array(
                        'post_author' => $author,
                        'post_content' => $body,
                        'post_title' => $title,
                        'post_status' => "publish",
                        'post_type' => "post",
                        'guid' => $id,
                    );
                    wp_insert_post($args);
                }
            }
            // $item_count = sizeof($feed->items);
        }
    }


echo <<<EOD
    <form class="" action="" method="post">
        <label for="Title">Feed Title</label>
        <input type="text" name="title" value="" required=true><br>
        <label for="Url">Url Feed</label>
        <input type="text" name="url" value="" required=true><br>
        <input type="submit" name="" value="Submit">
    </form>
EOD;
}

function get_feeds() {
    global $wpdb;
    $result = $wpdb->get_results( "SELECT * FROM wp_feeder" );
    foreach ( $result as $print )   {
        echo '<p>'. $print->title. ":  " . $print->feed_url . '</p>';
    }
}
?>

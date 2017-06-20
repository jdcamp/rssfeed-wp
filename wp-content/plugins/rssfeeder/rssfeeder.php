<?php

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
add_action('admin_menu', 'add_rss_post_page');

function add_rss_post_page() {
  add_posts_page('rss feeder', 'rss feeder', 'manage_options','rss-feeder', 'rss_form');
}
function rss_form() {
  if (isset($_POST['url'])) {
    error_reporting(-1);
    date_default_timezone_set('America/Los_Angeles');
    error_reporting(-1);
    require 'vendor/autoload.php';




      $my_feeds = [$_POST['url']];
      for ($i = 0; $i < 1; $i++) {
        try {


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
      // $item_count = sizeof($feed->items);
      // for ($j=0; $j < $item_count ; $j++) {
      foreach ($test as $item) {
        // }
        $author = $item->getAuthor();
        $body = $item->getContent();
        $title = $item->getTitle();
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
    } catch (Exception $e) {
      echo $my_feeds[$i] . ' is not a valid feed';
    }
    }
  }
echo <<<EOD
  <form class="" action="" method="post">
    <label for="Url">Url Feed</label>
    <input type="text" name="url" value="" required=true><br>
    <input type="submit" name="" value="Submit">
  </form>
EOD;
}
 ?>

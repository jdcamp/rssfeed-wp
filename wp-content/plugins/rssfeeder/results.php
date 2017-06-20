<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Test Parser</title>
  </head>
  <body>
    <?php
    error_reporting(-1);
    date_default_timezone_set('America/Los_Angeles');
    error_reporting(-1);
    require 'vendor/autoload.php';

    use PicoFeed\Reader\Reader;



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
    ?>

  </body>
</html>

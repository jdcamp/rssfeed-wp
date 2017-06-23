<?php

require_once 'rssfeeder.php';

function sinetiks_feeder_create() {
    $title = $_POST["title"];
    $feed_url = $_POST["feed_url"];
    $keywords = $_POST["keywords"];
    //insert
    if (isset($_POST['insert']) && is_unique_feed($feed_url) && is_valid_rss_url($feed_url)) {
        global $wpdb;
        $table_feed_url = $wpdb->prefix . "feeder";
        $wpdb->insert(
                $table_feed_url, //table
                array('title' => $title, 'feed_url' => $feed_url, 'keywords' => $keywords), //data
                array('%s', '%s') //data format
        );
        $message.="Feed inserted";
    } else {
        echo '<div class="updated"><p>Feed not added: Duplicate or invalid</p></div>';
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/sinetiks-feeds/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Add New Feed</h2>
        <?php if (isset($message)): ?><div class="updated"><p><?php echo $message; ?></p></div><?php endif; ?>
        <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">

            <table class='wp-list-table widefat fixed'>
                <tr>
                    <th class="ss-th-width">Title</th>
                    <td><input type="text" name="title" value="<?php echo $title; ?>" class="ss-field-width" /></td>
                </tr>
                <tr>
                    <th class="ss-th-width">URL</th>
                    <td><input type="text" name="feed_url" value="<?php echo $feed_url; ?>" class="ss-field-width" /></td>
                </tr>
                <tr>
                    <th class="ss-th-width">Keywords</th>
                    <td><input type="text" name="keywords" value="<?php echo $keywords; ?>" class="ss-field-width" /></td>
                </tr>
            </table>
            <input type='submit' name="insert" value='Save' class='button'>
        </form>
    </div>
    <?php
}

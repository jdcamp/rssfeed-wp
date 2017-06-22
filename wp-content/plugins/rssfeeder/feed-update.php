<?php

function sinetiks_feeder_update() {
    global $wpdb;
    $table_name = $wpdb->prefix . "feeder";
    $id = $_GET["id"];
    $title = $_POST["title"];
    $feed_url = $_POST["feed_url"];
//update
    if (isset($_POST['update']) && is_valid_rss_url($feed_url) && is_unique_feed($feed_url)) {
        $wpdb->update(
                $table_name, //table
                array('title' => $title, 'feed_url' => $feed_url), //data
                //array('feed_url' => $feed_url), //data
                array('id' => $id), //where
                array('%s'), //data format
                array('%s'), //data format
                array('%s') //where format
        );
        echo '<div class="updated"><p>Feed updated</p></div>';
    } else if (($_POST['update']) && (!is_valid_rss_url($feed_url) || !is_unique_feed($feed_url))) {
        echo '<div class="updated"><p>Feed not updated: Duplicate or invalid</p></div>';
    }
//delete
    else if (isset($_POST['delete'])) {
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %s", $id));
    } else {//selecting value to update
        $feeds = $wpdb->get_results($wpdb->prepare("SELECT id,title,feed_url from $table_name where id=%s", $id));
        foreach ($feeds as $s) {
            $title = $s->title;
            $feed_url = $s->feed_url;
        }
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/sinetiks-feeds/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Feeds</h2>

        <?php if ($_POST['delete']) { ?>
            <div class="updated"><p>Feed deleted</p></div>
            <a href="<?php echo admin_url('admin.php?page=sinetiks_feeder_list') ?>">&laquo; Back to feeds list</a>

        <?php } else if ($_POST['update'] && is_valid_rss_url($feed_url) && is_unique_feed($feed_url)) { ?>
            <!-- <div class="updated"><p>Feed updated</p></div> -->
            <a href="<?php echo admin_url('admin.php?page=sinetiks_feeder_list') ?>">&laquo; Back to feeds list</a>
        <?php } else if ($_POST['update'] && !(is_valid_rss_url($feed_url) && is_unique_feed($feed_url))) { ?>
            <!-- <div class="updated"><p>Feed not updated: Duplicate or invalid</p></div> -->
            <a href="<?php echo admin_url('admin.php?page=sinetiks_feeder_list') ?>">&laquo; Back to feeds list</a>
        <?php } else { ?>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <table class='wp-list-table widefat fixed'>
                    <tr><th>Title</th><td><input type="text" name="title" value="<?php echo $title; ?>"/></td>
                    <td><input type="text" name="feed_url" value="<?php echo $feed_url; ?>"/></td></tr>
                </table>
                <input type='submit' name="update" value='Save' class='button'> &nbsp;&nbsp;
                <input type='submit' name="delete" value='Delete' class='button' onclick="return confirm('Sure you want to delete?')">
            </form>
        <?php } ?>

    </div>
    <?php
}

<?php

function sinetiks_feeder_update() {
    global $wpdb;
    $table_name = $wpdb->prefix . "feeder";
    $id = $_GET["id"];
    $title = $_POST["title"];
    $url = $_POST["feed_url"];
    $keywords = $_POST["keywords"];
//update
    if (isset($_POST['update_url']) && is_unique_feed($url) && is_valid_rss_url($url)) {
        $wpdb->update(
                $table_name, //table
                array('feed_url' => $url), //data
                //array('feed_url' => $feed_url), //data
                array('id' => $id), //where
                array('%s'), //data format
                array('%s'), //data format
                array('%s'), //data format
                array('%s') //where format
        );
        echo '<div class="updated"><p>Feed updated</p></div>';
    } else if (isset($_POST['update_title'])) {
        $wpdb->update(
                $table_name, //table
                array('title' => $title), //data
                //array('feed_url' => $feed_url), //data
                array('id' => $id), //where
                array('%s'), //data format
                array('%s'), //data format
                array('%s'), //data format
                array('%s') //where format
        );
        echo '<div class="updated"><p>Feed title updated</p></div>';
    } else if (isset($_POST['update_url']) && (!is_unique_feed($url) || !is_valid_rss_url($url))) {
        echo '<div class="updated"><p>Feed not updated: duplicate or invalid</p></div>';
    } else if (isset($_POST['update_keywords'])) {
        $wpdb->update(
                $table_name, //table
                array('keywords' => $keywords), //data
                //array('feed_url' => $feed_url), //data
                array('id' => $id), //where
                array('%s'), //data format
                array('%s') //where format
        );
        echo '<div class="updated"><p>Keywords updated</p></div>';
//delete
    } else if (isset($_POST['delete'])) {
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %s", $id));
    } else {//selecting value to update
        $feeds = $wpdb->get_results($wpdb->prepare("SELECT id,title,feed_url,keywords from $table_name where id=%s", $id));
        foreach ($feeds as $s) {
            $title = $s->title;
            $feed_url = $s->feed_url;
            $keywords = $s->keywords;
        }
    }
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/sinetiks-feeds/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Feeds</h2>

        <?php if ($_POST['delete']) { ?>
            <div class="updated"><p>Feed deleted</p></div>
            <a href="<?php echo admin_url('admin.php?page=sinetiks_feeder_list') ?>">&laquo; Back to feeds list</a>

        <?php } else if ($_POST['update_url'] && is_valid_rss_url($feed_url)) { ?>
            <!-- <div class="updated"><p>Feed updated</p></div> -->
            <a href="<?php echo admin_url('admin.php?page=sinetiks_feeder_list') ?>">&laquo; Back to feeds list</a>
        <?php } else if ($_POST['update_title']) { ?>
            <!-- <div class="updated"><p>Feed updated</p></div> -->
            <a href="<?php echo admin_url('admin.php?page=sinetiks_feeder_list') ?>">&laquo; Back to feeds list</a>
        <?php } else if ($_POST['update_url'] && !is_valid_rss_url($feed_url)) { ?>
            <!-- <div class="updated"><p>Feed updated</p></div> -->
            <a href="<?php echo admin_url('admin.php?page=sinetiks_feeder_list') ?>">&laquo; Back to feeds list</a>
        <?php } else if ($_POST['update_keywords']) { ?>
            <!-- <div class="updated"><p>Feed updated</p></div> -->
            <a href="<?php echo admin_url('admin.php?page=sinetiks_feeder_list') ?>">&laquo; Back to feeds list</a>
        <?php } else { ?>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <table class='wp-list-table widefat fixed'>
<<<<<<< HEAD
                    <tr><th>Title</th><td><input type="text" name="title" value="<?php echo $title; ?>"/></td>
                    <td><input type="text" name="feed_url" value="<?php echo $feed_url; ?>ppp"/></td></tr>
=======
                    <tr><th class="ss-th-width">Title</th>
                        <td><input type="text" name="title" value="<?php echo $title; ?>"/>
                        <input type='submit' name="update_title" value='Update Title' class='button'> &nbsp;&nbsp;</td>
                    </tr>
                </table>
            </form>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <table class='wp-list-table widefat fixed'>
                    <tr><th class="ss-th-width">URL</th>
                        <td><input type="text" name="feed_url" value="<?php echo $feed_url; ?>"/>
                        <input type='submit' name="update_url" value='Update URL' class='button'> &nbsp;&nbsp;</td>
                    </tr>
                </table>
            </form>
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <table class='wp-list-table widefat fixed'>
                    <tr><th class="ss-th-width">Keywords</th>
                        <td><input type="text" name="keywords" value="<?php echo $keywords; ?>"/>
                        <input type='submit' name="update_keywords" value='Update Keywords' class='button'> &nbsp;&nbsp;</td>
                    </tr>
>>>>>>> origin/master
                </table>
                <br><br>
                <input type='submit' name="delete" value='Delete Feed' class='button' onclick="return confirm('Sure you want to delete?')">
            </form>
        <?php } ?>

    </div>
    <?php
}

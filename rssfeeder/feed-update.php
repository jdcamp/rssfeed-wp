<?php
//Update or delete rss feed from database in the admin menu

function feeder_feeder_update()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "feeder";
    $id = $_GET["id"];
    $title = $_POST["title"];
    $url = $_POST["feed_url"];
    $keywords = $_POST["keywords"];
    $category = $_POST["category"];

    // update the rss feed url
    if (isset($_POST['update_url']) && is_unique_feed($url) && is_valid_rss_url($url)) {// checks to make sure the url is unique and a valid rss feed
        $wpdb->update($table_name,//table
        array(
            'feed_url' => $url//data
        ),
        array(
            'id' => $id//data
        ));
        echo '<div class="updated"><p>Feed updated</p></div>';
    }
    // update the rss feed title
    elseif (isset($_POST['update_title'])) {
        $wpdb->update($table_name,
        array(
            'title' => $title
        ), array(
            'id' => $id
        ));
        echo '<div class="updated"><p>Feed title updated</p></div>';
    }
    // if updated url is not valid or is a duplicate, display error message
    elseif (isset($_POST['update_url']) && (!is_unique_feed($url) || !is_valid_rss_url($url))) {
        echo '<div class="updated"><p>Feed not updated: duplicate or invalid</p></div>';
    }
    // update rss feed keywords
    elseif (isset($_POST['update_keywords'])) {
        $wpdb->update($table_name,
        array(
            'keywords' => $keywords
        ), array(
            'id' => $id
        ));
        echo '<div class="updated"><p>Keywords updated</p></div>';
    }
    // update rss feed category
    elseif (isset($_POST['update_category'])) {
        $temp_category = get_term_by('name', $_POST['category'], 'category');//checks for category already in database
        $temp_category != false ? : wp_create_category($_POST['category']);//if not in database, create it in database
        $wpdb->update($table_name,
        array(
            'category' => $category
        ), array(
            'id' => $id
        ));
        echo '<div class="updated"><p>Category updated</p></div>';
    }

    //delete rss feed from database
    elseif (isset($_POST['delete'])) {
        $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE id = %s",$id));
    }
    //selecting value to update
    else {
        $feeds = $wpdb->get_results($wpdb->prepare("SELECT * from $table_name where id = %s",$id));
        foreach ($feeds as $s) {
            $title = $s->title;
            $feed_url = $s->feed_url;
            $keywords = $s->keywords;
            $category = $s->category;
        }
    } ?>

    <!-- front end UI -->
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Feeds</h2>
        <?php
        //feed deleted message
        if ($_POST['delete']) {
            ?>
            <div class="updated"><p>Feed deleted</p></div>
            <a href="<?php
            echo admin_url('admin.php?page=feeder_feeder_list'); ?>">&laquo; Back to feeds list</a>
            <?php
        }
        //feed url updated message
        elseif ($_POST['update_url'] && is_valid_rss_url($feed_url)) {
            ?>
            <div class="updated"><p>Feed url updated</p></div>
            <a href="<?php
            echo admin_url('admin.php?page=feeder_feeder_list'); ?>">&laquo; Back to feeds list</a>
            <?php
        }
        //feed title updated message
        elseif ($_POST['update_title']) {
            ?>
            <div class="updated"><p>Feed title updated</p></div>
            <a href="<?php
            echo admin_url('admin.php?page=feeder_feeder_list'); ?>">&laquo; Back to feeds list</a>
            <?php
        }
        //feed keywords updated message
        elseif ($_POST['update_keywords']) {
            ?>
            <div class="updated"><p>Feed keywords updated</p></div>
            <a href="<?php
            echo admin_url('admin.php?page=feeder_feeder_list'); ?>">&laquo; Back to feeds list</a>
            <?php
        }
        //feed category updated message
        elseif ($_POST['update_category']) {
            ?>
            <div class="updated"><p>Feed category updated</p></div>
            <a href="<?php
            echo admin_url('admin.php?page=feeder_feeder_list'); ?>">&laquo; Back to feeds list</a>
            <?php
        }
        //display admin input form
        else {
            ?>
            <!-- update title -->
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <table class='wp-list-table widefat fixed'>
                    <tr><th class="ss-th-width">Title</th>
                        <td><input type="text" name="title" value="<?php echo $title; ?>"/>
                        <input type='submit' name="update_title" value='Update Title' class='button'> &nbsp;&nbsp;</td>
                    </tr>
                </table>
            </form>
            <!-- update url -->
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <table class='wp-list-table widefat fixed'>
                    <tr><th class="ss-th-width">URL</th>
                        <td><input type="text" name="feed_url" value="<?php echo $feed_url; ?>"/>
                        <input type='submit' name="update_url" value='Update URL' class='button'> &nbsp;&nbsp;</td>
                    </tr>
                </table>
            </form>
            <!-- update keywords -->
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <table class='wp-list-table widefat fixed'>
                    <tr><th class="ss-th-width">Keywords</th>
                        <td><input type="text" name="keywords" value="<?php echo $keywords; ?>"/>
                        <input type='submit' name="update_keywords" value='Update Keywords' class='button'> &nbsp;&nbsp;</td>
                    </tr>
                </table>
            </form>
            <!-- update category -->
            <form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                <table class='wp-list-table widefat fixed'>
                    <tr><th class="ss-th-width">Category</th>
                        <td><input type="text" name="category" value="<?php echo $category; ?>"/>
                        <input type='submit' name="update_category" value='Update Category' class='button'> &nbsp;&nbsp;</td>
                    </tr>
                </table>
                <br><br>
                <!-- delete feed -->
                <input type='submit' name="delete" value='Delete Feed' class='button' onclick="return confirm('Sure you want to delete?')">
            </form>
            <?php
        } ?>
    </div>
    <?php
}

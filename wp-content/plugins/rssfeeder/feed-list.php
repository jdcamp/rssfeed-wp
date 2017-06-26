<?php
function get_by_source($source)
{
  global $wpdb;
  $results = $wpdb->get_results(
    'SELECT wp_posts.*
    FROM wp_posts
    JOIN wp_postmeta
      ON wp_postmeta.post_id = wp_posts.id
      WHERE wp_postmeta.meta_key = "_source"
      AND  wp_postmeta.meta_value ="'.$source.'" ;'
    );
    foreach ($results as $result) {
      foreach ($result as $attr_type => $attr) {
        echo "Type: ".$attr_type . " value: " . $attr . "<br>";
      }
    }
}
function get_by_original()
{
  global $wpdb;
  $results = $wpdb->get_results(
    'SELECT DISTINCT wp_posts.*
    FROM wp_posts
    JOIN wp_postmeta
      ON wp_postmeta.post_id = wp_posts.id
      WHERE NOT EXISTS (
        SELECT wp_posts.*
         FROM wp_posts
           WHERE wp_postmeta.post_id = wp_posts.id
           AND wp_postmeta.meta_key = "_source"
      )
        ;');
}
function sinetiks_feeder_list() {
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/sinetiks-feeds/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Feeds</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=sinetiks_feeder_create'); ?>">Add New</a>
            </div>
            <br class="clear">
        </div>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "feeder";

        $rows = $wpdb->get_results("SELECT title,feed_url,id from $table_name");
        ?>
        <table class='wp-list-table widefat fixed striped posts'>
            <tr>
                <th class="manage-column ss-list-width">Title</th>
                <th class="manage-column ss-list-width">URL </th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td class="manage-column ss-list-width"><?php echo $row->title; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->feed_url; ?></td>
                    <td><a href="<?php echo admin_url('admin.php?page=sinetiks_feeder_update&id=' . $row->id); ?>">Update</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php
}

<?php
function feeder_feeder_list() {
    ?>
    <link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/feeder-feeds/style-admin.css" rel="stylesheet" />
    <div class="wrap">
        <h2>Feeds</h2>
        <div class="tablenav top">
            <div class="alignleft actions">
                <a href="<?php echo admin_url('admin.php?page=feeder_feeder_create'); ?>">Add New</a>
            </div>
            <br class="clear">
        </div>
        <?php
        global $wpdb;
        $table_name = $wpdb->prefix . "feeder";

        $rows = $wpdb->get_results("SELECT * from $table_name");
        ?>
        <table class='wp-list-table widefat fixed striped posts'>
            <tr>
                <th class="manage-column ss-list-width">Title</th>
                <th class="manage-column ss-list-width">Keywords</th>
                <th class="manage-column ss-list-width">Category</th>
                <th class="manage-column ss-list-width">URL </th>
                <th>&nbsp;</th>
            </tr>
            <?php foreach ($rows as $row) { ?>
                <tr>
                    <td class="manage-column ss-list-width"><?php echo $row->title; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->keywords; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->category; ?></td>
                    <td class="manage-column ss-list-width"><?php echo $row->feed_url; ?></td>
                    <td><a href="<?php echo admin_url('admin.php?page=feeder_feeder_update&id=' . $row->id); ?>">Update</a></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    <?php
}

<?php
/**
* Creates admin menu items for RSS Autoblog
*/
function feeder_feeder_modifymenu() {
	//this is the main item for the menu
	add_menu_page('Feeds', //page title
	'Feeds', //menu title
	'manage_options', //capabilities
	'feeder_feeder_list', //menu slug
	'feeder_feeder_list' //function
	);
	//this is a submenu
	add_submenu_page('feeder_feeder_list', //parent slug
	'Add New Feed', //page title
	'Add New', //menu title
	'manage_options', //capability
	'feeder_feeder_create', //menu slug
	'feeder_feeder_create'); //function
	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
	'Update Feed', //page title
	'Update', //menu title
	'manage_options', //capability
	'feeder_feeder_update', //menu slug
	'feeder_feeder_update'); //function
}
add_action('admin_menu','feeder_feeder_modifymenu');
define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . 'feed-list.php');
require_once(ROOTDIR . 'feed-create.php');
require_once(ROOTDIR . 'feed-update.php');

/**
* adds source column to the all posts page in admin view
*/
function rss_feeder_modify_columns( $columns ) {
  $new_columns = array(
    'source' => __('Source', 'rss_feeder_textdomain'),
  );
  $filtered_columns = array_merge($columns, $new_columns);
  return $filtered_columns;
}
add_filter('manage_posts_columns', 'rss_feeder_modify_columns');

/**
* Adds values to the source column
*/
function rss_feeder_custom_column_content($column){
  global $post;
  $source = get_post_meta($post->ID, '_source', 1);
  echo $source;
}
add_action( 'manage_posts_custom_column', 'rss_feeder_custom_column_content' );

/**
* Set the format of the all post page in admin view
*/
function sortable_columns() {

  return array(
	'title' => 'title',
    'author' => 'author',
	'source' => 'source',
	'date' => 'date',
	'categories' => 'categories',
	'tags' => 'tags',
  );
}

add_filter( "manage_edit-post_sortable_columns", "sortable_columns" );

add_action( 'pre_get_posts', 'manage_wp_posts_pre_get_posts', 1 );

/**
* Makes author and source sortable
*/
function manage_wp_posts_pre_get_posts( $query ) {
   if ( $query->is_main_query() && ( $orderby = $query->get( 'orderby' ) ) ) {

	   switch( $orderby ) {
		   case 'source':
		   $query->set( 'meta_key', '_source' );
		   $query->set( 'orderby', 'meta_value' );

		   break;

		   case 'author':
		   $query->set( 'meta_key', '_post_author' );
		   $query->set( 'orderby', 'meta_value');

		   break;
	   }
   }
}

/**
* Format of the custom meta box
*/
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

/**
* Creates a forum box showing the source of the post
*/
function add_custom_meta_box()
{
    add_meta_box("demo-meta-box", "Source", "custom_meta_box_markup", "post", "side", "high", $source);
}

add_action("add_meta_boxes", "add_custom_meta_box");
/**
* Saves changes made to the source meta. Function uses 
* @param int $post_id the id of the post
* @param WP_post $post the wordpress post object
* @param update values
* All values are provided by wordpress
*/
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

<?php

// wp_enqueue_script('jquery-2.1.4.min', get_template_directory_uri() . '/js/jquery-2.1.4.min.js');
// wp_enqueue_script('bootstrap.min', get_template_directory_uri() . '/js/bootstrap.min.js');
// wp_enqueue_script('waypoints.min', get_template_directory_uri() . '/js/waypoints.min.js');
// wp_enqueue_script('jquery.animateNumber.min', get_template_directory_uri() . '/js/jquery.animateNumber.min.js');
// wp_enqueue_script('waypoints-sticky.min', get_template_directory_uri() . '/js/waypoints-sticky.min.js');
// // wp_enqueue_script('retina.min', get_template_directory_uri() . '/js/retina.min.js');
// wp_enqueue_script('jquery.magnific-popup.min', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js');
// wp_enqueue_script('jquery.ajaxchimp.min', get_template_directory_uri() . '/js/jquery.ajaxchimp.min.js');
// wp_enqueue_script('tweetie.min', get_template_directory_uri() . '/js/tweetie.min.js');
// wp_enqueue_script('main', get_template_directory_uri() . '/js/main.js');
// wp_enqueue_script('gmap', get_template_directory_uri() . '/js/gmap.js');
//
//
// wp_enqueue_style('bootstrap.min', get_template_directory_uri() . '/css/bootstrap.min.css');
// wp_enqueue_style('font-awesome.min', get_template_directory_uri() . '/css/font-awesome.min.css');
// wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/css/magnific-popup.css');
// wp_enqueue_style('main', get_template_directory_uri() . '/css/main.css');
// wp_enqueue_style('mystyle', get_template_directory_uri() . '/css/mystyle.css');
/**
 * Register our sidebars and widgetized areas.
 *
 */
function arphabet_widgets_init() {

	register_sidebar( array(
		'name'          => 'Home right sidebar',
		'id'            => 'home_right_1',
		'before_widget' => '<div>',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="rounded">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'arphabet_widgets_init' );

if ( ! function_exists( 'post_pagination' ) ) :
   function post_pagination() {
     global $wp_query;
     $pager = 999999999; // need an unlikely integer

        echo paginate_links( array(
             'base' => str_replace( $pager, '%#%', esc_url( get_pagenum_link( $pager ) ) ),
             'format' => '?paged=%#%',
             'current' => max( 1, get_query_var('paged') ),
             'total' => $wp_query->max_num_pages
        ) );
   }
endif;

function rss_feeder_modify_columns( $columns ) {
  $new_columns = array(
    'source' => __('Source', 'rss_feeder_textdomain'),
  );
  $filtered_columns = array_merge($columns, $new_columns);
  return $filtered_columns;
}

add_filter('manage_posts_columns', 'rss_feeder_modify_columns');
function rss_feeder_custom_column_content($column){
  global $post;
  $source = get_post_meta($post->ID, '_source', 1);
  echo $source;
}
add_action( 'manage_posts_custom_column', 'rss_feeder_custom_column_content' );


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

function add_custom_meta_box()
{
    add_meta_box("demo-meta-box", "Source", "custom_meta_box_markup", "post", "side", "high", $source);
}

add_action("add_meta_boxes", "add_custom_meta_box");

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

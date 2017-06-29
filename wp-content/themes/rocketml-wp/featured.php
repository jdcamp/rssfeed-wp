<?php
/**
 * Template Name: Featured Page Template
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 */

 ?>
 <!-- Header -->
 <?php get_header('search');?>
<<<<<<< HEAD
=======

>>>>>>> 8d07bf2875d6c9511818aa18df45f1c59ee5190c
 <?php wp_enqueue_style('blog', get_template_directory_uri() . '/css/blog.css');?>
 <?php wp_enqueue_style('dropdown', get_template_directory_uri() . '/css/dropdown.css');?>
<div class="container">
  <div class="col-sm-9">

 <?php
 $args = array(
    'post_type'        => 'post',
     'posts_per_page'   => 5,
     'paged'        => get_query_var('paged') ? get_query_var('paged') : 1,
     'meta_query' => array(
       array(
         'key' => '_source',
         'value' => 'internal'
       )
       )
 );

 // Custom query.
$query = new WP_Query($args);
global $query;
 // Check that we have query results.
 if ($query->have_posts()) {

     // Start looping over the query results.
     while ($query->have_posts()) {
         $query->the_post();
         // Contents of the queried post results go here.
         get_template_part('template-parts/content', 'blogposts');
     }
 }


 ?>
<?php get_template_part('template-parts/content', 'pagination') ?>
</div>
<div class="col-sm-3">
  <?php get_sidebar(); ?>
</div>
</div>
 <?php get_footer(); ?>

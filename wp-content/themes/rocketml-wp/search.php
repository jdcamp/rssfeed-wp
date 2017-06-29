<?php
/**
 * Template Name: search
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 */
 ?>

<?php get_header('search'); ?>
<?php wp_enqueue_style('blog', get_template_directory_uri() . '/css/blog.css');?>
<?php wp_enqueue_style('dropdown', get_template_directory_uri() . '/css/dropdown.css');?>

<div class="container">
  <div class="col-sm-9">

<section id="content" role="main">
<?php if ( have_posts() ) : ?>
<header class="header">
<h1 class="entry-title"><?php printf( __( 'Search Results for: %s', 'rocketml-wp' ), get_search_query() ); ?></h1>
</header>
<?php while ( have_posts() ) : the_post(); ?>
<?php get_template_part( 'template-parts/content','blogposts' ); ?>
<?php endwhile; ?>
<?php get_template_part( 'nav', 'below' ); ?>
<?php else : ?>
<article id="post-0" class="post no-results not-found">
<header class="header">
<h2 class="entry-title"><?php _e( 'Nothing Found', 'rocketml-wp' ); ?></h2>
</header>
<section class="entry-content">
<p><?php _e( 'Sorry, nothing matched your search. Please try again.', 'rocketml-wp' ); ?></p>
<?php get_search_form(); ?>
</section>
</article>
<?php endif; ?>
</section>
</div>
<div class="col-sm-3">
<?php get_sidebar(); ?>
</div>
</div>
<?php get_footer(); ?>

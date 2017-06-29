<?php
/**
 * Template Name: Scraper Page Template
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
<?php get_header(); ?>
<?php wp_enqueue_style('mystyle', get_template_directory_uri() . '/css/post.css'); ?>

<!-- Post Content -->
<section id="content" class="entry-content" role="main">
  <?php include 'Readability.php'; ?>
    <?php
    $doc = new Readability();
    $url = 'https://github.com/ElemeFE/node-interview';
    var_dump(get_meta_tags($url));
    $doc->input($url);
    $doc->init();
    $content = $doc->getContent();

    $title = $doc->getTitle()->nodeValue;
     ?>
     <h1><?php echo $title; ?></h1>
     <p><?php echo $content;  ?></p>
    </body>
  </html>

</section>

<hr>

<!-- Related Posts Section -->

<div class="suggestions">
    <p>Related Posts</p>

    <?php
        $tags = wp_get_post_tags($post->ID);
        if ($tags) {
            $first_tag = $tags[0]->term_id;
            $second_tag = $tags[1]->term_id;
            $third_tag = $tags[2]->term_id;
            $fourth_tag = $tags[3]->term_id;
            $fifth_tag = $tags[4]->term_id;
            $args=array(
                'tag__in' => array($first_tag, $second_tag, $third_tag, $fourth_tag, $fifth_tag),
                'post__not_in' => array($post->ID),
                'posts_per_page'=>5,
                'ignore_sticky_posts'=>1
            );
            $my_query = new WP_Query($args);
            if( $my_query->have_posts() ) {
                while ($my_query->have_posts()) : $my_query->the_post(); ?>

                <a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>

                <br>
                <?php endwhile;
            }
            wp_reset_query();
        }
    ?>
</div>
<br>

<!-- RocketML-Website Footer -->
<?php get_footer(); ?>

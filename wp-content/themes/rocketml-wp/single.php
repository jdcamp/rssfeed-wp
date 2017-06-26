<?php get_header(); ?>
<?php wp_enqueue_style('mystyle', get_template_directory_uri() . '/css/post.css'); ?>

<!-- Post Content -->
<section id="content" role="main">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_template_part( 'entry' ); ?>
<?php if ( ! post_password_required() ) comments_template( '', true ); ?>
<?php endwhile; endif; ?>
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

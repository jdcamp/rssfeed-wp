<!-- blog page welcome section -->
<?php

         echo '<div class="row">';
        echo '<div class="col-sm-3 post-meta">';
        $date = get_the_date();
        printf("<h4>".$date."</h4>");
        print("<p>Posted by</p><p>" . get_the_author()."</p>");

    edit_post_link(
      sprintf(
        /* translators: %s: Name of current post */
        __('Edit<span class="screen-reader-text"> "%s"</span>', 'rocketml-wp'),
        get_the_title()
      ),
      '<span class="edit-link">',
      '</span>'
    );
  echo '</div>';
echo '<div class="col-sm-9">';

$classes = implode(get_post_class(), ' ');
echo ('<div id="post-"'. ' class="' . $classes .'">');
	echo '<header class="entry-header">';

		echo the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');
	echo '</header>';

	echo '<div class="entry-content">';
            /* translators: %s: Name of current post */
            the_excerpt();

            wp_link_pages(array(
                'before'      => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'rocketml-wp') . '</span>',
                'after'       => '</div>',
                'link_before' => '<span>',
                'link_after'  => '</span>',
                'pagelink'    => '<span class="screen-reader-text">' . __('Page', 'rocketml-wp') . ' </span>%',
                'separator'   => '<span class="screen-reader-text">, </span>',
            ));

 echo	'</div></div></div></div>'; ?>

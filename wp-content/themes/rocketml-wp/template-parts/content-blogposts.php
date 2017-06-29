
<div class="row">
  <!-- left side of post -->
   <div class="col-sm-3 post-meta">
      <?php
         $date = get_the_date();
         //gets classes associated with post
         $classes = implode(get_post_class(), ' ');
         ?>
      <h4><?php echo $date; ?></h4>
      <p>Posted by: <?php echo get_the_author(); ?></p>
      <p>Source: <?php echo get_post_meta(get_the_ID(), '_source', 1); ?></p>
      <?php
      //displays edit post link when logged in as admin
         edit_post_link(
           sprintf(
             /* translators: %s: Name of current post */
             __('Edit<span class="screen-reader-text"> "%s"</span>', 'rocketml-wp'),
             get_the_title()
           ),
           '<span class="edit-link">',
           '</span>'
         );
         ?>
   </div>
   <!-- right side of post -->
   <div class="col-sm-9">
      <div id="post-<?php echo get_the_ID(); ?>" class="<?php echo $classes ;?>">
         <header class="entry-header">
            <?php
               echo the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');
               ?>
         </header>
         <div class="entry-content">
            <?php
            //displays summary of post
               the_excerpt();
               wp_link_pages(array(
                   'before'      => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'rocketml-wp') . '</span>',
                   'after'       => '</div>',
                   'link_before' => '<span>',
                   'link_after'  => '</span>',
                   'pagelink'    => '<span class="screen-reader-text">' . __('Page', 'rocketml-wp') . ' </span>%',
                   'separator'   => '<span class="screen-reader-text">, </span>',
               ));
               ?>
         </div>
      </div>
   </div>
</div>

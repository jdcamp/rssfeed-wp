<!DOCTYPE html>

<html>
<head>
<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,300,700" rel="stylesheet">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>RocketML- Super Fast Computational Engine for ML</title>
<meta name="description" content="Super Fast Computational Engine to build machine learning models">
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.ico" type="image/x-icon">
<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/img/favicon.ico" type="image/x-icon">
<link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.min.css" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/css/magnific-popup.css" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/css/main.css" rel="stylesheet">
<link href="<?php echo get_template_directory_uri(); ?>/css/dropdown.css" rel="stylesheet">
<script type="text/javascript" src="<?php  echo get_template_directory_uri();?>/js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="<?php  echo get_template_directory_uri();?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php  echo get_template_directory_uri();?>/js/waypoints.min.js"></script>
<script type="text/javascript" src="<?php  echo get_template_directory_uri();?>/js/jquery.animateNumber.min.js"></script>
<script type="text/javascript" src="<?php  echo get_template_directory_uri();?>/js/waypoints-sticky.min.js"></script>
<script type="text/javascript" src="<?php  echo get_template_directory_uri();?>/js/jquery.magnific-popup.min.js"></script>
<script type="text/javascript" src="<?php  echo get_template_directory_uri();?>/js/jquery.ajaxchimp.min.js"></script>
<script type="text/javascript" src="<?php  echo get_template_directory_uri();?>/js/tweetie.min.js"></script>
<script type="text/javascript" src="<?php  echo get_template_directory_uri();?>/js/main.js"></script>
<script type="text/javascript" src="<?php  echo get_template_directory_uri();?>/js/gmap.js"></script>

<!-- <script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-96177999-1', 'auto');
  ga('send', 'pageview');

</script> -->
<?php wp_head(); ?>
</head>
<body>

<!--hero section-->

<header class="hero-section" <?php if(is_home()){
  echo " id='home-image'";
} elseif (is_page_template('index.php')) {
  echo " id='blog-home'";
} elseif (is_page_template('single.php')) {

}
?>>

  <!--navigation-->
<?php get_template_part('template-parts/content', 'navbar'); ?>


  <!--navigation end-->

  <!--welcome message-->

  <?php if (is_home()) {
    get_template_part('template-parts/content', 'welcome');
  } elseif (is_page_template('index.php')) {
    get_template_part('template-parts/content', 'blog');
  } else {

  }
  ?>

  <!--welcome message end-->
</header>

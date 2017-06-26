<?php
/**
 * Template Name: Front Page Template
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

?>
<?php get_header(); ?>

<!--hero section end-->

<!--Featured on

<section class="featured-on section-spacing text-center">
  <div class="container">
    <header class="section-header">
      <h2>We Are Featured On</h2>
    </header>
    <div class="row">
      <div class="col-md-12">
        <ul class="featured-sites">
          <li><a href="" title="Site Name"><img src="img/site-1.png" alt="site"></a> </li>
          <li><a href="" title="Site Name"><img src="img/site-2.png" alt="site"></a></li>
          <li><a href="" title="Site Name"><img src="img/site-3.png" alt="site"></a></li>
          <li><a href="" title="Site Name"><img src="img/site-4.png" alt="site"></a></li>
        </ul>
      </div>
    </div>
  </div>
</section> -->

<!--Featured on end-->

<!--benefits-->

<section class="benefits section-spacing text-center" id="features">
  <div class="container">
    <header class="section-header">
      <h2>Speed is the new UX for ML</h2>
      <h3>Built for Data Scientists and Developers</h3>
    </header>
    <div class="row">
      <div class="col-sm-4"> <img src="<?php echo get_template_directory_uri(); ?>/img/benefits-2.png" alt="benefits of product">
        <h4>Rocket Fast Backend</h4>
        <p>HPC infrastructure built from ground up to optimize every millisecond, so that data scientists never have to downsample.</p>
      </div>
      <div class="col-sm-4"> <img src="<?php echo get_template_directory_uri(); ?>/img/benefits-3.png" alt="benefits of product">
        <h4>Application-Oriented Toolkits</h4>
        <p>Incorporates automatic feature engineering, model selection, and machine specific to the application.</p>
      </div>
      <div class="col-sm-4"> <img src="<?php echo get_template_directory_uri(); ?>/img/benefits-1.png" alt="benefits of product">
        <h4>Developer Friendly API</h4>
        <p>Seamlessly blend ETL, interactive queries, machine learning using SQL, Python, Java, R, or Scala.</p>
      </div>
    </div>
  </div>
</section>

<!--benefits end-->

<!--Features-->

<div class="features section-spacing">
  <div class="container">

    <!--feature 1-->

    <div class="row">
      <div class="col-md-7 col-md-push-5 text-center"> <img src="<?php echo get_template_directory_uri(); ?>/img/feature-1.png" alt="feature"> </div>
      <div class="col-md-5 col-md-pull-7">
        <article>
          <h2>Push to the Limits of Amdahl's law</h2>
          <p>In computer architecture, Amdahl's law is used to predict the theoretical speedup when using multiple processors. RockekML is built to scale by eliminating weak links. Every component is tuned so that the system is pushed to the limits of Amdahl's law. In essence everybody who uses RocketML gets a supercomputer at their disposal!</p>
          <!-- <ul>
            <li>Highly scalable algorithm</li>
            <li>End to End Platform</li>
            <li>Nulla nec lacinia velit</li>
          </ul>-->
        </article>
      </div>
    </div>

    <!--feature 1 end-->

    <!--feature 2-->
    <div class="row">
      <div class="col-md-7 col-md-push-3"> <img src="<?php echo get_template_directory_uri(); ?>/img/feature-2.png" alt="feature"> </div>
      <div class="col-md-5">
        <article>
          <h2>Simplified Model discovery process</h2>
          <p>To find the best model for a problem, data scientists try out different algorithms. With RocketML, they don't have to. It is like a Hyperloop solution to go from point A to point Z. Simplified workflow yields new benefits like, </p>
          <ul>
            <li>Reduces the fatigue on Data Scientists</li>
            <li>Lowers the bar on skill required</li>
            <li>Improves accuracy of results</li>
          </ul>
        </article>
      </div>
    </div>
    <!--feature 2 end-->

    <!--feature 3-->
    <div class="row">
      <div class="col-md-7 col-md-push-5 text-center"> <img src="<?php echo get_template_directory_uri(); ?>/img/feature-3.png" alt="feature"> </div>
      <div class="col-md-5 col-md-pull-7">
        <article>
          <h2>Familar Frameworks for Ease of Use</h2>
          <p>RocketML uses familiar APIs from popular data science open source tools like Pandas, Scikit and Spark. It also supports multiple languages supported like SQL, Python, Java, Scala and R. Data scientists and Developers don't have to learn a new system, making RocketML easy to use without special training.</p>
          <!-- <ul>
            <li>Python</li>
            <li>Spark</li>
            <li>Nulla nec lacinia velit</li>
          </ul>-->
        </article>
      </div>
    </div>
    <!--feature 3 end-->
  </div>
</div>

<!--Features end-->

<!--sub-form-->
<section class="sub-form section-spacing text-center" id="subscribe">
  <div class="container">
    <header class="section-header">
      <h2>Subscribe To Our Newsletter</h2>
      <h3>Subscribe to get monthly products updates and exclusive offers </h3>
    </header>
    <div class="row">
      <div class="col-md-6 center-block col-sm-11">
        <form id="mc-form">
          <div class="input-group">
            <!-- <input type="email" class="form-control" placeholder="Email Address" required id="mc-email"> -->
            <span class="input-group-btn">
            <a href="https://goo.gl/forms/lntc5rsGfJBq7CU73" class="btn" target="_blank">Request Accesss</a><i class="fa fa-envelope"></i>
            </span> </div>
          <label for="mc-email" id="mc-notification"></label>
        </form>
      </div>
    </div>
  </div>
</section>
<!--sub-form end-->

<!--site-footer-->
<footer class="site-footer section-spacing text-center">
  <div class="container">
    <div class="row">
      <div class="col-md-4">
        <p class="footer-links"><a href="">Terms of Use</a> <a href="">Privacy Policy</a></p>
      </div>
      <div class="col-md-4"> <small>&copy; 2017 RocketML. All rights reserved.</small></div>
      <div class="col-md-4">
        <!--social-->

        <ul class="social">
          <li><a href="https://twitter.com/" target="_blank"><i class="fa fa-twitter"></i></a></li>
          <li><a href="https://www.facebook.com/" target="_blank"><i class="fa fa-facebook"></i></a></li>
          <li><a href="https://www.youtube.com/" target="_blank"><i class="fa fa-youtube-play"></i></a></li>
        </ul>

        <!--social end-->

      </div>
    </div>
  </div>
</footer>
<!--site-footer end-->

<script src="<?php echo get_template_directory_uri(); ?>/js/jquery-2.1.4.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/bootstrap.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/waypoints.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.animateNumber.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/waypoints-sticky.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/retina.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.magnific-popup.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.ajaxchimp.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/tweetie.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/main.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/gmap.js"></script>
</body>
</html>

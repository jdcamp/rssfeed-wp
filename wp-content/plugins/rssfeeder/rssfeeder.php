<?php

/*
Plugin Name: RSS Feeder*/


function elegance_referal_init()
{
	if(is_page('share')){
		$dir = plugin_dir_path( __FILE__ );
		include($dir."frontend-form.php");
		die();
	}
	if(is_page('results-php')){
		$dir = plugin_dir_path( __FILE__ );
		include($dir."/results.php");
		die();
	}
}

add_action( 'wp', 'elegance_referal_init' );


 ?>

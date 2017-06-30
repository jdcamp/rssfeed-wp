<?php
	/*
	Plugin Name: Feeds
	Description: Adds the admin menu for viewing, adding, and updating rss feeds
	Version: 1
	Author: feeder.com
	Author URI: http://feeder.com
	*/

	//create menu items for admin
	add_action('admin_menu','feeder_feeder_modifymenu');
	function feeder_feeder_modifymenu() {
		//main item for the menu
		add_menu_page('Feeds', //page title
		'Feeds', //menu title
		'manage_options', //capabilities
		'feeder_feeder_list', //menu slug
		'feeder_feeder_list' //function
		);
		//submenu items
		add_submenu_page('feeder_feeder_list', //parent slug
		'Add New Feed', //page title
		'Add New', //menu title
		'manage_options', //capability
		'feeder_feeder_create', //menu slug
		'feeder_feeder_create'); //function
		//this submenu is HIDDEN, however, we need to add it anyways
		add_submenu_page(null, //parent slug
		'Update Feed', //page title
		'Update', //menu title
		'manage_options', //capability
		'feeder_feeder_update', //menu slug
		'feeder_feeder_update'); //function
	}
	//additional files required in plugin folder
	define('ROOTDIR', plugin_dir_path(__FILE__));
	require_once(ROOTDIR . 'feed-list.php');
	require_once(ROOTDIR . 'feed-create.php');
	require_once(ROOTDIR . 'feed-update.php');
?>

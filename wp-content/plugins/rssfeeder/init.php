<?php
/*
Plugin Name: Feeds
Description:
Version: 1
Author: sinetiks.com
Author URI: http://sinetiks.com
*/
// function to create the DB / Options / Defaults
//menu items
add_action('admin_menu','sinetiks_feeder_modifymenu');
function sinetiks_feeder_modifymenu() {
	//this is the main item for the menu
	add_menu_page('Feeds', //page title
	'Feeds', //menu title
	'manage_options', //capabilities
	'sinetiks_feeder_list', //menu slug
	'sinetiks_feeder_list' //function
	);
	//this is a submenu
	add_submenu_page('sinetiks_feeder_list', //parent slug
	'Add New Feed', //page title
	'Add New', //menu title
	'manage_options', //capability
	'sinetiks_feeder_create', //menu slug
	'sinetiks_feeder_create'); //function
	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(null, //parent slug
	'Update Feed', //page title
	'Update', //menu title
	'manage_options', //capability
	'sinetiks_feeder_update', //menu slug
	'sinetiks_feeder_update'); //function
}
define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . 'feed-list.php');
require_once(ROOTDIR . 'feed-create.php');
require_once(ROOTDIR . 'feed-update.php');

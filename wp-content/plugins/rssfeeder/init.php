<?php
/*
Plugin Name: Feeds
Description:
Version: 1
Author: sinetiks.com
Author URI: http://sinetiks.com
*/
// function to create the DB / Options / Defaults
function ss_options_install() {

    global $wpdb;

    $table_name = $wpdb->prefix . "feeder";
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
            title tinytext NOT NULL,
    		feed_url varchar(255) DEFAULT '' NOT NULL,
            PRIMARY KEY (id)
          ) $charset_collate; ";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

// run the install scripts upon plugin activation
register_activation_hook(__FILE__, 'ss_options_install');

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

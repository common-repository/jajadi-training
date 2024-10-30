<?php
/*
	Plugin Name: JaJaDi Training and Courses
	Plugin URI: http://tech.janjaapvandijk.nl/jajadi-training-plugin
	Description: With this plugin you can offer training and courses.
	Version: 0.5.7
	Author: Janjaap van Dijk
	Author URI: http://tech.janjaapvandijk.nl
	License: GPL2
	Text Domain: jajadi-training
	Domain Path: /languages/
*/

/*	Copyright 2013  J. van Dijk 

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/************************************************************************************************/
/*	Adding the Custom Column Depending on Post Type - TRAINING									*/
/************************************************************************************************/
add_filter('manage_jajadi_training_posts_columns', 'jajadi_columns_head_training_category', 10);  
add_action('manage_jajadi_training_posts_custom_column', 'jajadi_columns_content_training_category', 10, 2);
add_filter('manage_edit-jajadi_training_sortable_columns', 'jajadi_sortable_columns' );


/* Only run our customization on the 'edit.php' page in the admin. */
add_action( 'load-edit.php', 'jajadi_edit_training_load' );


/************************************************************************************************/
/*	Adding the Custom Column Depending on Post Type - COURSE									*/
/************************************************************************************************/
add_filter('manage_jajadi_course_posts_columns', 'jajadi_columns_head_course_category', 10);  
add_action('manage_jajadi_course_posts_custom_column', 'jajadi_columns_content_course_category', 10, 2);  

$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'jajadi_plugin_settings_link' );

/************************************************************************************************/
/*	Algemene acties																				*/
/************************************************************************************************/

// Include other files
include( plugin_dir_path( __FILE__ ) . 'jajadi-training-settings.php');
include( plugin_dir_path( __FILE__ ) . 'jajadi-custombox.php');
include( plugin_dir_path( __FILE__ ) . 'jajadi-training-widget.php');
include( plugin_dir_path( __FILE__ ) . 'jajadi-training-functions.php');
// Hooks a function to a specific filter action.
add_filter( 'post_updated_messages', 'jajadi_updated_messages' );
add_filter( 'post_updated_messages', 'jajadi_updated_messages_course' );
// Hooks a function on to a specific action.
add_action( 'admin_head', 'jajadi_admin_register_head' );
add_action( 'init', 'jajadi_training_register' ); 
add_action( 'contextual_help', 'jajadi_contextual_help', 10, 3 );
add_action( 'after_switch_theme', 'my_rewrite_flush' );
add_action( 'plugins_loaded', 'jajadi_load_textdomain');
// The register_activation_hook function registers a plugin function to be run when the plugin is activated.
register_activation_hook( __FILE__, 'my_rewrite_flush' );
// If setting Use Courses set on
if( get_option( 'usejajadicourse' ) == 'on' ){
	add_filter( 'the_content', 'jajadi_showcourses' );
	add_action( 'init', 'jajadi_course_register' ); 
	add_action('after_setup_theme', 'jajadi_register_taxonomies', 1);
}
?>
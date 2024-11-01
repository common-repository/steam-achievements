<?php

/*
 *	Plugin Name: Steam Achievements Plugin
 *	Plugin URI: http://anthonyskelton.com/wordpress-team-fortress-2-steam-achievement-plugin/
 *	Description: Returns the achievements earned in Team Fortress 2
 *	Version: 0.2
 *	Author: Anthony Skelton
 *	Author URI: http://www.anthonyskelton.com
 *	License: GPL2
 *
*/

/*
 *	Assign global variables
 *
*/

$plugin_url = WP_PLUGIN_URL . '/steam-achievements';
$options = array();

function wpsteam_achievements_menu() {

	add_options_page(
		'Steam Achievements Plugin',
		'Steam Achievements',
		'manage_options',
		'wpsteam-achievements',
		'wpsteam_achievements_options_page'
	);

}
add_action( 'admin_menu', 'wpsteam_achievements_menu' );


function wpsteam_achievements_options_page() {

	if( !current_user_can( 'manage_options' ) ) {

		wp_die( 'You do not have suggicient permissions to access this page.' );

	}

	global $plugin_url;
	global $options;

	if( isset( $_POST['wpsteam_name_form_submitted'] ) ) {

		$hidden_field = esc_html( $_POST['wpsteam_name_form_submitted'] );

		if( $hidden_field == 'Y' ) {

			$wpsteam_name = esc_html( $_POST['wpsteam_name'] );
			
			$wpsteam_profile = wpsteam_achievements_get_profile($wpsteam_name);
			$wpsteam_tf2_xml = wpsteam_tf2_xml($wpsteam_name);
			$achievement_count = count_achievements($wpsteam_tf2_xml);
			$achievements = most_recent_achievements($wpsteam_tf2_xml);

			$options['wpsteam_name']				    			 = $wpsteam_name;
			$options['wpsteam_profile']                  			 = $wpsteam_profile;
			$options['achievement_count']                               = $achievement_count;
			$options['last_updated']							 = time();
			$options['most_recent_achievement']                     = $achievements;

			update_option( 'wpsteam_achievements', $options );

		}

	}

	$options = get_option( 'wpsteam_achievements' );

	if( $options != '' ) {

		$wpsteam_name = $options['wpsteam_name'];
		$achievement_count = $options['achievement_count'];
		$wpsteam_profile = $options['wpsteam_profile'];
		$achievements = $options['most_recent_achievement'];


	}

	require( 'inc/options-page-wrapper.php' );

}

function wpsteam_achievements_get_profile($wpsteamid) {
	if(is_numeric($wpsteamid)) {
		$xml_url = 'http://steamcommunity.com/profiles/' . $wpsteamid . '?xml=1';
	} else {
		$xml_url = 'http://steamcommunity.com/id/' . $wpsteamid . '?xml=1';
	}
	$val = simplexml_load_file($xml_url);
	$profile = array();
	$profile['steamID'] = (string)$val->{'steamID'};
	$profile['avatarFull'] = (string)$val->{'avatarFull'};
	return $profile;
}

function wpsteam_tf2_xml($wpsteam_name) {
	if(is_numeric($wpsteam_name)) {
		$url = 'http://steamcommunity.com/profiles/' . $wpsteam_name . '/stats/TF2?xml=1';
	} else {
		$url = 'http://steamcommunity.com/id/' . $wpsteam_name . '/stats/TF2?tab=achievements&xml=1';
	} 
	$val = simplexml_load_file($url);
	return $val;
}

function sort_array_timestamps($a, $b) {
	return($b['unlockTimestamp'] - $a['unlockTimestamp']);
}
function count_achievements($wpsteam_tf2_xml) {
	$i = 0;
	if($wpsteam_tf2_xml->{'achievements'}->{'achievement'} != null) {
		foreach($wpsteam_tf2_xml->{'achievements'}->{'achievement'} as $item) {
			if($item[0]["closed"] == 1) {
				$i++;
			}
		}
	}
	
	return $i;
}
function most_recent_achievements($wpsteam_tf2_xml) {
	$obj_achievement = array();
	$i = 1;
	if($wpsteam_tf2_xml->{'achievements'}->{'achievement'} != null) {
		foreach($wpsteam_tf2_xml->{'achievements'}->{'achievement'} as $item) {
			if($item[0]["closed"] == 1) {
				foreach($item as $key => $value) {
					$achievement[(string)$key] = (string)$value;
				}
				$obj_achievement[] = $achievement;
			}
		}
		usort($obj_achievement, "sort_array_timestamps");
		return $obj_achievement;
	}
}

/*	
 *	Create an achievement widget
 *
*/
class Wpsteamid_Achievement_Widget extends WP_Widget {

	function wpsteamid_achievement_widget() {
		// Instantiate the parent object
		parent::__construct( false, 'Steam Achievement Plugin');
	}

	function widget( $args, $instance ) {
		// Widget output
		
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$num_achievements = $instance['num_achievements'];
		$display_tooltip = $instance['display_tooltip'];
		$achievement_width = $instance['achievement_width'];

		$options = get_option('wpsteam_achievements');
		$achievements = $options['most_recent_achievement'];

		require( 'inc/front-end.php');
	}

	function update( $new_instance, $old_instance) {
		// Save widget options
		
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['num_achievements'] = strip_tags($new_instance['num_achievements']);
		$instance['display_tooltip'] = strip_tags($new_instance['display_tooltip']);
		$instance['achievement_width'] = ($new_instance['achievement_width']);

		return $instance;
	}

	function form( $instance ) {
		// Output admin widget options form
		
		$title = esc_attr($instance['title']);
		$num_achievements = esc_attr($instance['num_achievements']);
		$display_tooltip = esc_attr($instance['display_tooltip']);
		$achievement_width = esc_attr($instance['achievement_width']);

		$options = get_option('wpsteam_achievements');
		$obj_achievements = $options['most_recent_achievement'];

		require( 'inc/widget-fields.php' );
	}
}

function wpsteam_achievements_register_widgets() {
	register_widget( "Wpsteamid_Achievement_Widget" );
}

add_action('widgets_init', 'wpsteam_achievements_register_widgets' );

function wpsteam_achievements_backend_styles() {
	wp_enqueue_style( 'wpsteam_achievements_styles', plugins_url( 'steam-achievements/wpsteam-achievements.css' ) );
}
add_action( 'admin_head', 'wpsteam_achievements_backend_styles' );

function wpsteam_achievements_frontend_scripts_and_styles() {
	wp_enqueue_style( 'wpsteam_achievements_styles', plugins_url( 'steam-achievements/wpsteam-achievements.css' ) );
	wp_enqueue_script( 'wpsteam-achievements.js', plugins_url('steam-achievements/wpsteam-achievements.js'), array('jquery'), '', true );
}
add_action( 'wp_enqueue_scripts', 'wpsteam_achievements_frontend_scripts_and_styles' );

?>
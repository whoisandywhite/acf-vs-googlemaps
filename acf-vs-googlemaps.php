<?php
/**
 * @package ACF vs Google Maps
 * @version 1.0
 */
/*
Plugin Name: ACF vs Google Maps
Description: Adds "enable" and API Key settings for Google Maps API (v3 update 2018)
Author: Andy White
Version: 1.0
Author URI: https://whoisandywhite.com
*/



function wiaw_this_plugin_last() {
	// ensure path to this file is via main wp plugin path
	$wp_path_to_this_file = preg_replace('/(.*)plugins\/(.*)$/', WP_PLUGIN_DIR."/$2", __FILE__);
	$this_plugin = plugin_basename(trim($wp_path_to_this_file));
	$active_plugins = get_option('active_plugins');
	$this_plugin_key = array_search($this_plugin, $active_plugins);
	# Do nothing if this is already the last plugin
		array_splice($active_plugins, $this_plugin_key, 1);
		array_push($active_plugins, $this_plugin);
		update_option('active_plugins', $active_plugins);
}
add_action('activated_plugin', 'wiaw_this_plugin_last');



# Register Options Pages
if( function_exists('acf_add_options_page') ) {
  	# ACF Settings Page
	$page = acf_add_options_page(array(
		'page_title' 	=> 'Google vs ACF',
		'menu_title' 	=> 'Google vs ACF',
		'menu_slug' 	=> 'google-vs-acf-settings',
		'capability' 	=> 'add_users',
		'redirect' 	=> false
	));
}
if( function_exists('acf_add_local_field_group') ){
	acf_add_local_field_group( array(
		'key' => 'group_5b7feb2ee20ea',
		'title' => '* Google vs ACF *',
		'fields' => array(
			array(
				'key' => 'field_5b8f8b9e9f04c',
				'label' => 'Enable Google Maps JS',
				'name' => 'wiaw_enable_google_maps_js',
				'type' => 'true_false',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'ui' => 1,
				'ui_on_text' => '',
				'ui_off_text' => '',
			),
			array(
				'key' => 'field_5b8f8bbc9f04d',
				'label' => 'Google Maps API Key',
				'name' => 'wiaw_google_maps_api_key',
				'type' => 'text',
				'instructions' => 'Sign up for your <a href="https://console.cloud.google.com/" target="_blank">Google Maps API Key</a>',
				'required' => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_5b8f8b9e9f04c',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'placeholder' => '',
				'prepend' => '',
				'append' => '',
				'maxlength' => '',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'google-vs-acf-settings',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'seamless',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => 1,
		'description' => '',
	));
}




# Register Maps scripts function
function wiaw_google_vs_acf_scripts() {
	$apikey = get_field( 'wiaw_google_maps_api_key','option');
	if ( !empty( $apikey)) {
	    wp_enqueue_script('googlemaps-js', '//maps.googleapis.com/maps/api/js?v=3.exp&key='.$apikey, array(),'1.0',true);
	}
}
if ( function_exists('get_field')) {
	# Is the API enabled, and a key set?
	if ( get_field( 'wiaw_enable_google_maps_js', 'option') && !empty( get_field( 'wiaw_google_maps_api_key', 'option'))) {
		add_action( 'wp_enqueue_scripts', 'wiaw_google_vs_acf_scripts' );
	}
}













<?php
/**
* Plugin Name: WooCommerce Customer REST API
* Description: JSON-based REST API for Woocommerce customer endpoints (e.g. Mobile Apps).
* Version: 1.0.0
* Author: David Ruisinger
* Author URI: flavordaaave.com
*/

define ( 'WCC_API_NAME' , 'WooCommerce Customer REST API' );
define ( 'WCC_API_DESCRIPTION' , 'JSON-based REST API for Woocommerce customer endpoints (e.g. Mobile Apps).' );
define ( 'WCC_API_VERSION' , '1.0.0' );
define ( 'WCC_API_BASE' , 'wcc-api' );
define ( 'WCC_API_INTERNAL_PREFIX' , '/wcc-api-plugin' );

/**
 * Include our files for the API.
 */
require_once( dirname( __FILE__ ) . '/lib/class-wc-settings.php' );
require_once( dirname( __FILE__ ) . '/lib/woocommerce-api.php' );

include_once( dirname( __FILE__ ) . '/lib/endpoints/class-wcc-api-start.php' );
include_once( dirname( __FILE__ ) . '/lib/endpoints/class-wcc-api-products.php' );
include_once( dirname( __FILE__ ) . '/lib/endpoints/class-wcc-api-orders.php' );


/**
 * Register rewrite rules for the API
 */
function wcc_api_init() {
	wcc_api_register_rewrites();
	
	//Integrate with WooCommerce settings if dependencies are checked
	wcc_api_check_dependencies();
}
add_action( 'init', 'wcc_api_init' );


/**
 * Add rewrite rules.
 */
function wcc_api_register_rewrites() {
	add_rewrite_rule( '^' . constant('WCC_API_BASE') . '/?$','index.php?json_route=' . constant('WCC_API_INTERNAL_PREFIX'),'top' );
	add_rewrite_rule( '^' . constant('WCC_API_BASE') . '(.*)?','index.php?json_route=' . constant('WCC_API_INTERNAL_PREFIX') . '$matches[1]','top' );
}


/**
 * Set the rewrites upon activation
 */
function wcc_api_activate() {
	wcc_api_register_rewrites();
	flush_rewrite_rules();
}


/**
 * Flush the rewrites upon deactivation
 */
function wcc_api_deactivate() {
	flush_rewrite_rules();
}


/**
 * Register activation/deactivation functions
 */
register_activation_hook(__FILE__, 'wcc_api_activate');
register_deactivation_hook(__FILE__, 'wcc_api_deactivate');


/**
 * Register the endpoints for the WP API REST Plugin
 */
function wcc_api_endpoints( $server ) {
	/**
	 * Create a client that can be used by the endpoints to receive the WC-API results
	 */
	$options = array(
		'debug'           => false,
		'return_as_array' => false,
		'validate_url'    => false,
		'timeout'         => 30,
		'ssl_verify'      => false,
	);
	$client = new WC_API_Client( get_bloginfo('url'), get_option('wc_settings_tab_wcc_api_ck'), get_option('wc_settings_tab_wcc_api_cs'), $options );

	// Start
	$wcc_api_start = new WCC_API_Start();
	add_filter( 'json_endpoints', array( $wcc_api_start, 'register_routes' ), 0 );

	// Products
	$wcc_api_products = new WCC_API_Products( $client );
	add_filter( 'json_endpoints', array( $wcc_api_products, 'register_routes' ), 0 );

	// Orders
	$wcc_api_orders = new WCC_API_Orders( $client );
	add_filter( 'json_endpoints', array( $wcc_api_orders, 'register_routes' ), 0 );
}
add_action( 'wp_json_server_before_serve', 'wcc_api_endpoints' );


/**
 * Check if WooCommerce & WP REST API Plugins are active
 */
function wcc_api_check_dependencies() {
	if ( class_exists( 'WooCommerce' ) ) {
		WC_Settings_Tab_WCC_API::init();
	} else {
		function wcc_api_wc_dependency_error_notice() {
			$class = "error";
			$message = "You need to have the WooCommerce Plugin activated in order to use the " . constant('WCC_API_NAME') . " Plugin.";
			
			echo"<div class=\"$class\"> <p>$message</p></div>"; 
		}
		add_action( 'admin_notices', 'wcc_api_wc_dependency_error_notice' ); 
	}


	if ( ! class_exists( 'WP_JSON_Posts' ) ) {
		function wcc_api_wp_json_dependency_error_notice() {
			$class = "error";
			$message = "You need to have the WP REST API Plugin activated in order to use the " . constant('WCC_API_NAME') . " Plugin.";
			
			echo"<div class=\"$class\"> <p>$message</p></div>"; 
		}
		add_action( 'admin_notices', 'wcc_api_wp_json_dependency_error_notice' ); 
	}
}













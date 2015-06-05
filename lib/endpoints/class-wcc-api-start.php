<?php

class WCC_API_Start {
	/**
	 * Register the Start route
	 *
	 * @param array $routes Existing routes
	 * @return array Modified routes
	 */
	public function register_routes( $routes ) {
		$start_route = array(
			// Start endpoints
			constant('WCC_API_INTERNAL_PREFIX') => array(
				array( array( $this, 'get_start'), WP_JSON_Server::READABLE ),
			),
		);

		return array_merge( $routes, $start_route );
	}



	/**
	 * Display the start page of the API
	 *
	 * @return start entity
	 */
	public function get_start() {
		// Basic info about the API
		$api_info = new stdClass();
   		$api_info->name = constant('WCC_API_NAME');
   		$api_info->description = constant('WCC_API_DESCRIPTION');
   		$api_info->version = constant('WCC_API_VERSION');

   		// Available routes in the API
   		$api_info->routes = new stdClass();   		

   		// Get Products
   		$get_products_route = new stdClass();
   		$get_products_route->accepts = array('GET');
   		$get_products_route->url = get_bloginfo('url') . '/' . constant('WCC_API_BASE') . '/products';
   		$api_info->routes->{"/products"} = $get_products_route;

   		// Get Product
   		$get_product_route = new stdClass();
   		$get_product_route->accepts = array('GET');
   		$get_product_route->url = get_bloginfo('url') . '/' . constant('WCC_API_BASE') . '/products/<id>';
   		$api_info->routes->{"/products/<id>"} = $get_product_route;

		return $api_info;
	}
}
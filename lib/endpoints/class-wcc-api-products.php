<?php

class WCC_API_Products {
	/**
	 * Constructor
	 *
	 * @param WC_API_Client $client WooCommerce API client
	 */
	public function __construct(WC_API_Client $client) {
		$this->client = $client;
	}


	/**
	 * Register the product-related routes
	 *
	 * @param array $routes Existing routes
	 * @return array Modified routes
	 */
	public function register_routes( $routes ) {
		$product_routes = array(
			// Product endpoints
			constant('WCC_API_INTERNAL_PREFIX') . '/products' => array(
				array( array( $this, 'get_products'), WP_JSON_Server::READABLE ),
			),

			constant('WCC_API_INTERNAL_PREFIX') . '/products/(?P<id>\d+)' => array(
				array( array( $this, 'get_product'), WP_JSON_Server::READABLE ),
			),
		);
		return array_merge( $routes, $product_routes );
	}


	/**
	 * Retrieve products.
	 *
	 * The optional $filter parameter modifies the query used to retrieve products.
	 * Accepted keys are 'post_type', 'post_status', 'number', 'offset',
	 * 'orderby', and 'order'.
	 *
	 * @param array $filter Parameters to pass through
	 * @return stdClass[] Collection of Product entities
	 */
	public function get_products( $filter = array() ) {
		try {
			return $this->client->products->get();
		} catch (WC_API_Client_Exception $e) {
			if ( $e instanceof WC_API_Client_HTTP_Exception ) {
				return $e->get_response();
			}			
		}
	}


	/**
	 * Retrieve a product.
	 *
	 * @uses get_product()
	 * @param int $id product ID
	 * @return array Product entity
	 */
	public function get_product( $id ) {
		$id = (int) $id;
		try {
			return $this->client->products->get( $id );
		} catch (WC_API_Client_Exception $e) {
			if ( $e instanceof WC_API_Client_HTTP_Exception ) {
				return $e->get_response();
			}			
		}
	}
}
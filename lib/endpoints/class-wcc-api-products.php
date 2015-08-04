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
				array( array( $this, 'get_products'), WP_JSON_Server::READABLE | WP_JSON_Server::HIDDEN_ENDPOINT),
			),

			constant('WCC_API_INTERNAL_PREFIX') . '/products/(?P<id>\d+)' => array(
				array( array( $this, 'get_product'), WP_JSON_Server::READABLE | WP_JSON_Server::HIDDEN_ENDPOINT ),
			),
		);
		return array_merge( $routes, $product_routes );
	}


	/**
	 * Retrieve products.
	 *
	 * @return stdClass[] Collection of Product entities
	 */
	public function get_products( $fields = null, $type = null, $filter = array(), $page = 1 ) {
		
		// Build the parameter array
		$params = array(
			'fields' => (string)$fields,
			'type' => (string)$type,
			'page' => (string)$page
		);

		foreach ($filter as $key => $value) {
			$params['filter[' . $key . ']'] = $value;
		}

		try {
			return $this->client->products->get( null, $params )->products;
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
	public function get_product( $id, $fields = null ) {
		$id = (int) $id;
		// Build the parameter array
		$params = array(
			'fields' => (string)$fields
		);
		try {
			return $this->client->products->get( $id, $params )->product;
		} catch (WC_API_Client_Exception $e) {
			if ( $e instanceof WC_API_Client_HTTP_Exception ) {
				return $e->get_response();
			}			
		}
	}
}
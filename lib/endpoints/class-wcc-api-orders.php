<?php

class WCC_API_Orders {
	/**
	 * Constructor
	 *
	 * @param WC_API_Client $client WooCommerce API client
	 */
	public function __construct(WC_API_Client $client) {
		$this->client = $client;
	}


	/**
	 * Register the order-related routes
	 *
	 * @param array $routes Existing routes
	 * @return array Modified routes
	 */
	public function register_routes( $routes ) {
		$order_routes = array(
			// Product endpoints
			constant('WCC_API_INTERNAL_PREFIX') . '/orders' => array(
				array( array( $this, 'create_order' ), WP_JSON_Server::CREATABLE | WP_JSON_Server::ACCEPT_JSON | WP_JSON_Server::HIDDEN_ENDPOINT ),
			),

			constant('WCC_API_INTERNAL_PREFIX') . '/orders/(?P<id>\d+)' => array(
				array( array( $this, 'update_payment_status' ), WP_JSON_Server::EDITABLE | WP_JSON_Server::ACCEPT_JSON | WP_JSON_Server::HIDDEN_ENDPOINT ),
			),
		);
		return array_merge( $routes, $order_routes );
	}


	/**
	 * Create an order
	 *
	 * @param array $data valid order data
	 * @return stdClass[] of the newly created order entity
	 */
	public function create_order( $data ) {
		try {
			return $this->client->orders->create( $data );
		} catch (WC_API_Client_Exception $e) {
			if ( $e instanceof WC_API_Client_HTTP_Exception ) {
				return $e->get_response();
			}			
		}
	}


	/**
	 * Update the payment status for an order
	 *
	 * @param int $id order ID
	 * @param string $status valid order status
	 * @return array|object newly-updated order
	 */
	public function update_payment_status( $id, $paystatus ) {
		$data = array(
			'order' => array(
				'payment_details' => array(
					'paid' => filter_var($paystatus, FILTER_VALIDATE_BOOLEAN))
				)
			);

		try {
			return $this->client->orders->update( $id, $data );
		} catch (WC_API_Client_Exception $e) {
			if ( $e instanceof WC_API_Client_HTTP_Exception ) {
				return $e->get_response();
			}			
		}
	}

}
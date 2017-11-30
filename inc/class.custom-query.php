<?php
/**
 * Custom Query
 * 
 * @package pkp
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CustomQuery' ) ) {

	/**
	 *
	 * Class for custom query
	 */
	class CustomQuery {

		/**
		 * Class constructor.
		 *
		 * @return void
		 */
		public function __construct() {

		}

		/**
		 * Query For Get Order with payment confirmation, status pending and payment method is BACS
		 *  
		 */
		public function payment_confirmation_order() {
			global $wpdb;

			$args_order = array(
				'post_type'		  => 'shop_order',
				'post_status'	  => 'wc-pending',
				'posts_per_page'  => -1,
				'meta_query' => array(
					array(
						'key'     => '_payment_method',
						'value'   => 'bacs',
					),
					array(
						'key'     => 'order_bacs_confirmation',
						'compare' => 'EXISTS',
					),
				),
			);

			$order_pending = new WP_Query( $args_order );

			return $order_pending;
		}

		public function get_confirm_payment() {
			$query = $this->payment_confirmation_order();
			return $query->post_count;
		}

	}

}
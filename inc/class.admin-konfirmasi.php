<?php
/**
 * Admin Konfirmasi - untuk menampilkan daftar konfirmasi di halaman order details
 * 
 * @package pkp
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AdminKonfirmasi' ) ) {

	/**
	 *
	 * Class for create admin konfirmasi pembayaran order details
	 */
	class AdminKonfirmasi {

		/**
		 * Class constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			//add_action( 'woocommerce_after_order_itemmeta', array( $this, 'list_payment_confirmation' ) );
			add_action( 'add_meta_boxes', array( $this, 'meta_box_payment_confirmation' ) );
		}

		public function meta_box_payment_confirmation() {
			add_meta_box(
		        'payment_confirmation',
		        __( 'Konfirmasi Pembayaran', 'pkp' ),
		        array( $this, 'list_payment_confirmation' ),
		        'shop_order',
		        'normal',
		        'core'
		    );
		}

		/**
		 * Callback to display list of payment confirmation
		 * 
		 * @return html
		 */
		public function list_payment_confirmation() {
			
			global $post;
			
			$order_bacs_confirmation = get_post_meta( $post->ID, 'order_bacs_confirmation' );

			require plugin_dir_path( __FILE__ ) . 'view/view.list-konfirmasi-pembayaran.php';
			
		}

	}

}

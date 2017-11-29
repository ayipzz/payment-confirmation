<?php
/**
 * Admin Konfirmasi
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
			add_action( 'add_meta_boxes', array( $this, 'meta_box_payment_confirmation' ) );
			add_action( 'woocommerce_order_status_changed', array( $this, 'order_processing_handle' ), 10, 3 );
		}

		/**
		 * Create Metabox Konfirmasi Pembayaran to Edit Order Details
		 * 
		 */
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

		/**
		 * Send email to customer when order status change to processing if payment method is bacs
		 * 
		 * @param  int $id         
		 * @param  string $old_status 
		 * @param  string $new_status 
		 *          
		 */
		public function order_processing_handle( $id, $old_status, $new_status ) {
    		
    		if ( $new_status != 'processing' ) return;

    		$order = new WC_Order( $id );

    		if ( $order->get_payment_method() != 'bacs' ) return;

    		// send email to customer
    		$option = new HelpersKonfirmasi();
			$user_email = $order->get_billing_email();
			$option->send_single_email( 'payment_received', $user_email );

		}

	}

}

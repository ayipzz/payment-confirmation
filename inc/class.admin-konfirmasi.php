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
			add_action( 'add_meta_boxes', array( $this, 'meta_box_payment_confirmation' ) );
			add_action( 'woocommerce_order_status_changed', array( $this, 'order_processing_handle' ), 10, 3 );
		}

		/**
		 * Create Metabox Konfirmasi Pembayaran to Edit Order Details
		 * 
		 * @return 
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
		 * Kirim Email ke customer bahwa pembayaran berhasil dan order sedang diprocess, syarat order status adalah processing dan metode pembayaran adalah bacs
		 * 
		 * @param  [type] $id         
		 * @param  [type] $old_status 
		 * @param  [type] $new_status 
		 * 
		 * @return [type]             
		 */
		public function order_processing_handle( $id, $old_status, $new_status ) {
    		
    		if ( $new_status != 'processing' ) return;

    		$order = new WC_Order( $id );

    		if ( $order->get_payment_method() != 'bacs' ) return;

    		// Kirim Email notifikasi ke customer bahwa pembayaran berhasil
			$user_email = $order->get_billing_email();
			$subject	= 'Pembayaran Berhasil';
			$message    = get_option( 'konfirmasi_pembayaran_setting_payment_success' ) ? get_option( 'konfirmasi_pembayaran_setting_payment_success' ) : 'Selamat Pembayaran anda berhasil';

			wp_mail( $user_email, $subject, $message );

		}

	}

}

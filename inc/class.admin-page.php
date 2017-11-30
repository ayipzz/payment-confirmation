<?php
/**
 * Admin Page PKP
 * 
 * @package pkp
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PaymentConfirmationAdminPage' ) ) {

	/**
	 *
	 * Class for add admin page on wp-admin
	 */
	class PaymentConfirmationAdminPage {

		private $options_general_settings;

		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 */
		public function __construct() {
			add_action( 'admin_menu', array( $this, 'pkp_admin_menu' ) );
			add_action( 'admin_init', array( $this, 'register_payment_confirmation_settings' ) );
		}

		/**
		 * Create Menu PKP Page
		 * 
		 * @version 1.0.0
		 * @since 1.0.0
		 */
		public function pkp_admin_menu() {
			add_menu_page( 
				'Plugin Konfirmasi Pembayaran', 
				'Konfirmasi', 
				'read', 
				'payment_confirmation',
				array( $this, 'payment_confirmation_handler' ),
				'dashicons-store', 
				150 
			);
		}

		/**
		 * Register Payment Confirmation Form Fields
		 * 
		 * @return
		 */
		public function register_payment_confirmation_settings() {
			register_setting( 'pkps', 'notif_success_title' );
			register_setting( 'pkps', 'notif_success_content' );
			register_setting( 'pkps', 'notif_failed_title' );
			register_setting( 'pkps', 'notif_failed_content' );
		}

		/**
		 * Callback pkp admin page untuk view
		 * 
		 * @version 1.0.0
		 * @since 1.0.0
		 */
		public function payment_confirmation_handler() {

			$this->options_general_settings = get_option( 'pkp_general_setting' );

			$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
			require PKP_PATH . 'inc/view/view.admin-page.php';

		}
		
	}

}

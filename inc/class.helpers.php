<?php
/**
 * Helpers Konfirmasi
 * 
 * @package pkp
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'HelpersKonfirmasi' ) ) {

	/**
	 *
	 * Class for helpers konfirmasi pembayaran
	 */
	class HelpersKonfirmasi {

		/**
		 * Class constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			
		}

		/**
		 * Display value from konfirmasi setting option
		 * 
		 * @param  string $key 
		 * @return string $value
		 */
		public function get_konfirmasi_option( $key ) {
			
			$value = get_option( $key ) ? get_option( $key ) : '';

			return $value;
		}

		/**
		 * Send single email
		 *
		 * @param string $email_type
		 * @param string $email_address
		 */
		public function send_single_email( $email_type, $email_address ) {

			global $woocommerce;
			$mailer = WC()->mailer();
			$mails = $mailer->get_emails();
			if( isset( $mails[$email_type] ) ) { 
				return $mails[$email_type]->trigger( $email_address );
			}
			return false;
		}

	}

}
<?php
/**
 * Sendmail
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SendmailKonfirmasi' ) ) {

	/**
	 *
	 * Class for send email konfirmasi pembayaran
	 */
	class SendmailKonfirmasi {

		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 */
		public function __construct() {
			
		}

		/**
		 * Send email with template woocommerce
		 * 
		 * @param  [type] $args data (email, type)
		 * 
		 * @return boolean
		 */
		public function send( $args ) {

			add_filter( 'wp_mail_content_type', function(){ return 'text/html'; } );

			if ( $args['type'] == 'payment_confirmation_success' ) {
				$subject	= 'Konfirmasi Pembayaran';
				$message    = get_option( 'konfirmasi_pembayaran_setting_confirm_success_customer' ) ? get_option( 'konfirmasi_pembayaran_setting_confirm_success_customer' ) : 'Selamat Anda berhasil melakaukan konfirmasi Pembayaran';
			} else if ( $args['type'] == 'admin_payment_confirmation_success' ) {
				$subject  = 'Konfirmasi Pembayaran';
				$message  = 'Ada Konfirmasi Pembayaran Baru, Silahkan Cek <a href="'. admin_url( 'edit.php?post_type=shop_order' ) .'">Disini</a>';
			}

			wp_mail( $args['email'], $subject, $message );

		}

	}

}

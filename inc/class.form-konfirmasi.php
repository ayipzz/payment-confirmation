<?php
/**
 * Form Konfirmasi
 * 
 * @package pkp
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'FormKonfirmasi' ) ) {

	/**
	 *
	 * Class for create form konfirmasi pembayaran frontend
	 */
	class FormKonfirmasi {

		/**
		 * Class constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			$this->create_shortcode();
		}

		/**
		 * Create form konfirmasi pembayaran shortcode
		 * @return shortcode
		 */
		public function create_shortcode() {
			add_shortcode( 'bacs-payment-confirmation', array( $this, 'form_konfirmasi_pembayaran' ) );
		}

		/**
		 * Display Form Konfirmasi Pembayaran
		 * @return html
		 */
		public function form_konfirmasi_pembayaran() {
			
			// get woocommerce bacs account
			$bacs_account = new WC_Gateway_BACS();

			require plugin_dir_path( __FILE__ ) . 'view/view.form-konfirmasi-pembayaran.php';

			// check input empty or not
			if ( isset( $_POST['action'] ) && $_POST['action'] == 'payment_confirmation' ) {
				
				if ( isset( $_POST['order-number'] ) && isset( $_POST['payment-code'] ) && isset( $_POST['payment-nominal'] ) && isset( $_POST['transfer-date'] ) && isset( $_POST['destination-bank'] ) && isset( $_POST['bank'] ) && isset( $_POST['bank-name'] ) && isset( $_FILES['payment-file'] ) && isset( $_POST['description'] ) ) {

					// Payment Confirmation form
					$action 			= @sanitize_text_field( $_POST['action'] );
					$order_id 			= @sanitize_text_field( $_POST['order-number'] );
					$payment_code 		= @sanitize_text_field( $_POST['payment-code'] );
					$payment_nominal 	= @sanitize_text_field( $_POST['payment-nominal'] );
					$transfer_date 		= @sanitize_text_field( $_POST['transfer-date'] );
					$destination_bank 	= @sanitize_text_field( $_POST['destination-bank'] );
					$bank 				= @sanitize_text_field( $_POST['bank'] );
					$bank_user_name 	= @sanitize_text_field( $_POST['bank-name'] );
					$payment_file 		= @sanitize_text_field( $_FILES['payment-file'] );
					$description 		= @sanitize_text_field( $_POST['description'] );					
						
					// get order detail by order id
					$order = wc_get_order( $order_id );

					if ( $order ) { // check order id exist or not

						if ( $order->get_payment_method() == 'bacs' ) { // check payment method and order status
							
							if ( $order->get_status() == 'pending' ) {

								$file_permission	= array( 'jpg', 'jpeg', 'png' );
								$upload_dir 		= wp_upload_dir();
								$file_name 			= @$_FILES['payment-file']['name'];
								$file_type			= @$_FILES['payment-file']['type'];
								$exp_filename		= explode( '.', $file_name );
								$file_ext			= strtolower( end( $exp_filename ) );
								$file_tmp 			= @$_FILES['payment-file']['tmp_name'];

								if ( in_array( $file_ext, $file_permission ) ) { // cek apakah type file diizinkan

									move_uploaded_file( $file_tmp, $upload_dir['path'] . '/' . $file_name );

									// prepare the data
									$data_confirmation = array(
										'payment_code'		=> $payment_code,
										'payment_nominal'	=> $payment_nominal,
										'transfer_date'		=> $transfer_date,
										'destination_bank'	=> $destination_bank,
										'bank'				=> $bank,
										'bank_user_name'	=> $bank_user_name,
										'payment_file'		=> $upload_dir['url'] . '/' . $file_name,
										'description'		=> $description
									);

									// check if post meta exist or not
									$order_bacs_confirmation = get_post_meta( $order->get_id(), 'order_bacs_confirmation' );
									$confirmation = array();
									
									if ( count( $order_bacs_confirmation ) > 0 ) { // jika sudah ada maka tambahkan konfirmasi baru + konfirmasi sebelumnya
									
										foreach ( $order_bacs_confirmation as $key => $value ) {
											$confirmation = maybe_unserialize( $value );
										}

										$confirmation[] = $data_confirmation; // tambahakan data lama dengan data baru

										if ( update_post_meta( $order->get_id(), 'order_bacs_confirmation', maybe_serialize( $confirmation ) ) ) {
											echo 'Berhasil menambahkan data konfirmasi';
										} else {
											$this->error_payment_confirmation( 'Gagal menambahkan data konfirmasi' );
										}

									} else { // buat konfirmasi baru
										
										$confirmation[] = $data_confirmation;

										if ( add_post_meta( $order->get_id(), 'order_bacs_confirmation', maybe_serialize( $confirmation ) ) )  {
											echo 'Berhasil menambahkan data konfirmasi';
										} else {
											$this->error_payment_confirmation( 'Gagal menambahkan data konfirmasi' );
										}
										
									}
									
								} else {
									$this->error_payment_confirmation( 'Tipe File tidak sesuai, pastikan (jpeg, jpg, png)' );
								}

							} else {
								$this->error_payment_confirmation( 'Order status tidak pending' );
							}

						} else {
							$this->error_payment_confirmation( 'metode pembayaran bukan bacs' );
						}
					} else {
						$this->error_payment_confirmation( 'order tidak ditemukan' );
					}

				} else {
					$this->error_payment_confirmation( '*)Pastikan semua kolom diisi' );
				}

			}
			
		}

		public function error_payment_confirmation( $message ) {
			echo 'Error, ' . $message;
		} 
	}

}
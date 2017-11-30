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

			if ( ! isset( $_POST['action'] ) ) {
				require plugin_dir_path( __FILE__ ) . 'view/view.form-konfirmasi-pembayaran.php';
			} else {
				$this->save_konfirmasi_pembayaran();
			}
			
		}

		/**
		 * Save error log
		 * 
		 * @return void
		 */
		public function pkps_error_validation( $message ) {
			$error = array();

			$error[] = $message;

			// error handle to display message for customer
			$this->error_handle( $error );

			return;
		}

		/**
		 * Handle save action form konfirmasi pembayaran
		 *
		 * @return 
		 */
		public function save_konfirmasi_pembayaran() {

			// check if have action and the action is payment_confirmation
			if ( ! isset( $_POST['action'] ) && $_POST['action'] != 'payment_confirmation' ) return;

			// check all input, empty or not
			if ( ! isset( $_POST['order-number'] ) && ! isset( $_POST['payment-code'] ) && ! isset( $_POST['payment-nominal'] ) && ! isset( $_POST['transfer-date'] ) && ! isset( $_POST['destination-bank'] ) && ! isset( $_POST['bank'] ) && ! isset( $_POST['bank-name'] ) && ! isset( $_FILES['payment-file'] ) && ! isset( $_POST['description'] ) ) { $this->pkps_error_validation('all_field_required' ); return; }

			// Prepare Data Payment Confirmation form
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

			// check order id exist or not
			if ( ! $order ) { $this->pkps_error_validation( 'order_not_found' ); return; }

			// check payment method is bacs
			if ( $order->get_payment_method() != 'bacs' ) { $this->pkps_error_validation( 'payment_not_bacs' ); return; }

			// check order status is pending payment
			if ( $order->get_status() != 'pending' ) { $this->pkps_error_validation( 'order_not_pending' ); return; }

			// Peprare file `bukti pembayaran` data
			$file_permission	= array( 'jpg', 'jpeg', 'png' );
			$upload_dir 		= wp_upload_dir();
			$file_name 			= @$_FILES['payment-file']['name'];
			$file_type			= @$_FILES['payment-file']['type'];
			$exp_filename		= explode( '.', $file_name );
			$file_ext			= strtolower( end( $exp_filename ) );
			$file_tmp 			= @$_FILES['payment-file']['tmp_name'];

			// check `bukti pembayaran` file type is allowed (jpg, png)
			if ( ! in_array( $file_ext, $file_permission ) ) { $this->pkps_error_validation( 'file_type_disallowed' ); return; }

			// upload file to wp uploads/date directory
			move_uploaded_file( $file_tmp, $upload_dir['path'] . '/' . $file_name );

			// prepare the data konfirmasi pembayaran
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

			$order_bacs_confirmation = get_post_meta( $order->get_id(), 'order_bacs_confirmation' );
			$confirmation = array();

			// check if post meta order_bacs_confirmation exist or not
			if ( count( $order_bacs_confirmation ) > 0 ) {
				foreach ( $order_bacs_confirmation as $key => $value ) {
					$confirmation = maybe_unserialize( $value );
				}

				$confirmation[] = $data_confirmation; // add new data to old data

				update_post_meta( $order->get_id(), 'order_bacs_confirmation', maybe_serialize( $confirmation ) );

			} else { // if post meta not exist create new
				
				$confirmation[] = $data_confirmation;

				add_post_meta( $order->get_id(), 'order_bacs_confirmation', maybe_serialize( $confirmation ) );
				
			}

			// send email to admin and customer
			$this->sendmail( $order_id );

		}

		/**
		 * Method to handle if have error then display the error message fe
		 * 
		 * @param  array $error  error message
		 * @return html
		 */
		public function error_handle( $error ) {

			// if found error, stop the process
			if ( count( $error ) > 0 ) { 
				$option = new HelpersKonfirmasi();
				echo $option->get_konfirmasi_option( 'pkps_confirm_failed_title' ) . '<br />';
				echo $option->get_konfirmasi_option( 'pkps_confirm_failed_content' );
			}

		}

		/**
		 * Send email to customer and admin if not error found
		 * 
		 * @return html
		 */
		public function sendmail( $order_id ) {
			$order = wc_get_order( $order_id );
			$option = new HelpersKonfirmasi();

			// send email notification to customer
			$user_email = $order->get_billing_email();
			$option->send_single_email( 'confirmation_submited', $user_email, $order_id );

			// send email notification to admin
			$admin_email = get_option( 'admin_email' );
			$option->send_single_email( 'admin_confirmation_submited', $admin_email, $order_id );

			// print success message
			echo $option->get_konfirmasi_option( 'pkps_confirm_success_title' ) . '<br />';
			echo $option->get_konfirmasi_option( 'pkps_confirm_success_content' );
		}

	}

}
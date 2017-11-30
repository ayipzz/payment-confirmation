<?php
/**
 * @since        1.0.0
 * @package      Plugin Konfirmasi Pembayaran
 * 
 * Plugin Name:  Plugin Konfirmasi Pembayaran
 * Description:  Plugin untuk konfirmasi pembayaran woocommerce.
 * Version:      1.0.0
 * Author:       Tonjoo
 * Author URI:   http://www.tonjoo.com/
 * Plugin URI:   http://www.tonjoostudio.com/plugin/plugin-konfirmasi-pembayaran
 * License:      GPL
 * Text Domain:  pkp
 * Domain Path:  /languages
 **/

// exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PluginKonfirmasiPembayaran' ) ) {

	/**
	 * Main Class PluginKonfirmasiPembayaran
	 */
	class PluginKonfirmasiPembayaran {

		/**
		 * Class constructor.
		 *
		 * @return void
		 */
		public function __construct() {

			$this->define_constants();

			include_once PKP_PATH . 'inc/class.admin-notice.php';
			$admin_notice = new AdminNotice();
			
			// Check if WooCommerce is active.
			$plugin = 'woocommerce/woocommerce.php';
			if ( ! in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ), true ) && ! ( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) ) ) {
				$admin_notice->add_notice( __( 'Plugin Konfirmasi Pembayaran need woocommerce plugin to be actived.', 'pkp' ), 'error', true );
				return;
			}

			add_filter( 'woocommerce_email_classes', array( $this, 'add_woocommerce_email' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ) , array( $this, 'plugin_add_settings_link' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

			$this->includes();

			new AdminKonfirmasi();
			new FormKonfirmasi();
			new AdminSettingKonfirmasi();
			
		}

		/**
		 * Setup plugin constants.
		 *
		 */
		private function define_constants() {
			define( 'PKP_VERSION', '1.0.0' );
			define( 'PKP_URL', plugins_url( '', __FILE__ ) );
			define( 'PKP_PATH', plugin_dir_path( __FILE__ ) );
			define( 'PKP_REL_PATH', dirname( plugin_basename( __FILE__ ) ) . '/' );
		}

		/**
		 * Include required files.
		 *
		 */
		private function includes() {
			include_once PKP_PATH . 'inc/class.helpers.php';
			include_once PKP_PATH . 'inc/class.form-konfirmasi.php';
			include_once PKP_PATH . 'inc/class.custom-query.php';
			include_once PKP_PATH . 'inc/class.admin-konfirmasi.php';
			include_once PKP_PATH . 'inc/class.admin-setting.php';
		}

		/**
		 * Register Custom Email woocommerce for konfirmasi pembayaran
		 */
		public function add_woocommerce_email( $email_classes ) {
			include_once PKP_PATH . 'inc/class.mailer.php';

			$args_confirm_submited = array( 
				'id' => 'confirm_submited', 
				'title'	=> __( 'Payment Confirmation', 'pkp' ),
				'heading'	=> __( 'Konfirmasi Pembayaran', 'pkp' ),
				'subject'	=> __( 'Konfirmasi Pembayaran', 'pkp' ),
				'description'	=> 'Payment Confirmation send email to customer' 
			);

			$args_admin_confirm_submited = array( 
				'id' => 'admin_confirm_submited', 
				'title'	=> __( 'Admin Payment Confirmation', 'pkp' ),
				'heading'	=> __( 'Konfirmasi Pembayaran', 'pkp' ),
				'subject'	=> __( 'Konfirmasi Pembayaran', 'pkp' ),
				'description'	=> 'Payment Confirmation send email to admin if have new confirmation payment' 
			);

			$args_payment_received = array( 
				'id' => 'payment_received', 
				'title'	=> __( 'Payment Received', 'pkp' ),
				'heading'	=> __( 'Pembayaran Diterima', 'pkp' ),
				'subject'	=> __( 'Pembayaran Diterima', 'pkp' ),
				'description'	=> 'Send email to customer if payment received' 
			);

			$email_classes['confirmation_submited'] = new PaymentConfirmationEmail( $args_confirm_submited );
			$email_classes['admin_confirmation_submited'] = new PaymentConfirmationEmail( $args_admin_confirm_submited );
			$email_classes['payment_received'] = new PaymentConfirmationEmail( $args_payment_received );
			
			return $email_classes;
		}

		/**
		 * Enqueue FE scripts and styles.
		 *
		 */
		public function wp_enqueue_scripts( ) {
			// CSS
			wp_enqueue_style( 'pkp-jquery-ui', PKP_URL . '/assets/css/jquery-ui.css' );
			wp_enqueue_style( 'pkp-main-style', PKP_URL . '/assets/css/payment-confirmation.css' );
			wp_enqueue_style( 'pkp-main-style', PKP_URL . '/assets/css/admin-payment-confirmation.css' );

			// JS
			wp_enqueue_script( 'pkp-jquery', PKP_URL . '/assets/js/jquery-3.1.1.min.js' );
			wp_enqueue_script( 'pkp-jquery-ui', PKP_URL . '/assets/js/jquery-ui.js' );
			wp_enqueue_script( 'pkp-nice-select', PKP_URL . '/assets/js/jquery.nice-select.min.js' );
			wp_enqueue_script( 'pkp-main-script', PKP_URL . '/assets/js/payment-confirmation.min.js' );
			wp_enqueue_script( 'pkp-custom-script', PKP_URL . '/assets/js/custom.js' );

		}

		/**
		 * Enqueue BE scripts and styles.
		 *
		 * @global string $post_type
		 */
		public function admin_enqueue_scripts() {
			wp_enqueue_style( 'pkp-admin-order-konfirmasi', PKP_URL . '/assets/css/admin-payment-confirmation.min.css' );
			
			if ( get_current_screen()->id == 'woocommerce_page_wc-settings' && ( isset( $_GET['tab'] ) && $_GET['tab'] == 'pkps' ) ) {
				wp_enqueue_style( 'pkp-admin-trumbowyg', PKP_URL . '/assets/css/trumbowyg.min.css' );
				wp_enqueue_script( 'pkp-tiny-script', PKP_URL . '/assets/js/trumbowyg.min.js' );
			}
		}

		/**
		 * Add setting quick link to the plugins list
		 * 
		 * @return html
		 */
		public function plugin_add_settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=wc-settings&tab=pkps">' . __( 'Settings' ) . '</a>';
			$email_settings_link = '<a href="admin.php?page=wc-settings&tab=email">' . __( 'Email Settings' ) . '</a>';
		    array_push( $links, $settings_link );
		    array_push( $links, $email_settings_link );
		  	return $links;
		}

	}

	new PluginKonfirmasiPembayaran();

}
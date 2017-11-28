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
			// Check if WooCommerce is active.
			$plugin = 'woocommerce/woocommerce.php';
			if ( ! in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ), true ) && ! ( is_multisite() && array_key_exists( $plugin, get_site_option( 'active_sitewide_plugins', array() ) ) ) ) {
				$this->add_notice( __( 'Plugin Konfirmasi Pembayaran need woocommerce plugin to be actived.', 'pkp' ), 'error', true );
				return;
			}

			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

			$this->define_constants();
			$this->includes();

			new FormKonfirmasi();
			new AdminKonfirmasi();
		}

		/**
		 * Setup plugin constants.
		 *
		 * @return void
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
		 * @return void
		 */
		private function includes() {
			include_once PKP_PATH . 'inc/class.form-konfirmasi.php';
			include_once PKP_PATH . 'inc/class.admin-konfirmasi.php';
		}

		/**
		 * Enqueue admin scripts and styles.
		 *
		 * @global string $post_type
		 */
		public function wp_enqueue_scripts( ) {
			// CSS
			wp_enqueue_style( 'pkp-jquery-ui', PKP_URL . '/assets/css/jquery-ui.css' );
			wp_enqueue_style( 'pkp-main-style', PKP_URL . '/assets/css/payment-confirmation.css' );

			// JS
			wp_enqueue_script( 'pkp-jquery', PKP_URL . '/assets/js/jquery-3.1.1.min.js' );
			wp_enqueue_script( 'pkp-jquery-ui', PKP_URL . '/assets/js/jquery-ui.js' );
			wp_enqueue_script( 'pkp-nice-select', PKP_URL . '/assets/js/jquery.nice-select.min.js' );
			wp_enqueue_script( 'pkp-main-script', PKP_URL . '/assets/js/payment-confirmation.min.js' );
			wp_enqueue_script( 'pkp-custom-script', PKP_URL . '/assets/js/custom.js' );

		}

		/**
		 * Add admin notices.
		 */
		public function add_notice( $html = '', $status = '', $paragraph = false ) {
			$this->notices[] = array(
				'html'       => $html,
				'status'     => $status,
				'paragraph'  => $paragraph,
			);

			add_action( 'admin_notices', array( $this, 'display_notice' ) );
		}

		/**
		 * Print admin notices.
		 */
		public function display_notice() {
			foreach ( $this->notices as $notice ) {
				echo '
				<div class="pkp ' . esc_attr( $notice['status'] ) . '">
					' . ( $notice['paragraph'] ? '<p>' : '' ) . '
					' . $notice['html'] . '
					' . ( $notice['paragraph'] ? '</p>' : '' ) . '
				</div>';
			}
		}

	}

	new PluginKonfirmasiPembayaran();

}
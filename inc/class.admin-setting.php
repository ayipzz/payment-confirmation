<?php
/**
 * Admin Setting Konfirmasi Pembayaran
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AdminSettingKonfirmasi' ) ) :

	/**
	 *
	 * Class for setting konfirmasi Pembayaran
	 */
	class AdminSettingKonfirmasi {

		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 */
		public function __construct() {
			$this->id = 'konfirmasi_pembayaran_setting';
			$this->label = __( 'Konfirmasi Pembayaran', 'pkp' );

			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'add_settings_tab' ), 50 );
			add_action( 'woocommerce_settings_tabs_konfirmasi_pembayaran_setting', array( $this, 'settings_tab' ) );
			add_action( 'woocommerce_update_options_konfirmasi_pembayaran_setting', array( $this, 'update_settings' ) );
		}

		/**
		 * Set the woocommerce tab setting
		 */
	    public function add_settings_tab( $settings_tabs ) {
	        $settings_tabs[$this->id] = $this->label;
	        return $settings_tabs;
	    }

	    /**
	     * Display Form
	     */
	    public function settings_tab() {
	    	woocommerce_admin_fields( $this->get_settings() );
	    }

		/**
		 * Save settings.
		 *
		 * @version 1.0.0
		 * @since   1.0.0
		 */
		public function update_settings() {
	        woocommerce_update_options( $this->get_settings() );
	    }

		/**
		 * Get_settings.
		 *
		 * @version 3.1.0
		 */
		public function get_settings() {
			global $current_section;
			$settings = apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() );

			return array_merge( $settings, array(
				array(
					'title'     => __( 'Email Settings', 'pkp' ),
					'type'      => 'title',
					'id'        => $this->id . '_reset_options',
				),
				array(
					'title'     => __( 'Email Konfirmasi Success (Customer)', 'pkp' ),
					'desc'      => '',
					'default'	=> '',
					'id'        => $this->id . '_confirm_success_customer',
					'type'      => 'textarea',
				),
				array(
					'title'     => __( 'Email Konfirmasi Success (Admin)', 'pkp' ),
					'desc'      => '',
					'default'	=> '',
					'id'        => $this->id . '_confirm_success_admin',
					'type'      => 'textarea',
				),
				array(
					'title'     => __( 'Email Payment Success', 'pkp' ),
					'desc'      => '',
					'default'	=> '',
					'id'        => $this->id . '_payment_success',
					'type'      => 'textarea',
				),
				array(
					'type'      => 'sectionend',
					'id'        => $this->id . '_end',
				),
			) );
		}

	}

endif;
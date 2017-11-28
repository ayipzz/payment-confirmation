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
			$this->id = 'pkps';
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
					'title'     => __( '1. Email', 'pkp' ),
					'type'      => 'title',
					'id'        => $this->id . '_sec_email',
				),
				array(
					'type'      => 'sectionend',
					'id'        => $this->id . '_end',
				),
				array(
					'title'     => __( 'Email Konfirmasi Pelanggan Baru', 'pkp' ),
					'type'      => 'title',
					'id'        => $this->id . '_sec_email_customer',
				),
				array(
					'title'     => __( 'Judul', 'pkp' ),
					'desc'      => '',
					'default'	=> __( 'Konfirmasi Pembayaran', 'pkp' ),
					'id'        => $this->id . '_confirm_customer_success_title',
					'type'      => 'text',
				),
				array(
					'title'     => __( 'Content', 'pkp' ),
					'desc'      => '',
					'default'	=> __( 'Terima Kasih, Konfirmasi Anda sedang kami proses, harap menunggu.' ),
					'id'        => $this->id . '_confirm_customer_success_content',
					'type'      => 'textarea',
				),
				array(
					'type'      => 'sectionend',
					'id'        => $this->id . '_end',
				),
				array(
					'title'     => __( 'Email Konfirmasi Berhasil', 'pkp' ),
					'type'      => 'title',
					'id'        => $this->id . '_sec_email_confirmation_success',
				),
				array(
					'title'     => __( 'Judul', 'pkp' ),
					'desc'      => '',
					'default'	=> '',
					'id'        => $this->id . '_confirm_email_success_title',
					'type'      => 'text',
				),
				array(
					'title'     => __( 'Content', 'pkp' ),
					'desc'      => '',
					'default'	=> '',
					'id'        => $this->id . '_confirm_email_success_content',
					'type'      => 'textarea',
				),
				array(
					'type'      => 'sectionend',
					'id'        => $this->id . '_end',
				),
				array(
					'title'     => __( '2. Notifikasi', 'pkp' ),
					'type'      => 'title',
					'id'        => $this->id . '_sec_notification',
				),
				array(
					'type'      => 'sectionend',
					'id'        => $this->id . '_end',
				),
				array(
					'title'     => __( 'Konfirmasi Gagal', 'pkp' ),
					'type'      => 'title',
					'id'        => $this->id . '_sec_confirm_failed',
				),
				array(
					'title'     => __( 'Judul', 'pkp' ),
					'desc'      => '',
					'default'	=> 'Konfirmasi Gagal',
					'id'        => $this->id . '_confirm_failed_title',
					'type'      => 'text',
				),
				array(
					'title'     => __( 'Content', 'pkp' ),
					'desc'      => '',
					'default'	=> '',
					'id'        => $this->id . '_confirm_failed_content',
					'type'      => 'textarea',
				),
				array(
					'type'      => 'sectionend',
					'id'        => $this->id . '_end',
				),
				array(
					'title'     => __( 'Konfirmasi Berhasil', 'pkp' ),
					'type'      => 'title',
					'id'        => $this->id . '_sec_confirm_success',
				),
				array(
					'title'     => __( 'Judul', 'pkp' ),
					'desc'      => '',
					'default'	=> '',
					'id'        => $this->id . '_confirm_success_title',
					'type'      => 'text',
				),
				array(
					'title'     => __( 'Content', 'pkp' ),
					'desc'      => '',
					'default'	=> '',
					'id'        => $this->id . '_confirm_success_content',
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
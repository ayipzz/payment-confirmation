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
			add_action( 'woocommerce_settings_tabs_pkps', array( $this, 'settings_tab' ) );
			add_action( 'woocommerce_update_options_pkps', array( $this, 'update_settings' ) );
			add_action( 'admin_footer', array( $this, 'js_config' ) );

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
		 */
		public function get_settings() {
			
			//wp_editor('','notif_success');

			global $current_section;
			$settings = apply_filters( 'woocommerce_get_settings_' . $this->id . '_' . $current_section, array() );

			//print_r( $settings ); 

			$a[] = '<input type="text" id="'.$this->id.'_notif_success_title" name="'.$this->id.'_notif_success_title">';


			/*return array_merge( $settings, array(
				array(
					'title'     => __( 'Notifikasi', 'pkp' ),
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
			) );*/
		}

		/**
		 * Add Editor
		 * 
		 * @return [type] [description]
		 */
		public function js_config() {
			if ( get_current_screen()->id == 'woocommerce_page_wc-settings' && ( isset( $_GET['tab'] ) && $_GET['tab'] == 'pkps' ) ) {
				wp_enqueue_script('tiny_mce');
				echo '<script type="text/javascript">
        tinyMCE.init({
            mode : "exact",
            elements : "pkps_confirm_failed_content,elem2", //putting extra tetarea id seperated by comma to show WYSIWYG 
            //theme : "simple",
            theme : "advanced",
               height:"250",
                //width:"600",
 
            // Theme options
            theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull",
            theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,| formatselect,fontselect,fontsizeselect",
            theme_advanced_buttons3 : "", 
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : true,
 
        });
    </script>';

				//echo '<script type="text/javascript">tinyMCE.init({ mode : "exact",selector:\'textarea\', menubar : false });</script>';
				/*echo '
				<style type="text/css">.trumbowyg-editor, .trumbowyg-textarea {background: #fff;}.trumbowyg-box, .trumbowyg-editor {min-height: 200px;}</style>
				<script type="text/javascript">jQuery(\'textarea\').trumbowyg({autogrow: true});</script>
				';*/
			}
		}

	}

endif;
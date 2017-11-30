<?php
/**
 * Admin Konfirmasi
 * 
 * @package pkp
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AdminKonfirmasi' ) ) {

	/**
	 *
	 * Class for create admin konfirmasi pembayaran order details
	 */
	class AdminKonfirmasi {

		/**
		 * Class constructor.
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'add_meta_boxes', array( $this, 'meta_box_payment_confirmation' ) );
			add_action( 'woocommerce_order_status_changed', array( $this, 'order_processing_handle' ), 10, 3 );
			add_action( 'manage_shop_order_posts_custom_column', array( $this, 'add_content_columns_konfirmasi' ) );
			add_filter( 'manage_edit-shop_order_columns' , array( $this, 'add_header_columns_konfirmasi' ) );
			add_filter( 'manage_edit-shop_order_sortable_columns', array( $this, 'columns_konfirmasi_sort' ) );
			add_filter( 'views_edit-shop_order', array( $this, 'add_payment_confirmation_filter_table' ) );
			add_filter( 'posts_where', array( $this, 'add_where_order_confirmation' ) );
		}

		/**
		 * Create Metabox Konfirmasi Pembayaran to Edit Order Details
		 * 
		 */
		public function meta_box_payment_confirmation() {
			add_meta_box(
		        'payment_confirmation',
		        __( 'Konfirmasi Pembayaran', 'pkp' ),
		        array( $this, 'list_payment_confirmation' ),
		        'shop_order',
		        'normal',
		        'core'
		    );
		}

		/**
		 * Callback to display list of payment confirmation
		 * 
		 * @return html
		 */
		public function list_payment_confirmation() {
			
			global $post;
			
			$order_bacs_confirmation = get_post_meta( $post->ID, 'order_bacs_confirmation' );

			require plugin_dir_path( __FILE__ ) . 'view/view.list-konfirmasi-pembayaran.php';
			
		}

		/**
		 * Send email to customer when order status change to processing if payment method is bacs
		 * 
		 * @param  int $id         
		 * @param  string $old_status 
		 * @param  string $new_status 
		 *          
		 */
		public function order_processing_handle( $id, $old_status, $new_status ) {
    		
    		if ( $new_status != 'processing' ) return;

    		$order = new WC_Order( $id );

    		if ( $order->get_payment_method() != 'bacs' ) return;

    		// send email to customer
    		$option = new HelpersKonfirmasi();
			$user_email = $order->get_billing_email();
			$option->send_single_email( 'payment_received', $user_email, '' );

		}

		/**
		 * Add New Header Columns Konfirmasi Pembayaran to table order
		 * 
		 * @param array $columns
		 */
		public function add_header_columns_konfirmasi( $columns ) {
			$new_columns = array();
			foreach ($columns as $column_name => $column_info) {
		        $new_columns[$column_name] = $column_info;
		        if ('order_total' === $column_name) {
		            $new_columns['confirm_payment'] = __('Konfirmasi', 'pkp');
		        }
		    }
		    return $new_columns;
		}

		/**
		 * Add New Content Columns Konfirmasi Pembayaran to table order
		 * 
		 * @param array $columns
		 */
		public function add_content_columns_konfirmasi( $column ) {
			global $post;
 
		    if ( 'confirm_payment' === $column ) {
		 
		        $order = wc_get_order( $post->ID );

		        if ( $order->get_payment_method() != 'bacs' ) { 
		        	echo '<img src="'. PKP_URL .'/assets/img/confirm_ignored.png" class="confirm_status" title="'. __( 'Tidak butuh konfirmasi pembayaran', 'pkp' ) .'" />'; return; 
		        }

		        $order_bacs_confirmation = get_post_meta( $post->ID, 'order_bacs_confirmation' );
		        $order_status = get_post_status( $post->ID );

		        if ( $order_bacs_confirmation && $order_status == 'wc-pending' ) {
		        	echo '<img src="'. PKP_URL .'/assets/img/confirm_confirmed.png" class="confirm_status" title="'. __( 'Sudah melakukan konfirmasi pembayaran', 'pkp' ) .'" />';
		        } else if ( $order_bacs_confirmation && ( $order_status == 'wc-cancelled' || $order_status == 'wc-failed' ) ) {
		        	echo '<img src="'. PKP_URL .'/assets/img/confirm_cancle.png" class="confirm_status" title="'. __( 'Konfirmasi batal karena order dibatalkan atau pembayaran gagal', 'pkp' ) .'" />';
		        } else if ( $order_bacs_confirmation && ( $order_status == 'wc-processing' || $order_status == 'wc-completed' || $order_status == 'wc-refunded' ) ) {
		        	echo '<img src="'. PKP_URL .'/assets/img/confirm_accept.png" class="confirm_status" title="'. __( 'Konfirmasi Pembayaran telah disetujui', 'pkp' ) .'" />';
		        } else {
		        	echo '<img src="'. PKP_URL .'/assets/img/confirm_pending.png" class="confirm_status" title="'. __( 'Belum melakukan konfirmasi pembayaran', 'pkp' ) .'" />';
		    	}

		    }
		}

		/**
		 * Sorting the Payment Confirmation Columns
		 * 
		 * @param  [type] $columns [description]
		 */
		public function columns_konfirmasi_sort( $columns ) {
			global $wpdb;

		    $custom = array(
		        'confirm_payment' => 'confirm_payment'
		    );

		    return wp_parse_args( $custom, $columns );
		}

		/**
		 * Add Custom link into header table order, to filter data with payment_confirmation and order status is pending and payment method is BACS
		 *
		 * @param array $views
		 */
		public function add_payment_confirmation_filter_table( $views ) {
			// get how many order with payment confirmation requirement
			global $order;
			$customquery = new CustomQuery();
			
			if ( isset( $_GET['payment'] ) && $_GET['payment'] == 'payment_confirmation' ) {
				$is_current = 'current';
			} else $is_current = '';

			$view = '<a href="' . admin_url('edit.php?post_type=shop_order&payment=payment_confirmation') . '" class="'. $is_current .'">Konfirmasi Pembayaran ('.$customquery->get_confirm_payment().')</a>';
			
			$views['payment_confirmation'] = $view;
			
			return $views;
			
		}

		/**
		 * Add where clause into table order if konfirmasi pembayaran selected, then display only order with payment method bacs, status pending and have a payment confirm
		 * 
		 * @param string $where
		 */
		public function add_where_order_confirmation( $where ) {

			if( is_admin() ) {
				
				if ( isset( $_GET['payment'] ) && $_GET['payment'] == 'payment_confirmation' ) {
					$where .= 'AND pkp_posts.ID IN ( SELECT pkp_postmeta.post_id FROM pkp_postmeta LEFT JOIN pkp_postmeta AS m2 ON m2.post_id = pkp_postmeta.post_id WHERE pkp_postmeta.meta_key = \'order_bacs_confirmation\' AND ( m2.meta_key = \'_payment_method\' AND m2.meta_value = \'bacs\' ) AND pkp_posts.post_status = \'wc-pending\' )';
				}
			}
			return $where;
		}

	}

}

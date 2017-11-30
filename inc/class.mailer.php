<?php
/**
 * Admin Emailer Classes
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PaymentConfirmationEmail' ) ) {

	class PaymentConfirmationEmail extends WC_Email {

		private $options;

		public function __construct( $args ) {
			$this->email_setting( $args );
			$this->options = $args;
		}

		public function get_email_setting( $key ) {
			$val = get_option( 'woocommerce_' . $this->options['id'] . '_settings' );
			$unserialize_option = maybe_unserialize( $val );
			
			return $unserialize_option[$key];
		}

		public function email_setting( $args ) {

			$this->id = $args['id'];
			$this->title = $args['title'];
			$this->description = $args['description'];

			$this->heading = apply_filters( 'pkp_email_heading', $args['heading'] );
			$this->subject = apply_filters( 'pkp_email_subject', $args['subject'] );

			$this->recipient = $this->get_option( 'recipient' );

			parent::__construct();
		}

		/**
		 * Trigger to send an email
		 * 
		 * @param  string $email
		 * 
		 * @return boolean
		 */
		public function trigger( $email, $order_id, $order = false ) {
			
			if ( $order_id && ! is_a( $order, 'WC_Order' ) ) {
				$order = wc_get_order( $order_id );
			}

			if ( is_a( $order, 'WC_Order' ) ) {
				$this->object = $order;
			} else {
				$this->object = false;
			}
			$this->recipient  = $email;
			
			if ( ! $this->is_enabled() || ! $this->get_recipient() ) {
				return;
			}
			// woohoo, send the email!
			return $this->send( $this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(), $this->get_attachments() );
		}

		public function get_content_html() {
			ob_start();
			$email_heading = $this->get_heading();
			$order         = $this->object;
			$email_body    = $this->get_email_setting('body_content');

			include apply_filters( 'pkp_email_template_path', PKP_PATH . 'templates/payment-confirmation.php' );
			return ob_get_clean();
		}

		/**
		 * Initialise settings form fields.
		 */
		public function init_form_fields() {
			$this->form_fields = array(
				'enabled' => array(
					'title'         => __( 'Enable/Disable', 'pkp' ),
					'type'          => 'checkbox',
					'label'         => __( 'Enable this email notification', 'pkp' ),
					'default'       => 'yes',
				),
				'subject' => array(
					'title'         => __( 'Subject', 'pkp' ),
					'type'          => 'text',
					'desc_tip'      => true,
					'description'   => sprintf( __( 'Available placeholders: %s', 'pkp' ), '<code>{site_title}, {order_date}, {order_number}</code>' ),
					'placeholder'   => $this->get_default_subject(),
					'default'       => '',
				),
				'heading' => array(
					'title'         => __( 'Email heading', 'pkp' ),
					'type'          => 'text',
					'desc_tip'      => true,
					'description'   => sprintf( __( 'Available placeholders: %s', 'pkp' ), '<code>{site_title}, {order_date}, {order_number}</code>' ),
					'placeholder'   => $this->get_default_heading(),
					'default'       => '',
				),
				'email_type' => array(
	                'title'       => 'Email type',
	                'type'        => 'select',
	                'description' => 'Choose which format of email to send.',
	                'default'     => 'html',
	                'class'       => 'email_type',
	                'options'     => array(
	                    'html'      => 'HTML'
	                )
	            ),
	            'body_content' => array(
					'title'         => __( 'Email Message', 'pkp' ),
					'type'          => 'textarea',
					'desc_tip'      => true,
					'description'   => __( 'Custom email message', 'pkp' ),
					'default'       => '',
				)
			);
		}

	}
}

<?php
/**
 * Admin Notice
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'AdminNotice' ) ) {

	/**
	 *
	 * Class for display admin notice
	 */
	class AdminNotice {

		/**
		 * Constructor.
		 *
		 * @version 1.0.0
		 */
		public function __construct() {

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

}
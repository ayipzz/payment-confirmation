<?php
/**
 * Admin new Email
 *
 * @author WooThemes
 * @package WooCommerce/Templates/Emails/HTML
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php do_action( 'woocommerce_email_header', $email_heading ); ?>

<?php echo $email_body; ?>

<?php if ( $order != false ) { ?>

	<?php do_action( 'woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email ); ?>

<?php } ?>

<?php do_action( 'woocommerce_email_footer' ); ?>

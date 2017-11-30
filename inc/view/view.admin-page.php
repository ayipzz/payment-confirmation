<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<?php settings_errors(); ?>
	
	<!-- Navigation Tab -->
	<div class="nav-tab-wrapper">
	    <a href="?page=payment_confirmation&tab=general" class="nav-tab <?php echo $active_tab == 'general' ? 'nav-tab-active' : ''; ?>"><?php _e( 'General', 'pkp' ); ?></a>
	    <a href="?page=payment_confirmation&tab=notification" class="nav-tab <?php echo $active_tab == 'notification' ? 'nav-tab-active' : ''; ?>"><?php _e( 'Notifikasi', 'pkp' ); ?></a>
	</div>

	<!-- Navigation Content -->
	<div class="content">
		
		<?php if( $active_tab == 'general' ) { ?>

			<p>Welcome to Plugin Konfirmasi Pembayaran</p>

        <?php } else if( $active_tab == 'notification' ) { ?>

			<form method="post" action="options.php">
			
			    <?php settings_fields( 'pkps' ); ?>
			    <?php do_settings_sections( 'pkps' ); ?>
				
				<div class="section_setting">
					<h3 class="title"><?php _e( 'Notifikasi Konfirmasi Gagal', 'pkp' ); ?></h3>
					<table class="setting_form">
						<tr>
							<td><label><b><?php _e( 'Judul', 'pkp' ); ?></b></label></td>
							<td><input type="text" name="notif_failed_title" value="<?php echo esc_attr( get_option('notif_failed_title') ); ?>"></td>
						</tr>
						<tr>
							<td valign="top"><label><b><?php _e( 'Content', 'pkp' ); ?></b></label></td>
							<td><?php wp_editor( get_option('notif_failed_content'), 'notif_failed_content', array('editor_height'=>'100px') ); ?></td>
						</tr>
					</table>
				</div>

				<div class="section_setting">
					<h3 class="title"><?php _e( 'Notifikasi Konfirmasi Berhasil', 'pkp' ); ?></h3>
					<table class="setting_form">
						<tr>
							<td><label><b><?php _e( 'Judul', 'pkp' ); ?></b></label></td>
							<td><input type="text" name="notif_success_title" value="<?php echo esc_attr( get_option('notif_success_title') ); ?>"></td>
						</tr>
						<tr>
							<td valign="top"><label><b><?php _e( 'Content', 'pkp' ); ?></b></label></td>
							<td><?php wp_editor( get_option('notif_success_content'), 'notif_success_content', array('editor_height'=>'100px') ); ?></td>
						</tr>
					</table>
				</div>
			    
			    <?php submit_button(); ?>

			</form>

		<?php } ?>
	</div>
</div>
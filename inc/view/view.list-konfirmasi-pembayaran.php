<div class="postbox payment-confirmation">
    <?php
    if ( $order_bacs_confirmation ) {
        foreach ( $order_bacs_confirmation as $key => $value ) {
            $confirmations = maybe_unserialize( $value );
            foreach ( $confirmations as $confirmation ) {
            ?>
            <div class="payment-confirmation--box">
                <div class="box-col box-col--left">
                    <table class="wp-list-table widefat fixed striped">
                        <tbody>
                            <tr>
                                <td><?php _e( 'Kode Pembayaran', 'pkp' ); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $confirmation['payment_code']; ?></td>
                            </tr>
                            <tr>
                                <td><?php _e( 'Nominal Pembayaran', 'pkp' ); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo wc_price( $confirmation['payment_nominal'] ); ?></td>
                            </tr>
                            <tr>
                                <td><?php _e( 'Tanggal Transfer', 'pkp' ); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $confirmation['transfer_date']; ?></td>
                            </tr>
                            <tr>
                                <td><?php _e( 'Bank Tujuan', 'pkp' ); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $confirmation['destination_bank']; ?></td>
                            </tr>
                            <tr>
                                <td><?php _e( 'Bank yang Digunakan', 'pkp' ); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $confirmation['bank']; ?></td>
                            </tr>
                            <tr>
                                <td><?php _e( 'Atas nama rekening yg digunakan', 'pkp' ); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $confirmation['bank_user_name']; ?></td>
                            </tr>
                            <tr>
                                <td><?php _e( 'Keterangan', 'pkp' ); ?></td>
                            </tr>
                            <tr>
                                <td><?php echo $confirmation['description']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="box-col box-col--right">
                    <img src="<?php echo $confirmation['payment_file']; ?>" alt="Bukti Pembayaran">
                </div>
            </div>  
        <?php
            }
        }
    } else {
        _e( 'Belum ada konfirmasi pembayaran', 'pkp' );    
    }
    ?>
</div>
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
                                <td>Kode Pembayaran</td>
                            </tr>
                            <tr>
                                <td><?php echo $confirmation['payment_code']; ?></td>
                            </tr>
                            <tr>
                                <td>Nominal Pembayaran</td>
                            </tr>
                            <tr>
                                <td><?php echo wc_price( $confirmation['payment_nominal'] ); ?></td>
                            </tr>
                            <tr>
                                <td>Tanggal Transfer</td>
                            </tr>
                            <tr>
                                <td><?php echo $confirmation['transfer_date']; ?></td>
                            </tr>
                            <tr>
                                <td>Bank Tujuan</td>
                            </tr>
                            <tr>
                                <td><?php echo $confirmation['destination_bank']; ?></td>
                            </tr>
                            <tr>
                                <td>Bank yang Digunakan</td>
                            </tr>
                            <tr>
                                <td><?php echo $confirmation['bank']; ?></td>
                            </tr>
                            <tr>
                                <td>Atas nama rekening yg digunakan</td>
                            </tr>
                            <tr>
                                <td><?php echo $confirmation['bank_user_name']; ?></td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
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
        echo 'Belum ada konfirmasi pembayaran';    
    }
    ?>
</div>
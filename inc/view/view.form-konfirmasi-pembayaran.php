<div class="payment-confirmation-content">
    <form action="" name="form-payment-confirmation" id="form-payment-confirmation" method="POST" class="form-payment-confirmation" enctype="multipart/form-data">
        <div class="form-group">
            <label for="order-number"><?php _e( 'Nomor Order', 'pkp' ); ?> <span class="required">*</span></label>
            <input name="order-number" id="order-number" type="text" class="form-control" placeholder="Nomor Order" value="<?php echo @$_POST['order-number']; ?>" required>
        </div>
        <div class="form-group">
            <label for="payment-code"><?php _e( 'Kode Pembayaran', 'pkp' ); ?></label>
            <input name="payment-code" id="payment-code" type="text" class="form-control" placeholder="Kode Pembayaran" value="<?php echo @$_POST['payment-code']; ?>" required>
        </div>
        <div class="form-group">
            <label for="payment-nominal"><?php _e( 'Nominal Pembayaran', 'pkp' ); ?></label>
            <input name="payment-nominal" id="payment-nominal" type="text" class="form-control" placeholder="Nominal Pembayaran" value="<?php echo @$_POST['payment-nominal']; ?>" required>
        </div>
        <div class="form-group">
            <label class="transfer-date"><?php _e( 'Tanggal Transfer', 'pkp' ); ?></label>
            <input type="text" name="transfer-date" id="transfer-date" class="form-control datepicker" value="<?php echo @$_POST['transfer-date']; ?>" aria-invalid="false" placeholder="Tanggal Transfer" required>
            <div class="right-icon-input">
                <span class="fa fa-calendar"></span>
            </div>
        </div>
        <div class="form-group">
            <label for="destination-bank"><?php _e( 'Bank Tujuan Transfer', 'pkp' ); ?></label>
            <select name="destination-bank" id="destination-bank" class="nice-select form-control" value="<?php echo @$_POST['destination-bank']; ?>" required>
                <option value="" selected disabled>List Bank</option>
                <?php
                foreach ( $bacs_account->account_details as $bacs) {
                    echo '<option value="' . $bacs['bank_name'] . '">' . $bacs['bank_name'] . '</option>';
                } 
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="bank"><?php _e( 'Bank yang Digunakan', 'pkp' ); ?></label>
            <input type="text" name="bank" id="bank" class="form-control" placeholder="<?php _e( 'Bank yang Digunakan', 'pkp' ); ?>" value="<?php echo @$_POST['bank']; ?>" required>
        </div>
        <div class="form-group">
            <label for="bank-name"><?php _e( 'Atas Nama Rekening yang Digunakan', 'pkp' ); ?></label>
            <input name="bank-name" id="bank-name" type="text" class="form-control" placeholder="Atas Nama Rekening yang Digunakan" value="<?php echo @$_POST['bank-name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="file-attachment"><?php _e( 'Upload Bukti Pembayaran', 'pkp' ); ?></label>
            <div class="input-group input-group-left-addon">
                <label class="input-group-btn">
                    <span class="input-group-addon">
                        <i class="fa fa-upload" aria-hidden="true"></i> <?php _e( 'Pilih File', 'pkp' ); ?>
                        <input type="file" name="payment-file" id="payment-file" style="display: none;" multiple required>
                    </span>
                </label>
                <input name="file-attachment" type="text" class="form-control" readonly>
            </div>
        </div>
        <div class="form-group">
            <label for="description"><?php _e( 'Keterangan', 'pkp' ); ?></label>
            <textarea name="description" class="form-control" placeholder="Keterangan" required><?php echo @$_POST['description']; ?></textarea>
        </div>
        <div class="form-button">
            <button type="button" class="btn btn-cancel"><?php _e( 'BATAL', 'pkp' ); ?></button>
            <button type="submit" id="submit_payment_confirmation" class="btn btn-primary"><?php _e( 'OK', 'pkp' ); ?></button>
        </div>
        <input type="hidden" name="action" value="payment_confirmation" />
        <?php wp_nonce_field( 'payment-confirmation' ); ?>
    </form>
</div>
<div class="payment-confirmation-content">
    <form action="" name="form-payment-confirmation" id="form-payment-confirmation" method="POST" class="form-payment-confirmation" enctype="multipart/form-data">
        <div class="form-group">
            <label for="order-number"><?php _e( 'Nomor Order', 'pkp' ); ?> <span class="required">*</span></label>
            <input name="order-number" id="order-number" type="text" class="form-control" placeholder="Nomor Order"" required>
        </div>
        <div class="form-group">
            <label for="payment-code"><?php _e( 'Kode Pembayaran', 'pkp' ); ?> <span class="required">*</span></label>
            <input name="payment-code" id="payment-code" type="text" class="form-control" placeholder="Kode Pembayaran" required>
        </div>
        <div class="form-group">
            <label for="payment-nominal"><?php _e( 'Nominal Pembayaran', 'pkp' ); ?> <span class="required">*</span></label>
            <input name="payment-nominal" id="payment-nominal" type="text" class="form-control" placeholder="Nominal Pembayaran" required>
        </div>
        <div class="form-group">
            <label class="transfer-date"><?php _e( 'Tanggal Transfer', 'pkp' ); ?> <span class="required">*</span></label>
            <input type="text" name="transfer-date" id="transfer-date" class="form-control datepicker" aria-invalid="false" placeholder="Tanggal Transfer" required>
            <div class="right-icon-input">
                <span class="fa fa-calendar"></span>
            </div>
        </div>
        <div class="form-group">
            <label for="destination-bank"><?php _e( 'Bank Tujuan Transfer', 'pkp' ); ?> <span class="required">*</span></label>
            <select name="destination-bank" id="destination-bank" class="nice-select form-control" required>
                <option value="" selected disabled>List Bank</option>
                <?php
                foreach ( $bacs_account->account_details as $bacs) {
                    echo '<option value="' . $bacs['bank_name'] . '">' . $bacs['bank_name'] . '</option>';
                } 
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="bank"><?php _e( 'Bank yang Digunakan', 'pkp' ); ?> <span class="required">*</span></label>
            <input type="text" name="bank" id="bank" class="form-control" placeholder="<?php _e( 'Bank yang Digunakan', 'pkp' ); ?>" required>
        </div>
        <div class="form-group">
            <label for="bank-name"><?php _e( 'Atas Nama Rekening yang Digunakan', 'pkp' ); ?> <span class="required">*</span></label>
            <input name="bank-name" id="bank-name" type="text" class="form-control" placeholder="Atas Nama Rekening yang Digunakan" required>
        </div>
        <div class="form-group">
            <label for="file-attachment"><?php _e( 'Upload Bukti Pembayaran', 'pkp' ); ?> <span class="required">*</span></label>
            <div class="input-group input-group-left-addon">
                <label class="input-group-btn">
                    <span class="input-group-addon">
                        <i class="fa fa-upload" aria-hidden="true"></i> <?php _e( 'Pilih File', 'pkp' ); ?>
                        <input type="file" name="payment-file" id="payment-file" style="display: none;" required>
                    </span>
                </label>
                <input name="file-attachment" type="text" class="form-control" readonly>
            </div>
        </div>
        <div class="form-group">
            <label for="description"><?php _e( 'Keterangan', 'pkp' ); ?> <span class="required">*</span></label>
            <textarea name="description" id="description" class="form-control" placeholder="Keterangan" required></textarea>
        </div>
        <div class="form-button">
            <button type="button" class="btn btn-cancel"><?php _e( 'BATAL', 'pkp' ); ?></button>
            <button type="submit" id="submit_payment_confirmation" class="btn btn-primary"><?php _e( 'OK', 'pkp' ); ?></button>
        </div>
        <input type="hidden" name="action" value="payment_confirmation" />
        <?php wp_nonce_field( 'payment-confirmation' ); ?>
    </form>
</div>
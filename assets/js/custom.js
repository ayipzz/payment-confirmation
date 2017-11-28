jQuery(function($){
	$("#submit_payment_confirmation").click(function(){
		if ( $("#order-number").val() == '' ) {

			alert("error : Masukan Order Number");
			$("#order-number").focus();
			return false;

		} else if ( $("#payment-code").val() == '' ) {

			alert("error : Masukan Kode Pembayaran");
			$("#payment-code").focus();
			return false;

		} else if ( $("#payment-nominal").val() == '' ) {

			alert("error : Masukan Nominal Pembayaran");
			$("#payment-nominal").focus();
			return false;

		} else if ( $("#transfer-date").val() == '' ) {

			alert("error : Masukan Tanggal Transfer");
			$("#transfer-date").focus();
			return false;

		} else if ( $("#destination-bank").val() == '' ) {

			alert("error : Pilih Bank Tujuan");
			$("#destination-bank").focus();
			return false;

		} else if ( $("#bank").val() == '' ) {

			alert("error : Masukan Bank Pengirim");
			$("#bank").focus();
			return false;

		} else if ( $("#bank-name").val() == '' ) {

			alert("error : Masukan Atas Nama Rekening yang Digunakan");
			$("#bank-name").focus();
			return false;

		} else if ( $("#payment-file").val() == '' ) {

			alert("error : Upload Bukti Pembayaran");
			$("#payment-file").focus();
			return false;

		} else if ( $("#description").val() == '' ) {

			alert("error : Masukan Keterangan");
			$("#description").focus();
			return false;

		} else {
			return true;
		}
		
		
	});

});
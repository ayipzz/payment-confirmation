jQuery(function($){
	$("#submit_payment_confirmation").click(function(){
		if ( $("#order-number").val() == '' ) {

			alert("Error : Masukan Order Number");
			$("#order-number").focus();
			return false;

		} else if ( $("#payment-code").val() == '' ) {

			alert("Error : Masukan Kode Pembayaran");
			$("#payment-code").focus();
			return false;

		} else if ( $("#payment-nominal").val() == '' ) {

			alert("Error : Masukan Nominal Pembayaran");
			$("#payment-nominal").focus();
			return false;

		} else if ( $("#transfer-date").val() == '' ) {

			alert("Error : Masukan Tanggal Transfer");
			$("#transfer-date").focus();
			return false;

		} else if ( $("#destination-bank").val() == '' ) {

			alert("Error : Pilih Bank Tujuan");
			$("#destination-bank").focus();
			return false;

		} else if ( $("#bank").val() == '' ) {

			alert("Error : Masukan Bank Pengirim");
			$("#bank").focus();
			return false;

		} else if ( $("#bank-name").val() == '' ) {

			alert("Error : Masukan Atas Nama Rekening yang Digunakan");
			$("#bank-name").focus();
			return false;

		} else if ( $("#payment-file").val() == '' ) {

			alert("Error : Upload Bukti Pembayaran");
			$("#payment-file").focus();
			return false;

		} else {
			return true;
		}
		
		
	});

});
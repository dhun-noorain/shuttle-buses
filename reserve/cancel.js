$(document).ready(function () {
	$(".re_cancel").click(function(){
		$(".test_span").html($(this).val());
		$(".cancel_val").val($(this).val());
	});
});
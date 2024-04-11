//개발관련 공통 js
$(document).ready(function(){
	// 숫자만 입력 가능하도록 처리
	$(document).on("keyup", ".numberonly", function () { 
		$(this).val($(this).val().replace(/[^0-9.]/gi, "")); 
	});
	
	$(".engonly").keyup(function(){$(this).val( $(this).val().replace(/[0-9]|[^\!-z\s]/g,"") );} );
})
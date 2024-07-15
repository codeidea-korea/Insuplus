
/*──────────────────────────────────────────────────────────────────────
														document ready - start
───────────────────────────────────────────────────────────────────────*/
$(document).ready(function(){

   

	
	//텝버튼 스크롤 이동
	$.scrollIt({		
		scrollTime: 400,
		topOffset: - $("#header").height(),
		activeClass: 'active',
	});

	scrollMotionTrigger();

	_slide_toggle('#footer .opener');
	

	$(function() {
		$("#_gototop").on("click", function() {
			$("html, body").animate({scrollTop:0}, '500');
			return false;
		});
	});

});
//document ready - end




/*──────────────────────────────────────────────
					브라우저 안쪽 높이(모바일기기포함) 구하기
───────────────────────────────────────────────*/
function setScreenSize() {
	//let vh = window.innerHeight * 0.01;
	let vh = window.innerHeight;
	document.documentElement.style.setProperty('--vh', `${vh}px`);
}


/*──────────────────────────────────
						fadeUp 모션
───────────────────────────────────*/
function scrollMotionTrigger(){
	$(".scrollMotion").each(function(q){
		gsap.to($(this), {
			scrollTrigger: {
				trigger: $(this),
				start: "top bottom",
				end:"bottom center",
				toggleClass: {targets: $(".scrollMotion").eq(q), className: "active"},
				once: true,
				//markers: true,
			}
		});
	});
};


//화면 높이를 body에 --vh로 지정
setScreenSize();
//window.addEventListener('resize', setScreenSize); //아이폰 url바 토글로 인해 높이가 계속 바뀜.




function _slide_toggle(toggle, container, single){	
	$(toggle).on('click',function(){
		var target = $(this).attr('data-target'),
			this_list = $(this).closest('.ftCon');
		if(single != 'single') {
			$(toggle).not(this).removeClass('active');
			$(this).closest('#footerContainer').find('.ftCon').not(this_list).removeClass('active');
		}
		$(this).toggleClass('active');
		$(this).closest('.ftCon').toggleClass('active');		
		$(target).slideToggle(600, 'easeInOutExpo');
		if(single != 'single') {
			$(container).not(target).slideUp(500, 'easeInOutExpo');
		}
	});
};
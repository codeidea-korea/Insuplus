if ( $(window).width() < 768 ) {
	var banner_height = $('.fullwidthbanner .tp-banner-mobile').height() / 2;
}else{
	var banner_height = $('.fullwidthbanner .tp-banner').height() / 2;
}
var icon_height = $('#main_icon').height();
function responsive_style(){
	//$(".blue-bg").css({'top':(banner_height + 59.9) + 'px','padding-top':(icon_height + 40) + 'px'});
	$("#main_plan").addClass('owl-carousel owl-theme');
	//console.log(banner_height, icon_height);
}
function responsive_style_disable(){
	//$(".blue-bg").css({'top':'auto','padding-top':'180px'});
	$("#main_plan").removeClass('owl-carousel owl-theme');
}
$(document).ready(function () {
	if($('.main_latest .status .list_wrap').height() >= 200){
		jQuery('.main_latest .status .list_wrap').addClass('scroll')
		jQuery('.main_latest .status .list_wrap.scroll').marquee({
			duration: 9000,
			gap: 0,
			delayBeforeStart: 0,
			direction: 'up',
			duplicated: true,
			pauseOnHover: true
		});
	}
    var owl = $('#main_plan'),
        owlOptions = {
			loop:true,
			margin:0,
			nav:false,
			items:1,
			responsiveClass:true,
			itemsDesktop : false,
			itemsDesktopSmall : false,
			itemsTablet: true,
			itemsMobile: true,
        };

    if ( $(window).width() < 769 ) {
		responsive_style();
        var owlActive = owl.owlCarousel(owlOptions);
    } else {
		responsive_style_disable();
        owl.addClass('off');
    }

	$(".tp-banner").owlCarousel({
		loop:true,
		margin:0,
		nav:false,
		dots:true,
		autoplay:true,
		autoplayTimeout:6000,
		autoplayHoverPause:false,
		items:1
	});
	$(".tp-banner-mobile").owlCarousel({
		loop:true,
		margin:0,
		nav:false,
		dots:true,
		autoplay:true,
		autoplayTimeout:6000,
		autoplayHoverPause:false,
		items:1
	});
    $(window).resize(function() {
        if ( $(window).width() < 769 ) {
			responsive_style();
            if ( $('#main_plan').hasClass('off') ) {
                var owlActive = owl.owlCarousel(owlOptions);
                owl.removeClass('off');
            }
        } else {
			responsive_style_disable();
            if ( !$('#main_plan').hasClass('off') ) {
                owl.addClass('off').trigger('destroy.owl.carousel');
                owl.find('.owl-stage-outer').children(':eq(0)').unwrap();
            }
        }
    });
});

var $menu = $('.nav-scroll .nav li');
var $contents = $('.tab-content');
var agent = navigator.userAgent.toLowerCase();
var varUA = navigator.userAgent.toLowerCase();
function frame_resize(frame) {
  var h = $(frame).contents().find('body').height();
  $(frame).css({ height: h + 10 + 'px' }).stop;
  //$(frame).animate({'height':h+ 'px'}).stop;
  $(frame).contents().find('body').addClass('iframe-body');
}
window.closeModal = function () {
  $('#pop_modal').modal('hide');
};
function add_people(flen, index) {
  //var objTbl;
  //var objRow;
  //var objCell;
  $('#variablePeople > div.row > div').hide().empty();
  for (i = 0; i < Number(flen); i++) {
    var number = i + 1;
    htmlStr = '';
    htmlStr += '<div class="detail-col-all flex-wrap clearfix">';
    htmlStr += '<h5 class="text-black text-left">동반인(' + number + ')</h5>';
    htmlStr += '<div class="col-xs-7 p-x-0">';
    htmlStr +=
      '<input type="text" class="form-control datepicker" name="add_birth[]" id="add_birth_' +
      number +
      '" title="생년월일 예)20190101" placeholder="생년월일 예)20190101" maxlength="8" />';
    htmlStr += '</div>';
    htmlStr += '<div class="col-xs-5 p-r-0">';
    htmlStr += '<div class="btn-group btn-group-justified">';
    htmlStr +=
      '<a class="btn btn-default" href="javascript:;" onClick="fn_chg_add_gender(this,' + i + ",'M')\">남성</a>";
    htmlStr +=
      '<a class="btn btn-default" href="javascript:;" onClick="fn_chg_add_gender(this,' + i + ",'F')\">여성</a>";
    htmlStr += '</div>';
    htmlStr += '</div>';
    htmlStr += '</div>';
    //$('#variablePeople > div.row > div').hide().empty();
    //$('#variablePeople > div:nth-last-child('+(10-flen)+')').html();
    //$('#variablePeople > div.row > div:nth-child(-n+'+flen+')').show().html(htmlStr);
    //$('#variablePeople > div:nth-child(-n+'+flen+')').show();
    $('#variablePeople > div.row > #add_people_' + number)
      .show()
      .html(htmlStr);
    //return false;
  }
}

function del_file() {
  var objTbl = document.getElementById('variablePeople');
  if (objTbl.rows.length - 1 > 0) {
    objTbl.deleteRow(objTbl.rows.length - 1);
    flen--;
  }
}
jQuery(function ($) {
  $.fn.extend({
    scrollRight: function (val) {
      if (val === undefined) {
        return this[0].scrollWidth - (this[0].scrollLeft + this[0].clientWidth) + 1;
      }
      return this.scrollLeft(this[0].scrollWidth - this[0].clientWidth - val);
    },
  });
  if ($('.table-responsive table').width() > $('.table-responsive').width()) {
    console.log('h_scroll');
    $('.info-wrap .info-box').show();
    $('.table-responsive').prepend(
      $(
        '<div class="table-wrap"><span class="arrow arrow-left hidden"></span><span class="arrow arrow-right"></span></div>'
      )
    );
    $('.table-responsive').on('scroll', function () {
      $('.info-wrap .info-box').animate({ opacity: 0 });
      if ($(this).scrollRight() > 1) {
        $(this).parent().find('.arrow-left').addClass('hidden');
        $(this).parent().find('.arrow-right').removeClass('hidden');
      } else {
        $(this).parent().find('.arrow-left').removeClass('hidden');
        $(this).parent().find('.arrow-right').addClass('hidden');
      }
    });
  } else {
    $('.info-wrap .info-box').hide();
  }
  var table_plan_idx = $('.table-plan .fixed-wrap tfoot > tr').length;
  for (i = 1; i <= table_plan_idx; i++) {
    var table_plan_text_length = $('.table-plan .fixed-wrap tfoot > tr:nth-child(' + i + ') > td').text().length;
    var table_plan_height = $('.table-plan .fixed-wrap tfoot > tr:nth-child(' + i + ') > td').height();
    console.log(table_plan_idx, table_plan_text_length);
    $('.table-plan .scroll-wrap tfoot > tr:nth-child(' + i + ') > td').css({
      height: table_plan_height + 17.75 + 'px',
      'white-space': 'normal',
    });
  }
  $menu.on('click', 'a', function (e) {
    var $target = $(this).parent();
    var idx = $target.index();
    var section = $contents.eq(idx);
    if ($(window).width() > 768) {
      var offsetTop = section.offset().top - 242;
    } else {
      var offsetTop = section.offset().top - 70;
    }
    $('html, body').stop().animate({ scrollTop: offsetTop }, 600);
    return false;
    console.log(offsetTop);
  });
});

$(document).ready(function () {
  jQuery('[data-toggle="tooltip"]').tooltip();
  $('.navbar-default').sticky({ topSpacing: 0 });
  if ($(window).width() > 768) {
    $('.nav-scroll').sticky({ topSpacing: 60 });
  } else {
  }

  $('.scrollup').click(function () {
    $('html, body').animate({ scrollTop: 0 }, 'slow');
    return false;
  });
  jQuery('iframe').load(function () {
    frame_resize(this);
  });
  $('#modal_iframe').load(function () {
    frame_resize(this);
  });
  $('[data-toggle="pop-modal"]').click(function () {
    var link_href = $(this).data('href');
    jQuery('#modal_iframe').attr('src', link_href);
    jQuery('#pop_modal .modal-header h3').text($(this).data('title'));
    jQuery('#pop_modal')
      .find('#modal_document')
      .removeClass('modal-lg')
      .removeClass('modal-md')
      .removeClass('modal-sm')
      .addClass('modal-' + $(this).data('size'));
    $('#pop_modal').modal('show');
    //e.preventDefault();
  });
  $('[data-toggle="alert-modal"]').click(function () {
    jQuery(this).parent().parent().find('.btn').removeClass('btn-theme-bg').addClass('btn-default');
    jQuery(this).toggleClass('btn-default').toggleClass('btn-theme-bg');
    jQuery('#alert_modal .modal-header h3').text($(this).data('title'));
    jQuery('#alert_modal').find('.select').text($(this).data('content'));
    $('#alert_modal').modal('show');
    //e.preventDefault();

    //jQuery('#modal_iframe').contents().find("head").append($("<style type='text/css'>body{background:transparent;margin:30px 30px 30px 30px;}  </style>"));
  });
  $('#card_select .btn').click(function (e) {
    jQuery(this).parent().parent().find('.btn').addClass('btn-outline');
    jQuery(this).toggleClass('btn-outline');
    e.preventDefault();
  });
  /*
	$('#search_plan .list .list_select_wrap').click(function(){
		jQuery(this).parent().toggleClass('active');
		jQuery(this).parent().find('.btn-group .btn').toggleClass('disabled');
	});
	*/
  $('#pop_modal').on('shown.bs.modal', function () {
    frame_resize('#modal_iframe');
    jQuery('#pop_modal').find('iframe.fade').addClass('in');
    jQuery('html').addClass('modal-open-html');
  });
  $('[data-toggle="close-modal"]').click(function () {
    parent.closeModal();
  });
  function all_checked1(sw) {
    var radios = $('#form_agree1 .detail-col-all .radiobox .radio-inline:last-child input[type=radio]');
    for (var i = 0; i < radios.length; i++) {
      radios[i].checked = sw;
      $('#form_agree1 .detail-col-all .radiobox .radio-inline:last-child input[type=radio]').prop('checked', true);
    }
    console.log(radios.length);
  }
  $('#form_agree1_all').click(function () {
    if (this.checked) all_checked1(true);
    else all_checked1(false);
  });
  $('#form_agree1 .detail-col-all .radiobox .radio-inline:first-child input[type=radio]').click(function () {
    $('#radio_1_all').prop('checked', false);
  });
  function all_checked2(sw) {
    var radios = $('#form_agree2 .detail-col-all .radiobox .radio-inline:first-child input[type=radio]');
    for (var i = 0; i < radios.length; i++) {
      radios[i].checked = sw;
      $('#form_agree2 .detail-col-all .radiobox .radio-inline:first-child input[type=radio]').prop('checked', true);
    }
  }
  $('#form_agree2 .detail-col-all .radiobox .radio-inline:last-child input[type=radio]').click(function () {
    $('#radio_2_all').prop('checked', false);
  });
  $('#form_agree2_all').click(function () {
    if (this.checked) all_checked2(true);
    else all_checked2(false);
  });

  $('.datepicker').datepicker({
    dateFormat: 'yy-mm-dd',
    startDate: '0d', // 선택 할 수 있는 가장 빠른 날짜. 이전으로는 선택 불가능 ( d : 일 m : 달 y : 년 w : 주)
    endDate: '6w', // 선택 할 수 있는 가장 빠른 날짜. 이전으로는 선택 불가능 ( d : 일 m : 달 y : 년 w : 주)
    closeText: '닫기',
    autoclose: true,
    prevText: '<i class="ti ti-angle-left"></i>',
    nextText: '<i class="ti ti-angle-right"></i>',
    navigationAsDateFormat: true,
    currentText: '오늘',
    monthNames: [
      '1월(JAN)',
      '2월(FEB)',
      '3월(MAR)',
      '4월(APR)',
      '5월(MAY)',
      '6월(JUN)',
      '7월(JUL)',
      '8월(AUG)',
      '9월(SEP)',
      '10월(OCT)',
      '11월(NOV)',
      '12월(DEC)',
    ],
    monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
    //dayNames: ["일","월","화","수","목","금","토"],
    //dayNamesShort: ["일","월","화","수","목","금","토"],
    dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
    showOtherMonths: true,
    firstDay: 0,
    isRTL: false,
    showMonthAfterYear: true,
    yearSuffix: '',
    changeMonth: true,
    changeYear: true,
    showOn: 'both',
    //buttonImage: '<?=$g4[path]?>/images/icon/calendar.gif',
    buttonText: "<i class='fa fa-calendar'></i>",
    buttonImageOnly: false,
    showButtonPanel: false,
    zIndex: '2',
  });
  var wow = new WOW({
    boxClass: 'wow', // animated element css class (default is wow)
    animateClass: 'animated', // animation css class (default is animated)
    offset: 100, // distance to the element when triggering the animation (default is 0)
    mobile: false, // trigger animations on mobile devices (true is default)
  });
  wow.init();

  $('#variablePeople > div.row > div, #variablePeople').hide();
  $('#select_add_people').change(function () {
    var f = document.fregist;
    if (f.select_add_people.value) {
      add_people(f.select_add_people.value);
      $('#variablePeople').show();
    } else {
      $("input[name='add_gender[]']").val('');
      $('#variablePeople').hide();
    }
  });

  $('[data-toggle=popover]').popover();
  $('[data-toggle=tooltip]').tooltip();
  $('tr[data-toggle="collapse"]').on('click', function () {
    if ($(this).hasClass('active')) {
      $(this).removeClass('active');
    } else {
      $('.tr-collapse .collapse').collapse('hide');
      $('tr[data-toggle="collapse"]').removeClass('active');
      $(this).addClass('active');
    }
  });
  // if (jQuery(window).height() >= jQuery('#footer').offset().top - jQuery(this).scrollTop()) {
  //   $('.pagination.arrow-only').addClass('absolute');
  // } else {
  //   $('.pagination.arrow-only').removeClass('absolute');
  // }
});

$(window).scroll(function () {
  //console.log($(this).scrollTop());
  if (jQuery(this).scrollTop() > 100) {
    jQuery('.scrollup').fadeIn();
  } else {
    jQuery('.scrollup').fadeOut();
  }
  if (jQuery(this).scrollTop() > 258) {
    $('#sidebar').addClass('fixed');
  } else {
    $('#sidebar').removeClass('fixed');
  }
  // if (jQuery(window).height() >= jQuery('#footer').offset().top - jQuery(this).scrollTop()) {
  //   $('.pagination.arrow-only').addClass('absolute');
  // } else {
  //   $('.pagination.arrow-only').removeClass('absolute');
  // }
  var scltop = $(window).scrollTop();
  $.each($contents, function (idx, item) {
    var $target = $contents.eq(idx);
    var i = $target.index();
    if ($target.offset().top - 242 <= scltop) {
      $menu.removeClass('active');
      $menu.eq(idx).addClass('active');
    }
    if (!(200 <= scltop)) {
      $menu.removeClass('active');
    }
  });
});

$(window).resize(function () {
  $('.navbar-collapse').css({ maxHeight: $(window).height() - $('.navbar-header').height() + 'px' });
});
/***********************************************************
 * ACCORDION
 ***********************************************************/
$('.panel-ico a[data-toggle="collapse"]').on('click', function () {
  if ($(this).closest('.panel-heading').hasClass('active')) {
    $(this).closest('.panel-heading').removeClass('active');
  } else {
    $('.panel-heading a[data-toggle="collapse"]').closest('.panel-heading').removeClass('active');
    $(this).closest('.panel-heading').addClass('active');
  }
});

// position: fixed 사용시 ie 떨림 현상 방지
if (navigator.userAgent.match(/Trident\/7\./)) {
  $('body').on('mousewheel', function () {
    event.preventDefault();

    var wheelDelta = event.wheelDelta,
      currentScrollPosition = window.pageYOffset;

    window.scrollTo(0, currentScrollPosition - wheelDelta);
  });
  /*
		$('body').keydown(function(e){
			e.preventDefault();
			var currentScrollPosition = window.pageYOffset;

			switch (e.which){
				case 38: //up
					window.scrollTo(0, currentScrollPosition - 120);
					break;
				case 40: //down
					window.scrollTo(0, currentScrollPosition + 120);
					break;
				default: return;
			}
		});
		*/
}

function menuOpen() {
  $('html, body, header').addClass('fixed');
}

function menuClose() {
  $('html, body, header').removeClass('fixed');
}

function popupOpen(type) {
  var box = $('.popup-box .box[data-name=' + type + ']');

  $('html, body, .popup-box').addClass('fixed');
  box.show();
  box.siblings().hide();
}

function popupClose() {
  $('html, body, .popup-box').removeClass('fixed');
}

$(document).on('click', '.footer-toggle', function () {
  var parents = $(this).closest('li');

  parents.toggleClass('active');
  parents.find('.list-body').stop().slideToggle();
});

$(document).on('change', '.all-check-box input', function () {
  var dataGroup = $(this).attr('data-group');
  var dataText = $(this).attr('data-text');
  var group = $('.check-box[data-group=' + dataGroup + ']');
  if ($(this).prop('checked', true)) {
    group.find('input[value=' + dataText + ']').prop('checked', true);
  }
});

$(document).on('change', '.check-box[data-group] input', function () {
  var dataGroup = $(this).closest('.check-box').attr('data-group');
  var parent = $('.all-check-box input[data-group=' + dataGroup + ']');
  var dataText = parent.attr('data-text');
  var len = $('.check-box[data-group=' + dataGroup + "] input[value!='Y']:checked").length;
  if (len > 0) {
    parent.prop('checked', false);
  }
});

// $(window).scroll(function () {
//   var scl = $(this).scrollTop();
//   var price = $('.price-box').offset().top;

//   if ($('.price-box').length === 0) return;

//   if (scl > price) {
//     $('.price-box').addClass('fixed');
//   } else {
//     $('.price-box').removeClass('fixed');
//   }
// });

///////////////////////////////////////////////////////////////////////////////
// kyle
///////////////////////////////////////////////////////////////////////////////
function applyDatepicker() {
  //input을 datepicker로 선언
  $('.datepicker').datepicker({
    dateFormat: 'yy-mm-dd', // Input Display Format 변경
    closeText: '초기화', // 취소버튼의 명칭
    showButtonPanel: true, // 취소가 가능한 판넬 표시
    ignoreReadonly: true, // readonly 값을 무시
    showOtherMonths: true, // 빈 공간에 현재월의 앞뒤월의 날짜를 표시
    showMonthAfterYear: true, // 년도 먼저 나오고, 뒤에 월 표시
    changeYear: true, // 콤보박스에서 년 선택 가능
    changeMonth: true, // 콤보박스에서 월 선택 가능
    showOn: 'both', // button:버튼을 표시하고,버튼을 눌러야만 달력 표시 ^ both:버튼을 표시하고,버튼을 누르거나 input을 클릭하면 달력 표시
    buttonImage: '/html/pilot/works/asset/images/icon_date.png', //버튼 이미지 경로
    buttonImageOnly: true, // 기본 버튼의 회색 부분을 없애고, 이미지만 보이게 함
    buttonText: '선택', // 버튼에 마우스 갖다 댔을 때 표시되는 텍스트
    yearSuffix: '년', // 달력의 년도 부분 뒤에 붙는 텍스트
    monthSuffix: '월', // 달력의 년도 부분 뒤에 붙는 텍스트
    monthNamesShort: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'], // 달력의 월 부분 텍스트
    monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'], // 달력의 월 부분 Tooltip 텍스트
    dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'], // 달력의 요일 부분 텍스트
    dayNames: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'], // 달력의 요일 부분 Tooltip 텍스트
    // minDate: '-20Y', // 최소 선택일자(-1D:하루전, -1M:한달전, -1Y:일년전)
    // maxDate: '+20Y', // 최대 선택일자(+1D:하루후, -1M:한달후, -1Y:일년후)
    onClose: function () {
      // 취소함수
      if ($(window.event.srcElement).hasClass('ui-datepicker-close')) {
        $(this).val('');
      }
    },
  });
}

function optionGenerator(selector, option) {
  const min = 0;
  const max = 10;
  const html = [];
  const start = option.start || min;
  const end = option.end || max;
  const interval = option.interval || 1;
  const preFix = option.preFix || '';
  const postFix = option.postFix || '';

  for (let i = start; i <= end; i = i + interval) {
    html.push(`<option value="${i}" ${option.defaultValue == i ? 'selected' : ''}>${preFix}${i}${postFix}</option>`);
  }
  if (option.existCover) {
    html.unshift(`<option value="">${option.coverText}</option>`);
  }

  document.querySelector(selector).innerHTML = html.join('');
}
///////////////////////////////////////////////////////////////////////////////

function openLayer(num) {
  $('html').addClass('fixed');
  $('.dim-bg').show().animate({ opacity: '.75' }, 300);
  $('[data-layer="layer0' + num + '"]')
    .show()
    .animate({ opacity: '1' }, 300);
}

function closeLayer(num) {
  $('html').removeClass('fixed');
  $('.dim-bg').animate({ opacity: '0' }, 300, function () {
    $('.dim-bg').hide();
  });
  $('[data-layer="layer0' + num + '"]').animate({ opacity: '0' }, 300, function () {
    $('[data-layer="layer0' + num + '"]').hide();
  });
}

// 데이트피커
$(function () {
  // applyDatepicker();
  // 초기값을 오늘 날짜로 설정
  // $('.datepicker').datepicker('setDate', 'today'); // (-1D:하루전, -1M:한달전, -1Y:일년전), (+1D:하루후, -1M:한달후, -1Y:일년후)

  // 결제수단 선택
  $('.method-box ul li a').on('click', function () {
    $('.method-box ul li').removeClass('on');
    $(this).closest('li').addClass('on');
    console.log(111);
  });
});

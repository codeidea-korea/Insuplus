function toTop() {
  $('html, body').stop().animate({ scrollTop: '0' }, 500);
}

function menuOpen() {
  $('html, body, header').addClass('fixed');
}

function menuClose() {
  $('html, body, header').removeClass('fixed');
}

function popupOpen(type) {
  // Check if the cookie exists
  if (document.cookie.includes(type)) {
    return; // Exit the function if the cookie exists
  }

  var box = $('.popup-box .box[data-name=' + type + ']');

  // 모든 팝업에서 fixed 클래스를 제거합니다.
  $('html, body').removeClass('fixed');
  $('.popup-box').removeClass('fixed');

  // 현재 열리는 팝업의 부모 요소에 fixed 클래스를 추가합니다.
  $('html, body').addClass('fixed');
  box.closest('.popup-box').addClass('fixed');
  
  // $('html, body, .popup-box').addClass('fixed');
  box.show();
  box.siblings().hide();
}

function popupClose() {
  $('html, body, .popup-box').removeClass('fixed');
  $('.popup-box .box').hide();
}

$(document).on('click', '.tab-box a', function () {
  var parents = $(this).closest('li');
  var index = parents.index();

  parents.addClass('active');
  parents.siblings().removeClass('active');
  $('.tab-content-box .content-box').eq(index).addClass('active');
  $('.tab-content-box .content-box').eq(index).siblings().removeClass('active');
});

$(document).on('click', '.footer-toggle', function () {
  var parents = $(this).closest('li');

  parents.toggleClass('active');
  parents.find('.list-body').stop().slideToggle();
});

$(document).on('click', '.tab-fixed-box .tab-body a', function () {
  var parents = $(this).closest('li');
  var pos = $(this).attr('data-position');
  var ps = $(pos).offset().top - $('.tab-fixed-box.fixed').outerHeight();

  parents.addClass('active');
  parents.siblings().removeClass('active');
  $('html, body').stop().animate({ scrollTop: ps }, 500);
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
  var len = $('.check-box[data-group=' + dataGroup + "] input[value!='예']:checked").length;
  if (len > 0) {
    parent.prop('checked', false);
  }
});

// 레이어 팝업
let nowTop = 0;

function openLayer(num) {
  nowTop += window.scrollY;
  $('.dim-bg').show().animate({ opacity: '.75' }, 300);
  $('[data-layer="layer0' + num + '"]')
    .show()
    .animate({ opacity: '1' }, 300);
  document.body.style.overflow = 'hidden';
  document.body.style.position = 'fixed';
  document.body.style.top = `-${nowTop}px`;
  document.body.style.width = '100%';
}

function closeLayer(num) {
  document.body.style.removeProperty('overflow');
  document.body.style.removeProperty('position');
  document.body.style.removeProperty('top');
  document.body.style.removeProperty('width');
  $('.dim-bg').animate({ opacity: '0' }, 300, function () {
    $('.dim-bg').hide();
  });
  $('[data-layer="layer0' + num + '"]').animate({ opacity: '0' }, 300, function () {
    $('[data-layer="layer0' + num + '"]').hide();
  });
  window.scrollTo(0, nowTop);
  nowTop = 0;
}

$(window).scroll(function () {
  var scl = $(this).scrollTop();
  var price_len = $('.price-box').length;
  var tab_len = $('.tab-fixed-box').length;

  if (price_len > 0) {
    var price = $('.price-box').offset().top;
    if (scl > price) {
      $('.price-box').addClass('fixed');
    } else {
      $('.price-box').removeClass('fixed');
    }
  }

  if (tab_len > 0) {
    var tab = $('.tab-fixed-box').offset().top;
    if (scl > tab) {
      $('.tab-fixed-box').addClass('fixed');
    } else {
      $('.tab-fixed-box').removeClass('fixed');
    }
  }
});

$(document).ready(function () {
  // 맨 위로 이동
  $('.top').click(function () {
    $('html, body').animate({ scrollTop: 0 }, 400);
    return false;
  });

  // 스크롤 시 메뉴 변경

  $(window).scroll(function () {
    let scroll = $(window).scrollTop();

    changeMenu(scroll);
  });

  $(window).resize(function () {
    let scroll = $(window).scrollTop();

    changeMenu(scroll);
  });

  function changeMenu(scroll) {
    if ($('.main-box-02').length < 1) return;

    const menuList = ['.main-box-02', '.main-box-03', '.main-box-04', '.main-box-05'];

    for (let i = 0; i < menuList.length; i++) {
      if (scroll > $(menuList[i]).offset().top - 150) {
        $('.tab-body ul li').removeClass('active');
        $('.tab-body ul li').eq([i]).addClass('active');

        if (i < 3) {
          $('.tab-body ul').stop().animate({ scrollLeft: '1000' }, 100);
        }
        if (i === 0) {
          $('.tab-body ul').stop().animate({ scrollLeft: '0' }, 100);
        }
      }
    }
  }
});

// 데이트피커
$(function () {
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
    buttonImage: './asset/images/icon_date.png', //버튼 이미지 경로
    buttonImageOnly: true, // 기본 버튼의 회색 부분을 없애고, 이미지만 보이게 함
    buttonText: '선택', // 버튼에 마우스 갖다 댔을 때 표시되는 텍스트
    yearSuffix: '년', // 달력의 년도 부분 뒤에 붙는 텍스트
    monthSuffix: '월', // 달력의 년도 부분 뒤에 붙는 텍스트
    monthNamesShort: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12'], // 달력의 월 부분 텍스트
    monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'], // 달력의 월 부분 Tooltip 텍스트
    dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'], // 달력의 요일 부분 텍스트
    dayNames: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'], // 달력의 요일 부분 Tooltip 텍스트
    minDate: '-20Y', // 최소 선택일자(-1D:하루전, -1M:한달전, -1Y:일년전)
    maxDate: '+20Y', // 최대 선택일자(+1D:하루후, -1M:한달후, -1Y:일년후)
    onClose: function () {
      // 취소함수
      if ($(window.event.srcElement).hasClass('ui-datepicker-close')) {
        $(this).val('');
      }
    },
  });

  // 초기값을 오늘 날짜로 설정
  $('.datepicker').datepicker('setDate', 'today'); // (-1D:하루전, -1M:한달전, -1Y:일년전), (+1D:하루후, -1M:한달후, -1Y:일년후)
});

$(document).ready(function () {
  // 0830 자세히보기 버튼

  // $('.btn-more').on('click', function () {
  //   $(this).next().slideToggle();
  //   $(this).toggleClass('off');
  // });

  // 결제수단 선택

  $('.method-box ul li a').on('click', function () {
    $('.method-box ul li').removeClass('on');
    $(this).closest('li').addClass('on');
  });

  // 숫자만 입력 가능하도록 처리
  $(document).on('keyup', '.numberonly', function () {
    $(this).val(
      $(this)
        .val()
        .replace(/[^0-9.]/gi, '')
    );
  });

  $('.engonly').keyup(function () {
    $(this).val(
      $(this)
        .val()
        .replace(/[0-9]|[^\!-z\s]/g, '')
    );
  });
});

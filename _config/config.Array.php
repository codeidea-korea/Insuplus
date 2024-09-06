<?
  $Arr_u_level = Array(
    0 => "비회원"
    , 1 => "일반회원"
    , 6 => "보험관리자"
    , 7 => "제휴관리자"
    , 8 => "정산관리자"
    , 9 => "종합관리자"
    , 10 => "전체관리자"
  );

  $Arr_account_type = Array(
    ""
    , "온라인 입금"
    , "신용카드 결제"
    , "계좌이체"
    , "핸드폰 결제"
  );

  $Arr_a_state = Array(
    ""
    , "주문접수"
    , "결제확인"
    , "상품(배송)준비중"
    , "상품발송완료"
    , "상품배송(발송)중"
    , "배송완료"
    , "구입완료"
    , "주문취소"
    , "반품(주문취소)완료"
  );


  $Arr_u_gubun = Array(
    0 => "개인"
    , 1 => "기업"
  );

  $Arr_u_state  = Array(
    0 => "승인대기"
    , 1 => "정상"
    , 2 => "탈퇴"
  );

  $Arr_u_sex    = Array(
    "M" => "남자"
    , "F" => "여자"
  );

  $f_c_part    = Array(
    "1" => "개인"
    , "2" => "법인"
  );

  $Arr_u_birth_luner = Array(
    0 => "양력"
    , 1 => "음력"
  );


  $Arr_u_marriage = Array(
    1 => "미혼"
    , 2 => "기혼"
  );

  // 게시판 secret에 대한 text형식 알림
  $open_num_arr = Array(
    1 => "Y"
    ,2 => "N"
  );

  $ArrHP = Array("010", "011", "016", "017", "018", "019");
  $ArrTEL = Array("02", "031", "032", "033", "041", "042", "043", "051", "052", "053", "054", "055", "061", "062", "063", "064", "070","국번없음");
  $ArrEmail = Array("nate.com","naver.com","gmail.com","daum.net","hanmail.net","msn.com","chollian.net", "dreamwiz.com", "empal.com", "freechal.com", "hananet.net", "hanmir.com", "hotmail.com", "hitel.net", "korea.com", "kornet.net", "lycos.co.kr", "naver.com", "nate.com", "paran.com", "sayclub.com", "yahoo.co.kr");
  $ArrTEL_text = Array("서울", "경기", "인천", "강원", "충남", "대전", "충북", "부산", "울산", "대구", "경북", "경남", "전남", "광주", "전북", "제주", "인터넷전화", "국번없음");

  $Arr_time = Array(
    0 => "09:00 ~ 10:00"
    ,1 => "10:00 ~ 11:00"
    ,2 => "11:00 ~ 12:00"
    ,3 => "12:00 ~ 13:00"
    ,4 => "13:00 ~ 14:00"
    ,5 => "14:00 ~ 15:00"
    ,6 => "15:00 ~ 16:00"
    ,7 => "16:00 ~ 17:00"
    ,8 => "17:00 ~ 18:00"
    ,9 => "18:00 ~ 19:00"
    ,10 => "19:00 ~ 20:00"
    ,11 => "20:00 ~ 21:00"
    ,12 => "21:00 ~ 22:00"
  );

  $days_name = Array(
    0 => "월"
    , 1 => "화"
    , 2 => "수"
    , 3 => "목"
    , 4 => "금"
    , 5 => "토"
    , 6 => "일"
  );
  
  $calendar_opt = "   monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
    dayNamesMin: ['일','월','화','수','목','금','토'],
    weekHeader: 'Wk',
    dateFormat: 'yy-mm-dd', //형식(20120303)
    autoSize: false, //오토리사이즈(body등 상위태그의 설정에 따른다)
    changeMonth: true, //월변경가능
    changeYear: true, //년변경가능
    showMonthAfterYear: true, //년 뒤에 월 표시
    showOtherMonths: true, // 다른달 보여주기
    selectOtherMonths: true, // 다른달 선택가능
    buttonImageOnly: true, //이미지표시
    buttonText: '달력선택', //버튼 텍스트 표시
    buttonImage: '/admin/images/a_btn_cal.gif', //이미지주소
    showOn: 'both', //엘리먼트와 이미지 동시 사용(both,button)
    yearRange: '2018:2040' //1990년부터 2020년까지
  };
  $('.calendar').datepicker(clareCalendar);
  $('img.ui-datepicker-trigger').attr('style','margin-left:5px; vertical-align:middle; cursor:pointer;'); //이미지버튼 style적용
  $('#ui-datepicker-div').hide(); //자동으로 생성되는 div객체 숨김
  ";

  //   $Arr_plan_cd = Array(
  //       0 => "없음"
  //     ,1 => "레드"
  //     ,2 => "옐로우"
  //     ,3 => "그린"
  //     ,4 => "블루"
  //     ,5 => "골드"
  // );
    
  //플랜 컬러명칭
  // $Arr_plan_cd_css = Array(
  //         0 => ""
  //     ,1 => "red"
  //     ,2 => "yellow"
  //     ,3 => "green"
  //     ,4 => "blue"
  //     ,5 => "gold"
  // );

  // 20230911 Be changed by kyle
  $Arr_plan_cd = Array(
    0 => "없음"
    ,1 => "Lv1"
    ,2 => "Lv2"
    ,3 => "Lv3"
    ,4 => "Lv4"
    ,5 => "Lv5"
  );
    
  //플랜 컬러명칭
  $Arr_plan_cd_css = Array(
    0 => ""
    ,1 => "Lv1"
    ,2 => "Lv2"
    ,3 => "Lv3"
    ,4 => "Lv4"
    ,5 => "Lv5"
  );

  $Arr_chk_period = Array(
    "Y" => "단기간"
    ,"N" => "장기간"
  );
  
  $nation_arr = array("US"=>"미국","EU"=>"유럽","JP"=>"일본","CN"=>"중국","CA"=>"캐나다","PH"=>"동남아시아","AU"=>"호주","ETC"=>"기타");


  $Arr_img_plus = Array(
    "A" => "<img src='../images/ic-small-logo.svg' align='absmiddle' alt='' class='' />"
    ,"B" => "<img src='../images/ic-small-logo02.png' align='absmiddle' alt='' class='' />"
    ,"C" => "<img src='../images/ic-small-logo02.png' align='absmiddle' alt='' class='' />"
    ,"D" => "<img src='../images/logo_flyingdoctors.png' align='absmiddle' alt='' class='' />"
    ,"N" => ""
  ); 
  
  // $Arr_txt_plus = Array(
  //   "A" => "A타입"
  //   ,"B" => "B타입"
  // }

  $Arr_txt_plus = Array(
    "A" => "A타입"
    ,"B" => "B타입"
    ,"C" => "인슈플러스"
    ,"D" => "플라잉닥터스"
    ,"E" => "플라잉닥터스B"
    ,"N" => "없음"
  );
  
  $status_arr = array("W"=>"대기","C"=>"확인중","A"=>"답변완료"); //qna 상태값
  $charge_type_arr = array("1"=>"후청구","2"=>"GOP"); //청구유형
  $charge_status_arr = array("1"=>"접수완료","2"=>"서류마미","3"=>"지급완료","4"=>"지급불가"); //청구상태
  $gop_status_arr = array("1"=>"신청완료","2"=>"Verification","3"=>"GOP 발송","4"=>"병원비지불","5"=>"보험사청구","6"=>"환급완료","7"=>"환급불가"); //GOP상태

  $Arr_group_join_status = Array(
    "N" => "견적"
    ,"W" => "입금대기"
    ,"Y" => "가입완료"
  );

  $Arr_option_group_value_of_insplus = Array(
    0 => "의료·여행편의 지원",
    1 => "건강검진",
    2 => "긴급이후송",
  );
  
  $Arr_partner_type = Array(
    "only" => "단독",
  );
?>
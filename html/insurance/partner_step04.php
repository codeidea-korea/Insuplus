<?php
include '../_include/_header_partner.html';
include_once $_SERVER['DOCUMENT_ROOT'] . '/_config/lib.php';
include $_SERVER['DOCUMENT_ROOT'] . '/_config/Mobile_Detect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/_config/Func.insurance.php';  // 추가
$detect = new Mobile_Detect;
?>
<style>
  /* 선택된 행의 배경색을 검은색으로 변경하는 CSS */
  .table-type01 tbody a.selected {
    background:#DC3347 !important; color:#fff;
  }
  /* 비활성화된 버튼에 대한 스타일링 */
  .btn.disabled {
    opacity: 0.5; /* 흐릿하게 만듦 */
    cursor: not-allowed; /* 클릭 불가능하게 표시 */
  }
</style>
<section> 
  <div class="container">
    <div class="white-box middle">
      <form>
        <div class="form-box">
          <div class="form-content">
            <div class="name-box">
              <small>가입상품</small>
              <strong>&nbsp;</strong>
            </div>
            <div class="title-box mt24">
              <h3>계약 정보</h3>
            </div>
            <div class="table-form-box" id="customer">
              <ul>
                <li>
                  <div class="table-head w200">
                    <strong>가입기간</strong>
                  </div>
                  <div class="table-body"></div>
                </li>
                <li>
                  <div class="table-head w200">
                    <strong>출국국가</strong>
                  </div>
                  <div class="table-body"></div>
                </li>
                <li>
                  <div class="table-head w200">
                    <strong>이메일</strong>
                  </div>
                  <div class="table-body"></div>
                </li>
                <li>
                  <div class="table-head w200">
                    <strong>휴대폰 번호</strong>
                  </div>
                  <div class="table-body"></div>
                </li>
                <li>
                  <div class="table-head w200">
                    <strong>상품금액</strong>
                  </div>
                  <div class="table-body"></div>
                </li>
                <li class="space">
                  <div class="table-head w200">
                    <strong>가입자 정보</strong>
                  </div>
                  <div class="table-body py24 py-lg-12"></div>
                </li>
              </ul>
            </div>
            <div class="title-box mt24" id="coupon_title" style="display: none;">
              <h3>할인 정보</h3>
            </div>
            <div class="coupon-box" id="coupon_ui" style="display: none;">
                <div class="coupon-inner">
                    <a href="javascript:void(0)" onClick="openCoupon(2)" class="wfull ml0">할인 쿠폰 다운로드</a>
                </div>
                <div class="coupon-inner mt10">
                    <a href="javascript:void(0)" onClick="openCoupon(1)" class="wfull ml0">할인 쿠폰 선택</a>
                </div>
                <p>※ 쿠폰은 중복사용되지 않습니다. 다인가입 시 합산한 금액에서 할인이 적용됩니다.</p>
            </div>
          </div>
        </div>
        <div class="form-box" id="payment-info">
          <div class="form-content">
            <div class="total-price-box">
              <div class="single-row">
                <div class="name">결제금액</div>
                <div class="amount">0원</div>
              </div>
              <div class="multi-row" id="multi-row-1">
                <div class="row">
                  <div class="name">여행자보험</div>
                  <div class="amount">0원</div>
                </div>
                <div class="row">
                  <div class="name">의료지원</div>
                  <div class="amount">0원(서비스 이용권 혜택가)</div>
                </div>
                <div class="row">
                  <div class="name">긴급이후송</div>
                  <div class="amount">0원(서비스 이용권 혜택가)</div>
                </div>
              </div>
              <div class="multi-row" id="multi-row-2">
                <div class="row">
                  <div class="name">할인금액</div>
                  <div class="amount">0원</div>
                </div>
              </div>
            </div>
            <div class="title-box mt24">
              <h3>결제방법</h3>
            </div>
            <div class="method-box mt15">
              <ul>
                <li class="card"><a href="javascript:void(0)">신용카드</a></li>
                <li class="phone"><a href="javascript:void(0)">휴대폰 결제</a></li>
                <li class="transfer"><a href="javascript:void(0)">실시간계좌이체</a></li>
                <li class="account"><a href="javascript:void(0)">가상계좌</a></li>
              </ul>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</section> 

<!-- 레이어 팝업 -->

<div class="dim-bg" style="display: none; opacity: 0"></div>
<div class="layer-container" style="display: none; opacity: 0" data-layer="layer01">
  <div class="layer-box">
    <h3 class="layer-title">쿠폰</h3>
    <div class="table-wrap">
      <table class="table-type01">
        <colgroup>
          <col width="70%" />
          <col width="10%" />
          <col width="10%" />
          <col width="10%" />
        </colgroup>
        <thead>
          <tr>
            <th>쿠폰명/사용가능 기간</th>
            <th>할인</th>
            <th>최대할인금액</th>
            <th>선택</th>
          </tr>
        </thead>
        <tbody id="coupon_list"></tbody>
      </table>
      <div class="button-box flex-tc">
        <button type="button" class="btn btn-gray btn-s mt15" onClick="applyCoupon()">쿠폰 적용</button>
        <script>
          // 이벤트 위임 방식으로 적용
          document.querySelector('#coupon_list').addEventListener('click', function(event) {
            const a = event.target.closest('a');  // 클릭한 요소의 가장 가까운 a 찾기
            if (a) {
              a.classList.toggle('selected'); // 선택된 행 토글
            }
          });

          function applyCoupon() {
            // 선택된 모든 tr 요소에서 data-code 값을 추출하여 배열로 저장
            const selectedRows = document.querySelectorAll('#coupon_list tr a.selected');
            if (selectedRows.length === 0) {
              alert('적용할 쿠폰을 선택해 주세요.');
              return;
            }

            const couponSeqs = Array.from(selectedRows).map(row => {
              const buttonElement = row.getAttribute('data-code');
              // a[data-code]가 존재할 경우만 data-code 값을 가져옴
              return buttonElement ? buttonElement : null;
            }).filter(value => value !== null); // null 값은 제외

            if (couponSeqs.length === 0) {
              alert('쿠폰 정보를 찾을 수 없습니다.');
              return;
            }

            selCoupon(couponSeqs, 1);  // 배열로 selCoupon 함수에 전달
          }
        </script>
      </div>
    </div>
    <button type="button" class="btn-close" data-layer-btn="layer01" onClick="closeCoupon(1)">
      <span class="tts">팝업 닫기</span>
    </button>
  </div>
</div>
<div class="layer-container" style="display: none; opacity: 0" data-layer="layer02">
  <div class="layer-box">
    <h3 class="layer-title">쿠폰 다운로드</h3>
    <div class="table-wrap">
      <table class="table-type01">
        <colgroup>
          <col width="70%" />
          <col width="10%" />
          <col width="10%" />
          <col width="10%" />
        </colgroup>
        <thead>
          <tr>
            <th>쿠폰명/사용가능 기간</th>
            <th>할인</th>
            <th>최대할인금액</th>
            <th>받기</th>
          </tr>
        </thead>
        <tbody id="download_coupon_list"></tbody>
      </table>
      <div class="button-box flex-tc">
        <button type="button" class="btn btn-gray btn-s mt15" onClick="closeCoupon(2)">닫기</button>
      </div>
    </div>
    <button type="button" class="btn-close" data-layer-btn="layer01" onClick="closeCoupon(2)">
      <span class="tts">팝업 닫기</span>
    </button>
  </div>
</div>
<div id="act_div"></div>

<!-- // 레이어 팝업 -->
<!-- 이니시스 표준결제 js -->
<? if (SERVER_CHECK == 'DEV') { ?>
  <script language="javascript" type="text/javascript" src="https://stgstdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
<? } else if (SERVER_CHECK == 'REAL') { ?>
  <script language="javascript" type="text/javascript" src="https://stdpay.inicis.com/stdjs/INIStdPay.js" charset="UTF-8"></script>
<? } ?>
<script src="./js/swiper.js?a=1"></script>
<script src="./js/ehd-object.js"></script>
<script>
  const clientKey = '<?=TOSS_CLIENT_KEY?>'; // 클라이언트 키
  const tossPayments = TossPayments(clientKey)
  function generateCustomerForms() {
    const customer = EHDObject.customer;
    const companions = EHDObject.companions;
    const list = Array.from(document.querySelectorAll('#customer li'));
    const totalPerson = 1 + (companions?.length || 0);
    const discount = customer.discount > 0 ? customer.salePrice : '0';

    list.forEach((el, idx) => {
      let html = [];
      switch (idx) {
        case 0: // 가입기간
          html.push(` <b class="tl">${customer.departureDate} ${customer.departureTime}시 `);
          html.push(` ~ ${customer.arrivalDate} ${customer.arrivalTime}시 `);
          html.push(` <br class="is-m" />( ${customer.dayPeriod}일 / ${totalPerson}명 )</b> `);
          break;
        case 1: // 출국국가
          html.push(` <b class="tl"> ${customer.nationname} </b> `);
          break;
        case 2: // email
          html.push(` <b class="tl"> ${customer.email} </b> `);
          break;
        case 3: // cellphone
          const phoneNumber = customer.cellphone.replace(/(\d{3})(\d{4})(\d{4})/, '$1-$2-$3')
          html.push(` <b class="tl"> ${phoneNumber} </b> `);
          break;
        case 3: // price
          html.push(` <b class="tl ft-blue"> ${customer.price} 원</b> `);
          break;
        case 4: // cellphone
          html.push(` <b class="tl"> ${customer.totalPrice.toLocaleString('ko-KR')} 원</b> `);
          break;
        case 5: // person
          html.push(` <p> `);
          html.push(`   <span> ${customer.name} </span> `);
          html.push(`   <span> ${customer.birth} </span> `);
          html.push(`   <span> ${customer.age}세 / ${customer.gender == 'M' ? '남' : '여'} </span> `);
          html.push(`   <b class="ft-blue">(${customer.price.toLocaleString('ko-KR')}원)</b> `);
          html.push(` </p> `);
          if (Array.isArray(companions) && companions.length > 0) {
            companions.forEach(c => {
              html.push(` <p> `);
              html.push(`   <span> ${c.name} </span> `);
              html.push(`   <span> ${c.birth} </span> `);
              html.push(`   <span> ${c.age}세 / ${c.gender == 'M' ? '남' : '여'} </span> `);
              html.push(`   <b class="ft-blue">(${c.price.toLocaleString('ko-KR')}원)</b> `);
              html.push(` </p> `);
            })
          }
          break;
      }
      el.querySelector('.table-body').innerHTML = html.join('');
    });
    document.querySelector('div.name-box > strong').textContent = EHDObject.selectedPlan.ins_plan_name;
    // document.querySelector('#payment-info p > b').textContent = `${customer.totalPrice.toLocaleString('ko-KR')} 원`;
    // document.querySelector('#payment-info p > small').textContent = `${discount.toLocaleString('ko-KR')} 원 할인`;
    // 결제 금액 업데이트
    document.querySelector('#payment-info .single-row .amount').textContent = `${customer.totalPrice.toLocaleString('ko-KR')} 원`;
    // 여행자보험 금액 업데이트
    // yjhdev
    document.querySelector('#payment-info #multi-row-1 .row:nth-child(1) .amount').textContent = `${customer.totalPrice.toLocaleString('ko-KR')} 원`;

  }
  window.addEventListener('load', (e) => {
    if (!EHDObject || !EHDObject.selectedPlan) {
      location.href = './renewal_step01.php';
      return;
    }

    generateCustomerForms();
    // yjhdev

    if(EHDObject.selectedPartnership[3] === 'rda' || EHDObject.depth0.name === "[B2B]농촌진흥청"){
        $("#payment-info .multi-row").hide();
        $("#multi-row-1").hide();
        $("#multi-row-2").hide();
    }else if(EHDObject.selectedPartnership[3] ==='surecare' || EHDObject.depth0.name==='[제휴] 슈어케어VIP'){
        $("#coupon_ui").css('display','block');
        $("#coupon_title").css('display','block');
        $("#multi-row-1").hide();
        $("#multi-row-2").show();
    }else if(EHDObject.selectedPartnership[3] ==='eyagi' || EHDObject.depth0.name==='[제휴] 슈어케어VIP'){
        $("#coupon_ui").css('display','block');
        $("#coupon_title").css('display','block');
        $("#multi-row-1").hide();
        $("#multi-row-2").show();
    }else if(EHDObject.selectedPartnership[3]  === 'myshop' || EHDObject.depth0.name === "[제휴] 마이쇼퍼케어"){
        $("#multi-row-1").show();
        $("#multi-row-2").hide();
    }
    // event handlers
    //document.querySelector('div.coupon-box a:nth-child(2)').addEventListener('click', (e)=>alert('코드적용'));
    document.querySelector('#payment-info li.card').addEventListener('click', (e) => chk_submit4('Card'));
    document.querySelector('#payment-info li.phone').addEventListener('click', (e) => chk_submit4('HPP'));
    document.querySelector('#payment-info li.account').addEventListener('click', (e) => chk_submit4('Vbank'));
    document.querySelector('#payment-info li.transfer').addEventListener('click', (e) => chk_submit4('transfer'));
    
});

  function getFormInfo() {
    const formData = new FormData();
    formData.append('PR_SEQ', EHDObject.selectedPlan.pr_cd);
    formData.append('plan_seq', EHDObject.selectedPlan.plan_seq);
    formData.append('s_date', EHDObject.customer.departureDate); // 기간 시작
    formData.append('s_date_time', EHDObject.customer.departureTime); // 기간 시작 시간
    formData.append('e_date', EHDObject.customer.arrivalDate); // 기간 종료
    formData.append('e_date_time', EHDObject.customer.arrivalTime); // 기간 종료 시간
    formData.append('gender', EHDObject.customer.gender); // 가입자 성별
    formData.append('birth', EHDObject.customer.birth.replaceAll('-', '')); // 가입자 생일
    formData.append('user_name', EHDObject.customer.name); // 가입자 이름
    formData.append('en_name', EHDObject.customer.nameen); // 영문여권 이름
    formData.append('user_rnumber', EHDObject.customer.num2); // 가입자 주민등록번호
    formData.append('user_hp', EHDObject.customer.cellphone); // 가입자 연락처
    formData.append('email', EHDObject.customer.emailid); // 가입자 이메일 앞
    formData.append('email2', EHDObject.customer.emailaddress); // 가입자 이메일 앞
    formData.append('c_name', EHDObject.customer.nationname); // 여행국 이름
    formData.append('c_code', EHDObject.customer.nationcode); // 여행국 코드
    formData.append('en_secur', EHDObject.customer.nameen ? 'Y' : ''); // 영문여권 여부
    formData.append('purpose', EHDObject.selectedPlan.purpose); // 여행타입
    formData.append('t_amt', EHDObject.customer.price); // 총금액
    formData.append('service_amt', EHDObject.customer.sPrice); // 개별 서비스 금액
    formData.append('select_add_people', EHDObject.companions.length); // 동반인명수
    formData.append('recommend_cd', EHDObject.customer.recommendCode ? EHDObject.customer.recommendCode : 0); //추천인 코드
    formData.append('cp_cd', EHDObject.customer.couponCode ? EHDObject.customer.couponCode : 0); //쿠폰 코드
    formData.append('is_abroad_resident', EHDObject.customer.is_abroad_resident); // 해외거주 여부
    formData.append('join_ch', EHDObject.selectedPartnership ? EHDObject.selectedPartnership.partnership_seq : null); // 제휴사 코드

    if(EHDObject.selectedPartnership[3] ==='surecare' || EHDObject.selectedPartnership[3] ==='eyagi'){
        formData.append('depth0', EHDObject.depth0 ? EHDObject.depth0.code : ''); // 카테고리 코드
        formData.append('depth1', EHDObject.depth1 ? EHDObject.depth1.code : ''); // 카테고리 코드
        formData.append('depth2', EHDObject.depth2 ? EHDObject.depth2.code : ''); // 카테고리 코드
        formData.append('depth3', EHDObject.depth3 ? EHDObject.depth3.code : ''); // 카테고리 코드
    }


    EHDObject.companions.forEach((item, idx) => {
      formData.append('add_gender[]', item.gender);
      formData.append('add_birth[]', item.birth.replaceAll('-', ''));
      formData.append('add_user_name[]', item.name);
      formData.append('add_rnumber[]', item.num2);
      formData.append('add_en_secur[]', item.nameen ? 'Y' : '');
      formData.append('add_en_name[]', item.nameen);
    });

    let survey = EHDObject.customer.survey
    for (let i = 0; i < Object.keys(survey).length; i++) {
      formData.append('pr_notice[]', survey[i]);
      formData.append('pr_notice_a[]', survey[i]);
    }
    formData.append('gopaymethod', EHDObject.customer.paymethod); // 결제방식
    return formData;
  }

  function chk_submit4(pay_type) {

const isMobile = <?= $detect->isMobile() ? 'true' : 'false' ?>;

<? if ($detect->isMobile()) { ?>
  if (pay_type == "Card") {
    pay_type = "wcard";
  } else if (pay_type == "HPP") {
    pay_type = "mobile";
  } else if (pay_type == "Vbank") {
    pay_type = "vbank";
  } else if (pay_type == "Transfer") {
    pay_type = "transfer";
    }
<? } ?>
if (!pay_type) {
  alert("결제방식을 선택해 주세요.");
  return;
}


// if(EHDObject.customer.cellphone === '01085634063' ||EHDObject.customer.cellphone === '01042241027' ||EHDObject.customer.cellphone === '01049775976' || EHDObject.customer.cellphone === '01038585916' || EHDObject.customer.cellphone === '01054405414' || EHDObject.customer.cellphone === '01020493619'){
//     if(pay_type == 'HPP' || pay_type == 'mobile'){
//       alert('휴대폰 결제는 현재 서비스 점검으로 이용이 어렵습니다.');
//       return;
//     } 
//     }


const PAY_TYPE_MAP = {
    'Card': '카드',
    'HPP': '휴대폰',
    'Vbank': '가상계좌',
    'wcard' : '카드',
    'mobile' : '휴대폰',
    'vbank' : '가상계좌',
    'transfer' : '계좌이체'
};

const toss_pay_type = PAY_TYPE_MAP[pay_type];
const fd= getFormInfo();

const protocol = window.location.protocol;
const domain = window.location.hostname;   


let port = window.location.port;
port ? port=":"+port : ''; 
console.log(toss_pay_type)    
$.ajax({
  url: './renewal_step04_toss_ajax.php',
  method: 'POST',
  data: fd,
  cache: false,
  contentType: false,
  processData: false,
  success: function(data) {
    let jsonRes = JSON.parse(data);
    const tossPayments = TossPayments(clientKey);

    // 토스페이먼츠 콘솔에 디버깅용 로그 추가
    // console.log("isMobile:", isMobile);
    // console.log("결제 요청 파라미터:", {
    //   amount: jsonRes.amount,
    //   orderId: jsonRes.order_id,
    //   orderName: jsonRes.order_name,
    //   customerName: jsonRes.customer_name,
    //   customerEmail: jsonRes.customer_email,
    //   customerMobilePhone: jsonRes.user_hp,
    //   successUrl: `${protocol}//${domain}${port}/html/insurance/payment_success.php`,
    //   failUrl: `${protocol}//${domain}${port}/html/insurance/payment_fail.php`
    // });

    try {
 
        // PC에서는 Promise 방식 사용 가능
        tossPayments.requestPayment(toss_pay_type, {
          amount: jsonRes.amount,
          orderId: jsonRes.order_id,
          orderName: jsonRes.order_name,
          customerName: jsonRes.customer_name,
          customerEmail: jsonRes.customer_email,
          customerMobilePhone: jsonRes.user_hp,
          validHours: 24,
          successUrl: `${protocol}//${domain}${port}/html/insurance/payment_success.php`,
          failUrl: `${protocol}//${domain}${port}/html/insurance/payment_fail.php`
      })
      
    } catch (e) {
      console.error("토스페이먼츠 초기화 오류:", e);
      alert("결제 초기화 중 오류가 발생했습니다");
    }
  }
});

// }else{


// EHDObject.customer.paymethod = pay_type;
// //var params = jQuery(formData).serialize();
// var request = $.ajax({
//   url: "./renewal_step04_ajax.php",
//   type: "POST",
//   data: getFormInfo(),
//   cache: false,
//   contentType: false,
//   processData: false,
//   success: function(result) {
//     if (result) {
//       $("#act_div").html(result);
//       setTimeout(function() {
//         <? if ($detect->isMobile()) { ?>
//           on_web();
//         <? } else { ?>
//           inipay();
//         <? } ?>


//       }, 1000);
//     }
//   },
//   error: function(xhr, status, error) {
//     alert("AJAX실패. 상품변경에 따른정보를 가져오는데 실패하였습니다. 관리자에게 문의하십시오.");
//     return false;
//   }
// });
// request.done(function(result) {});
// }

}


  function inipay() { //pc결제
    INIStdPay.pay('SendPayForm_id');
  }

  window.name = "BTPG_CLIENT";
  var width = 330;
  var height = 480;
  var xpos = (screen.width - width) / 2;
  var ypos = (screen.width - height) / 2;
  var position = "top=" + ypos + ",left=" + xpos;
  var features = position + ", width=320, height=440";

  function on_web() { //모바일결제

    var order_form = document.SendPayForm_id;
    var paymethod = order_form.paymethod.value;

    //	var wallet = window.open("", "BTPG_WALLET", features);
    //
    //	if (wallet == null)
    //	{
    //
    //		if ((webbrowser.indexOf("Windows NT 5.1")!=-1) && (webbrowser.indexOf("SV1")!=-1))
    //		{    // Windows XP Service Pack 2
    //			alert("팝업이 차단되었습니다. 브라우저의 상단 노란색 [알림 표시줄]을 클릭하신 후 팝업창 허용을 선택하여 주세요.");
    //		}
    //		else
    //		{
    //			alert("팝업이 차단되었습니다.");
    //		}
    //		return false;
    //	}
    //	order_form.target = "BTPG_WALLET";
    order_form.action = "https://mobile.inicis.com/smart/" + paymethod + "/";
    order_form.submit();
  }

//쿠폰 목록 가져오기
  function getCouponList(num) {
    if(num === 1){ // 사용가능한 쿠폰 조회
      let data = EHDObject.customer;
      let url = './pop_get_coupon_list.php?usr_cd=' + encodeURIComponent(data.cellphone);
      let request = $.ajax({
        type: "POST",
        data: getFormInfo(),
        url: url,
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
          if (result.success == "1") {
            let couponList = result.list;
            let tbody = '';
            couponList.forEach((item, idx) => {
              let date = item.start_date + '~' + item.end_date;
              let unit = item.service_fee_discount_applied == 'F' ? '원' : '%';
              let temp_discount = '';
              let insurance_max_discount_amount = 0;
              insurance_max_discount_amount = item.insurance_max_discount_amount ? Number.parseFloat(item.insurance_max_discount_amount)*10000 : 0;
              let service_fee_max_discount_amount = 0;
              service_fee_max_discount_amount = item.service_fee_max_discount_amount ? Number.parseFloat(item.service_fee_max_discount_amount)*10000 : 0;
              if(item.service_fee_discount_applied == 'F') {
                temp_discount = Number.parseFloat(item.temp_discount).toFixed().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '원';
                max_amount = Number.parseFloat(item.temp_discount).toFixed().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '원';
              } else {
                temp_discount = item.temp_discount + '%';
                if(item.temp_discount && item.insurance_discount_applied === "N" && item.service_fee_discount_applied === "N" ) { //구쿠폰은 최대할인 금액 3만원
                  max_amount = Number.parseFloat(30000).toFixed().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '원';
                } else {
                  max_amount = Number.parseFloat(insurance_max_discount_amount + service_fee_max_discount_amount).toFixed().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + '원';
                }
              }
              let row = '<tr> \
                    <td class="common-txt01">' + item.subject + '</td> \
                    <td class="bb-on" rowspan="2">' + temp_discount + '</td> \
                    <td class="bb-on" rowspan="2">' + max_amount + '</td> \
                    <td class="bb-on" rowspan="2"> \
                      <div class="button-box flex-tc"> \
                        <a href="javascript:void(0)" class="btn btn-white btn-xs" data-status="'+item.duplicate_status_yn+'" data-code="'+item.seq+'" onclick="isDuplicateCoupon(this)">선택</a> \
                      </div> \
                    </td> \
                  </tr> \
                  <tr> \
                    <td class="bb-on">' + date + '</td> \
                  </tr>';
              tbody += row;
            });
            $('#coupon_list').html(tbody);
          } else {
            let row = '<tr><td class="bb-on" colspan="4">등록된 쿠폰이 없습니다.</td></tr>';
            $('#coupon_list').html(row);
          }
        },
        error: function(xhr, status, error) {
          alert("AJAX실패. 상품변경에 따른정보를 가져오는데 실패하였습니다. 관리자에게 문의하십시오.");
          return false;
        }
      });
    } else if(num === 2) { //쿠폰 다운로드
      let data = EHDObject.customer;
      let url = './pop_get_download_coupon_list.php?usr_cd=' + encodeURIComponent(data.cellphone);
      let request = $.ajax({
        type: "POST",
        data: getFormInfo(),
        url: url,
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            // console.log(result)
          if (result.success == "1") {
            let couponList = result.list;
            let tbody = '';
            couponList.forEach((item, idx) => {
              let date = item.expire_date_s + '~' + item.expire_date_e;
              let unit = item.service_fee_discount_applied == 'F' ? '원' : '%';
              let discount = 0;
              let temp_discount = '';
              let insurance_max_discount_amount = 0;
              insurance_max_discount_amount = item.insurance_max_discount_amount ? Number.parseFloat(item.insurance_max_discount_amount)*10000 : 0;
              let service_fee_max_discount_amount = 0;
              service_fee_max_discount_amount = item.service_fee_max_discount_amount ? Number.parseFloat(item.service_fee_max_discount_amount)*10000 : 0;

              if(item.service_fee_discount_applied == 'F') {
                temp_discount = Number.parseFloat(item.service_fee_max_discount_amount).toFixed().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                discount = item.service_fee_max_discount_amount;
                max_amount = Number.parseFloat(item.service_fee_max_discount_amount).toFixed().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
              } else {
                if (item.insurance_discount_applied === 'P' && item.service_fee_discount_applied === 'P') {
                  temp_discount = Math.max(item.insurance_discount_rate, item.service_fee_discount_rate);
                  discount = Math.max(item.insurance_discount_rate, item.service_fee_discount_rate);
                } else if (item.insurance_discount_applied === 'P' && item.service_fee_discount_applied === 'N') {
                  temp_discount = item.insurance_discount_rate;
                  discount = item.insurance_discount_rate;
                } else if (item.insurance_discount_applied === 'N' && item.service_fee_discount_applied === 'P') {
                  temp_discount = item.service_fee_discount_rate;
                  discount = item.service_fee_discount_rate;
                }
                max_amount = Number.parseFloat(insurance_max_discount_amount + service_fee_max_discount_amount).toFixed().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
              }

              

              let row = '<tr> \
                    <td class="common-txt01">' + item.subject + '</td> \
                    <td class="bb-on" rowspan="2">' + temp_discount + unit + '</td> \
                    <td class="bb-on" rowspan="2">' + max_amount + '원' + '</td> \
                    <td class="bb-on" rowspan="2"> \
                      <div class="button-box flex-tc"> \
                        <a href="javascript:void(0)" class="btn btn-white btn-xs" data-seq="'+item.seq+'" data-value="'+discount+'" data-unit="'+ unit +'" onclick="downLoadCoupon(this)">받기</a> \
                      </div> \
                    </td> \
                  </tr> \
                  <tr> \
                    <td class="bb-on">' + date + '</td> \
                  </tr>';
              tbody += row;
            });
            $('#download_coupon_list').html(tbody);
          } else {
            let row = '<tr><td class="bb-on" colspan="4">사용가능한 쿠폰이 없습니다.</td></tr>';
            $('#download_coupon_list').html(row);
          }
        },
        error: function(xhr, status, error) {
          alert("AJAX실패. 상품변경에 따른정보를 가져오는데 실패하였습니다. 관리자에게 문의하십시오.");
          return false;
        }
      });
    }
  }

  function isDuplicateCoupon(clickedButton) {
    // 클릭된 버튼의 data-status 값 확인
    const status = clickedButton.getAttribute('data-status');
    
    // 클릭된 버튼이 선택된 상태인지 확인 (selected 클래스 확인)
    const isSelected = clickedButton.classList.contains('selected');
    if (status === 'N') {
      if (isSelected) {
        // 선택된 상태일 경우: 모든 버튼 다시 활성화
        document.querySelectorAll('a[data-status="N"]').forEach(function(button) {
          button.style.pointerEvents = 'auto'; // 클릭 활성화
          button.classList.remove('disabled'); // 비활성화 스타일 제거
        });
      } else {
        document.querySelectorAll('a[data-status="N"]').forEach(function(button) {
          // 클릭된 버튼과 동일하지 않은 버튼만 비활성화
          if (button !== clickedButton) {
            button.style.pointerEvents = 'none'; // 클릭 비활성화
            button.classList.add('disabled'); // 비활성화 스타일 추가
          }
        });
      }
    } else if (status === 'Y') {
      if (isSelected) {
        // 선택된 상태일 경우: 모든 버튼 다시 활성화
        document.querySelectorAll('a[data-status="Y"]').forEach(function(button) {
          button.style.pointerEvents = 'auto'; // 클릭 활성화
          button.classList.remove('disabled'); // 비활성화 스타일 제거
        });
      } else {
        document.querySelectorAll('a[data-status="Y"]').forEach(function(button) {
          // 클릭된 버튼과 동일하지 않은 버튼만 비활성화
          if (button !== clickedButton) {
            button.style.pointerEvents = 'none'; // 클릭 비활성화
            button.classList.add('disabled'); // 비활성화 스타일 추가
          }
        });
      }
    }
  }

 //쿠폰 선택 시 할인 적용
  function selCoupon(seqList) {
    EHDObject.customer.couponCode = seqList;
    $.ajax({
      type: "POST",
      url: "./renewal_step04_coupon.php?cp_cd=" + seqList,
      data: getFormInfo(),
      cache: false,
      contentType: false,
      processData: false,
      success: function(result) {
        if (result.success == "1") {
          EHDObject.customer.recommend_cd = 0; //쿠폰번호 초기화
          EHDObject.customer.couponName = result.cp_name;
          EHDObject.customer.discount = Number.parseFloat(result.s_amt_per).toFixed();
          EHDObject.customer.salePrice = result.sale_amt;
          EHDObject.customer.totalPrice = result.t_amount;
          
          document.querySelector('#payment-info .single-row .amount').textContent = `${EHDObject.customer.totalPrice.toLocaleString('ko-KR')} 원`;
          document.querySelector('#payment-info #multi-row-2 .row .amount').textContent = `${EHDObject.customer.salePrice.toLocaleString('ko-KR')} 원`;
        //   document.querySelector('#payment-info p > b').textContent = `${EHDObject.customer.totalPrice.toLocaleString('ko-KR')} 원`;
        //   document.querySelector('#payment-info p > small').textContent = `${EHDObject.customer.salePrice.toLocaleString('ko-KR')} 원 할인`;
          //generateCustomerForms();
        //   $('#recommend_name').val(result.cp_name);
        //   $('#recommend_name').attr("readonly", true);
        //   $('#discount_btn').hide();
        } else {
          EHDObject.customer.couponCode = 0;
        }
        console.log("asd")
        closeCoupon(1);
      },
      error: function(xhr, status, error) {
        $("input[name='cp_cd']").val("");
        alert("AJAX실패. 상품변경에 따른정보를 가져오는데 실패하였습니다. 관리자에게 문의하십시오.");
        console.log(xhr.responseText);
        return false;
      }
    });
  }


   function openCoupon(num) {
    getCouponList(num);
    $('html').addClass('fixed');
    $('.dim-bg').show().animate({
      opacity: '.75'
    }, 300);
    $('[data-layer="layer0' + num + '"]').show().animate({
      opacity: '1'
    }, 300);
  }

  //쿠폰 팝업 닫기
  function closeCoupon(num) {
    $('html').removeClass('fixed');
    $('.dim-bg').animate({
      opacity: '0'
    }, 300, function() {
      $('.dim-bg').hide();
    })
    $('[data-layer="layer0' + num + '"]').animate({
      opacity: '0'
    }, 300, function() {
      $('[data-layer="layer0' + num + '"]').hide();
    })
  }

  function downLoadCoupon(clickedButton) {
    const discount = clickedButton.getAttribute('data-value');
    const seq = clickedButton.getAttribute('data-seq');
    const unit = clickedButton.getAttribute('data-unit');
    $.ajax({
      type: "POST",
      url: "./pop_coupon_download_ajax.php?seq=" + seq + "&discount=" + discount + "&unit=" + unit,
      data: getFormInfo(),
      cache: false,
      contentType: false,
      processData: false,
      success: function(result) {
        if (result.success == "1") {
          clickedButton.style.pointerEvents = 'none'; // 클릭 불가
          clickedButton.style.opacity = '0.6';       // 비활성화된 것처럼 보이게 처리
          clickedButton.style.backgroundColor = 'gray'; // 배경색 변경
        } else {
          alert("이미 다운로드 받은 쿠폰입니다.");
        }
        // closeCoupon(2);
      },
      error: function(xhr, status, error) {
        $("input[name='cp_cd']").val("");
        alert("AJAX실패. 쿠폰 다운로드에 실패하였습니다. 관리자에게 문의하십시오.");
        console.log(xhr.responseText);
        return false;
      }
    });
  }
</script>
<?php
include '../_include/_tail.html';
include '../_include/_footer.html';
?>
<?php
include '../_include/_header_partner.html';
include_once $_SERVER['DOCUMENT_ROOT'] . '/_config/lib.php';
include $_SERVER['DOCUMENT_ROOT'] . '/_config/Mobile_Detect.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/_config/Func.insurance.php';  // 추가
$detect = new Mobile_Detect;
?>
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
          </div>
        </div>
        <div class="form-box" id="payment-info">
          <div class="form-content">
            <div class="total-price-box">
              <div class="single-row">
                <div class="name">결제금액</div>
                <div class="amount">0원</div>
              </div>
              <!-- yjhdev -->
              <!-- <div class="multi-row">
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
              </div> -->
            </div>
            <div class="title-box mt24">
              <h3>결제방법</h3>
            </div>
            <div class="method-box mt15">
              <ul>
                <li class="card"><a href="javascript:void(0)">신용카드</a></li>
                <li class="phone"><a href="javascript:void(0)">휴대폰 결제</a></li>
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
          <col />
          <col width="60px" />
          <col width="80px" />
        </colgroup>
        <thead>
          <tr>
            <th>쿠폰명/사용가능 기간</th>
            <th>할인율</th>
            <th>선택</th>
          </tr>
        </thead>
        <tbody id="coupon_list"></tbody>
      </table>
    </div>
    <button type="button" class="btn-close" data-layer-btn="layer01" onClick="closeCoupon(1)">
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
    // document.querySelector('#payment-info .multi-row .row:nth-child(1) .amount').textContent = `${customer.totalPrice.toLocaleString('ko-KR')} 원`;

  }
  window.addEventListener('load', (e) => {
    if (!EHDObject || !EHDObject.selectedPlan) {
      location.href = './renewal_step01.php';
      return;
    }

    generateCustomerForms();

    // event handlers
    //document.querySelector('div.coupon-box a:nth-child(2)').addEventListener('click', (e)=>alert('코드적용'));
    document.querySelector('#payment-info li.card').addEventListener('click', (e) => chk_submit4('Card'));
    document.querySelector('#payment-info li.phone').addEventListener('click', (e) => chk_submit4('HPP'));
    document.querySelector('#payment-info li.account').addEventListener('click', (e) => chk_submit4('Vbank'));
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
  }
<? } ?>
if (!pay_type) {
  alert("결제방식을 선택해 주세요.");
  return;
}

if(EHDObject.customer.cellphone === '01085634063' ||EHDObject.customer.cellphone === '01042241027' ||EHDObject.customer.cellphone === '01049775976' || EHDObject.customer.cellphone === '01038585916' || EHDObject.customer.cellphone === '01054405414' || EHDObject.customer.cellphone === '01020493619'){
  

const PAY_TYPE_MAP = {
    'Card': '카드',
    'HPP': '휴대폰',
    'Vbank': '가상계좌',
    'wcard' : '카드',
    'mobile' : '휴대폰',
    'vbank' : '가상계좌'
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
          successUrl: `${protocol}//${domain}${port}/html/insurance/payment_success.php`,
          failUrl: `${protocol}//${domain}${port}/html/insurance/payment_fail.php`
      })
      
    } catch (e) {
      console.error("토스페이먼츠 초기화 오류:", e);
      alert("결제 초기화 중 오류가 발생했습니다");
    }
  }
});

}else{


EHDObject.customer.paymethod = pay_type;
//var params = jQuery(formData).serialize();
var request = $.ajax({
  url: "./renewal_step04_ajax.php",
  type: "POST",
  data: getFormInfo(),
  cache: false,
  contentType: false,
  processData: false,
  success: function(result) {
    if (result) {
      $("#act_div").html(result);
      setTimeout(function() {
        <? if ($detect->isMobile()) { ?>
          on_web();
        <? } else { ?>
          inipay();
        <? } ?>


      }, 1000);
    }
  },
  error: function(xhr, status, error) {
    alert("AJAX실패. 상품변경에 따른정보를 가져오는데 실패하였습니다. 관리자에게 문의하십시오.");
    return false;
  }
});
request.done(function(result) {});
}

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
</script>
<?php
include '../_include/_tail.html';
include '../_include/_footer.html';
?>
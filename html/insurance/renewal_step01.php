<?php
include '../_include/_header_new.html';
include '../_include/_top.html';
?>
<section>
  <div class="container">
    <div class="title-box">
      <h2>간편조회</h2>
      <p>
        <b> <em>고객님의 여행정보</em> </b>를 입력해 주세요.
      </p>
    </div>
    <div class="white-box middle mt24">
      <form>
        <div class="form-box" id="listOfProduct" style="display: none">
          <div class="form-title">
            <strong>여행목적을 선택해 주세요</strong>
          </div>
          <div class="form-content">
            <div class="check-button-box">
              <ul></ul>
            </div>
          </div>
        </div>
        <div class="form-box" id="whichStayIn" style="display: none">
          <div class="form-title">
            <strong>현재 체류중이신 국가를 선택해 주세요</strong>
          </div>
          <div class="form-content">
            <div class="check-button-box">
              <ul></ul>
            </div>
          </div>
        </div>
        <div class="form-box">
          <div class="form-content">
            <div class="notice-box">
              <dl>
                <dt>유의사항</dt>
                <dd><p>해외 체류중 가입은 주재원, 법인소속 근로자, 교환교수만 가입하실 수 있습니다.</p></dd>
                <dd><p>유학생은 유학생 상품으로 가입해 주세요.</p></dd>
              </dl>
            </div>
          </div>
        </div>
        <div class="form-box">
          <div class="form-title">
            <strong>생년월일/성별</strong>
          </div>
          <div class="form-content">
            <div class="flex flex-vc flex-tj">
              <div class="col-6 pr8 pr-lg-4">
                <div class="input-box">
                  <div class="input-box-inner">
                    <input
                      type="input"
                      name="A-birth"
                      id="A-birth"
                      placeholder="생년월일(19800101)"
                      maxlength="8"
                      required
                    />
                  </div>
                </div>
              </div>
              <div class="col-6 pl8 pl-lg-4">
                <div class="check-button-box">
                  <ul>
                    <li>
                      <input
                        type="radio"
                        name="A-gender"
                        id="A-gender-01"
                        value="M"
                        placeholder="성별을 선택해주세요"
                        required
                      />
                      <label for="A-gender-01">남자</label>
                    </li>
                    <li>
                      <input type="radio" name="A-gender" id="A-gender-02" value="F" required />
                      <label for="A-gender-02">여자</label>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-box">
          <div class="form-title">
            <strong>출국일</strong>
          </div>
          <div class="form-content">
            <div class="flex flex-vc flex-tj">
              <div class="col-6 pr8 pr-lg-4">
                <div class="date-box">
                  <div class="date-box-inner">
                    <input
                      type="date"
                      name="A-departure"
                      id="A-departure"
                      placeholder="출국일을 입력해 주세요"
                      value=""
                      max="9999-12-31"
                      required
                    />
                  </div>
                </div>
              </div>
              <div class="col-6 pl8 pl-lg-4">
                <div class="select-box">
                  <div class="select-box-inner">
                    <select name="A-departure-time" id="A-departure-time" required></select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-box">
          <div class="form-title">
            <strong>귀국일</strong>
          </div>
          <div class="form-content">
            <div class="flex flex-vc flex-tj">
              <div class="col-6 pr8 pr-lg-4">
                <div class="date-box">
                  <div class="date-box-inner">
                    <input
                      type="date"
                      name="A-arrival"
                      id="A-arrival"
                      placeholder="귀국일을 입력해 주세요"
                      value=""
                      max="9999-12-31"
                      required
                    />
                  </div>
                </div>
              </div>
              <div class="col-6 pl8 pl-lg-4">
                <div class="select-box">
                  <div class="select-box-inner">
                    <select name="A-arrival-time" id="A-arrival-time" required></select>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="form-box">
          <div class="form-title">
            <strong>동반인 선택</strong>
          </div>
          <div class="form-content" id="companions">
            <div class="flex flex-vc flex-tj">
              <div class="col-6 pr8 pr-lg-4">
                <div class="select-box">
                  <div class="select-box-inner">
                    <select name="B-count" id="B-count"></select>
                  </div>
                </div>
              </div>
            </div>

            <div class="flex flex-vc flex-tr mt24">
              <div class="check-box">
                <div class="check-box-inner">
                  <input
                    type="checkbox"
                    name="B-agree"
                    id="B-agree"
                    placeholder="동반인 가입 동의를 해주세요"
                    required
                  />
                  <label for="check">동반인 가입을 동의 합니다.</label>
                </div>
                <a href="javascript:;" onclick="popupOpen('more');" class="more">자세히 보기</a>
              </div>
            </div>
          </div>
        </div>
        <div class="form-box">
          <div class="form-content">
            <div class="button-box">
              <button type="button" class="btn btn-active calculate">가격 조회</button>
            </div>
          </div>
        </div>
        <div class="form-box product-board" id="product-board">
          <div class="form-content">
            <div class="total-box total-box-top" style="margin-bottom: 30px">
              <div class="info-content" id="joinSumary">
                <span>2023.12.01</span>
                <em>~</em>
                <span>2023.12.31</span>
                <!-- (<span>10</span>일 / <span>2</span>명)-->
                <!-- 20231008 추가 날짜와 수정버튼 겹침 문제_total영역 div로 처리 -->
                <div class="info-total">(<span>10</span>일 / <span>2</span>명)</div>
              </div>
              <a href="#" class="edit">수정</a>
            </div>
            <div class="total-box total-box-bottom" id="joinPlanCd">
              <span class="level">LV 1</span>
              <div class="flex flex-vc">
                <span class="price">0원</span>
                <!-- 20231008 추가 날짜와 수정버튼 겹침 문제_total영역 div로 처리 -->
                <a href="#" class="btn-line viewbtn" onClick="openLayer(1)">보장내역 보기</a>
              </div>
            </div>
            <div class="title-box mt24">
              <h3>가입자 정보</h3>
            </div>
            <div class="info-list-box" id="companion-list"></div>
          </div>
        </div>
      </form>
    </div>
    <div class="hr-txt product-board">보험과 의료지원 서비스를 선택하여 가입할 수 있습니다.</div>
    <!-- 서비스 및 보장내역 출력 begin-->
    <div class="product-board" id="option-list"></div>
    <!-- 서비스 및 보장내역 출력 end-->
    <div class="white-box middle mt24 product-board">
      <div class="mt24">
        <div class="button-box">
          <a href="#" class="btn btn-active write">다음</a>
        </div>
      </div>
    </div>
  </div>
  <!-- 동반인 가입 동의 팝업 -->
  <div class="popup-box">
    <div class="box" data-name="more" style="max-width: 420px">
      <div class="popup-head">
        <h3>동반인 가입 동의</h3>
        <a href="javascript:;" class="close" onclick="popupClose();">닫기</a>
      </div>
      <div class="popup-body">
        <div class="popup-body-text">
          <p>
            본인은 본 보험계약 피 보험자로 되는 것에 대해 동의 하며, 본인이<br />
            타인을 대신하여 상품 가입 절차를 이행하는 경우 그 보험가입에<br />
            대해 해당 타인(들)로 부터 전권을 위임 받았음을 확인합니다.
          </p>
        </div>
        <div class="popup-body-button">
          <a href="javascript:;" onclick="popupClose();">확인</a>
        </div>
      </div>
    </div>
  </div>
  <!-- 동반인 가입 동의 팝업 -->

  <!-- 0830 보장내역 팝업 추가 -->
  <div class="dim-bg" style="display: none; opacity: 0"></div>
  <div class="layer-container" style="display: none; opacity: 0" data-layer="layer01"></div>
  <!-- // 0830 팝업 추가 -->
</section>

<script src="./js/swiper.js?a=1"></script>
<script>
  function toogleProductBoard(bool) {
    if (bool !== undefined && typeof bool === 'boolean') {
      EHDObject.isOpenProductBoard = bool;
    }

    if (EHDObject.isOpenProductBoard) {
      document.querySelectorAll('.product-board').forEach((e) => (e.style.display = ''));
    } else {
      document.getElementById('option-list').innerHTML = '';
      document.querySelectorAll('.product-board').forEach((e) => (e.style.display = 'none'));
    }
  }

  function displayProductFactory(elementName, querySelector) {
    return function (dataList) {
      const html = [];

      toogleProductBoard(false);
      if (Array.isArray(dataList) && dataList.length > 0) {
        dataList.forEach((item, idx) => {
          html.push(`<li>`);
          html.push(
            `  <input type="radio" name="${elementName}" id="${elementName}-${idx}" data-name="${item.category_name}" value="${item.category_code}">`
          );
          html.push(`  <label for="${elementName}-${idx}">${item.category_name}</label>`);
          html.push(`</li>`);
        });

        document.querySelector(`${querySelector} ul`).innerHTML = html.join('');
        document.querySelector(`${querySelector}`).style.display = 'block';

        const elements = document.querySelectorAll(`${querySelector} input[name=${elementName}]`);
        elements?.forEach((el) => el.addEventListener('click', onClickEventListenerForProduct));
      }

      dispatchCustomEventOnWindow(`ehd.${elementName}.rendered`);
    };
  }

  function processSelectedProduct(dataList) {
    if (Array.isArray(dataList) && dataList.length > 0) {
      let content;
      let selectedPlanCd = dataList.find((item) => item.plan_cd === EHDObject.plan_cd);

      if (!selectedPlanCd) selectedPlanCd = dataList[dataList.length - 1];

      EHDObject.planName = dataList;
      EHDObject.pr_cd = selectedPlanCd.pr_cd;
      EHDObject.plan_cd = selectedPlanCd.plan_cd;
      content = selectedPlanCd.guide;

      if (content) {
        document.querySelector('div.notice-box').innerHTML = content.replace(/(.*\n)/g, '<p>$1</p>');
      }
    }
  }

  /**
   * 동반자 폼 생성
   */
  function generateCompanionList() {
    const companions = [EHDObject.customer, ...EHDObject.companions];
    if (Array.isArray(companions) && companions.length > 0) {
      let html = companions.map((item) => {
        let h = [];
        h.push(`<li data-data="has">`);
        h.push(`  <span>${item.birth}</span>`);
        h.push(`  <span>${item.age}세 / ${item.gender == 'M' ? '남' : '여'}</span>`);
        h.push(`  <span>${item.price || 0} 원</span>`);
        h.push(`</li>`);
        return h.join('');
      });
      html.unshift('<ul>');
      html.push(`</ul>`);
      document.getElementById('companion-list').innerHTML = html.join('');
    } else {
      document.getElementById(
        'companion-list'
      ).innerHTML = `<ul><li style="justify-content: center;" data-data="none"><span>동반자 없음</span></li></li>`;
    }
  }

  /**
   * 가입자 정보 요약 폼 생성
   */
  function setMainInformation() {
    const __ = EHDObject;
    const period = __.isLongterm() === 1 ? __.customer.monthPeriod : __.customer.dayPeriod;
    const unit = __.isLongterm() === 1 ? '개월' : '일';
    const countOfJoiners = Array.from(__.companions).length + 1;
    document.getElementById('joinSumary').innerHTML = `
            <span>
                ${__.customer.departureDate}
            </span>
            <em>~</em>
            <span>
                ${__.customer.arrivalDate}
            </span>
            (<span>${period}</span>${unit} / <span>${countOfJoiners}</span>명)
        `;
  }

  function setPriceAll() {
    let totalPrice = 0;
    const __ = EHDObject;
    const companions = [__.customer, ...__.companions];

    if (Array.isArray(companions) && companions.length > 0) {
      companions.forEach((c, i) => {
        const p = __.calculatePriceByPerson(c);
        setPriceIntoCompanionList(p.totalPrice, i);
        c.price = p.totalPrice;
        c.sPrice = p.sPrice;
        c.gPrice = p.gPrice;
        totalPrice += p.totalPrice;
      });
    }
    if (__.customer) {
      __.customer.totalPrice = totalPrice;
      setPriceIntoTotal(totalPrice);
      changePlanName(__.selectedPlan ? __.selectedPlan.ins_plan_name : '');
    }
  }

  function setPriceIntoCompanionList(price, index = 0) {
    let list = document.getElementById('companion-list').querySelectorAll('li[data-data]');

    list.forEach((el, idx) => {
      if (el.dataset.data === 'has' && idx === index) {
        el.querySelector('span:last-child').textContent = `${Number(price).toLocaleString('ko-KR')} 원`;
      }
    });
  }

  function setPriceIntoTotal(price) {
    const element = document.getElementById('joinPlanCd').querySelector('span.price');
    element.textContent = `${Number(price).toLocaleString('ko-KR')} 원`;
  }

  function changePlanName(name) {
    const element = document.getElementById('joinPlanCd').querySelector('span.level');
    element.textContent = name;
  }

  function generateProductBoard() {
    // 출국일/입국일 기간, 인원 정보 설정
    setMainInformation();
    // 동반자 정보 출력
    generateCompanionList();
    // 가격정보 초기화
    setPriceAll();
  }

  // 선택된 보장내역/서비스 저장
  function checkOptionCheckbox() {
    const sElements = Array.from(document.querySelectorAll('input[id^=service-0]'));
    const gElements = Array.from(document.querySelectorAll('input[id^=guarantee-]'));
    const selectedItems = { guarantees: [], services: [] };

    sElements.forEach((e) => {
      if (e.checked) selectedItems.services.push(e.value);
    });
    gElements.forEach((e) => {
      if (e.checked) selectedItems.guarantees.push(e.value);
    });

    EHDObject.selectedItems = selectedItems;
  }

  /**
   * 선택된 보장내역(보험)/서비스를 기반으로 해당 플랜 검색
   * 검색된 플랜의 요금테이블을 가져와 요금 계산
   */
  function calculateSelectedOptions() {
    let {
      plans,
      services,
      NOT_AVAILABLE,
      SERVICE_GROUP_NAME: serviceGroupNames,
      selectedItems: { services: sOptions, guarantees: gOptions },
    } = EHDObject;

    const yn = (bool) => (bool ? 'Y' : 'N'); // 선택 여부 Y : 선택, N : 선택하지 않음
    let ext1, ext2, ext3; // ext1 : 보장보험1, ext2 : 보장보험2, ext3 : 서비스
    let ext3_comm, ext3_opt1, ext3_opt2; // comm : 공통서비스, opt1~2 : 옵션서비스1~2,
    services = services || [];

    ext1 = yn(gOptions && plans.find((p) => gOptions.find((g) => g == p.guarantee_seq1)));
    ext2 = yn(gOptions && plans.find((p) => gOptions.find((g) => g == p.guarantee_seq2)));
    ext3 = yn(sOptions && sOptions.length > 0);
    ext3_comm = yn(sOptions && sOptions.find((o) => o === serviceGroupNames[0]));
    ext3_opt1 = yn(sOptions && sOptions.find((o) => o === serviceGroupNames[1]));
    ext3_opt1_count = services.filter((o) => o.service_group_name === serviceGroupNames[1]).length;
    ext3_opt2 = yn(sOptions && sOptions.find((o) => o === serviceGroupNames[2]));
    ext3_opt2_count = services.filter((o) => o.service_group_name === serviceGroupNames[2]).length;

    let targetPlans = plans.filter(
      (p) => p.ext1 === ext1 && p.ext2 === ext2 && p.ext3 === ext3 && p.plan_cd === EHDObject.plan_cd
    );
    // console.log('보험/서비스 선택 플랜 :', targetPlans);

    if (ext3 === 'Y') {
      targetPlans = targetPlans.filter((p1) =>
        services.find((p2) =>
          p2.pr_cd === p1.pr_cd &&
          p2.plan_seq === p1.plan_seq &&
          p2.plan_cd === p1.plan_cd &&
          p2.service_group_name === serviceGroupNames[0] &&
          ext3_comm === 'Y'
            ? p2.k_amount !== NOT_AVAILABLE
            : p2.k_amount === NOT_AVAILABLE
        )
      );

      if (ext3_opt1_count > 0) {
        if (ext3_opt1 === 'Y') {
          targetPlans = targetPlans.filter((p1) =>
            services.find(
              (p2) =>
                p2.plan_seq === p1.plan_seq &&
                p2.service_group_name === serviceGroupNames[1] &&
                p2.k_amount != NOT_AVAILABLE
            )
          );
        } else {
          targetPlans = targetPlans.filter((p1) =>
            services.find(
              (p2) =>
                p2.plan_seq === p1.plan_seq &&
                p2.service_group_name === serviceGroupNames[1] &&
                p2.k_amount == NOT_AVAILABLE
            )
          );
        }
      }

      if (ext3_opt2_count > 0) {
        if (ext3_opt2 === 'Y') {
          targetPlans = targetPlans.filter((p1) =>
            services.find(
              (p2) =>
                p2.plan_seq === p1.plan_seq &&
                p2.service_group_name === serviceGroupNames[2] &&
                p2.k_amount != NOT_AVAILABLE
            )
          );
        } else {
          targetPlans = targetPlans.filter((p1) =>
            services.find(
              (p2) =>
                p2.plan_seq === p1.plan_seq &&
                p2.service_group_name === serviceGroupNames[2] &&
                p2.k_amount == NOT_AVAILABLE
            )
          );
        }
      }
      // console.log('공통/옵션 서비스 선택 플랜 :', targetPlans);
    }

    EHDObject.selectedPlan = targetPlans[0];
    // console.log('최종 선택된 플랜 :', EHDObject.selectedPlan);
    if (EHDObject.selectedPlan) {
      EHDObject.getPlanInfo({ api: EHDObject.GET_PLAN_PRICE, plan_seq: EHDObject.selectedPlan.plan_seq }, setPriceAll);
    } else setPriceAll();
  }

  /**
   * 서비스내역 폼 생성
   */
  function generateServiceList() {
    let orderNumber = 1;
    const html = [];
    const { commonServices, optionServices } = EHDObject;

    // 의료지원 서비스 항목
    commonServices.forEach((item, idx) => {
      if (idx === 0) {
        html.push(`<div class="white-box middle mt24">`);
        html.push(`  <div class="title-state-box">`);
        html.push(`    <strong>${item.service_group_name}</strong>`);
        html.push(`    <div class="check-box">`);
        html.push(`      <div class="check-box-inner type01">`);
        html.push(
          `        <input type="checkbox" value="${item.service_group_name}" id="service-0${orderNumber}" checked />`
        );
        html.push(`        <label for="service-0${orderNumber++}">선택</label>`);
        html.push(`      </div>`);
        html.push(`    </div>`);
        html.push(`  </div>`);
        html.push(`  <div class="title-box mt12">`);
        html.push(`    <h3>24시간 의료상담 및 병원예약</h3>`);
        html.push(`  </div>`);
        html.push(`  <p class="common-txt01">여행중 아프면 24시간 언제 어디서든 연락하세요.</p>`);
        html.push(
          `  <p class="common-txt01">보험을 가입하지 않으시면 해외병원비 대신지불, 원격화상진료 서비스는 제공되지 않습니다.</p>`
        );
        html.push(`  <div class="table-form-box">`);
        html.push(`    <ul>`);
      }
      html.push(`        <li>`);
      html.push(`          <div class="table-head w300">`);
      html.push(`            <strong>${item.service_name}</strong>`);
      html.push(`          </div>`);
      html.push(`          <div class="table-body flex-tr" style="justify-content: center;">`);
      if (item.chk_service === 'Y') {
        html.push(`            <b class="point">${item.k_amount}</b>`);
      } else {
        html.push(`            <b>${item.k_amount}</b>`);
      }
      html.push(`          </div>`);
      html.push(`        </li>`);
    });

    html.push(`    </ul>`);
    html.push(`  </div>`);
    html.push(`</div>`);

    if (Array.isArray(optionServices) && optionServices.length > 0) {
      // 건강검진, 긴급이후송 서비스 항목
      const arrLength = optionServices.length;
      let groupName = undefined;
      let message = undefined;

      optionServices.forEach((item, idx) => {
        if (idx === 0) {
          html.push(`<div class="white-box middle mt24">`);
          html.push(`  <div class="title-state-box">`);
          html.push(`    <strong class="memo">필요한 서비스를 추가하세요</strong>`);
          html.push(`  </div>`);
        }

        if (groupName !== item.service_group_name) {
          groupName = item.service_group_name;

          if (groupName === EHDObject.SERVICE_GROUP_NAME[1]) {
            message = '한국 건강검진 센터에서 1회 무료 검사를 제공해 드립니다.';
          } else if (groupName === EHDObject.SERVICE_GROUP_NAME[2]) {
            message = '긴급한 경우 에어앰뷸런스로 이송해 드리며 이송비용 2억까지 보장해 드립니다.';
          }

          if (idx !== 0) {
            html.push(`    </ul>`);
            html.push(`  </div>`);
          }

          html.push(`  <div class="title-box flex flex-tj mt12">`);
          html.push(`    <h3>${groupName}</h3>`);
          html.push(`    <div class="check-box">`);
          html.push(`      <div class="check-box-inner type02">`);
          html.push(`        <input type="checkbox" value="${groupName}" id="service-0${orderNumber}" checked>`);
          html.push(`        <label for="service-0${orderNumber++}">추가</label>`);
          html.push(`      </div>`);
          html.push(`    </div>`);
          html.push(`  </div>`);
          html.push(`  <p class="common-txt01">${message}</p>`);
          html.push(`  <button type="button" class="btn-more off">자세히 보기</button>`);
          html.push(`  <div class="table-form-box mt12" style="display: none;">`);
          html.push(`    <ul>`);
        }
        html.push(`        <li>`);
        html.push(`          <div class="table-head w-65">`);
        html.push(`            <strong>${item.service_name}</strong>`);
        html.push(`          </div>`);
        html.push(`          <div class="table-body" style="justify-content: center;">`);
        if (item.chk_service === 'Y') {
          html.push(`            <b class="point">${item.k_amount}</b>`);
        } else {
          html.push(`            <b>${item.k_amount}</b>`);
        }
        html.push(`          </div>`);
        html.push(`        </li>`);
      });

      html.push(`    </ul>`);
      html.push(`  </div>`);
      html.push(`</div>`);
    }

    document.getElementById('option-list').insertAdjacentHTML('beforeend', html.join(''));

    const serviceCheckBoxes = document.querySelectorAll('input[type=checkbox][id^=service-0]');
    serviceCheckBoxes.forEach((box) => box.addEventListener('click', serviceCheckBoxEventListener));

    // html 에서 checked 되 checkbox 로 가격 계산을 하기위해 호출
    serviceCheckBoxEventListener();

    // 서비스 자세히 보기 버튼
    $('.btn-more').on('click', function () {
      $(this).next().slideToggle();
      $(this).toggleClass('off');
    });
  }

  /**
   * 보장내역 폼 생성
   */
  function generateGuaranteeList() {
    const html = [];
    const dataList = EHDObject.guarantees;
    const kinds = [];

    dataList.forEach((item) => kinds.includes(item.guarantee_seq) || kinds.push(item.guarantee_seq));

    kinds.forEach((k, k_idx) => {
      const buff = [];
      let groupName = undefined;
      const list = dataList.filter((item) => item.guarantee_seq === k);

      list.forEach((item, idx) => {
        if (idx === 0) {
          buff.push(`<div class="white-box middle mt24">`);
          buff.push(`  <div class="title-state-box mt12">`);
          buff.push(`    <strong>${item.guarantee_name}</strong>`);
          buff.push(`    <div class="check-box">`);
          buff.push(`      <div class="check-box-inner type01">`);
          buff.push(
            `        <input type="checkbox" value="${item.guarantee_seq}" id="guarantee-${item.guarantee_seq}" checked/>`
          );
          buff.push(`        <label for="guarantee-${item.guarantee_seq}">선택</label>`);
          buff.push(`      </div>`);
          buff.push(`    </div>`);
          buff.push(`  </div>`);
        }

        if (groupName !== item.group_mn) {
          groupName = item.group_mn;

          if (idx !== 0) {
            buff.push(`    </ul>`);
            buff.push(`  </div>`);
          }

          buff.push(`  <div class="title-box mt12">`);
          buff.push(`    <h3>${groupName}</h3>`);
          buff.push(`  </div>`);
          buff.push(`  <div class="table-form-box">`);
          buff.push(`    <ul>`);
        }

        buff.push(`        <li>`);
        buff.push(`          <div class="table-head w300">`);
        buff.push(`            <strong>${item.service_name}</strong>`);
        buff.push(`          </div>`);
        buff.push(`          <div class="table-body flex-tr">`);
        if (k_idx === 0 && idx === 0) {
          // 보장내역 중 첫번째 보장내역을 select-box 로 변환하기위한 로직
          // 변환은 보장내역1 만 적용 한다
          const anotherGuarantees = [];
          EHDObject.anotherGuarantees.forEach((a) => {
            const el = a.find((b, i) => i === idx && b.service_name === item.service_name);
            if (el) anotherGuarantees.push(el);
          });

          buff.push(`            <div class="select-box flex-1">`);
          buff.push(`            <div class="select-box-inner">`);
          buff.push(`            <select class="tc">`);
          buff.push(`              <option value="${item.plan_cd}" selected>${item.g_amount}</option>`);
          anotherGuarantees.forEach((g) => buff.push(`<option value="${g.plan_cd}">${g.g_amount}</option>`));
          buff.push(`            </select>`);
          buff.push(`            </div>`);
        } else {
          buff.push(`            <b>${item.g_amount}</b>`);
        }
        buff.push(`          </div>`);
        buff.push(`        </li>`);

        if (idx === list.length - 1) {
          buff.push(`    </ul>`);
          buff.push(`  </div>`);
          buff.push(`</div>`);
        }
      });

      html.push(...buff);
    });

    document.getElementById('option-list').insertAdjacentHTML('afterbegin', html.join(''));
    const guaranteeCheckBoxes = document.querySelectorAll('input[type=checkbox][id^=guarantee-]');
    guaranteeCheckBoxes.forEach((box) => box.addEventListener('click', guaranteeCheckBoxEventListener));

    // html 에서 checked 되 checkbox 로 가격 계산을 하기위해 호출
    guaranteeCheckBoxEventListener();

    // 보장내역의1의 금액 변경 이벤트
    document.querySelectorAll('select.tc').forEach((el) => {
      el.addEventListener('change', (e) => {
        EHDObject.plan_cd = e.currentTarget.querySelector('option:checked').value;
        toogleProductBoard(false);
        onClickEventListenerForCalculateButton();
      });
    });
  }

  /**
   * 보장내역 팝업폼 생성
   */
  function generateGuaranteeListInPopup() {
    const dataList = EHDObject.guarantees;
    const kinds = [];
    const __ = EHDObject;

    dataList.forEach((item) => kinds.includes(item.guarantee_seq) || kinds.push(item.guarantee_seq));
    const layerBoxDiv = __.createElement('div', {
      classList: ['layer-box'],
      child: [{ tag: 'h3', options: { classList: ['layer-title'], text: '보장내역' } }],
    });

    kinds.forEach((k) => {
      let tableDiv, titleBoxDiv, groupName;
      const list = dataList.filter((item) => item.guarantee_seq === k);

      list.forEach((item, idx) => {
        if (groupName !== item.group_mn) {
          if (groupName !== undefined) {
            layerBoxDiv.append(titleBoxDiv);
            layerBoxDiv.append(tableDiv);
          }
          groupName = item.group_mn;
          titleBoxDiv = __.createElement('div', {
            classList: ['title-box', 'mt24'],
            child: { tag: 'h3', options: { text: groupName } },
          });
          tableDiv = __.createElement('div', {
            classList: 'table-wrap',
            child: {
              tag: 'table',
              options: {
                classList: 'table-type01',
                child: [
                  {
                    tag: 'colgroup',
                    options: {
                      child: [
                        { tag: 'col', options: { attribute: { width: '50%' } } },
                        { tag: 'col', options: { attribute: { width: '50%' } } },
                      ],
                    },
                  },
                  {
                    tag: 'thead',
                    options: {
                      child: {
                        tag: 'tr',
                        options: {
                          child: [
                            { tag: 'th', options: { text: '보장내역' } },
                            { tag: 'th', options: { text: '보장한도' } },
                          ],
                        },
                      },
                    },
                  },
                  { tag: 'tbody' },
                ],
              },
            },
          });
        } // end if

        __.createElement(
          'tr',
          {
            child: [
              { tag: 'td', options: { text: item.service_name } },
              { tag: 'td', options: { text: item.g_amount } },
            ],
          },
          tableDiv.querySelector('tbody')
        );
        __.createElement(
          'tr',
          {
            child: [
              {
                tag: 'td',
                options: { attribute: { colspan: '2' }, classList: 'type01', text: item.service_content },
              },
            ],
          },
          tableDiv.querySelector('tbody')
        );
      });

      layerBoxDiv.append(titleBoxDiv);
      layerBoxDiv.append(tableDiv);
    });

    __.createElement(
      'div',
      {
        classList: ['title-box', 'mt24'],
        child: { tag: 'h3', options: { text: '해지환급금' } },
      },
      layerBoxDiv
    );
    __.createElement(
      'p',
      {
        classList: 'common-txt01',
        text: '가입 후 중도해지할 경우 미경과 상품가격를 해지환급금으로 지급해 드립니다.',
      },
      layerBoxDiv
    );
    __.createElement(
      'button',
      {
        attribute: { type: 'button', 'data-layer-btn': 'layer01', onClick: 'closeLayer(1)' },
        classList: 'btn-close',
        child: { tag: 'span', options: { classList: 'tts', text: '팝업 닫기' } },
      },
      layerBoxDiv
    );

    document.querySelector('[data-layer=layer01]').innerHTML = '';
    document.querySelector('[data-layer=layer01]').append(layerBoxDiv);
  }

  function generatePlanInfo() {
    EHDObject.getPlanInfo({ api: EHDObject.GET_PLAN, pr_cd: EHDObject.pr_cd }, () => {
      // 플랜정보를 가져온 후 수행되어야 할 로직이기 때문에 콜백에 구현
      let plan;

      // 플랜정보 중 서비스가 있는 플랜으로 서비스 내역 가져와 출력
      plan = EHDObject.plans.find((item) => item.ext3 === 'Y' && EHDObject.plan_cd === item.plan_cd);
      EHDObject.getPlanInfo(
        { api: EHDObject.GET_SERVICE, pr_cd: plan?.pr_cd, plan_cd: plan?.plan_cd },
        // 서비스내역 출력
        generateServiceList
      );

      // 플랜정보 중 보장내역1, 보장내역2가 있는 플랜으로 보장내역 가져와 출력
      plan = EHDObject.plans.find(
        (item) => item.ext1 === 'Y' && item.ext2 === 'Y' && item.plan_cd === EHDObject.plan_cd
      );
      plan = plan || EHDObject.plans.find((item) => item.ext1 === 'Y' && item.plan_cd === EHDObject.plan_cd);
      EHDObject.getPlanInfo({ api: EHDObject.GET_GUARANTEE, pr_cd: plan?.pr_cd, plan_seq: plan?.plan_seq }, () => {
        // 보장내역정보를 가져온 후 수행되어야 할 로직이기 때문에 콜백에 구현
        // 보장내역1 의 첫번째, 두번째 항목을 select-box 로 만들기 위한 로직
        let anotherPlan;
        const temp = [];
        // 현재 선택된 plan_cd 이외의  group by plan_cd
        //
        anotherPlan = EHDObject.plans.filter((item) => {
          if (!temp.includes(item.plan_cd) && item.plan_cd !== plan.plan_cd && item.ext1 === 'Y') {
            temp.push(item.plan_cd);
            return true;
          } else {
            return false;
          }
        });
        // group by plan_cd 에서 plan_seq 만 취합 구분자 "|" 로 연결
        const planSeqs = anotherPlan?.reduce((prev, curr) => `${prev}|${curr.plan_seq}`, '');
        EHDObject.getPlanInfo(
          {
            api: EHDObject.GET_ANOTHER_GUARANTEES,
            pr_cd: plan?.pr_cd,
            plan_seq: planSeqs,
          },
          () => {
            // 보장내역 출력
            // 선택된 plan_cd 보장내역과 선택되지 않은 plan_cd 보장내역을 각각 구한 후 화면 rendering
            // document.querySelector('#joinPlanCd > span').textContent = EHDObject.PLAN_CD_NAME[EHDObject.plan_cd];
            document.querySelector('#joinPlanCd > span').textContent = EHDObject.plans[0].ins_plan_name;
            generateGuaranteeList();
            generateGuaranteeListInPopup();
          }
        );
      });
    });

    document.querySelector('a.edit').addEventListener('click', (e) => {
      toogleProductBoard(false);
      document.querySelector('#A-birth').focus();
    });
  }

  // 가격조회 전 check validation
  function checkValidation() {
    const customer = {};
    const companions = [];
    const pattern = /^(\d{4})(\d{2})(\d{2})$/;
    const bCount = Number(document.querySelector('#B-count').value || 0);
    const bBirths = document.getElementsByName('B-birth');
    const requiredElements = document.querySelectorAll('input[required]');
    const unCheckedElements = document.querySelectorAll('input[required][type=radio]');
    function formatDate(inputDate) {
      return inputDate.replace(pattern, '$1-$2-$3');
    }

    let emptyElement = Array.from(requiredElements).find((el) => {
      if (!el.value) {
        return true;
      }
      if (el.name.includes('birth') && !pattern.test(el.value)) {
        return true;
      }
    });
    emptyElement ||= Array.from(unCheckedElements).find(
      (el) => !Array.from(document.getElementsByName(el.name)).find((ell) => ell.checked)
    );

    if (emptyElement) {
      alert(emptyElement.getAttribute('placeholder') || '');
      emptyElement.focus();
      return false;
    }

    customer.birth = formatDate(document.getElementById('A-birth').value);
    customer.age = EHDObject.getAge(customer.birth);
    customer.gender = Array.from(document.getElementsByName(`A-gender`)).find((el) => el.checked)?.value;
    customer.departureDate = document.getElementById('A-departure').value;
    customer.departureTime = document.getElementById('A-departure-time').value;
    customer.arrivalDate = document.getElementById('A-arrival').value;
    customer.arrivalTime = document.getElementById('A-arrival-time').value;
      
    const depth3Name = EHDObject.depth3 ? EHDObject.depth3.name : '';
    const cDate = new Date();
    const dDate = new Date(customer.departureDate);
    const oneDay = 24 * 60 * 60 * 1000;
    let nDate = new Date(cDate.getTime());

    const day = Math.ceil(
      (new Date(customer.arrivalDate).getTime() - new Date(customer.departureDate).getTime()) / 1000 / 60 / 60 / 24
    );
    if (day >= 90) {
      customer.departureTime = '00';
      customer.arrivalTime = '23';
      document.getElementById('A-departure-time').value = '00';
      document.getElementById('A-arrival-time').value = '23';

      if(depth3Name && depth3Name === '해외거주') {
        nDate = new Date(cDate.getTime() + 3);
      }
    }

    const departureDate = new Date(`${customer.departureDate} ${customer.departureTime}:00:00`);
    const arrivalDate = new Date(`${customer.arrivalDate} ${customer.arrivalTime}:00:00`);
    const dayPeriod = Math.ceil(
      (Number(arrivalDate.getTime()) - Number(departureDate.getTime())) / 1000 / 60 / 60 / 24
    );
    const monthPeriod =
      (arrivalDate.getFullYear() - departureDate.getFullYear()) * 12 +
      (arrivalDate.getMonth() - departureDate.getMonth()) +
      (arrivalDate.getDate() - departureDate.getDate() >= 0 ? 1 : 0);

    customer.dayPeriod = dayPeriod;
    customer.monthPeriod = monthPeriod;

    // nDate.setHours(0, 0, 0, 0);
    // dDate.setHours(0, 0, 0, 0);

    if(departureDate < nDate) {
      alert('출국일을 확인해주세요.');
      document.getElementById('A-departure').focus();
      return false;
    }

    for (let i = 0; i < bCount; i++) {
      const person = {};
      person.birth = formatDate(bBirths[i].value);
      person.age = EHDObject.getAge(person.birth);
      person.gender = Array.from(document.getElementsByName(`B-gender-${i}`)).find((el) => el.checked)?.value;
      person.dayPeriod = dayPeriod;
      person.monthPeriod = monthPeriod;
      companions.push(person);
    }

    EHDObject.customer = customer;
    EHDObject.companions = companions;
    EHDObject.save();

    return true;
  }

  // 동반자 입력양식 생성
  function generateCompanionForms(num) {
    const html = [];
    const count = Number(num) || 0;

    document.querySelectorAll('#companions > div:not(:first-child)').forEach((el) => {
      const lastElement = document.querySelector('#companions > div:last-child');
      if (el !== lastElement) el.remove();
    });

    for (let i = 0; i < count; i++) {
      html.push(`<div class="flex flex-vc flex-tj mt10">`);
      html.push(`  <div class="col-6 pr8 pr-lg-4">`);
      html.push(`    <div class="date-box">`);
      html.push(`			<div class="date-box-inner">`);
      html.push(
        `				<input type="input" name="B-birth" id="B-birth-${i}" maxlength="8" placeholder="동반인 생년월일(19800101)" required />`
      );
      html.push(`			</div>`);
      html.push(`    </div>`);
      html.push(`  </div>`);
      html.push(`  <div class="col-6 pl8 pl-lg-4">`);
      html.push(`    <div class="check-button-box">`);
      html.push(`      <ul>`);
      html.push(`        <li>`);
      html.push(
        `          <input type="radio" name="B-gender-${i}" id="B-gender-1-${i}" value="M" placeholder="동반인 성별을 선택해주세요" required />`
      );
      html.push(`          <label for="B-gender-1-${i}">남자</label>`);
      html.push(`        </li>`);
      html.push(`        <li>`);
      html.push(`          <input type="radio" name="B-gender-${i}" id="B-gender-2-${i}" value="F" required />`);
      html.push(`          <label for="B-gender-2-${i}">여자</label>`);
      html.push(`        </li>`);
      html.push(`      </ul>`);
      html.push(`    </div>`);
      html.push(`  </div>`);
      html.push(`</div>`);
    }

    document.querySelector('#companions > div:first-child').insertAdjacentHTML('afterend', html.join(''));
    setDateLimitOnInput();
  }

  /////////////////////////////////////////////////////////////////////////////
  // Event Listener
  /////////////////////////////////////////////////////////////////////////////
  function dispatchCustomEventOnWindow(eventName) {
    window.dispatchEvent(new Event(eventName));
  }

  // depth2, depth3 common event listener
  function onClickEventListenerForProduct(event) {
    const targetName = event.currentTarget.name;

    EHDObject.init();
    EHDObject['depth3'] = undefined;
    EHDObject[targetName] = {
      code: event.currentTarget.value,
      name: event.currentTarget.dataset.name,
    };
    EHDObject.save();
    EHDObject.getPlanNames(processSelectedProduct);
    if (targetName === 'depth2') {
      document.querySelector('#whichStayIn').style.display = 'none';
      EHDObject.getCategories(EHDObject[targetName].code, displayProductFactory('depth3', '#whichStayIn'));
    }
    setDateLimitOnInput();
  }

  /////////////////////////////////////////////////////////////////////////
  // 가격조회 클릭 시 지정한 플랜 옵션이 선택된 상태로 가격출력하기 위한 알고리즘
  // 보장내역과 서비스는 html 생성 시 원하는 옵션이 체크된 상태로 rendering 하고
  // 보장내역과 서비스 체크박스 click event handler 를 함수 호출방식으로 호출해서 처리 한다
  /////////////////////////////////////////////////////////////////////////
  function serviceCheckBoxEventListener(event) {
    // 이벤트 객체를 new로 생성하여 호출한 경우 target 객체가 null 이기때문에 지정 함
    const element = event?.currentTarget ?? document.querySelector('input[type=checkbox][id^=service-0]');

    if (element.checked && (element.id === 'service-02' || element.id === 'service-03')) {
      document.getElementById('service-01').checked = true;
    } else if (!element.checked && element.id === 'service-01') {
      document.getElementById('service-02').checked = false;
      document.getElementById('service-03') ? (document.getElementById('service-03').checked = false) : '';
    }

    checkOptionCheckbox();
    calculateSelectedOptions();
  }

  function guaranteeCheckBoxEventListener(event) {
    const __ = EHDObject;
    const element = event?.currentTarget ?? document.querySelector('input[type=checkbox][id^=guarantee-]');

    if (!__.selectedItems || !__.selectedItems.guarantees) {
      __.selectedItems = { ...__.selectedItems, guarantees: [] };
    }

    if (element.checked) {
      __.selectedItems = {
        ...__.selectedItems,
        guarantees: [...__.selectedItems.guarantees, element.value],
      };
    } else {
      __.selectedItems = {
        ...__.selectedItems,
        guarantees: __.selectedItems.guarantees.filter((s) => s != element.value),
      };
    }
    calculateSelectedOptions();
  }

  // 가격상자 상단에 고정
  function fixPriceBox() {
    const fc = document.querySelector('.total-box-top');
    const fcHeight = fc.getBoundingClientRect().top + window.pageYOffset;

    window.scrollTo({ top: fcHeight, behavior: 'smooth' });
    window.addEventListener('scroll', function () {
      const boxHeight = 80;
      const totalBox = document.querySelectorAll('.total-box');
      if (window.scrollY > fcHeight) {
        totalBox.forEach((el, idx) => {
          el.zIndex = 9999;
          el.style.width = '93%';
          el.style.maxWidth = '672px';
          el.style.position = 'fixed';
          el.style.top = `${idx * boxHeight}px`;
        });
      } else {
        totalBox.forEach((el) => {
          el.style.top = '0px';
          el.style.width = '100%';
          el.style.maxWidth = '100%';
          el.style.position = 'relative';
        });
      }
    });
  }

  // 가격조회 버튼 클릭 이벤트 핸들러
  function onClickEventListenerForCalculateButton(event) {
    toogleProductBoard(false);

    if (!checkValidation()) {
      return false;
    }
    // 동반자가 있을 경우 동의 여부 체크
    const agreeElement = document.querySelector('#B-agree');
    if (document.querySelector('#B-count').value && !agreeElement.checked) {
      alert(agreeElement.getAttribute('placeholder'));
      return false;
    }

    EHDObject.selectedPlan = undefined;
    EHDObject.selectedItems = undefined;
    EHDObject.selectedPlanPrice = undefined;

    generatePlanInfo();
    generateProductBoard();
    toogleProductBoard(true);
    fixPriceBox();
  }

  function onClickEventListenerForNextButton(event) {
    const __ = EHDObject;
    const nextPage = './renewal_step02.php';
    const items = __.selectedItems;
    const checkedIns = items.guarantees.length > 0 ? true : false;
    const checkedSer = Array.isArray(items.services) && items.services.length > 0 ? true : false;
    let existsZeroPrice = false;

    if (!__.selectedPlan) {
      alert('선택된 플랜이 없습니다. \n보장내역/서비스를 다시 확인해 주시기 바랍니다.');
      return;
    }

    if (checkedIns) {
      if (Number.isNaN(__.customer.gPrice) || __.customer.gPrice <= 0) {
        alert('가입자에 맞는 보험료가 없습니다.');
        return;
      }

      existsZeroPrice =
        Array.isArray(__.companions) && __.companions.find((c) => Number.isNaN(c.gPrice) || c.gPrice <= 0);
      if (existsZeroPrice) {
        alert('동반자에 맞는 보험료가 없습니다.');
        return;
      }
    }

    if (checkedSer) {
      if (Number.isNaN(__.customer.sPrice) || __.customer.sPrice <= 0) {
        alert('가입자에 맞는 서비스료가 없습니다.');
        return;
      }

      existsZeroPrice =
        Array.isArray(__.companions) && __.companions.find((c) => Number.isNaN(c.sPrice) || c.sPrice <= 0);
      if (existsZeroPrice) {
        alert('동반자에 맞는 서비스료가 없습니다.');
        return;
      }
    }

    __.cleaning();
    __.save();
    location.href = nextPage;
  }

  function onChangeEventListnerForDepartureText(event) {
    const cDate = new Date();
    const cHour = cDate.getHours() + 1;
    const dDate = new Date(event.target.value);
    const oneDay = 24 * 60 * 60 * 1000;
    const fourDays = 4 * oneDay;
    const ninetyDays = 90 * oneDay;
    const oneYear = 365 * oneDay;
    const arrivalElement = document.getElementById('A-arrival');

    arrivalElement.value = '';
    if (EHDObject.isLongterm() === 1) {
      arrivalElement.max = EHDObject.getFormatedDate(new Date(dDate.getTime() + oneYear));
      if (!arrivalElement.value) {
        arrivalElement.value = EHDObject.getFormatedDate(new Date(dDate.getTime() + ninetyDays));
      }
    } else {
      arrivalElement.max = EHDObject.getFormatedDate(new Date(dDate.getTime() + ninetyDays));
      if (!arrivalElement.value) {
        arrivalElement.value = EHDObject.getFormatedDate(new Date(dDate.getTime() + fourDays));
      }

      // 단기상품의 경우 현재시간 + 1 시간 부터 선택가능하도록 설정
      if (EHDObject.getFormatedDate(cDate) === event.target.value) {
        optionGenerator('#A-departure-time', { start: cHour, end: 23, postFix: ' 시', defaultValue: cHour });
      } else {
        optionGenerator('#A-departure-time', { start: 0, end: 23, postFix: ' 시', defaultValue: 0 });
      }
    }
  }

  function renderedListenerBody(code, nodeList) {
    const elements = Array.from(nodeList);

    if (code) element = elements.find((e) => e.value === code);
    else element = elements[0];

    if (element) {
      element.checked = true;
      element.dispatchEvent(new Event('click'));
    } else {
      EHDObject.getPlanNames(processSelectedProduct);
    }
  }

  // when displayed depth2 product on the screen
  window.addEventListener('ehd.depth2.rendered', (event) => {
    renderedListenerBody(EHDObject.depth2?.code, document.querySelectorAll('#listOfProduct input[name=depth2]'));
  });

  // when displayed depth3 product on the screen
  window.addEventListener('ehd.depth3.rendered', (event) => {
    renderedListenerBody(EHDObject.depth3?.code, document.querySelectorAll('#whichStayIn input[name=depth3]'));
  });

  /////////////////////////////////////////////////////////////////////////////

  function setDateLimitOnInput() {
    const cDate = new Date();
    const cHour = cDate.getHours() + 1;
    const oneDay = 24 * 60 * 60 * 1000;
    const yDate = new Date(cDate.getTime() - oneDay);
    const nDate = new Date(cDate.getTime() + oneDay);
    let limitDate;

    limitDate = EHDObject.getFormatedDate(yDate);
    document.querySelectorAll('input[name$=birth]').forEach((el) => (el.max = limitDate));

    // 장기플랜인 경우 시간 수정 금지
    if (EHDObject.isLongterm() === 1) {
      limitDate = EHDObject.getFormatedDate(nDate);
      document.querySelectorAll('input[type=date]:not([name$=birth])').forEach((el) => {
        el.min = limitDate;
        if (el.id === 'A-departure') {
          const depth3Name = EHDObject.depth3 ? EHDObject.depth3.name : '';         
          if (depth3Name && depth3Name === '해외거주'){
            const tDate = new Date(cDate.getTime() + (oneDay * 3));
            el.min = EHDObject.getFormatedDate(tDate);
            el.value = EHDObject.getFormatedDate(tDate);
          } else {
            el.value = EHDObject.getFormatedDate(nDate);            
          }
          // el.value = EHDObject.getFormatedDate(nDate);
          el.dispatchEvent(new Event('change'));
        }


      });

      document.getElementById('A-arrival-time').setAttribute('disabled', 'true');
      document.getElementById('A-departure-time').setAttribute('disabled', 'true');
    } else {
      limitDate = EHDObject.getFormatedDate(cDate);
      document.querySelectorAll('input[type=date]:not([name$=birth])').forEach((el) => {
        el.min = limitDate;
        if (!el.value && el.id === 'A-departure') {
          el.value = EHDObject.getFormatedDate(cDate);
          optionGenerator('#A-departure-time', { start: cHour, end: 23, postFix: ' 시', defaultValue: 0 });
          el.dispatchEvent(new Event('change'));
        }
      });
    }
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
    const type = option.type;
    const padLength = option.padLength || 2;

    for (let i = start; i <= end; i = i + interval) {
      const val = type === 'number' ? i : String(i).padStart(padLength, '0');
      html.push(
        `<option value="${val}" ${option.defaultValue == val ? 'selected' : ''}>${preFix}${val}${postFix}</option>`
      );
    }
    if (option.existCover) {
      html.unshift(`<option value="">${option.coverText}</option>`);
    }

    document.querySelector(selector).innerHTML = html.join('');
  }

  document.addEventListener('DOMContentLoaded', function (event) {
    if (!EHDObject.depth1?.code) {
      location.href = './renewal_step00.php';
    }

    EHDObject.getCategories(EHDObject.depth1.code, displayProductFactory('depth2', '#listOfProduct'));

    optionGenerator('#B-count', {
      start: 1,
      end: 5,
      existCover: true,
      coverText: '없음',
      postFix: ' 명',
      type: 'number',
    });
    optionGenerator('#A-departure-time', { start: 0, end: 23, postFix: ' 시', defaultValue: 0 });
    optionGenerator('#A-arrival-time', { start: 0, end: 23, postFix: ' 시', defaultValue: 23 });

    // 출국일이 변경되면 귀국일 최대 입력 가능일은 귀국일 + 365일로 지정
    document.getElementById('A-departure').addEventListener('change', onChangeEventListnerForDepartureText);

    document.querySelector('#B-count').addEventListener('change', (e) => generateCompanionForms(e.currentTarget.value));
    document.querySelector('button.calculate').addEventListener('click', onClickEventListenerForCalculateButton);

    document.querySelector('a.write').addEventListener('click', onClickEventListenerForNextButton);

    setDateLimitOnInput();
  });
</script>
<?php
include '../_include/_tail.html';
include '../_include/_footer.html';
?>

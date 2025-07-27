<?php
include '../_include/_header_new.html';
include '../_include/_top.html';
?>
      <section>
        <div class="container">
          <div class="title-box">
            <h2>약관 동의</h2>
            <p>
              <b><em>가입자 정보</em></b
              >를 입력해 주세요.
            </p>
          </div>

          <div class="white-box middle mt24" id="form_abroad_resident">
            <form>
              <div class="form-box">
                <div class="form-title">
                  <div class="flex flex-vc flex-tj">
                    <strong>해외거주</strong>
                  </div>
                </div>
                <div class="form-content">
                  <div class="form-border">
                    <div class="form-question">
                      <p>
                        현재 외국에 거주중이거나 가입하는 장소가 외국이십니까?<br />
                        해외체류 중 가입은 유학, 법인소속 해외근무자(주재원, 공무원, 교환교수 등)만 가입하실 수
                        있습니다.
                      </p>
                    </div>
                    <!-- 0830 자동체크해제 -->
                    <div class="flex flex-vc px24 px-lg-20">
                      <div class="check-box mr50 mr-lg-25">
                        <div class="check-box-inner">
                          <input type="radio" name="is_abroad_resident" id="is_abroad_resident_y" value="Y" />
                          <label for="is_abroad_resident_y" class="fw4">예</label>
                        </div>
                      </div>
                      <div class="check-box">
                        <div class="check-box-inner">
                          <input type="radio" name="is_abroad_resident" id="is_abroad_resident_n" value="N" checked/>  
                          <label for="is_abroad_resident_n" class="fw4">아니오</label>
                        </div>
                      </div>
                    </div>
                    <!-- // 0830 자동체크해제 -->
                  </div>
                </div>
              </div>
            </form>
          </div>

          <div class="white-box middle mt24">
            <form>
              <div class="form-box">
                <div class="form-title">
                  <div class="flex flex-vc flex-tj">
                    <strong>가입전 알릴 사항<b>(필수)</b></strong>
                    <div class="check-box all-check-box">
                      <div class="check-box-inner">
                        <input type="radio" name="allcheck01" id="allcheck01" data-group="check01" data-text="N" />
                        <label for="allcheck01" class="fw4">전체 아니오</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-content" id="notice_contents"></div>
              </div>
            </form>
          </div>

          <div class="white-box middle mt24">
            <form>
              <div class="form-box" id="agree_box">
                <div class="form-title">
                  <div class="flex flex-vc flex-tj">
                    <strong>약관 확인 후 동의해 주세요.<b>(필수)</b></strong>
                    <div class="check-box all-check-box col-lg-12 mt-lg-10">
                      <div class="check-box-inner flex-tr">
                        <!-- <input type="radio" name="allcheck02" id="allcheck02" data-group="check02" data-text="Y" /> -->
                        <!-- <label for="allcheck02" class="fw4">전체 약관 동의</label>-->
                         <!-- 20250722 추가가 -->
                        <input type="radio" name="requiredcheck02" id="requiredcheck02" data-group="requiredcheck02" data-text="Y" />
                        <label for="requiredcheck02" class="fw4">필수 전체 동의</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="form-content">

                     <div class="form-border">
                    <div class="form-question">
                      <div class="flex flex-vc flex-tj">
                        <p>개인정보 수집·이용 동의서(필수)</p>
                        <a href="javascript:;" onclick="popupOpenRule('개인정보 수집·이용 동의서');">자세히 보기</a>
                      </div>
                    </div>
                    <div class="flex flex-vc px24 px-lg-20">
                      <div class="check-box mr50 mr-lg-25" data-group="requiredcheck02">
                        <div class="check-box-inner">
                          <input type="radio" name="radio01" id="radio01_y" value="Y" />
                          <label for="radio01_y" class="fw4">약관동의</label>
                        </div>
                      </div>
                      <div class="check-box" data-group="requiredcheck02">
                        <div class="check-box-inner">
                          <input type="radio" name="radio01" id="radio01_n" value="N" checked/>
                          <label for="radio01_n" class="fw4">약관 미동의</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-border">
                    <div class="form-question">
                      <div class="flex flex-vc flex-tj">
                        <p>고유식별정보 처리 동의서</p>
                        <a href="javascript:;" onclick="popupOpenRule('고유식별정보 처리 동의서');">자세히 보기</a>
                      </div>
                    </div>
                    <div class="flex flex-vc px24 px-lg-20">
                      <div class="check-box mr50 mr-lg-25" data-group="check02">
                        <div class="check-box-inner">
                          <input type="radio" name="radio02" id="radio02_y" value="Y" />
                          <label for="radio02_y" class="fw4">약관동의</label>
                        </div>
                      </div>
                      <div class="check-box" data-group="check02">
                        <div class="check-box-inner">
                          <input type="radio" name="radio02" id="radio02_n" value="N" checked/>
                          <label for="radio02_n" class="fw4">약관 미동의</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-border">
                    <div class="form-question">
                      <div class="flex flex-vc flex-tj">
                        <p>민감정보 처리 동의서</p>
                        <a href="javascript:;" onclick="popupOpenRule('민감정보 처리 동의서');">자세히 보기</a>
                      </div>
                    </div>
                    <div class="flex flex-vc px24 px-lg-20">
                      <div class="check-box mr50 mr-lg-25" data-group="check02">
                        <div class="check-box-inner">
                          <input type="radio" name="radio03" id="radio03_y" value="Y" />
                          <label for="radio03_y" class="fw4">약관동의</label>
                        </div>
                      </div>
                      <div class="check-box" data-group="check02">
                        <div class="check-box-inner">
                          <input type="radio" name="radio03" id="radio03_n" value="N" checked/>
                          <label for="radio03_n" class="fw4">약관 미동의</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-border">
                    <div class="form-question">
                      <div class="flex flex-vc flex-tj">
                        <p>개인(신용)정보 제공 동의서 (선택)</p>
                        <a href="javascript:;" onclick="popupOpenRule('개인(신용)정보 제공 동의서');">자세히 보기</a>
                      </div>
                    </div>
                    <div class="flex flex-vc px24 px-lg-20">
                      <div class="check-box mr50 mr-lg-25" data-group="check02">
                        <div class="check-box-inner">
                          <input type="radio" name="radio04" id="radio04_y" value="Y" />
                          <label for="radio04_y" class="fw4">약관동의</label>
                        </div>
                      </div>
                      <div class="check-box" data-group="check02">
                        <div class="check-box-inner">
                          <input type="radio" name="radio04" id="radio04_n" value="N" checked/>
                          <label for="radio04_n" class="fw4">약관 미동의</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-border">
                    <div class="form-question">
                      <div class="flex flex-vc flex-tj">
                        <p>개인(신용)정보 조회 동의서 (선택)</p>
                        <a href="javascript:;" onclick="popupOpenRule('개인(신용)정보 조회 동의서');">자세히 보기</a>
                      </div>
                    </div>
                    <div class="flex flex-vc px24 px-lg-20">
                      <div class="check-box mr50 mr-lg-25" data-group="check02">
                        <div class="check-box-inner">
                          <input type="radio" name="radio05" id="radio05_y" value="Y" />
                          <label for="radio05_y" class="fw4">약관동의</label>
                        </div>
                      </div>
                      <div class="check-box" data-group="check02">
                        <div class="check-box-inner">
                          <input type="radio" name="radio05" id="radio05_n" value="N" checked/>
                          <label for="radio05_n" class="fw4">약관 미동의</label>
                        </div>
                      </div>
                    </div>
                  </div>
                    
                  <div class="form-border">
                    <div class="form-question">
                      <div class="flex flex-vc flex-tj">
                        <p>개인정보 수집·이용 동의서(영문성명) (선택)</p>
                        <a href="javascript:;" onclick="popupOpenRule('개인정보 수집·이용 동의서(영문성명)');">자세히 보기</a>
                      </div>
                    </div>
                    <div class="flex flex-vc px24 px-lg-20">
                      <div class="check-box mr50 mr-lg-25" data-group="check02">
                        <div class="check-box-inner">
                          <input type="radio" name="radio06" id="radio06_y" value="Y" />
                          <label for="radio06_y" class="fw4">약관동의</label>
                        </div>
                      </div>
                      <div class="check-box" data-group="check02">
                        <div class="check-box-inner">
                          <input type="radio" name="radio06" id="radio06_n" value="N" checked/>
                          <label for="radio06_n" class="fw4">약관 미동의</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="form-border">
                    <div class="form-question">
                      <div class="flex flex-vc flex-tj">
                        <p>마케팅 활용 동의서 (선택)</p>
                        <a href="javascript:;" onclick="popupOpenRule('마케팅 활용 동의서');">자세히 보기</a>
                      </div>
                    </div>
                    <div class="flex flex-vc px24 px-lg-20">
                      <div class="check-box mr50 mr-lg-25" data-group="check02">
                        <div class="check-box-inner">
                          <input type="radio" name="radio07" id="radio07_y" value="Y" />
                          <label for="radio07_y" class="fw4">약관동의</label>
                        </div>
                      </div>
                      <div class="check-box" data-group="check02">
                        <div class="check-box-inner">
                          <input type="radio" name="radio07" id="radio07_n" value="N" checked/>
                          <label for="radio07_n" class="fw4">약관 미동의</label>
                        </div>
                      </div>
                    </div>
                  </div>

                  

                </div>
              </div>
              <div class="button-box mt42 mt-lg-24">
                <button type="button" class="btn btn-active write">가입하기</button>
              </div>
            </form>
          </div>
          <!-- 0922 수정 -->
          <div class="white-box middle mt24 type01">
            <button type="button" class="btn-more off type01">가입시 유의사항</button>

            <div class="caution-box mt12" style="display: none;">
              <h6>01. 보험계약체결</h6>
              <p>보험계약 체결 전에 상품설명서 및 약관을 읽어보시기 바랍니다. 보험계약자가 기존에 체결했던 보험계약을 해지하고 다른 보험계약을 체결하면 보험인수가 거절되거나 상품가격이 인상되거나 보장내용이 달라질 수 있습니다.</p><br/>
              <h6>02. 보험금을 지급하지 않는 사유</h6>
              <p>계약자나 보험수익자 또는 피보험자의 고의, 자해, 범죄 또는 폭력행위, 형의 집행, 전쟁, 혁명, 내란, 폭동, 핵연료물질, 방사선 등 면책사항은 보험약관에 자세히 명시되어 있습니다.</p><br/>
              <h6>03. 실손의료비 보험금 지급 관련 유의사항</h6>
              <p>발생 의료비 중 국민건강보험 급여의 본인부담금과 비급여를 보장해주는 보험이며, 약관상 보장제외 항목에서 발생한 의료비는 보장되지 않습니다. 실제 발생한 의료비를 보상하는 보험을 2개 이상 가입하더라도 실제 발생한 비용만을 보상받게 되므로, 유사한 보험가입여부 및 보상한도를 반드시 확인하시기 바랍니다. 보험금을 지급할 다수의 보험계약이 체결되어 있는 경우에는 각각의 계약에 대하여 다른 계약이 없는 것으로 하여 산출한 보상책임액의 합계액이 이 계약의 의료비를 초과했을 때, 이 계약에 따른 보상책임액의 위의 합계액에 대한 비율에 따라 의료비보험금을 지급하여 드립니다.</p><br/>
              <h6>04. 청약철회 청구제도</h6>
              <p>계약자는 보험증권을 받은 날로부터 15일 이내에 그 계약의 청약을 철회할 수 있으며 이 경우 이미 납입한 상품가격을 돌려드립니다. 다만, 진단계약, 보험기간이 1년 미만인 계약인 계약 또는 전문금융소비자가 체결한 계약의 경우에는 청약을 철회할 수 없으며 청약을 한 날로부터 30일이 초과된 계약은 청약을 철회할 수 없습니다.</p><br/>
              <h6>05. 보험계약상의 알릴 의무</h6>
              <p>보험계약 청약 시 계약자 및 피보험자는 청약서상의 질문사항(고지사항)에 대하여 사실대로 알려야 합니다. 만일 허위 또는 부실하게 알렸을 경우에는 보험사고 발생 시 보상이 되지 않음은 물론 보험계약이 해지될 수 있습니다.</p><br/>
              <h6>06. 보험사 3대 기본 지키기</h6> 
              <ul>
                <li>1. 자필서명</li> 
                <li>2. 계약자 보관용 청약서 전달</li> 
                <li>3. 약관 전달 및 주요 내용 설명 </li>
              </ul>
              <p>보험사가 3대 기본 지키기 미이행 시 보험계약자는 계약이 성립한 날로부터 3개월 이내에 계약을 취소할 수 있습니다. 이 경우 회사는 이미 납입한 상품가격을 돌려 드립니다.</p><br/>
              <h6>07. 해지환급금 산출기준</h6>
              <p>계약자, 피보험자 또는 보험수익자의 책임없는 사유에 의하는 경우 : 무효의 경우에는 회사에 납입한 상품가격의 전액, 효력상실, 해지 또는 소멸의 경우에는 경과하지 않은 기간에 대하여 일 단위로 계산한 상품가격</p>
              <p>계약자, 피보험자 또는 보험수익자의 책임있는 사유에 의하는 경우 : 이미 경과한 기간에 대하여 단기요율(1년 미만의 기간에 적용되는 요율)로 계산한 상품가격을 뺀 잔액. 다만, 계약자, 피보험자 또는 보험수익자의 고의 또는 중대한 과실로 무효가 된 때에는 상품가격을 돌려드리지 않습니다.</p><br/>
              <h6>08. 예금자 보호 안내</h6>
              <p>이 보험계약은 예금자보호법에 따라 예금보험공사가 보호하되, 보호 한도는 본 보험회사에 있는 귀하의 모든 예금보호 대상 금융상품의 해약환급금(또는 만기 시 보험금이나 사고보험금)에 기타지급금을 합하여 1인당 "최고 5천만 원"이며, 5천만 원을 초과하는 나머지 금액은 보호하지 않습니다. 다만, 보험계약자 및 상품가격 납부자가 법인이면 보호되지 않습니다. 위 내용은 예금자보호법 및 관련 법령의 개정에 따라 달라질 수 있으며, 자세한 내용은 <a href="www.kdic.or.kr" target="_blank">예금보험공사(www.kdic.or.kr)</a>로 문의하시기 바랍니다.</p><br/>
              <h6>09. 상담 및 보험분쟁 조정 안내</h6>
              <p>보험에 대한 문의사항 및 불만사항이 있을 경우 한화손해보험(1566-8000), 메리츠화재(1566-7711), 신한EZ손해보험(1544-2580)으로 연락주시면 신속하게 처리해 드리겠습니다. 또한 보험에 관한 분쟁이 있을 때에는 금융감독원 및 한국소비자원에 분쟁조정을 신청할 수 있습니다.</p>
              <p>금융감독원 전화 : 1332 / 인터넷 : <a href="www.fas.or.kr" target="_blank">www.fas.or.kr</a> (<a href="www.fcsc.kf" target="_blank">e-금융센터 : www.fcsc.kf</a>) </p>
              <p>한국소비자원 전화 : 1372 / 인터넷 : <a href="www.kca.go.kr" target="_blank">www.kca.go.kr</a></p><br/>
              <h6>10. 청약철회제도</h6>
              <p>전문금융소비자가 체결한 보험 계약 또는 청약일부터 30일 초과계약(65세 이상 계약자가 전화로 체결한 계약은 45일 초과 시), 회사가 건강상태 진단을 지원하는 계약, 보험기간(가입기간)이 90일 이내인 계약은 청약을 철회할 수 없습니다.</p>
              <p>【일반금융소비자】 전문금융소비자가 아닌 금융소비자를 말합니다.</p>
              <p>【전문금융소비자】 보험계약에 관한 전문성, 자산규모 등에 비추어 보험계약에 따른 위험감수능력이 있는 자로서, 국가, 지방자치단체, 한국은행, 금융회사, 주권상장법인 등을 포함하며, 「금융소비자보호에 관한 법률」 제2조제9호에서 정하는 전문금융소비자인 계약자를 말합니다.</p>

            </div>
          </div>
          <!-- // 0922 수정 -->          
        </div>
        <div class="popup-box">
          <div class="box" id="popup" style="max-width: 720px"></div>
        </div>
      </section>

    <script src="./js/swiper.js?a=1"></script>
    <script src="./js/ehd-object.js"></script>
    <script>
      //약관 자세히 보기 팝업
      function popupOpenRule(type) {
        $.get(
        //   "pop_rule_site_renewal.php",{"policy_name": type},
          "pop_rule_site_renewal_step03.php",{"policy_name": type},
          function(data) {
            console.log(data);
            document.getElementById('popup').innerHTML = data;
          },
          "html");

        let box = $('.popup-box .box');

        $('html, body, .popup-box').addClass('fixed');
        box.show();
        box.siblings().hide();
      }

      function validation() {
        let bool = true;
        let notice_chk = true;
        let agree_chk = true;
        let noticeCheck = EHDObject.productNotice;

        //알릴사항 확인
        if(noticeCheck.length > 0){
          $("#notice_contents input[type='radio']").each(function(i){
            if($(this).is(":checked")) {
              if($(this).val() == "Y") {
                alert("알릴사항을 다시 확인해 주세요.");
                $(this).focus();
                bool = false;
                return bool;
              }
            }
          });
        }

        $("#agree_box .check-box").each(function(i){
        //   console.log($(this).data("group"))
            if($(this).data("group") == "requiredcheck02" ) {
                if($(this).find("input[type='radio']:checked").val() == "N") {
                alert("필수 약관에 동의 해주셔야 합니다.");
                bool = false;
                return bool;
                }
            }
        });

        //약관 확인
        // $("#agree_box input[type='radio']").each(function(i){
        //   if($(this).is(":checked")) {
        //     if($(this).val() == "N") {
        //       alert("약관에 동의 해주셔야 합니다.");
        //       bool = false;
        //       return bool;
        //     }
        //   }
        // });
        return bool;
      }

      window.addEventListener('load', (e) => {
        if (!EHDObject || !EHDObject.selectedPlan) {
          location.href = './renewal_step01.php';
          return;
        }

        // 가입시 유의사항
        $('.btn-more').on('click', function() {
          $(this).next().slideToggle();
          $(this).toggleClass('off');
        })

        document.querySelector('button.write').addEventListener('click', (e) => {
          const survey = {};
          let prNotice = document.querySelectorAll("input[name^='pr_notice[']");
          let abroadResident = document.querySelector('input[name="is_abroad_resident"]:checked').value;
          if (!validation()) {
            return;
          }

          prNotice.forEach((item, idx) => {
            const element = item;
            survey[idx] = element.value;
          });

          EHDObject.customer.survey = survey;
          EHDObject.customer.is_abroad_resident = abroadResident;
          EHDObject.save();
          location.href = './renewal_step04.php';
        });

        if(EHDObject.isLongterm() && EHDObject.selectedPlan.is_notification_visible) {
          document.getElementById('form_abroad_resident').style.display = 'none';
        } else {
          document.getElementById('form_abroad_resident').style.display = '';
        }

        EHDObject.getPlanInfo({ api: EHDObject.GET_NOTICE, pr_cd: EHDObject.selectedPlan.pr_cd }, () => {
          const noticeList = EHDObject.productNotice;
          let noticeBody = '';
          let noticeContents = document.getElementById('notice_contents');
          const inAgreeFile = [EHDObject.selectedPlan.ins_term1_realname, EHDObject.selectedPlan.ins_term2_realname];
          const serviceAgreeFile = EHDObject.selectedPlan.service_term_realname;

          //알릴사항
          noticeList.forEach((item, idx, arr) => {
            let noticeBorder = 
            '<div class="form-border"> \
              <div class="form-question"> \
                  <input type="hidden" name="pr_notice['+idx+']" value="'+item['pr_notice']+'"> \
                <p>'+item['pr_notice']+'</p> \
              </div> \
              <div class="flex flex-vc px24 px-lg-20"> \
                <div class="check-box mr50 mr-lg-25" data-group="check01"> \
                  <div class="check-box-inner"> \
                    <input type="radio" name="radio'+idx+'" id="radio'+idx+'_y" value="Y" checked/> \
                    <label for="radio'+idx+'_y" class="fw4">예</label> \
                  </div> \
                </div> \
                <div class="check-box" data-group="check01"> \
                  <div class="check-box-inner"> \
                    <input type="radio" name="radio'+idx+'" id="radio'+idx+'_n" value="N" /> \
                    <label for="radio'+idx+'_n" class="fw4">아니오</label> \
                  </div> \
                </div> \
              </div> \
            </div>';
            noticeBody += noticeBorder;
          });
          noticeContents.innerHTML = noticeBody;
         /* 
          let agreeBox = document.getElementById('agree_box');
          inAgreeFile.forEach((item, idx) => {
            if(item) {
              let agreeBody = '<div class="form-border"> \
                <div class="form-question"> \
                  <div class="flex flex-vc flex-tj"> \
                    <p>보험 가입약관 동의</p> \
                    <a href="/_data/board/ins_agree/'+item+'" target="_blank">자세히 보기</a> \
                  </div> \
                </div> \
                <div class="flex flex-vc px24 px-lg-20"> \
                  <div class="check-box mr50 mr-lg-25" data-group="check02"> \
                    <div class="check-box-inner"> \
                      <input type="radio" name="radio'+idx+'" id="radio'+idx+'_y" value="Y" /> \
                      <label for="radio'+idx+'_y" class="fw4">약관동의</label> \
                    </div> \
                  </div> \
                  <div class="check-box" data-group="check02"> \
                    <div class="check-box-inner"> \
                      <input type="radio" name="radio'+idx+'" id="radio'+idx+'_n" value="N" checked/> \
                      <label for="radio'+idx+'_n" class="fw4">약관 미동의</label> \
                    </div> \
                  </div> \
                </div> \
              </div>';
              agreeBox.innerHTML = agreeBox.innerHTML + agreeBody;
            }
          });

          if(serviceAgreeFile) {
              console.log(serviceAgreeFile);
              let sAgreeBody = '<div class="form-border"> \
                <div class="form-question"> \
                  <div class="flex flex-vc flex-tj"> \
                    <p>서비스 약관 동의</p> \
                    <a href="/_data/board/service_agree/'+serviceAgreeFile+'" target="_blank">자세히 보기</a> \
                  </div> \
                </div> \
                <div class="flex flex-vc px24 px-lg-20"> \
                  <div class="check-box mr50 mr-lg-25" data-group="check02"> \
                    <div class="check-box-inner"> \
                      <input type="radio" name="s_radio" id="s_radio_y" value="Y" /> \
                      <label for="s_radio07_y" class="fw4">약관동의</label> \
                    </div> \
                  </div> \
                  <div class="check-box" data-group="check02"> \
                    <div class="check-box-inner"> \
                      <input type="radio" name="s_radio" id="s_radio_n" value="N" checked/> \
                      <label for="s_radio07_n" class="fw4">약관 미동의</label> \
                    </div> \
                  </div> \
                </div> \
              </div>';
              agreeBox.innerHTML = agreeBox.innerHTML + sAgreeBody;
            } 
*/
        });
      });
    </script>
<?php
include '../_include/_tail.html';
include '../_include/_footer.html';
?> 

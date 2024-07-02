<?php
include '../_include/_header_new.html';
include '../_include/_top.html';
?>
      <section>
        <div class="container">
          <div class="title-box">
            <h2>가입자 정보 입력</h2>
            <p>
              <b><em>가입자 정보</em></b>를 입력해 주세요.
            </p>
          </div>
          <div class="white-box middle mt24">
            <form>
              <div class="form-box">
                <div class="form-title">
                  <strong>이름</strong>
                </div>
                <div class="form-content">
                  <div class="input-box">
                    <div class="input-box-inner">
                      <input
                        type="text"
                        name="A-name"
                        id="A-name"
                        placeholder="한글 이름을 입력해주세요."
                        maxlength="30"
                        required
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-box">
                <div class="form-title">
                  <strong>주민등록번호(또는 외국인등록번호)</strong>
                </div>
                <div class="form-content">
                  <div class="flex flex-vc">
                    <div class="form-text-box mr15"><b>&nbsp;</b>&nbsp;&nbsp;&nbsp;-</div>
                    <div class="input-box flex-lg-1">
                      <div class="input-box-inner">
                        <input
                          type="number"
                          name="A-num2"
                          id="A-num2"
                          data-regexp="^[0-9]{7}$"
                          placeholder="주민등록번호 뒷자리"
                          max="9999999"
                          maxlength="7"
                          oninput="maxLengthCheck(this)"
                          required
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-box">
                <div class="form-title">
                  <strong>휴대폰 번호</strong>
                </div>
                <div class="form-content">
                  <div class="input-box">
                    <div class="input-box-inner">
                      <input
                        type="text"
                        name="A-cellphone"
                        id="A-cellphone"
                        placeholder="예)01088889999"
                        maxlength="50"
                        required
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-box">
                <div class="form-title">
                  <strong>이메일 주소</strong>
                </div>
                <div class="form-content">
                  <div class="flex flex-vc">
                    <div class="input-box flex-1">
                      <div class="input-box-inner">
                        <input
                          type="text"
                          name="A-emailid"
                          id="A-emailid"
                          placeholder="이메일 아이디"
                          maxlength="50"
                          required
                        />
                      </div>
                    </div>
                    <div class="form-text-box mx15 mx-lg-10">
                      <b>@</b>
                    </div>
                    <div class="input-box flex-1" style="margin-right: 10px;">
                      <div class="input-box-inner">
                        <input
                          type="text"
                          name="A-emailaddress"
                          id="A-emailaddress"
                          placeholder="이메일 서버"
                          maxlength="50"
                          required
                        />
                      </div>
                    </div>
                    <div class="select-box flex-1">
                      <div class="select-box-inner">
                        <select name="A-emailhost" id="A-emailhost">
                          <option value="">선택</option>
                          <option value="naver.com">naver.com</option>
                          <option value="daum.net">daum.net</option>
                          <option value="nate.com">nate.com</option>
                          <option value="gmail.com">gmail.com</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-box">
                <div class="form-title">
                  <strong>방문국가</strong>
                </div>
                <div class="form-content">
                  <div class="input-box flex-1">
                    <div class="input-box-inner">
                      <input
                        type="text"
                        name="A-nationname"
                        id="A-nationname"
                        placeholder="방문국가를 선택해주세요."
                        required
                        readonly
                      />
                      <input type="hidden" name="A-nationcode" id="A-nationcode" required />
                      <a href="javascript:void(0)" onClick="openLayer(1)" class="search">검색</a>
                    </div>
                  </div>
                  <div class="form-notice-box mt8">
                    <!-- <p>※ 여러국가를 방문하는 경우 <b>첫번째 체류 국가</b>를 선택해 주세요.</p> -->
                  </div>
                </div>
              </div>
              <div class="form-box">
                <div class="form-title">
                  <strong>영문이름</strong>
                </div>
                <div class="form-content">
                  <div class="input-box flex-1">
                    <div class="input-box-inner">
                      <input
                        type="text"
                        name="A-nameen"
                        id="A-nameen"
                        maxlength="100"
                        placeholder="영문이름 (영문가입증명서 필요시)"
                      />
                    </div>
                  </div>
                </div>
              </div>
              <div class="title-box gr" id="companion-list" style="display: none">
                <p>
                  <b><em>동반인 정보</em></b> 를 입력해 주세요.
                </p>
              </div>

              <div class="button-box mt42 mt-lg-24">
                <button type="button" class="btn btn-active write">다음</button>
              </div>
            </form>
          </div>
        </div>
      </section>

    <!-- 레이어 팝업 -->

    <div class="dim-bg" style="display: none; opacity: 0"></div>
    <div class="layer-container" style="display: none; opacity: 0" data-layer="layer01">
      <div class="layer-box" style="overflow: hidden">
        <h3 class="layer-title">방문국가 검색</h3>
		    <div class="notice-box">
            ※ 여러 국가를 방문하는 경우 <b>첫번째 체류 국가</b>나 <b>체류기간이 긴 국가</b>로 선택해주세요.<br />
            ※ 여행가는 나라의 여행 제한/금지 여부를 확인하세요.<br />
            외교부 지정 적색/흑색경보(철수권고/여행금지) 국가로 여행하는 경우 보험가입과 보상이 불가합니다.<br />
          <div class="flex flex-tj mt24">
            <div class="button-box w150">
              <a href="https://www.0404.go.kr/dev/main.mofa" class="btn btn-active btn-s" target="_blank">외교부 사이트</a>
            </div>
            <div class="check-box">
              <div class="check-box-inner">
                <input type="checkbox" name="searchNation" id="searchNation" checked/>
                <label for="check">확인</label>
              </div>
            </div>
          </div>
        </div>
        <div class="form-box mt24">
          <div class="form-content">
            <div class="input-box flex-1">
              <div class="input-box-inner">
                <input type="text" name="searching_keyword" placeholder="방문국가를 검색해 주세요." />
                <a href="javascript:void(0)" class="search">방문국가검색</a>
              </div>
            </div>
          </div>
        </div>
        <div class="country-wrap">
          <table class="table-type01">
            <colgroup>
              <col style="width: 50%" />
              <col style="width: 50%" />
            </colgroup>
            <thead>
              <tr>
                <th>국가명</th>
                <th>여행가능</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <button type="button" class="btn-close" data-layer-btn="layer01" onClick="closeLayer(1)">
          <span class="tts">팝업 닫기</span>
        </button>
      </div>
    </div>

    <!-- // 레이어 팝업 -->

    <script src="./js/swiper.js?a=1"></script>
    <script src="./js/ehd-object.js"></script>
    <script>
      function checkPrivateNumber(pNum){
        const forien = [5, 6, 7, 8]
        const arr = [2,3,4,5,6,7,8,9,2,3,4,5];
        const num = pNum.replace(/-/g, '');
        const magicNumber = 11;

        if (!num || num.length !== 13) return false;
        else if (forien.includes(Number(num[6]))) return true;

        const fields = arr.map((n, i) => n * num[i]);
        const sum = fields.reduce((p, n) => p + n, 0);

        return Number(num[12]) === ((magicNumber - (sum % magicNumber)) % 10 );
      }

      function generateCustomerForm() {
        const customer = EHDObject.customer;
        const birth = customer.birth.replace(/-/gi, '').substring(2);

        document.getElementById('A-num2').setAttribute('data-birth', birth);
        document.getElementById('A-num2').closest('div.flex').querySelector('b').textContent = birth;
      }

      function generateCompanionForms() {
        const companions = EHDObject.companions;
        const html = [];

        if (Array.isArray(companions) && companions.length > 0) {
          companions.forEach((comp, idx) => {
            const birth = comp.birth.replace(/-/gi, '').substring(2);
            html.push(`<div class="form-box mt24">`);
            html.push(`  <div class="form-title"><strong>이름</strong></div>`);
            html.push(`  <div class="form-content">`);
            html.push(`    <div class="input-box">`);
            html.push(`      <div class="input-box-inner">`);
            html.push(
              `        <input type="text" id="B-name${idx}" maxlength="30" placeholder="한글 이름을 입력해주세요." required />`
            );
            html.push(`      </div>`);
            html.push(`    </div>`);
            html.push(`  </div>`);
            html.push(`</div>`);
            html.push(`<div class="form-box">`);
            html.push(`  <div class="form-title"><strong>주민등록번호(또는 외국인등록번호)</strong></div>`);
            html.push(`  <div class="form-content">`);
            html.push(`    <div class="flex flex-vc">`);
            html.push(`      <div class="form-text-box mr15"><b>${birth} &nbsp;-</b></div>`);
            html.push(`      <div class="input-box flex-lg-1">`);
            html.push(`        <div class="input-box-inner">`);
            html.push(`          <input type="number" id="B-num2${idx}" data-regexp="^[0-9]{7}$" max="9999999" `);
            html.push(`          maxlength="7" oninput="maxLengthCheck(this)" data-birth=${birth} `);
            html.push(`          placeholder="주민등록번호 뒷자리" required />`);
            html.push(`        </div>`);
            html.push(`      </div>`);
            html.push(`    </div>`);
            html.push(`  </div>`);
            html.push(`</div>`);
            html.push(`<div class="form-box">`);
            html.push(`  <div class="form-title"><strong>영문이름</strong></div>`);
            html.push(`  <div class="form-content">`);
            html.push(`    <div class="input-box flex-1">`);
            html.push(`      <div class="input-box-inner">`);
            html.push(
              `        <input type="text" id="B-nameen${idx}" maxlength="100" placeholder="영문이름 (영문가입증명서 필요시)" />`
            );
            html.push(`      </div>`);
            html.push(`    </div>`);
            html.push(`  </div>`);
            html.push(`</div>`);
          });

          document.getElementById('companion-list').insertAdjacentHTML('afterend', html.join(''));
          document.getElementById('companion-list').style.display = 'block';
        }
      }

      function selectNationEventHadler(e){
        document.getElementById('A-nationcode').value = e.currentTarget.dataset.c;
        document.getElementById('A-nationname').value = e.currentTarget.dataset.n;
        closeLayer(1);
      }

      function generateProductCountiries(list) {
        const html = [];
        const dataList = list || EHDObject.countries;
        const layer = document.querySelector('div[data-layer=layer01]');

        if (Array.isArray(dataList) && dataList.length > 0) {
          dataList.forEach((c) => {
            html.push(`<tr>`);
            html.push(`  <td>${c.c_name}</td>`);
            html.push(`  <td>`);
            html.push(`    <div class="button-box flex-tc">`);
            html.push(
              `      <button class="btn btn-white btn-xs" data-c="${c.c_code}" data-n="${c.c_name}">선택</button>`
            );
            html.push(`    </div>`);
            html.push(`  </td>`);
            html.push(`</tr>`);
          });
        }

        layer.querySelector('tbody').innerHTML = html.join('');
        layer.querySelectorAll('button[data-c]').forEach((e) => {
          e.addEventListener('click', selectNationEventHadler);
        });
      }

      function checkValidation() {
        const inputList = Array.from(document.querySelectorAll('input[required]'));
        const element = inputList.find((e) => {
          const val = e.value.trim();

          if (!val) {
            return true;
          } else if (e.dataset.regexp) {
            const regexp = new RegExp(e.dataset.regexp);
            return !regexp.test(val);
          }
        });

        if (element) {
          alert(element.getAttribute('placeholder'));
          element.focus();
          return false;
        }
        return true;
      }

      function maxLengthCheck(object){
        if (object.value.length > object.maxLength){
          object.value = object.value.slice(0, object.maxLength);
        }    
      }

      window.addEventListener('load', () => {
        const layer = document.querySelector('div[data-layer=layer01]');
        const layerButton = layer.querySelector('a.search');
        const layerinput = layer.querySelector('input[name=searching_keyword]');

        if (!EHDObject || !EHDObject.selectedPlan) {
          location.href = './renewal_step01.php';
          return;
        }

        generateCustomerForm();
        generateCompanionForms();

        document.getElementById('A-emailhost').addEventListener('change', (e) => {
          const input = document.getElementById('A-emailaddress');
          const emailHost = e.currentTarget.querySelector(':checked').value;
          input.value = emailHost;
        });

        EHDObject.getPlanInfo(
          { api: EHDObject.GET_PRODUCT_COUNTRY, pr_cd: EHDObject.selectedPlan.pr_cd },
          generateProductCountiries
        );

        // 가입하기
        document.querySelector('button.write').addEventListener('click', (e) => {
          if (checkValidation()) {
            const customer = EHDObject.customer;
            const companions = EHDObject.companions;
            const list = document.querySelectorAll('input[id^=A-]');
            const male = ["1", "3", "5", "7"];
            const female = ["2", "4", "6", "8"];
            const fnc = (str) => {
              if (typeof str !== 'string' || str.lenth < 8) return false;
              return str.substring(0, 4) < '2020';
            }

            list.forEach((el) => {
              const name = el.id.replace('A-', '');
              customer[name] = el.value.trim();
            });
            customer.email = `${customer.emailid}@${customer.emailaddress}`;

            if ( 
              (customer.gender === 'M' && !male.includes(customer.num2[0])) || 
              (customer.gender === 'F' && !female.includes(customer.num2[0])) 
            ){
              alert('가입자의 성별과 주민번호가 일치하지 않습니다.');
              return;
            }

            if (fnc(customer.birth) && !checkPrivateNumber(customer.birth.replace(/-/gi, '').substring(2) + customer.num2)){
              alert('가입자의 주민번호를 다시한번 확인해 주시기 바랍니다.');
              return;
            }

            if (Array.isArray(companions) && companions.length > 0) {              
              for (const idx in companions){
                companions[idx].name = document.getElementById(`B-name${idx}`).value.trim();
                companions[idx].num2 = document.getElementById(`B-num2${idx}`).value.trim();
                companions[idx].nameen = document.getElementById(`B-nameen${idx}`).value.trim();
                
                if (fnc(companions[idx].birth) && !checkPrivateNumber(companions[idx].birth.replace(/-/gi, '').substring(2) + companions[idx].num2)
                    ){
                  alert(`${Number(idx) + 1}번째 동반인의 주민번호를 다시한번 확인해 주시기 바랍니다.`);
                  return;
                }
              }

              const comp = companions.find(
                c => 
                (c.gender === 'M' && !male.includes(c.num2[0])) || (c.gender === 'F' && !female.includes(c.num2[0]))
              )

              if (comp){
                alert('동반자의 성별과 주민번호가 일치하지 않습니다.');
                return;
              }
            }

            const formData = new FormData();
            const birth = EHDObject.customer.birth.replace(/-/gi, '').substring(2);
            const num2 = document.querySelector('input[name=A-num2]').value;
            const name = document.querySelector('input[name=A-name]').value;
            const emailid = document.querySelector('input[name=A-emailid]').value;
            const emailaddress = document.querySelector('input[name=A-emailaddress]').value;
            const plan_seq = EHDObject.selectedPlan.plan_seq;
            formData.append('birth', birth);
            formData.append('num2', num2);
            formData.append('name', name);
            formData.append('email', emailid+'@'+emailaddress);
            formData.append('plan_seq', plan_seq);

            let request = $.ajax({
              url: "./ajax_restricted_users.php",
              type: "POST",
              data: formData,
              cache: false,
              contentType: false,
              processData: false,
              success: function(result) {
                if (result.success == '1' && result.cnt == 0) {
                  EHDObject.cleaning();
                  EHDObject.save();
                  location.href = './renewal_step03.php';
                } else {
                  alert('고객님, 보험사 인수거절로\n 가입하실 수 없습니다.\n\n 자세한 내용은 고객센터로 문의해주세요.');
                  return false;
                  EHDObject.cleaning();
                  EHDObject.save();
                  location.href = '/html/main/';
                }
              },
              error: function(xhr, status, error) {
                alert("AJAX실패. 접근정보를 가져오는데 실패하였습니다. 관리자에게 문의하십시오.");
                return false;
              }
            });

          }
        });

        layerinput.addEventListener('keyup', (e) => {
          if (e.key === 'Enter') {
            layerButton.dispatchEvent(new Event('click'));
          }
        });

        layerButton.addEventListener('click', (e) => {
          const dataList = EHDObject.countries;
          const keyword = document.querySelector('input[name=searching_keyword]').value;

          if (Array.isArray(dataList) && dataList.length > 0) {
            if (!keyword) {
              generateProductCountiries(dataList);
            } else {
              const list = dataList.filter((c) => c.c_name.indexOf(keyword) > -1);
              generateProductCountiries(list);
            }
          }
        });

        document.querySelector('#searchNation').addEventListener('change', (e)=>{
          if (e.currentTarget.checked){
            layer.querySelectorAll('button[data-c]').forEach((el) => {
              el.addEventListener('click', selectNationEventHadler);
            });
          } else {
            layer.querySelectorAll('button[data-c]').forEach((el) => {
              el.removeEventListener('click', selectNationEventHadler);
            });
          }
        })
      });
    </script>
<?php
include '../_include/_tail.html';
include '../_include/_footer.html';
?> 

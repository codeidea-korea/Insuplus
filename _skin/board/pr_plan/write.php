<?
//사용자 모드입니다
if ($client_mode == "Y") {
?>

<? } else {
  //====================================================================================================
  //관리자 모드 시작
  //====================================================================================================
?>

  <?

  // 상품 코드 불러오기
  $SQL_PR = "select seq,subject from tbl_board_product  ";
  $RS_PR = $dbcon->query($SQL_PR);
  // 보험사 불러오기
  $SQL_Ins = "select seq,subject from tbl_board_ins_list  ";
  $RS_Ins = $dbcon->query($SQL_Ins);

  while ($ins_row = $dbcon->fetch_array($RS_Ins)){
    $Ins_List[count($Ins_List)] = $ins_row;
  }

  if ($seq) {
    // 상품내역검색
    $SQL_PR1 = "select ext4, ext5 from tbl_board_product where seq=" . $pr_cd . " ";
    $RS_PR1 = $dbcon->query($SQL_PR1);
    $pr_row1 = $dbcon->fetch_array($RS_PR1);

    if ($pr_row1["ext4"]) {
      //보장내역 검색
      $SQL_G = "select * from tbl_board_guarantee where seq=" . $pr_row1["ext4"] . " ";
      $RS_G = $dbcon->query($SQL_G);
      $row_g = $dbcon->fetch_array($RS_G);

      // 플랜 보장내역 옵션 검색
      $SQL_PG = "select * from tbl_board_plan_guarantee where plan_cd=" . $seq . " ";
      $RS_PG = $dbcon->query($SQL_PG);
    }
    //장단기 여부 체크    $Arr_chk_period

    if ($pr_row1["ext5"]) {
      //인슈플러스 검색
      $SQL_S = "select * from tbl_board_insuplus_opt where list_seq=" . $pr_row1["ext5"] . " ";
      $RS_S = $dbcon->query($SQL_S);
      $row_opt = $dbcon->fetch_array($RS_S);

      // 인슈플러스 옵션 검색
      $SQL_S = "select * from tbl_board_plan_insuplus where plan_seq=" . $seq . " ";
      $RS_S = $dbcon->query($SQL_S);
    }

    $SQL_C = "select *  ";
    $SQL_C .= "from tbl_board_product_category a ";
    $SQL_C .= "  inner join tbl_board_category b on (a.category_code = b.category_code) ";
    $SQL_C .= "where b.depth = 1 and a.product_seq = " . $pr_cd . " ";
    $RS_C = $dbcon->query($SQL_C);
    $row_pcate = $dbcon->fetch_array($RS_C);
  }

  ?>
  <script type="text/javascript">
    ///////////////////////////////////////////////////////////////////////////
    // 2023-06-23 added by kyle
    ///////////////////////////////////////////////////////////////////////////

    const PAGE_INS_LIST = JSON.parse(`<?= json_encode($Ins_List) ?>`);

    function clickWhetherToUseEventHandler(e){
      const notAvailable = "NOT-AVAILABLE";
      const idx = Number(e.currentTarget.value);
      const isChecked = e.currentTarget.checked;

      if (isChecked){
        // 서비스의 경우 idx 값이 10을 넘는 경우가 있음
        if (idx > 10){
          const strIdx = String(idx);
          for (let i = 0; i < strIdx.length; i++) setValue(strIdx[i]);
        } else setValue(idx);

        // 보장내역/서비스 사용하지 않음
        document.querySelector(`input[name="ext${idx > 3 ? 3:idx}"]`).value = "N";
      } else {
        if (idx > 10){
          const strIdx = String(idx);
          for (let i = 0; i < strIdx.length; i++) removeValue(strIdx[i]);
        } else removeValue(idx);

        // 보장내역/서비스 사용
        document.querySelector(`input[name="ext${idx > 3 ? 3:idx}"]`).value = "Y";
      }

      if (document.getElementById('medicalGroup') && document.getElementById('healthGroup') && document.getElementById('transferGroup')){
        switch(idx){
          case 3:
            if (isChecked) {
              // 의료지원은 기본이기때문에 의료지원이 제외되면 다른 모든 그룹도 제외 함
              const newEvent = Object.assign({}, e);
              const element01 = document.querySelector('#healthGroup');
              const element02 = document.querySelector('#transferGroup');

              element01.checked = true;
              element02.checked = true;

              newEvent.currentTarget = element01;
              clickWhetherToUseEventHandler(newEvent);

              newEvent.currentTarget = element02;
              clickWhetherToUseEventHandler(newEvent);
            }
          case 4:
          case 5:
            if (!isChecked) {
              document.getElementById('whetherToUse3').checked = false;
              document.querySelector(`input[name="ext3"]`).value = "Y";
            }
            else if (
                document.getElementById('medicalGroup').checked &&
                document.getElementById('healthGroup').checked &&
                document.getElementById('transferGroup').checked
            ) {
              document.getElementById('whetherToUse3').checked = true;
              document.querySelector(`input[name="ext3"]`).value = "N";
            }
            break;
          case 345:
            if (isChecked){
              document.getElementById('medicalGroup').checked = true;
              document.getElementById('healthGroup').checked = true;
              document.getElementById('transferGroup').checked = true;
            } else {
              document.getElementById('medicalGroup').checked = false;
              document.getElementById('healthGroup').checked = false;
              document.getElementById('transferGroup').checked = false;
            }
            break;
        }
      }

      function setValue(idx){
        document.querySelectorAll(`input[data-target="${idx}"]`).forEach(el=>{
          el.value = notAvailable;
          el.setAttribute('readonly', 'true');
        });
      }

      function removeValue(idx){
        document.querySelectorAll(`input[data-target="${idx}"]`).forEach(el=>{
          el.value = el.dataset.value;
          el.removeAttribute('readonly');
        });
      }
    }

    function generateGuaranteeForm(dataList){
      const html = [];
      const codeQueue = [];
      const nameQueue = [];
      const insQueue = [];

      if(Array.isArray(dataList) && dataList.length > 0){
        dataList.map(item => {
          if (!codeQueue.includes(item.guarantee_seq)){
            codeQueue.push(item.guarantee_seq);
            nameQueue.push(item.guarantee_name);
            insQueue.push(item.guarantee_ins_seq);
          }
        });

        html.push(`<table class="adm-view-tb" id="service_tb">`);
        html.push(`<colgroup>`);
        html.push(`<col width="8%">`);
        html.push(`<col width="12%">`);
        html.push(`<col width="40%">`);
        html.push(`<col width="8%">`);
        html.push(`<col width="12%">`);
        html.push(`<col width="8%">`);
        html.push(`<col width="12%">`);
        html.push(`</colgroup>`);
        codeQueue.forEach((item, idx) => {
          const guarantees = dataList.filter(o => o.guarantee_seq === item);
          const checkString = document.querySelector(`input[name=ext${idx+1}]`).value === 'N' ? 'checked':'';

          if (guarantees.length > 0){
            html.push(`<tr>`);
            html.push(`  <th>보장내역명</th>`);
            html.push(`  <td colspan="6">${nameQueue[idx]}</td>`);
            html.push(`</tr>`);
            html.push(`<tr>`);
            html.push(`  <th>보험사</th>`);
            html.push(`  <td colspan="6">`);
            html.push(`    <select name="guarantee${idx+1}_ins_seq" style="margin-right:20px;">`);
            html.push(`      <option value="">보장내역${idx+1} 보험사 선택</option>`);
            PAGE_INS_LIST.forEach(item=>html.push(`<option value="${item.seq}">${item.subject}</option>`))
            html.push(`    </select>`);
            html.push(`  <label for="whetherToUse${idx+1}" style="background-color:gold;">보장내역 제외 <input type="checkbox" id="whetherToUse${idx+1}" value="${idx+1}" ${checkString}/></label>`);
            html.push(`  </td>`);
            html.push(`</tr>`);
            html.push(`<tr>`);
            html.push(`  <th colspan="2">그룹명</th>`);
            html.push(`  <th colspan="1">담보명</th>`);
            html.push(`  <th colspan="2">가입금액</th>`);
            html.push(`  <th colspan="2">가입금액(가입증명서)</th>`);
            html.push(`</tr>`);
            guarantees.forEach(o => {
              html.push(`<tr>`);
              html.push(`  <td colspan="2" style="text-align:center;">`);
              html.push(`    ${o.group_mn}`);
              html.push(`    <input type="hidden" name="g_name[]" value="${o.service_name}">`);
              html.push(`    <input type="hidden" name="g_seq[]" value="${o.guarantee_opt_seq}">`);
              html.push(`  </td>`);
              html.push(`  <td>${o.service_name}</td>`);
              html.push(`  <td colspan="2"><input type="text" data-target="${idx+1}" name="g_amount[]" class="w100p" data-value="${o.g_amount || ''}" value="${o.g_amount || ''}"></td>`);
              html.push(`  <td colspan="2">`);
              html.push(`    <input type="text" data-target="${idx+1}" name="g_amount_certificate[]" class="w100p" data-value="${o.g_amount_certificate || ''}" value="${o.g_amount_certificate || ''}">`);
              html.push(`  </td>`);
              html.push(`</tr>`);
            });
          }
        });
        html.push(`</table>`);
      }
      document.querySelector('#guarantee_area').innerHTML = html.join('');

      if (Array.isArray(insQueue) && insQueue.length > 0){
        insQueue.forEach((item, idx) => {
          document.querySelector(`select[name=guarantee${idx+1}_ins_seq]`).value = item ? item:"";
          document.querySelector(`#whetherToUse${idx+1}`).addEventListener('click', clickWhetherToUseEventHandler);
        });
      }
    }

    function generateServiceForm(dataList){
      const html = [];
      if(Array.isArray(dataList) && dataList.length > 0){
        const checkString = document.querySelector(`input[name=ext3]`).value === 'N' ? 'checked':'';

        html.push(`<table class="adm-view-tb" id="service_tb2">`);
        html.push(`<colgroup>`);
        html.push(`<col width="10%">`);
        html.push(`<col width="10%">`);
        html.push(`<col width="20%">`);
        html.push(`<col width="20%">`);
        html.push(`<col width="20%">`);
        html.push(`<col width="20%">`);
        html.push(`</colgroup>`);

        html.push(`<tr>`);
        html.push(`  <th class="b_txt_w">&nbsp;</th>`);
        html.push(`  <th class="b_txt_w">그룹이름</th>`);
        html.push(`  <th class="b_txt_w">국문 서비스내역</th>`);
        html.push(`  <th class="b_txt_w">영문 서비스내역</th>`);
        html.push(`  <th class="b_txt_w">국문한도</th>`);
        html.push(`  <th class="b_txt_w">`);
        html.push(`    영문한도 <label for="whetherToUse3" style="background-color:gold;margin-left:30px;">`);
        html.push(`      서비스 제외 <input type="checkbox" id="whetherToUse3" value="345" ${checkString} />`);
        html.push(`    </label>`);
        html.push(`  </th>`);
        html.push(`</tr>`);
        dataList.forEach((item, idx) => {
          html.push(`<tr>`);
          html.push(`  <td style="text-align: center;">`);
          html.push(`    ${item.service_group_name}`);
          html.push(`    <input type="hidden" name="k_name[]" value="${item.service_name}">`);
          html.push(`    <input type="hidden" name="e_name[]" value="${item.service_name_en}">`);
          html.push(`    <input type="hidden" name="exposure_order[]" value="${idx}">`);
          html.push(`    <input type="hidden" name="insuplus_opt_idx[]" value="${item.insplus_opt_idx}">`);
          html.push(`  </td>`);
          html.push(`  <td>${item.service_name}</td>`);
          html.push(`  <td>${item.service_name_en}</td>`);
          html.push(`  <td><input type="text" class="w100p" data-target="3" name="k_amount[]" data-value="${item.k_amount || ''}" value="${item.k_amount || ''}"></td>`);
          html.push(`  <td><input type="text" class="w100p" data-target="3" name="e_amount[]" data-value="${item.e_amount || ''}" value="${item.e_amount || ''}"></td>`);
          html.push(`</tr>`);
        });
        html.push(`</table>`);
      }
      document.querySelector('#service_area').innerHTML = html.join('');
      if (document.querySelector(`#whetherToUse3`)){
        document.querySelector(`#whetherToUse3`).addEventListener('click', clickWhetherToUseEventHandler);
      }

      if(Array.isArray(dataList) && dataList.length > 0){
        serviceGroupControl(dataList);
      }
    }

    function serviceGroupControl(dataList){
      const offset = 3;
      const notAvailable = "NOT-AVAILABLE";
      const serviceDiv = document.querySelector('#service_area');
      const groupIds = ["medicalGroup", "healthGroup", "transferGroup"];
      const groupNames = ["의료·여행편의 지원", "건강검진", "긴급이후송"];
      const groupItems = [
        Array.from(serviceDiv.querySelectorAll('td')).filter(item=>item.textContent.trim() === groupNames[0]),
        Array.from(serviceDiv.querySelectorAll('td')).filter(item=>item.textContent.trim() === groupNames[1]),
        Array.from(serviceDiv.querySelectorAll('td')).filter(item=>item.textContent.trim() === groupNames[2])
      ];
      const groupChecked = [
        !!dataList.find(item=>item.service_group_name === groupNames[0] && item.k_amount === notAvailable),
        !!dataList.find(item=>item.service_group_name === groupNames[1] && item.k_amount === notAvailable),
        !!dataList.find(item=>item.service_group_name === groupNames[2] && item.k_amount === notAvailable),
      ];

      // 서비스 그룹별 체크박스 만들기
      groupItems.forEach((list, idx)=>{
        const targetValue = idx + offset;
        const length = list.length;

        if (length > 0){
          const td = makeCheckboxWithTD(idx, length);
          const tr = list[0].closest('tr');

          tr.insertAdjacentElement('afterbegin', td);

          // tr 의 rowspan 값 만큰 하위 tr 검색
          let element = tr;
          for(let i = 0; i < length; i++){
            Array.from(element.querySelectorAll('input[data-target]')).forEach(el => el.dataset.target=targetValue);
            element = element.nextSibling;
          }
        }
      });

      // 정의되지 않은 그룹인경우 empty TD 삽입
      if (!groupItems.find(a => a.length > 0)){
        serviceDiv.querySelectorAll('input[name^=k_name]')
            .forEach(el => el.closest('tr').insertAdjacentElement('afterbegin', document.createElement('td')));
      }

      // checkbox 양식 만드기 함수
      function makeCheckboxWithTD(idx, rowspan){
        const td = document.createElement('td');
        const label = document.createElement('label');
        const checkBox = document.createElement('input');

        checkBox.setAttribute('type', 'checkbox');
        checkBox.setAttribute('id', groupIds[idx]);
        checkBox.setAttribute('value', idx + offset);
        checkBox.checked = groupChecked[idx];
        checkBox.addEventListener('click', clickWhetherToUseEventHandler);
        
        label.setAttribute('for', groupIds[idx]);
        label.textContent = `${groupNames[idx]} 제외`;
        label.append(checkBox);
        
        td.setAttribute('rowspan', rowspan);
        td.append(label);

        return td;
      }
    }

    function generateAmountTableForm(selector, dataList){
      const html = [];

      function checkPeriod(dataList){
        // return ()=>dataList[0].chk_period === 'Y'
        let bool = <?= $row_pcate["category_name"] == '단기' ? "true":"false" ?>;
        return bool;
      }
      
      function checkPlanType(dataList){
        // return ()=>dataList[0].plan_type === 'G'
        return ()=>1===1;
      }

      if(Array.isArray(dataList) && dataList.length > 0){
        const isShortTerm = checkPeriod(dataList);
        const isGuaranee = checkPlanType(dataList);

        html.push(`<table class="adm-view-tb">`);
        if (isShortTerm){ // 단기
          html.push(`  <colgroup>`);
          html.push(`    <col width="10%" />`);
          if (isGuaranee()){
            html.push(`      <col width="5%" />`);
            html.push(`      <col width="5%" />`);
          }
          html.push(`      <col width="5%" />`);
          html.push(`      <col width="5%" />`);
          html.push(`      <col width="5%" />`);
          html.push(`      <col width="5%" />`);
          html.push(`      <col width="5%" />`);
          html.push(`      <col width="5%" />`);
          html.push(`      <col width="5%" />`);
          html.push(`      <col width="5%" />`);
          html.push(`      <col width="5%" />`);
          html.push(`      <col width="5%" />`);
          html.push(`      <col width="5%" />`);
          html.push(`      <col width="5%" />`);
          html.push(`      <col width="5%" />`);
          html.push(`      <col width="5%" />`);
          html.push(`      <col width="5%" />`);
          html.push(`      <col width="5%" />`);
          html.push(`    </colgroup>`);
          html.push(`  <thead>`);
          html.push(`    <tr>`);
          html.push(`      <th>플랜</th>`);
          if (isGuaranee()){
            html.push(`      <th>남녀구분</th>`);
            html.push(`      <th>보험나이</th>`);
          }
          html.push(`      <th>2</th>`);
          html.push(`      <th>3</th>`);
          html.push(`      <th>4</th>`);
          html.push(`      <th>5</th>`);
          html.push(`      <th>6</th>`);
          html.push(`      <th>7</th>`);
          html.push(`      <th>10</th>`);
          html.push(`      <th>14</th>`);
          html.push(`      <th>17</th>`);
          html.push(`      <th>21</th>`);
          html.push(`      <th>24</th>`);
          html.push(`      <th>27</th>`);
          html.push(`      <th>30</th>`);
          html.push(`      <th>45</th>`);
          html.push(`      <th>60</th>`);
          html.push(`      <th>90</th>`);
          html.push(`    </tr>`);
          html.push(`  </thead>`);
          html.push(`  <tbody>`);
          dataList.forEach((item, idx) => {
            html.push(`<tr>`);
            html.push(`  <td class="f_center">${item.plan_txt}</td>`);
            if (isGuaranee()){
              html.push(`  <td class="f_center">${item.gender}</td>`);
              html.push(`  <td class="f_center">${item.age}</td>`);
            }
            html.push(`  <td class="f_center">${Number(item.period1).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period2).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period3).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period4).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period5).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period6).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period7).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period8).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period9).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period10).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period11).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period12).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period13).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period14).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period15).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period16).toLocaleString('ko-KR')}</td>`);
            html.push(`</tr>`);
          });
          html.push(`  </tbody>`);
        } else { // 장기
          html.push(`  <colgroup>`);
          html.push(`    <col width="*" />`);
          if (isGuaranee()){
            html.push(`    <col width="5%" />`);
            html.push(`    <col width="5%" />`);
          }
          html.push(`    <col width="6.6%" />`);
          html.push(`    <col width="6.6%" />`);
          html.push(`    <col width="6.6%" />`);
          html.push(`    <col width="6.6%" />`);
          html.push(`    <col width="6.6%" />`);
          html.push(`    <col width="6.6%" />`);
          html.push(`    <col width="6.6%" />`);
          html.push(`    <col width="6.6%" />`);
          html.push(`    <col width="6.6%" />`);
          html.push(`    <col width="6.6%" />`);
          html.push(`    <col width="6.6%" />`);
          html.push(`    <col width="6.6%" />`);
          html.push(`  </colgroup>`);
          html.push(`  <thead>`);
          html.push(`    <tr>`);
          html.push(`      <th>플랜</th>`);
          if (isGuaranee()){
            html.push(`      <th>남녀구분</th>`);
            html.push(`      <th>보험나이</th>`);
          }
          html.push(`      <th>1개월</th>`);
          html.push(`      <th>2개월</th>`);
          html.push(`      <th>3개월</th>`);
          html.push(`      <th>4개월</th>`);
          html.push(`      <th>5개월</th>`);
          html.push(`      <th>6개월</th>`);
          html.push(`      <th>7개월</th>`);
          html.push(`      <th>8개월</th>`);
          html.push(`      <th>9개월</th>`);
          html.push(`      <th>10개월</th>`);
          html.push(`      <th>11개월</th>`);
          html.push(`      <th>12개월</th>`);
          html.push(`    </tr>`);
          html.push(`  </thead>`);
          html.push(`  <tbody>`);
          dataList.forEach((item, idx) => {
            html.push(`<tr>`);
            html.push(`  <td class="f_center">${item.plan_txt}</td>`);
            if (isGuaranee()){
              html.push(`  <td class="f_center">${item.gender}</td>`);
              html.push(`  <td class="f_center">${item.age}</td>`);
            }
            html.push(`  <td class="f_center">${Number(item.period1).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period2).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period3).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period4).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period5).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period6).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period7).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period8).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period9).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period10).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period11).toLocaleString('ko-KR')}</td>`);
            html.push(`  <td class="f_center">${Number(item.period12).toLocaleString('ko-KR')}</td>`);
            html.push(`</tr>`);
          });
          html.push(`  </tbody>`);
        }
        html.push(`</table>`);
      }
      document.querySelector(selector).innerHTML = html.join('');
    }

    function getGuarantee(pr_cd, seq = 0){
      let dataList = [];

      $.ajax({
        type: "POST",
        url: "/_skin/board/<?= $bc_skin ?>/ajax_guarantee.php",
        cache: false,
        async: false,
        data: {
          seq,
          pr_cd,
        },
        success: function(result) {
          dataList = JSON.parse(result);
        },
        error: function(xhr, status, error) {
          alert("AJAX실패. 상품변경에 따른정보를 가져오는데 실패하였습니다. 관리자에게 문의하십시오.");
        }
      });

      return dataList;
    }

    function getService(pr_cd, seq = 0){
      let dataList = [];

      $.ajax({
        type: "POST",
        url: "/_skin/board/<?= $bc_skin ?>/ajax_service.php",
        cache: false,
        async: false,
        data: {
          seq,
          pr_cd,
        },
        success: function(result) {
          dataList = JSON.parse(result);
        },
        error: function(xhr, status, error) {
          alert("AJAX실패. 상품변경에 따른정보를 가져오는데 실패하였습니다. 관리자에게 문의하십시오.");
        }
      });

      return dataList;
    }

    function getAmount(pr_cd, plan_type = 'G'){
      let dataList = [];

      $.ajax({
        type: "POST",
        url: "/_skin/board/<?= $bc_skin ?>/ajax_amount.php",
        cache: false,
        async: false,
        data: {
          plan_type,
          pr_cd,
        },
        success: function(result) {
          dataList = JSON.parse(result);
        },
        error: function(xhr, status, error) {
          alert("AJAX실패. 상품변경에 따른정보를 가져오는데 실패하였습니다. 관리자에게 문의하십시오.");
        }
      });

      return dataList;
    }
    ///////////////////////////////////////////////////////////////////////////

    // 상품변경에 따른 ajax 처리
    function chr_pr(cd, seq) {
      if (!cd) return ;

      let dataList = getGuarantee(cd, seq);
      generateGuaranteeForm(dataList);

      dataList = getService(cd, seq);
      generateServiceForm(dataList);

      if (seq){
        dataList = getAmount(seq, 'G'); // 보장내역 요금
        generateAmountTableForm('#guarantee_amount_area', dataList);
  
        dataList = getAmount(seq, 'S'); // 서비스 요금
        generateAmountTableForm('#service_amount_area', dataList);
      }
    }

    function chr_pr_service(cd, seq) {
      let dataList = getService(cd, seq);
      generateServiceForm(dataList);
    }

    function pop_agree(selector) {
      window.open(`/_skin/board/<?= $bc_skin ?>/pop_agree.php?selector=${selector}`, "_pop", "width=600,height=600");
    }

    function pop_service() {
      window.open("/_skin/board/<?= $bc_skin ?>/pop_service.php", "_pop", "width=600,height=600");
    }

    $(document).ready(function() {
      let pr_cd = `<?= $pr_cd ?>`;  // 상품코드
      let seq = `<?= $seq ?>`;  // 플랜코드

      chr_pr(pr_cd, seq);
      document.querySelector('button.copy_plan').addEventListener('click', copyPlan);
    });
  </script>
  <form name="WriteForm" action="" method="post" enctype="multipart/form-data" onsubmit="return WritePlanOkGo()" autocomplete="off">
    <input type="hidden" name="bc_id" value="<?= $bc_id ?>">
    <input type="hidden" name="page" value="<?= $page ?>">
    <input type="hidden" name="search_category" value="<?= $search_category ?>">
    <input type="hidden" name="search" value="<?= $search ?>">
    <input type="hidden" name="search_text" value="<?= $search_text ?>">
    <input type="hidden" name="act" value="ok">
    <input type="hidden" name="mode" value="">
    <input type="hidden" name="seq" value="<?= $seq ?>">
    <input type="hidden" name="seq_sub" value="<?= $seq_sub ?>">
    <input type="hidden" name="seq_level" value="<?= $seq_level ?>">
    <input type="hidden" name="writer" value="<?= $writer ?>">
    <input type="hidden" name="nick_name" value="<?= $nick_name ?>">
    <input type="hidden" name="passwd" value="">
    <input type="hidden" name="subject" value="&nbsp;" />
    <input type="hidden" name="chk_period" id="chk_period" value="<?= $chk_period ?>" />
    <input type="hidden" name="ext1" value="<?= $ext1 ?>">
    <input type="hidden" name="ext2" value="<?= $ext2 ?>">
    <input type="hidden" name="ext3" value="<?= $ext3 ?>">
    <!-- ### 게시판 시작 ###  -->
    <p class="tit_sub">
      1. 플랜정보 
      <span style="background-color: brown; color: white; padding: 4px 8px; border-radius: 5px; font-size: 0.5em;margin-left:20px;">
        PLAN_SEQ : <?= $seq ?>
      </span>
    </p>
    <table class="adm-view-tb">
      <colgroup>
        <col width="8%">
        <col width="42%">
        <col width="8%">
        <col width="42%">
      </colgroup>
      <? if ($mode == "mod") { ?>
        <tr>
          <th>작성일</th>
          <td><?= $PrintRegDate ?></td>
          <th>조회수</th>
          <td><?= $view_cnt ?></td>
        </tr>
      <? } ?>
      <tr>
        <th>상품명</th>
        <td>
          <select name="pr_cd" onchange="chr_pr(this.value, <?= $seq ?>);">
            <option value="">:: 선택 ::</option>
            <? while ($pr_row = $dbcon->fetch_array($RS_PR)) { ?>
              <option value="<?= $pr_row["seq"] ?>" <?= $pr_cd == $pr_row["seq"] ? "selected":""?> ><?= $pr_row["subject"] ?></option>
            <? } ?>
          </select>
          <? if ($chk_period) { ?> [<?= $Arr_chk_period[$chk_period] ?>상품]<? } ?>
        </td>
        <th><del>보험사</del></th>
        <td>
          <select name="ins_cd">
            <option value="0">:: 선택 ::</option>
          </select>
          <span style="color:red">사용하지 않음</span>
        </td>
      </tr>
      <tr>
        <th>플랜명</th>
        <td>
          <select name="plan_cd">
            <option value="">:: 선택1 ::</option>
            <? for ($c = 0; $c < count($Arr_plan_cd); $c++) { ?>
              <option value="<?= $c + 1 ?>" <?= $plan_cd == $c + 1 ? "selected":""?> ><?= $Arr_plan_cd[$c + 1] ?></option>
            <? } ?>
          </select>
        </td>
        <th><del>서비스</del></th>
        <td>
          <input type="radio" name="chk_service" value="A" <?= "A" == $chk_service ? "checked":"" ?> ><del>A타입</del>
          <input type="radio" name="chk_service" value="B" <?= "B" == $chk_service ? "checked":"" ?> ><del>B타입</del>
          <?
          $SQL_CMN_CD = "select cd_nm, cd_val1 from safety_training.fd_cmn_cd where grp_cd = 'CC13' order by ord ASC";
          $RS_CMN_CD = $dbcon->query($SQL_CMN_CD);
          while ($rows_cd = $dbcon->fetch_array($RS_CMN_CD)) {
          ?>
            <input type='radio' name='chk_service' value='<?= $rows_cd["cd_val1"] ?>' <?= $rows_cd["cd_val1"] == $chk_service ? "checked":"" ?> ><del><?= $rows_cd["cd_nm"] ?></del>
          <? } ?>
          <span style="color:red">사용하지 않음</span>
        </td>
      </tr>
      <tr>
        <th><del>기준 상품가격</del></th>
        <td colspan="3">
          <input type="text" name="common_amount" class="money" value="<?= $common_amount ?>" maxlength="20" />
        </td>
      </tr>
      <tr>
        <th>판매기간</th>
        <td>
          <input type="text" name="s_date" value="<?= $s_date ?>" class="datepicker w100" />
          <select name="s_date_time" class="ml10">
            <? for ($s = 0; $s < 24; $s++) {
              if (strlen($s) == 1) {
                $ss = "0" . $s;
              } else {
                $ss = $s;
              }
            ?>
              <option value="<?= $ss ?>" <?= $s_date_time == $ss ? "selected":"" ?> ><?= $ss ?>시</option>
            <? } ?>
          </select>
          ~
          <input type="text" name="e_date" value="<?= $e_date ?>" class="datepicker w100" />
          <select name="e_date_time" class="ml10">
            <? for ($s = 0; $s < 24; $s++) {
              if (strlen($s) == 1) {
                $ss = "0" . $s;
              } else {
                $ss = $s;
              }
            ?>
              <option value="<?= $ss ?>" <?= $e_date_time == $ss ? "selected":"" ?> ><?= $ss ?>시</option>
            <? } ?>
          </select>
        </td>
        <th>판매상태</th>
        <td>
          <input type="radio" name="plan_status" value="Y" <?= $plan_status == "Y" ? "checked":"" ?> > 판매중
          <input type="radio" name="plan_status" value="N" <?= $plan_status == "N" || $plan_status == "" ? "checked":"" ?> > 판매중지
        </td>
      </tr>
      <tr>
        <th>보험약관파일1</th>
        <td colspan="3">
          <input type="hidden" name="ins_term1_seq" id="agree_cd" value="<?= $ins_term1_seq ?>">
          <span id="ins_term1_seq_txt"><?= print_ins_agree($ins_term1_seq) ?></span>
          <span class="inp_black2"><a href="javascript:;" onclick="pop_agree('ins_term1_seq');" class="btn-form-normal">보험약관선택</a></span>
        </td>
      </tr>
      <tr>
        <th>보험약관파일2</th>
        <td colspan="3">
          <input type="hidden" name="ins_term2_seq" id="ins_term2_seq" value="<?= $ins_term2_seq ?>">
          <span id="ins_term2_seq_txt"><?= print_ins_agree($ins_term2_seq) ?></span>
          <span class="inp_black2"><a href="javascript:;" onclick="pop_agree('ins_term2_seq');" class="btn-form-normal">보험약관선택</a></span>
        </td>
      </tr>
      <tr>
        <th>서비스약관파일</th>
        <td colspan="3">
          <input type="hidden" name="service_cd" id="service_cd" value="<?= $service_cd ?>">
          <span id="service_cd_txt"><?= print_service_agree($service_cd) ?></span>
          <span class="inp_black2"><a href="javascript:;" onclick="pop_service();" class="btn-form-normal">서비스약관선택</a></span>
        </td>
      </tr>
      <tr>
        <th><del>플랜설명</del></th>
        <td colspan="3">
          <textarea name="content" id="content" style="width:30%;height:60px;" class="textarea"><?= $content ? $content:"사용하지 않음" ?></textarea>
          <br><span class="txt_red">※ 설명 추가 시 줄바꿈을 이용해 주세요.(15자 이하로 2개까지 등록 가능합니다)</span>
        </td>
      </tr>
      <tr>
        <th><del>서비스 설명</del></th>
        <td colspan="3">
          <textarea name="service_txt" id="ext6" style="width:30%;height:60px" class="textarea"><?= $service_txt ? $service_txt:"사용하지 않음" ?></textarea>
          <br><span class="txt_red">※ 설명 추가 시 줄바꿈을 이용해 주세요.(15자 이하로 3개까지 등록 가능합니다)</span>
        </td>
      </tr>
      <tr>
        <th>보험사 플랜명</th>
        <td>
          <input type="text" name="ins_plan_name" value="<?= $ins_plan_name ?>">
        </td>
        <th>심의필번호</th>
        <td>
          <input type="text" name="plan_isdn" value="<?= $plan_isdn ?>">
        </td>
      </tr>
      <tr>
        <th>노출여부</th>
        <td>
          <input type="radio" name="secret" value="N" <? if ($secret == "N" || $secret == "") echo "checked"; ?>> 비공개
          <input type="radio" name="secret" value="Y" <? if ($secret == "Y") echo "checked"; ?>> 공개
        </td>
        <th>증권번호</th>
        <td>
          <input type="text" name="stock_isdn" value="<?= $stock_isdn ?>">
        </td>
      </tr>
    </table>

    <p class="tit_sub mt40">2. 보장한도</p>
    <div id="guarantee_area"></div>

    <p class="tit_sub mt40">3. 서비스내역 </p>
    <div id="service_area"></div>

    <p class="tit_sub mt40">4. 상품가격 테이블 등록 ( 현재플랜의 기간은 <span id="chk_period_txt" style="color:#FF0000;"><?= $Arr_chk_period[$chk_period] ?></span>입니다.)</p>

    <table class="adm-view-tb">
      <colgroup>
        <col width="8%">
        <col width="42%">
        <col width="8%">
        <col width="42%">
      </colgroup>
      <tr>
        <th>상품가격 산출표</th>
        <td colspan="3"><input type="file" name="file1"> <span class="txt_red">※신규파일 등록시 기존내용 삭제됨</span></td>
      </tr>
    </table>
    
    <!-- 보장내역 요금 테이블 -->
    <div class="boxPrice mt20" id="guarantee_amount_area"></div>

    <p class="tit_sub mt40">5. 서비스 요금 등록</p>
    <table class="adm-view-tb">
      <colgroup>
        <col width="8%">
        <col width="42%">
        <col width="8%">
        <col width="42%">
      </colgroup>
      <tr>
        <th>서비스요금 일단위</th>
        <td colspan="3">
          <input type="number" name="service_amount_per_day" value="<?= $service_amount_per_day ?>"> <span class="txt_red">※일단위 요금 등록시 산출표 요금 사용 안함 (환불 처리시 주의 필요!)</span>
        </td>
      </tr>
      <tr>
        <th>서비스요금 산출표</th>
        <td colspan="3"><input type="file" name="file2"> <span class="txt_red">※신규파일 등록시 기존내용 삭제됨</span></td>
      </tr>
    </table>

    <!-- 서비스 요금 테이블 -->
    <div class="boxPrice mt20" id="service_amount_area"></div>

    <div class="btnWrap">
      <div class="leftWrap">
        <a href="javascript:list_go();" class="btn_list">목록</a>
      </div>
      <div class="rightWrap">
        <a href="javascript:del_go('<?= $seq ?>');" class="btn_normal">삭제</a>
        <button type="button" class="btn_add copy_plan"/>복사</button>
        <input type="submit" value="등록" class="btn_add">
      </div>
    </div>
  </form>
  <script>
    function validation(){
      ff = document.WriteForm;
      if (!ff.pr_cd.value) {
        alert('상품명을 선택해 주세요.');
        ff.pr_cd.focus();
        return false;
      }
      if (!ff.ins_cd.value) {
        alert('보험사를 선택해 주세요.');
        ff.ins_cd.focus();
        return false;
      }
      if (!ff.plan_cd.value) {
        alert('플랜명을 선택해 주세요.');
        ff.plan_cd.focus();
        return false;
      }
      if (!ff.common_amount.value) {
        alert('기준 상품가격를 입력해 주세요.');
        ff.common_amount.focus();
        return false;
      }
      if (!ff.s_date.value) {
        alert('판매기간 시작일을 입력해 주세요.');
        ff.s_date.focus();
        return false;
      }
      if (!ff.e_date.value) {
        alert('판매기간 종료일을 입력해 주세요.');
        ff.e_date.focus();
        return false;
      }
      if (!ff.content.value) {
        alert('플랜설명을 입력해 주세요.');
        ff.content.focus();
        return false;
      }

      return true;
    }

    function copyPlan(event){
      if (!validation()){
        return false;
      }

      document.WriteForm.seq.value = "";
      ff.mode.value = "write_ok";
      ff.action = "<?= $PHP_SELF ?>";
      ff.target = "board_iframe";
      ff.submit();
    }

    function WritePlanOkGo() {
      if (!validation()){
        return false;
      }

      <? if ($mode == "mod") { ?>
        ff.mode.value = "mod_ok";
        //ff.action = "notice_mod_ok.php";
      <? } elseif ($mode == "write") { ?>
        ff.mode.value = "write_ok";
        //ff.action = "notice_write_ok.php";
      <? } elseif ($mode == "reply") { ?>
        ff.mode.value = "reply_ok";
        //ff.action = "notice_write_ok.php";
      <? } ?>
      ff.action = "<?= $PHP_SELF ?>";
      ff.target = "board_iframe";
      //ff.submit();
    }
  </script>
<? } ?>
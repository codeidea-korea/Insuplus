<!DOCTYPE html>
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	#############################
	#### 페이지 설정
	#############################
	$event_category_master_seq				= REQSTR($seq, 0);

	// 쿼리설정
	$field		     = "
		c.event_category_master_seq, 
    c.event_category_seq, 
    c.depth0, 
    bc0.category_name AS depth0_name, 
    c.depth1, 
    bc1.category_name AS depth1_name, 
    c.depth2, 
    bc2.category_name AS depth2_name, 
    c.depth3, 
    bc3.category_name AS depth3_name, 
    c.use_yn
	";

	$table			= "
	tbl_event_coupon_category c
	LEFT JOIN tbl_board_category bc0 ON c.depth0 = bc0.category_code
	LEFT JOIN tbl_board_category bc1 ON c.depth1 = bc1.category_code
	LEFT JOIN tbl_board_category bc2 ON c.depth2 = bc2.category_code
	LEFT JOIN tbl_board_category bc3 ON c.depth3 = bc3.category_code
	";
	$where			= "and event_category_master_seq = ". $event_category_master_seq;
	$limit				= "";

	$ArrRS			= $dbcon -> getList($field, $table, $where, "c.event_category_seq ASC", $limit);
	$total_record	= $ArrRS[0];
	$result			= $ArrRS[1];
	unset($ArrRS);
?>
<html>
<head>
<title>InsuPlus</title>
<link href="/_css/admin.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script>
///////////////////////////////////////////////////////////////////////////////
// 2023-11-20 added by kyle
///////////////////////////////////////////////////////////////////////////////
<?  $PRD_CAT = getProductCatetories(); ?>
const PAGE_PRD_CAT = JSON.parse('<?= json_encode($PRD_CAT) ?>');

function generate_select(selector, list){
  let html=[];
	let title = document.querySelector(selector).getAttribute('placeholder');

  if (Array.isArray(list)){
    html = list.map(item => `<option value="${item.category_code}">${item.category_name}</option>`);
  }

  html.unshift(`<option value="">${title}</option>`);
  document.querySelector(selector).innerHTML = html.join('');
}

function init_depts(){
  let categories = PAGE_PRD_CAT.filter(item => item.depth === '0');

  generate_select('#category_depth0', categories);
}

function change_depths(event){
  const val = event.currentTarget.value;
  const {textContent} = [].find.call(event.currentTarget.children, (item) => item.selected);
	const depth = Number(event.currentTarget.dataset.depth);
  const categories = PAGE_PRD_CAT.filter(item => item.parent_code === val);

  if (depth !== 3) generate_select(`#category_depth${depth+1}`, categories);
	
	switch (depth) {
		case 0:
			generate_select('#category_depth2', null);
		case 1:
			generate_select('#category_depth3', null);
	}

  document.querySelector('input[name=category_cd]').value = document.querySelector('#category_depth3').value
      || document.querySelector('#category_depth2').value
      || document.querySelector('#category_depth1').value
      || document.querySelector('#category_depth0').value;
}

window.addEventListener('load', ()=>{
  const depth0 = '<?= $category_depth0 ?>';
  const depth1 = '<?= $category_depth1 ?>';
  const depth2 = '<?= $category_depth2 ?>';
  const depth3 = '<?= $category_depth3 ?>';
  const depths = [depth0, depth1, depth2, depth3];
  init_depts();

  document.querySelector('#category_depth0').addEventListener('change', change_depths);
  document.querySelector('#category_depth1').addEventListener('change', change_depths);
  document.querySelector('#category_depth2').addEventListener('change', change_depths);
  document.querySelector('#category_depth3').addEventListener('change', change_depths);

  depths.forEach((d,i)=>{
    if (d){
      document.querySelector(`#category_depth${i}`).value = d;
      document.querySelector(`#category_depth${i}`).dispatchEvent(new Event('change'));
    }
  })
});

///////////////////////////////////////////////////////////////////////////////
</script>
</head>
<body>
	<div class="popupWrap">
		<header>
			<h1>카테고리 선택</h1>
			<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
		</header>
		<div class="popContWrap">
			<form name="SearchForm" id="SearchForm" method="post">
				<input type="hidden" name="event_category_master_seq" value="<?=$event_category_master_seq?>">
				<input type="hidden" name="category_cd" value="">
				<input type="hidden" name="mode" value="">
				<table class="adm-searchForm">
					<colgroup>
						<col width="10%" />
						<col width="75%" />
						<col width="15%" />
					</colgroup>
					<tr>
						<th>카테고리</th>
						<td>
							<select name="category_depth0" id="category_depth0" data-depth="0" placeholder="지역 선택">
								<option value="">지역 선택</option>
							</select>
							<select name="category_depth1" id="category_depth1" data-depth="1" placeholder="구분1 선택">
								<option value="">구분1 선택</option>
							</select>
							<select name="category_depth2" id="category_depth2" data-depth="2" placeholder="구분2 선택">
								<option value="">구분2 선택</option>
							</select>
							<select name="category_depth3" id="category_depth3" data-depth="3" placeholder="구분3 선택">
								<option value="">구분3 선택</option>
							</select>
							<a href="javascript:;" onClick="fnAddCategory();" class="btn_normal">추가</a>
						</td>
						<td>
							<a href="javascript:;" onClick="g_select();" class="btn_normal">적용</a>
						</td>
					</tr>
				</table>
			</form>

			<form method="post" name="frmCategory" id="frmCategory" action="<?=$PHP_SELF?>">
				<input type="hidden" name="event_category_master_seq" value="<?=$event_category_master_seq?>">
				<table class="adm-list-tb">
					<tr>
						<th>선택</th>
						<th>지역</th>
						<th>구분1</th>
						<th>구분2</th>
						<th>구분3</th>
					</tr>
					<? if ($total_record == 0) { ?>
					<tr>
						<td colspan="5">등록된 데이터가 없습니다.</td>
					</tr>
					<?
						} else {
							while ($rows = $dbcon -> fetch_array($result)) { extract($rows); ?>
						<tr>
							<td>
								<input type="hidden" name="use_yn[]" value="<?=$use_yn?>">
								<input type="checkbox" name="chk[]" value="<?=$use_yn?>" <?= ($use_yn === 'Y') ? 'checked' : '' ?>>
							</td>
							<td>
								<input type="hidden" name="depth0[]" value="<?=$depth0?>">
								<span><?=$depth0_name?></span>
							</td>
							<td>
								<input type="hidden" name="depth1[]" value="<?=$depth1?>">
								<span><?=$depth1_name?></span>
							</td>
							<td>
								<input type="hidden" name="depth2[]" value="<?=$depth2?>">
								<span><?=$depth2_name?></span>
							</td>
							<td>
								<input type="hidden" name="depth3[]" value="<?=$depth3?>">
								<span><?=$depth3_name?></span>
							</td>
						</tr>
						<? } ?>
					<? } ?>
				</table>
				<input type=hidden name=f_delete>
			</form>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
	document.querySelectorAll('input[name="use_yn[]"]').forEach(function(checkbox, index) {
		checkbox.addEventListener('change', function() {
    	// 체크박스의 인덱스에 해당하는 use_yn[]의 값을 Y 또는 N으로 설정
    	let useYnInput = document.querySelectorAll('input[name="use_yn[]"]')[index];
    	if (this.checked) {
    		useYnInput.value = 'Y';  // 선택되었을 때
    	} else {
    		useYnInput.value = 'N';  // 선택 해제되었을 때
    	}
    });
	});

	function fnAddCategory() {
    var formData = document.SearchForm;

    // 카테고리 선택 확인
    if (formData.category_depth0.value == "") {
        alert("카테고리를 선택 해주세요.");
        return false;
    }
		
    // "등록된 데이터가 없습니다." row가 있으면 삭제
    const noDataRow = document.querySelector('.adm-list-tb tr td[colspan="5"]');
    if (noDataRow) {
        noDataRow.parentNode.remove();  // 해당 row 삭제
    }

    // 선택한 카테고리의 depth 값 가져오기
    let depth0 = formData.category_depth0.value;
    let depth1 = formData.category_depth1.value || "";  // 선택되지 않았을 경우 빈 값 처리
    let depth2 = formData.category_depth2.value || "";  // 선택되지 않았을 경우 빈 값 처리
    let depth3 = formData.category_depth3.value || "";  // 선택되지 않았을 경우 빈 값 처리

    // 기존 추가된 카테고리들과 중복 체크
    let rows = document.querySelectorAll('.adm-list-tb tr');
    for (let i = 1; i < rows.length; i++) { // 첫 번째 행은 헤더이므로 생략
        let existingDepth0 = rows[i].querySelector('input[name="depth0[]"]').value;
        let existingDepth1 = rows[i].querySelector('input[name="depth1[]"]').value;
        let existingDepth2 = rows[i].querySelector('input[name="depth2[]"]').value;
        let existingDepth3 = rows[i].querySelector('input[name="depth3[]"]').value;

        // 중복 체크: 모든 depth 값이 동일하면 중복
        if (depth0 === existingDepth0 && depth1 === existingDepth1 && depth2 === existingDepth2 && depth3 === existingDepth3) {
            alert("이미 추가된 카테고리입니다.");
            return false;  // 중복된 카테고리가 있으면 추가하지 않음
        }
    }

    // 선택한 카테고리의 이름 가져오기
    let depth0Name = formData.category_depth0.selectedIndex > 0 ? formData.category_depth0.options[formData.category_depth0.selectedIndex].text : "";
    let depth1Name = formData.category_depth1.selectedIndex > 0 ? formData.category_depth1.options[formData.category_depth1.selectedIndex]?.text : "";
    let depth2Name = formData.category_depth2.selectedIndex > 0 ? formData.category_depth2.options[formData.category_depth2.selectedIndex]?.text : "";
    let depth3Name = formData.category_depth3.selectedIndex > 0 ? formData.category_depth3.options[formData.category_depth3.selectedIndex]?.text : "";

    // 새로운 row 추가
    let newRow = `
        <tr>
            <td>
							<input type="hidden" name="use_yn[]" value="Y">
							<input type="checkbox" name="chk[]" value="Y" checked>
						</td>
            <td>
                <input type="hidden" name="depth0[]" value="${depth0}">
                <span>${depth0Name}</span>
            </td>
            <td>
                <input type="hidden" name="depth1[]" value="${depth1}">
                <span>${depth1Name}</span>
            </td>
            <td>
                <input type="hidden" name="depth2[]" value="${depth2}">
                <span>${depth2Name}</span>
            </td>
            <td>
                <input type="hidden" name="depth3[]" value="${depth3}">
                <span>${depth3Name}</span>
            </td>
        </tr>
    `;

    // 새로운 row를 테이블에 추가
    document.querySelector('.adm-list-tb').insertAdjacentHTML('beforeend', newRow);
	}


	function g_select(){
		// 체크박스에 대해 모든 값을 'Y' 또는 'N'으로 설정
    document.querySelectorAll('input[name="chk[]"]').forEach(function(checkbox, index) {
        let useYnInput = document.querySelectorAll('input[name="use_yn[]"]')[index];
        useYnInput.value = checkbox.checked ? 'Y' : 'N';  // 선택 여부에 따라 값 설정
    });

    let formElement = document.getElementById('frmCategory');
		let formData = new FormData(formElement);
		let urlAddr = "";
		// FormData 데이터를 확인하기 위해 콘솔에 출력
		formData.forEach((value, key) => {
				console.log(key + ": " + value);
		});

		if($("#frmCategory").serialize().length > 0) {
			urlAddr = "ajax_regist_category.php";
		} else {
			alert("카테고리를 추가 해주세요.");
			return;
		}
		$.ajax({
			url: urlAddr,
			data: formData,
			dataType: "json",
			type: "POST",
			processData: false,
      contentType: false,
			success: function(d) {
				if (d.result == "0000") {
					let ff = opener.document.WriteForm;
					ff.event_category_master_seq.value = d.seq;
					alert("적용 되었습니다.");
					// 현재 URL 뒤에 seq 값을 추가하여 새로고침
					let currentUrl = window.location.href;
					let newUrl;

					// URL에 이미 쿼리 스트링이 있는지 확인
					if (currentUrl.includes("?")) {
							// 기존 쿼리 스트링이 있을 경우 seq 값만 업데이트
							newUrl = currentUrl.replace(/seq=\d*/, `seq=${d.seq}`);
					} else {
							// 쿼리 스트링이 없을 경우 ?seq=d.seq 추가
							newUrl = currentUrl + `?seq=${d.seq}`;
					}

					// 페이지를 새로고침
					window.location.href = newUrl;
					// self.close();
				} else {
					alert("실행 중 실패했습니다.");
					return;
				}
			},
			error: function(e) {
				alert("실패했습니다. 관리자에게 문의해 주세요.");
				return;
			}
		})
	}
</script>

<? $dbcon -> dbcon_close();?>

<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

<?
}else{
?>
<script type="text/javascript">
function search_go(){
}	
//-->
</script>

<script>
///////////////////////////////////////////////////////////////////////////////
// 2023-11-17 added by kyle
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
<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
<input type="hidden" name="bc_id" value="<?=$bc_id?>">
<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
<input type="hidden" name="mode" value="list">
<input type="hidden" name="category_cd" value="">
<? if ($bc_category_use == "Y") { ?>
<input type="hidden" name="search_category" value="<?=$search_category?>">
<? } ?>

<?
// 상품 코드 불러오기
$SQL_PR = "select seq,subject from tbl_board_product  ";
$RS_PR = $dbcon -> query($SQL_PR);
// 보험사 불러오기
$SQL_Ins = "select seq,subject from tbl_board_ins_list  ";
$RS_Ins = $dbcon -> query($SQL_Ins);

?>
<table class="adm-searchForm">
<colgroup>
	<col width="8%" />
	<col width="42%" />
	<col width="8%" />
	<col width="42%" />
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
	</td>
	<th>플랜명</th>
	<td>
		<input type="text" name="ins_plan_name" class="w150" value="<?= $ins_plan_name ?>" placeholder="보험사 플랜명" />
	</td>
</tr>
<tr>
	<th>상품</th>
	<td colspan="3">
		<select name="pr_cd">
			<option value="">:: 상품명 선택 ::</option>
			<?while ($pr_row = $dbcon -> fetch_array($RS_PR) ) {?>
			<option value="<?=$pr_row["seq"]?>" <?if ($pr_cd==$pr_row["seq"]){?>selected<?}else{}?>><?=$pr_row["subject"]?></option>
			<?}?>
		</select>
		<select name="ins_cd">
			<option value="">:: 보험사 선택 ::</option>
			<?while ($ins_row = $dbcon -> fetch_array($RS_Ins) ) {?>
			<option value="<?=$ins_row["seq"]?>" <?if ($ins_cd==$ins_row["seq"]){?>selected<?}else{}?>><?=$ins_row["subject"]?></option>
			<?}?>
		</select>
		<select name="plan_cd">
			<option value="">:: 플랜 선택 ::</option>
			<?for($c=0;$c<count($Arr_plan_cd);$c++){?>
			<option value="<?=$c+1?>" <?if ($plan_cd==$c+1){?>selected<?}else{}?>><?=$Arr_plan_cd[$c+1]?></option>
			<?}?>
		</select>
		<select name="chk_service">
			<option value="" selected>:: 서비스 선택 ::</option>
			<?
				$SQL_CMN_CD = "select cd_nm, cd_val1 from safety_training.fd_cmn_cd where grp_cd = 'CC13' order by ord ASC";
				$RS_CMN_CD = $dbcon -> query($SQL_CMN_CD);
				while($rows_cd = $dbcon -> fetch_array($RS_CMN_CD)){
			?>
			<option value="<?= $rows_cd["cd_val1"]?>" <?if ($rows_cd["cd_val1"]==$chk_service){?>selected<?}else{}?>><?= $rows_cd["cd_nm"]?></option>
			<? } ?>
		</select>
	</td>
</tr>
<tr>
	<th>기간</th>
	<td colspan="3">
		<select name="search_dt">
			<option value="">:: 기간 선택 ::</option>
			<option value="regdate" <? if ($search_dt == "regdate" ) echo "selected"; ?>>등록일</option>
			<option value="s_date" <? if ($search_dt == "s_date" ) echo "selected"; ?>>판매기간</option>
		</select>
		<input type="text" name="search_dt1" maxlength="10" value="<?=$search_dt1?>" class="datepicker ml10 w100">
		~
		<input type="text" name="search_dt2" maxlength="10" value="<?=$search_dt2?>" class="datepicker w100">
	</td>
</tr>
<tr>
	<th>판매상태</th>
	<td colspan="3">
		<input type="radio" name="plan_status" value="" <?if ($plan_status==""){?>checked<?}?>> 전체
		<input type="radio" name="plan_status" value="Y" <?if ($plan_status=="Y"){?>checked<?}?>> 판매중
		<input type="radio" name="plan_status" value="N" <?if ($plan_status=="N"){?>checked<?}?>> 판매중지
			<input type="submit" value="검색">
		</td>
	</tr>
</table>
</form>


<? if ($auth_write) { ?>
<div class="btnWrapR">
	<a href="javascript:write_go();" class="btn_add">등록</a>
</div>
<? } ?>

<form method="post" name="frmCheckDel" action="<?=$PHP_SELF?>">
<table class="adm-list-tb">
<colgroup>
	<col width="6%" />
	<col width="10%" />
	<col width="10%" />
	<col width="20%" />
	<col width="*" />
	<col width="6%" />
	<col width="6%" />
	<col width="10%" />
	<col width="10%" />
</colgroup>
	<tr>
		<th>No</th>
		<th>상품명</th>
		<th>보험사</th>
		<th>플랜명</th>
		<th>판매기간</th>
		<th>판매상태</th>
		<th>상품가격</th>
		<th>공개여부</th>
		<th>등록일</th>
	</tr>
<?}?>
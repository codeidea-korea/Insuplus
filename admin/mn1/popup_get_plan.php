<!DOCTYPE html>
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	$page_btn_prev =">>"; // > 버튼
	$page_btn_next ="<<"; // < 버튼

	// 페이지 설정
	$page				= REQSTR($page, 1);
	$num_per_page	= REQSTR($num_per_page, 30);
	$page_per_block	= REQSTR($page_per_block, 10);
	$first					= $num_per_page*($page-1);
	$last					= $num_per_page*$page;
	#############################
	#### 페이지 설정

	$parameter .= "&num_per_page=".$num_per_page."&page_per_block=".$page_per_block;

	#############################

	// 검색설정
	$query_where		= "";

	#### 검색 설정 Start
	$pr_cd			= REQSTR($pr_cd, "");
	$ins_cd			= REQSTR($pr_ins_cdcd, "");
	$plan_cd		= REQSTR($plan_cd, "");
	$chk_service	= REQSTR($chk_service, "");
	$search_dt1		= REQSTR($search_dt1, "");
	$search_dt		= REQSTR($search_dt, "");

	$search = REQSTR($search, "");
	$search_text = REQSTR($search_text, "");

	if ($search == "all") {
		$query_where .= " and ( subject like '%".$search_text."%' or content like '%".$search_text."%' or writer like '%".$search_text."%' ) ";
	}
	else {
		if ( (getLen($search_text) > 0) ) {
			$query_where .= " and ".$search." like '%".$search_text."%'";
		}
	}
	
	/////////////////////////////////////////////////////////////////////////////
	// 2023-11-20 added
	$category_cd      = REQSTR($category_cd  , "");
  $ins_plan_name    = REQSTR($ins_plan_name, "");

	if (strlen($category_cd) > 0) $query_where .= " and pr_cd in (select product_seq from tbl_board_product_category where category_code = '" . $category_cd . "') ";
	if (strlen($ins_plan_name) > 0) $query_where .= " and ins_plan_name like '%" . $ins_plan_name . "%' ";

	$parameter .= "&category_depth0=" . $category_depth0 . "&category_depth1=" . $category_depth1 . "&category_depth2=" . $category_depth2 . 
			"&category_depth3=" . $category_depth3 . "&ins_plan_name=" . $ins_plan_name . "&category_cd=" . $category_cd;
	/////////////////////////////////////////////////////////////////////////////

	if($pr_cd) $query_where .= " and pr_cd = '".$pr_cd."' ";
		
	if($ins_cd) $query_where .= " and ins_cd = '".$ins_cd."' ";
	
	if($plan_cd) $query_where .= " and plan_cd = '".$plan_cd."' ";
	
	if($chk_service) $query_where .= " and chk_service = '".$chk_service."' ";
	
	if($search_dt1 && $search_dt2) {
		if($search_dt  == "regdate") {
			$query_where .= " and regdate >= '".$search_dt1."' and regdate <= '".$search_dt2."' ";
		} else if($search_dt  == "s_date") {
			$query_where .= " and (	(s_date <= '".$search_dt1."' and e_date >= '".$search_dt1."') ";
			$query_where .= " or (s_date >= '".$search_dt1."' and e_date <= '".$search_dt2."') ";
			$query_where .= " or (s_date <= '".$search_dt2."' and e_date >= '".$search_dt2."')	) ";
		}
	}
	
	if($plan_status) $query_where .= " and plan_status = '".$plan_status."' ";
	
	$parameter .= "&search=".$search."&search_text=".$search_text."&pr_cd=".$pr_cd."&ins_cd=".$ins_cd;
	$parameter .= "&plan_cd=".$plan_cd."&chk_service=".$chk_service;
	$parameter .= "&search_dt=".$search_dt."&search_dt1=".$search_dt1."&search_dt2=".$search_dt2."&plan_status=".$plan_status;
	#### 검색 설정 End

	// 쿼리설정
	$field		     = "*";

	$table			= " tbl_board_plan";
	$where			= $query_where;
	$limit				= $first.", ".$num_per_page;

	$ArrRS			= $dbcon -> getList($field, $table, $where, "seq DESC", $limit);
	$total_record	= $ArrRS[0];
	$result			= $ArrRS[1];
	unset($ArrRS);
	// 페이지 & 리스트 설정
	$total_page		= ceil($total_record/$num_per_page);
	$no				= $total_record - $first;

	// 상품 코드 불러오기
	$SQL_PR = "select seq,subject from tbl_board_product  ";
	$RS_PR = $dbcon -> query($SQL_PR);
	// 보험사 불러오기
	$SQL_Ins = "select seq,subject from tbl_board_ins_list  ";
	$RS_Ins = $dbcon -> query($SQL_Ins);
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
			<h1>상품 선택</h1>
			<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
		</header>
		<div class="popContWrap">
			<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
				<input type="hidden" name="bc_id" value="<?=$bc_id?>">
				<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
				<input type="hidden" name="mode" value="list">
				<input type="hidden" name="category_cd" value="">
				<table class="adm-searchForm">
					<colgroup>
						<col width="8%" />
						<col width="62%" />
						<col width="8%" />
						<col width="22%" />
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

			<form method="post" name="frmCheckDel" action="<?=$PHP_SELF?>">
				<table class="adm-list-tb">
					<colgroup>
						<col width="5%" />
						<col width="10%" />
						<col width="12%" />
						<col width="17%" />
						<!-- <col width="10%" /> -->
						<col width="*" />
						<col width="7%" />
						<col width="*" />
						<col width="7%" />
						<col width="8%" />
						<col width="5%" />
					</colgroup>
					<tr>
						<th>No</th>
						<th>상품명</th>
						<th>보험사</th>
						<th>플랜명</th>
						<!-- <th>서비스명</th> -->
						<th>판매기간</th>
						<th>판매상태</th>
						<th>보험료</th>
						<th>공개여부</th>
						<th>등록일</th>
						<th>선택</th>
					</tr>
					<? if ($total_record == 0) { ?>
					<tr>
						<td colspan="9">등록된 데이터가 없습니다.</td>
					</tr>
					<?
						} else {
							while ($rows = $dbcon -> fetch_array($result)) {
								extract($rows);
								//unset($rows);
								$secret_txt = $secret == "Y" ? "공개":"비공개";
								$plan_status_txt = $plan_status == "Y" ? "판매":"판매중지"; 
								$pr_name = print_pr_name($pr_cd);
								$ins_name = print_ins($guarantee1_ins_seq);
								?>
						<tr>
							<td><?=$no?></td>
							<td><?=$re?><?=$pr_name?><?=$print_cmt_cnt?> <?=$sNew?></td>
							<td><?=$ins_name?></td>
							<td><?=$ins_plan_name?></td>
							<!-- <td><?=$Arr_txt_plus[$chk_service]?></td> -->
							<td><?=$s_date?> ~ <?=$e_date?></td>
							<td><?=$plan_status_txt?></td>
							<td class="r"><?=number_format($common_amount)?>원</td>
							<td><?=$secret_txt?></td>
							<td><?=substr($regdate,0,10)?></td>
							<td><a href="javascript:;" onClick="g_select(this);" data='<?=json_encode($rows)?>' data_pr='<?=$pr_name?>' data_ins='<?=$ins_name?>' class="btn-form-normal">선택</a></td>
						</tr>
						<? $no = $no - 1;} ?>
					<? } ?>
				</table>
				<input type=hidden name=f_delete>
			</form>
		
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td width="70" valign="top" style="padding:11px 0 0 0"><?=$btn_list?></td>
					<td align="center" valign="top" style="padding:10px 0 0 0">
						<? list_page($page, $total_page, $page_per_block); ?></td>
					</td>
					<td width="70" align="right" valign="top" style="padding:11px 0 0 0">
					</td>
				</tr>
			</table>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
	function g_select(d){
		var data = $.parseJSON($(d).attr("data"));
		var prName = $(d).attr("data_pr");
		var insName = $(d).attr("data_ins");
		var ff = opener.document.frm_group_join;
		ff.plan_seq.value = data.seq;
		ff.plan_cd.value = data.plan_cd;
		ff.pr_cd.value = data.pr_cd;
		ff.service_cd.value = data.service_cd;
		ff.stock_isdn.value = data.stock_isdn;
		ff.ins_cd.value = data.guarantee1_ins_seq;
		// ff.chk_service.value = data.chk_service;
		ff.chk_service.value = data.ext3;
		ff.chk_period.value = data.chk_period;
		ff.agree_cd.value = data.agree_cd;
		ff.ins_plan_name.value = data.ins_plan_name;
		ff.pr_name.value = prName;
		ff.ins_name.value = insName;
		
		self.close();
	}
</script>

<? $dbcon -> dbcon_close();?>

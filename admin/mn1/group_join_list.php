<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$tm = "MN1";
	$lm = "";
	include $path_admin."inc/header.php";
?>
<?
//	$dbcon -> setDebug(1);
	$page_btn_prev =">>"; // > 버튼
	$page_btn_next ="<<"; // < 버튼

	// 페이지 설정
	$page				= REQSTR($page, 1);
	$num_per_page		= REQSTR($num_per_page, 30);
	$page_per_block		= REQSTR($page_per_block, 10);
	$first				= $num_per_page*($page-1);
	$last				= $num_per_page*$page;

	// 검색설정
	$query_where		= "";

	#### 검색 설정 Start
	$pr_cd				= REQSTR($pr_cd, "");
	$ins_cd				= REQSTR($ins_cd, "");
	$plan_cd			= REQSTR($plan_cd, "");
	$chk_service			= REQSTR($chk_service, "");
	$search_text		= REQSTR($search_text, "");
	$search_date_txt	= REQSTR($search_date_txt, "");
	$search_date_s		= REQSTR($search_date_s, "");
	$search_date_e		= REQSTR($search_date_e, "");

	// 2023-11-08 added
	$category_cd      = REQSTR($category_cd  , "");
	$ins_plan_name    = REQSTR($ins_plan_name, "");

	$parameter = "&pr_cd=" . $pr_cd . "&ins_cd=" . $ins_cd . "&plan_cd=" . $plan_cd . "&chk_service=" . $chk_service . "&search=" . $search .
			"&search_text=" . $search_text ."&num_per_page=" . $num_per_page . "&search_date_txt=" . $search_date_txt . "&search_date_s=" . $search_date_s . 
			"&search_date_e=" . $search_date_e . "&category_depth0=" . $category_depth0 . "&category_depth1=" . $category_depth1 . 
			"&category_depth2=" . $category_depth2 . "&category_depth3=" . $category_depth3 . "&ins_plan_name=" . $ins_plan_name . "&category_cd=" . $category_cd;

	$query_where .= " and A.group_join_status != 'N'";

	if ( strlen($pr_cd) > 0 ) $query_where .= " and A.pr_cd = '".$pr_cd."' ";
	
	if ( strlen($chk_service) > 0 ){
		if($chk_service === "N") {
			$query_where .= " and (C.chk_service = 'N' or C.chk_service = '') ";
		} else if($chk_service != "N") {
			$query_where .= " and C.chk_service = '".$chk_service."' ";
		}
	} 

	if ( strlen($ins_cd) > 0 ) $query_where .= " and A.ins_cd = '".$ins_cd."' ";

	// 2023-11-08 added
	if (strlen($category_cd) > 0) $query_where .= " and B.seq in (select product_seq from tbl_board_product_category where category_code = '" . $category_cd . "') ";
	if (strlen($ins_plan_name) > 0) $query_where .= " and C.ins_plan_name like '%" . $ins_plan_name . "%' ";

	if ( strlen($search_text) > 0 ) {
		$query_where .= " and A.o_name = '".all_seed_enc($search_text)."'  ";
	}

	if ( strlen($search_date_s) > 0 ) $query_where .= " and A.regdate >= '".$search_date_s." 00:00:00' ";
	if ( strlen($search_date_e) > 0 ) $query_where .= " and A.regdate <= '".$search_date_e." 23:59:59' ";
	#### 검색 설정 End
	
	// 쿼리설정
	$field		     = "B.subject as pr_name, (SELECT COUNT(*) FROM tbl_order_list_join C where C.group_join_id = A.group_join_id) AS join_cnt, A.*, C.chk_service ";

	$table			= " tbl_order_group_join_list A left join tbl_board_product B on A.pr_cd = B.seq left join tbl_board_plan C on A.plan_cd = C.seq";
	$where			= $query_where;
	$order			= " group_join_id DESC";
	$limit				= $first.", ".$num_per_page;

	$ArrRS			= $dbcon -> getList($field, $table, $where, $order, $limit);
	$total_record	= $ArrRS[0];
	$result			= $ArrRS[1];
	unset($ArrRS);

	// 페이지 & 리스트 설정
	$total_page		= ceil($total_record/$num_per_page);
	$no				= $total_record - $first;



	// 상품정보 불러오기
	$pr_list = array();
	$SQL_PR = "select seq,subject from tbl_board_product  ";
	$RS_PR = $dbcon -> query($SQL_PR);
	while($row = $dbcon->fetch_array($RS_PR)) {
		$pr_list[] = $row;
	}
	
	// 보험사 불러오기
	$ins_list = array();
	$SQL_Ins = "select seq,subject from tbl_board_ins_list  ";
	$RS_Ins = $dbcon -> query($SQL_Ins);
	while($row = $dbcon -> fetch_array($RS_Ins)){
		$ins_list[] = $row;
	}
?>
<script type="text/javascript">
 $(document).ready(function() {
var clareCalendar = {<?=$calendar_opt?>});
</script>
<script>
function view_go(n) {
	location.href = "group_join_view.php?group_join_id="+n+"<?=$parameter?>";
}

function write_go(){
	location.href = "group_join_write.php?<?=$parameter?>";
}
</script>

<?  $PRD_CAT = getProductCatetories(); ?>
<script>
///////////////////////////////////////////////////////////////////////////////
// 2023-11-08 added by kyle
///////////////////////////////////////////////////////////////////////////////
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
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">단체가입</td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<!-- (s) 검색영역  -->
			<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
			<input type="hidden" name="bc_id" value="<?=$bc_id?>">
			<input type="hidden" name="mode" value="list">
			<input type="hidden" name="search_category" value="<?=$search_category?>">
			<input type="hidden" name="category_cd" value="">

			<table class="adm-searchForm">
				<colgroup>
					<col width="5%" />
					<col width="45%" />
					<col width="5%" />
					<col width="45%" />
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
					<td>
						<select name="pr_cd">
							<option value="">상품명 선택</option>
							<?foreach($pr_list as $pr_row) {?>
							<option value="<?=$pr_row["seq"]?>" <?if ($pr_name==$pr_row["subject"]){?>selected<?}else{}?>><?=$pr_row["subject"]?></option>
							<?}?>
						</select>

						<select name="chk_service">
							<option value="">서비스 선택</option>
							<option value="C" <?if ($chk_service=="C"){?>selected<?}else{}?>>인슈플러스</option>
							<option value="D" <?if ($chk_service=="D"){?>selected<?}else{}?>>플라잉닥터스</option>
							<option value="N" <?if ($chk_service=="N"){?>selected<?}else{}?>>없음</option>
						</select>

						<select name="ins_cd">
							<option value="">보험사 선택</option>
							<?foreach($ins_list as $ins_row) {?>
							<option value="<?=$ins_row["seq"]?>" <?if ($ins_cd==$ins_row["subject"]){?>selected<?}else{}?>><?=$ins_row["subject"]?></option>
							<?}?>
						</select>
					</td>
					<th>등록일</th>
					<td>
						<input type="text" name="search_date_s" class="calendar w100 ml10" value="<?=$search_date_s?>" />
						~ <input type="text" name="search_date_e" class="calendar w100" value="<?=$search_date_e?>" />

					</td>
				</tr>
				<tr>
					<th>업체명</th>
					<td colspan="3">
						<input type="text" name="search_text" class="w400" value="<?=$search_text?>" />
						<input type="submit" value="검색" />
					</td>
				</tr>
			</table>
			</form>
			<!-- (e) 검색영역  -->

			<div class="btnWrap">
				<div class="leftWrap"><span class="totalCount">전체 : <strong><?=number_format($total_record)?></strong>건</span></div>
				<div class="rightWrap">
					<a class="btn_add" href="javascript: write_go();">등록</a>
				</div>
			</div>

			<!--  (s) 리스트 영역  -->
			<form method="post" name="frmCheckDel" action="<?=$PHP_SELF?>">

			<table class="adm-list-tb">
			<colgroup>
				<col width="5%" />
			</colgroup>
			<tr>
				<th>NO</th>
				<th>업체명</th>
				<th>상품</th>
				<th>서비스</th>
				<th>인원수</th>
				<th>총금액</th>
				<th>가입일</th>
				<th>수정일</th>
			</tr>
			<? if ($total_record == 0) { ?>
			<tr>
				<td colspan="6">등록된 데이터가 없습니다.</td>
			</tr>
			<?
				} else {
					while ($rows = $dbcon -> fetch_array($result)) {
						extract($rows);
						unset($rows);
						$SQL_PL = "select chk_service from tbl_board_plan where seq = '".$plan_cd."' ";
						$RS_PL = $dbcon -> query($SQL_PL);
						$chk = $dbcon->fetch_array($RS_PL);
			?>
			<tr onClick="view_go('<?=$group_join_id?>')" class="click" style="background:<?=$bg?>;">
				<td><?=$no?></td>
				<td><?=all_seed_dec($o_name)?></td>
				<td><?=$pr_name?></td>
				<?if($Arr_txt_plus[$chk["chk_service"]]){?>
				<td><?=$Arr_txt_plus[$chk["chk_service"]]?></td>
				<? } else {?>
				<td>-</td>
				<? } ?>
				<td><?= $join_cnt?></td>
				<?if($total_amount){?>
				<td><?=$total_amount?></td>
				<? } else {?>
				<td>-</td>
				<? } ?>
				<td><?=substr($regdate,0,10)?></td>
				<td><?=substr($moddate,0,16)?></td>
			</tr>
			<?
						$no = $no - 1;
					}
				}
				unset($result);

			?>
			</table>
			<input type=hidden name=f_delete>
			</form>
			<!--  (e) 리스트 영역  -->

			<!-- (s) 페이징 처리 -->
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td align="center" valign="top" style="padding:10px 0 0 0">
						<? list_page($page, $total_page, $page_per_block); ?>
					</td>
				</tr>
			</table>
			<!-- (e) 페이징 처리 -->


		</td>
	</tr>
</table>





<? include $path_admin."inc/footer.php"; ?>
<? $dbcon -> dbcon_close();?>
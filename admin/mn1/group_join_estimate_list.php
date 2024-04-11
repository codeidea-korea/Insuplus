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
	$num_per_page	= REQSTR($num_per_page, 30);
	$page_per_block	= REQSTR($page_per_block, 10);
	$first					= $num_per_page*($page-1);
	$last					= $num_per_page*$page;

	// 검색설정
	$query_where		= "";

	#### 검색 설정 Start
	$pr_cd				= REQSTR($pr_cd, "");
	$group_join_status				= REQSTR($group_join_status, "");

	$search_text					= REQSTR($search_text, "");

	$search_date_txt					= REQSTR($search_date_txt, "");
	$search_date_s				= REQSTR($search_date_s, "");
	$search_date_e				= REQSTR($search_date_e, "");

	$search_date_txt					= REQSTR($search_date_txt, "");
	$search_date_s				= REQSTR($search_date_s, "");
	$search_date_e				= REQSTR($search_date_e, "");

	if ( strlen($pr_cd) > 0 ) $query_where .= " and A.pr_cd = '".$pr_cd."' ";

	if ( strlen($search_text) > 0 ) {
		$query_where .= " and A.o_name = '".all_seed_enc($search_text)."'  ";
	}

	if ( strlen($search_date_s) > 0 ) $query_where .= " and A.regdate >= '".$search_date_s." 00:00:00' ";
	if ( strlen($search_date_e) > 0 ) $query_where .= " and A.regdate <= '".$search_date_e." 23:59:59' ";

	$parameter = "&pr_cd=".$pr_cd."&group_join_status=".$group_join_status."&search_text=".$search_text."&num_per_page=".$num_per_page."&search_date_s=".$search_date_s."&search_date_e=".$search_date_e;
	#### 검색 설정 End

	// 쿼리설정
	$field		     = "B.subject as pr_name, A.* ";

	$table			= " tbl_order_group_join_list A left join tbl_board_product B on A.pr_cd = B.seq";
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

	// 상품 코드 불러오기
	$pr_list = array();
	$SQL_PR = "select seq,subject from tbl_board_product  ";
	$RS_PR = $dbcon -> query($SQL_PR);
	while($row = $dbcon->fetch_array($RS_PR)) {
		$pr_list[] = $row;
	}

?>
<script type="text/javascript">
 $(document).ready(function() {
var clareCalendar = {<?=$calendar_opt?>});
</script>
<script>
function view_go(n) {
	location.href = "group_join_estimate_view.php?group_join_id="+n+"<?=$parameter?>";
}
</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">가입자</td>
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



			<table class="adm-searchForm">
				<colgroup>
					<col width="8%" />
					<col width="92%" />
				</colgroup>
				<tr>
					<th>상품</th>
					<td>
						<select name="pr_cd">
							<option value="">상품명 선택</option>
							<?foreach($pr_list as $pr_row) {?>
							<option value="<?=$pr_row["seq"]?>" <?if ($pr_cd==$pr_row["subject"]){?>selected<?}else{}?>><?=$pr_row["subject"]?></option>
							<?}?>
						</select>

						<select name="group_join_status">
							<option value="">플랜 선택</option>
							<option value="N" <?if ($group_join_status=="N"){?>selected<?}else{}?>>견적</option>
							<option value="W" <?if ($group_join_status=="W"){?>selected<?}else{}?>>입금대기</option>
							<option value="Y" <?if ($group_join_status=="Y"){?>selected<?}else{}?>>가입완료</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>견적일</th>
					<td>
						<input type="text" name="search_date_s" class="calendar w100 ml10" value="<?=$search_date_s?>" />
						~ <input type="text" name="search_date_e" class="calendar w100" value="<?=$search_date_e?>" />
					</td>
				</tr>
				<tr>
					<th>계약자명</th>
					<td>
						<input type="text" name="search_text" class="w400" value="<?=$search_text?>" />
						<input type="submit" value="검색" />
					</td>
				</tr>
			</table>
			</form>
			<!-- (e) 검색영역  -->

			<div class="btnWrap">
				<div class="leftWrap"><span class="totalCount">전체 : <strong><?=number_format($total_record)?></strong>건</span></div>
			</div>

			<!--  (s) 리스트 영역  -->
			<form method="post" name="frmCheckDel" action="<?=$PHP_SELF?>">

			<table class="adm-list-tb">
			<colgroup>
				<col width="2%" />
				<col width="7%" />
				<col width="6%" />
				<col width="9%" />
				<col width="5%" />
				<col width="5%" />
				<col width="7%" />
			</colgroup>
			<tr>
				<th>NO</th>
				<th>계약자명</th>
				<th>생년월일 / 사업자번호</th>
				<th>상품</th>
				<th>인원</th>
				<th>상태</th>
				<th>견적일</th>
			</tr>
			<? if ($total_record == 0) { ?>
			<tr>
				<td colspan="7">등록된 데이터가 없습니다.</td>
			</tr>
			<?
				} else {
					while ($rows = $dbcon -> fetch_array($result)) {
						extract($rows);
						unset($rows);
			?>
			<tr onClick="view_go('<?=$group_join_id?>')" class="click">
				<td><?=$no?></td>
				<td><?=all_seed_dec($o_name)?></td>
				<?if($group_join_type == "B2C"){?>
				<td><?=$birthdate?></td>
				<? } else {?>
				<td><?=$biz_num?></td>
				<? } ?>
				<td><?=$pr_name?></td>
				<td><?=$group_join_cnt?></td>
				<td><?=$Arr_group_join_status[$group_join_status]?></td>
				<td><?=substr($regdate,0,10)?></td>
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
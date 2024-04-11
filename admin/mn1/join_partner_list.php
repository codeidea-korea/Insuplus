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
	$pr_name				= REQSTR($pr_name, "");
	$ins_name				= REQSTR($ins_name, "");
	$plan_name				= REQSTR($plan_name, "");
	$chk_service				= REQSTR($chk_service, "");

	$search							= REQSTR($search, "");
	$search_text					= REQSTR($search_text, "");

	$search_orderby				= REQSTR($search_orderby, "");
	$search_sort					= REQSTR($search_sort, "");

	$search_date_txt					= REQSTR($search_date_txt, "");
	$search_date_s				= REQSTR($search_date_s, "");
	$search_date_e				= REQSTR($search_date_e, "");


	if ( strlen($pr_name) > 0 ) $query_where .= " and A.pr_name = '".$pr_name."' ";
	if ( strlen($ins_name) > 0 ) $query_where .= " and A.ins_name = '".$ins_name."' ";
	if ( strlen($plan_name) > 0 ) $query_where .= " and A.plan_name = '".$plan_name."' ";
	if ( strlen($chk_service) > 0 ) $query_where .= " and A.chk_service = '".$chk_service."' ";
	if ( strlen($join_status) > 0 ) $query_where .= " and B.join_status = '".$join_status."' ";

	if ( strlen($search_text) > 0 ) {
		$query_where .= " and A.orderno in (select orderno from tbl_order_list_join where ".$search." ='".all_seed_enc($search_text)."' )  ";
	}

	if ( strlen($search_date_s) > 0 ) $query_where .= " and ".$search_date_txt." >= '".$search_date_s." 00:00:00' ";
	if ( strlen($search_date_e) > 0 ) $query_where .= " and ".$search_date_txt." <= '".$search_date_e." 23:59:59' ";


	if ( strlen($search_orderby) == 0 ) $search_orderby .= " A.seq ";
	if ( strlen($search_sort) == 0 ) $search_sort .= "desc";


//	echo "pr_name : ".$pr_name."<BR>";
//	echo "ins_name : ".$ins_name."<BR>";
//	echo "plan_name : ".$plan_name."<BR>";
//	echo "chk_service : ".$chk_service."<BR>";
//	echo "search_orderby : ".$search_orderby."<BR>";
//	echo "search_sort : ".$search_sort."<BR>";
//	echo $query_where."<BR>";


	$parameter = "&pr_name=".$pr_name."&ins_name=".$ins_name."&plan_name=".$plan_name."&chk_service=".$chk_service."&search=".$search."&search_text=".$search_text."&search_orderby=".$search_orderby."&search_sort=".$search_sort."&num_per_page=".$num_per_page."&join_status=".$join_status."&search_date_txt=".$search_date_txt."&search_date_s=".$search_date_s."&search_date_e=".$search_date_e;

	#### 검색 설정 End

	// 쿼리설정
	$field		     = " * ";
	$field			.= ", (SELECT partnership_name FROM tbl_board_partner WHERE seq = A.join_ch) as partnership_name";

	$table			= " tbl_order_list A inner join tbl_order_list_join B on A.orderno=B.orderno ";
	$where          .= " AND join_ch= '".$_SESSION["ss_partner_seq_admin"]."' ";
	$where			.= $query_where;
	$orderby			= $search_orderby." ".$search_sort;
	$limit				= $first.", ".$num_per_page;

	$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);
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
//$dbcon -> dbcon_close();
?>
<script type="text/javascript">
 $(document).ready(function() {
var clareCalendar = {<?=$calendar_opt?>});
</script>
<script>
function view_go(n) {
	location.href = "join_partner_view.php?seq="+n+"<?=$parameter?>";
}
function excel_go() {
	location.href = "excel_join_partner.php?mode=excel&<?=$GLOBALS[parameter]?>";
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
					<th>기간</th>
					<td>
						<select name="search_date_txt">
							<option value="A.writedate" <?if ($search_date_txt=="A.writedate"){?>selected<?}else{}?>>결제일</option>
							<option value="A.s_date" <?if ($search_date_txt=="A.s_date"){?>selected<?}else{}?>>보험개시일</option>
							<option value="A.e_date" <?if ($search_date_txt=="A.e_date"){?>selected<?}else{}?>>보험종료일</option>
							<option value="B.cancle_date" <?if ($search_date_txt=="B.cancle_date"){?>selected<?}else{}?>>취소일</option>
						</select>
						<input type="text" name="search_date_s" class="calendar w100 ml10" value="<?=$search_date_s?>" />
						~ <input type="text" name="search_date_e" class="calendar w100" value="<?=$search_date_e?>" />

						<select name="join_status" class="ml10">
							<option value="">가입상태 선택</option>
							<option value="Y" <?if ($join_status=="Y"){?>selected<?}else{}?>>가입완료</option>
							<option value="N" <?if ($join_status=="N"){?>selected<?}else{}?>>가입취소</option>
							<option value="S" <?if ($join_status=="R"){?>selected<?}else{}?>>중도해지</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>가입자</th>
					<td>
						<select name="search">
							<option value="o_phone" <?if ($search=="o_phone"){?>selected<?}else{}?>>휴대폰번호</option>
							<option value="o_name" <?if ($search=="o_name"){?>selected<?}else{}?>>이름</option>
							<option value="orderno" <?if ($search=="orderno"){?>selected<?}else{}?>>주문번호</option>
						</select>
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
				<a href="javascript:;" class="btn_excel" onClick="excel_go('all')">엑셀다운로드</a>
				
				</div>
			</div>

			<!--  (s) 리스트 영역  -->
			<form method="post" name="frmCheckDel" action="<?=$PHP_SELF?>">

			<table class="adm-list-tb">
			<colgroup>
				<col width="5%" />
				<col width="*" />
				<col width="8%" />
				<col width="5%" />
				<col width="5%" />
				<col width="10%" />
				<col width="10%" />
				<col width="5%" />
				<col width="5%" />
				<col width="10%" />
				<col width="8%" />
				<col width="5%" />
				<col width="10%" />
			</colgroup>
			<tr>
				<th>NO</th>
				<th>상품명</th>
				<th>보험사</th>
				<th>플랜명</th>
				<th>서비스</th>
				<th>개시일</th>
				<th>종료일</th>
				<th class="btn">보험기간
					<a href="#" class="up">▲</a>
					<a href="#" class="down">▼</a>
				</th>
				<th class="btn">이름
					<a href="#" class="up">▲</a>
					<a href="#" class="down">▼</a>
				</th>
				<th>연락처</th>
				<th>가입채널</th>
				<th>가입상태</th>
				<th class="btn">상품가
					<a href="#" class="up">▲</a>
					<a href="#" class="down">▼</a>
				</th>
				<th class="btn">가입일
					<a href="#" class="up">▲</a>
					<a href="#" class="down">▼</a>
				</th>
			</tr>
			<? if ($total_record == 0) { ?>
			<tr>
				<td colspan="14">등록된 데이터가 없습니다.</td>
			</tr>
			<?
				} else {
					while ($rows = $dbcon -> fetch_array($result)) {
						extract($rows);
						unset($rows);
						if($chk_p == "Y") {
							$s_date = $s_date." ".$s_date_time."시";
							$e_date = $e_date." ".$e_date_time."시";
						} else if($chk_p == "N") {
							$s_date = $s_date;
							$e_date = $e_date;
						}
						$bg = "";
						if($join_status == "N") { //가입취소
							$bg = "#ededed";
						} else if($join_status == "R") { //중도해지
							$bg = "#eb8b8b";
						}
						//if ($join_status=="S"){$bg = "#f3c7d5";}
			?>
			<tr onClick="view_go('<?=$seq?>')" class="click" style="background:<?=$bg?>;">
				<td><?=$no?></td>
				<td><?=$pr_name?></td>
				<td><?=$ins_name?></td>
				<td><?=$plan_name?></td>
				<td><?=$Arr_txt_plus[$chk_service]?></td>
				<td><?=$s_date?></td>
				<td><?=$e_date?></td>
				<td><?=$ins_period?> <?=$arr_chk_p_gubun[$chk_p]?></td>
				<td><?=all_seed_dec($o_name)?></td>
				<td><?=all_seed_dec($o_phone)?></td>
				<td><?=$partnership_name?></td>
				<td><?=$arr_join_step[$join_status]?></td>
				<td class="r"><?=number_format($join_amount+$join_service)?>원</td>
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
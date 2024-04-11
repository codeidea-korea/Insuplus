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


	if ( strlen($pr_name) > 0 ) $query_where .= " and pr_name = '".$pr_name."' ";
	if ( strlen($ins_name) > 0 ) $query_where .= " and ins_name = '".$ins_name."' ";
	if ( strlen($plan_name) > 0 ) $query_where .= " and plan_name = '".$plan_name."' ";
	if ( strlen($chk_service) > 0 ) $query_where .= " and chk_service = '".$chk_service."' ";
	if ( strlen($order_step) > 0 ) $query_where .= " and order_step = '".$order_step."' ";

	if ( strlen($search_text) > 0 ) {
		$query_where .= " and orderno in (select orderno from tbl_order_list_join where ".$search." ='".all_seed_enc($search_text)."' )  ";
	}

	if ( strlen($search_date_s) > 0 ) $query_where .= " and ".$search_date_txt." >= '".$search_date_s."' ";
	if ( strlen($search_date_e) > 0 ) $query_where .= " and ".$search_date_txt." <= '".$search_date_e."' ";


	if ( strlen($search_orderby) == 0 ) $search_orderby .= " writedate ";
	if ( strlen($search_sort) == 0 ) $search_sort .= "desc";


//	echo "pr_name : ".$pr_name."<BR>";
//	echo "ins_name : ".$ins_name."<BR>";
//	echo "plan_name : ".$plan_name."<BR>";
//	echo "chk_service : ".$chk_service."<BR>";
//	echo "search_orderby : ".$search_orderby."<BR>";
//	echo "search_sort : ".$search_sort."<BR>";
//	echo $query_where."<BR>";


	$parameter = "&pr_name=".$pr_name."&ins_name=".$ins_name."&plan_name=".$plan_name."&chk_service=".$chk_service."&search=".$search."&search_text=".$search_text."&search_orderby=".$search_orderby."&search_sort=".$search_sort."&num_per_page=".$num_per_page."&order_step=".$order_step."&search_date_txt=".$search_date_txt."&search_date_s=".$search_date_s."&search_date_e=".$search_date_e;

	#### 검색 설정 End

	// 쿼리설정
	$field			 = " * ";
	$field			.= ", (SELECT partnership_name FROM tbl_board_partner WHERE seq = A.join_ch) as partnership_name";

	$table			= " tbl_order_list A ";
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
<script>
//제휴사는 페이지 생성 시  파일명에 pay_partner << 존재해야한다. header.php(상단 참고)
function view_go(n) {
	location.href = "pay_partner_view.php?orderno="+n+"<?=$parameter?>";
}
function excel_go() {
	location.href = "excel_pay_partner_history.php?mode=excel&<?=$GLOBALS[parameter]?>";
}
</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">결제내역</td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<!-- (s) 검색영역  -->
			<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()" autocomplete="off">
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
						<select name="pr_name">
							<option value="">상품명 선택</option>
							<?while ($pr_row = $dbcon -> fetch_array($RS_PR) ) {?>
							<option value="<?=$pr_row["subject"]?>" <?if ($pr_name==$pr_row["subject"]){?>selected<?}else{}?>><?=$pr_row["subject"]?></option>
							<?}?>
						</select>

						<select name="ins_name">
							<option value="">보험사 선택</option>
							<?while ($ins_row = $dbcon -> fetch_array($RS_Ins) ) {?>
							<option value="<?=$ins_row["subject"]?>" <?if ($ins_name==$ins_row["subject"]){?>selected<?}else{}?>><?=$ins_row["subject"]?></option>
							<?}?>
						</select>

						<select name="plan_name">
							<option value="">플랜 선택</option>
							<?for($c=0;$c<count($Arr_plan_cd);$c++){?>
							<option value="<?=$Arr_plan_cd[$c+1]?>" <?if ($plan_name==$Arr_plan_cd[$c+1]){?>selected<?}else{}?>><?=$Arr_plan_cd[$c+1]?></option>
							<?}?>
						</select>

						<select name="chk_service">
							<option value="">서비스 선택</option>
							<option value="A" <?if ($chk_service=="A"){?>selected<?}else{}?>>A</option>
							<option value="B" <?if ($chk_service=="B"){?>selected<?}else{}?>>B</option>
							<option value="N" <?if ($chk_service=="N"){?>selected<?}else{}?>>없음</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>기간</th>
					<td>
						<select name="search_date_txt">
							<option value="writedate" <?if ($search_date_txt=="writedate"){?>selected<?}else{}?>>결제일</option>
							<option value="s_date" <?if ($search_date_txt=="s_date"){?>selected<?}else{}?>>보험개시일</option>
							<option value="e_date" <?if ($search_date_txt=="e_date"){?>selected<?}else{}?>>보험종료일</option>
							<option value="cancle_date" <?if ($search_date_txt=="cancle_date"){?>selected<?}else{}?>>취소일</option>
						</select>
						<input type="text" name="search_date_s" class="calendar w100 ml10" value="" />
						~ <input type="text" name="search_date_e" class="calendar w100" value="" />

						<select name="order_step" class="ml10">
							<option value="">결제상태 선택</option>
							<option value="1" <?if ($order_step=="1"){?>selected<?}else{}?>>입금전</option>
							<option value="2" <?if ($order_step=="2"){?>selected<?}else{}?>>결제완료</option>
							<option value="N" <?if ($order_step=="N"){?>selected<?}else{}?>>결제취소</option>
							<option value="P" <?if ($order_step=="P"){?>selected<?}else{}?>>부분취소</option>
							<option value="R" <?if ($order_step=="R"){?>selected<?}else{}?>>환불</option>
						</select>
					</td>
				</tr>
				<tr>
					<th>가입자</th>
					<td>
						<select name="search">
							<option value="o_phone" <?if ($search=="o_phone"){?>selected<?}else{}?>>휴대폰번호</option>
							<option value="o_name" <?if ($search=="o_name"){?>selected<?}else{}?>>이름</option>
						</select>
						<input type="text" name="search_text" value="<?=$search_text?>" />
						<input type="submit" value="검색" />
					</td>
				</tr>
			</table>
			</form>
			<!-- (e) 검색영역  -->

			<div class="btnWrap">
				<div class="leftWrap"><span class="totalCount">전체 : <strong><?=number_format($total_record)?></strong>건</span></div>
				<div class="rightWrap">
					<a href="javascript:;" class="btn_excel" onclick="excel_go();">엑셀다운로드</a>
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
				<col width="5%" />
				<col width="5%" />
				<col width="5%" />
				<col width="5%" />
				<col width="5%" />
				<col width="8%" />
				<col width="8%" />
			</colgroup>
			<tr>
				<th>NO</th>
				<th>상품명</th>
				<th>보험사</th>
				<th>플랜명</th>
				<th>서비스</th>
				<th>개시일</th>
				<th>종료일</th>
				<th>보험기간</th>
				<th>이름</th>
				<th>가입자수</th>
				<th>가입채널</th>
				<th>결제상태</th>
				<th>상품가</th>
				<th class="btn">결제금액
					<a href="?" class="up">▲</a>
					<a href="#" class="down">▼</a>
				</th>
				<th>가입일</th>
				<th class="btn">취소일
					<a href="#" class="up">▲</a>
					<a href="#" class="down">▼</a>
				</th>
			</tr>
			<? if ($total_record == 0) { ?>
			<tr onClick="view_go()" class="click">
				<td colspan="16"><?=$GLOBALS[msg_list_notdata]?></td>
			</tr>
			<?
				} else {
					while ($rows = $dbcon -> fetch_array($result)) {
						extract($rows);
						unset($rows);
						$bg = "";
						if($order_step == "N" || $order_step == "P") { //결제취소, 부분취소
							$bg = "#ededed";
						} else if($order_step == "R") { //환불
							$bg = "#eb8b8b";
						}
			?>
			<!--<tr onClick="view_go('<?=$orderno?>')" class="click" style="background:<?=$bg?>;">-->
			<tr style="background:<?=$bg?>;">
				<td><?=$no?></td>
				<td><?=$pr_name?></td>
				<td><?=$ins_name?></td>
				<td><?=$plan_name?></td>
				<td><?=$Arr_txt_plus[$chk_service]?></td>
				<td><?=$s_date?> <?=$s_date_time?>시</td>
				<td><?=$e_date?> <?=$e_date_time?>시</td>
				<td><?=$ins_period?> <?=$arr_chk_p_gubun[$chk_p]?></td>
				<td><?=all_seed_dec($o_name)?></td>
				<td><?=$join_cnt?>명</td>
				<td><?=$partnership_name?></td>
				<td><?=$arr_ord_step[$order_step]?></td>
				<td class="r"><?=number_format($ins_amount+$service_amount)?></td>
				<td class="r"><?=number_format($t_amount)?></td>
				<td><?=substr($writedate,0,10)?></td>
				<td><?=substr($cancledate,0,10)?></td>
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
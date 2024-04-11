<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
// 관리자 체크
admin_chk($auth_admin, $url_admin_login_out);

if ($mode=="list_mod"){
	for ($k=0;$k<count($_POST["idx"]);$k++){
		$SQL_in1 = "update tbl_board_product_country set";
		$SQL_in1 .= " c_code = '".$_POST["c_code"][$k]."' ";
		$SQL_in1 .= " , c_area = '".$_POST["c_area"][$k]."' ";
		$SQL_in1 .= " , c_name = '".$_POST["c_name"][$k]."' ";
		$SQL_in1 .= " , trip_yn = '".$_POST["trip_yn"][$k]."' ";
		$SQL_in1 .= " where idx= '".$_POST["idx"][$k]."' ";
		$RS_In1 = $dbcon -> query($SQL_in1);
	}

	header("location: pop_nation.php?pr_seq=".$pr_seq."&search=".$search."&search_text=".$search_text."&page=".$page." ");
	exit;
}


if ($mode=="list_write"){

	$SQL_in1 = "insert into tbl_board_product_country set";
	$SQL_in1 .= " c_code = '".$_POST["c_code"]."' ";
	$SQL_in1 .= " , c_area = '".$_POST["c_area"]."' ";
	$SQL_in1 .= " , c_name = '".$_POST["c_name"]."' ";
	$SQL_in1 .= " , trip_yn = '".$_POST["trip_yn"]."' ";
	$SQL_in1 .= " , pr_seq = '".$_POST["pr_seq"]."' ";
	$RS_In1 = $dbcon -> query($SQL_in1);
	header("location: pop_nation.php?pr_seq=".$pr_seq."&search=".$search."&search_text=".$search_text."&page=".$page." ");
	exit;
}







$tm = "main";
$lm = "";
include $path_admin."inc/header_pop.php";


	//$dbcon -> setDebug(1);
	$page_btn_prev =">>"; // > 버튼
	$page_btn_next ="<<"; // < 버튼

	// 페이지 설정
	$page				= REQSTR($page, 1);
	$num_per_page	= REQSTR($num_per_page, 20);
	$page_per_block	= REQSTR($page_per_block, 10);
	$first					= $num_per_page*($page-1);
	$last					= $num_per_page*$page;

	// 검색설정
	$query_where		= " and pr_seq = '".$pr_seq."' ";

	#### 검색 설정 Start
	$search_u_level				= REQSTR($search_u_level, "");
	$search_u_gubun				= REQSTR($search_u_gubun, "");
	$search_u_state				= REQSTR($search_u_state, "");
	$search_u_sex				= REQSTR($search_u_sex, "");

	$search							= REQSTR($search, "");
	$search_text					= REQSTR($search_text, "");

	$search_orderby				= REQSTR($search_orderby, "");
	$search_sort					= REQSTR($search_sort, "");

	$search_date					= REQSTR($search_date, "");
	$search_date_s				= REQSTR($search_date_s, "");
	$search_date_e				= REQSTR($search_date_e, "");


	if ( strlen($search_text) > 0 ) {
		$query_where .= " and ".$search." like '%".$search_text."%' ";
	}

	if ( strlen($search_date_s) > 0 ) $query_where .= " and ".$search_date." >= '".$search_date_s."' ";
	if ( strlen($search_date_e) > 0 ) $query_where .= " and ".$search_date." <= '".$search_date_e."' ";


	if ( strlen($search_orderby) == 0 ) $search_orderby .= " idx ";
	if ( strlen($search_sort) == 0 ) $search_sort .= "desc";


//	echo "search_u_level : ".$search_u_level."<BR>";
//	echo "search_u_gubun : ".$search_u_gubun."<BR>";
//	echo "search_u_state : ".$search_u_state."<BR>";
//	echo "search_u_sex : ".$search_u_sex."<BR>";
//	echo "search_orderby : ".$search_orderby."<BR>";
//	echo "search_sort : ".$search_sort."<BR>";
//	echo $query_where."<BR>";


	$parameter = "&pr_seq=".$pr_seq."&search=".$search."&search_text=".$search_text;

	#### 검색 설정 End

	// 쿼리설정
	$field				= " * ";

	$table			= " tbl_board_product_country ";
	$where			= $query_where;
	$orderby			= $search_orderby." ".$search_sort;
	$limit				= $first.", ".$num_per_page;

	$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);
	$total_record	= $ArrRS[0];
	$result			= $ArrRS[1];
	unset($ArrRS);

	// 페이지 & 리스트 설정
	$total_page		= ceil($total_record/$num_per_page);
	$no				= $total_record - $first;
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">여행국가 관리</td>
	</tr>
	<tr>
		<td colspan="2" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td>


			<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
			<input type="hidden" name="bc_id" value="<?=$bc_id?>">
			<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
			<input type="hidden" name="mode" value="list">
			<? if ($bc_category_use == "Y") { ?>
			<input type="hidden" name="search_category" value="<?=$search_category?>">
			<? } ?>


				<table class="adm-searchForm">
					<colgroup>
						<col width="8%" />
						<col width="92%" />
					</colgroup>
					<tbody>
						<tr>
							<th>검색</th>
							<td>
								<select name="search">
									<option value="c_name" <? if ($search == "c_name" ) echo "selected"; ?>>국가</option>
								</select>
								<input type="text" name="search_text" style="width:200px" maxlength="30" value="<?=$search_text?>" class="input">
								<input type="submit" value="검색">
							</td>
						</tr>
					</tbody>
				</table>
			</form>

			<div class="btnWrapR">
				<a href="javascript:list_mod();" class="btn_add">수정</a>
			</div>
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> <b>총 <font color="#FF0000"><?=$total_record?></font> 건의 데이터가 있습니다.</b></font></td>
					<td align="right">
					</td>
				</tr>
				<tr>
					<td height="4" colspan="2"></td>
				</tr>
			</table>
			<!-- //Count & Sort End -->



			<script language="JavaScript">
			<!--
				function list_mod() {
					var ff = document.frm;
					ff.action="<?=$PHP_SELF?>";
					ff.submit();
				}
			//-->
			</script>

			<!-- //SearchForm End -->


			<form name="frm" method="post">
			<input type="hidden" name="mode" value="list_mod">
			<input type="hidden" name="bc_id" value="<?=$bc_id?>">
			<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
			<input type="hidden" name="search" value="<?=$search?>">
			<input type="hidden" name="search_text" value="<?=$search_text?>">
			<input type="hidden" name="page" value="<?=$page?>">
			<table width="98%" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto;">
				<tr>
					<td colspan="100" height="2" bgcolor="#D5D5D5"></td>
				</tr>
				<tr>
					<td align="center" height="28" bgcolor="#F3F3F3" class="a_thead" width="50">No.</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="100">국가코드</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead">지역</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead">국가</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="100">가능여부</td>
				</tr>
			<? if ($total_record == 0) { ?>
				<tr align="center">
					<td colspan="100" height="28"><b><?=$GLOBALS[msg_list_notdata]?></b></td>
				</tr>
				<tr>
					<td colspan="100" height="1" bgcolor="#E5E5E5"></td>
				</tr>
			<?
				} else {
					while ($rows = $dbcon -> fetch_array($result)) {
						extract($rows);
						unset($rows);
			?>
				<tr>
					<td align="center" class="a_content_td" height="28"><?=$no?><input type="hidden" name="idx[]" value="<?=$idx?>"></td>
					<td align="center" class="a_content_td"><input type="text" name="c_code[]" class="input" value="<?=$c_code?>"></td>
					<td align="center" class="a_content_td"><input type="text" name="c_area[]" class="input" value="<?=$c_area?>"></td>
					<td align="center" class="a_content_td"><input type="text" name="c_name[]" class="input" value="<?=$c_name?>"></td>
					<td align="center" class="a_content_td"><input type="text" name="trip_yn[]" class="input" value="<?=$trip_yn?>"></td>
				</tr>
				<tr>
					<td colspan="100" height="1" bgcolor="#E5E5E5"></td>
				</tr>
			<?
						$no = $no - 1;
					}
				}
				unset($result);

			?>
			</table>
			</form>

			<table align=center>
				<tr>
					<td>
						<table cellpadding=0 cellspacing=3 border=0>
							<tr>
								<td width="4">&nbsp;</td>
								<td valign="top" style="padding:1 0 0 0">
									<?
										$bc_skin = "default";
										list_page($page, $total_page, $page_per_block) ;
									?>
								</td>
								<td width="4">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table border=0 cellspacing=0 cellpadding=0 width=100%>
				<tr>
					<td height=20></td>
				</tr>
			</table>


		</td>
	</tr>
</table>


			<form name="frm_write" method="post">
			<input type="hidden" name="mode" value="list_write">
			<input type="hidden" name="bc_id" value="<?=$bc_id?>">
			<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
			<input type="hidden" name="search" value="<?=$search?>">
			<input type="hidden" name="search_text" value="<?=$search_text?>">
			<input type="hidden" name="page" value="<?=$page?>">
			<table width="98%" cellspacing="0" cellpadding="0" border="0" style="margin:0 auto;">
				<tr>
					<td colspan="100" height="2" bgcolor="#D5D5D5"></td>
				</tr>
				<tr>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="100">국가코드</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead">지역</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead">국가</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="100">가능여부</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="100">관리</td>
				</tr>
				<tr>
					<td align="center" class="a_content_td"><input type="text" name="c_code" class="input" value=""></td>
					<td align="center" class="a_content_td"><input type="text" name="c_area" class="input" value=""></td>
					<td align="center" class="a_content_td"><input type="text" name="c_name" class="input" value=""></td>
					<td align="center" class="a_content_td"><select name="trip_yn" class="select"><option value="가능">가능</option><option value="불가능">불가능</option></select></td>
					<td align="center" class="a_content_td"><em class="inp_black1"><input type="button" value="추가" onclick="n_write();"></em></td>
				</tr>
				<tr>
					<td colspan="100" height="1" bgcolor="#E5E5E5"></td>
				</tr>
			</table>
			</form>
<script>
	function n_write(){
		var ff = document.frm_write;
		ff.action="<?=$PHP_SELF?>";
		ff.submit();
	}

	function page_go(page) {
		location.href = "?page="+page+"<?=$parameter?>";
	}

	function modify_go(idx,state) {
		ifr_act.location.href="tel_ok.php?idx="+idx+"&r_state="+state;
	}

	function drop_go(idx) {
		MSG = "정말 삭제 하시겠습니까?\n삭제하신 정보는 다시 복원하실 수 없습니다.";
		if (confirm(MSG)) {
			location.href = "tel_del.php?idx="+idx+"<?=$parameter?>";
		}
	}
</script>
<!-- ### 페이지 끝 ###  -->
<?
	$dbcon -> dbcon_close();
?>
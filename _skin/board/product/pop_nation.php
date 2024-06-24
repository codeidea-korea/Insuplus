<!DOCTYPE html>
<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
// 관리자 체크
admin_chk($auth_admin, $url_admin_login_out);

if ($mode=="list_mod"){
	for ($k=0;$k<count($_POST["idx"]);$k++){
		$order_number = $_POST["order_number"][$k];
    if ($order_number === '') {
    	$order_number = 'NULL';
    } else {
    	$order_number = intval($order_number); // Ensure it's an integer
    }
		$SQL_in1 = "update tbl_board_product_country set";
		$SQL_in1 .= " c_code = '".$_POST["c_code"][$k]."' ";
		$SQL_in1 .= " , c_area = '".$_POST["c_area"][$k]."' ";
		$SQL_in1 .= " , c_name = '".$_POST["c_name"][$k]."' ";
		$SQL_in1 .= " , trip_yn = '".$_POST["trip_yn"][$k]."' ";
		$SQL_in1 .= " , order_number = ".$order_number." ";
		$SQL_in1 .= " where idx= '".$_POST["idx"][$k]."' ";
		$RS_In1 = $dbcon -> query($SQL_in1);
	}

	header("location: pop_nation.php?pr_seq=".$pr_seq."&search=".$search."&search_text=".$search_text."&page=".$page." ");
	exit;
}


if ($mode=="list_write"){
	
	$SQL_DUP_CHK = " SELECT count(*) FROM tbl_board_product_country WHERE pr_seq = '".$_POST["pr_seq"]."' AND c_code = '".$_POST["c_code"]."' and c_name = '".$_POST["c_name"]."' ";
	
	$count = $dbcon -> getCount($SQL_DUP_CHK);
	if($count > 0) {
		$SQL_up1 = " UPDATE tbl_board_product_country set";
		$SQL_up1 .= "  c_area = '".$_POST["c_area"]."' ";
		$SQL_up1 .= " , c_name = '".$_POST["c_name"]."' ";
		$SQL_up1 .= " , trip_yn = '".$_POST["trip_yn"]."' ";
		$SQL_up1 .= " , order_number = '".$_POST["order_number"]."' ";
		$SQL_up1 .= " WHERE  pr_seq = '".$_POST["pr_seq"]."' AND c_code = '".$_POST["c_code"]."' ";
		$RS_up1 = $dbcon -> query($SQL_up1);
	} else {
		$SQL_in1 = "insert into tbl_board_product_country set";
		$SQL_in1 .= " c_code = '".$_POST["c_code"]."' ";
		$SQL_in1 .= " , c_area = '".$_POST["c_area"]."' ";
		$SQL_in1 .= " , c_name = '".$_POST["c_name"]."' ";
		$SQL_in1 .= " , trip_yn = '".$_POST["trip_yn"]."' ";
		$SQL_in1 .= " , pr_seq = '".$_POST["pr_seq"]."' ";
		$SQL_in1 .= " , order_number = '".$_POST["order_number"]."' ";
		$RS_In1 = $dbcon -> query($SQL_in1);
	}
	
	
	
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
	$orderby			= "order_number IS NULL, order_number ASC, ".$search_orderby." ".$search_sort;
	$limit				= $first.", ".$num_per_page;

	$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);
	$total_record	= $ArrRS[0];
	$result			= $ArrRS[1];
	unset($ArrRS);

	// 페이지 & 리스트 설정
	$total_page		= ceil($total_record/$num_per_page);
	$no				= $total_record - $first;
?>
<div class="popupWrap">
	<header>
		<h1>여행국가 관리</h1>
		<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
	</header>
	
	<div class="popContWrap">
		<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
		<input type="hidden" name="bc_id" value="<?=$bc_id?>">
		<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
		<input type="hidden" name="mode" value="list">
		<? if ($bc_category_use == "Y") { ?>
		<input type="hidden" name="search_category" value="<?=$search_category?>">
		<? } ?>
		<table class="adm-searchForm">
			<colgroup>
				<col width="12%" />
				<col width="88%" />
			</colgroup>
			<tbody>
				<tr>
					<th>검색</th>
					<td>
						<select name="search">
							<option value="c_name" <? if ($search == "c_name" ) echo "selected"; ?>>국가</option>
						</select>
						<input type="text" name="search_text" style="width:200px" maxlength="30" value="<?=$search_text?>">
						<input type="submit" value="검색">
					</td>
				</tr>
			</tbody>
		</table>
		</form>
		
		<div class="btnWrap">
			<div class="leftWrap">
				<span class="totalCount">전체 : <strong><?=$total_record?></strong>건</span>
			</div>
			<div class="rightWrap">
				<a href="javascript:list_mod();" class="btn_add">수정</a>
			</div>
		</div>
		
		<form name="frm" method="post">
		<input type="hidden" name="mode" value="list_mod">
		<input type="hidden" name="bc_id" value="<?=$bc_id?>">
		<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
		<input type="hidden" name="search" value="<?=$search?>">
		<input type="hidden" name="search_text" value="<?=$search_text?>">
		<input type="hidden" name="page" value="<?=$page?>">
		<table class="adm-list-tb">
		<colgroup>
			<col width="8%" />
			<col width="*" />
			<col width="25%" />
			<col width="25%" />
			<col width="10%" />
			<col width="10%" />
		</colgroup>
		<tr>
			<th>No.</th>
			<th>국가코드</th>
			<th>지역</th>
			<th>국가</th>
			<th>노출순서</th>
			<th>가능여부</th>
		</tr>
		<? if ($total_record == 0) { ?>
		<tr>
			<td colspan="6"><?=$GLOBALS[msg_list_notdata]?></td>
		</tr>
		<?
			} else {
				while ($rows = $dbcon -> fetch_array($result)) {
					extract($rows);
					unset($rows);
		?>
		<tr>
			<td><?=$no?><input type="hidden" name="idx[]" value="<?=$idx?>"></td>
			<td><input type="text" name="c_code[]" value="<?=$c_code?>"></td>
			<td><input type="text" name="c_area[]" value="<?=$c_area?>"></td>
			<td><input type="text" name="c_name[]" value="<?=$c_name?>"></td>
			<td><input type="text" name="order_number[]" value="<?=$order_number?>"></td>
			<td>
				<select name="trip_yn[]">
					<option value="가능" <?=$trip_yn=="가능" ? "selected":"";?>>가능</option>
					<option value="불가" <?=$trip_yn=="불가" ? "selected":"";?>>불가</option>
				</select>
				
			</td>
		</tr>
		<?
					$no = $no - 1;
				}
			}
			unset($result);

		?>
		</table>
		</form>
		
		<!-- ### 페이지 시작 ###  -->
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td align="center" valign="top" style="padding:10px 0 0 0">
					<? 
					$bc_skin = "default";
					list_page($page, $total_page, $page_per_block); 
					?>
				</td>
				</td>
			</tr>
		</table>
		<!-- ### 페이지 끝 ###  -->		
		
		<form name="frm_write" method="post">
		<input type="hidden" name="mode" value="list_write">
		<input type="hidden" name="bc_id" value="<?=$bc_id?>">
		<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
		<input type="hidden" name="search" value="<?=$search?>">
		<input type="hidden" name="search_text" value="<?=$search_text?>">
		<input type="hidden" name="page" value="<?=$page?>">
		<table class="adm-list-tb mt40">
		<colgroup>
			<col width="15%" />
			<col width="25%" />
			<col width="25%" />
			<col width="10%" />
			<col width="*" />
			<col width="10%" />
		</colgroup>
			<tr>
				<th>국가코드</th>
				<th>지역</th>
				<th>국가</th>
				<th>노출순서</th>
				<th>가능여부</th>
				<th>관리</th>
			</tr>
			<tr>
				<td><input type="text" name="c_code" value=""></td>
				<td><input type="text" name="c_area" value=""></td>
				<td><input type="text" name="c_name" value=""></td>
				<td><input type="text" name="order_number" value=""></td>
				<td><select name="trip_yn">
						<option value="가능">가능</option>
						<option value="불가">불가</option>
					</select>
				</td>
				<td><em class="inp_black1"><input type="button" value="추가" onclick="n_write();"></em></td>
			</tr>
		</table>
		</form>
	</div>
</div>		
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

	function list_mod() {
		var ff = document.frm;
		ff.action="<?=$PHP_SELF?>";
		ff.submit();
	}
</script>
<!-- ### 페이지 끝 ###  -->
</html>
<?
	$dbcon -> dbcon_close();
?>
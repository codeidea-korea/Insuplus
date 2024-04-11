<!DOCTYPE html>
<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
// 관리자 체크
admin_chk($auth_admin, $url_admin_login_out);

$tm = "";
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
	$query_where		= "  ";

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

	/////////////////////////////////////////////////////////////////////////////
	// 2023-06-28 added by kyle
	$selector							= REQSTR($selector, "");
	/////////////////////////////////////////////////////////////////////////////


	if ( strlen($search_text) > 0 ) {
		$query_where .= " and ".$search." like '%".$search_text."%' ";
	}

	if ( strlen($search_date_s) > 0 ) $query_where .= " and ".$search_date." >= '".$search_date_s."' ";
	if ( strlen($search_date_e) > 0 ) $query_where .= " and ".$search_date." <= '".$search_date_e."' ";


	if ( strlen($search_orderby) == 0 ) $search_orderby .= " seq ";
	if ( strlen($search_sort) == 0 ) $search_sort .= "desc";


//	echo "search_u_level : ".$search_u_level."<BR>";
//	echo "search_u_gubun : ".$search_u_gubun."<BR>";
//	echo "search_u_state : ".$search_u_state."<BR>";
//	echo "search_u_sex : ".$search_u_sex."<BR>";
//	echo "search_orderby : ".$search_orderby."<BR>";
//	echo "search_sort : ".$search_sort."<BR>";


	$parameter = "&search=".$search."&search_text=".$search_text;

	#### 검색 설정 End

	// 쿼리설정
	$field				= " * ";

	$table			= " tbl_board_guarantee ";
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
<div class="popupWrap">
	<header>
		<h1>보장내역 선택</h1>
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
							<option value="subject" <? if ($search == "subject" ) echo "selected"; ?>>보장내역명</option>
						</select>
						<input type="text" name="search_text" style="width:200px" maxlength="30" value="<?=$search_text?>" class="input">
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
			<col width="30%" />
			<col width="10%" />
		</colgroup>
			<tr>
				<th>No.</th>
				<th>보장내역명</th>
				<th>등록일</th>
				<th>선택</th>
			</tr>
			<? if ($total_record == 0) { ?>
			<tr>
				<td span="4"><?=$GLOBALS[msg_list_notdata]?></td>
			</tr>
			<?
			} else {
				while ($rows = $dbcon -> fetch_array($result)) {
					extract($rows);
					unset($rows);
			?>
				<tr>
					<td><?=$no?><input type="hidden" name="idx[]" value="<?=$seq?>"></td>
					<td class="l"><?=$subject?></td>
					<td><?=$regdate?></td>
					<td><em class="inp_black1"><input type="button" value="선택" onclick="g_select(<?=$seq?>,'<?=$subject?>');"></em></td>
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
			
	</div>
</div>

<script>
	function g_select(idx, subject){
		let selector = "<?= $selector ?>";
		let ff = opener.document.all;

		ff[selector].value = idx;
		$(`#${selector}_txt`,opener.document).text(subject);

		window.close();
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
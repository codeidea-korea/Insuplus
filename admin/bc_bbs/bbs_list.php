<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$page = REQSTR($page, 1);
	$num_per_page = REQSTR($num_per_page, 10000);
	$page_per_block = REQSTR($page_per_block, 10);

	$first = $num_per_page*($page-1);
	$last = $num_per_page;

	$query_where = "";

	########################################
	#### 검색 설정
	$search = REQSTR($search, "");
	$search_text = REQSTR($search_text, "");
	if ( (getLen($search_text) > 0) ) {
		$query_where .= " and ".$search." like '%".$search_text."%'";
	}

	#### parameter 설정
	$parameter = "search=".$search."&search_text=".$search_text."&num_per_page=".$num_per_page."&page_per_block=".$page_per_block;
	########################################

	########################################
	#### 게시글을 가져온다.. ####
	$field = " * ";
	$table = "config_board_list ";
	$where = $query_where;
	$orderby = " bc_name  asc ";
	$limit = $first.", ".$last;

	$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);

	$total_record = $ArrListRs[0];
	########################################

	########################################
	#### 전체 페이지수를 계산한다.
	$total_page = ceil($total_record/$num_per_page);

	$no = $total_record - $first;

	########################################

?>
<?
	$tm = "bbs";
	$lm = "";
	include $path_admin."inc/header.php";
?>

<script language='JavaScript'>
<!--

function checkForm()
{

	var frm = document.frmMake.f_table;
	var frm1 = document.frmMake.f_title;
	if(!frm.value){
		alert('게시판 코드명을 입력하세요');
		frm.focus();
		return false;
	}

	var valid = "abcdefghijklmnopqrstuvwxyz0123456789_"
	var ok = "yes";
	var temp;
	for (var i=0; i<frm.value.length; i++) {
		temp = "" + frm.value.substring(i, i+1);
		if (valid.indexOf(temp) == "-1") ok = "no";
	}
	if (ok == "no") {
		alert("영문자와 숫자, _(언더바) 의 조합으로만 입력할 수 있습니다");
		frm.focus();
		frm.select();
		return false;
	}

	if(!frm1.value){
		alert('게시판타이틀을 입력하세요');
		frm1.focus();
		return false;
	}

}
//-->
</script>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">게시판관리</td>
	</tr>
	<tr>
		<td colspan="2" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<!-- <form name="frmMake" action="<?=$url_admin?>admin_process.php" method="post" onSubmit="return checkForm()">
<input type="hidden" name="mode" value="write">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 게시판 생성하기</td>
	</tr>
	<tr>
		<td height="4"></td>
	</tr>
</table>
<table class="b_search_box" width="100%">
	<tr>
		<td>
			<table cellpadding="0" cellspacing="3" border="0">
				<tr>
					<td width="4">&nbsp;</td>
					<td class="a_content_td" valign="top" style="padding:3 4 0 0;letter-spacing:-1px">추가할 게시판 <font color="#006699">코드명( 영문과 숫자, _ 의 조합)</font>과 <font color="#006699">제목</font>을 입력하세요.</td>
					<td width="4">&nbsp;</td>
					<td>
						<table cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td>
									<select name="f_lang" class="a_input">
									<option value="kor">kor</option>
									<option value="eng">eng</option>
									</select>
								</td>
								<td width="4">&nbsp;</td>
								<td class="a_content_td" valign="top" style="padding:3 4 0 0;letter-spacing:-1px"><b>코드명</b></td>
								<td valign="top"><input type="text" name="f_table" style="width:100" maxlength="20" class="input"></td>
								<td width="4">&nbsp;</td>
								<td class="a_content_td" valign="top" style="padding:3 4 0 0;letter-spacing:-1px"><b>제목</b></td>
								<td valign="top"><input type="text" name="f_title" style="width:100" maxlength="100" class="input"></td>
								<td valign="top" style="padding:1 0 0 4"><input type="image" src="<?=$url_admin?>images/a_btn_brdadd.gif"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>


<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="20"></td>
	</tr>
</table>
 -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 게시판 전체 리스트 및 관리</td>
		<td align="right">※ <b>총 <font color=#FF0000><?=$total_record?></font> 개의 게시판이 있습니다.</b></td>
	</tr>
	<tr>
		<td height="4" colspan="2"></td>
	</tr>
</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td colspan="8" height="2" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="40" height="28">No.</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="120">게시판코드명</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="100">게시판스킨</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead">게시판제목</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="39">보기</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="49">설정</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="52">삭제</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="65">카테고리</td>
	</tr>
<?
	#### 게시글을 시작 ####
	if ( $total_record > 0 ) {
		while($ListRs = $dbcon -> fetch_array($ArrListRs[1])) {
			extract($ListRs);
			unset($ListRs);

			$content		= strip_tags($content);
			if ( getLen($content) > 250 ) {
				$content = mb_substr($content,0,250,"UTF-8")."...";
			}

			$PrintRegDate = date('Y/m/d', strtotime($regDt) );
			// NEW 이미지
			$sNew="";
			if(strtotime($regDt) > (time() - (60 * 60 * 24 * 2))) {
				$sNew = $img_new;
			}

?>

	<tr>
		<td align="center" class="a_content_td" height="28"><?=$no?></td>
		<td align="center" class="a_content_td"><font color="#FF6600"><b><?=$bc_id?></b></font></td>
		<td align="center" class="a_content_td"><?=$bc_skin?></td>
		<td align="center" class="a_content_td"><?=$bc_name?></td>
		<td align="center" class="a_content_td"><a href="<?=$bc_path?>" target="_blank"><img src='<?=$url_admin?>images/a_icon_brdview.gif' alt='게시판 보기'></a></td>
		<td align="center" class="a_content_td"><a href="javascript:modify_go('<?=$bc_id?>');"><img src='<?=$url_admin?>images/a_icon_modify.gif' alt='게시판 설정변경'></a></td>
		<td align="center" class="a_content_td"><a href="javascript:delete_go('<?=$bc_id?>');"><img src='<?=$url_admin?>images/a_icon_delete.gif' alt='게시판 삭제'></a></td>
		<td align="center" class="a_content_td"><a href="javascript:cate_go('<?=$bc_id?>')"><img src='<?=$url_admin?>images/a_icon_category.gif' alt='카테고리 추가 및 변경'></a></td>
	</tr>
	<tr>
		<td colspan="8" height="1" bgcolor="#E5E5E5"></td>
	</tr>
<?
			$no--;
		}
	}
	else {
?>
	<tr align="center">
		<td colspan="15" class="a_content_td">등록 된 데이터가 없습니다</td>
	</tr>
	<tr>
		<td colspan="8" height="1" bgcolor="#E5E5E5" height="28"></td>
	</tr>
<?
	}
	unset($ArrListRs);
	#### 게시글을 끝 ####

?>
	<tr>
		<td colspan="8" height="2" bgcolor="#E5E5E5"></td>
	</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td height="20" align="right"><a href="javascript:modify_go('')"><img src="<?=$url_admin?>images/a_btn_brdadd.gif"></a></td>
	</tr>
</table>


<script>
	function page_go(page) {
		location.href = "?page="+page+"&<?=$parameter?>";
	}

	function modify_go(bc_id) {
		location.href = "bbs_modify.php?bc_id="+bc_id;
	}

	function delete_go(bc_id) {
		if (confirm("정말로 삭제하시겠습니까?\n삭제한 데이타는 다시 복구 할 수 없습니다.")) {
			location.href = "bbs_del_ok.php?bc_id="+bc_id;
		}
	}

	function cate_go(bc_id) {
		PopCate = window.open("bbs_category.php?category=board&bc_id="+bc_id,"PopCate","width=450, height=500, toolbar=no, location=no, status=no, menubar=no, scrollbars=yes, resizable=no, left=150, top=150");
		PopCate.focus();
	}
</script>
<!-- ### 페이지 끝 ###  -->
<?
	$dbcon -> dbcon_close();
?>
<? include $path_admin."inc/footer.php"; ?>

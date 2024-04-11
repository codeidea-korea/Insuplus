<?
	// 쿼리설정
	$field				= "
							*
						";
	$table			= "
							tbl_popup
						";
	$where			= $query_where;
	$orderby			= "pop_seq desc";
	//$orderby			= $search_orderby." ".$search_sort;
	$limit				= $first.", ".$num_per_page;
	$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);
	$total_record	= $ArrRS[0];
	$result			= $ArrRS[1];
	unset($ArrRS);

	// 페이지 & 리스트 설정
	$total_page		= ceil($total_record/$num_per_page);
	$no				= $total_record - $first;

	$parameter = "search=".$search."&search_text=".$search_text;
	########################################
?>
<!-- ### 검색 시작 ###  -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_popup?>images/admin_ball.gif"> 검색</td>
	</tr>
	<tr>
		<td height="4" colspan="2"></td>
	</tr>
</table>
<div class="searchbox">
<form name="search_form" action="<?=$PHP_SELF?>" method="post" onsubmit="return search_go()">
	<select name="search">
		<option value="pop_subject" <? if ($search == "pop_subject") { echo "selected"; } ?>>제목</option>
		<option value="pop_content" <? if ($search == "pop_content") { echo "selected"; } ?>>내용</option>
	</select>
	<input type="text" name="search_text" value="<?=$search_text?>" class="textBox" >
	<input type="image" value="검색" src="../images/common/btn_search.gif" alt="검색" align="absmiddle">
</form>
</div>
<!-- ### 검색 끝 ###  -->


<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="10" colspan="2"></td>
	</tr>
	<tr>
		<td class="a_st01"><img src="<?=$url_popup?>images/admin_ball.gif"> 게시판 전체 리스트 및 관리</td>
		<td align="right">※ <b>총 <font color=#FF0000><?=$total_record?></font> 개의 데이타가 있습니다.</b></td>
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
		<td align="center" bgcolor="#F3F3F3" class="a_thead">제목</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="80">사용유무</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="200">공개기간</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="49">수정</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="52">삭제</td>
	</tr>
<?
	#### 게시글을 시작 ####
	if ( $total_record > 0 ) {
		while($ListRs = $dbcon -> fetch_array($result)) {
			extract($ListRs);
			unset($ListRs);

			$pop_sdate = date('Y/m/d', strtotime($pop_sdate) );
			$pop_edate = date('Y/m/d', strtotime($pop_edate) );
			// NEW 이미지
			$sNew="";
			if(strtotime($regDt) > (time() - (60 * 60 * 24 * 2))) {
				$sNew = $img_new;
			}
			$print_pop_use = "";
			if ($pop_use == "Y") $print_pop_use = "사용중";
			else $print_pop_use = "미사용";

?>

	<tr>
		<td align="center" class="a_content_td" height="28"><?=$no?></td>
		<td align="center" class="a_content_td"><a href="javascript:view_go('<?=$pop_seq?>')"><font color="#FF6600"><b><?=$pop_subject?></b></font></a></td>
		<td align="center" class="a_content_td"><?=$print_pop_use?></td>
		<td align="center" class="a_content_td"><?=$pop_sdate?>~<?=$pop_edate?></td>
		<td align="center" class="a_content_td"><a href="javascript:mod_go('<?=$pop_seq?>');"><img src='<?=$url_admin?>images/a_icon_modify.gif' alt='수정'></a></td>
		<td align="center" class="a_content_td"><a href="javascript:del_go('<?=$pop_seq?>');"><img src='<?=$url_admin?>images/a_icon_delete.gif' alt='삭제'></a></td>
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
		<td height="20" align="right"><a href="javascript:write_go('')"><img src="<?=$url_popup?>images/b_btn_write.gif"></a></td>
	</tr>
</table>

<!-- ### 페이지 시작 ###  -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center"><? list_page_common($page, $total_page, $page_per_block); ?></td>
	</tr>
</table>
<!-- ### 페이지 끝 ###  -->

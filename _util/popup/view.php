<?
	$pop_seq = REQSTR($pop_seq, "");
	isnull($pop_seq);

	if ( getLen($pop_seq) > 0 ) {
		$field = " * ";
		$table = "tbl_popup";
		$where = " and pop_seq = '".$pop_seq."' ";
		$orderby = " pop_seq desc ";
		$limit = "0, 1";

		$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);

		$total_record = $ArrListRs[0];
		if ( $total_record == 0 ) {
			$dbcon -> dbcon_close();
			alert_back("게시판 정보가 누락되었습니다.");
			exit;
		}

		$ListRs = $dbcon -> fetch_array($ArrListRs[1]);
		extract($ListRs);
		unset($ListRs);
		unset($ArrListRs);

		$pop_sdate			= getStrCut($pop_sdate, 10);
		$pop_edate			= getStrCut($pop_edate, 10);

		if ( mb_strlen($pop_subject) > 90 ) {
			$pop_subject = mb_substr($pop_subject,0,90,"UTF-8")."...";
		}

		$PrintRegDate = date('Y/m/d ', strtotime($regDt) );
		// NEW 이미지
		$sNew="";
		if(strtotime($regDt) > (time() - (60 * 60 * 24 * 2))) {
			$sNew = $img_new;
		}

	}

	$parameter = "page=".$page."&search=".$search."&search_text=".$search_text;

?>


<!-- ### 게시판 시작 ###  -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="2" class="a_line_2px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제목</td>
		<td class="a_content">
			<?=$pop_subject?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">사용여부</td>
		<td class="a_content">
			<? if ( $pop_use == "Y") { ?>
				사용중
			<? } else { ?>
				미사용
			<? } ?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">공개기간</td>
		<td class="a_content">
			<?=$pop_sdate?> ~ <?=$pop_edate?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">팝업창 사이즈</td>
		<td class="a_content">
			width : <?= $pop_size_wid?>, height : <?= $pop_size_hei?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">팝업창 위치</td>
		<td class="a_content">
			top : <?= $pop_coor_top?>, left : <?= $pop_coor_left?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">등록일</td>
		<td class="a_content"><?=$pop_regDt?></td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">내용</td>
		<td class="a_content">
			<?=REQSTR2($pop_content)?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_2px">&nbsp;</td>
	</tr>
</table>

<!-- ### 게시판 시작 ###  -->


<!-- ### 버튼 시작 ###  -->
<div class="btnBox" align="right">


<? if ( $reply_use == "Y" && $auth_reply ) { ?>
	<a href="javascript:reply_go('<?=$pop_seq?>');"><img src="../images/common/btn_reply.gif" alt="답글" border="0" /></a>
<? } ?>
<? if ( $auth_modify && ($ss_mem_id == $writer || $ss_mem_level == 10) ) { ?>
	<a href="javascript:mod_go('<?=$pop_seq?>');"><img src="../images/common/btn_modify.gif" alt="수정" border="0" /></a>
<? } ?>
<? if ( $auth_delete && ($ss_mem_id == $writer || $ss_mem_level == 10) ) { ?>
	<a href="javascript:del_go('<?=$pop_seq?>');"><img src="../images/common/btn_delete.gif" alt="삭제" /></a>&nbsp;&nbsp;&nbsp;
<? } ?>
	<a href="javascript:list_go();"><img src="../images/common/btn_list.gif" alt="목록" /></a>
</div>

<!-- ### 버튼 끝 ###  -->


<?
/*
조회수<?=$view_cnt?>
글쓰기<a href="javascript:write_go()">
<iframe name="board_frame" id="board_frame" src="" width="600" height="600" frameborder="0" height="0" hspace="0" marginheight="0" marginwidth="0" scrolling="yes" vspace="0" allowtransparency="true" ></iframe>
*/
?>
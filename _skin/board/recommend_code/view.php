<? if ($client_mode=="Y"){ //사용자 모드입니다?>

<? }else{ //관리자 모드 ?>
<table class="adm-view-tb">
<colgroup>
	<col width="8%" />
	<col width="42%" />
	<col width="8%" />
	<col width="42%" />
</colgroup>
	<tr>
		<th>제목</th>
		<td colspan="3"><?=$subject?></td>
	</tr>
	<tr>
		<th>추천코드</th>
		<td><?=$recommendation_code?></td>
		<th>등록일</th>
		<td><?=$PrintRegDate?></td>
	</tr>
	<tr>
		<th>제휴사</th>
		<td colspan="3">
		<?while ($CateListRs = $dbcon -> fetch_array($ArrPartnerListRs[1]) ) {
				extract($CateListRs);
				if ($recom_partnership_code == $partnership_code ) echo $partnership_name; }?>
		</td>
	</tr>
	<tr>
		<th>사용기간</th>
		<td><?=substr($start_date, 0, 10)?> ~ <?=substr($end_date, 0, 10)?></td>
		<th>할인율</th>
		<td><?=$discount?>%</td>
	</tr>
					
<?
	if ($bc_comment_use == 'Y') {
		$category = "board";
		$bc_id = $bc_id;
		$seq = $seq;
?>
	<!-- comments Start -->
	<tr>
		<td>
			<? include $path_comment."index.php";?>
		</td>
	</tr>
	<!-- comments End -->
<?
	 }
 ?>
</table>



<? if($bc_prev_next == "Y") {// 다음글 이전글 허용시 ?>
<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
		<td height=20 colspan="3"></td>
	</tr>
	<tr>
		<td colspan="3" class="m_line_1px"></td>
	</tr>
	<tr>
		<td width=80  class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_prev.gif"></td>
		<td width=2></td>
		<td class="b_nextprev">&nbsp;&nbsp;&nbsp;<?=$prev_subject?></td>
	</tr>
	<tr>
		<td colspan="3" class="m_line_1px"></td>
	</tr>
	<tr>
		<td width=80  class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_next.gif"></td>
		<td width=2></td>
		<td class="b_nextprev">&nbsp;&nbsp;&nbsp;<?=$next_subject?></td>
	</tr>
	<tr>
		<td colspan="3" class="m_line_1px"></td>
	</tr>
</table>
<? } ?>

</table>
<!-- (s) 하단  버튼 영역 -->
<div class="btnWrap">
	<div class="leftWrap">
		<a href="javascript:list_go();" class="btn_list">목록</a>
	</div>
	<div class="rightWrap">
		<? if ($auth_delete) { ?>
			<a href="javascript:del_go('<?=$seq?>');" class="btn_normal">삭제</a>
		<? } ?>
		<? if ( $auth_modify) { ?>
			<a href="javascript:mod_go('<?=$seq?>');" class="btn_normal">수정</a>
		<? } ?>
	</div>
</div>
<!-- (e) 하단  버튼 영역 -->
<?}?>
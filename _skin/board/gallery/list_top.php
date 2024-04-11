<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>
			<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
			<input type="hidden" name="bc_id" value="<?=$bc_id?>">
			<input type="hidden" name="mode" value="list">
			<? if ($bc_category_use == "Y") { ?>
			<input type="hidden" name="search_category" value="<?=$search_category?>">
			<? } ?>
					<!-- 검색 -->
					<div class="board_search">
						<div class="float_left" style="width:40%;">Total : <span class="f_strong f_black"><?=$total_record?>,</span> <span class="f_strong f_black">[<?=$page?> / <?=$total_page?>]<span> Page</div>
						<div class="float_right f_right" style="width:60%;">

								<select title="게시판 검색" class="selectSt01" style="width:88px;" name="search">
									<option value="subject" <? if ($search == "subject" ) echo "selected"; ?>>Title</option>
									<option value="content" <? if ($search == "content" ) echo "selected"; ?>>Contents</option>
									<option value="nick_name" <? if ($search == "nick_name" ) echo "selected"; ?>>Name</option>
									<option value="all" <? if ($search == "all" ) echo "selected"; ?>>All</option>
								</select>

								<input type="text" class="inpSt01" style="width:124px;" title="Input Search words" name="search_text" value="<?=$search_text?>"/>
								<input type="image" src="../_images/board/btn_search.png" alt="Search" style="margin-left:-5px;border:0px;">
						</div>
					</div>
			</form>



			<div class="boardList01 blankAreaTy01">
				<ul class="board_list mt20" style="width:100%;">

<?
//사용자 모드2입니다
}else if ($client_mode2=="Y"){?>


<?
//사용자 모바일 모드입니다
}else if ($client_mobile_mode=="Y"){
?>




<?
// 관리자 모드
}else{?>
    <? if ($bc_category_use == "Y") {
?>
<!-- 카테고리 검색 Start -->
<table class="b_search_box">
	<tr>
		<td>
			<select name="search" class="select" onchange="cate_go(this.value)">
			<option value="all" <? if ($search_category == "all" || $search_category == "" ) echo "selected"; ?>>전체</option>
			<?
        while ($CateListRs = $dbcon -> fetch_array($ArrCateListRs[1]) ) {
            extract($CateListRs);
			?>
			<option value="<?=$idx?>" <? if ($search_category == $idx ) echo "selected"; ?>><?=$cate_name?></option>
			<?
        }
			?>
			</select>
		</td>
	</tr>
</table>
<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
		<td height=10></td>
	</tr>
</table>
<!-- 카테고리 검색 End -->
<?
    }
?>

<form method="post" name="frmCheckDel" action="<?=$PHP_SELF?>">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td colspan="7" class="m_line_2px">&nbsp;</td>
	</tr>
	<tr>
		<?
    if ($ss_u_level >= $auth_admin) {
		?>
		<!-- <td width="50"  class="b_txt"><a href="javascript:checkInverse()"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_select.gif"></a></td> -->
		<?
    }
		?>


		<td width="50"  class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_no.gif"></td>
		<?
    if ($bc_category_use == "Y") {
		?>
		<td width="70" class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_category2.gif"></td>
		<?
    }
		?>
		<td class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_title.gif"></td>

		<?
    if ( $bc_upfile_cnt > 0 ) {
		?>
		<td width="41" class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_fileview.gif"></td>
		<?
    }
		?>
		<td width="70" class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_writer.gif"></td>
		<td width="70" class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_date.gif"></td>
		<td width="50" class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_view.gif"></td>
	</tr>
	<tr>
		<td colspan="7" class="m_line_1px">&nbsp;</td>
	</tr>
	<?}?>

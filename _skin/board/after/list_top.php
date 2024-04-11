<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>
				<!-- InConts -->
				<div id="InConts" class="Inconts">
					<!-- 컨텐츠 내용 -->
					<div class="board">

			<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
			<input type="hidden" name="bc_id" value="<?=$bc_id?>">
			<input type="hidden" name="mode" value="list">
			<? if ($bc_category_use == "Y") { ?>
			<input type="hidden" name="search_category" value="<?=$search_category?>">
			<? } ?>
						<div class="title">
							<p class="num01"><span>전체 <em class="fColor01 bold"><?=$total_record?>건</em></span> | <span>페이지 <em class="fColor01 bold"><?=$page?> / <?=$total_page?></em></span> 건</p>
							<div class="search">
								<?if ($area_code==''){?>
								<select name="ss" onchange="cate_go(this.value)" class="selectSt01" style="width:88px;">
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

								<select name="search_ext" onchange="this.form.submit();" class="selectSt01" style="width:88px;">
								<option value="all" <? if ($search_category == "all" || $search_category == "" ) echo "selected"; ?>>전체</option>
								<?
								$arr_k = 0;
								foreach ($Arr_interest as $key => $val) {?>
								<option value="<?=$key?>" <? if ($key == $search_ext ) echo "selected"; ?>><?=$val?></option>
								<?
								}
								?>
								</select>
								<?}?>

								<select title="게시판 검색" class="selectSt01" style="width:88px;" name="search">
									<option value="subject" <? if ($search == "subject" ) echo "selected"; ?>>제목</option>
									<option value="content" <? if ($search == "content" ) echo "selected"; ?>>내용</option>
									<option value="nick_name" <? if ($search == "nick_name" ) echo "selected"; ?>>이름</option>
									<option value="all" <? if ($search == "all" ) echo "selected"; ?>>전체</option>
								</select>

								<input type="text" class="inpSt01" style="width:124px;" title="검색어를 입력" name="search_text" value="<?=$search_text?>"/><input type="image" src="/images/board/btn_search01.gif" alt="검색" />
							</div>
						</div>
				</form>


						<ul class="imgList04 mT25">
<?}else{?>
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

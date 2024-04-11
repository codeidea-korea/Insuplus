<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

				<!-- InConts -->
				<div id="InConts" class="Inconts">

					<!-- 컨텐츠 내용 -->
					<div class="board encyclo">
						<div class="title">
							<p class="num01 bullTy12">좌측 목록에서 병명을 선택하면 상세 설명을 확인하실 수 있습니다.</p>
						</div>


			<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
			<input type="hidden" name="bc_id" value="<?=$bc_id?>">
			<input type="hidden" name="mode" value="list">
			<input type="hidden" name="search" value="subject">
			<? if ($bc_category_use == "Y") { ?>
			<input type="hidden" name="search_category" value="<?=$search_category?>">
			<? } ?>
						<div class="search">
							<input type="text" class="inpSt01" style="width:168px;" title="검색어를 입력" name="search_text" value="<?=$search_text?>"/><input type="image" src="/images/board/btn_search01.gif" alt="검색" />
						</div>
			</form>
						<ul class="tab tabBox02">
<?
#################################################################
## 관리자 모드입니다.
#################################################################
}else{?>
    <? if ($bc_category_use == "Y") {?>
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

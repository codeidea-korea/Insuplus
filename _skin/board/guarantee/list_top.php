<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

<?}else{?>
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
					<option value="subject" <? if ($search == "subject" ) echo "selected"; ?>>보장내역명</option>
				</select>
				<input type="text" name="search_text" value="<?=$search_text?>">
				<input type="submit" value="검색">
			</td>
		</tr>
	</tbody>
</table>
</form>

<? if ($auth_write) { ?>
<div class="btnWrapR">
	<a href="javascript:write_go();" class="btn_add">등록</a>
</div>
<? } ?>

<form method="post" name="frmCheckDel" action="<?=$PHP_SELF?>">
<table class="adm-list-tb">
<colgroup>
	<col width="6%" />
	<col width="*" />
	<col width="15%" />
</colgroup>
	<tr>
		<?if ($ss_u_level >= $auth_admin) {?>
		<!-- <td width="50"  class="b_txt"><a href="javascript:checkInverse()"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_select.gif"></a></td> -->
		<?}?>
		<td>No</td>
		<?if ($bc_category_use == "Y") {?>
		<td><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_category2.gif"></td>
		<?}?>
		<td>보장 내역명</td>
		<?if ( $bc_upfile_cnt > 0 ) {?>
		<td><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_fileview.gif"></td>
		<?}?>
		<td>등록일</td>
	</tr>
<?}?>

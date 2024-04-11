<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

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

<? if ($auth_write) { ?>
<div class="btnWrapR">
	<a href="javascript:write_go();" class="btn_add">등록</a>
</div>
<? } ?>

<form method="post" name="frmCheckDel" action="<?=$PHP_SELF?>">
<table class="adm-list-tb">
<colgroup>
	<col width="5%" />
	<col width="*" />
	<col width="8%" />
	<col width="8%" />
</colgroup>
	<tr>
		<?if ($ss_u_level >= $auth_admin) {?>
		<!-- <td width="50"  class="b_txt"><a href="javascript:checkInverse()"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_select.gif"></a></td> -->
		<?}?>
		<th>No</th>
		<?if ($bc_category_use == "Y") {?>
		<td width="70" class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_category2.gif"></td>
		<?}?>
		<th>서비스 이용약관명</th>
		<?if ( $bc_upfile_cnt > 0 ) {?>
		<th>첨부파일</th>
		<?}?>
		<th>등록일</th>
	</tr>
	<?}?>

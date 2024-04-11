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
</form>

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
<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td height="10"></td>
	</tr>
</table>
<!-- 카테고리 검색 End -->
<?}?>

<? if ($auth_write) { ?>
<div class="btnWrapR">
	<a href="javascript:write_go();" class="btn_add">등록</a>
</div>
<? } ?>

<form method="post" name="frmCheckDel" action="<?=$PHP_SELF?>">
<table class="adm-list-tb">
<colgroup>
	<col width="20%" />
	<col width="25%" />
	<col width="*" />
	<col width="*" />
</colgroup>
<tr>
	<th>상품명</th>
	<th>카테고리 전체(지역/구분1/구분2/구분3)</th>
	<th>공개여부</th>
	<th>등록일</th>
</tr>
<?}?>
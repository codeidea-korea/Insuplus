<?if ($client_mode=="Y"){ //사용자?>

<? } else {//관리자 ?>

<!-- (s) 검색영역  -->
<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
<input type="hidden" name="bc_id" value="<?=$bc_id?>">
<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
<input type="hidden" name="mode" value="list">
<? if ($bc_category_use == "Y") { ?>
<input type="hidden" name="search_category" value="<?=$search_category?>">
<? } ?>
	<table class="adm-searchForm">
		<colgroup>
			<col width="12%" />
			<col width="88%" />
		</colgroup>
		<tbody>
			<tr>
				<th>검색</th>
				<td>
					<input type="hidden" name="search" value="subject" />
					<input type="text" name="search_text" maxlength="30" value="<?=$search_text?>"/>
					<input type="submit" value="검색"></td>
					
				</td>
			</tr>
		</tbody>
	</table>
</form>
<!-- (e) 검색영역  -->

<div class="btnWrapR">
	<a href="javascript:write_go();" class="btn_add">등록</a>
</div>

<form name="frm" method="post">
	<input type="hidden" name="mode" value="list_mod">
	<input type="hidden" name="bc_id" value="<?=$bc_id?>">
	<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
	<input type="hidden" name="search" value="<?=$search?>">
	<input type="hidden" name="search_text" value="<?=$search_text?>">
	<input type="hidden" name="page" value="<?=$page?>">
	<table class="adm-list-tb">
	<colgroup>
		<col width="20%" />
		<col width="*" />
		<col width="20%" />
		<col width="10%" />
	</colgroup>
	<tr>
		<th>배너 이미지</th>
		<th>제목</td>
		<th>선택</th>
		<th>등록일</th>
	</tr>
</div>
</div>
<?}?>

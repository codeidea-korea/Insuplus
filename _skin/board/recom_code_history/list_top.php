<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

<?
#############################################################
## 관리자 모드
#############################################################
}else{?>
<!-- (s) 검색영역  -->
<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
<input type="hidden" name="bc_id" value="<?=$bc_id?>">
<input type="hidden" name="mode" value="list">
<? if ($bc_category_use == "Y") { ?>
<input type="hidden" name="search_category" value="<?=$search_category?>">
<? } ?>

<? if ($bc_search_use == "Y") { ?>
<table class="adm-searchForm">
	<colgroup>
		<col width="8%" />
		<col width="20%" />
		<col width="92%" />
	</colgroup>
	<tr>
		<th>이름</th>
		<td colspan="2">
			<input type="hidden" name="search" value="contractor" />
			<input type="text" name="search_text" value="<?=$search_text?>" />
			<input type="submit" value="검색" />
		</td>
	</tr>
</table>			
<? } ?>
</form>
<!-- (e) 검색영역  -->

<form method="post" name="frmCheckDel" action="<?=$PHP_SELF?>">
<!-- 190725 수정 -->


<table class="adm-list-tb">
<colgroup>
	<col width="10%" />
	<col width="10%" />
	<col width="10%" />
	<col width="10%" />
	<col width="10%" />
	<col width="10%" />
</colgroup>
<tr>
	<th>상품명</th>
	<th>제휴사</th>
	<th>추천코드</th>
	<th>계약자</th>
	<th>상품가격</th>
	<th>등록일</th>
</tr>
<?}?>

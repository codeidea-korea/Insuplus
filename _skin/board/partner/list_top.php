<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

<?
#############################################################
## 관리자 모드
#############################################################
}else{
?>
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
		<th>제휴사</th>
		<td colspan="2">
			<input type="hidden" name="search" value="partnership_name" />
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

<div class="btnWrapR">
<? if ($auth_write) { ?>
	<a href="javascript:write_go();" class="btn_add">등록</a>
<? } ?>
</div>

<table class="adm-list-tb">
<colgroup>
	<col width="6%" />
	<col width="10%" />
	<col width="10%" />
	<col width="10%" />
	<col width="*" />
	<col width="15%" />
	<col width="15%" />
	<!-- <col width="15%" /> -->
</colgroup>
<tr>
	<th>NO</th>
	<th>제휴사</th>
	<th>제휴코드</th>
	<th>구분</th>
	<th>트레킹 URL</th>
	<th>제휴 수수료</th>
	<th>제휴기간</th>
	<!-- <th>등록일</th> -->
</tr>
<?}?>


<? if($client_mode=="Y"){ //사용자 모드입니다
?>

<? }else{ //관리자 ?>
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
		<th>기간</th>
		<td colspan="2">
			<select name="date_type">
				<option value="regdate" <? if ($date_type == "regdate" ) echo "selected"; ?>>등록일</option>
				<option value="partner" <? if ($date_type == "partner" ) echo "selected"; ?>>사용기간</option>
			</select>
			<input type="text" id="start_date" name="start_date" value="<?=$start_date?>" class="datepicker w100">
			<span style="padding-left: 5px; padding-right: 5px;">~</span>
			<input type="text" id="end_date" name="end_date" value="<?=$end_date?>" class="datepicker w100">
		</td>
	</tr>
	<tr>
		<th>제휴사</th>
		<td colspan="2">
			<select name="search">
				<option value="subject" <? if ($search == "subject" ) echo "selected"; ?>>제목</option>
				<option value="category" <? if ($search == "category" ) echo "selected"; ?>>제휴사</option>
			</select>
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
	<col width="*" />
	<col width="10%" />
	<col width="15%" />
	<col width="6%" />
	<col width="6%" />
	<col width="10%" />
	<col width="15%" />
</colgroup>
<tr>
	<th>NO</th>
	<th>제휴사</th>
	<th>제목</th>
	<th>추천코드</th>
	<th>사용기간</th>
	<th>할인율</th>
	<th>사용건</th>
	<th>내역확인</th>
	<th>등록일</th>
</tr>
<?}?>

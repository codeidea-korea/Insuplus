<? if ($client_mode=="Y"){ //사용자 ?>
				

<? } else {//관리자 ?>
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
		<col width="92%" />
	</colgroup>
	<tr>
		<th>청구</th>
		<td><select name="">
				<option value="">상품명 선택</option>
			</select>
			<select name="" class="ml10">
				<option value="">보험사 선택</option>
			</select>
			<select name="" class="ml10">
				<option value="">청구상태 선택</option>
			</select>
		</td>
	</tr>
	<tr>
		<th>청구일</th>
		<td><input type="text" name="start_date" class="datepicker w100" readonly> ~ <input type="text" name="end_date" class="datepicker w100" readonly>
		</td>
	</tr>
	<tr>
		<th>직접검색</th>
		<td>
			<select name="search">
				<option value="subject" <? if ($search == "subject" ) echo "selected"; ?>>제목</option>
				<option value="name" <? if ($search == "name" ) echo "selected"; ?>>이름</option>
				<option value="customer_content" <? if ($search == "customer_content" ) echo "selected"; ?>>문의내용</option>
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

<div class="btnWrapR">
	<a href="javascript:;" class="btn_excel">엑셀다운로드</a>
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
	<col width="10%" />
	<col width="*" />
	<col width="10%" />
	<col width="10%" />
	<col width="10%" />
</colgroup>
<tr>
	<th>NO</th>
	<th>상품명</th>
	<th>보험사</th>
	<th>플랜명</th>
	<th>가입자</th>
	<th>보험기간</th>
	<th>청구일</th>
	<th>발생일</th>
	<th>청구상태</th>
</tr>
<?}?>

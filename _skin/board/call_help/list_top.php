<?
//사용자 모드입니다
if ($client_mode=="Y"){
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
		<th>공개여부</th>
		<td width="200px">
			<input type="radio" name="secretVal" id="secretVal" value=""  <? if (!$secretVal) echo "checked"; ?>/><label>전체</label>
			<input type="radio" name="secretVal" id="secretVal" value="N" <? if ($secretVal == "N") echo "checked"; ?>/><label>공개</label>
			<input type="radio" name="secretVal" id="secretVal" value="Y" <? if ($secretVal == "Y") echo "checked"; ?>/><label>비공개</label>
		</td>
		<td>
			<!-- 카테고리 검색 Start -->
			<select name="search" onchange="cate_go(this.value)">
				<option value="all" <? if ($search_category == "all" || $search_category == "" ) echo "selected"; ?>>구분</option>
				<?while ($CateListRs = $dbcon -> fetch_array($ArrCateListRs[1]) ) {
            	extract($CateListRs);?>
				<option value="<?=$idx?>" <? if ($search_category == $idx ) echo "selected"; ?>><?=$cate_name?></option>
				<?}?>
			</select>
			<!-- 카테고리 검색 End -->
		</td>
	</tr>
	
	<tr>
		<th>직접검색</th>
		<td colspan="2">
			<select name="search">
				<option value="subject" <? if ($search == "subject" ) echo "selected"; ?>>제목</option>
				<option value="name" <? if ($search == "name" ) echo "selected"; ?>>플랜명</option>
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
</colgroup>
<tr>
	<th>NO</th>
	<th>구분</th>
	<th>제목</th>
	<th>공개여부</th>
	<th>등록일</th>
</tr>

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
		<th>공개여부</th>
		<td width="200px">
			<input type="radio" name="secretVal" id="secretVal" value=""  <? if (!$secretVal) echo "checked"; ?>/><label>전체</label>
			<input type="radio" name="secretVal" id="secretVal" value="N" <? if ($secretVal == "N") echo "checked"; ?>/><label>공개</label>
			<input type="radio" name="secretVal" id="secretVal" value="Y" <? if ($secretVal == "Y") echo "checked"; ?>/><label>비공개</label>
		</td>
		<td>
			<!-- 카테고리 검색 Start -->
			<select name="search" onchange="cate_go(this.value)">
				<option value="all" <? if ($search_category == "all" || $search_category == "" ) echo "selected"; ?>>구분</option>
				<?while ($CateListRs = $dbcon -> fetch_array($ArrCateListRs[1]) ) {
            	extract($CateListRs);?>
				<option value="<?=$idx?>" <? if ($search_category == $idx ) echo "selected"; ?>><?=$cate_name?></option>
				<?}?>
			</select>
			<!-- 카테고리 검색 End -->
		</td>
	</tr>
	
	<tr>
		<th>직접검색</th>
		<td colspan="2">
			<select name="search">
				<option value="subject" <? if ($search == "subject" ) echo "selected"; ?>>제목</option>
				<option value="name" <? if ($search == "name" ) echo "selected"; ?>>플랜명</option>
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
</colgroup>
<tr>
	<th>NO</th>
	<th>구분</th>
	<th>제목</th>
	<th>공개여부</th>
	<th>등록일</th>
</tr>
<?}?>

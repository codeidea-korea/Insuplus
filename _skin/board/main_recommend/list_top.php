<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

<?}else{?>
<script>
 	function popup_main_recommend_roll(code) {
		window.open("/_skin/board/<?=$bc_skin?>/popup_main_recommend_roll.php?bc_id=<?=$bc_id?>","_pop","width=600,height=600");
	}
</script>
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
	<tbody>
		<tr>
			<th>공개여부</th>
			<td>
				<input type="radio" name="secretVal" id="secretVal" value=""  <? if (!$secretVal) echo "checked"; ?>/><label>전체</label>
				<input type="radio" name="secretVal" id="secretVal" value="N" <? if ($secretVal == "N") echo "checked"; ?>/><label>공개</label>
				<input type="radio" name="secretVal" id="secretVal" value="Y" <? if ($secretVal == "Y") echo "checked"; ?>/><label>비공개</label>
			</td>
		</tr>
		<tr>
			<th>등록일</th>
			<td>
				<!-- 시작일 -->
				<input type="text" id="search_date_s" name="search_date_s" value="<?=$search_date_s?>" class="w100 datepicker">
				<span style="padding-left: 5px; padding-right: 5px;">~</span>
				<input type="text" id="search_date_e" name="search_date_e" value="<?=$search_date_e?>" class="w100 datepicker">
			</td>
		</tr>
		<tr>
			<th>직접검색</th>									
			<td>
				<select name="search">
					<option value="subject" <? if ($search == "subject" ) echo "selected"; ?>>제목</option>
				</select>
				<input type="text" name="search_text" maxlength="30" value="<?=$search_text?>">
				<input type="submit" value="검색">
			</td>
		</tr>
	</tbody>
</table>
<div class="btnWrapR">
	<a href="javascript:popup_main_recommend_roll();" class="btn_normal">메인 노출순서 변경</a>
	<a href="javascript:write_go();" class="btn_add">등록</a>
</div>
<? } ?>
</form>

<form method="post" name="frmCheckDel" action="<?=$PHP_SELF?>">
<table class="adm-list-tb">
<colgroup>
	<col width="6%" />
	<col width="6%" />
	<col width="*" />
	<col width="20%" />
	<col width="10%" />
	<col width="15%" />
</colgroup>
<tr>
	<th>NO</th>
	<th>노출순서</th>
	<th>제목</th>
	<th>노출기간</th>
	<th>상태</th>
	<th>등록일</th>
</tr>
<?}?>

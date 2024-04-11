<?
//사용자 모드입니다
if ($client_mode=="Y"){
	$_SESSION["ss_view_seq"] = "";
	$RS_PR = getGlobalProduct(); //탭메뉴 불러오기
?>
	<div class="col-md-10 col-sm-9">
		<div class="sub-content cs-wrap">
			<div class='row m-b-2'>
			<form name="SearchForm" method="get" action="<?=$PHP_SELF?>">
			<input type="hidden" name="bc_id" value="<?=$bc_id?>">
			<input type="hidden" name="search" value="all">
			<input type="hidden" name="mode" value="list">
				<div class='col-lg-6 col-md-6 col-sm-8 col-xs-12 col-lg-offset-6 col-md-offset-6 col-sm-offset-4 col-xs-offset-0'>
					<div class="input-group">
						<input type="text" name="search_text" class='form-control' placeholder='제목으로 검색해 주세요' value="<?=$search_text;?>">
						<span class="input-group-btn" onClick="frontSearch()"><a class='btn btn-default'><i class='fa fa-search'></i> 검색</a></span>
					</div>
				</div>
			</div>
			</form>
			<div id='event_list'>
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
			<select name="event_type" onchange="cate_go(this.value)">
				<option value="" <? if ($event_type == "" ) echo "selected"; ?>>전체</option>
				<option value="N" <? if ($event_type == "N" ) echo "selected"; ?>>일반</option>
				<option value="C" <? if ($event_type == "C" ) echo "selected"; ?>>쿠폰</option>
			</select>
			<!-- 카테고리 검색 End -->
		</td>
	</tr>
	<tr>
		<th>이벤트 기간</th>
		<td colspan="2">
	
			<input type="text" id="search_date_s" name="search_date_s" value="<?=$search_date_s?>" class="w100 datepicker">
			<!--  <span style="padding-left: 5px; padding-right: 5px;">~</span>
			<input type="text" id="search_date_e" name="search_date_e" value="<?=$search_date_e?>" class="w100 datepicker">-->
	
		</td>
	</tr>
	<tr>
		<th>이벤트</th>
		<td colspan="2">
			<select name="search">
				<option value="subject" <? if ($search == "subject" ) echo "selected"; ?>>제목</option>
				<option value="partner" <? if ($search == "partner" ) echo "selected"; ?>>제휴사</option>
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
	<col width="5%">
	<col width="5%">
	<col width="10%">
	<col width="10%">
	<col width="*">
	<col width="20%">
	<col width="6%">
	<col width="8%">
	<col width="15%">
</colgroup>
<tr>
	<th>NO</th>
	<th>구분</th>
	<th>제휴사</th>
	<th>배너</th>
	<th>이벤트 명</th>
	<th>이벤트 기간</th>
	<th>조회수</th>
	<th>노출</th>
	<th>등록일</th>
</tr>
<?}?>

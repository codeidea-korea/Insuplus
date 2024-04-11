<?
//사용자 모드입니다
if ($client_mode=="Y"){
	$_SESSION["ss_view_seq"] = "";
?>
	<!-- (s) col-md-10 col-sm-9  -->
	<div class="col-md-10 col-sm-9">
		<div class="sub-content cs-wrap">
			<div class='row m-y-2'>
			<form name="SearchForm" method="get" action="<?=$PHP_SELF?>">
			<input type="hidden" name="bc_id" value="<?=$bc_id?>">
			<input type="hidden" name="search" value="all">
			<input type="hidden" name="mode" value="list">
				<div class='col-lg-5 col-md-6 col-sm-8 col-xs-12 col-lg-offset-7 col-md-offset-6 col-sm-offset-4 col-xs-offset-0'>
					<div class="input-group">
						<input type="text" name="search_text" class='form-control' placeholder='제목+내용으로 검색해 주세요' value="<?=$search_text;?>">
						<span class="input-group-btn" onClick="frontSearch()"><a class='btn btn-default' ><i class='fa fa-search'></i> 검색</a></span>
					</div>
				</div>
			</div>
			<table width="100%" class="table table-break table-list">
				<colgroup>
					<col width='*' />
					<col width='10%' />
					<col width='10%' />
				</colgroup>
				<thead>
					<tr>
						<th>제목</th>
						<th>조회수</th>
						<th>등록일</th>
					</tr>
				</thead>
				<tbody class='text-center'>
<?}else{?>

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
				<input type="text" id="search_date_s" name="search_date_s" value="<?=$search_date_s?>" class="datepicker w100">
				~
				<input type="text" id="search_date_e" name="search_date_e" value="<?=$search_date_e?>" class="datepicker w100">
			</td>
		</tr>
		<tr>
			<th>직접검색</th>									
			<td>
				<select name="search">
					<option value="subject" <? if ($search == "subject" ) echo "selected"; ?>>제목</option>
					<option value="content" <? if ($search == "content" ) echo "selected"; ?>>내용</option>
				</select>
				<input type="text" name="search_text" style="width:200px" maxlength="30" value="<?=$search_text?>">
				<input type="submit" value="검색">
			</td>
		</tr>
	</tbody>
</table>
<div class="btnWrapR">
	<a href="javascript:write_go();" class="btn_add">등록</a>
</div>
<? } ?>

</form>
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

<form method="post" name="frmCheckDel" action="<?=$PHP_SELF?>">
<table class="adm-list-tb">
<colgroup>
	<col width="6%" />
	<col width="*" />
	<col width="10%" />
	<col width="10%" />
	<col width="15%" />
</colgroup>
<tr>
	<td>NO</td>
		<?
    if ($bc_category_use == "Y") {
		?>
		<td width="70" class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_category2.gif"></td>
		<?
    }
		?>
		<td>제목</td>

		<?
    if ( $bc_upfile_cnt > 0 ) {
		?>
		<td>제목</td>
		<?
    }
		?>
		<td>조회수</td>
		<td>공개여부</td>
		<td>등록일</td>
	</tr>
	<?}?>

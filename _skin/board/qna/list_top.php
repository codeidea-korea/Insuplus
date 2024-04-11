<? if ($client_mode=="Y"){ //사용자 
	$_SESSION["ss_view_seq"] = "";
	$RS_PR = getGlobalProduct(); //탭메뉴 불러오기
?>
<!-- (s) col-md-10 col-sm-9  -->
<div class="col-md-10 col-sm-9">
	<div class="sub-content cs-wrap">
		<!-- <div class='tab-responsive'>
			<ul class='nav nav-tabs nav-justified'>
				<li <? if(!$pr_cd) {?>class='active'<? } ?>><a href="javascript:;" onClick="frontTab('')">전체</a></li>
				<? while($pr_row = $dbcon -> fetch_array($RS_PR)) { ?>
				<li <? if($pr_cd == $pr_row["seq"]) {?>class='active'<? } ?>><a href="javascript:;" onClick="frontTab('<?=$pr_row["seq"]?>')"><?=$pr_row["subject"]?></a></li>
				<? } ?>
			</ul>
		</div> -->
	
		<!-- (s) 검색영역 -->
		<form name="SearchForm" method="get" action="<?=$PHP_SELF?>">
		<input type="hidden" name="bc_id" value="<?=$bc_id?>">
		<input type="hidden" name="search" value="name">
		<input type="hidden" name="pr_cd" value="">
		<input type="hidden" name="mode" value="list">
		<div class='row m-y-2'>
			<div class='col-lg-5 col-md-6 col-sm-8 col-xs-12 col-lg-offset-7 col-md-offset-6 col-sm-offset-4 col-xs-offset-0'>
				<div class="input-group">
					<input type="text" name="search_text" class='form-control' placeholder='작성자명으로 검색해 주세요' value="<?=$search_text;?>">
					<span class="input-group-btn" onClick="frontSearch()"><a class='btn btn-default' ><i class='fa fa-search'></i> 검색</a></span>
				</div>
			</div>
		</div>
		</form>
		<!-- (e) 검색영역 -->
		
		<table width="100%" class="table table-list table-break">
			<colgroup class='hidden-xs'>
				<col width='15%' />
				<col width='*' />
				<col width='10%' />
				<col width='10%' />
				<col width='10%' />
			</colgroup>
			<thead class='hidden-xs'>
				<tr>
					<th>상품명</th>
					<th>제목</th>
					<th>작성자</th>
					<th>답변여부</th>
					<th>등록일</th>
				</tr>
			</thead>
			<tbody class='text-center'>
		
<? }else{ //관리자 ?>

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
<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
		<td height=10></td>
	</tr>
</table>
<!-- 카테고리 검색 End -->
<?
    }
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
		<col width="92%" />
	</colgroup>
	<tr>
		<th>답변상태</th>
		<td><input type="radio" name="status" id="status_0" value=""  <?=$status=="" ? "checked":"";?>/><label for="status_0">전체</label>
			<input type="radio" name="status" id="status_W" value="W" <?=$status=="W" ? "checked":"";?>/><label for="status_W">대기</label>
			<input type="radio" name="status" id="status_C" value="C" <?=$status=="C" ? "checked":"";?>/><label for="status_C">확인중</label>
			<input type="radio" name="status" id="status_A" value="A" <?=$status=="A" ? "checked":"";?>/><label for="status_A">답변완료</label>
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
<!-- 190725 수정 -->

<div class="btnWrapR">
<? if ($auth_write) { ?>
	<a href="javascript:write_go();" class="btn_add">등록</a>
<? } ?>
</div>

<table class="adm-list-tb">
<colgroup>
	<col width="6%" />
	<col width="*" />
	<col width="10%" />
	<col width="15%" />
	<col width="10%" />
	<col width="15%" />
</colgroup>
<tr>
	<th>NO</th>
	<th>제목</th>
	<th>이름</th>
	<th>상품명</th>
	<th>답변상태</th>
	<th>등록일</th>
</tr>
<?}?>

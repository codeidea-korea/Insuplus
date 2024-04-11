<?php
	include '../_include/_header.html';
	include '../_include/_top.html';
	include '../_include/_sidebar.html';

	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php";
	#############################
	#### 파일 설정 세팅
	
	$PR_INFO = getInsuProductInfo($PR_SEQ);


	$list_type = "gallery";// 이미지 width ( 갤러리일때 사용하면 좋다.)
	$image_view_width = 600;
	$Arr_bc_upfile_ext_upload			= explode(",",$bc_upfile_ext_upload);			// 제한 확장자
	$bc_upfile_size						= 1024 * 1024 * $bc_upfile_size;				// 제한 사이즈
	$upload_path							= $path_data."board/".$bc_id;					// 업로드 폴더
	$upload_url								= $url_data."board/".$bc_id;					// 업로드 폴더
	// 업로드 사이즈
	$upload_size = 1024 * 1024 * 2;// "2048000"
	$Stop_Extension		= explode(",", $bc_upfile_ext_upload);
	$Stop_Size				= 1024 * 1024 * 10;

	$UpFileDirectory		= $path_root."_data/bbs/".$bc_id."/";
	$UpFileCategory		= $bc_id;

	#############################



	#############################
	#### 페이지 설정
	$page = REQSTR($page, 1);
	$num_per_page = 10;
	$page_per_block = 10;

	$first = $num_per_page*($page-1);
	$last = $num_per_page;

	$parameter .= "&num_per_page=".$num_per_page."&page_per_block=".$page_per_block;

	#############################

	########################################
	#### 검색 설정
//	echo "search : ".$search."<BR>";
//	echo "search_text : ".$search_text."<BR>";

	$search_category = REQSTR($search_category, "all");
	$search = REQSTR($search, "");
	$search_text = REQSTR($search_text, "");
	$secretVal = REQSTR($secretVal, "");
	$pr_cd = REQSTR($PR_SEQ, "");
	$nation = REQSTR($nation, "");
	
	if($nation) {
		$query_where .= " and nation = '".$nation."' ";
	}

	$field = " * ";
	$table = " tbl_category ";
	$where = " and category = 'board' and bc_id = 'counsel_case' ";
	$orderby = " cate_sort asc ";
	$limit = " ";

	$ArrCateListRs = $dbcon -> getList($field = "*", $table , $where, $orderby, $limit);

	$CateTotalCount = $ArrCateListRs[0];

	if ( $CateTotalCount == 0 ) {
		$dbcon -> dbcon_close();
		alert_back('카테고리 설정이 잘못되었습니다.\n관리자에게 문의하여 주십시오.');
		exit;
	}

	if ( $search_category != "all" ) {
		$query_where .= " and category = '".$search_category."' ";
	}
	$parameter .= "&search_category=".$search_category;


	if ($search == "all") {
		$query_where .= " and ( subject like '%".$search_text."%' or content like '%".$search_text."%') ";
	}
	else {
		if ( (getLen($search_text) > 0) ) {
			$query_where .= " and ".$search." like '%".$search_text."%'";
		}
	}
	
	$query_where .= "and secret = 'N'";

	$parameter .= "&search=".$search."&search_text=".$search_text. "&secretVal=". $secretVal. "&PR_SEQ=". $pr_cd."&nation=".$nation;

	#### parameter 설정$search_category
	#############################

	// 상품 검색
	$list_type = "list";

	#### 전체 페이지수를 계산한다.
	$SQL =  " SELECT 
			COUNT(*)
	FROM tbl_board_counsel_case WHERE 1=1 ";
	
	$total_record = $dbcon -> getCount($SQL.$query_where);

	$total_page = ceil($total_record/$num_per_page);
	$no = $total_record - $first;
	
	$SQL =  " SELECT 
			seq
			,category
			, (SELECT cate_name FROM tbl_category WHERE idx = c.category AND bc_id='counsel_case') as category_name
			,subject
			,content
			,view_cnt
			,notice
			,secret
			,nation
			,counsel_date
			,pr_cd
	FROM tbl_board_counsel_case c WHERE 1=1 ";

	$SQL .= $query_where;
	$SQL .= " ORDER BY seq DESC ";
	$limit = $first . ", " . $last;
	$SQL .= " limit " . $limit;
	
	$result = $dbcon -> query($SQL);
	if(!$result){
		$list_type = null;
	}
?>
<script>
	function searchCase() { 
		ff = document.SearchForm;
		ff.submit();
	}
	function page_go(page) {
		location.href = '?page='+page+'<?=$parameter?>';
	}
</script>
<div class="breadcrumb-image" style="background-image:url('../images/sub-title-1.jpg')">
	<div class="container">
		<h2><?=$PR_INFO["subject"]?></h2>
		<h4><?=$PR_INFO["content"]?></h4>
	</div>
</div>
<div class="breadcrumb-wrap">
    <div class="container">
		<ol class="breadcrumb">
			<li><a href="../main/index.php">InsuPlus HOME</a></li>
			<li><?=$PR_INFO["subject"]?></li>
		</ol>
    </div>
</div>
<div class="container">
	<div class="sub-content info-wrap">
		<!--20230531 문구변경 start-->
			<div class="text-center plan">
				<div class="plan_title half-highlight">플랜 비교하는 방법</div>
				<div class="plan_subtitle">
					<div class="num">1</div>
					<div class="tcopy">의료비 보장 한도를 확인하세요</div>
				</div>
				<div class="plan_subtitle">
					<div class="num">2</div>
					<div class="tcopy">지원되는 여행/의료 서비스를 확인하세요</div>
				</div>
			</div>
			<!--20230531 문구변경 end-->
		<ul class="nav nav-tabs nav-justified">
			<li class=""><a href="pr_plans.php?PR_SEQ=<?=$PR_SEQ?>">상품안내</a></li>
			<li class=""><a href="insuplus_note.php?PR_SEQ=<?=$PR_SEQ?>">유의사항</a></li>
			<li class="active"><a href="javascript:;">고객후기</a></li>
		</ul>
		<div class="row m-y-2">
			<div class="col-md-6 col-sm-8 col-xs-12 col-md-offset-6 col-sm-offset-4 col-xs-offset-0">
				<div class="row">
					<form name="SearchForm" method="get" action="<?=$PHP_SELF?>">
						<input type="hidden" name="search" value="all">
						<input type="hidden" name="mode" value="list">
						<input type="hidden" name="nation" value="<?=$nation?>">
						<input type="hidden" name="PR_SEQ" value="<?=$PR_SEQ?>">
						<div class="col-xs-3 p-r-0">
							<select name="search_category" class='form-control'>
								<option value="all" <? if ($search_category == "all" || $search_category == "") echo "selected"; ?>>구분</option>
								<? while ($CateListRs = $dbcon->fetch_array($ArrCateListRs[1])) {
									extract($CateListRs); ?>
									<option value="<?= $idx ?>" <? if ($search_category == $idx) echo "selected"; ?>><?= $cate_name ?></option>
								<? } ?>
							</select>
						</div>
						<div class="col-xs-3 p-r-0">
							<select class="form-control" name="nation">
							<option value="" <? if ($nation == "" ) echo "selected"; ?>>국가선택</option>
							<? foreach($nation_arr as $key=>$val) {?>
								<option value="<?=$key;?>" <?=$nation==$key ? "selected":"";?>><?=$val;?></option>
							<? } ?>
							</select>
						</div>
						<div class="col-xs-6 p-l-05">
							<div class="input-group">
								<input type="text" name="search_text" class="form-control" placeholder="제목으로 검색해 주세요" value="<?= $search_text?>">
								<span class="input-group-btn"><a class="btn btn-default" onclick="searchCase();"><i class="fa fa-search"></i><span class="hidden-xs">&nbsp;검색</strong></a></span>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<table width="100%" class="table table-break table-list" id="insurplus_table">
			<colgroup>
				<col class="col-sm-2 col-md-2 col-lg-1" />
				<col class="col-xs-1" />
				<col width="*" />
				<col class="col-sm-2 col-md-2 col-lg-1" />
				<col class="col-sm-2 col-md-2 col-lg-1" />
				<col width="30" />
			</colgroup>
			<thead>
				<tr>
					<th>구분</th>
					<th>국가</th>
					<th>제목</th>
					<th>조회수</th>
					<th>등록일</th>
					<th></th>
				</tr>
			</thead>
			<tbody class="text-center accordion" id="accordion">
			<?if ($list_type == "list") {
				while ($ListRs = $dbcon -> fetch_array($result) ) {
					extract($ListRs);
					if($secret != "Y") { ?>
					<tr data-toggle="collapse" data-target="#tr_<?=$seq?>">
							<td class="p-t-05 p-b-0 hidden-xs"><span class="label label-block label-theme-dark light radius"><?=$category_name?></span></td>
							<td class="hidden-xs"><?=$nation_arr[$nation]?></td>
							<td class="text-left flex-100"><span class="hidden visible-xs-inline-block m-r-05"><span class="label label-theme-dark light radius">[<?=print_pr_name($pr_cd)?>]</span>&nbsp;[<?=$nation_arr[$nation]?>]</span><?=$subject?></td>
							<td data-title="조회수" class="td-inline"><?=$view_cnt?></td>
							<td data-title="등록일" class="td-inline"><?=date("Y-m-d ", strtotime($counsel_date))?></td>
							<td class="collapse-icon"></td>
					</tr>
					<tr class="tr-collapse">
						<td class="text-left flex-100 p-a-0" colspan="6">
							<div class="collapse" id="tr_<?=$seq?>">
								<div class="p-y-1">
									[<?=print_pr_name($pr_cd)?>][<?=$nation_arr[$nation]?>]</span><?=$subject?><br><br><?=$content?></a>
								</div>
							</div>
						</td>
					</tr>
				<? }
				}
			}elseif ($list_type == "null") { ?>
				<tr>
					<td colspan="6">등록 된 데이터가 없습니다.</td>
				</tr>
			<? }else{ ?>
			.
			<? } ?>
			</tbody>
		</table>
		<div class="clearfix text-center">
			<ul class="pagination">
				<? list_page_ljh($page, $total_page, $page_per_block); ?>
			</ul>
		</div>
	</div>
</div>

<?php
	include "../_include/_tail.html";
	include "../_include/_footer.html";
?>


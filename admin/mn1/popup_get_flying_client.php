<!DOCTYPE html>
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	$page_btn_prev =">>"; // > 버튼
	$page_btn_next ="<<"; // < 버튼

	// 페이지 설정
	$page				= REQSTR($page, 1);
	$num_per_page	= REQSTR($num_per_page, 30);
	$page_per_block	= REQSTR($page_per_block, 10);
	$first					= $num_per_page*($page-1);
	$last					= $num_per_page*$page;
	#############################
	#### 페이지 설정

	$parameter .= "&num_per_page=".$num_per_page."&page_per_block=".$page_per_block;

	#############################

	// 검색설정
	$query_where		= "";

	#### 검색 설정 Start
	$search_text = REQSTR($search_text, "");

	if ((getLen($search_text) > 0) ) {
		$query_where .= " and fc.knm like '%".$search_text."%'";
	}
	
	$parameter .= "&search_text=".$search_text;
	#### 검색 설정 End

	// 쿼리설정
	$field		     = "fc.client_id, fc.knm, fc.enm, fc.cont_cd, fc.client_gubun, fc.biz_num, fc.prj_cd";
	$table			= "safety_training.fd_client fc";
	$where			= $query_where;
	$limit				= $first.", ".$num_per_page;

	$ArrRS			= $dbcon -> getList($field, $table, $where, "", $limit);
	$total_record	= $ArrRS[0];
	$result			= $ArrRS[1];
	unset($ArrRS);

	// 페이지 & 리스트 설정
	$total_page		= ceil($total_record/$num_per_page);
	$no				= $total_record - $first;
?>
<html>
<head>
<title>InsuPlus</title>
<link href="/_css/admin.css" rel="stylesheet" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
</head>
<body>
	<div class="popupWrap">
		<header>
			<h1>업체 선택</h1>
			<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
		</header>
		<div class="popContWrap">
			<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
				<input type="hidden" name="client_id" value="<?=$bc_id?>">
				<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
				<input type="hidden" name="mode" value="list">
				<table class="adm-searchForm">
					<colgroup>
						<col width="10%" />
						<col width="90%" />
					</colgroup>
					<tr>
						<th>기간</th>
						<td>
							<select name="search_dt">
								<option value="">:: 기간 선택 ::</option>
								<option value="regdate" <? if ($search_dt == "regdate" ) echo "selected"; ?>>등록일</option>
								<option value="s_date" <? if ($search_dt == "s_date" ) echo "selected"; ?>>판매기간</option>
							</select>
							<input type="text" name="search_dt1" maxlength="10" value="<?=$search_dt1?>" class="datepicker ml10 w100">
							~
							<input type="text" name="search_dt2" maxlength="10" value="<?=$search_dt2?>" class="datepicker w100">
						</td>
					</tr>
					<tr>
						<th>업체명(한글)</th>
						<td>
							<input type="text" name="search_text" class="w400" value="<?=$search_text?>" />
							<input type="submit" value="검색">
						</td>
					</tr>
				</table>
			</form>

			<form method="post" name="frmCheckDel" action="<?=$PHP_SELF?>">
				<table class="adm-list-tb">
					<colgroup>
						<col width="5%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
						<col width="7%" />
						<col width="5%" />
					</colgroup>
					<tr>
						<th>No</th>
						<th>업체명</th>
						<th>업체명(영문)</th>
						<th>사업자번호</th>
						<th>프로젝트</th>
						<th>선택</th>
					</tr>
					<? if ($total_record == 0) { ?>
					<tr>
						<td colspan="6">등록된 데이터가 없습니다.</td>
					</tr>
					<?
						} else {
							while ($rows = $dbcon -> fetch_array($result)) {
								extract($rows);
								//unset($rows);
								?>
						<tr>
							<td><?=$no?></td>
							<td><?=$knm?></td>
							<td><?=$enm?></td>
							<td><?=$biz_num?></td>
							<td><?=$grp_cd?></td>
							<td><a href="javascript:;" onClick="g_select(this);" data='<?=json_encode($rows)?>' class="btn-form-normal">선택</a></td>
						</tr>
						<? $no = $no - 1;} ?>
					<? } ?>
				</table>
				<input type=hidden name=f_delete>
			</form>
		
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td width="70" valign="top" style="padding:11px 0 0 0"><?=$btn_list?></td>
					<td align="center" valign="top" style="padding:10px 0 0 0">
						<? list_page($page, $total_page, $page_per_block); ?></td>
					</td>
					<td width="70" align="right" valign="top" style="padding:11px 0 0 0">
					</td>
				</tr>
			</table>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
	function g_select(d){
		var data = $.parseJSON($(d).attr("data"));
		var ff = opener.document.frm_group_join;
		ff.o_name_b2b.value = data.knm;
		ff.o_name_en_b2b.value = data.enm;
		ff.client_id.value = data.client_id;
		ff.biz_num.value = data.biz_num;
		ff.grp_cd.value = data.grp_cd != '' ||  data.grp_cd != "undefined" ? data.grp_cd : '';
		
		self.close();
	}
</script>

<? $dbcon -> dbcon_close();?>

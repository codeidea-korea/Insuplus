<!DOCTYPE html>
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	
	$bc_id = "charge";
	
	#### 게시판 설정 가져오기
	$field = " * ";
	$table = "config_board_list";
	$where = " and bc_id = '".$bc_id."' ";
	$orderby = " bc_id asc ";
	$limit = " 0, 1 ";
	$ArrListRs = $dbcon -> getList($field = "*", $table , $where, $orderby, $limit);
	if ( $ArrListRs[0] == 0 ) {
		$dbcon -> dbcon_close();
		alert_back("잘못된 게시판 정보입니다.1");
		exit;
	}
	$ListRs = $dbcon -> fetch_array($ArrListRs[1]);
	extract($ListRs);
	unset($ListRs);
	unset($ArrListRs);
	
	// 설정 추가
	include_once $path_skin_board.$bc_skin."/config.php";
	
	//데이터 추출
	$field = " u_idx, u_name, u_hp1, u_hp2, u_hp3 ";
	$field .= "  ";
	
	
	$table = "tbl_user";
	$where = " ";
	if($u_name) $where .= " AND u_name = '".$u_name."' ";		
	
	$orderby = " u_idx DESC ";
	$limit = $first.", ".$last;
		
	$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);	
	$total_record = $ArrListRs[0];

	#### 전체 페이지수를 계산한다.
	$total_page = ceil($total_record/$num_per_page);
	$no = $total_record - $first;
?>
<html>
<head>
<title>InsuPlus</title>
<link href="/_css/admin.css" rel="stylesheet" />
</head>
<body>
<div class="popupWrap">
	<header>
		<h1>가입자 찾기</h1>
		<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
	</header>
	<div class="popContWrap">
		<!-- (s) 검색영역 -->
		<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
		<input type="hidden" name="bc_id" value="<?=$bc_id?>">
		<input type="hidden" name="mode" value="list">
			<table class="adm-searchForm">
			<colgroup>
				<col width="12%" />
				<col width="88%" />
			</colgroup>
			<tr>
				<th>이름</th>
				<td><input type="text" name="u_name" value="<?=$u_name?>" /></td>
			</tr>
			<tr>
				<th>연락처</th>
				<td><input type="text" name="mobile" value="<?=$mobile?>" /> <input type="submit" value="검색" /></td>
			</tr>
			</table>			
		</form>
		<!-- (e) 검색영역 -->
		
		<!-- (s) 리스트 영역 -->
		<table class="adm-list-tb">
			<colgroup>
				<col width="15%" />
				<col width="15%" />
				<col width="15%" />
				<col width="12%" />
				<col width="*" />
				<col width="10%" />
				<col width="10%" />
			</colgroup>
			<tr>
				<th>상품명</th>
				<th>보험사</th>
				<th>플랜명</th>
				<th>가입자</th>
				<th>연락처</th>
				<th>결제상태</th>
				<th>결제금액</th>
			</tr>
			<?
			if ( $total_record > 0 ) {
			$temp_num = 1;
			$temp_num_img=0;
				while($ListRs = $dbcon -> fetch_array($ArrListRs[1])) { 
					extract($ListRs);
					//unset($ListRs);
	
				?>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td><a href="javascript:;" onClick="opener.setData(this)" data='<?=json_encode($ListRs)?>'><?=$u_name?></a></td>
					<td><?=$u_hp1?><?=$u_hp2?><?=$u_hp3?></td>
					<td></td>
					<td></td>
				</tr>
				<? } ?>
			<? } else { ?>
			<tr>
				<td colspan="7">등록 된 데이터가 없습니다.</td>
			</tr>
			<? } 
			unset($ArrListRs);
			?>
		</table>
		<!-- (e) 리스트 영역-->
		
		<!-- ### 페이지 시작 ###  -->
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td align="center" valign="top" style="padding:10px 0 0 0">
					<? list_page($page, $total_page, $page_per_block); ?></td>
				</td>
			</tr>
		</table>
		<!-- ### 페이지 끝 ###  -->		
	</div>
</div>
</body>
</html>


<? $dbcon -> dbcon_close();?>

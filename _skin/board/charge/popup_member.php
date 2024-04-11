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
		alert_back("잘못된 게시판 정보입니다.");
		exit;
	}
	$ListRs = $dbcon -> fetch_array($ArrListRs[1]);
	extract($ListRs);
	unset($ListRs);
	unset($ArrListRs);
	
	// 설정 추가
	include_once $path_skin_board.$bc_skin."/config.php";
	
	//데이터 추출
	$field = " j.seq, j.orderno, j.o_name, j.o_phone, j.o_isdn1, j.o_isdn2 ,j.join_status, j.gender   ";
	$field .= " ,o.pr_name, o.ins_name, o.plan_name, o.t_amount, o.s_date, o.e_date, o.join_nation_cd ";
	$field .= " , c.c_name  ";
	$field .= " , p.stock_isdn  ";
	$table  = " tbl_order_list_join j INNER JOIN tbl_order_list o ";
	$table .= " ON j.orderno = o.orderno ";
	$table .= " LEFT JOIN  tbl_board_product_country c ON  o.pr_cd = c.pr_seq  AND o.join_nation_cd = c.c_code ";
	$table .= " LEFT JOIN  tbl_board_plan p ON o.plan_cd = p.seq    ";
	$where = " AND j.join_status = 'Y' AND o.order_step = '2' ";
	if($u_name) $where .= " AND j.o_name = '".all_seed_enc($u_name)."' ";
	if($mobile) $where .= " AND j.o_phone = '".all_seed_enc($mobile)."' ";
	
	$orderby = " j.regdate DESC ";
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
				<col width="12%" />
				<col width="10%" />
			</colgroup>
			<tr>
				<th>상품명</th>
				<th>보험사</th>
				<th>플랜명</th>
				<th>가입자</th>
				<th>연락처</th>
				<th>가입상태</th>
				<th>결제금액</th>
			</tr>
			<?
			if ( $total_record > 0 ) {
			$temp_num = 1;
			$temp_num_img=0;
				while($ListRs = $dbcon -> fetch_array($ArrListRs[1])) {
					$ListRs["o_name"] = all_seed_dec($ListRs["o_name"]);
					$ListRs["o_phone"] = all_seed_dec($ListRs["o_phone"]);
					$ListRs["o_isdn1"] = all_seed_dec($ListRs["o_isdn1"]);
					$ListRs["o_isdn2"] = all_seed_dec($ListRs["o_isdn2"]);
					$ListRs["birth_date"] = substr($ListRs["o_isdn1"],2,6);
					$ListRs["gender_name"] = $ListRs["gender"] == "M" ? "남":"여";
					
					extract($ListRs);
					//unset($ListRs);
	
				?>
				<tr>
					<td><?=$pr_name?></td>
					<td><?=$ins_name?></td>
					<td><?=$plan_name?></td>
					<td><a href="javascript:;" onClick="opener.setData(this)" data='<?=json_encode($ListRs)?>'><?=$o_name?></a></td>
					<td><?=$o_phone?></td>
					<td><?=$arr_join_step[$join_status];?></td>
					<td class="r"><?=number_format($t_amount);?></td>
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

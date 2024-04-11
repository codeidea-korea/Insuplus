<!DOCTYPE html>
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php"; //추가
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
	$PR_INFO = getInsuProductInfo($seq); //상품정보
	$chk_p = $PR_INFO["ext1"];
	//상품에 대한 보장내역 검색
	$arr_guarantee = selGuaranteeList($PR_INFO["ext4"]);
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
			<h1>보장내역</h1>
			<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
		</header>
		<div class="popContWrap">
			<form method="post" name="frmCheckDel" action="<?=$PHP_SELF?>">
				<table class="adm-list-tb">
					<colgroup>
						<col width="50%" />
						<col width="25%" />
						<col width="25%" />
					</colgroup>
					<tr>
						<th>담보명</th>
						<th>보장금액(국)</th>
						<th>보장금액(영)</th>
					</tr>
					<? if (!$arr_guarantee) { ?>
					<tr>
						<td colspan="9">등록된 데이터가 없습니다.</td>
					</tr>
					<?
						} else {
							foreach($arr_guarantee as $row_guarantee){ 
								$SQL_G = "SELECT g_amount, g_amount_certificate FROM tbl_board_plan_guarantee WHERE plan_cd = '$plan_seq' and g_seq='".$row_guarantee["idx"]."' ";
								$RS_G = $dbcon -> query($SQL_G);
								$guarant_row = array();
								$guarant_row[]= $dbcon -> fetch_array($RS_G);
							?>
								<tr>
									<td><?=$row_guarantee["service_name"]?></td>
									<td colspan="<?=$colspan?>"><?=$guarant_row[0]["g_amount"]?></td>
									<td colspan="<?=$colspan?>"><?=$guarant_row[0]["g_amount_certificate"]?></td>
								</tr>
							<? }
						} ?>
				</table>
			</form>
		</div>
	</div>
</body>
</html>
<script type="text/javascript">
	function g_select(d){
		var data = $.parseJSON($(d).attr("data"));
		var prName = $(d).attr("data_pr");
		var insName = $(d).attr("data_ins");
		var ff = opener.document.frm_group_join;
		ff.plan_cd.value = data.seq;
		ff.pr_cd.value = data.pr_cd;
		ff.service_cd.value = data.service_cd;
		ff.stock_isdn.value = data.stock_isdn;
		ff.ins_cd.value = data.ins_cd;
		ff.chk_service.value = data.chk_service;
		ff.chk_period.value = data.chk_period;
		ff.agree_cd.value = data.agree_cd;
		ff.ins_plan_name.value = data.ins_plan_name;
		ff.pr_name.value = prName;
		ff.ins_name.value = insName;
		
		self.close();
	}
</script>

<? $dbcon -> dbcon_close();?>

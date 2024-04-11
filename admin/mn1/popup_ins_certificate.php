<!DOCTYPE html>
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	//가입자 정보 검색
	$SQL_L = "select * from tbl_order_list_join where seq='".$seq."'";
//	echo $SQL_L;
	$RS_L = $dbcon -> query($SQL_L);
	if (!$RS_L){
		echo "<script>alert('해당 보험내역 가입자 정보가 없습니다.');</script>";
		exit;
	}
	$row_L = $dbcon -> fetch_array($RS_L);

	// 가입보험내역 검색
	$SQL_V = "select * from tbl_order_list where orderno = '".$row_L["orderno"]."' ";
	//echo $SQL_V;
	$RS_V = $dbcon -> query($SQL_V);
	if (!$RS_V){
		echo "<script>alert('해당 보험내역이 없습니다.');</script>";
		exit;
	}
	$row_r = $dbcon -> fetch_array($RS_V);
?>
<html>
<head>
<title>InsuPlus</title>
<link href="/_css/admin.css" rel="stylesheet" />
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
</head>
<body>
<div class="popupWrap">
	<header>
		<h1>가입증명원 재발송</h1>
		<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
	</header>
	<div class="popContWrap">
<form name="frm_cert" id="frm_cert" method="post">
<input type="hidden" name="join_seq" value="<?=$seq?>">
<input type="hidden" name="orderno" value="<?=$row_L["orderno"]?>">
<input type="hidden" name="mode" value="view" />
		<!-- (s) 상세영역 -->
		<table class="adm-view-tb">
			<colgroup>
			<col width="16%">
			<col width="34%">
			<col width="16%">
			<col width="34%">
			</colgroup>
			<tr>
				<th>가입증명원</th>
				<td><label for="korea">국문</label><input type="radio" name="chk_lang" id="korea" value="K" checked/>
					<label for="english">영문</label><input type="radio" name="chk_lang" id="english" value="E" />
				</td>
				<th>결제상태</th>
				<td><?=$arr_ord_step[$row_r["order_step"]]?></td>
			</tr>
			<tr>
				<th>이름</th>
				<td><?=all_seed_dec($row_L["o_name"])?>
				<input type="hidden" name="o_name" value="<?=all_seed_dec($row_L["o_name"])?>"/></td>
				<th>영문</th>
				<td>
					<? if($row_L["chk_eng_passport"] == "Y") {?>
					<?=all_seed_dec($row_L["o_name_en"])?>
					<input type="hidden" name="o_name_en" value="<?=all_seed_dec($row_L["o_name_en"])?>"/>
					<? } else { ?>
					<input type="text" name="o_name_en" value=""/>
					<? } ?>
				</td>
			</tr>
			<tr>
				<th>휴대폰번호</th>
				<td colspan="3">
					<?=all_seed_dec($row_L["o_phone"])?>
					<input type="hidden" name="mobile" class="onlyNumber" maxlength="11" value="<?=all_seed_dec($row_L["o_phone"])?>"/>
				</td>
			</tr>
			<tr>
				<th>이메일</th>
				<td colspan="3">
					<?=all_seed_dec($row_r["o_email1"])?>@<?=all_seed_dec($row_r["o_email2"])?>
					<input type="hidden" name="email1" value="<?=all_seed_dec($row_r["o_email1"])?>"/>
					<input type="hidden" name="email2" value="<?=all_seed_dec($row_r["o_email2"])?>"/>
				</td>
			</tr>
		</table>
		<p class="txt_red">※ 영문 가입 증명원인 경우 영문명을 입력해주세요.</p>

		<div class="btnWrap">
			<div class="leftWrap"><a href="javascript:chk_certform();" class="btn_normal">가입증명원 확인</a></div><!-- 퍼블리셔한테 화면 받아야 할거 같습니다. -->
			</div>
		</div>
		<!-- (e) 상세영역 -->
</form>

	</div>
</div>
</body>
</html>
<script type="text/javascript">
<!--

function chk_certform(){
	// var pop_certificate = window.open('about:blank','certificate','width=760,height=500');
	var ff = document.frm_cert;
	ff.action = "popup_certificate_pdf.php";
	ff.target ="certificate";
	ff.target="_blank";
	ff.submit();
	
}
//-->
</script>

<? $dbcon -> dbcon_close();?>

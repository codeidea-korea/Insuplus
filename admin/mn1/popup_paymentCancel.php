<!DOCTYPE html>
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크


	// 주문 가져오기
	if ($orderno){
		$SQL = "select * from tbl_order_list where orderno='".$orderno."' ";
		//echo $SQL."<br>";
		$result = $dbcon -> query($SQL);
		$row= $dbcon -> fetch_array($result);
		$cancle_amt	= $row["t_amount"];
		$o_name		= all_seed_dec($row["o_name"]);

		// 가입자 가져오기
		if ($seq){
			$SQL = "select * from tbl_order_list_join where orderno ='".$orderno."' and seq='".$seq."' ";
			//echo $SQL."<br>";
			$RS = $dbcon -> query($SQL);
			$row_L= $dbcon -> fetch_array($RS);
			$cancle_amt = $row_L["t_amount"];
			$o_name = all_seed_dec($row_L["o_name"]);
		}


	}
?>
<html>
<head>
<title>InsuPlus</title>
<link href="/_css/admin.css" rel="stylesheet" />
</head>
<body>
<div class="popupWrap">
	<header>
		<h1>결제취소</h1>
		<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
	</header>
	<div class="popContWrap">

		<!-- (s) 상세영역 -->
		<p class="txt_red">결제취소 하시겠습니까?</p>

		<table class="adm-view-tb">
			<colgroup>
			<col width="20%">
			<col width="80%">
			</colgroup>
			<tr>
				<th>가입자</th>
				<td><?=$o_name?></td>
			</tr>
			<tr>
				<th>결제수단</th>
				<td><?=$row["pg_pay_type"]?></td>
			</tr>
			<tr>
				<th>취소금액</th>
				<td class="r emphasis"><?=number_format($cancle_amt)?>원</td>
			</tr>
		</table>


		<div class="btnWrapC">
			<? if($seq) {?>
			<a href="javascript:ord_cancle();" class="btn_add">부분취소</a>
			<? } else {?>
			<a href="javascript:ord_cancle();" class="btn_add">결제취소</a>
			<? } ?>
		</div>
		<!-- (e) 상세영역 -->


	</div>
</div>
</body>
</html>

<form name="frm_cancle">
<input type="hidden" name="mode" value="">
<input type="hidden" name="orderno" value="<?=$row["orderno"]?>">
<input type="hidden" name="join_seq" value="<?=$row_L["seq"]?>">
</form>
<script type="text/javascript">

function ord_cancle(ordno){
	var ff = document.frm_cancle;
	if(confirm("주문 취소 하시겠습니까?")){
		console.log("ordno", '<?=$row["orderno"]?>');
		ordno = '<?=$row["orderno"]?>';
		if(ordno.startsWith("TOSS")) {
			<?if ($seq){?>
			ff.mode.value = "repay";
			ff.action = "toss_pay_view_card_cancle_part.php";
			<?}else{?>
			ff.mode.value = "cancel";
			ff.action = "toss_pay_view_card_cancle_all.php";
			<?}?>
	
		}else{

			<?if ($seq){?>
			ff.mode.value = "repay";
			ff.action = "pay_view_card_cancle_part.php";
			<?}else{?>
			ff.mode.value = "cancel";
			ff.action = "pay_view_card_cancle_all.php";
			<?}?>
		
		}
		ff.submit();
	}
}
</script>
<? $dbcon -> dbcon_close();?>

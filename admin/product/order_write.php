<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$SQL = "
		select * from tbl_account
		where
			1=1
			and a_idx = '".$_GET["a_idx"]."'
	";
//			echo $SQL."<BR>";
//			exit;
	$rs1 = $dbcon -> query($SQL);
	$rs1_cnt = $dbcon -> num_rows($rs1);
	$row1 = $dbcon -> fetch_array($rs1);
	$SQL = "
		select * from tbl_account_product
		where
			1=1
			and ordernum = '".$row1["ordernum"]."'
	";
	$rs2 = $dbcon -> query($SQL);

	$rs2_cnt = $dbcon -> num_rows($rs1);
//			echo "rs1_cnt : ".$rs1_cnt."<BR>";
//			echo "rs2_cnt : ".$rs2_cnt."<BR>";

	if ( $rs1_cnt == 0 || $rs2_cnt == 0 ) {
		//echo "세션 만료";
		alert_back("일치되는 정보가 없습니다.");
		exit;
	}


	$parameter = "search_YearS=".$search_YearS."&search_MonthS=".$search_MonthS."&search_DayS=".$search_DayS."&search_YearE=".$search_YearE."&search_MonthE=".$search_MonthE."&search_DayE=".$search_DayE."&search_buyer_name=".$search_buyer_name."&search_ordernum=".$search_ordernum."&search_u_id=".$search_u_id."&search_buyer_email=".$search_buyer_email."&search_receive_name=".$search_receive_name."&search_accout_name=".$search_accout_name."&search_a_state=".$search_a_state;

	// 제품 수정시
	if ( getLen($a_idx) > 0 ) {
		$SQL = "
			select *
			from
				tbl_account
			where
				a_idx = '".$a_idx."'
			limit 0, 1
		";
		$row = $dbcon -> fetch_array($dbcon -> query($SQL));
		extract($row);
		unset($row);
	}

	if ( !$a_idx && $pc_num ) {
		$SQL = "
			select *
			from
				tbl_account_product
			where
				pc_num = '".$pc_num."'
			order by
				pc_sort desc
			limit 0, 1
		";
		$pc_option = $dbcon -> getCount($SQL);
		$pr_option = $pc_option;
	}

	$search_pc_num = $pc_num;
?>
<?
	$tm = "order";
	$lm = "";
	include $path_admin."inc/header.php";
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">  주문관리</td>
	</tr>
	<tr>
		<td colspan="3" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="3" height="19"></td>
	</tr>
</table>

<form name="OrderForm" method="post" action="<?=$url_admin?>product/order_state_ok.php" ENCTYPE='multipart/form-data'>
<input type="hidden" name="a_idx[]" value="<?=$a_idx?>">
<input type="hidden" name="mode" value="view">




<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 구입내역</td>
	</tr>
	<tr>
		<td height="2"></td>
	</tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td colspan="10" class="a_line_2px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt2" align="center" width="200" >사진</td>
		<td class="a_txt2" align="center" width="*">제품명</td>
		<td class="a_txt2" align="center" width="100">판매금액</td>
		<td class="a_txt2" align="center" width="100">수량</td>
		<td class="a_txt2" align="center" width="100">합계</td>
	</tr>
	<tr>
		<td colspan="10" class="a_line_1px">&nbsp;</td>
	</tr>

	<? while ($row2 = $dbcon->fetch_array($rs2) ) { ?>
	<tr>
		<td class="a_content_txt3"><img src="<?=$row2["pr_img"]?>" style="margin:10px 0 10px 0"></td>
		<td class="a_content_txt3"><?=$row2["pr_name"]?><BR><?=$row2["print_name"]?></td>
		<td class="a_content_txt3"><?=make_price_format($row2["pr_name"],1)?> 원</td>
		<td class="a_content_txt3"><?=$row2["goods_num"]?></td>
		<td class="a_content_txt3"><?=make_price_format($row2["show_price"],1)?> 원</td>
	</tr>
	<? } ?>
	<tr>
		<td colspan="10" class="a_line_2px">&nbsp;</td>
	</tr>

</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td height="30" align="right">
			배송료  :  <?=make_price_format($delivery_price,1)?> 원    +   구입총액  :  <?=make_price_format($total_price,1)?> 원   =   <font color="#ff0000"><b>총 결제금액 : <?=make_price_format($buy_price,1)?> 원</b></font>
		</td>
	</tr>
</table>


<BR><BR>



<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 주문자 정보내역 [주문번호 : <?=$row1["ordernum"]?>] <? if ($u_id) {?>[구입아이디 : <?=$u_id?>]<?} ?></td>
	</tr>
	<tr>
		<td height="2"></td>
	</tr>
</table>


<table width="100%" cellpadding="0" cellspacing="0" border="0" >
	<tr>
		<td colspan="2" class="a_line_2px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">주문자</td>
		<td class="a_content">
			<?=$row1["buyer_name"]?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">연락처</td>
		<td class="a_content">
			<?=$row1["buyer_tel"]?> / <?=$row1["buyer_hp"]?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">우편번호</td>
		<td class="a_content">
			<?=$row1["buyer_post"]?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">자택주소</td>
		<td class="a_content">
			<?=$row1["buyer_addr1"]?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">상세주소</td>
		<td class="a_content">
			<?=$row1["buyer_addr2"]?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">E-mail</td>
		<td class="a_content">
			<?=$row1["buyer_email"]?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">기타 요구사항</td>
		<td class="a_content">
			<?=$row1["buyer_content"]?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_2px">&nbsp;</td>
	</tr>
</table>



<BR>


<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 배달 정보내역</td>
	</tr>
	<tr>
		<td height="2"></td>
	</tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" border="0" >
	<tr>
		<td colspan="2" class="a_line_2px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">성명</td>
		<td class="a_content">
			<?=$row1["receive_name"]?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">연락처</td>
		<td class="a_content">
			<?=$row1["receive_tel"]?> / <?=$row1["receive_hp"]?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">우편번호</td>
		<td class="a_content">
			<?=$row1["receive_post"]?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">자택주소</td>
		<td class="a_content">
			<?=$row1["receive_addr1"]?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">상세주소</td>
		<td class="a_content">
			<?=$row1["receive_addr2"]?>
		</td>
	</tr>

	<tr>
		<td colspan="2" class="a_line_2px">&nbsp;</td>
	</tr>
</table>


<BR>



<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 결제 정보</td>
	</tr>
	<tr>
		<td height="2"></td>
	</tr>
</table>
<table width="100%" cellpadding="0" cellspacing="0" border="0" >
	<tr>
		<td colspan="2" class="a_line_2px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">주문상태</td>
		<td class="a_content">
			<select name="a_state[]">
				<?
					foreach( $Arr_a_state as $key => $val) {
						if ( $key ) {
				?>
				<option value="<?=$key?>" <? if ($key == $a_state) {echo "selected";} ?>><?=$val?></option>
				<?
						}
					}
				?>
			</select>

		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">주문날짜</td>
		<td class="a_content">
			<?=date("Y-m-d [H:i:s]", strtotime($row1["a_regdate"]) )?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">결제방법</td>
		<td class="a_content">
			<?
				$print_account_type = $Arr_account_type[$account_type];
				echo $print_account_type;
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<? if ( $row1["account_type"] == "1" ) { ?>
	<tr>
		<td class="a_txt">입금인명/입금가능일</td>
		<td class="a_content">
			<?=$row1["accout_name"]?> /
			<?
				//echo "accout_date : ".$row1["accout_date"]."<BR>";
				if ( $row1["accout_date"] ) {
					$print_accout_date = date("Y-m-d", $row1["accout_date"]);
					echo $print_accout_date;
				}
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">입금선택 계좌</td>
		<td class="a_content">
			<?=$row1["accout_bank"]?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<? } ?>
	<tr>
		<td class="a_txt">총 결제하실 금액</td>
		<td class="a_content">
			<font color="ff0000"><b><?=make_price_format($row1["buy_price"],1)?> 원</b></font>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
</table>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="80" align="center">
			<!-- <a href="javascript:history.back();"><img src="<?=$url_admin?>images/a_btn_submit.gif" hspace="4"></a> -->
			<input type="image" name="btnSubmit" id="btnSubmit" src="<?=$url_admin?>images/a_btn_submit.gif" hspace="4">
			<img src="<?=$url_admin?>images/a_btn_cancle.gif" width="76" height="28" onClick="javascript:document.location.href='<?=$url_admin?>product/order_list.php?<?=$parameter?>'" style="cursor:hand">
		</td>
	</tr>
</table>
</form>
<script>
	function ProductDownGo(idx) {
//		PFrame = document.all["ProductFrame"];
//		alert(PFrame.src);
		ProductFrame.location.href = "<?=$url_product?>product_download.php?idx="+idx;
	}
</script>
<iframe src="a" frameborder="0" width="0" height="0" id="ProductFrame"></iframe>
<script>
	function setFileDisabled(obj, objfile, num) {
		objfile = document.all[objfile];

		if (obj.checked == true) {
			objfile[num].disabled = false;
		}
		else {
			objfile[num].disabled = true;
		}
	}
</script>

<? include $path_admin."inc/footer.php"; ?>

<? $dbcon -> dbcon_close();?>

<?php
/*---------------------------------------------------------------------------------*/
	/*  ● 작업자 : 전한솔 팀장
	/*  ● 작성일자 : 2009.04.15
	/*  ● 설명 : 페이지 인식자 네이밍 규칙(플래쉬 페이지 인식 연동을 위한 네이밍 선언)
	/*           각 메뉴별 네이밍 규칙에따라 menuName을 설정해주고 해당 페이지 뎁스 및
	/*           로그인 여부에따라 subVal, thVal, fhVal, login를 반듯히 설정해 줄것!!
	/*
	/*  ● 회사소개 => COMPANY
	/*  ● 제품소개 => PRODUCT
	/*  ● 고객지원 => CUSTOMER
	/*  ● 자료실   => DATE
/*---------------------------------------------------------------------------------*/

include_once $_SERVER["DOCUMENT_ROOT"]."/inc/php/header.php";

$menuName = "REPORT";   //=> 1Dpeth 네이밍
$subVal = 1;             //=> 2Dpeth 넘버링
$thVal = 1;              //=> 3Dpeth 넘버링
$fhVal = 1;              //=> 4Dpeth 넘버링
getHeader();

?>
<script>

	// 우편번호 검색창 띄워주는 스크립트
	function OpenZipcode(url, obj_form, obj_post, obj_addr1, obj_addr2 ) {
		zipwin = window.open(url+"zipcode.php?obj_form="+obj_form+"&obj_post="+obj_post+"&obj_addr1="+obj_addr1+"&obj_addr2="+obj_addr2,"zipwin","width=480,height=320,left=280,top=340,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no");
		zipwin.focus();
	}

	// 받는분이 같은경우 체크박스
	function getReceiveInfo() {
		ff = document.BuyerWriteForm;
		Obj = ff.chkReceive;
		if (Obj.checked == true) {
			ff.receive_name.value = ff.buyer_name.value;

			ff.receive_tel1[ff.buyer_tel1.selectedIndex].selected = true;
			ff.receive_tel2.value = ff.buyer_tel2.value;
			ff.receive_tel3.value = ff.buyer_tel3.value;

			ff.receive_hp1[ff.buyer_hp1.selectedIndex].selected = true;
			ff.receive_hp2.value = ff.buyer_hp2.value;
			ff.receive_hp3.value = ff.buyer_hp3.value;

			ff.receive_post.value = ff.buyer_post.value;
			ff.receive_addr1.value = ff.buyer_addr1.value;
			ff.receive_addr2.value = ff.buyer_addr2.value;
		}
	}

	function checkFrom() {
		ff = document.BuyerWriteForm;

		// 주문정보
		if (!ff.buyer_name.value) {
			alert("주문하시는 분의 성명(실명)을 입력하여 주십시오.");
			ff.buyer_name.focus();
			return false;
		}

		if (!ff.buyer_tel2.value) {
			alert("주문하시는 분의 전화번호를 입력하여 주십시오.");
			ff.buyer_tel2.focus();
			return false;
		}
		if (!ff.buyer_tel3.value) {
			alert("주문하시는 분의 전화번호를 입력하여 주십시오.");
			ff.buyer_tel3.focus();
			return false;
		}

		if (!ff.buyer_hp2.value) {
			alert("주문하시는 분의 휴대전화번호를 입력하여 주십시오.");
			ff.buyer_hp2.focus();
			return false;
		}
		if (!ff.buyer_hp3.value) {
			alert("주문하시는 분의 휴대전화번호를 입력하여 주십시오.");
			ff.buyer_hp3.focus();
			return false;
		}


		if (!ff.buyer_post.value) {
			alert("주문하시는 분의 우편번호를 입력하여주십시오.");
			ff.buyer_post.focus();
			return false;
		}
		if (!ff.buyer_addr1.value) {
			alert("주문하시는 분의 자택주소를 입력하여주십시오.");
			ff.buyer_addr1.focus();
			return false;
		}
		if (!ff.buyer_addr2.value) {
			alert("주문하시는 분의 상세주소를 입력하여주십시오.");
			ff.buyer_addr2.focus();
			return false;
		}


		if (!ff.buyer_email1.value) {
			alert("주문하시는 분의 E-mail 주소를 입력하여주십시오.");
			ff.buyer_email1.focus();
			return false;
		}


		// 배송정보
		if (!ff.receive_name.value) {
			alert("물품을 받으시는 분의 성명을 입력하여 주십시오.");
			ff.receive_name.focus();
			return false;
		}

		if (!ff.receive_tel2.value) {
			alert("물품을 받으시는 분의 전화번호를 입력하여 주십시오.");
			ff.receive_tel2.focus();
			return false;
		}
		if (!ff.receive_tel3.value) {
			alert("물품을 받으시는 분의 전화번호를 입력하여 주십시오.");
			ff.receive_tel3.focus();
			return false;
		}

		if (!ff.receive_hp2.value) {
			alert("물품을 받으시는 분의 휴대전화번호를 입력하여 주십시오.");
			ff.receive_hp2.focus();
			return false;
		}
		if (!ff.receive_hp3.value) {
			alert("물품을 받으시는 분의 휴대전화번호를 입력하여 주십시오.");
			ff.receive_hp3.focus();
			return false;
		}


		if (!ff.receive_post.value) {
			alert("물품을 받으시는 분의 우편번호를 입력하여주십시오.");
			ff.receive_post.focus();
			return false;
		}
		if (!ff.receive_addr1.value) {
			alert("물품을 받으시는 분의 자택주소를 입력하여주십시오.");
			ff.receive_addr1.focus();
			return false;
		}
		if (!ff.receive_addr2.value) {
			alert("물품을 받으시는 분의 상세주소를 입력하여주십시오.");
			ff.receive_addr2.focus();
			return false;
		}

		// 결제정보

		// 온라인 입금
		if ( ff.account_type.length > 0 ) {
			if (ff.account_type[0].checked == true) {
				if (!ff.accout_name.value) {
					alert("[입금자명] 은 필수 항목 입니다. 입력해 주십시오.");
					ff.accout_name.focus();
					return false;
				}
			}
		} else {
			if (ff.account_type.value == "1") {
				if (!ff.accout_name.value) {
					alert("[입금자명] 은 필수 항목 입니다. 입력해 주십시오.");
					ff.accout_name.focus();
					return false;
				}
			}
		}

	}


</script>

<div id="cont">

	<div id="title">
		<div id="subject">
			<!-- Title -->
			<script>flashView("/inc/swf/sub_title.swf?pro_tit=주문정보 입력",352,30);</script>
			<!-- End -->
		</div>
		<div id="sitemap"><!-- SiteMap --><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td>HOME > 구매정보 > <span class="point_txt01">주문정보 입력</span></td><td width="1"><?php	include $_SERVER["DOCUMENT_ROOT"]."/inc/php/quick.php";?></td></tr></table><!-- End --></div>
	</div>


	<div id="con">


		<form name="BuyerWriteForm" action="cart.php" method="post" onsubmit="return checkFrom();">
		<input type="hidden" name="mode" value="buy">
		<input type="hidden" name="step" value="2">

		<?
			// 상품 리스트(결제 금액 및 배송료가 이곳에서 측정되므로 반드시 들어가야 합니다. )
			include_once "goods_list.php";
		?>


		<img src="/images/99_common/99_report01_txt01.gif">


		<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:2px 0 1px 0;">
			<tr>
				<td height="2" bgcolor="#8fa6c6"></td>
			</tr>
		</table>
		<table width="721" cellpadding="0" cellspacing="0" border="0">
			<col width="132" /><col width="200" /><col width="100" /><col width="200" />
			<tr>
				<td class="board_tit_style1_2">*성명(실명)</td>
				<td class="board_list_style1" colspan="3"><input type="text" class="input1" size="40%" name="buyer_name" value="<?=$buyer_name?>" maxlength="20" /></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*전화번호</td>
				<td class="board_list_style1" >
					<? getTel("buyer_tel", $buyer_tel1, $buyer_tel2, $buyer_tel3, 'input1' ); ?>
				</td>
				<td class="board_tit_style1_2">*휴대전화</td>
				<td class="board_list_style1" >
					<? getHP("buyer_hp", $buyer_hp1, $buyer_hp2, $buyer_hp3, 'input1' ); ?>
				</td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*우편번호</td>
				<td class="board_list_style1" colspan="3">
					<input name="buyer_post" value="<?=$buyer_post?>" type="text" class="input1" maxlength="7" style="width:120px" readonly onclick="javascript:OpenZipcode('<?=$url_member?>', 'BuyerWriteForm', 'buyer_post', 'buyer_addr1', 'buyer_addr2');" style="cursor:hand;text-align:center;">
					<img src="<?=$sc_url_skin_member?>images/m_btn02.gif" width="67" height="19" border="0" readonly onclick="javascript: OpenZipcode('<?=$url_member?>', 'BuyerWriteForm', 'buyer_post', 'buyer_addr1', 'buyer_addr2');" style="cursor:hand;" align="absmiddle">
				</td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*자택주소</td>
				<td class="board_list_style1"  colspan="3"><input name="buyer_addr1" value="<?=$buyer_addr1?>" type="text" class="input1" style="width:362px" maxlength="100"></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*상세주소</td>
				<td class="board_list_style1" colspan="3"><input name="buyer_addr2" value="<?=$buyer_addr2?>" type="text" class="input1" style="width:362px" maxlength="100"></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*E-mail</td>
				<td class="board_list_style1" colspan="3">
					<? getEmailForm("buyer_email1", "buyer_email2", $buyer_email1, $buyer_email2, ""); ?>
					<div>* 주문번호가 이메일로 발송되오니 정확하게 입력하여 주시기 바랍니다.</div>
				</td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">기타 하실말씀</td>
				<td class="board_list_style1" colspan="3"><textarea class="textarea1" style="height:120px;" name="buyer_content"><?=$buyer_content?></textarea></td>
			</tr>
			<tr>
				<td class="board_line_style1"></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
		</table>
		<br/>
		<br/>

		<!-- END -->


		<!-- 배송정보-->
		<img src="/images/99_common/99_report01_txt02.gif"><input type="checkbox" name="chkReceive" onclick="getReceiveInfo()" checked>

		<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:2px 0 1px 0;">
			<tr>
				<td height="2" bgcolor="#8fa6c6"></td>
			</tr>
		</table>
		<table width="721" cellpadding="0" cellspacing="0" border="0">
			<col width="132" /><col width="200" /><col width="100" /><col width="200" />
			<tr>
				<td class="board_tit_style1_2">*성명</td>
				<td class="board_list_style1" colspan="3"><input type="text" class="input1" size="40%" name="receive_name" value="<?=$receive_name?>" maxlength="20" /></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*전화번호</td>
				<td class="board_list_style1" >
					<? getTel("receive_tel", $receive_tel1, $receive_tel2, $receive_tel3, 'input1' ); ?>
				</td>
				<td class="board_tit_style1_2">*휴대전화</td>
				<td class="board_list_style1" >
					<? getHP("receive_hp", $receive_hp1, $receive_hp2, $receive_hp3, 'input1' ); ?>
				</td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*배송지 우편번호</td>
				<td class="board_list_style1" colspan="3">
					<input name="receive_post" value="<?=$receive_post?>" type="text" class="input1" maxlength="7" style="width:120px" readonly onclick="javascript:OpenZipcode('<?=$url_member?>', 'BuyerWriteForm', 'receive_post', 'receive_addr1', 'receive_addr2');" style="cursor:hand;text-align:center;">
					<img src="<?=$sc_url_skin_member?>images/m_btn02.gif" width="67" height="19" border="0" readonly onclick="javascript: OpenZipcode('<?=$url_member?>', 'BuyerWriteForm', 'receive_post', 'receive_addr1', 'receive_addr2');" style="cursor:hand;" align="absmiddle">
				</td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*배송지 자택주소</td>
				<td class="board_list_style1"  colspan="3"><input name="receive_addr1" value="<?=$receive_addr1?>" type="text" class="input1" style="width:362px" maxlength="100"></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*배송지 상세주소</td>
				<td class="board_list_style1" colspan="3"><input name="receive_addr2" value="<?=$receive_addr2?>" type="text" class="input1" style="width:362px" maxlength="100"></td>
			</tr>
			<tr>
				<td class="board_line_style1" ></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
		</table>
		<br/>
		<br/>
		<script>getReceiveInfo();//배송정보 체크박스</script>
		<!-- END -->


		<!-- 결제정보 -->

		<img src="/images/99_common/99_report01_txt03.gif">

		<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:2px 0 1px 0;">
			<tr>
				<td height="2" bgcolor="#8fa6c6"></td>
			</tr>
		</table>
		<table width="721" cellpadding="0" cellspacing="0" border="0">
			<col width="132" /><col width="*" /><col width="100" /><col width="200" />
			<tr>
				<td class="board_tit_style1_2">주문금액</td>
				<td class="board_list_style1" colspan="3"><b><?=make_price_format($total_price,1)?> 원</b></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">결제하실 금액</td>
				<td class="board_list_style1" colspan="3"><font color="#ff0000"><b><?=make_price_format($buy_price,1)?> 원</b></font></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">결제방식</td>
				<td colspan="3">
					&nbsp;
					<?
						foreach( $Arr_account_type as $key => $val ) {
							if ( $key ) {
					?>
					<input type="radio" name="account_type" value="<?=$key?>" <? if ( $key == "1" ) { echo "checked";} ?> onclick="changeBankArea()" /><?=$val?>&nbsp;&nbsp;
					<?
							}
						}
					?>
					<!-- <input type="radio" name="account_type" value="1" checked onclick="changeBankArea()" />온라인 입금&nbsp;&nbsp;
					<input type="radio" name="account_type" value="2"  onclick="changeBankArea()"/> 신용카드 결제 &nbsp;&nbsp;
					<input type="radio" name="account_type" value="3" onclick="changeBankArea()" />계좌이체&nbsp;&nbsp;
					<input type="radio" name="account_type" value="4"  onclick="changeBankArea()"/>핸드폰 결제&nbsp;&nbsp; -->
				</td>
			</tr>
			<tr>
				<td class="board_line_style1" ></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>

		</table>

<script>
	function changeBankArea() {
		ff = document.BuyerWriteForm;
		if ( ff.account_type[0].checked == true ) {
			document.all["BankArea"].style.display = "block";
		}
		else {
			document.all["BankArea"].style.display = "none";
		}
	}
</script>


		<table width="721" cellpadding="0" cellspacing="0" border="0" id="BankArea">
			<col width="132" /><col width="*" /><col width="100" /><col width="200" />
			<tr>
				<td height="5"></td>
			</tr>

			<tr>
				<td class="board_tit_style1_2">입금은행</td>
				<td class="board_list_style1"  colspan="3">
				<?
					//echo "sc_bank_account : ".$sc_bank_account."<BR>";
					$arr_sc_bank_account = explode("\n",$sc_bank_account);
					//echo count($arr_sc_bank_account);
				?>
					<select name="accout_bank">
						<? for ( $i = 0 ; $i < count($arr_sc_bank_account); $i++ ) { ?>
						<option value="<?=$arr_sc_bank_account[$i]?>">&nbsp;<?=$arr_sc_bank_account[$i]?>&nbsp;</option>
						<? } ?>
					</select>
				</tr>
				<tr>
					<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
					<td bgcolor="#dfdfdf" colspan="3"></td>
				</tr>
				<td class="board_tit_style1_2">입금예정일</td>
				<td class="board_list_style1"  colspan="3">
					<select name="accout_dateYear">
						<? for ( $i = date("Y") ; $i < date("Y") + 3; $i ++ ) { ?>
						<option value="<?=$i?>" <? if ($i == date("Y")) echo "selected";?>>&nbsp;<?=$i?>년&nbsp;</option>
						<? } ?>
					</select>
					<select name="accout_dateMonth">
						<? for ( $i = 1 ; $i <= 12; $i ++ ) { ?>
						<option value="<?=$i?>" <? if ($i == date("m")) echo "selected";?>>&nbsp;<?=$i?>월&nbsp;</option>
						<? } ?>
					</select>
					<select name="accout_dateDate">
						<? for ( $i = 1 ; $i <= 31; $i ++ ) { ?>
						<option value="<?=$i?>" <? if ($i == date("d")) echo "selected";?>>&nbsp;<?=$i?>일&nbsp;</option>
						<? } ?>
					</select>
				</td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*입금자명</td>
				<td class="board_list_style1"><input type="text" class="input1" size="30"  name="accout_name" maxlength="20"/></td>
				<td class="board_list_style1"></td>
				<td class="board_list_style1"></td>
			</tr>
			<tr>
				<td class="board_line_style1" ></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>

		</table>
		<br/>
		<br/>
		<!-- END -->

		<!-- 버튼 -->

		<div style="text-align:center;">
			<input type="image" src="/images/99_common/btn_order.gif">
			<a href="javascript:document.location.reload();"><img src="/images/99_common/btn_re_write.gif"></a>
		</div>
		<br/>
		<br/>
		</form>

		<!-- END -->


	</div>
</div>

<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/php/footer.php";
?>

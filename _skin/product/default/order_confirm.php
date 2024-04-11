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
$subVal = 2;             //=> 2Dpeth 넘버링
$thVal = 1;              //=> 3Dpeth 넘버링
$fhVal = 1;              //=> 4Dpeth 넘버링
getHeader();

?>


<div id="cont">

	<div id="title">
		<div id="subject">
			<!-- Title -->
			<script>flashView("/inc/swf/sub_title.swf?pro_tit=주문배송조회",352,30);</script>
			<!-- End -->
		</div>
		<div id="sitemap"><!-- SiteMap --><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td>HOME > 구매정보 > <span class="point_txt01">주문배송조회</span></td><td width="1"><?php	include $_SERVER["DOCUMENT_ROOT"]."/inc/php/quick.php";?></td></tr></table><!-- End --></div>
	</div>


	<div id="con">
		<!-- Contents -->
		<div style="padding-top:10px; padding-bottom:5px;"><img src="/images/99_common/99_report01_txt12.gif"></div>

		<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:2px 0 1px 0;">
			<tr>
				<td height="2" bgcolor="#8fa6c6"></td>
			</tr>
		</table>


		<form name="OrderConfirmForm" action="order.php" method="post">
		<input type="hidden" name="act" value="ok">
		<table width="721" cellpadding="0" cellspacing="0" border="0">
			<col width="100" /><col width="321" /><col width="100" /><col width="200" />
			<tr>
				<td class="board_tit_style1">주문번호</td>
				<td class="board_list_style1" colspan="3">
					<input type="text" class="input1" size="40" name="ordernum" /><br/><br/>
					1. 주문번호는 메일로 보내드렸습니다.<br/>
					2. 메일이 도착하지 않았을 경우 관리자에게 문의바랍니다.<br/>
					3. 회원이신 분은 로그인 후 이용하여 주시기 바랍니다.
				</td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="79" /></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
			<tr>
				<td class="board_tit_style1">성명(실명)</td>
				<td class="board_list_style1" colspan="3"><input type="text" class="input1" size="20" name="buyer_name" /></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="79" /></td>
				<td bgcolor="#dfdfdf" colspan="3"></td>
			</tr>
			<tr>
				<td class="board_tit_style1">이메일</td>
				<td class="board_list_style1" colspan="3">
					<? getEmailForm("buyer_email1", "buyer_email2", $buyer_email1, $buyer_email2, ""); ?>
				</td>
			</tr>
			<tr>
				<td class="board_line_style1"></td>
				<td bgcolor="#dfdfdf"></td>
				<td bgcolor="#dfdfdf"></td>
				<td bgcolor="#dfdfdf"></td>
			</tr>
		</table>
		<br/>
		<div style="text-align:center; padding-top:10px;">
			<input type="image" src="/images/99_common/btn_infor.gif">
			<a href="javascript:UserLoginGo();"><img src="/images/99_common/btn_log.gif"></a>
			<!-- <a href="#"><img src="/images/99_common/btn_re_write.gif"></a> -->
		</div>
		</form>
		<br>
		<!-- End -->

		<!-- 주문조회 (클릭시 주문내역없을때)popup -->
		<!--div style="text-align:center; padding-top:10px;">
				<img src="/images/99_common/99_report_popup.gif" usemap="#Map">
				</div-->

		<map name="Map" id="Map"><area shape="rect" coords="135,81,206,104" href="#" /></map>
		<!-- END -->

	</div>



</div>

<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/php/footer.php";
?>

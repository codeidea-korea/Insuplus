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


<div id="cont">

	<div id="title">
		<div id="subject">
			<!-- Title -->
			<script>flashView("/inc/swf/sub_title.swf?pro_tit=주문정보 확인",352,30);</script>
			<!-- End -->
		</div>
		<div id="sitemap"><!-- SiteMap --><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td>HOME > 구매정보 > <span class="point_txt01">주문정보 확인</span></td><td width="1"><?php	include $_SERVER["DOCUMENT_ROOT"]."/inc/php/quick.php";?></td></tr></table><!-- End --></div>
	</div>


	<div id="con">

		<!-- 주문정보확인 -->

		<div style="padding-bottom:5px;"><img src="/images/99_common/99_report01_txt04.gif"></div>

		<table width="100%" cellpadding="0" cellspacing="0" border="0" >
			<col width="132" /><col width="*" /><col width="100" /><col width="200" />
			<tr>
				<td class="board_tit_style1_2">*성명(실명)</td>
				<td colspan="3" class="board_list_style1" style="border-top:1px solid #dfdfdf;"><?=$buyer_name?></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3" bgcolor="#dfdfdf" ></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*전화번호</td>
				<td class="board_list_style1"><?=$buyer_tel?></td>
				<td class="board_tit_style1_2">*휴대전화</td>
				<td class="board_list_style1"><?=$buyer_hp?></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3"  bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*우편번호</td>
				<td colspan="3" class="board_list_style1"><?=$buyer_post?></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3" bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*자택주소</td>
				<td colspan="3" class="board_list_style1"><?=$buyer_addr1?></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3"  bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*상세주소</td>
				<td  colspan="3"class="board_list_style1"><?=$buyer_addr2?></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3" bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*E-mail</td>
				<td colspan="3" class="board_list_style1"><?=$buyer_email?></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3" bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">기타 하실말씀</td>
				<td colspan="3" class="board_list_style1" style="border-bottom:1px solid #dfdfdf;"><?=RESSTRTEXT($buyer_content)?>&nbsp;</td>
			</tr>
		</table>
		<br/>
		<br/>

		<!-- END -->

		<!-- 주문&결제정보 -->

		<div style="padding-bottom:5px;"><img src="/images/99_common/99_report01_txt05.gif"></div>

		<table width="100%" cellpadding="0" cellspacing="0" border="0" >
			<col width="132" /><col width="*" />
			<tr height="110">
				<td class="board_tit_style1_2" >주문내역</td>
				<td class="board_list_style1" style="border-top:1px solid #dfdfdf;">
					<center>
					<table width="90%" height="90" border="0" cellpadding="0" cellspacing="1" bgcolor="cccccc">
						<tr height="20" bgcolor="dddddd">
							<td width="30%">사진</td>
							<td width="30%">제품명</td>
							<td width="15%">판매금액</td>
							<td width="10%">수량</td>
							<td width="15%">합계</td>
						</tr>
<?
// 상품 정보
//	echo "pr_idx : ".$pr_idx."<BR>";
	for ( $i = 0 ; $i < count($pr_idx); $i++ ) {
?>
						<tr height="90">
							<td align="center" bgcolor="ffffff"><img src="<?=$_POST["pr_img"][$i]?>"></td><!--제품사진 114x74 -->
							<td align="center" bgcolor="ffffff"><?=$_POST["pr_name"][$i]?><BR><?=$_POST["print_option"][$i]?></td>
							<td align="center" bgcolor="ffffff"><b><?=make_price_format($_POST["pr_price"][$i],1)?> 원</b></td>
							<td align="center" bgcolor="ffffff"><?=$_POST["goods_num"][$i]?>&nbsp;</td>
							<td align="center" bgcolor="ffffff"><b><?=make_price_format($_POST["show_price"][$i],1)?> 원</b></td>
						</tr>
<?
	}
?>
					</table>
					</center>
				</td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">최종 결제 금액</td>
				<td class="board_list_style1"><font color="#ff0000"><b><?=make_price_format($_POST["buy_price"],1)?> 원</b></font></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">결제방식</td>
				<td class="board_list_style1">
					<?
						//echo "account_type : ".$_POST["account_type"]."<BR>";
						if ( $_POST["account_type"] == "1" ) {
							$print_account_type =  "온라인 입금";
						} elseif ( $_POST["account_type"] == "2" ) {
							$print_account_type =  "신용카드 결제";
						} elseif ( $_POST["account_type"] == "3" ) {
							$print_account_type =  "계좌이체";
						} elseif ( $_POST["account_type"] == "4" ) {
							$print_account_type =  "핸드폰 결제";
						}
						echo $print_account_type;
					?>
				</td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf"></td>
			</tr>

			<? if ( $_POST["account_type"] == "1" ) { ?>
			<tr>
				<td class="board_tit_style1_2">입금은행</td>
				<td class="board_list_style1"><?=$_POST["accout_bank"]?></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">입금예정일</td>
				<td class="board_list_style1">
					<?
						$accout_dateYear = $_POST["accout_dateYear"];
						$accout_dateMonth = $_POST["accout_dateMonth"];
						$accout_dateDate = $_POST["accout_dateDate"];

						$accout_date = mktime(0, 0, 0, $accout_dateMonth, $accout_dateDate, $accout_dateYear);
						$print_accout_date = date("Y-m-d", $accout_date);
						echo $print_accout_date;
					?>
				</td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">입금자명</td>
				<td class="board_list_style1" style="border-bottom:1px solid #dfdfdf;"><?=$_POST["accout_name"]?></td>
			</tr>
			<? } ?>
		</table>
		<br/>
		<br/>

		<!-- END -->

		<!-- 주문정보확인 -->

		<div style="padding-bottom:5px;"><img src="/images/99_common/99_report01_txt04.gif"></div>

		<table width="100%" cellpadding="0" cellspacing="0" border="0" >
			<col width="132" /><col width="*" /><col width="100" /><col width="200" />
			<tr>
				<td class="board_tit_style1_2">*성명(실명)</td>
				<td colspan="3" class="board_list_style1" style="border-top:1px solid #dfdfdf;"><?=$receive_name?></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3" bgcolor="#dfdfdf" ></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*전화번호</td>
				<td class="board_list_style1"><?=$receive_tel?></td>
				<td class="board_tit_style1_2">*휴대전화</td>
				<td class="board_list_style1"><?=$receive_hp?></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3"  bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*배송지 우편번호</td>
				<td colspan="3" class="board_list_style1"><?=$receive_post?></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3" bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*배송지 자택주소</td>
				<td colspan="3" class="board_list_style1"><?=$receive_addr1?></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3"  bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">*배송지 상세주소</td>
				<td  colspan="3"class="board_list_style1" style="border-bottom:1px solid #dfdfdf;"><?=$receive_addr2?></td>
			</tr>
		</table>
		<br/>
		<br/>

		<!-- END -->

		<!-- 버튼 -->

<form name="BuyerConfirmForm" action="cart.php" method="post" onsubmit="return funcFormChk()">
<input type="hidden" name="mode" value="buy">
<input type="hidden" name="step" value="3">

<!-- 상품 정보 -->
<?
	for ( $i = 0 ; $i < count($_POST["pr_idx"]); $i++ ) {
?>
<input type="hidden" name="pr_idx[]" value="<?=$_POST["pr_idx"][$i]?>"><!-- 상품 번호 -->
<input type="hidden" name="pr_img[]" value="<?=$_POST["pr_img"][$i]?>"><!-- 상품 이미지 -->
<input type="hidden" name="pr_name[]" value="<?=$_POST["pr_name"][$i]?>"><!-- 상품 이름 -->
<input type="hidden" name="print_option[]" value="<?=$_POST["print_option"]?>"><!-- 옵션명 -->
<input type="hidden" name="pr_price[]" value="<?=$_POST["pr_price"][$i]?>"><!-- 상품 가격 -->
<input type="hidden" name="goods_num[]" value="<?=$_POST["goods_num"][$i]?>"><!-- 주문수량 -->
<input type="hidden" name="show_price[]" value="<?=$_POST["show_price"][$i]?>"><!-- 상품 개별 합계가격 -->
<?
	}
?>


<!-- 금액 정보 -->
<input type="hidden" name="delivery_price" value="<?=$_POST["delivery_price"]?>"><!-- 배송료 -->
<input type="hidden" name="total_price" value="<?=$_POST["total_price"]?>"><!--  상품 총 합계가격 -->
<input type="hidden" name="buy_price" value="<?=$_POST["buy_price"]?>"><!-- 결제 번호 -->


<!-- 결제 방식 정보 -->
<input type="hidden" name="account_type" value="<?=$_POST["account_type"]?>"><!-- 결제방식 -->
<input type="hidden" name="accout_bank" value="<?=$_POST["accout_bank"]?>"><!-- 입금은행 -->
<input type="hidden" name="accout_date" value="<?=$accout_date?>"><!-- 입금예정일 -->
<input type="hidden" name="accout_name" value="<?=$_POST["accout_name"]?>"><!-- 입금자명 -->


<!-- 주문자 정보 -->
<input type="hidden" name="buyer_name" value="<?=$buyer_name?>"><!-- 주문자 성명 -->
<input type="hidden" name="buyer_tel" value="<?=$buyer_tel?>"><!-- 주문자 전화번호 -->
<input type="hidden" name="buyer_hp" value="<?=$buyer_hp?>"><!-- 주문자 핸드폰번호 -->
<input type="hidden" name="buyer_post" value="<?=$buyer_post?>"><!-- 주문자 우편번호 -->
<input type="hidden" name="buyer_addr1" value="<?=$buyer_addr1?>"><!-- 주문자 주소1 -->
<input type="hidden" name="buyer_addr2" value="<?=$buyer_addr2?>"><!-- 주문자 주소2 -->
<input type="hidden" name="buyer_email" value="<?=$buyer_email?>"><!-- 주문자 E-mail -->
<input type="hidden" name="buyer_content" value="<?=$buyer_content?>"><!-- 주문자 남기는말 -->

<!-- 배송지 정보 -->
<input type="hidden" name="receive_name" value="<?=$receive_name?>"><!-- 배송지 성명 -->
<input type="hidden" name="receive_tel" value="<?=$receive_tel?>"><!-- 배송지 전화번호 -->
<input type="hidden" name="receive_hp" value="<?=$receive_hp?>"><!-- 배송지 핸드폰번호 -->
<input type="hidden" name="receive_post" value="<?=$receive_post?>"><!-- 배송지 우편번호 -->
<input type="hidden" name="receive_addr1" value="<?=$receive_addr1?>"><!-- 배송지 주소1 -->
<input type="hidden" name="receive_addr2" value="<?=$receive_addr2?>"><!-- 배송지 주소2 -->

<? if ( $_POST["account_type"] > 1 ) { // 온라인 입금 이상 일때 (PG 사 이용일때... )  ?>
<?
	if ( $sc_pay_type == "inicis" ) {
?>

<!-----------------------------------------------------------------------------------------------------
※ 주의 ※
 아래의 body TAG의 내용중에
 onload="javascript:enable_click()" onFocus="javascript:focus_control()" 이 부분은 수정없이 그대로 사용.
 아래의 form TAG내용도 수정없이 그대로 사용.
------------------------------------------------------------------------------------------------------->


<!-- <script language=javascript src="http://plugin.inicis.com/pay40.js"></script> --><!-- euc-kr 일때 -->
<script language=javascript src="http://plugin.inicis.com/pay40_uni.js"></script><!-- utf-8 일때 -->

<script language=javascript>
StartSmartUpdate();
</script>
<!-- <body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0 onload="javascript:enable_click()" onFocus="javascript:focus_control()"> -->

<!----------------------------------------------------------------------------------
※ 주의 ※
 상단 자바스크립트는 지불페이지를 실제 적용하실때 지불페이지 맨위에 위치시켜
 적용하여야 만일에 발생할수 있는 플러그인 오류를 미연에 방지할 수 있습니다.

  <script language=javascript src="http://plugin.inicis.com/pay40.js"></script>
  <script language=javascript>
  StartSmartUpdate();	// 플러그인 설치(확인)
  </script>
----------------------------------------------------------------------------------->

<?
	$Temp_pr_name = "";
	$Temp_pr_name .= $_POST["pr_name"][0];
	if ( count($_POST["pr_idx"]) > 1 ) {
		$Temp_pr_name .= " 외  ".(count($_POST["pr_idx"])-1)." 개 상품";
	}
?>

<input type="hidden" name="gopaymethod" value=""><!-- 결제구분() -->
<input type="hidden" name="goodname" value="<?=$Temp_pr_name?>"><!-- 상품명 -->
<input type="hidden" name="price" value="<?=$_POST["buy_price"]?>"><!-- 삼품가격 -->
<!-- <input type="hidden" name="price" value="1000"> --><!-- 삼품가격 -->
<input type="hidden" name="buyername" value="<?=$buyer_name?>"><!-- 이름 -->
<input type="hidden" name="buyeremail" value="<?=$buyer_email?>"><!-- 이메일 -->
<input type="hidden" name="buyertel" value="<?=$buyer_hp?>"><!-- 전화번호 -->
<!-----------------------------------------------------------------------------------------------------
※ 주의 ※
보호자 이메일 주소입력 받는 필드는 소액결제(핸드폰 , 전화결제)
중에  14세 미만의 고객 결제시에 부모 이메일로 결제 내용통보하라는 정통부 권고 사항입니다.
다른 결제 수단을 이용시에는 해당 필드(parentemail)삭제 하셔도 문제없습니다.
------------------------------------------------------------------------------------------------------->
<!-- <input type=hidden name=parentemail value=""> -->

<!--
상점아이디.
테스트를 마친 후, 발급받은 아이디로 바꾸어 주십시오.
-->
<!-- <input type=hidden name=mid value=INIpayTest> -->
<input type=hidden name=mid value=GBsindohco>

<!--
화폐단위
WON 또는 CENT
주의 : 미화승인은 별도 계약이 필요합니다.
-->
<input type=hidden name=currency value="WON">


<!--
무이자 할부
무이자로 할부를 제공 : yes
무이자할부는 별도 계약이 필요합니다.
카드사별,할부개월수별 무이자할부 적용은 아래의 카드할부기간을 참조 하십시오.
무이자할부 옵션 적용은 반드시 매뉴얼을 참조하여 주십시오.
-->
<input type=hidden name=nointerest value="no">


<!--
카드할부기간
각 카드사별로 지원하는 개월수가 다르므로 유의하시기 바랍니다.

value의 마지막 부분에 카드사코드와 할부기간을 입력하면 해당 카드사의 해당
할부개월만 무이자할부로 처리됩니다 (매뉴얼 참조).
-->
<input type=hidden name=quotabase value="선택:일시불:3개월:4개월:5개월:6개월:7개월:8개월:9개월:10개월:11개월:12개월">


<!-- 기타설정 -->
<!--
SKIN : 플러그인 스킨 칼라 변경 기능 - 6가지 칼라(ORIGINAL, GREEN, ORANGE, BLUE, KAKKI, GRAY)
HPP : 컨텐츠 또는 실물 결제 여부에 따라 HPP(1)과 HPP(2)중 선택 적용(HPP(1):컨텐츠, HPP(2):실물).
Card(0): 신용카드 지불시에 이니시스 대표 가맹점인 경우에 필수적으로 세팅 필요 ( 자체 가맹점인 경우에는 카드사의 계약에 따라 설정) - 자세한 내용은 메뉴얼  참조.
OCB : OK CASH BAG 가맹점으로 신용카드 결제시에 OK CASH BAG 적립을 적용하시기 원하시면 "OCB" 세팅 필요 그 외에 경우에는 삭제해야 정상적인 결제 이루어짐.
no_receipt : 은행계좌이체시 현금영수증 발행여부 체크박스 비활성화 (현금영수증 발급 계약이 되어 있어야 사용가능)
-->
<input type=hidden name=acceptmethod value="SKIN(ORIGINAL):HPP(1):OCB">


<!--
상점 주문번호 : 무통장입금 예약(가상계좌 이체),전화결재 관련 필수필드로 반드시 상점의 주문번호를 페이지에 추가해야 합니다.
결제수단 중에 은행 계좌이체 이용 시에는 주문 번호가 결제결과를 조회하는 기준 필드가 됩니다.
상점 주문번호는 최대 40 BYTE 길이입니다.
-->
<input type=hidden name=oid size=40 value="merchant_oid">


<!--
플러그인 좌측 상단 상점 로고 이미지 사용
이미지의 크기 : 90 X 34 pixels
플러그인 좌측 상단에 상점 로고 이미지를 사용하실 수 있으며,
주석을 풀고 이미지가 있는 URL을 입력하시면 플러그인 상단 부분에 상점 이미지를 삽입할수 있습니다.
-->
<!--input type=hidden name=ini_logoimage_url  value="http://[사용할 이미지주소]"-->

<!--
좌측 결제메뉴 위치에 이미지 추가
이미지의 크기 : 단일 결제 수단 - 91 X 148 pixels, 신용카드/ISP/계좌이체/가상계좌 - 91 X 96 pixels
좌측 결제메뉴 위치에 미미지를 추가하시 위해서는 담당 영업대표에게 사용여부 계약을 하신 후
주석을 풀고 이미지가 있는 URL을 입력하시면 플러그인 좌측 결제메뉴 부분에 이미지를 삽입할수 있습니다.
-->
<!--input type=hidden name=ini_menuarea_url value="http://[사용할 이미지주소]"-->

<!--
플러그인에 의해서 값이 채워지거나, 플러그인이 참조하는 필드들
삭제/수정 불가
uid 필드에 절대로 임의의 값을 넣지 않도록 하시기 바랍니다.
-->
<input type=hidden name=quotainterest value="">
<input type=hidden name=paymethod value="">
<input type=hidden name=cardcode value="">
<input type=hidden name=cardquota value="">
<input type=hidden name=rbankcode value="">
<input type=hidden name=reqsign value="DONE">
<input type=hidden name=encrypted value="">
<input type=hidden name=sessionkey value="">
<input type=hidden name=uid value="">
<input type=hidden name=sid value="">
<input type=hidden name=version value=4000>
<input type=hidden name=clickcontrol value="">


<script language=javascript>

var openwin;

function funcFormChk()
{
	// MakePayMessage()를 호출함으로써 플러그인이 화면에 나타나며, Hidden Field
	// 에 값들이 채워지게 됩니다. 일반적인 경우, 플러그인은 결제처리를 직접하는 것이
	// 아니라, 중요한 정보를 암호화 하여 Hidden Field의 값들을 채우고 종료하며,
	// 다음 페이지인 INIsecurepay.php로 데이터가 포스트 되어 결제 처리됨을 유의하시기 바랍니다.

	if(document.BuyerConfirmForm.clickcontrol.value == "enable")
	{

		if(document.BuyerConfirmForm.goodname.value == "")  // 필수항목 체크 (상품명, 상품가격, 구매자명, 구매자 이메일주소, 구매자 전화번호)
		{
			alert("상품명이 빠졌습니다. 필수항목입니다.");
			return false;
		}
		else if(document.BuyerConfirmForm.price.value == "")
		{
			alert("상품가격이 빠졌습니다. 필수항목입니다.");
			return false;
		}
		else if(document.BuyerConfirmForm.buyername.value == "")
		{
			alert("구매자명이 빠졌습니다. 필수항목입니다.");
			return false;
		}
		else if(document.BuyerConfirmForm.buyeremail.value == "")
		{
			alert("구매자 이메일주소가 빠졌습니다. 필수항목입니다.");
			return false;
		}
		else if(document.BuyerConfirmForm.buyertel.value == "")
		{
			alert("구매자 전화번호가 빠졌습니다. 필수항목입니다.");
			return false;
		}
		else if(document.INIpay == null || document.INIpay.object == null)  // 플러그인 설치유무 체크
		{
			alert("\n이니페이 플러그인 128이 설치되지 않았습니다. \n\n안전한 결제를 위하여 이니페이 플러그인 128의 설치가 필요합니다. \n\n다시 설치하시려면 Ctrl + F5키를 누르시거나 메뉴의 [보기/새로고침]을 선택하여 주십시오.");
			return false;
		}
		else
		{
			/******
			 * 플러그인이 참조하는 각종 결제옵션을 이곳에서 수행할 수 있습니다.
			 * (자바스크립트를 이용한 동적 옵션처리)
			*/


			if (MakePayMessage(document.BuyerConfirmForm))
			{
				disable_click();
				openwin = window.open("<?=$url_inicis?>childwin.html","childwin","width=299,height=149");
				return true;
			}
			else
			{
				alert("결제를 취소하셨습니다.");
				return false;
			}
		}
	}
	else
	{
		return false;
	}
}


function enable_click()
{
	document.BuyerConfirmForm.clickcontrol.value = "enable"
}

function disable_click()
{
	document.BuyerConfirmForm.clickcontrol.value = "disable"
}

function focus_control()
{
	if(document.BuyerConfirmForm.clickcontrol.value == "disable")
		openwin.focus();
}

window.onload = function () {
	enable_click();
	//document.BuyerConfirmForm.onsubmit = return pay(document.BuyerConfirmForm);
}
window.onFocus = function () {
	focus_control();
}


</script>


<?
	} // end if (inicis)
?>
<? } else { ?>
<script>
	function funcFormChk() {
		if ( !confirm("\n\n결제를 진행합니다.\n\n입력 정보를 모두 확인하였고 이상이 없으면 확인을 선택해주세요.\n\n") ) {
			return false;
		}
	}
</script>

<? } // end if (account_type) ?>

		<div style="text-align:center;">
			<input type="image" src="/images/99_common/btn_settle.gif">
			<a href="javascript:history.back();"><img src="/images/99_common/btn_before.gif"></a>
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

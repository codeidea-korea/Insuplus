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

 if ( !$ss_u_id ) {
$menuName = "REPORT";   //=> 1Dpeth 네이밍
$subVal = 2;             //=> 2Dpeth 넘버링
$thVal = 1;              //=> 3Dpeth 넘버링
$fhVal = 1;              //=> 4Dpeth 넘버링

 } else {
$menuName = "MYPAGE";   //=> 1Dpeth 네이밍
$subVal = 3;             //=> 2Dpeth 넘버링
$thVal = 1;              //=> 3Dpeth 넘버링
$fhVal = 1;              //=> 4Dpeth 넘버링
 }
getHeader();

?>


<div id="cont">

<? if ( !$ss_u_id ) { ?>
	<div id="title">
		<div id="subject">
			<!-- Title -->
			<script>flashView("/inc/swf/sub_title.swf?pro_tit=주문배송조회",352,30);</script>
			<!-- End -->
		</div>
		<div id="sitemap"><!-- SiteMap --><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td>HOME > 구매정보 > <span class="point_txt01">주문배송조회</span></td><td width="1"><?php include $_SERVER["DOCUMENT_ROOT"]."/inc/php/quick.php";?></td></tr></table><!-- End --></div>
	</div>
<? } else { ?>
	<div id="title">
		<div id="subject">
			<!-- Title -->
			<script>flashView("/inc/swf/sub_title.swf?pro_tit=구매내역",352,30);</script>
			<!-- End -->
		</div>
		<div id="sitemap"><!-- SiteMap --><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td>HOME > 마이페이지 > <span class="point_txt01">구매내역</span></td><td width="1"><?php include $_SERVER["DOCUMENT_ROOT"]."/inc/php/quick.php";?></td></tr></table><!-- End --></div>
	</div>
<? } ?>
	<div id="con">





		<img src="/images/pixel.gif" width="1" height="25" />
		<div style="padding-bottom:5px;"><img src="/images/99_common/99_report01_txt05.gif"></div>

		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<td class="board_tit_style2" valign="bottom">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<col width="200" /><col width="1" /><col width="*" /><col width="1" /><col width="100" /><col width="1" /><col width="100" /><col width="1" /><col width="100" /><col width="1" />
					<tr>
						<td class="title_txt01" >사진</td>
						<td class="board_line_style2"></td>
						<td class="title_txt01">제품명</td>
						<td class="board_line_style2"></td>
						<td class="title_txt01">판매금액</td>
						<td class="board_line_style2"></td>
						<td class="title_txt01">수량</td>
						<td class="board_line_style2"></td>
						<td class="title_txt01">합계</td>
					</tr>
				</table>
			</td>
		</table>

		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<col width="200" /><col width="1" /><col width="*" /><col width="1" /><col width="100" /><col width="1" /><col width="100" /><col width="1" /><col width="100" /><col width="1" />
			<? while ($row2 = $dbcon->fetch_array($rs2) ) { ?>
			<tr>
				<td align="center"><img src="<?=$row2["pr_img"]?>" style="margin:10px 0 10px 0"></td>
				<td bgcolor="#dbdbdb"></td>
				<td class="board_list_style2" align="center"><?=$row2["pr_name"]?><BR><?=$row2["print_name"]?></td>
				<td bgcolor="#dbdbdb"></td>
				<td class="board_list_style2 " align="center"><?=make_price_format($row2["pr_name"],1)?> 원</td>
				<td bgcolor="#dbdbdb"></td>
				<td class="board_list_style2" align="center"><?=$row2["goods_num"]?></td>
				<td bgcolor="#dbdbdb"></td>
				<td class="board_list_style2" align="center"><?=make_price_format($row2["show_price"],1)?> 원</td>
			</tr>
			<tr>
				<td colspan="13" height="1" bgcolor="#dbdbdb"></td>
			</tr>
			<? } ?>
		</table>
		<br/>
		<br/>
		<!-- End -->



		<!-- 구입내역 -->

		<div style="padding-bottom:5px;"><img src="/images/99_common/99_report01_txt04.gif"></div>

		<table width="100%" cellpadding="0" cellspacing="0" border="0" >
			<col width="132" /><col width="*" /><col width="100" /><col width="200" />
			<tr>
				<td class="board_tit_style1_2">주문번호</td>
				<td colspan="3" class="board_list_style1" style="border-top:1px solid #dfdfdf;"><?=$row1["ordernum"]?></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3" bgcolor="#dfdfdf" ></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">주문하신 분</td>
				<td colspan="3" class="board_list_style1">
					이름 : <?=$row1["buyer_name"]?><br/>
					주소 : [<?=$row1["buyer_post"]?>] <?=$row1["buyer_addr1"]?> <?=$row1["buyer_addr2"]?><br/>
					전화 : <?=$row1["buyer_tel"]?> / <?=$row1["buyer_hp"]?>
				</td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3"  bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">받으실 분</td>
				<td colspan="3" class="board_list_style1">
					이름 : <?=$row1["receive_name"]?><br/>
					주소 : [<?=$row1["receive_post"]?>] <?=$row1["receive_addr1"]?> <?=$row1["receive_addr2"]?><br/>
					전호 : <?=$row1["receive_tel"]?> / <?=$row1["receive_hp"]?>
				</td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3" bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">주문날짜</td>
				<td colspan="3" class="board_list_style1"><?=date("Y-m-d [H:i:s]", strtotime($row1["a_regdate"]) )?></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3"  bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">결제방법</td>
				<td  colspan="3"class="board_list_style1">
					<?
						if ( $row1["account_type"] == "1" ) {
							$print_account_type =  "온라인 입금";
						} elseif ( $row1["account_type"] == "2" ) {
							$print_account_type =  "신용카드 결제";
						} elseif ( $row1["account_type"] == "3" ) {
							$print_account_type =  "계좌이체";
						} elseif ( $row1["account_type"] == "4" ) {
							$print_account_type =  "핸드폰 결제";
						}
						echo $print_account_type;
					?>

				</td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3"  bgcolor="#dfdfdf"></td>
			</tr>
			<? if ( $row1["account_type"] == "1" ) { ?>
			<tr>
				<td class="board_tit_style1_2">입금인명/입금일</td>
				<td  colspan="3"class="board_list_style1">
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
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3"  bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">입금선택 계좌</td>
				<td  colspan="3"class="board_list_style1"><?=$row1["accout_bank"]?></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3"  bgcolor="#dfdfdf"></td>
			</tr>
			<? } ?>
			<tr>
				<td class="board_tit_style1_2">총 결제하실 금액</td>
				<td  colspan="3"class="board_list_style1"><font color="ff0000"><b><?=make_price_format($row1["buy_price"],1)?> 원</b></font></td>
			</tr>
			<tr>
				<td class="board_line_style1"><img src="/images/99_common/gray_line.gif" width="115" /></td>
				<td colspan="3"  bgcolor="#dfdfdf"></td>
			</tr>
			<tr>
				<td class="board_tit_style1_2">기타 하실말씀</td>
				<td  colspan="3"class="board_list_style1" style="border-bottom:1px solid #dfdfdf;"><?=nl2br(RESSTR($row1["buyer_content"]))?></td>
			</tr>
		</table>
		<br/>
		<div>
			<font color="999999">
			- 회원이 아니신 분은 주문번호를 기억하여 주십시오 (상기 내용을 이메일로 발송하여 드립니다)<br>
			- 본 내용은 이메일로 보내지게 되며 입금(결제)여부 상관 없이 메일이 보내 지도록 되어있습니다.
			</font>
		</div>
		<table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:5px 0 5px 0;">
			<tr>
				<td height="2" bgcolor="#8fa6c6"></td>
			</tr>
		</table>
		<br>

		<div style="text-align:center;">
			<a href="order.php"><img src="/images/99_common/btn_ok.gif">  </a>
		</div>



	</div>
</div>

<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/php/footer.php";
?>

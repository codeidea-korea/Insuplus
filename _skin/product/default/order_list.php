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

	$menuName = "MYPAGE";   //=> 1Dpeth 네이밍
	$subVal = 3;             //=> 2Dpeth 넘버링
	$thVal = 1;              //=> 3Dpeth 넘버링
	$fhVal = 1;              //=> 4Dpeth 넘버링
	getHeader();
?>


<div id="cont">

	<div id="title">
		<div id="subject">
			<!-- Title -->
			<script>flashView("/inc/swf/sub_title.swf?pro_tit=구매내역",352,30);</script>
			<!-- End -->
		</div>
		<div id="sitemap"><!-- SiteMap --><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td>HOME > 마이페이지 > <span class="point_txt01">구매내역</span></td><td width="1"><?php	include $_SERVER["DOCUMENT_ROOT"]."/inc/php/quick.php";?></td></tr></table><!-- End --></div>
	</div>

	<div id="con">



		<div style="padding-top:10px;"><img src="/images/07_mypage/07_mypage_tit03.gif"></div>
			<!-- table -->

		<!-- Contents -->

		<!--img src="/images/98_skin/blue_style/06_notice_img01.gif" /-->

		<img src="/images/pixel.gif" width="1" height="25" />

		<div style="padding-bottom:8px;"><img src="/images/07_mypage/07_mypage03_txt01.gif"></div>

		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<td class="board_tit_style2" valign="bottom">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<col width="*" /><col width="1" /><col width="100" /><col width="1" /><col width="100" /><col width="1" /><col width="100" />
				<col width="1" /><col width="80"/>
				<!-- <col width="1" /><col width="90"/> -->
				<col width="1" /><col width="100" />
					<tr>
						<td class="title_txt01">주문번호</td>
						<td class="board_line_style2"></td>
						<td class="title_txt01">결제방법</td>
						<td class="board_line_style2"></td>
						<td class="title_txt01">결제금액</td>
						<td class="board_line_style2"></td>
						<td class="title_txt01">주문일</td>
						<td class="board_line_style2"></td>
						<td class="title_txt01">주문상태</td>
						<!-- <td class="board_line_style2"></td>
						<td class="title_txt01">배송상태</td> -->
						<td class="board_line_style2"></td>
						<td class="title_txt01">비고</td>
					</tr>
				</table>
			</td>
		</table>

		<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<col width="*" /><col width="1" /><col width="100" /><col width="1" /><col width="100" /><col width="1" /><col width="100" />
			<col width="1" /><col width="80"/>
			<!-- <col width="1" /><col width="90"/> -->
			<col width="1" /><col width="100" />
			<?
			if ( !$rs1_cnt ) {
			?>
			<tr>
				<td class="board_list_style2" align="center">구매하신 제품이 없습니다.</td>
				</td>
			</tr>
			<tr>
				<td colspan="13" height="1" bgcolor="#dbdbdb"></td>
			</tr>
			<?
			} else {
			while ($row1 = $dbcon -> fetch_array($rs1) ) { ?>
			<tr>
				<td align="center"><a href="order.php?mode=view&ordernum=<?=$row1["ordernum"]?>"><?=$row1["ordernum"]?></a></td>
				<td bgcolor="#dbdbdb"></td>
				<td class="board_list_style2" align="center">
					<?
						$print_account_type = $Arr_account_type[$row1["account_type"]];
						echo $print_account_type;
					?>
				</td>
				<td bgcolor="#dbdbdb"></td>
				<td class="board_list_style2 " align="center"><?=make_price_format($row1["buy_price"],1)?> 원</td>
				<td bgcolor="#dbdbdb"></td>
				<td class="board_list_style2" align="center"><?=date("Y.m.d", strtotime($row1["a_regdate"]) )?></td>
				<td bgcolor="#dbdbdb"></td>
				<td class="board_list_style2" align="center">
					<?
						$print_a_state = $Arr_a_state[$row1["a_state"]];
						echo $print_a_state;
					?>
				</td>
				<!-- <td bgcolor="#dbdbdb"></td>
				<td class="board_list_style2" align="center">대한통운</td> -->
				<td bgcolor="#dbdbdb"></td>
				<td class="board_list_style2" align="center">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="13" height="1" bgcolor="#dbdbdb"></td>
			</tr>
			<? }
			}
			?>
		</table>


		<? list_page_sindoh($page, $total_page, $page_per_block); ?>


	</div>
</div>

<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/php/footer.php";
?>

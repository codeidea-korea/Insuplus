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
			<script>flashView("/inc/swf/sub_title.swf?pro_tit=결제완료",352,30);</script>
			<!-- End -->
		</div>
		<div id="sitemap"><!-- SiteMap --><table width="100%" border="0" cellpadding="0" cellspacing="0"><tr><td>HOME > 구매정보 > <span class="point_txt01">결제완료</span></td><td width="1"><?php	include $_SERVER["DOCUMENT_ROOT"]."/inc/php/quick.php";?></td></tr></table><!-- End --></div>
	</div>


	<div id="con">


		<div><img src="/images/99_common/99_set_comp_02.gif"></div>
		<br/>
		<div style="text-align:center;">
			<a href="<?=$url_product?>order.php"><img src="/images/99_common/btn_infor.gif"></a>
			<a href="<?=$url_product?>shop.php"><img src="/images/99_common/btn_shopping_go.gif"></a>

		</div>
		<br/>

	</div>
</div>

<?php
include $_SERVER["DOCUMENT_ROOT"]."/inc/php/footer.php";
?>

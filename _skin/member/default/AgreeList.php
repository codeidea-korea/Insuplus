<?php
	/*---------------------------------------------------------------------------------*/
	/*  ● 작업자 : 전한솔 팀장
	/*  ● 작성일자 : 2009.10.18
	/*  ● 한의원 소개   => Highskin
	/*  ● 치료안내      => Info
	/*  ● 피부클리닉    => Skin
	/*  ● 비만클리닉    => Diet
	/*  ● 한방성형      => Face
	/*  ● 온라인상담    => Counseling
	/*  ● 커뮤니티      => Community
	/*  ● 맴버          => Member
	/*  ● 사이트맵      => Etc
	/*---------------------------------------------------------------------------------*/
	if($type=="member1"){
		$menuName = "member";      //=> 1Dpeth 네이c밍
		$subVal = 3;                 //=> 2Dpeth 넘버링
		$thVal = 1;                  //=> 3Dpeth 넘버링
		$fhVal = 1;                  //=> 4Dpeth 넘버링
		$tit_img = "<img src=\"/images/common/member_title_private.gif\" />";
		$tit_text = "MEMBER <img src=\"../../images/common/home_bl.gif\"/> 개인정보취급방침";
	}else if($type=="member2"){
		$menuName = "member";      //=> 1Dpeth 네이c밍
		$subVal = 5;                 //=> 2Dpeth 넘버링
		$thVal = 1;                  //=> 3Dpeth 넘버링
		$fhVal = 1;                  //=> 4Dpeth 넘버링
		$tit_img = "<img src=\"/images/common/member_title_guide.gif\" />";
		$tit_text = "MEMBER <img src=\"../../images/common/home_bl.gif\"/> 이용약관";
	}else if($type=="mypage1"){
		$menuName = "mypage";      //=> 1Dpeth 네이c밍
		$subVal = 3;                 //=> 2Dpeth 넘버링
		$thVal = 1;                  //=> 3Dpeth 넘버링
		$fhVal = 1;                  //=> 4Dpeth 넘버링
		$tit_img = "<img src=\"/images/common/member_title_private.gif\" />";
		$tit_text = "마이페이지 <img src=\"../../images/common/home_bl.gif\"/> 개인정보취급방침";
	}else if($type=="mypage2"){
		$menuName = "mypage";      //=> 1Dpeth 네이c밍
		$subVal = 5;                 //=> 2Dpeth 넘버링
		$thVal = 1;                  //=> 3Dpeth 넘버링
		$fhVal = 1;                  //=> 4Dpeth 넘버링
		$tit_img = "<img src=\"/images/common/member_title_guide.gif\" />";
		$tit_text = "마이페이지 <img src=\"../../images/common/home_bl.gif\"/> 이용약관";
	}




	include "../share/inc/php/header.php";
?>




		<!-- [Content] start -->
		<div id="Content">
			<div id="account_site">
				<ul>
					<li><img src="../../images/common/home_dot.gif" width="4" height="4" /></li>
					<li>홈 <img src="../../images/common/home_bl.gif"/> <?=$tit_text?></li>
				</ul>
			</div>
			<div id="account_title"></div>





			<div id="account_content">
				<!--[타이틀]-->
				<?=$tit_img?>
				<!--[타이틀 끝]-->

				<table width="653" border="0" cellpadding="0" cellspacing="0" class="mgt20">
					<tr>
						<td>

										<? if($subVal=="5") { ?>
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td valign="top"><img src="/_skin/member/default/images/agree_tit01.gif"></td>
											</tr>
										</table>
										<table width="100%" border="0" cellspacing="10" cellpadding="10" bgcolor="#D5D5D5">
											<tr>
												<td valign="top" bgcolor="#FFFFFF" height="400">
													<!-- 이용약관 -->
													<div style="width:100%; height:400px; overflow-y:scroll; overflow-x:hidden;" class="font_small">
														<?=nl2br($sc_agree);?>
													</div>
													<!-- 이용약관 -->
												</td>
											</tr>
										</table>
										<? } ?>
										<? if($subVal=="3") { ?>
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td valign="top"><img src="/_skin/member/default/images/agree_tit02.gif"></td>
											</tr>
										</table>
										<table width="100%" border="0" cellspacing="10" cellpadding="10" bgcolor="#D5D5D5">
											<tr>
												<td valign="top" bgcolor="#FFFFFF" height="400">
													<!-- 개인정보보호정책 -->
													<div style="width:100%; height:400px; overflow-y:scroll; overflow-x:hidden'" class="font_small">
														<?=nl2br($sc_policy);?>
													</div>
													<!-- //개인정보보호정책 -->
												</td>
											</tr>
										</table>
										<? } ?>

						</td>
					</tr>
				</table>
			</div>







<? include "../share/inc/php/footer.php"; ?>
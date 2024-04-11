<?php
	/*---------------------------------------------------------------------------------*/
	/*  ● 작업자 : 전한솔 팀장
	/*  ● 작성일자 : 2009.10.27
	/*  ● 회사소개   => company
	/*  ● 제품소개      => product
	/*  ● 고객센터    => costomer
	/*  ● 알림마당    => news
	/*  ● 인트라넷      => intranet
	/*  ● 회원가입          => join
	/*  ● 사이트맵      => sitemap
	/*---------------------------------------------------------------------------------*/

	$menuName = "member";      //=> 1Dpeth 네이c밍
	$subVal = 2;                 //=> 2Dpeth 넘버링
	$thVal = 1;                  //=> 3Dpeth 넘버링
	$fhVal = 1;                  //=> 4Dpeth 넘버링

	include "../share/inc/php/header.php";
?>




		<!-- [Content] start -->
		<div id="Content">
			<div id="account_site">
				<ul>
					<li><img src="../../images/common/home_dot.gif" width="4" height="4" /></li>
					<li>홈 <img src="../../images/common/home_bl.gif"/> MEMBER <img src="../../images/common/home_bl.gif"/> 회원가입</li>
				</ul>
			</div>
			<div id="account_title"></div>





			<div id="account_content">
				<!--[타이틀]-->
				<img src="../../images/common/member_title_join.gif"/>
				<!--[타이틀 끝]-->

				<table width="653" border="0" cellpadding="0" cellspacing="0" class="mgt20">
					<tr>
						<td>

							<!-- Content -->
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td valign="top" height="58"><img src="<?=$url_skin_member?>images/join_title.gif"></td>
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="156"><img src="<?=$url_skin_member?>images/agree_tab01.gif"></td>
									<td align="center"><img src="<?=$url_skin_member?>images/agree_tab_arrow.gif"></td>
									<td width="156"><img src="<?=$url_skin_member?>images/agree_tab02.gif"></td>
									<td align="center"><img src="<?=$url_skin_member?>images/agree_tab_arrow.gif"></td>
									<td width="156"><img src="<?=$url_skin_member?>images/agree_tab03_over.gif"></td>
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td height="20">&nbsp;</td>
								</tr>
								<tr>
									<td height="1" background="<?=$url_skin_member?>images/login_dot.gif"></td>
								</tr>
								<tr>
									<td height="20">&nbsp;</td>
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="10" cellpadding="0" bgcolor="#D5D5D5">
								<tr>
									<td align="center" valign="middle" bgcolor="#FFFFFF" height="245" style="padding:40 0 40 0">
										<table width="490" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="177" height="165" valign="top"><img src="<?=$url_skin_member?>images/join_end_img.gif"></td>
												<td valign="top">
													<table width="100%" border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td height="6"></td>
														</tr>
														<tr>
															<td valign="top"><img src="<?=$url_skin_member?>images/join_end_t01.gif"></td>
														</tr>
														<tr>
															<td height="15"></td>
														</tr>
														<tr>
															<td valign="top"><img src="<?=$url_skin_member?>images/join_end_t02.gif"></td>
														</tr>
														<tr>
															<td height="37"></td>
														</tr>
														<tr>
															<td valign="top"><a href="<?=$url_index?>"><img src="<?=$url_skin_member?>images/join_end_btn.gif"></a></td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
								  </td>
								</tr>
							</table>
							<!-- //Content -->



						</td>
					</tr>
				</table>
			</div>







<? include "../share/inc/php/footer.php"; ?>
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
	$subVal = 1;                 //=> 2Dpeth 넘버링
	$thVal = 1;                  //=> 3Dpeth 넘버링
	$fhVal = 1;                  //=> 4Dpeth 넘버링

	include "../share/inc/php/header.php";
?>




		<!-- [Content] start -->
		<div id="Content">
			<div id="account_site">
				<ul>
					<li><img src="../../images/common/home_dot.gif" width="4" height="4" /></li>
					<li>홈 <img src="../../images/common/home_bl.gif"/> MEMBER <img src="../../images/common/home_bl.gif"/> 로그인</li>
				</ul>
			</div>
			<div id="account_title"></div>





			<div id="account_content">
				<!--[타이틀]-->
				<img src="../../images/common/member_title_login.gif"/>
				<!--[타이틀 끝]-->

				<table width="653" border="0" cellpadding="0" cellspacing="0" class="mgt20">
					<tr>
						<td>

							<form name="form1" action="" method="post" onSubmit="return checkForm();">
							<input type="hidden" name="act" value="ok">
							<input type="hidden" name="url" value="<?=$url?>">
							<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
								<tr>
									<td valign="top" height="58"><img src="<?=$url_skin_member?>images/login_title.gif"></td>
								</tr>
								<tr>
									<td height="10" bgcolor="#D5D5D5"></td>
								</tr>
								<tr>
									<td height="195">
										<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
											<tr>
												<td width="180" height="195" background="<?=$url_skin_member?>images/login_img.gif">&nbsp;</td>
												<td align="center">
													<table width="288" height="53" border="0" cellpadding="0" cellspacing="0">
														<tr>
															<td width="63" height="29" valign="top"><img src="<?=$url_skin_member?>images/login_txt_id.gif"></td>
															<td width="167" valign="top">
																<input type="text" name="user" tabindex="101" maxlength="16" <?=$OnlyEng?> class="login_input" />
															</td>
															<td width="58" rowspan="2" valign="top"><input type="image" src="<?=$url_skin_member?>images/login_btn_login.gif" tabindex="103"></td>
														</tr>
														<tr>
															<td height="24" valign="top"><img src="<?=$url_skin_member?>images/login_txt_pw.gif"></td>
															<td valign="top">
																<input type="password" name="pass" tabindex="102" class="login_input" />
															</td>
														</tr>
													</table>
													<table width="288" border="0" cellpadding="0" cellspacing="0">
														<tr>
															<td height="22">&nbsp;</td>
														</tr>
														<tr>
															<td height="1" background="<?=$url_skin_member?>images/login_dot.gif"></td>
														</tr>
														<tr>
															<td height="21">&nbsp;</td>
														</tr>
													</table>
													<table width="288" border="0" cellpadding="0" cellspacing="0">
														<tr>
															<td width="209" height="25" valign="top"><img src="<?=$url_skin_member?>images/login_txt01.gif"></td>
															<td valign="top"><a href="javascript:UserJoinGo();"><img src="<?=$url_skin_member?>images/login_btn_join.gif" border="0"></a></td>
														</tr>
														<tr>
															<td width="209" height="19" valign="top"><img src="<?=$url_skin_member?>images/login_txt02.gif"></td>
															<td valign="top"><a href="javascript:UserFindIDGo();"><img src="<?=$url_skin_member?>images/login_btn_find.gif" border="0"></a></td>
														</tr>
													</table>
												</td>
												<td width="10" bgcolor="#D5D5D5">&nbsp;</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td height="10" bgcolor="#D5D5D5"></td>
								</tr>
							</table>
							</form>


						</td>
					</tr>
				</table>
			</div>







<? include "../share/inc/php/footer.php"; ?>
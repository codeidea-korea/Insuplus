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
							<form name="AgreeForm" method="post" action="" onSubmit="return registGo();">


							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td valign="top" height="58"><img src="<?=$url_skin_member?>images/join_title.gif"></td>
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="156"><img src="<?=$url_skin_member?>images/agree_tab01_over.gif"></td>
									<td align="center"><img src="<?=$url_skin_member?>images/agree_tab_arrow.gif"></td>
									<td width="156"><img src="<?=$url_skin_member?>images/agree_tab02.gif"></td>
									<td align="center"><img src="<?=$url_skin_member?>images/agree_tab_arrow.gif"></td>
									<td width="156"><img src="<?=$url_skin_member?>images/agree_tab03.gif"></td>
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
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td valign="top"><img src="<?=$url_skin_member?>images/agree_tit01.gif"></td>
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="10" cellpadding="10" bgcolor="#D5D5D5">
								<tr>
									<td valign="top" bgcolor="#FFFFFF" height="195">
										<!-- 이용약관 -->
										<div style="width:100%; height:195px; overflow-y:scroll; overflow-x:hidden';" class="font_small">
											<?=nl2br($sc_agree);?>
										</div>
										<!-- //이용약관 -->
									</td>
								</tr>
							</table>


							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td height="20">&nbsp;</td>
								</tr>
								<tr>
									<td valign="top"><img src="<?=$url_skin_member?>images/agree_tit02.gif"></td>
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="10" cellpadding="10" bgcolor="#D5D5D5">
								<tr>
									<td valign="top" bgcolor="#FFFFFF" height="195">
										<!-- 개인정보보호정책 -->
										<div style="width:100%; height:195px; overflow-y:scroll; overflow-x:hidden'" class="font_small">
											<?=nl2br($sc_policy);?>
										</div>
										<!-- //개인정보보호정책 -->
									</td>
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td height="8"></td>
								</tr>
								<tr>
									<td valign="top" align="right">
										<table border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td valign="top" width="230" style="padding-top:3px"><img src="<?=$url_skin_member?>images/agree_txt01.gif"></td>
												<td width="20"></td>
												<td valign="top"><input type="radio" name="u_agree" value="1"></td>
												<td valign="top" style="padding-top:3px"><img src="<?=$url_skin_member?>images/agree_txt02.gif"></td>
												<td valign="top"><input type="radio" name="u_agree" value="2" checked></td>
												<td valign="top" style="padding-top:3px"><img src="<?=$url_skin_member?>images/agree_txt03.gif"></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>

							<? if ( $sc_member_company == "Y" ) { ?>
							<!-- 회원구분 -->
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td height="20">&nbsp;</td>
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="1" cellpadding="9" bgcolor="#D5D5D5">
								<tr>
									<td valign="top" bgcolor="#FFFFFF">
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="107"  height="28" align="center" bgcolor="#E5E5E5"><img src="<?=$url_skin_member?>images/agree_txt_type01.gif"></td>
												<td align="center">
													<table border="0" cellspacing="0" cellpadding="0">
														<tr>
															<td valign="top"><input type="radio" name="u_gubun" value="1" checked></td>
															<td valign="top" width="70" style="padding-top:3px"><img src="<?=$url_skin_member?>images/agree_txt_type02.gif"></td>
															<td valign="top"><input type="radio" name="u_gubun" value="2"></td>
															<td valign="top" width="87" style="padding-top:3px"><img src="<?=$url_skin_member?>images/agree_txt_type03.gif"></td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<!-- //회원구분 -->
							<? } else {  ?>
								<input type="hidden" name="u_gubun" value="N">
							<? } ?>

							<? if ($sc_member_name_check == "Y") { ?>
							<!-- 실명인증 -->
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td height="10"></td>
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="1" cellpadding="9" bgcolor="#D5D5D5">
								<tr>
									<td valign="top" bgcolor="#FFFFFF">
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="107"  height="77" align="center" bgcolor="#E5E5E5"><img src="<?=$url_skin_member?>images/agree_txt_rn01.gif"></td>
												<td align="center">
													<table border="0" cellpadding="0" cellspacing="0">
														<tr>
															<td width="83" height="29" valign="top"><img src="<?=$url_skin_member?>images/agree_txt_name.gif"></td>
															<td width="167" valign="top"><input name="login_id" type="input" class="login_input" tabindex="101"></td>
															<td width="58" rowspan="2" valign="top"><input type="image" src="<?=$url_skin_member?>images/agree_btn_rn.gif" tabindex="3"></td>
														</tr>
														<tr>
															<td height="24" valign="top"><img src="<?=$url_skin_member?>images/agree_txt_jumin.gif"></td>
															<td valign="top">
																<table border="0" cellpadding="0" cellspacing="0">
																	<tr>
																		<td valign="top"><input name="f_jumin01" type="text" class="login_input2" maxlength="6" tabindex="102"></td>
																		<td valign="top"><img src="<?=$url_skin_member?>images/agree_txt_dash.gif"></td>
																		<td valign="top"><input name="f_jumin02" type="password" class="login_input2" maxlength="7" tabindex="103"></td>
																	</tr>
																</table>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td height="15"></td>
								</tr>
								<tr>
									<td><img src="<?=$url_skin_member?>images/agree_txt_rn02.gif"></td>
								</tr>
							</table>
							<!-- //실명인증 -->
							<? } else { ?>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td height="50" align="center">
										<table width="100" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="71" valign="top"><input type="image" src="<?=$url_skin_member?>images/agree_btn_join.gif"  border="0" /></td>
												<td width="29" valign="bottom"><a href="<?=$url_index?>"><img src="<?=$url_skin_member?>images/agree_btn_cancle.gif" hspace="5" border="0" style="margin-top:1px;" /></a></td>
											</tr>
										</table>
									</td>
								</tr>
							</table>

							<? } ?>


							</form>
							<!-- //Content -->



						</td>
					</tr>
				</table>
			</div>







<? include "../share/inc/php/footer.php"; ?>
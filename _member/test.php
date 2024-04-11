

<script>
	document.title = ":: 해우소한의원 ::";
	var GlobalLoginURL = "/member/login.php?url=/member/find_id.php";
	var GlobalLogoutURL = "/member/logout.php";
	var GlobalJoinURL = "/member/agree.php";
	var GlobalModifyURL = "/member/UserModify.php";
	var GlobalFindIDURL = "/member/find_id.php";
	var GlobalFindPWURL = "/member/find_pw.php";
	var GlobalDropURL = "/member/UserDrop.php";
	var GlobalAgree = "/member/AgreeList.php";
	var GlobalWarningURL = "";
	var GlobalPguideURL = "";
	var GlobalTguideURL = "";
</script>

<style>
	/*캘린더*/
	input.text_cal {
		behavior:url("/_util/calendar/htc_calendar2.htc");
		color:#666666;
		height:21px;
		border:1px #E5E5E5 solid;
		font-size:12px;
		font-family:"Gulim";
		letter-spacing:0px;
		text-align:center;
		ime-mode:disabled;
		width:122px;
	}
</style>


<html>
<head>
<title>ID/PW FIND</title>
<style>
body, br, p, table, tr, td, input, form, select, div, layer, iframe
{
	font-family: "Gulim";
	font-size:12px ;
	color:#4D4D4D;
	line-height:17px
}

body
{
	margin:0 0 0 0;
	background-color:#FFFFFF;
	scrollbar-3dlight-color:#F5F5F5;
	scrollbar-arrow-color:#FFFFFF;
	scrollbar-base-color:#CCCCCC;
	scrollbar-darkshadow-color:#F5F5F5;
	scrollbar-face-color:#DDDDDD;
	scrollbar-track-color: #EFEFEF;
	scrollbar-highlight-color:#D7D7D7;
	scrollbar-shadow-color:#D7D7D7;
}

form,img { border:0px; margin: 0 0 0 0; }

a:link {   color:#2D2D2D; text-decoration: none}
a:visited {color:#2D2D2D;  text-decoration: none}
a:active { color:#2D2D2D; text-decoration: none}
a:hover { color:#2D2D2D; text-decoration: none}

a{selector-dummy:expression(this.hideFocus=true); }
 .input_check {font-family:Dotum;font-size:11px;color:#404040;border:1px solid #D5D5D5;width:107px;height:17px}

</style>
</head>
<body topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
<table width="340" height="317" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="top" style="padding:16 15 15 15" background="/_skin/member/default/images/id_find_bg.gif">
			<table width="310" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="50" valign="top"><img src="/_skin/member/default/images/id_find_title.gif"></td>
					<td width="22" valign="top"><img src="/_skin/member/default/images/id_check_x.gif" onClick="window.close()" style="cursor:hand" alt="CLOSE"></td>
				</tr>
			</table>
			<form method="post" action="http://demo.bluecarpet.co.kr/dc_member/member_find_id_process.php" name="frmMember" onSubmit="return idFind();">
			<table width="310" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="8" background="/_skin/member/default/images/id_check_box_t.gif"></td>
				</tr>
				<tr>
					<td height="9" background="/_skin/member/default/images/id_check_box_bg.gif"></td>
				</tr>
				<tr>
					<td height="211" background="/_skin/member/default/images/id_check_box_bg.gif" valign="top" align="center">
						<table width="276" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="95" height="26" background="/_skin/member/default/images/id_find_tab_bg.gif"><img src="/_skin/member/default/images/id_find_tab01_over.gif" name="tab01"></td>
								<td width="93" background="/_skin/member/default/images/id_find_tab_bg.gif"><a href="http://demo.bluecarpet.co.kr/dc_member/member_find_pw.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab02','','/_skin/member/default/images/id_find_tab02_over.gif',1)"><img src="/_skin/member/default/images/id_find_tab02.gif" name="tab02"></a></td>
								<td background="/_skin/member/default/images/id_find_tab_bg.gif">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="3" height="14"></td>
							</tr>
						</table>
						<table width="276" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td height="7" background="/_skin/member/default/images/id_check_box02_t.gif"></td>
							</tr>
							<tr>
								<td height="95" background="/_skin/member/default/images/id_check_box02_bg.gif" style="padding:0 0 0 26">
									<table width="226" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td valign="top" height="20"><img src="/_skin/member/default/images/id_find_txt.gif"></td>
										</tr>
										<tr>
											<td valign="top">
												<table width="226" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td width="67" height="20" valign="top" style="padding:1 0 0 0"><img src="/_skin/member/default/images/id_find_name.gif"></td>
														<td valign="top"><input type="text" name="f_name" class="input_check" style="width:157px" tabindex="1"></td>
													</tr>
													<tr>
														<td height="20" valign="top" style="padding:1 0 0 0"><img src="/_skin/member/default/images/id_find_jumin.gif"></td>
														<td valign="top">
															<table border="0" cellspacing="0" cellpadding="0">
																<tr>
																	<td width="74" valign="top"><input type="text" maxlength="6" name="f_jumin1" class="input_check" style="width:74px" tabindex="2" onKeyPress="check_digit();" onKeyUp="check();"></td>
																	<td width="9" valign="top" style="padding:1 0 0 0"><img src="/_skin/member/default/images/id_find_dash.gif"></td>
																	<td width="74" valign="top"><input type="password" maxlength="7" name="f_jumin2" class="input_check" style="width:74px" tabindex="3" onKeyPress="check_digit();"></td>
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
							<tr>
								<td height="7" background="/_skin/member/default/images/id_check_box02_b.gif"></td>
							</tr>
						</table>
						<table width="276" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td height="20">&nbsp;</td>
							</tr>
							<tr>
								<td valign="top" align="center"><input type="image" src="/_skin/member/default/images/id_find_btn.gif"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="8" background="/_skin/member/default/images/id_check_box_b.gif"></td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
</body>
</html>

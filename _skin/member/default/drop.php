<link href="/css/bluecarpet.css" rel="stylesheet" type="text/css">
<body topmargin="0" leftmargin="0" marginwidth="0" marginheight="0" onLoad="setFocusmemberdrop();">
<table width="340" height="339" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="top" style="padding:16 15 15 15" background="<?=$sc_url_skin_member?>images/m_drop_bg.gif">
			<table width="310" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="50" valign="top"><img src="<?=$sc_url_skin_member?>images/m_drop_title.gif"></td>
					<td width="22" valign="top"><img src="<?=$sc_url_skin_member?>images/id_check_x.gif" onClick="window.close()" style="cursor:hand" alt="CLOSE"></td>
				</tr>
			</table>
			<form method="post" action="<?=$m_skin_url?>member_drop_process.php" name="frmMember" onSubmit="return memberDropCheck();" style="padding:0;margin:0;border:0">
			<input type="hidden" name="f_id" value="<?=$_SESSION['session_id']?>">
			<table width="310" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="8" background="<?=$sc_url_skin_member?>images/id_check_box_t.gif"></td>
				</tr>
				<tr>
					<td height="9" background="<?=$sc_url_skin_member?>images/id_check_box_bg.gif"></td>
				</tr>
				<tr>
					<td height="211" background="<?=$sc_url_skin_member?>images/id_check_box_bg.gif" valign="top" align="center">
						<table width="276" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td height="7" background="<?=$sc_url_skin_member?>images/id_check_box02_t.gif"></td>
							</tr>
							<tr>
								<td height="95" background="<?=$sc_url_skin_member?>images/id_check_box02_bg.gif" style="padding:7 0 0 26" valign="top">
									<table width="226" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td valign="top" height="20"><img src="<?=$sc_url_skin_member?>images/m_drop_txt01.gif"></td>
										</tr>
										<tr>
											<td valign="top">
												<table width="226" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td width="67" height="20" valign="top" style="padding:1 0 0 0"><img src="<?=$sc_url_skin_member?>images/id_find_id.gif"></td>
														<td class="input_txt"> <font color="#486D8F"><b><?=$_SESSION['session_id']?></b></font></td>
													</tr>
													<tr>
														<td height="20" valign="top" style="padding:1 0 0 0"><img src="<?=$sc_url_skin_member?>images/m_drop_pw.gif"></td>
														<td valign="top"><input type="password" name="f_pw" class="input_check" style="width:157px" tabindex="1"></td>
													</tr>
													<tr>
														<td height="20" valign="top" style="padding:1 0 0 0"><img src="<?=$sc_url_skin_member?>images/id_find_name.gif"></td>
														<td valign="top"><input type="text" name="f_name" class="input_check" style="width:157px" tabindex="2"></td>
													</tr>
													<tr>
														<td height="32" valign="top" style="padding:1 0 0 0"><img src="<?=$sc_url_skin_member?>images/id_find_jumin.gif"></td>
														<td valign="top">
															<table border="0" cellspacing="0" cellpadding="0">
																<tr>
																	<td width="74" valign="top"><input type="text" maxlength="6" name="f_jumin1" class="input_check" style="width:74px" tabindex="3" onKeyPress="check_digit();" onKeyUp="check();"></td>
																	<td width="9" valign="top" style="padding:1 0 0 0"><img src="<?=$sc_url_skin_member?>images/id_find_dash.gif"></td>
																	<td width="74" valign="top"><input type="password" maxlength="7" name="f_jumin2" class="input_check" style="width:74px" tabindex="4" onKeyPress="check_digit();"></td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td valign="top" height="38"><img src="<?=$sc_url_skin_member?>images/m_drop_txt02.gif"></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td height="7" background="<?=$sc_url_skin_member?>images/id_check_box02_b.gif"></td>
							</tr>
						</table>
						<table width="276" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td height="20">&nbsp;</td>
							</tr>
							<tr>
								<td valign="top" align="center"><input type="image" src="<?=$sc_url_skin_member?>images/m_drop_btn.gif">&nbsp;<img src="<?=$sc_url_skin_member?>images/m_drop_btn_cancle.gif" onClick="window.close()" style="cursor:hand"></td>
							</tr>
							<tr>
								<td height="14"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="8" background="<?=$sc_url_skin_member?>images/id_check_box_b.gif"></td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>
</body>
</html>
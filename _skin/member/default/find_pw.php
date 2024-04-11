

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
<script language="JavaScript">
<!--
	function resizePopup(w,h) {
		document.body.style.overflow='hidden';
		var clintAgent = navigator.userAgent;
		if ( clintAgent.indexOf("MSIE") != -1 ) {
			window.resizeBy(w-document.body.clientWidth, h-document.body.clientHeight);
		} else {
			window.resizeBy(w-window.innerWidth, h-window.innerHeight);
		}
	}
//-->
</script>

<script>

	 window.onload = function () {
		 resizePopup(340, 317);
	 }
</script>



</head>
<body topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
<table width="340" height="317" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="top" style="padding:16 15 15 15" background="<?=$url_skin_member?>images/id_find_bg.gif">
			<table width="310" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="50" valign="top"><img src="<?=$url_skin_member?>images/pw_find_title.gif"></td>
					<td width="22" valign="top"><img src="<?=$url_skin_member?>images/id_check_x.gif" onClick="window.close()" style="cursor:hand" alt="CLOSE"></td>
				</tr>
			</table>

			<table width="310" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="8" background="<?=$url_skin_member?>images/id_check_box_t.gif"></td>
				</tr>
				<tr>
					<td height="9" background="<?=$url_skin_member?>images/id_check_box_bg.gif"></td>
				</tr>
				<tr>
					<td height="211" background="<?=$url_skin_member?>images/id_check_box_bg.gif" valign="top" align="center">
						<table width="276" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="95" height="26" background="<?=$url_skin_member?>images/id_find_tab_bg.gif"><a href="http://demo.bluecarpet.co.kr/dc_member/member_find_id.php" onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('tab01','','<?=$url_skin_member?>images/id_find_tab01_over.gif',1)"><img src="<?=$url_skin_member?>images/id_find_tab01.gif" name="tab01"></a></td>
								<td width="93" background="<?=$url_skin_member?>images/id_find_tab_bg.gif"><img src="<?=$url_skin_member?>images/id_find_tab02_over.gif" name="tab02"></td>
								<td background="<?=$url_skin_member?>images/id_find_tab_bg.gif">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="3" height="14"></td>
							</tr>
						</table>




<? if ( $act == "ok" ) {?>
						<table width="276" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td height="7" background="<?=$url_skin_member?>images/id_check_box02_t.gif"></td>
							</tr>
							<tr>
								<td height="95" background="<?=$url_skin_member?>images/id_check_box02_bg.gif" style="padding:0 0 0 26">
									<table width="226" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td valign="top">
												<font color="#486D8F"><b>회원님의 이메일로 비밀번호를 발송해드렸습니다.</b></font>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td height="7" background="<?=$url_skin_member?>images/id_check_box02_b.gif"></td>
							</tr>
						</table>

<? } else { ?>
<script>
	function next_go() {
		ff = document.FindForm;
		if (!ff.u_id.value) {
			alert("아이디를 입력하여 주십시오.");
			ff.u_id.focus();
			return false;
		}

		if (!ff.u_name.value) {
			alert("이름을 입력하여 주십시오.");
			ff.u_name.focus();
			return false;
		}


		<? if ( $find_type == "jumin" ) { ?>
			if (!ff.u_jumin1.value || !ff.u_jumin2.value) {
				alert("주민등록번호를 입력하여 주십시오.");
				ff.u_jumin1.focus();
				return false;
			}
		<? } else { ?>
			if (!ff.u_email.value ) {
				alert("E-mail 주소를 입력하여 주십시오.");
				ff.u_email.focus();
				return false;
			}
		<? } ?>

		ff.action = "<?=$cf_site_url_ssl?>/member/find_pw.php";

	}
</script>
<form method="post" action="" name="FindForm" onSubmit="return next_go();" style="margin:0px;">
<input type="hidden" name="act" value="ok">




						<table width="276" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td height="7" background="<?=$url_skin_member?>images/id_check_box02_t.gif"></td>
							</tr>
							<tr>
								<td height="95" background="<?=$url_skin_member?>images/id_check_box02_bg.gif" style="padding:0 0 0 26">
									<table width="226" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td valign="top" height="20"><img src="<?=$url_skin_member?>images/pw_find_txt.gif"></td>
										</tr>
										<tr>
											<td valign="top">
												<table width="226" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td width="67" height="20" valign="top" style="padding:1 0 0 0"><img src="<?=$url_skin_member?>images/id_find_id.gif"></td>
														<td valign="top">
															<input type="text" name="u_id" <?=$OnlyEng?> tabindex="1" maxlength="20" class="input_check" style="width:157px">
														</td>
													</tr>
													<tr>
														<td height="20" valign="top" style="padding:1 0 0 0"><img src="<?=$url_skin_member?>images/id_find_name.gif"></td>
														<td valign="top">
															<input type="text" name="u_name" tabindex="2" class="input_check" style="width:157px">
														</td>
													</tr>
													<tr>
														<td height="20" valign="top" style="padding:1 0 0 0"><img src="<?=$url_skin_member?>images/id_find_jumin.gif"></td>
														<td valign="top">

															<? if ( $find_type == "jumin" ) { ?>
															<input type="text" name="u_jumin1" maxlength="6" tabindex="3" class="input_check" style="width:71px">
															-
															<input type="password" name="u_jumin2" maxlength="7" tabindex="4" class="input_check" style="width:72px">

															<? } else { ?>

															<input type="text" name="u_email" class="input_check" style="width:157px" tabindex="2" <?=$OnlyEng?>>

															<? } ?>

														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td height="7" background="<?=$url_skin_member?>images/id_check_box02_b.gif"></td>
							</tr>
						</table>
						<table width="276" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td height="20">&nbsp;</td>
							</tr>
							<tr>
								<td valign="top" align="center"><input type="image" src="<?=$url_skin_member?>images/pw_find_btn.gif" tabindex="5"></td>
							</tr>
						</table>

</form>

<? } ?>


					</td>
				</tr>
				<tr>
					<td height="8" background="<?=$url_skin_member?>images/id_check_box_b.gif"></td>
				</tr>
			</table>




		</td>
	</tr>
</table>
</body>
</html>

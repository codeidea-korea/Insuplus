<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
	if ( getLen($ss_u_id) == 0 ) {
		alert_close($msg_login_go);
	}

	$act = REQSTR($_POST[act], "");

	if ( getLen($act) > 0 ) {

		$u_id = REQSTR($_POST[u_id], "");
		$u_pw = REQSTR($_POST[u_pw], "");
		$u_jumin1 = REQSTR($_POST[u_jumin1], "");
		$u_jumin2 = REQSTR($_POST[u_jumin2], "");

		isnull($u_id);
		isnull($u_pw);
		isnull($u_jumin1);
		isnull($u_jumin2);

		$SQL = "
			select count(u_idx)
			from
				tbl_user
			where
				u_id = '".$u_id."'
				and u_pw = '".base64_encode($u_pw)."'
				and u_jumin1 = '".$u_jumin1."'
				and u_jumin2 = '".base64_encode($u_jumin2)."'
		";
		$result = $dbcon -> getCount($SQL);
//		echo $SQL."<BR>";
//		echo "result : " .$result."<BR>";

		if ( !$result ) {
			alert_back("정보가 일치하지 않습니다.");
		}

		else {
			MemberDeleteProcess($u_id, 2);
			LogoutProcess();
			alert_close('탈퇴되었습니다.\n이용해 주셔서 감사합니다.' , 1, $url_index );

		}
	}

	//include_once $path_skin_member."drop.php";
?>
<script>
	function DropGo() {
		ff = document.DropForm;
		if (!ff.u_id.value) {
			alert("아이디를 입력하여 주십시오.");
			return false;
		}

		if (!ff.u_pw.value) {
			alert("아이디를 입력하여 주십시오.");
			ff.u_pw.focus();
			return false;
		}

		if (!ff.u_name.value) {
			alert("이름을 입력하여 주십시오.");
			ff.u_name.focus();
			return false;
		}

		if (!ff.u_jumin1.value || !ff.u_jumin2.value) {
			alert("주민등록번호를 입력하여 주십시오.");
			ff.u_jumin1.focus();
			return false;
		}

	}
</script>
<body topmargin="0" leftmargin="0" marginwidth="0" marginheight="0">
<form name="DropForm"  method="post" action="<?=$PHP_SELF?>" onSubmit="return DropGo();" style="padding:0;margin:0;border:0">
<input type="hidden" name="u_id" value="<?=$ss_u_id?>">
<input type="hidden" name="act" value="ok">

<table width="340" height="339" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center" valign="top" style="padding:16 15 15 15" background="<?=$url_skin_member?>images/m_drop_bg.gif">
			<table width="310" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="50" valign="top"><img src="<?=$url_skin_member?>images/m_drop_title.gif"></td>
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
								<td height="7" background="<?=$url_skin_member?>images/id_check_box02_t.gif"></td>
							</tr>
							<tr>
								<td height="95" background="<?=$url_skin_member?>images/id_check_box02_bg.gif" style="padding:7 0 0 26" valign="top">
									<table width="226" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td valign="top" height="20"><img src="<?=$url_skin_member?>images/m_drop_txt01.gif"></td>
										</tr>
										<tr>
											<td valign="top">

												<table width="226" border="0" cellspacing="0" cellpadding="0">
													<tr>
														<td width="67" height="20" valign="top" style="padding:1 0 0 0"><img src="<?=$url_skin_member?>images/id_find_id.gif"></td>
														<td class="input_txt"> <font color="#486D8F"><b><?=$ss_u_id?></b></font></td>
													</tr>
													<tr>
														<td height="20" valign="top" style="padding:1 0 0 0"><img src="<?=$url_skin_member?>images/m_drop_pw.gif"></td>
														<td valign="top"><input type="password" name="u_pw" class="input_check" style="width:157px" tabindex="1"></td>
													</tr>
													<tr>
														<td height="20" valign="top" style="padding:1 0 0 0"><img src="<?=$url_skin_member?>images/id_find_name.gif"></td>
														<td valign="top"><input type="text" name="u_name" class="input_check" style="width:157px" tabindex="2"></td>
													</tr>
													<tr>
														<td height="32" valign="top" style="padding:1 0 0 0"><img src="<?=$url_skin_member?>images/id_find_jumin.gif"></td>
														<td valign="top">
															<table border="0" cellspacing="0" cellpadding="0">
																<tr>
																	<td width="74" valign="top"><input type="text" maxlength="6" name="u_jumin1" class="input_check" style="width:74px" tabindex="3" onKeyUp="check(this,6,this.form.u_jumin2);" <?=$OnlyNumber?>></td>
																	<td width="9" valign="top" style="padding:1 0 0 0"><img src="<?=$url_skin_member?>images/id_find_dash.gif"></td>
																	<td width="74" valign="top"><input type="password" maxlength="7" name="u_jumin2" class="input_check" style="width:74px" tabindex="4" <?=$OnlyNumber?>></td>
																</tr>
															</table>
														</td>
													</tr>
												</table>
											</td>
										</tr>
										<tr>
											<td valign="top" height="38"><img src="<?=$url_skin_member?>images/m_drop_txt02.gif"></td>
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
								<td valign="top" align="center"><input type="image" src="<?=$url_skin_member?>images/m_drop_btn.gif">&nbsp;<img src="<?=$url_skin_member?>images/m_drop_btn_cancle.gif" onClick="window.close()" style="cursor:hand"></td>
							</tr>
							<tr>
								<td height="14"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="8" background="<?=$url_skin_member?>images/id_check_box_b.gif"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

</form>

</body>
<script>

//첫번째 6자리 입력시 다음 필드로 자동으로 넘어가기
function check(obj, leng, nextobj)
{
	if(obj.value.length >= leng)
		nextobj.focus();
}
</script>
<?
	$dbcon -> dbcon_close();
?>

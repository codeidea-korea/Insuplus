<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$u_idx = REQSTR($u_idx, "");
	
	$sql = " SELECT seq, partnership_name From tbl_board_partner WHERE partnership_code != 'insuplus' ORDER BY seq ASC ";
	$rs_partner = $dbcon->query($sql);
	
	$partner_data = array();
	while($row_partner = $dbcon->fetch_array($rs_partner)) {
		$partner_data[] = $row_partner;
	}

	// 정보 수정시
	if ( strlen($u_idx) > 0 ) {
//		if ( $ss_u_level < $auth_admin && $ss_u_idx != $u_idx ) {
//			alert_back($msg_login_auth);
//			exit;
//		}

		$SQL = "
			select
				*
			from
							tbl_user A
			where
				A.u_idx = '".$u_idx."'
			limit 0, 1
		";

		$result = $dbcon -> query($SQL);
		$rows = $dbcon -> fetch_array($result);

		extract($rows);
		unset($rows);

		$print_u_level = $Arr_u_level[$u_level];

		$print_u_gubun = $Arr_u_gubun[$u_gubun];

		$print_u_state = $Arr_u_state[$u_state];

		$print_email_icon = "";
		if ( $u_email_receipt == "1" ) {
			$print_email_icon = "<img src='".$url_admin."images/a_icon_y.gif' alt='메일수신 동의함'>";
		}
		else {
			$print_email_icon = "<img src='".$url_admin."images/a_icon_n.gif' alt='메일수신 동의안함'>";
		}

		$print_u_sex		= $Arr_u_sex[$u_sex];

		$print_sms_icon = "";
		if ( $u_sms_receipt == "1" ) {
			$print_sms_icon = "<img src='".$url_admin."images/a_icon_y.gif' alt='SMS수신 동의함'>";
		}
		else {
			$print_sms_icon = "<img src='".$url_admin."images/a_icon_n.gif' alt='SMS수신 동의안함'>";
		}

		if ( $u_jumin1 ) {
			$print_u_jumin		= $u_jumin1."-*******";
		}
		else {
			$print_u_jumin		= "미등록";
		}

		$print_u_regdate			= mb_substr($u_regdate, 0, 10);
		$print_u_birth				= mb_substr($u_birth, 0, 10);
		$print_u_marriagedate	= mb_substr($u_marriagedate, 0, 10);
		
	}

	// 신규 가입시
	else {
		if ( $u_idx > 0 && $ss_u_level < $auth_admin ) {
			alert_back($msg_login_auth);
			exit;
		}

		//관리자 접근시...
		if ($ss_u_level >= $auth_admin) {

		}

		// 일반 사용자 접근시...
		else {

			if ($sc_member_company == "Y") {
				$u_gubun = REQSTR($_POST[u_gubun], "");
				//echo $u_gubun."<BR>";
				isnull($u_gubun);
			}
			else {
				$u_gubun = 0;
			}

			if ($sc_member_name_check == "Y") {
				$u_name = REQSTR($_POST[u_name], "");
				$u_jumin1 = REQSTR($_POST[u_jumin1], "");
				$u_jumin2 = REQSTR($_POST[u_jumin2], "");
				isnull($u_name);
				isnull($u_jumin1);
				isnull($u_jumin2);

				if ($u_jumin1 && $u_jumin2) {

					// 주민등록 번호 중복 체크
					if ( MemberCheckJoin($u_jumin1, $u_jumin2, 1) > 0 ) {
						alert_back($msg_error_join_state);
						exit;
					}

					// 주민등록번호의 7번째 한자리 숫자
					$TempBirthYear = substr($u_jumin1, 0, 2);
					$TempBirthMonth = substr($u_jumin1, 2, 2);
					$TempBirthDay = substr($u_jumin1, 4, 2);
					$TempBirthGubun = substr($u_jumin2, 0, 1);

					if ($TempBirthGubun == 9 || $TempBirthGubun == 0) $TempBirthYear = "18" . $TempBirthYear;
					else if ($TempBirthGubun == 1 || $TempBirthGubun == 2) $TempBirthYear = "19" . $TempBirthYear;
					else if ($TempBirthGubun == 3 || $TempBirthGubun == 4) $TempBirthYear = "20" . $TempBirthYear;

					// 성별은 F, M 으로 나눈다.
					// 주민등록번호의 7번째 자리가 홀수이면 남자(Male), 짝수이면 여자(Female)
					$u_sex = $TempBirth % 2 == 0 ? "F" : "M";

				}

				$chk_readonly = "readonly";
			}
		}
	}


?>
<?
	$tm = "member";
	$lm = "";
	include $path_admin."inc/header.php";

	$url_skin_member = "/_skin/member/default/";
 ?>
 <!-- <script language="JavaScript" src="/member/join.js"></script> -->
 <!-- <script language="JavaScript" src="<?=$url_member?>join.js"></script> -->

<form name="JoinForm" method="post" action="member_write_ok.php" enctype='multipart/form-data' onSubmit="return JoinGo()">
<input type="hidden" name="u_gubun" value="<?=$u_gubun?>">


<table width="600" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>

			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><img src="<?=$url_skin_member?>images/m_tit01.gif"></td>
					<td align="right"><img src="<?=$url_skin_member?>images/m_txt.gif"></td>
				</tr>
			</table>

			<input type="hidden" name="u_gubun" value="0">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="m_line_2px">&nbsp;</td>
				</tr>
				<tr>
					<td class="m_s_txt">회원등급</td>
					<td class="m_content">
						<select name="u_level" class="select">
						<? foreach ($Arr_u_level as $key => $val) { ?>
							<? if ( $key > 0 && $key <= $ss_u_level ) { ?>
								<option value="<?=$key?>" <? if ("".$key == $u_level) echo "selected";?>><?=$val?></option>
							<? } ?>
						<? } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<td class="m_s_txt">회원상태</td>
					<td class="m_content">
						<select name="u_state" class="select">
						<?
							foreach ($Arr_u_state as $key => $val) {
								$print_Selected = "";
								 if ( !$u_state ) $u_state = 1;
								 if ("".$key == $u_state) $print_Selected = "selected";

						?>
							<option value="<?=$key?>" <?=$print_Selected?>><?=$val?></option>
						<? } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<td class="m_s_txt">제휴사</td>
					<td class="m_content">
						<select name="u_partner_seq" class="select">
							<option value="">선택해 주세요.</option>
							<? foreach($partner_data as $row) {?>
								<option value="<?=$row["seq"]?>"><?=$row["partnership_name"]?></option>
							<? } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<!-- 아이디 Start -->
				<tr>
					<td class="m_s_txt">ID *</td>
					<td class="m_content">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
							<? if ( strlen($u_idx) > 0 ) { ?>
								<input type="hidden" name="id_chk" value="1">
								<input type="hidden" name="u_idx" value="<?=$u_idx?>">
								<input type="hidden" name="u_id" value="<?=$u_id?>">
								<td class="m_content_txt2"><b><?=$u_id?></b></td>
							<? } else { ?>
								<input type="hidden" name="id_chk" value="0">
								<td width="126" valign="top"><input name="u_id" value="<?=$u_id?>" <?=$OnlyEng?> maxlength="16" type="text" class="m_input" style="width:120px" ></td>
								<td class="m_content_txt2">영문 또는 숫자 6~16 자로 구성됩니다.</td>
							<? } ?>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<!-- 아이디 End -->

				<!-- 비밀번호 Start -->
				<tr>
					<td class="m_s_txt">비밀번호 *</td>
					<td class="m_content">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="126" valign="top"><input name="u_pw" type="password" class="m_input" maxlength="20" style="width:120px"></td>
								<td class="m_content_txt2">총 10 자리 이상 입력하셔야 합니다.</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<td class="m_s_txt">비밀번호 확인 *</td>
					<td class="m_content">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td width="126" valign="top"><input name="u_pw_r" type="password" class="m_input" maxlength="20" style="width:120px"></td>
								<td class="m_content_txt2">확인을 위해 비밀번호를 한 번 더 입력해 주십시오.</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<!-- 비밀번호 End -->

				<!-- 이름 Start -->
				<tr>
					<td class="m_s_txt">이름 *</td>

					<td class="m_content"><input name="u_name" value="<?=$u_name?>" <?=$chk_readonly?> type="text" class="m_input" maxlength="30" style="width:120px"></td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<!-- 이름 End -->

				<!-- 생년월일 Start -->
				<tr>
					<td class="m_s_txt">생년월일 *</td>
					<td class="m_content">
						<input type="text" name="u_birth" value="<?=$print_u_birth?>" <?=$ClassCalendar?> maxlength="10" style="width:120px">
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<!-- 생년월일 End -->

				<!-- 성별 Start -->
				<tr>
					<td class="m_s_txt">성별</td>
					<td class="m_content">
						<input type="radio" name="u_sex" value="M" <? if ( $u_sex == "M" || $u_sex == "" ) {echo "checked";} ?>> 남자
						<input type="radio" name="u_sex" value="F" <? if ( $u_sex == "F" ) {echo "checked";} ?>> 여자
					</td>
				</tr>
                <tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
                <tr>
					<td class="m_s_txt">접속 가능 IP</td>
					<td class="m_content">
                        <input name="u_accessible_ip" value="<?=$u_accessible_ip?>" type="text" class="m_input" maxlength="30" style="width:400px" placeholder="예시와 같이 접속 가능 IP를 공백 없이 ','로 구분하여 입력해 주세요." onkeyup="this.value=this.value.replace(/[^0-9.,*]/g,'');">
                        <br>ex)<span style='letter-spacing: .5px;font-weight: bold;'> '*' 로 작성할 경우 모든 IP에서 접속 가능</span>
                        <br>ex)<span style='letter-spacing: .5px;font-weight: bold;'> 192.168.0.1,192.168.0.2  </span>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<!-- 성별 End -->


				<!-- 전화번호 Start -->
				<tr>
					<td class="m_s_txt">전화번호</td>
					<td class="m_content">
						<? getTel("u_tel", $u_tel1, $u_tel2, $u_tel3, 'm_input' ); ?>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<!-- 전화번호 End -->

				<!-- 휴대폰 Start -->
				<tr>
					<td class="m_s_txt">휴대폰</td>
					<td class="m_content">
						<? getHP("u_hp", $u_hp1, $u_hp2, $u_hp3, 'm_input' ); ?>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<!-- 휴대폰 End -->

				<!-- 이메일 Start -->
				<tr>
					<td class="m_s_txt">이메일 *</td>
					<td class="m_content">
						<? getEmailForm("u_email1", "u_email2", $u_email1, $u_email2, "m_input"); ?>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<!-- 이메일 End -->

				<? if ( strlen($u_idx) > 0 ) { ?>
				<!-- 가입일 Start -->
				<tr>
					<td class="m_s_txt">가입일</td>
					<td class="m_content">
						<?=$print_u_regdate?>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<!-- 가입일 End -->
				<? } ?>
			</table>


			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="80" align="center">
						<?
							if ( $u_idx )
								$ok_img = $url_skin_member."images/m_btn_modify.gif";
							else
								$ok_img = $url_skin_member."images/m_btn_join.gif";
						?>
						<input type="image" src="<?=$ok_img?>" hspace="4">
						<a href="member_list.php"><img src="<?=$url_skin_member?>images/m_btn_cancle.gif" width="76" height="28"></a>
					</td>
				</tr>
			</table>
			</form>

			<script>
				TempGo = 0;
				function JoinGo() {
					// 방지할 ID, 이름
					var noName = new Array("admin", "administrator","webmaster","master","관리자","게시판관리자","어드민","웹마스터","사이트관리자","운영자","사이트운영자");

					var alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
					var numeric = '1234567890';
					var special = ' ~!@#$%^&*()-_=+|\\{}[];:"\'<>,.?\/';

					ff = document.JoinForm;

				<? if ( strlen($u_idx) == 0 ) { ?>
					if (!checkNorm(ff.u_id, '아이디', numeric+alpha+'(-_)+', 16)) {
						ff.u_id.focus();
						return false;
					}

					// if (ff.id_chk.value != 1) {
					// 	error(ff.u_id, "아이디 중복확인 해주세요.");
					// 	return false;
					// }

					// if (ff.u_pw.value < 6) {
					// 	ff.u_pw.value = "";
					// 	ff.u_pw_r.value = "";
					// 	error(ff.u_pw, "비밀번호는 총 10 자리 이상 입력하셔야 합니다.");
					// 	return false;
					// }
					<? } ?>

					if(checkPasswordValidation(ff.u_pw.value) == false) {
						alert("비밀번호는 영문, 숫자, 특수문자를 포함하여 8~15자리로 입력해주세요.");
						return false;
					}

					if (ff.u_pw.value != ff.u_pw_r.value ) {
						ff.u_pw.value = "";
						ff.u_pw_r.value = "";
						error(ff.u_pw, "비밀번호가 일치하지 않습니다.");
						return false;
					}

					if (ff.u_name.value == "") {
						error(ff.u_name, "이름을 입력해 주세요.");
						return false;
					}

//					if ( (!ff.u_tel2.value || !ff.u_tel3.value) && (!ff.u_hp2.value || !ff.u_hp3.value) ) {
//						alert("전화번호와 휴대폰번호중 하나는 필수 입력입니다.");
//						return false;
//					}

					if (ff.u_email1.value == "" || ff.u_email2.value == "") {
						alert("E-mail 주소를 입력하여 주십시오.");
						ff.u_email1.focus();
						return false;
					}

                    if (ff.u_accessible_ip.value == "") {
						error(ff.u_accessible_ip, "접속 가능 IP를 입력해 주세요.");
						return false;
					}
					if (TempGo > 0) {
						alert("<?=$msg_error_touch?>");
						return false;
					}

					//return false;
					TempGo = 1;
					ff.submit();
				}

				function checkPasswordValidation(password) {
					let bool = false;
					const pattern1 = /^(?=.*[a-zA-Z])(?=.*[!@#$%^*+=-])(?=.*[0-9]).{8,15}$/;
					const pattern2 = /^(?=.*[a-zA-Z])(?=.*[0-9]).{10,15}$/;
					const pattern3 = /^(?=.*[a-zA-Z])(?=.*[!@#$%^*+=-]).{10,15}$/;
					const pattern4 = /^(?=.*[!@#$%^*+=-])(?=.*[0-9]).{10,15}$/;

					if (pattern1.test(password)) bool = true;
					else if (pattern2.test(password)) bool = true;
					else if (pattern3.test(password)) bool = true;
					else if (pattern4.test(password)) bool = true;

					return bool;
				}

				function checkNorm(target, cmt, astr, lmax) {
					var i;
					var t = target.value;
					if (t.length == 0 ) {
						alert(cmt + '(을)를 기재하지 않으셨습니다.');
						return false;
					}
					if (lmax != 0 && t.length > lmax) {
						alert(cmt + '는 ' + lmax + '자 이내만 허용합니다.');
						return false;
					}
					if (astr.length >= 1) {
						for (i=0; i<t.length; i++) {
							if( astr.indexOf(t.substring(i,i+1)) < 0 ) {
								alert(cmt + '에 허용할 수 없는 문자가 입력되었습니다.!!!!!!');
								return false;
								break;
							}
						}
						return true;
					}
				}
			</script>
		</td>
	</tr>
</table>

<?
	$dbcon -> dbcon_close();
?>
<? include $path_admin."inc/footer.php"; ?>

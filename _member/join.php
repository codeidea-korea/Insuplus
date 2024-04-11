<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";

//	$dbcon -> setDebug(1);

	$u_idx = REQSTR($u_idx, "");
	$now_year = date("Y");
	$now_month = date("m");
	$now_day = date("d");


	// 정보 수정시
	if ( getLen($ss_u_idx) > 0 ) {
//		if ( $ss_u_level < $auth_admin && $ss_u_idx != $u_idx ) {
//			alert_back($msg_login_auth);
//			exit;
//		}

/*
				u_idx, A.u_id, u_pw, u_name, u_jumin1, u_jumin2, u_email1, u_email2, u_email_receipt, u_sms_receipt, u_level, u_gubun, u_state
				, DATE_FORMAT( u_regdate , '%Y 년 %m 월 %d 일' ) as u_regdate

				, u_hp, u_tel, u_fax, u_post, u_addr1, u_addr2, u_area, u_homepage, u_sex
				, DATE_FORMAT( u_birth , '%Y-%m-%d' ) as u_birth

				, u_birth_luner

				, ( select count(u_id) from tbl_user_log where u_id = A.u_id ) as u_log_cnt

*/
		$SQL = "
			select
					*
			from
				tbl_user A
			where
				A.u_idx = '".$ss_u_idx."'
			limit 0, 1
		";



		$result = $dbcon -> query($SQL);
		$rows = $dbcon -> fetch_array($result);

		extract($rows);

		$u_jumin2 = "*******";

		$Arr_u_birth = explode("-", $u_birth);
		if (sizeof($Arr_u_hp) > 0 ) {
			$u_birth_year = $Arr_u_birth[0];
			$u_birth_month = $Arr_u_birth[1];
			$u_birth_day = $Arr_u_birth[2];
		}
	}

	// 신규 가입시
	else {
		if ( $ss_u_idx > 0 && $ss_u_level < $auth_admin ) {
			alert_back($msg_login_auth);
			exit;
		}

		//관리자 접근시...
		if ($ss_u_level > $auth_admin) {

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

					$u_birth_year = $TempBirthYear;
					$u_birth_month = $TempBirthMonth;
					$u_birth_day = $TempBirthDay;

					// 성별은 F, M 으로 나눈다.
					// 주민등록번호의 7번째 자리가 홀수이면 남자(Male), 짝수이면 여자(Female)
					$u_sex = $u_jumin2 % 2 == 0 ? "F" : "M";

				}

				$chk_readonly = "readonly";
			}
		}

	}

	if ( !$u_birth_year && !$u_birth_month && !$u_birth_day ) {
		$u_birth_year = $now_year;
		$u_birth_month = $now_month;
		$u_birth_day = $now_day;
	}

	if ( $sc_member_top_include ) {
		include_once $_SERVER[DOCUMENT_ROOT].$sc_member_top_include;
	}

	if ( $sc_member_top_content ) {
		echo RESSTR($sc_member_top_content);
	}
?>


<?php
	//echo "path_skin_member : ".$path_skin_member."<BR>";
	include_once $path_skin_member."join.php";
?>

<script>
	TempGo = 0;
	function JoinGo() {
		// 방지할 ID, 이름
		var noName = new Array("admin", "administrator","webmaster","master","관리자","게시판관리자","어드민","웹마스터","사이트관리자","운영자","사이트운영자");

		var alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		var numeric = '1234567890';
		var special = ' ~!@#$%^&*()-_=+|\\{}[];:"\'<>,.?\/';

		ff = document.JoinForm;

		<? if ( getLen($u_idx) == 0 ) { ?>
		if(ff.u_id.value.length < 6) {
			alert("아이디는 영문 또는 숫자 6~16자로 구성됩니다.");
			ff.u_id.focus();
			return false;
		}
		else {
			if (!checkNorm(ff.u_id, '아이디', numeric+alpha+'(-_)+', 16)) {
				OBJ_ID.focus();
				return false;
			}
		}

		if (ff.id_chk.value != 1) {
			error(ff.u_id, "아이디 중복확인 해주세요.");
			return false;
		}

		if (ff.u_pw.value < 6) {
			ff.u_pw.value = "";
			ff.u_pw_r.value = "";
			error(ff.u_pw, "비밀번호는 총 6 자리 이상 입력하셔야 합니다.");
			return false;
		}
		<? } ?>

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

		<? if ( getLen($ss_u_idx) == 0 ) { ?>
		if ( ff.u_jumin1.value.length < 6 || ff.u_jumin2.value.length < 7 ) {
			error(ff.u_jumin1, "<?=$msg_join_jumin?>");
			return false;
		}

		if ( !juminCheck(ff.u_jumin1, ff.u_jumin2) ) {
			return false;
		}
		<? } ?>

		if ( (!ff.u_tel2.value || !ff.u_tel3.value) && (!ff.u_hp2.value || !ff.u_hp3.value) ) {
			alert("전화번호와 휴대폰번호중 하나는 필수 입력입니다.");
			return false;
		}

		if (ff.u_email1.value == "" || ff.u_email2.value == "") {
			alert("E-mail 주소를 입력하여 주십시오.");
			ff.u_email1.focus();
			return false;
		}

		if ( !ff.u_post.value || !ff.u_addr1.value || !ff.u_addr2.value ) {
			alert("주소를 입력하여 주십시오.");
			ff.u_addr2.focus();
			return false;
		}

		if (TempGo > 0) {
			alert("<?=$msg_error_touch?>");
			return false;
		}
		//return false;
		TempGo = 1;
	}
</script>

<?php


	if ( $sc_member_bottom_include ) {
		include_once $_SERVER[DOCUMENT_ROOT].$sc_member_bottom_include;
	}

	if ( $sc_member_bottom_content ) {
		echo RESSTR($sc_member_bottom_content);
	}

	$dbcon -> dbcon_close();
?>

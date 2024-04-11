<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";

	$now_year		= date("Y");
	$now_month	= date("m");
	$now_day		= date("d");

	// 정보 수정시
	if ( getLen($ss_u_idx) > 0 ) {
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
	else {
		alert_back($msg_login_go, $url_login);
	}


	if ( !$u_birth_year && !$u_birth_month && !$u_birth_day ) {
		$u_birth_year = $now_year;
		$u_birth_month = $now_month;
		$u_birth_day = $now_day;
	}
?>

<?php
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

<?
	$dbcon -> dbcon_close();
?>



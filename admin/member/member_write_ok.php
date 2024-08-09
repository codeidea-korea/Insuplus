<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$u_idx					= REQSTR($_POST[u_idx], "");
	$u_id						= REQSTR($_POST[u_id], "");
	$u_pw					= REQSTR($_POST[u_pw], "");
	$u_name				= REQSTR($_POST[u_name], "");
	$u_class				= REQSTR($_POST[u_class], "");
	$u_school				= REQSTR($_POST[u_school], "");
	$u_jumin1				= REQSTR($_POST[u_jumin1], "");
	$u_jumin2				= REQSTR($_POST[u_jumin2], "");
	$u_email1				= REQSTR($_POST[u_email1], "");
	$u_email2				= REQSTR($_POST[u_email2], "");
	$u_hp1					= REQSTR($_POST[u_hp1], "");
	$u_hp2					= REQSTR($_POST[u_hp2], "");
	$u_hp3					= REQSTR($_POST[u_hp3], "");
	$u_tel1					= REQSTR($_POST[u_tel1], "");
	$u_tel2					= REQSTR($_POST[u_tel2], "");
	$u_tel3					= REQSTR($_POST[u_tel3], "");
	$u_fax1					= REQSTR($_POST[u_fax1], "");
	$u_fax2					= REQSTR($_POST[u_fax2], "");
	$u_fax3					= REQSTR($_POST[u_fax3], "");
	$u_post					= REQSTR($_POST[u_post], "");
	$u_addr1				= REQSTR($_POST[u_addr1], "");
	$u_addr2				= REQSTR($_POST[u_addr2], "");
	$u_area					= REQSTR($_POST[u_area], "");
	$u_homepage			= REQSTR($_POST[u_homepage], "");
	$u_sex					= REQSTR($_POST[u_sex], "");
	$u_interest				= REQSTR($_POST[u_interest], "");
	$u_hobby				= REQSTR($_POST[u_hobby], "");
	$u_religion				= REQSTR($_POST[u_religion], "");
	$u_blood				= REQSTR($_POST[u_blood], "");
	$u_job					= REQSTR($_POST[u_job], "");
	$u_marriage				= REQSTR($_POST[u_marriage], "");
	$u_marriagedate		= REQSTR($_POST[u_marriagedate], "");
	$u_image				= REQSTR($_POST[u_image], "");
	$u_introduction		= REQSTR($_POST[u_introduction], "");
	$u_etc					= REQSTR($_POST[u_etc], "");
	$u_re_id					= REQSTR($_POST[u_re_id], "");
	$u_birth					= REQSTR($_POST[u_birth], "");
	$u_birth_luner			= REQSTR($_POST[u_birth_luner], "");
	$u_email_receipt		= REQSTR($_POST[u_email_receipt], "");
	$u_sms_receipt		= REQSTR($_POST[u_sms_receipt], "");
	$u_level					= REQSTR($_POST[u_level], "");
	$u_gubun				= REQSTR($_POST[u_gubun], "");
	$u_partner_seq				= REQSTR($_POST[u_partner_seq], "0");
	$u_state					= REQSTR($_POST[u_state], "");
	$u_regdate				= REQSTR($_POST[u_regdate], "");

	isnull($u_id);
	isnull($u_name);

	if ($u_pw != $u_pw_r) {
		alert_back($msg_error_null);
	}



//	echo "id_chk : ".$id_chk."<BR>";
//	echo "u_idx : ".$u_idx."<BR>";
//	echo "u_id : ".$u_id."<BR>";
//	echo "u_pw : ".$u_pw."<BR>";
//	echo "u_pw_r : ".$u_pw_r."<BR>";
//	echo "u_name : ".$u_name."<BR>";
//	echo "u_jumin1 : ".$u_jumin1."<BR>";
//	echo "u_jumin2 : ".$u_jumin2."<BR>";
//	echo "u_birth_year : ".$u_birth_year."<BR>";
//	echo "u_birth_month : ".$u_birth_month."<BR>";
//	echo "u_birth_day : ".$u_birth_day."<BR>";
//	echo "u_birth_luner : ".$u_birth_luner."<BR>";
//	echo "u_homepage : ".$u_homepage."<BR>";
//	echo "u_post : ".$u_post."<BR>";
//	echo "u_addr1 : ".$u_addr1."<BR>";
//	echo "u_addr2 : ".$u_addr2."<BR>";
//	echo "u_email_receipt : ".$u_email_receipt."<BR>";
//	echo "u_sms_receipt : ".$u_sms_receipt."<BR>";
//
//	echo "u_birth : ".$u_birth."<BR>";
//	echo "u_area : ".$u_area."<BR>";

	// 정보수정
	if ( getLen($u_idx) ) {
		$SQL_pw_set = "";
		if ( getLen($u_pw) ) {
			$u_pw = all_seed_enc($u_pw);
			$SQL_pw_set .= " , u_pw = '".$u_pw."' ";
		}
		if ( getLen($u_jumin1)  &&  getLen($u_jumin2) ) {
			$u_jumin2 = base64_encode($u_jumin2);
			$SQL_pw_set .= "
				, u_jumin1 = '".$u_jumin1."'
				, u_jumin2 = '".$u_jumin2."'
			";
		}

		$SQL = "
			update tbl_user
			set
				u_name = '".$u_name."'
				, u_email1 = '".$u_email1."'
				, u_email2 = '".$u_email2."'
				, u_hp1 = '".$u_hp1."'
				, u_hp2 = '".$u_hp2."'
				, u_hp3 = '".$u_hp3."'
				, u_tel1 = '".$u_tel1."'
				, u_tel2 = '".$u_tel2."'
				, u_tel3 = '".$u_tel3."'
				, u_birth = '".$u_birth."'
				, u_level = '".$u_level."'
				, u_partner_seq = '".$u_partner_seq."'
				, u_state = '".$u_state."'
				".$SQL_pw_set."
			where
				u_id = '".$u_id."'
		";
//		echo $SQL."<br>";
		$result = $dbcon -> query($SQL);
		if (!$result) {
			$dbcon -> dbcon_close();
			alert_back($msg_user_modify_error);
			exit;
		}

	} else { // 신규가입

		if ( strlen($u_idx) == 0 ) {
			isnull($u_pw);
			isnull($u_pw_r);
		}

		if ( MemberCheckJoin($u_id) > 0 ) {
			alert_page( $msg_error_join_state, "member_write.php");
		} else if(strlen($u_pw) < 10) {
			alert_back($msg_error_join_pw);
		}

		$u_pw = all_seed_enc($u_pw);

		$SQL = "
					INSERT INTO tbl_user (
						u_id, u_pw, u_name
						, u_email1, u_email2, u_hp1, u_hp2, u_hp3, u_tel1, u_tel2, u_tel3
						, u_sex
						, u_birth, u_level,u_partner_seq,  u_state
					) VALUES (
						'".$u_id."', '".$u_pw."', '".$u_name."'
						, '".$u_email1."', '".$u_email2."', '".$u_hp1."', '".$u_hp2."', '".$u_hp3."', '".$u_tel1."', '".$u_tel2."', '".$u_tel3."'
						, '".$u_sex."'
						, '".$u_birth."', '".$u_level."', '".$u_partner_seq."', '".$u_state."'
					)
				";
		echo "SQL : ".$SQL."<BR>";
		$result = $dbcon -> query($SQL);
//		exit;


		// 쿼리 에러시에 해당 아이디 모두 삭제
		if (!$result ) {
			MemberDeleteProcess($u_id);
		}
	}


	//include_once $sc_path_skin_member."join_end.php";

	$dbcon -> dbcon_close();

	// 정보수정
	if ( strlen($u_idx) > 0 ) {
		alert_page($msg_user_modify_ok, "member_write.php?u_idx=".$u_idx);
	}
	// 신규가입
	else {
		alert_page("", "member_list.php");
	}

//	if ( $ss_u_level < $auth_admin )  {
//		alert_page("", "join_end.php");
//	}
//	else {
//		alert_close("처리되었습니다.");
//	}
?>

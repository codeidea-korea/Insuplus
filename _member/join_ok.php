<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";

	$id_chk				= REQSTR($_POST[id_chk], "0");
	$u_idx				= REQSTR($_POST[u_idx], "");
	$u_id					= REQSTR($_POST[u_id], "");
	$u_pw				= REQSTR($_POST[u_pw], "");
	$u_pw_r				= REQSTR($_POST[u_pw_r], "");
	$u_name				= REQSTR($_POST[u_name], "");

	$u_class			= REQSTR($_POST[u_class], "");
	$u_school			= REQSTR($_POST[u_school], "");

	$u_tel1				= REQSTR($_POST[u_tel1], "");
	$u_tel2				= REQSTR($_POST[u_tel2], "");
	$u_tel3				= REQSTR($_POST[u_tel3], "");

	$u_hp1				= REQSTR($_POST[u_hp1], "");
	$u_hp2				= REQSTR($_POST[u_hp2], "");
	$u_hp3				= REQSTR($_POST[u_hp3], "");

	$u_fax1				= REQSTR($_POST[u_fax1], "");
	$u_fax2				= REQSTR($_POST[u_fax2], "");
	$u_fax3				= REQSTR($_POST[u_fax3], "");

	$u_jumin1			= REQSTR($_POST[u_jumin1], "");
	$u_jumin2			= REQSTR($_POST[u_jumin2], "");
	$u_birth_year		= REQSTR($_POST[u_birth_year], "");
	$u_birth_month	= REQSTR($_POST[u_birth_month], "");
	$u_birth_day		= REQSTR($_POST[u_birth_day], "");
	$u_birth_luner		= REQSTR($_POST[u_birth_luner], "");
	$u_post				= REQSTR($_POST[u_post], "");
	$u_addr1			= REQSTR($_POST[u_addr1], "");
	$u_addr2			= REQSTR($_POST[u_addr2], "");
	$u_email_receipt	= REQSTR($_POST[u_email_receipt], "");
	$u_sms_receipt	= REQSTR($_POST[u_sms_receipt], "");

	$u_birth				= $u_birth_year."-".$u_birth_month."-".$u_birth_day;
	$u_area				= mb_substr($u_addr1, 0, 2, "UTF-8");

	if ($u_sex != "M") {
		$u_sex = "F";
	}

	isnull($u_id);
	isnull($u_name);

	isnull($id_chk);

	if ( getLen($u_idx) > 0 ) {
		isnull($id_chk, 1);
	}
	else {
		isnull($u_pw);
		isnull($u_pw_r);
	}

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
			$u_pw = sql_password($u_pw);
			$SQL_pw_set .= " , u_pw = '".$u_pw."' ";
		}
		if ( getLen($u_jumin1)  &&  getLen($u_jumin2) ) {
			$u_jumin2 = sql_password($u_jumin2);
			$SQL_pw_set .= " , u_jumin1 = '".$u_jumin1."' ";
			$SQL_pw_set .= " , u_jumin2 = '".$u_jumin2."' ";
		}

		$SQL = "
			update tbl_user
			set
				u_name = '".$u_name."'
				, u_class = '".$u_class."'
				, u_school = '".$u_school."'
				, u_email1 = '".$u_email1."'
				, u_email2 = '".$u_email2."'
				, u_email_receipt = '".$u_email_receipt."'
				, u_sms_receipt = '".$u_sms_receipt."'
				, u_hp1 = '".$u_hp1."'
				, u_hp2 = '".$u_hp2."'
				, u_hp3 = '".$u_hp3."'
				, u_tel1 = '".$u_tel1."'
				, u_tel2 = '".$u_tel2."'
				, u_tel3 = '".$u_tel3."'
				, u_fax1 = '".$u_fax1."'
				, u_fax2 = '".$u_fax2."'
				, u_fax3 = '".$u_fax3."'
				, u_post = '".$u_post."'
				, u_addr1 = '".$u_addr1."'
				, u_addr2 = '".$u_addr2."'
				, u_area = '".$u_area."'
				, u_homepage = '".$u_homepage."'
				, u_sex = '".$u_sex."'
				, u_birth = '".$u_birth."'
				, u_birth_luner = '".$u_birth_luner."'

				".$SQL_pw_set."
			where
				u_id = '".$u_id."'
		";
		$result = $dbcon -> query($SQL);

	}

	// 신규가입
	else {

		if ( MemberCheckJoin($u_id) > 0 ) {
			alert_page( $msg_error_join_state, "agree.php");
		}

		$u_pw = sql_password($u_pw);
		$u_jumin2 = sql_password($u_jumin2);


		$SQL = "
					INSERT INTO tbl_user (
						u_id ,u_pw , u_name, u_class, u_school, u_jumin1 ,u_jumin2 ,u_email1 ,u_email2 ,u_email_receipt ,u_sms_receipt ,u_level ,u_gubun ,u_state

						, u_hp1 , u_hp2 , u_hp3 ,u_tel1 ,u_tel2 ,u_tel3 ,u_fax1 ,u_fax2 ,u_fax3 ,u_post ,u_addr1 ,u_addr2 ,u_area ,u_homepage ,u_sex ,u_birth ,u_birth_luner
					) VALUES (
						'".$u_id."', ('".$u_pw."'), '".$u_name."', '".$u_class."', '".$u_school."', '".$u_jumin1."' , '".$u_jumin2."' , '".$u_email1."', '".$u_email2."', '".$u_email_receipt."', '".$u_sms_receipt."', '".$join_user_level."', '".$u_gubun."', '".$join_user_state."'
						, '".$u_hp1."', '".$u_hp2."', '".$u_hp3."' , '".$u_tel1."', '".$u_tel2."', '".$u_tel3."' , '".$u_fax1."', '".$u_fax2."', '".$u_fax3."' , '".$u_post."' , '".$u_addr1."' , '".$u_addr2."' , '".$u_area."' , '".$u_homepage."' , '".$u_sex."', '".$u_birth."' , '".$u_birth_luner."'
					)
				";
		$result = $dbcon -> query($SQL);

		// 쿼리 에러시에 해당 아이디 모두 삭제
		if (!$result) {
			MemberDeleteProcess($u_id);
		}


		#### 신규회원 환영 메일 발송 Start ####
		if ( $u_email1 && $u_email2 ) {//&& $u_email_receipt == "Y"

			#### 작성자에게 메일전송
			$charset='UTF-8';
			$mail_subject = "회원가입을 환영합니다.";
			$toName=$u_name;
			$toEmail=$u_email1."@".$u_email2;
			$fromName=$sc_admin_name;
			$fromEmail=$sc_admin_email;
			$body= "";

			// 메일 스킨 적용
			ob_start();
			include($path_skin_member."/mail_join_ok.php");
			$body = ob_get_contents();
			ob_end_clean();

//			$body = str_replace("[mail_subject]", $mail_subject, $body);
//			$body = str_replace("[subject]", $subject, $body);
//			$body = str_replace("[nick_name]", $nick_name, $body);
//			$body = str_replace("[content]", nl2br(REQSTR2($content)), $body);
			$body = str_replace("[url_root]", $sc_site_url, $body);
//
//			$body = str_replace("[ext1]", REQSTR2($ext1), $body);
//			$body = str_replace("[ext2]", REQSTR2($ext2), $body);
//			$body = str_replace("[ext3]", REQSTR2($ext3), $body);
//			$body = str_replace("[ext4]", REQSTR2($ext4), $body);
//			$body = str_replace("[ext5]", REQSTR2($ext5), $body);
//			$body = str_replace("[ext6]", REQSTR2($ext6), $body);
//			$body = str_replace("[ext7]", REQSTR2($ext7), $body);
//			$body = str_replace("[ext8]", REQSTR2($ext8), $body);
//			$body = str_replace("[ext9]", REQSTR2($ext9), $body);
//			$body = str_replace("[ext10]", REQSTR2($ext10), $body);
//
//			$body = $mail_skin["header"].$body.$mail_skin["footer"];

			$return_mail = MailGo( $toName, $toEmail, $fromName, $fromEmail, $mail_subject, $body, $charset );

		}
		#### 신규회원 환영 메일 발송 End ####


	}


	//include_once $path_skin_member."join_end.php";

	$dbcon -> dbcon_close();
//
//	echo $SQL."<BR>";
//	exit;

	// 정보수정
	if ( getLen($u_idx) > 0 ) {
		alert_page($msg_user_modify_ok, $url_logout_ok);
	}
	// 신규가입
	else {
		alert_page("", $cf_site_url."/member/join_end.php");
	}

//	if ( $ss_u_level < $auth_admin )  {
//		alert_page("", "join_end.php");
//	}
//	else {
//		alert_close("처리되었습니다.");
//	}
?>

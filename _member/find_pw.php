<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
	login_chk(0);


	$act = REQSTR($_POST[act], "");


	if ( $act == "ok" ) {

		$u_id = REQSTR($_POST[u_id], "");
		$u_name = REQSTR($_POST[u_name], "");
		$u_jumin1 = REQSTR($_POST[u_jumin1], "");
		$u_jumin2 = REQSTR($_POST[u_jumin2], "");

		isnull($u_id);
		isnull($u_name);

		if ( $find_type == "jumin" ) {
			isnull($u_jumin1);
			isnull($u_jumin2);
			$u_jumin2 = base64_encode($u_jumin2);
			$SQL_SEARCH = "
				and u_jumin1 = '".$u_jumin1."'
				and u_jumin2 = '".$u_jumin2."'
			";
		} else {
			isnull($u_email);
			$SQL_SEARCH = "
				and (u_email1 + u_email2)  = '".$u_email."'
			";
		}


		$SQL = "
			select u_email1, u_email2
			from
				tbl_user
			where
				u_id = '".$u_id."'
				and u_name = '".$u_name."'
				$SQL_SEARCH
		";
//		echo $SQL."<BR>";

		$MRS = $dbcon -> query($SQL);
		$MRS_cnt = $dbcon -> num_rows($MRS);
//		echo "MRS_cnt : " .$MRS_cnt."<BR>";

		if ( !$MRS_cnt ) {
			alert_back("정보가 일치하지 않습니다.");
			exit;
		}

		$MROW = $dbcon -> fetch_array($MRS);
		$Temp_u_email = $MROW[u_email1]."@".$MROW[u_email2];
//		echo "Temp_u_email : " .$Temp_u_email."<BR>";
//		exit;

		#### 임시 비밀번호 생성
		$temp_u_pw = substr(base64_encode(time()),-10, -2); // 현재시간을 base64로 인코딩.
		$temp_u_pw = substr(base64_encode(time()),-10, -2); // 현재시간을 base64로 인코딩.

		#### 비밀번호 변경
		$SQL = "
			update tbl_user
			set
				u_pw = '".base64_encode($temp_u_pw)."'
			where
				u_name = '".$u_name."'
				$SQL_SEARCH
		";
		//u_pw = password('".$temp_u_pw."')
		$result = $dbcon -> query($SQL);
		$MRS_cnt = $dbcon -> num_rows($MRS);
		if ( !$MRS_cnt ) {
			alert_back("비번 변경 실패");
		}


		#### 사용자에게 임시 비밀번호 전송
		$charset='UTF-8';
		$subject = "[$sc_name] $u_name 님의 임시비밀번호입니다.";
		$toName=$u_name;
		$toEmail=$Temp_u_email; // 이 부분은 테스트할 메일주소로 반드시 수정하셔야 합니다.
		$fromName=$sc_admin_name;
		$fromEmail=$sc_admin_email;
		$body= "<img src=\"".$url_root."images/cost_0".$idx.".gif\"> ";

		// 메일 스킨 적용
		ob_start();
		include($path_skin_mail."default/find_pw.html");
		$body = ob_get_contents();
		ob_end_clean();

		$body = str_replace("[sc_url_skin_mail]", $sc_url_skin_mail, $body);
		$body = str_replace("[u_name]", $u_name, $body);
		$body = str_replace("[u_pw]", $temp_u_pw, $body);
		$body = str_replace("[url_root]", $url_root, $body);

		$return_mail = MailGo( $u_name, $Temp_u_email, $sc_admin_name, $sc_admin_email, $subject, $body, $charset );

		$dbcon -> dbcon_close();
		//alert_close($u_name.' 님의 임시 비밀 번호를 '.$Temp_u_email.' 로 전송하였습니다.\n확인 후 반드시 개인정보수정을 통하여 변경하여 주십시오.');
		//exit;
	}
	getLib();

	include_once $path_skin_member."find_pw.php";

	$dbcon -> dbcon_close();
?>

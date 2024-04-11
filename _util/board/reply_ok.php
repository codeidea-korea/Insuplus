<?
	$seq_sub = REQSTR($_POST[seq_sub], "");
	$seq_level = REQSTR($_POST[seq_level], "");
	$act = REQSTR($_POST[act], "");
	$nick_name = REQSTR($nick_name, "");
	$passwd = REQSTR($passwd, "");
	$subject = REQSTR($subject, "");
	//$content = REQSTR($content, "");
	$notice = REQSTR($notice, "N");
	$hidden = REQSTR($hidden, "N");

	$email1 = REQSTR($email1, "");
	$email2 = REQSTR($email2, "");
	$old_email = REQSTR($old_email, "");
	$old_name = REQSTR($old_name, "");
	$category = REQSTR($category, "");


	$ext1 = REQSTR($ext1, "");
	$ext2 = REQSTR($ext2, "");
	$ext3 = REQSTR($ext3, "");
	$ext4 = REQSTR($ext4, "");
	$ext5 = REQSTR($ext5, "");
	$ext6 = REQSTR($ext6, "");
	$ext7 = REQSTR($ext7, "");
	$ext8 = REQSTR($ext8, "");
	$ext9 = REQSTR($ext9, "");
	$ext10 = REQSTR($ext10, "");


	isnull($seq_sub);
	isnull($act);
//	isnull($subject);


	if ($bc_autoreg_use == "Y") {
		if (
			( !isset($_POST['signupcode']) ) || ( strlen($_POST['signupcode']) != 5 ) || ( !isset($_SESSION['signupcode']) ) || ( strtolower($_POST['signupcode']) != $_SESSION['signupcode'] )
		) {
			$dbcon -> dbcon_close();
			alert_back("코드가 일치하지 않습니다.");
			exit;
		}
		unset ($_POST['signupcode']);
		unset ($_SESSION['signupcode']);
	}


	#### 비밀번호 설정
	if ( $auth_level > 0 ) {
		$SQL_passwd = " '".$now_passwd."' ";
	}
	else {
		isnull($passwd);
		$SQL_passwd = " password('".$passwd."') ";
	}

	#### 공지글 설정 시작
	if ( $bc_notice_use == "Y" && $auth_notice ) {
		if ( $notice != "Y" ) { $notice = "N"; }
	}
	else { $notice = "N"; }

	#### hidden 설정 시작
	if ($bc_hidden_use == "Y" && $auth_hidden) {
		if ( $hidden != "Y" ) { $hidden = "N"; }
	}
	else { $hidden = "N"; }

	#### 카테고리 설정
	if ( $bc_category_use == "Y" ) {
		isnull($category);
	}


	#############################
	#### 파일 업로드 시작

//	if ( $bc_upfile_image == "Y" ) {
//		if ( strlen($_FILES["upfile_image"]["name"][0]) == 0 ) {
//			alert_back("이미지 파일을 올려주세요.");
//		}
//	}

	if ( $bc_upfile_cnt > 0 ) {
		$up = new FileUploadManager;
		$up->SetExceptExtension($Arr_bc_upfile_ext_upload);
		$up->SetExceptFileSize($bc_upfile_size);
		$up->SetFiles($_FILES["upfile"]);
		$up->SetDirectory($upload_path);
		$FileResult = $up->UploadManager();
		//echo "result : " .$result."<BR>";
	}
	#### 파일 업로드 끝
	#############################

	// 에디터 설정 ( 내용 설정)
	if ($bc_editor_use == "Y") {
	}
	else {
		$content = REQSTR($content, "");
		isnull($content);
	}




	#############################
	#### 온라인 상담 비밀글 처리
//	if ( $bc_id = 'contact' ) {
//		$SQL = "
//			update tbl_board_".$bc_id."
//			set
//				secret = 'N'
//			where
//				seq = '".$seq."'
//		";
//		//echo $SQL."<BR>";exit;
//		$dbcon -> query($SQL);
//	}
	#############################



	#############################
	#### 계층형 값 설정 시작
	//1000의 배수의 글들은 모두 새글로 등록된 것이다. 이하 새글
	//3000에 답글이 달려지면 2999이 될것이다. 그렇다면 어떤 값이 업데이트(-1) 되어야 할까?
	//2000보다는 크고 3000보다는 작은 값이 업데이트 되어야 한다.
	//2998번 글에 답글이 달려지면 2000보다 크고 2998번보다 작은 값이 업데이트 되어야 한다.

	//(답글이 달려질) 원본글보다 thread 값이 작은것중에서 제일 큰 새글의 thread 값(1000의 배수다)
	//원본글의 값이 1999면 1.999를 내림하여 1이 된다. 여기에 1000을 곱하니 1000이다.
	//그럼 1000이란 값은 1999번 보다는 작지만 작은 수들중에 가장 큰 1000의 배수가 맞나? 아~ 맞네.
	//만약 원본글에 답글이 달리는 경우는 $seq_sub 에 1000을 빼면된다.
	if ( $seq_sub % 1000 > 0) {
		$prev_parent_thread = floor($seq_sub/1000)*1000;
	}
	else {
		$prev_parent_thread = $seq_sub - 1000;
	}

	//원본글보다는 작고 위값보다는 큰 글들의 thread 값을 모두 1씩 낮춘다.
	$SQL = "
		update tbl_board_".$bc_id."
		set
			seq_sub=seq_sub-1
		where
			seq_sub > '".$prev_parent_thread."'
			and seq_sub < '".$seq_sub."'
	";
	$update_thread = $dbcon -> query($SQL);

	//원본글보다는 1작은 값으로 답글을 등록한다.
	//원본글의 바로 밑에 등록되게 된다.
	//depth는 원본글의 depth + 1 이다. 원본글이 3(이글도 답글이군)이면 답글은 4가된다.

	#### 계층형 값 설정 끝
	#############################

	$SQL = "
		insert into
			tbl_board_".$bc_id."
		(
			seq_sub, seq_level
			, category
			, subject, content
			, writer, passwd, nick_name
			, email1, email2
			, view_cnt
			, notice
			, imgfile
			, secret, hidden
			, ext1, ext2, ext3, ext4, ext5, ext6, ext7, ext8, ext9, ext10
		)
		values
		(
			'".($seq_sub-1)."', '".($seq_level+1)."'
			, '".$category."'
			, '".$subject."', '".$content."'
			, '".$writer."', ".$SQL_passwd.", '".$nick_name."'
			, '".$email1."', '".$email2."'
			, '".$view_cnt."'
			, '".$notice."'
			, '".$imgfile."'
			, '".$secret."', '".$hidden."'
			, '".$ext1."', '".$ext2."', '".$ext3."', '".$ext4."', '".$ext5."', '".$ext6."', '".$ext7."', '".$ext8."', '".$ext9."', '".$ext10."'
		)
	";

	$result = $dbcon -> query($SQL, $dbcon);
	if (!$result) {
		$dbcon -> dbcon_close();
		//echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
		//echo "에러";
		alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
		exit;
	}
	$inset_seq = mysql_insert_id();
	//echo "<xmp>".$SQL."</xmp><BR>";exit;

	#############################
	#### 파일 업로드 시작
	if ( $bc_upfile_cnt > 0 ) {
		if ($FileResult) {
			for ($i = 0 ; $i < count($up->returnValue); $i++) {
//				for ($j = 0 ; $j < count($up->returnValue[$i]); $j++) {
//					echo $up->returnValue[$i][$j]."<BR>";
//				}
				upfileUpload('board', $bc_id, $inset_seq, $up->returnValue[$i][0], $up->returnValue[$i][1], $up->returnValue[$i][2]);
			}
		}
	}
	#### 파일 업로드 끝
	#############################


	#############################
	#### 메일 처리

	if ( $bc_email_use == "B" || $bc_email_use == "Y" ) {

		$charset='UTF-8';
		$mail_subject = "[".$sc_name." > ".$bc_name."] ".$nick_name."님의 답변이 등록되었습니다.";
		$toName=$old_name;
		$toEmail=$old_email;
//			$fromName=$sc_name;
//			$fromEmail=$email1."@".$email2;
		$fromName=$sc_admin_name;
		$fromEmail=$sc_admin_email;
		$body= "";

		// 메일 스킨 적용
		ob_start();
		include($path_skin_board.$bc_skin."/mail.php");
		$body = ob_get_contents();
		ob_end_clean();

		$body = str_replace("[mail_subject]", $mail_subject, $body);
		$body = str_replace("[subject]", $subject, $body);
		$body = str_replace("[nick_name]", $nick_name, $body);
		$body = str_replace("[content]", nl2br(REQSTR2($content)), $body);
		//$body = str_replace("[goURL]", $sc_site_url.$bc_path, $body);
		$body = str_replace("[goURL]", $bc_path, $body);

		$ext1 = str_replace("[ext1]", REQSTR2($ext1), $body);
		$ext2 = str_replace("[ext2]", REQSTR2($ext2), $body);
		$ext3 = str_replace("[ext3]", REQSTR2($ext3), $body);
		$ext4 = str_replace("[ext4]", REQSTR2($ext4), $body);
		$ext5 = str_replace("[ext5]", REQSTR2($ext5), $body);
		$ext6 = str_replace("[ext6]", REQSTR2($ext6), $body);
		$ext7 = str_replace("[ext7]", REQSTR2($ext7), $body);
		$ext8 = str_replace("[ext8]", REQSTR2($ext8), $body);
		$ext9 = str_replace("[ext9]", REQSTR2($ext9), $body);
		$ext10 = str_replace("[ext10]", REQSTR2($ext10), $body);

		$body = $mail_skin["header"].$body.$mail_skin["footer"];

		$return_mail = MailGo( $toName, $toEmail, $fromName, $fromEmail, $mail_subject, $body, $charset );

	}


	#### 게시판 관리자에게 메일 보내기
	// 온라인 A/S
	if ( $bc_id == "board_as" ) {
		$charset='UTF-8';
		$mail_subject = "[".$sc_name." > ".$bc_name."] ".$nick_name."님의 답변이 등록되었습니다.";
		$toName=$sc_admin_name;
		$toEmail="tech@sindohcom.co.kr";
		//$toEmail="anmkst@bluecarpet.co.kr";
		$fromName=$sc_admin_name;
		$fromEmail=$sc_admin_email;
		$body= "";

		// 메일 스킨 적용
		ob_start();
		include($path_skin_board.$bc_skin."/mail.php");
		$body = ob_get_contents();
		ob_end_clean();

		$body = str_replace("[mail_subject]", $mail_subject, $body);
		$body = str_replace("[subject]", $subject, $body);
		$body = str_replace("[nick_name]", $nick_name, $body);
		$body = str_replace("[content]", nl2br(REQSTR2($content)), $body);
		//$body = str_replace("[goURL]", $sc_site_url.$bc_path, $body);
		$body = str_replace("[goURL]", $bc_path, $body);

		$body = str_replace("[ext1]", REQSTR2($ext1), $body);
		$body = str_replace("[ext2]", REQSTR2($ext2), $body);
		$body = str_replace("[ext3]", REQSTR2($ext3), $body);
		$body = str_replace("[ext4]", REQSTR2($ext4), $body);
		$body = str_replace("[ext5]", REQSTR2($ext5), $body);
		$body = str_replace("[ext6]", REQSTR2($ext6), $body);
		$body = str_replace("[ext7]", REQSTR2($ext7), $body);
		$body = str_replace("[ext8]", REQSTR2($ext8), $body);
		$body = str_replace("[ext9]", REQSTR2($ext9), $body);
		$body = str_replace("[ext10]", REQSTR2($ext10), $body);

		$body = $mail_skin["header"].$body.$mail_skin["footer"];

		$return_mail = MailGo( $toName, $toEmail, $fromName, $fromEmail, $mail_subject, $body, $charset );

	}
	if ( $bc_id == "board_as" || $bc_id == "contact" ||  $bc_id == "contact_buy" ) {
		$charset='UTF-8';
		$mail_subject = "[".$sc_name." > ".$bc_name."] ".$nick_name."님의 답변이 등록되었습니다.";
		$toName=$sc_admin_name;
		$toEmail=$sc_admin_email;
		//$toEmail="anmkst@nate.com";
		$fromName=$sc_admin_name;
		$fromEmail=$sc_admin_email;
		$body= "";

		// 메일 스킨 적용
		ob_start();
		include($path_skin_board.$bc_skin."/mail.php");
		$body = ob_get_contents();
		ob_end_clean();

		$body = str_replace("[mail_subject]", $mail_subject, $body);

		if (  $bc_id == "contact_buy" ) {
			$body = str_replace("[subject]", REQSTR2($ext1), $body);
		}
		else {
			$body = str_replace("[subject]", $subject, $body);
		}
		$body = str_replace("[nick_name]", $nick_name, $body);
		$body = str_replace("[content]", nl2br(REQSTR2($content)), $body);
		//$body = str_replace("[goURL]", $sc_site_url.$bc_path, $body);
		$body = str_replace("[goURL]", $bc_path, $body);

		$body = str_replace("[ext1]", REQSTR2($ext1), $body);
		$body = str_replace("[ext2]", REQSTR2($ext2), $body);
		$body = str_replace("[ext3]", REQSTR2($ext3), $body);
		$body = str_replace("[ext4]", REQSTR2($ext4), $body);
		$body = str_replace("[ext5]", REQSTR2($ext5), $body);
		$body = str_replace("[ext6]", REQSTR2($ext6), $body);
		$body = str_replace("[ext7]", REQSTR2($ext7), $body);
		$body = str_replace("[ext8]", REQSTR2($ext8), $body);
		$body = str_replace("[ext9]", REQSTR2($ext9), $body);
		$body = str_replace("[ext10]", REQSTR2($ext10), $body);

		$body = $mail_skin["header"].$body.$mail_skin["footer"];

		$return_mail = MailGo( $toName, $toEmail, $fromName, $fromEmail, $mail_subject, $body, $charset );
	}



	#############################


	$dbcon -> dbcon_close();

	if ( $bc_category_use == "Y" ) $parameter = "&search_category=".$category;

	alert_page("처리되었습니다..","?mode=list&page=".$page.$parameter, "parent");
?>

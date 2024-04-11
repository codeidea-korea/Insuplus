<?
	$act = REQSTR($_POST[act], "");
	$nick_name = REQSTR($nick_name, "");
	$passwd = REQSTR($passwd, "");
	$subject = REQSTR($subject, "");
	//$content = REQSTR($content, "");
	$notice = REQSTR($notice, "N");
	$hidden = REQSTR($hidden, "N");
	$secret = REQSTR($secret, "N");

	$email1 = REQSTR($email1, "");
	$email2 = REQSTR($email2, "");
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


	isnull($act);
//	isnull($subject);


	if ($bc_autoreg_use == "Y") {
		if ( !isset($_SESSION['signupcode']) ) {
			$_POST['signupcode'] = "";
			$_SESSION['signupcode'] = "";
			unset ($_POST['signupcode']);
			unset ($_SESSION['signupcode']);
			alert_back("코드가 생성되지 않았습니다.");
			exit;
		}
		if ( ( !isset($_POST['signupcode']) ) || ( getLen($_POST['signupcode']) != 5 ) ) {
			$_POST['signupcode'] = "";
			$_SESSION['signupcode'] = "";
			unset ($_POST['signupcode']);
			unset ($_SESSION['signupcode']);
			alert_back("입력하신 자동등록방지 코드가 누락되었습니다.");
			exit;
		}
		if ( strtolower($_POST['signupcode']) != strtolower($_SESSION['signupcode']) ) {
			$dbcon -> dbcon_close();
			$_POST['signupcode'] = "";
			$_SESSION['signupcode'] = "";
			unset ($_POST['signupcode']);
			unset ($_SESSION['signupcode']);
			alert_back("코드가 일치하지 않습니다.".strtolower($_POST['signupcode'])." : ".($_SESSION['signupcode']));
			exit;
		}
		$_POST['signupcode'] = "";
		$_SESSION['signupcode'] = "";
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
	if ($bc_hidden_use == "Y" && $auth_hidden ) {
		if ( $hidden != "Y" ) { $hidden = "N"; }
	}
	else { $hidden = "N"; }

	#### 카테고리 설정
	if ( $bc_category_use == "Y" ) {
		//isnull($category);
	}


	#############################
	#### 파일 업로드 시작
	if ( $bc_upfile_image == "Y") {

		// 기존 파일 가져오기
		$SQL = " select imgfile from tbl_board_".$bc_id." where seq = '".$seq."' ";
		$FileRow = $dbcon -> fetch_array($dbcon -> query($SQL));
		$imgfile_old = $FileRow[imgfile];


		$ObjFileName = "imgfile";
		${"Arr_".$ObjFileName} = setFileName(${$ObjFileName."_old"});

		if ( count(${$ObjFileName."_del"}) > 0 ) {
			for ($i = 0; $i < count(${$ObjFileName."_del"}); $i++ ) {
				if ( getLen(${$ObjFileName."_del"}[$i]) > 0 ) {
					DeleteFile($upload_path."/".${"Arr_".$ObjFileName}[$i][1]);
					DeleteFile($upload_path."/".${"Arr_".$ObjFileName}[$i][1].".thumb");
					if ( $bc_upfile_image_thum == "Y" ) {
						DeleteFile($upload_path."/".${"Arr_".$ObjFileName}[$i][1].".thumb2");
					}
					${"Arr_".$ObjFileName}[$i] = "";
				}
			}
		}

		${"Result".$ObjFileName} = "";
		for ($i = 0; $i < count(${"Arr_".$ObjFileName}); $i++) {
			if ( is_array(${"Arr_".$ObjFileName}[$i]) ) {
				for ( $j = 0 ; $j < count(${"Arr_".$ObjFileName}[$i]) ; $j++ ) {
					${"Result".$ObjFileName} .= ${"Arr_".$ObjFileName}[$i][$j];
					if ($j < count(${"Arr_".$ObjFileName}[$i])-1) ${"Result".$ObjFileName} .=",";
				}
				if ($i < count(${"Arr_".$ObjFileName})-1) ${"Result".$ObjFileName} .="|";
			}
		}

		try {
			$upload = new upload($upload_path);
			$upload->define($_FILES[$ObjFileName]);
			$upload->makeThumbnailed($bc_upfile_image_width, $bc_upfile_image_height, "thumb");

			if ( $bc_upfile_image_thum == "Y" ) {
				$upload->makeThumbnailed($bc_upfile_image_thum_width, $bc_upfile_image_thum_height, "thumb2");
			}
			$upload->checkImageOnly();
			$upload->uploadedFiles();
			${"temp".$ObjFileName} = getFileName();
		}
		catch(Exception $e) {
			// 에러처리 구문
			exit($e->getMessage());
		}

		if ( getLen(${"Result".$ObjFileName}) > 0 ) {
			if (getLen(${"temp".$ObjFileName}) > 0 ) {
				${"temp".$ObjFileName} = ${"Result".$ObjFileName}."|".${"temp".$ObjFileName};
			}
			else {
				${"temp".$ObjFileName} = ${"Result".$ObjFileName};
			}
		}
		${$ObjFileName} = ${"temp".$ObjFileName};

	}

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
	}



//	echo "act : ".$act."<BR>";
//	echo "nick_name : ".$nick_name."<BR>";
//	echo "passwd : ".$passwd."<BR>";
//	echo "subject : ".$subject."<BR>";
//	echo "content : ".$content."<BR>";
//
//	echo "notice : ".$notice."<BR>";
//	echo "hidden : ".$hidden."<BR>";
//
//	exit;



	#############################
	#### 계층형 값 설정 시작

	//현재 글중에서 가장 큰 값을 가져온다.
	//가져온 값을 올림한뒤에 1000을 더한다.
	//올림(ceil)하는 이유는 원래 글이 삭제된 경우 1999 와 같은 값이 최대값이 된다.
	//그냥 1000만 더하게 되면 2999 라는 번호로 새글이 저장되게 된다.
	//1000 은 변경가능하며 999개의 답글이 가능함
	//depth는 새글이므로 0이다.
	$max_thread_fetch = $dbcon -> fetch_row( $dbcon -> query("select max(seq_sub) from tbl_board_".$bc_id) );
	$max_thread = ceil($max_thread_fetch[0]/1000)*1000+1000;
	$seq_level = 0;
	#### 계층형 값 설정 끝
	#############################
	$SQL = "
		insert into tbl_board_".$bc_id." set
			seq = NULL
			, seq_sub = '".$max_thread."'
			, seq_level = '".$seq_level."'
			, subject = '".$subject."'
			, content = '".$content."'
			, writer = '".$writer."'
			, passwd = ".$SQL_passwd."
			, nick_name = '".$nick_name."'
			, email1 = '".$email1."'
			, email2 = '".$email2."'
			, view_cnt = '0'

			, notice = '".$notice."'
		";
		if ($category){
		$SQL .= "	, category = '".$category."' ";
		}
		$SQL .="	, imgfile = '".$imgfile."'

			, regdate = NULL
			, secret = '".$secret."'
			, hidden = '".$hidden."'
			, ext1 = '".$ext1."'
			, ext2 = '".$ext2."'
			, ext3 = '".$ext3."'
			, ext4 = '".$ext4."'
			, ext5 = '".$ext5."'
			, ext6 = '".$ext6."'
			, ext7 = '".$ext7."'
			, ext8 = '".$ext8."'
			, ext9 = '".$ext9."'
			, ext10 = '".$ext10."'

			".$add_field."
	";
	$result = $dbcon -> query($SQL);
	if (!$result) {
		$dbcon -> dbcon_close();
		alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
		exit;
	}
//	echo "<xmp>".$SQL."</xmp><BR>";exit;
//	exit;
	$seq = mysqli_insert_id($dbcon->dbcon);
	//echo "seq : ".$seq."<BR>";


	#############################
	#### 파일 업로드 시작
	if ( $bc_upfile_cnt > 0 ) {
		if ($FileResult) {
			for ($i = 0 ; $i < count($up->returnValue); $i++) {
//				for ($j = 0 ; $j < count($up->returnValue[$i]); $j++) {
//					echo $up->returnValue[$i][$j]."<BR>";
//				}
				upfileUpload('board', $bc_id, $seq, $up->returnValue[$i][0], $up->returnValue[$i][1], $up->returnValue[$i][2]);
			}
		}
	}
	#### 파일 업로드 끝
	#############################

	@include_once($path_skin_board.$bc_skin."/write_update.php");

	#############################
	#### 메일 처리
	if ( $bc_email_use == "A" || $bc_email_use == "Y" ) {

		if ($email1 && $email2) {

			#### 작성자에게 메일전송
			$charset='UTF-8';
			$mail_subject = "요청하신 예약이 잘 접수 되었습니다. 신속하게 연락 드리겠습니다.";
			$toName=$nick_name;
			$toEmail=$email1."@".$email2;
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
			$body = str_replace("[goURL]", $sc_site_url.$bc_path, $body);

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
			$body = str_replace("[schDate]", REQSTR2(date("Y-m-d", $schDate)), $body);
			$body = str_replace("[schTime]", REQSTR2($Arr_TimeTime2[$schTime]), $body);

			$body = $mail_skin["header"].$body.$mail_skin["footer"];

			$return_mail = MailGo( $toName, $toEmail, $fromName, $fromEmail, $mail_subject, $body, $charset );

		}



		#### 관리자에게 메일전송
		$charset='UTF-8';
		$mail_subject = "[".$sc_name." > ".$bc_name."] ".$nick_name."님의 예약 이 등록되었습니다.";
		$toName=$sc_admin_name;
		$toEmail=$sc_admin_email;
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
		$body = str_replace("[goURL]", $sc_site_url.$bc_path, $body);

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
		$body = str_replace("[ext10]", REQSTR2($ext10), $body);
		$body = str_replace("[ext10]", REQSTR2($ext10), $body);
		$body = str_replace("[schDate]", REQSTR2(date("Y-m-d", $schDate)), $body);
		$body = str_replace("[schTime]", REQSTR2($Arr_TimeTime2[$schTime]), $body);


		$body = $mail_skin["header"].$body.$mail_skin["footer"];


		$return_mail = MailGo( $toName, $toEmail, $fromName, $fromEmail, $mail_subject, $body, $charset );


	}




	$dbcon -> dbcon_close();

//	echo "메일전송 테스트중";
//	exit;

	//if ( $bc_category_use == "Y" ) $parameter .= "&search_category=".$category;

	if ( !getLen($bc_url_writeok) ) $bc_url_writeok = "?mode=list&page=1".$parameter;
	if ( $auth_level >= $auth_admin) $bc_url_writeok = "?mode=list&page=1".$parameter;


	$dbcon -> dbcon_close();
	alert_page("등록되었습니다.", $bc_url_writeok, "parent");
?>

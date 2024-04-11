<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$list_type = "gallery";// 이미지 width ( 갤러리일때 사용하면 좋다.)
	$image_view_width = 600;
	$Arr_bc_upfile_ext_upload			= explode(",",$bc_upfile_ext_upload);			// 제한 확장자
	$bc_upfile_size						= 1024 * 1024 * $bc_upfile_size;				// 제한 사이즈
	$upload_path							= $path_data."board/".$bc_id;					// 업로드 폴더
	$upload_url								= $url_data."board/".$bc_id;					// 업로드 폴더
	// 업로드 사이즈
	$upload_size = 1024 * 1024 * 2;// "2048000"
	$Stop_Extension		= explode(",", $bc_upfile_ext_upload);
	$Stop_Size				= 1024 * 1024 * 10;

	$UpFileDirectory		= $path_root."_data/bbs/".$bc_id."/";
	$UpFileCategory		= $bc_id;

	$mode = REQSTR($mode, "");
	$act = REQSTR($act, "");
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

	$pc_url = REQSTR($pc_url, "");

	$MEMRS = getMemberInfo("u_id", $ss_u_id);
	$now_writer			= $MEMRS[u_id];
	$now_nick_name		= $MEMRS[u_name];
	$now_passwd			= $MEMRS[u_pw];
	$now_email1			= $MEMRS[u_email1];
	$now_email2			= $MEMRS[u_email2];
	$now_homepage		= $MEMRS[u_homepage];
	unset($MEMRS);

	$passwd = $now_passwd;

	if($mode == "write_ok"){
		isnull($act);
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
			isnull($category);
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
				, pc_url = '".$pc_url."'

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


		if ( !getLen($bc_url_writeok) ) $bc_url_writeok = "popup_event_list.php?mode=list&page=1".$parameter;
		if ( $auth_level >= $auth_admin) $bc_url_writeok = "popup_event_list.php?mode=list&page=1".$parameter;


		$dbcon -> dbcon_close();
		alert_page("등록되었습니다.", $bc_url_writeok);
	} else if($mode == "mod_ok") {
		
		isnull($seq);
		isnull($act);
		#############################
		#### 기존데이타 가져오기 시작
		$SQL = "
			select
				writer
			from
				tbl_board_".$bc_id."
			where
				seq = '".$seq."'
			order by seq desc
			limit 0, 1
		";
		$OldRs = $dbcon -> fetch_row($dbcon -> query($SQL));
		$Old_writer = $OldRs[0];
		#### 기존데이타 가져오기 끝
		#############################
	//	echo "auth_level : ".$auth_level."<BR>";
	//	echo "auth_admin : ".$auth_admin."<BR>";
	//	echo "now_writer : ".$now_writer."<BR>";
	//	echo "Old_writer : ".$Old_writer."<BR>";
	//	echo "passwd : ".$passwd."<BR>";
		#############################
		#### 비밀번호 체크 시작
		//if ( $auth_level >= $auth_admin || ( $now_writer == $writer && $now_writer != "guest" ) ) {
		if ( $auth_level < $auth_admin && ( $now_writer != $Old_writer || $now_writer == "guest" )) {
			//isnull($passwd);
			//exit;
			$SQL = "
				select
					count(*)
				from
					tbl_board_".$bc_id."
				where
					seq = '".$seq."'
					and passwd = password('".$passwd."')
				order by seq desc
				limit 0, 1
			";
			$PassRs = $dbcon -> getCount($SQL);

	//		echo $SQL."<BR>";
	//		echo $PassRs."<BR>";
	//		exit;
			if ($PassRs == 0) {
				$dbcon -> dbcon_close();
				alert_back("비밀번호가 다릅니다.");
				exit;
			}
		}
		#### 비밀번호 체크 끝
		#############################

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
	//		isnull($category);
		}


		// 에디터 설정 ( 내용 설정)
		if ($bc_editor_use == "Y") {
		}
		else {
			$content = REQSTR($content, "");
		}
		if($bc_id != "qna") {
			isnull($content);
		}

		#############################
		#### 파일 업로드 시작

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

		if ( count($pr_img_thum_del) > 0 ) {
			for ($i = 0; $i < count($pr_img_thum_del); $i++ ) {
				if ( getLen($pr_img_thum_del[$i]) > 0 ) {
					DeleteFile($path_product_data.$Arr_pr_img_thum[$i][0]."/".$Arr_pr_img_thum[$i][1]);
					$Arr_pr_img_thum[$i] = "";
				}
			}
		}


		$Result_pr_img_thum = "";
		for ($i = 0; $i < count($Arr_pr_img_thum); $i++) {
			if ( is_array($Arr_pr_img_thum[$i] ) && getLen($Arr_pr_img_thum[$i][1]) > 0 ) {
				for ( $j = 0 ; $j < count($Arr_pr_img_thum[$i]) ; $j++ ) {
					$Result_pr_img_thum .= $Arr_pr_img_thum[$i][$j];
					if ($j < count($Arr_pr_img_thum[$i])-1) $Result_pr_img_thum .=",";
				}
				if ($i < count($Arr_pr_img_thum)-1) $Result_pr_img_thum .="|";
			}
		}

		try {
			$upload = new upload($path_product_data);
			$upload->define($_FILES['pr_img_thum']);
			$upload->makeThumbnailed(130, 98, "thumb_13098");
		//	$upload->makeThumbnailed(100, 100, "thumb_100");
		//	$upload->makeThumbnailed(200, 200, "thumb_200");
		//	$upload->makeThumbnailed(300, 300, "thumb_300");
		//	$upload->makeThumbnailed(400, 400, "thumb_400");
			$upload->checkImageOnly();
			$upload->uploadedFiles();
			$tempFile = getFileName();
		}
		catch(Exception $e) {
			// 에러처리 구문
			exit($e->getMessage());
		}

		if ( $bc_upfile_cnt > 0 ) {

			for ($i = 0 ; $i < count($del_upfile) ; $i++ ) {
				//echo "del_upfile : ".$del_upfile[$i]."<BR>";
				upfileDelete($del_upfile[$i]);
			}

			// 신규 파일 등록
			$up = new FileUploadManager;
			$up->SetExceptExtension($Arr_bc_upfile_ext_upload);
			$up->SetExceptFileSize($bc_upfile_size);
			$up->SetFiles($_FILES["upfile"]);
			$up->SetDirectory($upload_path);
			$FileResult = $up->UploadManager();
	//		echo "FileResult : " .$FileResult."<BR>";

			// 신규 파일 DB 등록
			if ($FileResult) {
				for ($i = 0 ; $i < count($up->returnValue); $i++) {
	//				for ($j = 0 ; $j < count($up->returnValue[$i]); $j++) {
	//					echo $up->returnValue[$i][$j]."<BR>";
	//				}
					upfileUpload('board', $bc_id, $seq, $up->returnValue[$i][0], $up->returnValue[$i][1], $up->returnValue[$i][2]);
				}
			}
	//		echo count($up->returnValue)."<BR>";
	//		echo $bc_id."<BR>";
	//		echo $seq."<BR>";
	//		exit;
		}
		#### 파일 업로드 끝
		#############################

		if ($nick_name){
			$add_query .= ", nick_name = '".$nick_name."' ";
		}
		if ($writer){
			$add_query .= ", writer = '".$writer."' ";
		}

		$SQL = "
			update tbl_board_".$bc_id."
			set
				subject = '".$subject."'
				, notice = '".$notice."'
				, hidden = '".$hidden."'
			";
			if ($content){
			$SQL .= "	, content = '".$content."' ";
			}
			if ($category){
			$SQL .= "	, category = '".$category."' ";
			}
			$SQL .="
				, secret = '".$secret."'
				, imgfile = '".$imgfile."'

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
				, pc_url = '".$pc_url."'
				$add_query
			where
				seq = '".$seq."'
		";
	//	echo $SQL;
	//	exit;
		$result = $dbcon -> query($SQL);
		if (!$result) {
			$dbcon -> dbcon_close();
			//echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
			//echo "에러";
			alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
			exit;
		}
		//echo "<xmp>".$SQL."</xmp><BR>";exit;

		//추가 프로그램 생성
		@include_once($path_skin_board.$bc_skin."/write_update.php");

		$dbcon -> dbcon_close();

		if ( $now_writer == "guest" ) {
			alert_page("수정되었습니다.","popup_event_list.php?mode=list&page=".$page.$parameter);
		} else {
			alert_page("수정되었습니다.","popup_event_list.php?mode=view&seq=".$seq."&page=".$page.$parameter);
		}
	} else if($mode == "del_ok") {
		isnull($seq);
		#############################
		#### 기존데이타 가져오기 시작
		$SQL = "
		select
			writer, hidden, imgfile
		";
		if ( $bc_id == "07_beinfo") {
			$SQL .= "
				, ext1
			";
		}

		$SQL .= "
			from
				tbl_board_".$bc_id."
			where
				seq = '".$seq."'
			order by seq desc
			limit 0, 1
		";
		$OldRs = $dbcon -> fetch_row($dbcon -> query($SQL));

		//		echo $SQL."<BR>";
		$Old_writer = $OldRs[0];
		$Old_hidden = $OldRs[1];
		$imgfile = $OldRs[2];

		if ( $bc_id == "07_beinfo") {
			$ext1 = $OldRs[3];
		}

		//	echo "hidden : ".$OldRs[0]."<BR>";
		//	exit;
		#### 기존데이타 가져오기 끝
		#############################


		#############################
		#### 비밀번호 체크 시작
		if ( $auth_level < $auth_admin && ( $now_writer != $Old_writer || $now_writer == "guest" )) {
			isnull($act);
			isnull($passwd);
			$SQL = "
				select
					count(*)
				from
					tbl_board_".$bc_id."
				where
					seq = '".$seq."'
					and passwd = password('".$passwd."')
				order by seq desc
				limit 0, 1
			";
			$PassRs = $dbcon -> getCount($SQL);

		//		echo $SQL."<BR>";
		//		echo $PassRs."<BR>";
		//		exit;
			if ($PassRs == 0) {
				$dbcon -> dbcon_close();
				alert_back("비밀번호가 다릅니다.");
				exit;
			}
		}
		#### 비밀번호 체크 끝
		#############################



		if ($bc_state_use == "Y" && $Old_hidden != "D") {
			$DSQL = "
				update tbl_board_".$bc_id."
				set
					hidden = 'D'
				where
					seq = '".$seq."'
			";
		}
		else {

			#############################
			#### 파일 삭제 처리

			$ObjFileName = "imgfile";
			${"Arr_".$ObjFileName} = setFileName(${$ObjFileName});
			for ($i = 0; $i < count(${"Arr_".$ObjFileName}); $i++ ) {
				DeleteFile($upload_path."/".${"Arr_".$ObjFileName}[$i][1]);
				if ( $bc_upfile_image_thum == "Y" ) {
					DeleteFile($upload_path."/".${"Arr_".$ObjFileName}[$i][1].".thumb");
				}
			}




			if ( $bc_id == "07_beinfo") {
				$ObjFileName = "ext1";
				${"Arr_".$ObjFileName} = setFileName(${$ObjFileName});
				for ($i = 0; $i < count(${"Arr_".$ObjFileName}); $i++ ) {
					DeleteFile($upload_path."/".${"Arr_".$ObjFileName}[$i][1]);
					if ( $bc_upfile_image_thum == "Y" ) {
						DeleteFile($upload_path."/".${"Arr_".$ObjFileName}[$i][1].".thumb");
					}
				}

			}

			$SQL = "
				select idx
				from
					tbl_file
				where
					category='board' and bc_id = '".$bc_id."' and seq = '".$seq."'
			";
			$result = $dbcon -> query($SQL);
			while($rows = $dbcon -> fetch_row($result) ) {
				upfileDelete($rows[0]);
			}

			#############################

			#############################
			#### 코멘트 삭제 처리
			$SQL = "
				delete from tbl_comment
				where
					category='board' and bc_id = '".$bc_id."' and seq = '".$seq."'
			";
			$dbcon -> query($SQL);
			#############################


			$DSQL = "
				delete from tbl_board_".$bc_id."
				where
					seq = '".$seq."'
			";
		}

		//echo $DSQL."<BR>";exit;

		$result = $dbcon -> query($DSQL);
		if (!$result) {
			$dbcon -> dbcon_close();
			//echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
			//echo "에러";
			alert_back("삭제 오류입니다. 관리자에게 문의하여 주십시오.");
			exit;
		}

		$dbcon -> dbcon_close();

		@include_once($path_skin_board.$bc_skin."/delete_update.php");

		alert_page("삭제되었습니다.","popup_event_list.php?mode=list".$parameter);
	}
	?>

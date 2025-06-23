<?

	$seq = REQSTR($seq, "");
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

	isnull($seq);
	isnull($act);
//	isnull($subject);

	if ($bc_autoreg_use == "Y") {
		if (
			( !isset($_POST['signupcode']) ) || ( getLen($_POST['signupcode']) != 5 ) || ( !isset($_SESSION['signupcode']) ) || ( strtolower($_POST['signupcode']) != $_SESSION['signupcode'] )
		) {
			$dbcon -> dbcon_close();
			alert_back("코드가 일치하지 않습니다.");
			exit;
		}
		$_POST['signupcode'] = "";
		$_SESSION['signupcode'] = "";
		unset ($_POST['signupcode']);
		unset ($_SESSION['signupcode']);
	}


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





//	if ( $bc_upfile_image == "Y" ) {
//		if ( getLen($_FILES["upfile"]["name"][0]) == 0 ) {
//			alert_back("이미지 파일을 올려주세요.");
//		}
//	}

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
		alert_page("수정되었습니다.","?mode=list&page=".$page.$parameter, "parent");
	} else {
		alert_page("수정되었습니다.","?mode=view&seq=".$seq."&page=".$page.$parameter, "parent");
	}
?>

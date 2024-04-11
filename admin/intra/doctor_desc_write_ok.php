<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$idx						= REQSTR($_POST[idx], "");
	$area_code				= REQSTR($_POST[area_code], "");
	$member_id				= REQSTR($_POST[member_id], "");
	$desc_txt				= $_POST[desc_txt];
	$book1					= $_POST[book1];
	$book2					= $_POST[book2];

	$parameter = "&search_u_level=".$search_u_level."&search_u_gubun=".$search_u_gubun."&search_u_state=".$search_u_state."&search_u_sex=".$search_u_sex."&search=".$search."&search_text=".$search_text."&search_orderby=".$search_orderby."&search_sort=".$search_sort."&num_per_page=".$num_per_page;

	isnull($area_code);
	isnull($member_id);

	if ($_FILES['doc_file']["tmp_name"]) {
		$upload_root = $_SERVER[DOCUMENT_ROOT]."/_data/doctor_pic";
		$up_file1 = up_img($_FILES['doc_file']["tmp_name"],$upload_root,$upload_size,"",time());
		$up_file1_name = $_FILES['doc_file']["name"];
		$add_pic = " , doctor_pic = '".$up_file1."' ";
	}

// 정보수정
	if ( getLen($idx) ) {

		$SQL = "
			update tbl_desc_doctor
			set
				member_id = '".$member_id."'
				, member_name = '".$member_name."'
				, desc_txt = '".$desc_txt."'
				, book1 = '".$book1."'
				, book2 = '".$book2."'
				, area_code = '".$area_code."'
				$add_pic
			where
				idx = '".$idx."'
		";
		$result = $dbcon -> query($SQL);
		if (!$result) {
			$dbcon -> dbcon_close();
			alert_back("데이터에 오류가 있어 수정이 되지 않습니다.");
			exit;
		}

	}else{
// 새로 입력
		isnull($area_code);


		$SQL = "
					INSERT INTO tbl_desc_doctor set
					member_id = '".$member_id."'
					, member_name = '".$member_name."'
					, desc_txt = '".$desc_txt."'
					, book1 = '".$book1."'
					, book2 = '".$book2."'
					, area_code = '".$area_code."'
					, doctor_pic = '".$up_file1."'
				";
//		echo "SQL : ".$SQL."<BR>";
//		exit;
		$result = $dbcon -> query($SQL);



		// 쿼리 에러시에 해당 아이디 모두 삭제
		if (!$result) {
			$dbcon -> dbcon_close();
			alert_back("데이터에 오류가 있어 입력이 되지 않습니다.");
			exit;
		}
	}

	$dbcon -> dbcon_close();

	// 정보수정
	if ( strlen($idx) > 0 ) {
		alert_page("의료진의 정보가 수정되었습니다.", "doctor_desc_write.php?idx=".$idx);
	}
	// 신규가입
	else {
		alert_page("", "doctor_desc.php?$parameter");
	}
?>

<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$idx						= REQSTR($_POST[idx], "");
	$area_code				= REQSTR($_POST[area_code], "");
	$a_tel					= REQSTR($_POST[a_tel], "");
	$a_time1					= REQSTR($_POST[a_time1], "");
	$a_time1_2				= REQSTR($_POST[a_time1_2], "");
	$a_time1_3				= REQSTR($_POST[a_time1_3], "");
	$a_time1_4				= REQSTR($_POST[a_time1_4], "");
	$a_time1_5				= REQSTR($_POST[a_time1_5], "");
	$a_time2					= REQSTR($_POST[a_time2], "");
	$a_time3					= REQSTR($_POST[a_time3], "");
	$a_time4					= REQSTR($_POST[a_time4], "");
	$a_time5					= REQSTR($_POST[a_time5], "");
	$park_desc				= REQSTR($_POST[park_desc], "");
	$addr_zip				= REQSTR($_POST[addr_zip], "");
//	$addr_load				= REQSTR($_POST[addr_load], "");
	$addr_load				= $_POST[addr_load];
	$bus_txt					= $_POST[bus_txt];
	$subway_txt			= $_POST[subway_txt];
	$car_txt					= $_POST[car_txt];
	$sms_info				= $_POST[sms_info];
	$home_url				= $_POST[home_url];

	// 진료원장 받기
	for ($k=0;$k<sizeof($doctors);$k++){
		if ($doctors[$k]){
			$doctors_list .= "||".$doctors[$k];
		}
	}
	$doctors_list = substr($doctors_list,2);

	isnull($area_code);


	#### 파일처리 Start
	if ( getLen($imgfile) > 0 ) {
		$ObjFileName = "imgfile";
		$upload_path = $path_data."area/";
		$bc_upfile_image_width = "86";
		$bc_upfile_image_height = "125";
		try {
			$upload = new upload($upload_path);
			$upload->define($_FILES[$ObjFileName]);
			$upload->makeThumbnailed($bc_upfile_image_width, $bc_upfile_image_height, "thumb");
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
//	echo $imgfile;
	if ($imgfile){
		$ar_image	= explode(",",$imgfile);
		$u_image	= $ar_image[1];
	}
	if ($u_image){
		$SQL_pw_set .= "
			, area_pic = '".$u_image."'
		";
	}
	#### 파일처리 End


// 정보수정
	if ( getLen($idx) ) {

		$SQL = "
			update tbl_desc_area
			set
				a_tel = '".$a_tel."'
				, a_time1 = '".$a_time1."'
				, a_time1_2 = '".$a_time1_2."'
				, a_time1_3 = '".$a_time1_3."'
				, a_time1_4 = '".$a_time1_4."'
				, a_time1_5 = '".$a_time1_5."'
				, a_time2 = '".$a_time2."'
				, a_time3 = '".$a_time3."'
				, a_time4 = '".$a_time4."'
				, a_time5 = '".$a_time5."'
				, a_lunch_time1 = '".$a_lunch_time1."'
				, a_lunch_time2 = '".$a_lunch_time2."'
				, addr_zip = '".$addr_zip."'
				, addr_load = '".$addr_load."'
				, park_desc = '".$park_desc."'
				, bus_txt = '".$bus_txt."'
				, subway_txt = '".$subway_txt."'
				, car_txt = '".$car_txt."'
				, sms_info = '".$sms_info."'
				, doctors = '".$doctors_list."'
				, home_url = '".$home_url."'
				".$SQL_pw_set."
			where
				idx = '".$idx."'
		";
//		echo "SQL : ".$SQL."<BR>";
//		exit;
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
					INSERT INTO tbl_desc_area set
					a_tel = '".$a_tel."'
					, a_time1 = '".$a_time1."'
					, a_time1_2 = '".$a_time1_2."'
					, a_time1_3 = '".$a_time1_3."'
					, a_time1_4 = '".$a_time1_4."'
					, a_time1_5 = '".$a_time1_5."'
					, a_time2 = '".$a_time2."'
					, a_time3 = '".$a_time3."'
					, a_time4 = '".$a_time4."'
					, a_time5 = '".$a_time5."'
					, a_lunch_time1 = '".$a_lunch_time1."'
					, a_lunch_time2 = '".$a_lunch_time2."'
					, addr_zip = '".$addr_zip."'
					, addr_load = '".$addr_load."'
					, park_desc = '".$park_desc."'
					, bus_txt = '".$bus_txt."'
					, subway_txt = '".$subway_txt."'
					, car_txt = '".$car_txt."'
					, sms_info = '".$sms_info."'
					, doctors = '".$doctors_list."'
					, area_code = '".$area_code."'
					, home_url = '".$home_url."'
					".$SQL_pw_set."
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
		alert_page("지점의 정보가 수정되었습니다.", "area_desc_write.php?idx=".$idx);
	}
	// 신규가입
	else {
		alert_page("", "area_desc.php");
	}
?>

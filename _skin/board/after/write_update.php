<?
/*=========================================================

여기에는 추가로 작성되는 컬럼이나 각 게시판의 특성을 나타내서 만들어 내도록 한다.

아래의 것은 예제로 이니 참고 하도록... by JHW

=========================================================*/
		$upload_path = $_SERVER["DOCUMENT_ROOT"]."/_data/board/gallery/";
		$bc_upfile_image_width = "298";
		$bc_upfile_image_height = "198";
//echo $b_file."전파일<br>";
	if ($b_file) {
		$ObjFileName = "b_file";
		try {
			$upload = new upload($upload_path);
			$upload->define($_FILES[$ObjFileName],"b","1");
			$upload->makeThumbnailed($bc_upfile_image_width, $bc_upfile_image_height, "thumb");
			$upload->checkImageOnly();
			$upload->uploadedFiles();
			$b_imgfile = getFileName();
		}
		catch(Exception $e) {
			// 에러처리 구문
			exit($e->getMessage());
		}

		if ($b_imgfile){
			$bar_image	= explode(",",$b_imgfile);
			$b_filename	= $bar_image[1];
		}
		// 정보 업데이트
		$sql = "update tbl_board_".$bc_id." set  b_file='".$b_filename."' where seq= ".$seq."";
//		echo $sql."전<br/>";
		$result = $dbcon -> query($sql);
		if (!$result) {
			$dbcon -> dbcon_close();
			echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
			//echo "에러";
			alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
			exit;
		}

	}
//echo $a_file."후파일<br>";
	if ($a_file) {
		$ObjFileName = "a_file";

		try {
			$upload = new upload($upload_path);
			$upload->define($_FILES[$ObjFileName],"a","2");
			$upload->makeThumbnailed($bc_upfile_image_width, $bc_upfile_image_height, "thumb");
			$upload->checkImageOnly();
			$upload->uploadedFiles();
			$a_imgfile = getFileName();
//			echo getFileName()."<br/>";
		}
		catch(Exception $e) {
			// 에러처리 구문
			exit($e->getMessage());
		}

		if ($a_imgfile){
			$aar_image	= explode(",",$a_imgfile);
			$a_filename	= $aar_image[1];
		}
		// 정보 업데이트
		$sql = "update tbl_board_".$bc_id." set  a_file='".$a_filename."' where seq= ".$seq."";
//		echo $sql."후<br/>";
		$result = $dbcon -> query($sql);
		if (!$result) {
			$dbcon -> dbcon_close();
			echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
			//echo "에러";
			alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
			exit;
		}

	}
?>

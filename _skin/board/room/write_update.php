<?
/*=========================================================

여기에는 추가로 작성되는 컬럼이나 각 게시판의 특성을 나타내서 만들어 내도록 한다.

아래의 것은 예제로 이니 참고 하도록... by JHW

=========================================================*/



//echo "<script>alert('안녕하세요');</script>";

//$sql = "update tbl_board_".$bc_id." set where seq= ".$seq.""
//	$result = $dbcon -> query($sql);
//	if (!$result) {
//		$dbcon -> dbcon_close();
//		//echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
//		//echo "에러";
//		alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
//		exit;
//	}

$sql = "update tbl_board_".$bc_id." set ";
$sql .= " day_m1_1 ='".$day_m1_1."',day_m1_2 ='".$day_m1_2."',day_m1_3 ='".$day_m1_3."' ";
$sql .= " ,day_m2_1 ='".$day_m2_1."',day_m2_2 ='".$day_m2_2."',day_m2_3 ='".$day_m2_3."'  ";
$sql .= " ,day_m3_1 ='".$day_m3_1."',day_m3_2 ='".$day_m3_2."',day_m3_3 ='".$day_m3_3."'  ";
$sql .= " where seq= ".$seq."";
//echo $sql;
	$result = $dbcon -> query($sql);
	if (!$result) {
		$dbcon -> dbcon_close();
		//echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
		//echo "에러";
		alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
		exit;
	}


// 이미지 삭제
for ($k = 1; $k<13;$k++){
	$ObjFiles = "del_file".$k;
//	echo getLen(${$ObjFiles})."<br>";
	if ( getLen(${$ObjFiles}) > 0 ) {
		$ar_filename = setFileName(${$ObjFiles});
		DeleteFile($upload_path."/".$ar_filename[$i][1]);
		$SQL = "update tbl_board_".$bc_id." set file".$k."  = '' where seq = ".$seq." ";
//		echo $SQL."<br>";
		$result = $dbcon -> query($SQL);
	}
}


// 이미지 업데이트
for ($k = 1; $k<13;$k++){
	$ObjFiles = "file".$k;
	if ( getLen(${$ObjFiles}) > 0 ) {
		try {
//			echo $upload_path."<br>";
			$upload = new upload($upload_path);
			$upload->define($_FILES[$ObjFiles], $ObjFiles, $k);

			if ( $bc_upfile_image_thum == "Y" ) {
				$upload->makeThumbnailed(692, 461, "1thumb");
				$upload->makeThumbnailed(162, 106, "2thumb");
				$upload->makeThumbnailed(50, 50, "3thumb");
			}
			$upload->checkImageOnly();
			$upload->uploadedFiles();
			${$ObjFiles} = getFileName();

			$SQL = "update tbl_board_".$bc_id." set file".$k."  = '".${$ObjFiles}."' where seq = ".$seq." ";
//			echo $SQL."<br>";
			$result = $dbcon -> query($SQL);

		}
		catch(Exception $e) {
			// 에러처리 구문
			exit($e->getMessage());
		}
	}
}
?>
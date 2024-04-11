<?
/*=========================================================

���⿡�� �߰��� �ۼ��Ǵ� �÷��̳� �� �Խ����� Ư���� ��Ÿ���� ����� ������ �Ѵ�.

�Ʒ��� ���� ������ �̴� ���� �ϵ���... by JHW

=========================================================*/



if ( $bc_upfile_image == "Y") {

	// 기존 파일 가져오기
	$SQL = " select imgfile2 from tbl_board_".$bc_id." where seq = '".$seq."' ";
	$FileRow = $dbcon -> fetch_array($dbcon -> query($SQL));
	$imgfile2_old = $FileRow[imgfile2];


	$ObjFileName2 = "imgfile2";
	${"Arr_".$ObjFileName2} = setFileName(${$ObjFileName2."_old"});
	
	if ( count(${$ObjFileName2."_del"}) > 0 ) {
		for ($i = 0; $i < count(${$ObjFileName2."_del"}); $i++ ) {
			if ( getLen(${$ObjFileName2."_del"}[$i]) > 0 ) {
				DeleteFile($upload_path."/".${"Arr_".$ObjFileName2}[$i][1]);
				DeleteFile($upload_path."/".${"Arr_".$ObjFileName2}[$i][1].".thumb");
				if ( $bc_upfile_image_thum == "Y" ) {
					DeleteFile($upload_path."/".${"Arr_".$ObjFileName2}[$i][1].".thumb2");
				}
				${"Arr_".$ObjFileName2}[$i] = "";
			}
		}
	}
	

	${"Result".$ObjFileName2} = "";
	for ($i = 0; $i < count(${"Arr_".$ObjFileName2}); $i++) {
		if ( is_array(${"Arr_".$ObjFileName2}[$i]) ) {
			for ( $j = 0 ; $j < count(${"Arr_".$ObjFileName2}[$i]) ; $j++ ) {
				${"Result".$ObjFileName2} .= ${"Arr_".$ObjFileName2}[$i][$j];
				if ($j < count(${"Arr_".$ObjFileName2}[$i])-1) ${"Result".$ObjFileName2} .=",";
			}
			if ($i < count(${"Arr_".$ObjFileName2})-1) ${"Result".$ObjFileName2} .="|";
		}
	}

	try {
		
		$upload = new upload($upload_path);
		$upload->define($_FILES[$ObjFileName2],"m");
		$upload->makeThumbnailed($bc_upfile_image_width, $bc_upfile_image_height, "thumb");

		if ( $bc_upfile_image_thum == "Y" ) {
			$upload->makeThumbnailed($bc_upfile_image_thum_width, $bc_upfile_image_thum_height, "thumb2");
		}
		$upload->checkImageOnly();
		$upload->uploadedFiles();
		${"temp".$ObjFileName2} = getFileName();
		
	}
	catch(Exception $e) {
		// 에러처리 구문
		exit($e->getMessage());
	}

	if ( getLen(${"Result".$ObjFileName2}) > 0 ) {
		if (getLen(${"temp".$ObjFileName2}) > 0 ) {
			${"temp".$ObjFileName2} = ${"Result".$ObjFileName2}."|".${"temp".$ObjFileName2};
		}
		else {
			${"temp".$ObjFileName2} = ${"Result".$ObjFileName2};
		}
	}
	${$ObjFileName2} = ${"temp".$ObjFileName2};

}

$sql = "update tbl_board_".$bc_id." set ins_name_en='".$ins_name_en."', imgfile2='".$imgfile2."' where seq= ".$seq."";
$result = $dbcon -> query($sql);
if (!$result) {
	$dbcon -> dbcon_close();
	//echo "����<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
	//echo "����";
	alert_back("��� �����Դϴ�. �����ڿ��� �����Ͽ� �ֽʽÿ�.");
	exit;
}
?>
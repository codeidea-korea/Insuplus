<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	//==================================================================================================
	//엑셀업로드  시작
	//==================================================================================================
	error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_USER_DEPRECATED);
	include $_SERVER["DOCUMENT_ROOT"]."/_util/PHPExcel-1.8/Classes/PHPExcel.php";
    if ($_FILES["file1"]){
		// 파일이 있는지 확인후 복사
        if($_FILES["file1"]["name"]) {

            $filename = $_FILES["file1"]["name"];
            $tmp_file = $_FILES["file1"]["tmp_name"];
            $filesize = $_FILES["file1"]["size"];

            $UpFilePathInfo = pathinfo($filename);
            $UpFileExt = strtolower($UpFilePathInfo["extension"]);

            //  확장자 체크 : csv파일이 아니면 history(-1)
            $file_info = explode(".", $filename);
            $filename = "xls_group_upload".date("YmdHis",time()).".".$UpFileExt;

            @move_uploaded_file($tmp_file, $_SERVER["DOCUMENT_ROOT"]."/_data/group_join/$filename");   //파일복사
            @unlink($tmp_file);

            $url = $_SERVER["DOCUMENT_ROOT"]."/_data/group_join/".$filename;
            echo $url;
        }
	}
?>
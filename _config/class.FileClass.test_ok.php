<?php
	header("Content-Type: text/html; charset=UTF-8");

	include "class.FileClass.php";

	$config["upload"]["path"] = $_SERVER["DOCUMENT_ROOT"]."/_data/";
	$config["upload"]["size"] = 2*1024*1024;
	$config["upload"]["ext"] = "jpeg, wmv";
	$config["upload"]["extbw"] = "b";

	$FC = new FileClass();
	$FC -> setUpPath($config["upload"]["path"]);
	$FC -> setMaxSize($config["upload"]["size"]);
	$FC -> setExtList($config["upload"]["ext"], $config["upload"]["extbw"]);


	$fileInfo = $FC -> fileUpload($_FILES['userfile']);

	if ( !$fileInfo ) {
		echo "업로드 실패<BR>";
		echo "Error : ".$FC -> Error."<BR>";
		echo "name : ".$_FILES['userfile']['name'] ."<BR>";
		echo "type : ".$_FILES['userfile']['type'] ."<BR>";
		echo "size : ".$_FILES['userfile']['size'] ."<BR>";
		echo "tmp_name : ".$_FILES['userfile']['tmp_name'] ."<BR>";
		echo "error : ".$_FILES['userfile']['error'] ."<BR>";
	}
	else {
		echo "업로드 성공<BR>";
		echo "realname : ".$fileInfo["realname"]."<BR>";
		echo "name : ".$fileInfo["name"]."<BR>";
		echo "size : ".$fileInfo["size"]."<BR>";
		echo "location : "."<BR>";
	}


// 클라이언트 머신에 존재하는 파일의 원래 이름.
echo "name : ".$_FILES['userfile']['name'] ."<BR>";

// 브라우저가 이 정보를 제공할 경우에, 파일의 mime 형식. 예를 들면 "image/gif". 그러나 이 mime 형은 PHP 측에서 확인하지 않으므로 이 값을 신용하지 마십시오.
echo "type : ".$_FILES['userfile']['type'] ."<BR>";

// 업로드된 파일의 바이트로 표현한 크기.
echo "size : ".$_FILES['userfile']['size'] ."<BR>";

// 서버에 저장된 업로드된 파일의 임시 파일 이름.
echo "tmp_name : ".$_FILES['userfile']['tmp_name'] ."<BR>";

// 파일 업로드에 관련한 에러 코드. PHP 4.2.0에서 추가되었습니다.
// http://kr2.php.net/manual/kr/features.file-upload.errors.php
echo "error : ".$_FILES['userfile']['error'] ."<BR>";

// 업로드 진행바
// http://kr2.php.net/manual/kr/apc.configuration.php#ini.apc.rfc1867


$uploaddir = $_SERVER["DOCUMENT_ROOT"].'/_data/';
echo "uploaddir : ".$uploaddir."<BR>";
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
echo "uploadfile : ".$uploadfile."<BR>";

$MAX_FILE_SIZE = $_POST["MAX_FILE_SIZE"];

echo "MAX_FILE_SIZE :".$MAX_FILE_SIZE."<BR>";

echo '<pre>';
if ( !is_dir($uploaddir) ) {
	echo "업로드 경로 설정이 잘못되었습니다.<BR>";
}

if ( is_uploaded_file($_FILES['userfile']['tmp_name']) ) {
	if ( !move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
		echo "파일 업로드에 실패하였습니다.<BR>";
	}

	echo "업로드 성공<BR>";
}
echo "error : ".$_FILES['userfile']['error'] ."<BR>";

echo '자세한 디버깅 정보입니다:';
//@unlink($_FILES['userfile']['tmp_name']);
print_r($_FILES);

print "</pre>";

?>
<?php


//	if ( !is_dir($uploaddir.'aaa/bbb') ) {
//		mkdir($uploaddir.'aaa/bbb', 0777);
//	}

if ( is_dir($uploaddir) ) {
//	$fh = fopen($uploaddir.'test.html', 'a');
//	fwrite($fh, '<h1>Hello world!</h1>');
//	fclose($fh);
//	echo $th."<BR>";
//	unlink($uploaddir.'test.html');

	if ( !is_dir($uploaddir.'testdir') ) {
		mkdir($uploaddir.'testdir', 0777);
	}
	else {
		rmdir($uploaddir.'testdir');
	}

	if ( is_dir($uploaddir.'testdir') ) {
		//rmdir($uploaddir.'testdir');
	}
}


?>
<?php
/*
<form action="" method="post" enctype="multipart/form-data">
<p>그림들:
<input type="file" name="pictures[]" />
<input type="file" name="pictures[]" />
<input type="file" name="pictures[]" />
<input type="submit" name="전송" />
</p>
</form>
<?php
foreach ($_FILES["pictures"]["error"] as $key => $error) {
    if ($error = UPLOAD_ERR_OK) {
        $tmp_name = $_FILES["pictures"]["tmp_name"][$key];
        $name = $_FILES["pictures"]["name"][$key];
        move_uploaded_file($tmp_name, "data/$name");
    }
}
?>
*/
?>
<?php


?>
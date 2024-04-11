<?php
class FileClassDB{

/*

	$config["SQL"]["tbl_file"]["select"] = "
		select
			idx
			, category
			, bc_id
			, seq
			, location
			, file_name
			, file_realname
			, file_size
			, down_cnt
			, regdate
		from
			tbl_file
	";

	$config["SQL"]["tbl_file"]["insert"] = "
		insert into tbl_file
		set
			idx		= null
			, category		= '".$category."'
			, bc_id		= '".$category."'
			, seq		= '".$category."'
			, location		= '".$location."'
			, file_name		= '".$category."'
			, file_realname		= '".$category."'
			, file_size		= '".$category."'
			, down_cnt		= '".$category."'
			, regdate		= CURRENT_TIMESTAMP
	";


	$config["SQL"]["tbl_file"]["update"] = "
		update into tbl_file
		set
			idx		= null
			, category		= '".$category."'
			, bc_id		= '".$category."'
			, seq		= '".$category."'
			, location		= '".$location."'
			, file_name		= '".$category."'
			, file_realname		= '".$category."'
			, file_size		= '".$category."'
			, down_cnt		= '".$category."'
			, regdate		= CURRENT_TIMESTAMP
	";

	$config["SQL"]["tbl_file"]["update"] = "
		delete from tbl_file
	";
*/



//$a = mysql_query("$sql",$connect);
//$b = mysql_affected_rows();
//if($b>0) {
//	echo "데이터 입력완료";
//} else {
//	echo "데이터 입력실패";
//}


}

class FileClass {


	// 제한 요소
	var $maxSize = 2097152;												// 허용 사이즈 (2MB)

	var $extBW = "w";																	// 허용할건지 차단할건지 ( "w", "b");
	var $extWhiteList = "hwp, ppt, xls, jpg, gif, bmp, svg";					// 허용 확장자
	var $extBlackList = "php, php3, php4, exe,-html,-htm";			// 차단 확장자
	var $extList = "";

	// 파일 저장 경로
	var $upPath = "";

	// 파일 정보
	var $ObjFile;
	var $fTmp_name;
	var $fName ;
	var $fNameReal;
	var $fExt;
	var $fSize;

	// 생성자
	function FileClass () {
	}

	// getter and setter

	// 디렉토리 세팅
	function setUpPath($upPath = "") {

		if ( !$upPath ) {
			$upPath = $_SERVER["DOCUMENT_ROOT"]."/_data/";
		}

		//echo "upPath : ".$upPath."<BR>";

		if ( !( $this->checkPath($upPath))  ) {
			@mkdir($upPath.'testdir', 0707);
		}

		$this -> upPath = $upPath;
	}

	// 사이즈 세팅
	function setMaxSize($maxSize) {
		$this -> maxSize = $maxSize;
	}

	// 확장자 세팅
	function setExtList($ext, $extBW = "") {
		if ( !$extBW ) {
			$extBW = "b";
		}

		if ( $extBW == "b" ) {
			$extList = $this -> extBlackList;
		}
		else {
			//$extList = $this -> extWhiteList;
			$extList = "";
		}

		$this -> extBW = $extBW;
		$this -> extList .= $extList.", ".$ext;
	}


	// 파일 세팅
	function setFile($File) {

		//$pos."<BR>";

		if ( !is_uploaded_file($File["tmp_name"]) ) {
			return false;
		}

		// Use
		 if ($File['error'] === UPLOAD_ERR_OK) {
			//uploading successfully done
		} else {
			$this -> Error = ($this -> file_upload_error_message($_FILES['file']['error']) );
			return false;
		}


		if ( !( $File["name"]) ) {
			return false;
		}

		if ( !( $File["size"]) ) {
			return false;
		}

		if ( !( $File["tmp_name"]) ) {
			return false;
		}



		$fileName = $File["name"];
		$fSize = $File["size"];
		$fTmp_name = $File["tmp_name"];
		$pos = strrpos($fileName,".");

		if ( !$pos ) {
			//echo "pos : ".$pos."<BR>";
			return false;
		}

		// 확장자 및 실제 파일명 추출
		$fExt = substr($fileName, $pos+1);
		$fNameReal = substr($fileName, 0, $pos);
		// 파일명 설정
		$fName = substr(base64_encode($fileName), 0, 10)."_".date('Ymd');
		//echo "fExt : ".$fExt."<BR>";


		//echo "fTmp_name : " .$fTmp_name."<BR>";

		$this -> ObjFile = $File;
		$this -> fTmp_name = $fTmp_name;
		$this -> fSize = $fSize;
		$this -> fExt = $fExt;
		$this -> fNameReal = $fNameReal;
		$this -> fName = $fName;

		return true;
	}


	// 디렉토리 체크
	function checkPath($path) {
		if ( !is_dir($path) ) {
			return false;
		}
		return true;
	}


	// 확장자 체크
	function checkExt($ext) {

		if (!$ext) {
		//echo "ext : ".$ext."<BR>";
			return false;
		}

		$excludedExtList = $this -> extList;
//		echo "excludedExtList : ".$excludedExtList."<BR>";
//		echo "ext : ".$ext."<BR>";

		if( eregi($ext, $excludedExtList) ) {
			return false;
		}
		return true;
	}


	// 사이즈 체크
	function checkSize($size) {
		$maxSize = $this -> maxSize;

		if ( $size > $maxSize ) {
			return false;
		}
		return true;
	}

	// 파일 업로드
	/*
	#### 업로드 설정
	1. 파일정보 세팅
		1-1. 디렉토리 세팅
		1-2. 허용 사이즈 세팅
		1-3. 제한 확장자 세팅
		1-4. 파일 세팅 (파일명 변환)

	2. 업로드
		2. 파일 허용 체킹
			2-1. 저장할 디렉토리 확인
				=> 세팅
			2-2  허용 파일 크기 확인
			2-3. 허용 확장자 확인

	=> true : 업로드 성공 ( 파일 정보 리턴 )
	-> false : 업로드 실패 ( 템프파일 삭제 )

	$config["upload"]["path"] = $_SERVER["DOCUMENT_ROOT"]."/_data/";
	$config["upload"]["size"] = 2*1024*1024;
	$config["upload"]["ext"] = "jpeg, wmv";
	$config["upload"]["extbw"] = "b";

	$FC = new FileClass();
	$FC -> setUpPath($config["upload"]["path"]);
	$FC -> setMaxSize($config["upload"]["size"]);
	$FC -> setExtList($config["upload"]["ext"], $config["upload"]["extbw"]);


	$fileInfo = $FC -> fileUpload($_FILES["up_file"])

	if ( !$fileInfo ) {
		echo "업로드 실패";
	}
	else {
		echo "realname : ".$fileInfo["realname"]."<BR>";
		echo "name : ".$fileInfo["name"]."<BR>";
		echo "size : ".$fileInfo["size"]."<BR>";
		echo "location : "."<BR>";
	}

	*/

	function fileUpload ($ObjFile) {

//		echo "ObjFile : ".$ObjFile."<BR>";
//		echo $ObjFile["name"]."<BR>";

		if ( !is_uploaded_file($ObjFile["tmp_name"]) ) {
			$this -> Error = "파일 정보가 없습니다.";
			return false;
		}

		// Use
		 if ( !$ObjFile['error'] === UPLOAD_ERR_OK)  {
			$this -> Error = ($this -> file_upload_error_message($_FILES['file']['error']) );
			return false;
		}


		//echo "111<BR>";
		if ( !$this -> setFile($ObjFile) ) {
			$this -> Error = "파일 정보가 잘못되었습니다.";
			return false;
		}

		//echo "222<BR>";
		if ( !$this -> checkPath($this->upPath) ) {
			$this -> Error = "디렉토리 설정이 잘못되었습니다.";
			return false;
		}
		//echo "333<BR>";
		if ( !$this -> checkSize($this->fSize) ) {
			$this -> Error = "업로드한 파일의 크기(".$this->displayFSize($this->fSize).")가 지정된 파일 크기(".$this->displayFSize($this->maxSize).") 지시어보다 큽니다.";
			return false;
		}

		//echo "fExt : ".$this -> fExt."11<BR>";

		//echo "444<BR>";
		if ( !$this -> checkExt($this -> fExt) ) {
			$this -> Error = "업로드 금지 확장자 압니다.";
			return false;
		}


		$up_file = $this->fName.".".$this->fExt;


//		echo "upPath : ".$this->upPath."<BR>";
//		echo "fTmp_name : ".$this -> fTmp_name."<BR>";
//		echo "fName : ".$this->fName."<BR>";
//		echo "fNameReal : ".$this->fNameReal."<BR>";
//		echo "fExt : ".$this->fExt."<BR>";
//		echo "fSize : ".$this->fSize."<BR>";
//		echo "up_file : ".$up_file."<BR>";

		//echo "555<BR>";
		//if ( !($this -> fTmp_name) || !is_uploaded_file($this -> fTmp_name ) ) {
		if ( !($this -> fTmp_name) || !(move_uploaded_file($this -> fTmp_name , $this->upPath.$up_file) ) ) {
			//@unlink($tmp);
			$this -> Error = "파일 업로드에 실패하였습니다.(에러코드 : ".$this->ObjFile["error"].")";
			return false;
		}

		//echo "666<BR>";
		$fExt = $this -> fExt;
		$fNameReal = $this -> fNameReal;
		$fName = $this -> fName;
		$fSize = $this -> fSize;


		$return["realname"] = $fNameReal.".".$fExt;
		$return["name"] = $fNameReal.".".$fExt;
		$return["size"] = $fSize;

		return $return;

//		echo "realname : ".$fileInfo["realname"]."<BR>";
//		echo "name : ".$fileInfo["name"]."<BR>";
//		echo "size : ".$fileInfo["size"]."<BR>";
//		echo "location : "."<BR>";

	}

	// 파일 다운로드
	function fileDownload() {

	}

	// 파일 삭제
	function fileDelete() {

	}


	// 파일 사이즈 출력용 변환
	function displayFSize($fSize){

		if(is_numeric($fSize)){
			$decr = 1024; $step = 0;
			$prefix = array('Byte','KB','MB','GB','TB','PB');

			while(($fSize / $decr) > 0.9) {
				$fSize = $fSize / $decr;
				$step++;
			}
			return round($fSize,2).' '.$prefix[$step];
		} else {
			return 'NaN';
		}

	}

	// 파일 업로드 에러 메시지 출력
	function file_upload_error_message($error_code) {
		switch ($error_code) {
			case UPLOAD_ERR_INI_SIZE:
				return '업로드한 파일이 php.ini upload_max_filesize 지시어보다 큽니다. ';
			case UPLOAD_ERR_FORM_SIZE:
				return '업로드한 파일이 HTML 폼에서 지정한 MAX_FILE_SIZE 지시어보다 큽니다. ';
			case UPLOAD_ERR_PARTIAL:
				return '파일이 일부분만 전송되었습니다. ';
			case UPLOAD_ERR_NO_FILE:
				return '파일이 전송되지 않았습니다. ';
			case UPLOAD_ERR_NO_TMP_DIR:
				return '임시 폴더가 없습니다. ';
			case UPLOAD_ERR_CANT_WRITE:
				return '디스크에 파일 쓰기를 실패했습니다.';
			case UPLOAD_ERR_EXTENSION:
				return '확장에 의해 파일 업로드가 중지되었습니다.';
			default:
				return 'Unknown upload error';
		}
	}


}

?>
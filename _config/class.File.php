<?

///////InsertColor.php ////////////////////////////////////
class FileUploadManager
{
	var $files;
	var $OrigianlFiles;
	var $exceptExtension;
	var $extension;
	var $fileName;
	var $directory;
	var $directory2;
	var $ExceptFileSize;
	var $realfileName;
	var $returnValue = Array();

	function SetFiles($files)
	{
		$this->files = $files;
		$this->OrigianlFiles = $files;
	}

	function SetExceptExtension($exceptExtension)
	{
		$this->exceptExtension = $exceptExtension;
	}

	function SetExceptFileSize($ExceptFileSize) {
		$this->ExceptFileSize = $ExceptFileSize;
	}

	function SetDirectory($directory)
	{
		$this->directory2 = $directory;
	}

	function CountVariable($variable)
	{
		return count($variable);
	}

	//확장자와 파일명 분리
	function FileExplodeExtension()
	{

		for($i=0; $i < $this->CountVariable($this->files["name"]) ; $i++) {
			$explodeFiles[$i] = explode(".",$this->files["name"][$i]);
			$fileinfo = pathinfo($this->files["name"][$i]);
			$ext = $fileinfo['extension'];
			$extension[$i] = $ext;
			$fileName[$i] = $explodeFiles[$i][0];
//			echo $extension[$i]."<BR>";
		}
		$this->extension = $extension;
		$this->fileName = $fileName;
	}

	//확장자 체크
	function CheckFileExtension()
	{
		for($i=0; $i<count($this->extension); $i++)
		{
//			echo strlen($this->extension[$i] )."<BR>";
//			echo "================1<BR>";
			if ( strlen($this->extension[$i] ) > 0 ) {
				$flag = false;
				for($j=0; $j<count($this->exceptExtension); $j++)
				{
//					echo strtolower($this->extension[$i])." | ";
//					echo strtolower($this->exceptExtension[$j])."<BR>";
					if( strtolower($this->extension[$i]) == strtolower($this->exceptExtension[$j]) )
					{
						$flag = true;
						break;
					}
				}
				if ( !$flag ) {
//					alert_back("업로드가 허용되지 않는 파일입니다");
					echo "업로드가 허용되지 않는 파일입니다";
					exit;
				}
			}
//			echo "================2<BR><BR>";
		}
//		exit;

	}

	function CheckFileSize() {
		for($i=0; $i<count($this->fileName); $i++) {
			if ( $this->files["size"][$i] > $this->ExceptFileSize*10000*1024) {
				alert_back("파일 크기 제한.");
				//echo "파일 크기 제한."."<BR>";
				exit;
			}
		}
	}

	//디렉토리 체크
	function CheckDirectory()
	{
		if(!file_exists($this->directory2))
		{
			//echo "directory2 : ".$this->directory2."<BR>";
			mkdir ($this->directory2, 0777);
		}
		//exit;
	}

	//화일명 변경
	function NewName()
	{
		#### 신규파일명 추출 <- 추후 업데이트 필요함.
		for($i=0; $i<count($this->fileName); $i++)
		{
			while(true) {
				////////////////////////////
				// 난수 발생 랜덤값 뽑기
				srand((double)microtime()*100000);
				$r = rand(1, 1000);
				////////////////////////////
				$TempFileName = time()."(".$r.")";
				if (!file_exists($this->directory2."/".$TempFileName.'.'.$this->extension[$i])) {
					//echo "NewName : ".$this->directory2."/".$TempFileName.'.'.$this->extension[$i]."<BR>";
					$this->realfileName[$i] = $TempFileName;
					break;
				}
				//$this->fileName[$i] = $this->fileName[$i]."(".$r.")";
			}
			//echo $this->realfileName[$i]."<BR>";
		}
	}

	function UploadFiles()
	{
		$this->CountVariable($this->files["name"]);
		$flag = 0;
		//echo "directory2 : ".$this->directory2."<BR>";
		for($i=0; $i < count($this->fileName); $i++)
		{
			if ( strlen($this->fileName[$i]) > 0 ) {
				$fileName[$i] = $this->fileName[$i].".".$this->extension[$i];
				$realfileName[$i] = $this->realfileName[$i].".".$this->extension[$i];
				if (!move_uploaded_file($this->OrigianlFiles["tmp_name"][$i], $this->directory2."/".$realfileName[$i])){
					alert_back("파일 업로드에 실패하였습니다.");
					//echo "업로드가 허용되지 않는 파일입니다";
					exit;
				}
//				echo $fileName[$i]."<br>";
//				echo $realfileName[$i]."<br>";
//				echo $this->directory2."/".$fileName[$i]."<br>";
//				echo $this->files["size"][$i]."<br>";
//				echo "============================================"."<br>";
				$this->returnValue[$i] = Array($fileName[$i], $realfileName[$i], $this->files["size"][$i]);
				$flag++;
			}
		}

		return $flag;
		//alert_back("파일 업로드 완료");
	}

	//파일일 업로드 메니져
	function UploadManager()
	{
		$this->FileExplodeExtension();
		$this->CheckFileExtension();
		$this->CheckFileSize();
		$this->CheckDirectory();
		$this->NewName();
		$result = $this->UploadFiles();
		return $result;
	}
	
	//파일다운로드
	function download($filepath,$filename="file",$filesize=0) {
    	// Header 설정
    	
    	if(preg_match("(MSIE 5.5|MSIE 6.0)", $HTTP_USER_AGENT)) // 브라우져 구분
    	{
    		//$filename = urlencode($filename); // 파일명이나 경로에 한글이나 공백이 포함될 경우를 고려
    		//Header("Cache-Control: cache, must-revalidate");
    		Header("Content-Type: doesn/matter");
    		Header("Content-type:application/octet-stream");
    		Header("Content-Length: $filesize");   // 이부부을 넣어 주어야지 다운로드 진행 상태가 표시 됩니다.
    		Header("Content-Disposition: inline; filename=$filename");
    		Header("Content-Transfer-Encoding: binary");
    		Header("Pragma: no-cache");
    		Header("Expires: 0");

    	}
    	else
    	{
    		//$filename = urlencode($filename);
    		Header("Cache-Control: cache, must-revalidate");
    		Header("Content-type: file/unknown");
    		//Header("Content-type:application/octet-stream");
    		Header("Content-Length: $filesize");
    		Header("Content-Disposition: attachment; filename=$filename");
    		Header("Content-Transfer-Encoding: binary");
    		Header("Content-Description: PHP3 Generated Data");
    		Header("Pragma: no-cache");
    		Header("Expires: 0");
    	}
    	
		/* 
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header("Content-Type: application/force-download");
		header('Content-Disposition: attachment; filename=' . $filename);
		// header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . $filesize);
		ob_clean();
		flush();
		readfile($filepath); 
		exit;
		*/

    	// 파일 전송
    	if (is_file($filepath))
    	{
    		$fp = fopen($filepath, "r");
    		if (!fpassthru($fp))
    			fclose($fp);
    	}
    	else
    	{
    		echo "해당 파일이나 경로가 존재하지 않습니다.";
    	}
    	
    }




}//class end
/////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////
// 사용법
//	$ExceptExtension = array('php','asp','jps','exe');
//	$up = new FileUploadManager;
//	$up->SetExceptExtension($ExceptExtension);
//	$up->SetFiles($_FILES["color_image"]);
//	$up->SetDirectory("../_data/UploadFiles");
//	$up->UploadManager();
/////////////////////////////////////////////////////////////////////

?>
<?

/*
	//사용
	echo $_FILES['up_file1']."<BR>";
	echo sizeof($_FILES['up_file1'])."<BR>";
	echo $_FILES['up_file1']["name"]." : name<BR>";
	echo $_FILES['up_file1']["type"]." : type<BR>";
	echo $_FILES['up_file1']["error"]." : error<BR>";
	echo $_FILES['up_file1']["size"]." : size<BR>";
	echo $_FILES['up_file1']["tmp_name"]." : tmp_name<BR>";

	echo $up_file1."<BR>";
	echo basename($up_file1);
	exit;

	$upload_root = "../../up_file/";
	$upload_size = "2048000";

	if ($_FILES['up_file1']) {
		$up_file1 = up_img($_FILES['up_file1']["tmp_name"],$upload_root,$upload_size,"",time());
		$up_file1_name = $_FILES['up_file1']["name"];
	}
*/
	###############################################################
	//이미지 파일 업로드 함수
	//사용법 up_img(업로드이미지,저장경로,용량제한,대체될이미지,저장될이미지명);
	//저장경로는 상대경로로,용량제한은 바이트로,대체이미지가없을경우 0,용량제한이 없을경우 0
	//저장될이미지명은 확장자없이 넣어주세요
	//에러메세지나 저장파일명이 리턴됩니다.
	function up_img($imgfile,$img_path,$rec_size,$db_img,$filename) {
		$image_size = getimagesize("$imgfile");
		$image_width = $image_size[0]; //가로
		$image_height = $image_size[1]; //세로
		$image_type = $image_size[2]; // 1:gif 2:jpeg 3:png
		$img_size = filesize($imgfile);

		if (($img_size > $rec_size) && ($rec_size != 0)) { //이미지 용량제한
			if (!@unlink($imgfile)) {
				alert_back("이미지 임시파일을 지우는 중에 오류가 발생했습니다.");
			}
			alert_back("업로드 제한 파일 크기 : ".($rec_size/1000/1024)."MB");
		}

		if ($image_type == '2') {
			$im = Imagecreatefromjpeg ($imgfile);
		} else if($image_type == '1') {
			$im = Imagecreatefromgif ($imgfile);
		} else if($image_type == '3') {
			$im = Imagecreatefrompng ($imgfile);
		} else {
			alert_back("이미지의 형식이 맞질않습니다.");
		}
		if ($image_type == '1') {
			$tmp_name = "${filename}.gif";
			Imagegif($im ,"${img_path}/${tmp_name}");
		} else {
			$tmp_name = "${filename}.jpeg";
			Imagejpeg($im ,"${img_path}/${tmp_name}");
		}
		ImageDestroy($im);

		if(!@unlink($imgfile)) {
			alert_back("이미지 임시파일을 지우는 중에 오류가 발생했습니다.");
			exit;
		}
		if($db_img != 0){
			@unlink("${img_path}${db_img}");
		}
		return $tmp_name;
	}
	###############################################################

	###############################################################
	//신규 파일 DB 입력
	function upfileUpload($category, $bc_id, $seq, $file_name, $file_realname, $file_size) {
		global $dbcon;
		$SQL = "
			insert into tbl_file(
				category, bc_id, seq, file_name, file_realname, file_size
			) values (
				'".$category."', '".$bc_id."', '".$seq."', '".$file_name."', '".$file_realname."', '".$file_size."'
			)
		";
		$dbcon -> query($SQL);
	}
	###############################################################

	###############################################################
	//기존 파일 삭제 처리
	function upfileDelete($idx) {
		global $dbcon;
		// 파일 삭제 처리
		$SQL = "
			select file_realname
			from
				tbl_file
			where
				category='board' and bc_id = '".$GLOBALS[bc_id]."' and seq = '".$GLOBALS[seq]."' and idx = '".$idx."'
		";
		$result = $dbcon -> fetch_row($dbcon -> query($SQL));
//		echo $result[0]."<BR>";
		DeleteFile($GLOBALS[upload_path]."/".$result[0]);

		//파일 삭제 DB 처리
		$SQL = "
			delete from tbl_file
			where
				category='board' and bc_id = '".$GLOBALS[bc_id]."' and seq = '".$GLOBALS[seq]."' and idx = '".$idx."'
		";
		$result = $dbcon -> query($SQL);
//		echo $result."<BR>";
//		exit;
	}
	###############################################################
		$image_view_width = 650;
		$image_view_height = 400;

	function upfileSelect($seq) {//, $image_view_width, $image_view_height
		global $dbcon;
		$SQL = "
			select *
			from tbl_file
			where
				category='board' and bc_id = '".$GLOBALS[bc_id]."' and seq = '".$seq."'
			order by idx asc
			limit 0 , 10
		";
		return ($dbcon -> query($SQL));
	}

?>
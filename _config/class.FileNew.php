<?
########## 파일 처리관련 CLASS #######################
# 만든이 : 거니
# 출처 : http://phpschool.com/gnuboard4/bbs/board.php?bo_table=tipntech&wr_id=37265&page=&cwin=&sfl=wr_name%7C%7Csubject&stx=%A1%C6A%A2%A5I&spt=&page=
################################################

class upload
{
	public $upload_tmpFileName = null;
	public $upload_fileName = null;
	public $upload_realfileName = null;
	public $upload_fileSize = null;
	public $upload_fileType = null;
	public $upload_fileWidth = null;
	public $upload_fileHeight = null;
	public $upload_fileExt = null;
	public $upload_fileImageType = null;
	public $upload_count = 0;
	public $upload_directory = null;
	public $upload_subdirectory = null;
	public $denied_ext = array
	(
		"php"			=> ".phps",
		"phtml"		=> ".phps",
		"html"			=> ".txt",
		"htm"			=> ".txt",
		"inc"			=> ".txt",
		"sql"			=> ".txt",
		"cgi"			=> ".txt",
		"pl"			=> ".txt",
		"jsp"			=> ".txt",
		"asp"			=> ".txt",
		"phtm"		=> ".txt"
	);

	function upload() {
		$this -> upload_tmpFileName = null;
		$this -> upload_fileName = null;
		$this -> upload_realfileName = null;
		$this -> upload_fileSize = null;
		$this -> upload_fileType = null;
		$this -> upload_fileWidth = null;
		$this -> upload_fileHeight = null;
		$this -> upload_fileExt = null;
		$this -> upload_fileImageType = null;
		$this -> upload_count = 0;
		$this -> upload_directory = null;
		$this -> upload_subdirectory = null;
		$this -> denied_ext = array
		(
			"php"			=> ".phps",
			"phtml"		=> ".phps",
			"html"			=> ".txt",
			"htm"			=> ".txt",
			"inc"			=> ".txt",
			"sql"			=> ".txt",
			"cgi"			=> ".txt",
			"pl"			=> ".txt",
			"jsp"			=> ".txt",
			"asp"			=> ".txt",
			"phtm"		=> ".txt"
		);
	}


	public function __construct($upload_dir)
	{
		$this->upload_directory = $upload_dir;
		$this->upload_subdirectory = time();
	}

	public function define($files, $newname= "", $ord="")
	{
		if ( $newname) $newname = $newname."_";

		if(is_array($files['tmp_name']))
		{
			for($i = 0; $i < sizeof($files['tmp_name']); $i++)
			{
				$this->_uploadErrorChecks($files['error'][$i], $files['name'][$i]);

				if(is_uploaded_file($files['tmp_name'][$i]))
				{
					$this->upload_tmpFileName[$i] = $files['tmp_name'][$i];
					$this->upload_fileSize[$i] = (int) $files['size'][$i] ? $files['size'][$i] : 0;
					$this->upload_fileType[$i] = $files['type'][$i];
					$this->upload_fileExt[$i] = $this->_getExtension($files['name'][$i]);
					$this->upload_fileName[$i] = $newname.time()."_".$i.".".$this->upload_fileExt[$i];
					echo $this->upload_fileName[$i]."<br>";
					$this->upload_realfileName[$i] = $this->_emptyToUnderline($this->_checkFileName($files['name'][$i]));

					// 이미지 파일
					if($this->_isThisImageFile($files['type'][$i]) == true)
					{
						$img = $this->_getImageSize($files['tmp_name'][$i]);
						$this->upload_fileWidth[$i] = (int) $img[0];
						$this->upload_fileHeight[$i] = (int) $img[1];
						$this->upload_fileImageType[$i] = $img[2];
					}
				}
			}
		}
		else
		{
			$this->_uploadErrorChecks($files['error'], $files['name']);

			if(is_uploaded_file($files['tmp_name']))
			{
				$this->upload_tmpFileName = $files['tmp_name'];
				$this->upload_fileSize = (int) ($files['size']) ? $files['size'] : 0;
				$this->upload_fileType = $files['type'];
				$this->upload_fileExt = $this->_getExtension($files['name']);
				$this->upload_fileName = time()."_".$ord.".".$this->upload_fileExt;
				$this->upload_realfileName = $this->_emptyToUnderline($this->_checkFileName($files['name']));


				// 이미지 파일
				if($this->_isThisImageFile($files['type']) == true)
				{
					$img = $this->_getImageSize($files['tmp_name']);
					$this->upload_fileWidth = $img[0];
					$this->upload_fileHeight = $img[1];
					$this->upload_fileImageType = $img[2];
				}
			}
		}
	}

	private function _isThisImageFile($type)
	{
		if(preg_match("/image/i", $type) == false && preg_match("/flash/", $type) == false)
		{
			return false;
		}
		else
		{
			return true;
		}
	}

	private function _uploadErrorChecks($errorCode, $fileName)
	{
		if($errorCode == UPLOAD_ERR_INI_SIZE)
		{
			throw new Exception($fileName . " : 업로드 제한용량(".ini_get('upload_max_filesize').")을 초과한 파일입니다.");
		}
		else if($errorCode == UPLOAD_ERR_FORM_SIZE)
		{
			throw new Exception($fileName . " : 업로드한 파일이 HTML 에서 정의되어진 파일 업로드 제한용량을 초과하였습니다.");
		}
		else if($errorCode == UPLOAD_ERR_PARTIAL)
		{
			throw new Exception("파일이 일부분만 전송되었습니다. ");
		}
	}

	private function _mkUploadDir()
	{
		if(is_dir($this->upload_directory) == false)
		{
			if(@mkdir($this->upload_directory, 0755) == false)
			{
				throw new Exception($this->upload_directory . " 디렉토리를 생성하지 못하였습니다. 퍼미션을 확인하시기 바랍니다.");
			}
		}
	}

	private function _mkUploadSubDir()
	{
		if(is_writable($this->upload_directory) == false)
		{
			throw new Exception($this->upload_directory . " 에 쓰기 권한이 없습니다. " . $this->upload_subdirectory . "디렉토리를 생성하지 못하였습니다.");
		}
		else
		{
//			$uploaded_path = $this->upload_directory . "/" . $this->upload_subdirectory;
			$uploaded_path = $this->upload_directory;

			if(is_dir($uploaded_path) == false)
			{
				if(@mkdir($uploaded_path, 0755) == false)
				{
					throw new Exception($uploaded_path . " 디렉토리를 생성하지 못하였습니다. 퍼미션을 확인하시기 바랍니다.");
				}
			}
			else
			{
				if(is_writable($uploaded_path) == false)
				{
					throw new Exception($uploaded_path . " 디렉토리에 쓰기 권한이 없습니다. 파일을 업로드 할 수 없습니다.");
				}
			}
		}
	}

	public function uploadedFiles()
	{
		if(is_array($this->upload_tmpFileName))
		{
			$this->_mkUploadDir();
			//$this->_mkUploadSubDir();

			for($i = 0; $i < sizeof($this->upload_tmpFileName); $i++)
			{
				$uploaded_filename = $this->upload_directory . "/" . $this->upload_fileName[$i];

				if(@move_uploaded_file($this->upload_tmpFileName[$i], $uploaded_filename) == false)
				{
					throw new Exception($uploaded_filename . " 을 저장하지 못하였습니다.");
				}
				else
				{
					$this->upload_count += 1;
				}

				@unlink($this->upload_tmpFileName[$i]);
			}
		}
		else
		{
			if(is_uploaded_file($this->upload_tmpFileName))
			{
				$this->_mkUploadDir();

				$uploaded_filename = $this->upload_directory . "/" .$this->upload_fileName;

				if(@move_uploaded_file($this->upload_tmpFileName, $uploaded_filename) == false)
				{
					throw new Exception($uploaded_filename . " 을 저장하지 못하였습니다.");
				}
				else
				{
					$this->upload_count += 1;
				}
				@unlink($this->upload_tmpFileName);
			}
		}
	}

	// 이미지정보
	private function _getImageSize($tmp_file)
	{
		$img = @getimagesize($tmp_file);

		$img[0] = $img[0] ? $img[0] : 0;
		$img[1] = $img[1] ? $img[1] : 0;

		return $img;
	}

	// 금지 확장자명을 허용 확장자로 변경하여 파일명 지정
	private function _checkFileName($fileName)
	{
		$fileName = strtolower($fileName);

		foreach($this->denied_ext as $key => $value)
		{
			if($this->_getExtension($fileName) == trim($key))
			{
				$expFileName = explode(".", $fileName);

				for($i = 0; $i < sizeof($expFileName) - 1; $i++)
				{
					$fname .= $expFileName[$i] . $value;
				}
				return $fname;
			}
		}
		return $fileName;
	}

	/**
	 * 파일명의 빈 부분을 "_" 로 변경
	*/
	private function _emptyToUnderline($fileName)
	{
		return preg_replace("/\ /i", "_", $fileName);
	}

	/**
	 * 확장자 추출
	*/
	private function _getExtension($fileName)
	{
		//return strtolower(substr(strrchr($fileName, "."), 1));
		$path = pathinfo($fileName);
		return $path['extension'];
	}

	/**
	 * 이미지 파일이 아닌 파일이 존재한다면 에러
	*/
	public function checkImageOnly()
	{
		if(is_array($this->upload_tmpFileName))
		{
			for($i = 0; $i < sizeof($this->upload_tmpFileName); $i++)
			{
				if($this->_isThisImageFile($this->upload_fileType[$i]) == false)
				{
					throw new Exception($this->upload_fileName[$i] . " 은 이미지 파일이 아닙니다.");
				}
			}
		}
		else
		{
			if(is_uploaded_file($this->upload_tmpFileName))
			{
				if($this->_isThisImageFile($this->upload_fileType) == false)
				{
					throw new Exception($this->upload_fileName . " 은 이미지 파일이 아닙니다.");
				}
			}
		}
	}

	/**
	 * gd library information - array
	 * --------------------------------------------------------------------------------
	 * GD Version : string value describing the installed libgd version.
	 * Freetype Support : boolean value. TRUE if Freetype Support is installed.
	 * Freetype Linkage : string value describing the way in which Freetype was linked. Expected values are: 'with freetype',
						 'with TTF library', and 'with unknown library'. This element will only be defined if Freetype Support
						 evaluated to TRUE.
	 * T1Lib Support : boolean value. TRUE if T1Lib support is included.
	 * GIF Read Support : boolean value. TRUE if support for reading GIF images is included.
	 * GIF Create Support : boolean value. TRUE if support for creating GIF images is included.
	 * JPG Support : boolean value. TRUE if JPG support is included.
	 * PNG Support : boolean value. TRUE if PNG support is included.
	 * WBMP Support : boolean value. TRUE if WBMP support is included.
	 * XBM Support : boolean value. TRUE if XBM support is included.
	 * --------------------------------------------------------------------------------
	*/
	public function makeThumbnailed($max_width, $max_height, $thumb_head = null)
	{
		if(extension_loaded("gd") == false)
		{
			throw new Exception("GD 라이브러리가 설치되어 있지 않습니다.");
		}
		else
		{
			$gd = @gd_info();

			if(substr_count(strtolower($gd['GD Version']), "2.") == 0)
			{
				$this->_thumbnailedOldGD($max_width, $max_height, $thumb_head);
			}
			else
			{
				$this->_thumbnailedNewGD($max_width, $max_height, $thumb_head);
			}
		}
	}

	/**
	 * GD Library 1.X 버전대를 위한 섬네일 함수
	 *
	 * GIF 포맷을 지원하지 않음
	*/
	private function _thumbnailedOldGD($max_width, $max_height, $thumb_head)
	{
		if(is_array($this->upload_tmpFileName))
		{
			$this->_mkUploadDir();
			//$this->_mkUploadSubDir();

			for($i = 0; $i < sizeof($this->upload_tmpFileName); $i++)
			{
				if($this->_isThisImageFile($this->upload_fileType[$i]) == true)
				{
					switch($this->upload_fileImageType[$i])
					{
						case 1 :
						$im = @imagecreatefromgif($this->upload_tmpFileName[$i]);
						break;
						case 2 :
						$im = @imagecreatefromjpeg($this->upload_tmpFileName[$i]);
						break;
						case 3 :
						$im = @imagecreatefrompng($this->upload_tmpFileName[$i]);
						break;
					}

					if(!$im)
					{
						throw new Exception("썸네일 이미지 생성 중 문제가 발생하였습니다.");
					}
					else
					{
						$sizemin = $this->_getWidthHeight($this->upload_fileWidth[$i], $this->upload_fileHeight[$i], $max_width, $max_height);

						$small = @imagecreate($sizemin[width], $sizemin[height]);

						@imagecolorallocate($small, 255, 255, 255);
						@imagecopyresized($small, $im, 0, 0, 0, 0, $sizemin[width], $sizemin[height], $this->upload_fileWidth[$i], $this->upload_fileHeight[$i]);

						$thumb_head = ($thumb_head != null) ? $thumb_head : "thumb";
						//$thumb_filename = $this->upload_directory . "/" . $this->upload_subdirectory . "/" . $this->upload_fileName[$i] . ".${thumb_head}";
						//$thumb_filename = $this->upload_directory . "/" . $this->upload_subdirectory . "_" . $this->upload_fileName[$i] . ".${thumb_head}";
						$thumb_filename = $this->upload_directory . "/" . $this->upload_fileName[$i] . ".${thumb_head}" ;

						if ($this->upload_fileImageType[$i] == 2)
						{
							if(@imagejpeg($small, $thumb_filename, 100) == false)
							{
								throw new Exception("jpg/jpeg 썸네일 이미지를 생성하지 못하였습니다.");
							}
						}
						else if ($this->upload_fileImageType[$i] == 3)
						{
							if(@imagepng($small, $thumb_filename) == false)
							{
								throw new Exception("png 썸네일 이미지를 생성하지 못하였습니다.");
							}
						}

						if($small != null)
						{
							@imagedestroy($small);
						}
						if($im != null)
						{
							@imagedestroy($im);
						}
					}
				}
			}
		}
		else
		{
			if(is_uploaded_file($this->upload_tmpFileName))
			{
				$this->_mkUploadDir();
				//$this->_mkUploadSubDir();

				if($this->_isThisImageFile($this->upload_fileType) == true)
				{
					switch($this->upload_fileImageType)
					{
						case 1 :
						$im = @imagecreatefromgif($this->upload_tmpFileName);
						break;
						case 2 :
						$im = @imagecreatefromjpeg($this->upload_tmpFileName);
						break;
						case 3 :
						$im = @imagecreatefrompng($this->upload_tmpFileName);
						break;
					}

					if(!$im)
					{
						throw new Exception("썸네일 이미지 생성 중 문제가 발생하였습니다.");
					}
					else
					{
						$sizemin = $this->_getWidthHeight($this->upload_fileWidth, $this->upload_fileHeight, $max_width, $max_height);

						$small = @imagecreate($sizemin[width], $sizemin[height]);

						@imagecolorallocate($small, 255, 255, 255);
						@imagecopyresized($small, $im, 0, 0, 0, 0, $sizemin[width], $sizemin[height], $this->upload_fileWidth, $this->upload_fileHeight);

						$thumb_head = ($thumb_head != null) ? $thumb_head : "thumb";
						//$thumb_filename = $this->upload_directory . "/" . $this->upload_subdirectory . "/" . $this->upload_fileName . ".${thumb_head}";
						//$thumb_filename = $this->upload_directory . "/" . $this->upload_subdirectory . "_" . $this->upload_fileName . ".${thumb_head}";
						$thumb_filename = $this->upload_directory . $this->upload_fileName . ".${thumb_head}";


						if ($this->upload_fileImageType == 2)
						{
							if(@imagejpeg($small, $thumb_filename, 100) == false)
							{
								throw new Exception("jpg/jpeg 썸네일 이미지를 생성하지 못하였습니다.");
							}
						}
						else if ($this->upload_fileImageType == 3)
						{
							if(@imagepng($small, $thumb_filename) == false)
							{
								throw new Exception("png 썸네일 이미지를 생성하지 못하였습니다.");
							}
						}

						if($small != null)
						{
							@imagedestroy($small);
						}
						if($im != null)
						{
							@imagedestroy($im);
						}
					}
				}
			}
		}
	}

	/**
	 * GD Library 2.X 버전대를 위한 섬네일 함수
	 *
	 * GIF 포맷을 지원함
	*/
	private function _thumbnailedNewGD($max_width, $max_height, $thumb_head)
	{
		if(is_array($this->upload_tmpFileName))
		{
			$this->_mkUploadDir();
			//$this->_mkUploadSubDir();

			for($i = 0; $i < sizeof($this->upload_tmpFileName); $i++)
			{
				if($this->_isThisImageFile($this->upload_fileType[$i]) == true)
				{
					switch($this->upload_fileImageType[$i])
					{
						case 1 :
						$im = @imagecreatefromgif($this->upload_tmpFileName[$i]);
						break;
						case 2 :
						$im = @imagecreatefromjpeg($this->upload_tmpFileName[$i]);
						break;
						case 3 :
						$im = @imagecreatefrompng($this->upload_tmpFileName[$i]);
						break;
					}

					$sizemin = $this->_getWidthHeight($this->upload_fileWidth[$i], $this->upload_fileHeight[$i], $max_width, $max_height);

					$small = @imagecreatetruecolor($sizemin[width], $sizemin[height]);

					@imagecolorallocate($small, 255, 255, 255);
					@imagecopyresampled($small, $im, 0, 0, 0, 0, $sizemin[width], $sizemin[height], $this->upload_fileWidth[$i], $this->upload_fileHeight[$i]);

					$thumb_head = ($thumb_head != null) ? $thumb_head : "thumb";
					//$thumb_filename = $this->upload_directory . "/" . $this->upload_subdirectory . "/" . $this->upload_fileName[$i] . ".${thumb_head}";
					//$thumb_filename = $this->upload_directory . "/" . $this->upload_subdirectory . "_" . $this->upload_fileName[$i] . ".${thumb_head}";
					$thumb_filename = $this->upload_directory . "/" . $this->upload_fileName[$i] . ".${thumb_head}";

					if($this->upload_fileImageType[$i] == 1)
					{
						if(@imagegif($small, $thumb_filename) == false)
						{
							throw new Exception("gif 썸네일 이미지를 생성하지 못하였습니다.");
						}
					}
					else if ($this->upload_fileImageType[$i] == 2)
					{
						if(@imagejpeg($small, $thumb_filename, 100) == false)
						{
							throw new Exception("jpg/jpeg 썸네일 이미지를 생성하지 못하였습니다.");
						}
					}
					else if ($this->upload_fileImageType[$i] == 3)
					{
						if(imagepng($small, $thumb_filename) == false)
						{
							throw new Exception("png 썸네일 이미지를 생성하지 못하였습니다.");
						}
					}

					if($small != null)
					{
						@imagedestroy($small);
					}
					if($im != null)
					{
						@imagedestroy($im);
					}
				}
			}
		}
		else
		{
			if(is_uploaded_file($this->upload_tmpFileName))
			{
				$this->_mkUploadDir();
				//$this->_mkUploadSubDir();

				if($this->_isThisImageFile($this->upload_fileType) == true)
				{
					switch($this->upload_fileImageType)
					{
						case 1 :
						$im = @imagecreatefromgif($this->upload_tmpFileName);
						break;
						case 2 :
						$im = @imagecreatefromjpeg($this->upload_tmpFileName);
						break;
						case 3 :
						$im = @imagecreatefrompng($this->upload_tmpFileName);
						break;
					}

					$sizemin = $this->_getWidthHeight($this->upload_fileWidth, $this->upload_fileHeight, $max_width, $max_height);

					$small = @imagecreatetruecolor($sizemin[width], $sizemin[height]);

					@imagecolorallocate($small, 255, 255, 255);
					@imagecopyresampled($small, $im, 0, 0, 0, 0, $sizemin[width], $sizemin[height], $this->upload_fileWidth, $this->upload_fileHeight);

					$thumb_head = ($thumb_head != null) ? $thumb_head : "thumb";
					//$thumb_filename = $this->upload_directory . "/" . $this->upload_subdirectory . "/" . $this->upload_fileName . ".${thumb_head}";
					//$thumb_filename = $this->upload_directory . "/" . $this->upload_subdirectory . "_" . $this->upload_fileName . ".${thumb_head}";
					$thumb_filename = $this->upload_directory . "/" . $this->upload_fileName . ".${thumb_head}";

					if($this->upload_fileImageType == 1)
					{
						if(@imagegif($small, $thumb_filename) == false)
						{
							throw new Exception("gif 썸네일 이미지를 생성하지 못하였습니다.");
						}
					}
					else if ($this->upload_fileImageType == 2)
					{
						if(@imagejpeg($small, $thumb_filename, 100) == false)
						{
							throw new Exception("jpg/jpeg 썸네일 이미지를 생성하지 못하였습니다.");
						}
					}
					else if ($this->upload_fileImageType == 3)
					{
						if(imagepng($small, $thumb_filename) == false)
						{
							throw new Exception("png 썸네일 이미지를 생성하지 못하였습니다.");
						}
					}

					if($small != null)
					{
						@imagedestroy($small);
					}
					if($im != null)
					{
						@imagedestroy($im);
					}
				}
			}
		}
	}

	/**
	 * 제한된 가로/세로 길이에 맞추어 원본이미지의 줄인 windth, height 값을 리턴
	 * _getWidthHeight(원본이미지가로, 원본이미지세로, 제한가로, 제한세로)
	*/
	private function _getWidthHeight($org_width, $org_height, $max_width, $max_height)
	{
		$img = array();

		if($org_width <= $max_width && $org_height <= $max_height)
		{
			$img[width] = $org_width;
			$img[height] = $org_height;
		}
		else
		{
			if($org_width > $org_height)
			{
				$img[width] = $max_width;
				$img[height] = ceil($org_height * $max_width / $org_width);
			}
			else if($org_width < $org_height)
			{
				$img[width] = ceil($org_width * $max_height / $org_height);
				$img[height] = $max_height;
			}
			else
			{
				$img[width] = $max_width;
				$img[height] = $max_height;
			}

			if($img[width] > $max_width)
			{
				$img[width] = $max_width;
				$img[height] = ceil($org_height * $max_width / $org_width);
			}
			if($img[height] > $max_height)
			{
				$img[width] = ceil($org_width * $max_height / $org_height);
				$img[height] = $max_height;
			}
		}
		return $img;
	}

	public function __destruct()
	{
	}
}
;


function FileDBDeleteSeq($category, $seq, $path, $thumb="") {
	global $dbcon, $path_product_data;

	if ( $seq ) {
		$SQL = "
			select * from tbl_file
			where
				category = '".$category."'
				and seq = '".$seq."'
		";
		$rs = $dbcon -> query($SQL);
		while( $row = $dbcon -> fetch_array($rs) ) {
			DeleteFile($path.$row["file_name"]);
			if (  $thumb ) {
				$Arr_thumb = explode(",", $thumb);
				for ( $i = 0; $i < count($Arr_thumb) ; $i++) {
					DeleteFile($path.$row["file_name"].".".$Arr_thumb[$i]);
				}
			}

	//		echo "file_name : ".$rs["file_name"]."<BR>";
	//		echo $path_product_data.$rs["file_name"]."<BR>";
		}


		$SQL = "
			delete from tbl_file
			where
				category = '".$category."'
				and seq = '".$seq."'
		";

		$dbcon -> query($SQL);

//		echo $SQL."<BR>";
//		exit;
	}
	return;
}




function FileDBDelete($idx, $path, $thumb="") {
	global $dbcon;

	if ( $idx ) {
		$SQL = "
			select * from tbl_file
			where idx = '".$idx."'
		";
		$rs = $dbcon -> fetch_array($dbcon -> query($SQL));
//		echo "file_name : ".$rs["file_name"]."<BR>";
//		echo $path_product_data.$rs["file_name"]."<BR>";
		//echo $SQL."<BR>";

		DeleteFile($path.$rs["file_name"]);

		if (  $thumb ) {
			$Arr_thumb = explode(",", $thumb);
			for ( $i = 0; $i < count($Arr_thumb) ; $i++) {
				DeleteFile($path.$rs["file_name"].".".$Arr_thumb[$i]);
			}
		}
		$SQL = "
			delete from tbl_file
				where idx = '".$idx."'
		";

		$dbcon -> query($SQL);

//		echo $SQL."<BR>";
//		exit;
	}
	return;
}


function FileDBInsert($category, $seq) {
	global $upload, $dbcon;

	$Result = "";

	// 다중 파일 업로드 정보를 가져옴
	if(is_array($upload->upload_tmpFileName))
	{
		for($i = 0; $i < sizeof($upload->upload_tmpFileName); $i++)
		{
//			echo "다중 파일 업로드 정보를 가져옴 [".$i."]<BR>";
			$fileDirectory = $upload->upload_directory;
			$fileSubDirectory = $upload->upload_subdirectory;
			$realfileName = $upload->upload_realfileName[$i];
			$fileName = $upload->upload_fileName[$i];
			$fileSize = $upload->upload_fileSize[$i];
			$fileType = $upload->upload_fileType[$i];
			$fileWidth = $upload->upload_fileWidth[$i];
			$fileHeight = $upload->upload_fileHeight[$i];
			$fileExt = $upload->upload_fileExt[$i];

			if ( getLen($fileName) > 0 ) {
				$SQL = "
					insert into tbl_file set
						idx = null
						, category = '$category'
						, bc_id = ''
						, seq = '$seq'
						, file_name = '$fileName'
						, file_realname = '$realfileName'
						, file_size = '$fileSize'
						, down_cnt = '0'
						, regdate = NOW()
						, topdata = ''
				";
				$dbcon -> query($SQL);
			}
		}
	}

	// 단일 파일 업로드 정보를 가져옴
	else
	{
//		echo "단일 파일 업로드 정보를 가져옴<BR>";
		$fileDirectory = $upload->upload_directory;
		$fileSubDirectory = $upload->upload_subdirectory;
		$realfileName = $upload->upload_realfileName;
		$fileName = $upload->upload_fileName;
		$fileSize = $upload->upload_fileSize;
		$fileType = $upload->upload_fileType;
		$fileWidth = $upload->upload_fileWidth;
		$fileHeight = $upload->upload_fileHeight;
		$fileExt = $upload->upload_fileExt;

		if ( getLen($fileName) > 0 ) {
			$SQL = "
				insert into tbl_file set
					idx = null
					, category = '$category'
					, bc_id = ''
					, seq = '$seq'
					, file_name = '$fileName'
					, file_realname = '$realfileName'
					, file_size = '$fileSize'
					, down_cnt = '0'
					, regdate = '$regdate'
					, topdata = ''
			";
			$dbcon -> query($SQL);
		}

//		echo "fileDirectory : ". $fileDirectory."<BR>";
//		echo "fileSubDirectory : ". $fileSubDirectory."<BR>";
//		echo "fileName : ". $fileName."<BR>";
//		echo "fileSize : ". $fileSize."<BR>";
//		echo "fileType : ". $fileType."<BR>";
//		echo "fileWidth : ". $fileWidth."<BR>";
//		echo "fileHeight : ". $fileHeight."<BR>";
//		echo "fileExt : ". $fileExt."<BR>";
	}

	//echo $Result."<BR>";

	return;
}




function getFileName() {
	global $upload;

	$Result = "";

	// 다중 파일 업로드 정보를 가져옴
	if(is_array($upload->upload_tmpFileName))
	{
		for($i = 0; $i < sizeof($upload->upload_tmpFileName); $i++)
		{
//			echo "다중 파일 업로드 정보를 가져옴 [".$i."]<BR>";
			$fileDirectory = $upload->upload_directory;
			$fileSubDirectory = $upload->upload_subdirectory;
			$realfileName = $upload->upload_realfileName[$i];
			$fileName = $upload->upload_fileName[$i];
			$fileSize = $upload->upload_fileSize[$i];
			$fileType = $upload->upload_fileType[$i];
			$fileWidth = $upload->upload_fileWidth[$i];
			$fileHeight = $upload->upload_fileHeight[$i];
			$fileExt = $upload->upload_fileExt[$i];

			if ( getLen($fileName) > 0 ) {
				$Result .= $realfileName.",".$fileName.",".$fileSize.",".$fileType.",".$fileWidth.",".$fileHeight.",".$fileExt;//$fileDirectory.",".
				if ( $i < sizeof($upload->upload_tmpFileName)-1 )
					$Result .= "|";
			}

//			echo "fileDirectory : ". $fileDirectory."<BR>";
//			echo "fileSubDirectory : ". $fileSubDirectory."<BR>";
//			echo "fileName : ". $fileName."<BR>";
//			echo "fileSize : ". $fileSize."<BR>";
//			echo "fileType : ". $fileType."<BR>";
//			echo "fileWidth : ". $fileWidth."<BR>";
//			echo "fileHeight : ". $fileHeight."<BR>";
//			echo "fileExt : ". $fileExt."<BR>";
		}
	}

	// 단일 파일 업로드 정보를 가져옴
	else
	{
//		echo "단일 파일 업로드 정보를 가져옴<BR>";
		$fileDirectory = $upload->upload_directory;
		$fileSubDirectory = $upload->upload_subdirectory;
		$realfileName = $upload->upload_realfileName;
		$fileName = $upload->upload_fileName;
		$fileSize = $upload->upload_fileSize;
		$fileType = $upload->upload_fileType;
		$fileWidth = $upload->upload_fileWidth;
		$fileHeight = $upload->upload_fileHeight;
		$fileExt = $upload->upload_fileExt;

		if ( getLen($fileName) > 0 ) {
			$Result .= $realfileName.",".$fileName.",".$fileSize.",".$fileType.",".$fileWidth.",".$fileHeight.",".$fileExt;//$fileDirectory.",".
		}

//		echo "fileDirectory : ". $fileDirectory."<BR>";
//		echo "fileSubDirectory : ". $fileSubDirectory."<BR>";
//		echo "fileName : ". $fileName."<BR>";
//		echo "fileSize : ". $fileSize."<BR>";
//		echo "fileType : ". $fileType."<BR>";
//		echo "fileWidth : ". $fileWidth."<BR>";
//		echo "fileHeight : ". $fileHeight."<BR>";
//		echo "fileExt : ". $fileExt."<BR>";
	}

	//echo $Result."<BR>";

	return $Result;
}

	###############################################################
	// DB File 정보 가져오기
	function setFileName($FileName) {
		//isnull($FileName);

		if ( getLen($FileName) > 0 ) {
			$Arr_FileName = explode("|", $FileName);
			$Result = Array();

			// 다중 파일 업로드 정보를 가져옴
			if( count($Arr_FileName) > 1 ) {
				//echo "다중 파일 업로드 정보를 가져옴 [".$i."]<BR>";
				for ( $i = 0 ; $i < count($Arr_FileName); $i++ ) {
					$Result[$i] = explode(",", $Arr_FileName[$i]);
				}
			}

			// 단일 파일 업로드 정보를 가져옴
			else {
				//echo "단일 파일 업로드 정보를 가져옴<BR>";
				$Result[0] = explode(",", $Arr_FileName[0]);
				//$Result .= $fileSubDirectory.",".$fileName.",".$fileSize.",".$fileType.",".$fileWidth.",".$fileHeight.",".$fileExt;//$fileDirectory.",".
			}
		}

//		for ( $i = 0 ; $i < count($Result); $i ++ ) {
//			for ( $j = 0 ; $j < count($Result[$i]); $j ++ ) {
//				echo "$i, $j : ".$Result[$i][$j]."<BR>";
//			}
//		}

		return $Result;
	}
	###############################################################


	###############################################################
	// 파일크기 정리해서 리턴
	function PrintFileSize($FileSize) {
		if ($FileSize < 1024) {
			$val = "(".$FileSize." Byte)";
		}
		else if ($FileSize >= 1024 && $FileSize < 1024*1024) {
			$val = "(".round($FileSize/1024)." KB)";
		}
		else if ($FileSize >= 1024*1024 && $FileSize < 1024*1024*1024) {
			$val = "(".round($FileSize/(1024*1024))." MB)";
		}
		else if ($FileSize >= 1024*1024*1024 && $FileSize < 1024*1024*1024*1024) {
			$val = "(".round($FileSize/(1024*1024*1024))." GB)";
		}
		return $val;
	}
	###############################################################


	###############################################################
	//파일삭제 (경로 + 파일명)
	function DeleteFile($file_name)
	{
		//$uploaddir = '../uploadfile/';

		// 본파일 삭제
		if(file_exists($file_name)) {
			//echo "파일 있음";
			if(!(@unlink($file_name))) {
				//echo "파일 삭제 실패";
				//errMsg('$file_name ϻ');
			}
		}
		else {
			//echo "파일 없음";
		}

		// 썸네일 삭제
		if(file_exists($file_name.".thumb")) {
			//echo "파일 있음";
			if(!(@unlink($file_name.".thumb"))) {
				//echo "파일 삭제 실패";
				//errMsg('$file_name ϻ');
			}
		}
		else {
			//echo "파일 없음";
		}

		// 썸네일 삭제
		if(file_exists($file_name.".1thumb")) {
			//echo "파일 있음";
			if(!(@unlink($file_name.".1thumb"))) {
				//echo "파일 삭제 실패";
				//errMsg('$file_name ϻ');
			}
		}
		else {
			//echo "파일 없음";
		}

		// 썸네일 삭제
		if(file_exists($file_name.".2thumb")) {
			//echo "파일 있음";
			if(!(@unlink($file_name.".2thumb"))) {
				//echo "파일 삭제 실패";
				//errMsg('$file_name ϻ');
			}
		}
		else {
			//echo "파일 없음";
		}

		// 썸네일 삭제
		if(file_exists($file_name.".3thumb")) {
			//echo "파일 있음";
			if(!(@unlink($file_name.".3thumb"))) {
				//echo "파일 삭제 실패";
				//errMsg('$file_name ϻ');
			}
		}
		else {
			//echo "파일 없음";
		}
	}
	###############################################################


?>


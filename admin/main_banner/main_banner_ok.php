<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$idx = REQSTR($_POST[idx], "");
	$gubun = REQSTR($_POST[gubun], "");
	$tit1 = REQSTR($_POST[tit1], "");
	$tit2 = REQSTR($_POST[tit2], "");
	$contents = REQSTR($_POST[contents], "");
	$link = REQSTR($_POST["link"], "");

	isnull($gubun);


	$SQL = "
		select idx, imgfile
		from tbl_main_product
		where gubun = '".$gubun."'
		order by idx desc
		limit 0, 1
	";
	$rows = $dbcon -> fetch_array( $dbcon->query($SQL) );
	if ( $rows ) {
		$idx = $rows[idx];
		$imgfile_old = $rows[imgfile];
	}

	#### 파일처리 Start
	$ObjFileName = "imgfile";
	$upload_path = $path_data."main_product/";
	$bc_upfile_image_width = "86";
	$bc_upfile_image_height = "125";
	#### 파일처리 End


	//---------------- 수정 Start ---------------------
	if ( getLen($idx) ) {

		#### 파일처리 Start
		$ObjFileName = "imgfile";
		${"Arr_".$ObjFileName} = setFileName(${$ObjFileName."_old"});

		if ( count(${$ObjFileName."_del"}) > 0 ) {
			for ($i = 0; $i < count(${$ObjFileName."_del"}); $i++ ) {
				if ( getLen(${$ObjFileName."_del"}[$i]) > 0 ) {
					DeleteFile($upload_path."/".${"Arr_".$ObjFileName}[$i][1]);
					${"Arr_".$ObjFileName}[$i] = "";
				}
			}
		}

		${"Result".$ObjFileName} = "";
		for ($i = 0; $i < count(${"Arr_".$ObjFileName}); $i++) {
			if ( is_array(${"Arr_".$ObjFileName}[$i]) ) {
				for ( $j = 0 ; $j < count(${"Arr_".$ObjFileName}[$i]) ; $j++ ) {
					${"Result".$ObjFileName} .= ${"Arr_".$ObjFileName}[$i][$j];
					if ($j < count(${"Arr_".$ObjFileName}[$i])-1) ${"Result".$ObjFileName} .=",";
				}
				if ($i < count(${"Arr_".$ObjFileName})-1) ${"Result".$ObjFileName} .="|";
			}
		}

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
//		echo $upload_path."<BR>";
//		echo $imgfile."<BR>";
//		exit;
		#### 파일처리 End


		$SQL = "
			update tbl_main_product
			set
				tit1 = '".$tit1."'
				, tit2 = '".$tit2."'
				, contents = '".$contents."'
				, `link` = '".$link."'
				, imgfile = '".$imgfile."'
			where
				gubun = '".$gubun."'
				and idx = '".$idx."'
		";
		$dbcon -> query($SQL);


	}
	//---------------- 수정 End ---------------------

	//---------------- 신규 입력 Start ---------------------
	else {
		#### 파일처리 Start
		if ( getLen($imgfile) > 0 ) {
			try {
				$upload = new upload($upload_path);
				$upload->define($_FILES[$ObjFileName]);
				$upload->makeThumbnailed($bc_upfile_image_width, $bc_upfile_image_height, "thumb");
				$upload->checkImageOnly();
				$upload->uploadedFiles();
				${$ObjFileName} = getFileName();
			}
			catch(Exception $e) {
				// 에러처리 구문
				exit($e->getMessage());
			}
		}
		#### 파일처리 End

		$SQL = "
			INSERT INTO tbl_main_product (
				idx , tit1 , tit2 , contents , `link` , imgfile , gubun ,regdate
			)
			VALUES (
				NULL , '".$tit1."', '".$tit2."', '".$contents."', '".$link."', '".$imgfile."', '".$gubun."',  CURRENT_TIMESTAMP
			);
		";
		$dbcon -> query($SQL);
	}
	$dbcon -> dbcon_close();
	alert_page("처리되었습니다.", "main_product.php?gubun=".$gubun);
	//---------------- 신규 입력 Start ---------------------
?>

<? $dbcon -> dbcon_close();?>

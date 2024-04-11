<?
/*=========================================================

여기에는 추가로 작성되는 컬럼이나 각 게시판의 특성을 나타내서 만들어 내도록 한다.

아래의 것은 예제로 이니 참고 하도록... by JHW

=========================================================*/

	// 게시판 관련 업데이트
	$sql = "update tbl_board_".$bc_id." set sort_order = ".$sort_order.", is_notification_visible = '".$is_notification_visible."' where seq= ".$seq."";
	$result = $dbcon -> query($sql);
	if (!$result) {
		$dbcon -> dbcon_close();
//		echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
//		echo "에러";
		alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
		exit;
	}

	//  보험사 및 서비스 등록
	// 기존 정보 삭제
	$SQL_OP1_del = "delete from tbl_board_product_service where pr_seq = '".$seq."' ";
	$RS_OP1_del = $dbcon -> query($SQL_OP1_del);
	// 신규정보 입력
	for ($k=0;$k<=$_POST["arr_num1"];$k++){
		if ($_POST["ins_seq"][$k]){
		$SQL_in1 = "insert into tbl_board_product_service set";
		$SQL_in1 .= " pr_seq = '".$seq."' ";
		$SQL_in1 .= " , ins_seq = '".$_POST["ins_seq"][$k]."' ";
		$SQL_in1 .= " , service_gubun = '".$_POST["service_gubun"][$k]."' ";
//		echo $SQL_in1."<br>";
		$RS_In1 = $dbcon -> query($SQL_in1);
		}
	}


	// 보험상품 알릴사항 입력
	// 기존 정보 삭제
	$SQL_OP1_del = "delete from tbl_board_product_notice where pr_seq = '".$seq."' ";
	$RS_OP1_del = $dbcon -> query($SQL_OP1_del);
	// 신규정보 입력
	for ($k=0;$k<=$_POST["arr_num2"];$k++){
		if ($_POST["pr_notice"][$k]){
		$SQL_in1 = "insert into tbl_board_product_notice set";
		$SQL_in1 .= " pr_seq = '".$seq."' ";
		$SQL_in1 .= " , pr_notice = '".$_POST["pr_notice"][$k]."' ";
//		echo $SQL_in1."<br>";
		$RS_In1 = $dbcon -> query($SQL_in1);
		}
	}

	/////////////////////////////////////////////////////////////////////////////
	// 2023-06-21 added by kyle
	// Insert product category information
	$category_depth0 = REQSTR($category_depth0, "");
	$category_depth1 = REQSTR($category_depth1, "");
	$category_depth2 = REQSTR($category_depth2, "");
	$category_depth3 = REQSTR($category_depth3, "");

	// Delete all previous data for updates
	$categorySQL = "delete from tbl_board_product_category where product_seq = ".$seq." ";
	$dbcon -> query($categorySQL);

	$categorySQL = "insert into tbl_board_product_category(product_seq, category_code) ";

	if (isset($category_depth0) && $category_depth0 != ""){
		$SQL = $categorySQL."values(".$seq.", '".$category_depth0."')";
		$dbcon -> query($SQL);
	}
	if (isset($category_depth1) && $category_depth1 != ""){
		$SQL = $categorySQL."values(".$seq.", '".$category_depth1."')";
		$dbcon -> query($SQL);
	}
	if (isset($category_depth2) && $category_depth2 != ""){
		$SQL = $categorySQL."values(".$seq.", '".$category_depth2."')";
		$dbcon -> query($SQL);
	}
	if (isset($category_depth3) && $category_depth3 != ""){
		$SQL = $categorySQL."values(".$seq.", '".$category_depth3."')";
		$dbcon -> query($SQL);
	}
	/////////////////////////////////////////////////////////////////////////////

	//==================================================================================================
	//엑셀업로드
	//==================================================================================================
	if ($_FILES["ex_ceountry"]){
		error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_USER_DEPRECATED);
		include $_SERVER["DOCUMENT_ROOT"]."/_util/PHPExcel-1.8/Classes/PHPExcel.php";
		//-- 읽을 범위 필터 설정 (아래는 A열만 읽어오도록 설정함  => 속도를 중가시키기 위해)
//		class MyReadFilter implements PHPExcel_Reader_IReadFilter
//		{
//			public function readCell($column, $row, $worksheetName = '') {
//				// Read rows 1 to 7 and columns A to E only
//				if (in_array($column,range('A','BK'))) {
//					return true;
//				}
//				return false;
//			}
//		}
//		$filterSubset = new MyReadFilter();

		// 파일이 있는지 확인후 복사
		if($_FILES["ex_ceountry"]["name"]) {

			$filename = $_FILES["ex_ceountry"]["name"];
			$tmp_file = $_FILES["ex_ceountry"]["tmp_name"];
			$filesize = $_FILES["ex_ceountry"]["size"];

			$UpFilePathInfo = pathinfo($filename);
			$UpFileExt = strtolower($UpFilePathInfo["extension"]);

			//  확장자 체크 : csv파일이 아니면 history(-1)
			$file_info = explode(".", $filename);
//			if($file_info[1] != "xls") {
//				ERROR_BACK("xls 파일만 업로드 가능합니다.");
//				exit;
//			}

			$filename = "xls_ord_upload".date("YmdHis",time()).".".$UpFileExt;

			@move_uploaded_file($tmp_file, $_SERVER["DOCUMENT_ROOT"]."/_data/excel/$filename");   //파일복사
			@unlink($tmp_file);

			$url = $_SERVER["DOCUMENT_ROOT"]."/_data/excel/".$filename;

			//파일 타입 설정 (확자자에 따른 구분)
			$inputFileType = 'Excel2007';
			if($file_info[1] == "xls") {
				$inputFileType = 'Excel5';
			}

	//		echo $url."<br>";
	//		echo $inputFileType."<br>";

			//==================================================================
			//  PHPEXCEL 용 로더
			//==================================================================
			//엑셀리더 초기화
			$objReader = PHPExcel_IOFactory::createReader($inputFileType);

			//데이터만 읽기(서식을 모두 무시해서 속도 증가 시킴)
			$objReader->setReadDataOnly(true);

			//범위 지정(위에 작성한 범위필터 적용)
			//	$objReader->setReadFilter($filterSubset);

			//업로드된 엑셀 파일 읽기
			//	$objPHPExcel = $objReader->load($url);
			$objPHPExcel = PHPExcel_IOFactory::load($url);

			//첫번째 시트로 고정
			$objPHPExcel->setActiveSheetIndex(0);

			//고정된 시트 로드
			$objWorksheet = $objPHPExcel->getActiveSheet();

			$cellIterator = $objWorksheet->getRowIterator()->current()->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells( true );
			$maxRow = $objWorksheet->getHighestRow();

			for($i=2;$i<=$maxRow;$i++) {
				$SQL_DUP_CHK = " SELECT count(*) FROM tbl_board_product_country WHERE pr_seq = '".$seq."' ";
				$SQL_DUP_CHK .= " AND c_code = '".str_replace("\"","",$objWorksheet->getCell('C' . $i)->getValue())."'  ";
				$count = $dbcon->getCount($SQL_DUP_CHK);
				
				if($count > 0) {
					$SQL_N_UP = " UPDATE tbl_board_product_country set";
					$SQL_N_UP .= " c_area = '".str_replace("\"","",$objWorksheet->getCell('A' . $i)->getValue())."' ";
					$SQL_N_UP .= " ,c_name = '".str_replace("\"","",$objWorksheet->getCell('B' . $i)->getValue())."' ";
					$SQL_N_UP .= " ,trip_yn = '".str_replace("\"","",$objWorksheet->getCell('D' . $i)->getValue())."' ";
					$SQL_N_UP .= " WHERE pr_seq = '".$seq."' ";
					$SQL_N_UP .= " AND c_code = '".str_replace("\"","",$objWorksheet->getCell('C' . $i)->getValue())."' ";
					
					$RS_N_UP = $dbcon -> query($SQL_N_UP);
				} else {
					$SQL_N_IN = "insert into tbl_board_product_country set";
					$SQL_N_IN .= " pr_seq = '".$seq."' ";
					$SQL_N_IN .= " ,c_code = '".str_replace("\"","",$objWorksheet->getCell('C' . $i)->getValue())."' ";
					$SQL_N_IN .= " ,c_area = '".str_replace("\"","",$objWorksheet->getCell('A' . $i)->getValue())."' ";
					$SQL_N_IN .= " ,c_name = '".str_replace("\"","",$objWorksheet->getCell('B' . $i)->getValue())."' ";
					$SQL_N_IN .= " ,trip_yn = '".str_replace("\"","",$objWorksheet->getCell('D' . $i)->getValue())."' ";
					
					$RS_N_IN = $dbcon -> query($SQL_N_IN);
				}
				
				
				
			}


		}else {

//			ERROR_BACK("xls 파일이 없습니다.");
//			exit;

		}
	}
?>
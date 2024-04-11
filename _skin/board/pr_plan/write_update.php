
<?
/*=========================================================

여기에는 추가로 작성되는 컬럼이나 각 게시판의 특성을 나타내서 만들어 내도록 한다.

아래의 것은 예제로 이니 참고 하도록... by JHW

=========================================================*/

  if ($ext1 != 'N') $ext1 = 'Y';
  if ($ext2 != 'N') $ext2 = 'Y';
  if ($ext3 != 'N') $ext3 = 'Y';

	$SQL_C = "select *  ";
	$SQL_C .= "from tbl_board_product_category a ";
	$SQL_C .= "  inner join tbl_board_category b on (a.category_code = b.category_code) ";
	$SQL_C .= "where b.depth = 1 and a.product_seq = " . $pr_cd . " ";
	$RS_C = $dbcon->query($SQL_C);
	$row_pcate = $dbcon->fetch_array($RS_C);

	if ($row_pcate["category_name"] == '단기'){
		$chk_period = 'Y';
	} else {
		$chk_period = 'N';
	}

	// 게시판 관련 업데이트
	$sql = "update tbl_board_".$bc_id." set
				pr_cd = '".$pr_cd."'
				, ins_cd = '".$ins_cd."'
				, plan_cd = '".$plan_cd."'
				, chk_service = '".$chk_service."'
				, common_amount = '".$common_amount."'
				, s_date = '".$s_date."'
				, s_date_time = '".$s_date_time."'
				, e_date = '".$e_date."'
				, e_date_time = '".$e_date_time."'
				, agree_cd = '".($agree_cd > 0 ? $agree_cd : 0)."'
				, service_cd = '".($service_cd > 0 ? $service_cd : 0)."'
				, service_txt = '".$service_txt."'
				, ins_plan_name = '".$ins_plan_name."'
				, plan_isdn = '".$plan_isdn."'
				, stock_isdn = '".$stock_isdn."'
				, plan_status = '".$plan_status."'
				, chk_period = '".$chk_period. "'
				, service_amount_per_day = ".($service_amount_per_day > 0 ? $service_amount_per_day : 'null')."
				, guarantee1_ins_seq = '".($guarantee1_ins_seq > 0 ? $guarantee1_ins_seq : 0). "'
				, guarantee2_ins_seq = '".($guarantee2_ins_seq > 0 ? $guarantee2_ins_seq : 0). "'
				, ins_term1_seq = '".($ins_term1_seq > 0 ? $ins_term1_seq : 0). "'
				, ins_term2_seq = '".($ins_term2_seq > 0 ? $ins_term2_seq : 0). "'
				, ext1 = '".$ext1. "'
				, ext2 = '".$ext2. "'
				, ext3 = '".$ext3. "'
			where seq= ".$seq."";

	$result = $dbcon -> query($sql);
	if (!$result) {
		$dbcon -> dbcon_close();
//		echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
//		echo "에러";
		alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
		exit;
	}

	// 보장내역 저장
	if (is_array($_POST["g_name"])){
		// 기존 보장내역 삭제
		$DEL_G = "delete from tbl_board_plan_guarantee where plan_cd = ".$seq." ";
		$result_DG = $dbcon -> query($DEL_G);

		for($k=0;$k<count($_POST["g_name"]);$k++){
			$SQL_In = "insert into tbl_board_plan_guarantee set";
			$SQL_In .= "  plan_cd='".$seq."' ";
			$SQL_In .= " , g_name='".$_POST["g_name"][$k]."' ";
			$SQL_In .= " , g_amount='".$_POST["g_amount"][$k]."' ";
			$SQL_In .= " , g_amount_certificate='".$_POST["g_amount_certificate"][$k]."' ";
			$SQL_In .= " , g_seq='".$_POST["g_seq"][$k]."' ";
			// echo $SQL_In."<br>";
			$result_in = $dbcon -> query($SQL_In);
		}
	}

	if (is_array($_POST["k_name"])){
		// 기존 서비스내역 삭제
		$SQL_OP1_del = "delete from tbl_board_plan_insuplus where plan_seq = '".$seq."' ";
		$RS_OP1_del = $dbcon -> query($SQL_OP1_del);
		for($k=0;$k<count($_POST["k_name"]);$k++){
			$SQL_in1 = "insert into tbl_board_plan_insuplus set";
			$SQL_in1 .= " plan_seq = '".$seq."' ";
			$SQL_in1 .= " , k_name = '".$_POST["k_name"][$k]."' ";
			$SQL_in1 .= " , e_name = '".$_POST["e_name"][$k]."' ";
			$SQL_in1 .= " , k_amount = '".$_POST["k_amount"][$k]."' "; //국문한도
			$SQL_in1 .= " , e_amount = '".$_POST["e_amount"][$k]."' "; //영문한도
			$SQL_in1 .= " , exposure_order='".$_POST["exposure_order"][$k]."' ";
			$SQL_in1 .= " , insuplus_opt_idx=".$_POST["insuplus_opt_idx"][$k]." ";
			$RS_In1 = $dbcon -> query($SQL_in1);
		}
	}

	//==================================================================================================
	//엑셀업로드  시작
	//==================================================================================================
	error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_USER_DEPRECATED);
	include $_SERVER["DOCUMENT_ROOT"]."/_util/PHPExcel-1.8/Classes/PHPExcel.php";
	if ($_FILES["file1"]){
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
		if($_FILES["file1"]["name"]) {

			$filename = $_FILES["file1"]["name"];
			$tmp_file = $_FILES["file1"]["tmp_name"];
			$filesize = $_FILES["file1"]["size"];

			$UpFilePathInfo = pathinfo($filename);
			$UpFileExt = strtolower($UpFilePathInfo["extension"]);

			//  확장자 체크 : csv파일이 아니면 history(-1)
			$file_info = explode(".", $filename);
			$filename = "xls_ord_upload".date("YmdHis",time()).".".$UpFileExt;

			@move_uploaded_file($tmp_file, $_SERVER["DOCUMENT_ROOT"]."/_data/excel/$filename");   //파일복사
			@unlink($tmp_file);

			$url = $_SERVER["DOCUMENT_ROOT"]."/_data/excel/".$filename;

			//파일 타입 설정 (확자자에 따른 구분)
			$inputFileType = 'Excel2007';
			if($file_info[1] == "xls") {
				$inputFileType = 'Excel5';
			}
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
			$DEL_A1 = "delete from tbl_board_plan_amount1 where plan_cd='".$seq."' and plan_type = 'G' ";
			$RS_D1 = $dbcon -> query($DEL_A1);
			for($i=2;$i<=$maxRow;$i++) {
				$SQL_N_IN = "insert into tbl_board_plan_amount1 set";
				$SQL_N_IN .= " plan_cd = '".$seq."' ";
				$SQL_N_IN .= " ,plan_txt = '".str_replace("\"","",$objWorksheet->getCell('A' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,gender = '".str_replace("\"","",$objWorksheet->getCell('B' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,age = '".str_replace("\"","",$objWorksheet->getCell('C' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period1 = '".str_replace("\"","",$objWorksheet->getCell('D' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period2 = '".str_replace("\"","",$objWorksheet->getCell('E' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period3 = '".str_replace("\"","",$objWorksheet->getCell('F' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period4 = '".str_replace("\"","",$objWorksheet->getCell('G' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period5 = '".str_replace("\"","",$objWorksheet->getCell('H' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period6 = '".str_replace("\"","",$objWorksheet->getCell('I' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period7 = '".str_replace("\"","",$objWorksheet->getCell('J' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period8 = '".str_replace("\"","",$objWorksheet->getCell('K' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period9 = '".str_replace("\"","",$objWorksheet->getCell('L' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period10 = '".str_replace("\"","",$objWorksheet->getCell('M' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period11 = '".str_replace("\"","",$objWorksheet->getCell('N' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period12 = '".str_replace("\"","",$objWorksheet->getCell('O' . $i)->getValue())."' ";
				if ($chk_period=="Y"){		//장기 기간 플랜인경우만 해당
				$SQL_N_IN .= " ,period13 = '".str_replace("\"","",$objWorksheet->getCell('P' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period14 = '".str_replace("\"","",$objWorksheet->getCell('Q' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period15 = '".str_replace("\"","",$objWorksheet->getCell('R' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period16 = '".str_replace("\"","",$objWorksheet->getCell('S' . $i)->getValue())."' ";
				}
				$SQL_N_IN .= " ,plan_type = 'G' ";
				// echo $SQL_N_IN."<br>";
				$RS_N_IN = $dbcon -> query($SQL_N_IN);
			}
		}
	}

	if ($_FILES["file2"]){
		// 파일이 있는지 확인후 복사
		if($_FILES["file2"]["name"]) {

			$filename = $_FILES["file2"]["name"];
			$tmp_file = $_FILES["file2"]["tmp_name"];
			$filesize = $_FILES["file2"]["size"];

			$UpFilePathInfo = pathinfo($filename);
			$UpFileExt = strtolower($UpFilePathInfo["extension"]);

			//  확장자 체크 : csv파일이 아니면 history(-1)
			$file_info = explode(".", $filename);
			$filename = "xls_ord_upload".date("YmdHis",time()).".".$UpFileExt;

			@move_uploaded_file($tmp_file, $_SERVER["DOCUMENT_ROOT"]."/_data/excel/$filename");   //파일복사
			@unlink($tmp_file);

			$url = $_SERVER["DOCUMENT_ROOT"]."/_data/excel/".$filename;

			//파일 타입 설정 (확자자에 따른 구분)
			$inputFileType = 'Excel2007';
			if($file_info[1] == "xls") {
				$inputFileType = 'Excel5';
			}
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
			$DEL_A1 = "delete from tbl_board_plan_amount1 where plan_cd='".$seq."' and plan_type = 'S' ";
			$RS_D1 = $dbcon -> query($DEL_A1);
			for($i=2;$i<=$maxRow;$i++) {
				$SQL_N_IN = "insert into tbl_board_plan_amount1 set";
				$SQL_N_IN .= " plan_cd = '".$seq."' ";
				$SQL_N_IN .= " ,plan_txt = '".str_replace("\"","",$objWorksheet->getCell('A' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,gender = '".str_replace("\"","",$objWorksheet->getCell('B' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,age = '".str_replace("\"","",$objWorksheet->getCell('C' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period1 = '".str_replace("\"","",$objWorksheet->getCell('D' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period2 = '".str_replace("\"","",$objWorksheet->getCell('E' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period3 = '".str_replace("\"","",$objWorksheet->getCell('F' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period4 = '".str_replace("\"","",$objWorksheet->getCell('G' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period5 = '".str_replace("\"","",$objWorksheet->getCell('H' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period6 = '".str_replace("\"","",$objWorksheet->getCell('I' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period7 = '".str_replace("\"","",$objWorksheet->getCell('J' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period8 = '".str_replace("\"","",$objWorksheet->getCell('K' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period9 = '".str_replace("\"","",$objWorksheet->getCell('L' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period10 = '".str_replace("\"","",$objWorksheet->getCell('M' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period11 = '".str_replace("\"","",$objWorksheet->getCell('N' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period12 = '".str_replace("\"","",$objWorksheet->getCell('O' . $i)->getValue())."' ";
				if ($chk_period=="Y"){		//장기 기간 플랜인경우만 해당
				$SQL_N_IN .= " ,period13 = '".str_replace("\"","",$objWorksheet->getCell('P' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period14 = '".str_replace("\"","",$objWorksheet->getCell('Q' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period15 = '".str_replace("\"","",$objWorksheet->getCell('R' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,period16 = '".str_replace("\"","",$objWorksheet->getCell('S' . $i)->getValue())."' ";
				}
				$SQL_N_IN .= " ,plan_type = 'S' ";
				// echo $SQL_N_IN."<br>";
				$RS_N_IN = $dbcon -> query($SQL_N_IN);
			}
		}
	}

	// 2023-07-18 서비스 요금테이블이 보험요금테이블과 통합되면서 !
	// 사용되지 않도록 file2 => file20 으로 변경 함
	if ($_FILES["file20"]){
	// if ($_FILES["file2"]){
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
		if($_FILES["file2"]["name"]) {

			$filename = $_FILES["file2"]["name"];
			$tmp_file = $_FILES["file2"]["tmp_name"];
			$filesize = $_FILES["file2"]["size"];

			$UpFilePathInfo = pathinfo($filename);
			$UpFileExt = strtolower($UpFilePathInfo["extension"]);

			//  확장자 체크 : csv파일이 아니면 history(-1)
			$file_info = explode(".", $filename);
			$filename = "xls_ord_upload".date("YmdHis",time())."2.".$UpFileExt;

			@move_uploaded_file($tmp_file, $_SERVER["DOCUMENT_ROOT"]."/_data/excel/$filename");   //파일복사
			@unlink($tmp_file);

			$url = $_SERVER["DOCUMENT_ROOT"]."/_data/excel/".$filename;

			//파일 타입 설정 (확자자에 따른 구분)
			$inputFileType = 'Excel2007';
			if($file_info[1] == "xls") {
				$inputFileType = 'Excel5';
			}
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
			$DEL_A2 = "delete from tbl_board_plan_amount2 where plan_cd='".$seq."' ";
			$RS_D2 = $dbcon -> query($DEL_A2);
			for($i=2;$i<=$maxRow;$i++) {
				$SQL_N_IN = "insert into tbl_board_plan_amount2 set";
				$SQL_N_IN .= " plan_cd = '".$seq."' ";
				$SQL_N_IN .= " ,stype = '".str_replace("타입","",$objWorksheet->getCell('A' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,mon1 = '".str_replace("\"","",$objWorksheet->getCell('B' . $i)->getValue())."' ";
				$SQL_N_IN .= " ,mon2 = '".str_replace("\"","",$objWorksheet->getCell('C' . $i)->getCalculatedValue())."' ";
				$SQL_N_IN .= " ,mon3 = '".str_replace("\"","",$objWorksheet->getCell('D' . $i)->getCalculatedValue())."' ";
				$SQL_N_IN .= " ,mon4 = '".str_replace("\"","",$objWorksheet->getCell('E' . $i)->getCalculatedValue())."' ";
				$SQL_N_IN .= " ,mon5 = '".str_replace("\"","",$objWorksheet->getCell('F' . $i)->getCalculatedValue())."' ";
				$SQL_N_IN .= " ,mon6 = '".str_replace("\"","",$objWorksheet->getCell('G' . $i)->getCalculatedValue())."' ";
				$SQL_N_IN .= " ,mon7 = '".str_replace("\"","",$objWorksheet->getCell('H' . $i)->getCalculatedValue())."' ";
				$SQL_N_IN .= " ,mon8 = '".str_replace("\"","",$objWorksheet->getCell('I' . $i)->getCalculatedValue())."' ";
				$SQL_N_IN .= " ,mon9 = '".str_replace("\"","",$objWorksheet->getCell('J' . $i)->getCalculatedValue())."' ";
				$SQL_N_IN .= " ,mon10 = '".str_replace("\"","",$objWorksheet->getCell('K' . $i)->getCalculatedValue())."' ";
				$SQL_N_IN .= " ,mon11 = '".str_replace("\"","",$objWorksheet->getCell('L' . $i)->getCalculatedValue())."' ";
				$SQL_N_IN .= " ,mon12 = '".str_replace("\"","",$objWorksheet->getCell('M' . $i)->getCalculatedValue())."' ";
//				echo $SQL_N_IN."<br>";
				$RS_N_IN = $dbcon -> query($SQL_N_IN);
			}
		}
	}
	//==================================================================================================
	//엑셀업로드  종료
	//==================================================================================================
?>
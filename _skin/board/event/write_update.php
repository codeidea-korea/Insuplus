<?
/*=========================================================

여기에는 추가로 작성되는 컬럼이나 각 게시판의 특성을 나타내서 만들어 내도록 한다.

아래의 것은 예제로 이니 참고 하도록... by JHW

=========================================================*/

$domain = getDomain();

$event_type = REQSTR($event_type, "");
$coupon_name = REQSTR($coupon_name, "");
$event_partnership_code = REQSTR($event_partnership_code, "");
$start_date = REQSTR($start_date, "");
$end_date = REQSTR($end_date, "");
$expire_date_s = REQSTR($expire_date_s, "");
$expire_date_e = REQSTR($expire_date_e, "");
$coupon_size = REQSTR($coupon_size, "");
$partner_event_yn = REQSTR($partner_event_yn, "N");
$partner_coupon_name = REQSTR($partner_coupon_name, "");
$partner_coupon_discount = REQSTR($partner_coupon_discount, "");
$event_url = $domain."/html/customer/event_list.php?mode=view&alliance_code=".encrypt($event_partnership_code)."&seq=".$seq;

//쿠폰 조건 추가
$duplicate_status_yn = REQSTR($duplicate_status_yn, "N");
$insurance_discount_applied = REQSTR($insurance_discount_applied, "");
$service_fee_discount_applied = REQSTR($service_fee_discount_applied, "");
$insurance_discount_rate = REQSTR($insurance_discount_rate, "0");
$insurance_max_discount_amount = REQSTR($insurance_max_discount_amount, "0");
$service_fee_discount_rate = REQSTR($service_fee_discount_rate, "0");
$service_fee_max_discount_amount = REQSTR($service_fee_max_discount_amount, "0");
$subscription_start_date = REQSTR($subscription_start_date, "1");
$subscription_end_date = REQSTR($subscription_end_date, "365");
$event_category_master_seq = REQSTR($event_category_master_seq, null);
$min_companion = REQSTR($min_companion, 0);
$max_companion = REQSTR($max_companion, 5);

//==================================================================================================
//엑셀업로드  시작
//==================================================================================================
error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_USER_DEPRECATED);
include $_SERVER["DOCUMENT_ROOT"]."/_util/PHPExcel-1.8/Classes/PHPExcel.php";
if ($_FILES["file1"] && $event_partnership_code != ""){
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
		for($i=2;$i<=$maxRow;$i++) {
			$couponCode = str_replace("\"","",$objWorksheet->getCell('B' . $i)->getValue());
			if(!$couponCode) {
				$dbcon -> dbcon_close();
				alert_page("유효성 검사에 실패 하였습니다. 엑셀 양식을 다시 확인해주세요");
				exit;
			}
			$SQL_PC = "SELECT COUNT(*) FROM tbl_partner_coupon where coupon_cd = '".$couponCode."' ";
			$RS = $dbcon -> query($SQL_PC);
			$row= $dbcon -> fetch_array($RS);
			if($row[0] == 0) {
				$SQL_N_IN = "insert into tbl_partner_coupon set";
				$SQL_N_IN .= " coupon_cd = '".$couponCode."' ";
				$SQL_N_IN .= " ,event_seq = '".$seq."' ";
				$SQL_N_IN .= " ,partner_name = '".$event_partnership_code."' ";
				$SQL_N_IN .= " ,regdate = CURRENT_TIMESTAMP ";
				$RS_N_IN = $dbcon -> query($SQL_N_IN);
			} else {
				$dbcon -> dbcon_close();
				alert_page("이미 등록된 쿠폰코드 입니다. ".$couponCode);
				exit;
			}
			
		}
		//echo $SQL_N_IN."<br>";
	}
} else {
	$dbcon -> dbcon_close();
	alert_close("제휴사를 선택해주세요.");
	exit;
}
try {
$sql  = " UPDATE tbl_board_".$bc_id." SET ";
$sql .= " event_type='".$event_type."' ";
$sql .= " , event_partnership_code='".$event_partnership_code."' ";
$sql .= " , start_date='".$start_date."' ";
$sql .= " , end_date='".$end_date."' ";
if($event_type == 'C'){
	$sql .= " , coupon_name='".$coupon_name."' ";
	$sql .= " , expire_date_s='".$expire_date_s."' ";
	$sql .= " , expire_date_e='".$expire_date_e."' ";
	$sql .= " , coupon_size='".$coupon_size."' ";
	// 새로운 변수 추가
	$sql .= " , duplicate_status_yn='".$duplicate_status_yn."' ";
	$sql .= " , insurance_discount_applied='".$insurance_discount_applied."' ";
	$sql .= " , service_fee_discount_applied='".$service_fee_discount_applied."' ";
	$sql .= " , insurance_discount_rate='".$insurance_discount_rate."' ";
	$sql .= " , insurance_max_discount_amount='".$insurance_max_discount_amount."' ";
	$sql .= " , service_fee_discount_rate='".$service_fee_discount_rate."' ";
	$sql .= " , service_fee_max_discount_amount='".$service_fee_max_discount_amount."' ";
	$sql .= " , subscription_start_date='".$subscription_start_date."' ";
	$sql .= " , subscription_end_date='".$subscription_end_date."' ";
	if($event_category_master_seq) {
		$sql .= " , event_category_master_seq='".$event_category_master_seq."' ";
	}
	$sql .= " , min_companion=".$min_companion." ";
	$sql .= " , max_companion=".$max_companion." ";

	if($partner_coupon_discount > 0) {
		$sql .= " , partner_coupon_discount='" . $partner_coupon_discount . "' ";
	}
	$sql .= " , partner_coupon_name='".$partner_coupon_name."' ";
}
$sql .= " , partner_event_yn='".$partner_event_yn."' ";
$sql .= " , partner_coupon_yn='".$partner_coupon_yn."' ";
$sql .= " , event_url='".$event_url."' ";
$sql .= " where seq= ".$seq."" ;
$result = $dbcon -> query($sql);
} catch (Exception $e) {
	alert_back($e->getMessage());
	$dbcon -> dbcon_close();
	exit;
}
if (!$result) {
	$dbcon -> dbcon_close();
	//echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
	//echo "에러";
	alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
	exit;
}
?>
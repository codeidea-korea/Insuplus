<?

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '1');
header("Content-Type: text/html; charset=UTF-8");
// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');
if (!isset($set_time_limit)) $set_time_limit = 0;
@set_time_limit($set_time_limit);
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/KISA_SEED_CBC.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.Array.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.session.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.DB.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.global.php";


if($_GET["mode"] == "excel" && getLen($ss_u_idx) > 0) {
	$dbcon = new dbcon;
	//	$sms_dbcon = new sms_dbcon;
	$dbcon -> dbcon_open(0);
	@mysqli_query("set names utf8");
	$SC_Rows = getSiteConfig();
	extract($SC_Rows);
	unset($SC_Rows);
	
	// DB별개 연결
	$dbconn = mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_database_name) or die("데이터베이스 연결에 실패하였습니다.");
	$status = mysqli_select_db($dbconn,$mysql_database_name);
	if (!$status) {
		error("DB_CONNECT_ERROR");
		exit;
	}
	
	include_once $_SERVER["DOCUMENT_ROOT"]."/_util/PHPExcel/Classes/PHPExcel.php";
	$objPHPExcel = new PHPExcel();
	
	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
	->setLastModifiedBy("Maarten Balliauw")
	->setTitle("Office 2007 XLSX Test Document")
	->setSubject("Office 2007 XLSX Test Document")
	->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
	->setKeywords("office 2007 openxml php")
	->setCategory("Test result file");
	
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	
	$bc_id = "gop";
	
	#### 게시판 설정 가져오기
	$field = " * ";
	$table = "config_board_list";
	$where = " and bc_id = '".$bc_id."' ";
	$orderby = " bc_id asc ";
	$limit = " 0, 1 ";
	
	$ArrListRs = $dbcon -> getList($field = "*", $table , $where, $orderby, $limit);
	if ( $ArrListRs[0] == 0 ) {
		$dbcon -> dbcon_close();
		alert_back("잘못된 게시판 정보입니다.1");
		exit;
	}
	$ListRs = $dbcon -> fetch_array($ArrListRs[1]);
	extract($ListRs);
	unset($ListRs);
	unset($ArrListRs);
	
	include_once $path_skin_board.$bc_skin."/config.php";
	
	$field = " a.seq, a.apply_date, a.occur_date,a.network , a.gop_status, a.regdate ";
	$field .= " , j.o_name  ";
	$field .= " , o.s_date, o.e_date, o.pr_name, o.ins_name, o.plan_name, o.chk_service ";
	$field .= " , p.stock_isdn, p.plan_cd  ";
	$table  = " tbl_board_".$bc_id." a LEFT JOIN tbl_order_list_join j on a.join_seq = j.seq";
	$table .= " LEFT JOIN tbl_order_list o ON j.orderno = o.orderno";
	$table .= " LEFT JOIN tbl_board_plan p ON o.plan_cd  = p.seq";
	$where  = " and a.notice <> 'Y' ";
	$where .= $query_where;
	$orderby = " a.seq_sub desc, a.seq desc ";
	$limit = "";
	
	$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);
	
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A1', 'GOP 고유번호')
	->setCellValue('B1', '증권번호')
	->setCellValue('C1', '상품명')
	->setCellValue('D1', '보험사')
	->setCellValue('E1', '플랜명')
	->setCellValue('F1', '서비스')
	->setCellValue('G1', '해외네트워크')
	->setCellValue('H1', '가입자')
	->setCellValue('I1', '보험기간')
	->setCellValue('J1', '청구일')
	->setCellValue('K1', '발생일')
	->setCellValue('L1', 'GOP상태')
	->setCellValue('M1', '등록일');
	
	//셀 항목 스타일
	$objPHPExcel->getActiveSheet()->getStyle('A1:O1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB("F00000");;
	
	$p =1;
	if($ArrListRs[0] > 0) {
		while($ListRs = $dbcon -> fetch_array($ArrListRs[1])) {
				$ListRs["o_name"] = all_seed_dec($ListRs["o_name"]);
				$ListRs["gop_status"] = $gop_status_arr[$ListRs["gop_status"]];
				extract($ListRs);
				unset($ListRs);
				$p++;
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueExplicit("A{$p}", $seq, PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("B{$p}", $stock_isdn, PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("C{$p}", $pr_name, PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("D{$p}", $ins_name, PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("E{$p}", $plan_name, PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("F{$p}", $chk_service, PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("G{$p}", $network, PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("H{$p}", $o_name, PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("I{$p}", $s_date."~".$e_date, PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("J{$p}", $apply_date, PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("K{$p}", $occur_date, PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("L{$p}", $gop_status, PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("M{$p}", $regdate, PHPExcel_Cell_DataType::TYPE_STRING);
		}
		
		unset($ListRs);
		unset($ArrListRs);
	}
	
	//$objPHPExcel->getActiveSheet()->setTitle('Simple');
	$objPHPExcel->setActiveSheetIndex(0);
	$file = "insuplus_GOP관리_".date("Ymd").".xlsx";
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename='.$file.'');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	
	$dbcon -> dbcon_close();
}
exit;
?>
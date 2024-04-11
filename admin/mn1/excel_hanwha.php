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
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.plan.php";


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
	
	$bc_id = "charge";
	// 검색설정
	$query_where		= "";

	#### 검색 설정 Start
	$pr_name				= REQSTR($pr_name, "");
	$ins_name				= REQSTR($ins_name, "");
	$plan_name				= REQSTR($plan_name, "");
	$chk_service				= REQSTR($chk_service, "");

	$search							= REQSTR($search, "");
	$search_text					= REQSTR($search_text, "");

	$search_orderby				= REQSTR($search_orderby, "");
	$search_sort					= REQSTR($search_sort, "");

	$search_date_txt					= REQSTR($search_date_txt, "");
	$search_date_s				= REQSTR($search_date_s, "");
	$search_date_e				= REQSTR($search_date_e, "");


	if ( strlen($pr_name) > 0 ) $query_where .= " and A.pr_name = '".$pr_name."' ";
	if ( strlen($ins_name) > 0 ) $query_where .= " and A.ins_name = '".$ins_name."' ";
	if ( strlen($plan_name) > 0 ) $query_where .= " and A.plan_name = '".$plan_name."' ";
	if ( strlen($chk_service) > 0 ) $query_where .= " and A.chk_service = '".$chk_service."' ";
	if ( strlen($join_status) > 0 ) $query_where .= " and B.join_status = '".$join_status."' ";

	if ( strlen($search_text) > 0 ) {
		$query_where .= " and A.orderno in (select orderno from tbl_order_list_join where ".$search." ='".all_seed_enc($search_text)."' )  ";
	}

	if ( strlen($search_date_s) > 0 ) $query_where .= " and ".$search_date_txt." >= '".$search_date_s." 00:00:00' ";
	if ( strlen($search_date_e) > 0 ) $query_where .= " and ".$search_date_txt." <= '".$search_date_e." 23:59:59' ";

	
	$field = " a.seq, a.writedate, a.join_cnt, a.join_ch, a.ins_name";
	$field .= " ,a.pr_name, a.plan_name, a.o_name, b.o_isdn1";
	$field .= ",b.o_isdn2, b.cancle_amount, a.s_date";
	$field .= ",a.s_date_time, a.e_date, a.e_date_time";
	$field .= ",a.ins_period, a.chk_p, a.join_nation_name";
	$field .= ",a.o_email1, a.o_email2, b.orderno, b.join_status";
	$field .= ",a.order_step, a.cancle_amount, b.cancle_date, a.ins_amount";
	$field .= ",a.s_amount, a.cancle_amount, a.t_amount, b.join_status";
	$field .= ",a.service_amount";

	$table  = " tbl_order_list a INNER JOIN tbl_order_list_join b ON (a.orderno=b.orderno)";

	$where .= " AND a.ins_name='한화손해보험' ".$query_where;
	$orderby = " a.seq  DESC  ";
	$limit = "";
	
	$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);
	
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A1', '선택')
	->setCellValue('B1', '구분')
	->setCellValue('C1', '성별')
	->setCellValue('D1', '생년월일')
	->setCellValue('E1', '연령')
	->setCellValue('F1', '상해급수')
	->setCellValue('G1', '교통급수')
	->setCellValue('H1', '인원수')
	->setCellValue('I1', '보험시기')
	->setCellValue('J1', '보험종기')
	->setCellValue('K1', '보험료')
	->setCellValue('L1', '서비스료');

	//셀 항목 스타일
	$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB("F00000");;
	
	$p =1;
	if($ArrListRs[0] > 0) {
		while($ListRs = $dbcon -> fetch_array($ArrListRs[1])) {
				$ListRs["o_name"] = all_seed_dec($ListRs["o_name"]);
				$ListRs["o_isdn1"] = all_seed_dec($ListRs["o_isdn1"]);
				$ListRs["o_isdn2"] = all_seed_dec($ListRs["o_isdn2"]);
				$ListRs["birth_date"] = substr($ListRs["o_isdn1"],2,6);
				$gender = substr($o_isdn2,0,1)%2;
				$gender_name = $gender == "M" ? "01":"02";
				$ListRs["join_status"] = $ListRs["join_status"] == "Y" ? "1" : "2" ;
				
				$ListRs["charge_type"] = $charge_type_arr[$ListRs["charge_type"]];
				$ListRs["charge_status"] = $charge_status_arr[$ListRs["charge_status"]];
				extract($ListRs);
				unset($ListRs);
				$p++;
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueExplicit("A{$p}", " ", 						PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("B{$p}", $join_status, 				PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("C{$p}", $gender_name,	 			PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("D{$p}", $o_isdn1, 					PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("E{$p}", fn_ins_age($o_isdn1), 		PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("F{$p}", "1", 						PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("G{$p}", "1", 						PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("H{$p}", $join_cnt, 					PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("I{$p}", str_replace("-","", $s_date),PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("J{$p}", str_replace("-","", $e_date),PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("K{$p}", $ins_amount, 				PHPExcel_Cell_DataType::TYPE_NUMERIC)
				->setCellValueExplicit("L{$p}", $service_amount,		PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("K{$p}")->getNumberFormat()->setFormatCode('#,##0');
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("L{$p}")->getNumberFormat()->setFormatCode('#,##0');
		}
		
		unset($ListRs);
		unset($ArrListRs);
	}
	
	//$objPHPExcel->getActiveSheet()->setTitle('Simple');
	$objPHPExcel->setActiveSheetIndex(0);
	$file = "insuplus_한화손해보험_".date("Ymd").".xlsx";
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
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
	$pr_name				= REQSTR($_GET["pr_name"], "");
	$ins_name				= REQSTR($_GET["ins_name"], "");
	$plan_name				= REQSTR($_GET["plan_name"], "");
	$chk_service			= REQSTR($_GET["chk_service"], "");
	$order_step				= REQSTR($_GET["order_step"], "");

	$search					= REQSTR($_GET["search"], "");
	$search_text			= REQSTR($_GET["search_text"], "");

	$search_orderby			= REQSTR($_GET["search_orderby"], "");
	$search_sort			= REQSTR($_GET["search_sort"], "");

	$search_date_txt		= REQSTR($_GET["search_date_txt"], "");
	$search_date_s			= REQSTR($_GET["search_date_s"], "");
	$search_date_e			= REQSTR($_GET["search_date_e"], "");


	if ( strlen($pr_name) > 0 ) $query_where .= " and pr_name = '".$pr_name."' ";
	if ( strlen($ins_name) > 0 ) $query_where .= " and ins_name = '".$ins_name."' ";
	if ( strlen($plan_name) > 0 ) $query_where .= " and plan_name = '".$plan_name."' ";
	if ( strlen($chk_service) > 0 ) $query_where .= " and chk_service = '".$chk_service."' ";
	if ( strlen($order_step) > 0 ) $query_where .= " and order_step = '".$order_step."' ";

	if ( strlen($search_text) > 0 ) {
		$query_where .= " and orderno in (select orderno from tbl_order_list_join where ".$search." ='".all_seed_enc($search_text)."' )  ";
	}

	if ( strlen($search_date_s) > 0 ) $query_where .= " and ".$search_date_txt." >= '".$search_date_s." 00:00:00' ";
	if ( strlen($search_date_e) > 0 ) $query_where .= " and ".$search_date_txt." <= '".$search_date_e." 23:59:59' ";

	// 상품 코드 불러오기
	$SQL_PR = "select seq,subject from tbl_board_product  ";
	$RS_PR = $dbcon -> query($SQL_PR);
	// 보험사 불러오기
	$SQL_Ins = "select seq,subject from tbl_board_ins_list  ";
	$RS_Ins = $dbcon -> query($SQL_Ins);

	
	$field = " a.*, tbe.coupon_name AS tbe_coupon_name, tbrc.recommendation_code AS tbrc_recom_name, tbp.partnership_name AS join_ch_name ";

	$table  = " tbl_order_list a
	LEFT OUTER JOIN tbl_board_coupon_history tbch ON (a.cp_cd=tbch.seq)
	LEFT OUTER JOIN tbl_board_event tbe ON (tbch.event_seq=tbe.seq)
	LEFT OUTER JOIN tbl_board_recommend_code tbrc ON (a.recommend_cd=tbrc.seq)
	LEFT OUTER JOIN tbl_board_partner tbp ON (a.join_ch= tbp.seq) ";

	$where .= " AND a.join_ch='".$_SESSION["ss_partner_seq_admin"]."' ".$query_where;
	$orderby = " seq  DESC  ";
	$limit = "";
	
	$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);
	
	$objPHPExcel->setActiveSheetIndex(0)
	->setCellValue('A1', '결제일')
	->setCellValue('B1', '채널')
	->setCellValue('C1', '보험사')
	->setCellValue('D1', '상품명')
	->setCellValue('E1', '플랜명')
	->setCellValue('F1', '이름')
	->setCellValue('G1', '인원수')
	->setCellValue('H1', '보험기간')
	->setCellValue('I1', '이메일')
	->setCellValue('J1', '주문번호')
	->setCellValue('K1', 'TID')
	->setCellValue('L1', '상품가')
	->setCellValue('M1', '할인금액')
	->setCellValue('N1', '결제금액')
	->setCellValue('O1', '보험료')
	->setCellValue('P1', '서비스료')
	->setCellValue('Q1', '부가세')
	->setCellValue('R1', '결제상태')
	->setCellValue('S1', '취소일')
	->setCellValue('T1', '추천인코드')
	->setCellValue('U1', '쿠폰사용');
	
	//셀 항목 스타일
	$objPHPExcel->getActiveSheet()->getStyle('A1:U1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB("F00000");;
	
	$p =1;
	if($ArrListRs[0] > 0) {
		while($ListRs = $dbcon -> fetch_array($ArrListRs[1])) {
				$ListRs["o_name"] = all_seed_dec($ListRs["o_name"]);
				$ListRs["o_isdn1"] = all_seed_dec($ListRs["o_isdn1"]);
				$ListRs["o_isdn2"] = all_seed_dec($ListRs["o_isdn2"]);
				$ListRs["birth_date"] = substr($ListRs["o_isdn1"],2,6);
				//$gender = substr($o_isdn2,0,1)%2;
				$ListRs["o_email1"] = all_seed_dec($ListRs["o_email1"]);
				$ListRs["o_email2"] = all_seed_dec($ListRs["o_email2"]);
				
				$ListRs["charge_type"] = $charge_type_arr[$ListRs["charge_type"]];
				$ListRs["charge_status"] = $charge_status_arr[$ListRs["charge_status"]];
				$ListRs["join_status"] = $arr_join_step[$ListRs["join_status"]];
				extract($ListRs);
				unset($ListRs);
				$p++;
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValueExplicit("A{$p}", $writedate, 														PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("B{$p}", $join_ch_name, 														PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("C{$p}", $ins_name,															PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("D{$p}", $pr_name, 															PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("E{$p}", $plan_name, 														PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("F{$p}", $o_name, 															PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("G{$p}", $join_cnt,															PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("H{$p}", $s_date." ".$s_date_time.":00 ~ ".$e_date." ".$e_date_time.":00",	PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("I{$p}", $o_email1."@".$o_email2,											PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("J{$p}", $orderno,															PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("K{$p}", $pg_isdn, 															PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("L{$p}", $ins_amount+$service_amount, 										PHPExcel_Cell_DataType::TYPE_NUMERIC)
				->setCellValueExplicit("M{$p}", $s_amount, 															PHPExcel_Cell_DataType::TYPE_NUMERIC)
				->setCellValueExplicit("N{$p}", $t_amount, 															PHPExcel_Cell_DataType::TYPE_NUMERIC)
				->setCellValueExplicit("O{$p}", $ins_amount, 														PHPExcel_Cell_DataType::TYPE_NUMERIC)
				->setCellValueExplicit("P{$p}", $service_amount, 													PHPExcel_Cell_DataType::TYPE_NUMERIC)
				->setCellValueExplicit("Q{$p}", $vat_amount, 														PHPExcel_Cell_DataType::TYPE_NUMERIC)
				->setCellValueExplicit("R{$p}", $arr_ord_step[$order_step], 										PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("S{$p}", $cancle_date, 														PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("T{$p}", $tbrc_recom_name, 													PHPExcel_Cell_DataType::TYPE_STRING)
				->setCellValueExplicit("U{$p}", $tbe_coupon_name, 													PHPExcel_Cell_DataType::TYPE_STRING);
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("L{$p}")->getNumberFormat()->setFormatCode('#,##0');
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("M{$p}")->getNumberFormat()->setFormatCode('#,##0');
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("N{$p}")->getNumberFormat()->setFormatCode('#,##0');
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("O{$p}")->getNumberFormat()->setFormatCode('#,##0');
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("P{$p}")->getNumberFormat()->setFormatCode('#,##0');
				$objPHPExcel->setActiveSheetIndex(0)->getStyle("Q{$p}")->getNumberFormat()->setFormatCode('#,##0');
		}
		
		unset($ListRs);
		unset($ArrListRs);
	}
	
	//$objPHPExcel->getActiveSheet()->setTitle('Simple');
	$objPHPExcel->setActiveSheetIndex(0);
	$file = "insuplus_결제내역_".date("Ymd").".xlsx";
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
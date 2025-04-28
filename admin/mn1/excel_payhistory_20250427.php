<?

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '1');
header("Content-Type: text/html; charset=UTF-8");
// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');
if (!isset($set_time_limit)) $set_time_limit = 0;
@set_time_limit($set_time_limit);
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/config.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/KISA_SEED_CBC.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/config.Array.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/class.session.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/class.DB.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/Func.global.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/Func.plan.php";


if ($_GET["mode"] == "excel" && getLen($ss_u_idx) > 0) {
  $dbcon = new dbcon;
  //  $sms_dbcon = new sms_dbcon;
  $dbcon->dbcon_open(0);
  @mysqli_query("set names utf8");
  $SC_Rows = getSiteConfig();
  extract($SC_Rows);
  unset($SC_Rows);

  // DB별개 연결
  $dbconn = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database_name) or die("데이터베이스 연결에 실패하였습니다.");
  $status = mysqli_select_db($dbconn, $mysql_database_name);
  if (!$status) {
    error("DB_CONNECT_ERROR");
    exit;
  }

  include_once $_SERVER["DOCUMENT_ROOT"] . "/_util/PHPExcel/Classes/PHPExcel.php";
  $objPHPExcel = new PHPExcel();

  // Set document properties
  $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
    ->setLastModifiedBy("Maarten Balliauw")
    ->setTitle("Office 2007 XLSX Test Document")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Test result file");

  admin_chk($auth_admin, $url_admin_login_out); // 관리자 체크

  $bc_id = "charge";
  // 검색설정
  $query_where    = "";

  #### 검색 설정 Start
  $pr_name        = REQSTR($_GET["pr_name"], "");
  $ins_name        = REQSTR($_GET["ins_name"], "");
  $plan_name        = REQSTR($_GET["plan_name"], "");
  $chk_service      = REQSTR($_GET["chk_service"], "");
  $order_step        = REQSTR($_GET["order_step"], "");
  $join_ch        = REQSTR($_GET["join_ch"], "");
  $group_join_type    = REQSTR($_GET["group_join_type"], "");
  $client_id        = REQSTR($_GET["client_id"], "");

  $search          = REQSTR($_GET["search"], "");
  $search_text      = REQSTR($_GET["search_text"], "");

  $search_orderby      = REQSTR($_GET["search_orderby"], "");
  $search_sort      = REQSTR($_GET["search_sort"], "");

  $search_date_txt    = REQSTR($_GET["search_date_txt"], "");
  $search_date_s      = REQSTR($_GET["search_date_s"], "");
  $search_date_e      = REQSTR($_GET["search_date_e"], "");

  // 2023-11-08 added
  $category_cd      = REQSTR($_GET["category_cd"]  , "");
  $ins_plan_name    = REQSTR($_GET["ins_plan_name"], "");

  if (strlen($pr_name) > 0) $query_where .= " and pr_name = '" . $pr_name . "' ";
  if (strlen($ins_name) > 0) $query_where .= " and ins_name = '" . $ins_name . "' ";
  if (strlen($plan_name) > 0) $query_where .= " and plan_name = '" . $plan_name . "' ";
  if (strlen($chk_service) > 0) $query_where .= " and chk_service = '" . $chk_service . "' ";
  if (strlen($order_step) > 0) $query_where .= " and order_step = '" . $order_step . "' ";
  if (strlen($join_ch) > 0) $query_where .= "and tbp.partnership_name like '%" . $join_ch . "%'";
  if (is_null($group_join_type) || $group_join_type === "B2C") {
    $query_where .= "and (tolj.group_join_type is null or tolj.group_join_type = 'B2C')";
  } else if ($group_join_type === "B2B") {
    $query_where .= "and tolj.group_join_type = '" . $group_join_type . "'";
  }
  if (strlen($client_id) > 0) $query_where .= "and 1 = (select COUNT(*) from tbl_order_group_join_list where group_join_id = A.group_join_id and client_id = " . $client_id . ")";

  // 2023-11-08 added
  if (strlen($category_cd) > 0) $query_where .= " and A.pr_cd in (select product_seq from tbl_board_product_category where category_code = '" . $category_cd . "') ";
  if (strlen($ins_plan_name) > 0) $query_where .= " and A.plan_cd in (select seq from tbl_board_plan where ins_plan_name like '%" . $ins_plan_name . "%') ";

  if (strlen($search_text) > 0) {
    if ($search == "orderno") {
      $query_where .= " and A.orderno in (select orderno from tbl_order_list_join where " . $search . " ='" . $search_text . "' )  ";
    } else {
      $query_where .= " and A.orderno in (select orderno from tbl_order_list_join where " . $search . " ='" . all_seed_enc($search_text) . "' )  ";
    }
  }

  if (strlen($search_date_s) > 0) $query_where .= " and " . $search_date_txt . " >= '" . $search_date_s . " 00:00:00' ";
  if (strlen($search_date_e) > 0) $query_where .= " and " . $search_date_txt . " <= '" . $search_date_e . " 23:59:59' ";

  // 상품 코드 불러오기
  $SQL_PR = "select seq,subject from tbl_board_product  ";
  $RS_PR = $dbcon->query($SQL_PR);
  // 보험사 불러오기
  $SQL_Ins = "select seq,subject from tbl_board_ins_list  ";
  $RS_Ins = $dbcon->query($SQL_Ins);


  $field = " A.*, tbe.coupon_name AS tbe_coupon_name, tbrc.recommendation_code AS tbrc_recom_name, tbp.partnership_name AS join_ch_name, tolj.group_join_type AS tolj_group_join_type, ";
  $field .= "togjl.o_name AS togjl_client_name ";

  $table  = " tbl_order_list A
  LEFT OUTER JOIN tbl_board_coupon_history tbch ON (A.cp_cd=tbch.seq or A.new_cp_cd=tbch.seq)
  LEFT OUTER JOIN tbl_board_event tbe ON (tbch.event_seq=tbe.seq)
  LEFT OUTER JOIN tbl_board_recommend_code tbrc ON (A.recommend_cd=tbrc.seq)
  LEFT OUTER JOIN tbl_board_partner tbp ON (A.join_ch= tbp.seq) 
  LEFT OUTER JOIN tbl_order_group_join_list togjl ON (A.group_join_id=togjl.group_join_id)
  LEFT JOIN tbl_order_list_join tolj ON (A.orderno=tolj.orderno and A.o_name = tolj.o_name)";

  $where .= $query_where;
  $orderby = " seq  DESC  ";
  $limit = "";

  $ArrListRs = $dbcon->getList($field, $table, $where, $orderby, $limit);

  $objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A1', '결제일')
    ->setCellValue('B1', '채널')
    ->setCellValue('C1', '보험사')
    ->setCellValue('D1', '구분1')
    ->setCellValue('E1', '상품명')
    ->setCellValue('F1', '플랜명')
    ->setCellValue('G1', '서비스')
    // ->setCellValue('G1', '원격진료코드')
    ->setCellValue('H1', '이름')
    ->setCellValue('I1', '인원수')
    ->setCellValue('J1', '보험시작일')
    ->setCellValue('K1', '보험종료일')
    ->setCellValue('L1', '보험기간')
    ->setCellValue('M1', '이메일')
    ->setCellValue('N1', '주문번호')
    ->setCellValue('O1', 'TID')
    ->setCellValue('P1', '상품가')
    ->setCellValue('Q1', '할인금액')
    ->setCellValue('R1', '결제금액')
    ->setCellValue('S1', '보험료')
    ->setCellValue('T1', '서비스료')
    ->setCellValue('U1', '부가세')
    ->setCellValue('V1', '결제상태')
    ->setCellValue('W1', '결제취소일')
    ->setCellValue('X1', '추천인코드')
    ->setCellValue('Y1', '쿠폰사용')
    ->setCellValue('Z1', '결제수단')
    ->setCellValue('AA1', '환불일')
    ->setCellValue('AB1', '환불금액')
    ->setCellValue('AC1', '환불보험료')
    ->setCellValue('AD1', '환불서비스료')
    ->setCellValue('AE1', '취소자수')
    ->setCellValue('AF1', 'B2B/B2C')
    ->setCellValue('AG1', '업체명');

  //셀 항목 스타일
  $objPHPExcel->getActiveSheet()->getStyle('A1:AG1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB("F00000");;

  $p = 1;
  if ($ArrListRs[0] > 0) {
    while ($ListRs = $dbcon->fetch_array($ArrListRs[1])) {
      $ListRs["o_name"] = all_seed_dec($ListRs["o_name"]);
      $ListRs["o_isdn1"] = all_seed_dec($ListRs["o_isdn1"]);
      $ListRs["o_isdn2"] = all_seed_dec($ListRs["o_isdn2"]);
      $ListRs["birth_date"] = substr($ListRs["o_isdn1"], 2, 6);
      $gender = substr($o_isdn2, 0, 1) % 2;
      $ListRs["o_email1"] = all_seed_dec($ListRs["o_email1"]);
      $ListRs["o_email2"] = all_seed_dec($ListRs["o_email2"]);

      $ListRs["charge_type"] = $charge_type_arr[$ListRs["charge_type"]];
      $ListRs["charge_status"] = $charge_status_arr[$ListRs["charge_status"]];
      $ListRs["join_status"] = $arr_join_step[$ListRs["join_status"]];

      $refund_amount = 0;      //환불금액
      $refund_ins_amount = 0;    //환불보험료
      $refund_servie_amount = 0;  //환불서비스료

      //가입자 가져오기
      $SQL_J = "select * from tbl_order_list_join where orderno ='" . $ListRs["orderno"] . "' order by field(chk_join,'N','Y') ASC,  seq ASC ";
      $RS_J = $dbcon->query($SQL_J);

      $join_data = array();
      while ($row_join = $dbcon->fetch_array($RS_J)) {
        $join_data[] = $row_join;
      }

      /////////////////////////////////////////////////////////////////////////
      // 2023-11-22 added
      $SQL_C = "select a.category_code, a.category_name from tbl_board_category a inner join tbl_board_product_category b on a.category_code = b.category_code where b.product_seq = " . $ListRs["pr_cd"] . " and a.`depth` = 1 limit 1";
      $RS_C = $dbcon->query($SQL_C);
      $RS_ROW = $dbcon -> fetch_array($RS_C);
      $category_name = $RS_ROW["category_name"];
      /////////////////////////////////////////////////////////////////////////

      $join_refund_s_amount = 0;
      $join_refund_i_amount = 0;
      $cancle_cnt = 0;
      if(count($join_data) > 0) { //동반인이 있을 때
        $z = 0;
        foreach ($join_data as $row_L) {
          if($row_L["join_status"] == "N") {
            $join_refund_s_amount = $join_refund_s_amount + $row_L["join_service"];
            $join_refund_i_amount = $join_refund_i_amount + $row_L["join_amount"];
            $cancle_cnt++;
          }
          $z++;
        }
      }

      if ($ListRs["order_step"] == "N" || $ListRs["order_step"] == "R") {  //결제취소 여부 확인을 결제 상태 값으로 변경함
        $refund_ins_amount = $ListRs["ins_amount"] - $ListRs["refund_i_amount"];
        $refund_servie_amount = $ListRs["service_amount"] - $ListRs["refund_s_amount"];
        $refund_amount = $refund_ins_amount + $refund_servie_amount - $ListRs["s_amount"];
      } else if ($ListRs["order_step"] == "P") {
        $refund_ins_amount = $join_refund_i_amount > 0 ? $join_refund_i_amount : $ListRs["ins_amount"] - $ListRs["refund_i_amount"];
        $refund_servie_amount = $join_refund_s_amount > 0 ? $join_refund_s_amount : $ListRs["service_amount"] - $ListRs["refund_s_amount"];
        $refund_amount = $ListRs["cancle_amount"];
      }

      $SQL_TEL = "select * from tbl_telemedicine_cd_list where join_orderno = '" . $ListRs["orderno"] . "'";
      $RS_TEL = $dbcon->query($SQL_TEL);
      $row_tel = $dbcon->fetch_array($RS_TEL);

      $group_join_type = "";
      $client_name = "";
      if (is_null($ListRs["tolj_group_join_type"]) || $ListRs["tolj_group_join_type"] === "B2C") {
        $group_join_type = "B2C";
        $client_name = "";
      } else {
        $group_join_type = "B2B";
        $client_name = all_seed_dec($ListRs["togjl_client_name"]);
      }

      extract($ListRs);
      unset($ListRs);
      $p++;

      //결제 정보의 보험기간 사용 안함. 시작일, 종료일로 일수 계산 20231122 이준호
      $period = getArrPeriod($s_date." 00",$e_date." 23","Y");
      $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValueExplicit("A{$p}", $writedate,                    PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("B{$p}", $join_ch_name,                 PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("C{$p}", $ins_name,                     PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("D{$p}", $category_name,                PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("E{$p}", $pr_name,                      PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("F{$p}", $plan_name,                    PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("G{$p}", $Arr_txt_plus[$chk_service],   PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("H{$p}", $o_name,                       PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("I{$p}", $join_cnt,                     PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("J{$p}", $s_date,                       PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("K{$p}", $e_date,                       PHPExcel_Cell_DataType::TYPE_STRING)
        // ->setCellValueExplicit("L{$p}", $ins_period,                   PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit("L{$p}", $period["day"],                   PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit("M{$p}", $o_email1 . "@" . $o_email2,   PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("N{$p}", $orderno,                      PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("O{$p}", $pg_isdn,                      PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("P{$p}", $ins_amount + $service_amount, PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit("Q{$p}", $s_amount,                     PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit("R{$p}", $t_amount,                     PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit("S{$p}", $ins_amount,                   PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit("T{$p}", $service_amount,               PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit("U{$p}", $vat_amount,                   PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit("V{$p}", $arr_ord_step[$order_step],    PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("W{$p}", $cancle_date,                  PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("X{$p}", $tbrc_recom_name,              PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("Y{$p}", $tbe_coupon_name,              PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("Z{$p}", $pg_pay_type,                  PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("AA{$p}", $refund_date,                 PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("AB{$p}", (int)$refund_amount,          PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit("AC{$p}", $refund_ins_amount,           PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit("AD{$p}", $refund_servie_amount,        PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit("AE{$p}", $cancle_cnt,                  PHPExcel_Cell_DataType::TYPE_NUMERIC)
        ->setCellValueExplicit("AF{$p}", $group_join_type,             PHPExcel_Cell_DataType::TYPE_STRING)
        ->setCellValueExplicit("AG{$p}", $client_name,                 PHPExcel_Cell_DataType::TYPE_STRING);
      $objPHPExcel->setActiveSheetIndex(0)->getStyle("N{$p}")->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->setActiveSheetIndex(0)->getStyle("O{$p}")->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->setActiveSheetIndex(0)->getStyle("P{$p}")->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->setActiveSheetIndex(0)->getStyle("Q{$p}")->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->setActiveSheetIndex(0)->getStyle("R{$p}")->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->setActiveSheetIndex(0)->getStyle("S{$p}")->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->setActiveSheetIndex(0)->getStyle("Z{$p}")->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->setActiveSheetIndex(0)->getStyle("AA{$p}")->getNumberFormat()->setFormatCode('#,##0');
      $objPHPExcel->setActiveSheetIndex(0)->getStyle("AB{$p}")->getNumberFormat()->setFormatCode('#,##0');
    }

    unset($ListRs);
    unset($ArrListRs);
  }

  //$objPHPExcel->getActiveSheet()->setTitle('Simple');
  $objPHPExcel->setActiveSheetIndex(0);
  $file = "insuplus_결제내역_" . date("Ymd") . ".xlsx";
  header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
  header('Content-Disposition: attachment;filename=' . $file . '');
  header('Cache-Control: max-age=0');
  // If you're serving to IE 9, then the following may be needed
  header('Cache-Control: max-age=1');
  // If you're serving to IE over SSL, then the following may be needed
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
  header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
  header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
  header('Pragma: public'); // HTTP/1.0
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  $objWriter->save('php://output');

  $dbcon->dbcon_close();
}
exit;
?>
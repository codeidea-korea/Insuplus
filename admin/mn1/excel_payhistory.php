<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', '1');
header("Content-Type: text/html; charset=UTF-8");
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
    $dbcon->dbcon_open(0);
    @mysqli_query("set names utf8");

    include_once $_SERVER["DOCUMENT_ROOT"] . "/_util/PHPExcel/Classes/PHPExcel.php";
    $objPHPExcel = new PHPExcel();

    $objPHPExcel->getProperties()->setCreator("System")
        ->setTitle("결제 내역")
        ->setDescription("Exported 결제내역");

    admin_chk($auth_admin, $url_admin_login_out);

    // =========================
    // 검색 조건 구성
    // ========================= 
    $query_where = "";

    $pr_name = REQSTR($_GET["pr_name"], "");
    $ins_name = REQSTR($_GET["ins_name"], "");
    $plan_name = REQSTR($_GET["plan_name"], "");
    $chk_service = REQSTR($_GET["chk_service"], "");
    $order_step = REQSTR($_GET["order_step"], "");
    $join_ch = REQSTR($_GET["join_ch"], "");
    $group_join_type = REQSTR($_GET["group_join_type"], "");
    $client_id = REQSTR($_GET["client_id"], "");
    $search = REQSTR($_GET["search"], "");
    $search_text = REQSTR($_GET["search_text"], "");
    $search_date_txt = REQSTR($_GET["search_date_txt"], "");
    $search_date_s = REQSTR($_GET["search_date_s"], "");
    $search_date_e = REQSTR($_GET["search_date_e"], "");
    $category_cd = REQSTR($_GET["category_cd"], "");
    $ins_plan_name = REQSTR($_GET["ins_plan_name"], "");

    if ($pr_name) $query_where .= " and A.pr_name = '{$pr_name}' ";
    if ($ins_name) $query_where .= " and A.ins_name = '{$ins_name}' ";
    if ($plan_name) $query_where .= " and A.plan_name = '{$plan_name}' ";
    if ($chk_service) $query_where .= " and A.chk_service = '{$chk_service}' ";
    if ($order_step) $query_where .= " and A.order_step = '{$order_step}' ";
    if ($join_ch) $query_where .= " and tbp.partnership_name like '%{$join_ch}%'";
    if (is_null($group_join_type) || $group_join_type === "B2C") {
        $query_where .= " and (tolj.group_join_type is null or tolj.group_join_type = 'B2C')";
    } else if ($group_join_type === "B2B") {
        $query_where .= " and tolj.group_join_type = 'B2B'";
    }
    if ($client_id) $query_where .= " and 1 = (select COUNT(*) from tbl_order_group_join_list where group_join_id = A.group_join_id and client_id = {$client_id})";
    if ($category_cd) $query_where .= " and A.pr_cd in (select product_seq from tbl_board_product_category where category_code = '{$category_cd}')";
    if ($ins_plan_name) $query_where .= " and A.plan_cd in (select seq from tbl_board_plan where ins_plan_name like '%{$ins_plan_name}%')";

    if ($search_text) {
        if ($search == "orderno") {
            $query_where .= " and A.orderno in (select orderno from tbl_order_list_join where {$search} = '{$search_text}')";
        } else {
            $query_where .= " and A.orderno in (select orderno from tbl_order_list_join where {$search} = '" . all_seed_enc($search_text) . "')";
        }
    }

    if ($search_date_s) $query_where .= " and {$search_date_txt} >= '{$search_date_s} 00:00:00'";
    if ($search_date_e) $query_where .= " and {$search_date_txt} <= '{$search_date_e} 23:59:59'";

    // =========================
    // 쿼리 구성 (UNION ALL 최적화)
    // =========================
    $sql = "
    (
        SELECT  
            A.seq, A.orderno, A.writedate, A.o_name, tolj.o_isdn1, tolj.o_isdn2, 
            A.o_email1, A.o_email2, A.pr_name, A.pr_cd, A.ins_name, A.plan_name, 
            A.plan_cd, A.chk_service, A.join_cnt, A.s_date, A.e_date, A.ins_period, 
            A.ins_amount, A.service_amount, A.s_amount, A.t_amount, A.vat_amount,
            A.order_step, A.cancle_date, A.refund_date, A.pg_isdn, A.pg_pay_type,
            A.group_join_id, A.join_ch, A.cancle_amount, A.refund_i_amount, A.refund_s_amount, A.recommend_cd, 
            tbe.coupon_name AS tbe_coupon_name, tbrc.recommendation_code AS tbrc_recom_name, 
            tbp.partnership_name AS join_ch_name, tolj.group_join_type AS tolj_group_join_type,
            togjl.o_name AS togjl_client_name
        FROM tbl_order_list A
        LEFT JOIN tbl_board_coupon_history tbch ON A.cp_cd = tbch.seq
        LEFT JOIN tbl_board_event tbe ON tbch.event_seq = tbe.seq
        LEFT JOIN tbl_board_recommend_code tbrc ON A.recommend_cd = tbrc.seq
        LEFT JOIN tbl_board_partner tbp ON A.join_ch = tbp.seq
        LEFT JOIN tbl_order_group_join_list togjl ON A.group_join_id = togjl.group_join_id
        LEFT JOIN tbl_order_list_join tolj ON A.orderno = tolj.orderno AND A.o_name = tolj.o_name
        WHERE 1=1 {$query_where}
    )
    UNION ALL
    (
              SELECT 
            A.seq, A.orderno, A.writedate, A.o_name, tolj.o_isdn1, tolj.o_isdn2, 
            A.o_email1, A.o_email2, A.pr_name, A.pr_cd, A.ins_name, A.plan_name, 
            A.plan_cd, A.chk_service, A.join_cnt, A.s_date, A.e_date, A.ins_period, 
            A.ins_amount, A.service_amount, A.s_amount, A.t_amount, A.vat_amount,
            A.order_step, A.cancle_date, A.refund_date, A.pg_isdn, A.pg_pay_type,
            A.group_join_id, A.join_ch, A.cancle_amount, A.refund_i_amount, A.refund_s_amount, A.recommend_cd, 
            tbe.coupon_name AS tbe_coupon_name, tbrc.recommendation_code AS tbrc_recom_name, 
            tbp.partnership_name AS join_ch_name, tolj.group_join_type AS tolj_group_join_type,
            togjl.o_name AS togjl_client_name
        FROM tbl_order_list A
       LEFT JOIN tbl_board_coupon_history tbch ON A.new_cp_cd = tbch.seq
        LEFT JOIN tbl_board_event tbe ON tbch.event_seq = tbe.seq
        LEFT JOIN tbl_board_recommend_code tbrc ON A.recommend_cd = tbrc.seq
        LEFT JOIN tbl_board_partner tbp ON A.join_ch = tbp.seq
        LEFT JOIN tbl_order_group_join_list togjl ON A.group_join_id = togjl.group_join_id
        LEFT JOIN tbl_order_list_join tolj ON A.orderno = tolj.orderno AND A.o_name = tolj.o_name
        WHERE 1=1 {$query_where}
    )
    ORDER BY seq DESC
    ";

    $list = $dbcon->query($sql);

    // =========================
    // 엑셀 헤더 세팅
    // =========================
    $columns = ['결제일', '채널', '보험사', '상품명', '플랜명', '이름', '이메일', '주문번호', 'TID', '결제금액', '결제상태', '환불일', '환불금액'];
    $col = 'A';
    foreach ($columns as $header) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($col.'1', $header);
        $col++;
    }

    // =========================
    // 엑셀 데이터 삽입
    // =========================
    $row_num = 2;
    foreach ($list as $row) { 
        $o_name = all_seed_dec($row['o_name']);
        $o_email = all_seed_dec($row['o_email1']) . '@' . all_seed_dec($row['o_email2']);

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValueExplicit("A{$row_num}", $row['writedate'], PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("B{$row_num}", $row['join_ch_name'], PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("C{$row_num}", $row['ins_name'], PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("D{$row_num}", $row['pr_name'], PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("E{$row_num}", $row['plan_name'], PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("F{$row_num}", $o_name, PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("G{$row_num}", $o_email, PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("H{$row_num}", $row['orderno'], PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("I{$row_num}", $row['pg_isdn'], PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("J{$row_num}", $row['t_amount'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
            ->setCellValueExplicit("K{$row_num}", $row['order_step'], PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("L{$row_num}", $row['refund_date'], PHPExcel_Cell_DataType::TYPE_STRING)
            ->setCellValueExplicit("M{$row_num}", $row['cancle_amount'], PHPExcel_Cell_DataType::TYPE_NUMERIC);

        $row_num++;
    }

    // =========================
    // 엑셀 출력
    // =========================
    $file = "결제내역_" . date('Ymd') . ".xlsx";

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment; filename=\"{$file}\"");
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');

    $dbcon->dbcon_close();
}
exit;
?>

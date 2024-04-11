<?
    include_once $_SERVER['DOCUMENT_ROOT']."/_config/lib.php";
    $partnership_code = REQSTR($partnership_code, "");
    $sql  = " SELECT count(partnership_code) FROM tbl_board_".$bc_id;
    $sql .= " where partnership_code= '".$partnership_code."'" ;
    $result = $dbcon -> getCount($sql) ;
    if (!$result) {
        $dbcon -> dbcon_close();
        //echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
        //echo "에러";
        alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
        exit;
    }
    echo $result;
?>
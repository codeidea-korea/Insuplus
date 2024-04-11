<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

//주문취소 - 결제취소
error_reporting(E_ALL);
ini_set("display_errors", 0);


// 가입가입내역 검색
$SQL_V = "select * from tbl_order_list where orderno in (select orderno from tbl_order_list_join where o_phone = '".$_SESSION["enc_hp"]."' and o_isdn2='".$_SESSION["enc_rnumber"]."' and orderno='".$orderno."' and chk_join='N')";
//echo $SQL_V;
$RS_V = $dbcon -> query($SQL_V);
if (!$RS_V){
	echo "<script>alert('해당 가입내역이 없습니다.');</script>";
	exit;
}
$row_r = $dbcon -> fetch_array($RS_V);

$mid = $row_r["pg_id"];
$tid = $row_r["pg_isdn"];
$msg = "고객주문 취소".date("His");
echo $row_r["pg_id"]."<br>";
echo $tid."<br>";

	/**************************
	 * 1. 라이브러리 인클루드 *
	 **************************/
//	require("../insurance/libs/INILib.php");
	require($_SERVER["DOCUMENT_ROOT"]."_util/inilite/libs/INILiteLib.php");

    /***************************************
     * 2. INIpay41 클래스의 인스턴스 생성 *
     ***************************************/
    $inipay = new INILite;

    /*********************
     * 3. 취소 정보 설정 *
     *********************/
    $inipay->m_inipayHome = $_SERVER["DOCUMENT_ROOT"]."_util/inilite"; //상점 수정 필요
//	echo $inipay->m_inipayHome."<br>";
    $inipay->m_key = SIGNKEY; //상점 수정 필요
    $inipay->m_ssl = "true";                //ssl지원하면 true로 셋팅해 주세요.
    $inipay->m_type = "cancel"; // 고정
    $inipay->m_log = "true";              // true로 설정하면 로그가 생성됨(적극권장)
    $inipay->m_debug = "true";  // 로그모드("true"로 설정하면 상세로그가 생성됨. 적극권장)
    $inipay->m_mid = $mid; // 상점아이디
    $inipay->m_tid = $tid; // 취소할 거래의 거래아이디
    $inipay->m_cancelMsg = iconv("UTF-8","EUC-KR",$msg); // 취소사유
//echo $inipay->m_cancelMsg."<br>";

    /****************
     * 4. 취소 요청 *
     ****************/
    $inipay->startAction();
    /****************************************************************
     * 5. 취소 결과                                             *
     *                                                          *
     * 결과코드 : $inipay->m_resultCode ("00"이면 취소 성공)      *
     * 결과내용 : $inipay->m_resultMsg (취소결과에 대한 설명)    *
     * 취소날짜 : $inipay->m_pgCancelDate (YYYYMMDD)            *
     * 취소시각 : $inipay->m_pgCancelTime (HHMMSS)              *
     * 현금영수증 취소 승인번호 : $inipay->m_rcash_cancel_noappl    *
     * (현금영수증 발급 취소시에만 리턴됨)                          *
     ****************************************************************/
	 echo $inipay->m_resultCode."결과<br>";
if ($inipay->m_resultCode == "00") {
	//주문취소
	$SQL = "update tbl_order_list set  order_step='N',cancle_date= now() where orderno='".$orderno."' ";
	$RS_C1 = $dbcon -> query($SQL);
	// 가입원취소
	$SQL = "update tbl_order_list_join set  join_status='N',cancle_date= now(),cancle_amount=t_amount where orderno='".$orderno."' ";
	$RS_C1 = $dbcon -> query($SQL);
        echo "결제 취소요청이 성공하였습니다.  <br>";
}else{
        echo "결제 취소요청이 실패하였습니다.  <br>";
        echo "TX Response_code = " . $inipay->m_resultCode . "<br>";
        echo "TX Response_msg = " . iconv("EUC-KR","UTF-8",$inipay->m_resultMsg) . "<p>";
}

?>
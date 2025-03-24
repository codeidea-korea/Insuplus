<?session_start();
include "../common/loginchk.html";
include $_SERVER["DOCUMENT_ROOT"]."/html/_common/dbconn.php";
include $_SERVER["DOCUMENT_ROOT"]."/html/_common/function.php";

//접속 권한, 네비게이션 위치 START
$menuMcategory = "09140000";
$SQ_mn_auth = " select admin_auth from menuCategory where catecode='".$menuMcategory."' ";
$result_mn_auth = mysql_query_exe($SQ_mn_auth,$dbconn);
$mn_auth = mysqli_fetch_row($result_mn_auth);
if ($mn_auth[0]){
	adminConAuth($mn_auth[0]);
}else{
	adminConAuth("A");
}
//접속 권한, 네비게이션 위치 END


/* cancel.php
 *
 * 이미 승인된 지불을 취소한다.
 * 은행계좌 이체 , 무통장입금은 이 모듈을 통해 취소 불가능.
 *  [은행계좌이체는 상점정산 조회페이지 (https://iniweb.inicis.com)를 통해 취소 환불 가능하며, 무통장입금은 취소 기능이 없습니다.]
 *
 * Date : 2006/04
 * Author : ts@inicis.com
 * Project : INIpay V4.11 for Unix
 *
 * http://www.inicis.com
 * Copyright (C) 2006 Inicis, Co. All rights reserved.
 */


    /**************************
     * 1. 라이브러리 인클루드 *
     **************************/
    require("./libs/INILiteLib.php");


    /***************************************
     * 2. INIpay41 클래스의 인스턴스 생성 *
     ***************************************/
    $inipay = new INILite;
 
    
    /*********************
     * 3. 취소 정보 설정 *
     *********************/
    //$inipay->m_inipayHome = "/home/endoshop/public_html/shop/_util/inilite"; //상점 수정 필요
    $inipay->m_inipayHome = $_SERVER["DOCUMENT_ROOT"]."/log/pg";
    $inipay->m_key = "SU5JTElURV9UUklQTEVERVNfS0VZU1RS"; //상점 수정 필요
    // $inipay->m_key = "bERqdzJqbTNoNEdua3hJK29vZko3UT09"; //상점 수정 필요
    $inipay->m_ssl = "true";                //ssl지원하면 true로 셋팅해 주세요.
    $inipay->m_type = "cancel"; // 고정
    $inipay->m_log = "true";              // true로 설정하면 로그가 생성됨(적극권장)
    $inipay->m_debug = "true";  // 로그모드("true"로 설정하면 상세로그가 생성됨. 적극권장)
    $inipay->m_mid = $mid; // 상점아이디
    $inipay->m_tid = $tid; // 취소할 거래의 거래아이디
    $inipay->m_cancelMsg = $msg; // 취소사유


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

    $orderno						= $HTTP_POST_VARS["orderno"];				//주문번호

    /*
     * 1. 결제취소 요청 결과처리
     *
     * 취소결과 리턴 파라미터는 연동메뉴얼을 참고하시기 바랍니다.
     */
    if ($inipay->m_resultCode == "00") {
        //1)결제취소결과 화면처리(성공,실패 결과 처리를 하시기 바랍니다.)

		// 성공시 주문 데이터에 결과 코드를 넣도록 한다
		$UPD ="UPDATE orderInfo SET ";
		$UPD.="pg_c_code='".$inipay->m_resultCode."' ";
		$UPD.=",pg_c_massage='".$inipay->m_resultMsg."' ";
		$UPD.=",pg_c_date=now() ";
		$UPD.="WHERE orderno='$orderno' ";
//		echo $UPD."<br>";
		$RESULT = mysql_query_exe($UPD,$dbconn);
		if(!$RESULT) {
			ERROR_BACK("ERROR orderInfo");
			exit;
		}

        echo "결제 취소요청이 완료되었습니다.  <br>";
        echo "TX Response_code = " . $inipay->m_resultCode . "<br>";
        echo "TX Response_msg = " . $inipay->m_resultMsg . "<p>";

		echo "<script>opener.document.location.reload();</script>";
    } else {
        //2)API 요청 실패 화면처리

		// 성공시 주문 데이터에 결과 코드를 넣도록 한다
		$UPD ="UPDATE orderInfo SET ";
		$UPD.="pg_c_code='".$inipay->m_resultCode."' ";
		$UPD.=",pg_c_massage='".$inipay->m_resultMsg."' ";
		$UPD.=",pg_c_date=now() ";
		$UPD.="WHERE orderno='$orderno' ";
//		echo $UPD."<br>";
		$RESULT = mysql_query_exe($UPD,$dbconn);
		if(!$RESULT) {
			ERROR_BACK("ERROR orderInfo");
			exit;
		}

        echo "결제 취소요청이 실패하였습니다.  <br>";
        echo "TX Response_code = " . $inipay->m_resultCode . "<br>";
        echo "TX Response_msg = " . $inipay->m_resultMsg . "<p>";
    }
?>
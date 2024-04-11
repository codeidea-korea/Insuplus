<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php";

$log = new log();

//주문취소 - 결제취소
error_reporting(E_ALL);
ini_set("display_errors", 0);


if($_POST["mode"] == "repay") {

	// 가입가입내역 검색
	$SQL_V  = "select * ";
	$SQL_V .= ", (SELECT o_phone FROM tbl_order_list_join WHERE chk_join='N' AND orderno = o.orderno) as o_phone ";
	$SQL_V .= " from tbl_order_list o where orderno in (select orderno from tbl_order_list_join where seq='".$seq."' and orderno='".$orderno."' )";
	$RS_V = $dbcon -> query($SQL_V);
	if (!$RS_V){
		echo "<script>alert('해당 가입내역이 없습니다.');</script>";
		exit;
	}
	$row_r = $dbcon -> fetch_array($RS_V);
	
	
	// 가입자 본인내역 검색
	$SQL = "select * from tbl_order_list_join WHERE seq = '".$seq."' AND join_status = 'Y' ";
	$RS = $dbcon -> query($SQL);
	$row = $dbcon -> fetch_array($RS);
	
	
	//주문인원수 확인
	$SQL_1 = "select count(*) as j_cnt from tbl_order_list_join where orderno ='".$orderno."' and join_status='Y' ";
	$log->log_write("1=".$SQL_1);
	$RS_1 = $dbcon -> query($SQL_1);
	$row_1 = $dbcon -> fetch_array($RS_1);
	$j_cnt = $row_1["j_cnt"]; //가입자 내역 갯수
	
	$mid			= $row_r["pg_id"];				// 상점아이디
	$tid			= $row_r["pg_isdn"];			// 주문거래번호
	$price			= $row_r["t_amount"];			// 결제금액
	$email = all_seed_dec($row_r["o_email1"])."@".all_seed_dec($row_r["o_email2"]); 
	
	$cancle_date = date("Y-m-d H:i:s"); //변수명 주의
	$cancle_amount = $row["t_amount"];		
	$cancle_vat_amount = $row["vat_amount"];
	
	if($j_cnt > 1) { //부분취소
		$chg_t_amount	= $row_r["t_amount"] - $cancle_amount;
		$cancle_price	= $cancle_amount; //상점 취소금액
		$tax = $cancle_vat_amount;
		
		$ord_step = "P";		// 부분취소
	} else {  //마지막 취소
		$chg_t_amount	= $row_r["t_amount"] - $row_r["t_amount"];
		$cancle_price = $row_r["t_amount"];
		$tax = $row_r["vat_amount"];
		
		$ord_step = "N";		// 전체취소
	}
	
	/* * INIlite_repay.php
	 *
	 * 이미 정상 승인된 거래에서 취소를 원하는 금액을 입력하여 다시 승인을 득하도록 요청한다.
	 *
	 * [주의] 취소 후 재승인이므로 새 거래아이디가 반환되나, 원거래 TID로 부분취소(재승인)이 가능함에 유의
	 * [주의] 원거래가 신용카드 지불인 경우에만 가능
	 * (OK 캐쉬백 적립 등이 포함되어 있어도 불가)
	 * [주의] 반드시 취소할 금액을 입력하도록 함
	 *
	 * Date : 2010/11
	 * Author : ts@inicis.com
	 * Project : INILITE_V2 INIAPI PHP
	 *
	 * http://www.inicis.com
	 * Copyright (C) 2008 Inicis, Co. All rights reserved.
	 */
	
	//echo $mid." 상정아이디<br>";
	//echo $tid." 주문거래번호<br>";
	//echo $price." 결제금액<br>";
	//echo $cancle_price." 취소할 금액<br>";
	//echo $chg_t_amount." 취소후남은금액<br>";
	//exit;
	
		/**************************
		 * 1. 라이브러리 인클루드 *
		 **************************/
		require("../insurance/libs/INILib.php");
	
		/***************************************
		 * 2. INIpay41 클래스의 인스턴스 생성 *
		 ***************************************/
		$inipay = new INIpay50;
	
		/***********************
		 * 3. 재승인 정보 설정 *
		 ***********************/
		$inipay->SetField("inipayhome", $_SERVER["DOCUMENT_ROOT"]."/_util/inilite");       // 이니페이 홈디렉터리(상점수정 필요)
		$inipay->SetField("type", "repay");      // 고정 (절대 수정 불가)
		$inipay->SetField("pgid", "INIphpRPAY");      // 고정 (절대 수정 불가)
		$inipay->SetField("subpgip","203.238.3.10"); 				// 고정
		$inipay->SetField("debug", false);        // 로그모드("true"로 설정하면 상세로그가 생성됨.)
		$inipay->SetField("mid", $mid);            // 상점아이디
		$inipay->SetField("admin", "1111");         //비대칭 사용키 키패스워드
		$inipay->SetField("oldtid", $tid);            // 취소할 거래의 거래아이디
		$inipay->SetField("currency", $currency);     // 화폐단위
		$inipay->SetField("price", $cancle_price);						//취소금액
		$inipay->SetField("confirm_price", $chg_t_amount);		// 승인요청금액
		$inipay->SetField("buyeremail",$email);				// 구매자 이메일 주소
		$inipay->SetField("tax",$tax);								// 부가세
		$inipay->SetField("taxfree",$taxfree);						// 비가세
	
		if(isset($no_acct) && !empty($no_acct) && isset($nm_acct) && !empty($nm_acct)) {
			$inipay->SetField("no_acct", $no_acct);		//국민은행 계좌이체 부분 취소 환불 계좌번호
			$inipay->SetField("nm_acct", $nm_acct);		//국민은행 계좌이체 부분 취소 환불 계좌주명
		}
	
		/****************
		 * 4. 취소 요청 *
		 ****************/
		$inipay->startAction();
	
		/*******************************************************************
		 * 5. 재승인 결과                                                  *
		 *                                                                 *
		 * 신거래번호 : $inipay->getResult('TID')                                     *
		 * 결과코드 : $inipay->getResult('ResultCode') ("00"이면 재승인 성공)         *
		 * 결과내용 : $inipay->getResult('ResultMsg') (재승인결과에 대한 설명)        *
		 * 원거래 번호 : $inipay->getResult('PRTC_TID')                                *
		 * 최종결제 금액 : $inipay->getResult('PRTC_Remains')                              *
		 * 부분취소 금액 : $inipay->getResult('PRTC_Price')                          *
		 * 부분취소,재승인 구분값 : $inipay->getResult('PRTC_Type')              *
		 *                          ("0" : 재승인, "1" : 부분취소)         *
		 * 부분취소(재승인) 요청횟수 : $inipay->getResult('PRTC_Cnt')           *
		 *******************************************************************/
	
		$SQL = "insert into tbl_order_pg_list set";
		$SQL .= " orderno = '".$orderno."' ";
		$SQL .= " ,gubun = '부분취소' ";
		$SQL .= " ,pg_isdn = '".$inipay->m_tid."' ";
		$SQL .= " ,pg_con = '".$inipay->getResult('ResultCode')."/".iconv("EUC-KR","UTF-8",$inipay->getResult('ResultMsg'))."/최종결제 금액 : ".$inipay->getResult('PRTC_Remains')." / 원거래번호: ".$inipay->getResult('PRTC_TID')." / 부분취소금액 : ".$inipay->getResult('PRTC_Price')." / 부분취소 구분값 : ".$inipay->getResult('PRTC_Type')." / 부분취소(재승인) 요청횟수 : ".$inipay->getResult('PRTC_Cnt')." / 신거래번호 : ".$inipay->getResult('TID')." ' ";
		$SQL .= " ,regdate = now() ";
	//	echo $SQL."<br>";
		$RS = $dbcon -> query($SQL);
	
	
	    if ($inipay->getResult('ResultCode') == "00") {
	    	
			// 가입원취소
			$SQL  = " UPDATE tbl_order_list_join SET  join_status='N', cancle_date= '".$cancle_date."' , cancle_amount=".$cancle_amount;
			$SQL .= " ,cancle_vat_amount = ".$cancle_vat_amount.", t_amount = t_amount - ".$cancle_amount." , vat_amount= vat_amount - ".$cancle_vat_amount;
			$SQL .= " WHERE seq='".$seq."' ";
			$RS_C1 = $dbcon -> query($SQL);
			
			if ($j_cnt>1){ 
				$SQL  = " update tbl_order_list set order_step='".$ord_step."' ,cancle_date= '".$cancle_date."', cancle_amount = cancle_amount+".$cancle_amount." ,cancle_vat_amount = cancle_vat_amount+".$cancle_vat_amount;
				$SQL .= " , t_amount= t_amount-".$cancle_amount." , vat_amount = vat_amount -".$cancle_vat_amount."  where orderno='".$orderno."' ";
			} else {
				$SQL  = " update tbl_order_list set order_step='".$ord_step."' ,cancle_date= '".$cancle_date."', cancle_amount = cancle_amount+t_amount ,cancle_vat_amount = cancle_vat_amount+vat_amount ";
				$SQL .= " , t_amount= t_amount-t_amount , vat_amount = vat_amount -vat_amount where orderno='".$orderno."' ";
			}
			$RS_C1 = $dbcon -> query($SQL);
			
			//쿠폰이 있는 경우 돌려준다.
			//추천코드는 무한입력이라 안 돌려줘도 된다.
			if($row_r["sale_gubun"] == "C" && $j_cnt < 2) {
				$SQL  = " UPDATE tbl_board_coupon_history SET ";
				$SQL .= " orderno = '', discount = 0, s_amount = 0, use_yn = 'N' ";
				$SQL .= " WHERE seq = '".$row_r["cp_cd"]."' ";
			
				$RS_C1 = $dbcon -> query($SQL);
			}
			
			if($j_cnt < 2) {// 마지막 취소 시 받은 쿠폰은 삭제한다.
				//가입 시 받은 쿠폰은 사용전 이면 삭제한다.
				$SQL = " DELETE FROM tbl_board_coupon_history WHERE receive_orderno = '".$orderno."' AND use_yn='N' ";
				$dbcon -> query($SQL);
			}
				
			//카카오 취소 알림톡
			$param = array();
			$param["name"] = all_seed_dec($row_r["o_name"]);
			// $param["pr_name"] = $row_r["pr_name"]." ".$row_r["ins_name"]." ".$row_r["plan_name"];
			$param["pr_name"] = $row_r["plan_name"];
			if($row_r["chk_service"] == "A" || $row_r["chk_service"]) {
				$param["pr_name"] .= $Arr_txt_plus[$row_r["chk_service"]];
			}
			if($row_r["chk_p"] == "Y") { //단기
				$s_date = $row_r["s_date"]." ".$row_r["s_date_time"]."시";
				$e_date = $row_r["e_date"]." ".$row_r["e_date_time"]."시";
			} else if($row_r["chk_p"] == "N") { //장기
				$s_date = $row_r["s_date"];
				$e_date = $row_r["e_date"];
			}
			$param["period"] = $s_date." ~ ".$e_date;
			$param["cancle_date"] = $cancle_date;
			$param["cancle_amount"] = number_format($cancle_price)."원";
			$mobile = all_seed_dec($row_r["o_phone"]);
			kakaoJoinCancel($param,$mobile);
			
	
			echo "<script>alert('결제 취소요청이 성공하였습니다.');parent.document.location.reload();</script>";
		}else{
			echo "<script>alert('결제 취소요청이 실패하였습니다.\\n사유:".iconv("EUC-KR","UTF-8",$inipay->m_resultMsg)."\\n같은 반복되는 경우 관리자에게 문의하세요.');</script>";
		}
}


?>
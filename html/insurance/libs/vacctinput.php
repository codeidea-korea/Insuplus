<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.mail.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php";

//*******************************************************************************
// FILE NAME : INIpayResult.php
// DATE : 2009.07
// 이니시스 가상계좌 입금내역 처리demon으로 넘어오는 파라메터를 control 하는 부분 입니다.
//*******************************************************************************
//**********************************************************************************
//이니시스가 전달하는 가상계좌이체의 결과를 수신하여 DB 처리 하는 부분 입니다.
//필요한 파라메터에 대한 DB 작업을 수행하십시오.
//**********************************************************************************

@extract($_GET);
@extract($_POST);
@extract($_SERVER);

$result = 0;
$log = new log();


//**********************************************************************************
//  이부분에 로그파일 경로를 수정해주세요.	

//$INIpayHome = "/home/usr/www/vacct";      // 이니페이 홈디렉터리
$INIpayHome = $_SERVER["DOCUMENT_ROOT"]."/log/pg/vacct/".date("Y")."/".date("m");      // 이니페이 홈디렉터리
//**********************************************************************************


$TEMP_IP = getenv("REMOTE_ADDR");
//$PG_IP = substr($TEMP_IP, 0, 10);
$PG_IP = $TEMP_IP;

$log->log_write("**************log_write vacct start***********");


// if ($PG_IP == "203.238.37.15" || $PG_IP == "39.115.212.9" || $PG_IP == "183.109.71.153" || 
// 	$PG_IP == "118.129.210.25" || $PG_IP == "183.109.71.153" || $PG_IP == "203.238.37.15") {  //PG에서 보냈는지 IP로 체크

    $msg_id = $msg_id;             //메세지 타입
    $no_tid = $no_tid;             //거래번호
    $no_oid = $no_oid;             //상점 주문번호
    $id_merchant = $id_merchant;   //상점 아이디
    $cd_bank = $cd_bank;           //거래 발생 기관 코드
    $cd_deal = $cd_deal;           //취급 기관 코드
    $dt_trans = $dt_trans;         //거래 일자
    $tm_trans = $tm_trans;         //거래 시간
    $no_msgseq = $no_msgseq;       //전문 일련 번호
    $cd_joinorg = $cd_joinorg;     //제휴 기관 코드

    $dt_transbase = $dt_transbase; //거래 기준 일자
    $no_transeq = $no_transeq;     //거래 일련 번호
    $type_msg = $type_msg;         //거래 구분 코드
    $cl_close = $cl_close;         //마감 구분코드
    $cl_kor = $cl_kor;             //한글 구분 코드
    $no_msgmanage = $no_msgmanage; //전문 관리 번호
    $no_vacct = $no_vacct;         //가상계좌번호
    $amt_input = $amt_input;       //입금금액
    $amt_check = $amt_check;       //미결제 타점권 금액
    $nm_inputbank = $nm_inputbank; //입금 금융기관명
    $nm_input = $nm_input;         //입금 의뢰인
    $dt_inputstd = $dt_inputstd;   //입금 기준 일자
    $dt_calculstd = $dt_calculstd; //정산 기준 일자
    $flg_close = $flg_close;       //마감 전화
    //가상계좌채번시 현금영수증 자동발급신청시에만 전달
    $dt_cshr = $dt_cshr;       //현금영수증 발급일자
    $tm_cshr = $tm_cshr;       //현금영수증 발급시간
    $no_cshr_appl = $no_cshr_appl;  //현금영수증 발급번호
    $no_cshr_tid = $no_cshr_tid;   //현금영수증 발급TID
    
    if(!is_dir($INIpayHome)) {
    	mkdir($INIpayHome, 0777,true);
    }

    $logfile = fopen($INIpayHome . "/".date("d")."_result.log", "a+");

    fwrite($logfile, "************************************************");
    fwrite($logfile, "ID_MERCHANT : " . $id_merchant . "\r\n");
    fwrite($logfile, "NO_TID : " . $no_tid . "\r\n");
    fwrite($logfile, "NO_OID : " . $no_oid . "\r\n");
    fwrite($logfile, "NO_VACCT : " . $no_vacct . "\r\n");
    fwrite($logfile, "AMT_INPUT : " . $amt_input . "\r\n");
    fwrite($logfile, "NM_INPUTBANK : " . $nm_inputbank . "\r\n");
    fwrite($logfile, "NM_INPUT : " . $nm_input . "\r\n");
    fwrite($logfile, "DT_TRANSBASE : " . $dt_transbase . "\r\n");
    fwrite($logfile, "NO_TRANSEQ : " . $no_transeq . "\r\n");
    fwrite($logfile, "CL_CLOSE : " . $cl_close . "\r\n");
    fwrite($logfile, "DT_INPUTSTD : " . $dt_inputstd . "\r\n");
    fwrite($logfile, "DT_CALCULSTD : " . $dt_calculstd . "\r\n");
    fwrite($logfile, "************************************************");

    /*
      fwrite( $logfile,"전체 결과값"."\r\n");
      fwrite( $logfile, $msg_id."\r\n");
      fwrite( $logfile, $no_tid."\r\n");
      fwrite( $logfile, $no_oid."\r\n");
      fwrite( $logfile, $id_merchant."\r\n");
      fwrite( $logfile, $cd_bank."\r\n");
      fwrite( $logfile, $dt_trans."\r\n");
      fwrite( $logfile, $tm_trans."\r\n");
      fwrite( $logfile, $no_msgseq."\r\n");
      fwrite( $logfile, $type_msg."\r\n");
      fwrite( $logfile, $cl_close."\r\n");
      fwrite( $logfile, $cl_kor."\r\n");
      fwrite( $logfile, $no_msgmanage."\r\n");
      fwrite( $logfile, $no_vacct."\r\n");
      fwrite( $logfile, $amt_input."\r\n");
      fwrite( $logfile, $amt_check."\r\n");
      fwrite( $logfile, $nm_inputbank."\r\n");
      fwrite( $logfile, $nm_input."\r\n");
      fwrite( $logfile, $dt_inputstd."\r\n");
      fwrite( $logfile, $dt_calculstd."\r\n");
      fwrite( $logfile, $flg_close."\r\n");
      fwrite( $logfile, "\r\n");
     */

    fclose($logfile);


	//************************************************************************************
		//위에서 상점 데이터베이스에 등록 성공유무에 따라서 성공시에는 "OK"를 이니시스로
		//리턴하셔야합니다. 아래 조건에 데이터베이스 성공시 받는 FLAG 변수를 넣으세요
		//(주의) OK를 리턴하지 않으시면 이니시스 지불 서버는 "OK"를 수신할때까지 계속 재전송을 시도합니다
		//기타 다른 형태의 PRINT( echo )는 하지 않으시기 바랍니다
	//      if (데이터베이스 등록 성공 유무 조건변수 = true)
	//      {
	$orderno = $no_oid;

	//데이터 검증
	$sql_data_order_check  = " SELECT o.pay_name, o.order_step, j.o_phone FROM ";
	$sql_data_order_check .= " tbl_order_list o INNER JOIN tbl_order_list_join j ON o.orderno = j.orderno WHERE o.orderno = '".$orderno."' AND j.chk_join = 'N' ";
	$result_data_order_check = $dbcon -> query($sql_data_order_check);
	$row_data_order_check = $dbcon->fetch_array($result_data_order_check);

	$vbank_num = str_replace(" ","",$row_data_order_check["pay_name"]);
	$vbank_num = explode("/",$vbank_num)[2];
	$o_phone = $row_data_order_check["o_phone"];

	$log->log_write("=== vacct start result : ".$result);

	if($vbank_num == $no_vacct && $row_data_order_check["order_step"] == "1") {
		
		$log->log_write("=== vacct step 1  ===");
		
		$order_step = "2";
		$join_status = "Y";
		
		//주문내역 상태 변경 
		$sql  = " UPDATE tbl_order_list SET order_step = '".$order_step."' ";
		$sql .= " WHERE orderno = '".$orderno."' ";
		
		$result = $dbcon->query($sql);
		
		//가입내역 상태 변경
		$sql  = " UPDATE tbl_order_list_join SET join_status = '".$join_status."' ";
		$sql .= " WHERE orderno = '".$orderno."' ";
		
		$result = $dbcon->query($sql);
		
		
		if($result) {
			$sql  = " SELECT count(*) FROM tbl_board_coupon_history ";
			$sql .= " WHERE mobile = '".$o_phone."' AND receive_orderno = '".$orderno."' ";
			$log->log_write($sql);
			$log->log_write("count===".$coupon_cnt);
			$coupon_cnt = $dbcon->getCount($sql); //가입 시 받은 쿠폰여부 체크
				
			$SQL_ORDER  = " SELECT j.o_name,j.o_phone ";
			$SQL_ORDER .= " , o.o_email1, o.o_email2 ";
			$SQL_ORDER .= " , o.ins_file_cd, o.service_file_cd ";
			$SQL_ORDER .= " , o.order_step, o.s_date, o.s_date_time, o.e_date, o.e_date_time, o.chk_p ";
			$SQL_ORDER .= " , o.sale_gubun, o.sale_discount, o.cp_cd, o.recommend_cd, o.s_amount ";
			$SQL_ORDER .= " , o.pr_name, o.ins_name, o.plan_name, o.chk_service, o.purpose ";
			$SQL_ORDER .= " , o.ins_amount, o.service_amount, o.t_amount, o.pg_pay_type, j.regdate, o.plan_cd ";
			$SQL_ORDER .= " FROM tbl_order_list_join j INNER JOIN  tbl_order_list o  ON j.orderno = o.orderno ";
			$SQL_ORDER .= " WHERE j.orderno = '".$orderno."' AND chk_join = 'N'  ";
			$result_order = $dbcon -> query($SQL_ORDER);
			$row_order = $dbcon->fetch_array($result_order);
				
			// 플랜검색
			$SQL_PLAN = "select * from tbl_board_plan where seq=".$row_order["plan_cd"]."";
			$RS_PLAN = $dbcon -> query($SQL_PLAN);
			$row_plan = $dbcon -> fetch_array($RS_PLAN);
				
			if($coupon_cnt == 0 && strtolower($row_order["pg_pay_type"]) == "vbank") { //쿠폰 전달
		
				$s_coupon_date = date("Y-m-d",strtotime($row_order["regdate"]));
				$e_coupon_date = date("Y-m-d",strtotime($s_coupon_date." +180 days "));
		
				$SQL_EVENT  = "SELECT 
												seq
												,category
												,subject
												,content
												,coupon_name
												,start_date
												,end_date
												,expire_date_s
												,expire_date_e
												,discount
												,coupon_size
												,event_type
												,event_url
												,event_partnership_code
												,duplicate_status_yn
												,insurance_discount_applied
												,service_fee_discount_applied
												,insurance_discount_rate
												,insurance_max_discount_amount
												,service_fee_discount_rate
												,service_fee_max_discount_amount
												,subscription_start_day
												,subscription_end_day
												,event_category_master_seq
												,min_companion
												,max_companion";
				$SQL_EVENT .= " FROM tbl_board_event WHERE event_type='C' AND event_partnership_code = 'insuplus'";
				$SQL_EVENT .= " AND start_date <= now() AND end_date >= now()";
				$SQL_EVENT .= " ORDER BY seq desc LIMIT 0,1 ";
				$result_event = $dbcon -> query($SQL_EVENT);
				$row_event = $dbcon->fetch_array($result_event);

				$discount = 0;
				$symbol = '%';
				$discountValue = '';
				if($row_event) {
					if($row_event["service_fee_discount_applied"] == 'F') {
						$symbol = '원';
						$discount = number_format($row_event["service_fee_max_discount_amount"]);
					} else {
						if($row_event["insurance_discount_applied"] == 'P' && $row_event["service_fee_discount_applied"] == 'P') {
							$symbol = '%';
							$discount = max($row_event["insurance_discount_rate"], $row_event["service_fee_discount_rate"]);
						} else if($row_event["insurance_discount_applied"] == 'P') {
							$symbol = '%';
							$discount = $row_event["insurance_discount_rate"];
						} else if($row_event["service_fee_discount_applied"] == 'P'){
							$symbol = '%';
							$discount = $row_event["service_fee_discount_rate"];
						}
					}
				}
				$discountValue = $discount.$symbol;

				if($row_event) {
					$INS_SQL_CP  =  " INSERT INTO tbl_board_coupon_history ";
					$INS_SQL_CP .= " (receive_orderno, event_seq, start_date, end_date, mobile ";
					$INS_SQL_CP .= " , ori_mobile, temp_discount, use_yn, writedate) ";
					$INS_SQL_CP .= "  VALUES ";
					$INS_SQL_CP .= " ('".$orderno."', '".$row_event["seq"]."', '".$s_coupon_date."', '".$e_coupon_date."','".$o_phone."' ";
					$INS_SQL_CP .= " ,'".$o_phone."', '".$discount."','N', now()) ";

					$result = $dbcon -> query($INS_SQL_CP);			
					$log->log_write("vacct coupon result : ".$result);
				}
			}

			$param = array();
			$mobile = all_seed_dec($row_order["o_phone"]);
			$param["name"] = all_seed_dec($row_order["o_name"]);
			//$param["pr_name"] = $row_order["pr_name"]." ".$row_order["ins_name"]." ".$row_order["plan_name"];
			$param["pr_name"] = $row_order["plan_name"];
			$param["plan_name"] = $row_order["plan_name"];
			if ($row_order["chk_p"] == "Y") {
				$s_date = $row_order["s_date"] . " " . $row_order["s_date_time"];
				$e_date = $row_order["e_date"] . " " . $row_order["e_date_time"];
				$param["period"] = $s_date . "시 ~ " . $e_date . "시";
			} else if ($row_order["chk_p"] == "N") {
				$s_date = $row_order["s_date"];
				$e_date = $row_order["e_date"];
				$param["period"] = $s_date . " ~ " . $e_date;
			}
			$arr_period = getArrPeriod($s_date, $e_date, $row_order["chk_p"]);
			$param["period"] .= " (" . $arr_period["day"] . "일)";
			$param["purpose"] = $row_order["purpose"];
			$param["ins_amount"] = $row_order["ins_amount"];
			$param["amount"] = $row_order["ins_amount"] + $row_order["service_amount"];
			$param["t_amount"] = $row_order["t_amount"];
			$param["payMethod"] = $resultMap["payMethod"];
			$param["pay_name"] = $pay_name;

			//서비스가 인슈플러스인 경우 원격진료코드 발행	
			$param["telemedic"] = "";
			if ($row_order["chk_service"] == "C" || $row_order["chk_service"] == "D") {
				// $SQL_T = "SELECT telemedicine_cd from tbl_telemedicine_cd_list where expire_date = " . $arr_period["month"] . " and join_orderno is null order by telemedi_seq ASC limit 1";
				// $RS_T = $dbcon->query($SQL_T);
				// $tele_cd = $dbcon->fetch_array($RS_T);
				// $param["telemedic"] = $tele_cd["telemedicine_cd"];
				// $log->log_write(" 원격진료코드 발행 (8) : " . $param["telemedic"]);
				// $log->log_write("=== pc 결제내역 (알림톡, 이메일 생략) END === ");
				// $SQL_TI = "UPDATE tbl_telemedicine_cd_list set join_orderno = '" . $resultMap["MOID"] . "', usedate = now() where telemedicine_cd = '" . $tele_cd["telemedicine_cd"] . "' ";
				// $RS_TI = $dbcon->query($SQL_TI);
				//서비스명
				$SQL_CMN_CD = "select cd_nm from safety_training.fd_cmn_cd where grp_cd = 'CC13' and cd_val1 = '" . $row_order["chk_service"] . "' order by ord ASC";
				$RS_CMN_CD = $dbcon->query($SQL_CMN_CD);
				$service_nm = $dbcon->fetch_array($RS_CMN_CD);
				$param["pr_name"] .= " " . $service_nm["cd_nm"] . "타입";
				$param["service_name"] = $service_nm["cd_nm"] . "타입";
			}	
			
			//===================================================
			// 롯데 등업코드 생성
			//===================================================	
			$SQL_LOTTE = "SELECT seq, coupon_cd, start_date, end_date FROM tbl_temporary_coupon 
							where orderno is NULL and start_date <= now() and end_date >= now() order by seq asc
							limit 1";
			$RS_LOTTE = $dbcon -> query($SQL_LOTTE);
			$lotte_promotion_info = $dbcon->fetch_array($RS_LOTTE);
			$log->log_write("=== 롯데 등업코드 생성 === ". $orderno);

			$today = strtotime(date('Y-m-d H:i:s'));
			$promotion_start_date = strtotime($lotte_promotion_info["start_date"]);
			$promotion_end_date = strtotime($lotte_promotion_info["end_date"]);
			
			if($lotte_promotion_info && $promotion_start_date <= $today && $promotion_end_date >= $today) {							
				$SQL_LOTTE_USE = "UPDATE tbl_temporary_coupon set orderno = '".$resultMap["MOID"]."', used_date = now() where seq = '".$lotte_promotion_info["seq"]."' ";
				$RS_LOTTE_USE = $dbcon -> query($SQL_LOTTE_USE);
				$param["promotion"] = $lotte_promotion_info["coupon_cd"];
				$log->log_write("orderno===" . $resultMap["MOID"]);
				//===================================================
				// 롯데 등업코드 알림톡 전송
				//===================================================						
				kakaoPromotionSend($param,$mobile);
			} else {
				// if ($row_order["chk_service"] == "A" || $row_order["chk_service"] == "B" || $row_order["chk_service"] == "N") {
				// 	kakaoInsuplusJoin($param,all_seed_dec($o_phone));
				// } else {
				// 	kakaoJoin($param,all_seed_dec($o_phone));
				// }
				kakaoInsuplusJoin($param,$mobile);
			}
			
			//가입감사쿠폰은 항상 알림톡 발송 하도록 20231201
			$param["discount"] = $discountValue;
			$param["discount_txt"] = $param["discount"]."% (할인 최대한도 30,000원)";
			$param["coupon_name"] = $row_event["coupon_name"];
			$param["coupon_period"] = $s_coupon_date." ~ ".$e_coupon_date;	
			
			kakaoJoinCoupon($param,all_seed_dec($o_phone));
			
			$param["chk_service"] = $row_order["chk_service"];
			$param["domain"] = getDomain();
			$param["ins_seq"] = $row_order["ins_file_cd"]; //약관파일 고유번호
			$param["ins_term1"] = $row_plan["ins_term1_seq"]; //보험약관 파일 고유번호
			$param["ins_term2"] = $row_plan["ins_term2_seq"]; //보험약관 파일 고유번호
			$param["service_seq"] = $row_order["service_file_cd"]; //약관파일 고유번호
			$email = all_seed_dec($row_order["o_email1"])."@".all_seed_dec($row_order["o_email2"]);

			mailJoinSend($param, $email); // 서비스 상관없이 인슈플러스 양식으로 발송 20230303
			/*if($row_order["chk_service"] == "D" || $row_order["chk_service"] == "E") {
				mailJoinFlyingSend($param, $email);
			} else {
				mailJoinSend($param, $email);
			}*/

			$log->log_write("=== vacct end result ===");
			echo "OK";
		} else {
			echo "FAIL";
		}
	}
// } else {
// 	$log->log_write("log_write vacct error : ".$PG_IP);
// }
$dbcon -> dbcon_close();
?>

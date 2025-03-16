<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.mail.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php";

$log = new log(); 

?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style type="text/css">
            body { background-color: #efefef;}
            body, tr, td {font-size:11pt; font-family:굴림,verdana; color:#433F37; line-height:19px;}
            table, img {border:none}

        </style>
        <link rel="stylesheet" href="../css/group.css" type="text/css">
        <script type="text/javascript">
            function cancelTid() {
                var form = document.frm;

                var win = window.open('', 'OnLine', 'scrollbars=no,status=no,toolbar=no,resizable=0,location=no,menu=no,width=600,height=400');
                win.focus();
                form.action = "http://walletpaydemo.inicis.com/stdpay/cancel/INIcancel_index.jsp";
                form.method = "post";
                form.target = "OnLine";
                form.submit();

            }
        </script>
    </head>
    <body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0>
       
<?php
        require_once('../libs/INIStdPayUtil.php');
        require_once('../libs/HttpClient.php');

        $util = new INIStdPayUtil();

        try {

            //#############################
            // 인증결과 파라미터 일괄 수신
            //#############################
            //		$var = $_REQUEST["data"];

            //#####################
            // 인증이 성공일 경우만
            //#####################
            if (strcmp("0000", $_REQUEST["resultCode"]) == 0) {
                //############################################
                // 1.전문 필드 값 설정(***가맹점 개발수정***)
                //############################################;

                $mid 				= $_REQUEST["mid"];     					// 가맹점 ID 수신 받은 데이터로 설정
                $signKey 		= SIGNKEY; 		// 가맹점에 제공된 키(이니라이트키) (가맹점 수정후 고정) !!!절대!! 전문 데이터로 설정금지
                //$signKey 		= "UTM2cWdEZzExVUtJMmVkeGJwL0c4QT09";//운영
                
                $timestamp 		= $util->getTimestamp();   					// util에 의해서 자동생성
                $charset 		= "UTF-8";        							// 리턴형식[UTF-8,EUC-KR](가맹점 수정후 고정)
                $format 			= "JSON";        							// 리턴형식[XML,JSON,NVP](가맹점 수정후 고정)

                $authToken 	= $_REQUEST["authToken"];   				// 취소 요청 tid에 따라서 유동적(가맹점 수정후 고정)
                $authUrl 			= $_REQUEST["authUrl"];    					// 승인요청 API url(수신 받은 값으로 설정, 임의 세팅 금지)
                $netCancel 		= $_REQUEST["netCancelUrl"];   				// 망취소 API url(수신 받은f값으로 설정, 임의 세팅 금지)

                $mKey 			= hash("sha256", $signKey);					// 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)

                //#####################
                // 2.signature 생성
                //#####################
                $signParam["authToken"] 	= $authToken;  	// 필수
                $signParam["timestamp"] 	= $timestamp;  	// 필수
                // signature 데이터 생성 (모듈에서 자동으로 signParam을 알파벳 순으로 정렬후 NVP 방식으로 나열해 hash)
                $signature = $util->makeSignature($signParam);


                //#####################
                // 3.API 요청 전문 생성
                //#####################
                $authMap["mid"] 			= $mid;   		// 필수
                $authMap["authToken"] 		= $authToken; 	// 필수
                $authMap["signature"] 		= $signature; 	// 필수
                $authMap["timestamp"] 		= $timestamp; 	// 필수
                $authMap["charset"] 		= $charset;  	// default=UTF-8
                $authMap["format"] 			= $format;  	// default=XML


                try {

                    $httpUtil = new HttpClient();

                    //#####################
                    // 4.API 통신 시작
                    //#####################

                    $authResultString = "";

                    if ($httpUtil->processHTTP($authUrl, $authMap)) {
                        $authResultString = $httpUtil->body;
//                        echo "<p><b>RESULT DATA :</b> $authResultString</p>";			//PRINT DATA
                    } else {
                        echo "Http Connect Error\n";
                        echo $httpUtil->errormsg;

                        throw new Exception("Http Connect Error");
                    }

                    //############################################################
                    //5.API 통신결과 처리(***가맹점 개발수정***)
                    //############################################################
//                    echo "## 승인 API 결과 ##";

                    $resultMap = json_decode($authResultString, true);
                    /*************************  결제보안 추가 2016-05-18 START ****************************/
                    $secureMap["mid"]		= $mid;							//mid
                    $secureMap["tstamp"]	= $timestamp;					//timestemp
                    $secureMap["MOID"]		= $resultMap["MOID"];			//MOID
                    $secureMap["TotPrice"]	= $resultMap["TotPrice"];		//TotPrice

					$log->log_write("결제 성공여부 : ".$resultMap["resultCode"]);
                    // signature 데이터 생성
                    $secureSignature = $util->makeSignatureAuth($secureMap);
                    /*************************  결제보안 추가 2016-05-18 END ****************************/
					if ((strcmp("0000", $resultMap["resultCode"]) == 0) && (strcmp($secureSignature, $resultMap["authSignature"]) == 0) ){	//결제보안 추가 2016-05-18
					   /*****************************************************************************
				       * 여기에 가맹점 내부 DB에 결제 결과를 반영하는 관련 프로그램 코드를 구현한다.

						 [중요!] 승인내용에 이상이 없음을 확인한 뒤 가맹점 DB에 해당건이 정상처리 되었음을 반영함
								처리중 에러 발생시 망취소를 한다.
				       ******************************************************************************/
                      // 20250316 yjhzzzzdev 수정
                      if ($resultMap["payMethod"]=="Card" || $resultMap["payMethod"]=="VCard"){$pay_name = "CARD / ".$resultMap["CARD_PurchaseName"];}
                      if ($resultMap["payMethod"]=="HPP" || $resultMap["payMethod"]=="MOBILE"){$pay_name = "HPP";$order_step = "2";$join_status="Y";}
                      if ($resultMap["payMethod"]=="VBank"){$pay_name = "가상계좌 / ".$resultMap["vactBankName"];}
                      //    if ($resultMap["payMethod"]=="Card" || $resultMap["payMethod"]=="VCard"){$pay_name = "CARD / ".$resultMap["CARD_PurchaseName"]." / ".$resultMap["CARD_Num"]." ";$order_step = "2"; $join_status="Y";}
					//    if ($resultMap["payMethod"]=="HPP" || $resultMap["payMethod"]=="MOBILE"){$pay_name = "HPP";$order_step = "2";$join_status="Y";}
					//    if ($resultMap["payMethod"]=="VBank"){$pay_name = "가상계좌 / ".$resultMap["vactBankName"]." / ".$resultMap["VACT_Num"]."";$order_step = "1"; $join_status="W";}


//                        echo "<tr><th class='td01'><p>거래 성공 여부</p></th>";
//                        echo "<td class='td02'><p>성공</p></td></tr>";
						//===================================================
						// 주문입력
						//===================================================
						$SQL = "insert into tbl_order_list ";
						$SQL .= " ( ";
						$SQL .= " orderno,pr_name,ins_name,plan_name, agree_cd, service_name, rule_site_cd, rule_group_cd, rule_privacy_cd, ins_file_cd, service_file_cd ,s_date,s_date_time,e_date,e_date_time,ins_period,chk_p,o_name,o_email1,o_email2,purpose,join_cnt,join_ch,join_nation_cd,join_nation_name,order_step,ins_amount,s_amount,cp_amount,vat_amount,t_amount,service_amount,writedate,pg_id,pg_pay_type,pg_isdn,pay_name,chk_service ";
						$SQL .= " ,sale_gubun,sale_discount,new_cp_cd,recommend_cd ";
						$SQL .= " ,pr_cd,ins_cd,plan_cd,service_cd ";
						$SQL .= " ) ";
						$SQL .= " select ";
						$SQL .= " orderno,pr_name,ins_name,plan_name, agree_cd, service_name, rule_site_cd, rule_group_cd, rule_privacy_cd, ins_file_cd, service_file_cd  ,s_date,s_date_time,e_date,e_date_time,ins_period,chk_p,o_name,o_email1,o_email2,purpose,join_cnt,join_ch,join_nation_cd,join_nation_name,'".$order_step."',ins_amount,s_amount,cp_amount,vat_amount,t_amount,service_amount,now(),'".$mid."','".$resultMap["payMethod"]."','".$resultMap["tid"]."','".$pay_name."',chk_service ";
						$SQL .= " ,sale_gubun,sale_discount,new_cp_cd,recommend_cd ";
						$SQL .= " ,pr_cd,ins_cd,plan_cd,service_cd ";
						$SQL .= " from tbl_order_listTemp ";
						$SQL .= " where orderno ='".$resultMap["MOID"]."' ";
//						echo $SQL." 첫번째<br>";
						$log->log_write("=== pc 결제내역 START === ");
						$log->log_write("결제내역등록(1) : ".$SQL);
						$result_ord = $dbcon -> query($SQL);

						//===================================================
						// 가입 회원정보 입력
						//===================================================
						$SQL_J = " insert into tbl_order_list_join ";
						$SQL_J .= " (orderno,gender,chk_join,chk_eng_passport,o_name,o_phone,o_isdn1,o_isdn2,o_name_en, ins_plan_cd,join_amount,join_service,vat_amount,s_amount,t_amount,regdate,join_status,is_abroad_resident) ";
						$SQL_J .= " select orderno,gender,chk_join,chk_eng_passport,o_name,o_phone,o_isdn1,o_isdn2,o_name_en, ins_plan_cd, join_amount,join_service,vat_amount,s_amount,t_amount,now(),'".$join_status."',is_abroad_resident ";
						$SQL_J .= " from tbl_order_list_joinTemp where orderno ='".$resultMap["MOID"]."' ";
						
						$log->log_write("가입내역등록(2) : ".$SQL_J);
						
						$result_J = $dbcon -> query($SQL_J);


						//===================================================
						// 질문사항 입력
						//===================================================
						$SQL_N = "insert into tbl_order_list_notice (orderno,pr_notice,pr_notice_a) ";
						$SQL_N .= " select orderno,pr_notice,pr_notice_a from tbl_order_list_noticeTemp where orderno='".$resultMap["MOID"]."' ";
						
						$log->log_write("질문사항 등록(3) : ".$SQL_N);
//						echo $SQL_J." 질문사항<br>";
						$result_N = $dbcon -> query($SQL_N);

						//===================================================
						// 쿠폰 입력
						//===================================================
						$SQL_cp = " insert into tbl_order_list_coupon (orderno,coupon_name,new_cp_cd,cp_sale_per,coupon_amount,writedate) ";
						$SQL_cp .= " select orderno,coupon_name,new_cp_cd,cp_sale_per,coupon_amount,now() from tbl_order_list_couponTemp where orderno= '".$resultMap["MOID"]."' ";
						$result_cp = $dbcon -> query($SQL_cp);
						$log->log_write("쿠폰 히스토리 등록 (4) : ".$SQL_cp);
						
						//===================================================
						// 결제정보 출력
						//===================================================
						$SQL_ORDER  = " SELECT j.o_name,j.o_phone ";
						$SQL_ORDER .= " , o.o_email1, o.o_email2 ";
						$SQL_ORDER .= " , o.ins_file_cd, o.service_file_cd ";
						$SQL_ORDER .= " , o.order_step, o.s_date, o.s_date_time, o.e_date, o.e_date_time, o.chk_p ";
						$SQL_ORDER .= " , o.sale_gubun, o.sale_discount, o.new_cp_cd, o.recommend_cd, o.s_amount ";
						$SQL_ORDER .= " , o.pr_name, o.ins_name, o.plan_name, o.chk_service, o.purpose ";
						$SQL_ORDER .= " , o.ins_amount, o.service_amount, o.t_amount, j.regdate, o.plan_cd";
						$SQL_ORDER .= " FROM tbl_order_list_join j INNER JOIN  tbl_order_list o  ON j.orderno = o.orderno ";
						$SQL_ORDER .= " WHERE j.orderno = '".$resultMap["MOID"]."' AND chk_join = 'N'  ";
						
						$log->log_write("결제정보 출력 (4) : ".$SQL_ORDER);
						
						$result_order = $dbcon -> query($SQL_ORDER);
						$row_order = $dbcon->fetch_array($result_order);
						
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
						
						$log->log_write("이벤트 정보 출력 (5) : ".$SQL_EVENT);
						
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

						// 플랜검색
						$SQL_PLAN = "select * from tbl_board_plan where seq=".$row_order["plan_cd"]."";
						$RS_PLAN = $dbcon -> query($SQL_PLAN);
						$row_plan = $dbcon -> fetch_array($RS_PLAN);
						
						//===================================================
						// 사용 쿠폰, 추천코드 처리
						//===================================================
						if($row_order["sale_gubun"] == "C") {
							$UP_SQL_CP = " UPDATE tbl_board_coupon_history SET ";
							$UP_SQL_CP .= " orderno = '".$resultMap["MOID"]."' , discount = '".$row_order["sale_discount"]."' ";
							$UP_SQL_CP .= " , s_amount = '".$row_order["s_amount"]."' , use_yn = 'Y' ";
							$UP_SQL_CP .= " WHERE seq in (".$row_order["new_cp_cd"].") "; 
							
							$log->log_write(" 쿠폰사용내역 (6) : ".$UP_SQL_CP);
							
							$result_upd_cp = $dbcon -> query($UP_SQL_CP);
						} else if($row_order["sale_gubun"] == "R") {
							
							$SQL_RECOMMEND = " SELECT recommendation_code, discount FROM tbl_board_recommend_code WHERE seq = '".$row_order["recommend_cd"]."' ";
							$log->log_write("  추천코드내역 출력 (6) : ".$SQL_RECOMMEND);
							$result_recommend = $dbcon->query($SQL_RECOMMEND);
							$row_recommend = $dbcon->fetch_array($result_recommend);
							
							$UP_SQL_RECOMMEND = " INSERT INTO tbl_board_recommend_code_history ";
							$UP_SQL_RECOMMEND .= " (orderno, recommend_seq, recommend_name, discount, temp_discount, s_amount, writedate) ";
							$UP_SQL_RECOMMEND .= " values  ";
							$UP_SQL_RECOMMEND .= " ('".$resultMap["MOID"]."','".$row_order["recommend_cd"]."' ,'".$row_recommend["recommendation_code"]."' ";
							$UP_SQL_RECOMMEND .= " 	,'".$row_order["sale_discount"]."','".$row_recommend["discount"]."','".$row_order["s_amount"]."',now()) ";
							
							$log->log_write("  추천코드사용내역 (6) : ".$UP_SQL_RECOMMEND);
							
							$result_ins_recommend = $dbcon -> query($UP_SQL_RECOMMEND);
						}
						
						//===================================================
						// 감사쿠폰등록 (무통장 입금 제외 쿠폰 발생 안함)
						//===================================================
						if ($resultMap["payMethod"]!="VBank"){
							if($row_event) {
								$INS_SQL_CP  =  " INSERT INTO tbl_board_coupon_history ";
								$INS_SQL_CP .= " (receive_orderno, event_seq, start_date, end_date, mobile ";
								$INS_SQL_CP .= " , ori_mobile, temp_discount, use_yn, writedate) ";
								$INS_SQL_CP .= "  VALUES ";
								$INS_SQL_CP .= " ('".$resultMap["MOID"]."', '".$row_event["seq"]."', '".$s_coupon_date."', '".$e_coupon_date."','".$row_order["o_phone"]."' ";
								$INS_SQL_CP .= " ,'".$row_order["o_phone"]."', '".$discount."','N', now()) ";
								
								$log->log_write("  감사쿠폰발행(7)   : ".$INS_SQL_CP);
								
								$result_ins_cp = $dbcon -> query($INS_SQL_CP);
							}							
						}

						$param = array();
						$mobile = all_seed_dec($row_order["o_phone"]);
						$param["name"] = all_seed_dec($row_order["o_name"]);
						//$param["pr_name"] = $row_order["pr_name"]." ".$row_order["ins_name"]." ".$row_order["plan_name"];
						$param["pr_name"] = $row_order["plan_name"];
						$param["plan_name"] = $row_order["plan_name"];
						
						if($row_order["chk_p"] == "Y") {
							$s_date = $row_order["s_date"]." ".$row_order["s_date_time"];
							$e_date = $row_order["e_date"]." ".$row_order["e_date_time"];
							$param["period"] = $s_date."시 ~ ".$e_date."시";
						} else if($row_order["chk_p"] == "N") {
							$s_date = $row_order["s_date"];
							$e_date = $row_order["e_date"];
							$param["period"] = $s_date." ~ ".$e_date;
						}
						$arr_period = getArrPeriod($s_date,$e_date,$row_order["chk_p"]);
						$param["period"] .= " (".$arr_period["day"]."일)";
						
						$param["purpose"] = $row_order["purpose"];
						$param["ins_amount"] = $row_order["ins_amount"];
						$param["amount"] = $row_order["ins_amount"]+$row_order["service_amount"];
						$param["t_amount"] = $row_order["t_amount"];
						
						$param["payMethod"] = $resultMap["payMethod"];
						$param["pay_name"] = $pay_name;
						//서비스가 인슈플러스인 경우 원격진료코드 발행	
						if($row_order["chk_service"] == "C" || $row_order["chk_service"] == "D") { 
							// $SQL_T = "SELECT telemedicine_cd from tbl_telemedicine_cd_list where expire_date = ".$arr_period["month"]." and join_orderno is null order by telemedi_seq ASC limit 1";
							
							// $RS_T = $dbcon -> query($SQL_T);
							// $tele_cd = $dbcon->fetch_array($RS_T);
							// $param["telemedic"] = $tele_cd["telemedicine_cd"];

							// $log->log_write(" 원격진료코드 발행 (8) : ". $param["telemedic"]);
							// $log->log_write("=== pc 결제내역 (알림톡, 이메일 생략) END === ");

							// $SQL_TI = "UPDATE tbl_telemedicine_cd_list set join_orderno = '".$resultMap["MOID"]."', usedate = now() where telemedicine_cd = '".$tele_cd["telemedicine_cd"]."' ";
							// $RS_TI = $dbcon -> query($SQL_TI);
							
							//서비스명
							$SQL_CMN_CD = "select cd_nm from safety_training.fd_cmn_cd where grp_cd = 'CC13' and cd_val1 = '".$row_order["chk_service"]."' order by ord ASC";
							$RS_CMN_CD = $dbcon -> query($SQL_CMN_CD);
							$service_nm = $dbcon->fetch_array($RS_CMN_CD);
							$param["pr_name"] .= " ".$service_nm["cd_nm"]."타입";
							$param["service_name"] = $service_nm["cd_nm"]."타입";
						}
						
						//===================================================
						// 롯데 등업코드 생성
						//===================================================	
						$SQL_LOTTE = "SELECT seq, coupon_cd, start_date, end_date FROM tbl_temporary_coupon 
										where orderno is NULL and start_date <= now() and end_date >= now() order by seq asc
										limit 1";
						$RS_LOTTE = $dbcon -> query($SQL_LOTTE);
						$lotte_promotion_info = $dbcon->fetch_array($RS_LOTTE);
						
						$today = strtotime(date('Y-m-d H:i:s'));
						$promotion_start_date = strtotime($lotte_promotion_info["start_date"]);
						$promotion_end_date = strtotime($lotte_promotion_info["end_date"]);

						if($lotte_promotion_info && $promotion_start_date <= $today && $promotion_end_date >= $today) {
							if ($resultMap["payMethod"] != "VBank") { //알림톡 쿠폰발행(무통장 X)									
								$SQL_LOTTE_USE = "UPDATE tbl_temporary_coupon set orderno = '".$resultMap["MOID"]."', used_date = now() where seq = '".$lotte_promotion_info["seq"]."' ";
								$RS_LOTTE_USE = $dbcon -> query($SQL_LOTTE_USE);
								$param["promotion"] = $lotte_promotion_info["coupon_cd"];
								$log->log_write("등업쿠폰 : " . $SQL_LOTTE_USE);
								//===================================================
								// 롯데 등업코드 알림톡 전송
								//===================================================						
								kakaoPromotionSend($param,$mobile);
							} else {
								//추가 알림톡
								$arr_pay_name = explode("/", $param["pay_name"]);
								$param["bank"] = $arr_pay_name[1];
								$param["account"] = $arr_pay_name[2];
								kakaoJoinBankInfo($param, $mobile);
							}
						} else {
							//===================================================
							// 알림톡 전송
							//===================================================						
							if ($resultMap["payMethod"]!="VBank"){ //알림톡 쿠폰발행(무통장 X)
								
								// if($row_order["chk_service"] == "A" || $row_order["chk_service"] == "B" || $row_order["chk_service"] == "N") {
								// 	kakaoInsuplusJoin($param,$mobile);
								// } else {
								// 	kakaoJoin($param,$mobile);
								// }
								kakaoInsuplusJoin($param,$mobile);
								
							} else {
								//추가 알림톡
								$arr_pay_name = explode("/",$param["pay_name"]);
								$param["bank"] = $arr_pay_name[1];
								$param["account"] = $arr_pay_name[2];
								kakaoJoinBankInfo($param,$mobile);
							}
						}

						//가입감사쿠폰은 항상 알림톡 발송 하도록 20231201
						$param["discount"] = $discountValue;
						$param["discount_txt"] = $param["discount"]."% (할인 최대한도 30,000원)";
						$param["coupon_name"] = $row_event["coupon_name"];
						$param["coupon_period"] = $s_coupon_date." ~ ".$e_coupon_date;
						
						kakaoJoinCoupon($param,$mobile);						

						//===================================================
						// 이메일 전송
						//===================================================
						$param["chk_service"] = $row_order["chk_service"];
						$param["domain"] = getDomain();
						$param["ins_seq"] = $row_order["ins_file_cd"]; //약관파일 고유번호
						$param["ins_term1"] = $row_plan["ins_term1_seq"]; //보험약관 파일 고유번호
						$param["ins_term2"] = $row_plan["ins_term2_seq"]; //보험약관 파일 고유번호
						$param["service_seq"] = $row_order["service_file_cd"]; //약관파일 고유번호
						$email = all_seed_dec($row_order["o_email1"])."@".all_seed_dec($row_order["o_email2"]);

						mailJoinSend($param, $email); // 서비스 상관없이 인슈플러스 양식으로 발송 20230303
						/*if($row_order["chk_service"] == "D" || $row_order["chk_service"] == "E") {
							mailJoinFlyingSend($param,$email);
						} else {
							mailJoinSend($param,$email);
						}*/

						$_SESSION["orderno"] = $resultMap["MOID"];		// 주문번호 세션처리
						header("location: ../renewal_step05.php");
						exit;


					} else {
						//거래 실패
						$error =  "거래번호 : ".$resultMap["tid"];
						$error .= ",지불수단 : ".$resultMap["payMethod"];
						$error .= ",결과코드 : ".$resultMap["resultCode"];
						$error .= ",결과내용 : ".$resultMap["resultMsg"];
						$error .= ",주문번호 : ".$resultMap["MOID"];
						
						$sql =  " UPDATE tbl_order_listTemp SET ";
						$sql .= " fail_reason = '".$error."' ";
						$sql .= " WHERE orderno = '".$resultMap["MOID"]."' ";
						$dbcon ->query($sql);

						echo "<script>";
						echo "alert('거래가 실패했습니다. 오류코드 :".$resultMap["resultCode"]." 오류내용:". $resultMap["resultMsg"]."'); ";
						echo "location.href = '/html/main/index.php'; ";
						echo "</script>";
						//header("location: ../main/index.php");
						exit;
						/*
						echo "주문번호:".$resultMap["MOID"];
                        echo "<tr><th class='td01'><p>거래 성공 여부</p></th>";
                        echo "<td class='td02'><p>실패</p></td></tr>";
						echo "<tr><th class='line' colspan='2'><p></p></th></tr>
	                        <tr><th class='td01'><p>결과 코드</p></th>
	                        <td class='td02'><p>" . @(in_array($resultMap["resultCode"] , $resultMap) ? $resultMap["resultCode"] : "null" ) . "</p></td></tr>";

						//결제보안키가 다른 경우.
						if (strcmp($secureSignature, $resultMap["authSignature"]) != 0) {
							echo "<tr><th class='line' colspan='2'><p></p></th></tr>
								<tr><th class='td01'><p>결과 내용</p></th>
								<td class='td02'><p>" . "* 데이터 위변조 체크 실패" . "</p></td></tr>";

							//망취소
							if(strcmp("0000", $resultMap["resultCode"]) == 0) {
								throw new Exception("데이터 위변조 체크 실패");
							}
						} else {
							echo "<tr><th class='line' colspan='2'><p></p></th></tr>
								<tr><th class='td01'><p>결과 내용</p></th>
								<td class='td02'><p>" . @(in_array($resultMap["resultMsg"] , $resultMap) ? $resultMap["resultMsg"] : "null" ) . "</p></td></tr>";
						}
						*/

                    }

                    //공통 부분만
                    // 수신결과를 파싱후 resultCode가 "0000"이면 승인성공 이외 실패
                    // 가맹점에서 스스로 파싱후 내부 DB 처리 후 화면에 결과 표시
                    // payViewType을 popup으로 해서 결제를 하셨을 경우
                    // 내부처리후 스크립트를 이용해 opener의 화면 전환처리를 하세요
                    //throw new Exception("강제 Exception");
                } catch (Exception $e) {
                    // $s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
                    //####################################
                    // 실패시 처리(***가맹점 개발수정***)
                    //####################################
                    //---- db 저장 실패시 등 예외처리----//
                    $s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
                    echo $s;

                    //#####################
                    // 망취소 API
                    //#####################

                    $netcancelResultString = ""; // 망취소 요청 API url(고정, 임의 세팅 금지)

                    if ($httpUtil->processHTTP($netCancel, $authMap)) {
                        $netcancelResultString = $httpUtil->body;
                    } else {
                        echo "Http Connect Error\n";
                        echo $httpUtil->errormsg;

                        throw new Exception("Http Connect Error");
                    }

					echo "<br/>## 망취소 API 결과 ##<br/>";

					/*##XML output##*/
					//$netcancelResultString = str_replace("<", "&lt;", $$netcancelResultString);
					//$netcancelResultString = str_replace(">", "&gt;", $$netcancelResultString);

                    // 취소 결과 확인
                    echo "<p>". $netcancelResultString . "</p>";
                }
            } else {

                //#############
                // 인증 실패시
                //#############
                echo "<br/>";
                echo "####인증실패####";

                echo "<pre>" . var_dump($_REQUEST) . "</pre>";
            }
        } catch (Exception $e) {
            $s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
            echo $s;
        }
?>
</body>
</html>
<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.mail.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php";

//*******************************************************************************
// FILE NAME : mx_rnoti.php
// FILE DESCRIPTION :
// 이니시스 smart phone 결제 결과 수신 페이지 샘플
// 기술문의 : ts@inicis.com
// HISTORY
// 2010. 02. 25 최초작성
// 2010  06. 23 WEB 방식의 가상계좌 사용시 가상계좌 채번 결과 무시 처리 추가(APP 방식은 해당 없음!!)
// WEB 방식일 경우 이미 P_NEXT_URL 에서 채번 결과를 전달 하였으므로,
// 이니시스에서 전달하는 가상계좌 채번 결과 내용을 무시 하시기 바랍니다.
//*******************************************************************************




  $PGIP = $_SERVER['REMOTE_ADDR'];
  writeLog("**** mobile noti start ****");
  //if(true)	//테스트 PG에서 보냈는지 IP로 체크
  if($PGIP == "211.219.96.165" || $PGIP == "118.129.210.25" || $PGIP == "183.109.71.153" || $PGIP == "39.115.212.9")	//PG에서 보냈는지 IP로 체크
  {
	// 이니시스 NOTI 서버에서 받은 Value
	$P_TID;				// 거래번호
	$P_MID;				// 상점아이디
	$P_AUTH_DT;			// 승인일자
	$P_STATUS;			// 거래상태 (00:성공, 01:실패)
	$P_TYPE;			// 지불수단
	$P_OID;				// 상점주문번호
	$P_FN_CD1;			// 금융사코드1
	$P_FN_CD2;			// 금융사코드2
	$P_FN_NM;			// 금융사명 (은행명, 카드사명, 이통사명)
	$P_AMT;				// 거래금액
	$P_UNAME;			// 결제고객성명
	$P_RMESG1;			// 결과코드
	$P_RMESG2;			// 결과메시지
	$P_NOTI;			// 노티메시지(상점에서 올린 메시지)
	$P_AUTH_NO;			// 승인번호


	$P_TID = $_REQUEST["P_TID"];
	$P_MID = $_REQUEST["P_MID"];
	$P_AUTH_DT = $_REQUEST["P_AUTH_DT"];
	$P_STATUS = $_REQUEST["P_STATUS"];
	$P_TYPE = $_REQUEST["P_TYPE"];
	$P_OID = $_REQUEST["P_OID"];
	$P_FN_CD1 = $_REQUEST["P_FN_CD1"];
	$P_FN_CD2 = $_REQUEST["P_FN_CD2"];
	$P_FN_NM = $_REQUEST["P_FN_NM"];
	$P_AMT = $_REQUEST["P_AMT"];
	$P_UNAME = $_REQUEST["P_UNAME"];
	$P_RMESG1 = $_REQUEST["P_RMESG1"];
	$P_RMESG2 = $_REQUEST["P_RMESG2"];
	$P_NOTI = $_REQUEST["P_NOTI"];
	$P_AUTH_NO = $_REQUEST["P_AUTH_NO"];


	$value = array(
		"PageCall time" => $PageCall_time,
		"P_TID"			=> $P_TID,
		"P_MID"     => $P_MID,
		"P_AUTH_DT" => $P_AUTH_DT,
		"P_STATUS"  => $P_STATUS,
		"P_TYPE"    => $P_TYPE,
		"P_OID"     => $P_OID,
		"P_FN_CD1"  => $P_FN_CD1,
		"P_FN_CD2"  => $P_FN_CD2,
		"P_FN_NM"   => $P_FN_NM,
		"P_AMT"     => $P_AMT,
		"P_UNAME"   => $P_UNAME,
		"P_RMESG1"  => $P_RMESG1,
		"P_RMESG2"  => $P_RMESG2,
		"P_NOTI"    => $P_NOTI,
		"P_AUTH_NO" => $P_AUTH_NO
	);
	// 결제처리에 관한 로그 기록
	writeLog($value);
	// PG 입력 내용
	$SQL = "insert into tbl_order_pg_list set orderno='".$P_OID."' , pg_isdn = '".$P_TID."',pg_con='".$P_STATUS."/".$P_TYPE."' ,regdate=now(); ";
	$result = $dbcon -> query($SQL);


	//WEB 방식의 경우 가상계좌 채번 결과 무시 처리
	//(APP 방식의 경우 해당 내용을 삭제 또는 주석 처리 하시기 바랍니다.)
	// if($P_TYPE == "VBANK")	//결제수단이 가상계좌이며
	//      {
	//입금통보 "02" 가 아니면(가상계좌 채번 : 00 또는 01 경우)

			// 주문성공시 00
			if ($P_STATUS == "00") {

					if ($P_TYPE=="CARD"){$pay_name = "CARD / ".$resultMap["CARD_PurchaseName"]." / ".$resultMap["CARD_Num"]." ";$order_step = "2"; $join_status="Y";}
					if ($P_TYPE=="HPP"){$pay_name = "HPP";$order_step = "2";}
					if ($P_TYPE=="VBANK"){$pay_name = "가상계좌 / ".$resultMap["vactBankName"]." / ".$resultMap["VACT_Num"]."";$order_step = "1"; $join_status="W";}
					//===================================================
					// 주문입력
					//===================================================
					$SQL = "insert into tbl_order_list ";
					$SQL .= " ( ";
					$SQL .= " orderno,pr_name,ins_name,plan_name, agree_cd, service_name, rule_site_cd, rule_group_cd, rule_privacy_cd, ins_file_cd, service_file_cd ,s_date,s_date_time,e_date,e_date_time,ins_period,chk_p,o_name,o_email1,o_email2,purpose,join_cnt,join_nation_cd,join_nation_name,order_step,ins_amount,s_amount,cp_amount,vat_amount,t_amount,service_amount,writedate,pg_id,pg_pay_type,pg_isdn,pay_name,chk_service ";
					$SQL .= " ,sale_gubun,sale_discount,cp_cd,recommend_cd ";
					$SQL .= " ,pr_cd,ins_cd,plan_cd,service_cd ";
					$SQL .= " ) ";
					$SQL .= " select ";
					$SQL .= " orderno,pr_name,ins_name,plan_name, agree_cd, service_name, rule_site_cd, rule_group_cd, rule_privacy_cd, ins_file_cd, service_file_cd  ,s_date,s_date_time,e_date,e_date_time,ins_period,chk_p,o_name,o_email1,o_email2,purpose,join_cnt,join_nation_cd,join_nation_name,'".$order_step."',ins_amount,s_amount,cp_amount,vat_amount,t_amount,service_amount,now(),'".$mid."','".$P_TYPE."','".$P_TID."','".$pay_name."',chk_service ";
					$SQL .= " ,sale_gubun,sale_discount,cp_cd,recommend_cd ";
					$SQL .= " ,pr_cd,ins_cd,plan_cd,service_cd ";
					$SQL .= " from tbl_order_listTemp ";
					$SQL .= " where orderno ='".$orderno."' ";
					//						echo $SQL." 첫번째<br>";
					$result_ord = $dbcon -> query($SQL);

					//===================================================
					// 가입 회원정보 입력
					//===================================================
					$SQL_J = " insert into tbl_order_list_join ";
					$SQL_J .= " (orderno,gender,chk_join,chk_eng_passport,o_name,o_phone,o_isdn1,o_isdn2,o_name_en,join_amount,join_service,vat_amount,s_amount,t_amount,regdate,join_status,is_abroad_resident) ";
					$SQL_J .= " select orderno,gender,chk_join,chk_eng_passport,o_name,o_phone,o_isdn1,o_isdn2,o_name_en,join_amount,join_service,vat_amount,s_amount,t_amount,now(),'".$join_status."',is_abroad_resident ";
					$SQL_J .= " from tbl_order_list_joinTemp where orderno ='".$orderno."' ";
					//						echo $SQL_J." 두번째<br>";
					$result_J = $dbcon -> query($SQL_J);


					//===================================================
					// 질문사항 입력
					//===================================================
					$SQL_N = "insert into tbl_order_list_notice (orderno,pr_notice,pr_notice_a) ";
					$SQL_N .= " select orderno,pr_notice,pr_notice_a from tbl_order_list_noticeTemp where orderno='".$orderno."' ";
					//						echo $SQL_J." 질문사항<br>";
					$result_N = $dbcon -> query($SQL_N);

					//===================================================
					// 쿠폰 입력
					//===================================================
					$SQL_cp = " insert into tbl_order_list_coupon (orderno,coupon_name,coupon_seq,cp_sale_per,coupon_amount,writedate) ";
					$SQL_cp .= " select orderno,coupon_name,coupon_seq,cp_sale_per,coupon_amount,now() from tbl_order_list_couponTemp where orderno= '".$orderno."' ";
					$result_cp = $dbcon -> query($SQL_cp);

					//===================================================
					// 결제정보 출력
					//===================================================
					$SQL_ORDER  = " SELECT j.o_name,j.o_phone ";
					$SQL_ORDER .= " , o.o_email1, o.o_email2 ";
					$SQL_ORDER .= " , o.ins_file_cd, o.service_file_cd ";
					$SQL_ORDER .= " , o.order_step, o.s_date, o.s_date_time, o.e_date, o.e_date_time, o.chk_p ";
					$SQL_ORDER .= " , o.sale_gubun, o.sale_discount, o.cp_cd, o.recommend_cd, o.s_amount ";
					$SQL_ORDER .= " , o.pr_name, o.ins_name, o.plan_name, o.chk_service, o.purpose ";
					$SQL_ORDER .= " , o.ins_amount, o.service_amount, o.t_amount, o.plan_cd  ";
					$SQL_ORDER .= " FROM tbl_order_list_join j INNER JOIN  tbl_order_list o  ON j.orderno = o.orderno ";
					$SQL_ORDER .= " WHERE j.orderno = '".$orderno."' AND chk_join = 'N'  ";
					$result_order = $dbcon -> query($SQL_ORDER);
					$row_order = $dbcon->fetch_array($result_order);
					$s_date = $row_order["s_date"];
					$e_date = date("Y-m-d",strtotime($row_order["s_date"]." +90 days "));
					
					// 플랜검색
					$SQL_PLAN = "select * from tbl_board_plan where seq=".$row_order["plan_cd"]."";
					$RS_PLAN = $dbcon -> query($SQL_PLAN);
					$row_plan = $dbcon -> fetch_array($RS_PLAN);
					//===================================================
					// 사용 쿠폰, 추천코드 처리
					//===================================================
					if($row_order["sale_gubun"] == "C") {
						$UP_SQL_CP = " UPDATE tbl_board_coupon_history SET ";
						$UP_SQL_CP .= " orderno = '".$orderno."' , discount = '".$row_order["sale_discount"]."' ";
						$UP_SQL_CP .= " , s_amount = '".$row_order["s_amount"]."' , use_yn = 'Y' ";
						$UP_SQL_CP .= " WHERE seq = '".$row_order["cp_cd"]."' ";

						$result_upd_cp = $dbcon -> query($UP_SQL_CP);
					} else if($row_order["sale_gubun"] == "R") {

						$SQL_RECOMMEND = " SELECT recommendation_code, discount FROM tbl_board_recommend_code WHERE seq = '".$row_order["recommend_cd"]."' ";
						//echo $SQL_RECOMMEND;
						$result_recommend = $dbcon->query($SQL_RECOMMEND);
						$row_recommend = $dbcon->fetch_array($result_recommend);

						$UP_SQL_RECOMMEND = " INSERT INTO tbl_board_recommend_code_history ";
						$UP_SQL_RECOMMEND .= " (orderno, recommend_seq, recommend_name, discount, temp_discount, s_amount, writedate) ";
						$UP_SQL_RECOMMEND .= " values  ";
						$UP_SQL_RECOMMEND .= " ('".$orderno."','".$row_order["recommend_cd"]."' ,'".$row_recommend["recommendation_code"]."' ";
						$UP_SQL_RECOMMEND .= " 	,'".$row_order["sale_discount"]."','".$row_recommend["discount"]."','".$row_order["s_amount"]."',now()) ";
						//echo $UP_SQL_RECOMMEND;
						$result_ins_recommend = $dbcon -> query($UP_SQL_RECOMMEND);
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
					$RS_LOTTE = $dbcon->query($SQL_LOTTE);
					$lotte_promotion_info = $dbcon->fetch_array($RS_LOTTE);
					$log->log_write("주문코드 ".$P_STATUS. " === 롯데 등업코드 생성 === ". $orderno);

					$today = strtotime(date('Y-m-d H:i:s'));
					$promotion_start_date = strtotime($lotte_promotion_info["start_date"]);
					$promotion_end_date = strtotime($lotte_promotion_info["end_date"]);

					if ($lotte_promotion_info && $promotion_start_date <= $today && $promotion_end_date >= $today) {

						$SQL_LOTTE_USE = "UPDATE tbl_temporary_coupon set orderno = '" . $resultMap["MOID"] . "', used_date = now() where seq = '" . $lotte_promotion_info["seq"] . "' ";
						$RS_LOTTE_USE = $dbcon->query($SQL_LOTTE_USE);
						$param["promotion"] = $lotte_promotion_info["coupon_cd"];

						//===================================================
						// 롯데 등업코드 알림톡 전송
						//===================================================						
						kakaoPromotionSend($param, $mobile);
					} else {
						// if ($row_order["chk_service"] == "A" || $row_order["chk_service"] == "B" || $row_order["chk_service"] == "N") {
						// 	kakaoInsuplusJoin($param, $mobile);
						// } else {
						// 	kakaoJoin($param, $mobile);
						// }
						kakaoInsuplusJoin($param,$mobile);
					}

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
						mailJoinSend($param,$email);
					} else {
						mailJoinFlyingSend($param,$email);
					}*/


			//============================================================
			// 주문번호가 있고 입금알림인경우 02
			//============================================================
			} else if ($P_TID && $P_STATUS=="02") {
				writeLog("**** mobile noti vacct step 1 ****");
				$mo_result = 0;
				$orderno = $P_OID;
				
				//데이터 검증
				$sql_data_order_check  = " SELECT o.pay_name, o.order_step, j.o_phone FROM ";
				$sql_data_order_check .= " tbl_order_list o INNER JOIN tbl_order_list_join j ON o.orderno = j.orderno WHERE o.orderno = '".$orderno."' AND j.chk_join = 'N' ";
				$result_data_order_check = $dbcon -> query($sql_data_order_check);
				$row_data_order_check = $dbcon->fetch_array($result_data_order_check);
				
				$vbank_num = str_replace(" ","",$row_data_order_check["pay_name"]);
				$vbank_num = explode("/",$vbank_num)[2];
				$o_phone = $row_data_order_check["o_phone"];
				
				$no_vacct = $P_RMESG1;
				$no_vacct = explode("|",$no_vacct)[0];
				$no_vacct = explode("=",$no_vacct)[1];
				
				writeLog("=== vacct start result : ".$mo_result);
				
				if($vbank_num == $no_vacct && $row_data_order_check["order_step"] == "1") { //조건 추가 계좌번호 비교
					writeLog("=== vacct step 1  ===");
					
					$order_step = "2";
					$join_status = "Y";
					
					//주문내역 상태 변경
					$sql  = " UPDATE tbl_order_list SET order_step = '".$order_step."' ";
					$sql .= " WHERE orderno = '".$orderno."' ";
					
					$mo_result = $dbcon->query($sql);
					
					//가입내역 상태 변경
					$sql  = " UPDATE tbl_order_list_join SET join_status = '".$join_status."' ";
					$sql .= " WHERE orderno = '".$orderno."' ";
					
					$mo_result = $dbcon->query($sql);
					
					
					if($mo_result) {
						$sql  = " SELECT count(*) FROM tbl_board_coupon_history ";
						$sql .= " WHERE mobile = '".$o_phone."' AND receive_orderno = '".$orderno."' ";
						writeLog($sql);
						writeLog("count===".$coupon_cnt);
						$coupon_cnt = $dbcon->getCount($sql); //가입 시 받은 쿠폰여부 체크
							
						$SQL_ORDER  = " SELECT j.o_name,j.o_phone ";
						$SQL_ORDER .= " , o.o_email1, o.o_email2 ";
						$SQL_ORDER .= " , o.ins_file_cd, o.service_file_cd ";
						$SQL_ORDER .= " , o.order_step, o.s_date, o.s_date_time, o.e_date, o.e_date_time, o.chk_p ";
						$SQL_ORDER .= " , o.sale_gubun, o.sale_discount, o.cp_cd, o.recommend_cd, o.s_amount ";
						$SQL_ORDER .= " , o.pr_name, o.ins_name, o.plan_name, o.chk_service, o.purpose ";
						$SQL_ORDER .= " , o.ins_amount, o.service_amount, o.t_amount, o.pg_pay_type ";
						$SQL_ORDER .= " FROM tbl_order_list_join j INNER JOIN  tbl_order_list o  ON j.orderno = o.orderno ";
						$SQL_ORDER .= " WHERE j.orderno = '".$orderno."' AND chk_join = 'N'  ";
						$result_order = $dbcon -> query($SQL_ORDER);
						$row_order = $dbcon->fetch_array($result_order);
							
							
						if($coupon_cnt == 0 && strtolower($row_order["pg_pay_type"]) == "vbank") { //쿠폰 전달						
							$param = array();
							$mobile = all_seed_dec($row_order["o_phone"]);
							$param["name"] = all_seed_dec($row_order["o_name"]);
							//$param["pr_name"] = $row_order["pr_name"]." ".$row_order["ins_name"]." ".$row_order["plan_name"];
							$param["pr_name"] = $row_order["plan_name"];

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
							if ($row_order["chk_service"] == "C" || $row_order["chk_service"] == "D") {
								$SQL_T = "SELECT telemedicine_cd from tbl_telemedicine_cd_list where expire_date = " . $arr_period["month"] . " and join_orderno is null order by telemedi_seq ASC limit 1";

								$RS_T = $dbcon->query($SQL_T);
								$tele_cd = $dbcon->fetch_array($RS_T);
								$param["telemedic"] = $tele_cd["telemedicine_cd"];

								$log->log_write(" 원격진료코드 발행 (8) : " . $param["telemedic"]);
								$log->log_write("=== pc 결제내역 (알림톡, 이메일 생략) END === ");

								$SQL_TI = "UPDATE tbl_telemedicine_cd_list set join_orderno = '" . $resultMap["MOID"] . "', usedate = now() where telemedicine_cd = '" . $tele_cd["telemedicine_cd"] . "' ";
								$RS_TI = $dbcon->query($SQL_TI);

								//서비스명
								$SQL_CMN_CD = "select cd_nm from safety_training.fd_cmn_cd where grp_cd = 'CC13' and cd_val1 = '" . $row_order["chk_service"] . "' order by ord ASC";
								$RS_CMN_CD = $dbcon->query($SQL_CMN_CD);
								$service_nm = $dbcon->fetch_array($RS_CMN_CD);
								$param["pr_name"] .= " " . $service_nm["cd_nm"] . "타입";
								$param["service_name"] = $service_nm["cd_nm"] . "타입";
							}

							if($mo_result) {	//쿠폰발행 알림톡 전송
								//===================================================
								// 롯데 등업코드 생성
								//===================================================	
								$SQL_LOTTE = "SELECT seq, coupon_cd, start_date, end_date FROM tbl_temporary_coupon 
												where orderno is NULL and start_date <= now() and end_date >= now() order by seq asc
												limit 1";
								$RS_LOTTE = $dbcon -> query($SQL_LOTTE);
								$lotte_promotion_info = $dbcon->fetch_array($RS_LOTTE);
								$log->log_write("주문코드 ".$P_STATUS. " === 롯데 등업코드 생성 === ". $orderno);
								
								$today = strtotime(date('Y-m-d H:i:s'));
								$promotion_start_date = strtotime($lotte_promotion_info["start_date"]);
								$promotion_end_date = strtotime($lotte_promotion_info["end_date"]);

								if($lotte_promotion_info && $promotion_start_date <= $today && $promotion_end_date >= $today) {
									
									$SQL_LOTTE_USE = "UPDATE tbl_temporary_coupon set orderno = '".$orderno."', used_date = now() where seq = '".$lotte_promotion_info["seq"]."' ";
									$RS_LOTTE_USE = $dbcon -> query($SQL_LOTTE_USE);
									$param["promotion"] = $lotte_promotion_info["coupon_cd"];

									//===================================================
									// 롯데 등업코드 알림톡 전송
									//===================================================						
									kakaoPromotionSend($param,$mobile);
								} else {
									// if($row_order["chk_service"] == "A" || $row_order["chk_service"] == "B" || $row_order["chk_service"] == "N") {
									// 	kakaoInsuplusJoin($param,$mobile);
									// } else {
									// 	kakaoJoin($param,$mobile);
									// }
									kakaoInsuplusJoin($param,$mobile);
								}
								
								$param["chk_service"] = $row_order["chk_service"];
								$param["domain"] = getDomain();
								$param["ins_seq"] = $row_order["ins_file_cd"]; //약관파일 고유번호
								$param["service_seq"] = $row_order["service_file_cd"]; //약관파일 고유번호
								$email = all_seed_dec($row_order["o_email1"])."@".all_seed_dec($row_order["o_email2"]);

								mailJoinSend($param, $email); // 서비스 상관없이 인슈플러스 양식으로 발송 20230303

								writeLog("=== vacct end result ===");
					
								echo "OK";
								return;				
									
							} else {
								echo "FAIL";
								return;
							}
						}
					}
				}
			// 실패인경우 01
			} else {
				$err_msg = array('ERR_MSG' => "TID가 전달되지 않았습니다.");
				writeLog($err_msg);
				$resultMSG = "FAIL";    // TID가 전달되지 않았습니다.
			}

			echo $resultMSG;                        // 절대로 지우지마세요
			return;



  		$PageCall_time = date("H:i:s");

		$value = array(
				"PageCall time" => $PageCall_time,
				"P_TID"			=> $P_TID,
				"P_MID"     => $P_MID,
				"P_AUTH_DT" => $P_AUTH_DT,
				"P_STATUS"  => $P_STATUS,
				"P_TYPE"    => $P_TYPE,
				"P_OID"     => $P_OID,
				"P_FN_CD1"  => $P_FN_CD1,
				"P_FN_CD2"  => $P_FN_CD2,
				"P_FN_NM"   => $P_FN_NM,
				"P_AMT"     => $P_AMT,
				"P_UNAME"   => $P_UNAME,
				"P_RMESG1"  => $P_RMESG1,
				"P_RMESG2"  => $P_RMESG2,
				"P_NOTI"    => $P_NOTI,
				"P_AUTH_NO" => $P_AUTH_NO
				);


 			// 결제처리에 관한 로그 기록
 		writeLog($value);


		/***********************************************************************************
		 ' 위에서 상점 데이터베이스에 등록 성공유무에 따라서 성공시에는 "OK"를 이니시스로 실패시는 "FAIL" 을
		 ' 리턴하셔야합니다. 아래 조건에 데이터베이스 성공시 받는 FLAG 변수를 넣으세요
		 ' (주의) OK를 리턴하지 않으시면 이니시스 지불 서버는 "OK"를 수신할때까지 계속 재전송을 시도합니다
		 ' 기타 다른 형태의 echo "" 는 하지 않으시기 바랍니다
		'***********************************************************************************/

		// if(데이터베이스 등록 성공 유무 조건변수 = true)
// 결제 결과 정보입력


  }else{
	echo "FAIL";
  }

function writeLog($msg)
{
    $file = "noti_input_".date("Ymd")."_vbank.log";

    if(!($fp = fopen($_SERVER["DOCUMENT_ROOT"]."/log/pg/mobile/".$file, "a+"))) return 0;

    ob_start();
    print_r($msg);
    $ob_msg = ob_get_contents();
    ob_clean();

    if(fwrite($fp, " ".$ob_msg."\n") === FALSE)
    {
        fclose($fp);
        return 0;
    }
    fclose($fp);
    return 1;
}


?>

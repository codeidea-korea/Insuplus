<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.mail.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	
	$log = new log();
	$result = 0;
	
	if($_POST["mode"] == "change") {
		
		//주문내역 상태 변경
		$sql  = " UPDATE tbl_order_list SET order_step = '".$order_step."'";
		$sql .= " WHERE orderno = '".$orderno."' ";
		
		$result = $dbcon->query($sql);
		
		$join_status = "";
		if($order_step == "1") {
			$join_status = "W";
		} else if($order_step == "2") {
			$join_status = "Y";
		} else if($order_step == "N") {
			$join_status = "N";
		} else if($order_step == "R") {
			$join_status = "R";
		}
		
		//가입내역 상태 변경
		$sql  = " UPDATE tbl_order_list_join SET join_status = '".$join_status."' ";
		$sql .= " WHERE orderno = '".$orderno."' ";

		$result = $dbcon->query($sql);
		
		if($order_step == "2") {
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
			$SQL_ORDER .= " , o.ins_amount, o.service_amount, o.t_amount, o.pg_pay_type ";
			$SQL_ORDER .= " FROM tbl_order_list_join j INNER JOIN  tbl_order_list o  ON j.orderno = o.orderno ";
			$SQL_ORDER .= " WHERE j.orderno = '".$orderno."' AND chk_join = 'N'  ";
			$result_order = $dbcon -> query($SQL_ORDER);
			$row_order = $dbcon->fetch_array($result_order);
			
			
			if($coupon_cnt == 0 && strtolower($row_order["pg_pay_type"]) == "vbank") { //쿠폰 전달

			//감사쿠폰 발행 안함 필요시 주석 해제
			/*
				$s_coupon_date = $row_order["s_date"];
				$e_coupon_date = date("Y-m-d",strtotime($row_order["s_date"]." +90 days "));
				
				$SQL_EVENT  = " SELECT seq,discount,coupon_name FROM tbl_board_event WHERE event_type='C' AND event_partnership_code = 'insuplus' ";
				$SQL_EVENT .= " ORDER BY seq desc LIMIT 0,1 ";
				$result_event = $dbcon -> query($SQL_EVENT);
				$row_event = $dbcon->fetch_array($result_event);
				
				$INS_SQL_CP  =  " INSERT INTO tbl_board_coupon_history ";
				$INS_SQL_CP .= " (receive_orderno, event_seq, start_date, end_date, mobile ";
				$INS_SQL_CP .= " , ori_mobile, temp_discount, use_yn, writedate) ";
				$INS_SQL_CP .= "  VALUES ";
				$INS_SQL_CP .= " ('".$orderno."', '".$row_event["seq"]."', '".$s_coupon_date."', '".$e_coupon_date."','".$o_phone."' ";
				$INS_SQL_CP .= " ,'".$o_phone."', '".$row_event["discount"]."','N', now()) ";					
				$result_cp = $dbcon -> query($INS_SQL_CP);
				*/
				//서비스가 인슈플러스인 경우 원격진료코드 조회
				if ($row_order["chk_service"] == "C" || $row_order["chk_service"] == "D") {
					$SQL_TEL = "select * from tbl_telemedicine_cd_list where join_orderno = '" . $orderno . "'";
					$RS_TEL = $dbcon->query($SQL_TEL);
					$tele_cd = $dbcon->fetch_array($RS_TEL);

					//서비스명
					$SQL_CMN_CD = "select cd_nm from safety_training.fd_cmn_cd where grp_cd = 'CC13' and cd_val1 = '" . $row_order["chk_service"] . "' order by ord ASC";
					$RS_CMN_CD = $dbcon->query($SQL_CMN_CD);
					$service_nm = $dbcon->fetch_array($RS_CMN_CD);
				}
				//if($result_cp) { //쿠폰발행 알림톡 전송 <--쿠폰 발행 필요시 밑에 주석 처리 후 주석 해제하여 사용
				if(1==1){ //가상계좌 이메일 알림톡 확인 용도로 변경
					$param["name"] = all_seed_dec($row_order["o_name"]);
					// $param["pr_name"] = $row_order["pr_name"] . " " . $row_order["ins_name"] . " " . $row_order["plan_name"];
                    $param["pr_name"] = $row_order["ins_name"] . " " . $row_order["plan_name"];
					if ($row_order["chk_service"] == "C" || $row_order["chk_service"] == "D") {
						$param["pr_name"] .= " " . $service_nm["cd_nm"] . "타입";
						$param["service_name"] = $service_nm["cd_nm"] . "타입";
						$param["telemedic"] = count($tele_cd) > 0 ? $tele_cd["telemedicine_cd"] : "-";
					}

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

					if ($row_order["chk_service"] == "A" || $row_order["chk_service"] == "B" || $row_order["chk_service"] == "N") {
						kakaoInsuplusJoin($param, all_seed_dec($o_phone));
					} else {
						kakaoJoin($param, all_seed_dec($o_phone));
					}
				
					$param["chk_service"] = $row_order["chk_service"];
					$param["domain"] = getDomain();
					$param["ins_seq"] = $row_order["ins_file_cd"]; //약관파일 고유번호
					$param["service_seq"] = $row_order["service_file_cd"]; //약관파일 고유번호
					$email = all_seed_dec($row_order["o_email1"])."@".all_seed_dec($row_order["o_email2"]);
					
					mailJoinSend($param,$email);
					
					/* //쿠폰발행 사용시 주석 해제
					$param["discount"] = $row_event["discount"];
					$param["discount_txt"] = $param["discount"]."% (할인 최대한도 30,000원)";
					$param["coupon_name"] = $row_event["coupon_name"];
					$param["coupon_period"] = $s_coupon_date." ~ ".$e_coupon_date;

					kakaoJoinCoupon($param,all_seed_dec($o_phone));
					*/
					$result = 1;
				}
			}
		} else if($order_step == "R") {
			//주문내역 상태 변경
			$sql  = " UPDATE tbl_order_list SET cancle_date = now() ";
			$sql .= " WHERE orderno = '".$orderno."' ";
			$result = $dbcon->query($sql);

			//가입내역 상태 변경
			$sql  = " UPDATE tbl_order_list_join SET cancle_date = now() ";
			$sql .= " WHERE orderno = '".$orderno."' ";
		} else if ($order_step == "N") {
			//결제취소로 변경시 취소일 수정
			$sql  = " UPDATE tbl_order_list SET cancle_date = now() ";
			$sql .= " WHERE orderno = '" . $orderno . "' ";
			$result = $dbcon->query($sql);
		}
		
		$dbcon -> dbcon_close();
	}	
?>
{"result":"<?=$result;?>"}

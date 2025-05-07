<?php
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$log = new log();
	
	if($mode == "cancel") {
		// 가입보험내역 검색
		$SQL_V  = " select * ";
		$SQL_V .= " ,( SELECT o_phone FROM tbl_order_list_join WHERE orderno = o.orderno AND chk_join = 'N' ) as o_phone ";
		$SQL_V .= " from tbl_order_list o where orderno in (select orderno from tbl_order_list_join where orderno='".$orderno."' and chk_join='N')";
		//echo $SQL_V;
		$RS_V = $dbcon -> query($SQL_V);
		if (!$RS_V){
			echo "<script>alert('해당 보험내역이 없습니다.');</script>";
			exit;
		}
		$row_r = $dbcon -> fetch_array($RS_V);
 
		$secretKey = TOSS_SECRET_KEY; // 토스페이먼츠 콘솔에서 발급받은 시크릿 키
		$paymentKey = $row_r['pg_isdn']; // 취소할 결제의 paymentKey
		$cancelReason = "고객주문 취소".date("His"); // 취소 사유
		
			
		$data = [
			'cancelReason' => $cancelReason,
		];


		$payload = json_encode($data);
		$url = "https://api.tosspayments.com/v1/payments/{$paymentKey}/cancel";
		$authorization = base64_encode($secretKey . ':');

		$headers = [
			"Authorization: Basic " . $authorization,
			"Content-Type: application/json",
			"Idempotency-Key: " . uniqid("cancel_", true)
		];

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

		$log->log_write("Response Code: " . $httpCode);
		$log->log_write("Response Body: " . $response);

		$SQL = "insert into tbl_order_pg_list set";
		$SQL .= " orderno = '".$orderno."' ";
		$SQL .= " ,gubun = '전체취소' ";
		$SQL .= " ,pg_isdn = '".$paymentKey."' ";
		$SQL .= " ,pg_con = '".$httpCode."' ";
		$SQL .= " ,regdate = now() ";

		$RS = $dbcon -> query($SQL);

		
		$SQL = "insert into tbl_order_pg_list set";
		$SQL .= " orderno = '".$orderno."' ";
		$SQL .= " ,gubun = '전체취소(관리자)' ";
		$SQL .= " ,pg_isdn = '".$paymentKey."' ";
		$SQL .= " ,pg_con = '".$httpCode."' ";
		$SQL .= " ,regdate = now() ";

		$log->log_write("SQL1=".$SQL);
		$RS = $dbcon -> query($SQL);
	
		if($httpCode == 200) {

			$cancle_date = date("Y-m-d H:i:s"); //변수명 주의
			
			//주문취소
			$SQL  = " UPDATE tbl_order_list SET order_step='N',cancle_date= '".$cancle_date."' ";
			$SQL .= " ,cancle_vat_amount= vat_amount-cancle_vat_amount ";
			$SQL .= " ,cancle_amount= t_amount-cancle_amount ";
			$SQL .= " ,vat_amount = vat_amount-cancle_vat_amount ";
			$SQL .= " ,t_amount = t_amount-cancle_amount ";
			$SQL .= " where orderno='".$orderno."' ";
			$log->log_write("SQL2=".$SQL);
			$RS_C1 = $dbcon -> query($SQL);
			
			// 가입원취소
			$SQL  = " update tbl_order_list_join set  join_status='N',cancle_date= '".$cancle_date."' ";
			$SQL .= " ,cancle_vat_amount = vat_amount-cancle_vat_amount ";
			$SQL .= " ,cancle_amount = t_amount-cancle_amount ";
			$SQL .= " ,vat_amount = vat_amount-cancle_vat_amount ";
			$SQL .= " ,t_amount = t_amount-cancle_amount ";
			$SQL .= " where orderno='".$orderno."' ";
			$log->log_write("SQL3=".$SQL);
			$RS_C1 = $dbcon -> query($SQL);
			
			//쿠폰이 있는 경우 돌려준다.
			//추천코드는 무한입력이라 안 돌려줘도 된다.
			if($row_r["sale_gubun"] == "C") {
				$SQL  = " UPDATE tbl_board_coupon_history SET ";
				$SQL .= " orderno = '', discount = 0, s_amount = 0, use_yn = 'N' ";
				if($row_r["new_cp_cd"]){
					$SQL .= " WHERE seq in (".$row_r["new_cp_cd"].") ";
				} else {
					$SQL .= " WHERE seq = '".$row_r["cp_cd"]."' ";
				}
				
				$RS_C1 = $dbcon -> query($SQL);
			}
			
			//가입 시 받은 쿠폰은 사용전 이면 삭제한다.
			$SQL = " DELETE FROM tbl_board_coupon_history WHERE receive_orderno = '".$orderno."' AND use_yn='N' ";
			$dbcon -> query($SQL);
			
			
			//취소 알림톡 전송
			$param = array();
			$param["name"] = all_seed_dec($row_r["o_name"]);
			// $param["pr_name"] = $row_r["pr_name"]." ".$row_r["ins_name"]." ".$row_r["plan_name"];
			$param["pr_name"] = $row_r["plan_name"];
			if($row_r["chk_service"] == "A" || $row_r["chk_service"]) {
				$param["pr_name"] .= " ".$Arr_txt_plus[$row_r["chk_service"]];
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
			$param["cancle_amount"] = number_format($row_r["t_amount"])."원";
			$mobile = all_seed_dec($row_r["o_phone"]);
			kakaoJoinCancel($param,$mobile);
			
			echo "<script>alert('결제 취소요청이 성공하였습니다.');opener.document.location.reload();;window.close();</script>";
		}else{
			$errorData = json_decode($response, true);
		
			echo "<script>alert('결제 취소요청이 실패하였습니다.\\n사유:".iconv("EUC-KR","UTF-8",$errorData['message'])."\\n같은 반복되는 경우 관리자에게 문의하세요.');</script>";
		}
	}
?>
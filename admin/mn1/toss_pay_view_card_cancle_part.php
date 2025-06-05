<?php
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	if($mode == "repay") {
		// 가입보험내역 검색
		$SQL_V = "select * ";
		$SQL_V .= ", (SELECT o_phone FROM tbl_order_list_join WHERE chk_join='N' AND orderno = o.orderno) as o_phone ";
		$SQL_V .= " from tbl_order_list o where orderno in (select orderno from tbl_order_list_join where seq=".$join_seq." and orderno='".$orderno."' )";
		echo $SQL_V;
		$RS_V = $dbcon -> query($SQL_V);
		if (!$RS_V){
			echo "<script>alert('해당 보험내역이 없습니다.');</script>";
			exit;
		}
		$row_r = $dbcon -> fetch_array($RS_V);
		
		
		// 가입자 본인내역 검색
		$SQL = "select * from tbl_order_list_join where seq=".$join_seq." AND orderno='".$orderno."'  AND join_status = 'Y' ";
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
		
	
	
	
$secretKey = TOSS_SECRET_KEY; // 토스페이먼츠 콘솔에서 발급받은 시크릿 키
$paymentKey = $tid; // 취소할 결제의 paymentKey
$cancelReason = "고객주문 취소".date("His"); // 취소 사유

$data = [
	'cancelReason' => $cancelReason,
    'cancelAmount'=>$cancle_amount

];


if (!empty($cancelAmount)) {
    $data['cancelAmount'] = $cancelAmount;
}

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

	$cancelRequestCount = $response['cancels'].count(); // 취소 요청 횟수

	$balanceAmount = $response['balanceAmount']; // 잔여 결제 금액
	$cancelAmount = $response['cancelAmount']; // 취소 금액
	$lastTransactionKey = $response['lastTransactionKey']; // 마지막 거래 키

	$SQL = "insert into tbl_order_pg_list set";
	$SQL .= " orderno = '".$orderno."' ";
	$SQL .= " ,gubun = '부분취소(관리자)' ";
	$SQL .= " ,pg_isdn = '".$paymentKey."' ";
	$SQL .= " ,pg_con = '".$httpCode
			."/ 최종결제 금액 : ".$balanceAmount
			." / 원거래번호: ".$paymentKey
			." / 부분취소금액 : ".$cancle_amount
			." / 부분취소(재승인) 요청횟수 : ".$cancelRequestCount
			." / 마지막거래 키키 : ".$lastTransactionKey." ' ";
	$SQL .= " ,regdate = now() ";

		$RS = $dbcon -> query($SQL);
	
		if($httpCode == 200) {
		 	
		 	// 가입원취소
			$SQL  = " UPDATE tbl_order_list_join SET  join_status='N', cancle_date= '".$cancle_date."' , cancle_amount=".$cancle_amount;
			$SQL .= " ,cancle_vat_amount = ".$cancle_vat_amount.", t_amount = t_amount - ".$cancle_amount." , vat_amount= vat_amount - ".$cancle_vat_amount;
			$SQL .= " WHERE seq='".$join_seq."' ";
			echo $SQL;
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
				if($row_r["new_cp_cd"]){
					$SQL .= " WHERE seq in (".$row_r["new_cp_cd"].") ";
				} else {
					$SQL .= " WHERE seq = '".$row_r["cp_cd"]."' ";
				}
					
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
			
			echo "<script>alert('결제 취소요청이 성공하였습니다.');opener.document.location.reload();window.close();</script>";
		}else{
			echo "<script>alert('결제 취소요청이 실패하였습니다. 같은 반복되는 경우 관리자에게 문의하세요.');</script>";
		}
	}
?>
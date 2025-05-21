<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php";

function my_print_r($thing,$description=false){
	echo '<pre style="background:#fff; padding:10px; color:#111; font-family:monospace; font-size:12px; border:1px solid #555">';
	if($description) echo '<strong>'.$description.'</strong><br><br>';
	print_r($thing);
	echo '</pre>';
}

//주문취소 - 결제취소
error_reporting(E_ALL);
ini_set("display_errors", 0);


// 가입가입내역 검색
$SQL_V = "select * from tbl_order_list where orderno in (select orderno from tbl_order_list_join where o_phone = '".$_SESSION["enc_hp"]."' and o_name='".$_SESSION["enc_nm"]."' and orderno='".$orderno."' and chk_join='N')";
//echo $SQL_V;
$RS_V = $dbcon -> query($SQL_V);
if (!$RS_V){
	echo "<script>alert('해당 가입내역이 없습니다.');</script>";
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

if($httpCode == 200) {

	
		$cancle_date = date("Y-m-d H:i:s"); //변수명 주의
		
		
		$SQL_ORDER  = " SELECT o.orderno, o.sale_gubun, o.cp_cd, o.new_cp_cd, o.s_date, o.s_date_time, o.e_date, o.e_date_time, o.chk_p ";
		$SQL_ORDER .= ", o.pr_name, o.ins_name, o.plan_name, o.chk_service, o.t_amount, o.cancle_amount ";
		$SQL_ORDER .=" , j.o_name, j.o_phone FROM tbl_order_list o INNER JOIN tbl_order_list_join j ON o.orderno = j.orderno ";
		$SQL_ORDER .= " WHERE o.orderno = '".$orderno."' AND o.order_step='2' AND j.chk_join = 'N' ";
		$rs = $dbcon->query($SQL_ORDER);
		
		$row = $dbcon->fetch_array($rs);
		
		if($row["orderno"]) {
			
			//주문취소
			$SQL  = " update tbl_order_list set  order_step='N',cancle_date= '".$cancle_date."' ";
			$SQL .= " ,cancle_vat_amount= vat_amount-cancle_vat_amount ";
			$SQL .= " ,cancle_amount= t_amount-cancle_amount ";
			$SQL .= " ,vat_amount = vat_amount-cancle_vat_amount ";
			$SQL .= " ,t_amount = t_amount-cancle_amount ";
			$SQL .= " where orderno='".$orderno."' ";
			
			$RS_C1 = $dbcon -> query($SQL);
			
			// 가입정보 취소
			$SQL  = " update tbl_order_list_join set  join_status='N',cancle_date= '".$cancle_date."' ";
			$SQL .= " ,cancle_vat_amount = vat_amount-cancle_vat_amount ";
			$SQL .= " ,cancle_amount = t_amount-cancle_amount ";
			$SQL .= " ,vat_amount = vat_amount-cancle_vat_amount ";
			$SQL .= " ,t_amount = t_amount-cancle_amount ";
			$SQL .= " where orderno='".$orderno."' ";

			$RS_C1 = $dbcon -> query($SQL);
			
			//쿠폰이 있는 경우 돌려준다.
			//추천코드는 무한입력이라 안 돌려줘도 된다.
			if($row["sale_gubun"] == "C") {
				$SQL  = " UPDATE tbl_board_coupon_history SET ";
				$SQL .= " orderno = '', discount = 0, s_amount = 0, use_yn = 'N' ";
				if($row["new_cp_cd"]) {
					$SQL .= " WHERE seq in (".$row["new_cp_cd"].") ";
				} else {
					$SQL .= " WHERE seq = '".$row["cp_cd"]."' ";
				}

				$RS_C1 = $dbcon -> query($SQL);
			} 
			
			//가입 시 받은 쿠폰은 사용전 이면 삭제한다.
			$SQL = " DELETE FROM tbl_board_coupon_history WHERE receive_orderno = '".$orderno."' AND use_yn='N' ";
			$dbcon -> query($SQL);

			//취소 알림톡 전송
			$param = array();
			$param["name"] = all_seed_dec($row["o_name"]);
			// $param["pr_name"] = $row["pr_name"]." ".$row["ins_name"]." ".$row["plan_name"];
			$param["pr_name"] = $row["plan_name"];
			if($row["chk_service"] == "A" || $row["chk_service"]) {
				$param["pr_name"] .= $Arr_txt_plus[$row["chk_service"]];
			}
			if($row["chk_p"] == "Y") { //단기
				$s_date = $row["s_date"]." ".$row["s_date_time"]."시";
				$e_date = $row["e_date"]." ".$row["e_date_time"]."시";
			} else if($row["chk_p"] == "N") { //장기
				$s_date = $row["s_date"];
				$e_date = $row["e_date"];
			}
			$param["period"] = $s_date." ~ ".$e_date;
			$param["cancle_date"] = $cancle_date;
			$param["cancle_amount"] = number_format($row["t_amount"])."원";
			$mobile = all_seed_dec($row["o_phone"]);
			kakaoJoinCancel($param,$mobile);
		}
		
		echo "<script>alert('결제 취소요청이 성공하였습니다.');parent.document.location.reload();</script>";
	}else{
		$errorData = json_decode($response, true);
		
			echo "<script>alert('결제 취소요청이 실패하였습니다.\\n사유:".iconv("EUC-KR","UTF-8",$errorData['message'])."\\n같은 반복되는 경우 관리자에게 문의하세요.');</script>";
	}
?>
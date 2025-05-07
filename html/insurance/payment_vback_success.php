<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.mail.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php";

$log = new log();
$rawPostData = file_get_contents("php://input");
$log->log_write("Toss VA Deposit Callback Start");
$log->log_write($rawPostData);

$data = json_decode($rawPostData, true);

$log->log_write("Toss VA Deposit Callback Data: " . print_r($data, true));

if (!$data) {
    $log->log_write("Invalid JSON payload");
    http_response_code(400);
    echo "INVALID JSON";
    exit;
}

if ($data["status"] !== "DONE") {
    $log->log_write("Not a completed virtual account payment");
    http_response_code(400);
    echo "NOT A VALID PAYMENT";
    exit;
}else if ($data["status"] === 'DONE') {
  
$orderId = $data["orderId"];
$virtualAccount = '';
$accountNumber = ''; // 가상계좌 번호
$bankCode = '';          // 은행 코드
$bankName = '';          // 은행 이름

  // 토스페이먼츠 API로 결제 상세 정보 조회
  $secretKey = TOSS_SECRET_KEY;
  $url = "https://api.tosspayments.com/v1/payments/orders/{$orderId}";
  
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'Authorization: Basic ' . base64_encode($secretKey . ':'),
      'Content-Type: application/json'
  ]);
  
  $response = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);
  
  if ($httpCode == 200) {
      $paymentData = json_decode($response, true);
      
      $log->log_write("Payment Data: " . print_r($paymentData, true));
      // 가상계좌 정보 추출
      if (isset($paymentData['method']) && $paymentData['method'] === '가상계좌') {
          $virtualAccount = $paymentData['virtualAccount'];
          $accountNumber = $virtualAccount['accountNumber']; // 가상계좌 번호
          $bankCode = $virtualAccount['bankCode'];          // 은행 코드
          $bankName = $virtualAccount['bankName'];          // 은행 이름
     
      }
  }




// 주문 정보 조회
$SQL_ORDER  = "SELECT j.o_name, j.o_phone, o.o_email1, o.o_email2, o.plan_name, o.purpose, ";
$SQL_ORDER .= "o.ins_amount, o.service_amount, o.t_amount, o.pg_pay_type, o.pay_name, o.order_step, ";
$SQL_ORDER .= "o.s_date, o.s_date_time, o.e_date, o.e_date_time, o.chk_p, o.chk_service, ";
$SQL_ORDER .= "o.ins_file_cd, o.service_file_cd, o.plan_cd, j.regdate ";
$SQL_ORDER .= "FROM tbl_order_list_join j INNER JOIN tbl_order_list o ON j.orderno = o.orderno ";
$SQL_ORDER .= "WHERE o.orderno = '".$orderId."' AND j.chk_join = 'N'";
$result_order = $dbcon->query($SQL_ORDER);
$row_order = $dbcon->fetch_array($result_order);

if (!$row_order) {
    $log->log_write("주문 정보 없음: " . $orderId);
    http_response_code(404);
    echo "ORDER NOT FOUND";
    exit;
}

$payName = all_seed_dec($row_order["pay_name"]);
$expectedAccount = explode("/", str_replace(" ", "", $payName))[2];
$orderStep = $row_order["order_step"];

if ($expectedAccount !== $accountNumber) {
    $log->log_write("계좌번호 불일치: expected=" . $expectedAccount . " / received=" . $accountNumber);
    http_response_code(400);
    echo "ACCOUNT MISMATCH";
    exit;
}

if ($orderStep != "1") {
    $log->log_write("이미 처리된 주문입니다.");
    http_response_code(200);
    echo "ALREADY DONE";
    exit;
}

// 주문 상태 업데이트
$dbcon->query("UPDATE tbl_order_list SET order_step = '2' WHERE orderno = '" . $orderId . "'");
$dbcon->query("UPDATE tbl_order_list_join SET join_status = 'Y' WHERE orderno = '" . $orderId . "'");

$param = array();
$mobile = all_seed_dec($row_order["o_phone"]);
$param["name"] = all_seed_dec($row_order["o_name"]);
$param["plan_name"] = $row_order["plan_name"];
$param["pr_name"] = $row_order["plan_name"];
$param["purpose"] = $row_order["purpose"];
$param["ins_amount"] = $row_order["ins_amount"];
$param["amount"] = $row_order["ins_amount"] + $row_order["service_amount"];
$param["t_amount"] = $row_order["t_amount"];
$param["payMethod"] = $row_order["pg_pay_type"];
$param["pay_name"] = $row_order["pay_name"];
$param["domain"] = getDomain();
$param["chk_service"] = $row_order["chk_service"];

// 기간 계산
if ($row_order["chk_p"] == "Y") {
    $s_date = $row_order["s_date"] . " " . $row_order["s_date_time"];
    $e_date = $row_order["e_date"] . " " . $row_order["e_date_time"];
    $param["period"] = $s_date . "시 ~ " . $e_date . "시";
} else {
    $s_date = $row_order["s_date"];
    $e_date = $row_order["e_date"];
    $param["period"] = $s_date . " ~ " . $e_date;
}
$arr_period = getArrPeriod($s_date, $e_date, $row_order["chk_p"]);
$param["period"] .= " (" . $arr_period["day"] . "일)";

// 플랜 약관 정보
$SQL_PLAN = "SELECT ins_term1_seq, ins_term2_seq FROM tbl_board_plan WHERE seq = " . $row_order["plan_cd"];
$RS_PLAN = $dbcon->query($SQL_PLAN);
$row_plan = $dbcon->fetch_array($RS_PLAN);
$param["ins_seq"] = $row_order["ins_file_cd"];
$param["ins_term1"] = $row_plan["ins_term1_seq"];
$param["ins_term2"] = $row_plan["ins_term2_seq"];
$param["service_seq"] = $row_order["service_file_cd"];

// 쿠폰 구성
$s_coupon_date = date("Y-m-d", strtotime($row_order["regdate"]));
$e_coupon_date = date("Y-m-d", strtotime($s_coupon_date . " +180 days"));
$param["discount"] = "10%";
$param["discount_txt"] = "10% (할인 최대한도 30,000원)";
$param["coupon_name"] = "가입 감사 쿠폰";
$param["coupon_period"] = $s_coupon_date . " ~ " . $e_coupon_date;
 
// 알림톡 & 이메일 발송
kakaoInsuplusJoin($param, $mobile);
kakaoJoinCoupon($param, $mobile);
$email = all_seed_dec($row_order["o_email1"]) . "@" . all_seed_dec($row_order["o_email2"]);
mailJoinSend($param, $email);

$log->log_write("입금 완료 처리 완료: " . $orderId);
http_response_code(200);
echo "OK";

$dbcon->dbcon_close();
}
?>

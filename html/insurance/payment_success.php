<!DOCTYPE html>
<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/Func.insurance.php"; //추가
include $_SERVER["DOCUMENT_ROOT"] . "/_config/Mobile_Detect.php";

$detect = new Mobile_Detect;

function my_print_r($thing,$description=false){
	echo '<pre style="background:#fff; padding:10px; color:#111; font-family:monospace; font-size:12px; border:1px solid #555">';
	if($description) echo '<strong>'.$description.'</strong><br><br>';
	print_r($thing);
	echo '</pre>';
}




// 결제 정보 및 API 키 설정
$paymentKey = $_GET['paymentKey'];  // 결제 키
$orderId = $_GET['orderId'];        // 주문 번호
$amount = $_GET['amount'];          // 결제 금액
$secretKey  ='test_sk_DnyRpQWGrNwMx0NRge7L8Kwv1M9E';


$sql = "SELECT * FROM tbl_toss_temp_orders WHERE order_id = '$orderId'";

$result = $dbcon->query($sql);
$row = $dbcon -> fetch_array($result);

$curl = curl_init();

curl_setopt_array($curl, [
  CURLOPT_URL => "https://api.tosspayments.com/v1/payments/$paymentKey",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "Authorization: Basic " . base64_encode($secretKey . ":"),
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);
$responseJson = json_decode($response, true);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
    echo "<h4>TOSS Response Data</h4>";
    my_print_r($responseJson);
    echo "<h4>가입자 임시데이터</h4>";
    my_print_r($row);
}
?>

  
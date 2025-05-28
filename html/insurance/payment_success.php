<!DOCTYPE html>
<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.mail.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php";

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
$secretKey  = TOSS_SECRET_KEY; // 비밀 키
$mid = TOSS_MID;
// 승인 요청
$url = 'https://api.tosspayments.com/v1/payments/confirm';

$data = [
    'paymentKey' => $paymentKey,
    'orderId' => $orderId,
    'amount' => (int) $amount
];

$payload = json_encode($data);
$authorization = base64_encode($secretKey . ':');

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Basic ' . $authorization,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);


// my_print_r($response);
// exit;

// 결과 처리
if ($httpCode === 200) {
    $resultMap = json_decode($response, true);
    
    //결제수단
    $payMethod ='';
    switch ( $resultMap["method"]) {
        case '카드':
            $payMethod = 'Card';
            break;
        case '간편결제':
            $payMethod = 'Card';
            break;
        case '가상계좌':
            $payMethod = 'VBank';
            break;
        case '휴대폰':
            $payMethod = 'HPP';
            break;
        case '계좌이체':
            $payMethod = 'transfer';
            break;
    }
   
    $accountNumber= '';
    $bankCode = '';     // 은행 코드
    $bankName = '';     // 은행 이름


   if($payMethod == "VBank"){
    $virtualAccount = $resultMap['virtualAccount'];
    $accountNumber = $virtualAccount['accountNumber']; // 가상계좌 번호
    $bankCode = $virtualAccount['bankCode'];          // 은행 코드
    $bankName = $virtualAccount['bankName'] ?? $pg_bank_gubun[$bankCode];          // 은행 이름

   }
   if($payMethod == "transfer"){
    // $accountNumber = $resultMap['transfer']['accountNumber']; // 계좌 번호
    $bankCode = $resultMap['transfer']['bankCode'];          // 은행 코드
    $bankName = $pg_bank_gubun[$bankCode];          // 은행 이름
   }


    //결제카드
    $cardNumber = $resultMap["card"]["number"] ?? '';
    $cardCompany = $toss_pg_card_gubun[$resultMap["card"]["issuerCode"]] ?? '';

    //주문번호
    $orderno = $resultMap["orderId"] ?? '';

    //결제 고유 키
    $tid = $resultMap["paymentKey"] ?? '';

    try {
     
    if ($payMethod=="Card" || $payMethod=="VCard") {
        
        $pay_name = "CARD / ".$cardCompany." / ".$cardNumber." ";
        $order_step = "2";
        $join_status="Y";
    }

      if ($payMethod=="HPP" || $payMethod=="MOBILE"){$pay_name = "HPP";$order_step = "2";$join_status="Y";}
      if ($payMethod=="VBank"){$pay_name = "가상계좌 / ".$bankName." / ".$accountNumber."";$order_step = "1"; $join_status="W";}
      if ($payMethod=="transfer"){$pay_name = "실시간계좌이체 / ".$bankName;$order_step = "2"; $join_status="Y";}
      $log->log_write("=== pay_name === ");
      $log->log_write($pay_name); 

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
     $SQL .= " orderno,pr_name,ins_name,plan_name, agree_cd, service_name, rule_site_cd, rule_group_cd, rule_privacy_cd, ins_file_cd, service_file_cd  ,s_date,s_date_time,e_date,e_date_time,ins_period,chk_p,o_name,o_email1,o_email2,purpose,join_cnt,join_ch,join_nation_cd,join_nation_name,'".$order_step."',ins_amount,s_amount,cp_amount,vat_amount,t_amount,service_amount,now(),'".$mid."','".$payMethod."','".$tid."','".all_seed_enc($pay_name)."',chk_service ";
     $SQL .= " ,sale_gubun,sale_discount,new_cp_cd,recommend_cd ";
     $SQL .= " ,pr_cd,ins_cd,plan_cd,service_cd ";
     $SQL .= " from tbl_order_listTemp ";
     $SQL .= " where orderno ='".$orderno."' "; 
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
     $SQL_J .= " from tbl_order_list_joinTemp where orderno ='".$orderno."' ";
     
     $log->log_write("가입내역등록(2) : ".$SQL_J);
     
     $result_J = $dbcon -> query($SQL_J);


     //===================================================
     // 질문사항 입력
     //===================================================
     $SQL_N = "insert into tbl_order_list_notice (orderno,pr_notice,pr_notice_a) ";
     $SQL_N .= " select orderno,pr_notice,pr_notice_a from tbl_order_list_noticeTemp where orderno='".$orderno."' ";
     
     $log->log_write("질문사항 등록(3) : ".$SQL_N);
//						echo $SQL_J." 질문사항<br>";
     $result_N = $dbcon -> query($SQL_N);

     //===================================================
     // 쿠폰 입력
     //===================================================
     $SQL_cp = " insert into tbl_order_list_coupon (orderno,coupon_name,new_cp_cd,cp_sale_per,coupon_amount,writedate) ";
     $SQL_cp .= " select orderno,coupon_name,new_cp_cd,cp_sale_per,coupon_amount,now() from tbl_order_list_couponTemp where orderno= '".$orderno."' ";
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
     $SQL_ORDER .= " WHERE j.orderno = '".$orderno."' AND chk_join = 'N'  ";
     
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
       $UP_SQL_CP .= " orderno = '".$orderno."' , discount = '".$row_order["sale_discount"]."' ";
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
       $UP_SQL_RECOMMEND .= " ('".$orderno."','".$row_order["recommend_cd"]."' ,'".$row_recommend["recommendation_code"]."' ";
       $UP_SQL_RECOMMEND .= " 	,'".$row_order["sale_discount"]."','".$row_recommend["discount"]."','".$row_order["s_amount"]."',now()) ";
       
       $log->log_write("  추천코드사용내역 (6) : ".$UP_SQL_RECOMMEND);
       
       $result_ins_recommend = $dbcon -> query($UP_SQL_RECOMMEND);
     }
     
     //===================================================
     // 감사쿠폰등록 (무통장 입금 제외 쿠폰 발생 안함)
     //===================================================
     if ($payMethod!="VBank"){
       if($row_event) {
         $INS_SQL_CP  =  " INSERT INTO tbl_board_coupon_history ";
         $INS_SQL_CP .= " (receive_orderno, event_seq, start_date, end_date, mobile ";
         $INS_SQL_CP .= " , ori_mobile, temp_discount, use_yn, writedate) ";
         $INS_SQL_CP .= "  VALUES ";
         $INS_SQL_CP .= " ('".$orderno."', '".$row_event["seq"]."', '".$s_coupon_date."', '".$e_coupon_date."','".$row_order["o_phone"]."' ";
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
     
     $param["payMethod"] = $payMethod;
     $param["pay_name"] = $pay_name;
     //서비스가 인슈플러스인 경우 원격진료코드 발행	
     if($row_order["chk_service"] == "C" || $row_order["chk_service"] == "D") { 
   
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
       if ($payMethod != "VBank") { //알림톡 쿠폰발행(무통장 X)									
         $SQL_LOTTE_USE = "UPDATE tbl_temporary_coupon set orderno = '".$orderno."', used_date = now() where seq = '".$lotte_promotion_info["seq"]."' ";
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
       if ($payMethod!="VBank"){ //알림톡 쿠폰발행(무통장 X)
         
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
           //20250319 yjhzzzzdev 변경
                         // $param["account"] = $VACT_Num;
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
    //  my_print_r($param,"이메일 발송 파라미터"); 
     $_SESSION["orderno"] = $orderno;		// 주문번호 세션처리
     header("location: ../insurance/renewal_step05.php");
     exit;

    } catch (Exception $e) {

        $s = $e->getMessage() . ' (오류코드:' . $e->getCode() . ')';
        echo $s;

   

        $netcancelResultString = ""; // 망취소 요청 API url(고정, 임의 세팅 금지)

        if ($httpUtil->processHTTP($netCancel, $authMap)) {
            $netcancelResultString = $httpUtil->body;
        } else {
            echo "Http Connect Error\n";
            echo $httpUtil->errormsg;

            throw new Exception("Http Connect Error");
        }

    }
    
        
} else {
    $errorData = json_decode($response, true);
    // 에러 처리
    echo "<script>alert('결제 요청에 실패하였습니다.\\n사유:".iconv("EUC-KR","UTF-8",$errorData['message'])."\\n같은 반복되는 경우 관리자에게 문의하세요.');</script>";

}
?>

  
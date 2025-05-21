<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/_config/lib.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/_config/Func.alrimTalk.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/_config/Func.mail.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/_config/class.log.php';

$log = new log();
$rawPostData = file_get_contents('php://input');
$log->log_write('Toss VA Deposit Callback Start');
$log->log_write($rawPostData);

$data = json_decode($rawPostData, true);

$log->log_write('Toss VA Deposit Callback Data: ' . print_r($data, true));

if (!$data) {
    $log->log_write('Invalid JSON payload');
    http_response_code(400);
    echo 'INVALID JSON';
    exit;
}

if ($data['status'] !== 'DONE') {
    $log->log_write('Not a completed virtual account payment');
    http_response_code(400);
    echo 'NOT A VALID PAYMENT';
    exit;
} else if ($data['status'] === 'DONE') {
    $orderId = $data['orderId'];
    $virtualAccount = '';
    $accountNumber = '';  // 가상계좌 번호
    $bankCode = '';  // 은행 코드
    $bankName = '';  // 은행 이름

    // 데이터 검증
    $sql_data_order_check = ' SELECT o.pay_name, o.order_step, j.o_phone FROM ';
    $sql_data_order_check .= " tbl_order_list o INNER JOIN tbl_order_list_join j ON o.orderno = j.orderno WHERE o.orderno = '" . $orderId . "' AND j.chk_join = 'N' ";
    $result_data_order_check = $dbcon->query($sql_data_order_check);
    $row_data_order_check = $dbcon->fetch_array($result_data_order_check);
    $o_phone = $row_data_order_check['o_phone'];
    $orderStep = $row_data_order_check['order_step'];
    $payName = all_seed_dec($row_data_order_check['pay_name']);
    $expectedAccount = explode('/', str_replace(' ', '', $payName))[2];

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

        $log->log_write('Payment Data: ' . print_r($paymentData, true));
        // 가상계좌 정보 추출
        if (isset($paymentData['method']) && $paymentData['method'] === '가상계좌') {
            $virtualAccount = $paymentData['virtualAccount'];
            $accountNumber = $virtualAccount['accountNumber'];  // 가상계좌 번호
            $bankCode = $virtualAccount['bankCode'];  // 은행 코드
            $bankName = $virtualAccount['bankName'];  // 은행 이름
        }

        if ($expectedAccount !== $accountNumber) {
            $log->log_write('계좌번호 불일치: expected=' . $expectedAccount . ' / received=' . $accountNumber);
            http_response_code(400);
            echo 'ACCOUNT MISMATCH';
            exit;
        } else {
            // 주문 상태 업데이트
            $dbcon->query("UPDATE tbl_order_list SET order_step = '2' WHERE orderno = '" . $orderId . "'");
            $dbcon->query("UPDATE tbl_order_list_join SET join_status = 'Y' WHERE orderno = '" . $orderId . "'");

            $sql = ' SELECT count(*) FROM tbl_board_coupon_history ';
            $sql .= " WHERE mobile = '" . $o_phone . "' AND receive_orderno = '" . $orderId . "' ";
            $log->log_write($sql);
            $log->log_write('count===' . $coupon_cnt);
            $coupon_cnt = $dbcon->getCount($sql);  // 가입 시 받은 쿠폰여부 체크

            // 주문 정보 조회
            $SQL_ORDER = 'SELECT j.o_name, j.o_phone, o.o_email1, o.o_email2, o.plan_name, o.purpose, ';
            $SQL_ORDER .= 'o.ins_amount, o.service_amount, o.t_amount, o.pg_pay_type, o.pay_name, o.order_step, ';
            $SQL_ORDER .= 'o.s_date, o.s_date_time, o.e_date, o.e_date_time, o.chk_p, o.chk_service, ';
            $SQL_ORDER .= 'o.ins_file_cd, o.service_file_cd, o.plan_cd, j.regdate ';
            $SQL_ORDER .= 'FROM tbl_order_list_join j INNER JOIN tbl_order_list o ON j.orderno = o.orderno ';
            $SQL_ORDER .= "WHERE o.orderno = '" . $orderId . "' AND j.chk_join = 'N'";
            $result_order = $dbcon->query($SQL_ORDER);
            $row_order = $dbcon->fetch_array($result_order);

            // 플랜 약관 정보
            $SQL_PLAN = 'SELECT ins_term1_seq, ins_term2_seq FROM tbl_board_plan WHERE seq = ' . $row_order['plan_cd'];
            $RS_PLAN = $dbcon->query($SQL_PLAN);
            $row_plan = $dbcon->fetch_array($RS_PLAN);

            if ($coupon_cnt == 0 && strtolower($row_order['pg_pay_type']) == 'vbank') {  // 쿠폰 전달

                $s_coupon_date = date('Y-m-d', strtotime($row_order['regdate']));
                $e_coupon_date = date('Y-m-d', strtotime($s_coupon_date . ' +180 days '));

                $SQL_EVENT = 'SELECT 
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
                                            ,max_companion';
                $SQL_EVENT .= " FROM tbl_board_event WHERE event_type='C' AND event_partnership_code = 'insuplus'";
                $SQL_EVENT .= ' AND start_date <= now() AND end_date >= now()';
                $SQL_EVENT .= ' ORDER BY seq desc LIMIT 0,1 ';
                $result_event = $dbcon->query($SQL_EVENT);
                $row_event = $dbcon->fetch_array($result_event);

                $discount = 0;
                $symbol = '%';
                $discountValue = '';
                if ($row_event) {
                    if ($row_event['service_fee_discount_applied'] == 'F') {
                        $symbol = '원';
                        $discount = number_format($row_event['service_fee_max_discount_amount']);
                    } else {
                        if ($row_event['insurance_discount_applied'] == 'P' && $row_event['service_fee_discount_applied'] == 'P') {
                            $symbol = '%';
                            $discount = max($row_event['insurance_discount_rate'], $row_event['service_fee_discount_rate']);
                        } else if ($row_event['insurance_discount_applied'] == 'P') {
                            $symbol = '%';
                            $discount = $row_event['insurance_discount_rate'];
                        } else if ($row_event['service_fee_discount_applied'] == 'P') {
                            $symbol = '%';
                            $discount = $row_event['service_fee_discount_rate'];
                        }
                    }
                }
                $discountValue = $discount . $symbol;

                if ($row_event) {
                    $INS_SQL_CP = ' INSERT INTO tbl_board_coupon_history ';
                    $INS_SQL_CP .= ' (receive_orderno, event_seq, start_date, end_date, mobile ';
                    $INS_SQL_CP .= ' , ori_mobile, temp_discount, use_yn, writedate) ';
                    $INS_SQL_CP .= '  VALUES ';
                    $INS_SQL_CP .= " ('" . $orderId . "', '" . $row_event['seq'] . "', '" . $s_coupon_date . "', '" . $e_coupon_date . "','" . $o_phone . "' ";
                    $INS_SQL_CP .= " ,'" . $o_phone . "', '" . $discount . "','N', now()) ";

                    $result = $dbcon->query($INS_SQL_CP);
                    $log->log_write('vacct coupon result : ' . $result);
                }
            }

            $param = array();
            $mobile = all_seed_dec($row_order['o_phone']);
            $param['name'] = all_seed_dec($row_order['o_name']);
            $param['pr_name'] = $row_order['plan_name'];
            $param['plan_name'] = $row_order['plan_name'];
            if ($row_order['chk_p'] == 'Y') {
                $s_date = $row_order['s_date'] . ' ' . $row_order['s_date_time'];
                $e_date = $row_order['e_date'] . ' ' . $row_order['e_date_time'];
                $param['period'] = $s_date . '시 ~ ' . $e_date . '시';
            } else {
                $s_date = $row_order['s_date'];
                $e_date = $row_order['e_date'];
                $param['period'] = $s_date . ' ~ ' . $e_date;
            }
            $arr_period = getArrPeriod($s_date, $e_date, $row_order['chk_p']);
            $param['period'] .= ' (' . $arr_period['day'] . '일)';
            $param['purpose'] = $row_order['purpose'];
            $param['ins_amount'] = $row_order['ins_amount'];
            $param['amount'] = $row_order['ins_amount'] + $row_order['service_amount'];
            $param['t_amount'] = $row_order['t_amount'];
            $param['payMethod'] = '';
            $param['pay_name'] ='';
            
            // 서비스가 인슈플러스인 경우 원격진료코드 발행
            $param['telemedic'] = '';
            if ($row_order['chk_service'] == 'C' || $row_order['chk_service'] == 'D') {
                // $SQL_T = "SELECT telemedicine_cd from tbl_telemedicine_cd_list where expire_date = " . $arr_period["month"] . " and join_orderno is null order by telemedi_seq ASC limit 1";
                // $RS_T = $dbcon->query($SQL_T);
                // $tele_cd = $dbcon->fetch_array($RS_T);
                // $param["telemedic"] = $tele_cd["telemedicine_cd"];
                // $log->log_write(" 원격진료코드 발행 (8) : " . $param["telemedic"]);
                // $log->log_write("=== pc 결제내역 (알림톡, 이메일 생략) END === ");
                // $SQL_TI = "UPDATE tbl_telemedicine_cd_list set join_orderno = '" . $resultMap["MOID"] . "', usedate = now() where telemedicine_cd = '" . $tele_cd["telemedicine_cd"] . "' ";
                // $RS_TI = $dbcon->query($SQL_TI);
                // 서비스명
                $SQL_CMN_CD = "select cd_nm from safety_training.fd_cmn_cd where grp_cd = 'CC13' and cd_val1 = '" . $row_order['chk_service'] . "' order by ord ASC";
                $RS_CMN_CD = $dbcon->query($SQL_CMN_CD);
                $service_nm = $dbcon->fetch_array($RS_CMN_CD);
                $param['pr_name'] .= ' ' . $service_nm['cd_nm'] . '타입';
                $param['service_name'] = $service_nm['cd_nm'] . '타입';
            }

            // ===================================================
            // 롯데 등업코드 생성
            // ===================================================
            $SQL_LOTTE = 'SELECT seq, coupon_cd, start_date, end_date FROM tbl_temporary_coupon 
            where orderno is NULL and start_date <= now() and end_date >= now() order by seq asc
            limit 1';
            $RS_LOTTE = $dbcon->query($SQL_LOTTE);
            $lotte_promotion_info = $dbcon->fetch_array($RS_LOTTE);
            $log->log_write('=== 롯데 등업코드 생성 === ' . $orderno);

            $today = strtotime(date('Y-m-d H:i:s'));
            $promotion_start_date = strtotime($lotte_promotion_info['start_date']);
            $promotion_end_date = strtotime($lotte_promotion_info['end_date']);

            if ($lotte_promotion_info && $promotion_start_date <= $today && $promotion_end_date >= $today) {
                $SQL_LOTTE_USE = "UPDATE tbl_temporary_coupon set orderno = '" . $resultMap['MOID'] . "', used_date = now() where seq = '" . $lotte_promotion_info['seq'] . "' ";
                $RS_LOTTE_USE = $dbcon->query($SQL_LOTTE_USE);
                $param['promotion'] = $lotte_promotion_info['coupon_cd'];
                $log->log_write('orderno===' . $resultMap['MOID']);
                // ===================================================
                // 롯데 등업코드 알림톡 전송
                // ===================================================
                kakaoPromotionSend($param, $mobile);
            } else {
                // if ($row_order["chk_service"] == "A" || $row_order["chk_service"] == "B" || $row_order["chk_service"] == "N") {
                // 	kakaoInsuplusJoin($param,all_seed_dec($o_phone));
                // } else {
                // 	kakaoJoin($param,all_seed_dec($o_phone));
                // }
                kakaoInsuplusJoin($param, $mobile);
            }

            $param['discount'] = '10%';
            $param['discount_txt'] = '10% (할인 최대한도 30,000원)';
            $param['coupon_name'] = '가입 감사 쿠폰';
            $param['coupon_period'] = $s_coupon_date . ' ~ ' . $e_coupon_date;
            kakaoJoinCoupon($param, $mobile);

            $param['chk_service'] = $row_order['chk_service'];
            $param['domain'] = getDomain();
            $param['ins_seq'] = $row_order['ins_file_cd'];
            $param['ins_term1'] = $row_plan['ins_term1_seq'];
            $param['ins_term2'] = $row_plan['ins_term2_seq'];
            $param['service_seq'] = $row_order['service_file_cd'];
            $email = all_seed_dec($row_order['o_email1']) . '@' . all_seed_dec($row_order['o_email2']);

            $log->log_write('Payment Data: ' . print_r($param, true));
            mailJoinSend($param, $email);
         

            
            $log->log_write('입금 완료 처리 완료: ' . $orderId);
            http_response_code(200);
            echo 'OK';
        }
     
    }
    $dbcon->dbcon_close();
}
?>

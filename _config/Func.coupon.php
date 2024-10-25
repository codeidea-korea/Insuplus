<?
function fn_calculate_coupon_discount ($cp_cd, $t_ins_amt, $t_service_amt, $total_amount) {
  $dbcon = $GLOBALS["dbcon"];
  //쿠폰 검색
  $SQL_CP = "select A.subject
  , A.duplicate_status_yn
  , A.insurance_discount_applied
  , A.service_fee_discount_applied
  , A.insurance_discount_rate
  , A.insurance_max_discount_amount
  , A.service_fee_discount_rate
  , A.service_fee_max_discount_amount
  , A.event_category_master_seq
  , B.discount 
  , B.temp_discount 
  from tbl_board_event A inner join tbl_board_coupon_history B on A.seq=B.event_seq where B.seq in (".$cp_cd.") ";
  $result_cp = $dbcon -> query($SQL_CP);

  if($result_cp) {
    while($row_cp = $dbcon -> fetch_array($result_cp)){
      $cp_list[] = $row_cp;
    }
  } else {
    $cp_list = array();
  }
  $totalDiscount = 0;  // 총 할인 금액 초기화
  $s_amt_per = 0;  // 최종 할인율

  // 1. 구쿠폰 처리
  foreach ($cp_list as $coupon) {
    if (isset($coupon['temp_discount']) && $coupon['insurance_discount_applied'] === 'N' && $coupon['service_fee_discount_applied'] === 'N') {
      // 구쿠폰 할인 계산
      $discount = $coupon['temp_discount'];
      $s_amt_temp = $total_amount / 100 * $discount;
      $s_amt_temp = floor($s_amt_temp);  // 소수점 버림

      // 최대 할인 금액 3000원 처리
      if ($s_amt_temp <= 3000) {  
          $s_amount = $s_amt_temp;
          $s_amt_per = $discount;
      } else {
          $s_amount = 3000;  // 3000원까지 할인
          $s_amt_per = (float)3000 * 100 / $total_amount;  // 재계산
      }
      $totalDiscount += $s_amount;  // 구쿠폰 할인 금액 합산

      break;  // 구쿠폰은 1개만 적용되므로 루프 종료
    }
  }

  // 2. 신쿠폰 처리 (중복 가능한 경우)
  foreach ($cp_list as $coupon) {
    if (isset($coupon['temp_discount']) && $coupon['insurance_discount_applied'] != 'N' || $coupon['service_fee_discount_applied'] != 'N') {
      // 신쿠폰 처리
      $discountResult = calculateDiscount($coupon, $t_ins_amt, $t_service_amt);
      $totalDiscount += $discountResult['totalDiscount'];  // 신쿠폰 할인 금액 합산
    }
  }

  // 최종 할인율 계산
  $discountPercentage = ($totalDiscount / $total_amount) * 100;
  $s_amt_per = floor($discountPercentage);  // 소수점 버림 처리

  // 최종 결과 반환
  return [
      'totalDiscount' => $totalDiscount,  // 총 할인 금액
      's_amt_per' => $s_amt_per  // 최종 할인율
  ];
}

// 할인 금액 계산 함수
function calculateDiscount($couponData, $t_ins_amt, $t_service_amt) {
  $totalDiscount = 0;

  // 보험료 할인 계산 (정률 할인만 적용됨)
  $insuranceDiscount = 0;
  if ($couponData['insurance_discount_applied'] === 'P') {
      // 정률 보험료 할인 계산
      $insuranceDiscount = floor(($couponData['insurance_discount_rate'] / 100) * $t_ins_amt);  // 소수점 버림
      // 보험료 할인 금액이 최대 할인 금액을 넘지 않도록 제한
      $maxInsuranceDiscount = $couponData['insurance_max_discount_amount'] * 10000;
      $insuranceDiscount = min($insuranceDiscount, $maxInsuranceDiscount);
  }

  // 서비스료 할인 계산 (정률 또는 정액)
  $serviceFeeDiscount = 0;
  if ($couponData['service_fee_discount_applied'] === 'P') {
      // 정률 서비스료 할인 계산
      $serviceFeeDiscount = floor(($couponData['service_fee_discount_rate'] / 100) * $t_service_amt);  // 소수점 버림
      // 서비스료 할인 금액이 최대 할인 금액을 넘지 않도록 제한
      $maxServiceFeeDiscount = $couponData['service_fee_max_discount_amount'] * 10000;
      $serviceFeeDiscount = min($serviceFeeDiscount, $maxServiceFeeDiscount);
  } elseif ($couponData['service_fee_discount_applied'] === 'F') {
      // 정액 서비스료 할인 계산 (정액할인 시 최대 금액 대신 정액을 사용)
      $serviceFeeDiscount = $couponData['service_fee_max_discount_amount'];
  }

  // 전체 할인 계산
  $totalDiscount = $insuranceDiscount + $serviceFeeDiscount;

  return [
      'totalDiscount' => $totalDiscount,
      'insuranceDiscount' => $insuranceDiscount,
      'serviceFeeDiscount' => $serviceFeeDiscount
  ];
}
?>
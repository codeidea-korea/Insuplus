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
  // 전체 할인 금액 계산
  $totalDiscount = 0;

  foreach ($cp_list as $coupon) {
      $discountResult = calculateDiscount($coupon, $t_ins_amt, $t_service_amt);
      $totalDiscount += $discountResult['totalDiscount'];

      // echo "쿠폰명: " . $coupon['subject'] . "<br>";
      // echo "전체 할인 금액: " . number_format($discountResult['totalDiscount']) . "원<br>";
      // echo "보험료 할인 금액: " . number_format($discountResult['insuranceDiscount']) . "원<br>";
      // echo "서비스료 할인 금액: " . number_format($discountResult['serviceFeeDiscount']) . "원<br><br>";
  }

  // 최종 할인율 계산
  $discountPercentage = ($totalDiscount / $total_amount) * 100;
  $s_amt_per = floor($discountPercentage);  // 소수점 버림
  return [
    'totalDiscount' => $totalDiscount,
    's_amt_per' => $s_amt_per
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

  // 임시 할인이 적용되는 경우 처리
  if (isset($couponData['temp_discount']) && $couponData['temp_discount'] > 0) {
      $totalDiscount += $couponData['temp_discount'];
  }

  return [
      'totalDiscount' => $totalDiscount,
      'insuranceDiscount' => $insuranceDiscount,
      'serviceFeeDiscount' => $serviceFeeDiscount
  ];
}

// 전체 할인 계산 처리
function calculateTotalDiscount($coupons, $t_ins_amt, $t_service_amt) {
	$nonDuplicateDiscount = 0;
	$duplicateDiscount = 0;

	// 중복할인 불가능한 쿠폰 먼저 처리
	foreach ($coupons as $coupon) {
			if ($coupon['duplicate_status_yn'] === 'N') {
					// 비중복 쿠폰 먼저 적용
					$discountResult = calculateDiscount($coupon, $t_ins_amt, $t_service_amt);
					$nonDuplicateDiscount += $discountResult['totalDiscount'];

					// 잔여 금액 갱신
					$t_ins_amt -= $discountResult['insuranceDiscount'];
					$t_service_amt -= $discountResult['serviceFeeDiscount'];
			}
	}

	// 중복할인 가능한 쿠폰 처리
	foreach ($coupons as $coupon) {
			if ($coupon['duplicate_status_yn'] === 'Y') {
					// 중복 가능 쿠폰은 잔여 금액에 대해 적용
					$discountResult = calculateDiscount($coupon, $t_ins_amt, $t_service_amt);
					$duplicateDiscount += $discountResult['totalDiscount'];
			}
	}

	return [
			'nonDuplicateDiscount' => $nonDuplicateDiscount,
			'duplicateDiscount' => $duplicateDiscount,
			'totalDiscount' => $nonDuplicateDiscount + $duplicateDiscount
	];
}
?>
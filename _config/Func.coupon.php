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
    return [
      "totalDiscount" => 0,  // 총 할인 금액
      "s_amt_per" => 0  // 최종 할인율
  ];
  }
  /*
  보험료: $t_ins_amt
  서비스료: $t_service_amt
  상품가격: $total_amount
  a. 보험료 할인금액 :
    보험료 x 중복불가 보험료 할인율 + (보험료 - ( 보험료 x 중복불가 보험료 할인율 )) x 중복가능 보험료 할인율

  b. 서비스료 할인금액 :
    정률 + 정률 : 서비스료 x 중복불가 서비스료 할인율 + (서비스료 - ( 서비스료 x 중복불가 서비스료 할인율 )) x 중복가능 서비스료 할인율
    정률 + 정액 : 서비스료 x 중복불가 서비스료 할인율 + 중복가능 서비스료 정액금액
    정액 + 정률 : 중복불가 서비스료 정액금액 + ( 서비스료 – 중복불가 서비스료 정액금액 ) x 중복가능 서비스료 할인율
    정액 + 정액 : 중복불가 서비스료 정액금액 + 중복가능 서비스료 정액금액

  ★최대할인금액은 각 할인율 계산시 적용.

  c. 구쿠폰은 중복불가 쿠폰으로 구분한다.
    구쿠폰 최대할인금액은 3,000원까지 적용
    보험료 할인율, 서비스료 할인율의 값은 temp_discount 값으로 적용한다.

  d. 할인 금액이 최대 할인 금액을 넘지 않도록 제한한다.
    - 최대 할인 금액은 10,000원 단위로 적용 ( 최대할인금액*10000을 해준다. )
    insurance_max_discount_amount : 보험료 최대할인금액
    service_fee_max_discount_amount : 서비스료 최대할인금액


  e. service_fee_discount_applied 값에 따라서 정률, 정액 할인으로 구분한다.
    - 서비스료 할인 금액이 최대 할인 금액을 넘지 않도록 제한
    - 정액 서비스료 할인 계산 (정액할인 시 최대 금액 대신 정액을 사용)
    1) 정률 : P
      - 정률 할인율 : service_fee_discount_rate
      - 최대 할인금액: service_fee_max_discount_amount
    2) 정액 : F
      - 정액 할인금액 : service_fee_max_discount_amount
  */

  $totalDiscount = 0;  // 총 할인 금액 초기화
  $s_amt_per = 0;  // 최종 할인율

  $old_coupon = null;  // 옛날 쿠폰도 중복 불가 쿠폰처럼 처리한다. (temp_discount != null, insurance_discount_applied === "N", service_fee_discount_applied === "N")
  $no_dup_coupon = null; // 중복불가 쿠폰 (duplicate_status_yn === "N")
  $dup_coupon = null;  // 중복가능 쿠폰 (duplicate_status_yn === "Y")
  $total_discount_info = Array(
    "insurance_discount_amount" => 0,  // 보험료 할인 금액
    "service_discount_amount" => 0,  // 서비스료 할인 금액

    "no_dup_insurance_discount_applied" => "N",  // 중복불가 보험료 할인 적용 여부
    "no_dup_service_fee_discount_applied" => "N",  // 중복불가 서비스료 할인 적용 여부
    "no_dup_insurance_discount_rate" => 0,  // 중복불가 보험료 할인율 초기화
    "no_dup_service_fee_discount_rate" => 0,  // 중복불가 서비스료 할인율 초기화
    "no_dup_service_fee_discount_fixed_amount" => 0,  // 중복불가 서비스료 정액요금 초기화
    "no_dup_max_insurance_discount_amount" => 0,  // 보험료 최대 할인 금액
    "no_dup_max_service_discount_amount" => 0,  // 서비스료 최대 할인 금액

    "dup_insurance_discount_applied" => "N",  // 중복 보험료 할인 적용 여부
    "dup_service_fee_discount_applied" => "N",  // 중복 서비스료 할인 적용 여부
    "dup_coupon_insurance_discount_rate" => 0,  // 중복가능 보험료 할인율 초기화
    "dup_service_fee_discount_rate" => 0,  // 중복불가 서비스료 할인율 초기화
    "dup_service_fee_discount_fixed_amount" => 0,  // 중복불가 서비스료 정액요금 초기화
    "dup_max_insurance_discount_amount" => 0,  // 보험료 최대 할인 금액
    "dup_max_service_discount_amount" => 0,  // 서비스료 최대 할인 금액
  );

  // 1. 쿠폰 구분 처리
  foreach ($cp_list as $coupon) {
    if (isset($coupon["temp_discount"]) && $coupon["insurance_discount_applied"] === "N" && $coupon["service_fee_discount_applied"] === "N") { //구쿠폰
      $old_coupon = $coupon;
      $total_discount_info["no_dup_insurance_discount_rate"] = $old_coupon["temp_discount"] ? $old_coupon["temp_discount"] : 0;
      $total_discount_info["no_dup_service_fee_discount_rate"] = $old_coupon["temp_discount"] ? $old_coupon["temp_discount"] : 0;
      $total_discount_info["no_dup_service_fee_discount_fixed_amount"] = 0;
      $total_discount_info["no_dup_max_insurance_discount_amount"] = 10000;
      $total_discount_info["no_dup_max_service_discount_amount"] = 20000;
      $total_discount_info["no_dup_insurance_discount_applied"] = "P";
      $total_discount_info["no_dup_service_fee_discount_applied"] = "P";
    } else if ($coupon["duplicate_status_yn"] === "N") { //중복불가 쿠폰
      $no_dup_coupon = $coupon;
      if($no_dup_coupon["insurance_discount_applied"] === "P") {// 보험료 할인 구분
        $total_discount_info["no_dup_insurance_discount_rate"] = $no_dup_coupon["insurance_discount_rate"];
        $total_discount_info["no_dup_max_insurance_discount_amount"] = $no_dup_coupon["insurance_max_discount_amount"] * 10000;
        $total_discount_info["no_dup_insurance_discount_applied"] = "P";
      }
      if($no_dup_coupon["service_fee_discount_applied"] === "P") {//서비스료 할인 구분
        $total_discount_info["no_dup_service_fee_discount_rate"] = $no_dup_coupon["service_fee_discount_rate"];
        $total_discount_info["no_dup_max_service_discount_amount"] = $no_dup_coupon["service_fee_max_discount_amount"] * 10000;
        $total_discount_info["no_dup_service_fee_discount_applied"] = "P";
      } else if($no_dup_coupon["service_fee_discount_applied"] === "F") {
        $total_discount_info["no_dup_service_fee_discount_fixed_amount"] = $no_dup_coupon["service_fee_max_discount_amount"];
        $total_discount_info["no_dup_service_fee_discount_applied"] = "F";
      }
    } else { //중복쿠폰
      $dup_coupon = $coupon;
      if($dup_coupon["insurance_discount_applied"] === "P") {//보험료 할인 구분
        $total_discount_info["dup_coupon_insurance_discount_rate"] = $dup_coupon["insurance_discount_rate"];
        $total_discount_info["dup_max_insurance_discount_amount"] = $dup_coupon["insurance_max_discount_amount"] * 10000;
        $total_discount_info["dup_insurance_discount_applied"] = "P";
      }
      if($dup_coupon["service_fee_discount_applied"] === "P") {//서비스료 할인 구분
        $total_discount_info["dup_service_fee_discount_rate"] = $dup_coupon["service_fee_discount_rate"];
        $total_discount_info["dup_max_service_discount_amount"] = $dup_coupon["service_fee_max_discount_amount"] * 10000;
        $total_discount_info["dup_service_fee_discount_applied"] = "P";
      } else if($dup_coupon["service_fee_discount_applied"] === "F") {
        $total_discount_info["dup_service_fee_discount_fixed_amount"] = $dup_coupon["service_fee_max_discount_amount"];
        $total_discount_info["dup_service_fee_discount_applied"] = "F";
      }
    }
  }

  // 2. 할인금액 계산
  $total_discount_info["insurance_discount_amount"] = calculateInsuranceDiscount($t_ins_amt, $total_discount_info);
  // 할인 금액 계산
  $total_discount_info["service_discount_amount"] = calculateServiceFeeDiscount($t_service_amt, $total_discount_info);


  // 보험료 할인율
  $total_discount_insurance_rate = $total_discount_info["insurance_discount_amount"] / $t_ins_amt * 100;
  // 서비스료 할인율
  $total_discount_service_rate = $total_discount_info["service_discount_amount"] / $t_service_amt * 100;

  $totalDiscount = floor($total_discount_info["insurance_discount_amount"] + $total_discount_info["service_discount_amount"]);
  $totalDiscount = $totalDiscount >= $total_amount ? $total_amount : $totalDiscount; // 할인금액은 상품가격 보다 클 수 없음

  // 최종 할인율 계산
  $s_amt_per = floor(($totalDiscount / $total_amount) * 100);

  // 최종 결과 반환
  return [
    "insurance_discount_amount" => $total_discount_info["insurance_discount_amount"],  // 보험료 할인 금액
    "service_discount_amount" => $total_discount_info["service_discount_amount"],  // 서비스료 할인 금액
    "insurance_discount_rate" => $total_discount_insurance_rate,  // 보험료 할인율
    "service_discount_rate" => $total_discount_service_rate,  // 서비스료 할인율
    "totalDiscount" => $totalDiscount,  // 총 할인 금액
    "s_amt_per" => $s_amt_per  // 최종 할인율
  ];
}

function calculateInsuranceDiscount($insuranceAmount, $total_discount_info) {
  // 중복 불가 보험료 할인 계산
  $noDupDiscount = $insuranceAmount * ($total_discount_info["no_dup_insurance_discount_rate"] / 100);
  $noDupDiscount = min($noDupDiscount, $total_discount_info["no_dup_max_insurance_discount_amount"]); // 최대 할인 금액 적용

  // 중복 불가 할인 후 남은 보험료 금액 계산
  $remainingInsurance = $insuranceAmount - $noDupDiscount;

  // 중복 가능 보험료 할인 계산
  $dupDiscount = $remainingInsurance * ($total_discount_info["dup_coupon_insurance_discount_rate"] / 100);
  $dupDiscount = min($dupDiscount, $total_discount_info["dup_max_insurance_discount_amount"]); // 최대 할인 금액 적용

  // 총 보험료 할인 금액 계산
  $totalInsuranceDiscount = $noDupDiscount + $dupDiscount;

  // 결과 반환
  return $totalInsuranceDiscount;
}

function calculateServiceFeeDiscount($serviceAmount, $total_discount_info) {
  $noDupDiscount = 0;
  $dupDiscount = 0;

  // 중복 불가 서비스료 할인 계산
  if ($total_discount_info["no_dup_service_fee_discount_applied"] === "P") {
    // 정률 할인 계산
    $noDupDiscount = $serviceAmount * ($total_discount_info["no_dup_service_fee_discount_rate"] / 100);
    $noDupDiscount = min($noDupDiscount, $total_discount_info["no_dup_max_service_discount_amount"]); // 최대 할인 금액 적용
  } elseif ($total_discount_info["no_dup_service_fee_discount_applied"] === "F") {
    // 정액 할인 계산
    $noDupDiscount = $total_discount_info["no_dup_service_fee_discount_fixed_amount"];
  }

  // 중복 가능 서비스료 할인 계산
  $remainingService = $serviceAmount - $noDupDiscount;
  if ($total_discount_info["dup_service_fee_discount_applied"] === "P") {
    // 중복 불가 정률 + 중복 가능 정률
    if ($total_discount_info["no_dup_service_fee_discount_applied"] === "P") {
      $dupDiscount = $remainingService * ($total_discount_info["dup_service_fee_discount_rate"] / 100);
      $dupDiscount = min($dupDiscount, $total_discount_info["dup_max_service_discount_amount"]); // 최대 할인 금액 적용
    }
    // 중복 불가 정액 + 중복 가능 정률
    elseif ($total_discount_info["no_dup_service_fee_discount_applied"] === "F") {
      $dupDiscount = $remainingService * ($total_discount_info["dup_service_fee_discount_rate"] / 100);
      $dupDiscount = min($dupDiscount, $total_discount_info["dup_max_service_discount_amount"]); // 최대 할인 금액 적용
    }
  } elseif ($total_discount_info["dup_service_fee_discount_applied"] === "F") {
    // 정률 + 정액
    if ($total_discount_info["no_dup_service_fee_discount_applied"] === "P") {
      $dupDiscount = $total_discount_info["dup_service_fee_discount_fixed_amount"];
    }
    // 정액 + 정액
    elseif ($total_discount_info["no_dup_service_fee_discount_applied"] === "F") {
      $dupDiscount = $total_discount_info["dup_service_fee_discount_fixed_amount"];
    }
  }

  // 총 서비스료 할인 금액
  $totalServiceDiscount = $noDupDiscount + $dupDiscount;

  // 결과 반환
  return $totalServiceDiscount;
}
?>
<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php"; //추가
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.coupon.php";

  admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
/*
  할인금액 이상하게 적용 되었을 경우 재계산하여 적용하는 스크립트
  "할인금액 재계산 적용하기" 부분 주석 해제 후 사용
*/
  $order_info_SQL = 
  "select 
    seq
    ,orderno
    ,pr_cd
    ,ins_cd
    ,plan_cd
    ,service_cd
    ,s_date
    ,s_date_time
    ,e_date
    ,e_date_time
    ,ins_period
    ,chk_p
    ,chk_service
    ,o_name
    ,join_cnt
    ,sale_gubun
    ,sale_discount
    ,cp_cd
    ,recommend_cd
    ,order_step
    ,ins_amount
    ,service_amount
    ,s_amount
    ,cp_amount
    ,vat_amount
    ,t_amount
    ,writedate
    ,new_cp_cd
  from tbl_order_list
  where 1=1
    AND s_amount > 0 ";
  // $order_info_SQL .= " AND orderno in ('P_202411251433561732512837667','P_202411251406321732511193778') ";
  $order_info_SQL .= " AND new_cp_cd is not null
    AND order_step in ('1', '2')
    AND writedate >= '2024-11-25'
    AND writedate <= '2024-11-27 13:47'
  ORDER BY writedate DESC";
  $order_info_result = $dbcon -> query($order_info_SQL); // 결제 정보 조회
  while($order_row = $dbcon -> fetch_array($order_info_result) ) {
    echo "order_info : ";
    echo $order_row["seq"]." ".$order_row["orderno"]." ".$order_row["pr_cd"]." ".$order_row["ins_cd"]." ".$order_row["plan_cd"]." ".$order_row["service_cd"]." ";
    echo $order_row["s_date"]." ".$order_row["s_date_time"]." ".$order_row["e_date"]." ".$order_row["e_date_time"]." ".$order_row["ins_period"]." ";
    echo $order_row["chk_p"]." ".$order_row["chk_service"]." ".all_seed_dec($order_row["o_name"])." ".$order_row["join_cnt"]." ".$order_row["sale_gubun"]." ";
    echo $order_row["sale_discount"]." ".$order_row["cp_cd"]." ".$order_row["recommend_cd"]." ".$order_row["order_step"]." ".$order_row["ins_amount"]." ";
    echo $order_row["service_amount"]." ".$order_row["s_amount"]." ".$order_row["cp_amount"]." ".$order_row["vat_amount"]." ".$order_row["t_amount"]." ".$order_row["writedate"]." ".$order_row["new_cp_cd"];
    echo "<br>";
    
    $arr_add_gender = array();
		$arr_add_birth = array();
		$arr_add_name = array();
    $user_seq = array();
    $user_gender = array();
    $user_name = array();
    $user_age = array();
    $user_amt = array();
    $user_service_amt = array();

    $select_add_people = $order_row["join_cnt"];		// 동반인명수
    if (!$select_add_people){$select_add_people = 0;}
    $t_select_add_people = $select_add_people;

    $t_amt = 0;
    $t_ins_amt = 0;
    $t_service_amt = 0;
    $total_amount = 0;

    //상품검색
    $SQL_ins = "select seq,ext4,ext5 from tbl_board_product where seq='".$order_row["pr_cd"]."' ";
    $RS_ins = $dbcon -> query($SQL_ins);
    $PR_row = $dbcon -> fetch_array($RS_ins);

    // 플랜검색
    $SQL_PLAN = "select * from tbl_board_plan where seq=".$order_row["plan_cd"]." and s_date<='".$order_row["s_date"]."' and e_date>='".$order_row["s_date"]."' and plan_status='Y' AND secret='Y' ";
    //echo $SQL_PLAN;
    $result_plan = $dbcon -> query($SQL_PLAN);
    $row_plan = $dbcon -> fetch_array($result_plan);

    $join_user_SQL = 
    "select
      seq
      ,orderno
      ,gender
      ,chk_join
      ,o_name
      ,o_isdn1
      ,o_isdn2
      ,join_status
      ,join_amount
      ,join_service
      ,vat_amount
      ,s_amount
      ,t_amount
      ,regdate
      from tbl_order_list_join
      where 1=1
      AND orderno = '".$order_row["orderno"]."'
      AND join_status NOT IN ('N', 'R')";
    $join_user_result = $dbcon -> query($join_user_SQL);
    while($join_row = $dbcon -> fetch_array($join_user_result) ) { // 가입자, 동반자 정보 조회
      echo "join_user : ";
      echo $join_row["seq"]." ".$join_row["orderno"]." ".$join_row["gender"]." ".$join_row["chk_join"]." ".all_seed_dec($join_row["o_name"])." ".all_seed_dec($join_row["o_isdn1"])." ".all_seed_dec($join_row["o_isdn2"])." ";
      echo $join_row["join_status"]." ".$join_row["join_amount"]." ".$join_row["join_service"]." ".$join_row["vat_amount"]." ".$join_row["s_amount"]." ".$join_row["t_amount"]." ".$join_row["regdate"];
      echo "<br>";
      if($join_row["s_amount"] > 0){
        $PR_INFO = getInsuProductInfo($pr_cd); //상품정보
        $chk_p = $order_row["chk_p"];

        $user_seq[] = $join_row["seq"];												// 가입자 seq
        $user_gender[] = $join_row["gender"];									        // 동반인 성별
        $user_birth = all_seed_dec($join_row["o_isdn1"]);											      // 동반인 생일
        $user_name[] = $join_row["o_name"];		// 동반인 이름

        $user_age[] = fn_ins_age($user_birth);
        $user_amt[] = $join_row["join_amount"];
        $user_service_amt[] = $join_row["join_service"];
        $t_user_service_amt = $t_user_service_amt + $join_row["join_service"];
        $t_amt = $t_amt + $join_row["join_amount"];		// 총 여행보험비용
        $t_ins_amt = $t_ins_amt + $join_row["join_amount"];		// 총 보험료
        $t_service_amt = $t_service_amt + $join_row["join_service"];		// 총 서비스비용
      }
    }
    
    $total_amount = $t_ins_amt+$t_service_amt;
    echo "상품가격 : ".$total_amount;
    //쿠폰 검색
    $sale_gubun = ""; //할인방법
    $s_amt_per = 0;
    $s_amount = 0;
    $usr_s_amount = 0;
    if ($order_row["new_cp_cd"]){ //쿠폰
      $sale_gubun = "C";

      $cp_discount_info = fn_calculate_coupon_discount($order_row["new_cp_cd"], $t_ins_amt, $t_service_amt, $total_amount);

      $s_amount = $cp_discount_info["totalDiscount"];				// 총 할인금액
      $s_amt_per = $cp_discount_info["s_amt_per"];								// 총 할인율
      $insurance_discount_rate = $cp_discount_info["insurance_discount_rate"];	// 보험료 할인율
      $service_discount_rate = $cp_discount_info["service_discount_rate"];		// 서비스료 할인율

    }else if($order_row["recommend_cd"]) { //추천코드
      $sale_gubun = "R";
      $SQL_RECOMMEND  = " SELECT seq, discount FROM tbl_board_recommend_code WHERE seq = '".$order_row["recommend_cd"]."' ";
      $result_recommend = $dbcon -> query($SQL_RECOMMEND);
      $row_recommend = $dbcon -> fetch_array($result_recommend);

      $discount = $row_recommend["discount"];
      $s_amt_temp = $total_amount/100*$discount;
      $s_amt_temp = floor($s_amt_temp);

      if ($s_amt_temp<=30000){ 	//할인폭이 3만원 이상인경우 3만원까지만 할인되도록
        $s_amount = $s_amt_temp;
        $s_amt_per = $discount;
      }else{
        $s_amount = 30000;
        $s_amt_per = (float)30000*100/$total_amount; //재계산 들어감
      }
    }
    echo " 총 할인금액: ".$s_amount;
    echo " 총 결제금액: ".($total_amount-$s_amount)."<br/>";

    for($k=0; $k<$select_add_people; $k++){
      $add_usr_s_amount = 0;
      if ($order_row["new_cp_cd"]){
        $add_user_ins_dis_amount = floor($user_amt[$k]/100*$insurance_discount_rate);	// 가입자 보험료 할인금액
        $add_user_service_dis_amount = floor($user_service_amt[$k]/100*$service_discount_rate);	// 가입자 서비스료 할인금액
        $add_usr_s_amount = $add_user_ins_dis_amount + $add_user_service_dis_amount;	// 가입자 총 할인금액
      } else if($order_row["recommend_cd"]) {
        $add_usr_s_amount = ($user_amt[$k] + $user_service_amt[$k])/100*$s_amt_per;	// 가입자 총 할인금액
      }
      $add_usr_t_amount =	$user_amt[$k] + $user_service_amt[$k] - $add_usr_s_amount;	// 가입자 결제금액
      echo "seq: ".$user_seq[$k]." 개별 결제금액: ".$add_usr_t_amount." 개별 할인금액: ".$add_usr_s_amount."<br/>";

      //할인금액 재계산 적용하기
      $update_SQL = "UPDATE tbl_order_list_join SET s_amount = '".$add_usr_s_amount."', t_amount = '".$add_usr_t_amount."' WHERE seq = '".$user_seq[$k]."'";
      $dbcon -> query($update_SQL);
    }
    echo "join user done<br>";
  }
  
  echo "done";
  exit;
?>
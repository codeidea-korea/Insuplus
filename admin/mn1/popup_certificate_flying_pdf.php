<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
include_once $_SERVER["DOCUMENT_ROOT"]."/_util/dompdf/dompdf_config.inc.php";
//require_once("./dompdf/dompdf_config.inc.php");

// 가입보험내역 검색
$SQL_V = "select * from tbl_order_list where orderno in (select orderno from tbl_order_list_join where seq=".$join_seq." and orderno='".$orderno."' )";
// echo $SQL_V;
$RS_V = $dbcon -> query($SQL_V);
if (!$RS_V){
  echo "<script>alert('해당 보험내역이 없습니다.');</script>";
  exit;
}
$row_r = $dbcon -> fetch_array($RS_V);

//단체가입 여부 조회
$SQL_GJ = "select * from tbl_order_group_join_list where group_join_id = '".$row_r["group_join_id"]."'";
$RS_GJ = $dbcon -> query($SQL_GJ);
if ($RS_GJ){
  $row_group = $dbcon -> fetch_array($RS_GJ);
}

//영문플랜명 상품에서 출력
$SQL_PRODUCT =  " SELECT ext4, ext9 FROM tbl_board_product WHERE seq = '".$row_r["pr_cd"]."' ";
$RS_PRODUCT = $dbcon -> query($SQL_PRODUCT);
$row_product = $dbcon -> fetch_array($RS_PRODUCT);

// 영문이름 업데이트 시 처리
if ($o_name_en && $chk_lang == "E"){
$SQL_U = "update tbl_order_list_join set o_name_en='".all_seed_enc($o_name_en)."' where seq=".$join_seq." and orderno='".$orderno."' ";
$RS = $dbcon -> query($SQL_U);
}

// 가입자 본인내역 검색
$SQL = "select * from tbl_order_list_join where seq=".$join_seq." and orderno='".$orderno."' ";
$RS = $dbcon -> query($SQL);
$row = $dbcon -> fetch_array($RS);

//계약자 정보 검색
$SQL_CONT = "select * from tbl_order_list_join tolj left join tbl_order_list tol on (tolj.orderno = tol.orderno) where tolj.orderno = '".$orderno."' order by tolj.seq ASC limit 1";
$RS_CONT = $dbcon -> query($SQL_CONT);
$row_cont_info = $dbcon -> fetch_array($RS_CONT);

//단체가입 상태 검색
$SQL_CMN = "select * from safety_training.fd_cmn_cd where grp_cd ='CC09'";
$RS_CMN = $dbcon -> query($SQL_CMN);
$ARR_CMN = array();
while($row_cmn = $dbcon->fetch_array($RS_CMN)) {
  $ARR_CMN[$row_cmn["cd"]] = $row_cmn["cd_nm"];
}

// 플랜검색
$SQL_PLAN = "select * from tbl_board_plan where seq=".$row_r["plan_cd"]."";
$RS_PLAN = $dbcon -> query($SQL_PLAN);
$row_plan = $dbcon -> fetch_array($RS_PLAN);

// 플랜관련
// $SQL_G = "select * from tbl_board_plan_guarantee where plan_cd=".$row_r["plan_cd"]."";
// $RS_G = $dbcon -> query($SQL_G);

//보장내역
// if($row_product["ext4"]){
//   $SQL_GO = "select * from tbl_board_guarantee_opt where list_seq=".$row_product["ext4"]."";
//   $RS_GO = $dbcon -> query($SQL_GO);
//   $cnt=0;
//   $arrayGORow = array();
//   while($row_g_opt = $dbcon -> fetch_array($RS_GO)){
//     $arrayGORow[$cnt] = $row_g_opt["service_name_en"];
//     $cnt++;
//   }
// }
    
//보험사정보
// $SQL_INS  = " SELECT subject, ins_name_en, imgfile2 ";
// $SQL_INS .= ", (SELECT file_realname FROM tbl_file WHERE bc_id = 'ins_list' AND seq = '".$row_plan["ins_cd"]."' ORDER BY idx desc LIMIT 0,1) as signimg ";
// $SQL_INS .= " FROM tbl_board_ins_list WHERE seq = '".$row_plan["ins_cd"]."' "; //사인이미지 수정
// $RS_INS = $dbcon -> query($SQL_INS);
// $row_ins = $dbcon -> fetch_array($RS_INS);
// $arr_img_info = setFileName($row_ins["imgfile2"])[0];
// $row_ins["imgfile2"] = $arr_img_info[1];

if ($chk_lang=="E"){
  $url = "./pdf_join_certification_flying_table_en.php";
}else{
  $url = "./pdf_join_certification_flying_table.php";
}

$data = array(
  'orderno' => $orderno    // 주문번호
  , 'seq' => $join_seq      // 가입순번
);

$options = array(
  'http' => array(
    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
    'method'  => 'POST',
    'content' => http_build_query($data)
  )
);
$context  = stream_context_create($options);
$chHtml = @file_get_contents($url, false, $context);
    $error_level = error_reporting();
    error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

//========================================
//회원정보 처리
//========================================
$USER_NAME   = all_seed_dec($row["o_name"]);
$USER_ENAME   = all_seed_dec($row["o_name_en"]);

$USER_BIRTH = substr(all_seed_dec($row["o_isdn1"]),2,6)."-".substr(all_seed_dec($row["o_isdn2"]),0,1)."******";
$USER_EMAIL = all_seed_dec($row_r["o_email1"])."@".all_seed_dec($row_r["o_email2"]);

if($row_group){ //단체가입 여부에 따른 계약자명 처리
  $CONTRACTOR_KR = all_seed_dec($row_group["o_name"]);
  $CONTRACTOR_EN = all_seed_dec($row_group["o_name_en"]);
} else {
  $CONTRACTOR_KR = all_seed_dec($row_cont_info["o_name"]);
  $CONTRACTOR_EN = all_seed_dec($row_cont_info["o_name_en"]);
}

if($row_r["chk_p"] == "Y") {
  $INS_PERIOD = $row_r["s_date"]." ".$row_r["s_date_time"].":00 ~ ".$row_r["e_date"]." ".$row_r["e_date_time"].":00";
} else if($row_r["chk_p"] == "N") {
  $INS_PERIOD = $row_r["s_date"]." ~ ".$row_r["e_date"];
}

if ($chk_lang == "E") {    //가격단위
  $won = "won";
  $language = "영문";
  $name = $USER_ENAME;
} else {
  $won = "원";
  $language = "국문";
  $name = $USER_NAME;
}

if ($chk_lang != "E") {   //계약자명 처리
  $CONTRACTORROW = "<tr>";
  $CONTRACTORROW .= "  <th width='100' style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>계약자명</th>";
  $CONTRACTORROW .= "  <td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>" . all_seed_dec($row_group["o_name"]) . "</td>";
  $CONTRACTORROW .= "  <th width='100' style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>증권번호</th>";
  $CONTRACTORROW .= "  <td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>" . $row_plan["stock_isdn"] . "</td>";
  $CONTRACTORROW .= "</tr>";
} else {
  if ($row_r["o_name_en"]) {
    $CONTRACTORROW = "<tr>";
    $CONTRACTORROW .= "  <th width='100' style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>Name of Policy Holder</th>";
    $CONTRACTORROW .= "  <td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>" . all_seed_dec($row_group["o_name_en"]) . "</td>";
    $CONTRACTORROW .= "  <th width='100' style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>Policy Number</th>";
    $CONTRACTORROW .= "  <td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>" . $row_plan["stock_isdn"] . "</td>";
    $CONTRACTORROW .= "</tr>";
  }
}

//서비스 내역
// $CHK_SERVICE = "";
// $service_img = "";
// if ($certType != "I") {
//   if ($row_r["chk_service"] != "N" && $row_r["chk_service"] != "") {
//     $sql = "select * from tbl_board_plan_insuplus where 1=1 and plan_seq = '" . $row_r["plan_cd"] . "' ";
//     $RS_PLAN_SEQ = $dbcon->query($sql);
//     $ARR_PLAN_ROW = array();
//     while ($row_s = $dbcon->fetch_array($RS_PLAN_SEQ)) {
//       if ($row_s["k_amount"]) {
//         if ($chk_lang == "E") {
//           $ARR_PLAN_ROW[] = "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" . $row_s["e_name"] . "</th>
//               <td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" . $row_s["e_amount"] . "</td></tr>";
//         } else {
//           $ARR_PLAN_ROW[] = "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" . $row_s["k_name"] . "</th>
//               <td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" . $row_s["k_amount"] . "</td></tr>";
//         }
//       }
//     }
//     if ($chk_lang == "E") {
//       //영문 인슈플러스
//       $CHK_SERVICE = "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:20px 0 20px 0;'>Certification of FlyingDoctors Service</h5>";
//       $CHK_TABLE = "<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>";
//       $CHK_TABLE .= "<colgroup><col width='50%' /><col width='*' /></colgroup>";
//       $CHK_TABLE .= "<thead>  <tr><th style='background-color: #f2f7ff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Details</th>";
//       $CHK_TABLE .= "<th style='background-color: #fff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Limit</th>";
//       $CHK_TABLE .= "</tr></thead><tbody>";
//       $service_img_lang = "<p style='font-size:12px;margin:8px 0 18px 0;color:#333;'>";
//       $service_img_lang .= "  The above customer confirms that they have joined to a 24-hour overseas emergency transportation service.<br />";
//       $service_img_lang .= "  Please refer to the service guide for details.<br />";
//       $service_img_lang .= "  Flying Doctors is an overseas emergency transportation service brand on Bizinsight, Bizinsight provides services <br />";
//       $service_img_lang .= "  In accordance with the above collateral and compensation limits</p>";
//       $service_img_lang .= "<p style=\"text-align:right; padding:20px 20px;\"><img src=\"[DROOT]/html/images/[FLYIMG]\" align=\"absmiddle\"></p>";
//     } else {
//       //국문 인슈플러스
//       $CHK_SERVICE = "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:10px 0 5px 0;'>플라잉닥터스 서비스 가입 증명서</h5>";
//       $CHK_TABLE = "<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>";
//       $CHK_TABLE .= "<colgroup><col width='50%' /><col width='*' /></colgroup>";
//       $CHK_TABLE .= "<thead>  <tr><th style='background-color: #f2f7ff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>보장내역</th>";
//       $CHK_TABLE .= "<th style='background-color: #fff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>보장한도</th>";
//       $CHK_TABLE .= "</tr></thead><tbody>";
//       $service_img_lang = "<p style=\"text-align:right; padding:5px 20px;\"><img src=\"[DROOT]/html/images/[FLYIMG]\" align=\"absmiddle\"></p>";
//     }

//     if ($ARR_PLAN_ROW) {
//       foreach ($ARR_PLAN_ROW as $rows) {
//         $CHK_TABLE .= $rows;
//       }
//       $CHK_TABLE .= "</tbody></table>";
//       $CHK_SERVICE .= $CHK_TABLE;
//     }

//     if ($chk_fly_type == "A") {
//       $FLYIMG = "korea_assistance_stamp.png";
//       $chk_kor_service = "<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:0px 0 5px 0;'>상기 고객은 플라잉닥터스 서비스에 가입되었음을 확인합니다.</h3>";
//       $chk_kor_service .= "<p style='font-size:12px;margin:8px 0 5px 0;color:#333;'>코리아 어시스턴스는 다음의 담보내용과 보상한도액에 의거하여 서비스를 제공합니다.";
//       $chk_kor_service .= "자세한 사항에 대하여는 서비스 안내서를 참조하시기 바랍니다.</p>";
//     } else {
//       $chk_kor_service = "";
//       if ($chk_lang == "E") {
//         $FLYIMG = "biz_sign_en.png";
//       } else {
//         $FLYIMG = "biz_sign.png";
//       }
//     }
//     $service_img = $service_img_lang;
//   }
// }

///////////////////////////////////////////////////////////////////////////
// 2023-07-05 modified by kyle
///////////////////////////////////////////////////////////////////////////
// print service (insuplus)
$CHK_SERVICE = "";
$service_img = "";
$plan_service_all = getPlanService($row_r["pr_cd"], $row_r["plan_cd"]);

if($certType != "I" && $row_r["chk_service"] != "N" && $row_r["chk_service"] != "") {
  if ($chk_lang == "E") {
    //영문 인슈플러스
    $CHK_SERVICE .= "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:0 0 12px 0;'>Certificate of INSUPLUS Service</h5>";
    $CHK_SERVICE .= "<table width='100%' cellspacing='0' cellpadding='0' border='0' style='border-top: solid 2px #595959;border-bottom: solid 2px #595959; margin:0 0 14px 0;'>";
    $CHK_SERVICE .= "<colgroup><col width='40%'><col width='60%'></colgroup>";
    $CHK_SERVICE .= "<thead><tr><th style=\"background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;\">Coverage</th>";
    $CHK_SERVICE .= "<th style=\"background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;\">Coverage Limit</th></tr>";
    $CHK_SERVICE .= "</thead><tbody>";


    foreach($plan_service_all as $insuplus){
      if ( $insuplus["e_amount"] != "NOT-AVAILABLE"){
        $CHK_SERVICE .= "<tr><th width='350px' style=\"background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;\">" ;
        $CHK_SERVICE .= $insuplus["service_name_en"];
        $CHK_SERVICE .= "</th><td style=\"background-color: #ffffff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;\">" ;
        $CHK_SERVICE .= $insuplus["e_amount"] . "</td></tr>";
      }
    }

    $CHK_SERVICE .= "</tbody></table>";
    $CHK_SERVICE .= "<p style='font-size:12px;margin:8px 0 18px 0;color:#333;'>";
    $CHK_SERVICE .= "  The above customer confirms that they have joined to a 24-hour overseas emergency transportation service.<br />";
    $CHK_SERVICE .= "  Please refer to the service guide for details.<br />";
    $CHK_SERVICE .= "  Flying Doctors is an overseas emergency transportation service brand on Bizinsight, Bizinsight provides services <br />";
    $CHK_SERVICE .= "  In accordance with the above collateral and compensation limits</p>";
    $CHK_SERVICE .= "<p style=\"text-align:right; padding:20px 20px;\"><img src=\"[DROOT]/html/images/[FLYIMG]\" align=\"absmiddle\"></p>";
  } else {
    //국문 인슈플러스
    $CHK_SERVICE .= "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:0 0 12px 0;'>인슈플러스 서비스 가입 증명서</h5>";
    $CHK_SERVICE .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top: solid 2px #595959;border-bottom: solid 2px #595959; margin:0 0 14px 0;'>";
    $CHK_SERVICE .= "<colgroup><col width='20%'><col width='60%'></colgroup>";
    $CHK_SERVICE .= "<thead><tr><th style=\"background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;\">보장내역</th>";
    $CHK_SERVICE .= "<th style=\"background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;\">보장한도</th>";
    $CHK_SERVICE .= "</tr></thead><tbody>";

    
    foreach($plan_service_all as $insuplus){
      if ( $insuplus["k_amount"] != "NOT-AVAILABLE"){
        $CHK_SERVICE .= "<tr><th width='350px' style=\"background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;\">" ;
        $CHK_SERVICE .= $insuplus["service_name"];
        $CHK_SERVICE .= "</th><td style=\"background-color: #ffffff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;\">" ;
        $CHK_SERVICE .= $insuplus["k_amount"] . "</td></tr>";
      }
    }
    
    $CHK_SERVICE .= "</tbody></table>";
    $CHK_SERVICE .= "<p style=\"text-align:right; padding:5px 20px;\"><img src=\"[DROOT]/html/images/[FLYIMG]\" align=\"absmiddle\"></p>";
  }

  if ($chk_fly_type == "A") {
      $FLYIMG = "korea_assistance_stamp.png";
      $chk_kor_service = "<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:0px 0 5px 0;'>상기 고객은 플라잉닥터스 서비스에 가입되었음을 확인합니다.</h3>";
      $chk_kor_service .= "<p style='font-size:12px;margin:8px 0 5px 0;color:#333;'>코리아 어시스턴스는 다음의 담보내용과 보상한도액에 의거하여 서비스를 제공합니다.";
      $chk_kor_service .= "자세한 사항에 대하여는 서비스 안내서를 참조하시기 바랍니다.</p>";
    } else {
      $chk_kor_service = "";
      if ($chk_lang == "E") {
        $FLYIMG = "biz_sign_en.png";
      } else {
        $FLYIMG = "biz_sign.png";
      }
    }
}
///////////////////////////////////////////////////////////////////////////
// print guarantee
// 보장내역이 2개까지 등록이 가능하기 때문에 템플릿을 수정하지 않기 위해 
// $G_TABLE 변수에 모든 화면을 구현하고 $ins_noti, $ins_sign_img 변수는 "" 로 만듬
$G_TABLE = "";
$ins_noti = "";
$ins_sign_img = "";
$plan_guarantee_seq = array();
$plan_guarantee_all = getPlanGuarantee($row_r["pr_cd"], $row_r["plan_cd"]);
$plan_sign_image = getPlanInsurance($row_r["plan_cd"]);

foreach($plan_guarantee_all as $ell){
  if (!in_array($ell["guarantee_seq"], $plan_guarantee_seq)){
    $plan_guarantee_seq[] = $ell["guarantee_seq"];
  }
}

if($certType != "S" && count($plan_guarantee_seq) > 0) {
  $page_loop_idx = 0;

  foreach($plan_guarantee_seq as $guarantee_seq){
    if ($chk_lang == "E") {
      $G_TABLE .= "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:20px 0 5px 0;'>Certification of " . $plan_sign_image[$page_loop_idx]["ins_name_en"] . "</h5>";
      $G_TABLE .= "<p style='margin:8px 0 5px 0;font-size:12px;font-weight:400;color:#333;'>";
      $G_TABLE .= "Above subscriber is certified to insured of <span style='font-weight:400;color:red;'>" . $plan_sign_image[$page_loop_idx]["ins_name_en"] . ".</span><br/>";
      $G_TABLE .= "Please refer to the KOREA ASSISTANCE provides services based on the following collateral and compensation limits.<br/><br/>";
      $G_TABLE .= "Coverage: If mentioned, this coverage includes<br/>";
      $G_TABLE .= "the medical expense caused by Covid-19 infection, infectious disease up to the coverage limit of this policy<br/>";
      $G_TABLE .= "</p>";
      $G_TABLE .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>";
      $G_TABLE .= "<colgroup><col width='50%' /><col width='*' /></colgroup>";
      $G_TABLE .= "<thead>  <tr><th style='background-color: #f2f7ff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Details</th>";
      $G_TABLE .= "<th style='background-color: #fff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Limit</th>";
      $G_TABLE .= "</tr></thead><tbody>";

      foreach($plan_guarantee_all as $p_guarantee){
        if ($p_guarantee["guarantee_seq"] == $guarantee_seq){
          if ($p_guarantee["g_amount_certificate"] && $p_guarantee["g_amount_certificate"] != "NOT-AVAILABLE") {
            $G_TABLE .= "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>";
            $G_TABLE .= $p_guarantee["service_name_en"] ;
            $G_TABLE .= "</th><td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" ;
            $G_TABLE .= $p_guarantee["g_amount_certificate"] . "</td></tr>";
          }
        }
      }

      $G_TABLE .= "</tbody></table>";
      $G_TABLE .= "<p style='font-size:12px;margin:8px 0 18px 0;color:#333;'></p>";
      $G_TABLE .= "<p style='font-size:12px;margin:8px 0 5px 0;color:#333;'>This insurance contract is a group insurance which KOREA ASSISTANCE is policy holder and pays premium.</p>";
      $G_TABLE .= "<div style=\"font-size:12px;color:#333;\">■ Notice</div>";
      $G_TABLE .= "<div style=\"margin-left: 20px;font-size:12px;color:#333;\">Death Collateral does not apply to those under 15-years-old.</div>";
      $G_TABLE .= "<div style=\"margin-left: 20px;font-size:12px;color:#333;\">Deductible : If not mentioned, refer to insurance policy wording.</div>";
      $G_TABLE .= "<p style=\"text-align:right; padding:10px 20px;\"><img src='[DROOT]/_data/board/ins_list/".setFileName($plan_sign_image[$page_loop_idx]["eng_img"])[0][1]."' align='absmiddle'></p>";
    } else {
      $G_TABLE .= "<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:12px 0 5px 0;'>상기 고객을 피보험자로 하여 아래와 같이 <span style='color:#dc3347'>".$plan_sign_image[$page_loop_idx]["subject"]."</span>에 가입되었음을 확인 합니다.</h3>";
      $G_TABLE .= "<p style='font-size:12px;margin:0 0 30px 0;color:#333;'>보험계약의 자세한 사항에 대하여는 <span style='color:#dc3347'>".$plan_sign_image[$page_loop_idx]["subject"]."</span> 약관을 참조하시기 바랍니다.<br> 코리아 어시스턴스는 다음의 담보내용과 보상한도액에 의거하여 서비스를 제공합니다.</p>";
      $G_TABLE .= "<table width='100%' cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>";
      $G_TABLE .= "<colgroup><col width='50%' /><col width='*' /></colgroup><thead><tr>";
      $G_TABLE .= "<th style='background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>담보내용</th>";
      $G_TABLE .= "<th style='background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>보험가입금액 / 보상한도액</th></tr></thead><tbody>";

      foreach($plan_guarantee_all as $p_guarantee){
        if ($p_guarantee["guarantee_seq"] == $guarantee_seq){
            if ($p_guarantee["g_amount"] && $p_guarantee["g_amount"] != "NOT-AVAILABLE") {
              $G_TABLE .= "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" ;
              $G_TABLE .= $p_guarantee["service_name"] ;
              $G_TABLE .= "</th><td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" ;
              $G_TABLE .= $p_guarantee["g_amount"] . "</td></tr>";
            }
        }
      }

      $G_TABLE .= "</tbody></table>";

      if ($chk_fly_type == "A") {
        $G_TABLE .= "<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:0px 0 5px 0;'>";
        $G_TABLE .= "상기 고객을 피보험자로 하여 아래와 같이 <span style=\"color: red;\">" . $plan_sign_image[$page_loop_idx]["subject"] . "</span>에 가입되었음을 확인합니다.</h3>";
        $G_TABLE .= "<p style='font-size:12px;margin:8px 0 5px 0;color:#333;'>";
        $G_TABLE .= "보험계약의 자세한 사항에 대하여는 <span style=\"color: red;\">" . $plan_sign_image[$page_loop_idx]["subject"] . "</span> 약관을 참조하시기 바랍니다. <br/>";
        $G_TABLE .= "코리아 어시스턴스는 다음의 담보내용과 보상한도액에 의거하여 서비스를 제공합니다.<br/>";
        $G_TABLE .= "이 보험계약은 보험계약자 및 상품가격 납부자가 코리아어시스턴스인 단체보험으로 예금자보험법에 의거, 예금자보호대상에서 제외됩니다.</P>";
        $G_TABLE .= "";
        $G_TABLE .= "";
        $G_TABLE .= "<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:0px 0 5px 0;'>유의사항</h3>";
        $G_TABLE .= "<ul style='text-align: left; margin: 0px 0 0 20px;padding: 0;line-height: 1.75;color: #333; font-size: 13px;'>";
        $G_TABLE .= "  <li>만 15세 미만은 상해사망 / 질병사망 담보가 적용되지 않습니다. (상법 732조)</li>";
        $G_TABLE .= "  <li>해외의료실비";
        $G_TABLE .= "    <dl style='margin:0;padding:0 0 0 15px;'>";
        $G_TABLE .= "      <dd>- 피보험자가 해외여행 중에 입은 상해 또는 질병으로 인하여 해외의료기관에서 치료를 받은 경우 가입한도 내 실제 부담한 의료비를 보상합니다.</dd>";
        $G_TABLE .= "    </dl>";
        $G_TABLE .= "  </li>";
        $G_TABLE .= "  <li>국내의료실비(급여/비급여)";
        $G_TABLE .= "    <ol style='margin:0;padding:0 0 0 15px;'>";
        $G_TABLE .= "      <li>급여";
        $G_TABLE .= "        <dl style='margin:0;padding:0 0 0 15px;'>";
        $G_TABLE .= "          <dd>- 피보험자가 해외여행 중에 입은 상해 또는 질병으로 인하여 국내의료기관에 입원 또는 통원하여 급여 치료를 받거나 처방조제를 받은 경우에 보상합니다.<br />";
        $G_TABLE .= "            &nbsp&nbsp<b>(입원 : 의료급여 중 본인이 부담한 의료비의 80%보상 / 통원 및 처방조제 : 병원규모별 1~2만원과 보장대상 의료비의 20%중 큰 금액을 차감한 금액 보상)</b></dd>";
        $G_TABLE .= "        </dl>";
        $G_TABLE .= "      </li>";
        $G_TABLE .= "      <li>비급여 (3대 비급여 제외)";
        $G_TABLE .= "        <dl style='margin:0;padding:0 0 0 15px;'>";
        $G_TABLE .= "          <dd>- 피보험자가 해외여행 중에 입은 상해 또는 질병으로 인하여 국내의료기관에 입원 또는 통원하여 비급여 치료를 받거나 처방조제를 받은 경우에 보상합니다.<br />";
        $G_TABLE .= "            &nbsp&nbsp<b>(입원 : 비의료급여 중 본인이 부담한 의료비의 70%보상 / 통원 및 처방조제 : 병원규모별 3만원과 보장대상 의료비의 30%중 큰 금액을 차감한 금액 보상)</b></dd>";
        $G_TABLE .= "        </dl>";
        $G_TABLE .= "      </li>";
        $G_TABLE .= "    </ol>";
        $G_TABLE .= "  </li>";
        $G_TABLE .= "  <li>휴대품 파손/도난";
        $G_TABLE .= "    <dl style='margin:0;padding:0 0 0 15px;'>";
        $G_TABLE .= "      <dd>- 여행중 휴대한 물품 파손, 도난인 경우 사용기간을 감안한 감가상각한금액, 파손의 경우 수리비용 보상<br />&nbsp&nbsp(휴대물품당 1개/1쌍당 20만원, 자기부담금 1만원. 현금, 신용카드, 여권은 보상되지 않음)</dd>";
        $G_TABLE .= "    </dl>";
        $G_TABLE .= "  </li>";
        $G_TABLE .= "</ul>";
      } else {
        $G_TABLE = "<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:0px 0 5px 0;'>상기 고객은 <span style=\"color: red;\">24시간 해외 긴급이후송 서비스</span>에 가입되었음을 확인합니다.</h3>";
        $G_TABLE .= "<p style='font-size:12px;margin:8px 0 5px 0;color:#333;'>자세한 사항에 대하여는 <span style=\"color: red;\">서비스 안내서</span>를 참조하시기 바랍니다.";
        $G_TABLE .= "플라잉닥터스는 비즈인사이트의 해외긴급이후송 서비스 브랜드이며,<br /> 비즈인사이트는 위의 담보내용과 보상한도액에 의거하여 서비스를 제공해 드립니다.</p>";
      }

      $G_TABLE .= "<p style=\"text-align:right; padding:5px 20px;\"><img src='[DROOT]/_data/board/ins_list/".$plan_sign_image[$page_loop_idx]["kor_img"]."' align='absmiddle'></p>";
    }
    $page_loop_idx++;
  }
}
///////////////////////////////////////////////////////////////////////////
//보장내역
// $key = 0;
// $G_TABLE = "";
// $ins_sign_img = "";
// $ins_noti = "";
// if ($certType !=  "S") {
//   if ($arrayGORow) {
//     while ($row_g = $dbcon->fetch_array($RS_G)) {
//       if ($chk_lang == "E") {
//         if ($row_g["g_amount_certificate"]) {
//           $G_LIST .= "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" . $arrayGORow[$key] . "</th><td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" . $row_g["g_amount_certificate"] . "</td></tr>";
//         }
//       } else {
//         if ($row_g["g_amount"]) {
//           $G_LIST .= "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" . $row_g["g_name"] . "</th><td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" . $row_g["g_amount"] . "</td></tr>";
//         }
//       }
//       $key++;
//     }
//   }
  
//   if ($G_LIST) {
//     if ($chk_lang == "E") {
//       $G_TABLE = "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:20px 0 5px 0;'>Certification of " . $row_ins["ins_name_en"] . "</h5>";
//       $G_TABLE .= "<p style='margin:8px 0 5px 0;font-size:12px;font-weight:400;color:#333;'>";
//       $G_TABLE .= "Above subscriber is certified to insured of <span style='font-weight:400;color:red;'>" . $row_ins["ins_name_en"] . ".</span><br/>";
//       $G_TABLE .= "Please refer to the KOREA ASSISTANCE provides services based on the following collateral and compensation limits.<br/><br/>";
//       $G_TABLE .= "Coverage: If mentioned, this coverage includes<br/>";
//       $G_TABLE .= "the medical expense caused by Covid-19 infection, infectious disease up to the coverage limit of this policy<br/>";
//       $G_TABLE .= "</p>";
//       $G_TABLE .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>";
//       $G_TABLE .= "<colgroup><col width='50%' /><col width='*' /></colgroup>";
//       $G_TABLE .= "<thead>  <tr><th style='background-color: #f2f7ff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Details</th>";
//       $G_TABLE .= "<th style='background-color: #fff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Limit</th>";
//       $G_TABLE .= "</tr></thead><tbody>";
//       $ins_sign_img = "<p style='font-size:12px;margin:8px 0 18px 0;color:#333;'></p>";
//       $ins_sign_img .= "<p style='font-size:12px;margin:8px 0 5px 0;color:#333;'>This insurance contract is a group insurance which KOREA ASSISTANCE is policy holder and pays premium.</p>";
//       $ins_sign_img .= "<div style=\"font-size:12px;color:#333;\">■ Notice</div>";
//       $ins_sign_img .= "<div style=\"margin-left: 20px;font-size:12px;color:#333;\">Death Collateral does not apply to those under 15-years-old.</div>";
//       $ins_sign_img .= "<div style=\"margin-left: 20px;font-size:12px;color:#333;\">Deductible : If not mentioned, refer to insurance policy wording.</div>";
//       $ins_sign_img .= "<p style=\"text-align:right; padding:10px 20px;\"><img src='[DROOT]/_data/board/ins_list/[SIGNIMG]' align='absmiddle'></p>";
//     } else {
//       $G_TABLE = "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 32px;border-radius: 8px;margin:20px 0 20px 0;'>보험사 국문명 가입증명서</h5>";
//       $G_TABLE .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>";
//       $G_TABLE .= "<colgroup><col width='50%' /><col width='*' /></colgroup>";
//       $G_TABLE .= "<thead>  <tr><th style='background-color: #f2f7ff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>보장내역</th>";
//       $G_TABLE .= "<th style='background-color: #fff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>보장한도</th>";
//       $G_TABLE .= "</tr></thead><tbody>";
//       $ins_sign_img = "<p style=\"text-align:right; padding:5px 20px;\"><img src='[DROOT]/_data/board/ins_list/[SIGNIMG]' align='absmiddle'></p>";
//       if ($chk_fly_type == "A") {
//         $ins_noti = "<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:0px 0 5px 0;'>";
//         $ins_noti .= "상기 고객을 피보험자로 하여 아래와 같이 <span style=\"color: red;\">" . $row_ins["subject"] . "</span>에 가입되었음을 확인합니다.</h3>";
//         $ins_noti .= "<p style='font-size:12px;margin:8px 0 5px 0;color:#333;'>";
//         $ins_noti .= "보험계약의 자세한 사항에 대하여는 <span style=\"color: red;\">" . $row_ins["subject"] . "</span> 약관을 참조하시기 바랍니다. <br/>";
//         $ins_noti .= "코리아 어시스턴스는 다음의 담보내용과 보상한도액에 의거하여 서비스를 제공합니다.<br/>";
//         $ins_noti .= "이 보험계약은 보험계약자 및 상품가격 납부자가 코리아어시스턴스인 단체보험으로 예금자보험법에 의거, 예금자보호대상에서 제외됩니다.</P>";
//         $ins_noti .= "";
//         $ins_noti .= "";
//         $ins_noti .= "<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:0px 0 5px 0;'>유의사항</h3>";
//         $ins_noti .= "<ul style='text-align: left; margin: 0px 0 0 20px;padding: 0;line-height: 1.75;color: #333; font-size: 13px;'>";
//         $ins_noti .= "  <li>만 15세 미만은 상해사망 / 질병사망 담보가 적용되지 않습니다. (상법 732조)</li>";
//         $ins_noti .= "  <li>해외의료실비";
//         $ins_noti .= "    <dl style='margin:0;padding:0 0 0 15px;'>";
//         $ins_noti .= "      <dd>- 피보험자가 해외여행 중에 입은 상해 또는 질병으로 인하여 해외의료기관에서 치료를 받은 경우 가입한도 내 실제 부담한 의료비를 보상합니다.</dd>";
//         $ins_noti .= "    </dl>";
//         $ins_noti .= "  </li>";
//         $ins_noti .= "  <li>국내의료실비(급여/비급여)";
//         $ins_noti .= "    <ol style='margin:0;padding:0 0 0 15px;'>";
//         $ins_noti .= "      <li>급여";
//         $ins_noti .= "        <dl style='margin:0;padding:0 0 0 15px;'>";
//         $ins_noti .= "          <dd>- 피보험자가 해외여행 중에 입은 상해 또는 질병으로 인하여 국내의료기관에 입원 또는 통원하여 급여 치료를 받거나 처방조제를 받은 경우에 보상합니다.<br />";
//         $ins_noti .= "            &nbsp&nbsp<b>(입원 : 의료급여 중 본인이 부담한 의료비의 80%보상 / 통원 및 처방조제 : 병원규모별 1~2만원과 보장대상 의료비의 20%중 큰 금액을 차감한 금액 보상)</b></dd>";
//         $ins_noti .= "        </dl>";
//         $ins_noti .= "      </li>";
//         $ins_noti .= "      <li>비급여 (3대 비급여 제외)";
//         $ins_noti .= "        <dl style='margin:0;padding:0 0 0 15px;'>";
//         $ins_noti .= "          <dd>- 피보험자가 해외여행 중에 입은 상해 또는 질병으로 인하여 국내의료기관에 입원 또는 통원하여 비급여 치료를 받거나 처방조제를 받은 경우에 보상합니다.<br />";
//         $ins_noti .= "            &nbsp&nbsp<b>(입원 : 비의료급여 중 본인이 부담한 의료비의 70%보상 / 통원 및 처방조제 : 병원규모별 3만원과 보장대상 의료비의 30%중 큰 금액을 차감한 금액 보상)</b></dd>";
//         $ins_noti .= "        </dl>";
//         $ins_noti .= "      </li>";
//         $ins_noti .= "    </ol>";
//         $ins_noti .= "  </li>";
//         $ins_noti .= "  <li>휴대품 파손/도난";
//         $ins_noti .= "    <dl style='margin:0;padding:0 0 0 15px;'>";
//         $ins_noti .= "      <dd>- 여행중 휴대한 물품 파손, 도난인 경우 사용기간을 감안한 감가상각한금액, 파손의 경우 수리비용 보상<br />&nbsp&nbsp(휴대물품당 1개/1쌍당 20만원, 자기부담금 1만원. 현금, 신용카드, 여권은 보상되지 않음)</dd>";
//         $ins_noti .= "    </dl>";
//         $ins_noti .= "  </li>";
//         $ins_noti .= "</ul>";
//       } else {
//         $ins_noti = "<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:0px 0 5px 0;'>상기 고객은 <span style=\"color: red;\">24시간 해외 긴급이후송 서비스</span>에 가입되었음을 확인합니다.</h3>";
//         $ins_noti .= "<p style='font-size:12px;margin:8px 0 5px 0;color:#333;'>자세한 사항에 대하여는 <span style=\"color: red;\">서비스 안내서</span>를 참조하시기 바랍니다.";
//         $ins_noti .= "플라잉닥터스는 비즈인사이트의 해외긴급이후송 서비스 브랜드이며,<br /> 비즈인사이트는 위의 담보내용과 보상한도액에 의거하여 서비스를 제공해 드립니다.</p>";
//       }
//     }
//     $G_TABLE .= $G_LIST . "</tbody></table>";
//   }
// }

if ($chk_fly_type == "A") {
  $chk_fly_type_name = "코리아어시스턴스";
  $FOOTER = "<table width='100%' cellspacing='0' cellpadding='0' border='0' style='background:#29354c; padding:20px;'>";
  $FOOTER .= "<tr valign='middle'><td style='text-align:left;'><img src='[DROOT]/html/images/footer-logo1.png' align='absmiddle'></td>";
  $FOOTER .= "<td style='text-align:right;color:#fff;'>https://koreaassistance.co.kr<br>";
  $FOOTER .= "<strong style='font-size:14px;'>Tel: +82 2 360 2545</strong><br>B1, 7,Chungjeong-ro, Seodaemun-gu, Seoul, Korea</td></tr></table>";
} else {
  $chk_fly_type_name = "플라잉닥터스";
  $FOOTER = "<table width='100%' cellspacing='0' cellpadding='0' border='0' style='background:#29354c; padding:10px 20px;'>";
  $FOOTER .= "<tr valign='middle'><td style='text-align:left;'><img src='[DROOT]/html/images/footer-logo2.png' align='absmiddle'></td>";
  $FOOTER .= "<td style='text-align:right;color:#fff;'>https://flyingdoctors.co.kr <br>";
  $FOOTER .= "<strong style='font-size:14px;'>Tel: +82 2 360 2525</strong><br>F8, 7,Chungjeong-ro, Seodaemun-gu, Seoul, Korea</td></tr></table>";
}

$chHtml  = str_replace("[USER_NAME]",$USER_NAME,$chHtml);
$chHtml  = str_replace("[USER_ENAME]",$USER_ENAME,$chHtml);
$chHtml  = str_replace("[CONTRACTOR_KR]",$CONTRACTOR_KR,$chHtml);
$chHtml  = str_replace("[CONTRACTOR_EN]",$CONTRACTOR_EN,$chHtml);
$chHtml  = str_replace("[USER_BIRTH]",$USER_BIRTH,$chHtml);
$chHtml  = str_replace("[USER_PHONE]",all_seed_dec($row["o_phone"]),$chHtml);
$chHtml  = str_replace("[USER_EMAIL]",$USER_EMAIL,$chHtml);
$chHtml  = str_replace("[PLAN_NAME]",$row_r["plan_name"],$chHtml);
$chHtml  = str_replace("[PURPOSE]",$row_r["purpose"],$chHtml);
$chHtml  = str_replace("[ENG_PLAN_NAME]",$row_product["ext9"],$chHtml); //영문 플랜명(국문하고 다름)
$chHtml  = str_replace("[SERVICE_TYPE]",$Arr_txt_plus[$row_r["chk_service"]],$chHtml);
$chHtml  = str_replace("[ORDERNO]",$row_r["orderno"],$chHtml);
$chHtml  = str_replace("[USER_TYPE]",$row_r["pr_name"],$chHtml);
$chHtml  = str_replace("[PR_AMOUNT]",number_format($row["t_amount"]).$won,$chHtml);
$chHtml  = str_replace("[USER_JNAME]",all_seed_dec($row["o_name"]),$chHtml);    //피보험자
$chHtml  = str_replace("[USER_JENAME]",all_seed_dec($row["o_name_en"]),$chHtml);    //피보험자
$chHtml  = str_replace("[STOCK_NO]",$row_plan["stock_isdn"],$chHtml);    //증권번호
$chHtml  = str_replace("[INS_PERIOD]",$INS_PERIOD,$chHtml);      // 보험기간
$chHtml  = str_replace("[NATION_NAME]",$row_r["join_nation_name"],$chHtml);    //해외체류국가
$chHtml  = str_replace("[CHK_SERVICE]",$CHK_SERVICE,$chHtml);   //서비스내역
$chHtml  = str_replace("[G_LIST]",$G_TABLE,$chHtml);              //보험담보내용
$chHtml  = str_replace("[INS_NAME]",$row_ins["subject"],$chHtml);  
$chHtml  = str_replace("[CONTRACTOR_ROW]",$CONTRACTORROW,$chHtml);  //계약자명
$chHtml  = str_replace("[INS_NAME_EN]",$row_ins["ins_name_en"],$chHtml);  //보험사 영문명
$chHtml  = str_replace("[SERVICEIMG]", $service_img, $chHtml);  //공급업자 서명  
$chHtml  = str_replace("[INSSIGNIMG]", $ins_sign_img, $chHtml); //사인이미지
// if($chk_lang=="E"){
//   if($row_ins["imgfile2"]){
//     $chHtml  = str_replace("[SIGNIMG]",$row_ins["imgfile2"],$chHtml); //사인이미지
//   }
// } else {
//   if($row_ins["signimg"]) {
//     $chHtml  = str_replace("[SIGNIMG]",$row_ins["signimg"],$chHtml); //사인이미지
//   }
// }
$chHtml  = str_replace("[FLYIMG]", $FLYIMG, $chHtml);  //공급업자 서명
$chHtml  = str_replace("[CHK_KOR_SERVICE]", $chk_kor_service, $chHtml);
$chHtml  = str_replace("[INS_NOTI]", $ins_noti, $chHtml);
$chHtml  = str_replace("[CHK_FLY_TYPE_NAME]", $chk_fly_type_name, $chHtml);  //상단 이름 구분
$chHtml  = str_replace("[FOOTER]",$FOOTER,$chHtml);  //하단 문구
$chHtml  = str_replace("[DROOT]","",$chHtml);              //이미지를 위한 루트경로

//dompdf 버전 문제로 인한 html 소스 공백 제거
$html = preg_replace('/\r\n|\r|\n/','',$chHtml);
echo $html;

//    $pdf_filename = "인슈플러스_".$orderno."_"."".$USER_NAME.$chk_lang.".pdf";      //파일명처리
//        //html 코드를 pdf로 변환 (변경하면 안됨)
//        $dompdf = new \DOMPDF();
//    $webRoot = $_SERVER["DOCUMENT_ROOT"];
//        $dompdf->set_paper( 'A4', 'portrait' );
//        $dompdf->load_html($html);
//        $dompdf->render();
//        $dompdf->stream($pdf_filename); //저장되는 파일 이름을 설정한다.
//    $output = $dompdf->output();
//        error_reporting($error_level);
?>

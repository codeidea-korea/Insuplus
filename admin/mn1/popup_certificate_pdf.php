<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.mail.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_util/dompdf/dompdf_config.inc.php";
//admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

$group_join_type = "";
$arr_join_seq = array();
if($group_join_id) {
	$SQL_J = "SELECT seq, orderno FROM tbl_order_list_join where group_join_id = '".$group_join_id."' ";
	$RS_J = $dbcon->query($SQL_J);
	while($row = $dbcon->fetch_array($RS_J)) {
		$arr_join_seq[] = $row;
	}
} else {
	$info = array();
	$info["seq"] = $join_seq;
	$info["orderno"] = $orderno;
	$arr_join_seq[] = $info;
}

foreach($arr_join_seq as $join_row){
  $result = 0;
	$join_seq = $join_row["seq"];
	$orderno = $join_row["orderno"];
  // 가입보험내역 검색
  $SQL_V = "select * from tbl_order_list A where A.orderno in (select orderno from tbl_order_list_join where seq=".$join_seq." and orderno='".$orderno."' )";
  $RS_V = $dbcon -> query($SQL_V);
  if (!$RS_V){
    echo "<script>alert('해당 보험내역이 없습니다.');</script>";
    exit;
  }
  $row_r = $dbcon -> fetch_array($RS_V);

  //단체가입 여부 조회
  $SQL_G = "select * from tbl_order_group_join_list where group_join_id = '".$row_r["group_join_id"]."'";
  $RS_G = $dbcon -> query($SQL_G);
  if ($RS_G){
    $row_group = $dbcon -> fetch_array($RS_G);
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

  //보험사정보
  if($row_plan["ins_cd"] > 0){
    $SQL_INS  = " SELECT subject, ins_name_en, imgfile2 ";
    $SQL_INS .= ", (SELECT file_realname FROM tbl_file WHERE bc_id = 'ins_list' AND seq = '".$row_plan["ins_cd"]."' ORDER BY idx desc LIMIT 0,1) as signimg ";
    $SQL_INS .= " FROM tbl_board_ins_list WHERE seq = '".$row_plan["ins_cd"]."' "; //사인이미지 수정
    $RS_INS = $dbcon -> query($SQL_INS);
    $row_ins = $dbcon -> fetch_array($RS_INS);
    $arr_img_info = setFileName($row_ins["imgfile2"])[0];
    $row_ins["imgfile2"] = $arr_img_info[1];
  }

  //인슈, 플라잉 템플릿 구분 처리
  $url = "./pdf_join_certification_table.php";

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
  #가입자 정보
  /*
  'USER_NAME'     : 가입자명
  'USER_ENAME'    : 가입자 영문명
  'USER_BIRTH'    : 생일 Ex 19990101
  'USER_PHONE'    : 연락처 01011112222
  'USER_EMAIL'    : 이메일 mail1@mail.com
  'INS_PERIOD'    : 보험기간 단기 일단위, 장기 월단위로 구분
  'STOCK_NO'      : 증권번호
  'ENG_PLAN_NAME' : 영문 플랜명(국문하고 다름)
  'ORDERNO'       : 주문번호
  'CONTRACTOR_KR' : 계약자명
  'CONTRACTOR_EN' : 계약자 영문명
  'PLAN_NAME'     : 플랜명
  'NATION_NAME'   : 국가명
  'PURPOSE'       : 출국목적
  */
  $JOIN_INFO = array(
      'USER_NAME'     => all_seed_dec($row["o_name"]) 
    , 'USER_ENAME'    => all_seed_dec($row["o_name_en"])
    , 'USER_BIRTH'    => substr(all_seed_dec($row["o_isdn1"]),2,6)."-".substr(all_seed_dec($row["o_isdn2"]),0,1)."******"
    , 'USER_PHONE'    => all_seed_dec($row["o_phone"])
    , 'USER_EMAIL'    => all_seed_dec($row_r["o_email1"])."@".all_seed_dec($row_r["o_email2"])
    , 'INS_PERIOD'    => ""
    , 'STOCK_NO'      => $row_plan["stock_isdn"]
    , 'ENG_PLAN_NAME' => $row_product["ext9"]
    , 'ORDERNO'       => $row_r["orderno"]
    , 'CONTRACTOR_KR' => ""
    , 'CONTRACTOR_EN' => ""
    , 'USER_TYPE'     => $row_r["pr_name"]
    , 'PLAN_NAME'     => $row_r["plan_name"]
    , 'NATION_NAME'   => $row_r["join_nation_name"]
    , 'PURPOSE'       => $row_r["purpose"]
    , 'AMOUNT'        =>   $row_cont_info["t_amount"] 
  );

  if (is_null($row["group_join_type"]) || $row["group_join_type"] === "B2C") {
    $group_join_type = "B2C";
  } else {
    $group_join_type = "B2B";
  }

  $USER_NAME   = all_seed_dec($row["o_name"]);
  $USER_ENAME   = all_seed_dec($row["o_name_en"]);

  $USER_BIRTH = substr(all_seed_dec($row["o_isdn1"]),2,6)."-".substr(all_seed_dec($row["o_isdn2"]),0,1)."******";
  $USER_EMAIL = all_seed_dec($row_r["o_email1"])."@".all_seed_dec($row_r["o_email2"]);

  if($row_group){ //단체가입 여부에 따른 계약자명 처리
    $CONTRACTOR_KR = all_seed_dec($row_group["o_name"]);
    $CONTRACTOR_EN = all_seed_dec($row_group["o_name_en"]);
    $JOIN_INFO["CONTRACTOR_KR"] = all_seed_dec($row_group["o_name"]);
    $JOIN_INFO["CONTRACTOR_EN"] = all_seed_dec($row_group["o_name_en"]);
  } else {
    $CONTRACTOR_KR = all_seed_dec($row_cont_info["o_name"]);
    $CONTRACTOR_EN = all_seed_dec($row_cont_info["o_name_en"]);
    $JOIN_INFO["CONTRACTOR_KR"] = all_seed_dec($row_cont_info["o_name"]);
    $JOIN_INFO["CONTRACTOR_EN"] = all_seed_dec($row_cont_info["o_name_en"]);
  }

  if($row_r["chk_p"] == "Y") {
    $INS_PERIOD = $row_r["s_date"]." ".$row_r["s_date_time"].":00 ~ ".$row_r["e_date"]." ".$row_r["e_date_time"].":00";
    $JOIN_INFO["INS_PERIOD"] = $row_r["s_date"]." ".$row_r["s_date_time"].":00 ~ ".$row_r["e_date"]." ".$row_r["e_date_time"].":00";
  } else if($row_r["chk_p"] == "N") {
    $INS_PERIOD = $row_r["s_date"]." ~ ".$row_r["e_date"];
    $JOIN_INFO["INS_PERIOD"] = $row_r["s_date"]." ~ ".$row_r["e_date"];
  }

  $param = array();
	$param["name"] = $o_name;
	$param["pr_name"] = $row_r["pr_name"]." ".$row_r["ins_name"]." ".$row_r["plan_name"];
	$param["plan_name"] = $row_r["plan_name"];
	$param["period"] = $row_r["s_date"]." ~ ".$row_r["e_date"];
	$param["purpose"] = $row_r["purpose"];
	$param["chk_service"] = $row_r["chk_service"];
	$param["domain"] = getDomain();
	$param["amount"] = $row_r["ins_amount"]+$row_r["service_amount"];
	$param["t_amount"] = $row["t_amount"];
	$param["ins_seq"] = $row_r["ins_file_cd"]; //약관파일 고유번호
	$param["ins_term1"] = $row_plan["ins_term1_seq"];; //보험약관 파일 고유번호
	$param["ins_term2"] = $row_plan["ins_term2_seq"];; //보험약관 파일 고유번호
	$param["service_seq"] = $row_plan["service_cd"]; //서비스 약관파일 고유번호
	$param["service_name"] = $row_r["service_name"]; //서비스명
	$email = $USER_EMAIL;

  ///////////////////////////////////////////////////////////////////////////
  // 2023-07-05 modified by kyle
  ///////////////////////////////////////////////////////////////////////////

  //가입자 정보
  $JOIN_INFO_TABLE ="";
  // 서비스 내역
  $CHK_SERVICE = "";
  // 보장 내역
  $G_TABLE = "";

  //상단 배너 인슈플러스, 플라잉다터스 구분
  $HEADER = "";
  //가입증명서 정보
  $BODY = "";
  //가입증명서 발급자 정보
  $FOOTER = "";
  $cert_type = "";

  if($group_join_type === "B2C") { //일반 가입자 인슈플러스 템플릿
    $cert_type = "인슈플러스";
    if($certType != "I") {
      $plan_service_all = getPlanService($row_r["pr_cd"], $row_r["plan_cd"]);
      if ($chk_lang == "E") {
        //영문 인슈플러스
        $CHK_SERVICE = "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:0 0 12px 0;'>Certificate of INSUPLUS Service</h5>";
        $CHK_SERVICE = "<table width='100%' cellspacing='0' cellpadding='0' border='0' style='border-top: solid 2px #595959;border-bottom: solid 2px #595959; margin:0 0 14px 0;'>";
        $CHK_SERVICE .= "<colgroup><col width='40%'><col width='60%'></colgroup>";
        $CHK_SERVICE .= "<thead><tr><th style=\"background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;\">Coverage</th>";
        $CHK_SERVICE .= "<th style=\"background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;\">Coverage Limit</th></tr>";
        $CHK_SERVICE .= "</thead><tbody>";

        foreach($plan_service_all as $insuplus){
          if ( $insuplus["e_amount"] != "NOT-AVAILABLE"){
            $CHK_SERVICE .= "<tr><th width='350px' style=\"background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;\">" ;
            $CHK_SERVICE .= $insuplus["service_name_en"];
            $CHK_SERVICE .= "</th><td style=\"background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;\">" ;
            $CHK_SERVICE .= $insuplus["e_amount"] . "</td></tr>";
          }
        }

        $CHK_SERVICE .= "<h3 style='font-size:15px;font-weight: 400; font-family: NanumGothic;line-height: 1.5;padding:0'>Above subscriber is certified INSUPLUS Service</h3>";
        $CHK_SERVICE .= "  <p>By purchasing this item, you agree to the INSUPLUS Terms of Service and applicable insurance Terms of Conditions.<br/>";
        $CHK_SERVICE .= "  Please refer to INSUPLUS Terms of Service and applicable insurance Terms and Conditions for details of this contract.</p>";
        $CHK_SERVICE .= "<table style='width:100%; text-align:right; padding:0 0 20px 0;'>";
        $CHK_SERVICE .= "<tr><td><img src='[DROOT]/html/images/korea_assistance_stamp.png' align='absmiddle'></td></tr></table>";
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
            $CHK_SERVICE .= "</th><td style=\"background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;\">" ;
            $CHK_SERVICE .= $insuplus["k_amount"] . "</td></tr>";
          }
        }
        $CHK_SERVICE .= "</tbody></table>";
        $CHK_SERVICE .=  "<h3 style='font-size:15px;font-weight: 400; font-family:NanumGothic;line-height: 1.5;padding:0'>상기 가입자가 인슈플러스 서비스에 가입되었음을 증명합니다.</h3>";
        $CHK_SERVICE .=  "<ul style='text-align: left; padding:10px 20px;line-height: 1.5;color: #333; font-size: 13px;'>";
        $CHK_SERVICE .=  "  <li>이 상품의 구매는 인슈플러스 서비스 약관 및 해당 보험약관에 동의하였음을 의미합니다.</li>";
        $CHK_SERVICE .=  "  <li>이 상품계약의 자세한 사항은 인슈플러스 서비스 약관 및 해당 보험약관을 참조하시기 바랍니다.</li>";
        $CHK_SERVICE .=  "  <li>국가 혹은 지역별 플라잉닥터스 제휴병원은 상황에 따라 수시로 변경될 수 있습니다.</li>";
        $CHK_SERVICE .=  "  <li>이로 인해 병원비 대신지불 서비스가 제공되지 않는 경우 고객이 치료비 납입 후, 보상 청구 하여야 합니다.</li>";
        $CHK_SERVICE .=  "  <li>이 상품은 플라잉닥터스에서 서비스를 제공하며, 국내 보험사에서 보험담보를 보장합니다.</li>";
        $CHK_SERVICE .=  "  <li>플라잉닥터스는 하기 보험가입증명서의 보험가입금액에 의거하여 서비스를 제공 합니다.</li>";
        $CHK_SERVICE .=  "  <li>해외의료비를 보장하는 복수의 보험계약에 가입되어 있음이 확인된 경우 병원비 대신지불 서비스가 제한됩니다.</li>";
        $CHK_SERVICE .=  "</ul>";
        $CHK_SERVICE .=  "<table style='width:100%; text-align:right; padding:0 0 20px 0;'>";
        $CHK_SERVICE .=  "<tr><td><img src='[DROOT]/html/images/korea_assistance_stamp.png' align='absmiddle'></td></tr></table>";
      }
    }
    ///////////////////////////////////////////////////////////////////////////
    $plan_guarantee_seq = array();
    $arrayGORow = array();
    $plan_guarantee_all = getPlanGuarantee($row_r["pr_cd"], $row_r["plan_cd"]);
    $plan_sign_image = getPlanInsurance($row_r["plan_cd"]);

    foreach($plan_guarantee_all as $ell){
      if (!in_array($ell["guarantee_seq"], $plan_guarantee_seq)){
        if($ell["guarantee_opt_seq"] == "" && $row_product["ext4"]){
          $SQL_GO = "select * from tbl_board_guarantee_opt where list_seq=".$row_product["ext4"]."";
          $RS_GO = $dbcon -> query($SQL_GO);
          $cnt=0;
          while($row_g_opt = $dbcon -> fetch_array($RS_GO)){
            $arrayGORow[$cnt] = $row_g_opt["service_name_en"];
            $cnt++;
          }
        }
        $plan_guarantee_seq[] = $ell["guarantee_seq"];
      }
    }

    if($certType != "S" && count($plan_guarantee_seq) > 0) {
      $page_loop_idx = 0;

      foreach($plan_guarantee_seq as $guarantee_seq){
        // 사용하지 않는 보장내역 제외
        if ( ($row_plan["ext1"] != 'N' && $guarantee_seq == $row_product["ext4"]) || ($row_plan["ext2"] != 'N' && $guarantee_seq == $row_product["ext10"]) ){
          if ($chk_lang == "E") {
            $G_TABLE .= "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:20px 0px 0px 0px;'>Certificate of ".$plan_sign_image[$page_loop_idx]["ins_name_en"]."</h5>";
            $G_TABLE .= "<h3 style='font-size:13px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:5px 0 5px 0;'>Above subscriber is certified to insured of <span style='color:#dc3347'>".$plan_sign_image[$page_loop_idx]["ins_name_en"]."</span><br/>";
            $G_TABLE .= "Please refer to the Flying Doctors provides services based on the following collateral and compensation limits.<br/><br/>";
            $G_TABLE .= "Coverage: If mentioned, this coverage includes the medical expense caused by Covid-19 infection, infectious disease up to the coverage limit of this policy</h3>";
            $G_TABLE .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>";
            $G_TABLE .= "<colgroup><col width='50%' /><col width='*' /></colgroup><thead>";
            $G_TABLE .= "  <tr><th style='background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage</th>";
            $G_TABLE .= "    <th style='background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Limit</th></tr>";
            $G_TABLE .= "</thead><tbody>";
  
            $cnt=0;
            foreach($plan_guarantee_all as $p_guarantee){
              if ($p_guarantee["guarantee_seq"] == $guarantee_seq){
                if(count($arrayGORow) > 0) {
                  if ($p_guarantee["g_amount_certificate"]) {
                    $G_TABLE .= "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>";
                    $G_TABLE .= $arrayGORow[$cnt];
                    $G_TABLE .= "</th><td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" ;
                    $G_TABLE .= $p_guarantee["g_amount_certificate"] . "</td></tr>";
                  }
                } else {
                  if ($p_guarantee["g_amount_certificate"]) {
                    $G_TABLE .= "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>";
                    $G_TABLE .= $p_guarantee["service_name_en"] ;
                    $G_TABLE .= "</th><td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" ;
                    $G_TABLE .= $p_guarantee["g_amount_certificate"] . "</td></tr>";
                  }
                }
              }
              $cnt++;
            }
  
            $G_TABLE .= "</tbody></table>";
            $G_TABLE .= "<p style='font-size:12px;margin:8px 0 7px 0;color:#333;'>This insurance contract is a group insurance which Flying Doctors is policy holder and pays premium.</p>          ";
            $G_TABLE .= "<h3 style='font-size:13px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:0px 0 5px 0;'>■ Notice</h3>";
            $G_TABLE .= "<ul style='text-align: left; margin: 0px 0px 0px 20px;padding: 0;line-height: 1.75;color: #333; font-size: 11px;'>";
            $G_TABLE .= "  <li>Death Collateral does not apply to those under 15-years-old.</li>";
            $G_TABLE .= "  <li>Deductible : If not mentioned, refer to insurance policy wording.</li>";
            $G_TABLE .= "  <li>Countries eligible for membership and guarantee : Make sure that your country is not affected by travel restrictions or prohibitions. Compensation cannot be provided if you travel to a country designated by the Ministry of Foreign Affairs under a red alert (recommendation for withdrawal) or a black warning (prohibition of travel).</li>";
            $G_TABLE .= "</ul>";
            if(is_null($row_ins)) {
              $G_TABLE .= "<p style=\"text-align:right; padding:20px 20px;\"><img src=\"[DROOT]/_data/board/ins_list/".setFileName($plan_sign_image[$page_loop_idx]["eng_img"])[0][1]."\" align=\"absmiddle\"><p>";
            } else {
              $G_TABLE .= "<p style=\"text-align:right; padding:20px 20px;\"><img src=\"[DROOT]/_data/board/ins_list/".$row_ins["imgfile2"]."\" align=\"absmiddle\"><p>";
            }
          } else {
            $G_TABLE .= '<h5 style="font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 32px;border-radius: 8px;margin:20px 0 20px 0;">가입증명서</h5>
                          <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-top:solid 2px #595959;border-bottom:solid 2px #595959;">
                            <colgroup>
                              <col width="20%" />
                              <col width="30%" />
                              <col width="20%" />
                              <col width="30%" />
                            </colgroup>
                            <tr>
                              <th style="background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:14px;font-weight:400;text-align:center;">피보험자</th>
                              <td style="background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:14px;font-weight:400;text-align:left;">'.$JOIN_INFO["USER_NAME"].'</td>
                              <th style="background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:14px;font-weight:400;text-align:center;">증권번호</th>
                              <td style="background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:14px;font-weight:400;text-align:left;">'.$JOIN_INFO["STOCK_NO"].'</td>
                            </tr>
                            <tr>
                              <th style="background-color: #f6f6f6;padding:5px 15px;font-size:14px;font-weight:400;text-align:center;">보험기간</th>
                              <td style="background-color: #fff;padding:5px 15px;font-size:12px;font-weight:400;text-align:left; letter-spacing:-1px;">'.$JOIN_INFO["INS_PERIOD"].'</td>
                              <th style="background-color: #f6f6f6;padding:5px 15px;font-size:14px;font-weight:400;text-align:center;">해외 체류국가</th>
                              <td style="background-color: #fff;padding:5px 15px;font-size:14px;font-weight:400;text-align:left;">'.$JOIN_INFO["NATION_NAME"].'</td>
                            </tr>
                          </table>';
            $G_TABLE .= "<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:12px 0 5px 0;'>상기 고객을 피보험자로 하여 아래와 같이 <span style='color:#dc3347'>".$plan_sign_image[$page_loop_idx]["subject"]."</span>에 가입되었음을 확인 합니다.</h3>";
            $G_TABLE .= "<p style='font-size:12px;margin:0 0 30px 0;color:#333;'>보험계약의 자세한 사항에 대하여는 <span style='color:#dc3347'>".$plan_sign_image[$page_loop_idx]["subject"]."</span> 약관을 참조하시기 바랍니다.<br> 플라잉닥터스는 다음의 담보내용과 보상한도액에 의거하여 서비스를 제공합니다.</p>";
            $G_TABLE .= "<table width='100%' cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>";
            $G_TABLE .= "<colgroup><col width='50%' /><col width='*' /></colgroup><thead><tr>";
            $G_TABLE .= "<th style='background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>담보내용</th>";
            $G_TABLE .= "<th style='background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>보험가입금액 / 보상한도액</th></tr></thead><tbody>";
  
            foreach($plan_guarantee_all as $p_guarantee){
              if ($p_guarantee["guarantee_seq"] == $guarantee_seq){
                  if ($p_guarantee["g_amount"]) {
                    $G_TABLE .= "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" ;
                    $G_TABLE .= $p_guarantee["service_name"] ;
                    $G_TABLE .= "</th><td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" ;
                    $G_TABLE .= $p_guarantee["g_amount"] . "</td></tr>";
                  }
              }
            }
            
            $G_TABLE .= "</tbody></table>";
            $G_TABLE .= "<p style='font-size:12px;margin:8px 0 18px 0;color:#333;'>이 보험계약은 보험계약자 및 보험료 납부자가 플라잉닥터스인 단체보험으로 예금자보험법에 의거, 예금자보호대상에서 제외됩니다.</p>";
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
            if(is_null($row_ins)) {
              $G_TABLE .= "<p style=\"text-align:right; padding:20px 20px;\"><img src=\"[DROOT]/_data/board/ins_list/".$plan_sign_image[$page_loop_idx]["kor_img"]."\" align=\"absmiddle\"></p>";
            } else {
              $G_TABLE .= "<p style=\"text-align:right; padding:20px 20px;\"><img src=\"[DROOT]/_data/board/ins_list/".$row_ins["signimg"]."\" align=\"absmiddle\"></p>";
            }
          }
          $page_loop_idx++;

        }
      }
    }

    $HEADER = makeInsCertHeader($chk_lang);
    $JOIN_INFO_TABLE = makeInsJoinInfo($JOIN_INFO, $chk_lang);
    $BODY = makeInsCertBody($chk_lang, $JOIN_INFO_TABLE, $CHK_SERVICE, $G_TABLE);
    $FOOTER = makeInsCertFooter();
    
  } else if($group_join_type === "B2B") { //단체가입자 플라잉닥터스 템플릿
    $cert_type = "플라잉닥터스";
    $chk_kor_service = "";
    $chk_fly_type_name = "";
    $plan_service_all = getPlanService($row_r["pr_cd"], $row_r["plan_cd"]);
    if($certType != "I") {
      if ($row_plan["ext3"] != 'N'){
        if ($chk_lang == "E") {
          //영문 플라잉닥터스
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
              $CHK_SERVICE .= "</th><td style=\"background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;\">" ;
              $CHK_SERVICE .= $insuplus["e_amount"] . "</td></tr>";
            }
          }
  
          $CHK_SERVICE .= "</tbody></table>";
          $CHK_SERVICE .= "<p style='font-size:12px;margin:8px 0 18px 0;color:#333;'>";
          $CHK_SERVICE .= "  The above customer confirms that they have joined to a 24-hour overseas emergency transportation service.<br />";
          $CHK_SERVICE .= "  Please refer to the service guide for details.<br />";
          $CHK_SERVICE .= "  Flying Doctors is an overseas emergency transportation service brand on Bizinsight, Bizinsight provides services <br />";
          $CHK_SERVICE .= "  In accordance with the above collateral and compensation limits</p>";
        } else {
          //국문 플라잉닥터스
          $CHK_SERVICE .= "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:0 0 12px 0;'>플라잉닥터스 서비스 가입 증명서</h5>";
          $CHK_SERVICE .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top: solid 2px #595959;border-bottom: solid 2px #595959; margin:0 0 14px 0;'>";
          $CHK_SERVICE .= "<colgroup><col width='20%'><col width='60%'></colgroup>";
          $CHK_SERVICE .= "<thead><tr><th style=\"background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;\">보장내역</th>";
          $CHK_SERVICE .= "<th style=\"background-color: #f6f6f6;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;\">보장한도</th>";
          $CHK_SERVICE .= "</tr></thead><tbody>";
  
          
          foreach($plan_service_all as $insuplus){
            if ( $insuplus["k_amount"] != "NOT-AVAILABLE"){
              $CHK_SERVICE .= "<tr><th width='350px' style=\"background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;\">" ;
              $CHK_SERVICE .= $insuplus["service_name"];
              $CHK_SERVICE .= "</th><td style=\"background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;\">" ;
              $CHK_SERVICE .= $insuplus["k_amount"] . "</td></tr>";
            }
          }
          
          $CHK_SERVICE .= "</tbody></table>";
        }
        $FLYIMG = "";
        if ($chk_fly_type == "A") {
            $chk_fly_type_name = "코리아어시스턴스";
            $FLYIMG = "korea_assistance_stamp.png";
            $chk_kor_service = "<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:0px 0 5px 0;'>상기 고객은 플라잉닥터스 서비스에 가입되었음을 확인합니다.</h3>";
            $chk_kor_service .= "<p style='font-size:12px;margin:8px 0 5px 0;color:#333;'>플라잉닥터스는 다음의 담보내용과 보상한도액에 의거하여 서비스를 제공합니다.";
            $chk_kor_service .= "자세한 사항에 대하여는 서비스 안내서를 참조하시기 바랍니다.</p>";
          } else {
            $chk_fly_type_name = "플라잉닥터스";
            $chk_kor_service = "";
            if ($chk_lang == "E") {
              $FLYIMG = "biz_sign_en.png";
            } else {
              $FLYIMG = "biz_sign.png";
            }
          }
          $CHK_SERVICE .= "<p style=\"text-align:right; padding:5px 20px;\"><img src=\"[DROOT]/html/images/".$FLYIMG."\" align=\"absmiddle\"></p>";   
      }
    }
    ///////////////////////////////////////////////////////////////////////////
    // print guarantee
    // 보장내역이 2개까지 등록이 가능하기 때문에 템플릿을 수정하지 않기 위해 
    // $G_TABLE 변수에 모든 화면을 구현하고 $ins_noti, $ins_sign_img 변수는 "" 로 만듬
    $G_TABLE = "";
    $plan_guarantee_seq = array();
    $arrayGORow = array();
    $plan_guarantee_all = getPlanGuarantee($row_r["pr_cd"], $row_r["plan_cd"]);
    $plan_sign_image = getPlanInsurance($row_r["plan_cd"]);

    foreach($plan_guarantee_all as $ell){
      if (!in_array($ell["guarantee_seq"], $plan_guarantee_seq)){
        if($ell["guarantee_opt_seq"] == "" && $row_product["ext4"]){
          $SQL_GO = "select * from tbl_board_guarantee_opt where list_seq=".$row_product["ext4"]."";
          $RS_GO = $dbcon -> query($SQL_GO);
          $cnt=0;
          while($row_g_opt = $dbcon -> fetch_array($RS_GO)){
            $arrayGORow[$cnt] = $row_g_opt["service_name_en"];
            $cnt++;
          }
        }
        $plan_guarantee_seq[] = $ell["guarantee_seq"];
      }
    }

    if($certType != "S" && count($plan_guarantee_seq) > 0) {
      $page_loop_idx = 0;

      foreach($plan_guarantee_seq as $guarantee_seq){
        // 사용하지 않는 보장내역 제외
        if ( ($row_plan["ext1"] != 'N' && $guarantee_seq == $row_product["ext4"]) || ($row_plan["ext2"] != 'N' && $guarantee_seq == $row_product["ext10"]) ){
          if ($chk_lang == "E") {
            $G_TABLE .= "<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 10px;border-radius: 8px;margin:20px 0 5px 0;'>Certification of " . $plan_sign_image[$page_loop_idx]["ins_name_en"] . "</h5>";
            $G_TABLE .= "<p style='margin:8px 0 5px 0;font-size:12px;font-weight:400;color:#333;'>";
            $G_TABLE .= "Above subscriber is certified to insured of <span style='font-weight:400;color:red;'>" . $plan_sign_image[$page_loop_idx]["ins_name_en"] . ".</span><br/>";
            $G_TABLE .= "Please refer to the Flying Doctors provides services based on the following collateral and compensation limits.<br/><br/>";
            $G_TABLE .= "Coverage: If mentioned, this coverage includes<br/>";
            $G_TABLE .= "the medical expense caused by Covid-19 infection, infectious disease up to the coverage limit of this policy<br/>";
            $G_TABLE .= "</p>";
            $G_TABLE .= "<table width=\"100%\" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>";
            $G_TABLE .= "<colgroup><col width='50%' /><col width='*' /></colgroup>";
            $G_TABLE .= "<thead>  <tr><th style='background-color: #f2f7ff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Details</th>";
            $G_TABLE .= "<th style='background-color: #fff;padding:5px 15px;font-size:13px;font-weight:400;text-align:center;border-bottom:2px solid #595959;'>Coverage Limit</th>";
            $G_TABLE .= "</tr></thead><tbody>";
    
            $cnt=0;
            foreach($plan_guarantee_all as $p_guarantee){
              if ($p_guarantee["guarantee_seq"] == $guarantee_seq){
                if(count($arrayGORow) > 0) {
                  if ($p_guarantee["g_amount_certificate"] && $p_guarantee["g_amount_certificate"] != "NOT-AVAILABLE") {
                    $G_TABLE .= "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>";
                    $G_TABLE .= $arrayGORow[$cnt];
                    $G_TABLE .= "</th><td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" ;
                    $G_TABLE .= $p_guarantee["g_amount_certificate"] . "</td></tr>";
                  }
                } else {
                  if ($p_guarantee["g_amount_certificate"] && $p_guarantee["g_amount_certificate"] != "NOT-AVAILABLE") {
                    $G_TABLE .= "<tr><th width='350px' style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>";
                    $G_TABLE .= $p_guarantee["service_name_en"] ;
                    $G_TABLE .= "</th><td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:13px;font-weight:400;text-align:center;'>" ;
                    $G_TABLE .= $p_guarantee["g_amount_certificate"] . "</td></tr>";
                  }
                }
              }
              $cnt++;
            }
    
            $G_TABLE .= "</tbody></table>";
            $G_TABLE .= "<p style='font-size:12px;margin:8px 0 18px 0;color:#333;'></p>";
            $G_TABLE .= "<p style='font-size:12px;margin:8px 0 5px 0;color:#333;'>This insurance contract is a group insurance which Flying Doctors is policy holder and pays premium.</p>";
            $G_TABLE .= "<div style=\"font-size:12px;color:#333;\">■ Notice</div>";
            $G_TABLE .= "<div style=\"margin-left: 20px;font-size:12px;color:#333;\">Death Collateral does not apply to those under 15-years-old.</div>";
            $G_TABLE .= "<div style=\"margin-left: 20px;font-size:12px;color:#333;\">Deductible : If not mentioned, refer to insurance policy wording.</div>";
            $G_TABLE .= "<div style=\"margin-left: 20px;font-size:12px;color:#333;\">Countries eligible for membership and guarantee : Make sure that your country is not affected by travel restrictions or prohibitions. Compensation cannot be provided if you travel to a country designated by the Ministry of Foreign Affairs under a red alert (recommendation for withdrawal) or a black warning (prohibition of travel).</div>";
            if(is_null($row_ins)) {
              $G_TABLE .= "<p style=\"text-align:right; padding:20px 20px;\"><img src=\"[DROOT]/_data/board/ins_list/".setFileName($plan_sign_image[$page_loop_idx]["eng_img"])[0][1]."\" align=\"absmiddle\"><p>";
            } else {
              $G_TABLE .= "<p style=\"text-align:right; padding:20px 20px;\"><img src=\"[DROOT]/_data/board/ins_list/".$row_ins["imgfile2"]."\" align=\"absmiddle\"><p>";
            }
          } else {
            $G_TABLE .= "<h3 style='font-size:15px;font-weight: 400; font-family: \"NanumGothic\";line-height: 1.5;margin:12px 0 5px 0;'>상기 고객을 피보험자로 하여 아래와 같이 <span style='color:#dc3347'>".$plan_sign_image[$page_loop_idx]["subject"]."</span>에 가입되었음을 확인 합니다.</h3>";
            $G_TABLE .= "<p style='font-size:12px;margin:0 0 30px 0;color:#333;'>보험계약의 자세한 사항에 대하여는 <span style='color:#dc3347'>".$plan_sign_image[$page_loop_idx]["subject"]."</span> 약관을 참조하시기 바랍니다.<br> 플라잉닥터스는 다음의 담보내용과 보상한도액에 의거하여 서비스를 제공합니다.</p>";
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
            $G_TABLE .= "      <dd>- 여행중 휴대한 물품 파손, 도난인 경우 사용기간을 감안한 감가상각한 금액, 파손의 경우 수리비용 보상<br />&nbsp&nbsp<b>(휴대물품당 1개/1쌍당 20만원, 자기부담금 1만원. 현금, 신용카드, 여권은 보상되지 않음)</b></dd>";
            $G_TABLE .= "    </dl>";
            $G_TABLE .= "  </li>";
            $G_TABLE .= "</ul>";
    
            if(is_null($row_ins)) {
              $G_TABLE .= "<p style=\"text-align:right; padding:20px 20px;\"><img src=\"[DROOT]/_data/board/ins_list/".$plan_sign_image[$page_loop_idx]["kor_img"]."\" align=\"absmiddle\"></p>";
            } else {
              $G_TABLE .= "<p style=\"text-align:right; padding:20px 20px;\"><img src=\"[DROOT]/_data/board/ins_list/".$row_ins["signimg"]."\" align=\"absmiddle\"></p>";
            }
          }
          $page_loop_idx++;
        }
      }
    }
    
    $HEADER = makeFlyingCertHeader($chk_lang);
    $JOIN_INFO_TABLE = makeFlyingJoinInfo($JOIN_INFO, $chk_lang);
    $BODY = makeFlyingCertBody($chk_lang, $chk_fly_type_name, $JOIN_INFO_TABLE, $CHK_SERVICE, $chk_kor_service, $G_TABLE);
    $FOOTER = makeFlyingCertFooter($chk_fly_type);
  }
  ///////////////////////////////////////////////////////////////////////////

  if ($chk_lang=="E"){    //가격단위
    $won = "won";
    $language = "영문";
    $name = $USER_ENAME;
  }else{
    $won = "원";
    $language = "국문";
    $name = $USER_NAME;
  }

  $chHtml  = str_replace("[HEADER]",$HEADER,$chHtml);                     //가입증명서 배너
  $chHtml  = str_replace("[BODY]",$BODY,$chHtml);                         //가입증명서 정보
  $chHtml  = str_replace("[FOOTER]",$FOOTER,$chHtml);                     //가입증명서 발급자 정보

  if($mode === "view") {
    $chHtml  = str_replace("[DROOT]","//".$_SERVER["HTTP_HOST"],$chHtml);              //이미지를 위한 루트경로
    //dompdf 버전 문제로 인한 html 소스 공백 제거
    $html = preg_replace('/\r\n|\r|\n/','',$chHtml);
    echo $html;
  } else if($mode === "down") {
    $chHtml  = str_replace("[DROOT]",$_SERVER["DOCUMENT_ROOT"],$chHtml);              //이미지를 위한 루트경로
    //dompdf 버전 문제로 인한 html 소스 공백 제거
    $html = preg_replace('/\r\n|\r|\n/','',$chHtml);

    $pdf_filename = $cert_type."_".$orderno."_"."".$USER_NAME."_".$chk_lang.".pdf";      //파일명처리
    //html 코드를 pdf로 변환 (변경하면 안됨)
    $dompdf = new DOMPDF();
    $webRoot = $_SERVER["DOCUMENT_ROOT"];
    $dompdf->set_paper( 'A4', 'portrait' );
    $dompdf->set_option('enable_html5_parser', TRUE);
    $dompdf->load_html($html);
    $dompdf->render();
    $dompdf->stream($pdf_filename); //저장되는 파일 이름을 설정한다.
    $output = $dompdf->output();
    error_reporting($error_level);
  } else if($mode === "email") {
    // $chHtml  = str_replace("[DROOT]","//".$_SERVER["HTTP_HOST"],$chHtml);              //이미지를 위한 루트경로
    $chHtml  = str_replace("[DROOT]",$_SERVER["DOCUMENT_ROOT"],$chHtml);              //이미지를 위한 루트경로
    //dompdf 버전 문제로 인한 html 소스 공백 제거
    $html = preg_replace('/\r\n|\r|\n/','',$chHtml);

    $pdf_filename = $cert_type."_가입증명서_".$language."_".$orderno."_"."".$name.".pdf";			//파일명처리
    //html 코드를 pdf로 변환 (변경하면 안됨)	
    $dompdf = new DOMPDF();
    $webRoot = $_SERVER["DOCUMENT_ROOT"];
    $dompdf->set_paper( 'A4', 'portrait' );
    $dompdf->set_option('enable_html5_parser', TRUE);
    $dompdf->load_html($html);
    $dompdf->render();
    $output = $dompdf->output();
    $file_url = $_SERVER["DOCUMENT_ROOT"]."/up_file/".$pdf_filename;
    file_put_contents($file_url, $output);
    $pdf_url = "http://".$_SERVER["HTTP_HOST"]."/up_file/".$pdf_filename;
    error_reporting($error_level);

    if($group_join_type === "B2C") {
      mailJoinSend($param,$email1."@".$email2); //이메일은 자동으로 status 결과값이 나옵니다.
    } else {
      if($chk_fly_type=="A") {
          mailJoinSend2($param,$USER_EMAIL,$pdf_url,$pdf_filename); //이메일은 자동으로 status 결과값이 나옵니다.
      } else {
        mailBizJoinSend($param,$USER_EMAIL,$pdf_url,$pdf_filename); //플라잉닥터스 가입증명서 구분값이 비즈인사이트인 경우
      }
    }
    $result = "1";	
    $send_log = array();
    $send_log["manager_yn"] = "Y";
    $send_log["gubun"] = "E";
    $send_log["memo"] = "가입증명서 재발행";
    $send_log["orderno"] = $orderno;
    insSendLog($send_log); //func.alrimTalk.php 메소드 존재
  }
}
if($mode != 'view') {  
  echo '{"result":"'.$result.'"}';
}
?>
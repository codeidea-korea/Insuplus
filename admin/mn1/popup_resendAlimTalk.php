<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

    $result = "0";		
	$SQL  = " SELECT j.o_name, j.chk_eng_passport ";
	$SQL .= " , o.pr_name, o.ins_name, o.plan_name, o.chk_service";
	$SQL .= " , o.s_date, o.s_date_time, o.e_date, o.e_date_time, o.chk_p ";
	$SQL .= " , o.purpose, o.t_amount, o.ins_amount, o.service_amount, o.purpose ";
	$SQL .= " FROM tbl_order_list_join j INNER JOIN tbl_order_list o ON j.orderno = o.orderno WHERE j.seq = '".$join_seq."' AND o.order_step = '2' ";
	$RS = $dbcon->query($SQL);
	
	$row = $dbcon->fetch_array($RS);
	
	$name = "";
	if($chk_lang == "K") { //영문명이 없는 경우 등록한다.
		$name = $o_name;
	} else if($chk_lang == "E") {
		$name = $o_name_en;
		
		if($row["chk_eng_passport"] != "Y") {
			$SQL_UP  = " UPDATE tbl_order_list_join SET chk_eng_passport ='Y', o_name_en = '".all_seed_enc($o_name_en)."' ";
			$SQL_UP .= " WHERE seq = '".$join_seq."' ";
			$dbcon->query($SQL_UP);
		}
	}
	
	$param = array();
	$param["name"] = $name;
	$param["pr_name"] = $row["pr_name"]." ".$row["ins_name"]." ".$row["plan_name"];
	if($row["chk_service"] == "A" || $row["chk_service"]) {
		$param["pr_name"] .= " ".$Arr_txt_plus[$row["chk_service"]];
	}
	if($row["chk_p"] == "Y") { //단기
		$s_date = $row["s_date"]." ".$row["s_date_time"]."시";
		$e_date = $row["e_date"]." ".$row["e_date_time"]."시";
	} else if($row["chk_p"] == "N") { //장기
		$s_date = $row["s_date"];
		$e_date = $row["e_date"];
	}
	$param["period"] = $s_date." ~ ".$e_date;
	$param["purpose"] = $row["purpose"];
	$param["amount"] = $row["ins_amount"]+$row["service_amount"];
	$param["t_amount"] = $row["t_amount"];
	$param["orderno"] = $orderno;
	
	kakaoCertificateReissue($param, $mobile); //알림톡 전송
    
    $result = "1";
?>
{"result":"<?=$result;?>"}
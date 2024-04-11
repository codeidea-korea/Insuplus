<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$parameter = "&pr_name=".$pr_name."&ins_name=".$ins_name."&plan_name=".$plan_name."&chk_service=".$chk_service."&search=".$search."&search_text=".$search_text."&search_orderby=".$search_orderby."&search_sort=".$search_sort."&num_per_page=".$num_per_page."&join_status=".$join_status."&search_date_txt=".$search_date_txt."&search_date_s=".$search_date_s."&search_date_e=".$search_date_e;

	$SQL = "update tbl_order_list_join set ";
	$SQL .= " o_name = '".all_seed_enc($o_name)."' ";
	$SQL .= " ,o_name_en = '".all_seed_enc($o_name_en)."' ";
	if(strlen($o_phone) > 1){
		$SQL .= " ,o_phone = '".all_seed_enc($o_phone)."' ";
	}
	$SQL .= " ,flight_no = '".$flight_no."' ";
	if (isset($arrival_date) && !empty($arrival_date)){
		$SQL .= " ,arrival_date = '".$arrival_date."' ";
	}
	$SQL .= "where orderno='".$orderno."' and seq=".$seq." ";
	// echo $SQL." 1<br>";
	$result1 = $dbcon -> query($SQL);

	$SQL = "update tbl_order_list set ";
	$SQL .= " o_email1 = '".all_seed_enc($o_email1)."' ";
	$SQL .= " ,o_email2 = '".all_seed_enc($o_email2)."' ";
	$SQL .= " ,o_memo = '".$o_memo."' ";
	$SQL .= " ,join_ch = '".$join_ch."' ";
	$SQL .= "where orderno='".$orderno."'  ";
	// echo $SQL." 2<br>";
	$result2 = $dbcon -> query($SQL);
?>
<script type="text/javascript">

document.location.href="join_view.php?seq=<?=$seq?><?=$parameter?>";

</script>
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$parameter = "&pr_cd=".$pr_cd."&group_join_status=".$group_join_status."&search_text=".$search_text."&num_per_page=".$num_per_page."&search_date_s=".$search_date_s."&search_date_e=".$search_date_e;
	

	$SQL = "update tbl_order_group_join_list set ";
	$SQL .= " group_join_status = '".$group_join_status."' ";
	$SQL .= "where group_join_id='".$group_join_id."' ";
//	echo $SQL." 1<br>";
	$result1 = $dbcon -> query($SQL);
?>
<script type="text/javascript">
<!--
document.location.href="group_join_estimate_view.php?group_join_id=<?=$group_join_id?><?=$parameter?>";
//-->
</script>
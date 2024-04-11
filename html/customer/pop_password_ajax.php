<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

if($_POST["mode"] == "check") {
	$SQL =  " SELECT count(*) FROM tbl_board_qna WHERE  seq = '".$seq."' ";
	$SQL .= " AND customer_password = '".all_seed_enc($customer_password)."' ";
	
	$result = $dbcon -> getCount($SQL);
	
	if($result) $_SESSION["ss_view_seq"] = $seq;
	
	$dbcon -> dbcon_close();		
}
?>
{"result":"<?=$result;?>"}
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	// 관리자 체크
	admin_chk($auth_admin, $url_admin_login_out);
?>
<?
	$SQL = "delete from tbl_product_option where optnum = '$_POST[f_optnum]'";
	mysql_query($SQL, $dbcon) or die (echo_error(mysql_error()));
	$optcnt = get_one_data("select count(*) from tbl_product_option where pnum='$_POST[f_pnum]'", $dbcon);

?>
<script language='JavaScript'>
<!--
	parent.document.frmOptdel.f_optcnt.value = "<?=$optcnt?>";
	parent.removeOption();
//-->
</script>
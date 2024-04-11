<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$pc_num = REQSTR($pc_num, "");
	$SQL = "
		select
			pc_option
		from tbl_product_category
		where
			1=1
			and pc_num = '".$pc_num."'
	";
	$dbcon -> getCount($SQL);

//			and length(pc_num) = '".(getLen($pc_num) + 2)."'
//			and pc_num like SUBSTRING('".$pc_num."', 1, ".(getLen($pc_num)).")

	echo
?>
<?
	$dbcon -> dbcon_close();
?>
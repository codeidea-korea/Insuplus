<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	$dbcon -> dbcon_close();
?>
<?
	if ( strlen($ss_u_idx) == 0 || $ss_u_level < $auth_admin) {
		LogoutProcess();
		$URL = $url_admin_login;
	}
	else {
		$URL = $url_admin_index;
	}

//	echo $URL."<BR>";
//	exit;

?>
	<script>
		//alert("<?=$URL?>");
		location.href = "<?=$URL?>";
	</script>
<?
	exit;
	
?>

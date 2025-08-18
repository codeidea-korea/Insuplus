<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	LogoutProcess();
	$dbcon -> dbcon_close();
	//alert_page("로그아웃 되었습니다.",$root_path."admin/");
    if(!isset($_GET['ment'])){
	alert_page($msg_louout_ok,$url_admin);
}else{

    header( "Location: /admin/login.php" );
}
?>

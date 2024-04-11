<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";


	FuncCartDelAll();// 장바구니 비우기
	LogoutProcess();// 로그아웃
	$dbcon -> dbcon_close();
	alert_page($msg_louout_ok,$url_logout_ok);
?>

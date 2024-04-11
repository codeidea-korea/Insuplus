<!DOCTYPE html>
<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
// 관리자 체크
admin_chk($auth_admin, $url_admin_login_out);

$tm = "";
$lm = "";
include $path_admin."inc/header_pop.php";

?>
<div class="popupWrap">
	<header>
		<h1>이벤트 찾기</h1>
		<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
	</header>
	
	<div class="popContWrap">
		<?
			$bc_id = "main_event";
			include_once $path_board."board.php";
			$dbcon -> dbcon_close();
		?>
	</div>
</div>

</html>
<?
	$dbcon -> dbcon_close();
?>
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
<script>
function g_select(idx,img,subject){
		var ff = opener.document.all;
		ff.event_seq.value=idx;
		var img_name = img.split(",");
		var html = "<img src='/_data/board/main_event/"+img_name[1]+"' width='160'/> "
		$("#event_img",opener.document).html(html);
		$("#event_subject",opener.document).text(subject);
		window.close();
	}
</script>
</html>
<?
	$dbcon -> dbcon_close();
?>
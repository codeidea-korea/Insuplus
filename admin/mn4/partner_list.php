<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$tm = "MN4";
	$lm = "";
	include $path_admin."inc/header.php";

	// 2023-06-30 added by kyle
	// get depth 0 categories
	$PRD_CAT = getProductCatetories(["depth"=>"0"]);
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">제휴사 관리</td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>
<script>
	/////////////////////////////////////////////////////////////////////////////
	// 2023-06-30 added by kyle
	// 파트너별 가입가능 상품 카테고리 코드 lookup 을 위한 스크립트
	const PAGE_PRD_CAT = JSON.parse(`<?= json_encode($PRD_CAT) ?>`);
	window.addEventListener('load', ()=>{
		const PAGE_TARGET = Array.from(document.querySelectorAll('td.product-category'));
	
		PAGE_TARGET.forEach(item=>{
			let obj = PAGE_PRD_CAT.find(el=>el.category_code === item.textContent);
			item.textContent = obj.category_name || item.textContent;
		});
	})
	/////////////////////////////////////////////////////////////////////////////
</script>

<!-- 190725 수정 800px->100% -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
		<?php
			$bc_id = "partner";
			include_once $path_board."board.php";
			$dbcon -> dbcon_close();
		?>
		</td>
	</tr>
</table>

<? include $path_admin."inc/footer.php"; ?>
<? $dbcon -> dbcon_close();?>
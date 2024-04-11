<?php
	include '../_include/_header.html';
	
	$policy_name = $_GET["policy_name"];
	$bc_id = "policy";
	$today = date("Y-m-d");
	
	$SQL  = " SELECT content FROM tbl_board_policy where secret = 'N' ";
	$SQL .=	" AND category = (SELECT idx FROM tbl_category WHERE bc_id = '".$bc_id."' AND cate_name = '".$policy_name."') ";
	$SQL .= " AND start_date <= '".$today."' AND end_date >= '".$today."' ";
	$SQL .=	" ORDER BY seq DESC  limit 0,1 ";

	$RS = $dbcon -> query($SQL);
	$ROW = $dbcon -> fetch_array($RS);
	
?>
<div class='terms'>
	<div class="terms_content">
		<?=$ROW["content"]?>
	</div>
</div>
<div class='text-center m-t-3'>
	<a class='btn btn-theme-dark p-x-5' data-toggle="close-modal" aria-hidden="true">확인</a>
</div>
<?php
	include '../_include/_footer.html';
?>


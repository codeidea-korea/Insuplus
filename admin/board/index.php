<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// ������ üũ
?>
<?
	$tm = "board";
	$lm = "";
	include $path_admin."inc/header.php";
?>

<?


	if ( $_GET["bc_id"] ) {
		$bc_id = $_GET["bc_id"];
	}
	else {
		$bc_id = $_POST["bc_id"];
	}

	if ( !$bc_id ) {
		$SQL = "
			select
				bc_id, bc_name
			from
				config_board_list
			order by
				bc_name
			limit 0, 1
		";
	}
	else {
		$SQL = "
			select
				bc_id, bc_name
			from
				config_board_list
			where
				bc_id = '".$bc_id."'
		";
	}

//	echo $SQL."<BR>";
	$TempRow = $dbcon->fetch_array($dbcon -> query($SQL));

	$bc_id = $TempRow["bc_id"];
	$bc_name = $TempRow["bc_name"];

//	echo "bc_id : " .$bc_id."<BR>";
//	echo "bc_name : " .$bc_name."<BR>";

	unset($TempRow);

	$html = new html;
	echo $html -> getAdminTitle($bc_name);

	//exit;
?>
<?php
// bc_id 가 ins_list 일때만
if($bc_id == 'ins_list'){

?>
<script type="text/javascript" src="<?= $url_admin ?>js/block.js"></script>
<?php
}
?>

<table border="0" cellpadding="0" cellspacing="0" width="1200" >
	<tr>
		<td>
			<?include_once $path_board."board.php";?>
		</td>
	</tr>
</table>

<? include $path_admin."inc/footer.php"; ?>


<? $dbcon -> dbcon_close();?>

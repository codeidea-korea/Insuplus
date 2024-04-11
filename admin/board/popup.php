<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// ������ üũ
?>
<?
	$tm = "board";
	$lm = "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
	$html_version = time();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="/_css/admin.css?v=<?=$html_version;?>" rel="stylesheet" type="text/css">
<meta content="IE=9" http-equiv="X-UA-Compatible" />
<?php getLib();?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>

<script type="text/javascript" src="/js/swfobject.js"></script>
<script type="text/javascript" src="/js/ecaso.js"></script>
<script src="/admin/js/admin.js"></script>
<script src="/admin/js/jquery-ui.js"></script>
<link href="/_css/jquery-ui.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color:#FFFFFF;">
<!-- Header Start -->

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" height="500" style="padding-right:20px; box-sizing:borer-box;">
				<tr>
					<td width="198" valign="top" style="padding:20px 10px 20px">

					</td>
					<td width="15">&nbsp;</td>
					<td align="left" valign="top" style="padding:20px 0px 20px 0px;">
						<!-- Content Start -->

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


	//exit;
?>
<table border="0" cellpadding="0" cellspacing="0" width="1000" >
	<tr>
		<td>
<?
	include_once $path_board."board.php";
?>
		</td>
	</tr>
</table>

<? $dbcon -> dbcon_close();?>

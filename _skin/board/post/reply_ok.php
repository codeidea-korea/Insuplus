<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";

	$seq = $_POST["seq"];
	$bc_id = $_GET["bc_id"];
	$reply_ok = REQSTR($_POST["reply_ok"]);
	$reply = REQSTR($_POST["reply"]);

	isnull($seq);

//if ($reply){
	$dbcon -> dbcon_open(0);
	$TempSQL = "
		update tbl_board_".$bc_id."
		set
			reply_ok = '".$reply_ok."'
			, reply = '".$reply."'
			, reply_date = now()
		where
			seq = '".$seq."'


	";
//	echo $TempSQL."<BR>";
	$dbcon -> query($TempSQL);

	$dbcon -> dbcon_close();



?>
<script>
	alert("처리되었습니다.");
	parent.location.reload();
</script>
<?//}?>
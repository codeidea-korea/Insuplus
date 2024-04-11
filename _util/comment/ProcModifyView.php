<?
	$root_path = "../../";
	include $root_path."_config/inc.php";

	$dbcon = dbcon_open();

	$idx					= req_str($_GET["idx"],"");

	if ( strlen($idx) == 0 ) {
		alert_page("필수 정보 누락","aa");
	}

	$SQL = "
		select
			u_id, comment
		from
			tbl_comment
		where
			idx = '".$idx."'
	";
	$result = mysql_query($SQL, $dbcon);
	if (!$result) {
		alert_page("쿼리 실행 에러","aa");
	}

	$rows = mysql_fetch_row($result);
	$u_id		= $rows[0];
	$comment		= $rows[1];
	$comment		= str_replace(chr(10)&chr(13),"",$comment);

	dbcon_close($dbcon);
echo "<xmp>".$comment."</xmp>"."<BR>";
?>


<script>
	ff = parent.document.CommentForm;
	ff.comment.value = "<?=$comment?>";
	//location.href = "aa";
</script>

<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
?>
<?
	if ( strlen($pop_seq) && $mode != "preview" ) {
		$SQL = "
			select
				pop_seq, pop_size_wid, pop_size_hei, pop_content,pop_subject
			from
				tbl_popup
			where
				1=1
				and pop_seq = ".$pop_seq."
		";
		//echo $SQL."<BR>";
		//exit;
		$result = $dbcon -> query($SQL);
		$rows = $dbcon -> fetch_array($result);
		$pop_seq					= $rows["pop_seq"];
		$pop_size_wid			= $rows["pop_size_wid"];
		$pop_size_hei			= $rows["pop_size_hei"];
		$pop_content			= $rows["pop_content"];
		$pop_subject			= $rows["pop_subject"];


	}
	else {
		if ( getLen($pop_size_wid) == 0 || getLen($pop_size_hei) == 0 ) {
			alert_close("게시판 정보가 누락되었습니다.");
			exit;
		}
		$content = str_replace('\"','"',$content);
	}
	$dbcon -> dbcon_close();


?>
<head>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
<?
	getLib();
?>
</head>
<?if ($pop_subject){?>
<script type="text/javascript">
<!--
	document.title = "<?=$pop_subject?>";
//-->
</script>
<?}?>
<body leftmargin="0" topmargin="0">
<center>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top"><?=$pop_content?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#000000">
	<tr height="30">
		<td width="10"></td>
	<? if ( $mode != "preview" ) { ?>
		<td style="font-size:12px;"><? include( $path_popup."inc.popup.php" ) ?><font color="#FFFFFF">오늘하루 열지 않음</font></td>
	<? } ?>
		<td align="right"><img src="<?=$url_popup?>images/close.gif" border="0" onclick="window.close();" style="cursor:pointer;"></td>
		<td width="10"></td>
	</tr>
</table>

</center>
</body>
</html>
<script>
function go_pop_url(url){
opener.document.location.href= url;
self.close();
}
</script>
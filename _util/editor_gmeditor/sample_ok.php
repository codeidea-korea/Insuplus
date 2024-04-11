<?
include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
?>
<?
	$content = REQSTR($_POST[content], "");

	$content = REQSTR2($content);
	echo $content."<BR>";
?>
<?
	$dbcon -> dbcon_close();
?>

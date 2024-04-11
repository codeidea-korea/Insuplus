<?
	include_once("$_SERVER[DOCUMENT_ROOT]/_config/lib.php");
?>
<? //getThumbnailBoard ('notice', 6, '/sub/notice.php', 40, '45' ) ?>
<?
	$SQL = "
		select seq, subject, regdate
		from tbl_board_notice
		order by seq desc
		limit 0, 5
	";
	$rs = $dbcon -> query($SQL);

?>
<script src="<?=$url_root?>script/scrollControl.js"></script>
<table width="775" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="69" height="26" valign="top"><a href="#"><img src="../images/notice_tit.gif"></a></td>
		<td valign="top" class="main_notice" style="padding:0px:">
			<!-- <div id="scrollBtn" style="width:30px;float:left;">
				<a class="prev" href="javascript:scrollCtrl.setCourse('top');" title="위로">▲</a>
				<a class="next" href="javascript:scrollCtrl.setCourse('down');" title="아래로">▼</a>
			</div> -->
			<div id="scrollstage" style="width:400px;border:0px solid #aaaaaa;">
			<? while ( $rows = $dbcon -> fetch_array($rs) ) { ?>
				<div><a href="/sub/notice.php?mode=view&seq=<?=$rows[seq]?>">[ <?=getStrCut($rows[regdate], 10)?> ] <?=$rows[subject]?></a></div>
			<? } ?>
			</div>
		</td>
	</tr>
</table>
<div id="debug"></div>
<script type="text/javascript">
var scrollCtrl    = new ScrollControl('scrollstage', {inteval:50,freeze:3000,height:'16'});
</script>














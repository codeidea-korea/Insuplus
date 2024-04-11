<?
	$root_path = "../../";
	include $root_path."_config/inc.php";
?>
<?
	if ( strlen($seq) && $mode != "preview" ) {
		$dbcon = dbcon_open();

		$SQL = "
			select
				seq, size_wid, size_hei, content, type
			from
				Tbl_board_popup
			where
				1=1
				and seq = ".$seq."
		";
		//echo $SQL."<BR>";
		//exit;

		$result = mysql_query($SQL, $dbcon);
		if (!$result) {
			//$dbcon -> dbcon_close();
			//echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
			//echo "에러";
			//alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
			//exit;
		}
		else {
			$rows = mysql_fetch_array($result);

			$seq					= $rows["seq"];
			$size_wid			= $rows["size_wid"];
			$size_hei			= $rows["size_hei"];
			$content			= $rows["content"];
			$type			= $rows["type"];
		}

		$dbcon -> dbcon_close();

	}
	else {
		if ( strlen($type) == 0 || strlen($size_wid) == 0 || strlen($size_hei) == 0 || strlen($sdate_year) == 0 || strlen($sdate_month) == 0 || strlen($sdate_day) == 0 || strlen($edate_year) == 0 || strlen($edate_month) == 0 || strlen($edate_day) == 0 ) {
			alert_close("게시판 정보가 누락되었습니다.");
			exit;
		}
		$content = str_replace('\"','"',$content);
	}


?>
<link href="<?=$root_path?>/css/style.css" type="text/css" rel="stylesheet">
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

<center>
<table width="<?=$size_wid?>" border="0" cellspacing="0" cellpadding="0">
<?
	if ($type == "A" || $type == "B" ) {
?>
	<tr>
		<td><img src="/images/common/pop_<?=$size_wid?>_top.gif" /></td>
	</tr>
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" background="/images/common/pop_<?=$size_wid?>_bar.gif">
				<tr>
					<td style="padding:0 20px;">
						<?=$content?>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td><img src="/images/common/pop_<?=$size_wid?>_btm.gif" /></td>
	</tr>
<?
	}
	else {
?>
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" background="/images/common/pop_<?=$size_wid?>_bar.gif">
				<tr>
					<td style="padding:0 20px;"><?=$content?></td>
				</tr>
			</table>
		</td>
	</tr>
<?
	}
?>

	<? if ( $mode == "preview" ) { ?>
	<tr>
		<td height="32" align="right" valign="middle" background="" style="padding-right:10px; padding-top:0px;"><a href="javascript:self.close();">창 닫기</a></td>
	</tr>
	<? } else { ?>
	<tr>
		<td height="32" align="right" valign="middle" background="" style="padding-right:10px; padding-top:0px;"><? include( $root_path."_config/inc.popup.php" ) ?>오늘하루열지않기</td>
	</tr>
	<? } ?>
</table>

</center>
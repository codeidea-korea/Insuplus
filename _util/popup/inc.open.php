<?
//-- inc.open.php ----------------------------------------------------
//-- 제작 이정훈(email:wildroseBoy@hotmail.com) 2003-12-27
//-- 수정 김승태 2008-04-01
function popup( $url, $parameter, $size_width, $size_height, $area_left, $area_top, $popnum ) {
	$cookie_name = str_replace( ".", "_dot_", basename( $url ) ).$popnum;

/*
	echo "cookie_name : ".$cookie_name."<BR>";
	echo $_COOKIE[$cookie_name]."<BR>";
	echo file_exists( $url )."<BR>";
*/

	$flag = false;

	// 쿠키값이 없고, 파일이 존재할때만 팝업을 띄움
	if( !$_COOKIE[$cookie_name] ) {// && file_exists( $url )
		//echo "팝업열기 : ".$url."<BR>";
		?>
		<script>
			$(document).ready(function() {
			<? if ( $cookie_name ) { ?>
				<?=$cookie_name?> = window.open( "<?=$url.$parameter?>", "<?=$cookie_name?>", "top=<?=$area_top?>, left=<?=$area_left?>, width=<?=$size_width?>,height=<?=$size_height?>" );
				<?=$cookie_name?>.focus();
			<? } else { ?>
					window.open( "<?=$url.$parameter?>", "<?=$cookie_name?>", "top=<?=$area_top?>, left=<?=$area_left?>, width=<?=$size_width?>,height=<?=$size_height?>" );
			<? } ?>
			});
		</script>
		<?
		$flag = true;

	}

	return $flag;
}

function popup_layer($url, $parameter, $size_width, $size_height, $area_left, $area_top, $popnum){

	$cookie_name = str_replace( ".", "_dot_", basename( $url ) ).$popnum;
	$flag = false;

	// 팝업 정보 검색
	$SQL = "
		select
			pop_seq, pop_size_wid, pop_size_hei, pop_content
		from
			tbl_popup
		where
			1=1
			and pop_seq = ".$popnum."
	";
	echo $SQL."<BR>";
//	exit;
	$PopRS		= mysql_query($SQL);
	$row_pop	= $dbcon -> fetch_array($PopRS);
	echo $rows["pop_seq"];
	$pop_seq				= $row_pop["pop_seq"];
	$pop_size_wid			= $row_pop["pop_size_wid"];
	$pop_size_hei			= $row_pop["pop_size_hei"];
	$pop_content			= $row_pop["pop_content"];
echo $pop_seq;
	// 쿠키값이 없고, 파일이 존재할때만 팝업을 띄움
	if( $_COOKIE[$cookie_name]=="" ) {
		?>
		<div style="position:absolute;z-index:999;left:<?=$area_left?>px;top:<?=$area_top?>px;width:<?=$size_width?>px;height:<?=$size_height?>px;">
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
		</div>
		<?
		$flag = true;
	}
	return $flag;
}
?>

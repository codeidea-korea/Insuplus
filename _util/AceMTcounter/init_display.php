<?
include("./lib/_connect.php");

// 독립적으로 호출하면 카운트가 되지 않게 하며, 한번 카운트 되면 더이상 카운트 시키지 않기 위해 값을 없앤다.
if($_COOKIE[AceMTcount_flag] && $_COOKIE[AceMTcount_flag_display]) {

	$w = $_GET[w];
	$h = $_GET[h];

	$query = "select * from AceMTcounter_display where cd_width='$w' and cd_height='$h'";
	$res = mysql_query($query);
	if(mysql_num_rows($res)) {
		mysql_query("update AceMTcounter_display set cd_hit=cd_hit+1, cd_uptime=unix_timestamp() where cd_width='$w' and cd_height='$h'");
	}
	else {
		mysql_query("insert into AceMTcounter_display set cd_width='$w', cd_height='$h', cd_hit=cd_hit+1, cd_uptime=unix_timestamp()");
	}

	setcookie("AceMTcount_flag", 0, 0, "/");
}
?>

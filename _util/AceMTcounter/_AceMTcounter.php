<?
// DB연결
include("lib/_connect.php");

if(!isset($_COOKIE[AceMTcount_flag])) {
	// 오늘 동일한 아이피가 이미 찍혀 있는지 체크
	$query = "select * from AceMTcounter_ip where ci_ip='$_SERVER[REMOTE_ADDR]' and ci_yy='$yy' and ci_mm='$mm' and ci_dd='$dd'";
	mysql_query($query);
	if(mysql_affected_rows()) {
		$ci_todayip = 0;
	}
	else {
		$ci_todayip = 1;
	}

	// ip에 대한 시간별 카운트
	$query = "select * from AceMTcounter_ip where ci_ip='$_SERVER[REMOTE_ADDR]' and ci_yy='$yy' and ci_mm='$mm' and ci_dd='$dd' and ci_hh='$hh'";
	mysql_query($query);
	if(mysql_affected_rows()) {
		$query = "update AceMTcounter_ip set ci_hit=ci_hit+1, ci_uptime=unix_timestamp() where ci_ip='$_SERVER[REMOTE_ADDR]' and ci_yy='$yy' and ci_mm='$mm' and ci_dd='$dd' and ci_hh='$hh'";
	}
	else {
		$domain = gethostbyaddr($_SERVER[REMOTE_ADDR]);
		$domain = $_SERVER[REMOTE_ADDR];
		$query = "insert into AceMTcounter_ip set ci_ip='$_SERVER[REMOTE_ADDR]', ci_domain='$domain', ci_yy='$yy', ci_mm='$mm', ci_dd='$dd', ci_ww='$ww', ci_hh='$hh', ci_hit=1, ci_todayip='$ci_todayip', ci_uptime=unix_timestamp()";
	}
	mysql_query($query);

	// 브라우져에 대한 카운트
	$query = "select * from AceMTcounter_browser where cb_browse='$_SERVER[HTTP_USER_AGENT]'";
	mysql_query($query);
	if(mysql_affected_rows()) {
		$query = "update AceMTcounter_browser set cb_hit=cb_hit+1, cb_uptime=unix_timestamp() where cb_browse='$_SERVER[HTTP_USER_AGENT]'";
	}
	else {
		$query = "insert into AceMTcounter_browser set cb_browse='$_SERVER[HTTP_USER_AGENT]', cb_hit=1, cb_uptime=unix_timestamp()";
	}
	mysql_query($query);

	// 이전url이 어디인지 체크
	$REPLACE_REFERER = substr($_SERVER[HTTP_REFERER], 0, 250);
	$query = "select * from AceMTcounter_url where cu_url='$REPLACE_REFERER'";
	mysql_query($query);
	if(mysql_affected_rows()) {
		$query = "update AceMTcounter_url set cu_hit=cu_hit+1, cu_uptime=unix_timestamp() where cu_url='$REPLACE_REFERER'";
	}
	else {
		$query = "insert into AceMTcounter_url set cu_url='$REPLACE_REFERER', cu_hit=1, cu_uptime=unix_timestamp()";
	}
	mysql_query($query);

	setcookie("AceMTcount_flag", "1", 0, "/");

	//echo "<script>document.write(\"<img border='0' width='0' height='0' src='".$url_counter."init_display.php?w=\"+screen.width+\"&h=\"+screen.height+\"'>\");</script>";
}


// 청소작업 --- start
$query = "delete from AceMTcounter_now where uptime<unix_timestamp()-120";
mysql_query($query);

$query = "replace into AceMTcounter_now set session_id='" . session_id() . "', ip='$_SERVER[REMOTE_ADDR]', uptime=unix_timestamp()";
mysql_query($query);
// 청소완료 --- end


$yy = date("y");
$mm = date("m");
$dd = date("d");

$query = "select sum(ci_todayip) from AceMTcounter_ip where ci_yy='$yy' and ci_mm='$mm' and ci_dd='$dd'";
$res = mysql_query($query);
$row = mysql_fetch_row($res);
$today = $row[0];

$query = "select sum(ci_hit) from AceMTcounter_ip where ci_yy='$yy' and ci_mm='$mm' and ci_dd='$dd'";
$res = mysql_query($query);
$row = mysql_fetch_row($res);
$today_count = $row[0];

$py = date("y", mktime(0,0,0,$mm,$dd-1,$yy));
$pm = date("m", mktime(0,0,0,$mm,$dd-1,$yy));
$pd = date("d", mktime(0,0,0,$mm,$dd-1,$yy));

$query = "select sum(ci_todayip) from AceMTcounter_ip where ci_yy='$py' and ci_mm='$pm' and ci_dd='$pd'";
$res = mysql_query($query);
$row = mysql_fetch_row($res);
$yesterday = number_format($row[0]);

$query = "select sum(ci_hit) from AceMTcounter_ip where ci_yy='$py' and ci_mm='$pm' and ci_dd='$pd'";
$res = mysql_query($query);
$row = mysql_fetch_row($res);
$yesterday_count = number_format($row[0]);

$query = "select sum(ci_todayip) from AceMTcounter_ip";
$res = mysql_query($query);
$row = mysql_fetch_row($res);
$total = number_format($row[0]);

$query = "select sum(ci_hit) from AceMTcounter_ip";
$res = mysql_query($query);
$row = mysql_fetch_row($res);
$total_count = number_format($row[0]);

$query = "select count(*) from AceMTcounter_now";
$res = mysql_query($query);
$row = mysql_fetch_row($res);
$now_count = number_format($row[0]);

$query = "select count(distinct ip) from AceMTcounter_now";
$res = mysql_query($query);
$row = mysql_fetch_row($res);
$now = number_format($row[0]);
?>
<?/*
<script>
//parent.today_count.innerHTML = "<?=number_format($today)?>";
//parent.today_ply.innerHTML = "<?=number_format($today_count)?>";
//parent.yesterday_count.innerHTML = "<?=number_format($yesterday)?>";
//parent.yesterday_ply.innerHTML = "<?=number_format($yesterday_count)?>";
//parent.total_count.innerHTML = "<?=number_format($total)?>";
//parent.total_ply.innerHTML = "<?=number_format($total_count)?>";
//parent.now_count.innerHTML = "<?=number_format($now_count)?>";
//parent.now_ply.innerHTML = "<?=number_format($now)?>";
</script>
*/?>
<?
// DB연결
include("lib/_connect.php");

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

<script language="JavaScript">
<!--
var AceMTcounterString  = "";

AceMTcounterString += "오늘(<?=number_format($today)?> Visit, ";
AceMTcounterString += "<?=number_format($today_count)?>명) ";
AceMTcounterString += "<font color='#FF9900'>|</font> ";
AceMTcounterString += "어제(<?=number_format($yesterday)?> Visit, ";
AceMTcounterString += "<?=number_format($yesterday_count)?>명) ";
AceMTcounterString += "<font color='#FF9900'>|</font> ";
AceMTcounterString += "전체(<?=number_format($total)?> Visit, ";
AceMTcounterString += "<?=number_format($total_count)?>명) ";
AceMTcounterString += "<font color='#FF9900'>|</font> ";
AceMTcounterString += "현재(<?=number_format($now)?> Visit, ";
AceMTcounterString += "<?=number_format($now_count)?>명) ";
AceMTcounterString += "<font color='#FF9900'>|</font>";

parent.AceMTcounterView.innerHTML = AceMTcounterString;
//-->
</script>
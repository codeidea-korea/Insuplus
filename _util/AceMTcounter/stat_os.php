<?
include("./lib/_util.php");
include("./lib/_connect.php");
include("./lib/_class.php");

$res = mysql_query("select sum(cb_hit) from AceMTcounter_browser");
$row = mysql_fetch_row($res);
$all_total = $row[0];

$res = mysql_query("select * from AceMTcounter_browser order by cb_uptime");
while($row = mysql_fetch_assoc($res)) {
	$getArrayOS = explode(";", $row[cb_browse]);
	$OS = trim($getArrayOS[2]);

	if(preg_match("/Windows 98/", $OS)) {
		$getos[0][os] = "Windows 98";
		$getos[0][hit] += $row[cb_hit];
		$getos[0][uptime] = $row[cb_uptime];
	}
	else if(preg_match("/Windows NT 4.0/", $OS)) {
		$getos[1][os] = "Windows NT 4.0";
		$getos[1][hit] += $row[cb_hit];
		$getos[1][uptime] = $row[cb_uptime];
	}
	else if(preg_match("/Windows NT 5.0/", $OS)) {
		$getos[2][os] = "Windows NT 5.0";
		$getos[2][hit] += $row[cb_hit];
		$getos[2][uptime] = $row[cb_uptime];
	}
	else if(preg_match("/Windows NT 5.1/", $OS)) {
		$getos[3][os] = "Windows NT 5.1";
		$getos[3][hit] += $row[cb_hit];
		$getos[3][uptime] = $row[cb_uptime];
	}
	else if(preg_match("/Windows NT 5.2/", $OS)) {
		$getos[4][os] = "Windows NT 5.2";
		$getos[4][hit] += $row[cb_hit];
		$getos[4][uptime] = $row[cb_uptime];
	}
	else if(preg_match("/FreeBSD i386/", $OS)) {
		$getos[5][os] = "FreeBSD i386";
		$getos[5][hit] += $row[cb_hit];
		$getos[5][uptime] = $row[cb_uptime];
	}
	else if(preg_match("/Linux i686/", $OS)) {
		$getos[6][os] = "Linux i686";
		$getos[6][hit] += $row[cb_hit];
		$getos[6][uptime] = $row[cb_uptime];
	}
	else if(preg_match("/PPC Mac OS X/", $OS)) {
		$getos[7][os] = "PPC Mac OS X";
		$getos[7][hit] += $row[cb_hit];
		$getos[7][uptime] = $row[cb_uptime];
	}
	else {
		$getos[8][os] = "Other";
		$getos[8][hit] += $row[cb_hit];
		$getos[8][uptime] = $row[cb_uptime];
	}
}

include("_header.php");
?>

<table width="680" border="0" cellpadding="3" cellspacing="0">
<tr height="25" bgcolor="#DDDDDD">
    <td width="50" align="center"><b>NO</b></td>
    <td><b>운영체제</b></td>
    <td width="140" align="center"><b>업데이트</b></td>
    <td width="50" align="right"><b>히트</b></td>
    <td width="50" align="right"><b>방문율</b></td>
    <td width="160"><b>그래프</b></td>
</tr>
<tr align="center" bgcolor="#C0C0C0"><td colspan="6" height="1"></td></tr>

<?
for($i=0, $count=count($getos); $i<$count; $i++) {
    $bgcolor = ($i%2) ? "#F5F5F5" : "#FFFFFF";
    $rows[$i][_facing] = $count - $i;

    $per = $getos[$i][hit] / $all_total * 100;
    $graph = intval($getos[$i][hit] / $all_total * 160);
    if($graph < 1) $graph = 1;
?>

<tr bgcolor="<?=$bgcolor?>" class="gray">
    <td align="center"><?=$rows[$i][_facing]?></td>
    <td><?=$getos[$i][os]?></td>
    <td align="center"><?=date("Y년 m월 d일 H시", $getbrowse[$i][uptime])?></td>
    <td align="right"><?=number_format($getos[$i][hit])?></td>
    <td align="right"><?=sprintf("%2.2f", $per)?>%</td>
    <td>
        <table border="0" cellpadding="0" cellspacing="0">
        <tr bgcolor="#FF9900"><td width="<?=$graph?>" height="15"></td></tr>
        </table>
    </td>
</tr>
<tr align="center" bgcolor="#EAEAEA"><td colspan="6" height="1"></td></tr>

<? } ?>
</table>

<?
include("_footer.php");
?>
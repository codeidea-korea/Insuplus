<?
include("./lib/_util.php");
include("./lib/_connect.php");
include("./lib/_class.php");

$res = mysql_query("SELECT SUM(cb_hit) FROM AceMTcounter_browser");
$row = mysql_fetch_row($res);
$all_total = $row[0];

$res = mysql_query("SELECT * FROM AceMTcounter_browser ORDER BY cb_uptime");
while($row = mysql_fetch_assoc($res)) {
	$getBrowse = explode(";", $row[cb_browse]);
	$Browse = trim($getBrowse[1]);
	$getB[$Browse] = 1;
	if(preg_match("/MSIE 6.0/", $Browse)) {
		$getbrowse[0][browse] = "익스플로러 6.0";
		$getbrowse[0][hit] += $row[cb_hit];
		$getbrowse[0][uptime] = $row[cb_uptime];
	}
	else if(preg_match("/MSIE 5.5/", $Browse)) {
		$getbrowse[1][browse] = "익스플로러 5.5";
		$getbrowse[1][hit] += $row[cb_hit];
		$getbrowse[1][uptime] = $row[cb_uptime];
	}
    else if(preg_match("/MSIE 5.0/", $Browse)) {
		$getbrowse[2][browse] = "익스플로러 5.0";
		$getbrowse[2][hit] += $row[cb_hit];
		$getbrowse[2][uptime] = $row[cb_uptime];
	}
	else if(preg_match("/MSIE 4/", $Browse)) {
		$getbrowse[3][browse] = "익스플로러 4";
		$getbrowse[3][hit] += $row[cb_hit];
		$getbrowse[3][uptime] = $row[cb_uptime];
	}
	else if(preg_match("/U/", $Browse)) {
		$getbrowse[4][browse] = "U";
		$getbrowse[4][hit] += $row[cb_hit];
		$getbrowse[4][uptime] = $row[cb_uptime];
	}
    else if(preg_match("/Google/", $Browse)) {
		$getbrowse[5][browse] = "구글로봇";
		$getbrowse[5][hit] += $row[cb_hit];
		$getbrowse[5][uptime] = $row[cb_uptime];
	}
    else if(preg_match("/Yahoo/", $Browse)) {
		$getbrowse[6][browse] = "야후로봇";
		$getbrowse[6][hit] += $row[cb_hit];
		$getbrowse[6][uptime] = $row[cb_uptime];
	}
    else if(preg_match("/Konqueror/", $Browse)) {
		$getbrowse[7][browse] = "Konqueror";
		$getbrowse[7][hit] += $row[cb_hit];
		$getbrowse[7][uptime] = $row[cb_uptime];
	}
    else if(preg_match("/WISEnutbot/", $Browse)) {
		$getbrowse[8][browse] = "WISEnutbot";
		$getbrowse[8][hit] += $row[cb_hit];
		$getbrowse[8][uptime] = $row[cb_uptime];
	}

    else if(preg_match("/openfind/", $Browse)) {
		$getbrowse[9][browse] = "openfind";
		$getbrowse[9][hit] += $row[cb_hit];
		$getbrowse[9][uptime] = $row[cb_uptime];
	}
    else if(preg_match("/koreawisenut.com/", $Browse)) {
		$getbrowse[10][browse] = "koreawisenut.com";
		$getbrowse[10][hit] += $row[cb_hit];
		$getbrowse[10][uptime] = $row[cb_uptime];
	}
    else if(preg_match("/alltheweb.com/", $Browse)) {
		$getbrowse[11][browse] = "alltheweb.com";
		$getbrowse[11][hit] += $row[cb_hit];
		$getbrowse[11][uptime] = $row[cb_uptime];
	}
    else if($Browse == "") {
		$getbrowse[13][browse] = "정보없음";
		$getbrowse[13][hit] += $row[cb_hit];
		$getbrowse[13][uptime] = $row[cb_uptime];
	}
	else {
		$getbrowse[12][browse] = "기타";
		$getbrowse[12][hit] += $row[cb_hit];
		$getbrowse[12][uptime] = $row[cb_uptime];
	}
}

include("_header.php");
?>

<table width="680" border="0" cellpadding="3" cellspacing="0">
<tr height="25" bgcolor="#DDDDDD">
    <td width="50" align="center"><b>NO</b></td>
    <td><b>브라우즈별</b></td>
    <td width="140" align="center"><b>업데이트</b></td>
    <td width="50" align="right"><b>히트</b></td>
    <td width="50" align="right"><b>방문율</b></td>
    <td width="160"><b>그래프</b></td>
</tr>
<tr align="center" bgcolor="#C0C0C0"><td colspan="6" height="1"></td></tr>

<?
for($i=0, $count=count($getbrowse); $i<$count; $i++) {
    $bgcolor = ($i%2) ? "#F5F5F5" : "#FFFFFF";
    $rows[$i][_facing] = $count - $i;
    $per = $getbrowse[$i][hit] / $all_total * 100;
    $graph = intval($getbrowse[$i][hit] / $all_total * 160);
    if($graph < 1) $graph = 1;
?>

<tr height="25" bgcolor="<?=$bgcolor?>" class="gray">
    <td align="center"><?=$rows[$i][_facing]?></td>
    <td><?=$getbrowse[$i][browse]?></td>
    <td align="center"><?=date("Y년 m월 d일 H시", $getbrowse[$i][uptime])?></td>
    <td align="right"><?=number_format($getbrowse[$i][hit])?></td>
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
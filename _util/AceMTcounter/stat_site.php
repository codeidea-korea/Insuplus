<?
include("./lib/_util.php");
include("./lib/_connect.php");
include("./lib/_class.php");

$res = mysql_query("SELECT SUM(cs_hit) FROM AceMTcounter_site");
$rs = mysql_fetch_row($res);
$all_total = intval($rs[0]);

// 정렬
if($_GET[ko] === NULL) {
	$_GET[ko] = "cs_uptime";
	$_GET[kc] = "DESC";
}
$korder = "$_GET[ko] $_GET[kc]";

// 검색
if($_GET['domain'] != "") {
	$myFlds[] = "cs_site LIKE '%$_GET[domain]%'";
}
if(count($myFlds)) {
	$findquery = " AND " . implode(" AND ", $myFlds);
}

// 페이징
if($_GET[nowpg] < 1) $_GET[nowpg] = 1;
if($_GET[lineNum] < 1) $_GET[lineNum] = 20;
$obj = new listManager($_GET[nowpg], 10, $_GET[lineNum]);
$obj->setTotal("SELECT cs_hit FROM AceMTcounter_site WHERE 1 {$findquery}");
$result_rows = $obj->getList("SELECT cs_hit AS now_total, cs_hit*100/$all_total AS per, A.* FROM AceMTcounter_site A WHERE 1 {$findquery} ORDER BY {$korder}");
list($pgtop, $pgend, $getpgtop, $getpgpre, $getpgnext, $getpgend) = $obj->getPage();
if($getpgtop > 0) {
	$url = url_clear("{$_SERVER[PHP_SELF]}?{$_SERVER[QUERY_STRING]}&nowpg=$getpgtop");
	$link_pgtop = "<a href='$url'><img src='./img/first.gif' border='0' align='absmiddle'></a>";
}
else {
	$link_pgend = "<img src='./img/first.gif' border='0' align='absmiddle'>";
}
if($getpgpre > 0) {
	$url = url_clear("{$_SERVER[PHP_SELF]}?{$_SERVER[QUERY_STRING]}&nowpg=$getpgpre");
	$link_pgpre = "<a href='$url'>[이전]</a>";
}
if($getpgnext > 1) {
	$url = url_clear("{$_SERVER[PHP_SELF]}?{$_SERVER[QUERY_STRING]}&nowpg=$getpgnext");
	$link_pgnext = "<a href='$url'>[다음]</a>";
}
if($getpgend > 1) {
	$url = url_clear("{$_SERVER[PHP_SELF]}?{$_SERVER[QUERY_STRING]}&nowpg=$getpgend");
	$link_pgend = "<a href='$url'><img src='./img/last.gif' border='0' align='absmiddle'></a>";
}
else {
	$link_pgend = "<img src='./img/last.gif' border='0' align='absmiddle'>";
}

if($_GET[nowpg] > 1 && count($result_rows)==0) {
	$_GET[nowpg] = $getpgend;
	tomove(url_clear("$_SERVER[REQUEST_URI]&nowpg=$_GET[nowpg]"));
}

$nowDir = dirname($_SERVER[PHP_SELF]);

include("_header.php");
?>

<table width="680" border="0" cellpadding="3" cellspacing="0">
<tr><td colspan="6" bgcolor="#F0F0F0"></td></tr>
<form>
<tr height="25" class="gray">
    <td>
        도메인찾기:
        <input type="text" name="domain" value="<?=$_GET['domain']?>" size="40">
        &nbsp;
        <input type="submit" value="검색">
    </td>
</tr>
</form>
<tr><td colspan="6" bgcolor="#F0F0F0"></td></tr>
</table>

<br>

<table width="680" border="0" cellpadding="3" cellspacing="0">
<tr height="25" bgcolor="#DDDDDD">
    <td width="50" align="center">
        <font color="#000000"><b>NO</b></font>
    </td>
    <td>
        <b><?=listTitleSort("<font color='#000000'>방문한 사이트</font>", "cs_site", "ASC", $nowDir)?></b>
    </td>
    <td width="50" align="right">
        <b><?=listTitleSort("<font color='#000000'>히트</font>", "now_total", "ASC", $nowDir)?></b>
    </td>
    <td width="70" align="center">
        <b><?=listTitleSort("<font color='#000000'>업데이트</font>", "cs_uptime", "ASC", $nowDir)?></b>
    </td>
    <td width="50" align="right">
        <b><?=listTitleSort("<font color='#000000'>방문율</font>", "per", "ASC", $nowDir)?></b>
    </td>
    <td width="200">
        <font color="#000000"><b>그래프</b></font>
    </td>
</tr>
<tr><td colspan="6" bgcolor="#F0F0F0"></td></tr>

<?
$c = count($result_rows);
for($i=0; $i<$c; $i++) {
    $bgcolor = ($i%2) ? "#F5F5F5" : "#FFFFFF";
    $graph = intval($result_rows[$i][now_total] / $all_total * 200);
    if($graph < 1) $graph = 1;
?>

<tr height="25" class="gray">
    <td align="center"><?=$result_rows[$i][_number]?></td>
    <td><a href="<?=$result_rows[$i][cs_site]?>"><?=cut_string(urldecode($result_rows[$i][cs_site]), 50)?></a></td>
    <td align="right"><?=number_format($result_rows[$i][now_total])?></td>
    <td align="center"><?=date("Y-m-d", $result_rows[$i][cs_uptime])?></td>
    <td align="right"><?=sprintf("%5.2f", $result_rows[$i][per])?>%</td>
    <td>
        <table cellpadding="0" cellspacing="0" border="0">
        <tr bgcolor="#FF9900"><td width="<?=$graph?>" height="22"></td></tr>
        </table>
    </td>
</tr>
<tr><td colspan="11" bgcolor="#F0F0F0"></td></tr>

<?
}
if($c == 0) {
?>

<tr align="center" height="50">
    <td colspan="6"><font color="red">값이 존재하지 않습니다.</font></td>
</tr>
<tr align="center" bgcolor="#EAEAEA"><td colspan="6" height="1"></td></tr>

<?
}
?>

<tr height="5">
    <td colspan="6"></td>
</tr>
<tr align="center">
    <td colspan="6">
        <table border="0" cellpadding="4" cellspacing="0">
        <tr>
            <td>
                <?=$link_pgtop?>
                ...
                <?=$link_pgpre?>
                <?
                for ($i=$pgtop; $i<=$pgend; $i++) {
                    $link_page = url_clear("{$_SERVER[PHP_SELF]}?{$_SERVER[QUERY_STRING]}&nowpg=$i");
                    if ($i == $_GET[nowpg]) {
                        echo " <span class='page_bold'>[{$i}]</span> ";
                    }
                    else {
                        echo " <a href=\"{$link_page}\" class='page_bold'>[{$i}]</a> ";
                    }
                }
                if ($pgend == 0) {
                    echo " <span class='page_bold'>[1]</span> ";
                }
                ?>

                <?=$link_pgnext?>
                ...
                <?=$link_pgend?>
            </td>

        </tr>
        </table>
    </td>
</tr>
</table>

<?
include("_footer.php");
?>
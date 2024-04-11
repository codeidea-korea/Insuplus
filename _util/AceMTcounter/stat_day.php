<?
include("./lib/_util.php");
include("./lib/_connect.php");

if($_GET['fy'] == "") {
    $_GET['fy'] = date("y");
}
if($_GET['fm'] == "") {
    $_GET['fm'] = date("m");
}
if($_GET['fd'] == "") {
    $_GET['fd'] = date("d");
}

$sql = "SELECT MIN(ci_yy), MAX(ci_yy) FROM AceMTcounter_ip";
$res = mysql_query($sql);
$rs  = mysql_fetch_row($res);
$sy  = $rs[0];
$ey  = $rs[1];

$select_yy = Array();
for($i=$sy; $i<=$ey; $i++) {
    $fi = sprintf("%02d", $i);
    $select_yy[$fi] = "20" . sprintf("%02d", $i) . "년";
}
$select_mm = Array();
for($i=1; $i<=12; $i++) {
    $fi = sprintf("%02d", $i);
    $select_mm[$fi] = $fi . "월";
}
$select_dd = Array();
for($i=1; $i<=31; $i++) {
    $fi = sprintf("%02d", $i);
    $select_dd[$fi] = $fi . "일";
}

$date1 = date("ymd");
$y1 = substr($date1, 0, 2);
$m1 = substr($date1, 2, 2);
$d1 = substr($date1, 4, 2);
$u1 = $_SERVER['PHP_SELF'] . "?fy=" . $y1 . "&fm=" . $m1 . "&fd=" . $d1;

$date2 = date("ymd", mktime(0,0,0,$m1-1,$d1,$y1));
$y2 = substr($date2, 0, 2);
$m2 = substr($date2, 2, 2);
$d2 = substr($date2, 4, 2);
$u2 = $_SERVER['PHP_SELF'] . "?fy=" . $y2 . "&fm=" . $m2 . "&fd=" . $d2;

$date3 = date("ymd", mktime(0,0,0,$m1-2,$d1,$y1));
$y3 = substr($date3, 0, 2);
$m3 = substr($date3, 2, 2);
$d3 = substr($date3, 4, 2);
$u3 = $_SERVER['PHP_SELF'] . "?fy=" . $y3 . "&fm=" . $m3 . "&fd=" . $d3;

$ul = "fy=" . $_GET['fy'] . "&fm=" . $_GET['fm'] . "&fd=" . $_GET['fd'];

if($_GET['fy'] != "") {
	$findQuery[] = "ci_yy='$_GET[fy]'";
}
if($_GET['fm'] != "") {
	$findQuery[] = "ci_mm='$_GET[fm]'";
}
if(count($findQuery)) {
    $FindWhere = " WHERE " . implode(" AND ", $findQuery);
}

$res = mysql_query("SELECT 1 FROM AceMTcounter_ip GROUP BY ci_yy, ci_mm");
$total_rows = mysql_num_rows($res);
if($total_rows <= 0) {
    $total_rows = 1;
}

$res = mysql_query("SELECT SUM(ci_hit), SUM(ci_todayip) FROM AceMTcounter_ip");
$rs = mysql_fetch_row($res);
$total_vi_hit = $rs[0];
$total_ip_hit = $rs[1];

$res = mysql_query("SELECT SUM(ci_hit), SUM(ci_todayip) FROM AceMTcounter_ip {$FindWhere}");
$rs = mysql_fetch_row($res);
$search_vi_hit = $rs[0];
$search_ip_hit = $rs[1];

include("_header.php");
?>

<table cellpadding="4" cellspacing="0" border="0">
<form>
<tr>
    <td>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
        	<td>
                날짜검색:
                <select name="fy" onchange="this.form.submit()">
                    <?=selectbox($select_yy, $_GET['fy'], true)?>
                </select>
                <select name="fm" onchange="this.form.submit()">
                    <?=selectbox($select_mm, $_GET['fm'], true)?>
                </select>
                <select name="fd" onchange="this.form.submit()">
                    <?=selectbox($select_dd, $_GET['fd'], true)?>
                </select>
            </td>
            <td align="center">
                <table cellpadding="2" cellspacing="0" border="0">
                <tr>
                	<td>
                        <table cellpadding="2" cellspacing="0" border="0">
                        <tr>
                        	<td width="12" height="12" bgcolor="#95DEFD"></td>
                        	<td width="1"></td>
                        	<td width="12" height="12" bgcolor="#3300FF"></td>
                            <td width="1"></td>
                        	<td>비교영역</td>
                        </tr>
                        </table>
                    </td>
                    <td width="15"></td>
                	<td>
                        <table cellpadding="2" cellspacing="0" border="0">
                        <tr>
                        	<td width="12" height="12" bgcolor="#FF6600"></td>
                            <td width="1"></td>
                            <td width="12" height="12" bgcolor="#FF0000"></td>
                            <td width="1"></td>
                        	<td>검색영역</td>
                        </tr>
                        </table>
                    </td>
                </tr>
                </table>
            </td>
            <td align="right">
                <input type="button" value="현재달" class="button" onclick="location.href='<?=$u1?>'">
                <input type="button" value="1달전" class="button" onclick="location.href='<?=$u2?>'">
                <input type="button" value="2달전" class="button" onclick="location.href='<?=$u3?>'">
            </td>
        </tr>
        </table>
    </td>
</tr>
</form>
<tr>
    <td height="10"></td>
</tr>
<tr>
    <td><img src="img_day1-1.php?<?=$ul?>"></td>
</tr>
<tr>
    <td align="center">
        [표1-1]. 일별 방문수 합계
        (
        <?=number_format($search_vi_hit)?>
        /
        <?=number_format($total_vi_hit)?>
        )
    </td>
</tr>
<tr>
    <td><img src="img_day1-2.php?<?=$ul?>"></td>
</tr>
<tr>
    <td align="center">
        [표1-2]. 일별 평균 방문수
        (
        <?=number_format($search_vi_hit)?>
        /
        <?=number_format($total_vi_hit/$total_rows)?>
        )
    </td>
</tr>
<tr>
    <td height="15"></td>
</tr>
<tr>
    <td><img src="img_day2-1.php?<?=$ul?>"></td>
</tr>
<tr>
    <td align="center">
       [표2-1]. IP당 일별 방문수 합계
       (
        <?=number_format($search_ip_hit)?>
        /
        <?=number_format($total_ip_hit)?>
        )
    </td>
</tr>
<tr>
    <td><img src="img_day2-2.php?<?=$ul?>"></td>
</tr>
<tr>
    <td align="center">
        [표2-2]. IP당 일별 평균 방문수
        (
        <?=number_format($search_ip_hit)?>
        /
        <?=number_format($total_ip_hit/$total_rows)?>
        )
    </td>
</tr>
</table>


<?
include("_footer.php");
?>
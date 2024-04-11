<?
include("./lib/_util.php");
include("./lib/_connect.php");
include("./lib/_gd.php");

Header("Cache-Control: no-cache, must-revalidate");
Header("Pragma: no-cache");
Header("Content-Type:image/jpeg");

$select_ww = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");

$fw = date("w", mktime(0,0,0,$_GET['fm'],$_GET['fd'],$_GET['fy']));
$fsdate = date("ymd", mktime(0,0,0,$_GET['fm'],$_GET['fd']-$fw,  $_GET['fy']));
$fedate = date("ymd", mktime(0,0,0,$_GET['fm'],$_GET['fd']-$fw+6,$_GET['fy']));

$fsy = substr($fsdate, 0, 2);
$fsm = substr($fsdate, 2, 2);
$fsd = substr($fsdate, 4, 2);
$fey = substr($fedate, 0, 2);
$fem = substr($fedate, 2, 2);
$fed = substr($fedate, 4, 2);

$findQuery[] = "CONCAT(ci_yy,ci_mm,ci_dd) BETWEEN '{$fsy}{$fsm}{$fsd}' AND '{$fey}{$fem}{$fed}'";
if($_GET['fw'] != "") {
	$findQuery[] = "ci_ww='$_GET[fw]'";
}
else {
    $_GET['fw'] = $fw;
}
if(count($findQuery)) {
    $FindWhere = " WHERE " . implode(" AND ", $findQuery);
}

$res = mysql_query("SELECT ci_ww, SUM(ci_hit) FROM AceMTcounter_ip GROUP BY ci_ww");
while($rs = mysql_fetch_row($res)) {
    $ww = intval($rs[0]);
	$res2 = mysql_query("SELECT 1 FROM AceMTcounter_ip WHERE ci_ww='$ww' GROUP BY ci_yy, ci_mm, ci_dd");
	$total_rows = mysql_num_rows($res2);
	if($total_rows <= 0) {
        $total_rows = 1;
    }
    $all_hit[$ww] = sprintf("%d", $rs[1]/$total_rows);
}

$res = mysql_query("SELECT ci_ww, SUM(ci_hit) FROM AceMTcounter_ip {$FindWhere} GROUP BY ci_ww");
while($rs = mysql_fetch_row($res)) {
    $ww = intval($rs[0]);
    $search_hit[$ww] = sprintf("%d", $rs[1]);
}

$max_val = max(array_merge_recursive($all_hit, $search_hit));

/*
    환경설정

    - gnx : 가로 격자칸수
    - gdx : 가로 증가폭
    - gsx : 가로 시작위치
    - gex : 가로 종료위치
    - gmx : 가로 전체사이즈

    - gny : 세로 격자칸수
    - gdy : 세로 증가폭
    - gsy : 세로 시작위치
    - gey : 세로 종료위치
    - gmy : 세로 전체사이즈

    - gdd : 전체그래프의 간격
    - gsdd: 전체그래프와 검색그래프의 간격
*/

$gnx = 7;
$gdx = 82;
$gsx = 50;
$gex = $gsx + $gnx * $gdx;
$gmx = 680;

$gny = 10;
$gdy = 25;
$gsy = 20;
$gey = $gsy + $gny * $gdy;
$gmy = 300;

$gdd = 4;
$gsdd = 4;

$mode = "week";
include("./lib/_graph_engin.php");
?>
<?
include("./lib/_util.php");
include("./lib/_connect.php");
include("./lib/_gd.php");

Header("Cache-Control: no-cache, must-revalidate");
Header("Pragma: no-cache");
Header("Content-Type:image/jpeg");

if($_GET['fy'] != "") {
	$findQuery[] = "ci_yy='$_GET[fy]'";
}
if(count($findQuery)) {
    $FindWhere = " WHERE " . implode(" AND ", $findQuery);
}

$res = mysql_query("SELECT ci_mm, SUM(ci_todayip) FROM AceMTcounter_ip GROUP BY ci_mm");
while($rs = mysql_fetch_row($res)) {
    $mm = intval($rs[0]);
	$res2 = mysql_query("SELECT 1 FROM AceMTcounter_ip WHERE ci_mm='$mm' GROUP BY ci_yy");
	$total_rows = mysql_num_rows($res2);
	if($total_rows <= 0) {
        $total_rows = 1;
    }
    $all_hit[$mm-1] = sprintf("%d", $rs[1]/$total_rows);
}

$res = mysql_query("SELECT ci_mm, SUM(ci_todayip) FROM AceMTcounter_ip {$FindWhere} GROUP BY ci_mm");
while($rs = mysql_fetch_row($res)) {
    $mm = intval($rs[0]);
    $search_hit[$mm-1] = sprintf("%d", $rs[1]);
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

$gnx = 12;
$gdx = 47;
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

$mode = "month";
include("./lib/_graph_engin.php");
?>
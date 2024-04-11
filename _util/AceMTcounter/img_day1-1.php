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
if($_GET['fm'] != "") {
	$findQuery[] = "ci_mm='$_GET[fm]'";
}
if(count($findQuery)) {
    $FindWhere = " WHERE " . implode(" AND ", $findQuery);
}

$res = mysql_query("SELECT ci_dd, SUM(ci_hit) FROM AceMTcounter_ip GROUP BY ci_dd");
while($rs = mysql_fetch_row($res)) {
	$dd = intval($rs[0]);
	$all_hit[$dd-1] = $rs[1];
}

$res = mysql_query("SELECT ci_dd, SUM(ci_hit) FROM AceMTcounter_ip {$FindWhere} GROUP BY ci_dd");
while($rs = mysql_fetch_row($res)) {
	$dd = intval($rs[0]);
	$search_hit[$dd-1] = $rs[1];
}

$max_val = max($all_hit);

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

$gnx = 31;
$gdx = 18;
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

$mode = "day";
include("./lib/_graph_engin.php");
?>
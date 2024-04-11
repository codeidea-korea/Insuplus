<?
	header("Content-Type: text/html; charset=UTF-8");
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/config.php";

$AceMTcounterVersion = "3.00";
/*****************************************************************
// 버전 : AceMTcounter 3.0;
// 작성자 : 슈퍼개발(program@mytechnic.com)
// 홈페이지 : mytechnic.com

    참고:
    이 주석을 삭제하면, 버전 업데이트를 할수 없을 것입니다.
    주석을 삭제하고 싶다면, 버전정보는 꼭 기억해 두셔야 하며,
    개발을 개인적으로 진행하시거나, 업데이트를 하지 않을 경우에는
    삭제하여도 무방하다고 봅니다.
*****************************************************************/

$conn = mysql_connect($mysql_host, $mysql_user, $mysql_password);
mysql_select_db($mysql_database_name, $conn);

$yy = date("y");
$mm = date("m");
$dd = date("d");
$hh = date("H");
$ww = date("w");
?>
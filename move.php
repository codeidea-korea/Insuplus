<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
$moveEvent = REQSTR($_GET["cd"], "");

if(!empty($moveEvent)){
  header( 'Location: https://www.insuplus.co.kr/html/main/' );
}
?>
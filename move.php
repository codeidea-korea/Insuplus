<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
$moveEvent = REQSTR($_GET["cd"], "");
$LandingURL = "/html/customer/landing";

if(!empty($moveEvent)) {
  switch($moveEvent) {
    case "sinhancard":
      header( "Location: ".$LandingURL."/sinhancard/sinhancard.php" );
      break;
    default:
    header( "Location: /html/main/" );
      break;
  }
} else {
  header( "Location: /html/main/" );
}
?>
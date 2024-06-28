<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
$moveEvent = REQSTR($_GET["cd"], "");
$LandingURL = "/html/customer/landing";

if(!empty($moveEvent)) {
  switch($moveEvent) {
    case "shinhancard":
      // header( "Location: ".$LandingURL."/shinhancard/shinhancard.php" );
      header( "Location: /html/main/" );
      break;
    default:
    header( "Location: /html/main/" );
      break;
  }
} else {
  header( "Location: /html/main/" );
}
?>
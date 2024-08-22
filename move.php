<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
$moveEvent = REQSTR($_GET["cd"], "");
$LandingURL = "/html/customer/landing";

if(!empty($moveEvent)) {
  switch($moveEvent) {
    case "shinhancard":
      header( "Location: ".$LandingURL."/shinhan/index.php" );
      break;
    case "daytour":
      header( "Location: ".$LandingURL."/daytour/daytour_landing.php" );
      break;
    case "myshop":
      header( "Location: /html/insurance/partner_step01.php?alliance_code=TVRiRCtmaWVIZEZBT3Y3QWk0b09Tdz09" );
      break;
    default:
    header( "Location: /html/main/" );
      break;
  }
} else {
  header( "Location: /html/main/" );
}
?>
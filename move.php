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
      header( "Location: /html/insurance/partner_step01.php?alliance_code=TVRiRCtmaWVIZEZBT3Y3QWk0b09Tdz09&utm_source=myshop&utm_medium=landing" );
      break;
    case "rda":
        header( "Location: /html/insurance/partner_step01.php?alliance_code=emZabnplanNGTThLRHdScWc5eVZkZz09" );
        break;
    case "surecare":
        header( "Location: /html/insurance/partner_step01.php?alliance_code=RGI4MTh4TTR3SlkrRVBpYlVId0JjQT09" );
        break;
    case "eyagi":
        header( "Location: /html/insurance/partner_step01.php?alliance_code=T2d6WjE2OG94QnhUQXRkYnRRRlpuUT09" );
        break;
    case "lttravel":
        header( "Location: /html/insurance/partner_step01.php?alliance_code=MmdLSFc3U0Q5WGtLUWpnTzAwQURmUT09" );
        break;
    default:
    header( "Location: /html/main/" );
      break;
  }
} else {
  header( "Location: /html/main/" );
}
?> 
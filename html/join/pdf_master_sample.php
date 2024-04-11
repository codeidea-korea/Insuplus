<!DOCTYPE html>
<?

require_once("./dompdf/dompdf_config.inc.php");

$chHtml = "";

$chHtml .="<html>";

$chHtml .="<head>";

$chHtml .="<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />";

$chHtml .="<title>pdf 테스트</title>";

$chHtml .="<style>";

$chHtml .="     div, td, span, p, li {color:#000; font-family:'NanumGothic', '나눔고딕', 'dotum', '돋움'; font-size:14px;}";
$chHtml .="</style>";

$chHtml .="</head>";

$chHtml .="<body>";

$chHtml .="     <div>안녕하세요. PDF 변환 테스트 입니다</div>";

$chHtml .="</body>";

$chHtml .="</html>";




$dompdf = new DOMPDF();
//$dompdf->set_option('defaultFont', 'NanumGothic');
$dompdf->load_html($chHtml);
$dompdf->render();

$dompdf->stream("sample33.pdf");



?>

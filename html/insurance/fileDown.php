<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

$mode = $_POST["mode"];

if($mode=="down") {
	$fileDown = new FileUploadManager();
	
	$file = $_POST["file"];	
	$path = $_SERVER["DOCUMENT_ROOT"]."/".all_seed_dec($file);
	$fileDown->download($path,$_POST["filename"],$_POST["filesize"]);
}
?>

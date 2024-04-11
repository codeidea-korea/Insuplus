<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

$mode = $_GET["mode"];

if($mode=="down") {
	$fileDown = new FileUploadManager();
	
	$seq = $_GET["seq"];
	$bc_id = $_GET["bc_id"];
	
	if($bc_id == "ins_agree") {
		$data = selInsAgreeFile($seq);
	} else  if($bc_id == "service_agree") {
		$data = selServiceFile($seq);
	}
		
	$path = $_SERVER["DOCUMENT_ROOT"]."/_data/board/".$bc_id."/".$data["file_realname"];
	
	if($data["file_realname"]) {
		$fileDown->download($path,$data["file_name"],$data["file_size"]);
	} else {
		echo "파일이 존재하지 않습니다.";
	}
} 

?>

<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

if (!$api){
	echo "API코드가 없습니다.";
	exit;
}


$functionParam = [
  "api"=>$api,
  "pr_cd"=>$pr_cd,
  "plan_cd"=>$plan_cd,
  "plan_seq"=>$plan_seq,
  "keyword"=>$keyword
];
 

// print_r($functionParam);
//  echo 'categories(0) : '.var_dump($functionParam);
$rows = getPlanInfo_new($functionParam);
echo json_encode($rows);
?>
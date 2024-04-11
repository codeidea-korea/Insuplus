<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
// 관리자 체크
// admin_chk($auth_admin, $url_admin_login_out);

// if (!$pr_cd){
// 	echo "상품코드가 없습니다.";
// 	exit;
// }

// 플랜 보장내역, 서비스 요금 테이블 조회
// plan_type is 'G' : Guarantee, 'S' : Service
$functionParam = [
  "depth0"=>$depth0,
  "depth1"=>$depth1,
  "depth2"=>$depth2,
  "depth3"=>$depth3
];

// echo 'categories(0) : '.var_dump($functionParam);
$rows = getPlanNameWithCategory($functionParam);

?><?= json_encode($rows)?>
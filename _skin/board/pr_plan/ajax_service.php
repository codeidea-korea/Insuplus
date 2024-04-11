<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
// 관리자 체크
admin_chk($auth_admin, $url_admin_login_out);

if (!$pr_cd){
	echo "상품코드가 없습니다.";
	exit;
}

$rows = getPlanService($pr_cd, $seq);

?><?= json_encode($rows)?>
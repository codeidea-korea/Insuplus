<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.mail.php";
header("Content-Type: application/json");

$email = REQSTR($email, "");
$user_name = all_seed_enc(REQSTR($name, ""));
$o_isdn1 = all_seed_enc(REQSTR($birth, ""));
$o_isdn2 = all_seed_enc(REQSTR($num2, ""));
$plan_seq = REQSTR($plan_seq, '');
$attempt_date = date('Y-m-d H:i:s');
$rows = array();

$sql			= "
  SELECT count(*)
  FROM tbl_restricted_users
  where is_restricted = 'Y'
  and user_name = '$user_name'
  and o_isdn1 = '$o_isdn1'
  and o_isdn2 = '$o_isdn2'
";
$cnt = $dbcon->getCount($sql);
if($cnt > 0) {
  
  // 플랜검색
  $SQL_PLAN = "select * from tbl_board_plan where seq=".$plan_seq." and plan_status='Y' AND secret='Y' ";
  //echo $SQL_PLAN;
  $result_plan = $dbcon -> query($SQL_PLAN);
  $row_plan = $dbcon -> fetch_array($result_plan);

  $param = array();
	$param["name"] = all_seed_dec($user_name);
	$param["o_isdn"] = all_seed_dec($o_isdn1)."-".substr(all_seed_dec($o_isdn2), 0, 1);
	$param['product_name'] = $row_plan["ins_plan_name"]." - ".$Arr_plan_cd[$row_plan["plan_cd"]];
  $param['attempt_date'] = $attempt_date;

  mailRestrictedUsersSend($param, $email);
}
echo(json_encode(array("success"=>"1","cnt"=>$cnt)));
?>
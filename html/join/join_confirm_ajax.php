<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";


$mobile = $_POST['mobile'];
$param = '';
for ($i = 0; $i < 6; $i++) {
    $param .= mt_rand(0, 9);
}


kakaoInsuplusCertificationNumberSend($param,$mobile);   // 가입안내



?>  
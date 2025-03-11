<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

// 요청 정보 가져오기
$requestUrl = $_SERVER['REQUEST_URI'];  // 요청 URL
$requestMethod = $_SERVER['REQUEST_METHOD'];  // 요청 방식(GET/POST)
$user_id = $_SESSION['ss_u_id'] ? $_SESSION['ss_u_id'] : '';

$stmt = "INSERT INTO tbl_actionlog (id, url, program) VALUES ('$user_id','$requestUrl', '$actionProgram')";
$dbcon->query($stmt);
?>
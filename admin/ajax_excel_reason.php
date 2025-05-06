<?php
error_reporting(0);
ini_set('display_errors', 0);

$reason = isset($_POST['reason']) ? $_POST['reason'] : '';
$program = isset($_POST['program']) ? $_POST['program'] : '';
$excel_enc = isset($_POST['excel_enc']) ? $_POST['excel_enc'] : '';
$actionProgram = preg_replace('/[^가-힣a-zA-Z0-9]/u', '', $program);

if ($reason) {
    $actionProgram= $actionProgram." - ".$reason." - ".$excel_enc;
    include_once $_SERVER["DOCUMENT_ROOT"] . "/actionlog.php";

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
<?php
if ($path_root == "") $path_root = $_SERVER[DOCUMENT_ROOT]."/";
session_save_path($path_root."_session/");
session_start();
require_once('chsignup.class.php');
$im = &new chsignup;
$im->set_font('adler.ttf');
$_SESSION['signupcode'] = $im->create_image();
?>

<?php
include('kcaptcha.php');

session_start();
$captcha = new KCAPTCHA();
$captcha->setKeyString(get_session("captcha_keystring"));
$captcha->getKeyString();
$captcha->image();
$captcha = "";

echo "132413241234134";

?>
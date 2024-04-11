<?php
session_start();
if($mode="action"){
	"<br>액션 -> ".var_dump($_POST);
	echo "<br>";
	"<br>세션 -> ".var_dump($_SESSION);
	echo "<br>";
	if($_POST['captcha'] != $_SESSION['digit']) die("ㅋㅋㅋㅋ");
}
?>
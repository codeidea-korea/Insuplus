<?php
if ($path_root == "") $path_root = $_SERVER[DOCUMENT_ROOT]."/";
session_save_path($path_root."_session/");
session_start();
?>
<html>
<head>
<title>CHSignup</title>
</head>
<body onload="document.form.imagecode.focus()">
<h2>CHSignup</h2>
<?php
if (isset($_POST['imagecode'])
     && strlen($_POST['imagecode']) == 5
     && isset($_SESSION['signupcode'])
     && strtolower($_POST['imagecode']) == $_SESSION['signupcode'])
{
    echo '<b><font color="green">코드가 정확하게 맞았습니다. :-)</font></b><BR>';
}
elseif (isset($_POST['imagecode']))
{
    echo '<b><font color="red">코드가 일치하지 않습니다. :-(</font></b><BR>';
}
echo strtolower($_POST['imagecode'])."<BR>";
echo $_SESSION['signupcode']."<BR>";

unset ($_SESSION['signupcode']);
?>

<form name="form" method="post" action="<?=$_SERVER['PHP_SELF']?>?<?=SID?>">
<table>
    <tr>
        <td colspan=2>이미지에 보이는 글자를 입력하여 주십시오.</td>
    </tr>
    <tr>
        <td><img src="chsignup.php?<?=SID?>"><?=SID?></td>
        <td><input type="text" name="imagecode"></td>
    </tr>
    <tr>
        <td colspan=2><input type="submit" value="확인"></td>
    </tr>
</table>
</form>
</body>
</html>

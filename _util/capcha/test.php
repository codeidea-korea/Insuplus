<?php
session_start();
include($_SERVER[DOCUMENT_ROOT]."/html/_util/capcha/simple-php-captcha.php");
$_SESSION['captcha'] = simple_php_captcha();
print_r($_SESSION['captcha']);
$_SESSION['captcha_txt'] = $_SESSION['captcha'][code];

var_dump($_SESSION);

echo $_SESSION['captcha_txt']."<br>";
?>
<form method="POST" action="action.php?mode=action" onsubmit="return checkForm(this);">
<p><img src="<?=$_SESSION['captcha']['image_src'] ?>" border="1" alt="CAPTCHA"></p>
<p><input type="text" size="6" maxlength="5" name="captcha" value=""><br>
<button type="submit">입력</button>
</form>
<script type="text/javascript">

  function checkForm(form)
  {
 
    if(!form.captcha.value.match(/^\d{5}$/)) {
      alert('똑바로 입력해라.);
      form.captcha.focus();
      return false;
    }
    return true;
  }

</script>


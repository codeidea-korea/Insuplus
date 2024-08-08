<!DOCTYPE html>
<?
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out); // 관리자 체크

if ($_POST) {
  $currentPassword = $_POST['currentPassword'];
  $newPassword = $_POST['newPassword'];
  $confirmPassword = $_POST['confirmPassword'];

  $SQL = "SELECT * FROM tbl_user WHERE u_id = '$ss_u_id'";
  $RS = $dbcon->query($SQL);
  $row = $dbcon->fetch_array($RS);

  if ($currentPassword === all_seed_dec($row['u_pw'])) {
    if ($newPassword === $confirmPassword) {
		  $u_pw = all_seed_enc($newPassword);
      $SQL = "UPDATE tbl_user SET u_pw = '$u_pw' WHERE u_id = '$ss_u_id'";
      $dbcon->query($SQL);
      echo '<script>alert("비밀번호가 변경되었습니다."); window.close();</script>';
    } else {
      alert_page('비밀번호가 일치하지 않습니다.');
    }
  } else {
    alert_page('현재 비밀번호가 일치하지 않습니다.');
  }
}
?>
<html>
<head>
  <title>Password Change</title>
	<link href="/_css/admin.css" rel="stylesheet" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
  <style>
    body {
      padding: 10px;
    }
    #passwordChangePopup {
      width: 300px;
      margin: 0 auto;
      padding: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    h2 {
      text-align: center;
    }
    label {
      display: block;
    }
    input {
      width: 100%;
      margin-bottom: 10px;
    }
    input[type="submit"] {
      width: auto;
      margin: 0 auto;
    }
  </style>
  <script>
    $(document).ready(function() {
      $('form').submit(function(e) {
        var currentPassword = $('#currentPassword').val();
        var newPassword = $('#newPassword').val();
        var confirmPassword = $('#confirmPassword').val();

        if (currentPassword === '' || newPassword === '' || confirmPassword === '') {
          e.preventDefault();
          alert('비밀번호를 입력 해주세요.');
        } else if (newPassword !== confirmPassword) {
          e.preventDefault();
          alert('비밀번호가 일치하지 않습니다.');
        } else if (!checkPasswordValidation(newPassword)) {
          e.preventDefault();
          alert('비밀번호는 영문, 숫자, 특수문자를 포함하여 8~15자리로 입력해주세요.');
        }
      });
    });

  function checkPasswordValidation(password) {
    let bool = false;
    const pattern1 = /^(?=.*[a-zA-Z])(?=.*[!@#$%^*+=-])(?=.*[0-9]).{8,15}$/;
    const pattern2 = /^(?=.*[a-zA-Z])(?=.*[0-9]).{10,15}$/;
    const pattern3 = /^(?=.*[a-zA-Z])(?=.*[!@#$%^*+=-]).{10,15}$/;
    const pattern4 = /^(?=.*[!@#$%^*+=-])(?=.*[0-9]).{10,15}$/;

    if (pattern1.test(password)) bool = true;
    else if (pattern2.test(password)) bool = true;
    else if (pattern3.test(password)) bool = true;
    else if (pattern4.test(password)) bool = true;

    return bool;
  }
  </script>
</head>
<body>
  <div id="passwordChangePopup">
    <h2>Password Change</h2>
    <form action="passwd_change.php" method="POST">
      <label for="currentPassword">Current Password:</label>
      <input type="password" id="currentPassword" name="currentPassword" required maxlength="15"><br><br>
      
      <label for="newPassword">New Password:</label>
      <input type="password" id="newPassword" name="newPassword" required maxlength="15"><br><br>
      
      <label for="confirmPassword">Confirm Password:</label>
      <input type="password" id="confirmPassword" name="confirmPassword" required maxlength="15"><br><br>
      
      <input type="submit" value="Change Password">
    </form>
  </div>
</body>
</html>
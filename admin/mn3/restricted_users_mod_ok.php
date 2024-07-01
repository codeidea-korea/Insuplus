
<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
$type = REQSTR($action_type, "");

  if($type === "add") {
    $is_restricted = REQSTR($is_restricted['new'], "");
    $user_name = all_seed_enc(REQSTR($user_name['new'], ""));
    $o_isdn1 = all_seed_enc(REQSTR($o_isdn1['new'], ""));
    $o_isdn2 = all_seed_enc(REQSTR($o_isdn2['new'], ""));
    $note = REQSTR($note['new'], "");

    $sql = "INSERT INTO tbl_restricted_users SET ";
    $sql .= " is_restricted='".$is_restricted."' ";
    $sql .= " , user_name='".$user_name."' ";
    $sql .= " , o_isdn1='".$o_isdn1."' ";
    $sql .= " , o_isdn2='".$o_isdn2."' ";
    $sql .= " , note='".$note."' ";
    $sql .= " , regdate=now() ";
    $result = $dbcon -> query($sql);

    if(!$result) {
      alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
      exit;
    }
  } else if($type === "save"){
    $seq = REQSTR($seq, "");
    $is_restricted = REQSTR($is_restricted[$seq], "");
    $user_name = all_seed_enc(REQSTR($user_name[$seq], ""));
    $o_isdn1 = all_seed_enc(REQSTR($o_isdn1[$seq], ""));
    $o_isdn2 = all_seed_enc(REQSTR($o_isdn2[$seq], ""));
    $note = REQSTR($note[$seq], "");

    $sql = "UPDATE tbl_restricted_users SET ";
    $sql .= " is_restricted='".$is_restricted."' ";
    $sql .= " , user_name='".$user_name."' ";
    $sql .= " , o_isdn1='".$o_isdn1."' ";
    $sql .= " , o_isdn2='".$o_isdn2."' ";
    $sql .= " , note='".$note."' ";
    $sql .= " WHERE seq='".$seq."' ";
    $result = $dbcon -> query($sql);

    if(!$result) {
      alert_back("수정 오류입니다. 관리자에게 문의하여 주십시오.");
      exit;
    }
  } else if($type === "delete") {
    $seq = REQSTR($seq, "");

    $sql = "DELETE FROM tbl_restricted_users WHERE seq='".$seq."' ";
    $result = $dbcon -> query($sql);

    if(!$result) {
      alert_back("삭제 오류입니다. 관리자에게 문의하여 주십시오.");
      exit;
    }
  }
?>
<script type="text/javascript">
<!--
alert('정상적으로 처리 되었습니다.')
document.location.href="restricted_users.php?<?=$parameter?>";
//-->
</script>
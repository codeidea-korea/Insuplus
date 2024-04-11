<?
include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

$idx = REQSTR($idx, "");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title> 내용변경 </title>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
 </head>
<?
// 정보 수정시
if ( strlen($idx) > 0 ) {
    //		if ( $ss_u_level < $auth_admin && $ss_idx != $idx ) {
    //			alert_back($msg_login_auth);
    //			exit;
    //		}

    $SQL = "
    select
    *
    from
    tbl_desc_area A
    where
    A.idx = '".$idx."'
    limit 0, 1
    ";
    $result = $dbcon -> query($SQL);
    $rows = $dbcon -> fetch_array($result);
	$content = $rows[$name];
    extract($rows);
    unset($rows);

	$arr_doctor = explode("||",$doctors);
}
?>
 <body>
<form name="frm" method="post">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="80" align="center">
						<?
							include_once $_SERVER["DOCUMENT_ROOT"]."/_util/board/editor.php";
						?>
					</td>
				</tr>
				<tr>
					<td height="80" align="center">
						<a href="javascript: chg_content('<?=$name?>')"><img src="/_skin/board/faq/images/b_btn_submit.gif" hspace="4"></a>
					</td>
				</tr>
			</table>
</form>
 </body>
</html>
<script type="text/javascript">
<!--
function chg_content(val){
	var ff = document.frm;
//	checkSpacContents();
//	if(!checkSpacContents()) {
//		alert("내용을 입력해주세요");
//		editor.focus();
//		return;
//	}
	content = ff.content.value;
//	alert(ff.content.value);
	opener.document.frm.<?=$name?>.value = content;
	opener.document.getElementById("<?=$name?>").innerHTML = content;
	window.close();
}
//-->
</script>
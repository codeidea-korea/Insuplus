<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	#### 계층형 값 설정 끝
	#############################

	$SQL = "
		insert into tbl_doctors_days (doc_name, days, section) values('$doc_name', '$days', '$section')
	";
	$result = $dbcon -> query($SQL);
	if (!$result) {
		$dbcon -> dbcon_close();
		alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
		exit;
	}

	$seq = mysql_insert_id();

	$dbcon -> dbcon_close();
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
alert('등록되었습니다.');
opener.location.reload();
window.close();
//-->
</SCRIPT>
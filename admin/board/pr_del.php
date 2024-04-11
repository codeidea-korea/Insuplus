<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	if ($ss_u_level<6){
		echo "<script>alert('팀장회원 이상만 사용이 가능합니다.');history.back();</script>";
		exit;
	};
	#### 계층형 값 설정 끝
	#############################

	if($idx){

	$SQL = "delete from tbl_symptom where idx=$idx";

	}else{

		echo "<script>alert('정상경로가 아닙니다.');history.back();</script>";
		exit;

	}

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
alert('삭제되었습니다.');
document.location.href="pr_list.php";
//-->
</SCRIPT>
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	#### 계층형 값 설정 끝
	#############################
	if($mode=="write"){
	$SQL = "
		insert into tbl_doctors_etc (content, days, section) values('$content', '$days', '$section')
	";
	}else{
	$SQL = "update tbl_doctors_etc set
	content = '$content'
	Where days = '$days' And section = '$section'
	";
	}

	$result = $dbcon -> query($SQL);
	if (!$result) {
		$dbcon -> dbcon_close();
		alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
		exit;
	}

	$seq = mysql_insert_id();

	$dbcon -> dbcon_close();
	$list = "days_list.php?days=$days&section=$section";
	alert_page("등록되었습니다.", $list, "parent");

?>

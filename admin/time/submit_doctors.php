<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	#### 계층형 값 설정 끝
	#############################
	$Count = count($idx);

	for($i=0; $i < $Count;$i++){
	$SQL = "update tbl_doctors_days set
	A1 = '$A1[$i]'
	,A2 = '$A2[$i]'
	,A3 = '$A3[$i]'
	,A4 = '$A4[$i]'
	,A5 = '$A5[$i]'
	,P1 = '$P1[$i]'
	,P2 = '$P2[$i]'
	,P3 = '$P3[$i]'
	,P4 = '$P4[$i]'
	,P5 = '$P5[$i]'
	,orders = '$orders[$i]'
	Where days = '$days' And section = '$section' And idx=$idx[$i]
	";
//echo $SQL."<br>";
	$result = $dbcon -> query($SQL);
	if (!$result) {
		$dbcon -> dbcon_close();
		alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
		exit;
	}

	}
	$seq = mysql_insert_id();

	$dbcon -> dbcon_close();
	$list = "days_list.php?days=$days&section=$section";
	alert_page("등록되었습니다.", $list, "parent");

?>

<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	#### 계층형 값 설정 끝
	#############################

	if($mode=="write"){

	$SQL = "
		insert into tbl_doctor_schedule set
		r_year			= '$year'
		, r_month		= '$month'
		, r_day			= '$day'
		, doctors		= '$doctor'
		, name1			= '$name1'
		, name2			= '$name2'
		, name3			= '$name3'
		, area_code		= '".$area_code."'
		, time10			= '".$time10."'
		, time11			= '".$time11."'
		, time12			= '".$time12."'
		, time13			= '".$time13."'
		, time14			= '".$time14."'
		, time15			= '".$time15."'
		, time16			= '".$time16."'
		, time17			= '".$time17."'
		, time18			= '".$time18."'
		, time19			= '".$time19."'
		, time20			= '".$time20."'
	";

	}else{

	$SQL = "
		update tbl_doctor_schedule set
		name1 = '$name1'
		,name2 = '$name2'
		,name3 = '$name3'
		, time10			= '".$time10."'
		, time11			= '".$time11."'
		, time12			= '".$time12."'
		, time13			= '".$time13."'
		, time14			= '".$time14."'
		, time15			= '".$time15."'
		, time16			= '".$time16."'
		, time17			= '".$time17."'
		, time18			= '".$time18."'
		, time19			= '".$time19."'
		, time20			= '".$time20."'
		where r_year = '$year' And  r_month = '$month' And r_day = '$day' And doctors = '$doctor' and area_code = '".$area_code."'
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
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
alert('등록되었습니다.');
opener.location.reload();
window.close();
//-->
</SCRIPT>
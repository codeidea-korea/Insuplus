<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	if ($ss_u_level<6){
		echo "<script>alert('팀장회원 이상만 사용이 가능합니다.');history.back();</script>";
		exit;
	};
	#### 계층형 값 설정 끝
	#############################

	if($mode=="write"){

	$r_form2 = str_replace(":","",$from);
	$r_to2 = str_replace(":","",$to);

	$SQL = "
		insert into tbl_doctor (r_year, r_month, r_day, doctors, name1, name2,  r_from, r_to, r_from2, r_to2, st_in, ed_in, t_in,t_area ) values('$year', '$month', '$day', '$doctor', '$name1', '$name2', '$from', '$to', $r_form2, $r_to2, $st_in, $ed_in, $t_in, '$t_area')
	";

	}else{

	$r_form2 = str_replace(":","",$from);
	$r_to2 = str_replace(":","",$to);


	$SQL = "
		update tbl_doctor set
		name1 = '$name1'
		,name2 = '$name2'
		,name3 = '$name3'
		,doctors = '$doctor'
		,r_from = '$from'
		,r_to = '$to'
		,r_from2 = '$r_form2'
		,r_to2 = '$r_to2'
		,st_in = '$st_in'
		,ed_in = '$ed_in'
		,t_in = '$t_in'
		,t_area = '$t_area'
		where idx = $idx
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
document.location.href="calendar_view.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>&s_t_area=<?=$t_area?>";
//-->
</SCRIPT>
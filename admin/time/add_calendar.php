<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크


	###############이번 년도 이번 달의 총 날짜수 구하기 2011-08-18 jms
	$total_day= date("t",mktime(0,0,1,$month,1,$year));

	$SQL = "
				select count(*)
				from tbl_doctor
				where
					r_year = '".$year."' and  r_month='".$month."' and doctors='".$doctor."'
			";
			$cnt += $dbcon -> getCount($SQL);


	if($cnt>0){
		alert_back(" $month 달에 데이터가 등록되있습니다.");
		exit;

	}

	for($i=1;$i<=$total_day;$i++){
	$sql = "insert into tbl_doctor (r_year,r_month,r_day,doctors,name1,name2,name3)values('".$year."','".$month."','".$i."','".$doctor."','진료','진료','')";
	$result = $dbcon -> query($sql);
	if (!$result) {
		$dbcon -> dbcon_close();
		alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
		exit;
	}
	}
	$dbcon -> dbcon_close();

	?>
	<script>
		alert("정상적으로 등록되었습니다.");
		location.href='./reserve_calendar.php?doctor=<?=$doctor?>&year=<?=$year?>&month=<?=$month?>';
	</script>
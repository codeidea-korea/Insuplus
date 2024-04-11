<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크



if ($idx){
	$SQL = "update TB_Reserve set
					pwd='".$pwd."', u_name='".$u_name."', u_tel='".$u_tel."', u_email='".$u_email."', u_id='".$u_id."', r_date='".$r_date."', r_time='".$r_time."', r_state='".$r_state."'
					, area='".$area."', doctor='".$doctor."', doctor_id='".$doctor_id."', medical_exam='".$medical_exam."', treat='".$treat."', content='".$content."', reply='".$reply."'
					where idx=".$idx."
				";
	echo $SQL."<br>";
	$result = $dbcon -> query($SQL);
	$tt = "수정";
}else{
	//예약코드 생성
	$reserve_code = time();
	$SQL = "insert into TB_Reserve set
					pwd='".$pwd."', u_name='".$u_name."', u_tel='".$u_tel."', u_email='".$u_email."', u_id='".$u_id."', r_date='".$r_date."', r_time='".$r_time."', r_state='".$r_state."'
					, area='".$area."', doctor='".$doctor."', doctor_id='".$doctor_id."', medical_exam='".$medical_exam."', treat='".$treat."', content='".$content."', reply='".$reply."'
					, regdate = now()
				";
	echo $SQL."<br>";
	$result = $dbcon -> query($SQL);
	$tt = "등록";
}
?>
<script type="text/javascript">
<!--
alert("<?=$tt?>되었습니다");
parent.window.close();
parent.opener.window.location.reload();
//-->
</script>
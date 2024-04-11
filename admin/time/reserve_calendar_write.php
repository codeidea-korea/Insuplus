<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

$sSQL = "SELECT * FROM tbl_doctor_schedule WHERE r_year  = '".$year."' AND r_month = '".$month."' And r_day= '".$day."' and doctors = '".$doctor."' and area_code='".$area_code."' order by r_day";
//echo $sSQL."<br/>";
$result = $dbcon -> query($sSQL);
$row = mysql_fetch_array($result);
?>
<link href="/_css/admin.css" rel="stylesheet" type="text/css">
<link href="/share/css/content.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<table width="250" border="0" cellspacing="0" cellpadding="0" class="mgt10" align="center">
	<form name="frm" method="post" action="submit_calendar.php">
	<input type="hidden" name="year" value="<?=$year?>">
	<input type="hidden" name="month" value="<?=$month?>">
	<input type="hidden" name="day" value="<?=$day?>">
	<input type="hidden" name="mode" value="<?=$mode?>">
	<input type="hidden" name="doctor" value="<?=$doctor?>">
	<input type="hidden" name="area_code" value="<?=$area_code?>">
	<tr>
		<td class="bg_gry"></td>
	</tr>
	<tr>
		<td  class="pdlr25 bg_gry" valign="top">의료진 스케쥴을 입력해 주세요</td>
	</tr>
	<tr>
		<td  class="pdlr25 bg_gry" valign="top">
			<div class="mgt10">
				<?=$year?>년<?=$month?>월 <?=$day?>일
			</div>
			<table cellspacing="0" cellpadding="0" width="100%" border="0">
				<tr>
					<td>
						사용자화면표시
					</td>
					<td width="80" align="center">
						예약연동시간
					</td>
					<td></td>
				</tr>
				<tr>
					<td valign="top">
						<textarea name="name1" style="width:100px;height:160px;"><?=$row[name1]?></textarea>
					</td>
					<td width="80" align="center" valign="top">
						<input type="button" onclick="AllChkList();" value="전체체크">
						<input type="checkbox" name="chk" style="display:none;">
						<?for($k=10;$k<21;$k++){?>
						<input type="checkbox" name="time<?=$k?>" value="Y" <?if ($row["time".$k]=="Y"){?>checked<?}?>><?=$k?>시<br/>
						<?}?>
					</td>
					<td><input type="submit" value="저장"></td>
				</tr>
			</table>
		</td>
	</tr>
</form>
</table>
<script type="text/javascript">
<!--
function AllChkList() {
	var f = document.frm;
	if (f.chk.checked==true){
		f.chk.checked = false;
		f.time10.checked = false;
		f.time11.checked = false;
		f.time12.checked = false;
		f.time13.checked = false;
		f.time14.checked = false;
		f.time15.checked = false;
		f.time16.checked = false;
		f.time17.checked = false;
		f.time18.checked = false;
		f.time19.checked = false;
		f.time20.checked = false;
	}else{
		f.chk.checked = true;
		f.time10.checked = true;
		f.time11.checked = true;
		f.time12.checked = true;
		f.time13.checked = true;
		f.time14.checked = true;
		f.time15.checked = true;
		f.time16.checked = true;
		f.time17.checked = true;
		f.time18.checked = true;
		f.time19.checked = true;
		f.time20.checked = true;
	}
 }
//-->
</script>
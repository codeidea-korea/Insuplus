<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	if ($ss_u_level<6){
		echo "<script>alert('팀장회원 이상만 사용이 가능합니다.');history.back();</script>";
		exit;
	};

	if ($doctor==""){
		$doctor = "doctor";
	}

?>
<?
function getTotalDays($month,$year)
{
   $date = date( "t", mktime( 0, 0, 1, $month, 1, $year));
   return $date;
}

function selMode($txt){
	if ($txt){
	$txt = $txt."<br>";
	}
	return $txt;
}

function showCalendar( $year, $month, $totalDays, $doctor)
{
	$date1 = $year."-".$month."-01";
	$date2 = $year."-".$month."-31";

	// 예약 정보 구해 놓기
	include_once $_SERVER["DOCUMENT_ROOT"]."/admin/intranet/config.php";

	$sSQL = "SELECT * FROM tbl_doctor WHERE r_year  = $year AND r_month =$month  order by r_day";
	$result = mysql_query( $sSQL, $con) or die("DB Error : $sSQL");
	$i = 0;
	while( $row = mysql_fetch_array( $result)) {
		foreach( $row as $key => $value) {
			$arList[$i][$key] = $value;
		}
		$arList[$i]["ResDay"] = substr( $arList[$i]["ResDay"], 0, 10);
		$temp = split("-", $arList[$i]["ResDay"]);
		$arList[$i]["DAY"] = $temp[2];
		$i++;
	}
	// 예약 정보 구해 놓기 끝

  $firstDay = date('w', mktime(0, 0, 0, $month, 1, $year));
  $htmlCode =  "";
  $htmlCode .= "<TR height='69'>\n";

  $col = 0;

	// 날짜없는 앞에 빈칸 채우기
	for($i = 0; $i < $firstDay; $i++) {
		if($i != 0) {
			$htmlCode .= "   <TD BGCOLOR=\"#FFFFFF\"  >&nbsp;</TD>\n";
		} else {
			$htmlCode .= "<TD BGCOLOR=\"#FFFFFF\"><table width=\"100%\" height='100%' border=\"0\" cellspacing=\"2\" cellpadding=\"2\"  >"	//bgcolor=\"#F7F3D9\"
			. " <tr><td height=\"33\" colspan=\"2\" >&nbsp;</td></tr>"
			. " <tr><td width=\"33%\"><div align=\"left\">&nbsp;</td>"
			." <td width=\"67%\">&nbsp;</td></tr></table></TD>";
		}
		$col++;
	}

	for($j = 1; $j <= $totalDays; $j++) {
		$j_varW = date('w', mktime(0, 0, 0, $month, $j, $year));  // 요일
		if($j_varW == 0){
			$j_var = "<font class='tx_red11'>$j</font>";
		}else if($j_varW == 6){
			$j_var = "<font class='tx_blue11'>$j</font>";
		}else{
			$j_var = $j;
		}

		// 날짜 찍기
		$htmlCode .= "<TD BGCOLOR='#FFFFFF'>";
		if($j_varW != 0){
			$htmlCode .="<table width='100%' height='100%' border=0 cellspacing=2 cellpadding=2>"
					      . "<tr align='left'><td height=20 colspan=2 valign=top>";
		} else {
			$htmlCode .="<table width='100%' height='100%' border=0 cellspacing=2 cellpadding=2  >"	 //bgcolor='#F7F3D9'
					      . "<tr align='left'><td height=20 colspan=2 valign=top>";
		}
		$htmlCode .= $j_var;
 		$htmlCode .="</td></tr>";

		// 내용 찍기
		$DayInfo = ""; // 예정정보 뿌릴꺼
		for( $i = 0; $i < sizeof($arList); $i++) {

			if((int)$arList[$i]["r_day"] == $j) {
				$idx	    = $arList[$i]["idx"];
				$name1 = "<a href='javascript: OnView($year,$month,$j,$idx)'>".$arList[$i]["name1"]."</a>";
//				$name2 = $arList[$i]["name2"];
//				$name3 = $arList[$i]["name3"];
                $DayInfo .= selMode($name1);
                $DayInfo .= selMode($name2);
                $DayInfo .= selMode($name3);
			}
		}

		if ($DayInfo==""){
				$DayInfo = "<a href= 'javascript: OnWrite($year,$month,$j,\"write\",\"\",\"\",\"\", \"$doctor\",\"\")'>[등록]</a>";
		}

		if( $DayInfo != ""){
			$htmlCode .=	"<tr><td colspan=2 align='center'>$DayInfo</td></tr>";
		}

		$htmlCode .=	"</table></TD>";
		$col++;

		// 7칸이면 칸 띄우기
		if($col == 7) {
         $htmlCode .= "</TR>\n";
         if($j != $totalDays) {
            $htmlCode .= "<TR height='69'>\n";
         }
         $col = 0;
      }
   }

   // 마지막 남은 빈칸 채우기
	 while($col > 0 && $col < 7) {
      $htmlCode .= "   <TD BGCOLOR=\"#FFFFFF\" valign=\"top\" >&nbsp;</TD>\n";
      $col++;
   }
   $htmlCode .= "</TR>\n";


   return $htmlCode;
}
?>
<script language=javascript>
function nextCal(year, month)
{
	if(month == 12){
		year=  year-1+2;
		month = 1;
	}else{
		month = month-1+2;
	}

	location.href='calendar.php?doctor=<?=$doctor?>&year='+year+'&month='+month;
}

function preCal(year, month)
{
	if(month == 1) {
		year= year-1;
		month = 12;
	} else {
		month= month-1;
	}

	location.href='calendar.php?doctor=<?=$doctor?>&year='+year+'&month='+month;
}
</script>

<?
	if($year == "") $year = date("Y");
	if($month == "") $month = date("m");
	$totalDays = getTotalDays($month,$year);
?>



<table width="628" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr><td height="10" bgcolor="#FFFFFF"></td></tr>
	<tr>
		<td width="228" align="center" bgcolor="#FFFFFF">
			<table width="100%" border="0" cellspacing="1" cellpadding="3">
				<tr>
					<td width="28" height="24"><div align="center"><img src="images/prev.gif" height="22" border="0" style="cursor:hand" OnClick="preCal('<?=$year?>','<?=$month?>');"></div></td>
					<td width="113"><div align="center"><img src="images/<?=$month."m.gif"?>" ></div></td>
					<td width="361"><div align="center"><img src="images/next.gif"  height="22" border="0" style="cursor:hand" OnClick="nextCal('<?=$year?>','<?=$month?>');"></div></td>
				</tr>
			</table>
		</td>
		<td width="400" align="right" bgcolor="#FFFFFF">
		</td>
	</tr>
</table>


<table  width="674" border="0" cellspacing="1" cellpadding="0" bgcolor="#fcdccf" class="mgt10 tx_bod11 c">
					  <tr>
						<td colspan="7" class="tline_bg" height="6"></td>
					  </tr>
					  <tr class="h25" align="center">
						<td class="t_ttbg tx_red11 b c">일</td>
						<td class="t_ttbg tx_org11 b c">월</td>
						<td class="t_ttbg tx_org11 b c">화</td>
						<td class="t_ttbg tx_org11 b c">수</td>
						<td class="t_ttbg tx_org11 b c">목</td>
						<td class="t_ttbg tx_org11 b c">금</td>
						<td class="t_ttbg tx_blue11 b c">토</td>
					  </tr>
	<? print(showCalendar( $year, $month, $totalDays, $doctor)); ?>
</table>


<form name="frm_win" method="post">
<input type="hidden" name="year">
<input type="hidden" name="month">
<input type="hidden" name="day">
<input type="hidden" name="mode">
<input type="hidden" name="name1">
<input type="hidden" name="name2">
<input type="hidden" name="name3">
<input type="hidden" name="doctor">
</form>

<SCRIPT LANGUAGE="JavaScript">
<!--
function OnWrite(year,month,day,mode,name1,name2,name3,doctor,idx){
	if(name1==""){name1="";}
	if(name2==""){name2="";}
	if(name3==""){name3="";}

	document.frm_win.year.value=year;
	document.frm_win.month.value=month;
	document.frm_win.day.value=day;
	document.frm_win.mode.value=mode;
	document.frm_win.name1.value=name1;
	document.frm_win.name2.value=name2;
	document.frm_win.name3.value=name3;
	document.frm_win.doctor.value=doctor;

	document.frm_win.action = 'calendar_write.php?idx='+idx;
//	document.frm_win.target="_new";
	document.frm_win.submit();

}

function OnView(year,month,day,idx){
	document.location.href="calendar_view.php?idx"+idx+"&year="+year+"&month="+month+"&day="+day;
}
//-->
</SCRIPT>
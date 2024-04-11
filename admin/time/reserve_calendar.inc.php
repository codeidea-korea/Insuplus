<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크


// 아무작업이 없는 경우 이유득 원장님선택
if ($doctor==""){
	$doctor = "brian";
}
// 지점코드가 없는 경우 강남점
if ($area_code==""){
	$area_code = "01";
}

function getTotalDays($month,$year)
{
   $date = date( "t", mktime( 0, 0, 1, $month, 1, $year));
   return $date;
}

function selMode($txt){
	switch($txt){
		case "진료":
			$txt = "<font class='tx_blue11'>진료</font><br>";
			break;
		case "휴진":
			$txt = "<font class='tx_red11'>휴진</font><br>";
			break;
		case "수술":
			$txt = "<font class='tx_blue11'>수술</font><br>";
			break;
		case "당직":
			$txt = "<font class='tx_red11'>당직</font><br>";
			break;
		case "야간진료":
			$txt = "<font class='tx_red11'>야간진료</font><br>";
			break;
		case "없음":
			$txt = "<br>";
			break;
		case "":
			$txt = "<br>";
			break;
	}
	return $txt;
}

function showCalendar( $year, $month, $totalDays, $doctor,$area_code)
{
	$date1 = $year."-".$month."-01";
	$date2 = $year."-".$month."-31";

	// 예약 정보 구해 놓기
	include_once $_SERVER["DOCUMENT_ROOT"]."/admin/time/config.php";

	$sSQL = "SELECT * FROM tbl_doctor_schedule WHERE r_year  = '".$year."' AND r_month = '".$month."' And doctors = '".$doctor."' and area_code='".$area_code."' order by r_day";
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
				$name1 = $arList[$i]["name1"];
				$name2 = $arList[$i]["name2"];
				$name3 = $arList[$i]["name3"];
				$idx	= $arList[$i]["idx"];

                $DayInfo .= nl2br($name1)."<br/>";

				$DayInfo .= "<a href='javascript: OnWrite($year,$month,$j,\"modify\",\"$name1\",\"$name2_temp\",\"$name3_temp\", \"$doctor\", \"$area_code\")'>[수정]</a>";
				$DayInfo .= "<a href='javascript: OnDelete(\"$idx\")'>[삭제]</a><br>";
			}
		}

		if ($DayInfo==""){
				$DayInfo = "<a href= 'javascript: OnWrite($year,$month,$j,\"write\",\"\",\"\",\"\", \"$doctor\", \"$area_code\")'>[등록]</a>";
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

	location.href='reserve_calendar.php?doctor=<?=$doctor?>&area_code=<?=$area_code?>&year='+year+'&month='+month;
}

function preCal(year, month)
{
	if(month == 1) {
		year= year-1;
		month = 12;
	} else {
		month= month-1;
	}

	location.href='reserve_calendar.php?doctor=<?=$doctor?>&area_code=<?=$area_code?>&year='+year+'&month='+month;
}
</script>

<?
	if($year == "") $year = date("Y");
	if($month == "") $month = date("m");
	$totalDays = getTotalDays($month,$year);


	// 원장검색
    $SQL = "
    select
    *
    from
    tbl_desc_area A
    where
    A.area_code = '".$area_code."'
    limit 0, 1
    ";
    $result = $dbcon -> query($SQL);
    $rows = $dbcon -> fetch_array($result);
    extract($rows);
    unset($rows);

	$arr_doctor = explode("||",$doctors);
?>



<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
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
		<td width="" align="right" bgcolor="#FFFFFF">
					<a href="./reserve_calendar.php?doctor=<?=$doctor?>&year=<?=date("Y")?>&month=<?=date("m")?>">[이번달 진료로 변경]</a>
					지점 :
					<select name="area_code" onchange="document.location.href='?area_code='+this.value+'&doctor=<?=$doctor?>';">
					<?foreach ($Arr_h_area2 as $key => $val) {?>
					<option value="<?=$key?>" <? if ($key == $area_code) echo "selected";?>><?=$val?></option>
					<?}?>
					</select>
					원장 :
					<select name="doctor" onchange="chg_doctor(this.value,'<?=$area_code?>')">
					<option value="">::: 선택하세요 :::</option>
					<?for ($J=0;$J<sizeof($arr_doctor);$J++) {
						$arr_doctor_val = explode("@",$arr_doctor[$J]);
						$arr_doctor_id		= $arr_doctor_val[0];
						$arr_doctor_name	 = $arr_doctor_val[1];
					?>
					<option value="<?=$arr_doctor_id?>" <? if ($arr_doctor_id == $doctor) echo "selected";?>><?=$arr_doctor_name?></option>
					<?}?>
					</select>
		</td>
	</tr>
</table>


<table  width="800" border="0" cellspacing="1" cellpadding="0" bgcolor="#fcdccf" class="mgt10 tx_bod11 c">
					  <tr>
						<td colspan="7" class="tline_bg" height="6"></td>
					  </tr>
					  <tr class="h25">
						<td class="t_ttbg tx_red11 b c">일</td>
						<td class="t_ttbg tx_org11 b c">월</td>
						<td class="t_ttbg tx_org11 b c">화</td>
						<td class="t_ttbg tx_org11 b c">수</td>
						<td class="t_ttbg tx_org11 b c">목</td>
						<td class="t_ttbg tx_org11 b c">금</td>
						<td class="t_ttbg tx_blue11 b c">토</td>
					  </tr>
	<? print(showCalendar( $year, $month, $totalDays, $doctor,$area_code)); ?>
</table>

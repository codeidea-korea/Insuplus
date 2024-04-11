<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
if ($ss_u_level<6){
    echo "<script>alert('팀장회원 이상만 사용이 가능합니다.');history.back();</script>";
    exit;
};
include_once $_SERVER["DOCUMENT_ROOT"]."/admin/surgery/config.php";

function call_doc($doctor, $idx){

    switch($doctor){
        case "김경길":
        $bg_txt = "bgcolor='yellow'><a href='calendar_write.php?idx=".$idx."'><font color='000000'>";
        break;
        case "박준성":
        $bg_txt = "bgcolor='0033bb'><a href='calendar_write.php?idx=".$idx."'><font color='FFFFFF'>";
        break;
        case "정도현":
        $bg_txt = "bgcolor='#00FFFF'><a href='calendar_write.php?idx=".$idx."'><font color='000000'>";
        break;
        case "최정기":
        $bg_txt = "bgcolor='red'><a href='calendar_write.php?idx=".$idx."'><font color='FFFFFF'>";
        break;
        case "이호원":
        $bg_txt = "bgcolor='green'><a href='calendar_write.php?idx=".$idx."'><font color='FFFFFF'>";
        break;
        case "신형호":
        $bg_txt = "bgcolor='#FF00DD'><a href='calendar_write.php?idx=".$idx."'><font color='FFFFFF'>";
        break;
        case "조윤정":
        $bg_txt = "bgcolor='#5F00FF'><a href='calendar_write.php?idx=".$idx."'><font color='FFFFFF'>";
        break;
        case "서정훈":
        $bg_txt = "bgcolor='#FFBB00'><a href='calendar_write.php?idx=".$idx."'><font color='000000'>";
        break;
        case "염동주":
        $bg_txt = "bgcolor='#BCE55C'><a href='calendar_write.php?idx=".$idx."'><font color='000000'>";
        break;
    }
    return $bg_txt;
}

function Call_cal($sect, $year, $month, $day, $r_time, $t_area){
    global $con;

    $r_from2 = str_replace(":","",$r_from2);
    $r_to2 = str_replace(":","",$to2);

    $SQL = "
    select
    *
    from
    tbl_doctor
    where
    1=1
    and r_year = '".$year."'
    and r_month = '".$month."'
    and r_day = '".$day."'
    and name1 = '".$sect."'
    and t_area = '".$t_area."'
    and  ".$r_time." between r_from2 and r_to2
    order by r_from asc
    ";
    //		echo $SQL."<BR>";
    $result = mysql_query( $SQL, $con) or die("DB Error : $sSQL");

    $i = 0;
    $arList = "";
    while( $row = mysql_fetch_array( $result)) {
        foreach( $row as $key => $value) {
            $arList[$i][$key] = $value;
        }
        $i++;
    }

    if (is_array($arList)){
        for( $i = 0; $i < sizeof($arList); $i++) {
            $idx	    = $arList[$i]["idx"];
            $year		= $arList[$i]["r_year"];
            $month	= $arList[$i]["r_month"];
            $day		= $arList[$i]["r_day"];
            $doctor	= $arList[$i]["doctors"];
            $name1	= $arList[$i]["name1"];
            $name2	= $arList[$i]["name2"];
            $name3	= $arList[$i]["name3"];
            $from		= $arList[$i]["r_from"];
            $to		= $arList[$i]["r_to"];

            echo "<td align='center' ".call_doc($doctor, $idx).$name2."</a></td>";

            $popnum++;
        }
    }
    else{
        echo "<td>&nbsp;</td>";
    }
}
?>
<link href="/_css/admin.css" rel="stylesheet" type="text/css">
<link href="/share/css/content.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크


$tm = "intra";
$lm = "";
include $path_admin."inc/header.php";

// 지점선택 없을땐 일단 천안역점으로
if ($s_t_area==""){
	$s_t_area = "1";
}
?>


<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">수술 스케쥴</td>
	</tr>
	<tr>
		<td colspan="2" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<table width="800">
	<tr align="center">
		<td bgcolor="yellow" ><font color='000000'>김경길 원장님</td>
		<td bgcolor="0033bb" ><font color='FFFFFF'>박준성 원장님</td>
		<td bgcolor="00FFFF" ><font color='000000'>정도현 원장님</td>
		<td bgcolor="red" ><font color='FFFFFF'>최정기 원장님</td>
		<td bgcolor="green" ><font color='ffffff'>이호원 원장님</td>
		<td bgcolor="FF00DD" ><font color='FFFFFF'>신형호 원장님</td>
		<td bgcolor="5F00FF" ><font color='FFFFFF'>조윤정 원장님</td>
		<td bgcolor="FFBB00" ><font color='000000'>서정훈 원장님</td>
		<td bgcolor="BCE55C" ><font color='000000'>염동주 원장님</td>
	</tr>
	<tr>
		<td colspan="9" style="text-align:right;">
			<select name="s_t_area" style="height: 35px;" onchange="chg_area(this.value);">
			<option value="1" <?if ($s_t_area=="1"){echo "selected";} ?>>천안역점</option>
			<option value="2" <?if ($s_t_area=="2"){echo "selected";} ?>>쌍용역점</option>
			<option value="3" <?if ($s_t_area=="3"){echo "selected";} ?>>온양역점</option>
			</select>
			<input type="button" value="리스트로" onclick="document.location.href='calendar.php?doctor=doctor&year=<?=$year?>&month=<?=$month?>';">
			<input type="button" value="추가등록" onclick="OnWrite('<?=$year?>','<?=$month?>','<?=$day?>',write,'','','','','')">
			<input type="button" value="엑셀받기" onclick="document.location.href='calendar_excel.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>&idx=<?=$idx?>&s_t_area=<?=$s_t_area?>'">
		</td>
	</tr>
</table>
<table width="800">
	<tr>
		<td>백내장 : 15 분</td>
		<td>라섹 : 15 분</td>
		<td>ICL : 40 분</td>
		<td>안검성형술 : 70 분</td>
		<td>호츠수술 : 60 분</td>
		<td>익상편 : 15 분</td>
		<td>AVASTIN : 5 분</td>
	</tr>
</table>
<table width="800" bgcolor="#000000" cellspacing="1" cellpadding="0" border="0">
	<tr bgcolor="FFFFFF" align="center">
		<td width="50">시간</td>
		<td width="100">백내장</td>
		<td width="100">MEL80라섹</td>
		<td width="100">EX500라섹</td>
		<td width="100">ICL</td>
		<td width="100">안검성형술</td>
		<!--<td width="100">호츠수술</td>-->
		<td width="100">익상편</td>
		<td width="100">AVASTIN</td>
	</tr>
	<?for
	($j=9;$j<20;$j++){
	?>
	<?for
	    ($jj=0;$jj<=45;$jj=$jj+15){
	?>
	<?
	        if( strlen($jj)==1){
	            $jj_txt = "0".$jj;
	        }
	        else{
	            $jj_txt = $jj;
	        }
	?>
	<tr bgcolor="FFFFFF" align="center">
		<td><?=$j?>:<?=$jj_txt?></td>
		<!--백내장-->
		<?Call_cal('백내장',$year, $month, $day, $j.$jj_txt ,$s_t_area)?>
		<!--라섹-->
		<?Call_cal('MEL80라섹',$year, $month, $day, $j.$jj_txt ,$s_t_area)?>
		<?Call_cal('EX500라섹',$year, $month, $day, $j.$jj_txt ,$s_t_area)?>
		<!--ICL-->
		<?Call_cal('ICL',$year, $month, $day, $j.$jj_txt ,$s_t_area)?>
		<!--안검성형술-->
		<?Call_cal('안검성형술',$year, $month, $day, $j.$jj_txt ,$s_t_area)?>
		<!--호츠수술-->
		<?//Call_cal('호츠수술',$year, $month, $day, $j.$jj_txt ,$s_t_area)?>
		<!--익상편-->
		<?Call_cal('익상편',$year, $month, $day, $j.$jj_txt ,$s_t_area)?>
		<!--AVASTIN-->
		<?Call_cal('AVASTIN',$year, $month, $day, $j.$jj_txt ,$s_t_area)?>
	</tr>
	<?}?>
	        <?}
	?>
	<tr bgcolor="FFFFFF" align="center">
		<td>20:00</td>
		<!--백내장-->
		<?Call_cal('백내장',$year, $month, $day, 2000 ,$s_t_area)
		?>
		<!--라섹-->
		<?Call_cal('MEL80라섹',$year, $month, $day, 2000 ,$s_t_area)?>
		<?Call_cal('EX500라섹',$year, $month, $day, 2000 ,$s_t_area)?>
		<!--ICL-->
		<?Call_cal('ICL',$year, $month, $day, 2000 ,$s_t_area)?>
		<!--안검성형술-->
		<?Call_cal('안검성형술',$year, $month, $day, 2000 ,$s_t_area)?>
		<!--호츠수술-->
		<?//Call_cal('호츠수술',$year, $month, $day, 2000 ,$s_t_area)?>
		<!--익상편-->
		<?Call_cal('익상편',$year, $month, $day, 2000 ,$s_t_area)?>
		<!--AVASTIN-->
		<?Call_cal('AVASTIN',$year, $month, $day, 2000 ,$s_t_area)?>
	</tr>
</table>


<?
	    include $path_admin."inc/footer.php";
?>
<form name="frm_win" method="post">
<input type="hidden" name="year">
<input type="hidden" name="month">
<input type="hidden" name="day">
<input type="hidden" name="mode">
<input type="hidden" name="name1">
<input type="hidden" name="name2">
<input type="hidden" name="name3">
<input type="hidden" name="doctor">
<input type="hidden" name="t_area">
</form>

<?
	    $dbcon -> dbcon_close();
?>
<SCRIPT LANGUAGE="JavaScript">
<!--
function OnWrite(year,month,day,mode,name1,name2,name3,doctor,idx){
    if(name1==""){
        name1="";
    }
    if(name2==""){
        name2="";
    }
    if(name3==""){
        name3="";
    }

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

function chg_area(area){
	document.location.href="calendar_view.php?year=<?=$year?>&month=<?=$month?>&day=<?=$day?>&s_t_area="+area;
}
//-->
</SCRIPT>

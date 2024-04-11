<?php

// 검색설정
$query_where		= "";

// 검색조건
$search_name = REQSTR($_GET['sname'], "");

if ( strlen($search_name) > 0 ) $query_where .= " and A.nick_name like '%".$search_name."%' ";
$parameter = "&sname=".$search_name;


if ( strlen($search_category) > 0 && $search_category != "all" ) $query_where .= " and A.category = '".$search_category."' ";
$parameter = "&search_category=".$search_category;

if($_GET['year']){
	$schYear = $_GET['year'];
}

if($_GET['month']){
	$schMonth = $_GET['month'];
}

// schYear, schMonth 를 받아오고 없음 오늘날짜로.
if(empty($schYear)) $schYear = date("Y");
if(empty($schMonth)) $schMonth = date("m");

// 이전달 다음달 설정
if($schMonth=="1"){
	$prevYear = $schYear - 1;
	$prevMonth = "12";
	$nextYear = $schYear;
	$nextMonth = $schMonth + 1;
}else if($schMonth=="12"){
	$prevYear = $schYear;
	$prevMonth = $schMonth - 1;
	$nextYear = $schYear + 1;
	$nextMonth = "1";
}else{
	$prevYear = $schYear;
	$prevMonth = $schMonth - 1;
	$nextYear = $schYear;
	$nextMonth = $schMonth + 1;
}


// 해당년월의 처음 요일을 구한다.
$firstTime = strtotime($schYear . "-" . $schMonth . "-01");
$firstWeek = date("w", $firstTime);

// 해당년월의 마지막 날을 구한다
for ($ld = 28; checkdate($schMonth,$ld,$schYear); $ld ++);
$lastDay = $ld - 1;

// 해당년월의 마지막 요일을 구한다.
$lastTime = strtotime($schYear . "-" . $schMonth . "-" . $lastDay);
$lastWeek = date("w", $lastTime);

// 배열에 날짜 데이터를 먼저 우겨넣는다.
$arrSCH = array(array(),array(),array(),array(),array(),array(),array());

for($i = 0; $i < $firstWeek; $i ++) { // 해당월의 처음 요일이 되기 전 공백 추가.
    $arrSCH[$i][] = "";
}

$maxRow = 0;
for($schDay = 1; $schDay <= $lastDay; $schDay ++) {
    $checkday = mktime(0, 0, 0, $schMonth, $schDay, $schYear);
    $week = date("w", $checkday);
    $arrSCH[$week][] = $schDay;

    if(count($arrSCH[$week]) > $maxRow) $maxRow = count($arrSCH[$week]);
}



// 검색조건
$SQLcalendar = "
			select
				A.seq, A.category, A.subject, A.nick_name, A.ext9, date_format(A.ext10 , '%e') as 'regdate_d', B.cate_name
			from
				intra_board_schedule A left outer join intra_category B on A.category = B.idx
			where
				DATE_FORMAT(A.ext10 ,'%Y-%c') = '".$schYear."-".$schMonth."' ". $query_where . "
			order by
				regdate_d , ext9 ";
//echo $SQLcalendar;

$RScalendar = $dbcon -> query($SQLcalendar);


$arrSeq = array(array());
$arrCategory = array(array());
$arrSubject = array(array());
$arrNickname = array(array());
$arrTime = array(array());
$arrDate = array(array());
$arrCateName = array(array());

$cnt = 0;
$dataDate_temp = 0;

while($rowCalendar = $dbcon -> fetch_array($RScalendar)){

	$dataDate = $rowCalendar['regdate_d'];

	if($dataDate == $dataDate_temp){
		$cnt ++;
	}else{
		$dataDate_temp = $dataDate;
		$cnt = 0;
	}
	$arrSeq[$dataDate][$cnt] = $rowCalendar['seq'];
	$arrCategory[$dataDate][$cnt] = $rowCalendar['category'];
	$arrSubject[$dataDate][$cnt] = $rowCalendar['subject'];
	$arrNickname[$dataDate][$cnt] = $rowCalendar['nick_name'];
	$arrTime[$dataDate][$cnt] = $rowCalendar['ext9'];
	$arrDate[$dataDate][$cnt] = $rowCalendar['regdate_d'];
	$arrCateName[$dataDate][$cnt] = $rowCalendar['cate_name'];

}
?>
						<form name="frmSearch" method="get" action="">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td height="20" class="st_bg"><span class="st">검색</span></td>
							</tr>
						</table>
						<table width="100%" border="0" cellpadding="5" cellspacing="3" class="search_bg01">
							<tr>
								<td height="20" class="search_bg02">
									<table border="0" cellpadding="0" cellspacing="0">
										<tr>
											<? if ($bc_category_use == "Y") { ?>
											<!-- 카테고리 검색 Start -->
											<td class="f11px_FFFFFF">ㆍ카테고리&nbsp;</td>
											<td class="f11px_FFFFFF">
												<select name="search_category" class="search_select">
													<option value="">전체</option>
													<?
														while ($CateListRs = $dbcon -> fetch_array($ArrCateListRs[1]) ) {
															extract($CateListRs);
													?>
													<option value="<?=$idx?>"><?=$cate_name?></option>
													<?
														}
													?>
												</select>
											</td>
											<td width="4"></td>
											<? } ?>
											<td class="f11px_FFFFFF">ㆍ이름&nbsp;</td>
											<td width="4"></td>
											<td class="f11px_FFFFFF"><input type="text" name="sname" class="search_input"></td>
											<td width="4"></td>
											<td class="f11px_FFFFFF">ㆍ년월검색&nbsp;</td>
											<td class="f11px_FFFFFF"><? getSelectBoxArray($Arr_year,'year','0',':::년도선택:::','search_select'); ?></td>
											<td width="4"></td>
											<td class="f11px_FFFFFF"><? getSelectBoxArray($Arr_month,'month','0',':::월선택:::','search_select'); ?></td>
											<td class="f11px_FFFFFF">&nbsp;<input type="submit" value="  검색  " class="search_btn"></td>
											<td width="4"></td>
											<td width="120" height="100%"><input type="button" value="  검색초기화  " class="search_btn" style="width:100%;height:100%" onClick="resetSearch();" style="cursor:hand"></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						</form>
						<script language="JavaScript">
							setSearchCalendar('<?=$search_category?>', '<?=$sname?>', '<?=$year?>', '<?=$month?>');
						</script>

						<span style="height:20px;"><spacer type="block" width="1" height="20"></span>

						<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="#bb650d">
							<tr>
								<td height="32" bgcolor="#bb650d" align="center">
									<table border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td width="100" class="f12px_FFFFFF"><a href="?mode=<?=$mode?>&year=<?=$prevYear?>&month=<?=$prevMonth?><?=$parameter?>">◀◀ 이전달</a></td>
											<td align="center" class="dayTitle"><?=$schYear?>년 <?=$schMonth?>월</td>
											<td width="100" align="right" class="f12px_FFFFFF"><a href="?mode=<?=$mode?>&year=<?=$nextYear?>&month=<?=$nextMonth?><?=$parameter?>">다음달 ▶▶</a></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						<table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="#E5E5E5">
						<col width="14%"><col width="14%"><col width="14%"><col width="14%"><col width="14%"><col width="14%"><col width="14%">
						<?
						for($row = 0; $row < $maxRow; $row ++) {
							echo "<tr>";
							for($col = 0; $col < 7; $col ++) {
								$day = $arrSCH[$col][$row];
								if(!$day) $day = "&nbsp;";
								if($col==6){
									$classDay = "f11px_0000FF";
								}else if($col=="0"){
									$classDay = "f11px_FF0000";
								}else{
									$classDay = "f11px";
								}
								echo "<td style=\"padding: 10px 10px 10px 10px\" valign=\"top\" height=\"150\" class=\"f11px\">";
								echo "<div class=\"".$classDay."\"><a href=\"?mode=write&search_category=".$search_category."&ext10=".$schYear."-".$schMonth."-".$day."\"><b>".$day."</b></a></div>";
								for($i=0;$i<count($arrSeq[$day]);$i++){
									if($arrCateName[$day][$i]=="프로젝트"){
										$titleColor="_0000FF";
									}else if($arrCateName[$day][$i]=="영업일정"){
										$titleColor="_FF0000";
									}else{
										$titleColor="";
									}
									echo "<span class=\"f11px".$titleColor."\">[".$arrCateName[$day][$i]."]</span> - 작성자 : ".$arrNickname[$day][$i]."<br>";
									echo "<a href=\"?mode=view&seq=".$arrSeq[$day][$i]."&search_category=".$arrCategory[$day][$i]."\"><b>".$arrTime[$day][$i]."</b> ".$arrSubject[$day][$i]."</a><br><br>";
									//echo $arrDate[$day][$i];
								}
								echo "</td>";
							}
							echo "</tr>";
						}
						?>
						</table>

						<table border="0" cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td align="right" valign="top" style="padding:11 0 0 0">
									<? if ($auth_write) { ?>
										<a href="javascript:write_go();"><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_write.gif"></a>
									<? } ?>
								</td>
							</tr>
						</table>
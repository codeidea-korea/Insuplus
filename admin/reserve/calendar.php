<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
$tm = "reserve";
$lm = "";
include $path_admin."inc/header.php";

// 지점코드
if (!$_GET["sarea_code"]){
	$_GET["sarea_code"]="01";
}

if(!$year_cal)
{
    $year_cal = date("Y");
}
if(!$month_cal)
{
    $month_cal = date("m");
}
if(!$day_cal)
{
    $day_cal = date("j");
}
$next_year = date("Y",strtotime($year_cal."-".$month_cal."-".$day_cal."+1 year"));
$prev_year = date("Y",strtotime($year_cal."-".$month_cal."-".$day_cal."-1 year"));

$next_month = date("m",strtotime($year_cal."-".$month_cal."-".$day_cal."+1 month"));
$prev_month = date("m",strtotime($year_cal."-".$month_cal."-".$day_cal."-1 month"));

//다음,이전달에 대한 처리
if ($prev_month=="12"){
	$prev1 = $prev_year;
	$prev2 = $prev_month;
	$prev3 = "01";
}else{
	$prev1 = $year_cal;
	$prev2 = $prev_month;
	$prev3 = "01";
}

if ($next_month=="01"){
	$next1 = $next_year;
	$next2 = $next_month;
	$next3 = "01";
}else{
	$next1 = $year_cal;
	$next2 = $next_month;
	$next3 = "01";
}


$time = strtotime($year_cal.'-'.$month_cal.'-01');
list($tday, $sweek) = explode('-', date('t-w', $time));  // 총 일수, 시작요일
$tweek = ceil(($tday + $sweek) / 7);  // 총 주차
$lweek = date('w', strtotime($year_cal.'-'.$month_cal.'-'.$tday));  // 마지막요일

include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.days.php";
?>

<script>
//설명레이어 위치 및 표시 함수
	function lyrposit(){
  	msgview.style.posLeft = event.x - 0 + document.body.scrollLeft;
  	msgview.style.posTop = event.y + 20 + document.body.scrollTop;
//		$("#msgview").css("left",event.x+10+$(window).scrollLeft());
//		$("#msgview").css("top",event.y+15+$(window).scrollTop());
	}

	function lyrset(str){
  	msg='<table cellspacing=1 cellpadding=2 border=0 bgcolor=000000><tr><td bgcolor=FFFFE1><font size=2>';
  	msg+=str;
    msg+='</font></td></tr></table>';
    msgview.innerHTML=msg;
	}

	function lyrhide(){
  	msgview.innerHTML='';
	}
</script>

<body topmargin=0>

<!-- 설명레이어 -->
<div id="msgview" style="position:absolute; z-index:1;"></div>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">예약 문의 (예약완료)</td>
	</tr>
	<tr>
		<td colspan="2" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<table cellpadding=0 cellspacing=0 width="95%" align=center border=0 height="100%">
	<tr>
		<td>
			<!--제목-->
			<table cellpadding=5 cellspacing=0 width="1280" align=center height="100%" border="0">
				<tr>
					<td width="120">
						<select name="sarea_code" onchange="document.location.href='<?=$PHP_SELF?>?sarea_code='+this.value;">
							<option value="">:: 선택 ::</option>
						<? foreach ($Arr_h_area as $key => $val) { ?>
							<option value="<?=$key?>" <? if ( "".$key == $_GET["sarea_code"]) echo "selected";?> ><?=$val?></option>
						<? } ?>
						</select>
					</td>
					<td align="right">
						<a href="?year_cal=<?=$prev1?>&month_cal=<?=$prev2?>&day_cal=<?=$prev3?>&sarea_code=<?=$_GET["sarea_code"]?>">◀</a>
					</td>
					<td align="center" width="150"><font style="text-decoration:none;font-size:12px;font-weight:bold"><?=$year_cal?>년 <?=$month_cal?>월</font></td>
					<td align="left"><a href="?year_cal=<?=$next1?>&month_cal=<?=$next2?>&day_cal=<?=$next3?>&sarea_code=<?=$_GET["sarea_code"]?>">▶</a></td>

				</tr>
			</table>
		</td>
	</tr>
	<tr height=2><td></td></tr>
	<tr>
		<td>
			<!--내용-->
			<table width='1280' cellpadding='3' cellspacing='1' border='0' align=center height="100%">
				<tr>
					<th align=center bgcolor="#7abae4" width="180">일</th>
					<th align=center bgcolor="#7abae4" width="180">월</th>
					<th align=center bgcolor="#7abae4" width="180">화</th>
					<th align=center bgcolor="#7abae4" width="180">수</th>
					<th align=center bgcolor="#7abae4" width="180">목</th>
					<th align=center bgcolor="#7abae4" width="180">금</th>
					<th align=center bgcolor="#7abae4" width="180">토</th>
				</tr>

				<?
				for ($n=1,$i=0; $i<$tweek; $i++) {
				?>
				<tr>


					<?
				    for ($k=0; $k<7; $k++) {
					?>

					<td align="left" valign="top" style="border:solid 1px #7abae4" height="125">
						<?
				        if ($k =="0") //일요일
				        {
				            $color="red";
				        }
				        elseif ($k =="6") //토요일
				        {
				            $color="blue";
				        }
				        else // 평일
				        {
				            $color="black";
				        }
						?>
						<?
				        if (!(($i == 0 && $k < $sweek) || ($i == $tweek-1 && $k > $lweek))) {
						?>
						<?
				            if ($n==date("d")) //오늘
				            {
				                $color="orange";
				            }
						?>
						<?
				            if($year_cal==$year_cal && $holiday_array[(int)$month_cal][$n]) $color=red;
							//날짜가 1단위인경우
							if (strlen($n)==1){
								$nn = "0".$n;
							}else{
								$nn = $n;
							}
//							$now_date =  $year_cal."-".$month_cal."-".$nn;
							$now_date =  $year_cal.$month_cal.$nn;

						?>
						<font color="<?=$color?>"><?=$n?> <?=$holiday_array[(int)$month_cal][$n]?> [<a href="javascript: reserve_room('<?=$now_date?>')">예약</a>]</font>
						<?
							// 하루하루 예약검색
							$cnt = 0;
							$SQL = "select a.* from TB_Reserve a where a.area='".$_GET["sarea_code"]."' and a.r_date = '".$now_date."' and r_state='예약완료' order by a.r_time asc";
//							echo $SQL;
							$rs = $dbcon -> query($SQL);
							while ( $rows = $dbcon -> fetch_array($rs) ) {
								$r_time1			= mb_substr($rows[r_time], 0, 2);
								$r_time2			= mb_substr($rows[r_time], 2, 2);
								if (strlen($rows[r_time])>4){
									$r_time_txt = $rows[r_time];
								}else{
									$r_time_txt = $r_time1.":".$r_time2;
								}
						?>
							<br>
							<?if ($rows["r_state"]=="예약대기"){?><img src="/admin/images/icon_reser1.gif"><?}?>
							<?if ($rows["r_state"]=="예약완료"){?><img src="/admin/images/icon_reser2.gif"><?}?>
							<a href="javascript: reserve_modify('<?=$now_date?>','<?=$rows["idx"]?>');" onmousemove=lyrposit() onmouseout=lyrhide() onmouseover="lyrset('<?=$rows["u_name"]?><br><?=$rows["u_tel"]?><br><?=$rows["u_state"]?><br>')"><?=$rows["u_name"]?> - <?=$r_time_txt?></a>
						<?
							$cnt = 1;
							}
				            $n++;
				        }
				        //if
						?>
					</td>
					<?
				    }
				    //for
					?>

				</tr>
				<?
				}
				//for
				?>
			</table>
		</td>
	</tr>
</table>

<? include $path_admin."inc/footer.php"; ?>


<script type="text/javascript">
<!--
// 예약
function reserve_room(dd){
	window.open('write.php?nowdate='+dd,'_popr','width=520,height=600,scrollbars=yes');
}

function reserve_modify(dd,idx){
	window.open('write.php?nowdate='+dd+'&idx='+idx,'_popr','width=520,height=600,scrollbars=yes');
}
//-->
</script>
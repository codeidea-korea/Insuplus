<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
include_once $_SERVER["DOCUMENT_ROOT"]."/admin/time/config.php";

if ($days==""){
    $year = substr(date("Y-m-d",strtotime("+0 day")),0,4);
    $month = substr(date("Y-m-d",strtotime("+0 day")),5,2);
    $days = $year.$month;
}
else{
    $year = substr($days,0,4);
    $month = substr($days,4,2);
}

if ($section==""){
    $section = 1;
}




//날짜구하기
function getTotalDays($month,$year)
{
   $date = date( "t", mktime( 0, 0, 1, $month, 1, $year));
   return $date;
}

$totalDays = getTotalDays((int)$month,$year);


$k = 0;
for($j = 1; $j <= $totalDays; $j++) {
		$j_varW = date('w', mktime(0, 0, 0, (int)$month, $j, $year));  // 요일
		if($j_varW == 6){
			$sat_txt[$k] = $j;
			$k = $k + 1;
		}
}
$sat = Array($sat_txt);

$firstDay = date('w', mktime(0, 0, 0, $month, 1, $year));


#### 검색 설정 End
//월검색
// 쿼리설정
$SQLTEMP = "SELECT * FROM tbl_doctors_days WHERE days='$days' And section ='$section' order by orders desc";
//echo "SQLTEMP : ".$SQLTEMP."<BR>";exit;
$RSTEMP1 = $dbcon -> query($SQLTEMP);


//기타공지사항 검색
$sSQL2 = "SELECT * FROM tbl_doctors_etc WHERE days='$days' And section ='$section' order by idx";
$RSTEMP2 = $dbcon -> query($sSQL2);

$dbcon -> dbcon_close();
?>
<?
$tm = "time";
$lm = "";
include $path_admin."inc/header.php";

if($section=="1"){$txt = "평일 진료시간표";}
if($section=="2"){$txt = "토요일 진료시간표";}
?>
<!-- ########################## 컨텐츠 영역 START ##########################-->
<div id="account_content" style="width:675px">
	<!--1월-->
	<p class="t_bl mgt20">
	<span class="tx_bod16 b"><?=$month?>월</span>
	<span class="tx_bod11">/ <?=$txt?></span>
	</p>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
<form name="frm_search" method="get">
		<tr>
			<td>
			<select name="days_year">
			<?for($i=2010;$i<2021;$i++){?>
			<option value="<?=$i?>" <?if($year==$i){echo "selected";}?>><?=$i?></option>
			<?}?>
			</select>년
			<select name="days_month">
			<?for($i=1;$i<13;$i++){
					if(strlen($i)==1){
					$p = "0".$i;
					}else{
					$p = $i;
					}
				?>
			<option value="<?=$p?>" <?if($month==$i){echo "selected";}?>><?=$p?></option>
			<?}?>
			</select>월
			<input type="button" value="변경" onclick="OnSearch()">
			</td>
			<td align="right"><input type="button" value="의료진 추가" onclick="OnWrite('<?=$days?>','<?=$section?>')"></td>
		</tr>
</form>
	</table>

	<!--표-->
	<table width="100%" border="0" cellspacing="0" cellpadding="0" class="mgt10">
		<form name="frm_etc" method="post" action="submit_doctors.php">
		<input type="hidden" name="days" value="<?=$days?>">
		<input type="hidden" name="section" value="<?=$section?>">
		<tr>
			<td colspan="8" class="tline_bg" height="6"></td>
		</tr>
		<tr class="h25">
			<td class="t_ttbg tx_org11 b c" width="80">의료진</td>
			<td class="t_ttbg tx_org11 b c">구분</td>
<?if($section=="1"){?>
			<td class="t_ttbg tx_org11 b c">월</td>
			<td class="t_ttbg tx_org11 b c">화</td>
			<td class="t_ttbg tx_org11 b c">수</td>
			<td class="t_ttbg tx_org11 b c">목</td>
			<td class="t_ttbg tx_org11 b c">금</td>
			<td class="t_ttbg tx_org11 b c">순서</td>
<?}?>
<?if($section=="2"){?>
			<td class="t_ttbg tx_org11 b c"><?if ($sat_txt[0]<>""){?><?=(int)$month?>/<?=$sat_txt[0]?><?}?></td>
			<td class="t_ttbg tx_org11 b c"><?if ($sat_txt[1]<>""){?><?=(int)$month?>/<?=$sat_txt[1]?><?}?></td>
			<td class="t_ttbg tx_org11 b c"><?if ($sat_txt[2]<>""){?><?=(int)$month?>/<?=$sat_txt[2]?><?}?></td>
			<td class="t_ttbg tx_org11 b c"><?if ($sat_txt[3]<>""){?><?=(int)$month?>/<?=$sat_txt[3]?><?}?></td>
			<td class="t_ttbg tx_org11 b c"><?if ($sat_txt[4]<>""){?><?=(int)$month?>/<?=$sat_txt[4]?><?}?></td>
			<td class="t_ttbg tx_org11 b c">순서</td>
<?}?>
		</tr>
		<!--반복구간-->
		<?php
		$TempNo = 0;
		while ( $ROWSTEMP = $dbcon -> fetch_array($RSTEMP1) ) {
		?>
		<input type="hidden" name="doc_name[]" value="<?=$ROWSTEMP[doc_name]?>">
		<input type="hidden" name="idx[]" value="<?=$ROWSTEMP[idx]?>">
		<tr class="h25 c">
			<td rowspan="3" align="center" class="bg_vorg tx_org11 b">
				<?=$ROWSTEMP[doc_name]?>
		    [<a href="javascript: OnDelete('<?=$ROWSTEMP[idx]
				?>')">삭제</a>]
			</td>
			<td height="25" align="center" class="pdd5 bod_l tx_bod11 b">오전</td>
			<td align="center" class="pdd5 bod_l tx_bod11">
				<select name="A1[]">
				<option value="">선택</option>
				<option value="진료" <?if ($ROWSTEMP[A1]=="진료"){echo "selected";}?>>진료</option>
				<option value="휴진" <?if ($ROWSTEMP[A1]=="휴진"){echo "selected";}?>>휴진</option>
				</select>
			</td>
			<td align="center" class="pdd5 bod_l tx_red11">
				<select name="A2[]">
				<option value="">선택</option>
				<option value="진료" <?if ($ROWSTEMP[A2]=="진료"){echo "selected";}?>>진료</option>
				<option value="휴진" <?if ($ROWSTEMP[A2]=="휴진"){echo "selected";}?>>휴진</option>
				</select>
			</td>
			<td align="center" class="pdd5 bod_l tx_bod11">
				<select name="A3[]">
				<option value="">선택</option>
				<option value="진료" <?if ($ROWSTEMP[A3]=="진료"){echo "selected";}?>>진료</option>
				<option value="휴진" <?if ($ROWSTEMP[A3]=="휴진"){echo "selected";}?>>휴진</option>
				</select>
			</td>
			<td align="center" class="pdd5 bod_l tx_bod11">
				<select name="A4[]">
				<option value="">선택</option>
				<option value="진료" <?if ($ROWSTEMP[A4]=="진료"){echo "selected";}?>>진료</option>
				<option value="휴진" <?if ($ROWSTEMP[A4]=="휴진"){echo "selected";}?>>휴진</option>
				</select>
			</td>
			<td align="center" class="pdd5 tx_bod11">
				<select name="A5[]">
				<option value="">선택</option>
				<option value="진료" <?if ($ROWSTEMP[A5]=="진료"){echo "selected";}?>>진료</option>
				<option value="휴진" <?if ($ROWSTEMP[A5]=="휴진"){echo "selected";}?>>휴진</option>
				</select>
			</td>
			<td rowspan="3" align="center" class="pdd5 tx_bod11" width="50">
				<input type="text" name="orders[]" value="<?=$ROWSTEMP[orders]?>" size="3">
			</td>
		</tr>
		<tr>
			<td height="1" colspan="6" align="center" class="bg_gry"></td>
		</tr>
		<tr class="h25 c">
			<td height="25" align="center" class="pdd5 bod_l tx_bod11 b">오후</td>
			<td align="center" class="pdd5 bod_l tx_bod11">
				<select name="P1[]">
				<option value="">선택</option>
				<option value="진료" <?if ($ROWSTEMP[P1]=="진료"){echo "selected";}?>>진료</option>
				<option value="휴진" <?if ($ROWSTEMP[P1]=="휴진"){echo "selected";}?>>휴진</option>
				</select>
			</td>
			<td align="center" class="pdd5 bod_l tx_red11">
				<select name="P2[]">
				<option value="">선택</option>
				<option value="진료" <?if ($ROWSTEMP[P2]=="진료"){echo "selected";}?>>진료</option>
				<option value="휴진" <?if ($ROWSTEMP[P2]=="휴진"){echo "selected";}?>>휴진</option>
				</select>
			</td>
			<td align="center" class="pdd5 bod_l tx_bod11">
				<select name="P3[]">
				<option value="">선택</option>
				<option value="진료" <?if ($ROWSTEMP[P3]=="진료"){echo "selected";}?>>진료</option>
				<option value="휴진" <?if ($ROWSTEMP[P3]=="휴진"){echo "selected";}?>>휴진</option>
				</select>
			</td>
			<td align="center" class="pdd5 bod_l tx_bod11">
				<select name="P4[]">
				<option value="">선택</option>
				<option value="진료" <?if ($ROWSTEMP[P4]=="진료"){echo "selected";}?>>진료</option>
				<option value="휴진" <?if ($ROWSTEMP[P4]=="휴진"){echo "selected";}?>>휴진</option>
				</select>
			</td>
			<td align="center" class="pdd5 tx_bod11">
				<select name="P5[]">
				<option value="">선택</option>
				<option value="진료" <?if ($ROWSTEMP[P5]=="진료"){echo "selected";}?>>진료</option>
				<option value="휴진" <?if ($ROWSTEMP[P5]=="휴진"){echo "selected";}?>>휴진</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="8" class="bg_gry" height="1"></td>
		</tr>
		<?php
		    $TempNo++;
		}
		if ( !$TempNo ) {
		?>
		<tr class="h25 c">
			<td align="center" class="pdd5 tx_bod11" colspan="8">등록된 정보가 없습니다.</td>
		</tr>
		<tr>
			<td colspan="8" class="bg_gry" height="1"></td>
		</tr>
		<?php
		}
		?>

		<!--반복구간 end-->
		<tr>
			<td colspan="7" align="center" height="50"><input type="submit" value="저장"></td>
		</tr>
		</form>
	</table>
	<!--표-->

	<!--기타공지사항-->
	<table width="674" border="0" cellspacing="0" cellpadding="0" class="mgt10">
		<form name="frm_etc" method="post" action="submit_etc.php">
		<input type="hidden" name="days" value="<?=$days?>">
		<input type="hidden" name="section" value="<?=$section?>">
		<tr>
			<td><img src="../../images/01_miz/04_01_bx_t.gif" /></td>
		</tr>
		<tr>
			<td  class="pdlr25 box_bg" valign="top">
				<p><img src="../../images/01_miz/04_01_bx_tt01.gif"/></p>
				<div class="mgt10">
					<?php
					$TempNo = 0;
					while ( $ROWS2 = $dbcon -> fetch_array($RSTEMP2) ) {
					?>
					<input type="hidden" name="mode" value="modify">
					<textarea style="width:100%;height:100px;" class="input01" name="content"><?=$ROWS2[content]?></textarea>
					<?php
					    $TempNo++;
					}
					if ( !$TempNo ) {
					?>
					<input type="hidden" name="mode" value="write">
					<textarea style="width:100%;height:100px;" class="input01" name="content"></textarea>
					<?php
					}
					?>
				</div>
			</td>
		</tr>
		<tr>
			<td><img src="../../images/01_miz/04_01_bx_b.gif" width="674" height="14" /></td>
		</tr>
		<tr>
			<td align="center" height="50"><input type="submit" value="저장"></td>
		</tr>
		</form>
	</table>
	<!--기타공지사항 end-->
	<!--1월 end-->
</div>

<!-- ### 페이지 끝 ###  -->
<?
$dbcon -> dbcon_close();
?>
<?
include $path_admin."inc/footer.php";
?>


<script>
function OnWrite(days,sec) {
    window.open('doc_write.php?days='+days+'&sec='+sec,'','width=300,height=140,left=350,top=320,resizable=0,scrollbars=0')
}

function OnDelete(idx){
    if(confirm("삭제하시겠습니까? 삭제후 복원이 불가능합니다.")){
        document.location.href="submit_doctors_delete.php?days=<?=$days?>&section=<?=$section?>&idx="+idx;
    }
}

function OnSearch(){
var frm = document.frm_search;
A1 = frm.days_year.value;
A2 = frm.days_month.value;
A3 = A1+A2;
document.location.href="days_list.php?section=<?=$section?>&days="+A3;
}
</script>

<?
include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

$tm = "board";
$lm = "";
include $path_admin."inc/header.php";


$idx = REQSTR($idx, "");

// 정보 수정시
if ( strlen($idx) > 0 ) {
    //		if ( $ss_u_level < $auth_admin && $ss_idx != $idx ) {
    //			alert_back($msg_login_auth);
    //			exit;
    //		}

    $SQL = "
    select
    *
    from
    tbl_desc_area A
    where
    A.idx = '".$idx."'
    limit 0, 1
    ";
    $result = $dbcon -> query($SQL);
    $rows = $dbcon -> fetch_array($result);
    extract($rows);
    unset($rows);

	$arr_doctor = explode("||",$doctors);
}


// 의료진검색
$SQL = "select u_id, u_name from tbl_user where u_level in ('5') order by u_name ";
$RS = $dbcon -> query($SQL);
$i = 0;
while($dataRow = mysql_fetch_array($RS)) {
  $arr_doctor_name[$i]	= $dataRow['u_name'];
  $arr_doctor_id[$i]		= $dataRow['u_id'];
  $i++;
}

$url_skin_member = "/_skin/member/default/";


?>

<form name="frm" method="post" action="area_desc_write_ok.php" enctype='multipart/form-data' onSubmit="return chk_submit()">
<input type="hidden" name="idx" value="<?=$idx?>">


<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="a_st">지점설명</td>
		<td align="right"></td>
	</tr>
</table>

<table width="600" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="m_line_2px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">지점선택</th>
					<td class="m_content">
						<select name="area_code" class="select">
						<?foreach ($Arr_h_area2 as $key => $val) {?>
						<option value="<?=$key?>" <? if ("".$key == $area_code) echo "selected";?>><?=$val?></option>
						<?}?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">연락처</th>
					<td class="m_content"><textarea name="a_tel" type="text" class="m_input" style="width:300px;height:50px;"><?=$a_tel?></textarea></td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">월요일 근무시간</th>
					<td class="m_content"><input name="a_time1" value="<?=$a_time1?>" type="text" class="m_input" style="width:200px"> ex) AM 11:00 – PM 8:00</td>
				</tr>
				<tr>
					<th class="m_txt">화요일 근무시간</th>
					<td class="m_content"><input name="a_time1_2" value="<?=$a_time1_2?>" type="text" class="m_input" style="width:200px"> ex) AM 11:00 – PM 8:00</td>
				</tr>
				<tr>
					<th class="m_txt">수요일 근무시간</th>
					<td class="m_content"><input name="a_time1_3" value="<?=$a_time1_3?>" type="text" class="m_input" style="width:200px"> ex) AM 11:00 – PM 8:00</td>
				</tr>
				<tr>
					<th class="m_txt">목요일 근무시간</th>
					<td class="m_content"><input name="a_time1_4" value="<?=$a_time1_4?>" type="text" class="m_input" style="width:200px"> ex) AM 11:00 – PM 8:00</td>
				</tr>
				<tr>
					<th class="m_txt">금요일 근무시간</th>
					<td class="m_content"><input name="a_time1_5" value="<?=$a_time1_5?>" type="text" class="m_input" style="width:200px"> ex) AM 11:00 – PM 8:00</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">주말 근무시간</th>
					<td class="m_content"><input name="a_time2" value="<?=$a_time2?>" type="text" class="m_input" style="width:200px"> ex) AM 10:00 – PM 5:00</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">기본 진료시간</th>
					<td class="m_content"><input name="a_time4" value="<?=$a_time4?>" type="text" class="m_input" style="width:200px"> ex) AM 10:00 – PM 5:00</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">야간 진료시간</th>
					<td class="m_content"><input name="a_time5" value="<?=$a_time5?>" type="text" class="m_input" style="width:200px"> ex) AM 10:00 – PM 5:00</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">평일 점심시간</th>
					<td class="m_content"><input name="a_lunch_time1" value="<?=$a_lunch_time1?>" type="text" class="m_input" style="width:200px"> ex) PM 1:00 ~ 2:00</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">토요일 점심시간</th>
					<td class="m_content"><input name="a_lunch_time2" value="<?=$a_lunch_time2?>" type="text" class="m_input" style="width:200px"> ex) AM 12:00 ~ PM 1:00</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">휴일 근무시간</th>
					<td class="m_content"><input name="a_time3" value="<?=$a_time3?>" type="text" class="m_input" style="width:200px"> ex) 일요일 휴무 / 공휴일 개별공지</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">주차장 이용안내</th>
					<td class="m_content"><input name="park_desc" value="<?=$park_desc?>" type="text" class="m_input" style="width:200px"> ex) 지하 주차장 이용 가능</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">홈페이지URL</th>
					<td class="m_content">
						<input name="home_url" value="<?=$home_url?>" type="text" class="m_input" style="width:100%"><br/>
						없는경우 빈칸으로 처리
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<!-- 주소 Start -->
				<tr>
					<th class="m_txt">주소</th>
					<td class="m_content">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr height="26">
								<td width="60" align="right">기존 주소 : &nbsp;</td>
								<td valign="top" height="20"><input name="addr_zip" value="<?=$addr_zip?>" type="text" class="m_input" style="width:362px" maxlength="100"></td>
							</tr>
							<tr height="26">
								<td  align="right">신 주소 : &nbsp;</td>
								<td valign="top" height="20"><input name="addr_load" value="<?=$addr_load?>" type="text" class="m_input" style="width:362px" maxlength="100"></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<!-- 주소 End -->
				<tr>
					<th class="m_txt">버스이용시 설명</th>
					<td class="m_content">
						<textarea name="bus_txt" style="display:none;"><?=$bus_txt?></textarea>
						<div id="bus_txt"><?=$bus_txt?></div>
						[<a href="javascript: chg_content('bus_txt')">내용수정</a>]
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">전철이용시 설명</th>
					<td class="m_content">
						<textarea name="subway_txt" style="display:none;"><?=$subway_txt?></textarea>
						<div id="subway_txt"><?=$subway_txt?></div>
						[<a href="javascript: chg_content('subway_txt')">내용수정</a>]
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">자가용이용시 설명</th>
					<td class="m_content">
						<textarea name="car_txt" style="display:none;"><?=$car_txt?></textarea>
						<div id="car_txt"><?=$car_txt?></div>
						[<a href="javascript: chg_content('car_txt')">내용수정</a>]
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">지점대표사진</th>
					<td class="m_content"><input type="file" name="imgfile">
					<?if ($area_pic){?><img src="/_data/area/<?=$area_pic?>" width="100" height="40"><?}?>
					크기 : 1000 X 304
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">지점원장</th>
					<td class="m_content">
						해당지점의 원장님을 선택하시고 더 이상 없을때는 선택하세요로 두시면 됩니다.<br/>
						<?for ($k=0;$k<10;$k++){?>
							<select name="doctors[]">
								<option value="">:: 선택하세요 ::</option>
								<?for ($J=0;$J<sizeof($arr_doctor_name);$J++) {?>
								<option value="<?=$arr_doctor_id[$J]."@".$arr_doctor_name[$J]?>" <? if ($arr_doctor_id[$J]."@".$arr_doctor_name[$J] == $arr_doctor[$k]) echo "selected";?>><?=$arr_doctor_name[$J]?></option>
								<?}?>
							</select>
						<?}?>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
			</table>


			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="80" align="center">
						<?
						if ( $idx )
						    $ok_img = $url_skin_member."images/m_btn_modify.gif";
						else
						    $ok_img = $url_skin_member."images/m_btn_join.gif";
						?>
						<input type="image" src="/_skin/board/faq/images/b_btn_submit.gif" hspace="4">
						<a href="area_desc.php"><img src="<?=$url_skin_member?>images/m_btn_cancle.gif" width="76" height="28"></a>
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>

<script>
TempGo = 0;
function chk_submit() {

	var ff = document.frm;

	if (ff.a_tel.value == "") {
		alert("연락처를 입력하여 주십시오.");
		ff.a_tel.focus();
		return false;
	}

	if (ff.a_time1.value == "") {
		alert("평일근무시간을 입력하여 주십시오.");
		ff.a_time1.focus();
		return false;
	}

	if (ff.a_time2.value == "") {
		alert("주말근무시간을 입력하여 주십시오.");
		ff.a_time2.focus();
		return false;
	}

	if (ff.a_time3.value == "") {
		alert("휴일근무시간을 입력하여 주십시오.");
		ff.a_time3.focus();
		return false;
	}

	if (ff.park_desc.value == "") {
//		alert("주차장안내를 입력하여 주십시오.");
//		ff.park_desc.focus();
//		return false;
	}

	if (ff.addr_zip.value == "") {
		alert("기존주소를 입력하여 주십시오.");
		ff.addr_zip.focus();
		return false;
	}

	if (ff.addr_load.value == "") {
//		alert("신주소를 입력하여 주십시오.");
//		ff.addr_load.focus();
//		return false;
	}
}

function chg_content(div){
	window.open('area_desc_pop.php?idx=<?=$idx?>&name='+div,'_pop','width=600,height=600');
}
</script>

<?
$dbcon -> dbcon_close();
?>
<?
include $path_admin."inc/footer.php";
?>

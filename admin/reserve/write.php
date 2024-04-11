<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

// 예약내용 가져오기
if ($idx){
$SQL = "select * from TB_Reserve where idx=".$idx." ";
//echo $SQL."<br>";
$result = $dbcon -> query($SQL);
$row= $dbcon -> fetch_array($result);

// 의료진검색
$SQL = "select u_id, u_name from tbl_user where u_level in ('5') order by u_name ";
$RS = $dbcon -> query($SQL);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<title>온라인 예약 관리</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="/_css/admin.css" rel="stylesheet" type="text/css">
<link href="/_css/board.css" rel="stylesheet" type="text/css">
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript">
 $(document).ready(function() {
  //******************************************************************************
  // 상세검색 달력 스크립트
  //******************************************************************************
  var clareCalendar = {
   monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
   dayNamesMin: ['일','월','화','수','목','금','토'],
   weekHeader: 'Wk',
   dateFormat: 'yymmdd', //형식(20120303)
   autoSize: false, //오토리사이즈(body등 상위태그의 설정에 따른다)
   changeMonth: true, //월변경가능
   changeYear: true, //년변경가능
   showMonthAfterYear: true, //년 뒤에 월 표시
   showOtherMonths: true, // 다른달 보여주기
   selectOtherMonths: true, // 다른달 선택가능
   buttonImageOnly: true, //이미지표시
   buttonText: '달력선택', //버튼 텍스트 표시
   buttonImage: '/admin/images/a_btn_cal.gif', //이미지주소
   showOn: "both", //엘리먼트와 이미지 동시 사용(both,button)
   yearRange: '1990:2020' //1990년부터 2020년까지
  };
  $("#r_date").datepicker(clareCalendar);
  $("img.ui-datepicker-trigger").attr("style","margin-left:5px; vertical-align:middle; cursor:pointer;"); //이미지버튼 style적용
  $("#ui-datepicker-div").hide(); //자동으로 생성되는 div객체 숨김
 });

function chg_doctor(val){
	arr_val = val.split("||");
	var ff = document.frm;
	ff.doctor.value = arr_val[1];
	ff.doctor_id.value = arr_val[0];
}
</script>

<style>
.reserve_tb1 td{border-top-color: #CCCCCC; border-right-color: #CCCCCC; border-bottom-color: #CCCCCC; border-left-color: #CCCCCC; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid;}

.reserve_tb td{border-top-color: #7abae4; border-right-color: #7abae4; border-bottom-color: #7abae4; border-left-color: #7abae4; border-top-width: 1px; border-right-width: 1px; border-bottom-width: 1px; border-left-width: 1px; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid;}
</style>

<form name="frm" method="post" action="" target="ifr_action">
<input type="hidden" name="idx" value="<?=$idx?>">
<input type="hidden" name="doctor" value="<?=$row["doctor"]?>">
<input type="hidden" name="doctor_id" value="<?=$row["doctor_id"]?>">
<table width="100%" cellpadding='3' cellspacing='1' class="reserve_tb">
<tr>
	<td>예약일</td><td><input type="text" name="r_date" id="r_date" size="10" class="m_input" value="<?=$row["r_date"]?>"></td>
</tr>
<tr>
	<td>예약시간</td><td colspan="3">
	<select name="r_time">
		<option value="">:: 선택 ::</option>
		<?$kk= 0;?>
		<?for($k=11;$k<18;$k++){
			if (strlen($k)==1){
				$kk = "0"&$k;
			}else{
				$kk = $k;
			}
		?>
		<option value="<?=$kk?>00" <?if($row["r_time"]==$kk."00"){echo"selected";}?>><?=$k?>:00</option>
		<?
		$kk++;
		}
		?>
		<option value="20:00" <?if($row["r_time"]=="20:00"){echo "selected";}?>>20:00</option>
	</select>
	</td>
</tr>
<?if (!$idx){?>
<tr>
	<td>회원아이디</td><td colspan="3"><input type="text" name="u_id" class="m_input" value="<?=$row["u_id"]?>"></td>
</tr>
<?}?>
<tr>
	<td>예약자</td><td colspan="3"><input type="text" name="u_name" class="m_input" value="<?=$row["u_name"]?>"></td>
</tr>
<?if (!$idx){?>
<tr>
	<td>비밀번호</td><td colspan="3"><input type="text" name="pwd" class="m_input" value="<?=$row["pwd"]?>"></td>
</tr>
<?}?>
<tr>
	<td>연락처</td><td colspan="3"><input type="text" name="u_tel" class="m_input" value="<?=$row["u_tel"]?>"></td>
</tr>
<tr>
	<td>이메일</td><td colspan="3"><input type="text" name="u_email" class="m_input" value="<?=$row["u_email"]?>" style="width:98%;"></td>
</tr>
<tr>
	<td>지점</td><td colspan="3">
		<select name="area">
			<option value="">:: 선택 ::</option>
		<? foreach ($Arr_h_area as $key => $val) { ?>
			<option value="<?=$key?>" <? if ( "".$key == $row["area"]) echo "selected";?> ><?=$val?></option>
		<? } ?>
		</select>
	</td>
</tr>
<tr>
	<td>주치의</td><td colspan="3">
		<select name="sel_doctor" onchange="chg_doctor(this.value)">
			<option value="||">:: 선택하세요 ::</option>
			<?while ( $doc_rows = $dbcon -> fetch_array($RS) ) {?>
			<option value="<?=$doc_rows["u_id"]?>||<?=$doc_rows["u_name"]?>" <? if ($doc_rows["u_id"] == $row["doctor_id"]) echo "selected";?>><?=$doc_rows["u_name"]?></option>
			<?}?>
		</select>
	</td>
</tr>
<tr>
	<td>진료구분</td><td colspan="3">
		<select name="treat">
			<option value="">:: 선택 ::</option>
		<? foreach ($Arr_treat as $key => $val) { ?>
			<option value="<?=$key?>" <? if ( $key == $row["treat"]) echo "selected";?> ><?=$val?></option>
		<? } ?>
		</select>
	</td>
</tr>
<tr>
	<td>초＊재진 여부</td><td colspan="3">
		<input type="radio" name="medical_exam" value="초진" <?if ($row["medical_exam"]=="" || $row["medical_exam"]=="초진"){echo "checked";}?>>초진
		<input type="radio" name="medical_exam" value="재진" <?if ($row["medical_exam"]=="재진"){echo "checked";}?>>재진
	</td>
</tr>

<tr>
	<td>상태</td>
	<td colspan="3">
		<select name="r_state">
			<option value="예약대기" <?if ($row["r_state"]=="예약대기"){echo "selected";}?>>예약대기</option>
			<option value="예약완료" <?if ($row["r_state"]=="예약완료"){echo "selected";}?>>예약완료</option>
			<option value="예약취소" <?if ($row["r_state"]=="예약취소"){echo "selected";}?>>예약취소</option>
		</select>
	</td>
</tr>
<tr>
	<td>예약자 글</td><td colspan="3"><textarea name="content" style="width:100%;height:60px;" class="m_input"><?=$row["content"]?></textarea></td>
</tr>
<tr>
	<td>관리자 글</td><td colspan="3"><textarea name="reply" style="width:100%;height:60px;" class="m_input"><?=$row["reply"]?></textarea></td>
</tr>
</table>
<table width="100%">
<tr>
<td style="vertical-align:middle;text-align:center;">
<a href="javascript: on_submit();"><img src="/_skin/member/default/images/m_btn_modify.gif" hspace="4"></a>
<a href="javascript: window.close();"><img src="/_skin/member/default/images/m_btn_cancle.gif" width="76" height="28"></a>
</td>
</tr>
</table>
</form>
<script type="text/javascript">
<!--
function on_submit(){
	var ff = document.frm;
	ff.action="write_ok.php";

	if (ff.r_date.value=="")
	{
		alert("예약날짜를 선택해주시기 바랍니다.");
		ff.r_date.focus();
		return;
	}
	if (ff.r_time.value=="")
	{
		alert("예약시간을 선택해주시기 바랍니다.");
		ff.r_time.focus();
		return;
	}
	if (ff.u_name.value=="")
	{
		alert("예약자명을 입력해 주시기 바랍니다.");
		ff.u_name.focus();
		return;
	}
	if (ff.u_tel.value=="")
	{
		alert("연락처를 입력해 주시기 바랍니다.");
		ff.u_tel.focus();
		return;
	}

	ff.submit();
}
//-->
</script>

<div id="act_all" style="display:none;width:300px;height:200px;"></div>
<iframe src="" id="ifr_action" name="ifr_action" width="500" height="200" frameborder="0" style="display:none;"></iframe>
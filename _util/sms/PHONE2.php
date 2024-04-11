<?

include ("./module/sms/var/sms_var.php");
$sms_use_num		= file("./module/sms/var/num.txt");
$sms_use_date		= file("./module/sms/var/sms_date.cgi");
$SMS_FDATE			= trim($sms_use_date[0]);
$g_SMS_HAVENUM		= ($sms_use_num[0]) ? $sms_use_num[0] : 0 ; //보유량
$g_ADMIN_HAND_TEL	= str_replace("-", "", $sms_admin_num);		//휴대폰번호
$g_ADMIN_HAND_EXP	= explode("-", $sms_admin_num);
$g_SMS_MALL_ID		= $sms_mall_num;
$g_SMS_MALL_PASS = $sms_mall_pass;
$img_path			= "./module/sms";
$SEND_SMS_TOTAL = db_fetch_array(db_query("SELECT count(*) FROM zio_smsinfo",$DB_CONNECT));
?>

<HTML>
<HEAD>
<META HTTP-EQUIV="MSThemeCompatible" Content="No">
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">

</HEAD>


<BODY LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0  >
<DIV ALIGN=CENTER>


<?
preg_match("/([a-zA-Z0-9_.]+)$/", $PHP_SELF, $f1);
preg_match("/([a-zA-Z0-9_.]+)$/", __FILE__, $f2);
if($f1[1] == $f2[1]) exit;
?>

<script language=javascript>
var chked;
function hngLen(fo){
	var msglen = 0;
	for(k=0;k<fo.length;k++){
		t = fo.charAt(k);
		if (escape(t).length > 4)
			msglen += 2;
		else
			msglen++;
	}
	return msglen;
}


function fsize_print() {

	document.tophone.cbyte.value = hngLen(document.tophone.CONTENT.value);

	if ( hngLen(document.tophone.CONTENT.value) > 80) {
	alert('전송메세지는 80바이트 이내이어야 합니다.');

	document.tophone.cbyte.value = hngLen(document.tophone.CONTENT.value);
	document.tophone.CONTENT.value = document.tophone.CONTENT.value.substring(0,70);
	document.tophone.CONTENT.focus();
	return false;
	}
}
function send_check() {
	if(!document.tophone.return_phone.value) {
	alert('\n\n운영자 전화번호가 등록되지 않았습니다.    \n\n[문자전송서비스] 설정항목에 운영자 \n\n전화번호를 등록해 주십시오.\n\n');
	return false;
	}
	if ( document.tophone.CONTENT.value.length < 1 ) {
	alert('전송메세지가 입력되지 않았습니다.');
	document.tophone.CONTENT.focus();
	return false;
	}
	if ( document.tophone.return_phone.value.length < 1 ) {
	alert('보내는사람 전화번호가 입력되지 않았습니다.');
	document.tophone.return_phone.focus();
	return false;
	}
	if ( document.tophone.preengage[1].checked  ) {

		if ( document.tophone.preengage_day.value.length < 2 ) {
		alert('예약일이 입력되지 않았습니다.');
		document.tophone.preengage_day.focus();
		return false;
		}
		if ( document.tophone.preengage_hour.value.length < 2 ) {
		alert('예약시간이 입력되지 않았습니다.');
		document.tophone.preengage_hour.focus();
		return false;
		}
		if ( document.tophone.preengage_min.value.length < 2 ) {
		alert('예약분이 전화번호가 입력되지 않았습니다.');
		document.tophone.preengage_min.focus();
		return false;
		}
	}

	if (!document.tophone.multi_num.value || parseInt(document.tophone.multi_num.value) < 1) {
		if ( document.tophone.phone_num.value.length < 1 ) {
		alert('받는사람 전화번호가 입력되지 않았습니다.');
		document.tophone.phone_num.focus();
		return false;
		}
	}

	if ( hngLen(document.tophone.CONTENT.value) > 80) {
	alert('전송메세지는 80바이트 이내이어야 합니다.');

	document.tophone.cbyte.value = hngLen(document.tophone.CONTENT.value);
	document.tophone.CONTENT.value = document.tophone.CONTENT.value.substring(0,70);
	document.tophone.CONTENT.focus();
	return false;
	}
}
function reciever_copy(msg,name,p1,p2) {

	if (msg) {
	document.tophone.CONTENT.value = msg;
	document.tophone.cbyte.value = hngLen(msg);
	}
	document.tophone.toname.value = name;
	document.tophone.phone_type.value = p1;
	document.tophone.phone_num.value = p2;
	//window.alert('전송내용을 입력해주시거나, 코드보기를 통해 선택해 주세요.');
	document.tophone.CONTENT.focus();
}
function msg_copy(msg) {

	document.tophone.CONTENT.value = msg;
	document.tophone.CONTENT.focus();
}

function sms_cmp(dat){

	var f = document.forms.sms_list;
	var i = 0;
	var chked = 0;
	var sms_str = "";
	for(i = 0; i < f.length; i++ ) {
		if(f[i].type == 'checkbox' && f[i].name.substring(0,5) == dat) {
			if(f[i].checked) {
				chked++;
				sms_str = sms_str + f[i].value + "/%%%/";
			}
		}
	}
	if( chked < 1 ) {
		if (dat == 'child') {
			var dat_str = "";
		}
		else {
			var dat_str = "";
		}
		alert(dat_str + ' 수신자가 선택되지 않았습니다.');
		return false;
	}
	document.tophone.num_list.value = sms_str;
	if(dat == 'child') {
	document.tophone.CONTENT.value = document.sms_list.birth_msg.value;
	}
	else {
	document.tophone.CONTENT.value = document.sms_list.spe_msg.value;
	}
	document.all.reciever_numer.style.visibility = "hidden";
	document.all.reciever_numer.style.position = "absolute";
	document.all.hreciever_numer.style.visibility = "visible";
	document.all.hreciever_numer.style.position = "";
	document.tophone.multi_num.value = chked;
}
function sms_cmp1(dat){

	var f = document.forms.sms_list;
	var i = 0;
	var chked = 0;
	var sms_str = "";
	for(i = 0; i < f.length; i++ ) {
		if(f[i].type == 'checkbox' && f[i].name.substring(0,5) == dat) {
			if(f[i].checked) {
				chked++;
				sms_str = sms_str + f[i].value + "/%%%/";
			}
		}
	}
	if( chked > 0 ) {

	document.tophone.num_list.value = sms_str;
	if(dat == 'child') {
	document.tophone.CONTENT.value = document.sms_list.birth_msg.value;
	}
	else {
	document.tophone.CONTENT.value = document.sms_list.spe_msg.value;
	}
	document.all.reciever_numer.style.visibility = "hidden";
	document.all.reciever_numer.style.position = "absolute";
	document.all.hreciever_numer.style.visibility = "visible";
	document.all.hreciever_numer.style.position = "";
	document.tophone.multi_num.value = chked;


	}
	else {

	re_set();

	}

}
function sms_sel(dat){
	var f = document.forms.sms_list;
	var i = 0;
	var selps = 0;
	for(i = 0; i < f.length; i++ ) {
		if(f[i].type == 'checkbox' && f[i].name.substring(0,5) == dat) {
			if(f[i].checked) {
				f[i].checked = false;
			}
			else {
				f[i].checked = true;
				selps++;
			}
		}
	}

	if( selps < 1 ) {

	re_set();

	}
}
function re_set() {
	document.all.hreciever_numer.style.visibility = "hidden";
	document.all.hreciever_numer.style.position = "absolute";
	document.all.reciever_numer.style.visibility = "visible";
	document.all.reciever_numer.style.position = "";
	document.tophone.reset();
}


function sms_sel_1(dat){
	var f = document.forms.mall_list;
	var i = 0;
	var chked = 0;
	var sms_str = "";
	var sms_str1 = "";

	for(i = 0; i < f.length; i++ ) {
		if(f[i].type == 'checkbox' && f[i].name.substring(0,5) == dat) {
			if(f[i].checked) {
				chked++;
				sms_str = sms_str + f[i].value + "\n";
				var smsvalue = f[i].value.split("|");
				sms_str1 = sms_str1 + smsvalue[0] + "\n";
			}
		}
	}
	if( chked > 0 ) {

	document.tophone.num_list.value = sms_str;
	document.tophone.num_list1.value = sms_str1;

	if(chked > 6 && chked < 16) {
	document.tophone.num_list1.style.height = (chked*15) + 5;
	}

	document.all.reciever_numer.style.visibility = "hidden";
	document.all.reciever_numer.style.position = "absolute";
	document.all.hreciever_numer.style.visibility = "visible";
	document.all.hreciever_numer.style.position = "";
	document.tophone.multi_num.value = chked;

	}
	else {

	re_set();

	}
}
function select_all(t){
	var f = document.mall_list;
	for (var i=0;	i<f.elements.length;i++){
		var ele=f.elements[i];
		if (ele.name.substring(0,5)=="multi") {
			if(t == 1) {
				ele.checked=true;
			}
			else {
				ele.checked=false;
			}
		}
	}
	sms_sel_1('multi');
}
</script>

<iframe name=hidden_phone src='' width=0 height=0 frameborder=0></iframe>
<table cellSpacing="0" cellPadding="0" width="152" border="0">

<FORM NAME='tophone' ACTION='http://www.munjabada.co.kr/Remote/RemoteSmsSend.php' TARGET="hidden_phone"  METHOD=POST onsubmit='return send_check();'>
<!--
<FORM NAME='tophone' ACTION='../module/sms/sendresult.php' TARGET="hidden_phone" METHOD=POST onsubmit='return send_check();'>
-->

<INPUT TYPE=HIDDEN NAME='remote_url' VALUE="haewuso.co.kr/admin/module/sms/sendresult.php">
<INPUT TYPE=HIDDEN NAME='remote_id' VALUE='<?ECHO $g_SMS_MALL_ID;?>'>
<INPUT TYPE=HIDDEN NAME='remote_pass' VALUE='<?ECHO $g_SMS_MALL_PASS;?>'>
<INPUT TYPE=HIDDEN NAME='msg_on_off' VALUE='off'>

  <tbody>
    <tr>
      <td width="12">
        <p><img src="<?ECHO $img_path;?>/image/phone/mob_01.gif" border="0" width="12" height="12"></p>
      </td>
      <td width="128" background="<?ECHO $img_path;?>/image/phone/mob_topbg.gif">
        <p><img src="<?ECHO $img_path;?>/image/phone/mob_topbg.gif" border="0" width="10" height="12"></p>
      </td>
      <td width="12">
        <p><img src="<?ECHO $img_path;?>/image/phone/mob_02.gif" border="0" width="12" height="12"></p>
      </td>
    </tr>
    <tr>
      <td width="12" background="<?ECHO $img_path;?>/image/phone/mob_leftbg.gif">
        <p>　</p>
      </td>
      <td align="middle" width="128" bgColor="#0bdcf5">
        <table cellSpacing="0" cellPadding="0" width="128" border="0">
          <tbody>
              <tr>
				<td nowrap><img src="<?ECHO $img_path;?>/image/phone/mob_img01.gif" border="0" width="26" height="12"></td>
				<td nowrap style='font-size:8pt;'><?ECHO number_format($g_SMS_HAVENUM);?>건 가능</td>
				<td align=right><img src="<?ECHO $img_path;?>/image/phone/mob_img02.gif" border="0" width="17" height="12"></td>
            </tr>
          </tbody>
        </table>
      </td>
      <td width="12" background="<?ECHO $img_path;?>/image/phone/mob_rightbg.gif">
        <p>　</p>
      </td>
    </tr>
    <tr>
      <td width="12" background="<?ECHO $img_path;?>/image/phone/mob_leftbg.gif">
        <p><img src="<?ECHO $img_path;?>/image/phone/mob_leftbg.gif" border="0" width="12" height="10"></p>
      </td>
      <td align="middle" width="138" bgColor="#0bdcf5">
	  <textarea onchange='fsize_print();' onkeyup='fsize_print();' onkeypress='fsize_print();' style="BORDER-TOP-WIDTH: 0px; BORDER-LEFT-WIDTH: 0px; FONT-SIZE: 9pt; BACKGROUND: none transparent scroll repeat 0% 0%; BORDER-BOTTOM-WIDTH: 0px; OVERFLOW: hidden; BORDER-RIGHT-WIDTH: 0px; colorvc_resdate: #000000" name="CONTENT" rows="7" cols="16"></textarea>
      </td>
      <td width="12" background="<?ECHO $img_path;?>/image/phone/mob_rightbg.gif">
        <p><img src="<?ECHO $img_path;?>/image/phone/mob_rightbg.gif" border="0" width="12" height="10"></p>
      </td>
    </tr>

    <tr>
      <td width="12" background="<?ECHO $img_path;?>/image/phone/mob_leftbg.gif">
      </td>
      <td align="middle" width="128" bgColor="#0bdcf5">
	  <br>
        <table cellSpacing="0" cellPadding="0" width="128" border="0">
          <tbody>
            <tr>
				<td style='font-size:8pt;' valign=bottom></td>
				<td align=right style='font-size:8pt;'><input style="border:0;color:green;text-align:right;background:#0bdcf5;height:15;font-size:8pt;height:14;" size="2" value="0" name="cbyte">Bytes</td>
            </tr>
          </tbody>
        </table>
      </td>
      <td width="12" background="<?ECHO $img_path;?>/image/phone/mob_rightbg.gif">
        <p>　</p>
      </td>
    </tr>

    <tr>
      <td width="12">
        <p><img src="<?ECHO $img_path;?>/image/phone/mob_03.gif" border="0" width="12" height="12"></p>
      </td>
      <td width="128" background="<?ECHO $img_path;?>/image/phone/mob_downbg.gif">
        <p><img src="<?ECHO $img_path;?>/image/phone/mob_downbg.gif" border="0" width="10" height="12"></p>
      </td>
      <td width="12">
        <p><img src="<?ECHO $img_path;?>/image/phone/mob_04.gif" border="0" width="12" height="12"></p>
      </td>
    </tr>
    <tr>
      <td align="middle" colSpan="3" height="10">

      </td>
    </tr>
    <tr>
      <td align="middle" colSpan="3">
        <p><img src="<?ECHO $img_path;?>/image/phone/mob_top.gif" border="0" width="152" height="10"></p>
      </td>
    </tr>
    <tr>
      <td align="middle" background="<?ECHO $img_path;?>/image/phone/mob_bg.gif" colSpan="3">
	  <b><font color="#2b74be">발신번호</font></b>
	  <input style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid" maxLength="12" size="12" name="return_phone" value="<?ECHO $sms_admin_num;?>"></td>
    </tr>
    <tr>
      <td align="middle" background="<?ECHO $img_path;?>/image/phone/mob_bg.gif" colSpan="3"><span style="FONT-SIZE: 9pt">전송유형<input type="radio" CHECKED value="" name="preengage">즉시<input type="radio" value="on" name="preengage">예약</span></td>
    </tr>
    <tr>
      <td style="PADDING-TOP: 5px" background="<?ECHO $img_path;?>/image/phone/mob_bg.gif" colSpan="3"><span style="FONT-SIZE: 9pt">&nbsp;&nbsp;(예약)
         <input style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; FONT-SIZE: 9pt; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid" maxLength="2" size="3" value="<?ECHO date("Y")?>" name="preengage_year">-<input style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; FONT-SIZE: 9pt; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid" maxLength="2" size="1" value="<?ECHO date("m")?>" name="preengage_month">-<input style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; FONT-SIZE: 9pt; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid" maxLength="2" size="1" value="" name="preengage_day"><br>
		<img src="<?ECHO $img_path;?>/image/phone/blank.gif" width=50 height=1>
        <input style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; FONT-SIZE: 9pt; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid" maxLength="2" size="2" value="" name="preengage_hour">시 <input style="BORDER-RIGHT: #999999 1px solid; BORDER-TOP: #999999 1px solid; FONT-SIZE: 9pt; BORDER-LEFT: #999999 1px solid; BORDER-BOTTOM: #999999 1px solid" maxLength="2" size="2" value="" name="preengage_min">분<br>
        </span></td>
    </tr>
    <tr>
      <td background="<?ECHO $img_path;?>/image/phone/mob_bg.gif" colSpan="3">
        <p><img height="8" src="<?ECHO $img_path;?>/image/phone/blank.gif" width="10" border="0"></p>
      </td>
    </tr>
    <tr>
      <td background="<?ECHO $img_path;?>/image/phone/line_02.gif" colSpan="3">
        <p><img height="1" src="<?ECHO $img_path;?>/image/phone/blank.gif" width="10" border="0"></p>
      </td>
    </tr>
    <tr>
      <td background="<?ECHO $img_path;?>/image/phone/mob_bg.gif" colSpan="3">
        <p><img src="<?ECHO $img_path;?>/image/phone/blank.gif" border="0" width="10" height="5"></p>
      </td>
    </tr>
	</table>

<table cellspacing=0 cellpadding=0>
    <tr>
      <td background="<?ECHO $img_path;?>/image/phone/mob_bg.gif" colSpan="3">
        <p><img src="<?ECHO $img_path;?>/image/phone/blank.gif" border="0" width="10" height="5"></p>
      </td>
    </tr>
    <tr>
      <td align="middle" background="<?ECHO $img_path;?>/image/phone/mob_bg.gif" colSpan="3">
        <p><INPUT TYPE=IMAGE SRC="<?ECHO $img_path;?>/image/phone/mob_send.gif" border="0" width="54" height="24"><img src="<?ECHO $img_path;?>/image/phone/blank.gif" border="0" width="10" height="10"><a href="javascript:re_set();"><img src="<?ECHO $img_path;?>/image/phone/mob_clear.gif" border="0" width="54" height="24"></a></p>
      </td>
    </tr>
    <tr>
      <td align="middle" colSpan="3">
        <p><img src="<?ECHO $img_path;?>/image/phone/mob_down.gif" border="0" width="152" height="10"></p>
      </td>
    </tr>
    <tr>
      <td align="middle" colSpan="3">
        <p>&nbsp;</p>
      </td>
    </tr>
  </tbody>

<?$SMS_DATA = db_query("SELECT * FROM zio_members ".$WHEREIS , $DB_CONNECT);?>
  <textarea name=num_list rows=1 cols=1 style='position:absolute;visibility:hidden;'><?$i=0;while($SMS_MB=db_fetch_array($SMS_DATA)):?><?=trim($SMS_MB[MB_HAND_TEL])?>,<?$i++;endwhile?></textarea>
   <textarea name=send_id rows=1 cols=1 style='position:absolute;visibility:hidden;'></textarea>
  <input type=hidden name=toname value="">
  </FORM>

</table>


</DIV>
</BODY>
</HTML>


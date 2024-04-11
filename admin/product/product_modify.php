<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	// 관리자 체크
	admin_chk($auth_admin, $url_admin_login_out);
?>
<?
	$tm = "product";
	$lm = "";
	include $path_admin."inc/header.php";
?>
<!-- Content Start -->
<script language='JavaScript'>
<!--
// 입력시 입력값 검사하기
function checkForm(edit)
{

	var frm = document.frmWrite;

	if(!frm.f_pname.value){
		alert('제품명을 입력해 주세요');
		frm.f_pname.focus();
		return false;
	}

	if(!frm.f_cnum.value){
		alert('카테고리를 선택해 주세요');
		frm.f_cnum.focus();
		return false;
	}

	if(edit=="E"){
		RainEditor('f_pinfo','E');
	}
	else{
		if(!frm.f_pinfo.value) {
			alert('제품상세설명을 입력하세요!');
			frm.f_pinfo.focus();
			return false;
		}
	}
	document.getElementById("btnSubmit").disabled=true;
	return true;
}

function rows_size(idx)
{
	var f = document.frmWrite;
	if(idx=="1"){
		size = f.f_cheader;
	}
	else if(idx=="2"){
		size = f.f_cfooter;
	}

	if(size.rows == 5){
		size.rows = 10; // 세로 size
	}
	else if(size.rows == 10){
		size.rows = 20; // 세로 size
	}
	else if(size.rows == 20){
		size.rows = 40; // 세로 size
	}
	else{
		size.rows = 5; // 원래 세로 size
	}
}

//옵션생성

var optCount = 0;

function initOption(opt,optval,optnum)
{
	var cnt = document.frmOptdel.f_optcnt.value;
	optNum = optnum.split('|agw|');
	optStr = opt.split('|agw|');
	optValue = optval.split('|agw|');
	for(i=0;i<cnt;i++){
		addOption(optStr[i],optValue[i],optNum[i]);
	}
}

function addOption(optval,optContent,optNum)
{

	var optList = document.getElementById("option_div");
	var br = document.createElement("br");
	var opt = document.createElement("input");
	var opt_label = document.createElement("span");
	var opt_value_label = document.createElement("span");
	var opt_del = document.createElement("span");
	opt.setAttribute("type", "text");
	opt.setAttribute("name", "f_coption[]");
	opt.setAttribute("id", "f_coption[]");
	if (optval) {
		opt.setAttribute("value", optval);
	}
	opt.setAttribute("className", "input");
	opt.style.width = "120px";

	opt_label.innerHTML ='옵션 : ';
	optList.appendChild(opt_label);
	opt_label.style.fontSize = "11px";
	opt_label.style.fontFamily = "Dotum";
	opt_label.style.letterSpacing = "-1px";
	optList.appendChild(opt);


	var opt_value = document.createElement("input");
	opt_value.setAttribute("type", "text");
	opt_value.setAttribute("name", "f_coption_value[]");
	opt_value.setAttribute("id", "f_coption_value[]");
	if (optContent) {
		opt_value.setAttribute("value", optContent);
	}
	opt_value.setAttribute("className", "input");
	opt_value.style.width = "350px";

	opt_value_label.innerHTML ='&nbsp;&nbsp;내용 : ';
	optList.appendChild(opt_value_label);
	opt_value_label.style.fontSize = "11px";
	opt_value_label.style.fontFamily = "Dotum";
	opt_value_label.style.letterSpacing = "-1px";
	optList.appendChild(opt_value);


	var opt_num = document.createElement("input");
	opt_num.setAttribute("type", "hidden");
	opt_num.setAttribute("name", "f_coption_num[]");
	opt_num.setAttribute("id", "f_coption_num[]");
	if (optNum) {
		opt_num.setAttribute("value", optNum);
	}
	optList.appendChild(opt_num);

	if (optNum) {
		optdel_no = optNum;
	}
	else {
		optdel_no = '';
	}
	opt_del.innerHTML ="&nbsp;<a href=\"javascript:delOption('" + optdel_no + "')\"><img src=\"<?=$url_admin?>images/a_icon_optdel.gif\" alt=\"옵션삭제\"></a>";
	optList.appendChild(opt_del);
	optList.appendChild(br);

	optCount ++;
	return false;
}

function removeOption()
{
	if (optCount > parseInt(document.frmOptdel.f_optcnt.value)) {
		try{
			var optList = document.getElementById("option_div");
			for(var i=0;i<=6;i++){
				var child = optList.childNodes.item(optList.childNodes.length-1);
				optList.removeChild(child);
			}
			optCount--;
			return false;
		}
		catch(e){
			alert("더이상 지울수 없습니다.");
			return false;
		}
	}
	else {
		alert("더이상 지울수 없습니다.");
		return false;
	}
}

function delOption(idx)
{
	var del_ok;
	del_ok = confirm("정말 삭제하시겠습니까?");
	var f = document.frmOptdel;
	if (idx) {
		if (del_ok) {
			f.f_optnum.value = idx;
			f.submit();
		}
	}
	else {
		removeOption();
	}
}

//-->
</script>
<iframe id="process" name="process" src="" style="width:0;height:0;border:0"></iframe>
<form name="frmOptdel" method="post" action="<?=$url_admin?>product/product_option_del.php" target="process">
<input type="hidden" name="f_optnum">
<input type="hidden" name="f_pnum" value="4">
<input type="hidden" name="f_optcnt" value="4">
</form>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">  제품 등록</td>
		<td width="300" align="right">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td align="right">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td bgcolor="#339933" width="150" height="22" align="center" class="a_tab_txt" valign="top" style="padding-top:3px"><a href='<?=$url_admin?>product/product_category_write.php'>최상위 카테고리 등록</a> </td>
								<td width="2"></td>
								<td bgcolor="#000000" width="150" height="22" align="center" class="a_tab_txt" valign="top" style="padding-top:3px"><a href='<?=$url_admin?>product/product_category_modify_all.php'>카테고리설정 전체변경</a> </td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="3" height="19"></td>
	</tr>
</table>
<script language="Javascript1.2" src="http://demo.bluecarpet.co.kr/editor/prototype-1.4.0.js"></script>
<script language="Javascript1.2" src="http://demo.bluecarpet.co.kr/editor/RainEditor.js"></script>
<form name="frmWrite" method="post" action="<?=$url_admin?>admin_process.php" ENCTYPE='multipart/form-data' onSubmit="return checkForm('E')">
<input type="hidden" name="mode" value="pro_modify">
<input type="hidden" name="f_pnum" value="4">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 제품 정보 입력</td>
	</tr>
	<tr>
		<td colspan="2" height="2"></td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="2" class="a_line_2px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품명</td>
		<td class="a_content"><input type="text" name="f_pname" style="width:100%" maxlength="255" class="a_input" value="제품2_1"></td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품카테고리</td>
		<td class="a_content">
			<select name="f_cnum" class="a_input">
			<option value=''>제품카테고리선택</option>
			<option value="2" selected>① 제품카테고리2</option>
			<option value="1" >① 제품카테고리1</option>
			<option value="4" >├─② 카테고리1_2</option>
			<option value="3" >├─② 카테고리1_1</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품출력순위</td>
		<td class="a_content"><input type="text" name="f_prank" style="width:100px" maxlength="10" class="a_input" value="1"></td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">
			제품 옵션
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>옵션갯수조절&nbsp;</td>
					<td width="20" height="11"><img src="<?=$url_admin?>images/a_btn_optadd.gif" onClick="return addOption('','');" alt="옵션 갯수 늘리기" style="cursor:hand"></td>
					<td><img src="<?=$url_admin?>images/a_btn_optdel.gif" onClick="return removeOption();" alt="옵션 갯수 줄이기" style="cursor:hand"></td>
				</tr>
			</table>
		</td>
		<td class="a_content">
			<div name="option_div" id="option_div"></div>
			<script type="text/javascript">initOption('111|agw|222|agw|333|agw|444|agw|','|agw||agw||agw||agw|','13|agw|14|agw|15|agw|16|agw|');</script>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품 간략 설명<br><a href="javascript:rows_size(1)">[ 쓰기폼 늘리기 ]</a></td>
		<td class="a_content"><textarea rows="5" name="f_psimpleinfo" style="width:100%" class="a_textarea"></textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품 상세 설명</td>
		<td class="a_content"><textarea rows="15" name="f_pinfo" style="width:100%" class="a_textarea">aaa</textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품리스트 이미지</td>
		<td class="a_content">
			<input type="file" name="f_plist_img" style="width:100%" maxlength="255" class="a_input">
			<input type="hidden" name="f_plist_img_old" value="">
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품상세 이미지</td>
		<td class="a_content">
			<input type="file" name="f_pview_img" style="width:100%" maxlength="255" class="a_input">
			<input type="hidden" name="f_pview_img_old" value="">
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="80" align="center">
			<input type="image" name="btnSubmit" id="btnSubmit" src="<?=$url_admin?>images/a_btn_submit.gif" hspace="4">
			<img src="<?=$url_admin?>images/a_btn_cancle.gif" width="76" height="28" onClick="javascript:document.location.href='<?=$url_admin?>product/product_list.php'" style="cursor:hand">
		</td>
	</tr>
</table>
</form>



<? include $path_admin."inc/footer.php"; ?>


<? $dbcon -> dbcon_close();?>



<html>
<head>
<link href="/_css/admin.css" rel="stylesheet" type="text/css">
<link href="/inc/css/blue_style.css" rel="stylesheet" type="text/css">
<link href="/inc/css/content.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="/_css/board.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="/script/global.js"></script>
<script language="JavaScript" src="/script/public.js"></script>
<!-- <script language="JavaScript" src="/script/quickmenu.js"></script> -->

<script>
	top.document.title = ":::: (주) 신도컴퓨터 ::::";
	document.title = ":::: (주) 신도컴퓨터 ::::";
	var GlobalLoginURL = "/member/login.php?url=/admin/product/product_write.php";
	var GlobalLogoutURL = "/member/logout.php";
	var GlobalJoinURL = "/member/agree.php";
	var GlobalModifyURL = "/member/UserModify.php";
	var GlobalFindIDURL = "/member/find_id.php";
	var GlobalFindPWURL = "/member/find_pw.php";
	var GlobalDropURL = "/member/UserDrop.php";
	var GlobalAgree = "/member/AgreeList.php";
	var GlobalWarningURL = "";
	var GlobalPguideURL = "";
	var GlobalTguideURL = "";
</script>

<style>
	/*캘린더*/
	input.text_cal {
		behavior:url("/_util/calendar/htc_calendar2.htc");
		color:#666666;
		height:21px;
		border:1px #E5E5E5 solid;
		font-size:12px;
		font-family:"Gulim";
		letter-spacing:0px;
		text-align:center;
		ime-mode:disabled;
		width:122px;
	}
</style>
</head>
<body style="background-color:#FFFFFF;">
<!-- Header Start -->
<table border="0" cellpadding="0" cellspacing="0" width="100%" background="/admin/images/admin_top_bg.gif">
	<tr>
		<td width="950" height="30">
			<table border="0" cellpadding="0" cellspacing="0" width="950">
				<tr>
					<td align="right" valign="top" class="a_global" style="padding-top:3px">
						<a href="/" target="_blank"><font color="#006699">SITE HOME</font></a>  &nbsp;|&nbsp;
						<a href="/admin/"><font color="#339933">ADMIN HOME</font></a>  &nbsp;|&nbsp;
						<a href="/admin/logout.php">LOGOUT</a>
					</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;</td>
	</tr>
</table>

<!--  TomMenu Start -->
<style type="text/css">
a.rollover img { border-width:0px; display:block; }
a.rollover img.rollover { display:none; }
a.rollover:hover { position:relative; }
a.rollover:hover img { display:none; }
a.rollover:hover img.rollover { display:block; }

.TopMenutt, .TopMenutt a:link, .TopMenutt a:visited, .TopMenutt .a:active,.TopMenutt a:hover{
	font-weight:bold; letter-spacing:-1px
}

.leftMenutt, .leftMenutt a:link, .leftMenutt a:visited, .leftMenutt .a:active,.leftMenutt a:hover{
	font-weight:bold;
	letter-spacing:-1px;
	color:#3399cc;
	border:5px solid #E5E5E5;
	font-family:Dotum;
	font-size:16px;
	line-height:20px;
}

</style>


<table border="0" cellpadding="0" cellspacing="0" width="100%" background="/admin/images/admin_menu_bg.gif">
	<tr>
		<td width="950" height="55">
			<table border="0" cellpadding="0" cellspacing="0" width="950">
				<tr>
					<td width="15">&nbsp;</td>
					<td width="170"><a href="/admin/main.php"><img src="/admin/images/admin_logo.gif"></a></td>
					<td width="15">&nbsp;</td>
					<td valign="top" align="left" style="padding-left:50px;">

						<table border="0" cellpadding="0" cellspacing="0" class="TopMenutt">
							<tr>

<!--
																<td>
									<a href="/admin/main_product/">
										메인상품관리
									</a>
								</td>
								<td width="30" align="center"><img src="/admin/images/admin_menu_bar.gif" width="30" height="55"></td>

 -->
																<td>
									<a href="/admin/board/">
										게시판관리
									</a>
								</td>
								<td width="30" align="center"><img src="/admin/images/admin_menu_bar.gif" width="30" height="55"></td>

																<td>
									<a href="/admin/member/member_list.php"><!--  class="rollover" -->
										회원관리
									</a>
								</td>
								<td width="30" align="center"><img src="/admin/images/admin_menu_bar.gif" width="30" height="55"></td>

																							<td>
									<a href="/admin/product/product_list.php"><!--  class="rollover" -->
										제품관리
									</a>
								</td>

								<td width="30" align="center"><img src="/admin/images/admin_menu_bar.gif" width="30" height="55"></td>

																							<td>
									<a href="/admin/bc_bbs/bbs_list.php"><!--  class="rollover" -->
										게시판설정
									</a>
								</td>
								<td width="30" align="center"><img src="/admin/images/admin_menu_bar.gif" width="30" height="55"></td>


																							<td>
									<a href="/admin/popup/popup_list.php"><!--  class="rollover" -->
										팝업창관리
									</a>
								</td>
								<td width="30" align="center"><img src="/admin/images/admin_menu_bar.gif" width="30" height="55"></td>


																<td>
									<a href="/admin/counter/counter.php"><!--  class="rollover" -->
										통계관리
									</a>
								</td>

																							<td width="30" align="center"><img src="/admin/images/admin_menu_bar.gif" width="30" height="55"></td>
								<td>
									<a href="/admin/bc_site/site_config.php"><!--  class="rollover" -->
										환경설정
									</a>
								</td>
														</tr>
						</table>

					</td>
				</tr>
			</table>
		</td>
		<td>&nbsp;</td>
	</tr>
</table>

<!--  //TomMenu End -->
<!-- //Header End -->


<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="200" background="/admin/images/admin_left_bg.gif" valign="top" align="center" style="padding:20 0 20 0">

			<!--  LeftMenu Start -->



	<table cellpadding="0" cellspacing="0" width="170">
		<tr>
			<td align="center" height="50" class="leftMenutt">제품관리</td>
		</tr>
	</table>
	<table border="0" cellpadding="0" cellspacing="0" width="170">
		<tr>
			<td height="29" width="23" align="right" valign="top" style="padding-top:10px"><img src="/admin/images/admin_left_ball.gif" hspace="6"></td>
			<td valign="top" style="padding-top:7px" class="a_lm01">
				<a href="/admin/product/product_category.php">카테고리 관리</a>
			</td>
		</tr>
		<tr>
			<td colspan="2" height="1" bgcolor="#E5E5E5"></td>
		</tr>
		<tr>
			<td height="29" width="23" align="right" valign="top" style="padding-top:10px"><img src="/admin/images/admin_left_ball.gif" hspace="6"></td>
			<td valign="top" style="padding-top:7px" class="a_lm01">
				<a href="/admin/product/product_list.php">제품관리</a>
			</td>
		</tr>
		<tr>
			<td colspan="2" height="1" bgcolor="#E5E5E5"></td>
		</tr>
	</table>










			<!--  //LeftMenu End -->

		</td>
		<td width="15">&nbsp;</td>
		<td width="735" align="left" valign="top" style="padding:20 0 20 0">
			<!-- Content Start -->


<script language='JavaScript'>
<!--
// 입력시 입력값 검사하기
function ProductWriteGo()
{

	var frm = document.ProductWriteForm;

	if(!frm.pr_name.value){
		alert('제품명을 입력해 주세요');
		frm.pr_name.focus();
		return false;
	}

	if(!frm.pc_num.value){
		alert('카테고리를 선택해 주세요');
		return false;
	}


//	if(!frm.pr_content.value) {
//		alert('제품상세설명을 입력하세요!');
//		return false;
//	}

	document.getElementById("btnSubmit").disabled=true;
	return true;
}

function rows_size(idx)
{
	var f = document.ProductWriteForm;
	if(idx=="1"){
		size = f.pr_content_thum;
	}
	else if(idx=="2"){
		size = f.pr_content_thum;
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


function initOption(cnt,opt, optval)
{
	optStr = opt.split('|');
	optvalStr = optval.split('|');

	for( i=0 ; i < cnt ; i++ ){
		addOption(optStr[i], optvalStr[i]);
	}
}

function addOption(optval, optval2)
{
	var optList = document.getElementById("option_div");
	var br = document.createElement("br");
	var opt = document.createElement("input");
	var opt_label = document.createElement("span");
	var opt_value_label = document.createElement("span");
	opt.setAttribute("type", "text");
	opt.setAttribute("name", "pr_option[]");
	opt.setAttribute("id", "pr_option[]");
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
	opt_value.setAttribute("name", "pr_option_value[]");
	opt_value.setAttribute("id", "pr_option_value[]");
	if (optval2) {
		opt_value.setAttribute("value", optval2);
	}
	opt_value.setAttribute("className", "input");
	opt_value.style.width = "360px";

	opt_value_label.innerHTML ='&nbsp;&nbsp;내용 : ';
	optList.appendChild(opt_value_label);
	opt_value_label.style.fontSize = "11px";
	opt_value_label.style.fontFamily = "Dotum";
	opt_value_label.style.letterSpacing = "-1px";
	optList.appendChild(opt_value);

	optList.appendChild(br);


	optCount ++;
	return false;
}

function removeOption(cnt)
{
	try{
		var optList = document.getElementById("option_div");
		for(var i=0;i<=4;i++){
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


	// 카테고리 옵션값 가져오기
	function getCategoryOtion() {
		var pc_num = document.ProductWriteForm.pc_num.value;
		if (pc_num) {
			ProductWriteIframe.location.href = "/admin//product/product_write_catesel.php?pc_num="+pc_num;
		}
	}
	// 카테고리 리스트 팝업창
	function PopCategorySelect() {
		PopCategory = window.open('/_util/product/category.php?form_name=ProductWriteForm','PopCategory','width=400,height=400,directories=no,resizable=no,scrollbars=yes');
		PopCategory.focus();
	}

//-->
</script>
<script language="JavaScript">
//	var flen = 0;
//	function add_file(delete_code, file_name, obj_table)
//	{
//		var upload_count = 5;
//		if (upload_count && flen >= upload_count)
//		{
//			alert("이 게시판은 "+upload_count+"개 까지만 파일 업로드가 가능합니다.");
//			return;
//		}
//
//		var objTbl;
//		var objRow;
//		var objCell;
//		if (document.getElementById)
//			objTbl = document.getElementById(obj_table);
//		else
//			objTbl = document.all[obj_table];
//
//		objRow = objTbl.insertRow(objTbl.rows.length);
//		objCell = objRow.insertCell(0);
//		objCell.innerHTML = "<input type='file' class=ed size=32 name='bf_file[]' title='파일 용량 2,049,536 바이트 이하만 업로드 가능'>";
//
//		if (delete_code) {
//			objCell.innerHTML += delete_code;
//		}
//		else {
//			;
//		}
//
//		flen++;
//	}
//
//
//	function del_file()
//	{
//		// file_length 이하로는 필드가 삭제되지 않아야 합니다.
//		var file_length = 1;
//		var objTbl = document.getElementById("variableFiles");
//		if (objTbl.rows.length - 1 > file_length)
//		{
//			objTbl.deleteRow(objTbl.rows.length - 1);
//			flen--;
//		}
//	}
	</script>


<iframe id="ProductWriteIframe" name="process" src="" style="width:0;height:0;border:0"></iframe>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="/admin/images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">  제품 등록</td>
		<td width="300" align="right">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td align="right">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td bgcolor="#339933" width="150" height="22" align="center" class="a_tab_txt" valign="top" style="padding-top:3px"><a href='/admin/product/product_category_write.php'>최상위 카테고리 등록</a> </td>
								<!-- <td width="2"></td>
								<td bgcolor="#000000" width="150" height="22" align="center" class="a_tab_txt" valign="top" style="padding-top:3px"><a href='/admin/product/product_category_modify_all.php'>카테고리설정 전체변경</a> </td> -->
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
<form name="ProductWriteForm" method="post" action="/admin/product/product_write_ok.php" ENCTYPE='multipart/form-data' onSubmit="return ProductWriteGo()">
<input type="hidden" name="pr_idx" value="51">
<input type="hidden" name="pc_num_old" value="04" readonly>
<input type="hidden" name="pc_num" value="04" readonly>
<input type="hidden" name="search_pc_num" value="04" readonly>


<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="/admin/images/admin_ball.gif"> 제품 정보 입력</td>
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
		<td class="a_content"><input type="text" name="pr_name" style="width:100%" maxlength="255" class="a_input" value="소모품"></td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품카테고리</td>
		<td class="a_content">
			<input type="text" name="pc_name" value="포토프린터" readonly onclick="PopCategorySelect();" style="cursor:hand;text-align:center;">
			<!-- <input type="text" name="pc_num_location" value="" style="border:0px;width:100%" readonly> -->
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">제품출력순위</td>
		<td class="a_content">
			<input type="hidden" name="pr_sort_old" value="51" readonly>
			<input type="text" name="pr_sort" value="51" maxlength="3"  class="a_input" style="width:50px;text-align:center;">
			( 자동 계산 됩니다. )
		</td>
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
					<td width="20" height="11"><img src="/admin/images/a_btn_optadd.gif" onClick="return addOption('','');" alt="옵션 갯수 늘리기" style="cursor:hand"></td>
					<td><img src="/admin/images/a_btn_optdel.gif" onClick="return removeOption('1');" alt="옵션 갯수 줄이기" style="cursor:hand"></td>
				</tr>
			</table>
		</td>
		<td class="a_content">
			<div name="option_div" id="option_div"></div>
						<script type="text/javascript">initOption('4','색상|유형||','|||');</script>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품 간략 설명<!-- <br><a href="javascript:rows_size(1)">[ 쓰기폼 늘리기 ]</a> --></td>
		<td class="a_content">
			<input type="hidden" id="pr_content_thum" name="pr_content_thum" value="제품 간략 설명&lt;br /&gt;
1111&lt;br /&gt;
22222&lt;br /&gt;
&lt;&gt;/'&quot;'123!@#!$!@#$&amp;nbsp;^%$^&amp;%*&amp;^)(*_&amp;(*)&lt;br /&gt;
&lt;br /&gt;
????&gt;&lt;&gt;&gt;?&lt;&gt;:L&quot;&quot;L::" style="display:none" /><input type="hidden" id="pr_content_thum___Config" value="AutoDetectLanguage=true&amp;DefaultLanguage=ko&amp;SkinPath=/_util/fckeditor/editor/skins/silver/" style="display:none" /><iframe id="pr_content_thum___Frame" src="/_util/fckeditor/editor/fckeditor.html?InstanceName=pr_content_thum&amp;Toolbar=bluecarpet" width="100%" height="300" frameborder="0" scrolling="no"></iframe>		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품 설명</td>
		<td class="a_content">
			<input type="hidden" id="pr_content" name="pr_content" value="제품 설명&lt;br /&gt;
1111&lt;br /&gt;
22222&lt;br /&gt;
333333&lt;br /&gt;
&lt;&gt;/'&quot;'123!@#!$!@#$&amp;nbsp;^%$^&amp;%*&amp;^)(*_&amp;(*)&lt;br /&gt;
&lt;br /&gt;
????&gt;&lt;&gt;&gt;?&lt;&gt;:L&quot;&quot;L::" style="display:none" /><input type="hidden" id="pr_content___Config" value="AutoDetectLanguage=true&amp;DefaultLanguage=ko&amp;SkinPath=/_util/fckeditor/editor/skins/silver/" style="display:none" /><iframe id="pr_content___Frame" src="/_util/fckeditor/editor/fckeditor.html?InstanceName=pr_content&amp;Toolbar=bluecarpet" width="100%" height="500" frameborder="0" scrolling="no"></iframe>		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">제품 사양</td>
		<td class="a_content">
			<input type="hidden" id="pr_content2" name="pr_content2" value="제품 사양&lt;br /&gt;
1111&lt;br /&gt;
22222&lt;br /&gt;
333333&lt;br /&gt;
&lt;&gt;/'&quot;'123!@#!$!@#$&amp;nbsp;^%$^&amp;%*&amp;^)(*_&amp;(*)&lt;br /&gt;
&lt;br /&gt;
????&gt;&lt;&gt;&gt;?&lt;&gt;:L&quot;&quot;L::" style="display:none" /><input type="hidden" id="pr_content2___Config" value="AutoDetectLanguage=true&amp;DefaultLanguage=ko&amp;SkinPath=/_util/fckeditor/editor/skins/silver/" style="display:none" /><iframe id="pr_content2___Frame" src="/_util/fckeditor/editor/fckeditor.html?InstanceName=pr_content2&amp;Toolbar=bluecarpet" width="100%" height="500" frameborder="0" scrolling="no"></iframe>		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">사용사례</td>
		<td class="a_content">
			<input type="hidden" id="pr_content3" name="pr_content3" value="사용사례&lt;br /&gt;
1111&lt;br /&gt;
22222&lt;br /&gt;
333333&lt;br /&gt;
&lt;&gt;/'&quot;'123!@#!$!@#$&amp;nbsp;^%$^&amp;%*&amp;^)(*_&amp;(*)&lt;br /&gt;
&lt;br /&gt;
????&gt;&lt;&gt;&gt;?&lt;&gt;:L&quot;&quot;L::" style="display:none" /><input type="hidden" id="pr_content3___Config" value="AutoDetectLanguage=true&amp;DefaultLanguage=ko&amp;SkinPath=/_util/fckeditor/editor/skins/silver/" style="display:none" /><iframe id="pr_content3___Frame" src="/_util/fckeditor/editor/fckeditor.html?InstanceName=pr_content3&amp;Toolbar=bluecarpet" width="100%" height="500" frameborder="0" scrolling="no"></iframe>		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>


	<tr>
		<td class="a_txt">
			제품리스트 이미지
		</td>
		<td class="a_content">
						<input type="file" name="pr_img_thum" style="width:50%" maxlength="255" class="a_input">
						<img id="pr_img_thum" width="0" height="0">
			<span style="width:100%;">추천 이미지 사이즈 : ( 가로 : 130px , 세로 : 130px )</span>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>


		<tr>
		<td class="a_txt">
			제품상세 이미지
			<span onclick="add_file('Tblpr_img', 5, '<input type=file name=pr_img[] id=pr_img style=width:50% maxlength=255 class=a_input>');" style='cursor:pointer; font-family:tahoma; font-size:12pt;'>+</span>
			<span onclick="del_file('Tblpr_img');" style='cursor:pointer; font-family:tahoma; font-size:12pt;'>-</span>
		</td>
		<td class="a_content">
			<table id="Tblpr_img" width="100%" cellpadding=0 cellspacing=0></table>
			1111						<script>add_file('Tblpr_img', 5, '<input type="file" name="pr_img[]" id="pr_img" style="width:50%" maxlength="255" class="a_input" disabled> 삭제 <input type="checkbox" name="pr_img_del[]" value="5" onclick="UseFile(this, \'pr_img\', \'\')"> <BR>등록파일 : nce20080709010140.bmp (4 KB)');</script>
												<script>add_file('Tblpr_img', 5, '<input type="file" name="pr_img[]" id="pr_img" style="width:50%" maxlength="255" class="a_input" disabled> 삭제 <input type="checkbox" name="pr_img_del[]" value="4" onclick="UseFile(this, \'pr_img\', \'\')"> <BR>등록파일 : nce20071116033824.bmp (4 KB)');</script>
												<script>add_file('Tblpr_img', 5, '<input type="file" name="pr_img[]" id="pr_img" style="width:50%" maxlength="255" class="a_input" disabled> 삭제 <input type="checkbox" name="pr_img_del[]" value="3" onclick="UseFile(this, \'pr_img\', \'\')"> <BR>등록파일 : nce20071116033819.bmp (4 KB)');</script>

			<img id="pr_img" width="0" height="0">
			<div style="width:100%;">추천 이미지 사이즈 : ( 가로 : 300px , 세로 : 300px )</div>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>







</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="80" align="center">
			<input type="image" name="btnSubmit" id="btnSubmit" src="/admin/images/a_btn_submit.gif" hspace="4">
			<img src="/admin/images/a_btn_cancle.gif" width="76" height="28" onClick="javascript:document.location.href='/admin/product/product_list.php?search_pc_num=04'" style="cursor:hand">
		</td>
	</tr>
</table>
</form>
<script>
	function setFileDisabled(obj, objfile, num) {
		objfile = document.all[objfile];

		if (obj.checked == true) {
			objfile[num].disabled = false;
		}
		else {
			objfile[num].disabled = true;
		}
	}
</script>

			<!-- //Content End -->
		</td>

		<td valign="top">

			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="padding:5 0 10 5px;">
						<div style="height:600px; overflow-y:scroll; overflow-x:hidden'">

<script>
	var old_menu = '';

	function menuclick(pc_num) {

		var menu_img = document.all("obj_"+pc_num);
		var submenu = document.all("obj_"+pc_num+"_sub");
		var space = (pc_num.length/2-1);

//		alert(pc_num);
//		alert(submenu);
//		alert(submenu.outerHTML);
//		alert(space);

		if( submenu.style.display == 'block' ) {
			submenu.style.display = 'none';
			menu_img.src = '/product/images/cate_plus.gif';
		}
		else{
			submenu.style.display = 'block';
			menu_img.src = '/product/images/cate_minus.gif';
			var str = submenu.outerHTML;
			if(str.length < 100) {
//				var space_str = '';
//				for(i = 0;i < space;i++) {
//					space_str += '&nbsp;&nbsp;';
//				}
//				str = space_str + "<img src='/product/images/loading.gif' border='0'>";
//				submenu.innerHTML = str;
				Get_Category(pc_num);
			}
		}
	}

	function menuHandler(pc_num, pc_name) {
//		var ff = eval("opener.document.");
//		alert(pc_num);
//		alert(pc_name);
//		ff.pc_num.value = pc_num;
//		ff.pc_name.value = pc_name;
//		opener.getCategoryOtion();
//		self.close();
		location.href = "/admin/product/product_list.php?search_pc_num="+pc_num;
	}

	function Get_Category(pc_num) {
		GetCategory.src = '/admin/product/category_proc.php?pc_num=' + pc_num;
	}
</script>
<script language='javascript' id='GetCategory'></script>

<img src='/product/images/folder_bullet.gif' border=0> <a href="javascript:menuHandler('','')">제품관리</a><BR>


			<a href="javascript:menuclick('01');"><img src='/product/images/cate_plus.gif' border='0' style='cursor:hand;' id='obj_01'></a><img src='/product/images/folder_bullet.gif' border='0'>

		<a href="javascript:menuHandler('01','스캐너');">스캐너</a><br>
		<span id="obj_01_sub" style="display:none"></span>


			<a href="javascript:menuclick('02');"><img src='/product/images/cate_plus.gif' border='0' style='cursor:hand;' id='obj_02'></a><img src='/product/images/folder_bullet.gif' border='0'>

		<a href="javascript:menuHandler('02','네트워크카메라');">네트워크카메라</a><br>
		<span id="obj_02_sub" style="display:none"></span>


			<a href="javascript:menuclick('03');"><img src='/product/images/cate_plus.gif' border='0' style='cursor:hand;' id='obj_03'></a><img src='/product/images/folder_bullet.gif' border='0'>

		<a href="javascript:menuHandler('03','산업용노트북');">산업용노트북</a><br>
		<span id="obj_03_sub" style="display:none"></span>


			<img src='/product/images/cate_default.gif'><img src='/product/images/folder_bullet.gif' border='0'>

		<a href="javascript:menuHandler('04','포토프린터');">포토프린터</a><br>
		<span id="obj_04_sub" style="display:none"></span>

						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2" height="1" bgcolor="#E5E5E5"></td>
				</tr>
			</table>


		</td>
	</tr>
</table>

<!-- Footer Start -->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td colspan="2" height="2" bgcolor="#CCCCCC"></td>
	</tr>
	<tr>
		<td width="300">
			<table border="0" width="100%" >
				<tr>
					<td colspan="2" width="100%">
						<a href="http://www.bluecarpet.co.kr" target="_blank"><img  src="/admin/images/admin_main_btn_04.gif"  hspace="4"></a>
						<a href="http://pms.bluecarpet.co.kr" target="_blank"><img src="/admin/images/admin_main_btn_05.gif"  hspace="4"></a>
					</td>
				</tr>
			</table>
		</td>
		<td width="650" height="32" align="right" class="copyright">COPYRIGHT 2009 BLUECARPET. ALL RIGHTS RESERVED.</td>
		<td align="right" valign="bottom"><a href="#"><img src="/admin/images/blank.gif" border="0" width="3" height="3"></a></td>
	</tr>
</table>
<!-- Footer End -->
</body>
</html>


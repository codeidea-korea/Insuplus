

	// 숫자만 입력
	function OnlyNumber() {
		var lkeycode = window.event.keyCode;
		var sOrg = String.fromCharCode(lkeycode);

		if(!sOrg.match(/^[\d|\.]/)) {
			window.event.keyCode = 0;
		}
		// if  (lkeycode < 48 || lkeycode > 57)
		// window.event.keyCode = 0;
	}

	// 포커스 이동
	function NextFocus(ThisValue, NextOBJ, length) {
		if (ThisValue.length == length) {
			NextOBJ.focus();
		}
		else {
			return;
		}
	}

	// 이미지 원문보기
	function showPicture(src) {
		var imgObj = new Image();
		imgObj.src = src;
		var wopt = "scrollbars=no,status=no,resizable=no";
		wopt += ",width=" + imgObj.width;
		wopt += ",height=" + imgObj.height;
		var wbody = "<head><title>사진 보기</title>";
		wbody += "<script language='javascript'>";
		wbody += "function finalResize(){";
		wbody += "  var oBody=document.body;";
		wbody += "  var oImg=document.images[0];";
		wbody += "  var xdiff=oImg.width-oBody.clientWidth+10;";
		wbody += "  var ydiff=oImg.height-oBody.clientHeight+10;";
		wbody += "  window.resizeBy(xdiff,ydiff);";
		wbody += "}";
		wbody += "</"+"script>";
		wbody += "</head>";
		wbody += "<body onLoad='finalResize()' style='margin:0'>";
		wbody += "<table border='0' cellpadding='0' cellspacing='0' width='100%' height='100%'>";
		wbody += "<tr>";
		wbody += "	<td valign='middle' align='center'>";
		wbody += "<a href='javascript:window.close()'><img src='" + src + "' border=0></a>";
		wbody += "	</td>";
		wbody += "</tr>";
		wbody += "</table>";
		wbody += "</body>";
		winResult = window.open("about:blank","",wopt);
		winResult.document.open("text/html", "replace");
		winResult.document.write(wbody);
		winResult.document.close();
		return;
	}


	function show_tab(divid,dcnt,idx) {
		for(i=1;i<= dcnt; i++) {
			if(idx==i) {
				document.getElementById(divid + i).style.display = "block";
			} else {
				document.getElementById(divid + i).style.display = "none";
			}
		}
	}


	/********** 회원 관련 페이지 이동 함수 Start ***********/

	function UserLoginGo() {
		location.href = GlobalLoginURL;
	}

	function UserLogoutGo() {
		location.href = GlobalLogoutURL;
	}

	function UserJoinGo() {
		location.href = GlobalJoinURL;
	}

	function UserModifyGo() {
		location.href = GlobalModifyURL;
	}

	function UserFindIDGo() {
		UserFind = window.open( GlobalFindIDURL , 'idpwFind', 'width=340,height=317,left=350,top=320,resizable=0,scrollbars=0' );
		UserFind.focus();
		//location.href = GlobalFindIDURL;
	}

	function UserFindPWGo() {
		UserFind = window.open( GlobalFindPWURL , 'idpwFind', 'width=340,height=317,left=350,top=320,resizable=0,scrollbars=0' );
		UserFind.focus();
		//location.href = GlobalFindPWURL;
	}

	function UserDropGo() {
		UserDrop = window.open( GlobalDropURL , 'UserDrop', 'width=340,height=339,left=350,top=320,resizable=0,scrollbars=0' );
		UserDrop.focus();
	}

	function UserAgreeGo() {
		location.href = GlobalAgreeURL;
//		UserAgree = window.open( UserAgreeURL , 'UserAgree', 'width=454,height=416,left=0,top=0,resizable=0,scrollbars=0' );
//		UserAgree.focus();
	}

	function Warning_email() {
		var GlobalAgreeListURL = "/member/Warning_email.php";
		location.href = GlobalAgreeListURL;
	}

	function UserAddFavorite(url, site_name) {
		window.external.AddFavorite(url, site_name);
	}

	/********** 회원 관련 페이지 이동 함수 End ***********/


	/* 비공개 상담 */
	function ContactGo() {
		pop_contact = window.open( "/sub/contact.php" , 'pop_contact', 'width=580,height=510,resizable=0,scrollbars=0' );
		pop_contact.focus();
	}

	function setPng24(obj) {
		obj.width=obj.height=1;
		obj.className=obj.className.replace(/\bpng24\b/i,'');
		obj.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+ obj.src+"',sizingMethod='image');"
		obj.src='';
		return '';
	}

	function add_file( ObjFileTable, upload_count, delete_code )
	{
		//var upload_count = 2;
		var objTbl;
		var objRow;
		var objCell;
		if (document.getElementById)
			objTbl = document.getElementById(ObjFileTable);
		else
			objTbl = document.all[ObjFileTable];

		if (upload_count && objTbl.rows.length >= upload_count) {
			alert("이 게시판은 "+upload_count+"개 까지만 파일 업로드가 가능합니다.");
			return;
		}

		if (delete_code) {
			objRow = objTbl.insertRow(objTbl.rows.length);
			objCell = objRow.insertCell(0);
			objCell.innerHTML += delete_code;
		}
	}

	function del_file( ObjFileTable )
	{
		// file_length 이하로는 필드가 삭제되지 않아야 합니다.
		var file_length = 1;
		var objTbl;

		if (document.getElementById)
			objTbl = document.getElementById(ObjFileTable);
		else
			objTbl = document.all[ObjFileTable];

		if (objTbl.rows.length > file_length) {
			objTbl.deleteRow(objTbl.rows.length - 1);
		}
		else {

		}
	}


	function UseFile(Obj, ObjFile, idx) {

		TargObj = document.getElementsByName(ObjFile);

		if (Obj.checked == true) {
			TargObj[idx].disabled = false;
		}
		else {
			TargObj[idx].disabled = true;
		}
	}


	function goProductPds(pc_num, pr_name) {
		location.href = "/html/04_data/data_01.php?search_ext10="+pc_num+"&search_ext9="+pr_name;
	}
	function goProductQna(pr_idx) {
		location.href = "/html/03_customer/customer_02.php?search="+pr_idx;
	}

//동영상 보기
function on_vodmain(file){
 document.write('<object Id="hiplayer" classid="CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95" codebase="http://activex.microsoft.com/activex/controls/mplayer/en/nsmp2inf.cab#Version=6,4,5,715" width="357" height=268 id="MediaPlayer" type="application/x-oleobject" standby="Loading Microsoft Windows Media Player components...">');
 document.write('<param name="AudioStream" value="true">');
 document.write('<param name="AutoSize" value="false">');
 document.write('<param name="AutoStart" value="true">');
 document.write('<param name="AnimationAtStart" value="false">');
 document.write('<param name="AllowScan" value="-1">');
 document.write('<param name="AllowChangeDisplaySize" value="true">');
 document.write('<param name="AutoRewind" value="false">');
 document.write('<param name="Balance" value="0">');
 document.write('<param name="BufferingTime" value="0">');
 document.write('<param name="ClickToPlay" value="true">');
 document.write('<param name="CursorType" value="true">');
 document.write('<param name="CurrentPosition" value="true">');
 document.write('<param name="CurrentMarker" value="0">');
 document.write('<param name="DisplayBackColor" value="0">');
 document.write('<param name="DisplayForeColor" value="16777215">');
 document.write('<param name="DisplayMode" value="0">');
 document.write('<param name="DisplaySize" value="0">');
 document.write('<param name="Enabled" value="true">');
 document.write('<param name="EnablePositionControls" value="false">');
 document.write('<param name="EnableFullScreenControls" value="false">');
 document.write('<param name="EnableTracker" value="false">');
 document.write('<param name="InvokeURLs" value="false">');
 document.write('<param name="Language" value="-1">');
 document.write('<param name="Loop" value="true">');
 document.write('<param name="PlayCount" value="5">');
 document.write('<param name="PreviewMode" value="false">');
 document.write('<param name="Rate" value="1">');
 document.write('<param name="SelectionStart" value="false">');
 document.write('<param name="SelectionEnd" value="false">');
 document.write('<param name="SendOpenStateChangeEvents" value="-1">');
 document.write('<param name="SendWarningEvents" value="-1">');
 document.write('<param name="SendErrorEvents" value="-1">');
 document.write('<param name="SendKeyboardEvents" value="0">');
 document.write('<param name="SendMouseMoveEvents" value="false">');
 document.write('<param NAME="SendMouseClickEvents" VALUE="True">');
 document.write('<param name="SendPlayStateChangeEvents" value="true">');
 document.write('<param name="ShowCaptioning" value="false">');
 document.write('<param name="ShowControls" value="true">');
 document.write('<param name="ShowAudioControls" value="true">');
 document.write('<param name="ShowDisplay" value="false">');
 document.write('<param name="ShowGotoBar" value="false">');
 document.write('<param name="ShowPositionControls" value="false">');
 document.write('<param name="ShowStatusBar" value="false">');
 document.write('<param name="ShowTracker" value="false">');
 document.write('<param name="TransparentAtStart" value="true">');
 document.write('<param name="VideoBorderWidth" value="0">');
 document.write('<param name="VideoBorderColor" value="0">');
 document.write('<param name="VideoBorder3D" value="0">');
 document.write('<param name="Volume" value="100">');
 document.write('<param name="WindowlessVideo" value="false">');
 document.write('<param NAME="EnableContextMenu" VALUE="true">');
 document.write('</object>');
}
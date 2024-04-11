<script language="Javascript" src="/_util/webnote/webnote.js"></script>
<?
//		$content = str_replace("\r","<br/>",$content);
//		$content = str_replace("\n","<br/>",$content);
?>
<script language="Javascript">
//debuging message set
webnote.setDebug();

//webnote config
webnote.setConfig({
	auto_start:			true,									//페이지로딩시 페이지에 웹노트 에디터를 자동으로 생성할것인지(true: 자동생성, false: 생성안함)
	lang:					"ko",									//언어셋(lang 디렉토리내에 언어셋.txt 파일이 있어야 함(ex: ko.txt)
	base_dir:			"/_util/webnote",								//웹노트 설치디렉토리를 직접 지정
	css_url:				"/_util/webnote/webnote.css",					//기본 css 파일을 직접 지정
	icon_dir:				"/_util/webnote/icon",						//기본 아이콘 디렉토리를 직접 지정
	emoticon_dir:		"/_util/webnote/emoticon",					//기본 이모티콘 디렉토리를 직접 지정
	attach_proc:		"/_util/webnote/webnote_attach.php",			//에디터에 이미지 즉시 업로드를 처리하는 서버스크립트를 직접 지정
	delete_proc: 		"/_util/webnote/webnote_attach.php",			//에디터에 즉시 업로드된 이미지 삭제를 처리하는 서버스크립트를 직접 지정(attach_proc 과 같을경우 설정 불필요)
	img_center:			"/_util/webnote/webnote_image_center.php",	//이미지센터를 다른 파일로 사용할 때
	use_blind:			true,									//팝업메뉴 출력 시 팝업 외에 다른 곳을 클릭 시 팝업이 닫히도록 반투명 배경 스크린 사용여부(true:사용(기본), false: 미사용)
	allow_dndupload:	true,									//드래그&드롭을 통한 이미지 파일 업로드 허용 여부
	allow_dndresize:	true,									//드래그&드롭을 통한 에디터 사이즈(높이) 조절 허용 여부
	fonts:					["굴림체","궁서체","돋움체","맑은고딕","나눔고딕"],					//선택할 수 있는 폰트종류를 직접 정의
	fontsizes:			["9pt","10pt","11pt","12pt","13pt","14pt","15pt","16pt","17pt","18pt","20pt","24pt","30pt"],							//선택할 수 있는 폰트사이즈를 직접 정의(단위포함)
	lineheights:			["120%","150%","180%"],					//선택할 수 있는 줄간격을 직접 정의(단위포함)
	emoticons:			["smile","cry"],						//선택할 수 있는 이모티콘들을 직접 정의(png 확장자파일만 가능하며, 확장자를 제외한 파일명만 나열)
	specialchars:		["§","☆"],								//선택할 수 있는 특수문자를 직접 정의
	code_highlight:		true
});

//webnote user tools set
webnote.setUserTools([
    {
		name: "brick",
		text: "내아이콘1",
		content: "<div class='webnote_popup_container_top'><textarea name='mycontents' id='mycontents' style='width:98%;height:100px'></textarea></div><div class='webnote_popup_container_bottom'><input type='button' class='webnote_btn_center' value='본문에삽입' onClick='insertMyContents()'></div>",
		popup_width: 300,
		callback: brink_func
    },
    {
		name: "bricks",
		text: "내아이콘2",
		content: "<div class='webnote_popup_container_top' id='mycontents2'></div><div class='webnote_popup_container_bottom'><input type='button' class='webnote_btn_center' value='닫기' onClick='myclosepop()'></div>",
		popup_width: 400,
		callback: function() {
			brinks_func();
		}
    }
]);

//webnote create callback set
webnote.onCreateCB = function() {
	myCallBack();
}

function myCallBack() {
	webnote.html('<?=stripslashes(str_replace("\r\n", "" , $content))?>');
}
function insertMyContents() {
	webnote.insertHTML($_("mycontents").value);
	webnote.closePopup();
}
function brink_func() {
    $_("mycontents").focus();
}
function brinks_func() {
	var html = webnote.getSelectHtml();
	if(html == "") html = "선택영역 없음";
	$_("mycontents2").innerHTML = html;
}
function myclosepop() {
	webnote.closePopup();
}

function checkForm(form) {

	if(form.subject.value == "") {
		alert("제목을 입력해주세요");
		form.subject.focus();
		return false;
	}
	if(form.contents1.value == "") {
		alert("내용을 입력해주세요");
		webnote.focusWebNote("contents1")		//에디터에 포커스를 주기위한 webnote 내장함수
		//focusWebNote("contents1");
		return false;
	}

	return true;
}

function empty() {
	webnote.empty();
}
function append() {
	webnote.append($_("append_data").value);
}
function prepend() {
	webnote.prepend($_("append_data").value);
}
function setContents() {
	webnote.html($_("append_data").value);
}
var editor_cnt = 1;
function createEditor() {

	editor_cnt++;
	var edtBox = document.createElement("div");
	edtBox.style.marginTop = "10px";
	edtBox.innerHTML = "<textarea name=\"contents"+editor_cnt+"\" editor=\"webnote\" style=\"height:150px;width:700px\"></textarea>";
	document.getElementById("dEditor").appendChild(edtBox);

//	innerHTML += "<div style='margin-top:10px'><textarea name=\"contents"+editor_cnt+"\" editor=\"webnote\" style=\"height:150px;width:700px\"></textarea></div>";

	//콜백 재정의
	webnote.onCreateCB = function() {
		myCallBack2();
	}

	webnote.initialize();
}
function myCallBack2() {
	webnote.html('<?=stripslashes(str_replace("\r\n", "" , $content))?>');
}
function createEditor2() {
	webnote.initialize();
}
function viewUploadImageList() {
	var list = webnote.getUploadImages();
	var data = "";
	for(var i = 0; i < list.length; i++) {
		data += " ["+list[i].org_name +"]"+list[i].url+"\n";
	}
	alert(data);

}
function viewContents() {
	alert(webnote.html());
}
function viewContents2() {
	alert(webnote.text());
}
function focusEditor2() {
	webnote.focusWebNote("contents2");
}
</script>
<textarea name="content" editor="webnote" style="height:250px;width:700px" tools="deny:"></textarea>
<div id="dEditor"></div>
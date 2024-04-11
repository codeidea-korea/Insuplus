$(document).ready(function(){
	$(".datepicker").datepicker({
			showOn: 'button',
            buttonText: "Choose Date",
            dateFormat: 'yy-mm-dd'
		}
	);//달력 호출
	$("img.ui-datepicker-trigger").attr("style","margin-left:5px; vertical-align:middle; cursor:pointer;"); //이미지버튼 style적용
	$("#ui-datepicker-div").hide(); //자동으로 생성되는 div객체 숨김
	
	// 숫자만 입력 가능하도록 처리
	$(document).on("keyup", ".numberonly", function () { 
		$(this).val($(this).val().replace(/[^0-9.]/gi, "")); 
	});
})

function openPopup(w,h,url) { //팝업 센터 오픈
    var _width = w;
    var _height = h; 
    var _left = Math.ceil(( window.screen.width - _width )/2);
    var _top = Math.ceil(( window.screen.height - _height )/2);
    
    window.open(url, 'popup', 'width='+ _width +', height='+ _height +', left=' + _left + ', top='+ _top );
}


// 진료 스케쥴 입력
function OnWrite(year,month,day,mode,name1,name2,name3,doctor,area_code){
	if(name1==""){name1="";}
	if(name2==""){name2="";}
	if(name3==""){name3="";}
    window.open('reserve_calendar_write.php?year='+year+'&month='+month+'&day='+day+'&mode='+mode+'&name1='+name1+'&name2='+name2+'&name3='+name3+'&doctor='+doctor+'&area_code='+area_code,'','width=300,height=320,left=350,top=320,resizable=0,scrollbars=auto')
}

//진료 스케쥴 삭제
function OnDelete(idx){
	window.open("reserve_calendar_delete.php?idx="+idx);
}

//원장님 변경
function chg_doctor(idx,area_code){
	document.location.href="reserve_calendar.php?doctor="+idx+"&area_code="+area_code;
}
function Calendar(targetObj,calidx,objDate) {    	
    var now = objDate.split("-");
	document.getElementById(calidx).style.display = (document.getElementById(calidx).style.display == "block") ? "none" : "block";
	if (now.length == 3) {														// 정확한지 검사
		Show_cal(now[0],now[1],now[2],calidx,targetObj);											// 넘어온 값을 년월일로 분리
	} else {
		now = new Date();
		Show_cal(now.getFullYear(), now.getMonth()+1, now.getDate(),calidx,targetObj);			// 현재 년/월/일을 설정하여 넘김.
	}
}
	
function doClick(calidx,targetObj) {	// 날자를 선택하였을 경우
	cal_Day = window.event.srcElement.title;    
	if (cal_Day.length > 7) {
		document.getElementById(targetObj).value=cal_Day;
	}
	document.getElementById(calidx).style.display='none';												// 화면에서 지움
}

function get_Yearinfo(year,month,day,calidx,targetObj) {											// 년 정보를 콤보 박스로 표시
	var min = parseInt(year) - 10;
	var max = parseInt(year);
	var i = new Number();
	var str = new String();
	
	str = "<select onChange=\"Show_cal(this.value,"+month+","+day+",'"+calidx+"','"+targetObj+"');\" class=select>";
	for (i=min; i<=max; i++) {
		if (i == parseInt(year)) {
			str += "<option value="+i+" selected>"+i+"</option>";
		} else {
			str += "<option value="+i+">"+i+"</option>";
		}
	}
	str += "</select>";
	return str;
}

function get_Monthinfo(year,month,day,calidx,targetObj) {										// 월 정보를 콤보 박스로 표시
	var i = new Number();
	var str = new String();
	
	str = "<select onChange=\"Show_cal("+year+",this.value,"+day+",'"+calidx+"','"+targetObj+"');\" class=select>";    
	for (i=1; i<=12; i++) {
		if (i == parseInt(month)) {
			str += "<option value="+i+" selected>"+i+"</option>";
		} else {
			str += "<option value="+i+">"+i+"</option>";
		}
	}
	str += "</select>";
	return str;
}

function day2(d) {																// 2자리 숫자료 변경
	var str = new String();	
	if (parseInt(d) < 10) {
		str = "0" + parseInt(d);
	} else {
		str = "" + parseInt(d);
	}
	return str;
}

function Show_cal(sYear, sMonth, sDay, calidx, targetObj) {    
	var Months_day = new Array(0,31,28,31,30,31,30,31,31,30,31,30,31)
	var Weekday_name = new Array("일", "월", "화", "수", "목", "금", "토");
	var intThisYear = new Number(), intThisMonth = new Number(), intThisDay = new Number();    
	document.getElementById(calidx).innerHTML = "";

	datToday = new Date();													// 현재 날자 설정
	
	intThisYear = parseInt(sYear);
	intThisMonth = parseInt(sMonth);
	intThisDay = parseInt(sDay);
	
	if (intThisYear == 0) intThisYear = datToday.getFullYear();				// 값이 없을 경우
	if (intThisMonth == 0) intThisMonth = parseInt(datToday.getMonth())+1;	// 월 값은 실제값 보다 -1 한 값이 돼돌려 진다.
	if (intThisDay == 0) intThisDay = datToday.getDate();
	
	switch(intThisMonth) {
		case 1:
				intPrevYear = intThisYear -1;
				intPrevMonth = 12;
				intNextYear = intThisYear;
				intNextMonth = 2;
				break;
		case 12:
				intPrevYear = intThisYear;
				intPrevMonth = 11;
				intNextYear = intThisYear + 1;
				intNextMonth = 1;
				break;
		default:
				intPrevYear = intThisYear;
				intPrevMonth = parseInt(intThisMonth) - 1;
				intNextYear = intThisYear;
				intNextMonth = parseInt(intThisMonth) + 1;
				break;
	}

	NowThisYear = datToday.getFullYear();										// 현재 년
	NowThisMonth = datToday.getMonth()+1;										// 현재 월
	NowThisDay = datToday.getDate();											// 현재 일
	
	datFirstDay = new Date(intThisYear, intThisMonth-1, 1);						// 현재 달의 1일로 날자 객체 생성(월은 0부터 11까지의 정수(1월부터 12월))
	intFirstWeekday = datFirstDay.getDay();										// 현재 달 1일의 요일을 구함 (0:일요일, 1:월요일)
	
	intSecondWeekday = intFirstWeekday;
	intThirdWeekday = intFirstWeekday;
	
	datThisDay = new Date(intThisYear, intThisMonth, intThisDay);				// 넘어온 값의 날자 생성
	intThisWeekday = datThisDay.getDay();										// 넘어온 날자의 주 요일

	varThisWeekday = Weekday_name[intThisWeekday];								// 현재 요일 저장
	
	intPrintDay = 1																// 달의 시작 일자
	secondPrintDay = 1
	thirdPrintDay = 1
	
	Stop_Flag = 0
	
	if ((intThisYear % 4)==0) {													// 4년마다 1번이면 (사로나누어 떨어지면)
		if ((intThisYear % 100) == 0) {
			if ((intThisYear % 400) == 0) {
				Months_day[2] = 29;
			}
		} else {
			Months_day[2] = 29;
		}
	}
	intLastDay = Months_day[intThisMonth];										// 마지막 일자 구함
	Stop_flag = 0
	
    //calidx, targetObj

	Cal_HTML = "<table width=100% border=0 cellpadding=0 cellspacing=0 style=\"font-size:8pt;font-family:Tahoma;background-color:#FFFFFF\">"
			+ "<tr align=center><td colspan=7 nowrap=nowrap align=center><span title=\"이전달\" style=\"cursor:hand;\" onClick=\"Show_cal("+intPrevYear+","+intPrevMonth+",1,'"+calidx+"','"+targetObj+"')\"><font color=#999999>◀</font></span> "
			+ "<b style=\"color:#333333\">"+get_Yearinfo(intThisYear,intThisMonth,intThisDay,calidx,targetObj)+"년"+get_Monthinfo(intThisYear,intThisMonth,intThisDay,calidx,targetObj)+"월</B>"
			+ " <span title=\"다음달\" style=\"cursor:hand;\" onClick=\"Show_cal("+intNextYear+","+intNextMonth+",1,'"+calidx+"','"+targetObj+"');\"><font color=#999999>▶</font></span></td></tr>"
            + "<tr><td colspan=7 height=2></td></tr>"
			+ "<tr align=center bgcolor=#999999><td class=a_cal_tit valign=top>일</td><td class=a_cal_tit valign=top>월</td><td class=a_cal_tit valign=top>화</td><td class=a_cal_tit valign=top>수</td><td class=a_cal_tit valign=top>목</td><td class=a_cal_tit valign=top>금</td><td class=a_cal_tit valign=top>토</td></tr>";
			
	for (intLoopWeek=1; intLoopWeek < 7; intLoopWeek++) {						// 주단위 루프 시작, 최대 6주
		Cal_HTML += "<tr align=right bgcolor=#FFFFFF>"
		for (intLoopDay=1; intLoopDay <= 7; intLoopDay++) {						// 요일단위 루프 시작, 일요일 부터
			if (intThirdWeekday > 0) {											// 첫주 시작일이 1보다 크면
				Cal_HTML += "<td onClick=doClick('"+calidx+"','"+targetObj+"');>";
				intThirdWeekday--;
			} else {
				if (thirdPrintDay > intLastDay) {								// 입력 날짝 월말보다 크다면
					Cal_HTML += "<td onClick=doClick('"+calidx+"','"+targetObj+"');>";
				} else {														// 입력날짜가 현재월에 해당 되면
					Cal_HTML += "<td onClick=doClick('"+calidx+"','"+targetObj+"'); title="+intThisYear+"-"+day2(intThisMonth).toString()+"-"+day2(thirdPrintDay).toString()+"  onMouseOver=\"this.style.background=\'#E5E5E5\'\" onMouseOut=\"this.style.background=\'#FFFFFF\'\" style=\"cursor:Hand;padding-right:5px;"
					if (intThisYear == NowThisYear && intThisMonth==NowThisMonth && thirdPrintDay==intThisDay) {
						Cal_HTML += "background-color:#E5E5E5;";
					}
					
					switch(intLoopDay) {
						case 1:													// 일요일이면 빨간 색으로
							Cal_HTML += "color:#FF3300;"
							break;
						case 7:
							Cal_HTML += "color:#0066FF;"
							break;
						default:
							Cal_HTML += "color:#666666;"
							break;
					}
					
					Cal_HTML += "\" class=a_cal_day>"+thirdPrintDay;
					
				}
				thirdPrintDay++;
				
				if (thirdPrintDay > intLastDay) {								// 만약 날짜 값이 월말 값보다 크면 루프문 탈출
					Stop_Flag = 1;
				}
			}
			Cal_HTML += "</td>";
		}
		Cal_HTML += "</tr>";
		if (Stop_Flag==1) break;
	}
	Cal_HTML += "</table>";    
	document.getElementById(calidx).innerHTML = Cal_HTML;    
}
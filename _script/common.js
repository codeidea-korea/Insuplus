	/*
	 * String 오브젝트에 trim 메소드가 없으면 추가합니다.
	 * 이 메소드는 문자열의 공백을 삭제해 줍니다.
	*/
	if(!String.prototype.trim) {
		String.prototype.trim = function()
		{
			return this.replace(/(^\s*)|(\s*$)/g, "");
		}
	}

	/*
	 * 문서에 이벤트를 추가합니다.
	 * addEvent(해당 객체, 이벤트명, 함수명, cap);로 사용합니다.
	*/
	function addEvent(e,evt,func,cap) {
		if(!(e = getObj(e))) return;
		evt = evt.toLowerCase();
		var eh = 'e.on'+evt+'=func';
		if(e.addEventListener) e.addEventListener(evt,func,cap);
		else if(e.attachEvent) e.attachEvent('on'+evt,func);
		else eval(eh);
	}

	/*
	 * 문서에 정의된 이벤트를 삭제합니다.
	 * removeEvent(해당 객체, 이벤트명, 함수명, cap);로 사용합니다.
	*/
	function removeEvent(e,evt,func,cap) {
		if(!(e = getObj(e))) return;
		evt = evt.toLowerCase();
		var eh = 'e.on'+evt+'=null';
		if(e.removeEventListener) e.removeEventListener(evt,func,cap);
		else if(e.detachEvent) e.detachEvent('on'+evt,func);
		else eval(eh);
	}

	/*
	 * 변수가 정의 되었는지 확인합니다.
	 * isDefined(변수명1,변수명2,변수명3,...) 형식으로 사용합니다.
	*/
	function isDefined() {
		for(var i=0;i<arguments.length;++i) if(typeof(arguments[i])=='undefined') return false;
			return true;
	}

	/*
	 * 변수가 문자열 형식인지 또는 문자열을 확인합니다.
	 * isString(변수명1,변수명2,변수명3,문자열,...) 형식으로 사용합니다.
	*/
	function isString() {
		for(var i=0;i<arguments.length;++i) if(typeof(arguments[i])!='string') return false;
			return true;
	}

	/*
	 * 변수가 숫자 형식인지 또는 숫자를 확인합니다.
	 * isNumber(변수명1,변수명2,변수명3,숫자,...) 형식으로 사용합니다.
	*/
	function isNumber() {
		for(var i=0; i<arguments.length; ++i) if(isNaN(arguments[i]) || typeof(arguments[i])!='number') return false;
			return true;
	}

	/*
	 * 문서 객체 스타일의 display 속성을 반환하거나 수정합니다.
	 * display(객체 아이디); 형식은 display 속성을 반환합니다.
	 * display(객체 아이디,(block,none)); 형식은 display 속성을 변경합니다.
	*/
	function display(e,s) {
		if(!(e = getObj(e))) return null;
		if(e.style && isDefined(e.style.display)) {
			if(isString(s)) e.style.display = s;
			return e.style.display;
		}
		return null;
	}

	/*
	 * 문서 객체 스타일의 alpha 속성을 수정합니다.
	 * setAlpha(객체 아이디,(0~100)); 형식으로 사용합니다.
	*/
	function setAlpha(e,s) {
		if(!(e = getObj(e))) return null;
		if(e.style && isDefined(e.style.filter)) {
			if(isDefined(e.style.position) && e.style.position != 'static') {
				if(isNumber(s)) e.style.filter = 'alpha(opacity='+s+')';
				var temp = e.style.filter.replace(/(.*?)opacity=([0-9]+)(.*?)$/ig,'$2');
				if(!temp) temp = 100;
				return temp;
			}
		}
		else{
			if(isNumber(s)) {
				s = s / 100;
				e.style.opacity = s;
			}
			var temp = e.style.opacity;
			if(!temp) temp = 100;
			return temp;
		}
	}

	/*
	 * 요소의 아이디를 이용하여 객체를 반환합니다.
	 * 변수 = getObj(아이디); 방식으로 사용합니다.
	*/
	function getObj(s) {
		if(typeof(s) != 'string') return s;
		if(document.getElementById) s = document.getElementById(s);
		else if(document.all) s = document.all(s);
		else s = null;
		return s;
	}


	/*
	 * Iframe 이동시 사용합니다.
	 * IframeGo("프레임ID", "이동할주소"); 방식으로 사용합니다.
	*/
	function IframeGo( ObjIFName, ObjSRC ) {
		ObjIF = getObj(ObjIFName);
		ObjIF.contentWindow.location.href = ObjSRC;
	}


	/*
	 * 객체의 background 속성을 변경하거나 반환합니다.
	 * setBG(객체 아이디[,색상코드,이미지]]); 형식으로 사용합니다.
	*/
	function setBG(e,c,i) {
		if(!(e = getObj(e))) return;
		var bg = '';
		if(e.style) {
			if(isString(c)) {
				if(!(_common.browserName == 'opera' && _common.browserVers < 6)) e.style.backgroundColor = c;
				else e.style.background = c;
			}
			if(isString(i)) e.style.backgroundImage = (i != '') ? 'url('+i+')':null;
			if(!(_common.browserName == 'opera' && _common.browserVers < 6)) bg = e.style.backgroundColor;
			else bg = e.style.background;
		}
		return bg;
	}

	/*
	 * Element를 새로 생성합니다.
	 * createElement(태그명) 형식으로 사용합니다.
	*/
	function createElement(tag) {
		if (document.createElement) return document.createElement(tag);
		else return null;
	}

	/*
	 * 객체의 left 스타일 속성을 변경하거나 반환합니다.
	 * left(객체 아이디[,값]); 형식으로 사용합니다.
	*/
	function left(e,_x) {
		if(!(e = getObj(e))) return 0;
		var css = isDefined(e.style);
		if(css && isString(e.style.left)) {
			if(isNumber(_x)) e.style.left = _x+'px';
			else{
				_x = parseInt(e.style.left);
				if(isNaN(_x)) _x = 0;
			}
		}
		else if(css && isDefined(e.style.pixelLeft)) {
			if(isNumber(_x)) e.style.pixelLeft = _x;
			else _x = e.style.pixelLeft;
		}
		return _x;
	}

	/*
	 * 객체의 top 스타일 속성을 변경하거나 반환합니다.
	 * top(객체 아이디[,값]); 형식으로 사용합니다.
	
	function top(e,_y) {
		if(!(e = getObj(e))) return 0;
		var css = isDefined(e.style);
		if(css && isString(e.style.top)) {
			if(isNumber(_y)) e.style.top = _y+'px';
			else{
				_y = parseInt(e.style.top);
				if(isNaN(_y)) _y = 0;
			}
		}
		else if(css && isDefined(e.style.pixelTop)) {
			if(isNumber(_y)) e.style.pixelTop = _y;
			else _y = e.style.pixelTop;
		}
		return _y;
	}*/

	/*
	 * 클라이언트 화면의 넓이를 반환합니다.
	 * getClientWidth() 형식으로 사용합니다.
	*/
	function getClientWidth() {
		var w = 0;
		if(_common.browserName == 'opera' && _common.browserVers < 7) w = window.innerWidth;
		else if(document.compatMode == 'CSS1Compat' && _common.browserName != 'opera' && document.documentElement && document.documentElement.clientWidth)
			w = document.documentElement.clientWidth;
		else if(document.body && document.body.clientWidth)
			w = document.body.clientWidth;
		else if(isDefined(window.innerWidth,window.innerHeight,document.height)) {
			w = window.innerWidth;
			if(document.height>window.innerHeight) w -= 16;
		}
		return w;
	}

	/*
	 * 클라이언트 화면의 높이를 반환합니다.
	 * getClientHeight() 형식으로 사용합니다.
	*/
	function getClientHeight() {
		var h = 0;
		if(_common.browserName == 'opera' && _common.browserVers < 7) w = window.innerHeight;
		else if(document.compatMode == 'CSS1Compat' && _common.browserName != 'opera' && document.documentElement && document.documentElement.clientHeight) {
			h = document.documentElement.clientHeight;
		}
		else if(document.body && document.body.clientHeight) {
			h = document.body.clientHeight;
		}
		else if(isDefined(window.innerWidth,window.innerHeight,document.width)) {
			h = window.innerHeight;
			if(document.width>window.innerWidth) h -= 16;
		}
		return h;
	}

	/*
	 * BODY의 넓이를 반환합니다.
	 * getBodyWidth() 형식으로 사용합니다.
	*/
	function getBodyWidth() {
		var cw = getClientWidth();
		var sw = window.document.body.scrollWidth;
		return cw > sw ? cw:sw;
	}

	/*
	 * BODY의 높이를 반환합니다.
	 * getBodyHeight() 형식으로 사용합니다.
	*/
	function getBodyHeight() {
		var ch = getClientHeight;
		var sh = window.document.body.scrollHeight;
		return ch > sh ? ch:sh;
	}

	/*
	 * 객체의 innerHTML을 수정하거나 반환합니다.
	 * innerHTML(객체 아이디[,값]); 형식으로 사용합니다.
	*/
	function innerHTML(e,h) {
		if(!(e = getObj(e)) || !isString(e.innerHTML)) return null;
		var s = e.innerHTML;
		if(isString(h)) e.innerHTML = h;
		return s;
	}

	/*
	 * 화면의 스크롤 된 넓이를 수정하거나 반환합니다.
	 * getScrollLeft([객체 아이디[,값]]); 형식으로 사용합니다.
	*/
	function getScrollLeft(e,bWin) {
		var offset = 0;
		if (!isDefined(e) || bWin || e == document || e.tagName.toLowerCase() == 'html' || e.tagName.toLowerCase() == 'body') {
			var w = window;
			if (bWin && e) w = e;
			if(w.document.documentElement && w.document.documentElement.scrollLeft) offset = w.document.documentElement.scrollLeft;
			else if(w.document.body && isDefined(w.document.body.scrollLeft)) offset = w.document.body.scrollLeft;
		}
		else{
			e = getObj(e);
			if(e && isNumber(e.scrollLeft)) offset = e.scrollLeft;
		}
		return offset;
	}

	/*
	 * 화면의 스크롤 된 넓이를 수정하거나 반환합니다.
	 * getScrollTop([객체 아이디[,값]]); 형식으로 사용합니다.
	*/
	function getScrollTop(e,bWin) {
		var offset = 0;
		if (!isDefined(e) || bWin || e == document || e.tagName.toLowerCase() == 'html' || e.tagName.toLowerCase() == 'body') {
			var w = window;
			if (bWin && e) w = e;
			if(w.document.documentElement && w.document.documentElement.scrollTop) offset = w.document.documentElement.scrollTop;
			else if(w.document.body && isDefined(w.document.body.scrollTop)) offset = w.document.body.scrollTop;
		}
		else{
			e = getObj(e);
			if(e && isNumber(e.scrollTop)) offset = e.scrollTop;
		}
		return offset;
	}

	/*
	 * 객체의 visibility 스타일 속성을 수정하거나 반환합니다.
	 * visibility(객체 아이디[,값]]); 형식으로 사용합니다.
	*/
	function visibility(e,bShow) {
		if(!(e = getObj(e))) return null;
		if(e.style && isDefined(e.style.visibility)) {
			if(isDefined(bShow)) e.style.visibility = bShow ? 'visible' : 'hidden';
			return e.style.visibility;
		}
		return null;
	}

	/*
	 * 쿠키 값을 얻습니다.
	 * getCookie(쿠키명); 형식으로 사용합니다.
	*/
	function getCookie(name) {
		var value=null, search=name+"=";
		if (document.cookie.length > 0) {
			var offset = document.cookie.indexOf(search);
			if (offset != -1) {
				offset += search.length;
				var end = document.cookie.indexOf(";", offset);
				if (end == -1) end = document.cookie.length;
				value = unescape(document.cookie.substring(offset, end));
			}
		}
		return value;
	}

	/*
	 * 쿠키를 삭제합니다.
	 * deleteCookie(쿠키명); 형식으로 사용합니다.
	*/
	function deleteCookie(name,path) {
		if(getCookie(name)) {
			document.cookie = name + '=' + '; path=' + ((!path) ? '/':path) + '; expires=' + new Date(0).toGMTString();
		}
	}

	/*
	 * 쿠키를 생성합니다.
	 * setCookie(쿠키명,값[,시간[,경로]]); 형식으로 사용합니다.
	*/
	function setCookie(name,value,expire,path) {
		document.cookie = name + '=' + escape(value) + ((!expire) ? '':('; expires=' + expire.toGMTString())) + '; path=' + ((!path) ? '':path);
	}

	/*
	 * 객체 value 값이 존재하는지 확인합니다.
	 * isValue(객체 아이디); 형식으로 사용합니다.
	*/
	function isValue(ele) {
		if(!(ele = getObj(ele))) return true;
		if(!ele.value) return false;
		return true;
	}


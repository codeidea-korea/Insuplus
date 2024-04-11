
	function resetID() {
		var ff = document.JoinForm;
		ff.id_chk.value = 0;
	}


	// 방지할 ID, 이름
	var noName = new Array("admin", "administrator","webmaster","master","관리자","게시판관리자","어드민","웹마스터","사이트관리자","운영자","사이트운영자");

	var alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	var numeric = '1234567890';
	var special = ' ~!@#$%^&*()-_=+|\\{}[];:"\'<>,.?\/';

	// 에러시 체크
	function error (elem,text) {
		window.alert(text);
		elem.select();
		elem.focus();
	}

	// 아이디 체크창 열기
	function checkID(url) {
		var ff = document.JoinForm;

//		if(ff.u_id.value.length < 6) {
//			alert("아이디는 영문 또는 숫자 6~16자로 구성됩니다.");
//			ff.u_id.focus();
//			return false;
//		}
//		else {
			if (!checkNorm(ff.u_id, '아이디', numeric+alpha+'(-_)+', 16)) {
				ff.u_id.focus();
				return;
			}
//		}
		idcheck = window.open( url+'idcheck.php?u_id=' + ff.u_id.value, 'idcheck', 'width=350,height=327,left=350,top=320,resizable=0,scrollbars=0' );
		idcheck.focus();
	}

	function checkNorm(target, cmt, astr, lmax) {
		var i;
		var t = target.value;
		if (t.length == 0 ) {
			alert(cmt + '(을)를 기재하지 않으셨습니다.');
			return false;
		}
		if (lmax != 0 && t.length > lmax) {
			alert(cmt + '는 ' + lmax + '자 이내만 허용합니다.');
			return false;
		}
		if (astr.length >= 1) {
			for (i=0; i<t.length; i++) {
				if( astr.indexOf(t.substring(i,i+1)) < 0 ) {
					alert(cmt + '에 허용할 수 없는 문자가 입력되었습니다');
					return false;
					break;
				}
			}
			return true;
		}
	}


	// 주민번호 체크
	function juminCheck(jumin1 ,jumin2) {

		var str_jumin1 = jumin1.value;
		var jumin1_err = jumin1;
		var str_jumin2 = jumin2.value;
		var jumin2_err = jumin2;
		var checkImg='';


		var i3=0
		for (var i=0;i<str_jumin1.length;i++)
		{
			var ch1 = str_jumin1.substring(i,i+1);
			if (ch1<'0' || ch1>'9') { i3=i3+1 }
		}
		if ((str_jumin1 == '') || ( i3 != 0 ))
		{
		  error(jumin1_err,'주민등록번호를 정확히 입력해주십시오');
		  return false;
		}


		var i4=0
		for (var i=0;i<str_jumin2.length;i++)
		{
			var ch1 = str_jumin2.substring(i,i+1);
			if (ch1<'0' || ch1>'9') { i4=i4+1 }
		}
		if ((str_jumin2 == '') || ( i4 != 0 ))
		{
			error(jumin2_err,'주민등록번호를 정확히 입력해주십시오');
			return false;
		}
		if(str_jumin2.substring(0,1) > 4)
		{
			error(jumin2_err,'주민등록번호를 정확히 입력해주십시오');
			return false;
		}

		if((str_jumin1.length > 7) || (str_jumin2.length > 8))
		{
			error(jumin2_err,'주민등록번호를 정확히 입력해주십시오');
			return false;
		}

		if ((str_jumin1 == '72') || ( str_jumin2 == '18'))
		{
		  error(jumin1_err,'주민등록번호를 정확히 입력해주십시오');
		  return false;
		}

		var f1=str_jumin1.substring(0,1)
		var f2=str_jumin1.substring(1,2)
		var f3=str_jumin1.substring(2,3)
		var f4=str_jumin1.substring(3,4)
		var f5=str_jumin1.substring(4,5)
		var f6=str_jumin1.substring(5,6)
		var hap=f1*2+f2*3+f3*4+f4*5+f5*6+f6*7
		var l1=str_jumin2.substring(0,1)
		var l2=str_jumin2.substring(1,2)
		var l3=str_jumin2.substring(2,3)
		var l4=str_jumin2.substring(3,4)
		var l5=str_jumin2.substring(4,5)
		var l6=str_jumin2.substring(5,6)
		var l7=str_jumin2.substring(6,7)
		hap=hap+l1*8+l2*9+l3*2+l4*3+l5*4+l6*5
		hap=hap%11
		hap=11-hap
		hap=hap%10
		if (hap != l7)
		{
		  error(jumin1_err,'주민등록번호를 정확히 입력해주십시오');
		  return false;
		}
		var i9=0
		return true;

	}




	function selectBox(start,end,inpt,adds,strd) {

		var i=0,iinput=0;
		var t = eval(adds);
		var sTemp="";
		var strString="";

		//if(inpt !=""){
			iinput=inpt;
		if (t > 0) {
			for(i=start ; i<=end ; i+=t){
				if(i==iinput){
					if(i<10){
						sTemp="0"+ i;
					}
					else{
						sTemp= i;
					}
					strString+=" <option value='"+sTemp+"' selected>"+i+" "+strd+"</option>";
				}
				else{
					if(i<10){
						sTemp="0"+ i;
					}
					else{
						sTemp= i;
					}
					strString+=" <option value='"+sTemp+"'>"+i+" "+strd +"</option>";
				}
			}
		}
		else {
			for(i=start ; i>=end ; i+=t){
				if(i==iinput){
					if(i<10){
						sTemp="0"+ i;
					}
					else{
						sTemp= i;
					}
					strString+=" <option value='"+sTemp+"' selected>"+i+" "+strd+"</option>";
				}
				else{
					if(i<10){
						sTemp="0"+ i;
					}
					else{
						sTemp= i;
					}
					strString+=" <option value='"+sTemp+"'>"+i+" "+strd +"</option>";
				}
			}
		}
		//}
		return strString;

	}



	// 우편번호 검색창 띄워주는 스크립트
	function OpenZipcode(url, obj_form, obj_post, obj_addr1, obj_addr2 ) {
		zipwin = window.open(url+"zipcode.php?obj_form="+obj_form+"&obj_post="+obj_post+"&obj_addr1="+obj_addr1+"&obj_addr2="+obj_addr2,"zipwin","width=490,height=320,left=280,top=340,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no");
		zipwin.focus();
	}



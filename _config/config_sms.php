<?
// 현재 URL 가져오기
function getURL(){              // 다른 곳에 중복해 쓸 경우에는 getURL1, getURL2 이런 식으로 바꿔줘야
	$server	= getenv("HTTP_HOST");			// 현재 자신의 URL 을 가져온다.
	$file			= getenv("SCRIPT_NAME");
	$query		= getenv("QUERY_STRING");
	$url			= "http://$server$file";
	if($query) $url.="?$query";
	return $url;
}

$url_Now = getURL();            // 다른 곳에 중복해 쓸 경우에는 getURL1, getURL2 이런 식으로 바꿔줘야
//echo "url_p : ".$url_p."<BR>";

	/* 전역변수 호환성 설정 */
	// $HTTP_POST_VARS : 4.1.0 이전 버전
	// $_POST : 4.1.0 이후 버전
	$php_ver = explode(".", phpversion());
	if ($php_ver[0]<4 || $php_ver[0]==4 && $php_ver[1]<1) {
		$_GET =& $HTTP_GET_VARS;
		$_POST =& $HTTP_POST_VARS;
		$_COOKIE =& $HTTP_COOKIE_VARS;
		$_SESSION =& $HTTP_SESSION_VARS;
		$_FILES =& $HTTP_POST_FILES;
		$_ENV =& $HTTP_ENV_VARS;
		$_SERVER =& $HTTP_SERVER_VARS;
	}


	/* 여러도메인을 사용할 때 관리자 도메인으로 이동시키키 위해서 처리함 2009.03.13 김승태 */
//	echo "cf_mall_url : ".$cf_mall_url."<BR>";
//	echo "g_user_host : ".$g_user_host."<BR>";
//	echo getenv("HTTP_HOST")."<BR>";

/*
	$cf_site_url = "http://highskin.co.kr";
	$cf_site_url_ssl = "https://highskin.co.kr:50000";

	if(!eregi(getenv("HTTP_HOST"),$cf_site_url) && !eregi(getenv("HTTP_HOST"),$cf_site_url_ssl)) {
		echo "<meta http-equiv='Refresh' content='0; URL=" . $cf_site_url . "'>";
		exit;
	}
*/

//	echo "<xmp>";
//	print_r($_SERVER);
//	echo "</xmp>";
//	exit;

	########################################################
	// MySQL DB 접속정보
	$mysql_sms_host						= "localhost";
	$mysql_sms_database_name		= "kmsms";
	$mysql_sms_user						= "ljh";
	$mysql_sms_password				= "sjskdnfl!@#";
	########################################################


	#### email 관련
//	$email_from_name		= "관리자";
//	$email_from_email			= "admin@email.com";



	/* Config*/
	// dir
	if (!$path_root) {
		$path_root = $_SERVER["DOCUMENT_ROOT"]."/";
		$url_root				= "/";	// 웹 루트경로
	}
	else {
		// 상대경로로 루트의 경로를 지정할때...
		$url_root = $path_root;	// 웹 루트경로
	}

	#### 파일 경로 & 웹 경로
	//$path_root			= $_SERVER["DOCUMENT_ROOT"]."/";	// 시스템루트경로
	//$url_root				= "http://".$_SERVER["SERVER_NAME"]."/";	// 웹 루트경로
	//$url_root				= "/";	// 웹 루트경로
//	echo $url_root."<BR>";
//	exit;


	#### 메인 경로
	//$url_index			= $url_root."main/main.html";
	//$url_index			= $url_root."index.html";
	$url_index				= $url_root."html/index.php";


	#### 관리자 경로
	$path_admin			= $path_root."admin/";
	$url_admin				= $url_root."admin/";


	#### UTIL 경로
	$path_util							= $path_root."_util/";
	$url_util								= $url_root."_util/";

	$path_board						= $path_util."board/";
	$path_comment					= $path_util."comment/";

	$url_board							= $url_util."board/";
	$url_comment						= $url_util."comment/";

	//$path_member						= $path_util."member/";
	$path_member						= $path_root."html/member/";
	$url_member						= $url_root."html/member/";


	#### 업로드 폴더 경로
	$path_data							= $path_root."_data/";
	$url_data							= $url_root."_data/";

	#### 에디터 경로
	$path_editor						= $path_util."editor_gmeditor/";
	$url_editor							= $url_util."editor_gmeditor/";

	$path_fckeditor					= $path_util."fckeditor/";
	$url_fckeditor						= $url_util."fckeditor/";

	$path_editor_upload				= $path_data."_editor/";
	$url_editor_upload				= $url_data."_editor/";

	#### CHSignup
	$url_signup							= $url_util."chsignup/chsignup.php";

	#### HTC Calendar2
	$url_calendar						= $url_util."calendar/htc_calendar2.htc";
	$ClassCalendar						= " class=\"text_cal\" maxlength=\"10\" ";


	#### AceMTCounter 3.0
	$path_counter						= $path_util."AceMTcounter/";
	$url_counter						= $url_util."AceMTcounter/";

	#### 팝업 정보 경로
	$path_popup						= $path_util."popup/";
	$url_popup							= $url_util."popup/";
	$path_popup_data				= $path_data."popup/";


	#### 회원 정보 경로
	$url_member_join					= $url_member."member01.php";
	$url_member_find_id				= $url_member."find_id.php";
	$url_member_find_pw				= $url_member."find_pw.php";

	$url_member_modify				= $url_root."html/mypage/member_modify.php";
	$url_member_drop					= $url_root."html/mypage/member_leave.php";

	$url_member_agree				= $url_member."AgreeList.php";

	$url_login							= $url_root."html/login/login.php?url=".$PHP_SELF;
	$url_logout							= $url_root."html/login/logout.php";
	$url_logout_ok						= $url_index;									// 로그아웃 후 이동할 페이지


	#### 스킨 경로
	$path_skin							= $path_root."_skin/";
	$url_skin								= $url_root."_skin/";

	$path_skin_member				= $path_root."html/member/";
	$path_skin_member1				= $path_root."_skin/member/";
	$path_skin_board					= $path_root."_skin/board/";
	$path_skin_mail					= $path_root."_skin/mail/";
	$path_skin_comment				= $path_root."_skin/comment/";

	$url_skin_member1				= $url_root."_skin/member/";
	$url_skin_board					= $url_root."_skin/board/";
	$url_skin_mail						= $url_root."_skin/mail/";
	$url_skin_comment				= $url_root."_skin/comment/";

	#### Product 경로
	$path_product						= $path_root."product/";
	$url_product						= $url_root."product/";

	$path_skin_product				= $path_skin."product/";		// 사용하지 말것 아래 형식으로 사용할 것
	$url_skin_product					= $url_skin."product/";

	$path_product_skin_				= $path_skin."product/";
	$url_product_skin					= $url_skin."product/";

	$path_product_data				= $path_data."product/";
	$url_product_data					= $url_data."product/";

	$search_product					= $url_root."sub/product_search.php";

	#### 주로 사용하는 경로
	$url_admin_login					= $url_admin."login.php";
	$url_admin_login_ok				= $url_admin."main.php";						// 로그인 후 이동할 페이지
	$url_admin_login_out				= $url_admin."logout.php";
	$url_admin_index					= $url_admin_login_ok;
	$url_redirect							= $url_root."_config/AlertRedirect.php";

	// 이니시스경로
	$path_inicis	 = $path_root."INIpay41/";
	$url_inicis	 = $url_root."INIpay41/";

	#### 관리자 권한
	$auth_admin		= 7;
	$join_user_level = 1;
	$login_user_level	= 1;
	$join_user_state = 1;

	#### 숫자만 입력
	$OnlyNumber				= " onKeyPress=\"OnlyNumber();\" style=\"ime-mode:disabled;\" ";
	#### 영어만 입력
	$OnlyEng					= " style=\"ime-mode:disabled;\" ";

	#### JS 허용문자 - 적용안됨...나중에 변경하도록 하자
	$var_special				= ' ~!@#$%^&*()-_=+|\\{}[];:"\'<>,.?\/';
	$var_alpha					= '1234567890';
	$var_numeric				= 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

	$PostGubun1				= "동으로 검색";
	$PostGubun2				= "우편번호로 검색";
	// 아이디 / 비번 찾기 형식
	$find_type					= "email";


	#### 네이버 API 관련
	if ($_SERVER["SERVER_NAME"]=="test.ljh.co.kr"){
	$naver_load_key			= "366e026760c9ec6595decc0b9b3ff67d";			// test.ljh.co.kr
	}else if ($_SERVER["SERVER_NAME"]=="www.ljh.co.kr"){
	$naver_load_key	 		= "7d2938605a96e0044079e64ad31403e0";		// www.ljh.co.kr
	}else if ($_SERVER["SERVER_NAME"]=="ljh.co.kr"){
	$naver_load_key			= "8f7caa0412a335f7ebb008dedb2df7fa";			// ljh.co.kr
	}
?>
<?
	header("Content-Type: text/html; charset=UTF-8");
	// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
	header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');
	if (!isset($set_time_limit)) $set_time_limit = 0;
	@set_time_limit($set_time_limit);
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.Array.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.MSG.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.SQL.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.session.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.DB.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.File.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.FileNew.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.html.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.global.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.page.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.mail.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.product.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.editor.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.Cart.php";

	$dbcon = new dbcon;
//	$sms_dbcon = new sms_dbcon;
	$dbcon -> dbcon_open(0);
	mysqli_query("set names utf8");
	$SC_Rows = getSiteConfig();
	extract($SC_Rows);
	unset($SC_Rows);

	// DB별개 연결
	$dbconn = mysqli_connect($mysql_host,$mysql_user,$mysql_password,$mysql_database_name) or die("데이터베이스 연결에 실패하였습니다.");
	$status = mysqli_select_db($dbconn,$mysql_database_name);
	if (!$status) {
	   error("DB_CONNECT_ERROR");
	   exit;
	}


// 짧은 환경변수를 지원하지 않는다면
if (isset($HTTP_POST_VARS) && !isset($_POST)) {
	$_POST   = &$HTTP_POST_VARS;
	$_GET    = &$HTTP_GET_VARS;
	$_SERVER = &$HTTP_SERVER_VARS;
	$_COOKIE = &$HTTP_COOKIE_VARS;
	$_ENV    = &$HTTP_ENV_VARS;
	$_FILES  = &$HTTP_POST_FILES;

    if (!isset($_SESSION))
		$_SESSION = &$HTTP_SESSION_VARS;
}

//
// phpBB2 참고
// php.ini 의 magic_quotes_gpc 값이 FALSE 인 경우 addslashes() 적용
// SQL Injection 등으로 부터 보호
//
if( !get_magic_quotes_gpc() )
{
	if( is_array($_GET) )
	{
		while( list($k, $v) = each($_GET) )
		{
			if( is_array($_GET[$k]) )
			{
				while( list($k2, $v2) = each($_GET[$k]) )
				{
					$_GET[$k][$k2] = addslashes($v2);
				}
				@reset($_GET[$k]);
			}
			else
			{
				$_GET[$k] = addslashes($v);
			}
		}
		@reset($_GET);
	}

	if( is_array($_POST) )
	{
		while( list($k, $v) = each($_POST) )
		{
			if( is_array($_POST[$k]) )
			{
				while( list($k2, $v2) = each($_POST[$k]) )
				{
					$_POST[$k][$k2] = addslashes($v2);
				}
				@reset($_POST[$k]);
			}
			else
			{
				$_POST[$k] = addslashes($v);
			}
		}
		@reset($_POST);
	}

	if( is_array($_COOKIE) )
	{
		while( list($k, $v) = each($_COOKIE) )
		{
			if( is_array($_COOKIE[$k]) )
			{
				while( list($k2, $v2) = each($_COOKIE[$k]) )
				{
					$_COOKIE[$k][$k2] = addslashes($v2);
				}
				@reset($_COOKIE[$k]);
			}
			else
			{
				$_COOKIE[$k] = addslashes($v);
			}
		}
		@reset($_COOKIE);
	}
}

$_GET = xss_clean($_GET);

//==========================================================================================================================
// extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
//--------------------------------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
                  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
                  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for ($i=0; $i<$ext_cnt; $i++) {
    // GET 으로 선언된 전역변수가 있다면 unset() 시킴
    if (isset($_GET[$ext_arr[$i]])) unset($_GET[$ext_arr[$i]]);
}
//==========================================================================================================================

// PHP 4.1.0 부터 지원됨
// php.ini 의 register_globals=off 일 경우
@extract($_GET);
@extract($_POST);
@extract($_SERVER);
?>
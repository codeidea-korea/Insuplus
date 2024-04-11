<?
include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";

$ev_code		= $_REQUEST["ev_code"];	//이벤트번호
$os				= $_REQUEST["os"];			//OS군
$os_name		= $_REQUEST["os_name"];	//OS명칭
$browser		= $_REQUEST["browser"];		//브라우저 명칭
$browser_v		= $_REQUEST["browser_v"];	//브라우저 버젼
$mobile_yn		= $_REQUEST["mobile_yn"];	//모바일여부
$m_pixel			= $_REQUEST["m_pixel"];		//접속화면픽셀
$sword			= $_REQUEST["sword"];		//검색어
$u_ip				= $_REQUEST["u_ip"];			//접속아이피
$referer			= $_REQUEST["referer"];		//전주소
$log_domain	= $_REQUEST["log_domain"];		//전주소
$now_year		= date("Y");							//현재년도
$now_month	= date("m");							//현재달
$now_day		= date("d");							//현재날짜
$now_hour		= date("H");							//현재시간
$now_minute	= date("i");							//현재분

if ($ev_code){
// 이벤트 로그
$SQL = "insert into tbl_event_log set
			ev_code = '".$ev_code."'
			, log_url = '".$referer."'
			, log_word = '".$sword."'
			, log_domain = '".$log_domain."'
			, log_ip = '".$u_ip."'
			, log_os = '".$os."'
			, log_os_name = '".$os_name."'
			, log_pixel = '".$m_pixel."'
			, log_browser = '".$browser."'
			, log_browser_v = '".$browser_v."'
			, log_year = '".$now_year."'
			, log_month = '".$now_month."'
			, log_day = '".$now_day."'
			, log_hour = '".$now_hour."'
			, log_minute = '".$now_minute."'
			, log_mobile = '".$mobile_yn."'
			, regdate = now()
			";
$result = $dbcon -> query($SQL);
}
?>

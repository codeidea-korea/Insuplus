<?
// DB연결
$THIS_PATH = dirname(__FILE__);
include("$THIS_PATH/lib/_connect.php");

if(!isset($_COOKIE[AceMTcount_flag])) {
	$query = "SELECT * FROM AceMTcounter_ip WHERE ci_ip='$_SERVER[REMOTE_ADDR]' AND ci_yy='$yy' AND ci_mm='$mm' AND ci_dd='$dd'";
	mysql_query($query);
	if(mysql_affected_rows()) {
        $ci_todayip = 0;
    }
    else {
        $ci_todayip = 1;
    }

	$query = "SELECT * FROM AceMTcounter_ip WHERE ci_ip='$_SERVER[REMOTE_ADDR]' AND ci_yy='$yy' AND ci_mm='$mm' AND ci_dd='$dd' AND ci_hh='$hh'";
	mysql_query($query);
	if(mysql_affected_rows()) {
		$query = "UPDATE AceMTcounter_ip SET ci_hit=ci_hit+1, ci_uptime=UNIX_TIMESTAMP() WHERE ci_ip='$_SERVER[REMOTE_ADDR]' AND ci_yy='$yy' AND ci_mm='$mm' AND ci_dd='$dd' AND ci_hh='$hh'";
	}
	else {
		$domain = gethostbyaddr($_SERVER[REMOTE_ADDR]);
		$domain = $_SERVER[REMOTE_ADDR];
		$query = "INSERT INTO AceMTcounter_ip SET ci_ip='$_SERVER[REMOTE_ADDR]', ci_domain='$domain', ci_yy='$yy', ci_mm='$mm', ci_dd='$dd', ci_ww='$ww', ci_hh='$hh', ci_hit=1, ci_todayip='$ci_todayip', ci_uptime=UNIX_TIMESTAMP()";
	}
	mysql_query($query);

	$query = "SELECT * FROM AceMTcounter_browser WHERE cb_browse='$_SERVER[HTTP_USER_AGENT]'";
	mysql_query($query);
	if(mysql_affected_rows()) {
		$query = "UPDATE AceMTcounter_browser SET cb_hit=cb_hit+1, cb_uptime=UNIX_TIMESTAMP() WHERE cb_browse='$_SERVER[HTTP_USER_AGENT]'";
	}
	else {
		$query = "INSERT INTO AceMTcounter_browser SET cb_browse='$_SERVER[HTTP_USER_AGENT]', cb_hit=1, cb_uptime=UNIX_TIMESTAMP()";
	}
	mysql_query($query);

    $REPLACE_REFERER = substr($REPLACE_REFERER, 0, 250);
    $query = "SELECT * FROM AceMTcounter_url WHERE cu_url='$REPLACE_REFERER'";
    mysql_query($query);
    if(mysql_affected_rows()) {
        $query = "UPDATE AceMTcounter_url SET cu_hit=cu_hit+1, cu_uptime=UNIX_TIMESTAMP() WHERE cu_url='$REPLACE_REFERER'";
    }
    else {
        $query = "INSERT INTO AceMTcounter_url SET cu_url='$REPLACE_REFERER', cu_hit=1, cu_uptime=UNIX_TIMESTAMP()";
    }
    mysql_query($query);

    if($REPLACE_REFERER != "") {
        $getArrayURL = explode("/", $REPLACE_REFERER);
        $site = trim(urldecode($getArrayURL[2]));
        $z = explode(".", $site);
        if(count($z) > 1) {
            $sql = "SELECT cs_uptime FROM AceMTcounter_site WHERE cs_site = '$site'";
            $res = mysql_query($sql);
            if(mysql_num_rows($res) > 0) {
                $sql = "UPDATE AceMTcounter_site SET cs_hit = cs_hit + 1, cs_uptime = UNIX_TIMESTAMP() WHERE cs_site = '$site'";
                mysql_query($sql);
            }
            else {
                $sql = "INSERT INTO AceMTcounter_site (cs_site, cs_hit, cs_uptime) VALUES ('$site', 1, UNIX_TIMESTAMP())";
                mysql_query($sql);
            }
        }
    }

	setcookie("AceMTcount_flag", 1, 0, "/");
	$DESPLAY_COUNT_FLAG = true;
}

if(session_id() == "") {
    session_start();
}

$query = "DELETE FROM AceMTcounter_now WHERE uptime < UNIX_TIMESTAMP()-120";
mysql_query($query);

$query = "REPLACE INTO AceMTcounter_now SET session_id='" . session_id() . "', ip='$_SERVER[REMOTE_ADDR]', uptime=UNIX_TIMESTAMP()";
mysql_query($query);

if($DESPLAY_COUNT_FLAG == true) {
	setcookie("AceMTcount_flag_display", 1, 0, "/");
	$len = strlen($_SERVER[DOCUMENT_ROOT]);
	$INIT_DISPLAY = "./init_display.php";
	echo "<script>document.write(\"<img border='0' width='0' height='0' src='{$INIT_DISPLAY}?w=\"+screen.width+\"&h=\"+screen.height+\"'>\");</script>";
}
?>
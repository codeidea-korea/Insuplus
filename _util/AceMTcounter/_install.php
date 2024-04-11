<?php
include("./lib/_connect.php");

$sql = "DROP TABLE AceMTcounter_site";
mysql_query($sql);

$sql = "CREATE TABLE AceMTcounter_site (
  cs_site varchar(255) NOT NULL default '',
  cs_hit int(11) NOT NULL default '0',
  cs_uptime int(11) NOT NULL default '0',
  PRIMARY KEY  (cs_site),
  KEY cs_site (cs_site),
  KEY cs_uptime (cs_uptime)
) TYPE=MyISAM;";
mysql_query($sql);


$sql = "SELECT * FROM AceMTcounter_url";
$res = mysql_query($sql);
while($rs = mysql_fetch_assoc($res)) {
	if(stristr($rs[cu_url], "http")) {
		$getArrayURL = explode("/", $rs[cu_url]);
		$site = trim(urldecode($getArrayURL[2]));
		$z = explode(".", $site);
		if(count($z) > 1) {
			$cu_hit = $rs[cu_hit];
			$cu_uptime = $rs[cu_uptime];
		}
		else {
			continue;
		}
	}
	else {
		continue;
	}

	$sql = "SELECT * FROM AceMTcounter_site WHERE cs_site = '$site'";
	$r = mysql_query($sql);
	if(mysql_num_rows($r) > 0) {
		$sql = "UPDATE AceMTcounter_site SET cs_hit = cs_hit + $cu_hit, cs_uptime = $cu_uptime WHERE cs_site = '$site'";
		mysql_query($sql);
	}
	else {
		$sql = "INSERT INTO AceMTcounter_site (cs_site, cs_hit, cs_uptime) VALUES ('$site',$cu_hit,NOW())";
		mysql_query($sql);
	}
}
?>
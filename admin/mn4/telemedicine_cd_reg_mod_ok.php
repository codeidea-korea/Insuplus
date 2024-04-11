<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	if($mode=="insert") {
		$result = "";
		$prefix = "ispc".$expire_date."m";

		$SQL_CD = "select COUNT(*) as cnt from tbl_telemedicine_cd_list where expire_date = ".$expire_date."
					order by telemedicine_cd desc limit 1;";
		$RS_CD = $dbcon -> query($SQL_CD);
		$row = $dbcon -> fetch_array($RS_CD);

		
		$tempArr = array();
		$teleCodeArr = array();
		$TeleCodeCount = $row["cnt"];
		$taskCount = $telemedicine_cd;

		for($i = 1; $i < $taskCount+1; $i++) {
			$codeNum = $TeleCodeCount+$i;
			if(strlen($codeNum) == 3) {
				$codeNum = "0".$codeNum;
			}
			$teleCodeArr[$i] = $prefix.$codeNum;
		}
		shuffle($teleCodeArr);

		for($i = 0; $i < count($teleCodeArr); $i++) {
			$SQL_I = "insert into tbl_telemedicine_cd_list(telemedicine_cd, expire_date) values ('".$teleCodeArr[$i]."', '".$expire_date."')";
			$RS_I = $dbcon -> query($SQL_I);
			if($RS_I) {
				$result = "1";
			} else {
				$result = "0";
				echo "{'result':'".$result."'}";
			}
		}
	}
?>
{"result":"<?=$result;?>"}
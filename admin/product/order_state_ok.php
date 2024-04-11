<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
//	$a_idx = REQSTR($a_idx,"");
//	echo "a_idx : ".$a_idx."<BR>";
//	exit;


	//$dbcon -> setDebug(1);

	//$a_idx = explode(",", $a_idx);

	if ( is_array($a_idx) ) {
		$Arr_a_idx = $a_idx;
		$Arr_a_state = $a_state;
	}
	else {
		isnull($a_idx);
		$Arr_a_idx[0] = $a_idx;
		$Arr_a_state[0] = $a_state;
	}

	for ( $tt = 0 ; $tt < count($Arr_a_idx) ; $tt++ ) {


		// 기존 데이타 가져오기
		$SQL = " select * from tbl_account where a_idx = '".$Arr_a_idx[$tt]."' ";
		$FileRow = $dbcon -> fetch_array($dbcon -> query($SQL));
		$ordernum = $FileRow[ordernum];


		// 적립금 및 포인트 처리 Start
		// 적립금 및 포인트 처리 End


		// 메일 보내기 Start
		// 메일 보내기 End


		$SQL = "
			update tbl_account
			set
				a_state = '".$Arr_a_state[$tt]."'
			where
				a_idx = '".$Arr_a_idx[$tt]."'
		";
		//echo $SQL."<BR>";
		$dbcon -> query($SQL);

	}

	if ( $mode == "view") {
		alert_page("처리되었습니다.", "order_write.php?a_idx=".$Arr_a_idx[0]."&".$parameter);
	} else {
		alert_page("처리되었습니다.", "order_list.php?".$parameter);
	}
?>

<? $dbcon -> dbcon_close(); ?>
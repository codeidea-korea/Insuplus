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
	}
	else {
		isnull($a_idx);
		$Arr_a_idx[0] = $a_idx;
	}

	for ( $tt = 0 ; $tt < count($Arr_a_idx) ; $tt++ ) {


		// 기존 데이타 가져오기
		$SQL = " select ordernum from tbl_account where a_idx = '".$Arr_a_idx[$tt]."' ";
		$FileRow = $dbcon -> fetch_array($dbcon -> query($SQL));
		$ordernum = $FileRow[ordernum];

		$SQL = "
			delete from tbl_account_product
			where
				ordernum = '".$ordernum."'
		";
		$dbcon -> query($SQL);


		$SQL = "
			delete from tbl_account
			where
				a_idx = '".$Arr_a_idx[$tt]."'
		";
		$dbcon -> query($SQL);

	}

	alert_page("삭제되었습니다.", "order_list.php?".$parameter);
?>

<? $dbcon -> dbcon_close(); ?>
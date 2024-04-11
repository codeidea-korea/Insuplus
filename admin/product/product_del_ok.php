<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
//	$pr_idx = REQSTR($pr_idx,"");
//	echo "pr_idx : ".$pr_idx."<BR>";
//	exit;


	//$dbcon -> setDebug(1);

	$search_pc_num				= REQSTR($search_pc_num, "");
	$parameter = "search_pc_num=".$search_pc_num;

	//$pr_idx = explode(",", $pr_idx);

	if ( is_array($pr_idx) ) {
		$Arr_pr_idx = $pr_idx;
	}
	else {
		isnull($pr_idx);
		$Arr_pr_idx[0] = $pr_idx;
	}

	for ( $tt = 0 ; $tt < count($Arr_pr_idx) ; $tt++ ) {

		// 파일삭제
		FileDBDeleteSeq("pr_img", $Arr_pr_idx[$tt], $path_product_data, "thumb_305,thumb_60,thumb_500");
		FileDBDeleteSeq("pr_img_thum", $Arr_pr_idx[$tt], $path_product_data, "thumb_160");
		FileDBDeleteSeq("pr_file", $Arr_pr_idx[$tt], $path_product_data);

		// 기존 데이타 가져오기
		$SQL = " select pr_sort, pc_num from tbl_product where pr_idx = '".$Arr_pr_idx[$tt]."' ";
		$FileRow = $dbcon -> fetch_array($dbcon -> query($SQL));
		$pr_sort = $FileRow[pr_sort];
		$pc_num = $FileRow[pc_num];

		$SQL = "
			update tbl_product
			set pr_sort = pr_sort-1
			where
				pc_num = '".$pc_num."'
				and pr_sort > '".$pr_sort."'
		";
		$dbcon -> query($SQL);


		$SQL = "
			delete from tbl_product
			where
				pr_idx = '".$Arr_pr_idx[$tt]."'
		";
		$dbcon -> query($SQL);

	}

	alert_page("삭제되었습니다.", "product_list.php?".$parameter);
?>

<? $dbcon -> dbcon_close(); ?>
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk("7", $url_admin_login_out);
?>
<?

	$idx				= REQSTR($_POST[idx],"");
	$category		= REQSTR($_POST[category],"");
	$bc_id			= REQSTR($_POST[bc_id],"");
	$cate_name	= REQSTR($_POST[cate_name],"");
	$mode			= REQSTR($_POST[mode],"");

//	echo "idx : ".$idx."<BR>";
//	echo "category : ".$category."<BR>";
//	echo "bc_id : ".$bc_id."<BR>";
//	echo "name : ".$name."<BR>";
//	echo "mode : ".$mode."<BR>";
//	exit;

	isnull($mode);
	isnull($category);
	isnull($bc_id);

	$SQL = "";

	if ($mode == "modify") {
		isnull($idx);

		$SQL = "
			update tbl_category
				SET
					cate_name = '".$cate_name."'
					, cate_name_en = '".$cate_name_en."'
				WHERE
					1=1
					and category = '".$category."'
					and bc_id = '".$bc_id."'
					and idx = '".$idx."'
		";
		//echo $SQL."<BR>";

	}
	elseif ($mode == "insert") {

		$SQL = "
			SELECT ifnull( max( cate_sort ) , '0' ) + 1
			FROM tbl_category
			WHERE
				1=1
				and category = '".$category."'
				and bc_id = '".$bc_id."'
		";
		$cate_sort = $dbcon -> fetch_row($dbcon -> query($SQL));
//print_r($cate_sort);
		$SQL = "
			insert into
				tbl_category(
					category, bc_id, cate_name, cate_sort, cate_name_en
				)
				values (
					'".$category."', '".$bc_id."', '".$cate_name."', '".$cate_sort[0]."', '".$cate_name_en."')
		";
//		echo $SQL."<BR>";
	}
	elseif ($mode == "delgo") {
		if ( strlen($cate_sort) > 0 ) {
			$SQL = "
				update tbl_category
					set
						cate_sort = cate_sort - 1
					where
						1=1
						and category = '".$category."'
						and bc_id = '".$bc_id."'
						and cate_sort > '".$cate_sort."'
			";
			$dbcon -> query($SQL);
		}
		$SQL = "
			DELETE from tbl_category
			where idx = '".$idx."'
		";
		//echo $SQL."<BR>";
	}

	$result = $dbcon -> query($SQL);
	$dbcon -> dbcon_close();

	if (!$result) {
		alert_back('실행 에러\n관리자에게 문의하여 주십시오.');
		exit;
	}


	alert_page("처리되었습니다.","bbs_category.php?category=".$category."&bc_id=".$bc_id."&idx=".$idx);

?>
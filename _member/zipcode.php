<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";

	$obj_form = REQSTR($obj_form, "");
	$obj_post = REQSTR($obj_post, "");
	$obj_addr1 = REQSTR($obj_addr1, "");
	$obj_addr2 = REQSTR($obj_addr2, "");

	isnull($obj_form);
	isnull($obj_post);
	isnull($obj_addr1);
	isnull($obj_addr2);

	$postgubun = REQSTR($_POST[postgubun], "");
	$search_dong = REQSTR($_POST[search_dong], "");

	if ($search_dong) {

		$SQL = "
			select
				zipcode, sido, gugun, dong, bunji, seq
			from
				zipcode
			where
				".$postgubun." like '%".$search_dong."%'
			order by seq asc
		";
		$total_count = $dbcon -> getCount($SQL);
		if ( $total_count > 0 ) {
			$result = $dbcon -> query($SQL);
		}

	}

	include_once $path_skin_member."zipcode.php";

	$dbcon -> dbcon_close();
?>

<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
	if ( strlen($ss_u_id) > 0 ) {
		alert_close($msg_logout_go);
	}

	$act = REQSTR($_POST[act], "");

	if ( strlen($act) > 0 ) {

		$u_name = REQSTR($_POST[u_name], "");
		isnull($u_name);


		 if ( $find_type == "jumin" ) {
			$u_jumin1 = REQSTR($_POST[u_jumin1], "");
			$u_jumin2 = REQSTR($_POST[u_jumin2], "");
			isnull($u_jumin1);
			isnull($u_jumin2);
			$u_jumin2 = base64_encode($u_jumin2);

			$SQL_SEARCH = "
				and u_jumin1 = '".$u_jumin1."'
				and u_jumin2 = '".$u_jumin2."'
			";
		} else {
			$u_email = REQSTR($_POST[u_email], "");
			isnull($u_email);
			$SQL_SEARCH = "
				and (u_email1 + u_email2)  = '".$u_email."'
			";
		}



		$SQL = "
			select count(*)
			from
				tbl_user
			where
				u_name = '".$u_name."'
				$SQL_SEARCH
		";
		$cnt = $dbcon -> getCount($SQL);
//		echo $SQL."<BR>";
//		echo "result : " .$result."<BR>";

//		echo "cnt : ".$cnt ."<BR>";
//		echo $SQL."<BR>";
//		exit;

		if ( !$cnt ) {
			alert_back("정보가 일치하지 않습니다.");
		}


		$SQL = "
			select u_id
			from
				tbl_user
			where
				u_name = '".$u_name."'
				$SQL_SEARCH
		";
		$result = $dbcon -> query($SQL);
		if ( !$result ) {
			alert_back("정보가 일치하지 않습니다.");
		}
		$print_u_id = "";

		$i = 0;
		while ( $MRS = $dbcon -> fetch_row($result) ) {
			if ( $i > 0 ) {
				$print_u_id .= ", ";
			}
			$print_u_id .= $MRS[0];
			$i++;
		}
	}

	getLib();

	include_once $path_skin_member."find_id.php";
	$dbcon -> dbcon_close();
?>

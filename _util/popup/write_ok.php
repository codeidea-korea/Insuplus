<?
	$pop_subject	= REQSTR($_POST[pop_subject], "" );
	$pop_content	= $_POST[content];
	isnull($pop_subject);
	isnull($pop_content);

	$pop_use = REQSTR($_POST[pop_use], "Y" );

	$pop_sdate = REQSTR($_POST[pop_sdate], date("Y-m-d") );
	$pop_edate = REQSTR($_POST[pop_edate], date("Y-m-d") );
	$pop_sdate .= " 00:00:00";
	$pop_edate .= " 23:59:59";
	$pop_seq	= REQSTR($_POST[pop_seq], "");

	if (getLen($pop_seq) == 0 ) {
		$SQL = "
			insert into
				tbl_popup
			(
				pop_subject, pop_content, pop_use, pop_size_wid, pop_size_hei, pop_coor_top, pop_coor_left, pop_sdate, pop_edate, area
			)
			values
			(
				'".$pop_subject."', '".$pop_content."', '".$pop_use."', '".$pop_size_wid."', '".$pop_size_hei."', '".$pop_coor_top."', '".$pop_coor_left."', '".$pop_sdate."', '".$pop_edate."', '".$area."'
			)
		";

		$dbcon -> query($SQL);
		$dbcon -> dbcon_close();
		alert_page("등록되었습니다.","?mode=list");
		exit;
	}
	else {
		$SQL = "
			update tbl_popup
			set
				pop_subject = '".$pop_subject."'
				, pop_content = '".$pop_content."'
				, pop_use = '".$pop_use."'
				, pop_size_wid = '".$pop_size_wid."'
				, pop_size_hei = '".$pop_size_hei."'
				, pop_coor_top = '".$pop_coor_top."'
				, pop_coor_left = '".$pop_coor_left."'
				, pop_sdate = '".$pop_sdate."'
				, pop_edate = '".$pop_edate."'
				, area = '".$area."'
			where
				pop_seq = '".$pop_seq."'
		";
		$dbcon -> query($SQL);
		$dbcon -> dbcon_close();
		alert_page("수정되었습니다.","?mode=write&pop_seq=".$pop_seq);
		exit;
	}

?>
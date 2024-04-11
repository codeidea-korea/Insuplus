<?
	$pop_seq = REQSTR($pop_seq, "");
	isnull($pop_seq);

	$parameter = "page=".$page."&search=".$search."&search_text=".$search_text;

	$SQL = "
		delete from tbl_popup
		where
			pop_seq = '".$pop_seq."'
	";

	//echo $SQL."<BR>";exit;
	$dbcon -> query($SQL);

	$dbcon -> dbcon_close();

	alert_page("삭제되었습니다..","?mode=list&".$parameter);
	exit;
?>
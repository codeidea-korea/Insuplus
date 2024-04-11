<a name="CommentArea"></a>
<?
	if ( strlen($category) == 0 ) {
		alert_back("코멘트의 코드값이 없습니다.");
	}
	include "ProcForm.php";

	########################################
	#### 코멘트 쓰기폼
	if ( strlen($cmt_idx) > 0 ) {
		$SQL = "
			select
				u_id, comment
			from
				tbl_comment
			where
				idx = '".$idx."'
				and code = '".$category."'
		";
		$result = mysql_query($SQL, $dbcon);
		if (!$result) {
			alert_back("쿼리 실행 에러");
		}

		$rows = mysql_fetch_row($result);
		$modify_u_id		= $rows[0];
		$modify_comment		= $rows[1];
	}
	//echo "path_skin_comment : ".$path_skin_comment."<BR>";
	include $path_skin_comment."default/WriteForm.php";

	########################################

	########################################
	#### 코멘트 리스트 영역 Start.. ####
	$field = " * ";
	$table = " tbl_comment ";
	$where = " and category = '".$category."' and bc_id = '".$bc_id."' and seq = '".$seq."' ";
	$orderby = " regdate desc ";
	$limit = $first.", ".$last;

	$ArrCmtListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);

	$CmtTotalCnt = $ArrCmtListRs[0];

	include $path_skin_comment."default/ListHead.php";

	if ( $CmtTotalCnt > 0 ) {
		$cmt_num = 1;
		while ( $CmtListRs = $dbcon -> fetch_array($ArrCmtListRs[1]) ) {
			extract($CmtListRs);
			unset($CmtListRs);
			$PrintRegDate = date('Y/m/d', strtotime($regdate) );
			include $path_skin_comment."default/ListMain.php";
			$cmt_num++;
		} // end while
	}
	$total_cnt_page = ceil($CmtTotalCnt/$num_per_page);
	unset($ArrCmtListRs);
	include $path_skin_comment."default/ListFoot.php";

	########################################



?>

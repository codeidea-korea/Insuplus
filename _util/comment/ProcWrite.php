<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";

	$idx					= REQSTR($_POST["idx"],"");
	$mode				= REQSTR($_POST["mode"],"");

	$category					= REQSTR($_POST["category"],"");
	$bc_id						= REQSTR($_POST["bc_id"],"");
	$cmt_u_id					= REQSTR($_POST["cmt_u_id"],"");
	$cmt_u_pw					= REQSTR($_POST["cmt_u_pw"],"");
	$cmt_u_name				= REQSTR($_POST["cmt_u_name"],"");
	$cmt_content				= REQSTR($_POST["cmt_content"],"");

	if ( strlen($ss_u_id) > 0 ) {
		$result = getMemberInfo('u_id', $ss_u_id);
		$cmt_u_pw = $result[u_pw];
		$SQL_u_pw = " '".$cmt_u_pw."' ";
		unset($result);
	}
	else {
		$SQL_u_pw = " password('".$cmt_u_pw."') ";
	}

	$cmt_ip = getenv("REMOTE_ADDR");

	if ($mode == "insert" ) {
//		isnull($cmt_u_id);
//		isnull($cmt_u_pw);
		if ( strlen($cmt_u_id) == 0 && strlen($cmt_u_pw) == 0 ){
			alert_page("비밀번호가 누락되었습니다.","aa");
			exit;
		}

		$SQL = "
			insert into tbl_comment(
				category, bc_id, seq, cmt_u_id, cmt_u_pw, cmt_u_name, cmt_content, cmt_ip
			)
			values (
				'".$category."', '".$bc_id."', '".$seq."', '".$cmt_u_id."', ".$SQL_u_pw.", '".$cmt_u_name."', '".$cmt_content."', '".$cmt_ip."'
			)
		";
		//echo $SQL."<BR>";exit;
		$result = $dbcon -> query($SQL);
		if ( !$result ) {
			$dbcon -> dbcon_close();
			alert_page("입력실패","aa");
			exit;
		}
	}


	$dbcon -> dbcon_close();
	//alert_page("처리되었습니다."));
?>
<script>
	alert("처리되었습니다.");
	//parent.location.href = parent.location.href+"#CommentArea";
	parent.location.reload();
</script>
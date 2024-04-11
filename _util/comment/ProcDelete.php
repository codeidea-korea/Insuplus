<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";

	$idx							= REQSTR($_POST["idx"],"");
	$cmt_u_pw					= REQSTR($_POST["cmt_u_pw"],"");

//		isnull($idx);
	if ( strlen($idx) == 0 ) {
		alert_page("필수 정보 누락","aa");
	}

	if ( strlen($ss_u_id) == 0 ) {
		if ( strlen($cmt_u_pw) == 0 ) {
			alert_page("필수 정보 누락","aa");
		}
		$SQL = "
			select
				count(*)
			from
				tbl_comment
			where
				1=1
				and idx = '".$idx."'
				and cmt_u_id = ''
				and cmt_u_pw = password('".$cmt_u_pw."')
		";
		$resultcnt = $dbcon -> getCount($SQL);
		if ($resultcnt == 0 ) {
			$dbcon -> dbcon_close();
			alert_page("비밀번호가 일치하지 않습니다.","aa");
			exit;
		}

		$SQL = "
			delete from tbl_comment
			where
				idx = '".$idx."'
		";
		$result = $dbcon -> query($SQL);
		if ( !$result ) {
			$dbcon -> dbcon_close();
			alert_page("삭제 실패","aa");
			exit;
		}
	}
	else {
		$SQL = "
			select cmt_u_id
			from tbl_comment
			where
				idx = '".$idx."'
		";
		$result = $dbcon -> fetch_row($dbcon -> query($SQL));
		$old_cmt_u_id = $result[0];

		if ( $ss_u_level >= $auth_admin || $ss_u_id == $old_cmt_u_id ) {
			$SQL = "
				delete from tbl_comment
				where
					idx = '".$idx."'
			";
			$result = $dbcon -> query($SQL);
			if ( !$result ) {
				$dbcon -> dbcon_close();
				alert_page("삭제 실패","aa");
				exit;
			}
		}
		else {
			$dbcon -> dbcon_close();
			alert_page("삭제 권한이 없습니다.","");
			exit;
		}
	}

	$dbcon -> dbcon_close();

?>


<script>
	alert("처리되었습니다.");
	parent.location.reload();
</script>

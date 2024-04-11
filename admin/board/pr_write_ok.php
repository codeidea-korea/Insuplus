<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$idx						= REQSTR($_POST[idx], "");
	// 시술프로그램 받기
	for ($k=0;$k<sizeof($prs);$k++){
		if ($prs[$k]){
			if ($k==0){
			$pr_list = $prs[$k];
			}else{
			$pr_list .= "@".$prs[$k];
			}
		}
	}

	isnull($code);

// 정보수정
	if ( getLen($idx) ) {

		$SQL = "
			update tbl_symptom
			set
				pr_name = '".$pr_list."'
				, code = '".$code."'
			where
				idx = '".$idx."'
		";
//		echo "SQL : ".$SQL."<BR>";
//		exit;
		$result = $dbcon -> query($SQL);
		if (!$result) {
			$dbcon -> dbcon_close();
			alert_back("데이터에 오류가 있어 수정이 되지 않습니다.");
			exit;
		}

	}else{
// 새로 입력

		$SQL = "
					INSERT INTO tbl_symptom set
					pr_name = '".$pr_list."'
					, code = '".$code."'
				";
//		echo "SQL : ".$SQL."<BR>";
//		exit;
		$result = $dbcon -> query($SQL);



		// 쿼리 에러시에 해당 아이디 모두 삭제
		if (!$result) {
			$dbcon -> dbcon_close();
			alert_back("데이터에 오류가 있어 입력이 되지 않습니다.");
			exit;
		}
	}

	$dbcon -> dbcon_close();

	// 정보수정
	if ( strlen($idx) > 0 ) {
		alert_page("증상별 프로그램 정보가 수정되었습니다.", "pr_write.php?idx=".$idx);
	}
	// 신규가입
	else {
		alert_page("", "pr_list.php");
	}
?>

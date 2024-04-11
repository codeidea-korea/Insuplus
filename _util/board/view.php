<?
	$seq = REQSTR($seq,"");
	$seq_sub = REQSTR($_GET[seq_sub],"");

	isnull($seq);
	//isnull($seq_sub);


	########################################
	#### 게시글을 가져온다.. ####
	$field = " * ";
		if ($bc_category_use == "Y") {
			$field .= " , category, (select cate_name from tbl_category where category='board' and bc_id = '".$bc_id."' and idx = A.category ) as cate_name";
		}
	$table = "tbl_board_".$bc_id." A";
	$where = " and seq = '".$seq."' ";
	$where .= $query_where;
	$orderby = " seq_sub desc ";
	$limit = "0, 1";
//echo $where;
	$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);

	$total_record = $ArrListRs[0];
	if ( $total_record == 0 ) {
		$dbcon -> dbcon_close();
		alert_back("게시판 정보가 누락되었습니다.");
		exit;
	}

	$ListRs = $dbcon -> fetch_array($ArrListRs[1]);
	extract($ListRs);
	unset($ListRs);
	unset($ArrListRs);

	//isnull($seq_sub);
//	echo "seq_sub : ".$seq_sub."<BR>";
//	echo "seq_level : ".$seq_level."<BR>";
//
//	echo "auth_admin : ".$auth_admin."<BR>";
//	echo "auth_level : ".$auth_level."<BR>";
//
//	echo ( $auth_level < $auth_admin)."aaa<BR>";
//	echo ( $now_writer != $writer && $writer != "guest" ) ."bbb<BR>";
//
//	echo "ss_u_level : ".$ss_u_level."<BR>";
//
//	echo "now_writer : ".$now_writer."<BR>";
//	echo "writer : ".$writer."<BR>";

	//exit;

	// 비밀글 사용시
	if ( $bc_secret_use == "Y" && $secret == "Y" ) {

		$checkPass = true;

		// 관리자는 그냥 패스~
		if ( $auth_level >= $auth_admin ) {
			$checkPass = false;
		}


		// 현재 접속자가 게시글 등록자이거나 현재접속자가 비회원이 아니라면
		if ( $now_writer == $writer && $writer != "guest" ) {
			$checkPass = false;
		}

		// 답변글이고
		if ( $seq_level > 0 ) {
			$SQL = "
				select writer from tbl_board_".$bc_id." where seq_sub = '".($seq_sub+1)."'
			";
			$Parent_writer = $dbcon -> getCount($SQL);

			//echo "Parent_writer : ".$Parent_writer."<BR>";

			//현재 접속자가 부모글의 작성자이거나 현재접속자가 비회원이 아니라면
			if ( $Parent_writer == $now_writer && $writer != "guest" ) {
				$checkPass = false;
			}
			unset ($Parent_writer);
		}

	}

	#############################
	#### 비밀번호 체크 시작
	if ( $checkPass ) {
		$act = REQSTR($act,"");
		//echo $act."<BR>";exit;
		if ( $act != "confirm" ) {
			alert_back("비밀번호가 입력되지 않았거나 잘못된 접근입니다.");
			exit;
		}
		$Confirm_seq = REQSTR($_POST[seq],"");
		$Confirm_passwd = REQSTR($_POST[passwd],"");
		if ( $auth_level < $auth_admin ) {
			isnull($passwd);
			isnull($Confirm_seq);
			isnull($Confirm_passwd);
			$SQL = "
				select
					count(*)
				from
					tbl_board_".$bc_id."
				where
					seq = '".$Confirm_seq."'
					and passwd = password('".$Confirm_passwd."')
				order by seq desc
				limit 0, 1
			";
			$PassRs = $dbcon -> getCount($SQL);

//				echo $SQL."<BR>";
//				echo $PassRs."<BR>";
//				exit;
			if ($PassRs == 0) {
				$dbcon -> dbcon_close();
				alert_back("비밀번호가 다릅니다.");
				exit;
			}
		}
	}
	unset ($checkPass);
	#### 비밀번호 체크 끝
	#############################


	########################################
	#### 조회수 올리기.. ####
	$flag_cnt = false;

	//echo "board_view : ".$_COOKIE["board_view"]."<BR>";

	if ( !strpos($_COOKIE['board_view'], ",".$seq.'_view') ) {
		$flag_cnt = true;
	}

	//echo "flag_cnt : ".$flag_cnt."<BR>";

	if ($flag_cnt) {
		$SQL = "
			update tbl_board_".$bc_id."
			set
				view_cnt = view_cnt + 1
			where
				seq = '".$seq."'
		";
		$dbcon -> query($SQL);
//		$_COOKIE["board_view"] = $_COOKIE["board_view"].",".$seq.'_view';
//		if (!headers_sent()) {
//			setcookie("board_view", $_COOKIE["board_view"], 0, "/");
//			//setcookie("board_view", $_COOKIE["board_view"], 0, "/", "", 1);
//		}
	}
	########################################

	$subject = getStrCut( $subject , 90, "...");
//	if ( getLen($subject) > 90 ) {
//		$subject = mb_substr($subject,0,90,"UTF-8")."...";
//	}

	$PrintRegDate = date('Y.m.d ', strtotime($regdate) );
	// NEW 이미지
	$sNew="";
	if(strtotime($regdate) > (time() - (60 * 60 * 24 * 2))) {
		$sNew = $img_new;
	}

	//$content = REQSTR2($content);
	########################################

	if ( $bc_editor_use == "Y" ) {
		$content = RESSTR($content);
	}
	else {
		$content = RESSTRTEXT($content);
	}


	// #### hidden 표시
	$print_hidden = "";
	if ( $bc_hidden_use == "Y" && $auth_hidden) {
		if ($hidden == "Y") {
			$print_hidden = "<font color='red'>[hidden]</font>";
		}
	}

	#### 공지글 설정 시작
	$print_notice = "";
	if ( $bc_notice_use == "Y" ) {
		if ( $notice == "Y" ) {
			$print_notice = $img_notice;
		}
	}

	// #### hidden 표시
	$print_hidden = "";
	if ( $bc_hidden_use == "Y" && $auth_hidden) {
		if ($hidden == "Y") {
			$print_hidden = "<font color='red'>[hidden]</font>";
		}
	}

	// #### 카테고리 표시
	$print_cate_name = "";
	if ($bc_category_use == "Y" && $category) {
		$print_cate_name = $cate_name;
	}

	// #### 첨부파일 처리
	if ($bc_upfile_cnt > 0 ) {
		$SQL = "
			select *
			from tbl_file
			where
				category='board' and bc_id = '".$bc_id."' and seq = '".$seq."'
		";
		$FileRs = $dbcon -> query($SQL);

        $SQL_Cnt = "
			select count(*)
			from tbl_file
			where
				category='board' and bc_id = '".$bc_id."' and seq = '".$seq."'
		";

        $FileCnt = $dbcon -> getCount($SQL_Cnt);

	}

	########################################
	#### 이전글, 다음글
	$field = " seq, seq_level, subject, secret ";
	$table = "tbl_board_".$bc_id;
	$where = " and seq < '".$seq."' ".$query_where;
	$orderby = " seq_sub desc ";
	$limit = "0, 1";
	$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);

	$total_record = $ArrListRs[0];

	if ( $total_record == 0 ) {
		$prev_seq				= "";
		$prev_seq_sub			= "";
		$prev_seq_level		= "";
		$prev_subject			= "이전글이 없습니다.";
		$prev_view_link		= "<a>";
	}
	else {
		$ListRs = $dbcon -> fetch_row($ArrListRs[1]);
		$prev_seq				= $ListRs[0];
		$prev_seq_level		= $ListRs[1];
		$prev_secret			= $ListRs[3];

		$print_secret = "";
		$prev_view_link = "<a href=\"javascript:view_go('".$prev_seq."');\">";
		if ( $bc_secret_use == "Y" && $prev_secret == "Y") {
			$print_secret = "<img src=\"".$url_skin_board.$bc_skin."/images/icon_secret.gif\">";
			$prev_view_link = "<a href=\"javascript:view_go_secret('".$prev_seq."');\">";
		}

		$prev_subject			= $prev_view_link.$ListRs[2].$print_secret."</a>";

	}
	unset($ListRs);
	unset($ArrListRs);

	$field = " seq, seq_level, subject, secret ";
	$table = "tbl_board_".$bc_id;
	$where = " and seq > '".$seq."' ".$query_where;
	$orderby = " seq_sub asc ";
	$limit = "0, 1";

	$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);

	$total_record = $ArrListRs[0];
	if ( $total_record == 0 ) {
		$next_seq				= "";
		$next_seq_sub			= "";
		$next_seq_level		= "";
		$next_subject			= "다음글이 없습니다.";
		$next_view_link		= "<a>";
	}
	else {
		$ListRs = $dbcon -> fetch_row($ArrListRs[1]);
		$next_seq				= $ListRs[0];
		$next_seq_level		= $ListRs[1];
		$next_secret			= $ListRs[3];

		$print_secret = "";
		$next_view_link = "<a href=\"javascript:view_go('".$next_seq."');\">";
		if ( $bc_secret_use == "Y" && $next_secret == "Y") {
			$print_secret = "<img src=\"".$url_skin_board.$bc_skin."/images/icon_secret.gif\">";
			$next_view_link = "<a href=\"javascript:view_go_secret('".$next_seq."');\">";
		}

		$next_subject			= $next_view_link.$ListRs[2].$print_secret."</a>";

	}
	unset($ListRs);
	unset($ArrListRs);
	########################################

	include $path_skin_board.$bc_skin."/view.php";

?>


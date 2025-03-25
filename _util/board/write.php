<?

	// 글쓰기, 수정일때
	if ( $mode == "mod") {

		#############################
		#### 비밀번호 체크 시작
		$act = REQSTR($act,"");
		if ( $act == "confirm" ) {
			$Confirm_seq = REQSTR($seq,"");
			$Confirm_passwd = REQSTR($passwd,"");


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

		#### 비밀번호 체크 끝
		#############################


		########################################
		#### 게시글을 가져온다.. ####
		$field = " *";
		if ($bc_category_use == "Y") {
			$field .= " , (select cate_name from tbl_category where category='board' and bc_id = '".$bc_id."' and idx = A.category ) as cate_name";
		}
		$table = "tbl_board_".$bc_id." A";
		$where = " and seq = '".$seq."' ";
		$orderby = " seq_sub desc ";
		$limit = "0, 1";

		$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);

		$total_record = $ArrListRs[0];
		if ( $total_record == 0 ) {
			$dbcon -> dbcon_close();
			alert_back("게시판 정보가 누락되었습니다.");
			exit;
		}
		$ListRs = $dbcon -> fetch_array($ArrListRs[1]);

		########################################
		extract($ListRs);
		unset($ListRs);
		unset($ArrListRs);

		if ($auth_level > 0 && $auth_level < $auth_admin ) {
			if ($writer != $ss_u_id) {
				$dbcon -> dbcon_close();
				alert_back("다른사람의 글을 수정할 수 없습니다.");
				exit;
			}
		}

		$PrintRegDate = date('Y/m/d ', strtotime($regdate) );

	}

	// 답변글일때
	else if ( $mode == "reply" ) {
		########################################
		#### 게시글을 가져온다.. ####
		$field = " seq_sub, seq_level, subject, content, email1, email2 ";
		if ($bc_category_use == "Y") {
			$field .= " , category, (select cate_name from tbl_category where category='board' and bc_id = '".$bc_id."' and idx = A.category ) as cate_name";
		}
		$table = "tbl_board_".$bc_id." A";
		$where = " and seq = '".$seq."' ";
		$orderby = " seq_sub desc ";
		$limit = "0, 1";

		$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);

		$total_record = $ArrListRs[0];
		if ( $total_record == 0 ) {
			$dbcon -> dbcon_close();
			alert_back("게시판 정보가 누락되었습니다.");
			exit;
		}
		$ListRs = $dbcon -> fetch_array($ArrListRs[1]);

		########################################

		$seq_sub			= $ListRs["seq_sub"];
		$seq_level			= $ListRs["seq_level"];
		//$subject				= $ListRs["subject"];
		//$content			= $ListRs["content"];
		$old_email			= $ListRs["email1"]."@".$ListRs["email2"];
		$old_name			= $ListRs["nick_name"];
		$category			= $ListRs["category"];
		$cate_name		= $ListRs["cate_name"];
		unset($ListRs);
		unset($ArrListRs);

//		$content = ">".str_replace("<BR>","<BR>::",$content)."<BR>";
//		$content = "======================================<BR>".$content;
//		$content = $content."======================================<BR><BR>";

		$writer = $now_writer;
		$nick_name = $now_nick_name;
		$passwd = $now_passwd;
		$email1 = $now_email1;
		$email2 = $now_email2;
		$homepage = $now_homepage;

	}



	else if ( $mode == "write" ) {
		$writer = $now_writer;
		$nick_name = $now_nick_name;
		$passwd = $now_passwd;
		$email1 = $now_email1;
		$email2 = $now_email2;
		$homepage = $now_homepage;
	}


	###########################################
	#### 에디터 설정
	if ($bc_editor_use == "Y" ) {
		include_once($path_editor.'func_editor.php');
	}
	else {
		$content = REQSTR2($content);
	}
	###########################################

?>

<? 
// echo $path_skin_board.$bc_skin;
include $path_skin_board.$bc_skin."/write.php";?>

<script>

	function WriteOkGo() {
		ff = document.WriteForm;

		if ( !ff.nick_name.value) {
			alert("작성자명을 입력하여 주십시오.");
			ff.nick_name.focus();
			return false;
		}

		<? if ( $auth_level == 0 ) { ?>
			if (!ff.passwd.value) {
				alert("비밀번호를 입력하여 주십시오.");
				ff.passwd.focus();
				return false;
			}
		<? } ?>

		if (!ff.subject.value) {
			alert("제목을 입력하여 주십시오.");
			ff.subject.focus();
			return false;
		}

		<? if ( $bc_email_use == "Y") { ?>
			if (!ff.email1.value || !ff.email2.value ) {
				alert("E-mail 주소를 입력하여 주십시오.");
				ff.email1.focus();
				return false;
			}
		<? } ?>



		<? if ($bc_editor_use == "Y") { ?>
			// 에디터의 내용을 textarea 에 삽입
//			if(!checkSpacContents()) {
//				alert("내용을 입력해주세요");
//				editor.focus();
//				return false;
//			}
			if(ff.content.value == "") {
				alert("내용을 입력해주세요");
				webnote.focusWebNote("content")		//에디터에 포커스를 주기위한 webnote 내장함수
				return false;
			}
		<? } else { ?>

			if(!ff.content.value) {
				alert('내용을 입력하세요!');
				ff.content.focus();
				return false;
			}
		<? } ?>


		<? if ( $bc_upfile_cnt > 0 && $list_type == "gallery" && $mode=="write") { ?>
			var up_file = document.getElementsByName('up_file');
			if(up_file.length > 0){
				if ( !up_file[0].value ) {
					alert("첨부파일 1개 이상 올려주세요.");
					up_file[0].focus();
					return;
				}
			}
		<? } ?>

		<? if ($bc_autoreg_use == "Y") { ?>
			if ( !ff.signupcode.value ) {
				alert('자동등록 방지를 위해 이미지에 보이는 글자를 입력하여 주십시오.');
				ff.signupcode.focus();
				return false;
			}
		<? } ?>


		<? if ( $mode == "mod" ) { ?>
			ff.mode.value = "mod_ok";
			//ff.action = "notice_mod_ok.php";
		<? } elseif ( $mode == "write" ) { ?>
			ff.mode.value = "write_ok";
			//ff.action = "notice_write_ok.php";
		<? } elseif ( $mode == "reply" ) { ?>
			ff.mode.value = "reply_ok";
			//ff.action = "notice_write_ok.php";
		<? } ?>
		ff.action = "<?=$PHP_SELF?>";
		ff.target = "board_iframe";
	}


	function WriteOk_qnaGo() {
		ff = document.WriteForm;

		if ( !ff.nick_name.value) {
			alert("작성자명을 입력하여 주십시오.");
			ff.nick_name.focus();
			return;
		}

		<? if ( $auth_level == 0 ) { ?>
			if (!ff.passwd.value) {
				alert("비밀번호를 입력하여 주십시오.");
				ff.passwd.focus();
				return;
			}
		<? } ?>

		if (!ff.subject.value) {
			alert("제목을 입력하여 주십시오.");
			ff.subject.focus();
			return;
		}

		<? if ( $bc_email_use == "Y") { ?>
			if (!ff.email1.value || !ff.email2.value ) {
				alert("E-mail 주소를 입력하여 주십시오.");
				ff.email1.focus();
				return;
			}
		<? } ?>



		<? if ($bc_editor_use == "Y") { ?>
			// 에디터의 내용을 textarea 에 삽입
//			if(!checkSpacContents()) {
//				alert("내용을 입력해주세요");
//				editor.focus();
//				return;
//			}

			// 신규 웹노트 에디터
			if(ff.content.value == "") {
				alert("내용을 입력해주세요");
				webnote.focusWebNote("content")		//에디터에 포커스를 주기위한 webnote 내장함수
				//focusWebNote("contents1");
				return false;
			}
		<? } else { ?>

			if(!ff.content.value) {
				alert('내용을 입력하세요!');
				ff.content.focus();
				return;
			}
		<? } ?>


		<? if ( $bc_upfile_cnt > 0 && $list_type == "gallery" && $mode=="write") { ?>
			var up_file = document.getElementsByName('up_file');
			if(up_file.length > 0){
				if ( !up_file[0].value ) {
					alert("첨부파일 1개 이상 올려주세요.");
					up_file[0].focus();
					return;
				}
			}
		<? } ?>

		<? if ($bc_autoreg_use == "Y") { ?>
			if ( !ff.signupcode.value ) {
				alert('자동등록 방지를 위해 이미지에 보이는 글자를 입력하여 주십시오.');
				ff.signupcode.focus();
				return;
			}
		<? } ?>


		<? if ( $mode == "mod" ) { ?>
			ff.mode.value = "mod_ok";
		<? } elseif ( $mode == "write" ) { ?>
			ff.mode.value = "write_ok";
		<? } elseif ( $mode == "reply" ) { ?>
			ff.mode.value = "reply_ok";
		<? } ?>
		ff.action = "<?=$PHP_SELF?>";
		ff.target = "board_iframe";
	}
</script>

<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

	if ( !(getLen($bc_id) > 0 ) ) {
		alert_back("게시판 정보가 누락되었습니다.");
		exit;
	}

// 게시판 테이블 생성
//	$tbl_insert_tbl_board = str_replace("[boardname]", $bc_id, $tbl_insert_tbl_board);
//	$dbcon -> query($tbl_insert_tbl_board);

	#### 게시판 설정 가져오기
	$field = " * ";
	$table = "config_board_list";
	$where = " and bc_id = '".$bc_id."' ";
	$orderby = " bc_id asc ";
	$limit = " 0, 1 ";

	$ArrListRs = $dbcon -> getList($field = "*", $table , $where, $orderby, $limit);

	if ( $ArrListRs[0] == 0 ) {
		$dbcon -> dbcon_close();
		alert_back("잘못된 게시판 정보입니다.1");
		exit;
	}

	$ListRs = $dbcon -> fetch_array($ArrListRs[1]);
	extract($ListRs);

//echo "<xmp>";
//print_r($ListRs);
//echo "</xmp>";


//	$TempNo = 0;
//	foreach ($ListRs as $key => $value) {
//		echo "no : ".$TempNo."<BR>";
//		echo "key : ".$key."<BR>";
//		echo "value : ".$value."<BR><BR>";
//		$TempNo++;
//	}
	unset($ListRs);
	unset($ArrListRs);
//	echo "bc_name : ".$bc_name."<BR>";
//	exit;


	// 메일 추가
	@include_once $path_skin_mail."mail_01.php";

	$parameter .= "&bc_id=".$bc_id;

	#### 로그인 체크
	if ($bc_login_check == "Y") {
		if ( getLen($ss_u_idx) == 0 ) {
			$dbcon -> dbcon_close();
			alert_page("로그인 후 이용해 주십시오.",$url_login);
			exit;
		}
	}

	#### 게시판 권한설정
	if ( getLen($ss_u_level) > 0 ) {
		$auth_level			= $ss_u_level;
	} else {
		$auth_level			= 0;
	}

	// 관리자 설정
	if ( $auth_level >= $auth_admin ) {
		$auth_view			= 1;
		$auth_write			= 1;
		$auth_modify		= 1;
		$auth_delete		= 1;
		$auth_notice		= 1;
		$auth_reply	 		= 1;
		$auth_comment	= 1;
		$auth_hidden		= 1;
		$auth_upload		= 1;
		$auth_download	= 1;
		$auth_secret		= 1;
	}
	else {
		if ( $auth_level >= $bc_auth_view ) $auth_view				= 1;
		if ( $auth_level >= $bc_auth_write ) $auth_write				= 1;
		if ( $auth_level >= $bc_auth_modify ) $auth_modify			= 1;
		if ( $auth_level >= $bc_auth_delete ) $auth_delete			= 1;
		if ( $auth_level >= $bc_auth_notice ) $auth_notice			= 1;
		if ( $auth_level >= $bc_auth_reply ) $auth_reply	 			= 1;
		if ( $auth_level >= $bc_auth_comment ) $auth_comment	= 1;
		if ( $auth_level >= $bc_auth_hidden ) $auth_hidden			= 1;
		if ( $auth_level >= $bc_auth_upload ) $auth_upload			= 1;
		if ( $auth_level >= $bc_auth_download ) $auth_download	= 1;
		if ( $auth_level >= $bc_auth_secret ) $auth_secret		= 1;
	}

	#### 접속자 정보 가져오기 ####
	if ($auth_level > 0) {
		$MEMRS = getMemberInfo("u_id", $ss_u_id);
//		extract($MEMRS);
//		$TempNo = 0;
//		foreach ($ListRs as $key => $value) {
//			echo "no : ".$TempNo."<BR>";
//			echo "key : ".$key."<BR>";
//			echo "value : ".$value."<BR><BR>";
//			$TempNo++;
//		}
		$now_writer			= $MEMRS["u_id"];
		$now_nick_name		= $MEMRS["u_name"];
		$now_passwd			= $MEMRS["u_pw"];
		$now_email1			= $MEMRS["u_email1"];
		$now_email2			= $MEMRS["u_email2"];
		$now_homepage		= $MEMRS["u_homepage"];
		unset($MEMRS);
	}
	else {
		$now_writer				= "guest";
		$now_nick_name		= "";
		$now_passwd			= "";
		$now_email1			= "";
		$now_email2			= "";
		$now_homepage		= "";
	}

	#### mode 설정
	if (!$mode) {
		if ($auth_level >= $auth_admin) $mode = "list";
		else $mode = $bc_url_start;
	}

	// 설정 추가
	include_once $path_skin_board.$bc_skin."/config.php";

	// 블로그형에서 사용함.
	if ($mode == "view" && !$seq ) {
		$TempCnt = $dbcon -> getCount(" select count(seq) from tbl_board_".$bc_id." where 1=1 ".$query_where." ");
		if ( !$TempCnt ) $mode = "list";
		$seq = $dbcon -> getCount(" select seq from tbl_board_".$bc_id." where 1=1 ".$query_where." order by seq_sub desc limit 0, 1");
	}

//	if ($bc_url_start != "list") {
//		if ($auth_level >= $auth_admin) $mode = "list";
//		else $mode = $bc_url_start;
//	}

	########################

	// echo "mode : ".$mode."<BR>";
//
//	echo "ss_u_level : ".$ss_u_level."<BR>";
//	echo "auth_level : ".$auth_level."<BR>";
//	echo "auth_view : ".$auth_view."<BR>";
//	echo "auth_write : ".$auth_write."<BR>";
//	echo "auth_modify : ".$auth_modify."<BR>";
//	echo "auth_delete : ".$auth_delete."<BR>";
//	echo "auth_notice : ".$auth_notice."<BR>";
//	echo "auth_reply : ".$auth_reply."<BR>";
//	echo "auth_comment : ".$auth_comment."<BR>";
//	echo "auth_hidden : ".$auth_hidden."<BR>";
//	echo "auth_upload : ".$auth_upload."<BR>";
//	echo "auth_download : ".$auth_download."<BR>";
//	echo "auth_secret : ".$auth_secret."<BR>";
//
//	echo "now_writer : ".$now_writer."<BR>";
//	echo "now_nick_name : ".$now_nick_name."<BR>";
//	echo "now_passwd : ".$now_passwd."<BR>";
//	exit;

	if ( getLen($bc_top_include) )  @include_once $bc_top_include;
	if ( getLen($bc_top_html) )  echo $bc_top_html;
 
	
	switch ($mode) {
		case "list" :
			include_once "list.php";
			break;
		case "write" :
			if ( !$auth_write ) {
				$dbcon -> dbcon_close();
				alert_back("권한이 없습니다.","?mode=list");
				exit;
			}
			include_once "write.php";
			break;
		case "write_ok" :
			if ( !$auth_write ) {
				$dbcon -> dbcon_close();
				alert_back("권한이 없습니다.","?mode=list");
				exit;
			}
			include_once "write_ok.php";
			break;
		case "mod" :
			if ( !$auth_modify ) {
				$dbcon -> dbcon_close();
				alert_back("권한이 없습니다.","?mode=list");
				exit;
			}
			$seq = REQSTR($seq, 0);
			isnull($seq);
			include_once "write.php";
			break;
		case "mod_ok" :
			if ( !$auth_modify ) {
				$dbcon -> dbcon_close();
				alert_back("권한이 없습니다.","?mode=list");
				exit;
			}
			include_once "mod_ok.php";
			break;

		case "reply" :
			if ( !$auth_reply ) {
				$dbcon -> dbcon_close();
				alert_back("권한이 없습니다.","?mode=list");
				exit;
			}
			$seq = REQSTR($seq, 0);
			isnull($seq);
			include_once "write.php";
			break;

		case "reply_ok" :
			if ( !$auth_reply ) {
				$dbcon -> dbcon_close();
				alert_back("권한이 없습니다.","?mode=list");
				exit;
			}
			include_once "reply_ok.php";
			break;

		case "view" :
			if ( !$auth_view ) {
				$dbcon -> dbcon_close();
				alert_back("권한이 없습니다.","?mode=list");
				exit;
			}
			$seq = REQSTR($seq, 0);
			isnull($seq);
			include_once "view.php";
			break;
		case "del_ok" :
			if ( !$auth_delete ) {
				$dbcon -> dbcon_close();
				alert_back("권한이 없습니다.","?mode=list");
				exit;
			}
			include_once "del_ok.php";
			break;
		case "confirm" :
			include_once "confirm.php";
			break;
	}

	if ( getLen($bc_bottom_include) )  @include_once $bc_bottom_include;
	if ( getLen($bc_bottom_html) )  echo $bc_bottom_html;


?>
<script>
	function view_go(seq) {
		<? if ($auth_view || $auth_level >= $auth_admin) { ?>
			location.href = "?mode=view&seq="+seq+"&page=<?=$page?><?=$parameter?>";
		<? } else { ?>
			alert("<?=$msg_login_auth?>");
		<? } ?>
	}
	function view_ongo(seq) {
		<? if ($auth_view || $auth_level >= $auth_admin) { ?>
			window.open("?mode=view&seq="+seq+"&page=<?=$page?><?=$parameter?>","","");
		<? } else { ?>
			alert("<?=$msg_login_auth?>");
		<? } ?>
	}
	function view_selfgo(seq) {
		<? if ($auth_view || $auth_level >= $auth_admin) { ?>
			location.href = "?mode=list&seq="+seq+"&page=<?=$page?><?=$parameter?>";
		<? } else { ?>
			alert("<?=$msg_login_auth?>");
		<? } ?>
	}

	function view_go_secret(seq) {
		<? if ( $bc_secret_use == "Y" ) { ?>
			<? if ( $auth_level >= $auth_admin ) { ?>
				location.href = "?mode=view&seq="+seq+"&page=<?=$page?><?=$parameter?>";
			<? } else if ( $auth_view && $auth_level < $auth_admin) { ?>
//				confirm_go(seq, "viewgo");
				alert("비공개 글은 본인만 확인할 수 있습니다.");
				return;
			<? } else { ?>
				alert("<?=$msg_login_auth?>");
			<? } ?>
		<? } ?>
	}

	function write_go() {
		<? if ($auth_write || $auth_level >= $auth_admin) { ?>
			location.href = "?mode=write<?=$parameter?>";
		<? } else { ?>
			alert("<?=$msg_login_auth?>");
		<? } ?>
	}

	function mod_go(seq) {
		<? if ( $auth_level >= $auth_admin || ( $now_writer == $writer && $now_writer != "guest" ) ) { ?>
				location.href = "?mode=mod&seq="+seq+"&page=<?=$page?><?=$parameter?>";
		<? } else if ( $auth_modify && $auth_level < $auth_admin) { ?>
				confirm_go(seq, "modgo");
		<? } else { ?>
			alert("<?=$msg_login_auth?>");
		<? } ?>
	}

	function confirm_go(seq, act) {
		location.href = "?mode=confirm&seq="+seq+"&act="+act+"&page=<?=$page?><?=$parameter?>";
	}

	function del_go(seq) {
		if (confirm("정말로 삭제 하시겠습니까?\n삭제한 정보는 복구가 불가능합니다.")) {
		<? if ( $auth_level >= $auth_admin || ( $now_writer == $writer && $now_writer != "guest" ) ) { ?>
				location.href = "?mode=del_ok&seq="+seq+"<?=$parameter?>";
		<? } else if ( $auth_delete && $auth_level < $auth_admin) { ?>
				confirm_go(seq, "delgo");
		<? } else { ?>
			alert("<?=$msg_login_auth?>");
		<? } ?>
		}
	}

	function reply_go(seq) {
		<? if ($auth_reply) { ?>
			location.href = "?mode=reply&seq="+seq+"<?=$parameter?>";
		<? } else { ?>
			alert("<?=$msg_login_auth?>");
		<? } ?>
	}

	function list_go() {
		location.href = "?mode=list&page=<?=$page?><?=$parameter?>";
	}

	function up_file_disabled(str, targ) {
		target = eval("document.form1."+targ);
		if (str.checked) {
			target.disabled = false;
		} else {
			target.disabled = true;
		}
	}

	function search_go() {
		ff = document.SearchForm;
		/*
		if (!ff.search_text.value) {
			alert("검색어를 입력해 주십시오.");
			ff.search_text.focus();
			return;
		}
		*/
	}

	<? if ($bc_category_use == "Y") { ?>
	function cate_go(category) {
		ff = document.SearchForm;
		ff.search_category.value = category;
		ff.submit();
	}
	<? } ?>


	function down_go(idx, category, bc_id, seq) {
		<? if ($auth_download) { ?>
			board_iframe.location.href = "<?=$url_data?>_download.php?idx="+idx+"&category="+category+"&bc_id="+bc_id+"&seq="+seq;
		<? } else { ?>
			alert("<?=$msg_login_auth?>");
		<? } ?>
	}

	function page_go(page) {
		location.href = "?mode=list&page="+page+"&<?=$GLOBALS["parameter"]?>";
	}

	function page_comment_go(page) {
		location.href = "?mode=view&seq=<?=$seq?>&page="+page+"&<?=$GLOBALS["parameter"]?>";
	}
</script>

<?if ($ss_u_id=="izm9870"){?>
<iframe src="" name="board_iframe" id="board_iframe" frameborder="1" width="600" height="300" marginheight="0" marginwidth="0" scrolling="auto"></iframe>
<?}else{?>
<iframe src="" name="board_iframe" id="board_iframe" frameborder="0" width="0" height="0" marginheight="0" marginwidth="0" scrolling="auto"></iframe>
<?}?>


<?
	if ($bc_category_use == "Y" ) {
		unset($CateListRs);
		unset($ArrCateListRs);
	}

	$dbcon -> dbcon_close();
?>

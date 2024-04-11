<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
?>
<?

	####################################
	#### 페이지 설정
	$mode = REQSTR($mode, "list");

	if ( getLen($mode) == 0 ) {
		$mode = "list";
	}

	// 페이지 설정
	$page				= REQSTR($page, 1);
	$num_per_page	= REQSTR($num_per_page, 10);
	$page_per_block	= REQSTR($page_per_block, 10);
	$first					= $num_per_page*($page-1);
	$last					= $num_per_page*$page;

	if ( (getLen($search_text) > 0) ) {
		$query_where = " and ".$search." like '%".$search_text."%'";
	}

	$upload_root = $path_popup_data;


	// new 이미지
	$img_new = "<img src='".$site_root."images/common/icn_new.gif' align='absmiddle' border='0'>";
	$img_blank = "<img src='".$site_root."images/common/icon_re.gif' align='absmiddle' border='0'>";

	$upload_size = "2048000";

	$image_view_width = 600;

	$table_id = "popup";

	####################################


	$auth_view					= 1;
	$auth_write					= 1;
	$auth_modify				= 1;
	$auth_delete				= 1;



//	#### 게시판 명과 로그인 체크 권한을 가져온다.
//	$SQL = "
//		select
//			table_name, login_check, notice_use, reply_use, upfile_cnt
//		from
//			Tbl_board_list
//		where
//			table_id = '".$table_id."'
//		order by table_id
//		limit 0, 1
//	";
//	//echo $SQL."<BR>";
//
//	$list_rs = $dbcon -> query($SQL);
//
//	$list_num_rows = mysql_num_rows($list_rs);
//	if ($list_num_rows == 0) {
//		$dbcon -> dbcon_close();
//		//echo "게시판 설정 오류. 관리자에게 문의하여 주십시오."."<BR>";
//		alert_back("게시판 설정 오류. 관리자에게 문의하여 주십시오.");
//		exit;
//	}
//
//	$list_rows = mysql_fetch_array($list_rs);
//	$table_name				= $list_rows["table_name"];
//	$login_check				= $list_rows["login_check"];
//	$notice_use				= $list_rows["notice_use"];
//	$reply_use					= $list_rows["reply_use"];
//	$upfile_cnt					= $list_rows["upfile_cnt"];
//
//
//	#### 로그인 체크
//	if ($login_check == "Y") {
//		if ( !( strlen($ss_men_num) > 0 ) ) {
//			$dbcon -> dbcon_close();
//			alert_page("로그인 후 이용해 주십시오.","/");//$site_root."member/login.php"
//			exit;
//		}
//	}
//
//	#### 게시판 권한설정을 가져온다.
//	if ( strlen($ss_mem_level) > 0 ) {
//		$auth_level			= $ss_mem_level;
//	} else {
//		$auth_level			= 0;
//	}
//
//
//
//	$SQL = "
//		select
//			auth_view , auth_write , auth_modify , auth_delete, auth_notice, auth_reply
//		from
//			Tbl_board_auth
//		where
//			table_id = '".$table_id."'
//			and mem_level = '".$auth_level."'
//		order by table_id , mem_level
//		limit 0, 1
//	";
//	//echo $SQL."<BR>";
//
//	$auth_rs = mysql_query($SQL, $dbcon);
//	if (!$auth_rs) {
//		//echo "게시판 오류입니다. 관리자에게 문의하여 주십시오."."<BR>";
//		alert_back("게시판 오류입니다. 관리자에게 문의하여 주십시오.");
//		exit;
//	}
//
//	$auth_num_rows = mysql_num_rows($auth_rs);
//	if ($auth_num_rows == 0) {
//		//echo "게시판 설정 오류. 관리자에게 문의하여 주십시오."."<BR>";
//		alert_back("게시판 설정 오류. 관리자에게 문의하여 주십시오.");
//		exit;
//	}
//
//	$auth_rows = mysql_fetch_array($auth_rs);
//	$auth_view					= $auth_rows["auth_view"];
//	$auth_write					= $auth_rows["auth_write"];
//	$auth_modify				= $auth_rows["auth_modify"];
//	$auth_delete				= $auth_rows["auth_delete"];
//	$auth_notice				= $auth_rows["auth_notice"];
//	$auth_reply					= $auth_rows["auth_reply"];
//

	$auth_msg = "권한이 없습니다.";
/*
	echo "ss_mem_level : ".$ss_mem_level."<BR>";
	echo "auth_level : ".$auth_level."<BR>";
	echo "auth_view : ".$auth_view."<BR>";
	echo "auth_write : ".$auth_write."<BR>";
	echo "auth_modify : ".$auth_modify."<BR>";
	echo "auth_delete : ".$auth_delete."<BR>";
	echo "auth_notice : ".$auth_notice."<BR>";
	//exit;
*/

	//echo $mode ."<BR>";
	switch ($mode) {
		case "list" :
			include "list.php";
			break;
		case "write" :
			if ( !($auth_write || $ss_mem_level == 10) ) {
				alert_back("권한이 없습니다.","?mode=list");
				exit;
			}
			include "write.php";
			break;
		case "write_ok" :
			if ( !($auth_write || $ss_mem_level == 10) ) {
				alert_back("권한이 없습니다.","?mode=list");
				exit;
			}
			include "write_ok.php";
			break;
		case "view" :
			if ( !($auth_view || $ss_mem_level == 10) ) {
				alert_back("권한이 없습니다.","?mode=list");
				exit;
			}
			include "view.php";
			break;
		case "del" :
			if ( !($auth_delete || $ss_mem_level == 10) ) {
				alert_back("권한이 없습니다.","?mode=list");
				exit;
			}
			include "delete_ok.php";
			break;
	}
?>
<script>
	function view_go(pop_seq) {
		<? if ($auth_view || $ss_mem_level == 10) { ?>
			location.href = "?mode=view&pop_seq="+pop_seq+"&page=<?=$page?><?=$parameter?>";
		<? } else { ?>
			alert("<?=$auth_msg?>");
		<? } ?>
	}

	function write_go() {
		<? if ($auth_write || $ss_mem_level == 10) { ?>
			location.href = "?mode=write&<?=$parameter?>";
		<? } else { ?>
			alert("<?=$auth_msg?>");
		<? } ?>
	}

	function mod_go(pop_seq) {
		<? if ($auth_modify || $ss_mem_level == 10) { ?>
			location.href = "?mode=write&pop_seq="+pop_seq+"&<?=$parameter?>";
		<? } else { ?>
			alert("<?=$auth_msg?>");
		<? } ?>
	}

	function del_go(pop_seq) {
		<? if ($auth_delete || $ss_mem_level == 10) { ?>
			if (confirm("정말로 삭제 하시겠습니까?\n삭제한 정보는 복구가 불가능합니다.")) {
				location.href = "?mode=del&pop_seq="+pop_seq+"&<?=$parameter?>";
			}
		<? } else { ?>
			alert("<?=$auth_msg?>");
		<? } ?>
	}

	function reply_go(pop_seq) {
		<? if ($auth_reply || $ss_mem_level == 10) { ?>
			location.href = "?mode=reply&pop_seq="+pop_seq+"&<?=$parameter?>";
		<? } else { ?>
			alert("<?=$auth_msg?>");
		<? } ?>
	}

	function list_go() {
		location.href = "?mode=list&<?=$parameter?>";
	}

	function page_go(page) {
		location.href = "?mode=list&page="+page+"&<?=$parameter?>";
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
		ff = document.search_form;
		if (!ff.search_text.value) {
			alert("검색어를 입력해 주십시오.");
			ff.search_text.focus();
			return false;
		}
	}

</script>

<?
	$dbcon -> dbcon_close();
?>
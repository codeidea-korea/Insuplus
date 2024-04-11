<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
$bc_id								= REQSTR($_POST[bc_id], "");
$bc_name							= REQSTR($_POST[bc_name], "");
$bc_skin								= REQSTR($_POST[bc_skin], "");
$bc_path								= REQSTR($_POST[bc_path], "");

$bc_url_start						= REQSTR($_POST[bc_url_start], "");
$bc_url_writeok					= REQSTR($_POST[bc_url_writeok], "");

$bc_login_check					= REQSTR($_POST[bc_login_check], "");
$bc_category_use				= REQSTR($_POST[bc_category_use], "");
$bc_notice_use					= REQSTR($_POST[bc_notice_use], "");
$bc_reply_use						= REQSTR($_POST[bc_reply_use], "");
$bc_comment_use				= REQSTR($_POST[bc_comment_use], "");
$bc_secret_use					= REQSTR($_POST[bc_secret_use], "");
$bc_hidden_use					= REQSTR($_POST[bc_hidden_use], "");
$bc_editor_use					= REQSTR($_POST[bc_editor_use], "");
$bc_search_use					= REQSTR($_POST[bc_search_use], "");
$bc_list_size						= REQSTR($_POST[bc_list_size], "");
$bc_page_size						= REQSTR($_POST[bc_page_size], "");
$bc_title_size						= REQSTR($_POST[bc_title_size], "");
$bc_new_use						= REQSTR($_POST[bc_new_use], "");
$bc_email_use						= REQSTR($_POST[bc_email_use], "");
$bc_homepage_use				= REQSTR($_POST[bc_homepage_use], "");
$bc_prev_next						= REQSTR($_POST[bc_prev_next], "");
$bc_state_use						= REQSTR($_POST[bc_state_use], "");
$bc_autoreg_use					= REQSTR($_POST[bc_autoreg_use], "");

$bc_upfile_image					= REQSTR($_POST[bc_upfile_image], "N");
$bc_upfile_image_width			= REQSTR($_POST[bc_upfile_image_width], "130");
$bc_upfile_image_height		= REQSTR($_POST[bc_upfile_image_height], "130");

$bc_upfile_image_thum					= REQSTR($_POST[bc_upfile_image_thum], "N");
$bc_upfile_image_thum_width			= REQSTR($_POST[bc_upfile_image_thum_width], "500");
$bc_upfile_image_thum_height		= REQSTR($_POST[bc_upfile_image_thum_height], "500");

$bc_upfile_flash					= REQSTR($_POST[bc_upfile_flash], "N");
$bc_upfile_flash_width			= REQSTR($_POST[bc_upfile_flash_width], "300");
$bc_upfile_flash_height			= REQSTR($_POST[bc_upfile_flash_height], "300");
$bc_upfile_media					= REQSTR($_POST[bc_upfile_media], "N");
$bc_upfile_media_width			= REQSTR($_POST[bc_upfile_media_width], "300");
$bc_upfile_media_height		= REQSTR($_POST[bc_upfile_media_height], "300");
$bc_upfile_cnt						= REQSTR($_POST[bc_upfile_cnt], 0);
$bc_upfile_size					= REQSTR($_POST[bc_upfile_size], "");
$bc_upfile_ext_upload			= REQSTR($_POST[bc_upfile_ext_upload], "");
$bc_top_include					= REQSTR($_POST[bc_top_include], "");
//$bc_top_html						= REQSTR($_POST[bc_top_html], "");
$bc_bottom_include				= REQSTR($_POST[bc_bottom_include], "");
//$bc_bottom_html					= REQSTR($_POST[bc_bottom_html], "");
$bc_auth_view					= REQSTR($_POST[bc_auth_view], 0);
$bc_auth_write					= REQSTR($_POST[bc_auth_write], 0);
$bc_auth_modify					= REQSTR($_POST[bc_auth_modify], 0);
$bc_auth_delete					= REQSTR($_POST[bc_auth_delete], 0);
$bc_auth_notice					= REQSTR($_POST[bc_auth_notice], 0);
$bc_auth_reply					= REQSTR($_POST[bc_auth_reply], 0);
$bc_auth_comment				= REQSTR($_POST[bc_auth_comment], 0);
$bc_auth_secret					= REQSTR($_POST[bc_auth_secret], 0);
$bc_auth_hidden					= REQSTR($_POST[bc_auth_hidden], 0);
$bc_auth_upload					= REQSTR($_POST[bc_auth_upload], 0);
$bc_auth_download					= REQSTR($_POST[bc_auth_download], 0);


//foreach ($_POST as $key => $value) {
//	echo $key." : ".$value."<BR>";
//}


isnull($bc_id);
isnull($bc_name);
isnull($bc_skin);


if ( $mode == "modify" ) {
	$SQL = "
		update config_board_list
		set
			bc_name = '".$bc_name."'
			, bc_skin = '".$bc_skin."'
			, bc_path = '".$bc_path."'


			, bc_url_start = '".$bc_url_start."'
			, bc_url_writeok = '".$bc_url_writeok."'


			, bc_login_check = '".$bc_login_check."'
			, bc_category_use = '".$bc_category_use."'
			, bc_notice_use = '".$bc_notice_use."'
			, bc_reply_use = '".$bc_reply_use."'
			, bc_comment_use = '".$bc_comment_use."'
			, bc_secret_use = '".$bc_secret_use."'
			, bc_hidden_use = '".$bc_hidden_use."'
			, bc_editor_use = '".$bc_editor_use."'
			, bc_search_use = '".$bc_search_use."'
			, bc_list_size = '".$bc_list_size."'
			, bc_page_size = '".$bc_page_size."'
			, bc_title_size = '".$bc_title_size."'
			, bc_new_use = '".$bc_new_use."'
			, bc_email_use = '".$bc_email_use."'
			, bc_homepage_use = '".$bc_homepage_use."'
			, bc_prev_next = '".$bc_prev_next."'
			, bc_state_use = '".$bc_state_use."'
			, bc_autoreg_use = '".$bc_autoreg_use."'

			, bc_upfile_image = '".$bc_upfile_image."'
			, bc_upfile_image_width = '".$bc_upfile_image_width."'
			, bc_upfile_image_height = '".$bc_upfile_image_height."'

			, bc_upfile_image_thum = '".$bc_upfile_image_thum."'
			, bc_upfile_image_thum_width = '".$bc_upfile_image_thum_width."'
			, bc_upfile_image_thum_height = '".$bc_upfile_image_thum_height."'

			, bc_upfile_flash = '".$bc_upfile_flash."'
			, bc_upfile_flash_width = '".$bc_upfile_flash_width."'
			, bc_upfile_flash_height = '".$bc_upfile_flash_height."'
			, bc_upfile_media = '".$bc_upfile_media."'
			, bc_upfile_media_width = '".$bc_upfile_media_width."'
			, bc_upfile_media_height = '".$bc_upfile_media_height."'
			, bc_upfile_cnt = '".$bc_upfile_cnt."'
			, bc_upfile_size = '".$bc_upfile_size."'
			, bc_upfile_ext_upload = '".$bc_upfile_ext_upload."'
			, bc_top_include = '".$bc_top_include."'
			, bc_top_html = '".$bc_top_html."'
			, bc_bottom_include = '".$bc_bottom_include."'
			, bc_bottom_html = '".$bc_bottom_html."'
			, bc_auth_view = '".$bc_auth_view."'
			, bc_auth_write = '".$bc_auth_write."'
			, bc_auth_modify = '".$bc_auth_modify."'
			, bc_auth_delete = '".$bc_auth_delete."'
			, bc_auth_notice = '".$bc_auth_notice."'
			, bc_auth_reply = '".$bc_auth_reply."'
			, bc_auth_comment = '".$bc_auth_comment."'
			, bc_auth_secret = '".$bc_auth_secret."'
			, bc_auth_hidden = '".$bc_auth_hidden."'
			, bc_auth_upload = '".$bc_auth_upload."'
			, bc_auth_download = '".$bc_auth_download."'
		where
			bc_id = '".$bc_id."'
	";
	$result = $dbcon -> query($SQL);
	if (!$result) {
		alert_back("수정에 실패하였습니다.");
		exit;
	}

	$go_page = "?bc_id=".$bc_id;

	alert_page("처리되었습니다.", "bbs_modify.php".$go_page);
}
else {
//	$SQL = "
//		insert into config_board_list (
//			bc_id, bc_name, bc_skin, bc_login_check, bc_category_use, bc_notice_use, bc_reply_use, bc_comment_use, bc_secret_use, bc_hidden_use, bc_editor_use, bc_search_use, bc_list_size, bc_page_size, bc_title_size, bc_new_use, bc_email_use, bc_homepage_use, bc_prev_next, bc_state_use, bc_upfile_image, bc_upfile_image_width, bc_upfile_image_height, bc_upfile_flash, bc_upfile_flash_width, bc_upfile_flash_height, bc_upfile_media, bc_upfile_media_width, bc_upfile_media_height, bc_upfile_cnt, bc_upfile_size, bc_upfile_ext_upload, bc_upfile_ext_download, bc_top_include, bc_top_html, bc_bottom_include, bc_bottom_html, bc_auth_view, bc_auth_write, bc_auth_modify, bc_auth_delete, bc_auth_notice, bc_auth_reply, bc_auth_comment, bc_auth_secret, bc_auth_hidden, bc_auth_upload, bc_auth_download
//		) values (
//			 '".$bc_id."', '".$bc_name."', '".$bc_skin."', '".$bc_login_check."', '".$bc_category_use."', '".$bc_notice_use."', '".$bc_reply_use."', '".$bc_comment_use."', '".$bc_secret_use."', '".$bc_hidden_use."', '".$bc_editor_use."', '".$bc_search_use."', '".$bc_list_size."', '".$bc_page_size."', '".$bc_title_size."', '".$bc_new_use."', '".$bc_email_use."', '".$bc_homepage_use."', '".$bc_prev_next."', '".$bc_state_use."', '".$bc_upfile_image."', '".$bc_upfile_image_width."', '".$bc_upfile_image_height."', '".$bc_upfile_flash."', '".$bc_upfile_flash_width."', '".$bc_upfile_flash_height."', '".$bc_upfile_media."', '".$bc_upfile_media_width."', '".$bc_upfile_media_height."', '".$bc_upfile_cnt."', '".$bc_upfile_size."', '".$bc_upfile_ext_upload."', '".$bc_upfile_ext_download."', '".$bc_top_include."', '".$bc_top_html."', '".$bc_bottom_include."', '".$bc_bottom_html."', '".$bc_auth_view."', '".$bc_auth_write."', '".$bc_auth_modify."', '".$bc_auth_delete."', '".$bc_auth_notice."', '".$bc_auth_reply."', '".$bc_auth_comment."', '".$bc_auth_secret."', '".$bc_auth_hidden."', '".$bc_auth_upload."', '".$bc_auth_download."'
//		)
//	";


	$SQL = "
		select
			count(*)
		from
			config_board_list
		where
			bc_id =  '".$bc_id."'
	";
	$TempRS = $dbcon->getCount($SQL);
	if ( $TempRS ) {
		alert_back("이미 사용중인 게시판입니다.");
		exit;
	}

	$SQL = "
		insert into config_board_list (
			bc_id, bc_name, bc_skin, bc_path
		) values (
			 '".$bc_id."', '".$bc_name."', '".$bc_skin."', '".$bc_path."'
		)
	";
	$result = $dbcon -> query($SQL);

	// 게시판 테이블 생성
	$tbl_insert_tbl_board = str_replace("[boardname]", $bc_id, $tbl_insert_tbl_board);


	$result2 = $dbcon -> query($tbl_insert_tbl_board);
	if (!$result || !$result2) {
		alert_back("입력에 실패하였습니다.");
		exit;
	}

	$UpFileDirectory = $path_root."_data/board/".$bc_id;

	if(!is_dir($UpFileDirectory)) {
		@mkdir($UpFileDirectory, 0777);
		@chmod($UpFileDirectory, 0777);
	}


	alert_page("처리되었습니다.", "bbs_list.php".$go_page);
}


?>

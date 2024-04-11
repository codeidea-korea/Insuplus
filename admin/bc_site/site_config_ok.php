<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk("7", $url_admin_login_out);


	$sc_idx = REQSTR($_POST["sc_idx"],"");
	$sc_name = REQSTR($_POST["sc_name"],"");
	$sc_site_title = REQSTR($_POST["sc_site_title"],"");
	$sc_admin_name = REQSTR($_POST["sc_admin_name"],"");
	$sc_admin_email = REQSTR($_POST["sc_admin_email"],"");
	$sc_meta_description = REQSTR($_POST["sc_meta_description"],"");
	$sc_meta_keyword = REQSTR($_POST["sc_meta_keyword"],"");
	$sc_meta_author = REQSTR($_POST["sc_meta_author"],"");
	$sc_meta_classification = REQSTR($_POST["sc_meta_classification"],"");
	$sc_meta_email = REQSTR($_POST["sc_meta_email"],"");

	$sc_site_url = REQSTR($_POST["sc_site_url"],"");
	$sc_site_root = REQSTR($_POST["sc_site_root"],"");
	$sc_server_root = REQSTR($_POST["sc_server_root"],"");

	$sc_menu_member = REQSTR($_POST["sc_menu_member"],"");
	$sc_menu_product = REQSTR($_POST["sc_menu_product"],"");
	$sc_menu_board = REQSTR($_POST["sc_menu_board"],"");
	$sc_menu_popup = REQSTR($_POST["sc_menu_popup"],"");
	$sc_menu_poll = REQSTR($_POST["sc_menu_poll"],"");



	$sc_top_include = REQSTR($_POST["sc_top_include"],"");
	$sc_top_content = REQSTR($_POST["sc_top_content"],"");
	$sc_bottom_include = REQSTR($_POST["sc_bottom_include"],"");
	$sc_bottom_content = REQSTR($_POST["sc_bottom_content"],"");


	#### 회원관련
	$sc_skin_member = REQSTR($_POST["sc_skin_member"],"");
	$sc_agree = REQSTR($_POST["sc_agree"],"");
	$sc_policy = REQSTR($_POST["sc_policy"],"");

	$sc_skin_product = REQSTR($_POST["sc_skin_product"],"");


	$sc_member_company = REQSTR($_POST["sc_member_company"],"");
	$sc_member_name_check = REQSTR($_POST["sc_member_name_check"],"");

	$sc_bank_account = REQSTR($_POST["sc_bank_account"],"");
	$sc_delivery_free = REQSTR($_POST["sc_delivery_free"],"");
	$sc_delivery_money = REQSTR($_POST["sc_delivery_money"],"");


	$sc_member_top_include = REQSTR($_POST["sc_member_top_include"],"");
	$sc_member_top_content = REQSTR($_POST["sc_member_top_content"],"");
	$sc_member_bottom_include = REQSTR($_POST["sc_member_bottom_include"],"");
	$sc_member_bottom_content = REQSTR($_POST["sc_member_bottom_content"],"");


	isnull($sc_idx);
	isnull($sc_name);
	isnull($sc_site_title);
	isnull($sc_admin_name);


	$query_set = "";
	if ( getLen($sc_menu_member) ) $query_set .= " , sc_menu_member = '".$sc_menu_member."' ";
	if ( getLen($sc_menu_product) ) $query_set .= " , sc_menu_product = '".$sc_menu_product."' ";
	if ( getLen($sc_menu_board) ) $query_set .= " , sc_menu_board = '".$sc_menu_board."' ";
	if ( getLen($sc_menu_popup) ) $query_set .= " , sc_menu_popup = '".$sc_menu_popup."' ";
	if ( getLen($sc_menu_poll) ) $query_set .= " , sc_menu_poll = '".$sc_menu_poll."' ";

	if ( getLen($sc_top_include) ) $query_set .= " , sc_top_include = '".$sc_top_include."' ";
	if ( getLen($sc_top_content) ) $query_set .= " , sc_top_content = '".$sc_top_content."' ";
	if ( getLen($sc_bottom_include) ) $query_set .= " , sc_bottom_include = '".$sc_bottom_include."' ";
	if ( getLen($sc_bottom_content) ) $query_set .= " , sc_bottom_content = '".$sc_bottom_content."' ";
	if ( getLen($sc_skin_member) ) $query_set .= " , sc_skin_member = '".$sc_skin_member."' ";
	if ( getLen($sc_agree) ) $query_set .= " , sc_agree = '".$sc_agree."' ";
	if ( getLen($sc_policy) ) $query_set .= " , sc_policy = '".$sc_policy."' ";

	$query_set .= " , sc_member_top_include = '".$sc_member_top_include."' ";
	$query_set .= " , sc_member_top_content = '".$sc_member_top_content."' ";
	$query_set .= " , sc_member_bottom_include = '".$sc_member_bottom_include."' ";
	$query_set .= " , sc_member_bottom_content = '".$sc_member_bottom_content."' ";


	if ( getLen($sc_member_company) ) $query_set .= " , sc_member_company = '".$sc_member_company."' ";
	if ( getLen($sc_member_name_check) ) $query_set .= " , sc_member_name_check = '".$sc_member_name_check."' ";


	if ( getLen($sc_meta_description) ) $query_set .= " , sc_meta_description = '".$sc_meta_description."' ";
	if ( getLen($sc_meta_keyword) ) $query_set .= " , sc_meta_keyword = '".$sc_meta_keyword."' ";
	if ( getLen($sc_meta_author) ) $query_set .= " , sc_meta_author = '".$sc_meta_author."' ";
	if ( getLen($sc_meta_classification) ) $query_set .= " , sc_meta_classification = '".$sc_meta_classification."' ";
	if ( getLen($sc_meta_email) ) $query_set .= " , sc_meta_email = '".$sc_meta_email."' ";


	if ( getLen($sc_site_url) ) $query_set .= " , sc_site_url = '".$sc_site_url."' ";
	if ( getLen($sc_site_root) ) $query_set .= " , sc_site_root = '".$sc_site_root."' ";
	if ( getLen($sc_server_root) ) $query_set .= " , sc_server_root = '".$sc_server_root."' ";

	//if ( getLen($sc_bank_account) )
		$query_set .= " , sc_bank_account = '".$sc_bank_account."' ";
	if ( getLen($sc_delivery_free) ) $query_set .= " , sc_delivery_free = '".$sc_delivery_free."' ";
	if ( getLen($sc_delivery_money) ) $query_set .= " , sc_delivery_money = '".$sc_delivery_money."' ";


	$SQL = "
		update config_site
		set
			sc_name = '".$sc_name."'
			, sc_site_title = '".$sc_site_title."'
			, sc_admin_name = '".$sc_admin_name."'
			, sc_admin_email = '".$sc_admin_email."'

			".$query_set."
		where
			sc_idx = '".$sc_idx."'
	";
//	echo $SQL."<BR>";
//	exit;
	$result = $dbcon -> query($SQL);
	if ( $result == false ) {
		alert_back( $GLOBALS[msg_error_query] );
//		echo ( $GLOBALS[msg_error_query] );
//		exit;
	}


	$dbcon -> dbcon_close();
	alert_page($msg_progress_ok, "site_config.php");
?>

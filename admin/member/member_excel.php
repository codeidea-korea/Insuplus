<?
	header("Content-Type: text/html; charset=UTF-8");
	// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
	header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');
	header("Content-type: application/vnd.ms-excel");
	header("Content-Disposition: attachment; filename=excel_".date("YmdHis").".xls");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
	header("Pragma: public");


	if (!isset($set_time_limit)) $set_time_limit = 0;
	@set_time_limit($set_time_limit);
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.Array.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.MSG.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/config.SQL.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.session.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.DB.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.File.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.FileNew.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.html.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.global.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.page.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.mail.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.product.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.editor.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.Cart.php";

	$dbcon = new dbcon;
	$dbcon -> dbcon_open(0);
	mysql_query("set names utf8");

	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크



	//$dbcon -> setDebug(1);
	$page_btn_prev =">>"; // > 버튼
	$page_btn_next ="<<"; // < 버튼

	// 페이지 설정
	$page				= REQSTR($page, 1);
	$num_per_page	= REQSTR($num_per_page, 1000000);
	$page_per_block	= REQSTR($page_per_block, 10);
	$first					= $num_per_page*($page-1);
	$last					= $num_per_page*$page;

	// 검색설정
	$query_where		= "";
	$query_where .= " and u_level <= '".$ss_u_level."' ";

	#### 검색 설정 Start
	$search_u_level				= REQSTR($search_u_level, "");
	$search_u_gubun				= REQSTR($search_u_gubun, "");
	$search_u_state				= REQSTR($search_u_state, "");
	$search_u_sex					= REQSTR($search_u_sex, "");

	$search							= REQSTR($search, "");
	$search_text					= REQSTR($search_text, "");

	$search_orderby				= REQSTR($search_orderby, "");
	$search_sort					= REQSTR($search_sort, "");

	$search_date					= REQSTR($search_date, "");
	$search_date_s				= REQSTR($search_date_s, "");
	$search_date_e				= REQSTR($search_date_e, "");


	if ( strlen($search_u_level) > 0 ) $query_where .= " and u_level = '".$search_u_level."' ";
	if ( strlen($search_u_gubun) > 0 ) $query_where .= " and u_gubun = '".$search_u_gubun."' ";
	if ( strlen($search_u_state) > 0 ) $query_where .= " and u_state = '".$search_u_state."' ";
	if ( strlen($search_u_sex) > 0 ) $query_where .= " and u_sex = '".$search_u_sex."' ";
	if ( strlen($search_u_mail) > 0 ) $query_where .= " and u_email_receipt = '".$search_u_mail."' ";
	if ( strlen($search_u_sms) > 0 ) $query_where .= " and u_sms_receipt = '".$search_u_sms."' ";

	if ( strlen($search_text) > 0 ) {
		if ( $search == "u_email" )
			$query_where .= " and u_email1 like '%".$search_text."%' or u_email2 like '%".$search_text."%' ";
		else
			$query_where .= " and ".$search." like '%".$search_text."%' ";
	}

	if ( strlen($search_date_s) > 0 ) $query_where .= " and ".$search_date." >= '".$search_date_s." 00:00:00' ";
	if ( strlen($search_date_e) > 0 ) $query_where .= " and ".$search_date." <= '".$search_date_e." 23:59:59' ";


	if ( strlen($search_orderby) == 0 ) $search_orderby .= "u_regdate";
	if ( strlen($search_sort) == 0 ) $search_sort .= "desc";

	#### 검색 설정 End

	// 쿼리설정
	$field				= " * "; //, ( select count(u_id) from tbl_user_log where u_id = A.u_id ) as u_log_cnt
	$table			= "tbl_user A";
	$where			= $query_where;
	$orderby			= $search_orderby." ".$search_sort;
	$limit				= $first.", ".$num_per_page;
	$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);
	$total_record	= $ArrRS[0];
	$result			= $ArrRS[1];
	unset($ArrRS);

	// 페이지 & 리스트 설정
	$total_page		= ceil($total_record/$num_per_page);
	$no				= $total_record - $first;

	$dbcon -> dbcon_close();

?>


			<table width="100%" cellspacing="0" cellpadding="0" border="1">
				<tr>
					<td align="center">No.</td>
					<td align="center">아이디</td>
					<td align="center">이름</td>
					<td align="center">지역</td>
					<td align="center">등급</td>
					<td align="center">상태</td>
					<td align="center">성별</td>
					<td align="center">연락처</td>
					<td align="center">이메일</td>
					<td align="center">메일여부</td>
					<td align="center">SMS여부</td>
					<td align="center">가입일</td>
				</tr>
			<? if ($total_record == 0) { ?>
				<tr align="center">
					<td colspan="100" height="28"><b><?=$GLOBALS[msg_list_notdata]?></b></td>
				</tr>
				<tr>
					<td colspan="100" height="1" bgcolor="#E5E5E5"></td>
				</tr>
			<?
				} else {
					while ($rows = $dbcon -> fetch_array($result)) {
						extract($rows);
						unset($rows);

						$print_u_level = $Arr_u_level[$u_level];

						$print_u_gubun = $Arr_u_gubun[$u_gubun];

						$print_u_state = $Arr_u_state[$u_state];

						$print_email_icon = "";
						if ( $u_email_receipt == "1" ) {
							$print_email_icon = "Y";
						}
						else {
							$print_email_icon = "N";
						}

						$print_u_sex		= $Arr_u_sex[$u_sex];

						$print_sms_icon = "";
						if ( $u_sms_receipt == "1" ) {
							$print_sms_icon = "Y";
						}
						else {
							$print_sms_icon = "N";
						}

						$print_u_regdate			= mb_substr($u_regdate, 0, 10);
						$print_u_birth				= mb_substr($u_birth, 0, 10);
						$print_u_marriagedate	= mb_substr($u_marriagedate, 0, 10);
			?>
				<tr>
					<td align="center"><?=$no?></td>
					<td align="center"><?=$u_id?></td>
					<td align="center"><?=$u_name?></td>
					<td align="center"><?=$u_area?></td>
					<td align="center"><?=$print_u_level?></td>
					<td align="center"><?=$print_u_state?></td>
					<td align="center"><?=$print_u_sex?></td>
					<td align="center"><?=$u_hp?></td>
					<td align="center"><?=$u_email?></td>
					<td align="center"><?=$print_email_icon?></td>
					<td align="center"><?=$print_sms_icon?></td>
					<td align="center"><?=$print_u_regdate?></td>
				</tr>
			<?
						$no = $no - 1;
					}
				}
				unset($result);

			?>
			</table>
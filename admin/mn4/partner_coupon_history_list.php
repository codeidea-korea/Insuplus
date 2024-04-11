<!DOCTYPE html>
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$tm = "MN4";
	$lm = "";
	include $path_admin."inc/header.php";
	
	$page_btn_prev =">>"; // > 버튼
	$page_btn_next ="<<"; // < 버튼

	// 페이지 설정
	$page				= REQSTR($page, 1);
	$num_per_page	= REQSTR($num_per_page, 20);
	$page_per_block	= REQSTR($page_per_block, 10);
	$first					= $num_per_page*($page-1);
	$last					= $num_per_page*$page;

	// 검색설정
	$query_where		= "";

	#### 검색 설정 Start
	$search				= REQSTR($search, "");
	$search_text		= REQSTR($search_text, "");
	$date_type			= REQSTR($date_type, "");

	$search_date		= REQSTR($search_date, "");
	$search_date_s		= REQSTR($search_date_s, "");
	$search_date_e		= REQSTR($search_date_e, "");
	
	if ( (getLen($search_text) > 0) ) {
		$query_where .= " and ".$search." like '%".$search_text."%'";
	}
	
	if($search_date_s && $search_date_e) {
		if($date_type == "regdate") {
			$query_where .= " and e.regdate >= '".$search_date_s."' AND e.regdate <= '".$search_date_e."' ";
		} else if($date_type == "expire_date") {
			$query_where .= " and (	(expire_date_s <= '".$search_date_s."' and expire_date_e >= '".$search_date_s."') ";
			$query_where .= " or (expire_date_s >= '".$search_date_s."' and expire_date_e <= '".$search_date_e."') ";
			$query_where .= " or (expire_date_s <= '".$search_date_e."' and expire_date_e >= '".$search_date_e."')	) ";
		}
	}
	
	$parameter  = "&search=".$search."&search_text=".$search_text."&date_type=".$date_type;
	$parameter .= "&search_date_s=".$search_date_s."&search_date_e=".$search_date_e;
	#### 검색 설정 End

	// 쿼리설정
	// 쿼리설정
	$field			= " if(e.partner_event_yn ='Y','제휴','-') as partner_event_yn_name ";
	$field         .= " , if(e.secret = 'N','공개','미공개') as secret_name ";
	$field         .= " , e.seq, e.partner_coupon_name, e.partner_coupon_discount, e.regdate ";
	$field         .= " , e.expire_date_s, e.expire_date_e ";
	$field         .= " , p.partnership_name ";
	$field         .= " , (SELECT COUNT(*) FROM tbl_partner_coupon where event_seq = e.seq) as coupon_size";
	$field 		   .= " , (SELECT count(*) FROM tbl_board_coupon_history WHERE event_seq = e.seq AND partner_coupon is not null ) pub_coupon_size ";
	$field 		   .= " , (SELECT count(*) FROM tbl_board_coupon_history WHERE event_seq = e.seq AND partner_coupon is not null AND use_yn ='Y' ) use_coupon_size ";
	$table			= " tbl_board_event e LEFT JOIN tbl_board_partner p ";
	$table		   .= " ON e.event_partnership_code = p.partnership_code ";
	$where			= " AND e.event_type = 'C' AND  e.event_partnership_code != 'insuplus' AND (SELECT COUNT(*) FROM tbl_partner_coupon where event_seq = e.seq) > 0 ".$query_where;
	$orderby		= " e.seq DESC ";
	$limit			= $first.", ".$num_per_page;
	
	$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);
	$total_record	= $ArrRS[0];
	$result			= $ArrRS[1];
	unset($ArrRS);
	
	// 페이지 & 리스트 설정
	$total_page		= ceil($total_record/$num_per_page);
	$no				= $total_record - $first;
	

?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">제휴사 쿠폰</td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<!-- 190725 수정 800px->100% -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
			<input type="hidden" name="bc_id" value="<?=$bc_id?>">
			<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
			<input type="hidden" name="mode" value="list">
			<? if ($bc_category_use == "Y") { ?>
			<input type="hidden" name="search_category" value="<?=$search_category?>">
			<? } ?>
				<table class="adm-searchForm">
					<colgroup>
						<col width="8%" />
						<col width="92%" />
					</colgroup>
					<tbody>
					<tr>
						<th>기간</th>
						<td colspan="2">
							<select name="date_type">
								<option value="regdate" <? if ($date_type == "regdate" ) echo "selected"; ?>>등록일</option>
								<option value="expire_date" <? if ($date_type == "expire_date" ) echo "selected"; ?>>사용기간</option>
							</select>
							<input type="text" id="search_date_s" name="search_date_s" value="<?=$search_date_s?>" class="datepicker w100">
							<span style="padding-left: 5px; padding-right: 5px;">~</span>
							<input type="text" id="search_date_e" name="search_date_e" value="<?=$search_date_e?>" class="datepicker w100">
						</td>
					</tr>
					<tr>
						<th>직접검색</th>
						<td colspan="2">
							<select name="search">
								<option value="e.partner_coupon_name" <? if ($search == "e.partner_coupon_name" ) echo "selected"; ?>>쿠폰명</option>
								<option value="p.partnership_name" <? if ($search == "p.partnership_name" ) echo "selected"; ?>>제휴사</option>
							</select>
							<input type="text" name="search_text" value="<?=$search_text?>" />
							<input type="submit" value="검색" />
						</td>
					</tr>
					</tbody>
				</table>
			</form>

			<form name="frm" method="post">
			<input type="hidden" name="mode" value="list_mod">
			<input type="hidden" name="bc_id" value="<?=$bc_id?>">
			<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
			<input type="hidden" name="search" value="<?=$search?>">
			<input type="hidden" name="search_text" value="<?=$search_text?>">
			<input type="hidden" name="page" value="<?=$page?>">

			
			<div class="btnWrap mt20">
				<div class="leftWrap"><span class="totalCount">전체 : <strong><?=number_format($total_record)?></strong>건</span></div>
				<div class="rightWrap"></div>
			</div>
			<table class="adm-list-tb">
			<colgroup>
				<col width="15%" />
				<col width="20%" />
				<col width="10%" />
				<col width="10%" />
				<col width="10%" />
				<col width="5%" />
				<col width="5%" />
				<col width="5%" />
				<col width="5%" />
				<col width="15%" />
			</colgroup>
				<tr>
					<th>쿠폰명</th>
					<th>사용기간</th>
					<th>제휴여부</th>
					<th>제휴사</th>
					<th>할인율</th>
					<th>총 수량</th>
					<th>발행건수</th>
					<th>사용건수</th>
					<th>공개여부</th>
					<th>등록일</th>
				</tr>
				<tbody>
				<? if ($total_record == 0) { ?>
				<tr>
					<td colspan="10"><?=$GLOBALS["msg_list_notdata"]?></td>
				</tr>
				<?
					} else {
						while ($rows = $dbcon -> fetch_array($result)) {
							extract($rows);
							unset($rows);
				?>
					<tr onClick="popup_partner_coupon_history_list('<?=$seq?>')" style="cursor:pointer">
						<td><?=$partner_coupon_name?></td>
						<td><?=substr($expire_date_s,0,10)?>~<?=substr($expire_date_e,0,10)?></td>
						<td><?=$partner_event_yn_name?></td>
						<td><?=$partnership_name?></td>
						<td><?=$partner_coupon_discount?>%</td>
						<td><?=number_format($coupon_size)?>건</td>
						<td><?=number_format($pub_coupon_size)?>건</td>
						<td><?=number_format($use_coupon_size)?>건</td>
						<td><?=$secret_name?></td>
						<td><?=substr($regdate,0,10)?></td>
					</tr>
				<?
							$no = $no - 1;
						}
					}
					unset($result);
	
				?>
				</tbody>
			</table>
			</form>
			
			<!-- ### 페이지 시작 ###  -->
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td align="center" valign="top" style="padding:10px 0 0 0">
							<? 
							$bc_skin = "default";
							list_page($page, $total_page, $page_per_block); 
							?>
						</td>
					</tr>
			</table>
			<!-- ### 페이지 끝 ###  -->	
			
		</td>
	</tr>
</table>
<? include $path_admin."inc/footer.php"; ?>
<script>
	function popup_partner_coupon_history_list(seq) {
		var url = "/admin/mn4/popup_partner_coupon_history_list.php?event_seq="+seq;
		window.open(url,"_p","width=950,height=600");
	}

	function page_go(page) {
		location.href = "?page="+page+"<?=$parameter?>";
	}
</script>

<?
	$dbcon -> dbcon_close();
?>
<!DOCTYPE html>
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	// 관리자 체크
	admin_chk($auth_admin, $url_admin_login_out);

	$tm = "";
	$lm = "";
	include $path_admin."inc/header_pop.php";

	//$dbcon -> setDebug(1);
	$page_btn_prev =">>"; // > 버튼
	$page_btn_next ="<<"; // < 버튼

	// 페이지 설정
	$page			= REQSTR($page, 1);
	$num_per_page	= REQSTR($num_per_page, 20);
	$page_per_block	= REQSTR($page_per_block, 10);
	$first					= $num_per_page*($page-1);
	$last					= $num_per_page*$page;
	$limit = $first.", ".$last;

	// 검색설정
	$query_where		= "  ";

	#### 검색 설정 Start
	$user_name					= REQSTR($user_name, "");
	$phon_number				= REQSTR($phon_number, "");
	$use_yn						= REQSTR($use_yn, "");
	$event_seq					= REQSTR($event_seq, "");
	
	$search_date_txt			= REQSTR($search_date_txt, "");
	$search_date_s				= REQSTR($search_date_s, "");
	$search_date_e				= REQSTR($search_date_e, "");
	
	
	if ( strlen($search_date_txt) > 0 ) {
		if($search_date_txt == "partnerPeriod") {
			if ( strlen($search_date_s) > 0 ) $query_where .= " and start_Partner_period >= '".$search_date_s." 00:00:00' ";
			if ( strlen($search_date_e) > 0 ) $query_where .= " and end_Partner_period <= '".$search_date_e." 23:59:59' ";
		} else if($search_date_txt == "regdate") {
			if ( strlen($search_date_s) > 0 ) $query_where .= " and regdate >= '".$search_date_s." 00:00:00' ";
			if ( strlen($search_date_e) > 0 ) $query_where .= " and regdate <= '".$search_date_e." 23:59:59' ";
		}
	}

	if ( strlen($search_text) > 0 ) {
		if($search == "partnership_name") {
			$query_where .= " and partnership_name like '%".$search_text."%' ";
		} else if($search == "partnership_code") {
			$query_where .= " and partnership_code like '%".$search_text."%' ";
		}
	}
	
	$parameter = "&search=".$search."&phon_number=".$phon_number."&use_yn=".$use_yn."&event_seq=".$event_seq."&search_date_txt=".$search_date_txt."&search_date_s=".$search_date_s."&search_date_e=".$search_date_e;
	
	// 쿼리설정
	$field			= " seq, partnership_name, partnership_code, start_Partner_period, end_Partner_period, regdate";
	$table			= " tbl_board_partner ";
 	$where		   .= $query_where;
	$orderby			= "regdate desc";
	$limit				= $first.", ".$num_per_page;
	
	$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);
	$total_record	= $ArrRS[0];
	$result			= $ArrRS[1];
	unset($ArrRS);
	
	// 페이지 & 리스트 설정
	$total_page		= ceil($total_record/$num_per_page);
	$no				= $total_record - $first;
?> 
<div class="popupWrap">
	<header>
		<h1>쿠폰 사용내역</h1>
		<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
	</header>
	
	<div class="popContWrap">
		<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
		<input type="hidden" name="bc_id" value="<?=$bc_id?>">
		<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
		<input type="hidden" name="mode" value="list">
		<input type="hidden" name="coupon_code" value="<?=$coupon_code?>">
		<input type="hidden" name="search" value="<?=$search?>">
		<input type="hidden" name="search_text" value="<?=$search_text?>">
		<input type="hidden" name="event_seq" value="<?=$event_seq?>">
		<? if ($bc_category_use == "Y") { ?>
		<input type="hidden" name="search_category" value="<?=$search_category?>">
		<? } ?>
			<table class="adm-searchForm">
				<colgroup>
					<col width="12%" />
					<col width="88%" />
				</colgroup>
				<tbody>
					<tr>
						<th>기간검색</th>
						<td colspan="3">
							<select name="search_date_txt">
								<option value="partnerPeriod" <? if ($search_date_txt == "partnerPeriod" ) echo "selected"; ?>>제휴기간</option>
								<option value="regdate" <? if ($search_date_txt == "regdate" ) echo "selected"; ?>>등록일</option>
							</select>
							<input type="text" name="search_date_s" class="calendar w100 ml10" value="<?=$search_date_s?>" />
							~ <input type="text" name="search_date_e" class="calendar w100" value="<?=$search_date_e?>" />
						</td>
					</tr>
					<tr>
						<th>직접검색</th>
						<td colspan="3">
							<select name="search">
								<option value="partnership_name" <? if ($search == "partnership_name" ) echo "selected"; ?>>제휴사명</option>
								<option value="partnership_code" <? if ($search == "partnership_code" ) echo "selected"; ?>>제휴코드</option>
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
		
		<table class="adm-list-tb">
		<colgroup>
			<col width="*%" />
			<col width="20%" />
			<col width="*" />
			<col width="15%" />
		</colgroup>
			<tr>
				<th>제휴사</th>
				<th>제휴코드</th>
				<th>제휴기간</th>
				<th>등록일</th>
			</tr>

			<? if ($total_record == 0) { ?>
				<tr>
					<td colspan="6"><?=$GLOBALS["msg_list_notdata"]?></td>
				</tr>
			<?
				} else {
					while ($rows = $dbcon -> fetch_array($result)) {
						extract($rows);
						unset($rows);
			?>
				<tr onclick="g_select('<?= $seq?>', '<?= $partnership_name?>');" style="cursor: pointer;">
					<td><?=$partnership_name?></td>
					<td><?=$partnership_code?></td>
					<td><?=substr($start_Partner_period, 0, 10)?>~<?=substr($end_Partner_period, 0, 10)?></td>
					<td><?=substr($regdate, 0, 10);?></td>
				</tr>
			<?
						$no = $no - 1;
					}
				}
				unset($result);

			?>
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
					</td>
				</tr>
			</table>
			<!-- ### 페이지 끝 ###  -->	
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		var clareCalendar = {<?=$calendar_opt?>});
</script>
<script>
	function g_select(idx,name){
		var ff = opener.document.frm_join;
		ff.join_ch.value=idx;
		ff.joinChannel.value=name;
		ff.delJoinChBtn.style.display = 'inline';
		window.close();
	}

	function page_go(page) {
		location.href = "?page="+page+"<?=$parameter?>";
	}
</script>
</html>
<?
	$dbcon -> dbcon_close();
?>
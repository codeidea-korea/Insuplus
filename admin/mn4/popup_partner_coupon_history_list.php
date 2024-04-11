<!DOCTYPE html>
<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
// 관리자 체크
admin_chk($auth_admin, $url_admin_login_out);

$tm = "";
$lm = "";
include $path_admin."inc/header_pop.php";

	//쿠폰명
	$SQL = " SELECT partner_coupon_name FROM tbl_board_event WHERE seq = '".$event_seq."' ";;
	$RS = $dbcon->query($SQL);
	$ROW = $dbcon->fetch_array($RS);

	//$dbcon -> setDebug(1);
	$page_btn_prev =">>"; // > 버튼
	$page_btn_next ="<<"; // < 버튼

	// 페이지 설정
	$page				= REQSTR($page, 1);
	$num_per_page	= REQSTR($num_per_page, 20);
	$page_per_block	= REQSTR($page_per_block, 10);
	$first					= $num_per_page*($page-1);
	$last					= $num_per_page*$page;
	$limit = $first.", ".$last;

	// 검색설정
	$query_where		= "  ";

	#### 검색 설정 Start
	$user_name							= REQSTR($user_name, "");
	$phon_number					= REQSTR($phon_number, "");
	$use_yn							= REQSTR($use_yn, "");
	$event_seq							= REQSTR($event_seq, "");
	
	
	if ( strlen($search_text) > 0 ) {
		if($search == "mobile") {
			$query_where .= " and h.mobile = '".all_seed_enc($search_text)."' ";
		} else if($search == "name") {
			$query_where .= " and j.o_name = '".all_seed_enc($search_text)."' ";
		}
	}

	if ( strlen($use_yn) > 0 ) {
		$query_where .= " and h.use_yn like '%".$use_yn."%' ";
	}
	
	$parameter = "&search=".$search."&phon_number=".$phon_number."&use_yn=".$use_yn."&event_seq=".$event_seq;
	
	// 쿼리설정
	$field			= " h.orderno, h.mobile, h.use_yn, h.writedate, h.start_date, h.end_date, ";
	$field		   .= " h.partner_coupon, e.partner_coupon_name, e.partner_coupon_discount, p.partnership_name ";
	$field		   .= " ,if(h.use_yn='Y','사용','미사용') as use_yn_name";
	$field		   .= " ,j.o_name";
	$table			= " tbl_board_coupon_history h INNER JOIN tbl_board_event e ";
	$table		   .= " ON h.event_seq = e.seq ";
	$table 		   .= " LEFT JOIN tbl_order_list_join j ON h.orderno = j.orderno ";
	$table		   .= " LEFT JOIN tbl_board_partner p ON e.event_partnership_code = p.partnership_code";
	$where	   	   .= " AND h.event_seq = '".$event_seq."' ";
	$where	   	   .= " AND h.partner_coupon is not null ";
 	$where		   .= $query_where;
	$orderby			= $search_orderby." ".$search_sort;
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
		<h1>제휴사 쿠폰 사용내역</h1>
		<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
	</header>
	
	<div class="popContWrap">
		<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
		<input type="hidden" name="bc_id" value="<?=$bc_id?>">
		<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
		<input type="hidden" name="mode" value="list">
		<input type="hidden" name="coupon_code" value="<?=$coupon_code?>">
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
						<th>사용여부</th>
						<td colspan=3>
							<input type="radio" name="use_yn" id="use_yn" value=""  <? if (!$use_yn) echo "checked"; ?>/><label>전체</label>
							<input type="radio" name="use_yn" id="use_yn" value="Y" <? if ($use_yn == "Y") echo "checked"; ?>/><label>사용</label>
							<input type="radio" name="use_yn" id="use_yn" value="N" <? if ($use_yn == "N") echo "checked"; ?>/><label>미사용</label>
						</td>
					</tr>
					<tr>
						<th>직접검색</th>
						<td colspan="3">
							<select name="search">
								<option value="mobile" <? if ($search == "mobile" ) echo "selected"; ?>>휴대폰번호</option>
								<option value="name" <? if ($search == "name" ) echo "selected"; ?>>이름</option>
							</select>
							<input type="text" name="search_text" value="<?=$search_text?>" />
							<input type="submit" value="검색" />
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		
		<div class="btnWrapL">
			<span>쿠폰명 : <?=$ROW["partner_coupon_name"]?></span>
		</div>
				
		<form name="frm" method="post">
		<input type="hidden" name="mode" value="list_mod">
		<input type="hidden" name="bc_id" value="<?=$bc_id?>">
		<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
		<input type="hidden" name="search" value="<?=$search?>">
		<input type="hidden" name="search_text" value="<?=$search_text?>">
		<input type="hidden" name="page" value="<?=$page?>">
		
		<table class="adm-list-tb">
		<colgroup>
			<col width="5%" />
			<col width="10%" />
			<col width="10%" />
			<col width="10%" />
			<col width="10%" />
			<col width="10%" />
			<col width="25%" />
			<col width="5%" />
			<col width="15%" />
		</colgroup>
			<tr>
				<th>NO</th>
				<th>휴대폰번호</th>
				<th>사용 주문번호</th>
				<th>이름</th>
				<th>제휴사</th>
				<th>쿠폰번호</th>
				<th>사용기간</th>
				<th>할인율</th>
				<th>등록일</th>
			</tr>

			<? if ($total_record == 0) { ?>
				<tr>
					<td colspan="9"><?=$GLOBALS["msg_list_notdata"]?></td>
				</tr>
			<?
				} else {
					while ($rows = $dbcon -> fetch_array($result)) {
						extract($rows);
						unset($rows);
			?>
				<tr>
					<td><?=$no?></td>
					<td><?=all_seed_dec($mobile)?></td>
					<td><?=$orderno?></td>
					<td><?=all_seed_dec($o_name)?></td>
					<td><?=$partnership_name?></td>
					<td><?=$partner_coupon?></td>
					<td><?=$start_date?>~<?=$end_date?></td>
					<td><?=$partner_coupon_discount;?>%</td>
					<td><?=substr($writedate, 0, 10);?></td>
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
<script>
	function g_select(idx,subject){
		var ff = opener.document.all;
		ff.event_seq.value=idx;
		$("#event_subject",opener.document).text(subject);
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
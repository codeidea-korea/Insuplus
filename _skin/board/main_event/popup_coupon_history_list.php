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



	if ( strlen($user_name) > 0 ) {
		$query_where .= " and a.user_name like '%".$user_name."%' ";
	}

	if ( strlen($phon_number) > 0 ) {
		$query_where .= " and a.phon_number like '%".$phon_number."%' ";
	}

	if ( strlen($use_yn) > 0 ) {
		$query_where .= " and a.use_yn like '%".$use_yn."%' ";
	}

	$parameter = "&user_name=".$user_name."&phon_number=".$phon_number."&use_yn=".$use_yn;

	#### 검색 설정 End

	// 쿼리설정
	$SQL = "";
	$SQL .= "
		SELECT 
			COUNT(*)
		FROM tbl_board_coupon_history a
		LEFT JOIN tbl_board_event b ON (a.coupon_code = b.seq)
		LEFT JOIN tbl_board_partner c ON (a.partnership_code = c.partnership_code)
		LEFT JOIN tbl_board_product d ON (a.product_code = d.seq)";
	$SQL .= " WHERE 1=1 AND coupon_code='".$coupon_code."' ".$query_where;
	// 총 카운트
	$total_count = $dbcon -> getCount($SQL);
	$SQL = "
	SELECT 
		a.seq AS seq,
		d.subject AS product_name,
		c.partnership_name AS partnership_name,
		a.user_name AS user_name,
		a.phon_number AS phon_number,
		b.expire_date_s AS expire_date_s,
		b.expire_date_e AS expire_date_e,
		a.use_yn AS use_yn,
		a.regdate AS regdate
	FROM tbl_board_coupon_history a
	LEFT JOIN tbl_board_event b ON (a.coupon_code = b.seq)
	LEFT JOIN tbl_board_partner c ON (a.partnership_code = c.partnership_code)
	LEFT JOIN tbl_board_product d ON (a.product_code = d.seq)
	";
	$SQL .= " WHERE 1=1 AND coupon_code='".$coupon_code."' ".$query_where;
	$SQL .= " ORDER BY a.regdate DESC ";
	$SQL .= " limit ".$limit;
	$result = $dbcon -> query($SQL);

	// 페이지 & 리스트 설정
	$total_page		= ceil($total_count/$num_per_page);
	$no				= $total_count - $first;


	$RS_PR = getGlobalProduct(); //상품명 불러오기
	$RS_INS = getGlobalIns(); //보험사 불러오기
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
		<? if ($bc_category_use == "Y") { ?>
		<input type="hidden" name="search_category" value="<?=$search_category?>">
		<? } ?>
			<table class="adm-searchForm">
				<colgroup>
					<col width="20%" />
					<col width="30%" />
					<col width="20%" />
					<col width="10%" />
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
						<th>휴대폰 번호</th>
						<td>
							<input type="number" name="phon_number" maxlength="30" value="<?=$phon_number?>"/>
						</td>
						<th>이름</th>
						<td>
							<input type="text" name="user_name" maxlength="30" value="<?=$user_name?>"/>
						</td>
					</tr>
					<tr><td colspan=4 align=right><input type="submit" value="검색"></td></tr>
				</tbody>
			</table>
		</form>
		
		<div class="btnWrapL">
			<span>쿠폰명 : <?=$coupon_code?></span>
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
			<col width="15%" />
			<col width="10%" />
			<col width="10%" />
			<col width="15%" />
			<col width="25%" />
			<col width="10%" />
			<col width="25%" />
		</colgroup>
			<tr>
				<th>상품명</th>
				<th>제휴사</th>
				<th>이름</th>
				<th>휴대폰번호</th>
				<th>사용기간</th>
				<th>사용여부</th>
				<th>등록일</th>
			</tr>

			<? if ($total_count == 0) { ?>
				<tr>
					<td colspan="7"><?=$GLOBALS[msg_list_notdata]?></td>
				</tr>
			<?
				} else {
					while ($rows = $dbcon -> fetch_array($result)) {
						extract($rows);
						unset($rows);
			?>
				<tr>
					<td><?if(!$product_name){
						echo "보험가입";
						} else {
						echo $product_name;
						}?></td>
					<td><?if(!$partnership_name){
						echo "-";
						} else {
						echo $partnership_name;
						}?></td>
					<td><?=$user_name?></td>
					<td><?=$phon_number?></td>
					<td><?if(!$partnership_name){
						echo "개시일 ~ 90일";
						} else {
						echo substr($expire_date_s, 0, 10)." ~ ".substr($expire_date_e, 0, 10);
						}?></td>
					<td>
					<?
					if($use_yn == 'Y'){
						echo "사용"; 
					} else {
						echo "미사용"; 
					}
					?>
					</td>
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
<!DOCTYPE html>
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);

	$tm = "";
	$lm = "";
	include $path_admin."inc/header_pop.php";
	
	$page_btn_prev =">>"; // > 버튼
	$page_btn_next ="<<"; // < 버튼
	
	// 페이지 설정
	$page				= REQSTR($page, 1);
	$num_per_page	= REQSTR($num_per_page, 20);
	$page_per_block	= REQSTR($page_per_block, 10);
	$first					= $num_per_page*($page-1);
	$last					= $num_per_page*$page;
	
	// 검색설정
	$query_where		= "  ";
	
	#### 검색 설정 Start
	$search_u_level				= REQSTR($search_u_level, "");
	$search_u_gubun				= REQSTR($search_u_gubun, "");
	$search_u_state				= REQSTR($search_u_state, "");
	$search_u_sex				= REQSTR($search_u_sex, "");
	
	$search							= REQSTR($search, "");
	$search_text					= REQSTR($search_text, "");
	

	$search_date					= REQSTR($search_date, "");
	$search_date_s				= REQSTR($search_date_s, "");
	$search_date_e				= REQSTR($search_date_e, "");
	
	if ( strlen($search_text) > 0 ) {
		$query_where .= " and ".$search." like '%".$search_text."%' ";
	}
	
	if ( strlen($search_date_s) > 0 ) $query_where .= " and ".$search_date." >= '".$search_date_s."' ";
	if ( strlen($search_date_e) > 0 ) $query_where .= " and ".$search_date." <= '".$search_date_e."' ";
	
	$parameter = "&search=".$search."&search_text=".$search_text."&recommend_seq=".$recommend_seq;
	
	#### 검색 설정 End
	
	// 쿼리설정
	$field			 = " h.orderno, h.recommend_name , h.writedate ";	
	$field			.= " ,c.recom_partnership_code ";
	$field			.= " ,p.partnership_name ";
	$table			 = " tbl_board_recommend_code_history h INNER JOIN  tbl_board_recommend_code c ";
	$table          .= " ON h.recommend_seq = c.seq ";
	$table   		.= " LEFT JOIN tbl_board_partner p ";
	$table          .= " ON c.recom_partnership_code = p.partnership_code ";
	$where			.= "AND h.recommend_seq = '".$recommend_seq."' ".$query_where;
	$orderby		 = " h.writedate DESC ";
	$limit			 = $first.", ".$num_per_page;
	
	
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
		<h1>추천코드 사용내역</h1>
		<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
	</header>
	
	<div class="popContWrap">
		<table border="0" cellpadding="0" cellspacing="0" width="100%" >
			<tr>
				<td>
					<!-- (s) 검색영역  -->
					<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
					<table class="adm-searchForm">
						<colgroup>
							<col width="8%" />
							<col width="20%" />
							<col width="92%" />
						</colgroup>
						<tr>
							<th>이름</th>
							<td colspan="2">
								<input type="hidden" name="search" value="contractor" />
								<input type="text" name="search_text" value="<?=$search_text?>" />
								<input type="submit" value="검색" />
							</td>
						</tr>
					</table>			
					</form>
					<!-- (e) 검색영역  -->
					
					<table class="adm-list-tb">
					<colgroup>
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
						<col width="10%" />
					</colgroup>
					<tr>
						<th>상품명</th>
						<th>제휴사</th>
						<th>추천코드</th>
						<th>계약자</th>
						<th>상품가격</th>
						<th>등록일</th>
					</tr>
					<? if ($total_record == 0) { ?>
					<tr>
						<td colspan="6"><?=$GLOBALS[msg_list_notdata]?></td>
					</tr>
					<?
					} else {
						while ($rows = $dbcon -> fetch_array($result)) {
							extract($rows);
							unset($rows);
						?>
						<tr>
							<td></td>
							<td><?=$partnership_name;?></td>
							<td><?=$recommend_name;?></td>
							<td></td>
							<td></td>
							<td><?=substr($writedate,0,10)?></td>
						</tr>
						<?
							$no = $no - 1;
						}
					}
					unset($result);

					?>
					</table>
					
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
				</td>
			</tr>
		</table>
	</div>
</div>
</html>
<? $dbcon -> dbcon_close();?>

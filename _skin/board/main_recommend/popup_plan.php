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

// 검색설정
$query_where		= "  ";

#### 검색 설정 Start
$search_u_level				= REQSTR($search_u_level, "");
$search_u_gubun				= REQSTR($search_u_gubun, "");
$search_u_state				= REQSTR($search_u_state, "");
$search_u_sex				= REQSTR($search_u_sex, "");

$search							= REQSTR($search, "");
$search_text					= REQSTR($search_text, "");

$pr_cd						= REQSTR($pr_cd, "");
$ins_cd						= REQSTR($ins_cd, "");
$plan_cd					= REQSTR($plan_cd, "");
$chk_service					= REQSTR($chk_service, "");

$search_orderby				= REQSTR($search_orderby, "");
$search_sort					= REQSTR($search_sort, "");

$search_date					= REQSTR($search_date, "");
$search_date_s				= REQSTR($search_date_s, "");
$search_date_e				= REQSTR($search_date_e, "");


if ( strlen($search_text) > 0 ) {
	$query_where .= " and ".$search." like '%".$search_text."%' ";
}

if ( strlen($pr_cd) > 0 ) {
	$query_where .= " and pr_cd like '%".$pr_cd."%' ";
}
if ( strlen($ins_cd) > 0 ) {
	$query_where .= " and ins_cd like '%".$ins_cd."%' ";
}
if ( strlen($plan_cd) > 0 ) {
	$query_where .= " and plan_cd like '%".$plan_cd."%' ";
}
if ( strlen($chk_service) > 0 ) {
	$query_where .= " and chk_service like '%".$chk_service."%' ";
}

if ( strlen($search_date_s) > 0 ) $query_where .= " and ".$search_date." >= '".$search_date_s."' ";
if ( strlen($search_date_e) > 0 ) $query_where .= " and ".$search_date." <= '".$search_date_e."' ";


if ( strlen($search_orderby) == 0 ) $search_orderby .= " seq ";
if ( strlen($search_sort) == 0 ) $search_sort .= "desc";

//	echo "search_u_level : ".$search_u_level."<BR>";
//	echo "search_u_gubun : ".$search_u_gubun."<BR>";
//	echo "search_u_state : ".$search_u_state."<BR>";
//	echo "search_u_sex : ".$search_u_sex."<BR>";
//	echo "search_orderby : ".$search_orderby."<BR>";
//	echo "search_sort : ".$search_sort."<BR>";
//	echo $query_where."<BR>";


$parameter = "&search=".$search."&search_text=".$search_text;

#### 검색 설정 End

// 쿼리설정
$field				= " * ";

$table			= " tbl_board_plan ";
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

$RS_PR = getGlobalProduct(); //상품명 불러오기
$RS_INS = getGlobalIns(); //보험사 불러오기
	
?>
<div class="popupWrap">
	<header>
		<h1>플랜찾기</h1>
		<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
	</header>
	
	<div class="popContWrap">
		<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
		<input type="hidden" name="bc_id" value="<?=$bc_id?>">
		<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
		<input type="hidden" name="mode" value="list">
		<input type="hidden" name="num" value="<?=$num?>">
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
						<th>상품</th>
						<td>
							<select name="pr_cd">
								<option value="">상품명 선택</option>
								<?while ($pr_row = $dbcon -> fetch_array($RS_PR) ) {?>
									<option value="<?=$pr_row["seq"]?>" <?if ($pr_cd==$pr_row["seq"]){?>selected<?}else{}?>><?=$pr_row["subject"]?></option>
								<? } ?>
							</select>
							<select name="ins_cd">
								<option value="">보험사 선택</option>
								<?while ($ins_row = $dbcon -> fetch_array($RS_INS) ) {?>
									<option value="<?=$ins_row["seq"]?>" <?if ($ins_cd==$ins_row["seq"]){?>selected<?}else{}?>><?=$ins_row["subject"]?></option>
								<? } ?>
							</select>
							<select name="plan_cd">
								<option value="">플랜 선택</option>
								<?for($c=0;$c<count($Arr_plan_cd);$c++){?>
								<option value="<?=$c+1?>" <?if ($plan_cd==$c+1){?>selected<?}else{}?>><?=$Arr_plan_cd[$c+1]?></option>
								<?}?>
							</select>
		
							
						</td>
					</tr>
					<tr>
						<th>서비스</th>
						<td><select name="chk_service">
								<option value="">서비스 선택</option>
								<option value="A" <?if ($chk_service=="A"){?>selected<?}else{}?>>A타입</option>
								<option value="B" <?if ($chk_service=="B"){?>selected<?}else{}?>>B타입</option>
								<option value="C" <?if ($chk_service=="C"){?>selected<?}else{}?>>인슈플러스</option>
								<option value="D" <?if ($chk_service=="D"){?>selected<?}else{}?>>플라잉닥터스</option>
								<option value="N" <?if ($chk_service=="N"){?>selected<?}else{}?>>없음</option>
							</select>
							<input type="submit" value="검색"></td>
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
			<col width="20%" />
			<col width="20%" />
			<col width="20%" />
			<col width="20%" />
			<col width="20%" />
		</colgroup>
			<tr>
				<th>상품명</th>
				<th>보험사</td>
				<th>플랜</th>
				<th>서비스</th>
				<th>등록일</th>
			</tr>
			<? if ($total_record == 0) { ?>
				<tr>
					<td colspan="5"><?=$GLOBALS["msg_list_notdata"]?></td>
				</tr>
			<?
				} else {
					while ($rows = $dbcon -> fetch_array($result)) {
						extract($rows);
						unset($rows);
						$chk_service_name = "없음";
						if($chk_service == "A") {
							$chk_service_name = "A타입";
						} else if($chk_service == "B") {
							$chk_service_name = "B타입";
						} else if($chk_service == "C") {
							$chk_service_name = "인슈플러스";
						} else if($chk_service == "D") {
							$chk_service_name = "플라잉닥터스";
						}
			?>
				<tr>
					<td><?=print_pr_name($pr_cd)?></td>
					<td><?=print_ins($ins_cd)?></td>
					<td><a href="javascript:g_select('<?=$seq?>', '<?=print_pr_name($pr_cd)?> <?=print_ins($ins_cd)?> <?=$Arr_plan_cd[$plan_cd]?> <?=$chk_service_name;?>');"><?=$Arr_plan_cd[$plan_cd]?></a></td>
					<td><?=$chk_service_name;?></td>
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
		ff.plan_seq_<?=$num?>.value=idx;
		$("#plan_subject_<?=$num?>",opener.document).text(subject);
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
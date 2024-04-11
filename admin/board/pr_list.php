<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

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
$query_where		= "";

#### 검색 설정 Start
$search_u_level				= REQSTR($search_u_level, "");
$search_u_gubun				= REQSTR($search_u_gubun, "");
$search_u_state				= REQSTR($search_u_state, "");
$search_u_sex				= REQSTR($search_u_sex, "");

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

if ( strlen($search_text) > 0 ) {
    if ( $search == "u_email" )
        $query_where .= " and u_email1 like '%".$search_text."%' or u_email2 like '%".$search_text."%' ";
    else
        $query_where .= " and ".$search." like '%".$search_text."%' ";
}

if ( strlen($search_date_s) > 0 ) $query_where .= " and ".$search_date." >= '".$search_date_s."' ";
if ( strlen($search_date_e) > 0 ) $query_where .= " and ".$search_date." <= '".$search_date_e."' ";


if ( strlen($search_orderby) == 0 ) $search_orderby .= " code asc ";

$parameter = "&search_u_level=".$search_u_level."&search_u_gubun=".$search_u_gubun."&search_u_state=".$search_u_state."&search_u_sex=".$search_u_sex."&search=".$search."&search_text=".$search_text."&search_orderby=".$search_orderby."&search_sort=".$search_sort."&num_per_page=".$num_per_page;

#### 검색 설정 End

// 쿼리설정
$field				= " * ";

$table			= " tbl_symptom A ";
$where			= $query_where;
//echo $where;
//exit;
$orderby			= $search_orderby." ".$search_sort;
$limit				= $first.", ".$num_per_page;
echo $orderby	;
$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);
$total_record	= $ArrRS[0];
$result			= $ArrRS[1];
unset($ArrRS);

// 페이지 & 리스트 설정
$total_page		= ceil($total_record/$num_per_page);
$no				= $total_record - $first;


$tm = "board";
$lm = "";
include $path_admin."inc/header.php";
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="a_st">증상별 프로그램</td>
		<td align="right"></td>
	</tr>
</table>

<table width="1100" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td>
			<a href="pr_write.php"><img src="/_skin/board/faq/images/b_btn_write.gif"></a>
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td colspan="100" height="2" bgcolor="#D5D5D5"></td>
				</tr>
				<tr>
					<td align="center" height="28" bgcolor="#F3F3F3" class="a_thead" width="50">No.</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="100">증상</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="45">수정</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="45">삭제</td>
				</tr>
				<?
				if ($total_record == 0) {
				?>
				<tr align="center">
					<td colspan="100" height="28" colspan="4"><b><?=$GLOBALS[msg_list_notdata]?></b></td>
				</tr>
				<tr>
					<td colspan="100" height="1" bgcolor="#E5E5E5" colspan="4"></td>
				</tr>
				<?
				}
				else {
				    while ($rows = $dbcon -> fetch_array($result)) {
				        extract($rows);
				        unset($rows);

				        $r_time1			= mb_substr($r_time, 0, 2);
				        $r_time2			= mb_substr($r_time, 2, 2);
				        $regdate			= mb_substr($regdate, 0, 10);
				        if ( $u_id != "" ) {
				            $print_member_icon = "<img src='".$url_admin."images/a_icon_y.gif' alt='회원상태'>";
				        }
				        else{
				            $print_member_icon = "<img src='".$url_admin."images/a_icon_n.gif' alt='비회원상태'>";
				        }
				?>
				<tr>
					<td align="center" class="a_content_td" height="28"><?=$no?></td>
					<td align="center" class="a_content_td"><font color="#FF6600"><b><?=$Arr_cost_detail_list[$code]?></b></font></td>
					<td align="center" class="a_content_td">
						<a href="javascript:modify_go('<?=$idx?>');"><img src='<?=$url_admin?>images/a_icon_modify.gif' alt='정보 수정'></a>
					</td>
					<td align="center" class="a_content_td">
						<a href="javascript:drop_go('<?=$idx?>', 1);"><img src='<?=$url_admin?>images/a_icon_delete.gif' alt='정보삭제'></a>
					</td>
				</tr>
				<tr>
					<td colspan="100" height="1" bgcolor="#E5E5E5"></td>
				</tr>
				<?
				        $no = $no - 1;
				    }
				}
				unset($result);

				?>
			</table>


			<table align=center>
				<tr>
					<td>
						<table cellpadding=0 cellspacing=3 border=0>
							<tr>
								<td width="4">&nbsp;</td>
								<td valign="top" style="padding:1 0 0 0">
									<?
									$bc_skin = "default";
									list_page($page, $total_page, $page_per_block) ;
									?>
								</td>
								<td width="4">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table border=0 cellspacing=0 cellpadding=0 width=100%>
				<tr>
					<td height=20></td>
				</tr>
			</table>


		</td>
	</tr>
</table>

<script>

function page_go(page) {
    location.href = "?page="+page+"<?=$parameter?>";
}

function modify_go(idx) {
	document.location.href="pr_write.php?idx="+idx;
}

function drop_go(idx) {
    MSG = "정말 삭제 하시겠습니까?\n삭제하면 기존 정보로 복원은 하실 수 없습니다.";
    if (confirm(MSG)) {
        location.href = "pr_del.php?idx="+idx+"<?=$parameter?>";
    }
}
</script>
<!-- ### 페이지 끝 ###  -->
<?
$dbcon -> dbcon_close();

include $path_admin."inc/footer.php";
?>

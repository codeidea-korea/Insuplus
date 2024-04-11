<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$tm = "board";
	$lm = "";
	include $path_admin."inc/header.php";
?>

<?
	$html = new html;
	echo $html -> getAdminTitle("실시간상담");
?>


<?
	// 페이지 이미지 처리
	//$page_btn_first ="<img src=\"/images/99_common/btn_fast_prev.gif\" />";  // >> 버튼
	$page_btn_prev ="<img src=\"".$url_admin."images/b_prev.gif\" />"; // > 버튼
	$page_btn_next ="<img src=\"".$url_admin."images/b_next.gif\" />"; // < 버튼
	//$page_btn_last ="<img src=\"/images/99_common/btn_fast_next.gif\" />";  // << 버튼

	// 페이지 설정
	$page				= REQSTR($page, 1);
	$num_per_page	= REQSTR($num_per_page, 10);
	$page_per_block	= REQSTR($page_per_block, 10);
	$first					= $num_per_page*($page-1);
	$last					= $num_per_page*$page;

	$search_orderby = REQSTR($search_orderby, "");

	// 검색설정
	$query_where		= "";
	//$query_where .= " and u_level <= '".$ss_u_level."' ";

	if ( !$search_orderby ) $search_orderby .= "idx";
	if ( !$search_sort ) $search_sort .= "desc";


	$parameter = "&search=".$search."&search_text=".$search_text."&search_orderby=".$search_orderby."&search_sort=".$search_sort."&num_per_page=".$num_per_page;


	$table_name = "tbl_messenger";

	// 카운트 실행
	$SQL = "
		select count(*) from ".$table_name." where 1=1 ".$query_where."
	";
	//mysql_query($SQL);
	$total_record			= $dbcon->getCount($SQL);

	//쿼리실행
	$SQL = "
		select * from ".$table_name." where 1=1 ".$query_where." order by ".$search_orderby." ".$search_sort." limit ".$first.", ".$num_per_page."
	";
	//echo $SQL."<BR>";
	$rs			= $dbcon->query($SQL);


	// 페이지 & 리스트 설정
	$total_page		= ceil($total_record/$num_per_page);
	$no				= $total_record - $first;


?>

<table width="750" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td>
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td class="a_st01"><img src="/admin/images/admin_ball.gif"> 실시간상담</td>
					<td align="right">※ <b>총 <font color=#FF0000><?=$total_record?></font> 개의 문의가 있습니다.</b></td>
				</tr>
				<tr>
					<td height="4" colspan="2"></td>
				</tr>
			</table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td colspan="8" height="2" bgcolor="#D5D5D5"></td>
				</tr>
				<tr height="28">
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="40">No.</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="100">이름</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="100">전화번호</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="100">네이트주소</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead">상담내용</td>

					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="100">등록일시</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="120">상태</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="52">삭제</td>
				</tr>

				<? if ( $total_record ) { ?>
					<?
						while ( $rows = $dbcon -> fetch_array($rs) ) {
							extract($rows);
							unset($rows);

							if ( $state == 0 ) {
								$print_state = "상담대기";
							} elseif ( $state == 1 ) {
								$print_state = "처리완료";
							}
					?>
				<tr height="28">
					<td align="center" class="a_content_td"><?=$no?></td>
					<td align="center" class="a_content_td"><font color="#FF6600"><b><?=$name?></b></font></td>
					<td align="center" class="a_content_td"><?=$tel1?>-<?=$tel2?>-<?=$tel3?></td>
					<td align="center" class="a_content_td"><?=$messenger?></td>
					<td align="center" class="a_content_td"><?=RESSTR($content)?></td>

					<td align="center" class="a_content_td"><?=str_replace(" ", "<BR>", $regdate)?></td>
					<td align="center" class="a_content_td"><a href="messenger_ok.php?idx=<?=$idx?>&state=<?=$state?><?=$parameter?>"><?=$print_state?></a></td>
					<td align="center" class="a_content_td"><a href="messenger_del.php?idx=<?=$idx?>&state=<?=$state?><?=$parameter?>"><img src='/admin/images/a_icon_delete.gif' alt='삭제'></a></td>
				</tr>
				<tr>
					<td colspan="8" height="1" bgcolor="#E5E5E5"></td>
				</tr>
					<?
							$no--;
						}
						unset($rs);
					?>
				<? } ?>
				<tr>
					<td colspan="8" height="1" bgcolor="#E5E5E5"></td>
				</tr>
			</table>


			<table align=center>
				<tr>
					<td>
						<table cellpadding=0 cellspacing=3 border=0>
							<tr>
								<td width="4">&nbsp;</td>
								<td valign="top" style="padding:1 0 0 0">
									<?
										list_page($page, $total_page, $page_per_block) ;
									?>
								</td>
								<td width="4">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<script>
	function page_go(page) {
		location.href = "?page="+page+"<?=$parameter?>";
	}

	//location.href = "view.php?page="+page+"<?=$parameter?>";

</script>

<? include $path_admin."inc/footer.php"; ?>


<? $dbcon -> dbcon_close();?>

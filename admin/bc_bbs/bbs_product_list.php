<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$page = REQSTR($page, 1);
	$num_per_page = REQSTR($num_per_page, 20);
	$page_per_block = REQSTR($page_per_block, 10);

	$first = $num_per_page*($page-1);
	$last = $num_per_page;

	$query_where = "";

	########################################
	#### 검색 설정
	$search = REQSTR($search, "");
	$search_text = REQSTR($search_text, "");
	if ( (getLen($search_text) > 0) ) {
		$query_where .= " and ".$search." like '%".$search_text."%'";
	}

	#### parameter 설정
	$parameter = "search=".$search."&search_text=".$search_text."&num_per_page=".$num_per_page."&page_per_block=".$page_per_block;
	########################################

	########################################
	#### 게시글을 가져온다.. ####
	$field = " * ";
	$table = "tbl_productregist  ";
	$where = $query_where;
	$orderby = " idx  desc ";
	$limit = $first.", ".$last;

	$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);

	$total_record = $ArrListRs[0];
	########################################

	########################################
	#### 전체 페이지수를 계산한다.
	$total_page = ceil($total_record/$num_per_page);

	$no = $total_record - $first;

	########################################

?>
<?
	$tm = "bbs";
	$lm = "";
	include $path_admin."inc/header.php";
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">제품등록관리 </td>
	</tr>
	<tr>
		<td colspan="2" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 마이페이지 > 제품등록관리</td>
		<td align="right">※ <b>총 <font color=#FF0000><?=$total_record?></font> 개의 게시판이 있습니다.</b></td>
	</tr>
	<tr>
		<td height="4" colspan="2"></td>
	</tr>
</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td colspan="8" height="2" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="60" height="28">No.</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="120">등록자 아이디</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead">제품구분</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="120">모델명</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="120">S/N</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="60">등록일</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="52">수정</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="52">삭제</td>
	</tr>
<?
	#### 게시글을 시작 ####
	if ( $total_record > 0 ) {
		while($ListRs = $dbcon -> fetch_array($ArrListRs[1])) {
			extract($ListRs);
			unset($ListRs);


			$print_pc_num  = "";
			if ( $pc_num ) {
				$TmpSQL = "
					select
						pc_name
					from
						tbl_product_category
					where
						pc_num = '".substr($pc_num, 0, 2)."'
					order by
						pc_sort asc
				";
				$TmpRs = $dbcon -> fetch_array($dbcon -> query($TmpSQL) );

				$print_pc_num = $TmpRs["pc_name"];

				$TmpSQL = "
					select
						pc_name
					from
						tbl_product_category
					where
						pc_num = '".$pc_num."'
					order by
						pc_sort asc
				";
				$TmpRs = $dbcon -> fetch_array($dbcon -> query($TmpSQL) );

				$print_pc_num .= "<BR>" .$TmpRs["pc_name"];

		//		echo "SelCate : ".$SelCate."<BR>";
		//		echo "pc_num : ".$pc_num."<BR>";
		//		echo "print_pc_num : ".$print_pc_num."<BR>";

			}
?>

	<tr>
		<td align="center" class="a_content_td" height="28"><?=$no?></td>
		<td align="center" class="a_content_td"><font color="#FF6600"><b><?=$u_id?></b></font></td>
		<td align="center" class="a_content_td"><?=$print_pc_num?></td>
		<td align="center" class="a_content_td"><?=$pr_name?></td>
		<td align="center" class="a_content_td"><?=$pr_code?></td>
		<td align="center" class="a_content_td"><?=date("Y.m.d", strtotime($regdate))?></td>
		<td align="center" class="a_content_td"><a href="javascript:modify_go('<?=$idx?>');"><img src='<?=$url_admin?>images/a_icon_modify.gif' ></a></td>
		<td align="center" class="a_content_td"><a href="javascript:delete_go('<?=$idx?>');"><img src='<?=$url_admin?>images/a_icon_delete.gif' ></a></td>
	</tr>
	<tr>
		<td colspan="8" height="1" bgcolor="#E5E5E5"></td>
	</tr>
<?
			$no--;
		}
	}
	else {
?>
	<tr align="center">
		<td colspan="15" class="a_content_td">등록 된 데이터가 없습니다</td>
	</tr>
	<tr>
		<td colspan="8" height="1" bgcolor="#E5E5E5" height="28"></td>
	</tr>
<?
	}
	unset($ArrListRs);
	#### 게시글을 끝 ####

?>
	<tr>
		<td colspan="8" height="2" bgcolor="#E5E5E5"></td>
	</tr>
</table>
		<? list_page_sindoh($page, $total_page, $page_per_block); ?>


<script>
	function page_go(page) {
		location.href = "?page="+page+"&<?=$parameter?>";
	}

	function modify_go(idx) {
		location.href = "bbs_product_modify.php?idx="+idx;
	}

	function delete_go(idx) {
		if (confirm("정말로 삭제하시겠습니까?\n삭제한 데이타는 다시 복구 할 수 없습니다.")) {
			location.href = "bbs_product_del_ok.php?idx="+idx;
		}
	}

</script>
<!-- ### 페이지 끝 ###  -->
<?
	$dbcon -> dbcon_close();
?>
<? include $path_admin."inc/footer.php"; ?>

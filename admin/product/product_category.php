<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?

	$query_where = "";
	$query_where .= "
		and length(pc_num) = ".(getLen($search_pc_num) + 2)."
		and pc_num like '".$search_pc_num."%'
	";

	$print_pc_lev_name = " ( <a href=\"?search_pc_num=\">최상위</a> ";
	if ( getLen($search_pc_num) > 0 ) {

		$print_pc_lev_name .= " > ";
		$RS_pc_num = getprecategoryname($search_pc_num, 2);
		while( $row_pc_num = $dbcon -> fetch_array($RS_pc_num) ) {
			$print_pc_lev_name .= "<a href=\"?search_pc_num=".$row_pc_num[pc_num]."\">";
			if ( $search_pc_num == $row_pc_num[pc_num] ) {
				$print_pc_lev_name .= "<b>".$row_pc_num[pc_name]."</b>";
			}
			else {
				$print_pc_lev_name .= $row_pc_num[pc_name]." > ";
			}
			$print_pc_lev_name .= "</a>";

			$row_pc_num_last = $row_pc_num[pc_num];
		}
	}
	$print_pc_lev_name .= " ) ";

	// 쿼리설정
	$field				= "
		*
		, ( select count(pc_num) from tbl_product_category where length(pc_num) = length(A.pc_num) + 2 and SUBSTRING(pc_num, 1, length(A.pc_num) ) = A.pc_num ) as next_pc_num_cnt
	";
	$table			= " tbl_product_category A ";
	$where			= $query_where;
	$orderby			= " pc_sort asc ";
	$limit				= "";
	$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);
	$total_record	= $ArrRS[0];
	$result			= $ArrRS[1];
	unset($ArrRS);

	$parameter = "search_pc_num=".$search_pc_num;

?>
<?
	$tm = "product";
	$lm = "";
	include $path_admin."inc/header.php";
?>
<script language='JavaScript'>
<!--
function rankChange(cid,tid)
{
	var f = document.frmChangRank;
	f.f_cid.value = cid;
	f.f_tid.value = tid;
	f.submit();
}
//-->
</script>
<form name="frmChangRank" method="post" action="<?=$url_admin?>admin_process.php">
<input type="hidden" name="mode" value="pro_category_rank">
<input type="hidden" name="f_cid">
<input type="hidden" name="f_tid">
</form>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">  제품 카테고리 관리</td>
		<td width="300" align="right">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td align="right">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td bgcolor="#339933" width="150" height="22" align="center" class="a_tab_txt" valign="top" style="padding-top:3px"><a href='product_category_write.php?pre_pc_num=<?=$row_pc_num_last?>&<?=$parameter?>'>카테고리 등록</a> </td>
								<td width="2"></td>
								<!-- <td bgcolor="#000000" width="150" height="22" align="center" class="a_tab_txt" valign="top" style="padding-top:3px"><a href='<?=$url_admin?>product/product_category_modify_all.php'>카테고리설정 전체변경</a> </td> -->
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="3" height="19"></td>
	</tr>
</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 제품 카테고리 리스트 <?=$print_pc_lev_name // category map?></td>
		<td align="right">※ <b>총 <font color="#FF0000"><?=$total_record?></font> 개</b></td>
	</tr>
	<tr>
		<td height="2" colspan="2"></td>
	</tr>
</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td colspan="100" height="2" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" height="28" style="padding-left:5px">제품 카테고리명</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="120">카테고리 코드</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="100">제품등록</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="60">사용여부</td>
		<!-- <td align="center" bgcolor="#F3F3F3" class="a_thead" width="45">순서△</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="45">순서▽</td> -->
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="68">하위추가</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="35">보기</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="43">설정</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="48">삭제</td>
	</tr>

<? if ($total_record == 0) { ?>
	<tr align="center">
		<td colspan="100"><?=$GLOBALS[msg_list_notdata]?></td>
	</tr>
<?
	} else {
		while ($rows = $dbcon -> fetch_array($result)) {
			extract($rows);
			unset($rows);

			// Level Image
			$img_pc_lev = "";
			//for ($i = 0; $i < getLen($pc_num); $i++) $img_pc_lev .= "&nbsp;&nbsp;";
			$img_pc_lev .= "<img src=\"".$url_admin."images/a_icon_lev".( getLen($pc_num) / 2 ).".gif\">";

			$img_pc_add = "<a href=\"product_category_write.php?pc_idx=".$pc_idx."\"><img src=\"".$url_admin."images/a_icon_modify.gif\" alt=\"카테고리 수정\"></a>";
			$img_pc_add_next = "<a href=\"product_category_write.php?pre_pc_num=".$pc_num."\"><img src=\"".$url_admin."images/a_icon_pcateadd.gif\" alt=\"하위카테고리 추가\"></a>";

			$img_pc_del = "";
			if ( $next_pc_num_cnt > 0 ) {
				$img_pc_del = "<img src=\"".$url_admin."images/a_icon_delete0.gif\" alt=\"제품이 등록되거나 하위카테고리가 있는 카테고리는 삭제하실 수 없습니다. 삭제하시려면 먼저 하위카테고리 및 제품을 삭제해 주십시오.\">";
			}
			else {
				$img_pc_del = "<a href=\"product_category_del.php?pc_idx=".$pc_idx."&".$parameter."\"><img src=\"".$url_admin."images/a_icon_delete.gif\" alt=\"제품카테고리 삭제\"></a>";
			}

			$print_use = "";
			if ( $pc_use == "Y") $print_use = "사용중";
			else $print_use = "미사용";
?>
	<tr>
		<!-- Category Name -->
		<td class="a_content_td" height="28">
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td class="a_content_td"><?=$img_pc_lev?></td>
					<td class="a_content_td" width="4"></td>
					<td class="a_content_td" style="padding-top:1px"><a href="?search_pc_num=<?=$pc_num?>"><?=$pc_name?> (<?=$next_pc_num_cnt?>)</a></td>
				</tr>
			</table>
		</td>
		<!-- Category Code -->
		<td align="center" class="a_content_td">
			<font color="#FF6600"><b><?=$pc_num?></b></font>
		</td>
		<td align="center" class="a_content_td" style="padding-top:1px"><a href="product_write.php?pc_num=<?=$pc_num?>"><img src='<?=$url_admin?>images/a_icon_prowrite.gif' alt='제품등록'></a></td>

		<td align="center" class="a_content_td"><?=$print_use ?></td>

		<!-- <td align="center" class="a_content_td"><img src='<?=$url_admin?>images/a_icon_up0.gif'></td>
		<td align="center" class="a_content_td"><img src='<?=$url_admin?>images/a_icon_down1.gif' alt='카테고리 출력순서 아래로' onClick="javascript:rankChange('2','1')" style='cursor:hand'></td> -->
		<td align="center" class="a_content_td"><?=$img_pc_add_next?></td>
		<td align="center" class="a_content_td"><a href="<?=$url_product?>/product_list.php?pc_num=<?=$pc_num?>" target="_blank"><img src='<?=$url_admin?>images/a_icon_brdview.gif' alt='카테고리 보기'></a></td>
		<td align="center" class="a_content_td"><?=$img_pc_add?></td>
		<td align="center" class="a_content_td">
			<?=$img_pc_del?>
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

	<!-- <tr>
		<td class="a_content_td" height="28">
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td class="a_content_td" width="5"></td>
					<td width="35" class="a_content_td"><img src=<?=$url_admin?>images/a_icon_lev1.gif></td>
					<td class="a_content_td" width="4"></td>
					<td class="a_content_td" style="padding-top:1px">제품카테고리2</td>
				</tr>
			</table>
		</td>
		<td align="center" class="a_content_td"><font color="#FF6600"><b>1</b></font></td>
		<td align="center" class="a_content_td" style="padding-top:1px"><a href="<?=$url_admin?>product/product_write.php?cnum=2"><img src='<?=$url_admin?>images/a_icon_prowrite.gif' alt='제품등록'></a></td>
		<td align="center" class="a_content_td"><img src='<?=$url_admin?>images/a_icon_up0.gif'></td>
		<td align="center" class="a_content_td"><img src='<?=$url_admin?>images/a_icon_down1.gif' alt='카테고리 출력순서 아래로' onClick="javascript:rankChange('2','1')" style='cursor:hand'></td>
		<td align="center" class="a_content_td"><a href="<?=$url_admin?>product/product_category_write.php?cid=1"><img src='<?=$url_admin?>images/a_icon_pcateadd.gif' alt='하위카테고리 추가'></a></td>
		<td align="center" class="a_content_td"><a href="http://demo.bluecarpet.co.kr/kor/product/product.html?category=1" target="_blank"><img src='<?=$url_admin?>images/a_icon_brdview.gif' alt='카테고리 보기'></a></td>
		<td align="center" class="a_content_td"><a href="<?=$url_admin?>product/product_category_modify.php?cid=1"><img src='<?=$url_admin?>images/a_icon_modify.gif' alt='카테고리 수정'></a></td>
		<td align="center" class="a_content_td">
			<img src='<?=$url_admin?>images/a_icon_delete0.gif' alt='제품이 등록되거나 하위카테고리가 있는 카테고리는 삭제하실 수 없습니다. 삭제하시려면 먼저 하위카테고리 및 제품을 삭제해 주십시오.'>
		</td>
	</tr>
	<tr>
		<td colspan="100" height="1" bgcolor="#E5E5E5"></td>
	</tr>
	<tr>
		<td class="a_content_td" height="28">
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td class="a_content_td" width="5"></td>
					<td width="35" class="a_content_td"><img src=<?=$url_admin?>images/a_icon_lev1.gif></td>
					<td class="a_content_td" width="4"></td>
					<td class="a_content_td" style="padding-top:1px">제품카테고리1</td>
				</tr>
			</table>
		</td>
		<td align="center" class="a_content_td"><font color="#FF6600"><b>2</b></font></td>
		<td align="center" class="a_content_td" style="padding-top:1px"><a href="<?=$url_admin?>product/product_write.php?cnum=1"><img src='<?=$url_admin?>images/a_icon_prowrite.gif' alt='제품등록'></a></td>
		<td align="center" class="a_content_td"><img src='<?=$url_admin?>images/a_icon_up1.gif' alt='카테고리 출력순서 위로' onClick="javascript:rankChange('2','1')" style='cursor:hand'></td>
		<td align="center" class="a_content_td"><img src='<?=$url_admin?>images/a_icon_down0.gif'></td>
		<td align="center" class="a_content_td"><a href="<?=$url_admin?>product/product_category_write.php?cid=2"><img src='<?=$url_admin?>images/a_icon_pcateadd.gif' alt='하위카테고리 추가'></a></td>
		<td align="center" class="a_content_td"><a href="http://demo.bluecarpet.co.kr/kor/product/product.html?category=2" target="_blank"><img src='<?=$url_admin?>images/a_icon_brdview.gif' alt='카테고리 보기'></a></td>
		<td align="center" class="a_content_td"><a href="<?=$url_admin?>product/product_category_modify.php?cid=2"><img src='<?=$url_admin?>images/a_icon_modify.gif' alt='카테고리 수정'></a></td>
		<td align="center" class="a_content_td">
			<img src='<?=$url_admin?>images/a_icon_delete0.gif' alt='제품이 등록되거나 하위카테고리가 있는 카테고리는 삭제하실 수 없습니다. 삭제하시려면 먼저 하위카테고리 및 제품을 삭제해 주십시오.'>
		</td>
	</tr>
	<tr>
		<td colspan="100" height="1" bgcolor="#E5E5E5"></td>
	</tr>
	<tr>
		<td class="a_content_td" height="28">
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td class="a_content_td" width="13"></td>
					<td width="35" class="a_content_td"><img src=<?=$url_admin?>images/a_icon_lev2.gif></td>
					<td class="a_content_td" width="4"></td>
					<td class="a_content_td" style="padding-top:1px">카테고리1_2</td>
				</tr>
			</table>
		</td>
		<td align="center" class="a_content_td"><font color="#FF6600"><b>2.01</b></font></td>
		<td align="center" class="a_content_td" style="padding-top:1px"><a href="<?=$url_admin?>product/product_write.php?cnum=4"><img src='<?=$url_admin?>images/a_icon_prowrite.gif' alt='제품등록'></a></td>
		<td align="center" class="a_content_td"><img src='<?=$url_admin?>images/a_icon_up0.gif'></td>
		<td align="center" class="a_content_td"><img src='<?=$url_admin?>images/a_icon_down2.gif' alt='카테고리 출력순서 아래로' onClick="javascript:rankChange('2.02','2.01')" style='cursor:hand'></td>
		<td align="center" class="a_content_td"><a href="<?=$url_admin?>product/product_category_write.php?cid=2.01"><img src='<?=$url_admin?>images/a_icon_pcateadd.gif' alt='하위카테고리 추가'></a></td>
		<td align="center" class="a_content_td"><a href="http://demo.bluecarpet.co.kr/kor/product/product.html?category=2.01" target="_blank"><img src='<?=$url_admin?>images/a_icon_brdview.gif' alt='카테고리 보기'></a></td>
		<td align="center" class="a_content_td"><a href="<?=$url_admin?>product/product_category_modify.php?cid=2.01"><img src='<?=$url_admin?>images/a_icon_modify.gif' alt='카테고리 수정'></a></td>
		<td align="center" class="a_content_td">
			<img src='<?=$url_admin?>images/a_icon_delete0.gif' alt='제품이 등록되거나 하위카테고리가 있는 카테고리는 삭제하실 수 없습니다. 삭제하시려면 먼저 하위카테고리 및 제품을 삭제해 주십시오.'>
		</td>
	</tr>
	<tr>
		<td colspan="100" height="1" bgcolor="#E5E5E5"></td>
	</tr>
	<tr>
		<td class="a_content_td" height="28">
			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td class="a_content_td" width="13"></td>
					<td width="35" class="a_content_td"><img src=<?=$url_admin?>images/a_icon_lev2.gif></td>
					<td class="a_content_td" width="4"></td>
					<td class="a_content_td" style="padding-top:1px">카테고리1_1</td>
				</tr>
			</table>
		</td>
		<td align="center" class="a_content_td"><font color="#FF6600"><b>2.02</b></font></td>
		<td align="center" class="a_content_td" style="padding-top:1px"><a href="<?=$url_admin?>product/product_write.php?cnum=3"><img src='<?=$url_admin?>images/a_icon_prowrite.gif' alt='제품등록'></a></td>
		<td align="center" class="a_content_td"><img src='<?=$url_admin?>images/a_icon_up2.gif' alt='카테고리 출력순서 위로' onClick="javascript:rankChange('2.02','2.01')" style='cursor:hand'></td>
		<td align="center" class="a_content_td"><img src='<?=$url_admin?>images/a_icon_down0.gif'></td>
		<td align="center" class="a_content_td"><a href="<?=$url_admin?>product/product_category_write.php?cid=2.02"><img src='<?=$url_admin?>images/a_icon_pcateadd.gif' alt='하위카테고리 추가'></a></td>
		<td align="center" class="a_content_td"><a href="http://demo.bluecarpet.co.kr/kor/product/product.html?category=2.02" target="_blank"><img src='<?=$url_admin?>images/a_icon_brdview.gif' alt='카테고리 보기'></a></td>
		<td align="center" class="a_content_td"><a href="<?=$url_admin?>product/product_category_modify.php?cid=2.02"><img src='<?=$url_admin?>images/a_icon_modify.gif' alt='카테고리 수정'></a></td>
		<td align="center" class="a_content_td">
			<img src='<?=$url_admin?>images/a_icon_delete0.gif' alt='제품이 등록되거나 하위카테고리가 있는 카테고리는 삭제하실 수 없습니다. 삭제하시려면 먼저 하위카테고리 및 제품을 삭제해 주십시오.'>
		</td>
	</tr>
	<tr>
		<td colspan="100" height="1" bgcolor="#E5E5E5"></td>
	</tr> -->
</table>

<? include $path_admin."inc/footer.php"; ?>


<? $dbcon -> dbcon_close();?>

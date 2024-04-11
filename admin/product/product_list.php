<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	//$dbcon -> setDebug(1);
?>
<?
	// 페이지 설정
	$page				= REQSTR($page, 1);
	$num_per_page	= REQSTR($num_per_page, 10);
	$page_per_block	= REQSTR($page_per_block, 10);
	$first					= $num_per_page*($page-1);
	$last					= $num_per_page*$page;

	// 검색설정
	$query_where		= "";

	#### 검색 설정 Start
	$search_pc_num				= REQSTR($search_pc_num, "");
	$parameter = "search_pc_num=".$search_pc_num;

	if ( getLen($search_pc_num) ) {
		//$query_where .= " and pc_num = '".$search_pc_num."' ";
		$query_where .= " and pc_num like '".$search_pc_num."%' ";

		$TmRS = getPreCategoryName($search_pc_num, 2);
		$CategoryName = " ( ";
		while( $TmRow = $dbcon -> fetch_array($TmRS) ) {
			if ( "".$TmRow[pc_num] != "".$search_pc_num ) {
				//$CategoryName .= "<a href=\"../sub/product.php?pc_num=".$TmRow[pc_num]."\">".$TmRow[pc_name]."</a> > ";
				$CategoryName .= $TmRow[pc_name]." > ";
			}
			else {
				//$pc_num_location .= "<b>".$TmRow[pc_name]."</b>";
				$CategoryName .= "<b>".$TmRow[pc_name]."</b>";
			}
			unset($TmRow);
		}
		$CategoryName .= " ) ";
		unset($TmRS);
	}

//	echo "search_u_level : ".$search_u_level."<BR>";
//	echo "search_u_gubun : ".$search_u_gubun."<BR>";
//	echo "search_u_state : ".$search_u_state."<BR>";
//	echo "search_u_sex : ".$search_u_sex."<BR>";
//	echo "search_orderby : ".$search_orderby."<BR>";
//	echo "search_sort : ".$search_sort."<BR>";
//	echo $query_where."<BR>";


	#### 검색 설정 End

	// 쿼리설정
	$field				= "
							*
							, ( select pc_name from tbl_product_category where pc_num = A.pc_num ) as pc_name
							, ( select count(*) from tbl_product where pr_sort > A.pr_sort ) as sort_up
							, ( select count(*) from tbl_product where pr_sort < A.pr_sort ) as sort_down
						";
	$table			= "
							tbl_product A
						";
	$where			= $query_where;
	//$orderby			= $search_orderby." ".$search_sort;
	$orderby			= " pr_sort desc ";
//	echo "orderby : ".$orderby."<BR>";
	//$orderby			= " left(pc_num,2), left(pc_num,4), left(pc_num,6), left(pc_num,8), left(pc_num,10), pr_sort desc";
	//$limit				= $first.", ".$num_per_page;
	$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);
	$total_record	= $ArrRS[0];
	$result			= $ArrRS[1];
	unset($ArrRS);

	// 페이지 & 리스트 설정
	$total_page		= ceil($total_record/$num_per_page);
	$no				= $total_record - $first;
?>
<?
	$tm = "product";
	$lm = "";
	include $path_admin."inc/header.php";
?>
<script language='JavaScript'>
<!--
function rankChange(cprank,tprank,cpnum,tpnum)
{
	var f = document.frmChangRank;
	f.f_cprank.value = cprank;
	f.f_tprank.value = tprank;
	f.f_cpnum.value = cpnum;
	f.f_tpnum.value = tpnum;
	f.submit();
}


// 체크한 상품 삭제하기
function checkDel(id) {
	var num = document.frmCheckDel.elements.length;
	for(var i = 0; i < num; i++) {
		if(document.frmCheckDel.elements[i].checked == true) {
			if(confirm('정말 삭제하시겠습니까?')) {
				document.frmCheckDel.submit();
			}
			break;
		}
	}
	if(i == num) {
		alert('하나 이상 제품을 선택하세요');
		return;
	}
}
// 전체 상품 선택하기
function checkInverse() {
	for(i=0;i<document.frmCheckDel.elements.length;i++) {
		if(document.frmCheckDel.elements[i].checked == true) {
			document.frmCheckDel.elements[i].checked = false;
		}
		else {
			document.frmCheckDel.elements[i].checked = true;
		}
	}
}

function sortGo( act, pr_idx, pr_sort) {
	TempFrame.location.href = "<?=$url_admin?>product/product_sort.php?act="+act+"&pr_idx="+pr_idx+"&pr_sort="+pr_sort;
}

//-->
</script>
<iframe id="TempFrame" src="" frameborder=0 width=0 height=0></iframe>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top"><span class="a_st">제품관리</span><?=$CategoryName?><!-- 전체 제품 관리 --></td>
		<td width="150" align="right">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td align="right">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td bgcolor="#339933" width="150" height="22" align="center" class="a_tab_txt" valign="top" style="padding-top:3px"><a href='<?=$url_admin?>product/product_write.php?pc_num=<?=$search_pc_num?>'>제품 등록</a> </td>
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
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 제품 리스트</td>
		<td align="right">
			※ <b>총 <font color="#FF0000"><?=$total_record?></font> 개의 제품이 있습니다.</b>
		</td>
	</tr>
	<tr>
		<td height="2" colspan="2"></td>
	</tr>
</table>
<form name="frmCheckDel" method="get" action="product_del_ok.php">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td colspan="9" height="2" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="54" height="28"><a href="javascript:checkInverse()">전체선택</a></td>
		<!-- <td align="center" bgcolor="#F3F3F3" class="a_thead" width="54">제품번호</td> -->
		<td align="center" bgcolor="#F3F3F3" class="a_thead" style="padding-left:5px">제품명</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" style="padding-left:5px">카테고리명</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="60">제품순위</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="45">순서△</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="45">순서▽</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="35">보기</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="43">수정</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="48">삭제</td>
	</tr>
<? if ($total_record == 0) { ?>
	<tr align="center" class="a_content_td" height="28">
		<td colspan="100"><?=$GLOBALS[msg_list_notdata]?></td>
	</tr>
	<tr>
		<td colspan="9" height="1" bgcolor="#E5E5E5"></td>
	</tr>
<?
	} else {
		while ($rows = $dbcon -> fetch_array($result)) {
			extract($rows);
			unset($rows);

			$print_u_level = $Arr_u_level[$u_level];

			$print_u_gubun = $Arr_u_gubun[$u_gubun];

			$print_u_state = $Arr_u_state[$u_state];

			$print_email_icon = "";
			if ( $u_email_receipt == "1" ) {
				$print_email_icon = "<img src='".$url_admin."images/a_icon_y.gif' alt='메일수신 동의함'>";
			}
			else {
				$print_email_icon = "<img src='".$url_admin."images/a_icon_n.gif' alt='메일수신 동의안함'>";
			}

			$print_u_sex		= $Arr_u_sex[$u_sex];

			$print_sms_icon = "";
			if ( $u_sms_receipt == "1" ) {
				$print_sms_icon = "<img src='".$url_admin."images/a_icon_y.gif' alt='SMS수신 동의함'>";
			}
			else {
				$print_sms_icon = "<img src='".$url_admin."images/a_icon_n.gif' alt='SMS수신 동의안함'>";
			}

			if ( $u_jumin1 ) {
				$print_u_jumin		= $u_jumin1."-*******";
			}
			else {
				$print_u_jumin		= "미등록";
			}

//			echo $sort_up ."<BR>";
//			echo $sort_down ."<BR>";

			$print_sort_up = "";
			$print_sort_down = "";
			if ( $sort_up ) $print_sort_up = "<a href=\"javascript:sortGo('up', '".$pr_idx."', '".$pr_sort."')\"><img src='".$url_admin."images/a_icon_up1.gif'></a>";//".(getLen($pc_num)/2)."
			else $print_sort_up = "<img src='".$url_admin."images/a_icon_up0.gif'>";
			if ( $sort_down ) $print_sort_down = "<a href=\"javascript:sortGo('down', '".$pr_idx."', '".$pr_sort."')\"><img src='".$url_admin."images/a_icon_down1.gif'></a>";//".(getLen($pc_num)/2)."
			else $print_sort_down = "<img src='".$url_admin."images/a_icon_down0.gif'>";
?>

	<tr>
		<td align="center" class="a_content_td" height="28"><input type="checkbox" name="pr_idx[]" value="<?=$pr_idx?>"></td>
		<!-- <td align="center" class="a_content_td"><?=$pr_idx?></td> -->
		<td class="a_content_td" style="padding-left:5px">
			<b><?=$pr_name?></b>
		</td>
		<td class="a_content_td" style="padding-left:5px">
			<b><font color="#FF6600"><?=$pc_name?></font></b><?//=$pc_num_location?>
		</td>
		<td align="center" class="a_content_td"><?=$pr_sort//."|".$sort_up."|".$sort_down?></td>
		<td align="center" class="a_content_td"><?=$print_sort_up?></td>
		<td align="center" class="a_content_td"><?=$print_sort_down?></td>
		<td align="center" class="a_content_td"><a href="<?=$url_product?>product_view.php?pr_idx=<?=$pr_idx?>" target="_blank"><img src='<?=$url_admin?>images/a_icon_brdview.gif' alt='제품 보기'></a></td>
		<td align="center" class="a_content_td"><a href="<?=$url_admin?>product/product_write.php?pr_idx=<?=$pr_idx?>&<?=$parameter?>"><img src='<?=$url_admin?>images/a_icon_modify.gif' alt='제품 수정'></a></td>
		<td align="center" class="a_content_td"><a href="<?=$url_admin?>product/product_del_ok.php?pr_idx=<?=$pr_idx?>&<?=$parameter?>" onClick="return confirm('정말 삭제하시겠습니까?')"><img src='<?=$url_admin?>images/a_icon_delete.gif' alt='제품 삭제'></a></td>
	</tr>
	<tr>
		<td colspan="9" height="1" bgcolor="#E5E5E5"></td>
	</tr>

<?
			$no = $no - 1;
		}
	}
	unset($result);

?>

</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="4"></td>
	</tr>
	<tr>
		<td width="90" valign="top" style="padding-top:6px"><img src="<?=$url_admin?>images/a_btn_prodel_check.gif" onClick="checkDel('del')" style="cursor:hand"></td>
		<td align="right">
			<? if ( $ss_u_level >= $auth_admin ) { ?>
			<!-- <a href="<?=$url_admin?>product/category_sort_set.php?search_pc_num=<?=$search_pc_num?>">순서 재정렬</a> -->
			<? } ?>
		</td>
	</tr>
</table>
</form>
<? include $path_admin."inc/footer.php"; ?>

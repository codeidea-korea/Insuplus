<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	//$dbcon -> setDebug(1);
?>
<?
	// 페이지 설정
	$page				= REQSTR($page, 1);
	$num_per_page	= REQSTR($num_per_page, 20);
	$page_per_block	= REQSTR($page_per_block, 10);
	$first					= $num_per_page*($page-1);
	$last					= $num_per_page*$page;

	// 검색설정
	$query_where		= "";
//search_YearS
//search_MonthS
//search_DayS
//search_YearE
//search_MonthE
//search_DayE
//search_buyer_name
//search_ordernum
//search_u_id
//search_accout_name
//search_a_state

	if ( $list_type == "1" ) {
		$query_where .= " and a_state < '8' ";
	}
	elseif ( $list_type == "2" ) {
		$query_where .= " and a_state < '7' ";
	}


	if ( getLen($search_YearS) && getLen($search_MonthS) && getLen($search_DayS) && getLen($search_YearE) && getLen($search_MonthE) && getLen($search_DayE)  ) {
		$query_where .= " and a_regdate between timestamp('".$search_YearS."-".$search_MonthS."-".$search_DayS." 00:00:00') and timestamp('".$search_YearE."-".$search_MonthE."-".$search_DayE." 23:59:59') ";
	}

	if ( !( getLen($search_YearS) && getLen($search_MonthS) && getLen($search_DayS) && getLen($search_YearE) && getLen($search_MonthE) && getLen($search_DayE)  ) ) {
		$search_YearS = date("Y");
		$search_MonthS = date("m");
		$search_DayS = date("d");
		$search_YearE = date("Y");
		$search_MonthE = date("m");
		$search_DayE = date("d");
	}

	if ( getLen($search_buyer_name) ) {
		$query_where .= " and buyer_name like '%".$search_buyer_name."%' ";
	}
	if ( getLen($search_ordernum) ) {
		$query_where .= " and ordernum like '%".$search_ordernum."%' ";
	}
	if ( getLen($search_u_id) ) {
		$query_where .= " and u_id like '%".$search_u_id."%' ";
	}
	if ( getLen($search_buyer_email) ) {
		$query_where .= " and buyer_email like '%".$search_buyer_email."%' ";
	}

	if ( getLen($search_receive_name) ) {
		$query_where .= " and receive_name like '%".$search_receive_name."%' ";
	}


	if ( getLen($search_accout_name) ) {
		$query_where .= " and accout_name like '%".$search_accout_name."%' ";
	}
	if ( getLen($search_a_state) ) {
		$query_where .= " and a_state = '".$search_a_state."' ";
	}

	//echo "query_where : ".$query_where."<BR>";


	#### 검색 설정 Start
	$parameter = "search_YearS=".$search_YearS."&search_MonthS=".$search_MonthS."&search_DayS=".$search_DayS."&search_YearE=".$search_YearE."&search_MonthE=".$search_MonthE."&search_DayE=".$search_DayE."&search_buyer_name=".$search_buyer_name."&search_ordernum=".$search_ordernum."&search_u_id=".$search_u_id."&search_buyer_email=".$search_buyer_email."&search_receive_name=".$search_receive_name."&search_accout_name=".$search_accout_name."&search_a_state=".$search_a_state;



	#### 검색 설정 End

	// 쿼리설정
	$field				= "
							*
						";
	$table			= "
							tbl_account A
						";
	$where			= $query_where;
	//$orderby			= $search_orderby." ".$search_sort;
	$orderby			= " a_idx desc ";
	//echo "orderby : ".$orderby."<BR>";
	//$orderby			= " left(pc_num,2), left(pc_num,4), left(pc_num,6), left(pc_num,8), left(pc_num,10), pr_sort desc";
	$limit				= $first.", ".$num_per_page;
	$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);
	$total_record	= $ArrRS[0];
	$result			= $ArrRS[1];
	unset($ArrRS);

	// 페이지 & 리스트 설정
	$total_page		= ceil($total_record/$num_per_page);
	$no				= $total_record - $first;
?>
<?
	$tm = "order";
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
	if ( id == "del" ) {
		var num = document.frmCheckDel.elements.length;
		for(var i = 0; i < num; i++) {
			if(document.frmCheckDel.elements[i].checked == true) {
				if(confirm('정말 삭제하시겠습니까?')) {
					document.frmCheckDel.action = "order_del_ok.php";
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
	if ( id == "modify" ) {
		var num = document.frmCheckDel.elements.length;
		for(var i = 0; i < num; i++) {
			if(document.frmCheckDel.elements[i].checked == true) {
				document.frmCheckDel.action = "order_state_ok.php";
				document.frmCheckDel.submit();
				break;
			}
		}
		if(i == num) {
			alert('하나 이상 제품을 선택하세요');
			return;
		}
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


//-->
</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top"><span class="a_st">주문관리</span></td>
	</tr>
	<tr>
		<td colspan="3" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="3" height="19"></td>
	</tr>
</table>

<form name="frmCheckDel" method="get" action="">


<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 주문 리스트</td>
		<td align="right">
			※ <b>총 <font color="#FF0000"><?=$total_record?></font> 개의 주문내역이 있습니다.</b>
		</td>
	</tr>
	<tr>
		<td height="2" colspan="2"></td>
	</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td colspan="9" height="2" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="54" height="28"><a href="javascript:checkInverse()">전체선택</a></td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="54">No</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead">주문번호</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="80">주문자</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="60">결제방법</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="100">주문금액</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="120">주문상태</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="80">주문일시</td>
		<!-- <td align="center" bgcolor="#F3F3F3" class="a_thead" width="43">수정</td> -->
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
			$print_account_type = $Arr_account_type[$account_type];


?>

	<tr>
		<td align="center" class="a_content_td" height="28"><input type="checkbox" name="a_idx[]" value="<?=$a_idx?>"></td>
		<td align="center" class="a_content_td"><a href="<?=$url_admin?>product/order_write.php?a_idx=<?=$a_idx?>&<?=$parameter?>"><?=$no?></a></td>
		<td class="a_content_td" style="padding-left:5px">
			<a href="<?=$url_admin?>product/order_write.php?a_idx=<?=$a_idx?>&<?=$parameter?>"><b><?=$ordernum?></b></a>
		</td>
		<td class="a_content_td" align="center">
			<b><font color="#FF6600"><?=$buyer_name?></font></b>
		</td>
		<td align="center" class="a_content_td"><?=$print_account_type?></td>
		<td align="center" class="a_content_td" style="text-align:right; padding-right:5px;"><?=make_price_format($buy_price,1)?> 원</td>
		<td align="center" class="a_content_td">
			<select name="a_state[]">
				<?
					foreach( $Arr_a_state as $key => $val) {
						if ( $key ) {
				?>
				<option value="<?=$key?>" <? if ($key == $a_state) {echo "selected";} ?>><?=$val?></option>
				<?
						}
					}
				?>
			</select>
		</td>
		<td align="center" class="a_content_td"><?=date("m-d[H:i]", strtotime($a_regdate) )?></td>
		<!-- <td align="center" class="a_content_td"><a href="<?=$url_admin?>product/order_write.php?a_idx=<?=$a_idx?>&<?=$parameter?>"><img src='<?=$url_admin?>images/a_icon_modify.gif' alt='제품 수정'></a></td> -->
		<td align="center" class="a_content_td"><a href="<?=$url_admin?>product/order_del_ok.php?a_idx=<?=$a_idx?>&<?=$parameter?>" onClick="return confirm('정말 삭제하시겠습니까?')"><img src='<?=$url_admin?>images/a_icon_delete.gif' alt='제품 삭제'></a></td>
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
		<? list_page_sindoh($page, $total_page, $page_per_block); ?>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="4"></td>
	</tr>
	<tr>
		<td valign="top" style="padding-top:6px">
			<input type="button" value="선택상품 삭제" onClick="checkDel('del')">
			<input type="button" value="선택상품 상태변경" onClick="checkDel('modify')">
		</td>
		<td align="right">
			<? if ( $ss_u_level >= $auth_admin ) { ?>
			<!-- <a href="<?=$url_admin?>product/category_sort_set.php?search_pc_num=<?=$search_pc_num?>">순서 재정렬</a> -->
			<? } ?>
		</td>
	</tr>
</table>


</form>

<BR>




<form name="OrderSearchForm" action="" method="get">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 주문내역 상세검색</td>
	</tr>
	<tr>
		<td height="2" colspan="2"></td>
	</tr>
</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td colspan="9" height="2" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="150" height="28">주문기간</td>
		<td align="left" class="a_content_td" style="padding-left:10px;" colspan="3">
			<select name="search_YearS">
				<? for ( $i = date("Y") ; $i > date("Y")-10; $i-- ) {
					if ( $i < 10 ) $print_i = "0".$i;
					else $print_i = $i;
					?>
				<option value="<?=$print_i?>" <? if ($i == $search_YearS) {echo "selected";} ?>><?=$i?></option>
				<? } ?>
			</select> 년
			<select name="search_MonthS">
				<? for ( $i = 1 ; $i <= 12; $i++ ) {
					if ( $i < 10 ) $print_i = "0".$i;
					else $print_i = $i;
					?>
				<option value="<?=$i?>" <? if ($i == $search_MonthS) {echo "selected";} ?>><?=$i?></option>
				<? } ?>
			</select> 월
			<select name="search_DayS">
				<? for ( $i = 1 ; $i <= 31; $i++ ) {
					if ( $i < 10 ) $print_i = "0".$i;
					else $print_i = $i;
					?>
				<option value="<?=$i?>" <? if ($i == $search_DayS) {echo "selected";} ?>><?=$i?></option>
				<? } ?>
			</select> 일
			~
			<select name="search_YearE">
				<? for ( $i = date("Y") ; $i > date("Y")-10; $i-- ) {
					if ( $i < 10 ) $print_i = "0".$i;
					else $print_i = $i;
					?>
				<option value="<?=$i?>" <? if ($i == $search_YearE) {echo "selected";} ?>><?=$i?></option>
				<? } ?>
			</select> 년
			<select name="search_MonthE">
				<? for ( $i = 1 ; $i <= 12; $i++ ) {
					if ( $i < 10 ) $print_i = "0".$i;
					else $print_i = $i;
					?>
				<option value="<?=$i?>" <? if ($i == $search_MonthE) {echo "selected";} ?>><?=$i?></option>
				<? } ?>
			</select> 월
			<select name="search_DayE">
				<? for ( $i = 1 ; $i <= 31; $i++ ) {
					if ( $i < 10 ) $print_i = "0".$i;
					else $print_i = $i;
					?>
				<option value="<?=$i?>" <? if ($i == $search_DayE) {echo "selected";} ?>><?=$i?></option>
				<? } ?>
			</select> 일

		</td>
	</tr>
	<tr>
		<td colspan="9" height="1" bgcolor="#E5E5E5"></td>
	</tr>
	<tr>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="150" height="28">주문자</td>
		<td align="left" class="a_content_td" style="padding-left:10px;">
			<input type="text" name="search_buyer_name" value="<?=$search_buyer_name?>" class="a_input">
		</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="150" height="28">주문번호</td>
		<td align="left" class="a_content_td" style="padding-left:10px;">
			<input type="text" name="search_ordernum" value="<?=$search_ordernum?>" class="a_input">
		</td>
	</tr>

	<tr>
		<td colspan="9" height="1" bgcolor="#E5E5E5"></td>
	</tr>
	<tr>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="150" height="28">아이디</td>
		<td align="left" class="a_content_td" style="padding-left:10px;">
			<input type="text" name="search_u_id" value="<?=$search_u_id?>" class="a_input">
		</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="150" height="28">이메일</td>
		<td align="left" class="a_content_td" style="padding-left:10px;">
			<input type="text" name="search_buyer_email" value="<?=$search_buyer_email?>" class="a_input">
		</td>
	</tr>
	<tr>
		<td colspan="9" height="1" bgcolor="#E5E5E5"></td>
	</tr>
	<tr>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="150" height="28">수취인명</td>
		<td align="left" class="a_content_td" style="padding-left:10px;">
			<input type="text" name="search_receive_name" value="<?=$search_receive_name?>" class="a_input">
		</td>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="150" height="28">입금인명</td>
		<td align="left" class="a_content_td" style="padding-left:10px;">
			<input type="text" name="search_accout_name" value="<?=$search_accout_name?>" class="a_input">
		</td>
	</tr>
	<tr>
		<td colspan="9" height="1" bgcolor="#E5E5E5"></td>
	</tr>
	<tr>
		<td align="center" bgcolor="#F3F3F3" class="a_thead" width="150" height="28">주문상태</td>
		<td align="left" class="a_content_td" style="padding-left:10px;" colspan="3">
			<select name="search_a_state">
				<option value="">선택하세요.</option>
				<?
					foreach( $Arr_a_state as $key => $val) {
						if ( $key ) {
				?>
				<option value="<?=$key?>" <? if ($key == $search_a_state) {echo "selected";} ?>><?=$val?></option>
				<?
						}
					}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="9" height="2" bgcolor="#E5E5E5"></td>
	</tr>

	<tr>
		<td align="center" height="28" colspan="4">
			<input type="submit" value=" 검 색 하 기 ">
		</td>
	</tr>

</table>
</form>
<? include $path_admin."inc/footer.php"; ?>

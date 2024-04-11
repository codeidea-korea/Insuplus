<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$idx = REQSTR($idx, "");
	isnull($idx);

	$query_where = " and idx = '".$idx."' ";

	$field = " * ";
	$table = "tbl_productregist";
	$where = " and idx = '".$idx."' ";
	$orderby = " idx desc ";
	$limit = "0, 1";

	$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);

	$total_record = $ArrListRs[0];
	if ( $total_record == 0 ) {
		$dbcon -> dbcon_close();
		alert_back("게시판 정보가 누락되었습니다.");
		exit;
	}

	$ListRs = $dbcon -> fetch_array($ArrListRs[1]);
	extract($ListRs);
	unset($ListRs);
	unset($ArrListRs);


	$PrintRegDate = date('Y-m-d ', strtotime($regdate) );

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



<form name="ModifyForm" action="" method="post" onsubmit="return next_go()">
<input type="hidden" name="idx" value="<?=$idx?>">


<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 마이페이지 > 제품등록관리</td>
	</tr>
	<tr>
		<td height="4" colspan="2"></td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="2" class="a_line_2px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">아이디</td>
		<td class="a_content">
			<input type="hidden" name="u_id" value="<?=$u_id?>" class="a_input"><?=$u_id?>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품구분</td>
		<td class="a_content">
<script>
	function getCateSub(pc_num, ObjForm, ObjSelectSub, pc_num_sub) {
		FrameCategory.location.href = "<?=$url_product?>multi-select_sub.php?pc_num="+pc_num+"&ObjForm="+ObjForm+"&ObjSelectSub="+ObjSelectSub+"&pc_num_sub="+pc_num_sub;
	}

</script>
<?
	$TmpSQL = "
		select
			pc_num, pc_name
		from
			tbl_product_category
		where
			length(pc_num) = 2
			and pc_use ='Y'
		order by
			pc_sort asc
	";
	$TmpRs = $dbcon -> query($TmpSQL) ;
?>
			<select name="SelCate" id= "SelCate" onchange="getCateSub(this.value, 'ModifyForm', 'pc_num', '');" style="height:20px;">
				<option>선택하세요.</option>
				<?
					while( $TmpRow = $dbcon -> fetch_array( $TmpRs )) {
				?>
				<option value="<?=$TmpRow["pc_num"]?>"<? if (substr($pc_num, 0, 2) == $TmpRow["pc_num"] ) echo "selected"; ?>> <?=$TmpRow["pc_name"]?></option>
				<?
					}
				?>
			</select>
			<select name="pc_num" style="height:20px;">
				<option>선택하세요.</option>
			</select>
			<iframe width=0 height=0 name="FrameCategory"></iframe>
			<?
			//	echo "pc_num : ".substr($ext10, getLen($ext10) - 2);
				if ($pc_num) {
					?><script language="JavaScript" Event="onLoad" For="window">getCateSub('<?=substr($pc_num, 0, 2)?>', 'ModifyForm', 'pc_num', '<?=$pc_num?>');</script><?
				}
			?>

		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">모델명</td>
		<td class="a_content">
			<input type="text" name="pr_name" value="<?=$pr_name?>" class="a_input">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">S/N</td>
		<td class="a_content">
			<input type="text" name="pr_code" value="<?=$pr_code?>" class="a_input">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">등록일</td>
		<td class="a_content">
			<?=$PrintRegDate?>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>
</table>
<!-- #### 기본설정 End #### -->



<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="80" align="center">
			<input type="image" src="<?=$url_admin?>images/a_btn_submit.gif" hspace="4">
			<a href="bbs_product_list.php"><img src="<?=$url_admin?>images/a_btn_cancle.gif" width="76" height="28"></a>
		</td>
	</tr>
</table>

</form>
<script>
	function page_go(page) {
		location.href = "?page="+page+"&<?=$parameter?>";
	}

	function modify_go(idx) {
		location.href = "bbs_modify.php?idx="+idx;
	}

	function delete_go(idx) {
		if (confirm("정말로 삭제하시겠습니까?")) {
			location.href = "bbs_del_ok.php?idx="+idx;
		}
	}

	function next_go() {
		ff = document.ModifyForm;

		ff.action = "bbs_product_modify_ok.php";
		//ff.submit();

	}
</script>
<!-- ### 페이지 끝 ###  -->
<?
	$dbcon -> dbcon_close();
?>
<? include $path_admin."inc/footer.php"; ?>

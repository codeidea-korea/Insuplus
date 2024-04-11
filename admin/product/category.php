<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
?>
<?
	$form_name = REQSTR($_GET[form_name],"");
?>
<?
	// 최상위 카테고리 가져오기
	$CateSQL = "
		select
			pc_num, pc_name
			, ( select count(pc_num) from tbl_product_category where length(pc_num) = length(A.pc_num) + 2 and SUBSTRING(pc_num, 1, length(A.pc_num) ) = A.pc_num ) as next_pc_num_cnt
		from
			tbl_product_category A
		where
			length(pc_num) = 2
		order by
			pc_sort asc
	";
	$CateRs = $dbcon -> query($CateSQL) ;
?>

<script>
	var old_menu = '';

	function menuclick(pc_num) {

		var menu_img = document.all("obj_"+pc_num);
		var submenu = document.all("obj_"+pc_num+"_sub");
		var space = (pc_num.length/2-1);

//		alert(pc_num);
//		alert(submenu);
//		alert(submenu.outerHTML);
//		alert(space);

		if( submenu.style.display == 'block' ) {
			submenu.style.display = 'none';
			menu_img.src = '<?=$url_product?>images/cate_plus.gif';
		}
		else{
			submenu.style.display = 'block';
			menu_img.src = '<?=$url_product?>images/cate_minus.gif';
			var str = submenu.outerHTML;
			if(str.length < 100) {
//				var space_str = '';
//				for(i = 0;i < space;i++) {
//					space_str += '&nbsp;&nbsp;';
//				}
//				str = space_str + "<img src='<?=$url_product?>images/loading.gif' border='0'>";
//				submenu.innerHTML = str;
				Get_Category(pc_num);
			}
		}
	}

	function menuHandler(pc_num, pc_name) {
//		var ff = eval("opener.document.<?=$form_name?>");
//		alert(pc_num);
//		alert(pc_name);
//		ff.pc_num.value = pc_num;
//		ff.pc_name.value = pc_name;
//		opener.getCategoryOtion();
//		self.close();
		location.href = "<?=$url_admin?>product/product_list.php?search_pc_num="+pc_num;
	}

	function Get_Category(pc_num) {
		GetCategory.src = '<?=$url_admin?>product/category_proc.php?pc_num=' + pc_num;
	}
</script>
<script language='javascript' id='GetCategory'></script>
<table style="width:240px" cellpadding="0" cellspacing="0" border="0">
<tr>
<td valign="top">
<img src='<?=$url_product?>images/folder_bullet.gif' border=0> <a href="javascript:menuHandler('','')">제품관리</a><BR>

<? while( $row = $dbcon -> fetch_array( $CateRs )) { ?>

	<? if ( $row[next_pc_num_cnt] > 0 ) { ?>
		<a href="javascript:menuclick('<?=$row[pc_num]?>');"><img src='<?=$url_product?>images/cate_plus.gif' border='0' style='cursor:hand;' id='obj_<?=$row[pc_num]?>'></a><img src='<?=$url_product?>images/folder_bullet.gif' border='0'>
	<? } else { ?>
		<img src='<?=$url_product?>images/cate_default.gif'><img src='<?=$url_product?>images/folder_bullet.gif' border='0'>
	<? } ?>

		<a href="javascript:menuHandler('<?=$row[pc_num]?>','<?=$row[pc_name]?>');"><?=$row[pc_name]?></a><br>
		<span id="obj_<?=$row[pc_num]?>_sub" style="display:none;"></span>

<? } ?>
</td>
</tr>
</table>
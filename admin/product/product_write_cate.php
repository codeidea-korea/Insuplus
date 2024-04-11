<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	// 관리자 체크
	admin_chk($auth_admin, $url_admin_login_out);
?>
<?
	// 현재 카테고리의 최대 순위값을 가져옴
	$prank = get_one_data("SELECT max(prank) FROM tbl_product where cnum='$_POST[cateNum]'", $dbcon);
	if ($prank) {
		$prank ++;
	} else {
		$prank = '1';
	}

	$cate_opt = get_one_data("select coption from tbl_product_category where cnum = '$_POST[cateNum]'", $dbcon);
	$cntopt = $_POST['cntopt'];

	if($cate_opt) {
		$opt_tmp = explode('|',$cate_opt);
		if ($opt_tmp) {
			$opt_tmp_len = '';
			$opt_tmp_len = sizeof($opt_tmp);
		}
?>
		<script language='JavaScript'>
		<!--
			var fw = parent.document.frmWrite;
			var f = parent.document.frmCnum;

			var optLen = '<?=$opt_tmp_len?>';
			var p_optLen = '<?=$cntopt?>';

			if (optLen > p_optLen) {
				var addCnt = optLen - p_optLen;
				for (i=0;i<addCnt;i++) {
					parent.addOption();
				}
			} else {
				var addCnt = p_optLen - optLen;
				for (i=0;i<addCnt;i++) {
					parent.removeOption();
				}
			}
			var obj = parent.document.getElementsByName('f_coption[]');
			var objval = '<?=$cate_opt?>';
			objval = objval.split('|');
			for (i=0;i<optLen;i++) {
				obj[i].value = objval[i];
			}
			f.cntopt.value = optLen;
			fw.f_prank.value = '<?=$prank?>';

		//-->
		</script>
<?php
	} else {
?>
		<script language='JavaScript'>
		<!--
			var fw = parent.document.frmWrite;
			var f = parent.document.frmCnum;

			var p_optLen = '<?=$cntopt?>';
			for (i=0;i<p_optLen;i++) {
				parent.removeOption();
			}
			f.cntopt.value = 0;
			fw.f_prank.value = '<?=$prank?>';
		//-->
		</script>
<?php
	}
?>



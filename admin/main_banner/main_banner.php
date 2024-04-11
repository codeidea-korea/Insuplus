<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$tm = "main_product";
	$lm = "";
	include $path_admin."inc/header.php";
?>

<?
	$gubun = REQSTR($_GET[gubun], "");

	$SQL = "
		select *
		from tbl_main_product
		where gubun = '".$gubun."'
		order by idx desc
		limit 0, 1
	";
	$rows = $dbcon -> fetch_array( $dbcon->query($SQL) );
	if ( $rows ) {
		extract($rows);
		unset($rows);
	}
	else {

	}


	if ($gubun == "1")
		$gubun_text = " - 상품1";
	elseif ($gubun == "2")
		$gubun_text = " - 상품2";
	elseif ($gubun == "3")
		$gubun_text = " - 상품3";

?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">메인상품관리 <?=$gubun_text?></td>
	</tr>
	<tr>
		<td colspan="2" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>



<form name="WriteForm" action="" method="post" enctype="multipart/form-data" onsubmit="return WriteOkGo()">
<input type="hidden" name="idx" value="<?=$idx?>">
<input type="hidden" name="gubun" value="<?=$gubun?>">


<!-- ### 게시판 시작 ###  -->

<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<colgroup>
		<col width="100">
		<col>
	</colgroup>
	<tr>
		<td colspan="2" class="m_line_2px">&nbsp;</td>
	</tr>


	<tr>
		<td class="b_txt_w">타이틀1</td>
		<td class="m_content">
			<input type="text" class="input" style="width:50%" name="tit1" value="<?=$tit1?>" maxlength="20">
		</td>
	</tr>
	<tr>
		<td colspan="2" class="m_line_1px">&nbsp;</td>
	</tr>

	<? if ($gubun != "3") { ?>
	<tr>
		<td class="b_txt_w">타이틀2</td>
		<td class="m_content">
			<input type="text" class="input" style="width:50%" name="tit2" value="<?=$tit2?>" maxlength="20">
		</td>
	</tr>
	<tr>
		<td colspan="2" class="m_line_1px">&nbsp;</td>
	</tr>
	<? } ?>

	<tr>
		<td class="b_txt_w">내용</td>
		<td class="m_content">
			<input type="text" class="input" style="width:100%" name="contents" value="<?=$contents?>" maxlength="100">
		</td>
	</tr>
	<tr>
		<td colspan="2" class="m_line_1px">&nbsp;</td>
	</tr>

	<tr>
		<td class="b_txt_w">Link</td>
		<td class="m_content">
			<input type="text" class="input" style="width:50%" name="link" value="<?=$link?>" maxlength="100">
		</td>
	</tr>
	<tr>
		<td colspan="2" class="m_line_1px">&nbsp;</td>
	</tr>

	<?
		$ObjFileName = "imgfile";
	?>
	<tr>
		<td class="b_txt_w">
			이미지
		</td>
		<td class="m_content">
			<table id="Tbl<?=$ObjFileName?>" width="100%" cellpadding=0 cellspacing=0></table>
			<?
				//echo $imgfile."<BR>";
				$ObjFileName = "imgfile";
				if ( getLen($idx) > 0 && getLen($$ObjFileName) > 0 ) {
					//echo "bbb";
					${"Arr_".$ObjFileName} = setFileName($$ObjFileName);
					for ( $i = 0 ; $i < count(${"Arr_".$ObjFileName}); $i++) {
						?>
						<script>add_file('Tbl<?=$ObjFileName?>', 1, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" class="input" disabled> 삭제 <input type="checkbox" name="<?=$ObjFileName?>_del[]" value="1" onclick="UseFile(this, \'<?=$ObjFileName?>\', \'<?=$i?>\')"> , 등록파일 : <?=${"Arr_".$ObjFileName}[$i][0]?> <?=PrintFileSize(${"Arr_".$ObjFileName}[$i][2])?>');</script>
						<?
					}
				}
				// 신규 파일 등록
				else {
					//echo "bbb";
					?>
						<script>add_file('Tbl<?=$ObjFileName?>', 1, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" class="input">');</script>
					<?
				}
			?>
			권장 이미지 사이즈 : 86 * 125
			<img id="<?=$ObjFileName?>" width="0" height="0">
		</td>
	</tr>
	<tr>
		<td colspan="2" class="m_line_1px">&nbsp;</td>
	</tr>

	<tr>
		<td colspan="2" height="80" align="center">
			<input type="image" name="imageField" src="http://oksan.bluecarpet.co.kr/_skin/board/default/images/b_btn_submit.gif" hspace="4">
		</td>
	</tr>
</table>
</form>

<script>
	function WriteOkGo() {
		ff = document.WriteForm;
		if (!ff.tit1.value) {
			alert("타이틀1을 입력하여 주십시오.");
			//ff.tit1.focus();
			return false;
		}

	<? if ($gubun != "3") { ?>
		if (!ff.tit2.value) {
			alert("타이틀2을 입력하여 주십시오.");
			//ff.tit2.focus();
			return false;
		}
	<? } ?>

		ff.action = "main_product_ok.php";
	}
</script>

<? include $path_admin."inc/footer.php"; ?>


<? $dbcon -> dbcon_close();?>

<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

<?}else{ //관리자 ?>
	<form name="WriteForm" action="" method="post" enctype="multipart/form-data" onsubmit="return WriteOkMainEventGo()">
	<input type="hidden" name="bc_id" value="<?=$bc_id?>">
	<input type="hidden" name="page" value="<?=$page?>">
	<input type="hidden" name="search_category" value="<?=$search_category?>">
	<input type="hidden" name="search" value="<?=$search?>">
	<input type="hidden" name="search_text" value="<?=$search_text?>">
	<input type="hidden" name="act" value="ok">
	<input type="hidden" name="mode" value="">
	<input type="hidden" name="seq" value="<?=$seq?>">
	<input type="hidden" name="seq_sub" value="<?=$seq_sub?>">
	<input type="hidden" name="seq_level" value="<?=$seq_level?>">
	<input type="hidden" name="writer" value="<?=$writer?>">
	<input type="hidden" name="nick_name" value="<?=$nick_name?>">
	<input type="hidden" name="content" value=".">
	
		<!-- ### 게시판 시작 ###  -->
		<table class="adm-view-tb">
			<colgroup>
				<col width="8%">
				<col width="42%">
				<col width="8%">
				<col width="42%">
			</colgroup>
			<tr>
				<th>제목</th>
				<td colspan="3">
					<? $subject = REQSTR2($subject); ?>
					<input type="text" name="subject" value="<?=$subject?>" class="w100p"/>
				</td>
			</tr>
			<? if ( $mode =="mod" ) { ?>
			<tr>
				<th>등록일</th>
				<td colspan="3"><?=$PrintRegDate?></td>
			</tr>
			<? } ?>
		
			<?
		    if ( $bc_upfile_image == "Y") {
			?>
			<?
		        $ObjFileName = "imgfile";
			?>
			<tr>
				<th>이미지<br/>
				<span class="txt_red">(512 x 256)</span>
				</td>
				<td>
					<table class="fileTb" id="Tbl<?=$ObjFileName?>"></table>
					<?
		        //echo $imgfile."<BR>";
		        $ObjFileName = "imgfile";
		        if ( getLen($seq) > 0 && getLen($$ObjFileName) > 0 ) {
		            ${
		                "Arr_".$ObjFileName}
		            = setFileName($$ObjFileName);
		            for ( $i = 0 ; $i < count(${"Arr_".$ObjFileName}); $i++) {
					?>
					<script>add_file('Tbl<?=$ObjFileName?>', <?=$bc_upfile_image_cnt?>, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" class="a_input" disabled> 삭제 <input type="checkbox" name="<?=$ObjFileName?>_del[]" value="1" onclick="UseFile(this, \'<?=$ObjFileName?>\', \'<?=$i?>\')"> , 등록파일 : <?=${"Arr_".$ObjFileName}[$i][0]?> <?=PrintFileSize(${"Arr_".$ObjFileName}[$i][2])?>');</script>
					<?
		            }
		        }
		        // 신규 파일 등록
		        else {
					?>
					<script>add_file('Tbl<?=$ObjFileName?>', <?=$bc_upfile_image_cnt?>, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" class="a_input">');</script>
					<?
		        }
					?>
					<img id="<?=$ObjFileName?>" width="0" height="0">
				</td>
			</tr>
			<?
		    }
			?>
			
			<tr>
				<th>URL</th>
				<td><input type="text" name="pc_url" value="<?=$pc_url?>" class="w100p"/></td>
			</tr>
		</table>
	
		<!-- (s) 하단  버튼 영역 -->
		<div class="btnWrap">
			<div class="leftWrap">
				<a href="javascript:<? if ($mode == "mod") {echo "view_go('".$seq."');";} else {echo "list_go();";} ?>" class="btn_list">목록</a>
			</div>
			<div class="rightWrap">
				<input type="submit" value="등록" class="btn_add"/>
			</div>
		</div>
		<!-- (e) 하단  버튼 영역 -->
	</form>
<script>
function WriteOkMainEventGo() {
	ff = document.WriteForm;
	

	if (!ff.subject.value) {
		alert("제목을 입력하여 주십시오.");
		ff.subject.focus();
		return false;
	}

	<? if ( $mode == "mod" ) { ?>
		ff.mode.value = "mod_ok";
		//ff.action = "notice_mod_ok.php";
	<? } elseif ( $mode == "write" ) { ?>
		ff.mode.value = "write_ok";
		//ff.action = "notice_write_ok.php";
	<? } elseif ( $mode == "reply" ) { ?>
		ff.mode.value = "reply_ok";
		//ff.action = "notice_write_ok.php";
	<? } ?>
	ff.action = "<?=$PHP_SELF?>";
	ff.target = "board_iframe";
	//ff.submit();
}
</script>
<?}?>

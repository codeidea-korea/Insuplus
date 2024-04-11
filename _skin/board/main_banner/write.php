<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

<?}else{?>
<form name="WriteForm" action="" method="post" enctype="multipart/form-data" onsubmit="return WriteOkGo()">
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
<input type="hidden" name="nick_name" value="<?=$nick_name?>" maxlength="20"  size="20" class="input">
<input type="hidden" name="content" value=".">
<!-- ### 게시판 시작 ###  -->

<table class="adm-view-tb">
	<colgroup>
		<col width="8%">
		<col width="42%">
		<col width="8%">
		<col width="42%">
	</colgroup>
	<?
        if ( $bc_category_use == "Y") {
	?>
	<tr>
		<td class="b_txt_w"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_category.gif"></td>
		<td class="m_content">
			<select name="category" class="input">
			<option value="All">전체</option>
			<?
                while ($CateListRs = $dbcon -> fetch_array($ArrCateListRs[1]) ) {
			?>
			<option value="<?=$CateListRs[idx]?>" <? if ($category == $CateListRs[idx] ) echo "selected"; ?>><?=$CateListRs[cate_name]?></option>
			<?}?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="m_line_1px">&nbsp;</td>
	</tr>
	<?
    }
	?>
<tr>
		<th>제목</th>
		<td colspan="3">
			<?
    $subject = REQSTR2($subject);
			?>
			<input type="text" name="subject" value="<?=$subject?>" class="w100p"/>
		</td>
	</tr>
	<? if($mode =="mod") { ?>
	<tr>
		<th>등록일</th>
		<td colspan="3">
			<?=$PrintRegDate?>
		</td>
	</tr>
	<? } ?>
	<tr>
		<th>노출순서</th>
		<td colspan="3"><input type="number" name="exposure_order" value="<?=$exposure_order?>"/></td>
	</tr>
	<?
    if ( $bc_upfile_image == "Y") {
	?>
	<?
        $ObjFileName = "imgfile";
	?>
	<tr>
		<th>
			pc 배너 이미지<br/>
			<span class="txt_red">(1920 x 736)</span>
		</td>
		<td class="m_content">
			<table class="fileTb" id="Tbl<?=$ObjFileName?>" width="100%" cellpadding=0 cellspacing=0></table>
			<?
	        //echo $imgfile."<BR>";
	        $ObjFileName = "imgfile";
	        if ( getLen($seq) > 0 && getLen($$ObjFileName) > 0 ) {
	            ${
	                "Arr_".$ObjFileName}
	            = setFileName($$ObjFileName);
	            for ( $i = 0 ; $i < count(${"Arr_".$ObjFileName}); $i++) {
				?>
				<script>add_file('Tbl<?=$ObjFileName?>', <?=$bc_upfile_image_cnt?>, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" disabled> 삭제 <input type="checkbox" name="<?=$ObjFileName?>_del[]" value="1" onclick="UseFile(this, \'<?=$ObjFileName?>\', \'<?=$i?>\')"> , 등록파일 : <?=${"Arr_".$ObjFileName}[$i][0]?> <?=PrintFileSize(${"Arr_".$ObjFileName}[$i][2])?>');</script>
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
        $ObjFileName2 = "imgfile2";
	?>
	<tr>
		<th>
			모바일 배너 이미지<br/>
			<span class="txt_red">(1080 x 945)</span>
		</td>
		<td class="m_content">
			<table class="fileTb" id="Tbl<?=$ObjFileName2?>" width="100%" cellpadding=0 cellspacing=0></table>
			<?
	        //echo $imgfile."<BR>";
	        $ObjFileName2 = "imgfile2";
	        if ( getLen($seq) > 0 && getLen($$ObjFileName2) > 0 ) {
	            ${
	                "Arr_".$ObjFileName2}
	            = setFileName($$ObjFileName2);
	            for ( $i = 0 ; $i < count(${"Arr_".$ObjFileName2}); $i++) {
				?>
				<script>add_file('Tbl<?=$ObjFileName2?>', <?=$bc_upfile_image_cnt?>, '<input type="file" name="<?=$ObjFileName2?>[]" id="<?=$ObjFileName2?>" style="width:50%" maxlength="255" disabled> 삭제 <input type="checkbox" name="<?=$ObjFileName2?>_del[]" value="1" onclick="UseFile(this, \'<?=$ObjFileName2?>\', \'<?=$i?>\')"> , 등록파일 : <?=${"Arr_".$ObjFileName2}[$i][0]?> <?=PrintFileSize(${"Arr_".$ObjFileName2}[$i][2])?>');</script>
				<?
	            }
	        }
	        // 신규 파일 등록
	        else {
				?>
				<script>add_file('Tbl<?=$ObjFileName2?>', <?=$bc_upfile_image_cnt?>, '<input type="file" name="<?=$ObjFileName2?>[]" id="<?=$ObjFileName2?>" style="width:50%" maxlength="255" class="a_input">');</script>
				<?
	        }
			?>
			<img id="<?=$ObjFileName2?>" width="0" height="0">
		</td>
	</tr>
	<? } ?>
	
	<? if ( $bc_secret_use == "Y" && $auth_secret ) { ?>
	<tr>
		<th>공개/비공개</th>
		<td colspan="3">
		<input type="radio" name="secret" value="N" <?=$secret?> <? if ($secret == "N" || $secret == "") echo "checked"; ?>>
		공개
		<input type="radio" name="secret" value="Y" <?=$secret?> <? if ($secret == "Y") echo "checked"; ?>>
		비공개
		</td>
	</tr>
	<?
    }
	?>
	<tr>
		<th>링크</th>
		<td colspan="3"><input type="text" name="pc_url" value="<?=$pc_url?>" class="w100p"/></td>
	</tr>
	<?
    if ( $bc_upfile_cnt > 0 && $auth_upload ) {
	?>
	<tr>
		<td class="b_txt_w">
			파일첨부
			<?
        if ( $bc_upfile_cnt > 1) {
			?>
			<img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_fileadd.gif" onClick="return AddFile('DivFile');" alt="파일첨부 갯수 늘리기" style="cursor:hand">
			<!-- <img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_filedel.gif" onClick="return removeList('<?=$file_cnt?>');" alt="파일첨부 갯수 줄이기" style="cursor:hand"> -->
			<?
        }
			?>
		</td>
		<td>
			<table border="0" cellpadding="0" cellspacing="0" width="100%" name="DivFile" id="DivFile">
				<script>
				function UseUpfile(Obj, idx) {

				    TargObj = document.getElementsByName("upfile[]");

				    if (Obj.checked == true) {
				        TargObj[idx].disabled = false;
				    }
				    else {
				        TargObj[idx].disabled = true;
				    }
				}
				</script>
				<?
        $FileNo = 0;
        if ( $mode == "mod" && getLen($seq) > 0) {

            // #### 첨부파일 처리
            $SQL = "
            select *
            from tbl_file
            where
            category='board' and bc_id = '".$bc_id."' and seq = '".$seq."'
            ";
            $FileRs = $dbcon -> query($SQL);
            while($RowFileRs = $dbcon -> fetch_row($FileRs) ) {
                $FC_idx						= $RowFileRs[0];
                $FC_category				= $RowFileRs[1];
                $FC_bc_id					= $RowFileRs[2];
                $FC_seq						= $RowFileRs[3];
                $FC_file_name				= $RowFileRs[4];
                $FC_file_realname			= $RowFileRs[5];
                $FC_file_size				= $RowFileRs[6];
                $FC_regdate				= $RowFileRs[7];
                $print_file_size = "";
                if ($FC_file_size < 1024) {
                    $print_file_size = "(".$FC_file_size." Byte)";
                }
                else if ($FC_file_size >= 1024 && $FC_file_size < 1024*1024) {
                    $print_file_size = "(".round($FC_file_size/1024)." KB)";
                }
                else if ($FC_file_size >= 1024*1024 && $FC_file_size < 1024*1024*1024) {
                    $print_file_size = "(".round($FC_file_size/(1024*1024))." MB)";
                }
                else if ($FC_file_size >= 1024*1024*1024 && $FC_file_size < 1024*1024*1024*1024) {
                    $print_file_size = "(".round($FC_file_size/(1024*1024*1024))." GB)";
                }
				?>
				<tr>
					<td class="m_content">
						<input type='file' name='upfile[]' class='input' style='width:100%' <? if ( getLen($FC_idx) > 0) { echo "disabled";}?>>
						<?
                if ( getLen($FC_idx) > 0) {
						?>
						<BR>
						<!-- <input type="hidden" name="old_upfile[]" value="<?=$FC_idx?>"> -->
						현재 등록된 파일 : <?=$FC_file_name?>
                    <?=$FC_file_size
						?>
						삭제 : <input type="checkbox" name="del_upfile[]" value="<?=$FC_idx?>" onclick="UseUpfile(this, '<?=$FileNo?>');">
						<?
                }
						?>
					</td>
				</tr>
				<?
                $FileNo++;
            }

            if ( $FileNo == 0) {
				?>
				<tr>
					<td class="m_content">
						<input type='file' name='upfile[]' class='input' style='width:100%'>
					</td>
				</tr>
				<?
            }
        }
        else {
				?>
				<tr>
					<td class="m_content">
						<input type='file' name='upfile[]' class='input' style='width:100%'>
					</td>
				</tr>
				<?
        }
				?>

			</table>
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td class="m_content">
						<span class="m_content_txt">
						업로드 확장자 제한 : <?=$bc_upfile_ext_upload?>,
        업로드 파일크기 제한 : <?=$bc_upfile_size
						?>
						</span>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="m_line_2px">&nbsp;</td>
	</tr>
	<script>
	num = <?=$FileNo+1?>;
	function AddFile(ObjDivName)
	{
	    if ( num >= <?=$bc_upfile_cnt?> ) {
	        alert("첨부파일은 <?=$bc_upfile_cnt?>개 까지만 가능합니다.");
	        return;
	    }

	    var objTbody, objRow, objCell;

	    objTbody = document.getElementById(ObjDivName);
	    objRow = objTbody.insertRow(objTbody.rows.length);
	    objCell = objRow.insertCell(0);
	    objCell.className = "m_content";
	    objCell.innerHTML+="<tr><td><input type='file' name='upfile[]' class='input' style='width:100%'></td></tr>";
	    num++;
	}
	</script>
	<?
    }
	?>

	<?
    if ($bc_autoreg_use == "Y") {
	?>
	<tr>
		<td class="b_txt_w"></td>
		<td class="m_content">
			<img src="<?=$url_signup?>" id="signupimage"<?/* onclick="change_signup()" style="cursor:hand();"*/?>>
			<input type="text" name="signupcode" class='input'>
			<BR>
			자동등록 방지를 위해 이미지에 보이는 글자를 입력하여 주십시오.
		</td>
	</tr>
	<tr>
		<td colspan="2" class="m_line_1px">&nbsp;</td>
	</tr>
	<script>
	function change_signup() {
	    <?/*
				//alert(document.images['signupimage']);
				//document.images['signupimage'].src="<?=$url_signup?>";
				// 추후 클릭시 이미지를 변경하는 방식으로 변경할것
	    */?>
	}
	</script>
	<?
    }
	?>
</table>
<!-- (s) 하단  버튼 영역 -->
<div class="btnWrap">
	<div class="leftWrap">
		<a href="javascript:<? if ($mode == "mod") {echo "view_go('".$seq."');";} else {echo "list_go();";} ?>" class="btn_list">목록</a>
	</div>
	<div class="rightWrap">
		<input type="submit" value="등록" class="btn_add"/>
</div>
<!-- (e) 하단  버튼 영역 -->
</form>

<?}?>

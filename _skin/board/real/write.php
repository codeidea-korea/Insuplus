<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>
<?
// 로그인 여부 확인
if (!$ss_u_idx){
	alert_page("로그인을 해주셔야 합니다.","/html/login/login.php?url=".$_SERVER["REQUEST_URI"]."");
	exit;
}
?>

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

				<!-- InConts -->
				<div id="InConts" class="Inconts">

					<!-- 컨텐츠 내용 -->
					<div class="notice">
						<strong><img src="/images/common/layout/tit_notice.png" alt="NOTICE" /></strong>
						<span>본 게시판 성격에 맞지 않는 게시물 등록 시, 무통보 삭제 될 수 있습니다.</span>
					</div>

					<div class="tableTy01 blankAreaTy04" style="border-top:2px solid #0bb0b2;">
						<table>
							<caption>온라인 Q&amp;A 게시판 쓰기</caption>
							<colgroup>
								<col style="width:15%;" />
								<col style="width:35%;" />
								<col style="width:15%;" />
								<col style="width:35%;" />
							</colgroup>
							<tbody>
								<tr>
									<th scope="row"><label for="inp_qna01">제목<img src="/images/common/icon/icon_require.gif" alt="필수사항" class="require" /></label></th>
									<td colspan="3">
										<?$subject = REQSTR2($subject);?>
										<input type="text" name="subject" value="<?=$subject?>" id="inp_qna01" class="inpSt01" style="width:418px;"/>
									</td>
								</tr>
								<tr>
									<th scope="row">글쓴이</th>
									<td>
										<input type="hidden" name="writer" value="<?=$writer?>">
										<?if ( $auth_level > 0 ) {?>
										<input type="hidden" name="nick_name" value="<?=$nick_name?>" maxlength="20"  size="20" class="input">
										<?if ( getLen($nick_name) > 0 ) {?>
										<?=$nick_name?>
										<? }?>
										<?}else{?>
										<input type="text" name="nick_name" value="<?=$nick_name?>" maxlength="20"  size="20" class="input" />
										<?}?>
									</td>
									<th scope="row">등록일</th>
									<td>
										<?
											if ($seq){
												echo $PrintRegDate;
											}else{
												echo date("Y.m.d");
											}
										?>
									</td>
								</tr>
								<!--
								<tr>
									<th scope="row">비밀글<img src="/images/common/icon/icon_require.gif" alt="필수사항" class="require" /></th>
									<td colspan="3">
										<input type="checkbox" name="secret" id="chk_qna01" value="Y"/><label for="chk_qna01" class="mL10">기본 비밀글로 등록됩니다.</label>
									</td>
								</tr>
								-->
	<?
    if ( $bc_upfile_image == "Y") {
	?>
	<?
        $ObjFileName = "imgfile";
	?>
	<tr>
		<th scope="row"><label for="file_qna01">리스트이미지</label></th>
		<td colspan="3">
			<style>
				#Tblimgfile > tbody > tr td{border-bottom:0px;}
				#Tblimgfile > tbody > tr td{padding-left:0px;}
			</style>
			<table id="Tbl<?=$ObjFileName?>" width="100%" cellpadding=0 cellspacing=0 style="border-bottom:0px;"></table>
			<?
        //echo $imgfile."<BR>";
        $ObjFileName = "imgfile";
        if ( getLen($seq) > 0 && getLen($$ObjFileName) > 0 ) {
            ${
                "Arr_".$ObjFileName}
            = setFileName($$ObjFileName);
            for ( $i = 0 ; $i < count(${"Arr_".$ObjFileName}); $i++) {
			?>
			<script>add_file('Tbl<?=$ObjFileName?>', <?=$bc_upfile_image_cnt?>, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" class="a_input"> 삭제 <input type="checkbox" name="<?=$ObjFileName?>_del[]" value="1" onclick="UseFile(this, \'<?=$ObjFileName?>\', \'<?=$i?>\')"> , 등록파일 : <?=${"Arr_".$ObjFileName}[$i][0]?> <?=PrintFileSize(${"Arr_".$ObjFileName}[$i][2])?>');</script>
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
									<th scope="row">내용</th>
									<td colspan="3" class="writeCont">
										<?
										if ( $bc_editor_use == "Y" ) {
											$content = RESSTR($content);
											$content = stripslashes($content);
											$content = str_replace("'","\"",$content);
//											$content = str_replace("\n","",$content);
											include_once $_SERVER["DOCUMENT_ROOT"]."/_util/board/editor.php";
										}else {?>
											<textarea name="content" id="content" style="width:100%;height:300px;" class="textarea"><?=$content?></textarea>
										<?}?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="btnArea tR">
						<a href="javascript: go_submit();" class="boxTxt boxSt01">확인</a><a href="javascript:list_go();" class="boxTxt boxSt03">목록</a>
						<input type="submit" name="bt_submit" id="bt_submit" style="display:none;">
					</div>

					<!--// 컨텐츠 내용 -->
				</div>
				<!--// InConts -->
</form>
<script type="text/javascript">
<!--
function go_submit(){
	$("#bt_submit").click();
}
//-->
</script>
<?
//==================================================
// 관리자 모드입니다.
//==================================================
}else{?>

    <form name="WriteForm" action="" method="post" enctype="multipart/form-data" onsubmit="return WriteOkGo()">
        <input type="hidden" name="bc_id" value="<?=$bc_id
?>">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="search_category" value="<?=$search_category?>">
<input type="hidden" name="search" value="<?=$search?>">
<input type="hidden" name="search_text" value="<?=$search_text?>">
<input type="hidden" name="act" value="ok">
<input type="hidden" name="mode" value="">
<input type="hidden" name="seq" value="<?=$seq?>">
<input type="hidden" name="seq_sub" value="<?=$seq_sub?>">
<input type="hidden" name="seq_level" value="<?=$seq_level?>">


<!-- ### 게시판 시작 ###  -->

<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<colgroup>
	<col width="100">
	<col>
	</colgroup>
	<tr>
		<td colspan="2" class="m_line_2px">&nbsp;</td>
	</tr>


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


	<?
    if ( $bc_secret_use == "Y" && $auth_secret ) {
	?>
	<tr>
		<td class="b_txt_w"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_secret.gif"></td>
		<td class="m_content">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td><input type="radio" name="secret" value="N" <?=$secret?> <? if ($secret == "N" || $secret == "") echo "checked"; ?>></td>
					<td class="m_content_txt">미사용</td>
					<td width="6">&nbsp;</td>
					<td><input type="radio" name="secret" value="Y" <?=$secret?> <? if ($secret == "Y") echo "checked"; ?>></td>
					<td class="m_content_txt">사용</td>
					<td width="10">&nbsp;</td>
					<td class="m_content_txt"> -  비밀글 사용시 게시물은 작성자와 관리자만 확인 할 수 있습니다.</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="m_line_1px">&nbsp;</td>
	</tr>
	<?
    }
	?>

	<tr>
		<td class="b_txt_w">작성자</td>
		<td class="m_content">
			<input type="hidden" name="writer" value="<?=$writer?>">
			<?
    if ( $auth_level > 0 ) {
			?>
			<input type="hidden" name="nick_name" value="<?=$nick_name?>" maxlength="20"  size="20" class="input">
			<?
        if ( getLen($nick_name) > 0 ) {
			?>
			<?=$nick_name?>
            <? }
			?>
			<?
    }
    else {
			?>
			<input type="text" name="nick_name" value="<?=$nick_name?>" maxlength="20"  size="20" class="input" />
			<?
    }
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="m_line_1px">&nbsp;</td>
	</tr>


	<?
    if ( $auth_level > 0 ) {
	?>
	<input type="hidden" name="passwd" value="">
	<?
    }
    else {
	?>
	<tr>
		<td class="b_txt_w">비밀번호</td>
		<td class="m_content">
			<input name="passwd" type="password" size="20" maxlength="20" class="input" />
		</td>
	</tr>
	<tr>
		<td colspan="2" class="m_line_1px">&nbsp;</td>
	</tr>
	<?
    }
	?>

	<?
    if ( $bc_email_use != "N") {
	?>
	<tr>
		<td class="b_txt_w">이메일</td>
		<td class="m_content">
			<?
        if ($mode == "mod") {
			?>
			<input type="hidden" name="email1" value="<?=$email1?>">
			<input type="hidden" name="email2" value="<?=$email2?>">
			<?=$email1?>@<?=$email2?>
            <? }
        else {
			?>
			<input type="hidden" name="old_email" value="<?=$old_email?>">
			<?
            getEmailForm("email1", "email2", $email1, $email2, "input");
			?>
			<?
            if ($mode == "write") {
			?>
			메일주소를 정확하게 입력하셔야 답변을 받으실수있습니다.
			<?
            }
			?>
			<?
        }
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="m_line_1px">&nbsp;</td>
	</tr>
	<?
    }
	?>

	<?
    if ( $bc_homepage_use == "Y") {
	?>
	<tr>
		<td class="b_txt_w">홈페이지</td>
		<td class="m_content" style="letter-spacing:0px">
			http://<input type="text" name="homepage" value="<?=$homepage?>" size="40" maxlength="40" class="input">
		</td>
	</tr>
	<tr>
		<td colspan="2" class="m_line_1px">&nbsp;</td>
	</tr>
	<?
    }
	?>

	<tr>
		<td class="b_txt_w">제목</td>
		<td class="m_content">
			<?
    $subject = REQSTR2($subject);
			?>
			<input type="text" name="subject" value="<?=$subject?>" style="width:100%" maxlength="150" class="input"/>
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<?
    if ( $bc_notice_use == "Y" && $auth_notice ) {
					?>
					<td><input type="checkbox" name="notice" value="Y" <? if ($notice == "Y") { echo "checked"; } ?>/></td>
					<td class="m_content_txt">공지글로 등록합니다.</td>
					<?
    }
					?>
					<?
    if ( $bc_hidden_use == "Y" && $auth_hidden) {
					?>
					<td><input type="checkbox" name="hidden" value="Y" <? if ($hidden == "Y") { echo "checked"; } ?>/></td>
					<td class="m_content_txt">해당글을 숨김니다.</td>
					<?
    }
					?>
				</tr>
			</table>

			<?
    if ( $mode =="mod" ) {
			?>
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td>작성일 : <?=$PrintRegDate?>, 조회수 : <?=$view_cnt?></td>
				</tr>
			</table>
			<?
    }
			?>

		</td>
	</tr>
	<tr>
		<td colspan="2" class="m_line_1px">&nbsp;</td>
	</tr>


	<tr>
		<td class="b_txt_w">내용</td>
		<td class="m_content">
			<?
    if ( $bc_editor_use == "Y" ) {
        $content = RESSTR($content);
        $content = str_replace("'","\"",$content);
        include_once $_SERVER["DOCUMENT_ROOT"]."/_util/board/editor.php";

        //echo myEditor('모드','에디터경로','폼이름','필드이름','폼사이즈','폼높이','랭귀지');
        //echo myEditor(1, $path_editor, $url_editor,'WriteForm','content','100%','400','utf-8');
    }
    else {
			?><textarea name="content" id="content" style="width:100%;height:300px;" class="textarea"><?=$content?></textarea><?
    }
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="m_line_1px">&nbsp;</td>
	</tr>

	<?
    if ( $bc_upfile_image == "Y") {
	?>
	<?
        $ObjFileName = "imgfile";
	?>
	<tr>
		<td class="b_txt_w">
			이미지
			<span onclick="add_file('Tbl<?=$ObjFileName?>', <?=$bc_upfile_image_cnt?>, '<input type=file name=<?=$ObjFileName?>[] id=<?=$ObjFileName?> style=width:50% maxlength=255 class=a_input>');" style='cursor:pointer; font-family:tahoma; font-size:12pt;'><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_fileadd.gif"></span>
			<span onclick="del_file('Tbl<?=$ObjFileName?>');" style='cursor:pointer; font-family:tahoma; font-size:12pt;'><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_filedel.gif"></span>
		</td>
		<td class="m_content">
			<table id="Tbl<?=$ObjFileName?>" width="100%" cellpadding=0 cellspacing=0></table>
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
	<tr>
		<td colspan="2" class="m_line_1px">&nbsp;</td>
	</tr>
	<?
    }
	?>


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
	<tr>
		<td colspan="2" height="80" align="center">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td><input type="image" name="imageField" src="<?=$url_skin_board.$bc_skin?>/images/b_btn_submit.gif" hspace="4"></td>
					<td width="4">&nbsp;</td>
					<td><a href="javascript:<? if ($mode == "mod") {echo "view_go('".$seq."');";} else {echo "list_go();";} ?>"><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_cancle.gif"></a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>

<?}?>

<? if($client_mode=="Y"){ //사용자 
	
	if($_SESSION["ss_view_seq"] != $seq && $mode == "mod") { //비밀번호 인증 체크 , 수정 모드
		alert_page("올바른 경로로 이용해 주세요.","qa_list.php");
	}
	
	$RS_PR = getGlobalProduct();
?>
<form name="WriteForm" action="" method="post" enctype="multipart/form-data" onsubmit="return WriteOkGo_qna()">
<input type="hidden" name="bc_id" value="<?=$bc_id?>">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="search" value="<?=$search?>">
<input type="hidden" name="search_text" value="<?=$search_text?>">
<input type="hidden" name="act" value="ok">
<input type="hidden" name="mode" value="">
<input type="hidden" name="seq" value="<?=$seq?>">
<input type="hidden" name="status" value="<?=$status?>">
<!-- (s)필수값 -->
<input type="hidden" name="secret" value="Y" />
<input type="hidden" name="passwd" value="1" />
<!-- (e)필수값 -->
<div class="col-md-10 col-sm-9">
	<div class="sub-content cs-wrap">
		<div class='text-danger text-right m-b-1'>* 필수 입력 사항입니다.</div>
		<div class='row-border'>
			<div class='detail-col-label'>제목<span class='text-danger pull-right'>*</span></div>
			<div class='detail-col-input colspan-3'><input type="text" name='subject' placeholder="제목" class="form-control" size='60' value="<?=$subject?>" /></div>
			<div class='detail-col-label'>상품명<span class='text-danger pull-right'>*</span></div>
			<div class='detail-col-input form-inline'>
				<select class='form-control' name="pr_cd">
					<option value='' selected>선택해 주세요</option>
					<? while($pr_row = $dbcon -> fetch_array($RS_PR)) { ?>
						<option value="<?=$pr_row["seq"]?>" <?if ($pr_cd==$pr_row["seq"]){?>selected<?}else{}?>><?=$pr_row["subject"]?></option>
					<? } ?>
				</select>
			</div>
			<div class='detail-col-label'>작성자<span class='text-danger pull-right'>*</span></div>
			<div class='detail-col-input form-inline'><input type="text" name='name' placeholder="이름을 입력해 주세요." class="form-control" size='20' value="<?=all_seed_dec($name)?>" /></div>
			<div class='detail-col-label'>연락처<span class='text-danger pull-right'>*</span></div>
			<div class='detail-col-input form-inline'><input type="text" name='mobile' placeholder="연락처를 입력해 주세요." class="form-control numberonly" size='20' value="<?=all_seed_dec($mobile)?>"/></div>
			<div class='detail-col-label'>이메일<span class='text-danger pull-right'>*</span></div>
			<div class='detail-col-input form-inline'>
				<div class='input-group'>
					<input type="text" name='email1' placeholder="email" class="form-control radius" size='12' value="<?=all_seed_dec($email1)?>"/>
					<span class='input-group-addon'>@</span>
					<input type="text" name='email2' placeholder="gmail.com" class="form-control" size='12' value="<?=all_seed_dec($email2)?>"/>
				</div>
			</div>
			<div class='detail-col-label'>문의내용<span class='text-danger pull-right'>*</span></div>
			<div class='detail-col-input colspan-3'>
				<textarea name="customer_content" rows="8" cols="" class='form-control'><?=$customer_content?></textarea>
			</div>
			<div class='detail-col-label'>비밀번호<span class='text-danger pull-right'></span></div>
			<div class='detail-col-input colspan-3 form-inline'><input type="password" name='customer_password' placeholder="" class="form-control" size='20' value="<?=all_seed_dec($customer_password)?>"/></div>
		</div>
		<? if($mode == "write") {?>
		<div class='m-t-1'>
			<span class='text-danger'>*</span>
			개인정보 이용 및 수집에 동의합니다.
			<a class='btn btn-sm btn-default' data-toggle='pop-modal' data-size='sm' data-href='./pop_privacy.php' data-title='개인정보 이용 및 수집 동의' target='modal_iframe'>자세히 보기</a>
			<div class='checkbox checkbox-inline m-l-1'>
				<input type="checkbox" name="agree_privacy" id="agree_privacy">
				<label for="agree_privacy">동의</label>
			</div>
		</div>
		<? } ?>
		<div class='row m-t-3'>
			<div class='col-md-2 col-sm-3 col-xs-6'>
				<a href="javascript:;" onClick="frontList()" class='btn btn-block btn-theme-dark light'>목록</a>
			</div>
			<div class='col-md-2 col-sm-3 col-xs-6 col-md-offset-8 col-sm-offset-6 col-xs-offset-0'>
				<? if($mode == "write") {?>
					<a href="javascript:;" onClick="frontWriteGO()" class='btn btn-block btn-theme-bg'>등록</a>
				<? } else { ?>
					<a href="javascript:;" onClick="frontWriteGO()" class='btn btn-block btn-theme-bg'>수정</a>
				<? } ?>
			</div>
		</div>
	</div>
</div>
</form>
<? }else{//관리자 
$RS_PR = getGlobalProduct();
?>
<form name="WriteForm" action="" method="post" enctype="multipart/form-data" onsubmit="return WriteOkGo_qna()">
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
<input type="hidden" name="nick_name" value="<?=$nick_name?>"/>
 <!-- (s) 관리자 상세화면  -->
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
			<? if ( $bc_notice_use == "Y" && $auth_notice ) { ?>
			<p><input type="checkbox" name="notice" value="Y" <? if ($notice == "Y") { echo "checked"; } ?>/> <span class="txt_red">공지글로 등록합니다.</span></p>
			<? } ?>
			<?  if ( $bc_hidden_use == "Y" && $auth_hidden) { ?>
			<p><input type="checkbox" name="hidden" value="Y" <? if ($hidden == "Y") { echo "checked"; } ?>/> <span class="txt_red">해당글을 숨김니다.</span></p>
			<? } ?>
		</td>
	</tr>
	<? if ( $mode =="mod" ) { ?>
	<tr>
		<th>작성일</th>
		<td><?=$PrintRegDate?></td>
		<th>조회수</th>
		<td><?=$view_cnt?></td>
	</tr>	
	<? } ?>
	<tr>
		<th>상품명</th>
		<td colspan="3">
			<select name="pr_cd">
				<option value="">선택해 주세요.</option>
				<? while($pr_row = $dbcon -> fetch_array($RS_PR)) { ?>
					<option value="<?=$pr_row["seq"]?>" <?if ($pr_cd==$pr_row["seq"]){?>selected<?}else{}?>><?=$pr_row["subject"]?></option>
				<? } ?>
			</select>
		</td>
	</tr>
	<tr>
		<th>이름</th>
		<td colspan="3"><input type="text" name="name" value="<?=all_seed_dec($name);?>" /></td>
	</tr>
	<tr>
		<th>연락처</th>
		<td><input type="text" name="mobile" value="<?=all_seed_dec($mobile)?>"></td>
		<th>이메일</th>
		<td><input type="text" name="email1" value="<?=all_seed_dec($email1)?>">@<input type="text" name="email2" value="<?=all_seed_dec($email2)?>"></td>
	</tr>

	
	<?
        if ( $bc_category_use == "Y") {
	?>
	<tr>
		<th>카테고리</th>
		<td colspan="3">
			<?
            if ( !$category ) {
			?>
			<select name="category" class="input">
			<?
                while ($CateListRs = $dbcon -> fetch_array($ArrCateListRs[1]) ) {
                    extract($CateListRs);
			?>
			<option value="<?=$idx?>" <? if ($category == $idx ) echo "selected"; ?>><?=$cate_name?></option>
			<?
            }
			?>
			</select>
			<?
        }
        else {
			?>
			<input type="hidden" name="category" value="<?=$category?>">
			<?=$cate_name?>
            <? }
			?>
		</td>
	</tr>
	<?
    }
	?>

	<?
    if ( $bc_secret_use == "Y" && $auth_secret ) {
	?>
	<tr>
		<th>비밀글</th>
		<td colspan="3">
			<input type="radio" name="secret" value="N" <?=$secret?> <? if ($secret == "N" || $secret == "") echo "checked"; ?>>
			<label>미사용</label>
			<input type="radio" name="secret" value="Y" <?=$secret?> <? if ($secret == "Y") echo "checked"; ?>>
			<label>사용</label>
			<span class="txt_red">-  비밀글 사용시 게시물은 작성자와 관리자만 확인 할 수 있습니다.</span>
		</td>
	</tr>
	<?
    }
	?>
	<tr>
		<th>문의내용</th>
		<td colspan="3"><textarea name="customer_content"><?=$customer_content;?></textarea></td>
	</tr>
	<tr>
		<th>비밀번호</th>
		<td colspan="3">
			<input type="password" name="customer_password" value="<?=all_seed_dec($customer_password)?>" />
		</td>
	</tr>	

	<?
    if ( $bc_homepage_use == "Y") {
	?>
	<tr>
		<th>홈페이지</th>
		<td colspan="3">
			http://<input type="text" name="homepage" value="<?=$homepage?>" size="40" maxlength="40" class="input">
		</td>
	</tr>
	<?
    }
	?>
	<?
    	if ( $bc_upfile_image == "Y") {
	?>
	<?
        $ObjFileName = "imgfile";
	?>
	<tr>
		<th>
			이미지
			<span onclick="add_file('Tbl<?=$ObjFileName?>', <?=$bc_upfile_image_cnt?>, '<input type=file name=<?=$ObjFileName?>[] id=<?=$ObjFileName?> style=width:50% maxlength=255 class=a_input>');" style='cursor:pointer; font-family:tahoma; font-size:12pt;'><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_fileadd.gif"></span>
			<span onclick="del_file('Tbl<?=$ObjFileName?>');" style='cursor:pointer; font-family:tahoma; font-size:12pt;'><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_filedel.gif"></span>
		</th>
		<td colspan="3">
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
	<?
    }
	?>


	<?
    if ( $bc_upfile_cnt > 0 && $auth_upload ) {
	?>
	<tr>
		<th>
			파일첨부
			<?
        if ( $bc_upfile_cnt > 1) {
			?>
			<img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_fileadd.gif" onClick="return AddFile('DivFile');" alt="파일첨부 갯수 늘리기" style="cursor:hand">
			<!-- <img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_filedel.gif" onClick="return removeList('<?=$file_cnt?>');" alt="파일첨부 갯수 줄이기" style="cursor:hand"> -->
			<?
        }
			?>
		</th>
		<td colspan="3">
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
		<th></td>
		<td colspan="3">
			<img src="<?=$url_signup?>" id="signupimage"<?/* onclick="change_signup()" style="cursor:hand();"*/?>>
			<input type="text" name="signupcode" class='input'>
			<BR>
			자동등록 방지를 위해 이미지에 보이는 글자를 입력하여 주십시오.
		</td>
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

<p class="tit_sub">- 답변</p>

<table class="adm-view-tb">
<colgroup>
	<col width="8%">
	<col width="42%">
	<col width="8%">
	<col width="42%">
</colgroup>
<tr>
	<th>답변상태</th>
	<td colspan="3">
		<input type="radio" name="status" value="W" <?=(!$status || $status=="W") ? "checked":"";?> /><label>대기</label>
		<input type="radio" name="status" value="C" <?=$status=="C" ? "checked":"";?> /><label>확인중</label>
		<input type="radio" name="status" value="A" <?=$status=="A" ? "checked":"";?> /><label>답변완료</label>
	</td>
</tr>
<tr>
	<th>알림톡 발송</th>
	<td colspan="3">
		<input type="checkbox" name="alrim_talk" id="alrim_talk" value="Y" /> <span class="txt_red">※ 답변상태(답변완료), 알림톡 발송 선택 시 알림톡이 전송됩니다.</span>
	</td>
</tr>
<tr>
	<th>답변</th>
	<td colspan="3">
	<?
   		if ( $bc_editor_use == "Y" ) {
        	$content = RESSTR($content);
        	$content = str_replace("'","\"",$content);
        	include_once $_SERVER["DOCUMENT_ROOT"]."/_util/board/editor.php";

       	 	//echo myEditor('모드','에디터경로','폼이름','필드이름','폼사이즈','폼높이','랭귀지');
       		//echo myEditor(1, $path_editor, $url_editor,'WriteForm','content','100%','400','utf-8');
   		} else { ?>
   			<textarea name="content" id="content" style="width:100%;height:300px;" class="textarea"><?=$content?></textarea>
   		<? } ?>
	</td>
</tr>
</table>

<!-- (s) 하단  버튼 영역 -->
<div class="btnWrap">
	<div class="leftWrap">
		<a href="javascript:list_go();" class="btn_list">목록</a>
	</div>
	<div class="rightWrap">
		<? if($mode=="mod") {?>
			<? if ($auth_delete) { ?>
				<a href="javascript:del_go('<?=$seq?>');" class="btn_normal">삭제</a>
			<? } ?>
			<input type="submit" value="수정" class="btn_add"/>
		<? } else { ?>
			<input type="submit" value="등록" class="btn_add"/>
		<? } ?>
	</div>
</div>
<!-- (e) 하단  버튼 영역 -->
</form>
<script>
function WriteOkGo_qna() {
			
	ff = document.WriteForm;

	if (!ff.subject.value) {
		alert("제목을 입력하여 주십시오.");
		ff.subject.focus();
		return false;
	}

	if($("#alrim_talk").is(":checked")) {
		if($("input:radio[name='status']:checked").val() != "A") {
			alert("답변완료 일때만 발송 가능합니다.");
			return false;
		}
	}

	<? if ($bc_editor_use == "Y") { ?>
		if(ff.content.value == "") {
			alert("답변을 입력해주세요");
			webnote.focusWebNote("content")		//에디터에 포커스를 주기위한 webnote 내장함수
			return false;
		}
	<? } else { ?>
		if(!ff.content.value) {
			alert('답변을 입력하세요!');
			ff.content.focus();
			return false;
		}
	<? } ?>

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

<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

<?
// 로그인 여부 확인
if (!$ss_u_idx){
	alert_page("로그인을 해주셔야 합니다.","/html/login/login.php?url=/html/counsel/qna.php");
	exit;
}


?>

    <form name="WriteForm" action="" method="post" enctype="multipart/form-data">
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
						<span>일반적인 사항에 대한 답변이 필요할 경우 자주 묻는 질문을 이용해주시기 바랍니다.<br />
							본 게시판 성격에 맞지 않은 게시물 등록 시, 무통보 삭제 될 수 있습니다.
						</span>
					</div>

					<div class="tableTy01 blankAreaTy04">
						<table>
							<caption>상담사례 게시판 쓰기</caption>
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
										<input type="text" id="inp_qna01" class="inpSt01" style="width:418px;" name="subject" value="<?=$subject?>"/>
									</td>
								</tr>
								<tr>
									<th scope="row">글쓴이</th>
									<td>
										<input type="hidden" name="writer" value="<?=$writer?>">
										<?if ( $auth_level > 0 ) {?>
										<input type="hidden" name="nick_name" value="<?=$nick_name?>" maxlength="20"  size="20" class="input">
										<?if ( getLen($nick_name) > 0 ) {
										echo $nick_name;
											}
										} else {?>
										<input type="text" name="nick_name" value="<?=$nick_name?>" maxlength="20"  size="20" class="input" />
										<?}?>
									</td>
									<th scope="row">등록일</th>
									<td>
										<?
											if ($mode=="mod"){
												echo $PrintRegDate;
											}else{
												echo date("Y.m.d");
											}
										?>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="sel_qna01">구분<img src="/images/common/icon/icon_require.gif" alt="필수사항" class="require" /></label></th>
									<td><select title="이메일 선택" id="sel_qna01" class="selectSt01" name="ext1">
											<? foreach ($Arr_cost_detail_list as $key => $val) { ?>
											<option value="<?=$key?>" <?if ($key==$ext1){echo "selected";}?>><?=$val?></option>
											<?}?>
										</select>
									</td>
									<th scope="row"><label for="sel_qna02">지점<img src="/images/common/icon/icon_require.gif" alt="필수사항" class="require" /></label></th>
									<td><select title="이메일 선택" id="sel_qna02" class="selectSt01" name="category">
											<?
												while ($CateListRs = $dbcon -> fetch_array($ArrCateListRs[1]) ) {
													extract($CateListRs);
											?>
											<option value="<?=$idx?>" <? if ($category == $idx ) echo "selected"; ?>><?=$cate_name?></option>
											<?
											}
											?>
										</select>
									</td>
								</tr>
								<tr>
									<th scope="row"><label for="file_qna01">파일</label></th>
									<td colspan="3">
			<style>
				#DivFile > tbody > tr td{border-bottom:0px;}
				#DivFile > tbody > tr td{padding-left:0px;}
			</style>
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
						<input type='file' name='upfile[]' class='input' style='width:50%' <? if ( getLen($FC_idx) > 0) { echo "disabled";}?>>
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
						<input type='file' name='upfile[]' class='input' style='width:50%'>
					</td>
				</tr>
				<?
            }
        }
        else {
				?>
				<tr>
					<td class="m_content">
						<input type='file' name='upfile[]' class='input' style='width:50%'>
					</td>
				</tr>
				<?
        }
				?>

			</table>
						<span class="m_content_txt">
						업로드 확장자 제한 : <?=$bc_upfile_ext_upload?>,
						업로드 파일크기 제한 : <?=number_format($bc_upfile_size*0.000001)?>MB
						</span>
									</td>
								</tr>
								<tr>
									<th scope="row">내용</th>
									<td colspan="3" class="writeCont">
										<div>
											<?
												if ( $bc_editor_use == "Y" ) {
													$content = RESSTR($content);
													include_once $_SERVER["DOCUMENT_ROOT"]."/_util/board/editor.php";
												}else {
											?>
												<textarea name="content" id="content" style="width:100%;height:300px;" class="textarea"><?=$content?></textarea>
											<?
												}
											?>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>

					<div class="btnArea tR">
						<a href="javascript:WriteOk_qnaGo();" class="boxTxt boxSt01">확인</a><a href="javascript: list_go();" class="boxTxt boxSt03">목록</a>
					</div>

					<!--// 컨텐츠 내용 -->
					<?if ($area_code==''){?>
					<?include $_SERVER["DOCUMENT_ROOT"]."/html/include/board_bottom.php";?>
					<?}?>

				</div>
				<!--// InConts -->

</form>

<?
##########################################################################
### 관리자 모드입니다
##########################################################################
}else{

	$RS_PR = getGlobalProduct(); //상품명 추출
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
	<tr>
	<? if ( $mode =="mod" ) { ?>			
		<th>상담일</th>
		<td>
			<input type="text" id="counsel_date" name="counsel_date" value="<?=$PrintRegDate?>" class="w100 datepicker">
		</td>
		
		<th>조회수</th>
		<td><?=$view_cnt?></td>
		<? } else {?>
			<th>상담일</th>
			<td colspan="3">
				<input type="text" id="counsel_date" name="counsel_date" value="<?=$PrintRegDate?>" class="w100 datepicker">
			</td>
		<? } ?>
	</tr>
	<tr>
		<th>상품명</th>
		<td>
			<select name="pr_cd" disabled>
				<option value="">선택해 주세요.</option>
				<? while($pr_row = $dbcon -> fetch_array($RS_PR)) { ?>
					<option value="<?=$pr_row["seq"]?>" <?if ($pr_cd==$pr_row["seq"]){?>selected<?}else{}?>><?=$pr_row["subject"]?></option>
				<? } ?>
			</select>
		</td>
	</tr>
	<?
        if ( $bc_category_use == "Y") {
	?>
	<tr>
		<th>구분</th>
		<td colspan="3">
			<select name="category">
			<?
				$type = $category;
                while ($CateListRs = $dbcon -> fetch_array($ArrCateListRs[1]) ) {
                    extract($CateListRs);
			?>
			<option value="<?=$idx?>" <? if ($type == $idx ) echo "selected"; ?>><?=$cate_name?></option>
			<?
            }
			?>
			</select>
		</td>
	</tr>
	<?
    }
	?>
	<tr>
		<th>국가</th>
		<td colspan="3">
			<select name="nation">
			<? foreach($nation_arr as $key=>$val) {?>
				<option value="<?=$key;?>" <?=$nation==$key ? "selected":"";?>><?=$val;?></option>
			<? } ?>
			</select>
		</td>
	</tr>
<?
        $ObjFileName = "imgfile";
	?>
	<tr>
		<th>
			배너이미지<br/>
			<span class="txt_red">(406 x 103)</span>

		</th>
		<td colspan="3">
			<table id="Tbl<?=$ObjFileName?>" class="fileTb"></table>
			<script>
				function readURL(input) {
					if (input.files && input.files[0]) {
						var reader = new FileReader();

						reader.onload = function(e) {
						$('#<?=$ObjFileName?>').attr('src', e.target.result);
						}

						reader.readAsDataURL(input.files[0]);
					}
				}

				$("#<?=$ObjFileName?>").change(function() {
					readURL(this);
				});
			</script>
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
			<?
				if ($bc_upfile_image == "Y") {
					$ObjFileName = "imgfile";
					//if ( $bc_upfile_image_thum == "Y" ) {}
					#### 이미지 처리
					if ( getLen($$ObjFileName) > 0 ) {
						${"Arr_".$ObjFileName} = setFileName($$ObjFileName);
						for ( $i = 0 ; $i < count(${"Arr_".$ObjFileName}); $i++) {

							${"info".$ObjFileName} = getimagesize($upload_path."/".${"Arr_".$ObjFileName}[$i][1]);
							${"info".$ObjFileName."width"} = ${"info".$ObjFileName}[0];
							${"info".$ObjFileName."height"} = ${"info".$ObjFileName}[1];

							if ( ${"info".$ObjFileName."width"} >= ${"info".$ObjFileName."height"}){
								if ( ${"info".$ObjFileName."width"} > $bc_upfile_image_width ) {
									${"size".$ObjFileName} = " width=\"".$bc_upfile_image_width."\" ";
								}
								else {
									${"size".$ObjFileName} = " width=\"".${"info".$ObjFileName."width"}."\" ";
								}
							}
							else {
								if ( ${"info".$ObjFileName."height"} > $bc_upfile_image_height ) {
									${"size".$ObjFileName} = " height=\"".$bc_upfile_image_height."\" ";
								}
								else {
									${"size".$ObjFileName} = " height=\"".${"info".$ObjFileName."height"}."\" ";
								}
							}

							echo "<img src=\"".$upload_url."/".${"Arr_".$ObjFileName}[$i][1]."\" width=\"200\">";

							break;
						}
					}
				}
			?>
			<img id="<?=$ObjFileName?>" width="0" height="0">
		</td>
	</tr>
	<tr>
		<th>내용</th>
		<td colspan="3">
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
	<?
    if ( $bc_secret_use == "Y" && $auth_secret ) {
	?>
	<tr>
		<th>공개/비공개</th>
		<td colspan="3">
			<input type="radio" name="secret" value="N" <?=$secret?> <? if ($secret == "N" || $secret == "") echo "checked"; ?>>
			<label>공개</label>
			<input type="radio" name="secret" value="Y" <?=$secret?> <? if ($secret == "Y") echo "checked"; ?>>
			<label>비공개</label>
		</td>
	</tr>
	<?
    }
	?>

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
<?}?>

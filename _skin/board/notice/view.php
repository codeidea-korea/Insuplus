<?
//사용자 모드입니다
	if ($client_mode=="Y"){
		
?>
<div class="col-md-10 col-sm-9">
		<div class="sub-content cs-wrap">
			<div class='row-border responsive'>
			<div class='detail-col-label'>제목</div>
				<div class='detail-col-input colspan-3'>
				<?if($notice == "Y"){?>
				<span class='label label-notice'>공지</span>
				<?}?>
				&nbsp;<?=$subject?> <?=$new_icon?> <?=$print_hidden?> 
				</div>
				<div class='detail-col-label valign-middle hidden-xs'><p>내용&nbsp;<span class='text-danger'>*</span></p></div>
				<div class='detail-col-input colspan-3 flex-column flex-100 p-y-2'>
				<p>
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

									echo "<a href=\"javascript:showPicture('".$upload_url."/".${"Arr_".$ObjFileName}[$i][1]."')\"><img src=\"".$upload_url."/".${"Arr_".$ObjFileName}[$i][1]."\" ".${"size".$ObjFileName}."></a>";

									break;
								}
							}
						}
					?>
				</p>
				<p><?=nl2br($content)?></p>
				</div>
			</div>
			<div class='row m-t-3'>
				<div class='col-md-2 col-sm-3 col-xs-4'>
					<a href="javascript:;" onClick="frontList()" class='btn btn-block btn-theme-dark light'>목록</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?}else{?>
<table class="adm-view-tb">
	<colgroup>
	<col width="8%">
	<col width="42%">
	<col width="8%">
	<col width="42%">
	</colgroup>

	<? if ($bc_category_use == "Y" ) { ?>
	<tr>
		<th><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_category2.gif"></th>
		<td colspan="3"><?=$print_cate_name?></td>
	</tr>
	<? } ?>
	<tr>
		<th>제목</th>
		<td colspan="3"><?=$subject?> <?=$new_icon?> <?=$print_hidden?> <?=$print_notice?></td>
	</tr>
	<tr>
		<th>작성일</th>
		<td><?=$PrintRegDate?></td>
		<th>조회수</th>
		<td><?=$view_cnt?></td>
	</tr>
	<tr>
		<th>공지여부</th>
		<td colspan="3"><?if ( $bc_notice_use == "Y" && $auth_notice ) {?>
				<input type="checkbox" disabled name="notice" value="Y" <? if ($notice == "Y") { echo "checked"; } ?>/>※ 공지글 인 경우 체크해 주세요.
			<?}?>
		</td>
	</tr>
	<? if ( ( $bc_homepage_use == "Y" && $homepage) || ( $bc_email_use != "N" && $email1 && $email2) ) { ?>
	<tr>
		<? if ($bc_homepage_use == "Y" && $homepage) { ?>
		<th><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_homepage.gif"></th>
		<td><a href="http://<?=$homepage?>" target="_blank">http://<?=$homepage?></a></td>
		<? } ?>
		<? if ( $bc_email_use != "N" && $email1 && $email2) { ?>
		<th><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_email.gif"></th>
		<td><a href="mailto:<?=$email1?>@<?=$email2?>"><?=$email1?>@<?=$email2?></a></td>
		<? } ?>
	</tr>
	<? } ?>

	<? if ($bc_upfile_cnt > 0) { ?>
	<tr>
		<th>파일첨부</th>
		<td><?
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

								if ($auth_download) {
									?><a href="javascript:down_go('<?=$FC_idx?>', '<?=$FC_category?>', '<?=$FC_bc_id?>', '<?=$FC_seq?>')"><?=$FC_file_name?></a> <?=$print_file_size?>, <?=$FC_regdate?><BR><?
								}
								else {
									?><?=$FC_file_name?><BR><?
								}
								unset($RowFileRs);
							}
							unset($FileRs);
						?></td>
	</tr>
	<? } ?>
	<tr>
		<th>내용</th>
		<td colspan="3" class="content">
			<p>
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

								echo "<a href=\"javascript:showPicture('".$upload_url."/".${"Arr_".$ObjFileName}[$i][1]."')\"><img src=\"".$upload_url."/".${"Arr_".$ObjFileName}[$i][1]."\" ".${"size".$ObjFileName}."></a>";

								break;
							}
						}
					}
				 ?>
			</p>
			<p><?=nl2br($content)?></p>
			</td>
	</tr>
	<tr>
		<th>공개/비공개</th>
		<td colspan="3">
			<? if ($secret == "N" || $secret == "") echo "공개"; 
			if ($secret == "Y") echo "비공개"; ?>
		</td>
	</tr>
<?
	if ($bc_comment_use == 'Y') {
		$category = "board";
		$bc_id = $bc_id;
		$seq = $seq;
?>
	<!-- comments Start -->
	<tr>
		<td>
			<? include $path_comment."index.php";?>
		</td>
	</tr>
	<!-- comments End -->
<?
	 }
 ?>
<?
	// 다음글 이전글 허용시
	if($bc_prev_next == "Y") {
?>
	<tr>
		<td>

			<table border=0 cellspacing=0 cellpadding=0 width=100%>
				<tr>
					<td height=20 colspan="3"></td>
				</tr>
				<tr>
					<td colspan="3" class="m_line_1px"></td>
				</tr>
				<tr>
					<td width=80  class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_prev.gif"></td>
					<td width=2></td>
					<td class="b_nextprev">&nbsp;&nbsp;&nbsp;<?=$prev_subject?></td>
				</tr>
				<tr>
					<td colspan="3" class="m_line_1px"></td>
				</tr>
				<tr>
					<td width=80  class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_next.gif"></td>
					<td width=2></td>
					<td class="b_nextprev">&nbsp;&nbsp;&nbsp;<?=$next_subject?></td>
				</tr>
				<tr>
					<td colspan="3" class="m_line_1px"></td>
				</tr>
			</table>
		</td>
	</tr>
<? } ?>
</table>
			
<!-- (s) 하단  버튼 영역 -->
<div class="btnWrap">
	<div class="leftWrap">
		<a href="javascript:list_go();" class="btn_list">목록</a>
	</div>
	<div class="rightWrap">
		<? if ($auth_delete) { ?>
			<a href="javascript:del_go('<?=$seq?>');" class="btn_normal">삭제</a>
		<? } ?>
		<? if ( $auth_modify) { ?>
			<a href="javascript:mod_go('<?=$seq?>');" class="btn_normal">수정</a>
		<? } ?>
	</div>
</div>
<!-- (e) 하단  버튼 영역 -->

<?}?>
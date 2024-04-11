<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>

<?}else{?>

			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td class="m_line_2px">&nbsp;</td>
				</tr>
				<tr>
					<td>
						<table border="0" cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td class="b_txt" width="80">제목</td>
								<td class="b_subtitle" style="padding-left:10px">
									<?=$subject?> <?=$new_icon?> <?=$print_hidden?> <?=$print_notice?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="m_line_1px">&nbsp;</td>
				</tr>

				<tr>
					<td>
						<table border="0" cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td class="b_txt" width="80">작성일</td>
								<td class="b_num" width="130" align="center"><?=$PrintRegDate?></td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="m_line_1px">&nbsp;</td>
				</tr>

				<? if ($bc_category_use == "Y" ) { ?>
				<tr>
					<td>
						<table border="0" cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td class="b_txt" width="80">구분</td>
								<td class="b_subtitle" style="padding-left:10px">
									<?=$print_cate_name?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="m_line_1px">&nbsp;</td>
				</tr>
				<? } ?>
				
				

			<? if ( ( $bc_homepage_use == "Y" && $homepage) || ( $bc_email_use != "N" && $email1 && $email2) ) { ?>
				<tr>
					<td>
						<table border="0" cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<? if ($bc_homepage_use == "Y" && $homepage) { ?>
								<td class="b_txt" width="80"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_homepage.gif"></td>
								<td class="b_num" width="130" style="padding-left:10px"><a href="http://<?=$homepage?>" target="_blank">http://<?=$homepage?></a></td>
								<? } ?>
								<? if ( $bc_email_use != "N" && $email1 && $email2) { ?>
								<td class="b_txt" width="80"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_email.gif"></td>
								<td class="b_num" width="130" style="padding-left:10px">
									<a href="mailto:<?=$email1?>@<?=$email2?>"><?=$email1?>@<?=$email2?></a>
								</td>
								<? } ?>
								<td>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="m_line_1px">&nbsp;</td>
				</tr>
			<? } ?>

			<? if ($bc_upfile_cnt > 0) { ?>
				<tr>
					<td>
						<table border="0" cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td class="b_txt" width="80"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_file.gif"></td>
								<td class="b_name" style="padding-left:10px">
									<?
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
									?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="m_line_1px">&nbsp;</td>
				</tr>
			<? } ?>
				<tr>
					<td>
						<table border=0 cellspacing=0 cellpadding=10 width=100%>
							<tr>
								<td class="b_txt" width="60" rowspan="2">내용</td>
								<td valign=top>
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

									<table border=0 cellspacing=0 cellpadding=0 width=100%>
										<tr>
											<td class="b_content" valign=top style="padding:10px 10px 10px 10px"><?=$content?></td>
										</tr>
									</table>
								</td>
							</tr>
			<?// if ($ip_view=='1') { ?>
							<tr>
								<td align=right height=20 class="b_num"><?=$ip?></td>
							</tr>
			<?// }  ?>
						</table>
					</td>
				</tr>
				<tr>
					<td class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<td>
						<table border="0" cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td class="b_txt" width="80">공개/비공개</td>
							<td class="b_subtitle" style="padding-left:10px">
								<? if ($secret == "N" || $secret == "") echo "공개"; 
								if ($secret == "Y") echo "비공개"; ?>
							</td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="m_line_1px">&nbsp;</td>
				</tr>
			<?
				if ($bc_comment_use == 'Y') {
					$category = "board";
					$bc_id = $bc_id;
					$seq = $seq;
			?>
				<!-- comments Start -->
				<tr>
					<td height=15></td>
				</tr>
				<tr>
					<td>
						<? include $path_comment."index.php";?>
					</td>
				</tr>
				<!-- comments End -->
			<?
				 }
			 ?>
				<tr>
					<td class="m_line_1px"></td>
				</tr>
				<tr>
					<td height=15></td>
				</tr>



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
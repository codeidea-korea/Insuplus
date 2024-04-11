<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>
				<!-- InConts -->
				<div id="InConts" class="Inconts">

					<!-- 컨텐츠 내용 -->
					<div class="board">
						<div class="boardView01">
							<table>
								<caption>공지사항 게시판 보기</caption>
								<colgroup>
									<col style="width:8%;" />
									<col width="*" />
									<col style="width:8%;" />
									<col style="width:10%;" />
									<col style="width:8%;" />
									<col style="width:15%;" />
									<col style="width:8%;" />
									<col style="width:5%;" />
								</colgroup>
								<thead>
									<tr>
										<th scope="col" colspan="8">
										<? if ($bc_category_use == "Y" ) { ?>
										<span class="iconSt02"><?=$print_cate_name?></span>
										<?}?>
										<?=$subject?>
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th scope="col">파일</th>
										<td>
									<?
									if ($bc_upfile_cnt > 0) {
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
												?><img src="/images/common/icon/icon_file.gif" alt="file" /><a href="javascript:down_go('<?=$FC_idx?>', '<?=$FC_category?>', '<?=$FC_bc_id?>', '<?=$FC_seq?>')"> 파일첨부</a> <br/><?=$print_file_size?><?
											}
											else {
												?><?=$FC_file_name?><BR><?
											}
											unset($RowFileRs);
										}
										unset($FileRs);
									}
									?>
										</td>
										<th scope="col">글쓴이</th>
										<td><?=$nick_name?></td>
										<th scope="col" class="bullTy04">등록일</th>
										<td><?=$PrintRegDate?></td>
										<th scope="col" class="bullTy04">조회수</th>
										<td><?=$view_cnt?></td>
									</tr>
									<tr>
										<td colspan="8" class="boardCont01">
											<div>
												<?=$content?>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>

						<dl class="prevNext">
							<dt class="prev">이전글</dt>
							<dd><?=$prev_subject?></dd>
							<dt class="next">다음글</dt>
							<dd><?=$next_subject?></dd>
						</dl>

						<div class="btnArea tR">
							<a href="javascript:list_go();" class="boxTxt boxSt03">목록</a>
						</div>
					</div>
					<!--// 컨텐츠 내용 -->
				</div>
				<!--// InConts -->
<?}else{?>

			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td class="m_line_2px">&nbsp;</td>
				</tr>

				<? if ($bc_category_use == "Y" ) { ?>
				<tr>
					<td>
						<table border="0" cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td class="b_txt" width="80"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_category2.gif"></td>
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
				<tr>
					<td>
						<table border="0" cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td class="b_txt" width="80"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_title.gif"></td>
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
								<td class="b_txt" width="80"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_writer.gif"></td>
								<td class="b_name" width="130" align="center"><?=$nick_name?></td>
								<td class="b_txt" width="80"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_date.gif"></td>
								<td class="b_num" width="130" align="center"><?//=$regdate?><?=$PrintRegDate?></td>
								<td class="b_txt" width="80"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_view.gif"></td>
								<td class="b_num" width="80" align="center"><?=$view_cnt?></td>
								<td>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="m_line_1px">&nbsp;</td>
				</tr>


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

				<tr>
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width=100%>
							<tr>
								<? if ( $auth_delete ) { ?>
								<td width="70" align="left"><a href="javascript:del_go('<?=$seq?>');"><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_delete.gif"></a></td>
								<? } ?>
								<? if ( $auth_modify) { ?>
								<td width="70" align="left"><a href="javascript:mod_go('<?=$seq?>');"><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_modify.gif"></a></td>
								<? } ?>
								<? if ( $bc_reply_use == "Y" && $auth_reply ) { ?>
								<td width="70" align="left"><a href="javascript:reply_go('<?=$seq?>');"><img  src="<?=$url_skin_board.$bc_skin?>/images/b_btn_reply.gif"></a></td>
								<? } ?>
								<? if ( $auth_write) { ?>
								<td width="70" align="left"><a href="javascript:write_go();"><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_write.gif"></a></td>
								<? } ?>
								<td align="right"><a href="javascript:list_go();"><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_list.gif"></a></td>
							</tr>
						</table>
					</td>
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

<?}?>
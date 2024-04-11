<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>

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
							<td class="b_txt" width="80">카테고리</td>
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
							<td class="b_txt" width="80">작성자</td>
							<td class="b_name" width="130" align="center"><?=$nick_name?></td>
							<td class="b_txt" width="80">작성일</td>
							<td class="b_num" width="130" align="center"><?//=$regdate?><?=$PrintRegDate?></td>
							<td class="b_txt" width="80">조회수</td>
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
							<td class="b_txt" width="80">홈페이지</td>
							<td class="b_num" width="130" style="padding-left:10px"><a href="http://<?=$homepage?>" target="_blank">http://<?=$homepage?></a></td>
							<? } ?>
							<? if ( $bc_email_use != "N" && $email1 && $email2) { ?>
							<td class="b_txt" width="80">이메일</td>
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
			<tr>
				<td>
					<table border="0" cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td class="b_txt" width="80">방이름</td>
							<td class="b_subtitle" style="padding-left:10px">
								<?=$ext1?>
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
							<td class="b_txt" width="80">특징</td>
							<td class="b_subtitle" style="padding-left:10px">
								<?=$ext2?>
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
							<td class="b_txt" width="80">크기</td>
							<td class="b_subtitle" style="padding-left:10px">
								<?=$ext3?>평
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
							<td class="b_txt" width="80">인원</td>
							<td class="b_subtitle" style="padding-left:10px">
								기준 : <?=$ext4?>명 / 최대 : <?=$ext5?>명
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
							<td class="b_txt" width="80">요금</td>
							<td class="b_subtitle" style="padding-left:10px">
								1일 비수기 요금 : <?=number_format($day_m1_1)?> 원 / <?=number_format($day_m1_2)?> / <?=number_format($day_m1_3)?> 원 <br>
								1일 준성수기 요금 : <?=number_format($day_m2_1)?> 원 / <?=number_format($day_m2_2)?> 원 / <?=number_format($day_m2_3)?> 원 <br>
								1일 성수기 요금 : <?=number_format($day_m3_1)?> 원 / <?=number_format($day_m3_2)?> 원 / <?=number_format($day_m3_3)?> 원
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
							<td class="b_txt" width="80">서비스안내</td>
							<td class="b_subtitle" style="padding-left:10px">
								<?=$ext10?>
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
							<td class="b_txt" width="80">설명</td>
							<td class="b_subtitle" style="padding-left:10px">
								<?=$content?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="m_line_1px">&nbsp;</td>
			</tr>
		<? if ($bc_upfile_cnt > 0) { ?>
			<tr>
				<td>
					<table border="0" cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td class="b_txt" width="80">파일첨부</td>
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
					<table border="0" cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td class="b_txt" width="80">이미지첨부</td>
							<td class="b_name" style="padding-left:10px">
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
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class="m_line_1px">&nbsp;</td>
			</tr>
<!-- 이미지 다운로드 -->
			<tr>
				<td>
					<table border="0" cellspacing="0" cellpadding="0" width="100%">
						<tr>
							<td class="b_txt" width="80">이미지</td>
							<td class="b_subtitle" style="padding-left:10px">
								<?for ($k = 1 ; $k<13 ; $k++){?>
								<?
									$filek = "file".$k;
									$filename = ${$filek};

									if ( getLen(${$filek}) > 0 ) {
								?>
								<?
										$ar_filename = setFileName(${$filek});
								?>
										<a href="<?=$upload_url?>/<?=$ar_filename[0][1]?>" target="_blank"><img src="<?=$upload_url?>/<?=$ar_filename[0][1]?>.3thumb" width="50" height="50"></a>
								<?
									}
								?>
								<?}?>&nbsp;
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
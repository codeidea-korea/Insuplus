<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>

				<!-- InConts -->
				<div id="InConts" class="Inconts">

					<!-- 컨텐츠 내용 -->
					<div class="board">
						<div class="boardView01">
							<table>
								<caption>의료진 칼럼 리스트 보기</caption>
								<colgroup>
									<col style="width:80px;" />
									<col style="width:110px;" />
									<col style="width:60px;" />
									<col width="*" />
									<col style="width:69px;" />
									<col style="width:140px;" />
									<col style="width:80px;" />
									<col style="width:40px;" />
								</colgroup>
								<thead>
									<tr>
										<th scope="col" colspan="8"><span class="iconSt03"><?=$print_cate_name?></span> <?=$subject?></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th scope="col">글쓴이</th>
										<td colspan="3"><?=$nick_name?> 원장</td>
										<th scope="col">등록일</th>
										<td><?=$PrintRegDate?></td>
										<th scope="col" class="bullTy04">조회수</th>
										<td><?=$view_cnt?></td>
									</tr>
									<tr>
										<th scope="col">파일</th>
										<td colspan="2">
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
												?><?=$FC_file_name?><?
											}
											unset($RowFileRs);
										}
										unset($FileRs);
									}
									?>
										</td>
										<th scope="col" class="bullTy04" align="right" style="padding-right:10px;">링크</th>
										<td colspan="4">
											<?if ($ext2){?>
											<a href="<?=$ext2?>" target="_blank" class="fColor03"><img src="/images/common/icon/icon_window.gif" alt="새창" /> 관련링크 가기</a>
											<?}?>
										</td>
									</tr>
									<tr>
										<td colspan="8" class="boardCont01">
											<div>
												<?
													$content = str_replace("돋움","NanumGothic",$content);
													$content = str_replace("바탕","NanumGothic",$content);
													$content = str_replace("굴림","NanumGothic",$content);
													$content = str_replace("gulim","NanumGothic",$content);
												?>
												<?=$content?>
												<br /><br /><br />
												<?
												$mem_dt = getMemberInfo("u_id",$writer);
												for ($k=0;$k<=20;$k++){
													if (strlen($k)==1){
														$kk = "0".$k;
													}else{
														$kk = $k;
													}
													if ($Arr_h_area2[$kk]==$print_cate_name){
														$area_code = $kk;
													}
												}

												// 지점정보 검색
												$SQLTEMP0 = "
													select *
													from tbl_desc_area
													where
														area_code = '".$area_code."'
													limit 0, 1
												";
												$RSTEMP0 = $dbcon -> query($SQLTEMP0);
												$area_row = $dbcon -> fetch_array($RSTEMP0);
												// 개인약력 검색
												$SQLTEMP0 = "
													select doctor_pic
													from tbl_desc_doctor
													where
														member_id = '".$writer."'
													limit 0, 1
												";
												$RSTEMP0 = $dbcon -> query($SQLTEMP0);
												$doctor_row = $dbcon -> fetch_array($RSTEMP0);
												?>
												<div class="columView">
													<div class="view">
														<div class="img" style="width:195px;height:238px;">
														<?if ($doctor_row["doctor_pic"]){?>
														<img src="/_data/doctor_pic/<?=$doctor_row["doctor_pic"]?>" alt="<?=$mem_dt["u_name"]?>원장" style="width:195px;height:238px;"/>
														<?}?>
														</div>
														<div class="txt">
															<strong>이지함 <?=$print_cate_name?> 피부과<br /><span class="fColor01"><?=$mem_dt["u_name"]?> 원장</span></strong>

															<ul>
																<li><strong>주소</strong>
																	<p style="width:160px;"><?=$area_row["addr_load"]?></p>
																</li>
																<li><strong>예약</strong>
																	<p><?=$area_row["a_tel_gate"]?></p>
																</li>
															</ul>

															<div>
																<a href="javascript: go_area_t02('<?=$area_code?>');"><img src="/images/community/cLink01.gif" alt="의료진 소개" /></a><a href="javascript: go_area_t01('<?=$area_code?>');"><img src="/images/community/cLink02.gif" alt="진료스케쥴" /></a><a href="javascript: go_area_t03('<?=$area_code?>');"><img src="/images/community/cLink03.gif" alt="병원위치" /></a>
															</div>
														</div>
													</div>
													<?
														// Top 5 검색
														$SQLTEMP0 = "
															select seq, subject, regdate, secret
															from tbl_board_column
															where
																category = '".$category."'
															order by seq desc
															limit 0, 5
														";
//														echo $SQLTEMP0;
														$RSTEMP0 = $dbcon -> query($SQLTEMP0);
													?>
													<div class="viewList">
														<strong><?=$print_cate_name?>지점칼럼 <span></span></strong>

														<ul>
															<?while ( $ROWSTEMP = $dbcon -> fetch_array($RSTEMP0) ) {
															$main_subject = "";
															$main_subject .= getStrCut($ROWSTEMP[subject], 36, "...");
															?>
															<li><img src="/images/counsel/txt_qnaTop.gif" alt="Q" /><a href="?mode=view&seq=<?=$ROWSTEMP[seq]?>"><?=$main_subject?></a></li>
															<?}?>
														</ul>

														<a href="?bc_id=column&mode=list&search_category=<?=$category?>">바로가기 &gt;</a>
													</div>
												</div>


											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>


						<dl class="prevNext mT30">
							<dt class="prev">이전글</dt>
							<dd><?=$prev_subject?></dd>
							<dt class="next">다음글</dt>
							<dd><?=$next_subject?></dd>
						</dl>

						<div class="btnArea tR">
							<a href="javascript:list_go();" class="boxTxt boxSt01">목록</a>
						</div>

			<?
			// Comment List Start
				if ($bc_comment_use == 'Y') {
					$category = "board";
					$bc_id = $bc_id;
					$seq = $seq;

					include $path_comment."index.php";
				}
			// Comment List End
			 ?>
					</div>
					<!--// 컨텐츠 내용 -->

					<?if ($area_code==''){?>
					<?include $_SERVER["DOCUMENT_ROOT"]."/html/include/board_bottom.php";?>
					<?}?>

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
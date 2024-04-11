<?
	$print_reply_image = "<img src=\"".$url_skin_board.$bc_skin."/images/reply_01.gif\">";
	if ($reply_ok == "Y") {
		if ( $reply) {
			$print_reply_image = "<img src=\"".$url_skin_board.$bc_skin."/images/reply_02.gif\">";
		}
		else {
			$print_reply_image = "<img src=\"".$url_skin_board.$bc_skin."/images/reply_03.gif\">";
		}
	}

//사용자 모드입니다
	if ($client_mode=="Y"){?>

<?
// 로그인 여부 확인
if (!$ss_u_idx){
	alert_page("로그인을 해주셔야 합니다.","/html/login/login.php?url=/html/community/post.php");
	exit;
}
?>

				<!-- InConts -->
				<div id="InConts" class="Inconts">

					<!-- 컨텐츠 내용 -->
					<div class="board">
						<div class="boardView01">
							<table>
								<caption>시술 후기 보기</caption>
								<colgroup>
									<col style="width:100px;" />
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
										<th scope="col">구분</th>
										<td colspan="3"><?=$Arr_cost_detail_list[$ext1]?></td>
										<th scope="col">등록일</th>
										<td><?=$PrintRegDate?></td>
										<th scope="col" class="bullTy04">조회수</th>
										<td>2</td>
									</tr>
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
										<th scope="col" class="bullTy04">링크</th>
										<td colspan="5">
											<?if ($ext2){?>
											<a href="<?=$ext2?>" target="_blank" class="fColor03"><img src="/images/common/icon/icon_window.gif" alt="새창" /> <?=$ext2?></a>
											<?}?>
										</td>
									</tr>
									<tr>
										<td colspan="8" class="boardCont02">
											<div>
												<?
												$content = str_replace("\r","<br/>",$content);
												$content = str_replace("\n","<br/>",$content);
												?>
												<?=$content?>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<?if ($reply){?>
						<div class="boardView03">
							<table>
								<caption>시술후기 답변</caption>
								<colgroup>
									<col style="width:9%;" />
									<col width="*" />
									<col style="width:8%;" />
									<col style="width:7%;" />
									<col style="width:8%;" />
									<col style="width:15%;" />
								</colgroup>
								<tbody>
									<tr>
										<th scope="col"><span class="iconSt04">답변</span></th>
										<td class="fColor02 bold">후기에 대한 답변입니다.</td>
										<th scope="col"><!-- 작성자 --></th>
										<td><!-- 관리자 --></td>
										<th scope="col" class="bullTy04"><!-- 등록일 --></th>
										<td><!-- 2014.02.05 16:02 --></td>
									</tr>
									<tr>
										<td colspan="6" class="boardCont02">
											<div>
												<?=nl2br(strip_tags($reply));?>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<?}?>

						<dl class="prevNext mT50">
							<dt class="prev">이전글</dt>
							<dd><?=$prev_subject?></dd>
							<dt class="next">다음글</dt>
							<dd><?=$next_subject?></dd>
						</dl>
						<div class="btnArea tR">
							<? if ( $auth_modify && !$reply && $writer==$ss_u_id) { ?><a href="javascript:mod_go('<?=$seq?>');" class="boxTxt boxSt01">수정</a><? } ?><? if ( $auth_delete && !$reply && $writer==$ss_u_id) { ?><a href="javascript:del_go('<?=$seq?>');" class="boxTxt boxSt02">삭제</a><? } ?><a href="javascript:list_go();" class="boxTxt boxSt03">목록</a>
						</div>
					</div>
					<!--// 컨텐츠 내용 -->

					<?include $_SERVER["DOCUMENT_ROOT"]."/html/include/board_bottom.php";?>

				</div>
				<!--// InConts -->

<?
//==================================================
// 관리자 모드입니다.
//==================================================
}else{?>

			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td class="m_line_2px">&nbsp;</td>
				</tr>
				<tr>
					<td>
						<table border="0" cellspacing="0" cellpadding="0" width="100%">
							<tr>
								<td class="b_txt" width="80">카테고리</td>
								<td class="b_name" width="130" align="center"><?=$print_cate_name?></td>
								<td class="b_txt" width="80">구분</td>
								<td class="b_num" width="130" align="center"><?=$Arr_cost_detail_list[$ext1]?></td>
								<td class="b_txt" width="80">BEST</td>
								<td class="b_num" width="80" align="center"><?=$ext3?></td>
								<td>&nbsp;</td>
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
								<td class="b_num" width="130" align="center"><?=$PrintRegDate?></td>
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
				<?
					// 관리자일때
					 if ( $auth_level >= $auth_admin && $auth_reply ) {
				?>
				<script>
					function reply2_go() {
						//alert(board_ifrmae);
						ff = document.replyform;
						ff.target="board_iframe";
						ff.action="<?=$url_skin_board.$bc_skin?>/reply_ok.php?bc_id=<?=$bc_id?>";
						ff.submit();
					}
				</script>
				<form name="replyform" method="post" action="">
				<input type="hidden" name="seq" value="<?=$seq?>">

				<tr>
					<td class="m_line_2px"></td>
				</tr>
				<tr>
					<td class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_rcontent.gif"></td>
				</tr>
				<tr>
					<td class="m_line_1px"></td>
				</tr>
				<tr>
					<td class="b_name" style="padding:6px 0px 0px 4px">
						<table width="100%" border="0" cellpadding="0" cellspacing="0">
							<tr>
								<td>
									<input type="radio" name="reply_ok" value="N" <? if (!$reply_ok || $reply_ok=="N") echo "checked"; ?>>미처리
									<input type="radio" name="reply_ok" value="Y" <? if ($reply_ok=="Y") echo "checked"; ?>>처리
									<font color="blue">(미처리 : <font color="red">미처리</font>, 처리+답변없음 : <font color="red">답변대기</font>, 처리+답변있음 : <font color="red">답변완료</font>)</font>
								</td>
								<td align="right">
									<a href="javascript:reply2_go();"><img  src="<?=$url_skin_board.$bc_skin?>/images/b_btn_reply.gif" align="absmiddle"></a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="b_content" valign="top" style="padding:10px 0px 10px 0px">
						<textarea name="reply" id="reply" style="width:100%;height:400px;" class="textarea"><?=REQSTR2($reply)?></textarea>
					</td>
				</tr>
				</form>
				<?}?>
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
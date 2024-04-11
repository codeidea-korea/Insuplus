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
<script type="text/javascript">
<!--
$( document ).ready( function() {
mod_go('<?=$seq?>');
});
//-->
</script>
<?}?>
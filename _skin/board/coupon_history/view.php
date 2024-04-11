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
									<?=$subject?>
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
								<td class="b_txt" width="80">추천코드</td>
								<td class="b_subtitle" width="170" style="padding-left:10px" ><?=$recommendation_code?></td>
								<td class="b_txt" width="80">등록일</td>
								<td class="b_num" width="130" align="center"><?//=$regdate?><?=substr($PrintRegDate, 0, 10)?></td>
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
						<td class="b_txt" width="80">제휴사</th>
						<td class="b_subtitle" style="padding-left:10px">
						<?while ($CateListRs = $dbcon -> fetch_array($ArrPartnerListRs[1]) ) {
								extract($CateListRs);
								if ($recom_partnership_code == $partnership_code ) echo $partnership_name; }?></td>
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
								<td class="b_txt" width="80">사용기간</td>
								<td class="b_subtitle" width="160" style="padding-left:10px">
								<?=substr($start_date, 0, 10)?> ~ <?=substr($end_date, 0, 10)?>
								</td>
								<td class="b_txt" width="80">할인율</td>
								<td class="b_subtitle" style="padding-left:10px">
									<?=$discount?>%
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
<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>


								</tbody>
							</table>
						</div>

						<!-- 페이징 -->
						<div class="paging">
							<? list_page_ljh($page, $total_page, $page_per_block); ?>
							<!--
							<div>
								<a href="#" class="boxTxt boxSt01">글쓰기</a>
							</div>
							-->
						</div>
						<!-- // 페이징 -->
					</div>
					<!--// 컨텐츠 내용 -->

				</div>
				<!--// InConts -->


<?}else{?>

			</table>
			<input type=hidden name=f_delete>
			</form>
			<!-- ### 게시판 끝 ###  -->


			<!-- ### 페이지 시작 ###  -->
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td align="center" valign="top" style="padding:10px 0 0 0">
						<? list_page($page, $total_page, $page_per_block); ?></td>
					</td>
				</tr>
			</table>
			<!-- ### 페이지 끝 ###  -->			

<?}?>
<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>


								</tbody>
							</table>
						</div>

						<!-- 페이징 -->

						<div class="list_number">
							<div class="list_n_menu">
								<? list_page_ljh($page, $total_page, $page_per_block); ?>
							</div>
						</div>
						<!-- // 페이징 -->


			<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
			<input type="hidden" name="bc_id" value="<?=$bc_id?>">
			<input type="hidden" name="mode" value="list">
			<? if ($bc_category_use == "Y") { ?>
			<input type="hidden" name="search_category" value="<?=$search_category?>">
			<? } ?>
					<!-- 검색 -->
					<div class="board_search">
						<div class="float_left" style="width:40%;">Total : <span class="f_strong"><?=$total_record?>,</span> <span class="f_strong">[<?=$page?> / <?=$total_page?>]<span> Page</div>
						<div class="float_right f_right" style="width:60%;">
								<select title="게시판 검색" class="selectSt01" style="width:88px;" name="search">
									<option value="subject" <? if ($search == "subject" ) echo "selected"; ?>>Title</option>
									<option value="content" <? if ($search == "content" ) echo "selected"; ?>>Contents</option>
									<option value="nick_name" <? if ($search == "nick_name" ) echo "selected"; ?>>Name</option>
									<option value="all" <? if ($search == "all" ) echo "selected"; ?>>All</option>
								</select>

								<input type="text" class="inpSt01" style="width:124px;" title="검색어를 입력" name="search_text" value="<?=$search_text?>"/>
								<input type="image" src="/html/_images/board/btn_search.png" alt="Search" style="margin-left:-5px;border:0px;">
						</div>
					</div>


					</div>
				</form>


<?}else{?>

			</table>
			<input type=hidden name=f_delete>
			</form>
			<!-- ### 게시판 끝 ###  -->


			<!-- ### 페이지 시작 ###  -->
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td width="70" valign="top" style="padding:11px 0 0 0"><?=$btn_list?></td>
					<td align="center" valign="top" style="padding:10px 0 0 0">
						<? list_page($page, $total_page, $page_per_block); ?></td>
					</td>
				</tr>
			</table>
			<!-- ### 페이지 끝 ###  -->

			<!-- ### 버튼 시작 ###  -->
			<? if ($ss_u_level >= $auth_admin) { ?>
			<!-- <table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td colspan="2" height="4"></td>
				</tr>
				<tr>
					<td width="116"><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_title_admin.gif"></td>
					<td width="70"><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_delete_admin.gif" onClick="checkView('del')" style="cursor:hand"></td>
					<td><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_notice_admin.gif"></td>
				</tr>
			</table> -->
			<? } ?>
			<!-- ### 버튼 끝 ###  -->

<?}?>
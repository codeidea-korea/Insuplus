<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>

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
					<td width="70" align="right" valign="top" style="padding:11px 0 0 0">
					</td>
				</tr>
			</table>
			<!-- ### 페이지 끝 ###  -->

			<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
			<input type="hidden" name="bc_id" value="<?=$bc_id?>">
			<input type="hidden" name="mode" value="list">
			<? if ($bc_category_use == "Y") { ?>
			<input type="hidden" name="search_category" value="<?=$search_category?>">
			<? } ?>

			<? if ($bc_search_use == "Y") { ?>
			<table border=0 cellspacing=0 cellpadding=0 width=100%>
				<tr>
					<td height=20></td>
				</tr>
			</table>
			<table align=center class="b_search_box">
				<tr>
					<td>
						<table cellpadding=0 cellspacing=3 border=0>
							<tr>
								<td width="4">&nbsp;</td>
								<td width="59" valign="top" style="padding:1px 0 0 0"><img src="<?=$url_skin_board.$bc_skin?>/images/b_search_txt.gif" ></td>
								<!-- 기간검색사용시 해제
									<td valign="top" style="padding:1 0 0 0">
										<select name=limit style=width:78 class=select>
											<option value=7 <?=$l7?>>최근 한주</option>
											<option value=31 <?=$l31?>>최근 한달</option>
											<option value=all <?=$la?>>전체 검색</option>
										</select>
									</td>
								-->
								<td valign="top" style="padding:1px 0 0 0">
									<select name="search" style="width:52" class="select">
										<option value="subject" <? if ($search == "subject" ) echo "selected"; ?>>제목</option>
										<option value="content" <? if ($search == "content" ) echo "selected"; ?>>내용</option>
										<option value="nick_name" <? if ($search == "nick_name" ) echo "selected"; ?>>이름</option>
										<option value="all" <? if ($search == "all" ) echo "selected"; ?>>전체</option>
									</select>
								</td>
								<td valign="top" width="101"><input type="text" name="search_text" style="width:100px" maxlength="30" value="<?=$search_text?>" class="input"></td>
								<td valign="top" style="padding:1px 0 0 0"><input type="image" src="<?=$url_skin_board.$bc_skin?>/images/b_btn_search.gif"></td>
								<td width="4">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<? } ?>

			</form>

<?}?>
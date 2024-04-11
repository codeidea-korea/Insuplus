<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>
		<?
			// 공지사항 출력
			if ($list_type == "notice") {
		?>
		<? }elseif ($list_type == "list") { ?>
								<?
								// 의료진 정보 검색 - 정보에 ID를 검출하여 검색
								$arr_doctor_detail = explode("@",$writer);
								// 의료진 사진 가져오기
								$mem_dt = getMemberInfo("u_id",$arr_doctor_detail[0]);
								if ($mem_dt["u_image"]){
									$col_img = "/_data/member/".$mem_dt["u_image"];
								}else{
									$col_img = "/images/board/test_img.jpg";
								}
								if ($ext1){
									$sub_content = $ext1;
								}else{
									$sub_content = getStrCut(strip_tags($content),120,"..");
								}
								?>
								<li><div class="img"><img src="<?=$col_img?>" alt="<?=$nick_name?>원장" style="width:118px;height:93px;"/></div>
									<?=str_replace(">","class=\"txt\">",$view_link)?>
										<span><span class="iconSt03"><?=$print_cate_name?></span>
											<strong><?=$re?><?=$subject?></strong>
											<img src="/images/common/icon/icon_h.gif" alt="h" />
										</span>

										<em><?=$sub_content?><?=$list[ext1]?></em>

										<span class="day">
											<em><?=$nick_name?> 원장</em>
											<span><?=$PrintRegDate?></span>
										</span>
									</a>
											<!--<img src="/images/common/icon/icon_file.gif" alt="첨부파일" />-->
											<!-- <?=str_replace(">","class=\"txt\">",$view_onlink)?><img src="/images/common/icon/icon_window.gif" alt="새창" /></a> -->
								</li>
		<? }elseif ($list_type == "null") { ?>
								<li style="width:100%;text-align:center;">등록 된 데이터가 없습니다.</li>
		<? }else{ ?>
		.
		<? } ?>

<?}else{?>

		<?
			// 공지사항 출력
			if ($list_type == "notice") {
		?>
			<tr bgcolor="#F3F3F3">
				<? if ($ss_u_level >= $auth_admin) { ?>
				<!-- <td align="center"><input type="checkbox" name="view_check[]" value="<?=$num?>"></td> -->
				<? } ?>
				<td align="center" height="25" class="b_num"><?=$img_notice?></td>
				<? if ($bc_category_use == "Y") { ?>
				<td align="center" width="70"  class="b_subtitle"><?//=$print_cate_name?></td>
				<? } ?>
				<td class="b_subtitle"><?=$view_link?><?=$re?><?=$subject?> <?=$print_cmt_cnt?> <?=$print_secret?> <?=$sNew?> <?=$print_hidden?></a></td>

				<? if ( $bc_upfile_cnt > 0 ) { ?>
				<td align="center" class="b_name"><?=$print_file?></td>
				<? } ?>

				<td align="center" class="b_name"><?=$nick_name?></td>
				<td align="center" class="b_num"><?=$PrintRegDate?></td>
				<td align="center" class="b_num"><?=$view_cnt?></td>
			</tr>
			<tr>
				<td colspan="7" class="m_line_1px">&nbsp;</td>
			</tr>

		<? }elseif ($list_type == "list") { ?>
			<tr>
				<? if ($ss_u_level >= $auth_admin) { ?>
				<!-- <td align="center"><input type="checkbox" name="view_check[]" value="<?=$num?>"></td> -->
				<? } ?>
				<td align="center" height="25" class="b_num"><?=$no?></td>
				<? if ($bc_category_use == "Y") { ?>
				<td align="center" width="70"  class="b_subtitle"><?=$print_cate_name?></td>
				<? } ?>
				<td class="b_subtitle"><?=$view_link?><?=$re?><?=$subject?> <?=$print_cmt_cnt?> <?=$print_secret?> <?=$sNew?> <?=$print_hidden?></a>
				</td>

				<? if ( $bc_upfile_cnt > 0 ) { ?>
				<td align="center" class="b_name"><?=$print_file?></td>
				<? } ?>

				<td align="center" class="b_name"><?=$nick_name?></td>
				<td align="center" class="b_num"><?=$PrintRegDate?></td>
				<td align="center" class="b_num"><?=$view_cnt?></td>
			</tr>
			<tr>
				<td colspan="7" class="m_line_1px">&nbsp;</td>
			</tr>
		<? }elseif ($list_type == "null") { ?>
			<tr>
				<td height="25" align="center" colspan="30" class="b_name">등록 된 데이터가 없습니다.</td>
			</tr>
			<tr>
				<td colspan="7" class="m_line_1px">&nbsp;</td>
			</tr>
		<? }else{ ?>
		.
		<? } ?>

<?}?>
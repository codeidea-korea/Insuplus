<?
//사용자 모드입니다
	if ($client_mode=="Y"){?>

<? }else{ ?>
<table class="adm-view-tb">
<colgroup>
	<col width="8%" />
	<col width="42%" />
	<col width="8%" />
	<col width="42%" />
</colgroup>
<tr>
	<th>제휴사명</th>
	<td><?=$partnership_name?></td>
	<th>구분</th>
	<td class="product-category"><?=$ext1?></td>
</tr>
<tr>
	<th>제휴코드</th>
	<td><?=$partnership_code?></td>
	<th>등록일</th>
	<td><?=$PrintRegDate?></td>
</tr>
<tr>
	<th>제휴기간</th>
	<td><?=substr($start_Partner_period, 0, 10)?> ~ <?=substr($end_Partner_period, 0, 10)?></td>
	<th>제휴 수수료</th>
	<td><?=$partnership_charge?>%</td>
</tr>
<tr>
	<th>트레킹 url</th>
	<td colspan="3"><?=$tracking_url?></td>
</tr>
<tr>
	<th>제휴사 Logo</th>
	<td colspan="3">
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

						echo "<a href=\"javascript:showPicture('".$upload_url."/".${"Arr_".$ObjFileName}[$i][1]."')\"><img src=\"".$upload_url."/".${"Arr_".$ObjFileName}[$i][1]."\" width=\"200\"></a>";

						break;
					}
				}
			}
		?>

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
		<td>
			<? include $path_comment."index.php";?>
		</td>
	</tr>
	<!-- comments End -->
<? } ?>
</table>

<? if($bc_prev_next == "Y") { // 다음글 이전글 허용시 ?>
<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td height="20" colspan="3"></td>
	</tr>
	<tr>
		<td colspan="3" class="m_line_1px"></td>
	</tr>
	<tr>
		<td width="80"  class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_prev.gif"></td>
		<td width="2"></td>
		<td class="b_nextprev">&nbsp;&nbsp;&nbsp;<?=$prev_subject?></td>
	</tr>
	<tr>
		<td colspan="3" class="m_line_1px"></td>
	</tr>
	<tr>
		<td width="80"  class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_next.gif"></td>
		<td width="2"></td>
		<td class="b_nextprev">&nbsp;&nbsp;&nbsp;<?=$next_subject?></td>
	</tr>
	<tr>
		<td colspan="3" class="m_line_1px"></td>
	</tr>
</table>
<? } ?>
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
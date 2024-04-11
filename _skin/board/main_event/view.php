<? if ($client_mode=="Y"){ //사용자?>

<? }else{ //관리자?>
	<table class="adm-view-tb">
		<colgroup>
		<col width="12%">
		<col width="38%">
		<col width="12%">
		<col width="38%">
		</colgroup>
		<tr>
			<th>제목</th>
			<td colspan="3"><?=$subject?></td>
		</tr>
		<tr>
			<th>작성일</th>
			<td><?=$PrintRegDate?></td>
			<th>조회수</th>
			<td><?=$view_cnt?></td>
		</tr>
		<tr>
			<th>이미지</th>
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
			<tr>
				<th>URL</th>
				<td colspan="3"><?=$pc_url;?></td>
			</tr>
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

<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

<?
##########################################################################
### 관리자 모드입니다
##########################################################################
}else{?>
<script type="text/javascript">
 	function submitCheck() {
		<? if ( $mode !="mod" ) { ?>
		if($('#duplicate_yn').val() == 'N' || !$('#recommendation_code').val()) {
			alert('추천코드를 입력한 뒤 중복체크를 해주세요.');
			$('#recommendation_code').focus();
			return false;
		 } 
		<? } ?>
		 
		 if(!$('#start_date').val()) {
			alert('사용기간을 입력 해주세요.');
			$('#start_date').focus();
			return false;
		 }else if(!$('#end_date').val()) {
			alert('사용기간을 입력 해주세요.');
			$('#end_date').focus();
			return false;
		 } else if(!$('#discount').val()) {
			alert('할인율을 입력 해주세요.');
			$('#discount').focus();
			return false;
		 }

		WriteOkGo();
	 }

 	function duplicateCheck(){ 
		var code = $('#recommendation_code').val();
		if(!code){
			alert('추천코드를 입력 해주세요.');
			$('#recommendation_code').focus();
			return false;
		}
		$.ajax({ type: "POST", url: "/_skin/board/recommend_code/duplicate_check.php",
		data: {bc_id : "<?=$bc_id?>", recommendation_code : code, },
		cache: false, 
		success: function(data){
			var flag = $.trim(data);
			if(flag >= 1){
				alert('사용 불가능한 코드입니다.');
				$('#recommendation_code').focus();
				return false;
			} else {
				alert('사용 가능한 코드입니다.');
				$('#duplicate_yn').val('Y');
				return false;
			}
		}
		});
	}
</script>
    <form name="WriteForm" action="" method="post" enctype="multipart/form-data" onsubmit="return submitCheck()">
        <input type="hidden" name="bc_id" value="<?=$bc_id
?>">
<input type="hidden" name="page" value="<?=$page?>">
<input type="hidden" name="search_category" value="<?=$search_category?>">
<input type="hidden" name="search" value="<?=$search?>">
<input type="hidden" name="search_text" value="<?=$search_text?>">
<input type="hidden" name="act" value="ok">
<input type="hidden" name="mode" value="">
<input type="hidden" name="seq" value="<?=$seq?>">
<input type="hidden" name="seq_sub" value="<?=$seq_sub?>">
<input type="hidden" name="seq_level" value="<?=$seq_level?>">
<input type="hidden" name="content" value=".">
<input type="hidden" id="duplicate_yn" value="N">

<input type="hidden" name="writer" value="<?=$writer?>">
<input type="hidden" name="nick_name" value="<?=$nick_name?>"/>
 <!-- (s) 관리자 상세화면  -->
<table class="adm-view-tb">
	<colgroup>
		<col width="8%">
		<col width="42%">
		<col width="8%">
		<col width="42%">
	</colgroup>
	<tr>
		<th>제목</th>
		<td colspan="3">
			<? $subject = REQSTR2($subject); ?>
			<input type="text" name="subject" value="<?=$subject?>" class="w100p"/>
		</td>
	</tr>	
	<tr>
		
	<? if ( $mode =="mod" ) { ?>
		<th>추천코드</th>
		<td>
			<input type="text" id="recommendation_code" name="recommendation_code" value="<?=$recommendation_code?>" style="width:95px">
			<input type="button" id="duplicate_check" value="중복체크" onClick="duplicateCheck();">
		</td>			
		<th>등록일</th>
		<td><?=$PrintRegDate?></td>
		<? } else {?>
			<th>추천코드</th>
		<td colspan="3">
			<input type="text" id="recommendation_code" name="recommendation_code" value="<?=$recommendation_code?>" style="width:95px">
			<input type="button" id="duplicate_check" value="중복체크" onClick="duplicateCheck();">
		</td>	
		<?} ?>
	</tr>
	<tr>
		<th>제휴사</th>
		<td><select title="제휴사 선택" id="recom_partnership_code" class="selectSt01" name="recom_partnership_code">
			<?while ($CateListRs = $dbcon -> fetch_array($ArrPartnerListRs[1]) ) {
				extract($CateListRs);?>
			<option value="<?=$partnership_code?>" <? if ($recom_partnership_code == $partnership_code ) echo "selected"; ?>><?=$partnership_name?></option>
			<?}?>
			</select>
		</td>
	</tr>
	<tr>
		<th>사용기간</th>
		<td>
			<input type="text" id="start_date" name="start_date" value="<?=substr($start_date, 0, 10)?>" style="width:100px" <?=$ClassCalendar?>>
			<span style="padding-left: 5px; padding-right: 5px;">~</span>
			<input type="text" id="end_date" name="end_date" value="<?=substr($end_date, 0, 10)?>" style="width:100px" <?=$ClassCalendar?>>
		</td>
		<th>할인율</th>
		<td>
			<input type="number" id="discount" name="discount" value="<?=$discount?>" style="width:55px">%
		</td>
	</tr>
		
</table>

<!-- (s) 하단  버튼 영역 -->
<div class="btnWrap">
	<div class="leftWrap">
		<a href="javascript:<? if ($mode == "mod") {echo "view_go('".$seq."');";} else {echo "list_go();";} ?>" class="btn_list">목록</a>
	</div>
	<div class="rightWrap">
		<input type="submit" value="등록" class="btn_add"/>
	</div>
</div>
<!-- (e) 하단  버튼 영역 -->
</form>
<?}?>

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
     <? if($mode != "mod") { ?>
       if($('#duplicate_yn').val() == 'N' || !$('#partnership_code').val()) {
        alert('제휴코드를 입력한 뒤 중복체크를 해주세요.');
        $('#partnership_code').focus();
        return false;
       }
     <? } ?>
     
     if(!$('#start_Partner_period').val()) {
      alert('제휴기간을 입력 해주세요.');
      $('#start_Partner_period').focus();
      return false;
     }
     
     if(!$('#end_Partner_period').val()) {
      alert('제휴기간을 입력 해주세요.');
      $('#end_Partner_period').focus();
      return false;
     }

     if(!$('#partnership_charge').val()) {
      alert('제휴수수료를 입력 해주세요.');
      $('#partnership_charge').focus();
      return false;
     }

    WriteOkGo();
   }

  function duplicateCheck(){ 
    var code = $('#partnership_code').val();
    if(!code){
      alert('제휴코드를 입력 해주세요.');
      $('#partnership_code').focus();
      return false;
    }
    $.ajax({ type: "POST", url: "/_skin/board/partner/duplicate_check.php",
    data: {bc_id : "<?=$bc_id?>", partnership_code : code, },
    cache: false, 
    success: function(data){
      var flag = $.trim(data);
      if(flag >= 1){
        alert('사용 불가능한 코드입니다.');
        $('#partnership_code').focus();
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
<input type="hidden" name="bc_id" value="<?=$bc_id?>">
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
<input type="hidden" name="subject" value=".">
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
    <th>제휴사명</th>
    <td >
      <? $partnership_name = REQSTR2($partnership_name); ?>
      <input type="text" name="partnership_name" value="<?=$partnership_name?>" class="w100p"/>
    </td>
    <th>구분</th>
    <td>
      <select name="ext1"></select>
      <script defer>
        { // 변수 사용범위 제약을 위해 {} 사용
          let ext1 = `<?= $ext1 ?>`;
          let html = PAGE_PRD_CAT.map(item=>`<option value="${item.category_code}">${item.category_name}</option>`);
          html.unshift(`<option value="">선택</option>`);
          document.querySelector('select[name=ext1]').innerHTML = html.join('');
          if (ext1){
            document.querySelector('select[name=ext1]').value = ext1;
          }
        }
      </script>
    </td>
  </tr>  
  <tr>  
    <th>제휴코드</th>
    <td>
      <? if($mode == "mod") {?>
        <?=$partnership_code?>
      <? } else { ?>
        <input type="text" id="partnership_code" name="partnership_code" value="<?=$partnership_code?>" class="w100">
        <input type="button" id="duplicate_check" value="중복체크" onClick="duplicateCheck();">
      <? } ?>
    </td>      
    <th>등록일</th>
    <td><?=$PrintRegDate?></td>
  </tr>
  
  <tr>
    <th>제휴기간</th>
    <td>
      <input type="text" id="start_Partner_period" name="start_Partner_period" value="<?=substr($start_Partner_period, 0, 10)?>" class="w100 datepicker">
      <span style="padding-left: 5px; padding-right: 5px;">~</span>
      <input type="text" id="end_Partner_period" name="end_Partner_period" value="<?=substr($end_Partner_period, 0, 10)?>" class="w100 datepicker">
    </td>
    <th>제휴수수료</th>
    <td>
      <input type="number" id="partnership_charge" name="partnership_charge" value="<?=$partnership_charge?>" class="w100">
    </td>
  </tr>
  <? if ( $mode =="mod" ) { ?>
  <tr>
    <th>트레킹 url</th>
    <td colspan="3"><?=$tracking_url;?></td>
  </tr>
  <? } else {?>
  <tr>
    <th>트레킹 url</th>
    <td colspan="3"><span class="txt_red">※ 등록 후 트레킹 URL이 생성됩니다.</span></td>
  </tr>
  <?} ?>
  <?
    if ( $bc_upfile_image == "Y") {
	?>
	<?
        $ObjFileName = "imgfile";
	?>
	<tr>
		<th>
			제휴사 Logo<br/>
			<span class="txt_red">(150 x 32)</span>
		</td>
		<td class="m_content">
			<table class="fileTb" id="Tbl<?=$ObjFileName?>" width="100%" cellpadding=0 cellspacing=0></table>
			<?
	        //echo $imgfile."<BR>";
	        $ObjFileName = "imgfile";
	        if ( getLen($seq) > 0 && getLen($$ObjFileName) > 0 ) {
	            ${
	                "Arr_".$ObjFileName}
	            = setFileName($$ObjFileName);
	            for ( $i = 0 ; $i < count(${"Arr_".$ObjFileName}); $i++) {
				?>
				<script>add_file('Tbl<?=$ObjFileName?>', <?=$bc_upfile_image_cnt?>, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" disabled> 삭제 <input type="checkbox" name="<?=$ObjFileName?>_del[]" value="1" onclick="UseFile(this, \'<?=$ObjFileName?>\', \'<?=$i?>\')"> , 등록파일 : <?=${"Arr_".$ObjFileName}[$i][0]?> <?=PrintFileSize(${"Arr_".$ObjFileName}[$i][2])?>');</script>
				<?
	            }
	        }
	        // 신규 파일 등록
	        else {
				?>
				<script>add_file('Tbl<?=$ObjFileName?>', <?=$bc_upfile_image_cnt?>, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" class="a_input">');</script>
				<?
	        }
			?>
			<img id="<?=$ObjFileName?>" width="0" height="0">
		</td>
	</tr>
  <? } ?>
  
</table>

<!-- (s) 하단  버튼 영역 -->
<div class="btnWrap">
  <div class="leftWrap">
    <a href="javascript:<? if ($mode == "mod") {echo "view_go('".$seq."');";} else {echo "list_go();";} ?>" class="btn_list">목록</a>
  </div>
  <div class="rightWrap">
    <? if ( $mode =="mod" ) { ?>
      <input type="submit" value="수정" class="btn_add"/>
    <? } else {?>
      <input type="submit" value="등록" class="btn_add"/>
    <? } ?>
  </div>
</div>
<!-- (e) 하단  버튼 영역 -->
</form>
<?}?>

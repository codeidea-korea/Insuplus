<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

<?}else{
  
  $SQL_cate = "select seq,subject from tbl_board_product  ";
  $RS_PR = $dbcon -> query($SQL_cate);

  $PRD_CAT = getProductCatetories();
  $PRD_CAT_MAPPING = getProductCatetoryMapping($seq);
?>

<script>
///////////////////////////////////////////////////////////////////////////////
// 2023-06-27 added by kyle
///////////////////////////////////////////////////////////////////////////////

const PAGE_PRD_CAT = JSON.parse('<?= json_encode($PRD_CAT) ?>');
const PAGE_PRD_CAT_MAPPING = JSON.parse('<?= json_encode($PRD_CAT_MAPPING) ?>');

function generate_select(selector, list){
  let html=[];
	let title = document.querySelector(selector).getAttribute('placeholder');

  if (Array.isArray(list)){
    html = list.map(item => `<option value="${item.category_code}">${item.category_name}</option>`);
  }

  html.unshift(`<option value="">${title}</option>`);
  document.querySelector(selector).innerHTML = html.join('');
}

function init_depts(){
  let categories = PAGE_PRD_CAT.filter(item => item.depth === '0');

  generate_select('#category_depth0', categories);
}

function change_depths(event){
  const val = event.currentTarget.value;
  const {textContent} = [].find.call(event.currentTarget.children, (item) => item.selected);
	const depth = Number(event.currentTarget.dataset.depth);
  const categories = PAGE_PRD_CAT.filter(item => item.parent_code === val);

	generate_select(`#category_depth${depth+1}`, categories);
	
	switch (depth) {
		case 0:
			generate_select('#category_depth2', null);
		case 1:
      // depth 가 0 인경우 필터링
      depth === 1 && change_ext1(textContent);
			generate_select('#category_depth3', null);
	}
}

/**
 * 구분1 의 값 장기/단기에 따라 보험구분을 변경
 * 구분1 의 코드 값으로 판단할 경우 제휴사에 따라 코드값이 변경되기 때문에 텍스트로 판별
 * 때문에 구분1 의 텍스트는 항상 장기/단기로 한정해야 함
 */
function change_ext1(gubun_name){
  const val = gubun_name.indexOf('단기') > -1 ? 'Y': gubun_name.indexOf('장기') > -1 ? 'N' : '';
  const el = document.querySelectorAll('input[name=ext1]');

  el.forEach(item=>{
    item.checked = false;
    if (item.value === val){
      item.checked = true;
    }
  })
}

window.addEventListener('load', ()=>{
  init_depts();

  document.querySelector('#category_depth0').addEventListener('change', change_depths);
  document.querySelector('#category_depth1').addEventListener('change', change_depths);
  document.querySelector('#category_depth2').addEventListener('change', change_depths);

  if (Array.isArray(PAGE_PRD_CAT_MAPPING) && PAGE_PRD_CAT_MAPPING.length > 0){
    let currentCategory;

		[0,1,2,3].forEach(idx=>{
			if(currentCategory = PAGE_PRD_CAT_MAPPING.find(item=>item.depth === String(idx))){
				document.querySelector(`#category_depth${idx}`).value = currentCategory.category_code;
				document.querySelector(`#category_depth${idx}`).dispatchEvent(new Event('change'));
			}
		})
  }
});

///////////////////////////////////////////////////////////////////////////////
</script>

<!-- WriteOkGo function definition in  _util\board\write.php file -->
<form name="WriteForm" action="" method="post" enctype="multipart/form-data" onsubmit="return WriteOkGo()">
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
<input type="hidden" name="nick_name" value="<?=$nick_name?>" maxlength="20"  size="20" class="input">

<!-- ### 게시판 시작 ###  -->
<p class="tit_sub">1. 상품정보</p>
<table class="adm-view-tb">
  <colgroup>
  <col width="8%">
  <col width="42%">
  <col width="8%">
  <col width="42%">
  </colgroup>
  <tr>
    <th>상품명</th>
    <td>
      <? $subject = REQSTR2($subject); ?>
      <input type="text" name="subject" value="<?=$subject?>" class="w100p"/>
      <? if ( $bc_notice_use == "Y" && $auth_notice ) { ?>
      <p><input type="checkbox" name="notice" value="Y" <?= $notice == "Y" ? "checked":"" ?>/> <span class="txt_red">공지글로 등록합니다.</span></p>
      <? } ?>
      <?  if ( $bc_hidden_use == "Y" && $auth_hidden) { ?>
      <p><input type="checkbox" name="hidden" value="Y" <?= $hidden == "Y" ? "checked":"" ?>/> <span class="txt_red">해당글을 숨김니다.</span></p>
      <? } ?>
    </td>
    <th>카테고리</th>
    <td>
        <select name="category_depth0" id="category_depth0" data-depth="0" placeholder="지역 선택">
        <option value="">지역 선택</option>
        </select>
        <select name="category_depth1" id="category_depth1" data-depth="1" style="margin-left:10px" placeholder="구분1 선택">
        <option value="">구분1 선택</option>
        </select>
        <select name="category_depth2" id="category_depth2" data-depth="2" style="margin-left:10px" placeholder="구분2 선택">
        <option value="">구분2 선택</option>
        </select>
        <select name="category_depth3" id="category_depth3" data-depth="3" style="margin-left:10px" placeholder="구분3 선택">
        <option value="">구분3 선택</option>
        </select>
        <p style="color:red;margin-top:10px;"> * 플랜요금이 장기/단기에 따라 달라지기 때문에 <span style="font-weight:bold;">구분1</span>은 무조건 장기/단기 만 등록이 가능 함</p>
    </td>
  </tr>
  <tr>
    <th><del>보험구분<del></th>
    <td>
      <input type="radio" name="ext1" value="Y" <?= $ext1=="Y" ? "checked":"" ?>> <del>단기여행</del>
      <input type="radio" name="ext1" value="N" <?= $ext1=="N" || !$ext1 ? "checked":"" ?>> <del>장기여행</del>
    </td>
    <th><del>출국목적</del></td>
    <td><input type="text" name="ext7" value="<?=$ext7?>" maxlength="150"></td>
  </tr>

  <tr>
    <th><del>GNB 노출여부</del></th>
    <td>
      <input type="radio" name="ext2" value="Y" <?= $ext2=="Y" ? "checked":"" ?>> <del>노출</del>
      <input type="radio" name="ext2" value="N" <?= $ext2=="N" ? "checked":"" ?>> <del>비노출</del>
    </td>
    <th><del>노출순서</del></th>
    <td><input type="text" name="sort_order" value="<?=$sort_order?>" class="numberonly"></td>
  </tr>

  <tr>
    <th>알릴사항 노출여부</th>
    <td>
      <input type="radio" name="ext3" value="Y" <?= $ext3=="Y" ? "checked":"" ?>> 노출
      <input type="radio" name="ext3" value="N" <?= $ext3=="N" ? "checked":"" ?>> 비노출
    </td>
    <th>상품가격 산출 기준</th>
    <td>
      <input type="text" name="ext8" value="<?=$ext8?>" />
    </td>
  </tr>
  <tr>
    <th>영문 가입증명서<br/>플랜명</th>
    <td colspan="3"><input type="text" name="ext9" value="<?=$ext9?>" class="w100p"/></td>
  <th>
  <tr>
    <th>소개</th>
    <td colspan="3">
      <textarea name="content" id="content" style="width:100%;height:100px;" class="textarea"><?=$content?></textarea>
    </td>
  </tr>
  <tr>
    <th>상품가입 안내</th>
    <td colspan="3">
      <textarea name="ext6" id="ext6" style="width:100%;height:100px" class="textarea"><?=$ext6?></textarea>
    </td>
  </tr>
  <tr>
    <th>해외거주 알릴사항 공개/비공개</th>
    <td colspan="3">
      <input type="radio" name="is_notification_visible" value="N" <?=$is_notification_visible?> <?= $is_notification_visible == "N" || $is_notification_visible == "" ? "checked":"" ?> >비공개
      <input type="radio" name="is_notification_visible" value="Y" <?=$is_notification_visible?> <?= $is_notification_visible == "Y" ? "checked":"" ?> > 공개
    </td>
  </tr>
  <?if ( $bc_secret_use == "Y" && $auth_secret ) {?>
  <tr>
    <th>공개/비공개</th>
    <td colspan="3">
      <input type="radio" name="secret" value="N" <?=$secret?> <?= $secret == "N" || $secret == "" ? "checked":"" ?> >비공개
      <input type="radio" name="secret" value="Y" <?=$secret?> <?= $secret == "Y" ? "checked":"" ?> > 공개
    </td>
  </tr>

  <?}?>
  <?if ( $bc_upfile_image == "Y") {?>
  <?$ObjFileName = "imgfile";?>
  <tr>
    <th><del>배너 이미지</del><br/><span class="txt_red">(1920 x 192)</span></th>
    <td colspan="3" class="fileTb">
      <table id="Tbl<?=$ObjFileName?>" class="fileTb"></table>
      <?
        //echo $imgfile."<BR>";
        $ObjFileName = "imgfile";
        if ( getLen($seq) > 0 && getLen($$ObjFileName) > 0 ) {
          ${
            "Arr_".$ObjFileName}
          = setFileName($$ObjFileName);
          for ( $i = 0 ; $i < count(${"Arr_".$ObjFileName}); $i++) {
          ?>
          <script>add_file('Tbl<?=$ObjFileName?>', <?=$bc_upfile_image_cnt?>, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" class="a_input" disabled> 삭제 <input type="checkbox" name="<?=$ObjFileName?>_del[]" value="1" onclick="UseFile(this, \'<?=$ObjFileName?>\', \'<?=$i?>\')"> , 등록파일 : <?=${"Arr_".$ObjFileName}[$i][0]?> <?=PrintFileSize(${"Arr_".$ObjFileName}[$i][2])?>');</script>
          <?
          }
        }
        // 신규 파일 등록
        else {
      ?>
      <script>add_file('Tbl<?=$ObjFileName?>', <?=$bc_upfile_image_cnt?>, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" class="a_input">');</script>
      <?}?>
      <img id="<?=$ObjFileName?>" width="0" height="0">
    </td>
  </tr>

  <?}?>


  <?

    if ( $bc_upfile_cnt > 0 && $auth_upload ) { 
  ?>
  <tr>
    <td class="b_txt_w">
      약관파일
      <?
        if ( $bc_upfile_cnt > 1) {
      ?>
      <img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_fileadd.gif" onClick="return AddFile('DivFile');" alt="파일첨부 갯수 늘리기" style="cursor:hand">
      <!-- <img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_filedel.gif" onClick="return removeList('<?=$file_cnt?>');" alt="파일첨부 갯수 줄이기" style="cursor:hand"> -->
      <?
        }
      ?>
    </td>
    <td>
      <table border="0" cellpadding="0" cellspacing="0" width="100%" name="DivFile" id="DivFile">
        <script>
        function UseUpfile(Obj, idx) {

            TargObj = document.getElementsByName("upfile[]");

            if (Obj.checked == true) {
                TargObj[idx].disabled = false;
            }
            else {
                TargObj[idx].disabled = true;
            }
        }
        </script>
        <?
        $FileNo = 0;
        if ( $mode == "mod" && getLen($seq) > 0) {

            // #### 첨부파일 처리
            $SQL = "
            select *
            from tbl_file
            where
            category='board' and bc_id = '".$bc_id."' and seq = '".$seq."'
            ";
            $FileRs = $dbcon -> query($SQL);
            while($RowFileRs = $dbcon -> fetch_row($FileRs) ) {
                $FC_idx            = $RowFileRs[0];
                $FC_category        = $RowFileRs[1];
                $FC_bc_id          = $RowFileRs[2];
                $FC_seq            = $RowFileRs[3];
                $FC_file_name        = $RowFileRs[4];
                $FC_file_realname      = $RowFileRs[5];
                $FC_file_size        = $RowFileRs[6];
                $FC_regdate        = $RowFileRs[7];
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
        ?>
        <tr>
          <td class="m_content">
            <input type='file' name='upfile[]' class='input' style='width:100%' <? if ( getLen($FC_idx) > 0) { echo "disabled";}?>>
            <?
                if ( getLen($FC_idx) > 0) {
            ?>
            <BR>
            <!-- <input type="hidden" name="old_upfile[]" value="<?=$FC_idx?>"> -->
            현재 등록된 파일 : <?=$FC_file_name?>
                    <?=$FC_file_size
            ?>
            삭제 : <input type="checkbox" name="del_upfile[]" value="<?=$FC_idx?>" onclick="UseUpfile(this, '<?=$FileNo?>');">
            <?
                }
            ?>
          </td>
        </tr>
        <?
                $FileNo++;
            }

            if ( $FileNo == 0) {
        ?>
        <tr>
          <td class="m_content">
            <input type='file' name='upfile[]' class='input' style='width:100%'>
          </td>
        </tr>
        <?
            }
        }
        else {
        ?>
        <tr>
          <td class="m_content">
            <input type='file' name='upfile[]' class='input' style='width:100%'>
          </td>
        </tr>
        <?
        }
        ?>

      </table>
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td class="m_content">
            <span class="m_content_txt">
            업로드 확장자 제한 : <?=$bc_upfile_ext_upload?>,
            업로드 파일크기 제한 : <?=$bc_upfile_size?>
            </span>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td colspan="2" class="m_line_2px">&nbsp;</td>
  </tr>
  <script>
  num = <?=$FileNo+1?>;
  function AddFile(ObjDivName)
  {
      if ( num >= <?=$bc_upfile_cnt?> ) {
          alert("첨부파일은 <?=$bc_upfile_cnt?>개 까지만 가능합니다.");
          return;
      }

      var objTbody, objRow, objCell;

      objTbody = document.getElementById(ObjDivName);
      objRow = objTbody.insertRow(objTbody.rows.length);
      objCell = objRow.insertCell(0);
      objCell.className = "m_content";
      objCell.innerHTML+="<tr><td><input type='file' name='upfile[]' class='input' style='width:100%'></td></tr>";
      num++;
  }
  </script>
  <?
    }
 
  ?>

  <?
    if ($bc_autoreg_use == "Y") {
  ?>
  <tr>
    <td class="b_txt_w"></td>
    <td class="m_content">
      <img src="<?=$url_signup?>" id="signupimage"<?/* onclick="change_signup()" style="cursor:hand();"*/?>>
      <input type="text" name="signupcode" class='input'>
      <BR>
      자동등록 방지를 위해 이미지에 보이는 글자를 입력하여 주십시오.
    </td>
  </tr>

  <script>
  function change_signup() {
      <?/*
        //alert(document.images['signupimage']);
        //document.images['signupimage'].src="<?=$url_signup?>";
        // 추후 클릭시 이미지를 변경하는 방식으로 변경할것
      */?>
  }
  </script>
  <?
    }
  ?>
</table>


<?

// 보험사 가져오기
$SQL_BCate = "select seq,subject from tbl_board_ins_list  ";
$RS_BCate = $dbcon -> query($SQL_BCate);
$RS_BCate1 = $dbcon -> query($SQL_BCate);
$Bcate_array = array();

while ($CateListRs = $dbcon -> fetch_array($RS_BCate1) ) {
  //$Bcate_array["seq"][] = $CateListRs["seq"];
  //$Bcate_array["subject"][] = $CateListRs["subject"];
  $Bcate_array[] = $CateListRs;
}


// 보장내역 가져오기
if ($ext4){
  $SQL_GUA = "select * from tbl_board_guarantee where seq=".$ext4." ";
  $RS_GUA = $dbcon -> query($SQL_GUA);
  $GUA = $dbcon -> fetch_array($RS_GUA);
  $ext4_txt = $GUA["subject"];
}
if ($ext10){
  $SQL_GUA = "select * from tbl_board_guarantee where seq=".$ext10." ";
  $RS_GUA = $dbcon -> query($SQL_GUA);
  $GUA = $dbcon -> fetch_array($RS_GUA);
  $ext10_txt = $GUA["subject"];
}

// 인슈플러스 가져오기
if ($ext5){
  $SQL_Service = "select * from tbl_board_insuplus where seq=".$ext5." ";
  $RS_Service = $dbcon -> query($SQL_Service);
  $Service = $dbcon -> fetch_array($RS_Service);
  $ext5_txt = $Service["subject"];
}

?>
<script type="text/javascript">

</script>

<p class="tit_sub mt40"><del>2. 보험사 및 서비스 등록</del></p>
<table id="service_tb" class="adm-list-tb">
  <colgroup>
    <col width="10%">
    <col width="20%">
    <col width="*">
    <col width="10%">
  </colgroup>
  <tr>
    <th>번호</th>
    <th>보험사</th>
    <th>서비스</th>
    <th>관리<br/><em class="inp_black1"><input type="button" value="추가" id="addTR"></em></th>
  </tr>
  <?
  $k = 0;

  $SQL_O1 = "select * from tbl_board_product_service where pr_seq='".$seq."' ";
  $RS_O1 = $dbcon -> query($SQL_O1);

  while($rows_o1 = $dbcon -> fetch_array($RS_O1)){
    
  ?>
  <tr>
    <td><?=$k+1?></td>
    <td>
      <select name='ins_seq[<?=$k?>]' class='select'>
            <option value='00'>:: 없음 ::</option>
      <? foreach($Bcate_array as $Bcate_row) { ?>
        <option value='<?=$Bcate_row["seq"]?>' <?= (trim($Bcate_row["seq"][$C])==trim($rows_o1["ins_seq"])) ? "selected":"" ?>><?=$Bcate_row["subject"]?></option>
      <?}?>
      </select>
    </td>
    <td>
      <input type='radio' name='service_gubun[<?=$k?>]' value='A' <?= "A"==$rows_o1["service_gubun"] ? "checked":"" ?> >인슈플러스A
      <input type='radio' name='service_gubun[<?=$k?>]' value='B' <?= "B"==$rows_o1["service_gubun"] ? "checked":"" ?> >인슈플러스B
      <?
        $SQL_CMN_CD = "select cd_nm, cd_val1 from safety_training.fd_cmn_cd where grp_cd = 'CC13' order by ord ASC";
        $RS_CMN_CD = $dbcon -> query($SQL_CMN_CD);
        while($rows_cd = $dbcon -> fetch_array($RS_CMN_CD)){
      ?>
      <input type='radio' name='service_gubun[<?=$k?>]' value='<?= $rows_cd["cd_val1"]?>' <?= ($rows_cd["cd_val1"]==$rows_o1["service_gubun"]) ? "checked":"" ?>><?= $rows_cd["cd_nm"]?>
      <? } ?>
    </td>
    <td><span class='inp_orange'><input type='button' value='삭제' class='delrow'></span></td>
  </tr>
  <?
  $k++;
  }?>
</table>
<input type="hidden" name="arr_num1" id="arr_num1" value="<?=$k?>">

<p class="tit_sub mt40">3. 보장내역관리</p>
<input type="hidden" name="ext4" value="<?=$ext4?>">
<input type="hidden" name="ext10" value="<?=$ext10?>">
<table class="adm-view-tb">
  <colgroup>
  <col width="8%"/>
  <col width="*">
  <col width="8%"/>
  </colgroup>
  <tr>
    <th>보장내역1</th>
    <td id="ext4_txt"><?=$ext4_txt?></td>
    <td><a href="javascript:;" class="btn-form-normal" onclick="pop_guarantee('ext4');">보장내역</a></td>
  </tr>
  <tr>
    <th>보장내역2</th>
    <td id="ext10_txt"><?=$ext10_txt?></td>
    <td><a href="javascript:;" class="btn-form-normal" onclick="pop_guarantee('ext10');">보장내역</a></td>
  </tr>
<table>

<p class="tit_sub mt40">4. 서비스내역관리</p>
<input type="hidden" name="ext5" value="<?=$ext5?>">
<table class="adm-view-tb">
  <colgroup>
  <col width="8%"/>
  <col width="*">
  <col width="8%"/>
  </colgroup>
  <tr>
    <th>서비스 내역명</th>
    <td id="ext5_txt"><?=$ext5_txt?></td>
    <td><a href="javascript:;" class="btn-form-normal" onclick="pop_service();">서비스 내역</a></td>
  </tr>
</table>


<p class="tit_sub mt40">5. 알릴사항</p>
<table id="notice_tb" class="adm-list-tb">
  <colgroup>
  <col width="8%"/>
  <col width="*">
  <col width="8%"/>
  </colgroup>
  <tr>
    <th>번호</th>
    <th>내용</th>
    <th>관리 <br/><em class="inp_black1"><input type="button" value="추가" id="addNotice"></em></th>
  </tr>
  <?
  $k = 0;
  $SQL_O1 = "select * from tbl_board_product_notice where pr_seq='".$seq."' ";
  $RS_O1 = $dbcon -> query($SQL_O1);
  while($rows_o1 = $dbcon -> fetch_array($RS_O1)){
  ?>
  <tr>
    <td><?=$k+1?></td>
    <td><input type='text' name='pr_notice[<?=$k?>]' style='width:100%;' value="<?=$rows_o1["pr_notice"]?>"></td>
    <td class="f_center"><span class='inp_orange'><input type='button' value='삭제' class='delrow'></span></td>
  </tr>
  <?
  $k++;
  }?>
</table>
<input type="hidden" name="arr_num2" id="arr_num2" value="<?=$k?>">

<p class="tit_sub mt40">6. 가입가능국가</p>
<table border="0" cellspacing="0" cellpadding="0"  class="adm-view-tb">
  <colgroup>
  <col width="8%"/>
  <col width="*">
  <col width="10%"/>
  <?if ($mode == "mod") {?>
  <col width="10%"/>
  <? } ?>
  </colgroup>
  <tr>
    <th>국가엑셀업로드</th>
    <td><input type="file" name="ex_ceountry" class="m_input"></td>
    <td>
      <a href="javascript:;" class="btn-form-excel" onclick="document.location.href='/_skin/board/<?=$bc_skin?>/excel/country_list.xlsx'">샘플엑셀 다운로드</a>
    </td>
    <?if ($mode == "mod") {?>
    <td>
      <a href="javascript:;" class="btn-form-normal" onclick="pop_nation(<?=$_REQUEST["seq"]?>)">여행국가 보기</a>
    </td>
    <?}?>
  </tr>
</table>

<div class="btnWrap">
  <div class="leftWrap">
    <a href="javascript:list_go();" class="btn_list">목록</a>
  </div>
  <div class="rightWrap">
    <a href="javascript:del_go('<?=$seq?>');" class="btn_normal">삭제</a>
    <input type="submit" value="등록" class="btn_add">
  </div>
</div>
</form>

<?}?>

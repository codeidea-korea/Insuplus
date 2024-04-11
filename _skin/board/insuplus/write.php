<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

<?}else{?>
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
<table class="adm-view-tb">
<colgroup>
  <col width="8%">
  <col width="42%">
  <col width="8%">
  <col width="42%">
</colgroup>
  <?
        if ( $bc_category_use == "Y") {
  ?>
  <tr>
    <td class="b_txt_w">카테고리</td>
    <td class="m_content">
      <?
            if ( !$category ) {
      ?>
      <select name="category" class="input">
      <?
                while ($CateListRs = $dbcon -> fetch_array($ArrCateListRs[1]) ) {
                    extract($CateListRs);
      ?>
      <option value="<?=$idx?>" <? if ($category == $idx ) echo "selected"; ?>><?=$cate_name?></option>
      <?
            }
      ?>
      </select>
      <?
        }
        else {
      ?>
      <input type="hidden" name="category" value="<?=$category?>">
      <?=$cate_name?>
            <? }
      ?>
    </td>
  </tr>

  <?
    }
  ?>


  <?
    if ( $bc_secret_use == "Y" && $auth_secret ) {
  ?>
  <tr>
    <td class="b_txt_w"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_secret.gif"></td>
    <td class="m_content">
      <table cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td><input type="radio" name="secret" value="N" <?=$secret?> <? if ($secret == "N" || $secret == "") echo "checked"; ?>></td>
          <td class="m_content_txt">미사용</td>
          <td width="6">&nbsp;</td>
          <td><input type="radio" name="secret" value="Y" <?=$secret?> <? if ($secret == "Y") echo "checked"; ?>></td>
          <td class="m_content_txt">사용</td>
          <td width="10">&nbsp;</td>
          <td class="m_content_txt"> -  비밀글 사용시 게시물은 작성자와 관리자만 확인 할 수 있습니다.</td>
        </tr>
      </table>
    </td>
  </tr>

  <?
    }
  ?>

  <?
    if ( $auth_level > 0 ) {
  ?>
  <input type="hidden" name="passwd" value="">
  <?
    }
    else {
  ?>
  <tr>
    <td class="b_txt_w">비밀번호</td>
    <td class="m_content">
      <input name="passwd" type="password" size="20" maxlength="20" class="input" />
    </td>
  </tr>

  <?
    }
  ?>

  <?
    if ( $bc_email_use != "N") {
  ?>
  <tr>
    <td class="b_txt_w">이메일</td>
    <td class="m_content">
      <?
        if ($mode == "mod") {
      ?>
      <input type="hidden" name="email1" value="<?=$email1?>">
      <input type="hidden" name="email2" value="<?=$email2?>">
      <?=$email1?>@<?=$email2?>
            <? }
        else {
      ?>
      <input type="hidden" name="old_email" value="<?=$old_email?>">
      <?
            getEmailForm("email1", "email2", $email1, $email2, "input");
      ?>
      <?
            if ($mode == "write") {
      ?>
      메일주소를 정확하게 입력하셔야 답변을 받으실수있습니다.
      <?
            }
      ?>
      <?
        }
      ?>
    </td>
  </tr>

  <?
    }
  ?>

  <?
    if ( $bc_homepage_use == "Y") {
  ?>
  <tr>
    <td class="b_txt_w">홈페이지</td>
    <td class="m_content" style="letter-spacing:0px">
      http://<input type="text" name="homepage" value="<?=$homepage?>" size="40" maxlength="40" class="input">
    </td>
  </tr>

  <?
    }
  ?>

  <tr>
    <th>서비스 이용약관명</th>
    <td colspan="3">
      <? $subject = REQSTR2($subject); ?>
      <input type="text" name="subject" value="<?=$subject?>" class="w100p"/>
      <? if ( $bc_notice_use == "Y" && $auth_notice ) { ?>
      <p><input type="checkbox" name="notice" value="Y" <? if ($notice == "Y") { echo "checked"; } ?>/> <span class="txt_red">공지글로 등록합니다.</span></p>
      <? } ?>
      <?  if ( $bc_hidden_use == "Y" && $auth_hidden) { ?>
      <p><input type="checkbox" name="hidden" value="Y" <? if ($hidden == "Y") { echo "checked"; } ?>/> <span class="txt_red">해당글을 숨김니다.</span></p>
      <? } ?>
    </td>
  </tr>
  <? if ( $mode =="mod" ) { ?>
  <tr>
    <th>작성일</th>
    <td><?=$PrintRegDate?></td>
    <th>조회수</th>
    <td><?=$view_cnt?></td>
  </tr>  
  <? } ?>
<script type="text/javascript">
  function del_row(idx){
    var tr_num = idx+1;
    var length = $(".inp_orange").length;
    if (length==1){
      $('#service_tb > tbody:last > tr:last').remove();
    }else{
      $('#service_tb > tbody:last > tr:eq('+tr_num+')').remove();
    }
  }

  function generateGroupNameBox(selector, defaultValue){
    const optionList = JSON.parse(`<?= json_encode($Arr_option_group_value_of_insplus) ?>`);
    let html = [];

    if (Array.isArray(optionList) && optionList.length > 0){
      html = optionList.map(item => `<option value="${item}" ${item == defaultValue ? "selected":""}>${item}</option>`);
    }

    document.querySelector(selector).innerHTML = html.join('');
  }

$(function(){
  $("#addTR").click(function () {
    var length = Number($("#arr_num").val())+1;
    var row = "<tr>";
    var list_seq = document.querySelector('input[name=seq]').value;
    row += "<td>";
    row += `  <input type='checkbox' name='check_service[${length}]' value='Y'>`;
    row += `  <input type="hidden" name="idx[${length}]" value="null">`;
    row += `  <input type="hidden" name="list_seq[${length}]" value="${list_seq}">`;
    row += `  <input type="hidden" name="type_a[${length}]">`;
    row += `  <input type="hidden" name="type_b[${length}]">`;
    row += "</td>";
    row += "<td><select name='service_group_name["+length+"]'></select></td>";
    row += "<td><input type='text' name='service_name["+length+"]'></td>";
    row += "<td><input type='text' name='service_name_en["+length+"]'></td>";
    row += "<td><span class='inp_orange'><input type='button' value='삭제' onclick=\"del_row("+length+");\"></span></td>";
    row += "</tr>";
    $("#service_tb").append(row);
    $("#arr_num").val(length);
    generateGroupNameBox(`select[name="service_group_name[${length}]"]`, undefined);
  });
});
</script>


<textarea name="content" id="content" style="width:100%;height:300px;display:none" class="textarea"><?=$content?>&nbsp;</textarea>
  <?
    if ( $bc_upfile_image == "Y") {
  ?>
  <?
        $ObjFileName = "imgfile";
  ?>
  <tr>
    <td class="b_txt_w">
      이미지
      <span onclick="add_file('Tbl<?=$ObjFileName?>', <?=$bc_upfile_image_cnt?>, '<input type=file name=<?=$ObjFileName?>[] id=<?=$ObjFileName?> style=width:50% maxlength=255 class=a_input>');" style='cursor:pointer; font-family:tahoma; font-size:12pt;'><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_fileadd.gif"></span>
      <span onclick="del_file('Tbl<?=$ObjFileName?>');" style='cursor:pointer; font-family:tahoma; font-size:12pt;'><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_filedel.gif"></span>
    </td>
    <td class="m_content">
      <table id="Tbl<?=$ObjFileName?>" width="100%" cellpadding=0 cellspacing=0></table>
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
      <?
        }
      ?>
      <img id="<?=$ObjFileName?>" width="0" height="0">
    </td>
  </tr>

  <?
    }
  ?>


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
// 서비스 옵션리스트 검색
$SQL_O1 = "select * from tbl_board_insuplus_opt where list_seq='".$seq."' ";
$RS_O1 = $dbcon -> query($SQL_O1);
?>
<table class="adm-list-tb mt40" id="service_tb">
  <colgroup>
    <col width="6%">
    <col width="24%">
    <col width="30%">
    <col width="30%">
    <col width="10%">
  </colgroup>
  <tr>
    <th>주요보장</th>
    <th>그룹</th>
    <th>서비스내역</th>
    <th>영문서비스내역</th>
    <th>관리 <br/><em class="inp_black1"><input type="button" value="추가" id="addTR"></em></th>
  </tr>
  <?
  $k = 0;
  while($rows_o1 = $dbcon -> fetch_array($RS_O1)){
  ?>
  <tr>
    <td>
      <input type='checkbox' name='check_service[<?=$k?>]' value='Y' <?if ($rows_o1["chk_service"]=="Y"){?>checked<?}?>>
      <input type="hidden" name="idx[<?=$k?>]" value="<?=$rows_o1["idx"]?>">
      <input type="hidden" name="list_seq[<?=$k?>]" value="<?=$rows_o1["list_seq"]?>">
      <input type="hidden" name="type_a[<?=$k?>]" value="<?=$rows_o1["type_a"]?>">
      <input type="hidden" name="type_b[<?=$k?>]" value="<?=$rows_o1["type_b"]?>">
    </td>
    <td><select name="service_group_name[<?=$k?>]"></select></td>
    <script>generateGroupNameBox(`select[name="service_group_name[<?=$k?>]"]`, '<?=$rows_o1["service_group_name"]?>');</script>
    <td><input type="text" name="service_name[<?=$k?>]" value="<?=$rows_o1["service_name"]?>"></td>
    <td><input type="text" name="service_name_en[<?=$k?>]" value="<?=$rows_o1["service_name_en"]?>"></td>
    <td><span class='inp_orange'><input type='button' value='삭제' onclick="del_row(<?=$k?>)"></span></td>
  </tr>
  <?
  $k++;
  }?>
</table>
<input type="hidden" name="arr_num" id="arr_num" value="<?=$k-1?>">

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

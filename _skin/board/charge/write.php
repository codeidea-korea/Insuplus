<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

<? }else{ ?>
<?

	if($mode == "mod") {
		$field = " j.o_name, j.o_phone, j.o_isdn1, j.o_isdn2, j.gender  ";
		$field .= " , o.pr_name, o.ins_name, o.plan_name, o.s_date, o.e_date  ";
		$field .= " , c.c_name  "; 
		$field .= " , p.stock_isdn  ";
		$table  = " tbl_order_list_join j LEFT JOIN tbl_order_list o ON j.orderno = o.orderno ";
		$table .= " LEFT JOIN  tbl_board_product_country c ON  o.pr_cd = c.pr_seq  AND o.join_nation_cd = c.c_code ";
		$table .= " LEFT JOIN  tbl_board_plan p ON o.plan_cd = p.seq    ";
		$where = " AND j.seq = '".$join_seq."' ";
		$orderby = "";
		$limit = "0, 1";
		
		$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);
		$ListRs = $dbcon -> fetch_array($ArrListRs[1]);
		########################################
		extract($ListRs);
		unset($ListRs);
		unset($ArrListRs);
		
		$o_name = all_seed_dec($o_name);
		$o_phone = all_seed_dec($o_phone);
		$o_isdn1 = all_seed_dec($o_isdn1);
		$o_isdn2 = all_seed_dec($o_isdn2);
		$birth_date = substr($o_isdn1,2,6);
		
		$gender = substr($o_isdn2,0,1)%2;
		$gender_name = $gender == "M" ? "남":"여";
	}
?>
<form name="WriteForm" action="" method="post" enctype="multipart/form-data" onsubmit="return WriteChargeOkGo()">
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

<input type="hidden" name="subject" value="-">
<input type="hidden" name="writer" value="<?=$writer?>">
<input type="hidden" name="nick_name" value="<?=$nick_name?>"/>

<!-- (s) 회원정보 추가 -->
<input type="hidden" name="join_seq" id="join_seq" value="<?=$join_seq?>"/>
<!-- (e) 회원정보 추가-->
 <!-- (s) 관리자 상세화면  -->
<p class="tit_sub">- 가입자 정보</p>
<table class="adm-view-tb">
	<colgroup>
		<col width="8%">
		<col width="42%">
		<col width="8%">
		<col width="42%">
	</colgroup>
	<tr>
		<th>이름</th>
		<td colspan="3"><span id="txt_o_name"><?=$o_name;?></span> 
			<? if($mode=="write") {?>
				<a href="javascript:;" class="btn-form-normal" onClick="openPopup(700,500,'/_skin/board/charge/popup_member.php');">가입자 찾기</a>
			<? } ?>
			
			<? if($mode=="write") {?>
			<a href="#" id="join_link" target="" class="btn-form-normal">가입자정보  상세보기</a>
			<? } else { ?>
			<a href="/admin/mn1/join_view.php?seq=<?=$join_seq?>" id="join_link" target="_blank" class="btn-form-normal">가입자정보  상세보기</a>
			<? } ?>
		</td>
	</tr>
	<tr>
		<th>상품명</th>
		<td id="txt_pr_name"><?=$pr_name;?></td>
		<th>증권번호</th>
		<td id="txt_stock_isdn"><?=$stock_isdn;?></td>
	</tr>
	<tr>
		<th>보험사</th>
		<td><span id="txt_ins_name"><?=$ins_name;?></span></td>
		<th>플랜명</th>
		<td><span id="txt_plan_name"><?=$plan_name;?></span></td>
	</tr>
	<tr>
		<th>가입기간</th>
		<td id="txt_join_date"><?=$s_date;?>  ~ <?=$e_date;?></td>
		<th>출국국가</th>
		<td id="txt_nation_name"><?=$c_name;?></td>
	</tr>
	<tr>
		<th>연락처</th>
		<td id="txt_o_phone"><?=$o_phone;?></td>
		<th>생년월일</th>
		<td id="txt_birth_date"><?=$birth_date;?></td>
	</tr>
	<tr>
		<th>성별</th>
		<td colspan="3" id="txt_gender"><?=$gender_name;?></td>
	</tr>
	<? if ( $mode =="mod" ) { ?>
	<tr>
		<th>작성일</th>
		<td colspan="3"><?=$PrintRegDate?></td>
	</tr>	
	<? } ?>
</table>
	
<p class="tit_sub">- 청구 신청</p>
<table class="adm-view-tb">
	<colgroup>
	<col width="8%">
	<col width="42%">
	<col width="8%">
	<col width="42%">
	</colgroup>
	<tr>
		<th>신청일</th>
		<td><input type="text" name="apply_date" class="datepicker w100" value="<?=$apply_date?>"></td>
		<th>발생일</th>
		<td><input type="text" name="occur_date" class="datepicker w100" value="<?=$occur_date?>"></td>
	</tr>
	<tr>
		<th>발생장소</th>
		<td><input type="text" name="place" value="<?=$place?>"></td>
		<th>병원명</th>
		<td><input type="text" name="hospital" value="<?=$hospital?>"></td>
	</tr>
	<tr>
		<th>진단내용</th>
		<td colspan="3"><textarea name="content" style="height:150px;"><?=$content;?></textarea></td>
	</tr>
	<tr>
		<th>지급 상품가격</th>
		<td colspan="3"><input type="text" name="money" class="money numberonly" value="<?=$money?>"/></td>
	</tr>
	<tr>
		<th>청구유형</th>
		<td><select name="charge_type" />
				<option value="">선택해주세요.</option>
				<? foreach($charge_type_arr as $key=>$val) {?>
					<option value="<?=$key;?>" <?=$charge_type==$key ? "selected":"";?>><?=$val;?></option>
				<? } ?>
			</select>
		</td>
		<th>청구상태</th>
		<td><select name="charge_status" />
				<option value="">선택해주세요.</option>
				<? foreach($charge_status_arr as $key=>$val) {?>
					<option value="<?=$key;?>" <?=$charge_status==$key ? "selected":"";?>><?=$val;?></option>
				<? } ?>
			</select>
		</td>
	</tr>
	<tr>
		<th>연락처</th>
		<td colspan="3"><input type="text" name="mobile" value="<?=all_seed_dec($mobile)?>" class="numberonly"></td>
	</tr>
	<?
        if ( $bc_category_use == "Y") {
	?>
	<tr>
		<th>카테고리</th>
		<td colspan="3">
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
		<th>비밀글</th>
		<td colspan="3">
			<input type="radio" name="secret" value="N" <?=$secret?> <? if ($secret == "N" || $secret == "") echo "checked"; ?>>
			<label>미사용</label>
			<input type="radio" name="secret" value="Y" <?=$secret?> <? if ($secret == "Y") echo "checked"; ?>>
			<label>사용</label>
			<span class="txt_red">-  비밀글 사용시 게시물은 작성자와 관리자만 확인 할 수 있습니다.</span>
		</td>
	</tr>
	<?
    }
	?>
	<?
    if ( $bc_homepage_use == "Y") {
	?>
	<tr>
		<th>홈페이지</th>
		<td colspan="3">
			http://<input type="text" name="homepage" value="<?=$homepage?>" size="40" maxlength="40" class="input">
		</td>
	</tr>
	<?
    }
	?>
	<?
    	if ( $bc_upfile_image == "Y") {
	?>
	<?
        $ObjFileName = "imgfile";
	?>
	<tr>
		<th>
			이미지
			<span onclick="add_file('Tbl<?=$ObjFileName?>', <?=$bc_upfile_image_cnt?>, '<input type=file name=<?=$ObjFileName?>[] id=<?=$ObjFileName?> style=width:50% maxlength=255 class=a_input>');" style='cursor:pointer; font-family:tahoma; font-size:12pt;'><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_fileadd.gif"></span>
			<span onclick="del_file('Tbl<?=$ObjFileName?>');" style='cursor:pointer; font-family:tahoma; font-size:12pt;'><img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_filedel.gif"></span>
		</th>
		<td colspan="3">
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
		<th>
			파일첨부
			<?
        if ( $bc_upfile_cnt > 1) {
			?>
			<img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_fileadd.gif" onClick="return AddFile('DivFile');" alt="파일첨부 갯수 늘리기" style="cursor:hand">
			<!-- <img src="<?=$url_skin_board.$bc_skin?>/images/b_btn_filedel.gif" onClick="return removeList('<?=$file_cnt?>');" alt="파일첨부 갯수 줄이기" style="cursor:hand"> -->
			<?
        }
			?>
		</th>
		<td colspan="3">
			<table class="fileTb" name="DivFile" id="DivFile">
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
                $FC_idx						= $RowFileRs[0];
                $FC_category				= $RowFileRs[1];
                $FC_bc_id					= $RowFileRs[2];
                $FC_seq						= $RowFileRs[3];
                $FC_file_name				= $RowFileRs[4];
                $FC_file_realname			= $RowFileRs[5];
                $FC_file_size				= $RowFileRs[6];
                $FC_regdate				= $RowFileRs[7];
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
			<table class="fileTb">
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
		<th></td>
		<td colspan="3">
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

<!-- (s) 하단  버튼 영역 -->
<div class="btnWrap">
	<div class="leftWrap">
		<a href="javascript:list_go();" class="btn_list">목록</a>
	</div>
	<div class="rightWrap">
		<? if($mode=="mod") {?>
			<? if ($auth_delete) { ?>
				<a href="javascript:del_go('<?=$seq?>');" class="btn_normal">삭제</a>
			<? } ?>
			<input type="submit" value="수정" class="btn_add"/>
		<? } else { ?>
			<input type="submit" value="등록" class="btn_add"/>
		<? } ?>
	</div>
</div>
<!-- (e) 하단  버튼 영역 -->
</form>
<?}?>
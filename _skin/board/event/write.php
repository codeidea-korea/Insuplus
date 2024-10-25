<script>
 	/* 숫자만 입력받기 */
 	function fn_press(event, type) {
        if(type == "numbers") {
            if(event.keyCode < 48 || event.keyCode > 57) return false;
            //onKeyDown일 경우 좌, 우, tab, backspace, delete키 허용 정의 필요
        }
    }
    /* 한글입력 방지 */
    function fn_press_han(obj)
    {
        //좌우 방향키, 백스페이스, 딜리트, 탭키에 대한 예외
        if(event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 || event.keyCode == 39
        || event.keyCode == 46 ) return;
        //obj.value = obj.value.replace(/[\a-zㄱ-ㅎㅏ-ㅣ가-힣]/g, '');
        obj.value = obj.value.replace(/[\ㄱ-ㅎㅏ-ㅣ가-힣]/g, '');
	}
</script>
<style>
	input[type="number"]::-webkit-outer-spin-button,
	input[type="number"]::-webkit-inner-spin-button {
			-webkit-appearance: none;
			margin: 0;
	}

	/* Firefox */
	input[type="number"] {
			-moz-appearance: textfield;
			text-align: right;
	}
</style>
<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

<?
##########################################################################
### 관리자 모드입니다
##########################################################################
}else{
	$SQL = "SELECT COUNT(*) FROM tbl_partner_coupon where event_seq = '".$seq."'";
	$RS = $dbcon -> query($SQL);
	$row = $dbcon -> fetch_array($RS);
	$partnerCouponCnt = $row[0];
?>
<form name="WriteForm" id="WriteForm" action="" method="post" enctype="multipart/form-data" onsubmit="return submitchk()">
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

<input type="hidden" name="writer" value="<?=$writer?>">
<input type="hidden" name="nick_name" value="<?=$nick_name?>"/>
 <!-- (s) 관리자 상세화면  -->
<table class="adm-view-tb">
	<colgroup>
		<col width="8%"> 
		<col width="22%">
		<col width="8%">
		<col width="22%">
		<col width="8%">
		<col width="22%">
	</colgroup>
	<tr>
		<th>이벤트명</th>
		<td colspan="5">
			<? $subject = REQSTR2($subject); ?>
			<input type="text" id="subject"name="subject" value="<?=$subject?>" class="w100p"/>
			<? if ( $bc_notice_use == "Y" && $auth_notice ) { ?>
			<p><input type="checkbox" name="notice" value="Y" <? if ($notice == "Y") { echo "checked"; } ?>/> <span class="txt_red">공지글로 등록합니다.</span></p>
			<? } ?>
			<?  if ( $bc_hidden_use == "Y" && $auth_hidden) { ?>
			<p><input type="checkbox" name="hidden" value="Y" <? if ($hidden == "Y") { echo "checked"; } ?>/> <span class="txt_red">해당글을 숨김니다.</span></p>
			<? } ?>
		</td>
	</tr>
	<tr>
	<? if ( $mode =="mod" ) { ?>
		<th>작성일</th>
		<td><?=$PrintRegDate?></td>
		<th>조회수</th>
		<td colspan="3"><?=$view_cnt?></td>
		<? } ?>
	</tr>
	<tr>
		<th>구분</th>
		<td>
			<input type="radio" name="event_type" id="event_type_1" value="N" <? if (!$event_type || $event_type == "N") echo "checked"; ?>/><label for="event_type_1">일반</label>
			<input type="radio" name="event_type" id="event_type_2" value="C" <? if ($event_type == "C") echo "checked"; ?>/><label for="event_type_2">쿠폰</label>
		</td>
		<th>쿠폰명</th>
		<td colspan="3">
			<input type="text" name="coupon_name" value="<?=$coupon_name?>" class="w100p"/>
		</td>
	</tr>
	<tr>
		<th>이벤트 기간</th>
		<td colspan="3">
			<input type="text" id="start_date" name="start_date" value="<?=substr($start_date, 0, 10)?>" class="datepicker w100">
			<span style="padding-left: 5px; padding-right: 5px;">~</span>
			<input type="text" id="end_date" name="end_date" value="<?=substr($end_date, 0, 10)?>" class="datepicker w100">
		</td>
		<th>할인율 (사용안함)</th>
		<td>
			<input type="text" id="discount" name="discount" value="<?=$discount?>" maxlength="11" onkeypress="return fn_press(event, 'numbers');" onkeydown="fn_press_han(this);" disabled/>%
		</td>
	</tr>
	<tr>
		<th>쿠폰 사용기간</th>
		<td>
			<input type="text" id="expire_date_s" name="expire_date_s" value="<?=substr($expire_date_s, 0, 10)?>" class="datepicker w100">
			<span style="padding-left: 5px; padding-right: 5px;">~</span>
			<input type="text" id="expire_date_e" name="expire_date_e" value="<?=substr($expire_date_e, 0, 10)?>" class="datepicker w100">
		</td>
		<th>중복여부</th>
		<td colspan="3">
			<select id="duplicate_status_yn" name="duplicate_status_yn" class="selectSt01">
				<option value="" selected>선택</option>
				<option value="Y" <? if ($duplicate_status_yn == "Y") echo "selected"; ?>>중복가능</option>
				<option value="N" <? if ($duplicate_status_yn == "N") echo "selected"; ?>>중복불가</option>
			</select>
		</td>
	</tr>

	<tr>
		<th>보험료 할인</th>
		<td>
			<input type="radio" name="insurance_discount_applied" id="insurance_discount_applied_1" value="N" <? if (!$insurance_discount_applied || $insurance_discount_applied == "N") echo "checked"; ?>/> <label for="insurance_discount_applied_1">미적용</label>
			<input type="radio" name="insurance_discount_applied" id="insurance_discount_applied_2" value="P" <? if ($insurance_discount_applied == "P") echo "checked"; ?>/> <label for="insurance_discount_applied_2">정률</label>
		</td>
		<th>보험료 할인율</th>
		<td><input type="number" name="insurance_discount_rate" value="<?= $insurance_discount_rate ?>" min="0" max="100" maxlength="3" oninput="checkMaxRate(this)">%</td>
		<th>보험료 최대 할인금액</th>
		<td>최대 <input type="number" name="insurance_max_discount_amount" value="<?= $insurance_max_discount_amount ?>" min="0" max="3" maxlength="1" oninput="checkMaxAmount(this)">만원</td>
	</tr>
	<tr>
		<th>서비스료 할인 적용여부</th>
		<td>
			<input type="radio" name="service_fee_discount_applied" id="service_fee_discount_applied_1" value="N" <? if (!$service_fee_discount_applied || $service_fee_discount_applied == "N") echo "checked"; ?> onchange="handleServiceFeeDiscountChange(this)"/> <label for="service_fee_discount_applied_1">미적용</label>
			<input type="radio" name="service_fee_discount_applied" id="service_fee_discount_applied_2" value="P" <? if ($service_fee_discount_applied == "P") echo "checked"; ?> onchange="handleServiceFeeDiscountChange(this)"/> <label for="service_fee_discount_applied_2">정률</label>
			<input type="radio" name="service_fee_discount_applied" id="service_fee_discount_applied_3" value="F" <? if ($service_discount_applied == "F") echo "checked"; ?> onchange="handleServiceFeeDiscountChange(this)"/> <label for="service_fee_discount_applied_3">정액</label>
		</td>
		<th>서비스료 할인율</th>
		<td><input type="number" name="service_fee_discount_rate" value="<?= $service_fee_discount_rate ?>" min="0" max="100" maxlength="3" oninput="checkMaxRate(this)">%</td>
		<th>서비스료 최대 할인금액</th>
		<td>최대
			<input type="number" name="service_fee_max_discount_amount" id="service_fee_max" value="<?= $service_fee_max_discount_amount ?>" min="0" max="3" maxlength="1" oninput="checkMaxAmount(this)">
			<input type="number" name="service_fee_max_discount_amount" id="service_fee_fixed" value="<?= number_format($service_fee_max_discount_amount) ?>" min="0" numberOnly>
			<span id="service_fee_name"></span>
		</td>
	</tr>
	<tr>
		<th>가입기간</th>
		<td colspan="5">
			<input type="number" id="subscription_start_date" name="subscription_start_date" value="<?= ($subscription_start_date) ? $subscription_start_date : '1' ?>" min="1" max="365" oninput="checkMaxValue(this)">
			<span style="padding-left: 5px; padding-right: 5px;">~</span>
			<input type="number" id="subscription_end_date" name="subscription_end_date" value="<?= ($subscription_end_date) ? $subscription_end_date : '365' ?>" min="1" max="365" maxlength="3" oninput="checkMaxValue(this)">
		</td>
	</tr>
	<tr>
		<th>카테고리</th>
		<td colspan="5">
			<input type="hidden" id="event_category_master_seq" name="event_category_master_seq" value="<?=$event_category_master_seq?>"/>
			<a href="javascript:;" onclick="fnCategory()" class="btn-form-normal">선택</a>
		</td>
	</tr>
	<tr>
		<th>동반인 수</th>
		<td colspan="5">
			<select id="min_companion" name="min_companion" class="selectSt01">
				<option value="">선택</option>
				<? for($i = 0; $i <= 5; $i++) { ?>
				<option value="<?=$i?>" <? 
					if ($min_companion == $i) {
						echo "selected";
					} else if (!$min_companion && $i === 0) {
						echo "selected";
					}
				?>><?=$i?></option>
				<? } ?>
			</select>
			<span style="padding-left: 5px; padding-right: 5px;">명 ~</span>
			<select id="max_companion" name="max_companion" class="selectSt01">
				<option value="">선택</option>
				<? for($i = 0; $i <= 5; $i++) { ?>
				<option value="<?=$i?>" <? 
					if ($max_companion == $i) {
						echo "selected";
					} else if (!$max_companion && $i === 5) {
						echo "selected";
					}
				?>><?=$i?></option>
				<? } ?>
			</select>
			<span style="padding-left: 5px; padding-right: 5px;">명</span>
		</td>
	</tr>

	<tr>
		<th>쿠폰수량</th>
		<td colspan="5">
		<input type="text" id="coupon_size" name="coupon_size" value="<?=$coupon_size?>" maxlength="11" onkeypress="return fn_press(event, 'numbers');" onkeydown="fn_press_han(this);" style="ime-mode:disabled;"/>개
		</td>
	</tr>

	<tr>
	<?if ($ss_u_idx){?>
		<th>제휴이벤트</th>
		<td>
			<input type="checkbox" name="partner_event_yn" value="Y" <? if ($partner_event_yn == "Y") { echo "checked"; } ?>/> <span class="txt_red">※ 제휴 이벤트 인 경우 체크</span>
			<input type="checkbox" name="partner_coupon_yn" value="Y" <? if ($partner_coupon_yn == "Y") { echo "checked"; } ?>/> <span class="txt_red">※ 제휴 쿠폰 사용할 경우 체크</span>
		</td>
	<? } ?>
		<th>제휴사</th>
		<td colspan="3">
			<select title="제휴사 선택" id="event_partnership_code" class="selectSt01" name="event_partnership_code">
				<option value="">선택해주세요.</option>
				<?while ($CateListRs = $dbcon -> fetch_array($ArrCateListRs[1]) ) {
					extract($CateListRs);?>
				<option value="<?=$partnership_code?>" <? if ($event_partnership_code == $partnership_code ) echo "selected"; ?>><?=$partnership_name?></option>
				<?}?>
			</select>
		</td>
	</tr>
	<tr>
		<th>제휴쿠폰명</th>
		<td>
			<input type="text" id="partner_coupon_name" name="partner_coupon_name" value="<?=$partner_coupon_name?>"/>
		</td>
		<th>제휴쿠폰 할인율</th>
		<td colspan="3">
			<input type="text" id="partner_coupon_discount" name="partner_coupon_discount" value="<?=$partner_coupon_discount?>" maxlength="11" onkeypress="return fn_press(event, 'numbers');" onkeydown="fn_press_han(this);" style="ime-mode:disabled;"/>%
		</td>
	</tr>
	<tr>
		<th>제휴쿠폰 등록</th>
		<td colspan="5">
			<input type="file" id="file1" name="file1">
			<a href="/_data/쿠폰양식.xlsx"><u>쿠폰양식 다운로드</u></a>
			<? if($partnerCouponCnt && $partnerCouponCnt > 0) {?>
				<span style="color: red;">등록된 제휴쿠폰 수 : <?= $partnerCouponCnt?></span>
			<? } ?>
			<br/><span class="txt_red">※ 제휴쿠폰 사용시 양식 참조하여 파일 업로드, 수량 추가시 동일하게 파일 업로드 (쿠폰코드는 중복불가)</span>
			
		</td>
	</tr>
	<?
    if ( $mode =="mod" ) {
	?>
	<tr>
		<th>제휴 트래킹 URL</th>
		<td colspan="5">
			<input type="text" name="event_url" value="<?=$event_url?>" maxlength="200" class="w100p">
		</td>
	</tr>
	<?
    }
	?>
	<?
        $ObjFileName = "imgfile";
	?>
	<tr>
		<th>
			배너이미지<br/>
			<span class="txt_red">(406 x 103)</span>

		</th>
		<td colspan="5">
			<table id="Tbl<?=$ObjFileName?>" class="fileTb"></table>
			<script>
				function readURL(input) {
					if (input.files && input.files[0]) {
						var reader = new FileReader();

						reader.onload = function(e) {
						$('#<?=$ObjFileName?>').attr('src', e.target.result);
						}

						reader.readAsDataURL(input.files[0]);
					}
				}

				$("#<?=$ObjFileName?>").change(function() {
					readURL(this);
				});
			</script>
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

							echo "<img src=\"".$upload_url."/".${"Arr_".$ObjFileName}[$i][1]."\" width=\"200\">";

							break;
						}
					}
				}
			?>
			<img id="<?=$ObjFileName?>" width="0" height="0">
		</td>
	</tr>

	<tr>
		<th>내용</th>
		<td colspan="5">
			<?
		    if ( $bc_editor_use == "Y" ) {
		        $content = RESSTR($content);
		        $content = str_replace("'","\"",$content);
		        include_once $_SERVER["DOCUMENT_ROOT"]."/_util/board/editor.php";

		        //echo myEditor('모드','에디터경로','폼이름','필드이름','폼사이즈','폼높이','랭귀지');
		        //echo myEditor(1, $path_editor, $url_editor,'WriteForm','content','100%','400','utf-8');
		    }
		    else {
					?><textarea name="content" id="content" style="width:100%;height:300px;" class="textarea"><?=$content?></textarea><?
		    }
			?>
		</td>
	</tr>
	<?
    if ( $bc_secret_use == "Y" && $auth_secret ) {
	?>
	<tr>
		<th>공개/비공개</th>
		<td colspan="5">
			<input type="radio" name="secret" value="N" <?=$secret?> <? if ($secret == "N" || $secret == "") echo "checked"; ?>>
			<label>공개</label>
			<input type="radio" name="secret" value="Y" <?=$secret?> <? if ($secret == "Y") echo "checked"; ?>>
			<label>비공개</label>
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
		<td colspan="5">
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
			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td class="m_content">
						<span class="m_content_txt">
						업로드 확장자 제한 : <?=$bc_upfile_ext_upload?>,
        업로드 파일크기 제한 : <?=$bc_upfile_size
						?>
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

</table>

<!-- (s) 하단  버튼 영역 -->
<div class="btnWrap">
	<div class="leftWrap">
		<a href="javascript:list_go();" class="btn_list">목록</a>
	</div>
	<div class="rightWrap">
		<? if ($mode == "mod") {?><input type="button" value="삭제" class="btn_normal" onclick="del_go('<?=$seq?>');"/><?}?>
		<input type="submit" value="<? if ($mode == "mod") {echo "수정";} else {echo "등록";} ?>" class="btn_add"/>
	</div>
</div>
<!-- (e) 하단  버튼 영역 -->
</form>
<?}?>
<script>
	$('#service_fee_fixed').hide();
	$('#event_type').change(function() {
		var eventType = $(this).val();
		alert(eventType);
	});
	function submitchk() {
		if($('#event_type').val() == 'C') {
			if($('#coupon_name').val() == '') {
				alert('쿠폰명을 입력해주세요.');
				$('#coupon_name').focus();
				return false;
			} else if($('#expire_date_s').val() == '') {
				alert('쿠폰 사용기간 시작일을 입력해주세요.');
				$('#expire_date_s').focus();
				return false;
			} else if($('#expire_date_e').val() == '') {
				alert('쿠폰 사용기간 시작일을 입력해주세요.');
				$('#expire_date_e').focus();
				return false;
			} else if($('#discount').val() == '') {
				alert('할인율을 입력해주세요.');
				$('#discount').focus();
				return false;
			} else if($('#coupon_size').val() == '') {
				alert('쿠폰수량을 입력해주세요.');
				$('#coupon_size').focus();
				return false;
			} else if($('#subject').val() == '') {
				alert('이벤트명을 입력해주세요.');
				$('#subject').focus();
				return false;
			} else if($('#start_date').val() == '') {
				alert('이벤트 시작일을 입력해주세요.');
				$('#start_date').focus();
				return false;
			} else if($('#end_date').val() == '') {
				alert('이벤트 종료일을 입력해주세요.');
				$('#end_date').focus();
				return false;
			}
			if($('#partner_coupon_yn').val() == 'Y') {
				if($('#partner_coupon_name').val() == '') {
					alert('제휴사 쿠폰명을 입력해주세요.');
					$('#partner_coupon_name').focus();
					return false;
				} else if($('#partner_coupon_discount').val() == '') {
					alert('제휴사 할인율을 입력해주세요.');
					$('#partner_coupon_discount').focus();
					return false;
				}
			}
		} else if($('#subject').val() == '') {
			alert('이벤트명을 입력해주세요.');
			$('#subject').focus();
			return false;
		} else if($('#start_date').val() == '') {
			alert('이벤트 시작일을 입력해주세요.');
			$('#start_date').focus();
			return false;
		} else if($('#end_date').val() == '') {
			alert('이벤트 종료일을 입력해주세요.');
			$('#end_date').focus();
			return false;
		<? if($mode != "mod") {?>
		} else if($('#imgfile').val() == '') {
			alert('배너이미지를 선택해주세요.');
			$('#end_date').focus();
			return false;
		<? } ?>
		} else if($('textarea[name=content]').val() == '') {
			alert('내용을 입력해주세요.');
			$('#idx_editor_webnote_content').focus().focus();
			return false;
		}
	WriteOkGo();
	}

	function fnCategory() {
		let seq = $('#event_category_master_seq').val();
		var popCategory = window.open('popup_category.php?seq='+ seq,'popCategory','top=0,left=0, width=935,height=600');
		popCategory.focus();
	}

	function checkMaxValue(input) {
    if (input.value > 365) {
    	input.value = 365;
    }
	}
	function checkMaxAmount(input) {
    if (input.value > 3) {
    	input.value = 3;
    }
	}
	function checkMaxRate(input) {
		if (input.value > 100) {
			input.value = 100;
		}
	}

	function handleServiceFeeDiscountChange(input) {
		if (input.value == 'F') {
			$('input[name=service_fee_discount_rate]').prop('disabled', true);
			$('#service_fee_fixed').show();
			$('#service_fee_max').hide();
			$('#service_fee_max').val(0);
			$('#service_fee_name').text('원');
		} else {
			$('input[name=service_fee_discount_rate]').prop('disabled', false);
			$('#service_fee_fixed').hide();
			$('#service_fee_max').show();
			$('#service_fee_fixed').val(0);
			$('#service_fee_name').text('만원');
		}
	}

	//3자리 단위마다 콤마 생성
	function addCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	$(document).ready(function() {
		$("input:text[numberOnly]").on("keyup", function() {
			$(this).val(addCommas($(this).val().replace(/[^0-9]/g,"")));
		});
		$("input:text[numberOnly]").on("focusout", function() {
			$(this).val(addCommas($(this).val().replace(/[^0-9]/g,"")));
		});
		$("input:text[numberOnly]").on("focusin", function() {
			$(this).val($(this).val().replace(/[^0-9]/g,""));
		});
	});

</script>
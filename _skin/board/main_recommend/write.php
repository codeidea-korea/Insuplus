<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

<?}else{
if($mode == "mod") {
	
	$SQL = " SELECT subject, imgfile FROM tbl_board_main_event WHERE seq = '".$event_seq."' ";
	$EVENT_RS = $dbcon->query($SQL);
	$EVENT_ROW = $dbcon->fetch_array($EVENT_RS);
	$banner_img_info = setFileName($EVENT_ROW["imgfile"])[0];
	
	$ROW1 = getFullPlanName($plan_seq_1);
	$plan_name_1 = $ROW1["pr_cd_name"]." ".$ROW1["ins_cd_name"]." ".$Arr_plan_cd[$ROW1["plan_cd"]]." ".$ROW1["chk_service_text"];
	
	$ROW2 = getFullPlanName($plan_seq_2);
	$plan_name_2 = $ROW2["pr_cd_name"]." ".$ROW2["ins_cd_name"]." ".$Arr_plan_cd[$ROW2["plan_cd"]]." ".$ROW2["chk_service_text"];
	
	$ROW3 = getFullPlanName($plan_seq_3);
	$plan_name_3 = $ROW3["pr_cd_name"]." ".$ROW3["ins_cd_name"]." ".$Arr_plan_cd[$ROW3["plan_cd"]]." ".$ROW3["chk_service_text"];
}
?>
<script>
function popup_event_list(code) {
	window.open("popup_event_list.php","_pop","width=700,height=600");
}
function popup_plan(code) {
	window.open("/_skin/board/<?=$bc_skin?>/popup_plan.php?num="+code,"_pop","width=600,height=600");
}
</script>
<form name="WriteForm" action="" method="post" enctype="multipart/form-data" onsubmit="return submitchk()">
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
<input type="hidden" name="nick_name" value="<?=$nick_name?>" maxlength="20"  size="20" class="input">
<input type="hidden" name="content" value=".">


<!-- ### 게시판 시작 ###  -->

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
		<input type="text" id="subject" name="subject" value="<?=$subject?>" class="w150"/> <span class="txt_red">(최대 7글자까지 작성)</span>
	</td>
</tr>
<tr>
	<th>부제</th>
	<td colspan="3">
		<textarea id="ext1" name="ext1" class="w150" style="height:50px;"><?=$ext1?></textarea> <span class="txt_red">(최대 8글자 2행까지 작성)</span>
	</td>
</tr>
<? if ( $mode =="mod" ) { ?>
<tr>
	<th>작성일</th>
	<td colspan="3"><?=$PrintRegDate?></td>
</tr>
<? } ?>
<tr>
	<th>노출기간</th>
	<td colspan="3">
		<input type="text" id="main_recommand_date_s" name="main_recommand_date_s" value="<?=$main_recommand_date_s?>" class="w100 datepicker">
		~
		<input type="text" id="main_recommand_date_e" name="main_recommand_date_e" value="<?=$main_recommand_date_e?>" class="w100 datepicker">
		
	</td>
</tr>
<tr>
	<th>노출순서</th>
	<td colspan="3">
		<input type="number" id="exposure_order" name="exposure_order" value="<?=$exposure_order?>" class="w50" />
		※ 낮은 노출번호 순서로 노출됩니다.
	</td>
</tr>

<tr>
	<th>공개/비공개</th>
	<td colspan="3">
	<input type="radio" name="secret" value="N" <?=$secret?> <? if ($secret == "N" || $secret == "") echo "checked"; ?>> 공개
	<input type="radio" name="secret" value="Y" <?=$secret?> <? if ($secret == "Y") echo "checked"; ?>> 비공개
	</td>
</tr>
</table>

<p class="tit_sub">- 이벤트 찾기</p>
<table class="adm-list-tb">
	<colgroup>
	<col width="30%">
	<col width="40%">
	<col width="30%">
	</colgroup>
	<tr>
		<th>배너이미지</td>
		<th>제목</td>
		<th>관리</td>
	</tr>
	<tr>
		<td id="event_img">
			<? if($banner_img_info[1]) {?>
				<img src="/_data/board/main_event/<?=$banner_img_info[1];?>" width="160"/>
			<? } ?>
		</td>
		<td id="event_row" class="l">
			<input type="hidden" id="event_seq" name="event_seq" value="<?=$event_seq?>"/>
			<span id="event_subject">
				<?=$EVENT_ROW["subject"]?>  
			</span>
		</td>
		<td>
			<input type="button" value="이벤트 찾기" style="width:40%" onclick="popup_event_list();"/>
		</td>
	</tr>
</table>

<p class="tit_sub">- 추천플랜</p>
<table class="adm-list-tb">
	<colgroup>
	<col width="10%">
	<col width="70%">
	<col width="20%">
	</colgroup>
	<tr>
		<th>콘텐츠 순서</th>
		<th>제목</th>
		<th>관리</th>
	</tr>
	<tr>
		<td>1</td>
		<td class="l">
			<input type="hidden" id="plan_seq_1" name="plan_seq_1" value="<?=$plan_seq_1?>"/>
			<span id="plan_subject_1"><?=$plan_name_1;?></span>
		</td>
		<td class="m_content" align="center">
			<input type="button" value="플랜찾기" style="width:40%" onclick="popup_plan('1');"/>
		</td>
	</tr>
	<tr>
		<td>2</td>
		<td class="l">
			<input type="hidden" id="plan_seq_2" name="plan_seq_2" value="<?=$plan_seq_2?>"/>
			<span id="plan_subject_2"><?=$plan_name_2;?></span>
		</td>
		<td>
			<input type="button" value="플랜찾기" style="width:40%" onclick="popup_plan('2');"/>
		</td>
	</tr>
	<tr>
		<td>3</td>
		<td class="l">
			<input type="hidden" id="plan_seq_3" name="plan_seq_3" value="<?=$plan_seq_3?>"/>
			<span id="plan_subject_3"><?=$plan_name_3;?></span>
		</td>
		<td>
			<input type="button" value="플랜찾기" style="width:40%" onclick="popup_plan('3');"/>
		</td>
	</tr>
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
<!-- (e) 하단  버튼 영역 -->
</form>

<?}?>
<script>
	function submitchk() {
		if($('#subject').val() == ''){
			alert('제목을 입력해 주세요.');
			$('#subject').focus();
			return false;
		} else if($('#ext1').val() == '') {
			alert('부제를 입력해 주세요.');
			$('#ext1').focus();
			return false;
		} else if($('#main_recommand_date_s').val() == '') {
			alert('노출기간 시작일을 입력해 주세요.');
			$('#main_recommand_date_s').focus();
			return false;
		} else if($('#main_recommand_date_e').val() == '') {
			alert('노출기간 종료일을 입력해 주세요.');
			$('#main_recommand_date_e').focus();
			return false;
		} else if($('#exposure_order').val() == '') {
			alert('노출순서를 입력해 주세요.');
			$('#exposure_order').focus();
			return false;
		} else if($('#event_seq').val() == '') {
			alert('이벤트를 선택해 주세요.');
			$('#event_seq').focus();
			return false;
		} else if($('#plan_seq_1').val() == '' && $('#plan_seq_2').val() == '' && $('#plan_seq_3').val() == '') {
			alert('추천플랜을 1개 이상 선택해 주세요.');
			$('#plan_seq_1').focus();
			return false;
		}
		WriteOkGo();
	}
</script>
<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$pop_seq = REQSTR($pop_seq, "");

	if ( getLen($pop_seq) > 0 ) {
		$field = " * ";
		$table = "tbl_popup";
		$where = " and pop_seq = '".$pop_seq."' ";
		$orderby = " pop_seq desc ";
		$limit = "0, 1";

		$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);

		$total_record = $ArrListRs[0];
		if ( $total_record == 0 ) {
			$dbcon -> dbcon_close();
			alert_back("게시판 정보가 누락되었습니다.");
			exit;
		}

		$ListRs = $dbcon -> fetch_array($ArrListRs[1]);
		extract($ListRs);
		unset($ListRs);
		unset($ArrListRs);

		$pop_sdate			= getStrCut($pop_sdate, 10);
		$pop_edate			= getStrCut($pop_edate, 10);

		if ( mb_strlen($pop_subject) > 90 ) {
			$pop_subject = mb_substr($pop_subject,0,90,"UTF-8")."...";
		}

		$PrintRegDate = date('Y/m/d ', strtotime($regDt) );
		// NEW 이미지
		$sNew="";
		if(strtotime($regDt) > (time() - (60 * 60 * 24 * 2))) {
			$sNew = $img_new;
		}

	}
	else {
		$pop_size_wid = 0;
		$pop_size_hei = 0;

		$pop_coor_top = 0;
		$pop_coor_left = 0;

	}

	if ( getLen($pop_sdate) == 0 ) $pop_sdate = date("Y-m-d");
	if ( getLen($pop_edate) == 0 ) $pop_edate = $pop_edate = date("Y-m-d", mktime(0,0,0,date(m)+1,date(d),date(Y)));
?>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript">
 $(document).ready(function() {
  //******************************************************************************
  // 상세검색 달력 스크립트
  //******************************************************************************
  var clareCalendar = {
   monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
   dayNamesMin: ['일','월','화','수','목','금','토'],
   weekHeader: 'Wk',
   dateFormat: 'yy-mm-dd', //형식(20120303)
   autoSize: false, //오토리사이즈(body등 상위태그의 설정에 따른다)
   changeMonth: true, //월변경가능
   changeYear: true, //년변경가능
   showMonthAfterYear: true, //년 뒤에 월 표시
   showOtherMonths: true, // 다른달 보여주기
   selectOtherMonths: true, // 다른달 선택가능
   buttonImageOnly: true, //이미지표시
   buttonText: '달력선택', //버튼 텍스트 표시
   buttonImage: '/admin/images/a_btn_cal.gif', //이미지주소
   showOn: "both", //엘리먼트와 이미지 동시 사용(both,button)
   yearRange: '1990:2020' //1990년부터 2020년까지
  };
  $("#pop_sdate").datepicker(clareCalendar);
  $("#pop_edate").datepicker(clareCalendar);
  $("img.ui-datepicker-trigger").attr("style","margin-left:5px; vertical-align:middle; cursor:pointer;"); //이미지버튼 style적용
  $("#ui-datepicker-div").hide(); //자동으로 생성되는 div객체 숨김
 });
 </script>


<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_popup?>images/admin_ball.gif">  팝업설정</td>
	</tr>
	<tr>
		<td colspan="2" height="2"></td>
	</tr>
</table>

<form name="PopupWriteForm" action="" method="post">
<input type="hidden" name="mode" value="">
<input type="hidden" name="pop_seq" value="<?=$pop_seq?>">
<!-- ### 게시판 시작 ###  -->

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="2" class="a_line_2px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제목</td>
		<td class="a_content">
			<input type="text" name="pop_subject" value="<?=$pop_subject?>" class="a_input" style="width:450px;"/>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">지역</td>
		<td class="a_content">
			<select name="area">
				<option value="">전체</option>
				<? foreach ($Arr_h_area as $key => $val) { ?>
					<option value="<?=$key?>" <? if ( "".$key == $area) echo "selected";?> ><?=$val?></option>
				<? } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">사용유무</td>
		<td colspan="5" class="a_content">
			<input type="radio" name="pop_use" value="Y" <? if ( getLen($pop_use) == 0 || $pop_use == "Y" ) echo "checked"; ?>> 사용함
			<input type="radio" name="pop_use" value="N" <? if ( $pop_use == "N" ) echo "checked"; ?>> 사용안함
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">공개기간</td>
		<td colspan="5" class="a_content">
			<input type="text" id="pop_sdate" name="pop_sdate" value="<?=$pop_sdate?>" <?=$ClassCalendar?> style="width:120px">
			~
			<input type="text" id="pop_edate" name="pop_edate" value="<?=$pop_edate?>" <?=$ClassCalendar?> style="width:120px">

		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">팝업창 사이즈</td>
		<td class="a_content">
			width :
			<input type="text" name="pop_size_wid" value="<?=$pop_size_wid?>" maxlength="3" class="a_input" style="width:50px;"/>
			,
			height : <input type="text" name="pop_size_hei" value="<?=$pop_size_hei?>" maxlength="3" class="a_input" style="width:50px;"/>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">팝업창 위치</td>
		<td class="a_content">
			top :
			<input type="text" name="pop_coor_top" value="<?=$pop_coor_top?>" maxlength="3" class="a_input" style="width:50px;"/>
			,
			left : <input type="text" name="pop_coor_left" value="<?=$pop_coor_left?>" maxlength="3" class="a_input" style="width:50px;"/>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">내용</td>
		<td class="a_content">
			<?
				###########################################
				#### 에디터 설정
//				include_once($path_editor.'/func_editor.php');
				###########################################
				//$content = REQSTR2($pop_content);
				$content = ($pop_content);
//				echo myEditor(1, $path_editor, $url_editor,'PopupWriteForm','pop_content','100%','300','utf-8');
				include_once $_SERVER["DOCUMENT_ROOT"]."/_util/board/editor.php";
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_2px">&nbsp;</td>
	</tr>
</table>
<!-- ### 게시판 시작 ###  -->

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="80" align="right">
			<!-- <a href="javascript:preview_go();"><img src="<?=$url_popup?>images/b_btn_preview.gif"></a> -->
			<? if ( getLen($pop_seq) > 0 ) { ?>
				<a href="javascript:del_go('<?=$pop_seq?>');"><img src="<?=$url_popup?>images/b_btn_delete.gif"></a>
			<? } ?>
			<a href="javascript:next_go();"><img src="<?=$url_popup?>images/b_btn_write.gif"></a>
			<a href="popup_list.php?page=<?=$page?>&<?=$parameters?>"><img src="<?=$url_popup?>images/b_btn_back.gif"></a>
		</td>
	</tr>
</table>

</form>


<script>
	function next_go() {
		ff = document.PopupWriteForm;

		if (!ff.pop_subject.value) {
			alert("제목을 입력하여 주십시오.");
			ff.pop_subject.focus();
			return;
		}

		if (ff.pop_size_wid.value == "" ) {
			alert("창 가로 사이즈를 입력하여 주십시오.");
			ff.pop_size_wid.focus();
			return;
		}

		if (ff.pop_size_hei.value == "" ) {
			alert("창 세로 사이즈를 입력하여 주십시오.");
			ff.pop_size_hei.focus();
			return;
		}

		if (ff.pop_coor_top.value == "" || ff.pop_coor_left.value == "") {
			alert("창 위치를 입력하여 주십시오.");
			ff.pop_coor_top.focus();
			return;
		}


		// 에디터 내용 삽입
//		editor_wr_ok();
//		if(!checkSpacContents()) {
//			alert("내용을 입력해주세요");
//			editor.focus();
//			return false;
//		}

			// 신규 웹노트 에디터
			if(ff.content.value == "") {
				alert("내용을 입력해주세요");
				webnote.focusWebNote("content")		//에디터에 포커스를 주기위한 webnote 내장함수
				//focusWebNote("contents1");
				return false;
			}


		ff.mode.value = "write_ok";
		ff.action = "";
		ff.target = "";
		ff.submit();
	}

	function preview_go() {
		ff = document.PopupWriteForm;
		sizeW = Number(ff.pop_size_wid.value)+10;
		sizeH = Number(ff.pop_size_hei.value)+10;
		//alert(sizeW);
		//alert(sizeH);

		var nLeft  = screen.width/2 - sizeW/2 ;
		var nTop  = screen.height/2 - sizeH/2 ;

		editor_wr_ok();

		opt = ",toolbar=no,menubar=no,location=no,scrollbars=no,status=no";//left=" + nLeft + ",top=" +  nTop + ",

		if (top.preview_pop == true) {
			preview_pop.close();
		}
		preview_pop = window.open("","preview_pop","width=" + sizeW + ",height=" + sizeH  + opt);
		ff.action = "<?=$url_popup?>/preview.php";

		ff.mode.value = "preview";
		ff.target = "preview_pop";
		ff.submit();
		preview_pop.resizeTo(sizeW, sizeH);
		preview_pop.focus();
	}


</script>

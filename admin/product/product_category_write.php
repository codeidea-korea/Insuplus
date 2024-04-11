<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$tm = "product";
	$lm = "";
	include $path_admin."inc/header.php";
?>
<?
	$pc_idx = REQSTR($pc_idx, "");
	$pre_pc_num = REQSTR($pre_pc_num, "");
	$search_pc_num = REQSTR($search_pc_num, "");

	$parameter = "search_pc_num=".$search_pc_num;

	// 정보 수정시
	if ( getLen($pc_idx) > 0 ) {

		$SQL = "
			select
				*
				, length(pc_num) as pc_num_len
			from
				tbl_product_category
			where
				pc_idx = '".$pc_idx."'
			limit 0, 1
		";

		$result = $dbcon -> query($SQL);
		$rows = $dbcon -> fetch_array($result);

		extract($rows);
		unset($rows);

		$search_pc_num = getStrCut($pc_num, getLen($pc_num)-2);

	}
	else {

		$pc_use = "Y";
		$pc_list_cols = 4;
		$pc_list_rows = 2;
		$pc_thum_width = 115;
		$pc_thum_height = 99;
		$pc_img_width = 237;
		$pc_img_height = 237;
		$pc_auth_list = 0;
		$pc_auth_view = 0;



	}
	$pre_cat_num = REQSTR($pre_cat_num,"");

	// 상위 카테고리 가져오기
	if ( $pre_cat_num ) {
		$pre_cat_num_len = strlen($pre_cat_num);
		$pre_cat_num = substr($pre_cat_num, 0, $pre_cat_num_len);
	}

?>
<script language='JavaScript'>
<!--
function PCWriteFormNextGo()
{
	var frm = document.PCWriteForm;

	if(!frm.pc_name.value){
		alert('카테고리명을 입력해 주세요');
		frm.pc_name.focus();
		return false;
	}

	/*
	if(!frm.pc_list_cols.value){
		alert('제품리스트 출력설정 가로값을 입력해 주세요.');
		frm.pc_list_cols.focus();
		return false;
	}

	if(isNaN(frm.pc_list_cols.value)){
		alert('제품리스트 출력설정 가로값을 숫자로  입력해 주세요.');
		frm.pc_list_cols.focus();
		frm.pc_list_cols.select();
		return false;
	}

	if(!frm.pc_list_rows.value){
		alert('제품리스트 출력설정 세로값을 입력해 주세요.');
		frm.pc_list_rows.focus();
		return false;
	}
	if(isNaN(frm.pc_list_rows.value)){
		alert('제품리스트 출력설정 세로값을 숫자로  입력해 주세요.');
		frm.pc_list_rows.focus();
		frm.pc_list_rows.select();
		return false;
	}
	if(!frm.pc_thum_width.value){
		alert('리스트 이미지 사이즈 가로값을 입력해 주세요.');
		frm.pc_thum_width.focus();
		return false;
	}
	if(isNaN(frm.pc_thum_width.value)){
		alert('리스트 이미지 사이즈 가로값을 숫자로  입력해 주세요.');
		frm.pc_thum_width.focus();
		frm.pc_thum_width.select();
		return false;
	}

	if(!frm.pc_thum_height.value){
		alert('리스트 이미지 사이즈 세로값을 입력해 주세요.');
		frm.pc_thum_height.focus();
		return false;
	}
	if(isNaN(frm.pc_thum_height.value)){
		alert('리스트 이미지 사이즈 세로값을 숫자로  입력해 주세요.');
		frm.pc_thum_height.focus();
		frm.pc_thum_height.select();
		return false;
	}

	if(!frm.pc_img_width.value){
		alert('제품상세 이미지 사이즈 가로값을 입력해 주세요.');
		frm.pc_img_width.focus();
		return false;
	}
	if(isNaN(frm.pc_img_width.value)){
		alert('제품상세 이미지 사이즈 가로값을 숫자로  입력해 주세요.');
		frm.pc_img_width.focus();
		frm.pc_img_width.select();
		return false;
	}

	if(!frm.pc_img_height.value){
		alert('제품상세 이미지 사이즈 세로값을 입력해 주세요.');
		frm.pc_img_height.focus();
		return false;
	}
	if(isNaN(frm.pc_img_height.value)){
		alert('제품상세 이미지 사이즈 세로값을 숫자로  입력해 주세요.');
		frm.pc_img_height.focus();
		frm.pc_img_height.select();
		return false;
	}
	*/
	document.getElementById("btnSubmit").disabled=true;
	return true;
}

function rows_size(Obj)
{
	if(Obj.rows < 40) {
		Obj.rows = Obj.rows * 2;
	}
	else {
		Obj.rows = 5; // 원래 세로 size
	}
}

function brdConfigOhter()
{
	MM_openBrWindow('product_category_other.php?cid=&type=write','configOther','scrollbars=yes,resizable=yes,width=480,height=560');
}

//-->
</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td width="200" valign="top" class="a_st"> 제품 카테고리 등록 / 수정</td>
		<td align="right">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td align="right">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td bgcolor="#339933" width="132" height="22" align="center" class="a_tab_txt" valign="top" style="padding-top:3px"><a href='<?=$url_admin?>product/product_category.php'>최상위 카테고리 리스트</a> </td>
								<td width="2"></td>
								<!-- <td bgcolor="#000000" width="250" align="center" class="a_tab_txt" valign="top" style="padding-top:3px"><a href='<?=$url_admin?>siteconfig/site_config.php'>카테고리 추가시 적용되는 기본설정값 변경하기</a></td> -->
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="3" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="3" height="18"></td>
	</tr>
</table>

<form name="PCWriteForm" method="post" action="product_category_write_ok.php" ENCTYPE='multipart/form-data' onSubmit="return PCWriteFormNextGo()">
<input type="hidden" name="pc_idx" value="<?=$pc_idx?>">
<input type="hidden" name="pc_num" value="<?=$pc_num?>">
<input type="hidden" name="pre_pc_num" value="<?=$pre_pc_num?>">
<input type="hidden" name="search_pc_num" value="<?=$search_pc_num?>">

<input type="hidden" name="pc_list_cols" value="<?=$pc_list_cols?>">
<input type="hidden" name="pc_list_rows" value="<?=$pc_list_rows?>">
<input type="hidden" name="pc_thum_width" value="<?=$pc_thum_width?>">
<input type="hidden" name="pc_thum_height" value="<?=$pc_thum_height?>">
<input type="hidden" name="pc_img_width" value="<?=$pc_img_width?>">
<input type="hidden" name="pc_img_height" value="<?=$pc_img_height?>">

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 제품 카테고리 기본설정</td>
		<!-- <td align="right" style="font-family:Dotum;font-size:11px;letter-spacing:-1px;padding-top:1px" valign="top"><font color="#FF0000">다른 카테고리 설정값을 적용</font>하시려면 환경설정 가져오기 버튼을 클릭하세요 → </td>
		<td align="right" valign="top" width="114"><img src="<?=$url_admin?>images/a_btn_configother.gif" onClick="javascript:brdConfigOhter()" style="cursor:hand"></td> -->
	</tr>
	<tr>
		<td colspan="2" height="2"></td>
	</tr>
</table>

<?


	// 하위 카테고리 추가시
	if ( getLen($pre_pc_num) > 0 ) {
		$print_pc_num = "";
		$print_pc_lev = "Lev. ".(getLen($pc_num) / 2 + 1);
		$RS_pc_num = getprecategoryname($pre_pc_num, 2);
		$print_pc_lev_name = "";
		$print_pc_lev_name = "<a href=\"product_category.php\">최상위</a> > ";
		while( $row_pc_num = $dbcon -> fetch_array($RS_pc_num) ) {
			$print_pc_lev_name .= "<a href=\"product_category.php?search_pc_num=".$row_pc_num[pc_num]."\">".$row_pc_num[pc_name]."</a> > ";
		}
	}
	else {
		// 수정시
		if ( getLen($pc_num) > 0 ) {
			$print_pc_num = "";
			$print_pc_lev = "Lev. ".(getLen($pc_num) / 2);
			$RS_pc_num = getprecategoryname($pc_num, 1);
			$print_pc_lev_name = "";
			$print_pc_lev_name = "<a href=\"product_category.php\">최상위</a> > ";
			while( $row_pc_num = $dbcon -> fetch_array($RS_pc_num) ) {
				$print_pc_lev_name .= "<a href=\"product_category.php?search_pc_num=".$row_pc_num[pc_num]."\">".$row_pc_num[pc_name]."</a> > ";
			}
		}
		// 최상위 신규 카테고리 추가시
		else {
			$print_pc_num = "";
			$print_pc_lev = "Lev. 1";
			$print_pc_lev_name = "<b>현재 <font color=\"#006699\">최상위</font> 카테고리입니다.</b>";
		}
	}
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="2" class="a_line_2px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">카테고리 정보</td>
		<td class="a_content">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="m_content_txt">ㆍ카테고리 코드 :</td>
					<td class="m_content_txt">&nbsp; <b><font color="#FF6600"><?=$pc_num?></font></b></td>
					<td width="10"></td>
					<td class="m_content_txt">ㆍ레벨 :</td>
					<td class="m_content_txt">&nbsp; <b><font color="#339933"><?=$print_pc_lev?></font></b></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>


	<tr>
		<td class="a_txt">카테고리명</td>
		<td class="a_content">
			<?=$print_pc_lev_name?>
			<input type="text" class="a_input" style="width:200px" maxlength="100" name="pc_name" value="<?=$pc_name?>">
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">사용유무</td>
		<td class="a_content">
			<input type="radio" name="pc_use" value="Y" <? if ( $pc_use == "Y" || getLen($pc_use) == 0 ) echo "checked"; ?>> 사용
			<input type="radio" name="pc_use" value="N" <? if ( $pc_use == "N" ) echo "checked"; ?>> 사용안함
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
<? /*
	<tr>
		<td class="a_txt">제품리스트 출력설정</td>
		<td class="a_content">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="m_content_txt">가로&nbsp;</td>
					<td>
						<input type="text" class="a_input" style="width:40px" maxlength="2" name="pc_list_cols" value="<?=$pc_list_cols?>">
					</td>
					<td class="m_content_txt">&nbsp; 개</td>
					<td class="m_content_txt">&nbsp; x&nbsp;&nbsp;세로&nbsp;</td>
					<td>
						<input type="text" class="a_input" style="width:40px" maxlength="2" name="pc_list_rows" value="<?=$pc_list_rows?>">
					</td>
					<td class="m_content_txt">&nbsp; 개</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">리스트 이미지 사이즈</td>
		<td class="a_content">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="m_content_txt">가로&nbsp;</td>
					<td>
						<input type="text" class="a_input" style="width:40px" maxlength="4" name="pc_thum_width" value="<?=$pc_thum_width?>">
					</td>
					<td class="m_content_txt">&nbsp; pixel</td>
					<td class="m_content_txt">&nbsp; x&nbsp;&nbsp;세로&nbsp;</td>
					<td>
						<input type="text" class="a_input" style="width:40px" maxlength="4" name="pc_thum_height" value="<?=$pc_thum_height?>">
					</td>
					<td class="m_content_txt">&nbsp; pixel</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품상세 이미지 사이즈</td>
		<td class="a_content">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="m_content_txt">가로&nbsp;</td>
					<td>
						<input type="text" class="a_input" style="width:40px" maxlength="4" name="pc_img_width" value="<?=$pc_img_width?>">
					</td>
					<td class="m_content_txt">&nbsp; pixel</td>
					<td class="m_content_txt">&nbsp; x&nbsp;&nbsp;세로&nbsp;</td>
					<td>
						<input type="text" class="a_input" style="width:40px" maxlength="4" name="pc_img_height" value="<?=$pc_img_height?>">
					</td>
					<td class="m_content_txt">&nbsp; pixel</td>
				</tr>
			</table>
		</td>
	</tr>

	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>

*/ ?>


<? /*
<script type="text/javascript">
<!--
var arrCellHtmlOpt;
var maxRowOpt = parseInt('5', 10);

function cellHtmlOptInit() {
	arrCellHtmlOpt = new Array();
	arrCellHtmlOpt[0] = "<input type=\"checkbox\" name=\"pc_option_check[]\" value=\"Y\">";
	arrCellHtmlOpt[1] = "<input type=\"text\" name=\"pc_option_name[]\" class=\"box\" style=\"width:100%;\" maxlength=\"50\">";
	arrCellHtmlOpt[2] = "<input type=\"text\" name=\"pc_option_text[]\" class=\"box\" style=\"width:100%;\" maxlength=\"1000\">";
	arrCellHtmlOpt[3] = "<img id=\"delOpt\" src=\"<?=$sc_url_skin_product?>images/dan_del.gif\" alt=\"삭제\" align=\"absmiddle\" onClick=\"delRowOpt(this, '')\" style=\"cursor:pointer\">";
}

function addRowOpt(kind) {
	var tableID = "tbOpt"+kind;

	var objTb = document.getElementById(tableID);
	var tbRowsLen = objTb.rows.length;

	if (typeof(arrCellHtmlOpt) == "undefined") {
		alert("페이지 로딩중 입니다.");
		return false;
	}

	if (maxRowOpt+1 <= tbRowsLen) {
		alert("더 이상 추가할 수 없습니다.\n\n최대 "+maxRowOpt+"개까지 추가할 수 있습니다.");
		return false;
	}

	var objTbRow = objTb.insertRow(tbRowsLen);

	for(i=0; i<arrCellHtmlOpt.length; i++) {
		objTbRowCell = objTbRow.insertCell(i);
		//objTbRowCell.innerHTML = arrCellHtmlOpt[i].replaceAll('#OPTIONKIND#', kind);
		objTbRowCell.innerHTML = arrCellHtmlOpt[i].split('#OPTIONKIND#').join(kind);

	}
}


// 현재 이벤트객체 Index 가져오기 ##################################################
function getDisObjIdx(obj) {
	var i = 0;
	var result = 0;

	var arrTag = document.getElementsByTagName('*');

	if (obj.sourceIndex) {
		while (arrTag[i].sourceIndex < obj.sourceIndex) {
			if (arrTag[i].id == obj.id) ++result;
			++i;
		}
	}
	else if (obj.compareDocumentPosition) {
		while ((arrTag[i].compareDocumentPosition(obj) & 6) - 3 > 0) {
			if (arrTag[i].id == obj.id) ++result;
			++i;
		}
	}

	return result;
}

function delRowOpt(item, kind) {
	var tableID = "tbOpt"+kind;
	var currIdx = getDisObjIdx(item);

	var objTb = document.getElementById(tableID);

	objTb.deleteRow(currIdx+1);

	if (objTb.rows.length == 1) addRowOpt(kind);
}

cellHtmlOptInit();
//-->
</script>

	<tr>
		<td class="a_txt">
			제품등록시 옵션설정
			<div style="width:100%; text-align:center;">
				<img src="<?=$sc_url_skin_product?>images/option_add.gif" alt="옵션추가" align="absmiddle" onClick="addRowOpt('')" style="cursor:pointer">
			</div>
		</td>
		<td class="a_content">
			<table id="tbOpt" cellpadding="0" cellspacing="0" border="0" width="100%">
				<colgroup>
				<col width="15">
				<col width="170">
				<col width="*">
				<col width="15" align="center">
				</colgroup>
				<tr bgcolor="#F5F5F5" align="center">
					<td></td>
					<td>옵션명</td>
					<td>옵션항목</td>
					<td></td>
				</tr>

				<?
					//$pc_option = "aa|bb|cc|dd";
					//$pc_option = "Y*옵션^남자+여자/0,남자/-15000,여자/-10000|*옵션2^남자+여자/0,남자/-15000,여자/-10000";
					if ( $pc_option ) {
						$Arr_pc_option = explode("|",$pc_option );
//						echo "pc_option : ".$pc_option."<BR>";
//						echo "Arr_pc_option : ".$Arr_pc_option."<BR>";
						for ($i = 0 ; $i < count($Arr_pc_option); $i++ ) {
							$Arr_pc_option[$i] = explode("@",$Arr_pc_option[$i] );
							$Arr_pc_option2[0] = $Arr_pc_option[$i][0];
							$Arr_pc_option[$i] = explode("^", $Arr_pc_option[$i][1] );
							$Arr_pc_option2[1] = $Arr_pc_option[$i][0];
							$Arr_pc_option2[2] = $Arr_pc_option[$i][1];
				?>
				<tr>
					<td><input type="checkbox" name="pc_option_check[]" value="Y" <? if ($Arr_pc_option2[0] == "Y") {  echo "checked"; } ?>></td>
					<td><input type="text" name="pc_option_name[]" class="box" style="width:100%;" maxlength="50" value="<?=$Arr_pc_option2[1]?>"></td>
					<td><input type="text" name="pc_option_text[]" class="box" style="width:100%;" maxlength="1000" value="<?=$Arr_pc_option2[2]?>"></td>
					<td><img id="delOpt" src="<?=$sc_url_skin_product?>images/dan_del.gif" alt="삭제" align="absmiddle" onClick="delRowOpt(this, '')" style="cursor:pointer"></td>
				</tr>
				<?
						}
					}

					if ( $i < 5 ) {
				?>


				<tr>
					<td><input type="checkbox" name="pc_option_check[]" value="Y"></td>
					<td><input type="text" name="pc_option_name[]" class="box" style="width:100%;" maxlength="50"></td>
					<td><input type="text" name="pc_option_text[]" class="box" style="width:100%;" maxlength="1000"></td>
					<td><img id="delOpt" src="<?=$sc_url_skin_product?>images/dan_del.gif" alt="삭제" align="absmiddle" onClick="delRowOpt(this, '')" style="cursor:pointer"></td>
				</tr>
				<?
					}
				?>
			</table>

			<div>카테고리에서 설정한 옵션이 제품등록시 기본적으로 보여지며,<BR> 제품등록 할때 옵션을 별도로 조절할수도 있습니다.</div>
			<div>- 가격을 설정할 수 있는 입력방식입니다.&nbsp;<span style="color:red;">필수선택 옵션은 앞의 체크박스를 선택해 주세요.</span></div>
			<div>- 각 옵션항목은 ","(콤마)와 "/"로 구분하여 입력해 주세요. (옵션항목/적용가격,옵션항목/적용가격)</div>
			<div style="color:#CD883D;"><font color="#FFFFFF">- </font>예) 커플링일 경우 -> 남자+여자/0,남자/-15000,여자/-10000</div>
			<div><span style="color:red;"> ※ 형식이 일치하지 않을경우 에러가 발생할 수 있습니다.</span></div>

		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
*/ ?>
	<tr>
		<td class="a_txt">제품리스트 상단 Include</td>
		<td class="a_content">
			<input type="text" class="a_input" style="width:100%" maxlength="255" name="pc_top_include" value="<?=REQSTR2($pc_top_include)?>">
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품리스트 상단 내용<br><a href="javascript:rows_size(document.PCWriteForm.pc_top_html)">[ 쓰기폼 늘리기 ]</a></td>
		<td class="a_content"><textarea rows="5" style="width:100%" class="a_textarea" name="pc_top_html"><?=REQSTR2($pc_top_html)?></textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품리스트 하단 Include</td>
		<td class="a_content">
			<input type="text" class="a_input" style="width:100%" maxlength="255" name="pc_bottom_include" value="<?=REQSTR2($pc_bottom_include)?>">
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품리스트 하단 내용<br><a href="javascript:rows_size(document.PCWriteForm.pc_bottom_html)">[ 쓰기폼 늘리기 ]</a></td>
		<td class="a_content"><textarea rows="5" style="width:100%" class="a_textarea" name="pc_bottom_html"><?=REQSTR2($pc_bottom_html)?></textarea></td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>



<!-- 	<tr>
		<td class="a_txt">스킨 선택</td>
		<td class="a_content">
			<select name="pc_skin" style="width:190" class="a_input">
			<?
				$dir = opendir($path_skin_product);
				while($dir_list = readdir($dir)) {
					if(!($dir_list == "." or $dir_list == "..")) {
						?><option value="<?=$dir_list?>" <?if($dir_list == $pc_skin) echo "selected"; ?>> <?=$dir_list?> </option><?
					}
				}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">이미지 out</td>
		<td class="a_content">
			<input type="file" style="width:100%" class="a_input" maxlength="255" name="pc_menu_img"><?=$pc_menu_img?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">이미지_over</td>
		<td class="a_content">
			<input type="file" style="width:100%" class="a_input" maxlength="255" name="pc_menu_img_ov"><?=$pc_menu_img_ov?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
 -->
</table>

<!-- <table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 제품 카테고리 권한설정</td>
	</tr>
	<tr>
		<td height="4"></td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">리스트 접근 권한</td>
		<td class="a_content">
			<select name="pc_auth_list" style="width:120px" class="select">
				<option value=''>회원등급검색</option>
				<? foreach ($Arr_u_level as $key => $val) {?>
					<? if ( $key <= $ss_u_level ) { ?>
					<option value="<?=$key?>" <? if ("".$key == $pc_auth_list) echo "selected"; ?>><?=$val?></option>
					<? } ?>
				<? } ?>
			</select>
		</td>
		<td class="a_txt">상세 보기 권한</td>
		<td class="a_content">
			<select name="pc_auth_view" style="width:120px" class="select">
				<option value=''>회원등급검색</option>
				<? foreach ($Arr_u_level as $key => $val) {?>
					<? if ( $key <= $ss_u_level ) { ?>
					<option value="<?=$key?>" <? if ("".$key == $pc_auth_view) echo "selected"; ?>><?=$val?></option>
					<? } ?>
				<? } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
</table> -->

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="80" align="center">
			<input type="image" name="btnSubmit" id="btnSubmit" src="<?=$url_admin?>images/a_btn_submit.gif" hspace="4">
			<img src="<?=$url_admin?>images/a_btn_cancle.gif" width="76" height="28" onClick="javascript:history.back();" style="cursor:hand">
		</td>
	</tr>
</table>
</form>


<? include $path_admin."inc/footer.php"; ?>


<? $dbcon -> dbcon_close();?>

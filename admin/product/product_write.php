<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$pr_idx = REQSTR($pr_idx, "");
	$pc_num = REQSTR($pc_num, "");

	$search_pc_num				= REQSTR($search_pc_num, "");
	$parameter = "search_pc_num=".$search_pc_num;

	// 제품 수정시
	if ( getLen($pr_idx) > 0 ) {
		$SQL = "
			select *
			from
				tbl_product
			where
				pr_idx = '".$pr_idx."'
			limit 0, 1
		";
		$row = $dbcon -> fetch_array($dbcon -> query($SQL));
		extract($row);
		unset($row);
	}

//	echo "pc_num : ".$pc_num."<BR>";
	// 카테고리 설정 가져오기
	if ( !$pr_idx && $pc_num ) {
		$SQL = "
			select pc_option
			from
				tbl_product_category
			where
				pc_num = '".$pc_num."'
			order by
				pc_sort desc
			limit 0, 1
		";
		$pc_option = $dbcon -> getCount($SQL);
		$pr_option = $pc_option;
	}

	$search_pc_num = $pc_num;
?>
<?
	$tm = "product";
	$lm = "";
	include $path_admin."inc/header.php";
?>

<script language='JavaScript'>
<!--
// 입력시 입력값 검사하기
function ProductWriteGo()
{

	var frm = document.ProductWriteForm;

	if(!frm.pr_name.value){
		alert('제품명을 입력해 주세요');
		frm.pr_name.focus();
		return false;
	}

	if(!frm.pc_num.value){
		alert('카테고리를 선택해 주세요');
		return false;
	}


//	if(!frm.pr_content.value) {
//		alert('제품상세설명을 입력하세요!');
//		return false;
//	}

	document.getElementById("btnSubmit").disabled=true;
	return true;
}

function rows_size(idx)
{
	var f = document.ProductWriteForm;
	if(idx=="1"){
		size = f.pr_content_thum;
	}
	else if(idx=="2"){
		size = f.pr_content_thum;
	}

	if(size.rows == 5){
		size.rows = 10; // 세로 size
	}
	else if(size.rows == 10){
		size.rows = 20; // 세로 size
	}
	else if(size.rows == 20){
		size.rows = 40; // 세로 size
	}
	else{
		size.rows = 5; // 원래 세로 size
	}
}

//-->
</script>
<script>

	// 카테고리 옵션값 가져오기
//	function getCategoryOtion() {
//		var pc_num = document.ProductWriteForm.pc_num.value;
//		if (pc_num) {
//			ProductWriteIframe.location.href = "<?=$url_admin?>/product/product_write_catesel.php?pc_num="+pc_num;
//		}
//	}

	// 카테고리 리스트 팝업창
	function PopCategorySelect() {
		PopCategory = window.open('/_util/product/category.php?form_name=ProductWriteForm','PopCategory','width=400,height=400,directories=no,resizable=no,scrollbars=yes');
		PopCategory.focus();
	}

</script>


<iframe id="ProductWriteIframe" name="process" src="" style="width:0;height:0;border:0"></iframe>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">  제품 등록</td>
		<td width="300" align="right">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td align="right">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td bgcolor="#339933" width="150" height="22" align="center" class="a_tab_txt" valign="top" style="padding-top:3px"><a href='<?=$url_admin?>product/product_category_write.php'>최상위 카테고리 등록</a> </td>
								<!-- <td width="2"></td>
								<td bgcolor="#000000" width="150" height="22" align="center" class="a_tab_txt" valign="top" style="padding-top:3px"><a href='<?=$url_admin?>product/product_category_modify_all.php'>카테고리설정 전체변경</a> </td> -->
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
		<td colspan="3" height="19"></td>
	</tr>
</table>

<?
	#### 카테고리 선택해서 왔을때...
	$pc_name = getCategoryName($pc_num);
//	if ( getLen($pr_idx) == 0  && getLen($pc_num) > 0 ) {
//		echo " <script>onload = getCategoryOtion;</script>";
//	}
?>

<form name="ProductWriteForm" method="post" action="<?=$url_admin?>product/product_write_ok.php" ENCTYPE='multipart/form-data' onSubmit="return ProductWriteGo()">
<input type="hidden" name="pr_idx" value="<?=$pr_idx?>">
<input type="hidden" name="pc_num_old" value="<?=$pc_num?>" readonly>
<input type="hidden" name="pc_num" value="<?=$pc_num?>" readonly>
<input type="hidden" name="search_pc_num" value="<?=$search_pc_num?>" readonly>


<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 제품 정보 입력</td>
	</tr>
	<tr>
		<td colspan="2" height="2"></td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="2" class="a_line_2px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품명</td>
		<td class="a_content"><input type="text" name="pr_name" style="width:100%" maxlength="255" class="a_input" value="<?=$pr_name?>"></td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품카테고리</td>
		<td class="a_content">
			<input type="text" name="pc_name" value="<?=$pc_name?>" readonly onclick="PopCategorySelect();" style="cursor:hand;text-align:center;">
			<!-- <input type="text" name="pc_num_location" value="<?=$pc_num_location?>" style="border:0px;width:100%" readonly> -->
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">제품출력순위</td>
		<td class="a_content">
			<input type="hidden" name="pr_sort_old" value="<?=$pr_sort?>" readonly>
			<input type="text" name="pr_sort" value="<?=$pr_sort?>" maxlength="3" <?=$OnlyNum?> class="a_input" style="width:50px;text-align:center;">
			( 자동 계산 됩니다. )
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>



<!--
	<tr>
		<td class="a_txt">상품 판매여부</td>
		<td class="a_content">
			<input type="radio" name="pr_sale_yn" value="Y" <? if ($pr_sale_yn == "Y" ) echo "checked"; ?>>판매함
			<input type="radio" name="pr_sale_yn" value="N" <? if ( $pr_sale_yn == "" || $pr_sale_yn == "N" ) echo "checked"; ?>>판매안함
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>


	<tr>
		<td class="a_txt">상품 원가</td>
		<td class="a_content">
			<input type="text" name="pr_cost" value="<?=$pr_cost?>" class="a_input" <?=$OnlyNum?>> 원 (숫자만 입력)
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">판매가격</td>
		<td class="a_content">
			<input type="text" name="pr_price" value="<?=$pr_price?>" class="a_input" <?=$OnlyNum?>> 원 (숫자만 입력)
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>


	<tr>
		<td class="a_txt">재고량</td>
		<td class="a_content">
			<input type="text" name="pr_stock" value="<?=$pr_stock?>" class="a_input" <?=$OnlyNum?>> 원 (숫자만 입력)
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>


	<tr>
		<td class="a_txt">무료배송</td>
		<td class="a_content">
			<input type="checkbox" name="pr_free_delivery" value="1" <? if($pr_free_delivery > "0") echo "checked"; ?>> 무료배송시 체크하여 주십시오.<br>
			1. 관리자가 정한 배송 하한선이 안되더라도 이 상품 선택시 무료 배송으로 간주됨<br>
			2. 다른 상품과 같이 구매해도 무료배송 되므로 주의 하십시오<br>
			3. 배송택배 선택 보다 우선 되므로 다른배송 서비스를 사용 하는 경우 유의하십시오.
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>

 -->



<script type="text/javascript">
<!--
/*
var arrCellHtmlOpt;
var maxRowOpt = parseInt('5', 10);

function cellHtmlOptInit() {
	arrCellHtmlOpt = new Array();
	arrCellHtmlOpt[0] = "<input type=\"checkbox\" name=\"pr_option_check[]\" value=\"Y\">";
	arrCellHtmlOpt[1] = "<input type=\"text\" name=\"pr_option_name[]\" class=\"box\" style=\"width:100%;\" maxlength=\"50\">";
	arrCellHtmlOpt[2] = "<input type=\"text\" name=\"pr_option_text[]\" class=\"box\" style=\"width:100%;\" maxlength=\"1000\">";
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
*/
//-->
</script>
<!--
	<tr>
		<td class="a_txt">
			제품 옵션
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
					//$pr_option = "aa|bb|cc|dd";
					//$pr_option = "Y*옵션^남자+여자/0,남자/-15000,여자/-10000|*옵션2^남자+여자/0,남자/-15000,여자/-10000";
					if ( $pr_option ) {
						$Arr_pr_option = explode("|",$pr_option );
//						echo "pr_option : ".$pr_option."<BR>";
//						echo "Arr_pr_option : ".$Arr_pr_option."<BR>";
						for ($i = 0 ; $i < count($Arr_pr_option); $i++ ) {
							$Arr_pr_option[$i] = explode("@",$Arr_pr_option[$i] );
							$Arr_pr_option2[0] = $Arr_pr_option[$i][0];
							$Arr_pr_option[$i] = explode("^", $Arr_pr_option[$i][1] );
							$Arr_pr_option2[1] = $Arr_pr_option[$i][0];
							$Arr_pr_option2[2] = $Arr_pr_option[$i][1];
				?>
				<tr>
					<td><input type="checkbox" name="pr_option_check[]" value="Y" <? if ($Arr_pr_option2[0] == "Y") {  echo "checked"; } ?>></td>
					<td><input type="text" name="pr_option_name[]" class="box" style="width:100%;" maxlength="50" value="<?=$Arr_pr_option2[1]?>"></td>
					<td><input type="text" name="pr_option_text[]" class="box" style="width:100%;" maxlength="1000" value="<?=$Arr_pr_option2[2]?>"></td>
					<td><img id="delOpt" src="<?=$sc_url_skin_product?>images/dan_del.gif" alt="삭제" align="absmiddle" onClick="delRowOpt(this, '')" style="cursor:pointer"></td>
				</tr>
				<?
						}
					}

					if ( $i < 5 ) {
				?>


				<tr>
					<td><input type="checkbox" name="pr_option_check[]" value="Y"></td>
					<td><input type="text" name="pr_option_name[]" class="box" style="width:100%;" maxlength="50"></td>
					<td><input type="text" name="pr_option_text[]" class="box" style="width:100%;" maxlength="1000"></td>
					<td><img id="delOpt" src="<?=$sc_url_skin_product?>images/dan_del.gif" alt="삭제" align="absmiddle" onClick="delRowOpt(this, '')" style="cursor:pointer"></td>
				</tr>
				<?
					}
				?>
			</table>
			<div>- 가격을 설정할 수 있는 입력방식입니다.&nbsp;<span style="color:red;">필수선택 옵션은 앞의 체크박스를 선택해 주세요.</span></div>
			<div>- 각 옵션항목은 ","(콤마)와 "/"로 구분하여 입력해 주세요. (옵션항목/적용가격,옵션항목/적용가격)</div>
			<div style="color:#CD883D;"><font color="#FFFFFF">- </font>예) 커플링일 경우 -> 남자+여자/0,남자/-15000,여자/-10000</div>
			<div><span style="color:red;"> ※ 형식이 일치하지 않을경우 에러가 발생할 수 있습니다.</span></div>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>

 -->

	<tr>
		<td class="a_txt">제품 간략 설명<!-- <br><a href="javascript:rows_size(1)">[ 쓰기폼 늘리기 ]</a> --></td>
		<td class="a_content">
			<?php
				$pr_content_thum = RESSTR($pr_content_thum);
				getEditor("pr_content_thum", $pr_content_thum, "100%", "300");
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">상세설명</td>
		<td class="a_content">
			<?php
				$pr_content = RESSTR($pr_content, "e");
				getEditor("pr_content", $pr_content, "100%", "500");
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
<? /*
	<tr>
		<td class="a_txt">ENGLISH</td>
		<td class="a_content">
			<?php
				$pr_content2 = RESSTR($pr_content2);
				getEditor("pr_content2", $pr_content2, "100%", "500");
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
*/ ?>
<!--
	<tr>
		<td class="a_txt">상세설명3/td>
		<td class="a_content">
			<?php
				$pr_content3 = RESSTR($pr_content3);
				getEditor("pr_content3", $pr_content3, "100%", "500");
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">소프트웨어 소개</td>
		<td class="a_content">
			<?php
				$pr_content4 = RESSTR($pr_content4);
				getEditor("pr_content4", $pr_content4, "100%", "500");
			?>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
 -->

	<?
		$ObjFileName = "pr_img_thum";
		$ObjFileCnt = 1;
		$FileCnt = 0;
		if ( $pr_idx ) {
			$SQL = "
				select * from tbl_file
				where
					category = '".$ObjFileName."'
					and seq = '".$pr_idx."'
				order by idx desc
				limit 0, ".$ObjFileCnt."
			";
			$FileRS = $dbcon -> query($SQL);
			$FileCnt = $dbcon -> num_rows($FileRS);
		}

	?>
	<tr>
		<td class="a_txt">
			제품리스트 이미지
			<!-- <span onclick="add_file('Tbl<?=$ObjFileName?>', <?=$ObjFileCnt?>, '<input type=file name=<?=$ObjFileName?>[] id=<?=$ObjFileName?> style=width:50% maxlength=255 class=a_input>');" style='cursor:pointer; font-family:tahoma; font-size:12pt;'>+</span>
			<span onclick="del_file('Tbl<?=$ObjFileName?>');" style='cursor:pointer; font-family:tahoma; font-size:12pt;'>-</span> -->
			<BR>(160 * 160)
		</td>
		<td class="a_content">
			<table id="Tbl<?=$ObjFileName?>" width="100%" cellpadding=0 cellspacing=0></table>
			<?

				if ( $FileCnt ) {
					$i = 0;
					while ( $FileRow = $dbcon->fetch_array($FileRS) ) {
						?>
						<script>add_file('Tbl<?=$ObjFileName?>', <?=$ObjFileCnt?>, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" class="a_input" disabled> 삭제 <input type="checkbox" name="<?=$ObjFileName?>_del[]" value="<?=$FileRow["idx"]?>" onclick="UseFile(this, \'<?=$ObjFileName?>\', \'<?=$i?>\')"> <BR>등록파일 : <a href="javascript:ProductDownGo(\'<?=$FileRow["idx"]?>\');"><?=$FileRow["file_realname"]?></a> <?=PrintFileSize($FileRow["file_size"])?>');</script>
						<?
						$i++;
					}
				}
				// 신규 파일 등록
				else {
					?>
						<script>add_file('Tbl<?=$ObjFileName?>', <?=$ObjFileCnt?>, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" class="a_input">');</script>
					<?
				}
			?>

			<table id="<?=$ObjFileName?>" width="0" cellpadding=0 cellspacing=0></table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>


	<?
		$ObjFileName = "pr_img";
		$ObjFileCnt = 5;
		$FileCnt = 0;
		if ( $pr_idx ) {
			$SQL = "
				select * from tbl_file
				where
					category = '".$ObjFileName."'
					and seq = '".$pr_idx."'
				order by idx desc
				limit 0, ".$ObjFileCnt."
			";
			$FileRS = $dbcon -> query($SQL);
			$FileCnt = $dbcon -> num_rows($FileRS);
		}

	?>
	<tr>
		<td class="a_txt">
			제품상세 이미지

			<span onclick="add_file('Tbl<?=$ObjFileName?>', <?=$ObjFileCnt?>, '<input type=file name=<?=$ObjFileName?>[] id=<?=$ObjFileName?> style=width:50% maxlength=255 class=a_input>');" style='cursor:pointer; font-family:tahoma; font-size:12pt;'>+</span>
			<span onclick="del_file('Tbl<?=$ObjFileName?>');" style='cursor:pointer; font-family:tahoma; font-size:12pt;'>-</span>
			<BR>(305 * 305)
		</td>
		<td class="a_content">
			<table id="Tbl<?=$ObjFileName?>" width="100%" cellpadding=0 cellspacing=0></table>
			<?

				if ( $FileCnt ) {
					$i = 0;
					while ( $FileRow = $dbcon->fetch_array($FileRS) ) {
						?>
						<script>add_file('Tbl<?=$ObjFileName?>', <?=$ObjFileCnt?>, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" class="a_input" disabled> 삭제 <input type="checkbox" name="<?=$ObjFileName?>_del[]" value="<?=$FileRow["idx"]?>" onclick="UseFile(this, \'<?=$ObjFileName?>\', \'<?=$i?>\')"> <BR>등록파일 : <a href="javascript:ProductDownGo(\'<?=$FileRow["idx"]?>\');"><?=$FileRow["file_realname"]?></a> <?=PrintFileSize($FileRow["file_size"])?>');</script>
						<?
						$i++;
					}
				}
				// 신규 파일 등록
				else {
					?>
						<script>add_file('Tbl<?=$ObjFileName?>', <?=$ObjFileCnt?>, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" class="a_input">');</script>
					<?
				}
			?>

			<table id="<?=$ObjFileName?>" width="0" cellpadding=0 cellspacing=0></table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>



<? /*

	<?
		$ObjFileName = "pr_file";
		$ObjFileCnt = 1;
		$FileCnt = 0;
		if ( $pr_idx ) {
			$SQL = "
				select * from tbl_file
				where
					category = '".$ObjFileName."'
					and seq = '".$pr_idx."'
				order by idx desc
				limit 0, ".$ObjFileCnt."
			";
			$FileRS = $dbcon -> query($SQL);
			$FileCnt = $dbcon -> num_rows($FileRS);
		}

	?>
	<tr>
		<td class="a_txt">
			브로슈어 파일
			<!-- <span onclick="add_file('Tbl<?=$ObjFileName?>', <?=$ObjFileCnt?>, '<input type=file name=<?=$ObjFileName?>[] id=<?=$ObjFileName?> style=width:50% maxlength=255 class=a_input>');" style='cursor:pointer; font-family:tahoma; font-size:12pt;'>+</span>
			<span onclick="del_file('Tbl<?=$ObjFileName?>');" style='cursor:pointer; font-family:tahoma; font-size:12pt;'>-</span> -->
		</td>
		<td class="a_content">
			<table id="Tbl<?=$ObjFileName?>" width="100%" cellpadding=0 cellspacing=0></table>
			<?

				if ( $FileCnt ) {
					$i = 0;
					while ( $FileRow = $dbcon->fetch_array($FileRS) ) {
						?>
						<script>add_file('Tbl<?=$ObjFileName?>', <?=$ObjFileCnt?>, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" class="a_input" disabled> 삭제 <input type="checkbox" name="<?=$ObjFileName?>_del[]" value="<?=$FileRow["idx"]?>" onclick="UseFile(this, \'<?=$ObjFileName?>\', \'<?=$i?>\')"> <BR>등록파일 : <a href="javascript:ProductDownGo(\'<?=$FileRow["idx"]?>\');"><?=$FileRow["file_realname"]?></a> <?=PrintFileSize($FileRow["file_size"])?>');</script>
						<?
						$i++;
					}
				}
				// 신규 파일 등록
				else {
					?>
						<script>add_file('Tbl<?=$ObjFileName?>', <?=$ObjFileCnt?>, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" class="a_input">');</script>
					<?
				}
			?>

			<table id="<?=$ObjFileName?>" width="0" cellpadding=0 cellspacing=0></table>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>
*/ ?>

	<tr>
		<td class="a_txt">
			조달쇼핑몰 링크
		</td>
		<td class="a_content">
			<input type="text" name="pr_link" style="width:100%" maxlength="255" class="a_input" value="<?=$pr_link?>">
		</td>
	</tr>
	<tr>
		<td colspan="2" class="a_line_1px">&nbsp;</td>
	</tr>





</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="80" align="center">
			<input type="image" name="btnSubmit" id="btnSubmit" src="<?=$url_admin?>images/a_btn_submit.gif" hspace="4">
			<img src="<?=$url_admin?>images/a_btn_cancle.gif" width="76" height="28" onClick="javascript:document.location.href='<?=$url_admin?>product/product_list.php?<?=$parameter?>'" style="cursor:hand">
		</td>
	</tr>
</table>
</form>
<script>
	function ProductDownGo(idx) {
//		PFrame = document.all["ProductFrame"];
//		alert(PFrame.src);
		ProductFrame.location.href = "<?=$url_product?>product_download.php?idx="+idx;
	}
</script>
<iframe src="a" frameborder="0" width="0" height="0" id="ProductFrame"></iframe>
<script>
	function setFileDisabled(obj, objfile, num) {
		objfile = document.all[objfile];

		if (obj.checked == true) {
			objfile[num].disabled = false;
		}
		else {
			objfile[num].disabled = true;
		}
	}
</script>

<? include $path_admin."inc/footer.php"; ?>

<? $dbcon -> dbcon_close();?>

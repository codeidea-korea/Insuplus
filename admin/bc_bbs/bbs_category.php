<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$category = REQSTR($category,"");
	$bc_id = REQSTR($bc_id,"");

	$category_name = REQSTR($category_name,"");

	$temp_idx = REQSTR($idx,"");

	$field = " * ";
	$table = " tbl_category ";
	$where = " and category = '".$category."'  and bc_id = '".$bc_id."' ";
	$orderby = " cate_sort asc ";
	$limit = " ";

	$ArrCateListRs = $dbcon -> getList($field = "*", $table , $where, $orderby, $limit);

	$CateTotalCount = $ArrCateListRs[0];
?>
	<!-- ########################## 컨텐츠 영역 START ##########################-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="/_css/admin.css" rel="stylesheet" type="text/css">
<meta content="IE=9" http-equiv="X-UA-Compatible" />
<?php getLib();?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script src="/admin/js/admin.js"></script>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<th colspan="2"><?=$category_name?></th>
	</tr>
	<tr>
		<th width="80">no</th>
		<th>카테고리</th>
		<th>카테고리 영문</th>
		<th width="80">삭제</th>
	</tr>
<?
	if ( $CateTotalCount == 0 ) {
?>
	<tr>
		<td colspan="3" align="center">등록된 내용이 없습니다.</td>
	</tr>
<?
	} else {
		$no = 1;
		$flag = false;
		while ( $CateListRs = $dbcon -> fetch_array($ArrCateListRs[1]) ) {
			extract($CateListRs);

			$bgcolor = "";
			if ( $temp_idx == $idx ) {
				$temp_idx				= $idx;
				$temp_cate_name		= $cate_name;
				$temp_cate_name_en= $cate_name_en;
				$temp_cate_sort				= $cate_sort;
				$temp_regdate		= $regdate;
				$bgcolor = "#FFFFCC";
				$flag = true;
			}
?>
	<tr bgcolor="<?=$bgcolor?>">
		<td width="80" align="center"><?=$no?></td>
		<td><a href="javascript:select_go('<?=$idx?>');"><?=$cate_name?></a></td>
		<td><a href="javascript:select_go('<?=$idx?>');"><?=$cate_name_en?></a></td>
		<td width="80" align="center"><a href="javascript:next_go('delgo','<?=$idx?>')">삭제</a></td>
	</tr>
<?
			$no++;
		}
	}

?>
</table>


<table class="tableCss" style="width:100%">
	<tr>
		<th width="150">추가/수정</th>

		<form name="CateWForm" action="" method="post">
		<input type="hidden" name="idx" value="<?=$temp_idx?>">
		<input type="hidden" name="category" value="<?=$category?>">
		<input type="hidden" name="bc_id" value="<?=$bc_id?>">
		<input type="hidden" name="mode" value="">
		<td><input type="text" name="cate_name" value="<?=$temp_cate_name?>"></td>
		<td><input type="text" name="cate_name_en" value="<?=$temp_cate_name_en?>"></td>
		</form>

		<th width="150" class="noLine">
			<? if ( getLen($temp_idx) > 0 && $flag == true ) { ?>
			<input type="button" value="수정" onclick="next_go('modify','<?=$temp_idx?>')">
			<? } ?>
			<input type="button" value="추가" onclick="next_go('insert','')">
		</th>
	</tr>
</table>

<script>
	function testetst() {
		key = event.keyCode;
		if ( key == 13 ) {// 키보드 상단 숫자키
			event.returnValue = false;
		}
	}

	function select_go(Tempidx) {
		location.href = "?idx="+Tempidx+"&category=<?=$category?>&bc_id=<?=$bc_id?>"
	}

	function next_go(Tempmode, Tempidx) {
		ff = document.CateWForm;
		ff.mode.value = Tempmode;


		if (!ff.mode.value) {
			alert("입력 모드 에러");
			return;
		}

		if ((Tempmode == "modify" || Tempmode == "delgo") ) {
			ff.idx.value = Tempidx;

			if (!ff.idx.value) {
				alert("인덱스값이 없습니다.");
				return;
			}
		}

		if ( (Tempmode == "modify" || Tempmode == "insert") && !ff.cate_name.value) {
			alert("카테고리명을 입력하여 주십시오.");
			ff.cate_name.focus();
			return;
		}

		ff.action = "bbs_category_ok.php";
		ff.submit();
	}

	//window.attchEvent("onload",OnLoadFunc);
	//onload = OnLoadFunc;

	function OnLoadFunc() {
		document.CateWForm.name.focus();
	}
</script>


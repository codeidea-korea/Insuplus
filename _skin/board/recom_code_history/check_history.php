<?
	include_once $_SERVER['DOCUMENT_ROOT']."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>

<?
$code = REQSTR($code, "");
$field = "partnership_name, partnership_code";
$table = "tbl_board_partner";
$where = "";
$orderby = "";
$limit = " ";
$ArrPartnerListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="/_css/admin.css" rel="stylesheet" type="text/css">
<meta content="IE=9" http-equiv="X-UA-Compatible" />
<?php getLib();?>
<script src="/js/jquery.min.js"></script>
<script type="text/javascript" src="/js/ecaso.js"></script>
<script src="/admin/js/admin.js"></script>
</head>
<body style="background-color:#FFFFFF;">

<table width="100%">
	<tr>
		<td>
			<ul class="menu_tab_01">
				<li class="on">
					<span class="on"><a href="#void">매체자료 업로드</a></span>
				</li>
			</ul>
		</td>
	</tr>
	<tr>
		<td style="padding:10px 0;"></td>
	</tr>
	<tr>
		<td style="padding:0px 10px;">



<div class="popup_con_wrap" style="height:303px; margin:0px;padding:4px;background:#FFFFFF;">




	<div style="height:5px;"></div>

<script language="javascript">
function goOpen() {
	var form1 = document.frmChk;

	if(!form1.transport_file1.value) {
		window.alert ("업로드 할 자료 파일을 선택해주세요");
		form1.transport_file1.focus();
		return false;
	}

	form1.submit();
}
</script>

<form name="frmChk" action="?mode=modi" method="post" ENCTYPE="multipart/form-data" onsubmit="return false;">
<input type="hidden" name="MAX_FILE_SIZE" value="300000000">
	<div style="height:5px;"></div>

	<div style="background:#9b9b9b; color:#FFFFFF; font-weight:bold; padding:5px 0 2px 5px;">자료 파일 업로드</div>
	<table class="order_detail_03">
		<colgroup>
			<col width="" />
			<col width="" />
		</colgroup>

		<tr>
			<th width="150px">자료 파일</th>
			<td style="text-align:left;">
			  <input type="file" name="transport_file1" class="file_M" size="40" />
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<span class="inp_black1"><input type="button" value="자료 업로드" onClick="goOpen()"></span>
			</td>
		</tr>
	</table>
	<div style="height:10px;"></div>
</div>

</form>


		</td>
	</tr>
</table>

</BODY>
</html>

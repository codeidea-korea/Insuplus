<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	// 관리자 체크
	admin_chk($auth_admin, $url_admin_login_out);
?>
<?
	$tm = "product";
	$lm = "";
?>
<html>
<head>
<title>제품카테고리 설정 가져오기</title>
<link href="<?=$url_admin?>inc/admin.css" rel="stylesheet" type="text/css">
</head>

<body>



<script language='JavaScript'>
<!--
function configSet(tmp,type)
{
    if (type=='modify'){
        opener.document.location.href = "<?=$url_admin?>product/product_category_modify.php?cid=&cid_other=" + tmp;
    }
    else if (type=='modify_all') {
        opener.document.location.href = "<?=$url_admin?>product/product_category_modify_all.php?cid_other=" + tmp;
    }
    else if (type=='write') {
        opener.document.location.href = "<?=$url_admin?>product/product_category_write.php?cid=&cid_other=" + tmp;
    }
    window.close();
}
//-->
</script>

<table border="0" cellpadding="20" cellspacing="0" width="100%">
	<tr>
		<td>

			<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
					<td valign="top" class="a_st">제품카테고리 설정 가져오기</td>
					<td align="right">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td bgcolor="#7F7F7F" width="55" align="center" class="a_tab_txt" valign="top" style="padding-top:3px"><a href='javascript:window.close();'>창닫기</a></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="3" height="1" bgcolor="#D5D5D5"></td>
				</tr>
				<tr>
					<td colspan="3" height="20"></td>
				</tr>
			</table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 사용방법</td>
					<td align="right" style="font-family:Dotum;font-size:11px;letter-spacing:-1px;padding-top:0px" valign="top">설정을 가져올 카테고리를 확인한 후 우측 <img src="<?=$url_admin?>images/a_icon_configset.gif" align="top"> 버튼을 클릭하시면 됩니다.</td>
				</tr>
				<tr>
					<td colspan="2" height="2"></td>
				</tr>
			</table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td colspan="4" height="2" bgcolor="#D5D5D5"></td>
				</tr>
				<tr>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" height="28">제품 카테고리명</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="80">카테고리 코드</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="100" >스킨</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="65">적용하기</td>
				</tr>

				<tr>
					<td class="a_content_td" height="28">
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td class="a_content_td" width="5"></td>
								<td width="35" class="a_content_td"><img src=<?=$url_admin?>images/a_icon_lev1.gif></td>
								<td class="a_content_td" width="4"></td>
								<td class="a_content_td" style="padding-top:1px">제품카테고리2</td>
							</tr>
						</table>
					</td>
					<td align="center" class="a_content_td"><font color="#FF6600"><b>1</b></font></td>
					<td align="center" class="a_content_td">product_gray</td>
					<td align="center" class="a_content_td"><img src="<?=$url_admin?>images/a_icon_configset.gif" onClick="javascript:configSet('1','')" style="cursor:hand" alt="카테고리 설정 적용하기"></td>
				</tr>
				<tr>
					<td colspan="4" height="1" bgcolor="#E5E5E5"></td>
				</tr>

				<tr>
					<td class="a_content_td" height="28">
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td class="a_content_td" width="5"></td>
								<td width="35" class="a_content_td"><img src=<?=$url_admin?>images/a_icon_lev1.gif></td>
								<td class="a_content_td" width="4"></td>
								<td class="a_content_td" style="padding-top:1px">제품카테고리1</td>
							</tr>
						</table>
					</td>
					<td align="center" class="a_content_td"><font color="#FF6600"><b>2</b></font></td>
					<td align="center" class="a_content_td">product_gray</td>
					<td align="center" class="a_content_td"><img src="<?=$url_admin?>images/a_icon_configset.gif" onClick="javascript:configSet('2','')" style="cursor:hand" alt="카테고리 설정 적용하기"></td>
				</tr>
				<tr>
					<td colspan="4" height="1" bgcolor="#E5E5E5"></td>
				</tr>

				<tr>
					<td class="a_content_td" height="28">
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td class="a_content_td" width="13"></td>
								<td width="35" class="a_content_td"><img src=<?=$url_admin?>images/a_icon_lev2.gif></td>
								<td class="a_content_td" width="4"></td>
								<td class="a_content_td" style="padding-top:1px">카테고리1_2</td>
							</tr>
						</table>
					</td>
					<td align="center" class="a_content_td"><font color="#FF6600"><b>2.01</b></font></td>
					<td align="center" class="a_content_td">product_gray</td>
					<td align="center" class="a_content_td"><img src="<?=$url_admin?>images/a_icon_configset.gif" onClick="javascript:configSet('2.01','')" style="cursor:hand" alt="카테고리 설정 적용하기"></td>
				</tr>
				<tr>
					<td colspan="4" height="1" bgcolor="#E5E5E5"></td>
				</tr>

				<tr>
					<td class="a_content_td" height="28">
						<table cellspacing="0" cellpadding="0" border="0">
							<tr>
								<td class="a_content_td" width="13"></td>
								<td width="35" class="a_content_td"><img src=<?=$url_admin?>images/a_icon_lev2.gif></td>
								<td class="a_content_td" width="4"></td>
								<td class="a_content_td" style="padding-top:1px">카테고리1_1</td>
							</tr>
						</table>
					</td>
					<td align="center" class="a_content_td"><font color="#FF6600"><b>2.02</b></font></td>
					<td align="center" class="a_content_td">product_gray</td>
					<td align="center" class="a_content_td"><img src="<?=$url_admin?>images/a_icon_configset.gif" onClick="javascript:configSet('2.02','')" style="cursor:hand" alt="카테고리 설정 적용하기"></td>
				</tr>
				<tr>
					<td colspan="4" height="1" bgcolor="#E5E5E5"></td>
				</tr>
			</table>

		</td>
	</tr>
</table>



<? $dbcon -> dbcon_close();?>

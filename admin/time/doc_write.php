<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<link href="/_css/admin.css" rel="stylesheet" type="text/css">
<link href="/share/css/content.css" rel="stylesheet" type="text/css">

					<table width="250" border="0" cellspacing="0" cellpadding="0" class="mgt10" align="center">
<form name="frm" method="post" action="submit_doctors_ins.php">
<input type="hidden" name="days" value="<?=$days?>">
<input type="hidden" name="section" value="<?=$sec?>">
					  <tr>
						<td class="bg_gry"></td>
					  </tr>
					  <tr>
						<td  class="pdlr25 bg_gry" valign="top">
						<table>
						<tr>
						<td>
						의료진 성함을 추가해주세요.
						</td>
						</tr>
						<tr>
						<td>
						<div class="mgt10"><input type="text" name="doc_name"></div>
						</td>
						</tr>
						</table>
						</td>
					  </tr>
					  <tr>
						<td class="bg_gry"></td>
					  </tr>
					  <tr>
						<td align="center" height="50"><input type="submit" value="저장"></td>
					  </tr>
</form>
					</table>
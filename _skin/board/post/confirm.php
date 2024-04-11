
<script>
	function ConfirmGo() {
		ff = document.ConfirmForm;
		<? if ($auth_level < $auth_admin) { ?>
			if (!ff.passwd.value) {
				alert("비밀번호를 입력하여 주십시오.");
				ff.passwd.focus();
				return false;
			}
		<? } ?>
		ff.mode.value = "<?=$act_mode?>";
		ff.action = "<?=$PHP_SELF?>";
	}

	function setFocus() {
		document.ConfirmForm.passwd.focus();
	}

	window.onload = function () {
		setFocus();
	}
</script>

<form name="ConfirmForm" action="" method="post" onsubmit="return ConfirmGo()">
<input type="hidden" name="act" value="confirm">
<input type="hidden" name="seq" value="<?=$seq?>">
<input type="hidden" name="mode" value="">
<input type="hidden" name="page" value="<?=$page?>">


<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="center">
			<table width="400" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="20"></td>
				</tr>
				<tr>
					<td align="center"><img src="<?=$url_skin_board.$bc_skin?>/images/b_confirm_title.gif" width="237" height="25" vspace="10" /></td>
				</tr>
				<tr>
					<td class="b_color"></td>
				</tr>
				<tr>
					<td align="center" bgcolor="fafafa">
						<br />
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td>
									<? if ($auth_level < $auth_admin) { ?>
										<input name="passwd" type="password" maxlength="20" size="23" class="input"/>
									<? } ?>
								</td>
								<td style="vertical-align:middle;">
									<input type="image" src="<?=$url_skin_board.$bc_skin?>/images/b_confirm_ok.gif" style="vertical-align:middle;"/>
									<a href="javascript:history.back();"><img src="<?=$url_skin_board.$bc_skin?>/images/b_confirm_cancle.gif" style="vertical-align:middle;"/></a>
								</td>
							</tr>
						</table>
						<br />
					</td>
				</tr>
				<tr>
					<td class="b_color"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</form>

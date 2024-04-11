<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	include_once($path_editor.'/func_editor.php');
	$mail_content = "";
?>

<?
	$tm = "member";
	$lm = "";
	include $path_admin."inc/header.php";
?>

<?
	$html = new html;
	echo $html -> getAdminTitle("회원메일발송");
?>


	<!-- ########################## 컨텐츠 영역 START ##########################-->


<table class="tableCss" style="width:90%">
<form name="MailForm" method="post" action="mail_form_ok.php" onsubmit="return NextGo()"><!-- enctype='multipart/form-data' -->
<input type="hidden" name="contenttype" value="0"><!-- 텍스트 타입 ( 0 : html, 1 : text ) -->
	<tr>
		<th>보내는 분</font></th>
		<td>
			<input size=50 name="mail_from_name" value="<?=$sc_admin_name?>">
			(예) 관리자
		</td>
	</tr>
	<tr>
		<th>보내는분 이메일</th>
		<td>
			<input name="mail_from_email" size="50" value="<?=$sc_admin_email?>">
			(예) admin@admin.co.kr
		</td>
	</tr>
	<tr>
		<th>제목</th>
		<td>
			<input type="text" name="mail_subject" style="width:90%;">
		</td>
	</tr>

	<tr>
		<th>내용</th>
		<td>
			<?
//				echo myEditor('모드','에디터경로','폼이름','필드이름','폼사이즈','폼높이','랭귀지');
				echo myEditor(1, $path_editor, $url_editor,'MailForm','mail_content','90%','200','utf-8');
			?>
		</td>
	</tr>
	<tr>
		<th>받는분 설정</th>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td style="border:0px;">
						<input type="radio" name="user_type" value="1" onclick="chk_touser()" CHECKED> 개인 이메일 (테스트용으로 사용하세요)
						<BR>
						<input type="radio" name="user_type" value="2" onclick="chk_touser()"> 전체
						(수신동의한 모든 회원에게 보냅니다.)
					</td>
				</tr>

				<tr>
					<td valign="top" style="border:0px;padding:0px"> &nbsp;
						<table border="0" cellspacing="0" cellpadding="0" style="border:0px;padding:0px;display:;" id="ToUser">
							<tr>
								<td style="border:0px;" width="50">
									<textarea name="mail_to_email" cols="30" rows="10"></textarea>
								</td>
								<td style="border:0px;" width="5">&nbsp;</td>
								<td style="border:0px;padding:0px" valign="top">
									받으실 분의 이메일 주소를 적어주세요.<BR>
									개인 이메일을 사용하여 충분히 테스트 후에<BR> 전체메일을 보내주세요<BR>
									각 이메일 구분은 줄(엔터키)로 합니다.<BR>
									예)<BR>
									test@gmail.com<BR>
									test1@nate.com<BR>
									test2@naver.com<BR>

								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr align="center" bgcolor=#ffffff>
		<td colspan=2><input type="submit" value="전송"></td>
	</tr>
</form>
</TABLE>

<script>
	function chk_touser() {
		ff = document.MailForm;
		ObjToUser = document.getElementById("ToUser");
		//alert(ObjToUser);
		if (ff.user_type[0].checked == true) {
			ObjToUser.style.display = "";
		}
		else {
			ObjToUser.style.display = "none";
		}
	}

	function NextGo() {
		editor_wr_ok();
	}
</script>

<!-- ### 페이지 끝 ###  -->
<?
	$dbcon -> dbcon_close();
?>
<? include $path_admin."inc/footer.php"; ?>

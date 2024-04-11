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
<form name="MailForm" method="post" action="member_sms_ok.php" onsubmit="return NextGo()"><!-- enctype='multipart/form-data' -->
<input type="hidden" name="contenttype" value="0"><!-- 텍스트 타입 ( 0 : html, 1 : text ) -->
	<tr>
		<th>보내는 분</font></th>
		<td>
			<input size=50 name="from_hp" value="025177024">
			(예) 07012345678
		</td>
	</tr>
	<tr>
		<th>제목</th>
		<td>
			<input type="text" name="sms_subject" style="width:90%;">
		</td>
	</tr>

	<tr>
		<th>내용</th>
		<td>
			<textarea name="sms_content" cols="40" rows="10"></textarea>
		</td>
	</tr>
	<tr>
		<th>받는분 설정</th>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td style="border:0px;">
						<input type="radio" name="user_type" value="1" onclick="chk_touser()" CHECKED> 개인 휴대폰 (테스트용으로 사용하세요)
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
									<textarea name="mail_to_email" cols="40" rows="10"></textarea>
								</td>
								<td style="border:0px;" width="5">&nbsp;</td>
								<td style="border:0px;padding:0px" valign="top">
									받으실 분의 휴대폰번호를 적어주세요.<BR>
									개인 휴대폰번호를 사용하여 충분히 테스트 후에<BR> 전체SMS를 보내주세요<BR>
									각 휴대폰 구분은 줄(엔터키)로 합니다.<BR>
									예)<BR>
									01012345678<BR>
									01011112222<BR>
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
	}
</script>

<!-- ### 페이지 끝 ###  -->
<?
	$dbcon -> dbcon_close();
?>
<? include $path_admin."inc/footer.php"; ?>

<?
"select * from tbl_user where substr(u_regdate,1,10)>'2014-04-01' and u_sms_receipt='1' and u_state='1'";
?>

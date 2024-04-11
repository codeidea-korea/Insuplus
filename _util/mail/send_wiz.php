<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<table>
<form name=messageform method=post action="send_wiz_ok.php" ><!-- enctype='multipart/form-data' -->
	<tr>
		<td align=middle bgcolor=E6E9EA><font color="#000000">보내는 분</font></td>
		<td align=left bgcolor=#ffffff><input size=50 name="FromName">(예) 숍핑몰 관리자</font></td>
	</tr>
	<tr>
		<td align=middle bgcolor=E6E9EA><font color="#000000">Email</font></td>
		<td align=left bgcolor=#ffffff> <font color="#000000"><input name="FromEmail" size="50">보내는분 이메일</font></td>
	</tr>
	<tr>
		<td align=middle bgcolor=E6E9EA><font color="#000000">Reply-To </font></td>
		<td align=left bgcolor=#ffffff> <input name="reply" size=50>
	 <font color="#000000"> (예) master@shop-wiz.com</font></td>
	</tr>
	<tr>
		<td align=middle bgcolor=E6E9EA><font color="#000000">제목</font></td>
		<td align=left bgcolor=#ffffff> <font color="#000000">
		<input size=81
		name=subject>
		</font></td>
	</tr>
	<tr>
		<td align=middle bgcolor=E6E9EA height=27><font color="#000000">텍스트타입</font></td>
		<td align=left bgcolor=#ffffff height=27> <font color="#000000">
		<input type=radio CHECKED value=0 name=contenttype>HTML 로 보내기
		<input type=radio value=1 name=contenttype>TXT 로 보내기 </font></td>
	</tr>
<!-- 	<tr>
		<td align=middle bgcolor=E6E9EA height=27><font color="#000000">스킨선택</font></td>
		<td align=left bgcolor=#ffffff height=27>
			<font color="#000000">
				<select style="WIDTH: 160px" name=MailSkin>
					<option value="">스킨없슴</option>
					<?
						$vardir = "./mailskin";
						$open_dir = opendir($vardir);
						while($opendir = readdir($open_dir)) {
							if(($opendir != ".") && ($opendir != "..") && is_dir("$vardir/$opendir")) {
								echo "<option value=\"$opendir\">$opendir 스킨</option>\n";
							}
						}
						closedir($open_dir);
					?>
				</select>
			</font>
		</td>
	</tr> -->
	<tr>
		<td align=middle bgcolor=E6E9EA><font color="#000000">내용</font></td>
		<td align=left bgcolor=#ffffff> <textarea name=body_txt rows=15 cols=80></textarea></td>
	</tr>
<!-- 	<tr>
		<td align=middle bgcolor=E6E9EA><font color="#000000">파일첨부 </font></td>
		<td align=left bgcolor=#ffffff><font color="#000000">&nbsp; </font> <input name="userfile" type="file" id="userfile"></td>
	</tr> -->
	<tr>
		<td align=middle bgcolor=E6E9EA><font color="#000000">메일발송옵션</font></td>
		<td align=left bgcolor=#ffffff>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td> <input type="radio" value="all" name="querymass"> <font color="#000000">전체</font> </td>
				</tr>
				<tr>
					<td><font color="#000000">
						<input type=radio CHECKED value=amail name=querymass>
						개인멜
						<input name=personal>
						(하나의 이멜을 적어주세요 - 테스트용)</font>
					</td>
				</tr>
				<tr>
					<td> &nbsp;
						<table width="100%" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td><textarea name="MailAddress" cols="50" rows="20" id="MailAddress"></textarea></td>
								<td width="15">&nbsp;</td>
								<td><textarea name="textarea2" cols="20" rows="20" wrap="PHYSICAL" disabled>
anmkst@naver.com
anmkst@nate.com
anmkst@bluecarpet.co.kr
anmkst@hanmail.net
anmkst@empas.co.kr
</textarea></td>
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

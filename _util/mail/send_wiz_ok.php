<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<p>&nbsp;</p>
<table width="750" border="0" cellspacing="0" cellpadding="0">
  <tr>
	<td> <table width=100% cellspacing=0 border=1 bgcolor=C0C0C0 align="CENTER" bordercolordark="white" bordercolorlight="#DDDDDD">
		<form name=messageform method=post action="<?=$PHP_SELF?>?menushow=<?=$menushow?>&THEME=mail2" enctype='multipart/form-data'>
		  <tr>
			<td align=center bgcolor=F3F3F3><font color="#092F7B"> 메일발송중!!!!</font></td>
		  </tr>

		  <tr bgcolor="#FFFFFF">
			<td align=center> &nbsp;&nbsp;&nbsp;&nbsp; <p>메일을 보내고 있습니다. 잠시만 기다려
				주십시요!</p>
			  <p>(서버 Traffic 상 다수의 메일 발송은 문제가 될 수 있습니다.)</p>
			</td>
		  </tr>
		</form>
	  </table></td>
  </tr>
</table>
<p><br>
</p>
<?

/* 파일 첨부
	if ($userfile_size) {
		$f = fopen($userfile, "r");
		$filedata = fread($f, filesize($userfile));
		fclose($f); unlink($userfile);
		$AttachFile[] = array("name" => $userfile_name, "data" => $filedata);
		unset($filedata);
	}
*/
/*
	if ($AttachFile == "") { // 첨부 파일이 없을 경우 //
		if (!strcmp($contenttype,"0")) {
			$add_header .= "MIME-Version: 1.0\n";
			$add_header .= "Content-Type: text/html$charset\n"; }
		else $add_header .= "Content-Type: text/plain$charset\n";
		$add_header .= "ShopWiz-Mailer: WizMall Mailer";;
	} else {// 첨부 파일이 있을 경우 //
		$boundary = "___==MultiPart_" . strtoupper(md5(uniqid(rand()))) . "==___";
		$add_header .= "MIME-Version: 1.0\n";
		$add_header .= "Content-type: multipart/mixed; BOUNDARY=\"$boundary\"\n";
		$add_header .= "ShopWiz Mailer : WizMall Mailer ver1.0 \n\n";;
		$add_header .= "This is a multi-part message in MIME format.\n\n";
		$add_header .= "--$boundary\n";
		if (!strcmp($contenttype,"0")) $add_header .= "Content-Type: text/html$charset\n";
		else $add_header .= "Content-Type: text/plain$charset\n";

		$add_header .= "Content-Transfer-Encoding: $textencode\n\n";
		$add_header .= $body_txt . "\n\n";

		for ($i=0; $i < sizeof($userfile); $i++) {
				$add_header .= "--" . $boundary . "\n";
				$add_header .= "Content-Type: application/octet-stream;";
				$add_header .= " name=\"" . $AttachFile[$i][name] . "\"\n";
				$add_header .= "Content-Transfer-Encoding: base64\n";
				$add_header .= "Content-Disposition: attachment;";
				$add_header .= " filename=\"" . $AttachFile[$i][name] . "\"\n\n";
				$filedata = base64_encode($AttachFile[$i][data]);  // 첨부파일경우 파일을 base64_encode로 변경
				$filedata = chunk_split($filedata);// 첨부파일경우 파일을 base64_encode로 변경
				$add_header .= $filedata . "\n";// 첨부파일경우 파일을 base64_encode로 변경
		}
		$add_header .= "--" . $boundary . "--\n";
		$body_txt = "";
	}
*/
	$contenttype=trim($contenttype);
	if(!$reply) $reply = $FromEmail;

	$charset="EUC-KR";
	$textencode="8bit";
	$charset = $charset =! "" ? "; charset=$charset" : "";
	$add_header = "From: $FromName<$FromEmail>\n";
	$add_header .= "Reply-To: $reply\n";

	if (!strcmp($contenttype,"0")) {
		$add_header .= "MIME-Version: 1.0\n";
		$add_header .= "Content-Type: text/html$charset\n"; }
	else $add_header .= "Content-Type: text/plain$charset\n";
	$add_header .= "ShopWiz-Mailer: WizMall Mailer";;

	$body_txt = ereg_replace("\r?\n\.\r?\n", "\n .\n", $body_txt);
	$body_txt = stripslashes($body_txt);
	$add_header = ereg_replace("\r?\n\.\r?\n", "\n .\n", $add_header);

	if($querymass != "amail"){ //전체메일이라면
		$MailArr = split("\n", $MailAddress);
		$MailCount = sizeof($MailArr);

		//echo "\$MailCount = $MailCount <br>";
		//exit;
		// $number_rows = @mysql_num_rows($result);
		$seq=0;
		for($start_number=0 ; $start_number<$MailCount ; $start_number++)
		{
			$MailArr[$seq] = ereg_replace("\r", "", $MailArr[$seq]);
			$MailArr[$seq] = ereg_replace("\n\r", "", $MailArr[$seq]);
			 $mailto = trim($MailArr[$seq]);
			//echo "\$mailto = $mailto <br>";

			if(mail($mailto, $subject, $body_txt, $add_header)){
				$seq++;
				echo "$mailto <br>";
				flush(stdout);
				usleep(50000);
			} else {
				echo "Mail전송 실패";
				//exit;
			}
		} //for문 닫음
	} else if($querymass == "amail"){ //개인메일이라면....

		if(mail($personalmass, $subject, $body_txt, $add_header))  echo "${personalmass}님께 멜이 발송되었습니다";
		//              if(mail($amail, $subject, "", $add_header))  echo "$amail님께 멜이 발송되었습니다";
		else echo "${personalmall}님께 Mail전송실패";
		$seq = 1;
		echo "<script>window.alert('$seq 개의 메일 발송을 완료하였습니다.'); history.go(-1);</script>";
		exit;
	}
//exit;
//mysql_close($connect);
	echo "$seq 개의 메일 발송을 완료하였습니다.";

	/* 메일 전송 완료 후 현재 wizmailservice.SendResult를 'sucess' 로 변경한다. */
	//echo "<script>window.alert('$seq 개의 메일 발송을 완료하였습니다.'); history.go(-1);</script>";
?>
<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";

	$from_name  = "테스터";
	$from_email = "test@bluecarpet.co.kr";
	$mail  = "anmkst@nate.com,anmkst@bluecarpet.co.kr,anmkst@naver.com,anmkst@paran.com,anmkst@gmail.com";

	$email = explode(",", $mail);
	for ($i=0; $i<count($email); $i++) {
		mailer($from_name, $from_email, trim($email[$i]), "테스트입니ㅏㄷ.", "<span style='font-size:9pt;'>[메일검사] 내용<p>이 내용이 제대로 보인다면 보내는 메일 서버에는 이상이 없는것입니다.<p>".date("Y-m-d H:i:s")."<p>이 메일 주소로는 회신되지 않습니다.</span>", 1);
		echo "from_name : ".$from_name."<BR>";
		echo "from_email : ".$from_email."<BR>";
		echo "to_email : ".$email[$i]."<BR>";
		echo "=====================<BR>";
	}


	$dbcon -> dbcon_close();
?>
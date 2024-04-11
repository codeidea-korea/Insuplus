<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk("7", $url_admin_login_out);

?>
<?
	$top_menu = "member";
	$left_menu = "";
	include $path_admin."inc/header.php";
	$main_title = "회원메일발송";
?>
	<div class="pageTitle"><?=$main_title?></div>



<?
echo $mail_content;
	$mail_content = REQSTR2($mail_content);
	#############################
	#### 메일 처리
	echo "보내는 분 : ".$mail_from_name."<BR>";
	echo "보내는 분 이메일 : ".$mail_from_email."<BR>";
	echo "제목 : ".$mail_subject."<BR>";
	echo "내용 : ".$mail_content."<BR>";

	echo "받는사람 리스트 : <BR>";

	if ( $user_type == "1" ) {

		$seq = 0;
		$Arr_to_email = explode("\n", $mail_to_email);
		for ($i=0; $i<count($Arr_to_email); $i++) {
			if ( trim($Arr_to_email[$i]) ) {
				$mail_return = mailer($mail_from_name, $mail_from_email, trim($Arr_to_email[$i]), $mail_subject, $mail_content, 1);

				echo trim($Arr_to_email[$i])."";
				if ( $mail_return ) {
					echo " : 성공<BR>";
					$seq++;
				}
				else  echo " : 실패<BR>";
				flush(stdout);
				usleep(50000);
			}
		}
	}
	elseif ($user_type == "2") {
		$SQL = "
			select u_email1, u_email2
			from tbl_user
			where
				u_email_receipt = 1
		";
		$result =$dbcon -> query($SQL);
		while ($rows = $dbcon -> fetch_row($result)) {
			$u_email1					= $rows[0];
			$u_email2						= $rows[1];
			$mail_to_email = trim($u_email1)."@".trim($u_email2);
			if ( trim($u_email1) && trim($u_email2) ) {
				$mail_return = mailer($mail_from_name, $mail_from_email, trim($mail_to_email), $mail_subject, $mail_content, 1);

				echo trim($mail_to_email)."";
				if ( $mail_return ) {
					echo " : 성공<BR>";
					$seq++;
				}
				else  echo " : 실패<BR>";
				flush(stdout);
				usleep(50000);
			}

		}
	}


	#############################

	echo ($seq) ." 개의 메일 발송을 완료하였습니다.<BR>";
	echo "<a href='javascript:history.go(-1);'>돌아가기</a>";

?>

<?
	$dbcon -> dbcon_close();
?>
<? include $path_admin."inc/footer.php"; ?>

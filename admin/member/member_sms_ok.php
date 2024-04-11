<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk("7", $url_admin_login_out);

?>
<?
	$top_menu = "member";
	$left_menu = "";
	include $path_admin."inc/header.php";
	$main_title = "회원SMS발송";
?>
	<div class="pageTitle"><?=$main_title?></div>



<?
	$sms_content = REQSTR2($sms_content);
	#############################
	#### 메일 처리
	echo "보내는 분 연락처 : ".$from_hp."<BR>";
	echo "제목 : ".$sms_subject."<BR>";
	echo "내용 : ".$sms_content."<BR>";
	echo "받는사람 리스트 : <BR>";

	if ( $user_type == "1" ) {

		$seq = 0;
		$Arr_to_email = explode("\n", $mail_to_email);
		for ($i=0; $i<count($Arr_to_email); $i++) {
			if ( trim($Arr_to_email[$i]) ) {
				$result_sms = MMS_Send($from_hp,trim($Arr_to_email[$i]),$sms_subject,$sms_content);
//				$result_sms = SMS_Send($from_hp,trim($Arr_to_email[$i]),$sms_content);


				echo trim($Arr_to_email[$i])."";
				if ( $result_sms ) {
					echo " : 개별발송성공<BR>";
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
			select u_hp, u_name, u_id
			from tbl_user
			where
				substr(u_regdate,1,10)>'2014-04-01' and substr(u_regdate,1,10)<'2014-08-18' and u_sms_receipt='1' and u_state='1'
		";
//		$SQL = "
//			select u_hp
//			from tbl_user
//			where u_id in ('izm9870','admin')
//		";
		$result =$dbcon -> query($SQL);
//		echo $result;
		$seq=0;
		while ($rows = $dbcon -> fetch_row($result)) {
			$to_tel					= $rows[0];
			if ( $to_tel && strlen($to_tel)==13) {
				$result_sms = MMS_Send($from_hp,$to_tel,$sms_subject,$sms_content);
//				$result_sms = SMS_Send($from_hp,$to_tel,$sms_content);

				echo trim($to_tel)." ".$rows[1];
				if ( $result_sms ) {
					echo " : 단체발송성공<BR>";
//					$seq++;
				}
				else  echo " : 실패<BR>";
				flush(stdout);
				usleep(50000);
			}
//			echo $k;
			$seq++;

		}
	}


	$SQL = "insert into tbl_sms_list set sms_content='".$sms_content."' , sms_subject='".$sms_subject."' , target_user='".$seq."' , user_type='".$user_type."' , regdate=now() ";
//	echo $SQL;
	$result =$dbcon -> query($SQL);

	#############################

	echo ($seq) ." 개의 SMS 발송을 완료하였습니다.<BR>";
	echo "<a href='javascript:history.go(-1);'>돌아가기</a>";

?>

<?
	$dbcon -> dbcon_close();
?>
<? include $path_admin."inc/footer.php"; ?>

<?
include "lib.php";
	getLib();

	$dbcon -> setDebug(0);

?>
<?
	#### 언론/방송 > 신문/잡지매체
	//SELECT * FROM zio_news_dat WHERE BB_UID !='' ORDER BY BB_PID ASC
	$SQL_board_1 = "SELECT * FROM zio_news_dat WHERE BB_UID !='' ORDER BY BB_PID desc";
	$bc_id = "06_media";
	$category = "1";


?>

<?

	#### 언론/방송 > TV/영상매체
	//SELECT * FROM zio_bodo_dat WHERE BB_UID !='' ORDER BY BB_PID ASC
	$SQL_board_2 = "SELECT * FROM zio_bodo_dat WHERE BB_UID !='' ORDER BY BB_PID desc";
	$bc_id = "06_media";
	$category = "2";
?>

<?

	#### 공지사항
	//SELECT * FROM zio_notice_dat WHERE BB_UID !='' ORDER BY BB_PID ASC
	$SQL_board_3 = "SELECT * FROM zio_notice_dat WHERE BB_UID !='' ORDER BY BB_PID desc";
	$bc_id = "08_notice";
	$category = "";
?>

<?

	#### 온라인 상담
	//SELECT * FROM zio_consult WHERE BB_CODE ='consult' ORDER BY BB_UID DESC
	$SQL_board_4 = "SELECT * FROM zio_consult WHERE BB_CODE ='consult' ORDER BY BB_UID asc";
	$bc_id = "01_online_list";
	$category = "14";
?>



<?

	#### 온라인 예약
	//SELECT * FROM zio_subst WHERE BB_CODE ='subst' ORDER BY BB_UID DESC
	$SQL_board_5 = "SELECT * FROM zio_subst WHERE BB_CODE ='subst' ORDER BY BB_UID asc";
	$bc_id = "03_reservation_list";
	$category = "";
?>


<?
	#### 치료비용문의 : 신규 게시판이 없으므로 패스~
	//SELECT * FROM zio_subst_pay WHERE BB_UID ORDER BY BB_UID DESC
	$SQL_board_6 = "SELECT * FROM zio_subst_pay WHERE BB_UID ORDER BY BB_UID asc";
?>

<?
	#### 후기
	//SELECT * FROM zio_subst_pay WHERE BB_UID ORDER BY BB_UID DESC
	$SQL_board_7 = "SELECT * FROM zio_exp_dat ORDER BY BB_PID desc";
	$bc_id = "06_after_list";
	$category = "";

?>








<?
	$dbcon -> dbcon_close();

	exit;




?>













<?
	// 치료후기

	$SQL = $SQL_board_7;
	$rs = $dbcon->query($SQL);
	while ( $rows = $dbcon -> fetch_array($rs) ) {
		extract($rows);
		unset($rows);





		#### 게시판 설정
	$bc_id = "06_after_list";
	$category = "";

//		// 신규 글이라면....
//		if ( $bb_Depth == 0 ) {
//			echo "신규글 등록<BR>";
//			$seq_sub = $dbcon -> getCount("select max(seq_sub) from tbl_board_".$bc_id) ;
//			$seq_sub = ceil($seq_sub/1000)*1000+1000;
//			$seq_level = 0;
//		}
//		// 답글이라면...
//		else {
//			echo "답변글 등록<BR>";
//			if ( $seq_sub % 1000 > 0) {
//				$prev_parent_thread = floor($seq_sub/1000)*1000;
//			}
//			else {
//				$prev_parent_thread = $seq_sub - 1000;
//			}
//
//			//원본글보다는 작고 위값보다는 큰 글들의 thread 값을 모두 1씩 낮춘다.
//			$SQL = "
//				update tbl_board_".$bc_id."
//				set
//					seq_sub=seq_sub-1
//				where
//					seq_sub > '".$prev_parent_thread."'
//					and seq_sub < '".$seq_sub."'
//			";
//			//$update_thread = $dbcon -> query($SQL);
//		}
			echo "신규글 등록<BR>";
			$seq_sub = $dbcon -> getCount("select max(seq_sub) from tbl_board_".$bc_id) ;
			$seq_sub = ceil($seq_sub/1000)*1000+1000;
			$seq_level = 0;

		//$subject = $BB_SUBJECT;
		$subject = REQSTR(RESSTR($BB_SUBJECT),"");

		$BB_CONTENT = str_replace("\n","<BR>",$BB_CONTENT);

		//$content = $BB_CONTENT;
		$content = REQSTR(RESSTR($BB_CONTENT),"");

		$Arremail = explode("@", $BB_EMAIL);
		$email1 = $Arremail[0];
		$email2 = $Arremail[1];


		$writer = $BB_ID;
		$SQL_passwd = "password('".$BB_PASS."') ";
		$nick_name = $BB_NAME;

		$view_cnt = $BB_HIT;

		if ( $BB_PREVIEW == "checked" ) {
			$notice = 'Y';
		}
		else {
			$notice = 'N';
		}

		$imgfile = '';


		if ( $BB_SECRET == "Y" ) {
			$secret = 'Y';
		} else {
			$secret = 'N';
		}
		$hidden = 'N';

		//echo "BB_DATE : ".$BB_DATE."<BR>";

		$ArrBB_DATE [0] = substr($BB_DATE ,0,4);
		$ArrBB_DATE [1] = substr($BB_DATE ,4,2);
		$ArrBB_DATE [2] = substr($BB_DATE ,6,2);
		$ArrBB_DATE [3] = substr($BB_DATE ,8,2);
		$ArrBB_DATE [4] = substr($BB_DATE ,10,2);
		$ArrBB_DATE [5] = substr($BB_DATE ,12,2);

		$regdate  = "$ArrBB_DATE[0]-$ArrBB_DATE[1]-$ArrBB_DATE[2] $ArrBB_DATE[3]:$ArrBB_DATE[4]:$ArrBB_DATE[5]";

		//$ext1 = $BB_CATEGORY;
		//$ext1 = $BB_NAME;

//		$ext3 = $BB_TEL;
//		$ext4 = $BB_JUMIN;
//
//
//		// 문의유형
//		$ext5 = $BB_SUBJ;
//
//
//		//예약 일자 : 2009-07-30-5 $Arr_f_time
//		$ArrBB_TIME [0] = substr($BB_TIME ,0,4);
//		$ArrBB_TIME [1] = substr($BB_TIME ,4,2);
//		$ArrBB_TIME [2] = substr($BB_TIME ,6,2);
//		$ArrBB_TIME [3] = substr($BB_TIME ,8,2);
//		$ArrBB_TIME [4] = substr($BB_TIME ,10,2);
//		$ArrBB_TIME [5] = substr($BB_TIME ,12,2);
//
//		$ext6  = "$ArrBB_TIME[0]-$ArrBB_TIME[1]-$ArrBB_TIME[2]-$ArrBB_TIME[3]$ArrBB_TIME[4]$ArrBB_TIME[5]";
//
//
//
//		if ( $BB_AS_CONTENT ) {
//			$ext9 = 'Y';
//		}
//		else {
//			$ext9 = 'N';
//		}
//
//		//$BB_ANSWER = str_replace("\n","<BR>",$BB_ANSWER);
//		$ext10 = REQSTR(RESSTR($BB_AS_CONTENT),"");


		$SQL2 = "
			insert into
				tbl_board_".$bc_id."
			(
				seq_sub, seq_level
				, category
				, subject, content
				, writer, passwd, nick_name
				, email1, email2
				, view_cnt
				, notice
				, imgfile
				, secret, hidden
				, regdate
				, ext1, ext2, ext3, ext4, ext5, ext6, ext7, ext8, ext9, ext10
			)
			values
			(
				'".$seq_sub."', '".$seq_level."'
				, '".$category."'
				, '".$subject."', '".$content."'
				, '".$writer."', ".$SQL_passwd.", '".$nick_name."'
				, '".$email1."', '".$email2."'
				, '".$view_cnt."'
				, '".$notice."'
				, '".$imgfile."'
				, '".$secret."', '".$hidden."'
				, '".$regdate."'
				, '".$ext1."', '".$ext2."', '".$ext3."', '".$ext4."', '".$ext5."', '".$ext6."', '".$ext7."', '".$ext8."', '".$ext9."', '".$ext10."'
			)
		";
		echo "SQL2 : <xmp>" .$SQL2."</xmp><BR>";

		$result = $dbcon -> query($SQL2);
		if ( !$result )  {
			echo ("에러 : ". mysql_error($result ) );
		}


	}
	unset($rs);
?>




<?
	// 온라인 예약 및 문의
	$SQL = $SQL_board_5;
	$rs = $dbcon->query($SQL);
	while ( $rows = $dbcon -> fetch_array($rs) ) {
		extract($rows);
		unset($rows);





		#### 게시판 설정
	$bc_id = "03_reservation_list";
	$category = "";

		// 신규 글이라면....
		if ( $bb_Depth == 0 ) {
			echo "신규글 등록<BR>";
			$seq_sub = $dbcon -> getCount("select max(seq_sub) from tbl_board_".$bc_id) ;
			$seq_sub = ceil($seq_sub/1000)*1000+1000;
			$seq_level = 0;
		}
		// 답글이라면...
		else {
			echo "답변글 등록<BR>";
			if ( $seq_sub % 1000 > 0) {
				$prev_parent_thread = floor($seq_sub/1000)*1000;
			}
			else {
				$prev_parent_thread = $seq_sub - 1000;
			}

			//원본글보다는 작고 위값보다는 큰 글들의 thread 값을 모두 1씩 낮춘다.
			$SQL = "
				update tbl_board_".$bc_id."
				set
					seq_sub=seq_sub-1
				where
					seq_sub > '".$prev_parent_thread."'
					and seq_sub < '".$seq_sub."'
			";
			//$update_thread = $dbcon -> query($SQL);
		}

		//$subject = $BB_SUBJECT;
		$subject = REQSTR(RESSTR($BB_SUBJECT),"");

		$BB_CONTENT = str_replace("\n","<BR>",$BB_CONTENT);

		//$content = $BB_CONTENT;
		$content = REQSTR(RESSTR($BB_CONTENT),"");

		$Arremail = explode("@", $BB_EMAIL);
		$email1 = $Arremail[0];
		$email2 = $Arremail[1];


		$writer = $BB_ID;
		$SQL_passwd = "password('".$BB_PASS."') ";
		$nick_name = $BB_NAME;

		$view_cnt = $BB_HIT;

		$notice = 'N';

		$imgfile = '';


		if ( $BB_SECRET == "Y" ) {
			$secret = 'Y';
		} else {
			$secret = 'N';
		}
		$hidden = 'N';

		//echo "BB_DATE : ".$BB_DATE."<BR>";

		$ArrBB_DATE [0] = substr($BB_DATE ,0,4);
		$ArrBB_DATE [1] = substr($BB_DATE ,4,2);
		$ArrBB_DATE [2] = substr($BB_DATE ,6,2);
		$ArrBB_DATE [3] = substr($BB_DATE ,8,2);
		$ArrBB_DATE [4] = substr($BB_DATE ,10,2);
		$ArrBB_DATE [5] = substr($BB_DATE ,12,2);

		$regdate  = "$ArrBB_DATE[0]-$ArrBB_DATE[1]-$ArrBB_DATE[2] $ArrBB_DATE[3]:$ArrBB_DATE[4]:$ArrBB_DATE[5]";

		//$ext1 = $BB_CATEGORY;
		//$ext1 = $BB_NAME;

		$ext3 = $BB_TEL;
		$ext4 = $BB_JUMIN;


		// 문의유형
		$ext5 = $BB_SUBJ;


		//예약 일자 : 2009-07-30-5 $Arr_f_time
		$ArrBB_TIME [0] = substr($BB_TIME ,0,4);
		$ArrBB_TIME [1] = substr($BB_TIME ,4,2);
		$ArrBB_TIME [2] = substr($BB_TIME ,6,2);
		$ArrBB_TIME [3] = substr($BB_TIME ,8,2);
		$ArrBB_TIME [4] = substr($BB_TIME ,10,2);
		$ArrBB_TIME [5] = substr($BB_TIME ,12,2);

		$ext6  = "$ArrBB_TIME[0]-$ArrBB_TIME[1]-$ArrBB_TIME[2]-$ArrBB_TIME[3]$ArrBB_TIME[4]$ArrBB_TIME[5]";



		if ( $BB_AS_CONTENT ) {
			$ext9 = 'Y';
		}
		else {
			$ext9 = 'N';
		}

		//$BB_ANSWER = str_replace("\n","<BR>",$BB_ANSWER);

		$BB_AS_CONTENT = str_replace("<br />","\n",$BB_AS_CONTENT);
		$ext10 = REQSTR(RESSTR($BB_AS_CONTENT),"");


		$SQL2 = "
			insert into
				tbl_board_".$bc_id."
			(
				seq_sub, seq_level
				, category
				, subject, content
				, writer, passwd, nick_name
				, email1, email2
				, view_cnt
				, notice
				, imgfile
				, secret, hidden
				, regdate
				, ext1, ext2, ext3, ext4, ext5, ext6, ext7, ext8, ext9, ext10
			)
			values
			(
				'".$seq_sub."', '".$seq_level."'
				, '".$category."'
				, '".$subject."', '".$content."'
				, '".$writer."', ".$SQL_passwd.", '".$nick_name."'
				, '".$email1."', '".$email2."'
				, '".$view_cnt."'
				, '".$notice."'
				, '".$imgfile."'
				, '".$secret."', '".$hidden."'
				, '".$regdate."'
				, '".$ext1."', '".$ext2."', '".$ext3."', '".$ext4."', '".$ext5."', '".$ext6."', '".$ext7."', '".$ext8."', '".$ext9."', '".$ext10."'
			)
		";
		echo "SQL2 : <xmp>" .$SQL2."</xmp><BR>";

		$result = $dbcon -> query($SQL2);
		if ( !$result )  {
			echo ("에러 : ". mysql_error($result ) );
		}


	}
	unset($rs);
?>





<?
	// 온라인 상담

	$SQL = $SQL_board_4;
	$rs = $dbcon->query($SQL);
	while ( $rows = $dbcon -> fetch_array($rs) ) {
		extract($rows);
		unset($rows);





		#### 게시판 설정
	$bc_id = "01_online_list";
	$category = "14";

		// 신규 글이라면....
		if ( $bb_Depth == 0 ) {
			echo "신규글 등록<BR>";
			$seq_sub = $dbcon -> getCount("select max(seq_sub) from tbl_board_".$bc_id) ;
			$seq_sub = ceil($seq_sub/1000)*1000+1000;
			$seq_level = 0;
		}
		// 답글이라면...
		else {
			echo "답변글 등록<BR>";
			if ( $seq_sub % 1000 > 0) {
				$prev_parent_thread = floor($seq_sub/1000)*1000;
			}
			else {
				$prev_parent_thread = $seq_sub - 1000;
			}

			//원본글보다는 작고 위값보다는 큰 글들의 thread 값을 모두 1씩 낮춘다.
			$SQL = "
				update tbl_board_".$bc_id."
				set
					seq_sub=seq_sub-1
				where
					seq_sub > '".$prev_parent_thread."'
					and seq_sub < '".$seq_sub."'
			";
			//$update_thread = $dbcon -> query($SQL);
		}

		//$subject = $BB_SUBJECT;
		$subject = REQSTR(RESSTR($BB_SUBJECT),"");

		$BB_CONTENT = str_replace("\n","<BR>",$BB_CONTENT);

		//$content = $BB_CONTENT;
		$content = REQSTR(RESSTR($BB_CONTENT),"");

		$Arremail = explode("@", $BB_EMAIL);
		$email1 = $Arremail[0];
		$email2 = $Arremail[1];

		$ext1 = $BB_CATEGORY;

		$writer = $BB_ID;
		$SQL_passwd = "password('".$BB_PASS."') ";
		$nick_name = $BB_NAME;

		$view_cnt = $BB_HIT;

		$notice = 'N';

		$imgfile = '';


		if ( $BB_SECRET != "N" ) {
			$secret = 'N';
		} else {
			$secret = 'Y';
		}
		$hidden = 'N';

		//echo "BB_DATE : ".$BB_DATE."<BR>";

		$ArrBB_DATE [0] = substr($BB_DATE ,0,4);
		$ArrBB_DATE [1] = substr($BB_DATE ,4,2);
		$ArrBB_DATE [2] = substr($BB_DATE ,6,2);
		$ArrBB_DATE [3] = substr($BB_DATE ,8,2);
		$ArrBB_DATE [4] = substr($BB_DATE ,10,2);
		$ArrBB_DATE [5] = substr($BB_DATE ,12,2);

		$regdate  = "$ArrBB_DATE[0]-$ArrBB_DATE[1]-$ArrBB_DATE[2] $ArrBB_DATE[3]:$ArrBB_DATE[4]:$ArrBB_DATE[5]";

		$ext1 = $BB_NAME;
		$ext2 = $BB_TEL;

		if ( $BB_ANSWER ) {
			$ext9 = 'Y';
		}
		else {
			$ext9 = 'N';
		}

		$BB_ANSWER = str_replace("<br />","\n",$BB_ANSWER);
		$ext10 = REQSTR(RESSTR($BB_ANSWER),"");


		$SQL2 = "
			insert into
				tbl_board_".$bc_id."
			(
				seq_sub, seq_level
				, category
				, subject, content
				, writer, passwd, nick_name
				, email1, email2
				, view_cnt
				, notice
				, imgfile
				, secret, hidden
				, regdate
				, ext1, ext2, ext3, ext4, ext5, ext6, ext7, ext8, ext9, ext10
			)
			values
			(
				'".$seq_sub."', '".$seq_level."'
				, '".$category."'
				, '".$subject."', '".$content."'
				, '".$writer."', ".$SQL_passwd.", '".$nick_name."'
				, '".$email1."', '".$email2."'
				, '".$view_cnt."'
				, '".$notice."'
				, '".$imgfile."'
				, '".$secret."', '".$hidden."'
				, '".$regdate."'
				, '".$ext1."', '".$ext2."', '".$ext3."', '".$ext4."', '".$ext5."', '".$ext6."', '".$ext7."', '".$ext8."', '".$ext9."', '".$ext10."'
			)
		";
		echo "SQL2 : <xmp>" .$SQL2."</xmp><BR>";

		$result = $dbcon -> query($SQL2);
		if ( !$result )  {
			echo ("에러 : ". mysql_error($result ) );
		}


	}
	unset($rs);
?>










<?

	// 공지사항
	$SQL = $SQL_board_3;
	$rs = $dbcon->query($SQL);
	while ( $rows = $dbcon -> fetch_array($rs) ) {
		extract($rows);
		unset($rows);





		#### 게시판 설정
	$bc_id = "08_notice";
	$category = "";

		// 신규 글이라면....
		if ( $bb_Depth == 0 ) {
			echo "신규글 등록<BR>";
			$seq_sub = $dbcon -> getCount("select max(seq_sub) from tbl_board_".$bc_id) ;
			$seq_sub = ceil($seq_sub/1000)*1000+1000;
			$seq_level = 0;
		}
		// 답글이라면...
		else {
			echo "답변글 등록<BR>";
			if ( $seq_sub % 1000 > 0) {
				$prev_parent_thread = floor($seq_sub/1000)*1000;
			}
			else {
				$prev_parent_thread = $seq_sub - 1000;
			}

			//원본글보다는 작고 위값보다는 큰 글들의 thread 값을 모두 1씩 낮춘다.
			$SQL = "
				update tbl_board_".$bc_id."
				set
					seq_sub=seq_sub-1
				where
					seq_sub > '".$prev_parent_thread."'
					and seq_sub < '".$seq_sub."'
			";
			//$update_thread = $dbcon -> query($SQL);
		}

		//$subject = $BB_SUBJECT;
		$subject = REQSTR(RESSTR($BB_SUBJECT),"");
		$content = $BB_CONTENT;
		//$content = REQSTR(RESSTR($BB_CONTENT),"");

		$Arremail = explode("@", $BB_EMAIL);
		$email1 = $Arremail[0];
		$email2 = $Arremail[1];

		$ext1 = $BB_CATEGORY;

		$writer = "admin";
		$SQL_passwd = "";
		$nick_name = $BB_NAME;

		$view_cnt = $BB_HIT;

		$notice = 'N';

		$imgfile = '';

		$secret = 'N';
		$hidden = 'N';

		//echo "BB_DATE : ".$BB_DATE."<BR>";

		$ArrBB_DATE [0] = substr($BB_DATE ,0,4);
		$ArrBB_DATE [1] = substr($BB_DATE ,4,2);
		$ArrBB_DATE [2] = substr($BB_DATE ,6,2);
		$ArrBB_DATE [3] = substr($BB_DATE ,8,2);
		$ArrBB_DATE [4] = substr($BB_DATE ,10,2);
		$ArrBB_DATE [5] = substr($BB_DATE ,12,2);

		$regdate  = "$ArrBB_DATE[0]-$ArrBB_DATE[1]-$ArrBB_DATE[2] $ArrBB_DATE[3]:$ArrBB_DATE[4]:$ArrBB_DATE[5]";


		$SQL2 = "
			insert into
				tbl_board_".$bc_id."
			(
				seq_sub, seq_level
				, category
				, subject, content
				, writer, passwd, nick_name
				, email1, email2
				, view_cnt
				, notice
				, imgfile
				, secret, hidden
				, regdate
				, ext1, ext2, ext3, ext4, ext5, ext6, ext7, ext8, ext9, ext10
			)
			values
			(
				'".$seq_sub."', '".$seq_level."'
				, '".$category."'
				, '".$subject."', '".$content."'
				, '".$writer."', '".$SQL_passwd."', '".$nick_name."'
				, '".$email1."', '".$email2."'
				, '".$view_cnt."'
				, '".$notice."'
				, '".$imgfile."'
				, '".$secret."', '".$hidden."'
				, '".$regdate."'
				, '".$ext1."', '".$ext2."', '".$ext3."', '".$ext4."', '".$ext5."', '".$ext6."', '".$ext7."', '".$ext8."', '".$ext9."', '".$ext10."'
			)
		";
		echo "SQL2 : <xmp>" .$SQL2."</xmp><BR>";

		$result = $dbcon -> query($SQL2);
		if ( !$result )  {
			echo ("에러 : ". mysql_error($result ) );
		}


	}
	unset($rs);
?>





<?
	#### 언론/방송 > TV/영상매체
	$SQL = $SQL_board_2;
	$rs = $dbcon->query($SQL);
	while ( $rows = $dbcon -> fetch_array($rs) ) {
		extract($rows);
		unset($rows);





		#### 게시판 설정
		$bc_id = "06_media";
		$category = "2";

		// 신규 글이라면....
		if ( $bb_Depth == 0 ) {
			echo "신규글 등록<BR>";
			$seq_sub = $dbcon -> getCount("select max(seq_sub) from tbl_board_".$bc_id) ;
			$seq_sub = ceil($seq_sub/1000)*1000+1000;
			$seq_level = 0;
		}
		// 답글이라면...
		else {
			echo "답변글 등록<BR>";
			if ( $seq_sub % 1000 > 0) {
				$prev_parent_thread = floor($seq_sub/1000)*1000;
			}
			else {
				$prev_parent_thread = $seq_sub - 1000;
			}

			//원본글보다는 작고 위값보다는 큰 글들의 thread 값을 모두 1씩 낮춘다.
			$SQL = "
				update tbl_board_".$bc_id."
				set
					seq_sub=seq_sub-1
				where
					seq_sub > '".$prev_parent_thread."'
					and seq_sub < '".$seq_sub."'
			";
			//$update_thread = $dbcon -> query($SQL);
		}

		//$subject = $BB_SUBJECT;
		$subject = REQSTR(RESSTR($BB_SUBJECT),"");
		$content = $BB_CONTENT;
		//$content = REQSTR(RESSTR($BB_CONTENT),"");

		$Arremail = explode("@", $BB_EMAIL);
		$email1 = $Arremail[0];
		$email2 = $Arremail[1];

		$BB_LINK = str_replace(";","",$BB_LINK);
		$ext2 = $BB_LINK;

		$writer = "admin";
		$SQL_passwd = "";
		$nick_name = $BB_NAME;

		$view_cnt = $BB_HIT;

		$notice = 'N';


		// 썸네일 이미지 , 형식 : wow.jpg,imgfile_1248913813_0.jpg,26132,image/pjpeg,130,87,jpg
		$imgfile = '';
		if ( $BB_FILE ) {
			$ArrBB_FILE = explode(";",$BB_FILE);
			$ArrTemp = explode(".", $ArrBB_FILE[0]);
			$ArrTemp2 = $ArrTemp[count($ArrTemp)-1];
			$imgfile = "$ArrBB_FILE[0],$ArrBB_FILE[0],0,image/pjpeg,130,87,$ArrTemp2";
		}


		$secret = 'N';
		$hidden = 'N';

		//echo "BB_DATE : ".$BB_DATE."<BR>";

		$ArrBB_DATE [0] = substr($BB_DATE ,0,4);
		$ArrBB_DATE [1] = substr($BB_DATE ,4,2);
		$ArrBB_DATE [2] = substr($BB_DATE ,6,2);
		$ArrBB_DATE [3] = substr($BB_DATE ,8,2);
		$ArrBB_DATE [4] = substr($BB_DATE ,10,2);
		$ArrBB_DATE [5] = substr($BB_DATE ,12,2);

		$regdate  = "$ArrBB_DATE[0]-$ArrBB_DATE[1]-$ArrBB_DATE[2] $ArrBB_DATE[3]:$ArrBB_DATE[4]:$ArrBB_DATE[5]";


		$SQL2 = "
			insert into
				tbl_board_".$bc_id."
			(
				seq_sub, seq_level
				, category
				, subject, content
				, writer, passwd, nick_name
				, email1, email2
				, view_cnt
				, notice
				, imgfile
				, secret, hidden
				, regdate
				, ext1, ext2, ext3, ext4, ext5, ext6, ext7, ext8, ext9, ext10
			)
			values
			(
				'".$seq_sub."', '".$seq_level."'
				, '".$category."'
				, '".$subject."', '".$content."'
				, '".$writer."', '".$SQL_passwd."', '".$nick_name."'
				, '".$email1."', '".$email2."'
				, '".$view_cnt."'
				, '".$notice."'
				, '".$imgfile."'
				, '".$secret."', '".$hidden."'
				, '".$regdate."'
				, '".$ext1."', '".$ext2."', '".$ext3."', '".$ext4."', '".$ext5."', '".$ext6."', '".$ext7."', '".$ext8."', '".$ext9."', '".$ext10."'
			)
		";
		echo "SQL2 : <xmp>" .$SQL2."</xmp><BR>";

		$result = $dbcon -> query($SQL2);
		if ( !$result )  {
			echo ("에러 : ". mysql_error($result ) );
		}



	}
	unset($rs);

?>





<?
	#### 언론/방송 > 신문/잡지매체
	$SQL = $SQL_board_1;
	$rs = $dbcon->query($SQL);
	while ( $rows = $dbcon -> fetch_array($rs) ) {
		extract($rows);
		unset($rows);





		#### 게시판 설정
		$bc_id = "06_media";
		$category = "1";

		// 신규 글이라면....
		if ( $bb_Depth == 0 ) {
			echo "신규글 등록<BR>";
			$seq_sub = $dbcon -> getCount("select max(seq_sub) from tbl_board_".$bc_id) ;
			$seq_sub = ceil($seq_sub/1000)*1000+1000;
			$seq_level = 0;
		}
		// 답글이라면...
		else {
			echo "답변글 등록<BR>";
			if ( $seq_sub % 1000 > 0) {
				$prev_parent_thread = floor($seq_sub/1000)*1000;
			}
			else {
				$prev_parent_thread = $seq_sub - 1000;
			}

			//원본글보다는 작고 위값보다는 큰 글들의 thread 값을 모두 1씩 낮춘다.
			$SQL = "
				update tbl_board_".$bc_id."
				set
					seq_sub=seq_sub-1
				where
					seq_sub > '".$prev_parent_thread."'
					and seq_sub < '".$seq_sub."'
			";
			//$update_thread = $dbcon -> query($SQL);
		}

		//$subject = $BB_SUBJECT;
		$subject = REQSTR(RESSTR($BB_SUBJECT),"");
		$content = $BB_CONTENT;
		//$content = REQSTR(RESSTR($BB_CONTENT),"");

		$Arremail = explode("@", $BB_EMAIL);
		$email1 = $Arremail[0];
		$email2 = $Arremail[1];

		$ext1 = $BB_CATEGORY;

		$writer = "admin";
		$SQL_passwd = "";
		$nick_name = $BB_NAME;

		$view_cnt = $BB_HIT;

		$notice = 'N';

		$imgfile = '';

		$secret = 'N';
		$hidden = 'N';

		//echo "BB_DATE : ".$BB_DATE."<BR>";

		$ArrBB_DATE [0] = substr($BB_DATE ,0,4);
		$ArrBB_DATE [1] = substr($BB_DATE ,4,2);
		$ArrBB_DATE [2] = substr($BB_DATE ,6,2);
		$ArrBB_DATE [3] = substr($BB_DATE ,8,2);
		$ArrBB_DATE [4] = substr($BB_DATE ,10,2);
		$ArrBB_DATE [5] = substr($BB_DATE ,12,2);

		$regdate  = "$ArrBB_DATE[0]-$ArrBB_DATE[1]-$ArrBB_DATE[2] $ArrBB_DATE[3]:$ArrBB_DATE[4]:$ArrBB_DATE[5]";


		$SQL2 = "
			insert into
				tbl_board_".$bc_id."
			(
				seq_sub, seq_level
				, category
				, subject, content
				, writer, passwd, nick_name
				, email1, email2
				, view_cnt
				, notice
				, imgfile
				, secret, hidden
				, regdate
				, ext1, ext2, ext3, ext4, ext5, ext6, ext7, ext8, ext9, ext10
			)
			values
			(
				'".$seq_sub."', '".$seq_level."'
				, '".$category."'
				, '".$subject."', '".$content."'
				, '".$writer."', '".$SQL_passwd."', '".$nick_name."'
				, '".$email1."', '".$email2."'
				, '".$view_cnt."'
				, '".$notice."'
				, '".$imgfile."'
				, '".$secret."', '".$hidden."'
				, '".$regdate."'
				, '".$ext1."', '".$ext2."', '".$ext3."', '".$ext4."', '".$ext5."', '".$ext6."', '".$ext7."', '".$ext8."', '".$ext9."', '".$ext10."'
			)
		";
		echo "SQL2 : <xmp>" .$SQL2."</xmp><BR>";

		$result = $dbcon -> query($SQL2);
		if ( !$result )  {
			echo ("에러 : ". mysql_error($result ) );
		}


	}
	unset($rs);
?>








<?

	/*
	#### 회원 DB 연동 끝!!!!
	$ArrMB_JOB = Array(
		"중/고생" => 1
		, "대학생" => 2
		, "대학원생" => 3
		, "서비스업" => 6
		, "인터넷" => 8
		, "정보통신업" => 8
		, "의료인" => 9
		, "언론/방송" => 10
		, "종교" => 12
		, "문화/예술" => 13
		, "주부" => 14
		, "농/축산" => 15
		, "수산/광업" => 15
		, "교직자" => 16
		, "군인" => 18
		, "무직" => 18
		, "기타" => 20
		, "aa" => 20
		, "건설업" => 21
		, "경영컨설팅" => 22
		, "공무원" => 23
		, "금융/증권" => 24
		, "보험업" => 25
		, "부동산업" => 26
		, "스포츠/레져" => 27
		, "유통업" => 28
		, "자영업" => 29
		, "제조업" => 30
		, "프리랜서" => 31
		, "회사원" => 32
	);
//	$ArrMB_JOB = Array(
//		1 => "중/고생"
//		, 2 => "대학생"
//		, 3 => "대학원생"
//		, 6 => "서비스업"
//		, 8 => "인터넷"
//		, 8 => "정보통신업"
//		, 9 => "의료인"
//		, 10 => "언론/방송"
//		, 12 => "종교"
//		, 13 => "문화/예술"
//		, 14 => "주부"
//		, 15 => "농/축산"
//		, 15 => "수산/광업"
//		, 16 => "교직자"
//		, 18 => "군인"
//		, 18 => "무직"
//		, 20 => "기타"
//		, 20 => ""
//		, 21 => "건설업"
//		, 22 => "경영컨설팅"
//		, 23 => "공무원"
//		, 24 => "금융/증권"
//		, 25 => "보험업"
//		, 26 => "부동산업"
//		, 27 => "스포츠/레져"
//		, 28 => "유통업"
//		, 29 => "자영업"
//		, 30 => "제조업"
//		, 31 => "프리랜서"
//		, 32 => "회사원"
//	);
//
//	$test = array_keys($ArrMB_JOB,"언론/방송");
//	print_r ( $test ) ;
//	echo $test[0];
//	exit;

	#### 회원리스트

//	MB_UID : 고유번호
//	MB_TYPE : 회원타입 ( 1 : 일반회원, 2 : 우수회원(사용안함), 3 : VIP 회원, 4 : 구취(사용안함), 5 : 변비(사용안함), 6 : 과민성대장(사용안함), 7  : 간해독(사용안함), 8 : 장해독(사용안함), 9 : 비만(사용안함) )
//	MB_KIND : 회원레벨 ( A : 관리자, P : 일반회원 )
//	MB_ID : 회원아이디
//	MB_PW : 회원비밀번호
//	MB_NAME : 회원이름
//	MB_JUMIN : 회원주민등록번호 ( 형식 : 1234561234567 )
//	MB_SEX : 성별 ( 1 : 남자 , 2 : 여자 )
//	MB_BIRTH : 생년월일 ( 형식 : 19751219 > 년월일)
//	MB_BIRTH_TYPE : 양/음력 ( 양력, 음력 )
//	MB_EMAIL : 이메일주소 ( aaa@aaa.aa )
//	MB_REMAIL : 이메일 수신 여부 ( 0 : 수신안함, 1 : 수신함 )
//	MB_ZIP :  우편번호 ( 형식 : 111-111 )
//	MB_ADDR1 : 주소 ( ~동까지 )
//	MB_ADDR2 : 상세주소
//	MB_JOB : 직업 (한글)
//
//		건설업
//		경영컨설팅
//		공무원
//		교직자
//		군인
//		금융/증권
//		기타
//		농/축산
//		대학생
//		대학원생
//		무직
//		문화/예술
//		보험업
//		부동산업
//		서비스업
//		수산/광업
//		스포츠/레져
//		언론/방송
//		유통업
//		의료인
//		인터넷
//		자영업
//		정보통신업
//		제조업
//		종교
//		주부
//		중/고생
//		프리랜서
//		회사원
//		"공백" > 기타
//
//	MB_URL : 홈페이지 ( 사용안함 )
//	MB_HAND_TEL : 핸드폰번호 ( 111-222-3333)
//	MB_HOME_TEL : 전화번호 ( 11-111-1111 )
//	MB_MARR : 결혼여부 ( 사용안함 )
//	MB_MARRDAY : 결혼일 ( 사용안함 )
//	MB_REGISDAY : 가입일 ( 20090720164000 : 년월일시분초 )
//	MB_LOGNUM : 로그인 횟수
//	MB_LOGDATE : 최근 로그인 시간
//	MB_ACTION : 회원상태 ( 1 : 승인, 2 : 보류, 3 : 기타, 4 : 탈퇴 )
//	MB_INTRO : ( 사용안함 )
//	MB_MEMO : ( 사용안함 )
//	MB_PRICEDAY : ( 사용안함 )
//	MB_ADD : ( 사용안함 )

	$Arr_u_state	= Array(
		0 => "승인대기"
		, 1 => "정상"
		, 2 => "탈퇴"
	);

	$SQL = "
		SELECT
			MB_TYPE
			, MB_KIND
			, MB_ID
			, MB_PW
			, MB_NAME
			, MB_JUMIN
			, MB_SEX
			, MB_BIRTH
			, MB_BIRTH_TYPE
			, MB_EMAIL
			, MB_REMAIL
			, MB_ZIP
			, MB_ADDR1
			, MB_ADDR2
			, MB_JOB
			, MB_URL
			, MB_HAND_TEL
			, MB_HOME_TEL
			, MB_MARR
			, MB_MARRDAY
			, MB_REGISDAY
			, MB_LOGNUM
			, MB_LOGDATE
			, MB_ACTION
		FROM zio_members order by MB_UID asc
	";
	$rs = $dbcon->query($SQL);
	while ( $rows = $dbcon -> fetch_array($rs) ) {
		extract($rows);
		unset($rows);

		if ( $MB_KIND == "A"  ) {
			$MB_KIND = $auth_admin;
		}
		else {
			$MB_KIND = 1;
		}

		$MB_HAND_TEL = explode("-", $MB_HAND_TEL);
		$MB_HOME_TEL = explode("-", $MB_HOME_TEL);

		$MB_JUMIN1 = substr($MB_JUMIN,0,6);
		$MB_JUMIN2 = substr($MB_JUMIN,6,7);

		$MB_EMAIL = explode("@", $MB_EMAIL);

		if ( $MB_SEX == 1 ) {
			$MB_SEX = "M";
		}
		else {
			$MB_SEX = "F";
		}

		if ( $MB_ACTION == 1 ) {
			$MB_ACTION = 1;
		} elseif ( $MB_ACTION == 2 ) {
			$MB_ACTION = 0;
		} elseif ( $MB_ACTION == 4 ) {
			$MB_ACTION = 2;
		} else {
			$MB_ACTION = 0;
		}

		//2009 07 20 16 40 00
		//echo "MB_REGISDAY : ".(string)$MB_REGISDAY."<BR>";
		$Arr_MB_REGISDAY[0] = substr((string)$MB_REGISDAY,0,4);
		$Arr_MB_REGISDAY[1] = substr($MB_REGISDAY,4,2);
		$Arr_MB_REGISDAY[2] = substr($MB_REGISDAY,6,2);
		$Arr_MB_REGISDAY[3] = substr($MB_REGISDAY,8,2);
		$Arr_MB_REGISDAY[4] = substr($MB_REGISDAY,10,2);
		$Arr_MB_REGISDAY[5] = substr($MB_REGISDAY,12,2);

		$MB_REGISDAY = "$Arr_MB_REGISDAY[0]-$Arr_MB_REGISDAY[1]-$Arr_MB_REGISDAY[2] $Arr_MB_REGISDAY[3]:$Arr_MB_REGISDAY[4]:$Arr_MB_REGISDAY[5]";
		echo "MB_REGISDAY : ".$MB_REGISDAY."<BR>";
//		print_r ($MB_JOB);
//		$MB_JOB = trim($MB_JOB);
//		$MB_JOB = str_replace("\n","",$MB_JOB);
//		$MB_JOB = str_replace("<BR>","",$MB_JOB);
//		$MB_JOB = REQSTR($MB_JOB);
//		$MB_JOB = RESSTR($MB_JOB);
//
//		print_r ("MB_JOB : <xmp>" .$MB_JOB."</xmp><BR>");
//		echo "MB_JOB : " .$ArrMB_JOB[$MB_JOB]."<BR>";
//		echo "MB_JOB : " .$ArrMB_JOB["언론/방송"]."<BR>";



		$SQL2 = "
			INSERT tbl_user set
				u_idx = NULL
				, u_id = '".$MB_ID."'
				, u_pw = '".base64_encode($MB_PW)."'
				, u_name = '".$MB_NAME."'
				, u_jumin1 = '".$MB_JUMIN1."'
				, u_jumin2 = '".base64_encode($MB_JUMIN2)."'
				, u_email1 = '".$MB_EMAIL[0]."'
				, u_email2 = '".$MB_EMAIL[1]."'
				, u_hp1 = '".$MB_HAND_TEL[0]."'
				, u_hp2 = '".$MB_HAND_TEL[1]."'
				, u_hp3 = '".$MB_HAND_TEL[2]."'
				, u_tel1 = '".$MB_HOME_TEL[0]."'
				, u_tel2 = '".$MB_HOME_TEL[1]."'
				, u_tel3 = '".$MB_HOME_TEL[2]."'
				, u_fax1 = ''
				, u_fax2 = ''
				, u_fax3 = ''
				, u_post = '".$MB_ZIP."'
				, u_addr1 = '".$MB_ADDR1."'
				, u_addr2 = '".$MB_ADDR2."'
				, u_area = ''
				, u_homepage = '".$MB_URL."'
				, u_sex = '".$MB_SEX."'
				, u_interest = ''
				, u_hobby = ''
				, u_religion = ''
				, u_blood = ''
				, u_job = '".$ArrMB_JOB[$MB_JOB]."'
				, u_marriage = ''
				, u_marriagedate = ''
				, u_image = ''
				, u_introduction = ''
				, u_etc = ''
				, u_re_id = ''
				, u_birth = ''
				, u_birth_luner = 0
				, u_email_receipt = '".$MB_REMAIL."'
				, u_sms_receipt = 0
				, u_level = '".$MB_KIND."'
				, u_gubun = 0
				, u_state = '".$MB_ACTION."'
				, u_regdate = '".$MB_REGISDAY."'
		";
//		$SQL2 = "
//			INSERT INTO tbl_user
//				( u_id, u_pw, u_name, u_jumin, u_jumin1, u_jumin2, u_email1, u_email2, u_email_receipt, u_sms_receipt, u_level, u_gubun, u_state, u_regdate )
//			SELECT
//
//				distinct MU_ID, PASSWORD(MU_PW), MU_NAME, MU_JUMINNUM, NULL , NULL , substring_index(MU_EMAIL, '@', 1), substring_index(MU_EMAIL, '@', -1), (case MU_EMAIL_RE when 'ok' then 'Y' when 'N' then 'N' else '' end), 'Y', 1, 0, (CASE MU_STATE WHEN 'Normal' THEN 1 ELSE 2 END  ), now()
//			FROM
//				MEMBER_USER
//			ORDER BY
//				mu_num ASC
//			LIMIT 0, 1
//		";
		echo $SQL2."<BR><BR>";
		$rs2 = $dbcon -> query($SQL2);


	}
	unset($rs);

	*/
?>








































































































































































































































































































































<?

/*
	#### 언론보도 이전작업
	$SQL = "

		select
			BD_NUM, BD_BBSCODE, BD_STATE, BD_NOTICE, BD_SECURITY, BD_SECURITYPW, BD_REF, BD_REFLEVEL, BD_REFSTEP, BD_ID, BD_WRITER, BD_EMAIL, BD_AHEAD, BD_TITLE, BD_HTML, BD_TEXT, BD_FILECODE, BD_IP, BD_DATE, BD_HIT, substring_index(BD_EMAIL, '@', 1) as email1, substring_index(BD_EMAIL, '@', -1) as email2
		from
			MIN_BBS_DATA
		where
			BD_BBSCODE ='INFORMATION'
		order by
			BD_REF asc,BD_REFSTEP desc
	";
		//select * from MIN_BBS_DATA where BD_STATE = 'Normal' and BD_BBSCODE ='INFORMATION' and BD_NOTICE=0 order by BD_REF desc,BD_REFSTEP asc limit 0, 15
	$SQL = "
		select
			BD_NUM, BD_BBSCODE, BD_STATE, BD_NOTICE, BD_SECURITY, BD_SECURITYPW, BD_REF, BD_REFLEVEL, BD_REFSTEP, BD_ID, BD_WRITER, BD_EMAIL, BD_AHEAD, BD_TITLE, BD_HTML, BD_TEXT, BD_FILECODE, BD_IP, BD_DATE, BD_HIT, substring_index(BD_EMAIL, '@', 1) as email1, substring_index(BD_EMAIL, '@', -1) as email2
		from MIN_BBS_DATA where BD_STATE = 'Normal' and BD_BBSCODE ='INFORMATION' and BD_NOTICE=0 order by BD_REF asc
	";

		//limit 0, 10
	$rs = $dbcon -> query($SQL);

	echo $SQL."<BR>";

	$no = 1000;
	while($rows = $dbcon ->fetch_array($rs)) {
		extract($rows);
		unset($rows);


		if ( $BD_NOTICE == "1" ) $BD_NOTICE = "Y";
		else $BD_NOTICE = "N";

		if ( $BD_STATE == "1" ) $BD_STATE = "Y";
		else $BD_STATE = "N";


		$BD_TITLE = str_replace("\\", "", $BD_TITLE);
		$BD_TITLE = REQSTR($BD_TITLE);

		$BD_TEXT = str_replace("\\", "", $BD_TEXT);
		$BD_TEXT = REQSTR($BD_TEXT);

		$SQL2 = "
			insert into tbl_board_news
				(seq_sub, seq_level, category, subject, content, writer, passwd, nick_name, email1, email2, view_cnt, notice, regdate, secret, hidden, etc1)
			values(
				'".$no."' , 0, 69, '".$BD_TITLE."', '".$BD_TEXT."', '".$BD_ID."', '".$BD_SECURITYPW."', '".$BD_WRITER."', '".$email1."', '".$email2."', '".$BD_HIT."', '".$BD_NOTICE."', '".date("Y-m-d h:d:s",$BD_DATE)."', 'N', '".$BD_STATE."', '".(string)array_search($BD_WRITER, $Arr_speech)."'
			)
		";
		echo "BD_DATE : ".$BD_DATE." , regdate : ".date("Y-m-d h:d:s",$BD_DATE)."<BR>";
		echo $no."<BR>";
		echo "<xmp>".$SQL2."</xmp><BR><BR>";
		//echo addslashes($SQL2)."<BR><BR>";
		$result = mysql_query($SQL2);
		if ( !$result ) {

			echo $BD_NUM."<BR>";
			echo $SQL2."<BR>";
			echo "error : ".mysql_error()."<BR>";
			exit;
		}
		$no = $no + 1000;
	}
	unset($rs);
*/


?>





<?
//	$dbcon -> dbcon_close();

//	exit;

?>


<?
	/*
-- 전체회원
SELECT
	MU_NUM , MU_STATE , MU_LEVEL , MU_ID , MU_PW , MU_NAME , MU_EMAIL , MU_EMAIL_RE , MU_JUMINNUM , MU_SEX , MU_BIRTHDAY , MU_MOBILE , MU_TEL , MU_ZIPCODE , MU_ADDR1 , MU_ADDR2
FROM
	MEMBER_USER
ORDER BY
	MEMBER_USER.MU_NUM DESC


-- 탈퇴회원
select
	MU_NUM , MU_STATE , MU_LEVEL , MU_ID , MU_PW , MU_NAME , MU_EMAIL , MU_EMAIL_RE , MU_JUMINNUM , MU_SEX , MU_BIRTHDAY , MU_MOBILE , MU_TEL , MU_ZIPCODE , MU_ADDR1 , MU_ADDR2
	FROM
	MEMBER_USER
where
	MU_STATE = 'Del'
ORDER BY
	MEMBER_USER.MU_NUM DESC


-- 회원 상태별
SELECT mu_state, count(*) FROM MEMBER_USER group by mu_state


MU_NUM				= 고유번호, 정렬순서
MU_STATE				= 회원 상태 ( Nomal : 2932), (Del : 11)
MU_LEVEL				= 회원 레벨 ( 3 : 2943)
MU_ID				= 아이디
MU_PW				= 비밀번호
MU_NAME				= 이름
MU_EMAIL				= test@test.net
MU_EMAIL_RE			= 공지메일받음 ( "" : 47) ( N : 269 ) ( ok : 2628 )
MU_JUMINNUM			= 주민등록번호 md5() 암호화
MU_SEX					= 성별 ( 1 : 2875) 남자 , ( 2 : 69 ) 여자
MU_BIRTHDAY			= 생년월일 yyyymmdd
MU_MOBILE				= 핸드폰 --
MU_TEL					= 전화번호 --
MU_ZIPCODE			= 우편번호 없음
MU_ADDR1				= 주소1 없음
MU_ADDR2 				= 주소2 없음

SELECT case MU_EMAIL_RE when 'ok' then 'Y' when 'N' then 'N' else '' end  FROM MEMBER_USER WHERE 1



-- 중복회원 제거
SELECT * FROM MEMBER_USER WHERE MU_ID = 'seo4549'
delete from MEMBER_USER where MU_ID = '2430'

-- 기본정보 입력


INSERT INTO tbl_user
	( u_id, u_pw, u_name, u_jumin, u_jumin1, u_jumin2, u_email1, u_email2, u_email_receipt, u_sms_receipt, u_level, u_gubun, u_state, u_regdate )
SELECT

	distinct MU_ID, PASSWORD(MU_PW), MU_NAME, MU_JUMINNUM, NULL , NULL , substring_index(MU_EMAIL, '@', 1), substring_index(MU_EMAIL, '@', -1), (case MU_EMAIL_RE when 'ok' then 'Y' when 'N' then 'N' else '' end), 'Y', 1, 0, (CASE MU_STATE WHEN 'Normal' THEN 1 ELSE 2 END  ), now()
FROM
	MEMBER_USER
ORDER BY
	mu_num ASC
LIMIT 0, 1



#1062 - Duplicate entry 'seo4549' for key 1
SELECT * FROM MEMBER_USER WHERE MU_ID = 'seo4549'
delete from MEMBER_USER where MU_ID = '2430'



--추가 정보 입력

INSERT INTO tbl_user_info
	(u_id, u_hp, u_tel, u_fax, u_post, u_addr1, u_addr2, u_area, u_homepage, u_sex, u_birth, u_birth_luner)
SELECT
	MU_ID, MU_MOBILE, MU_TEL, NULL , MU_ZIPCODE, MU_ADDR1, MU_ADDR2, NULL , NULL , (CASE MU_SEX WHEN '1' THEN 'M' ELSE 'W' END  ), MU_BIRTHDAY, 0
FROM
	MEMBER_USER
ORDER BY
	MU_NUM ASC
LIMIT 0, 1










Table : MIN_BBS_DATA

BD_NUM				= 고유번호
BD_BBSCODE			= 게시판 구분값
						AFTER : 171
						CONSULT : 1
						INFORMATION : 239
						NOTICE : 222

BD_STATE				= 삭제 여부
						Del 38
						Normal 595

BD_NOTICE				= 공지글 여부
						0 626
						1 7

BD_SECURITY			= 비밀글 여부
						OPEN 633

BD_SECURITYPW			= 비밀글 비밀번호
BD_REF				= 부모글 혹은 자신글 번호 SEQ
BD_REFLEVEL			= 답변글 깊이
BD_REFSTEP			= 답변글 순서
BD_ID					= 작성자 아이디
BD_WRITER				= 작성자 이름
BD_EMAIL				= 작성자 이메일
BD_AHEAD				= ??
						일반 633
BD_TITLE				= 글 제목
BD_HTML				= ??
BD_TEXT				= 글 내용
BD_FILECODE			= 파일코드
BD_IP					= 작성자 아이피
BD_DATE				= 작성일
BD_HIT				= 조회수



> 보도자료(언론보도/방송보도/기타매체)
> 온라인상담
> 치료후기
> 공지사항



-- 보도자료 = 언론보도(처리됨)
select * from MIN_BBS_DATA where BD_STATE = 'Normal' and BD_BBSCODE ='INFORMATION' and BD_NOTICE=0 order by BD_REF desc,BD_REFSTEP asc limit 0, 15



-- 치료후기(처리됨)
select * from MIN_BBS_DATA where BD_STATE = 'Normal' and BD_BBSCODE ='AFTER' and BD_NOTICE=0 order by BD_REF desc,BD_REFSTEP asc limit 0, 15

-- 공지사항(처리됨)
select * from MIN_BBS_DATA where BD_STATE = 'Normal' and BD_BBSCODE ='NOTICE' and BD_NOTICE=0 order by BD_REF desc,BD_REFSTEP asc limit 0, 15



-- 보도자료 - 동영상 (처리됨)
select * from MIN_MOVIE where MO_STATE = 'Normal' and MO_BBSCODE ='MOVIE_TV' order by MO_NUM desc limit 0, 16


-- 온라인상담
select * from MIN_CONSULT where CON_STATE = 'Normal' and CON_A !='' order by CON_NUM desc limit 0, 7




INSERT INTO tbl_board_news
	(seq_sub, seq_level, category, subject, content, writer, passwd, nick_name, email1, email2, view_cnt, notice, regdate, secret, hidden)
SELECT
	seq_sub, seq_level, category, subject, content, writer, passwd, nick_name, email1, email2, view_cnt, notice, regdate, secret, hidden
FROM
	MIN_BBS_DATA
WHERE
	BD_BBSCODE ='INFORMATION'
	and BD_NOTICE=0



BD_NUM				= 고유번호
BD_BBSCODE			= 게시판 구분값
						AFTER : 171
						CONSULT : 1
						INFORMATION : 239
						NOTICE : 222

BD_STATE				= 삭제 여부
						Del 38
						Normal 595

BD_NOTICE				= 공지글 여부
						0 626
						1 7

BD_SECURITY			= 비밀글 여부
						OPEN 633

BD_SECURITYPW			= 비밀글 비밀번호
BD_REF				= 부모글 혹은 자신글 번호 SEQ
BD_REFLEVEL			= 답변글 깊이
BD_REFSTEP			= 답변글 순서
BD_ID					= 작성자 아이디
BD_WRITER				= 작성자 이름
BD_EMAIL				= 작성자 이메일
BD_AHEAD				= ??
						일반 633
BD_TITLE				= 글 제목
BD_HTML				= ??
BD_TEXT				= 글 내용
BD_FILECODE			= 파일코드
BD_IP					= 작성자 아이피
BD_DATE				= 작성일
BD_HIT				= 조회수


select
	BD_NUM, BD_BBSCODE, BD_STATE, BD_NOTICE, BD_SECURITY, BD_SECURITYPW, BD_REF, BD_REFLEVEL, BD_REFSTEP, BD_ID, BD_WRITER, BD_EMAIL, BD_AHEAD, BD_TITLE, BD_HTML, BD_TEXT, BD_FILECODE, BD_IP, BD_DATE, BD_HIT
from
	MIN_BBS_DATA
where
	BD_BBSCODE ='INFORMATION'
	and BD_NOTICE=0
order by
	BD_REF desc,BD_REFSTEP asc
limit 0, 15

*/



?>

<?
/*
#### 공지사항 이전작업
$SQL = "
select
	BD_NUM, BD_BBSCODE, BD_STATE, BD_NOTICE, BD_SECURITY, BD_SECURITYPW, BD_REF, BD_REFLEVEL, BD_REFSTEP, BD_ID, BD_WRITER, BD_EMAIL, BD_AHEAD, BD_TITLE, BD_HTML, BD_TEXT, BD_FILECODE, BD_IP, BD_DATE, BD_HIT, substring_index(BD_EMAIL, '@', 1) as 'email1', substring_index(BD_EMAIL, '@', -1) as 'email2'
from MIN_BBS_DATA where  BD_BBSCODE ='NOTICE' order by BD_REF asc,BD_REFSTEP desc

";
	$rs = $dbcon -> query($SQL);

	echo $SQL."<BR>";

	$no = 1000;
	while($rows = $dbcon ->fetch_array($rs)) {
		extract($rows);
		unset($rows);


		if ( $BD_NOTICE == "1" ) $BD_NOTICE = "Y";
		else $BD_NOTICE = "N";

		if ( $BD_STATE == "1" ) $BD_STATE = "Y";
		else $BD_STATE = "N";


		$BD_TITLE = str_replace("\\", "", $BD_TITLE);
		$BD_TITLE = REQSTR($BD_TITLE);

		$BD_TEXT = str_replace("\\", "", $BD_TEXT);
		$BD_TEXT = REQSTR($BD_TEXT);

		$SQL2 = "
			insert into tbl_board_notice
				(seq_sub, seq_level, category, subject, content, writer, passwd, nick_name, email1, email2, view_cnt, notice, regdate, secret, hidden, etc1)
			values(
				'".$no."' , 0, 0, '".$BD_TITLE."', '".$BD_TEXT."', '".$BD_ID."', password('".$BD_SECURITYPW."') , '".$BD_WRITER."', '".$email1."', '".$email2."', '".$BD_HIT."', '".$BD_NOTICE."', '".date("Y-m-d h:d:s",$BD_DATE)."', 'N', '".$BD_STATE."', '".$BD_NUM."'
			)
		";
		//echo "BD_DATE : ".$BD_DATE." , regdate : ".date("Y-m-d h:d:s",$BD_DATE)."<BR>";
		echo "no : ".$no."<BR>";
		echo "BD_NUM : ".$BD_NUM."<BR>";
		echo "BD_REF : ".$BD_REF."<BR>";
		echo "BD_REFLEVEL : ".$BD_REFLEVEL."<BR>";
		echo "BD_REFSTEP : ".$BD_REFSTEP."<BR><BR>";
		echo "<xmp>".$SQL2."</xmp><BR><BR>";
		//echo addslashes($SQL2)."<BR><BR>";

		$result = mysql_query($SQL2);
		if ( !$result ) {

			echo $BD_NUM."<BR>";
			echo $SQL2."<BR>";
			echo "error : ".mysql_error()."<BR>";
			exit;
		}

		$no = $no + 1000;
	}
	unset($rs);
	exit;

	*/
?>



<?
/*
#### 치료후기 이전작업
$SQL = "
select
BD_NUM, BD_BBSCODE, BD_STATE, BD_NOTICE, BD_SECURITY, BD_SECURITYPW, BD_REF, BD_REFLEVEL, BD_REFSTEP, BD_ID, BD_WRITER, BD_EMAIL, BD_AHEAD, BD_TITLE, BD_HTML, BD_TEXT, BD_FILECODE, BD_IP, BD_DATE, BD_HIT
from MIN_BBS_DATA where BD_STATE = 'Normal' and BD_BBSCODE ='AFTER' and BD_NOTICE=0 order by BD_REF asc,BD_REFSTEP desc
";
	$rs = $dbcon -> query($SQL);

	echo $SQL."<BR>";

	$no = 1000;
	while($rows = $dbcon ->fetch_array($rs)) {
		extract($rows);
		unset($rows);


		if ( $BD_NOTICE == "1" ) $BD_NOTICE = "Y";
		else $BD_NOTICE = "N";

		if ( $BD_STATE == "1" ) $BD_STATE = "Y";
		else $BD_STATE = "N";


		$BD_TITLE = str_replace("\\", "", $BD_TITLE);
		$BD_TITLE = REQSTR($BD_TITLE);

		$BD_TEXT = str_replace("\\", "", $BD_TEXT);
		$BD_TEXT = REQSTR($BD_TEXT);

		$SQL2 = "
			insert into tbl_board_postscript
				(seq_sub, seq_level, category, subject, content, writer, passwd, nick_name, email1, email2, view_cnt, notice, regdate, secret, hidden, etc1)
			values(
				'".$no."' , 0, 0, '".$BD_TITLE."', '".$BD_TEXT."', '".$BD_ID."', '".$BD_SECURITYPW."', '".$BD_WRITER."', '".$email1."', '".$email2."', '".$BD_HIT."', '".$BD_NOTICE."', '".date("Y-m-d h:d:s",$BD_DATE)."', 'N', '".$BD_STATE."', '".$BD_NUM."'
			)
		";
		//echo "BD_DATE : ".$BD_DATE." , regdate : ".date("Y-m-d h:d:s",$BD_DATE)."<BR>";
		echo "no : ".$no."<BR>";
		echo "BD_NUM : ".$BD_NUM."<BR>";
		echo "BD_REF : ".$BD_REF."<BR>";
		echo "BD_REFLEVEL : ".$BD_REFLEVEL."<BR>";
		echo "BD_REFSTEP : ".$BD_REFSTEP."<BR><BR>";
		echo "<xmp>".$SQL2."</xmp><BR><BR>";
		//echo addslashes($SQL2)."<BR><BR>";

		$result = mysql_query($SQL2);
		if ( !$result ) {

			echo $BD_NUM."<BR>";
			echo $SQL2."<BR>";
			echo "error : ".mysql_error()."<BR>";
			exit;
		}

		$no = $no + 1000;
	}
	unset($rs);

exit;
*/


?>
<?
/*
#### 온라인상담 이전작업
$SQL = "
	SELECT
		CON_NUM, CON_PART, CON_SECRET, CON_STATE, CON_ID, CON_PW, CON_NAME, CON_EMAIL, CON_SEX, CON_AGE, CON_MOBILE, CON_TITLE, CON_TITLEA, CON_Q, CON_A, CON_DATEQ, CON_DATEA, CON_HIT, CON_HITA, CON_IP, substring_index(CON_EMAIL, '@', 1) as 'email1', substring_index(CON_EMAIL, '@', -1) as 'email2'
	FROM MIN_CONSULT
	ORDER BY CON_NUM asc
";

//CON_NUM : 글번호
//CON_PART :의미없는듯...( ONLINE )
//CON_SECRET : 비밀글 여부(O,X )
//CON_STATE : 삭제여부 (Del, Normal)
//CON_ID : 작성자 아이디
//CON_PW : 작성자 비밀번호
//CON_NAME : 작성자이름
//CON_EMAIL : 작성자 이메일
//CON_SEX : 작성자 성별
//CON_AGE : 작성자 나이
//CON_MOBILE : 작성자 연락처
//CON_TITLE : 질문 제목
//CON_TITLEA : 답변 제목
//CON_Q : 질문 내용
//CON_A : 답변 내용
//CON_DATEQ : 질문시간
//CON_DATEA : 답변시간
//CON_HIT : 질문 조회수
//CON_HITA : 답변 조회수
//CON_IP : 작성자 아이피


	$rs = $dbcon -> query($SQL);

	echo $SQL."<BR>";

	$no = 1000;
	while($rows = $dbcon ->fetch_array($rs)) {
		extract($rows);
		unset($rows);


		if ( $BD_NOTICE == "1" ) $BD_NOTICE = "Y";
		else $BD_NOTICE = "N";

		if ( $BD_STATE == "1" ) $BD_STATE = "Y";
		else $BD_STATE = "N";


		$CON_TITLE = str_replace("\\", "", $CON_TITLE);
		$CON_TITLE = REQSTR($CON_TITLE);

		$CON_Q = str_replace("\\", "", $CON_Q);
		$CON_Q = REQSTR($CON_Q);

		$CON_TITLEA = str_replace("\\", "", $CON_TITLEA);
		$CON_TITLEA = REQSTR($CON_TITLEA);

		$CON_A = str_replace("\\", "", $CON_A);
		$CON_A = REQSTR($CON_A);

		if ($CON_SECRET == "O" ) $CON_SECRET = 'N';
		else $CON_SECRET = 'Y';

		$SQL2 = "
			insert into tbl_board_contact
				(
					seq_sub
					, seq_level
					, category
					, subject
					, content
					, writer
					, passwd
					, nick_name
					, email1
					, email2
					, view_cnt
					, notice
					, regdate
					, secret
					, hidden
					, etc1
				)
			values (
				'".$no."'
				, 0
				, 0
				, '".$CON_TITLE."'
				, '".$CON_Q."'
				, '".$CON_ID."'
				, password('".$CON_PW."')
				, '".$CON_NAME."'
				, '".$email1."'
				, '".$email2."'
				, '".$CON_HIT."'
				, '".$BD_NOTICE."'
				, '".date("Y-m-d h:d:s",$CON_DATEQ)."'
				, '".$CON_SECRET."'
				, '".$BD_STATE."'
				, '".$CON_NUM."'
			)
		";

		//echo "BD_DATE : ".$BD_DATE." , regdate : ".date("Y-m-d h:d:s",$BD_DATE)."<BR>";
		echo "no : ".$no."<BR>";
		echo "BD_NUM : ".$BD_NUM."<BR>";
		echo "BD_REF : ".$BD_REF."<BR>";
		echo "BD_REFLEVEL : ".$BD_REFLEVEL."<BR>";
		echo "BD_REFSTEP : ".$BD_REFSTEP."<BR><BR>";
		echo "<xmp>".$SQL2."</xmp><BR><BR>";
		//echo addslashes($SQL2)."<BR><BR>";
		$result = mysql_query($SQL2);
		if ( !$result ) {

			echo $BD_NUM."<BR>";
			echo $SQL2."<BR>";
			echo "error : ".mysql_error()."<BR>";
			exit;
		}

		#### 답변 등록
		if ( getLen($CON_A) ) {
			$SQL3 = "
				insert into tbl_board_contact
					(
						seq_sub
						, seq_level
						, category
						, subject
						, content
						, writer
						, passwd
						, nick_name
						, email1
						, email2
						, view_cnt
						, notice
						, regdate
						, secret
						, hidden
						, etc1
					)
				values (
					'".($no-1)."'
					, 1
					, 0
					, '".$CON_TITLEA."'
					, '".$CON_A."'
					, 'admin'
					, password('1024')
					, '관리자'
					, 'admin'
					, 'iljoong.net'
					, '".$CON_HITA."'
					, '".$BD_NOTICE."'
					, '".date("Y-m-d h:d:s",$CON_DATEA)."'
					, '".$CON_SECRET."'
					, '".$BD_STATE."'
					, '".$CON_NUM."'
				)
			";
			echo "<xmp>".$SQL3."</xmp><BR><BR>";
			$result = mysql_query($SQL3);
			if ( !$result ) {

				echo $BD_NUM."<BR>";
				echo $SQL3."<BR>";
				echo "error : ".mysql_error()."<BR>";
				exit;
			}
		}
		$no = $no + 1000;
	}
	unset($rs);

	exit;
	*/
?>

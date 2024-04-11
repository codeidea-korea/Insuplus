<?
	$act = REQSTR($act, "");
	$seq = REQSTR($seq, "");
	$passwd = REQSTR($passwd, "");

	isnull($seq);


	#############################
	#### 기존데이타 가져오기 시작
	$SQL = "
		select
			writer, hidden, imgfile
	";
	if ( $bc_id == "07_beinfo") {
		$SQL .= "
			, ext1
		";
	}

	$SQL .= "
		from
			tbl_board_".$bc_id."
		where
			seq = '".$seq."'
		order by seq desc
		limit 0, 1
	";
	$OldRs = $dbcon -> fetch_row($dbcon -> query($SQL));

//		echo $SQL."<BR>";
	$Old_writer = $OldRs[0];
	$Old_hidden = $OldRs[1];
	$imgfile = $OldRs[2];

	if ( $bc_id == "07_beinfo") {
		$ext1 = $OldRs[3];
	}

//	echo "hidden : ".$OldRs[0]."<BR>";
//	exit;
	#### 기존데이타 가져오기 끝
	#############################


	#############################
	#### 비밀번호 체크 시작
	if ( $auth_level < $auth_admin && ( $now_writer != $Old_writer || $now_writer == "guest" )) {
		isnull($act);
		isnull($passwd);
		$SQL = "
			select
				count(*)
			from
				tbl_board_".$bc_id."
			where
				seq = '".$seq."'
				and passwd = password('".$passwd."')
			order by seq desc
			limit 0, 1
		";
		$PassRs = $dbcon -> getCount($SQL);

//		echo $SQL."<BR>";
//		echo $PassRs."<BR>";
//		exit;
		if ($PassRs == 0) {
			$dbcon -> dbcon_close();
			alert_back("비밀번호가 다릅니다.");
			exit;
		}
	}
	#### 비밀번호 체크 끝
	#############################



	if ($bc_state_use == "Y" && $Old_hidden != "D") {
		$DSQL = "
			update tbl_board_".$bc_id."
			set
				hidden = 'D'
			where
				seq = '".$seq."'
		";
	}
	else {

		#############################
		#### 파일 삭제 처리

		$ObjFileName = "imgfile";
		${"Arr_".$ObjFileName} = setFileName(${$ObjFileName});
		for ($i = 0; $i < count(${"Arr_".$ObjFileName}); $i++ ) {
			DeleteFile($upload_path."/".${"Arr_".$ObjFileName}[$i][1]);
			if ( $bc_upfile_image_thum == "Y" ) {
				DeleteFile($upload_path."/".${"Arr_".$ObjFileName}[$i][1].".thumb");
			}
		}




		if ( $bc_id == "07_beinfo") {
			$ObjFileName = "ext1";
			${"Arr_".$ObjFileName} = setFileName(${$ObjFileName});
			for ($i = 0; $i < count(${"Arr_".$ObjFileName}); $i++ ) {
				DeleteFile($upload_path."/".${"Arr_".$ObjFileName}[$i][1]);
				if ( $bc_upfile_image_thum == "Y" ) {
					DeleteFile($upload_path."/".${"Arr_".$ObjFileName}[$i][1].".thumb");
				}
			}

		}



		$SQL = "
			select idx
			from
				tbl_file
			where
				category='board' and bc_id = '".$bc_id."' and seq = '".$seq."'
		";
		$result = $dbcon -> query($SQL);
		while($rows = $dbcon -> fetch_row($result) ) {
			upfileDelete($rows[0]);
		}

		#############################

		#############################
		#### 코멘트 삭제 처리
		$SQL = "
			delete from tbl_comment
			where
				category='board' and bc_id = '".$bc_id."' and seq = '".$seq."'
		";
		$dbcon -> query($SQL);
		#############################


		$DSQL = "
			delete from tbl_board_".$bc_id."
			where
				seq = '".$seq."'
		";
	}

	//echo $DSQL."<BR>";exit;

	$result = $dbcon -> query($DSQL);
	if (!$result) {
		$dbcon -> dbcon_close();
		//echo "에러<BR>".mysql_errno($dbcon)." : ".mysql_error($dbcon)." <br>";
		//echo "에러";
		alert_back("삭제 오류입니다. 관리자에게 문의하여 주십시오.");
		exit;
	}

	$dbcon -> dbcon_close();

	@include_once($path_skin_board.$bc_skin."/delete_update.php");

	alert_page("삭제되었습니다.","?mode=list".$parameter);
?>

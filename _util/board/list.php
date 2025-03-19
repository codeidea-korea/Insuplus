<?
	########################################
	#### 공지글을 가져온다. ####

	if ($bc_notice_use == "Y") {
		$field = " * ";
//		if ($bc_category_use == "Y") {
//			$field .= " , (select cate_name from tbl_category where category='board' and bc_id = '".$bc_id."' and idx = A.category ) as cate_name";
//		}

		if ($bc_comment_use == 'Y') {
			$field .= " , (select count(idx) from tbl_comment where category='board' and bc_id = '".$bc_id."' and seq = A.seq ) as cmt_cnt ";
		}

		if ($bc_upfile_cnt > 0 ) {
			$field .= " , (select count(idx) from tbl_file where category='board' and bc_id = '".$bc_id."' and seq = A.seq ) as file_cnt ";
		}

		$table = "tbl_board_".$bc_id." A";
		$where = " and notice = 'Y' "; 
		$where .= $query_where;
		if ( !($bc_hidden_use == "Y" && $auth_hidden) ) { $where .= " and hidden <> 'Y' ";}
		if ($auth_level < $auth_admin ) $where .= " and hidden <> 'D' ";
		$orderby = " seq_sub desc ";
		$limit = "";
	if ($ss_u_id=='izm9870'){
//	echo "where : ".$where."<BR>";
	}
		$ArrNoticeRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);

		$total_Notice = $ArrNoticeRs[0];
	}
 
	########################################

//	echo "query_where : ".$query_where."<BR>";

	########################################
	#### 게시글을 가져온다.. ####
	$field = " * ";
	if ($bc_category_use == "Y") {
		$field .= " , (select cate_name from tbl_category where category='board' and bc_id = '".$bc_id."' and idx = A.category ) as cate_name";
	}

	if ($bc_comment_use == 'Y') {
		$field .= " , (select count(idx) from tbl_comment where category='board' and bc_id = '".$bc_id."' and seq = A.seq ) as cmt_cnt ";
	}

	if ($bc_upfile_cnt > 0 ) {
		$field .= " , (select count(idx) from tbl_file where category='board' and bc_id = '".$bc_id."' and seq = A.seq ) as file_cnt ";
	}
//	echo $query_where;
	$table = "tbl_board_".$bc_id." A";
	$where = " and notice <> 'Y' ";
	$where .= $query_where;
	if ($sorder){
	$orderby = $sorder;
	}else{
	$orderby = " seq_sub desc, seq desc ";
	}
	$limit = $first.", ".$last;
	if ($ss_u_id=='izm9870'){
//	echo "where : ".$where."<BR>";
	}
	$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);
	$total_record = $ArrListRs[0];
	########################################


	########################################
	#### 전체 페이지수를 계산한다.
	$total_page = ceil($total_record/$num_per_page);
	$no = $total_record - $first;

	########################################  

	//echo $bc_skin."<BR>";
    // echo $path_skin_board.$bc_skin; 
	include_once $path_skin_board.$bc_skin."/list_top.php";
    // echo "2"; 
	#### 공지글 시작 ####
	if ($bc_notice_use == "Y") {
		if ( $total_Notice > 0 ) {
			$total_notice_cnt = 0;
			while($NoticeRs = $dbcon -> fetch_array($ArrNoticeRs[1])) {
				extract($NoticeRs);
				unset($NoticeRs);

				$total_notice_cnt = $total_notice_cnt +1;


				if ( $bc_editor_use == "Y" ) {
					$content = RESSTR($content);
				}
				else {
					$content = RESSTRTEXT($content);
				}

				$subject = getStrCut( $subject, $bc_title_size, "...");

				// 계층형 레벨
				$re = "";
				if ($seq_level > 0 ) {
					for ($i = 0; $i < $seq_level; $i++) $re .= "&nbsp;&nbsp;&nbsp;&nbsp;";
					$re .= $img_re;
				}

				$PrintRegDate = date('Y.m.d', strtotime($regdate) );
				// NEW 이미지
				$sNew="";
				if(strtotime($regdate) > (time() - (60 * 60 * 24 * 2))) {
					$sNew = $img_new;
				}

				// 파일 이미지
				$file_img = "";
				if ( $bc_upfile_cnt > 0 && getLen($up_file) > 0 ) {
					$up_file_img = "<img src='".$UpFileDirectory.$realname."'  border='0'  width='98' height='68'>";
				}

				// hidden 표시
				$print_hidden = "";
				if ( $bc_hidden_use == "Y" && $auth_hidden) {
					if ($hidden == "Y") {
						$print_hidden = "<font color='red'>[hidden]</font>";
					}
					if ($hidden == "D") {
						$print_hidden = "<font color='red'>[delete]</font>";
					}
				}
			// 이미지 처리
			$print_list_image = "";
			$print_list_image_size = "";
			$image_view_width = $bc_upfile_image_thum_width;
			$image_view_height = $bc_upfile_image_thum_height;
			$FC_file = "";

			if ($bc_upfile_image == "Y" && $bc_upfile_image_thum == "Y" ) {
				$ObjFileName = "imgfile";

				if ( getLen(${$ObjFileName}) > 0 ) {
					${"Arr_".$ObjFileName} = setFileName(${$ObjFileName});

					if (${"Arr_".$ObjFileName}[0][1]){

						for ( $i = 0 ; $i < count(${"Arr_".$ObjFileName}); $i++) {

							if ( ${"info".$ObjFileName} = @getimagesize($upload_path."/".${"Arr_".$ObjFileName}[$i][1].".thumb") ) {
								${"info".$ObjFileName."width"} = ${"info".$ObjFileName}[0];
								${"info".$ObjFileName."height"} = ${"info".$ObjFileName}[1];

								if ( ${"info".$ObjFileName."width"} >= ${"info".$ObjFileName."height"}){
									if ( ${"info".$ObjFileName."width"} > $image_view_width ) {
										${"size".$ObjFileName} = " width=\"".$image_view_width."\" ";
									}
									else {
										${"size".$ObjFileName} = " width=\"".${"info".$ObjFileName."width"}."\" ";
									}
								}
								else {
									if ( ${"info".$ObjFileName."height"} > $image_view_height ) {
										${"size".$ObjFileName} = " height=\"".$image_view_height."\" ";
									}
									else {
										${"size".$ObjFileName} = " height=\"".${"info".$ObjFileName."height"}."\" ";
									}
								}

								$FC_file = $upload_url."/".${"Arr_".$ObjFileName}[$i][1].".thumb";
								$FC_file_img = $upload_url."/".${"Arr_".$ObjFileName}[$i][1];
								$print_list_image = "<img src=\"".$upload_url."/".${"Arr_".$ObjFileName}[$i][1].".thumb"."\" name=\"Image1\" width=\"".$image_view_width."\" height=\"".$image_view_height."\" border=\"0\" />";
								$print_list_image_url = $upload_url."/".${"Arr_".$ObjFileName}[$i][1].".thumb";
								//$print_list_image = "<img src=\"".$upload_url."/".${"Arr_".$ObjFileName}[$i][1].".thumb"."\" name=\"Image1\" ".${"size".$ObjFileName}." border=\"0\" />";

								break;
							}
						}
					}else{
								$print_list_image = "<img src=\"".$upload_url."/".${$ObjFileName}."\" name=\"Image1\" width=\"110\" height=\"102\" border=\"0\" />";
					}
				}else{
					if ($bc_id=="post"){
						$print_list_image = "";
					}else{
						$print_list_image = "<img src='/images/board/test_img.jpg' name=\"Image1\" width=\"111\" height=\"73\" border=\"0\" style=\"border:2px #CCCCCC solid;\" />";
					}
				}
			}
			else {
				$print_list_image = "<img src='/images/board/test_img.jpg' name=\"Image1\" width=\"111\" height=\"73\" border=\"0\" style=\"border:2px #CCCCCC solid;\" />";
			}

				$print_secret = "";//			icon_secret
				$view_link = "<a href=\"javascript:view_go('".$seq."');\">";
				$view_onlink = "<a href=\"javascript:view_ongo('".$seq."');\">";


				// 비밀글 사용시
	//			echo "bc_secret_use : ".$bc_secret_use."<BR>";
	//			echo "secret : ".$secret."<BR>";
				if ( $bc_secret_use == "Y" && $secret == "Y" ) {

					$checkPass = true;
					$print_secret = $img_secret ;

					// 관리자는 그냥 패스~
					if ( $auth_level >= $auth_admin ) {
						$checkPass = false;
					}

					// 현재 접속자가 게시글 등록자이거나 현재접속자가 비회원이 아니라면
					if ( $now_writer == $writer && $writer != "guest" ) {
						$checkPass = false;
					}

					//echo "seq_level : ".$seq_level."<BR>";
					// 답변글이고
					if ( $seq_level > 0 ) {
						$SQL = "
							select writer from tbl_board_".$bc_id." where seq_sub = '".($seq_sub+1)."'
						";
						$Parent_writer = $dbcon -> getCount($SQL);

						//echo "Parent_writer : ".$Parent_writer."<BR>";

						//현재 접속자가 부모글의 작성자이거나 현재접속자가 비회원이 아니라면
						if ( $Parent_writer == $now_writer && $writer != "guest" ) {
							$checkPass = false;
						}
						unset ($Parent_writer);
					}

				}

				if ( $checkPass ) {
					$view_link = "<a href=\"javascript:view_go_secret('".$seq."');\">";
					$view_onlink = "<a href=\"javascript:view_ongo('".$seq."');\">";
				}

				// #### 카테고리 표시
//				$print_cate_name = "";
//				if ($bc_category_use == "Y" && $category) {
//					$print_cate_name = "[".$cate_name."] ";
//				}

				// 코멘트 갯수 출력
				$print_cmt_cnt = "";
				if ($bc_comment_use == 'Y') {
					if ($cmt_cnt > 0 ) $print_cmt_cnt = "(".$cmt_cnt.")";
				}

				$print_file = "";
				if ($bc_upfile_cnt > 0 ) {
					if ($file_cnt > 0 )
						$print_file = $img_file;
				}

				$list_type = "notice";
				include $path_skin_board.$bc_skin."/list_middle.php";
			}
		}
		unset($ArrNoticeRs);
	}

      #### 공지글 끝 ####
?>

<?
	#### 게시글을 시작 ####
	if ( $total_record > 0 ) {
		$temp_num = 1;
		$temp_num_img=0;
		while($ListRs = $dbcon -> fetch_array($ArrListRs[1])) {
			extract($ListRs);
			unset($ListRs);

			if ( $bc_editor_use == "Y" ) {
				$content = RESSTR($content);
			}
			else {
				$content = RESSTRTEXT($content);
			}

			$subject = getStrCut( $subject, $bc_title_size, "...");


			// 계층형 레벨
			$re = "";
			if ($seq_level > 0 ) {
				for ($i = 0; $i < $seq_level; $i++) $re .= "&nbsp;&nbsp;&nbsp;&nbsp;";
				$re .= $img_re;
			}

			$PrintRegDate = date('Y.m.d', strtotime($regdate) );
			// NEW 이미지
			$sNew="";
			if(strtotime($regdate) > (time() - (60 * 60 * 24 * 2))) {
				$sNew = $img_new;
			}

			// 검색어
//			if ( getLen($search_text) > 0 ) {
//				$search_string = $search_text;
//				$replace_string = "<b><font color='#FF0000'>".$search_text."</font></b>";
//
//				if ($search == "all") {
//					$subject = str_replace($search_string,$replace_string,$subject);
//					$content = str_replace($search_string,$replace_string,$content);
//					$nick_name = str_replace($search_string,$replace_string,$nick_name);
//				}
//				else {
//					${$search} = str_replace($search_string,$replace_string,${$search});
//				}
//			}


			// 파일 이미지
			$file_img = "";
			if ( $bc_upfile_cnt > 0 && getLen($up_file) > 0 ) {
				$up_file_img = "<img src='".$UpFileDirectory.$realname."'  border='0'  width='98' height='68'>";
			}

			// hidden 표시
			$print_hidden = "";
			if ( $bc_hidden_use == "Y" && $auth_hidden) {
				if ($hidden == "Y") {
					$print_hidden = "<font color='red'>[hidden]</font>";
				}
				if ($hidden == "D") {
					$print_hidden = "<font color='red'>[delete]</font>";
				}
			}

			$print_secret = "";//			icon_secret
			$view_link = "<a href=\"javascript:view_go('".$seq."');\">";

			// 비밀글 사용시
//			echo "bc_secret_use : ".$bc_secret_use."<BR>";
//			echo "secret : ".$secret."<BR>";
			if ( $bc_secret_use == "Y" && $secret == "Y" ) {

				$checkPass = true;
				$print_secret = $img_secret ;

				// 관리자는 그냥 패스~
				if ( $auth_level >= $auth_admin ) {
					$checkPass = false;
				}

				// 현재 접속자가 게시글 등록자이거나 현재접속자가 비회원이 아니라면
				if ( $now_writer == $writer && $writer != "guest" ) {
					$checkPass = false;
				}

				//echo "seq_level : ".$seq_level."<BR>";
				// 답변글이고
				if ( $seq_level > 0 ) {
					$SQL = "
						select writer from tbl_board_".$bc_id." where seq_sub = '".($seq_sub+1)."'
					";
					$Parent_writer = $dbcon -> getCount($SQL);

					//echo "Parent_writer : ".$Parent_writer."<BR>";

					//현재 접속자가 부모글의 작성자이거나 현재접속자가 비회원이 아니라면
					if ( $Parent_writer == $now_writer && $writer != "guest" ) {
						$checkPass = false;
					}
					unset ($Parent_writer);
				}

			}

			if ( $checkPass ) {
				$view_link = "<a href=\"javascript:view_go_secret('".$seq."');\">";
				$view_onlink = "<a href=\"javascript:view_ongo('".$seq."');\">";
			}
			unset ($checkPass);

			// #### 카테고리 표시
			$print_cate_name = "";
			if ($bc_category_use == "Y" && $category) {
				$print_cate_name = $cate_name;
			}

			// 코멘트 갯수 출력
			$print_cmt_cnt = "";
			if ($bc_comment_use == 'Y') {
				if ($cmt_cnt > 0 ) $print_cmt_cnt = "(".$cmt_cnt.")";
			}

			$print_file = "";
			if ($bc_upfile_cnt > 0 ) {
				if ($file_cnt > 0 )
					$print_file = $img_file;
			}



			$print_list_image = "";
			$print_list_image_size = "";
			$image_view_width = $bc_upfile_image_thum_width;
			$image_view_height = $bc_upfile_image_thum_height;
			$FC_file = "";

			if ($bc_upfile_image == "Y" && $bc_upfile_image_thum == "Y" ) {
				$ObjFileName = "imgfile";

				if ( getLen(${$ObjFileName}) > 0 ) {
					${"Arr_".$ObjFileName} = setFileName(${$ObjFileName});

					if (${"Arr_".$ObjFileName}[0][1]){

						for ( $i = 0 ; $i < count(${"Arr_".$ObjFileName}); $i++) {

							if ( ${"info".$ObjFileName} = @getimagesize($upload_path."/".${"Arr_".$ObjFileName}[$i][1]."") ) {
								${"info".$ObjFileName."width"} = ${"info".$ObjFileName}[0];
								${"info".$ObjFileName."height"} = ${"info".$ObjFileName}[1];

								if ( ${"info".$ObjFileName."width"} >= ${"info".$ObjFileName."height"}){
									if ( ${"info".$ObjFileName."width"} > $image_view_width ) {
										${"size".$ObjFileName} = " width=\"".$image_view_width."\" ";
									}
									else {
										${"size".$ObjFileName} = " width=\"".${"info".$ObjFileName."width"}."\" ";
									}
								}
								else {
									if ( ${"info".$ObjFileName."height"} > $image_view_height ) {
										${"size".$ObjFileName} = " height=\"".$image_view_height."\" ";
									}
									else {
										${"size".$ObjFileName} = " height=\"".${"info".$ObjFileName."height"}."\" ";
									}
								}
								$FC_file = $upload_url."/".${"Arr_".$ObjFileName}[$i][1]."";
								$FC_file_img = $upload_url."/".${"Arr_".$ObjFileName}[$i][1];
								$print_list_image = "<img src=\"".$upload_url."/".${"Arr_".$ObjFileName}[$i][1].""."\" name=\"Image1\" width=\"".$image_view_width."\" height=\"".$image_view_height."\" border=\"0\" />";
								$print_list_image_url = $upload_url."/".${"Arr_".$ObjFileName}[$i][1]."";
								break;
							}
						}
					}else{
								$print_list_image = "<img src=\"".$upload_url."/".${$ObjFileName}."\" name=\"Image1\" width=\"110\" height=\"102\" border=\"0\" />";
					}
				}else{
					if ($bc_id=="post"){
						$print_list_image = "";
					}else{
						$print_list_image = "<img src='/images/board/test_img.jpg' name=\"Image1\" width=\"111\" height=\"73\" border=\"0\" style=\"border:2px #CCCCCC solid;\" />";
					}
				}
			}
			else {
				if ($bc_id=="post"){
					$print_list_image = "";
				}else{
					$ObjFileName = "imgfile";
					${"Arr_".$ObjFileName} = explode(",",$imgfile);
					$print_list_image = "<img src='".$upload_url."/".${"Arr_".$ObjFileName}[1]."' name=\"Image1\" width=\"111\" height=\"73\" border=\"0\" style=\"border:0px #CCCCCC solid;\" />";
				}
			}



//			$print_list_image = "";
//			$print_list_image_size = "";
//			$image_view_width = $bc_upfile_image_width;
//			$image_view_height = $bc_upfile_image_height;
//			$FC_file = "";
//
//			if ( $bc_upfile_cnt > 0 && $bc_upfile_image == "Y" && $file_cnt > 0 ) {
//				$FileRs = upfileSelect($seq);
//
//				while($RowFileRs = $dbcon -> fetch_row($FileRs) ) {
//					$FC_file_name				= $RowFileRs[4];
//					$FC_file_realname			= $RowFileRs[5];
//
//					if (is_file($upload_path."/".$FC_file_realname)) {
//						$Arr_file_info = pathinfo($upload_path."/".$FC_file_realname);
//						//echo $Arr_file_info['extension']."<BR>";
//						$file_info = strtolower($Arr_file_info['extension']);
//						if ( ( $file_info == "jpg" || $file_info == "jpeg" || $file_info == "gif" ) ) {
//
//							$up_file_info = getimagesize($upload_path."/".$FC_file_realname);
//							$up_file_width = $up_file_info[0];
//							$up_file_height = $up_file_info[1];
//							$up_file_type = $up_file_info[2];
//
//							if ($up_file_type < 4) {
//
////								// 가로형 & 정사각형
////								if ($up_file_width >= $up_file_height) {
////									if ($up_file_width > $image_view_width) {
////										$print_list_image_size = " width='".$image_view_width."' ";
////									}
////									else {
////										$print_list_image_size = " width='".$up_file_width."' ";
////									}
////								}
////
////								// 세로형
////								else if ($up_file_width < $up_file_height) {
////									if ($up_file_height > $image_view_height) {
////										$print_list_image_size = " height='".$image_view_height."' ";
////									}
////									else {
////										$print_list_image_size = " height='".$up_file_height."' ";
////									}
////								}
//								$print_list_image_size = " width=92 height=59 ";
//
//								$FC_file = $url_data."board/".$bc_id."/".$FC_file_realname;
//								$print_list_image = "<img src='".$FC_file."' ".$print_list_image_size." >";
//								break;
//							}
//						}
//					}
//					unset($RowFileRs);
//				}
//				unset($FileRs);
//			}
//			else {
//				$print_list_image = "<img src='/images/noimg.gif' width='72' height='54' >";
//			}


			$list_type = "list";
			include $path_skin_board.$bc_skin."/list_middle.php";

			$no--;
			$temp_num++;
			$temp_num_img++;
		}

		if ( $list_type_gallery == true ) {
			if ( ( ($temp_num-1) % 4) > 0 ) {
				//echo ( 4 - ($temp_num-1) % 4)."<BR>";
				for ($TempRoopNum = 0; $TempRoopNum < ( 4 - ($temp_num-1) % 4); $TempRoopNum++ ) {
						echo "
													<td >&nbsp;</td>
						";
				}
			}
		}
      
	}
	else {
       
		$list_type = "null";
		include $path_skin_board.$bc_skin."/list_middle.php";
	}
	unset($ArrListRs);
	#### 게시글을 끝 ####

	include $path_skin_board.$bc_skin."/list_bottom.php";

?>
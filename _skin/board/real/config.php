<?


	// 이미지 이미지 설정
	$img_blank = "<img src='".$url_skin_board.$bc_skin."/images/icon_re.gif' align='absmiddle' border='0'>";
	$img_notice = "<img src=\"".$url_skin_board.$bc_skin."/images/icon_notice.gif\" align=absmiddle border='0' />";

	// new 이미지
	$img_new = "<img src=\"/images/common/icon/icon_n.gif\" alt=\"n\" />";

	$img_re = "<img src=\"".$url_skin_board.$bc_skin."/images/icon_re.gif\" align=\"absmiddle\" border=0 />";
	$print_file = "<img src=\"/images/common/icon/icon_file.gif\" alt=\"file\" />";
	$img_file = "<img src=\"/images/common/icon/icon_file.gif\" alt=\"file\" />";
	$img_secret = "<img src=\"".$url_skin_board.$bc_skin."/images/icon_secret.gif\">";





	$page_btn_first ="<img src=\"".$url_skin_board.$bc_skin."/images/b_last.gif\" />";  // >> 버튼
	$page_btn_prev ="<img src=\"".$url_skin_board.$bc_skin."/images/b_prev.gif\" />"; // > 버튼
	$page_btn_next ="<img src=\"".$url_skin_board.$bc_skin."/images/b_next.gif\" />"; // < 버튼
	$page_btn_last ="<img src=\"".$url_skin_board.$bc_skin."/images/b_first.gif\" />";  // << 버튼


	#############################
	#### 카테고리 설정 가져오기
	if ($bc_category_use == "Y" ) {
		$search_category = REQSTR($search_category, "all");

		$field = " * ";
		$table = " tbl_category ";
		$where = " and category = 'board' and bc_id = '".$bc_id."' ";
		$orderby = " cate_sort asc ";
		$limit = " ";

		$ArrCateListRs = $dbcon -> getList($field = "*", $table , $where, $orderby, $limit);

		$CateTotalCount = $ArrCateListRs[0];

		if ( $CateTotalCount == 0 ) {
			$dbcon -> dbcon_close();
			alert_back('카테고리 설정이 잘못되었습니다.\n관리자에게 문의하여 주십시오.');
			exit;
		}

		if ( $search_category != "all" ) {
			$query_where .= " and category = '".$search_category."' ";
		}
		$parameter .= "&search_category=".$search_category;
	}
	#############################

	#############################
	#### 파일 설정 세팅


	$list_type = "gallery";// 이미지 width ( 갤러리일때 사용하면 좋다.)
	$image_view_width = 600;
	$Arr_bc_upfile_ext_upload			= explode(",",$bc_upfile_ext_upload);			// 제한 확장자
	$bc_upfile_size						= 1024 * 1024 * $bc_upfile_size;				// 제한 사이즈
	$upload_path							= $path_data."board/".$bc_id;					// 업로드 폴더
	$upload_url								= $url_data."board/".$bc_id;					// 업로드 폴더
	// 업로드 사이즈
	$upload_size = 1024 * 1024 * 2;// "2048000"
	$Stop_Extension		= explode(",", $bc_upfile_ext_upload);
	$Stop_Size				= 1024 * 1024 * 10;

	$UpFileDirectory		= $path_root."_data/bbs/".$bc_id."/";
	$UpFileCategory		= $bc_id;

	#############################



	#############################
	#### 페이지 설정
	$page = REQSTR($page, 1);
	$num_per_page = REQSTR($num_per_page, $bc_list_size);
	$page_per_block = REQSTR($page_per_block, $bc_page_size);

	$first = $num_per_page*($page-1);
	$last = $num_per_page;

	$parameter .= "&search_ext=".$search_ext."&num_per_page=".$num_per_page."&page_per_block=".$page_per_block;

	#############################

	########################################
	#### 검색 설정
//	echo "search : ".$search."<BR>";
//	echo "search_text : ".$search_text."<BR>";

	if ( $bc_search_use == "Y" ) {
		$search = REQSTR($search, "");
		$search_text = REQSTR($search_text, "");

		if ($search == "all") {
			$query_where .= " and ( subject like '%".$search_text."%' or content like '%".$search_text."%' or writer like '%".$search_text."%' ) ";
		}
		else {
			if ( (getLen($search_text) > 0) ) {
				$query_where .= " and ".$search." like '%".$search_text."%'";
			}
		}
		$parameter .= "&search=".$search."&search_text=".$search_text;

	}


		#### parameter 설정$search_category
		#############################


	########################################
	####  추가 검색 설정
//	for ( $i = 1 ; $i <= 10 ; $i++ ) {
//		${"search_ext".$i} = REQSTR(${"search_ext".$i}, "");
//		if ( ${"search_ext".$i} ) {
//			$query_where .= " and ext".$i." = '".${"search_ext".$i}."' ";
//		}
//		$parameter .= "&search_ext1=".$search_ext1;
//	}
	#############################


	#### hidden 설정 시작
	if ( !($bc_hidden_use == "Y" && $auth_hidden) ) {
		$query_where .= " and hidden <> 'Y' ";
	}

	#### 삭제 파일 처리
	if ($bc_state_use == "Y") {
		if ($auth_level < $auth_admin) {
			$query_where .= " and hidden <> 'D' ";
		}
	}



?>
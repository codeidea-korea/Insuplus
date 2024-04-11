<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?

	$pr_sort = REQSTR($_POST[pr_sort],"");
	$pr_sort_old = REQSTR($_POST[pr_sort_old],"");


	//$dbcon -> setDebug(0);
	#### Option 처리
//	$pr_option = setCategoryOption($pr_option);
//	$pr_option_value = setCategoryOption($pr_option_value);
	$pr_option = "";
	for ( $i = 0 ; $i < count($_POST["pr_option_name"]); $i++) {
		if ( $_POST["pr_option_name"][$i] ) {
			if ($i > 0) {
				$pr_option .= "|";
			}
			$pr_option .= $_POST["pr_option_check"][$i]."@".$_POST["pr_option_name"][$i]."^".$_POST["pr_option_text"][$i];
		}
	}
	$pr_option = str_replace(" ", "", $pr_option);


	$search_pc_num				= REQSTR($search_pc_num, "");
	$parameter = "search_pc_num=".$search_pc_num;

	$pr_content = RESSTR($_POST["pr_content"]);
	$pr_content2 = RESSTR($_POST["pr_content2"]);
	$pr_content3 = RESSTR($_POST["pr_content3"]);
	$pr_content4 = RESSTR($_POST["pr_content4"]);

	$pr_link = RESSTR($_POST["pr_link"]);

		$pr_sale_yn = $_POST["pr_sale_yn"];
		$pr_cost = $_POST["pr_cost"];
		$pr_price = $_POST["pr_price"];
		$pr_stock = $_POST["pr_stock"];
		$pr_free_delivery = $_POST["pr_free_delivery"];

		if ( !$pr_sale_yn ) {
			$pr_sale_yn = "N";
		}

		if ( !$pr_cost ) {
			$pr_cost = 0;
		}
		if ( !$pr_price ) {
			$pr_price = 0;
		}
		if ( !$pr_stock ) {
			$pr_stock = 0;
		}
		if ( !$pr_free_delivery ) {
			$pr_free_delivery = 0;
		}


	// 수정시
	if ( getLen($pr_idx) > 0 ) {

		#### 소트값 처리 변경 처리 : 카테고리별 > 전체 소트
		// 카테고리 변경시 sort 값 설정
//		if ( $pc_num != $pc_num_old) {
//
//			// 기존 카테고리 sort 하나씩 낮추기
//			$SQL = "
//				update tbl_product
//				set
//					pr_sort = pr_sort - 1
//				where
//					pc_num = '".$pc_num_old."'
//					and pr_sort > '".$pr_sort."'
//			";
//			$dbcon -> query($SQL);
//
//			// 신규 카테고리의 최대값+1 가져오기
//			$SQL = "
//				select MAX(pr_sort) + 1
//				from tbl_product
//				where pc_num = '".$pc_num."'
//			";
//			$pr_sort = $dbcon -> getCount($SQL);
//		}


		isnull($pr_sort);
		isnull($pr_sort_old);

//		33 : 3105C
//		34 : S7075C


//		echo "pr_sort : ". $pr_sort."<BR>";
//		echo "pr_sort_old : ". $pr_sort_old."<BR>";
		if ( $pr_sort != $pr_sort_old) {
//			echo "정렬값 교체<BR>";

			if ( $pr_sort > $pr_sort_old ) {
				//echo "111111";
				$SQL = "
					update tbl_product
					set
						pr_sort = pr_sort - 1
					where
						pr_sort <= '".$pr_sort."'
						and pr_sort > '".$pr_sort_old."'
				";
				$dbcon -> query($SQL);
			}
			if ( $pr_sort < $pr_sort_old ) {
				//echo "22222";
				$SQL = "
					update tbl_product
					set
						pr_sort = pr_sort + 1
					where
						pr_sort >= '".$pr_sort."'
						and pr_sort < '".$pr_sort_old."'
				";
				$dbcon -> query($SQL);
			}


			$SQL = "
				update tbl_product
				set
					pr_sort = '".$pr_sort."'
				where
					pr_idx = '".$pr_idx."'
			";
			$dbcon -> query($SQL);

		}
//		echo "pr_sort : ". $pr_sort."<BR>";
//		echo "pr_sort_old : ". $pr_sort_old."<BR>";
//
//		exit;


//		echo $pr_img."<BR>";
//		echo $pr_img_thum."<BR>";
//		echo $pr_file."<BR>";
//		exit;


		$SQL = "
			update tbl_product
			set
				pc_num = '".$pc_num."'
				, pr_name = '".$pr_name."'
				, pr_sale_yn = '".$pr_sale_yn."'
				, pr_cost = '".$pr_cost."'
				, pr_price = '".$pr_price."'
				, pr_stock = '".$pr_stock."'
				, pr_free_delivery = '".$pr_free_delivery."'
				, pr_option = '".$pr_option."'
				, pr_content_thum = '".$pr_content_thum."'
				, pr_content = '".$pr_content."'
				, pr_content2 = '".$pr_content2."'
				, pr_content3 = '".$pr_content3."'
				, pr_content4 = '".$pr_content4."'

				, pr_link = '".$pr_link."'
			where
				pr_idx = '".$pr_idx."'
		";
//				, pr_option_value = '".$pr_option_value."'
//				, pr_img_thum = '".$pr_img_thum."'
//				, pr_img = '".$pr_img."'
//				, pr_file = '".$pr_file."'
//				pc_name = '".$pc_name."'
//				, pc_num_location = '".$pc_num_location."'
//				, pr_sort = '".$pr_sort."'

		$dbcon -> query($SQL);


//		echo "<pre>".$SQL."</pre>"."<BR>";
//		exit;

		$ret_txt = "수정되었습니다.";
		$ret_url = "product_write.php?pr_idx=".$pr_idx."&".$parameter;
	}
	// 신규 입력
	else {


		$SQL = "
			select MAX(pr_sort) + 1
			from tbl_product
		";
		$pr_sort = $dbcon -> getCount($SQL);
		if ( !$pr_sort ) $pr_sort = 1;

		$SQL = "
			INSERT INTO tbl_product
			set
				pc_num = '".$pc_num."'
				, pr_sort = '".$pr_sort."'
				, pr_name = '".$pr_name."'
				, pr_sale_yn = '".$pr_sale_yn."'
				, pr_cost = '".$pr_cost."'
				, pr_price = '".$pr_price."'
				, pr_stock = '".$pr_stock."'
				, pr_free_delivery = '".$pr_free_delivery."'
				, pr_option = '".$pr_option."'
				, pr_content_thum = '".$pr_content_thum."'
				, pr_content = '".$pr_content."'
				, pr_content2 = '".$pr_content2."'
				, pr_content3 = '".$pr_content3."'
				, pr_content4 = '".$pr_content4."'
				, pr_regdate = CURRENT_TIMESTAMP

				, pr_link = '".$pr_link."'
		";
//				, pr_option_value = '".$pr_option_value."'
//				, pr_img_thum = ''
//				, pr_img = ''
//				, pr_file = ''
		//echo $SQL."<BR>";
		$dbcon -> query($SQL);

		$pr_idx = $dbcon -> get_insert_id();



//		echo $pr_img."<BR>";
//		echo $pr_img_thum."<BR>";
//		echo $pr_file."<BR>";
//		exit;


//		$SQL = "
//			select MAX(pr_sort) + 1
//			from tbl_product
//		";
//		$pr_sort = $dbcon -> getCount($SQL);
//		if ( !$pr_sort ) $pr_sort = 1;
//
//		$SQL = "
//			INSERT INTO tbl_product
//			set
//				pc_num = '".$pc_num."'
//				, pr_sort = '".$pr_sort."'
//				, pr_name = '".$pr_name."'
//				, pr_option = '".$pr_option."'
//				, pr_option_value = '".$pr_option_value."'
//				, pr_content_thum = '".$pr_content_thum."'
//				, pr_content = '".$pr_content."'
//				, pr_content2 = '".$pr_content2."'
//				, pr_content3 = '".$pr_content3."'
//				, pr_img_thum = '".$pr_img_thum."'
//				, pr_img = '".$pr_img."'
//				, pr_file = '".$pr_file."'
//				, pr_regdate = CURRENT_TIMESTAMP
//		";
//		//echo $SQL."<BR>";
//		$dbcon -> query($SQL);

		$ret_txt = "처리되었습니다.";
		$ret_url = "product_write.php?pr_idx=".$pr_idx."&".$parameter;

	}


		#### pr_img File 처리
		if ( count($pr_img_del) > 0 ) {
			for ($i = 0; $i < count($pr_img_del); $i++ ) {
				if ( getLen($pr_img_del[$i]) > 0 ) {
					FileDBDelete($pr_img_del[$i], $path_product_data,"thumb_305,thumb_60,thumb_500");
				}
			}
		}


		if ( getLen($pr_img) > 0 ) {
			try {
				$upload = new upload($path_product_data);
				$upload->define($_FILES['pr_img'], "pr_img");
				$upload->makeThumbnailed(305, 305, "thumb_305");
				$upload->makeThumbnailed(60, 60, "thumb_60");
				$upload->makeThumbnailed(500, 500, "thumb_500");
				$upload->checkImageOnly();
				$upload->uploadedFiles();
				FileDBInsert("pr_img", $pr_idx);
			}
			catch(Exception $e) {
				// 에러처리 구문
				exit($e->getMessage());
			}
		}


		#### pr_img_thum File 처리
		if ( count($pr_img_thum_del) > 0 ) {
			for ($i = 0; $i < count($pr_img_thum_del); $i++ ) {
				if ( getLen($pr_img_thum_del[$i]) > 0 ) {
					FileDBDelete($pr_img_thum_del[$i], $path_product_data, "thumb_160");
				}
			}
		}


		if ( getLen($pr_img_thum) > 0 ) {
			try {
				$upload = new upload($path_product_data);
				$upload->define($_FILES['pr_img_thum'], "pr_img_thum");
				$upload->makeThumbnailed(160, 160, "thumb_160");
//				$upload->makeThumbnailed(60, 60, "thumb_60");
//				$upload->makeThumbnailed(500, 500, "thumb_500");
				$upload->checkImageOnly();
				$upload->uploadedFiles();
				FileDBInsert("pr_img_thum", $pr_idx);
			}
			catch(Exception $e) {
				// 에러처리 구문
				exit($e->getMessage());
			}
		}


		#### pr_file File 처리
		if ( count($pr_file_del) > 0 ) {
			for ($i = 0; $i < count($pr_file_del); $i++ ) {
				if ( getLen($pr_file_del[$i]) > 0 ) {
					FileDBDelete($pr_file_del[$i], $path_product_data);
				}
			}
		}


		if ( getLen($pr_file) > 0 ) {
			try {
				$upload = new upload($path_product_data);
				$upload->define($_FILES['pr_file'], "pr_file");
				//$upload->makeThumbnailed(500, 500, "thumb_500");
				//$upload->checkImageOnly();
				$upload->uploadedFiles();
				FileDBInsert("pr_file", $pr_idx);
			}
			catch(Exception $e) {
				// 에러처리 구문
				exit($e->getMessage());
			}
		}

	$dbcon -> dbcon_close();
	alert_page($ret_txt, $ret_url);

?>


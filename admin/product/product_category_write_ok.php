<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?

	$pc_idx						= REQSTR($pc_idx, "");
	$pc_num						= REQSTR($pc_num, "");
	$pc_name					= REQSTR($pc_name, "");
	$pc_use						= REQSTR($pc_use, "");
	$pc_regdate				= REQSTR($pc_regdate, "");
	//$pc_option					= REQSTR($pc_option, "");
	$pc_list_cols				= REQSTR($pc_list_cols, "");
	$pc_list_rows				= REQSTR($pc_list_rows, "");
	$pc_thum_width			= REQSTR($pc_thum_width, "");
	$pc_thum_height			= REQSTR($pc_thum_height, "");
	$pc_img_width				= REQSTR($pc_img_width, "");
	$pc_img_height			= REQSTR($pc_img_height, "");
	$pc_menu_img				= REQSTR($pc_menu_img, "");
	$pc_menu_img_ov		= REQSTR($pc_menu_img_ov, "");
	$pc_skin						= REQSTR($pc_skin, "");
	$pc_top_include			= REQSTR($pc_top_include, "");
	$pc_top_html				= REQSTR($pc_top_html, "");
	$pc_bottom_include		= REQSTR($pc_bottom_include, "");
	$pc_bottom_html			= REQSTR($pc_bottom_html, "");
	$pc_auth_list				= REQSTR($pc_auth_list, "");
	$pc_auth_view			= REQSTR($pc_auth_view, "");


//	echo "pc_option_check : ".$_POST["pc_option_check"]."<BR>";
//	echo "pc_option_name : ".$_POST["pc_option_name"]."<BR>";
//	echo "pc_option_text : ".$_POST["pc_option_text"]."<BR>";
//	echo count($_POST["pc_option_check"])."<BR>";
//	echo count($_POST["pc_option_name"])."<BR>";
//	echo count($_POST["pc_option_text"])."<BR>";

//	$pc_option = setCategoryOption($pc_option);
//	echo $pc_option."<BR>";

	$pc_option = "";
	for ( $i = 0 ; $i < count($_POST["pc_option_name"]); $i++) {
		if ( $_POST["pc_option_name"][$i] ) {
			if ($i > 0) {
				$pc_option .= "|";
			}
			$pc_option .= $_POST["pc_option_check"][$i]."@".$_POST["pc_option_name"][$i]."^".$_POST["pc_option_text"][$i];
		}
	}
	$pc_option = str_replace(" ", "", $pc_option);
//	echo "pc_option : ".$pc_option."<BR>";
//
//	exit;


	$pre_pc_num						= REQSTR($pre_pc_num, "");
	//$pre_pc_num = "0201";
	$search_pc_num = REQSTR($search_pc_num, "");

//	exit;
	$parameter = "search_pc_num=".$search_pc_num;

//	echo "pc_idx : ".$pc_idx."<BR>";
//	echo "pc_num : ".$pc_num."<BR>";
//	echo "pc_name : ".$pc_name."<BR>";
//	echo "pc_sort : ".$pc_sort."<BR>";
//	echo "pc_use : ".$pc_use."<BR>";
//	echo "pc_regdate : ".$pc_regdate."<BR>";
//	echo "pc_option : ".$pc_option."<BR>";
//	echo "pc_list_cols : ".$pc_list_cols."<BR>";
//	echo "pc_list_rows : ".$pc_list_rows."<BR>";
//	echo "pc_thum_width : ".$pc_thum_width."<BR>";
//	echo "pc_thum_height : ".$pc_thum_height."<BR>";
//	echo "pc_img_width : ".$pc_img_width."<BR>";
//	echo "pc_img_height : ".$pc_img_height."<BR>";
//	echo "pc_menu_img : ".$pc_menu_img."<BR>";
//	echo "pc_menu_img_ov : ".$pc_menu_img_ov."<BR>";
//	echo "pc_skin : ".$pc_skin."<BR>";
//	echo "pc_top_include : ".$pc_top_include."<BR>";
//	echo "pc_top_html : ".$pc_top_html."<BR>";
//	echo "pc_bottom_include : ".$pc_bottom_include."<BR>";
//	echo "pc_bottom_html : ".$pc_bottom_html."<BR>";
//	echo "pc_auth_list : ".$pc_auth_list."<BR>";
//	echo "pc_auth_view : ".$pc_auth_view."<BR>";



	#### New Category Add
	if ( getLen($pc_idx) == 0 ) {

		$ArrCategoryInfo = setNewCategoryInfo($pre_pc_num);	// 신규 카테고리 번호 생성
		$pc_num = $ArrCategoryInfo[0];
		$pc_sort = $ArrCategoryInfo[1];
//		echo "pre_pc_num : ".$pre_pc_num."<BR>";
//		echo "pc_num : ".$pc_num."<BR>";
//		echo "pc_sort : ".$pc_sort."<BR>";

		$SQL = "
			insert into tbl_product_category (
				pc_num, pc_name, pc_sort, pc_use, pc_option, pc_list_cols, pc_list_rows, pc_thum_width, pc_thum_height, pc_img_width, pc_img_height, pc_menu_img, pc_menu_img_ov, pc_skin, pc_top_include, pc_top_html, pc_bottom_include, pc_bottom_html, pc_auth_list, pc_auth_view
			) values (
				'".$pc_num."', '".$pc_name."', '".$pc_sort."', '".$pc_use."', '".$pc_option."', '".$pc_list_cols."', '".$pc_list_rows."', '".$pc_thum_width."', '".$pc_thum_height."', '".$pc_img_width."', '".$pc_img_height."', '".$pc_menu_img."', '".$pc_menu_img_ov."', '".$pc_skin."', '".$pc_top_include."', '".$pc_top_html."', '".$pc_bottom_include."', '".$pc_bottom_html."', '".$pc_auth_list."', '".$pc_auth_view."'
			)
		";
		$dbcon -> query($SQL);
//		echo $SQL."<BR>";
//		exit;
	}

	#### Category Modify
	else {
//		#### pc_sort 값 가져오기
//		$SQL = "
//			select pc_sort
//			from tbl_product_category
//			where
//				1=1
//				and pc_num <> '".$pre_cat_num."'
//				and length(pc_num) = ".(strlen($pre_cat_num))."
//				and SUBSTRING(pc_num, 1, ".strlen($pre_cat_num)." ) = '".$pre_cat_num."'
//			order by pc_sort desc
//			limit 0, 1
//		";
//		if ($row = $dbcon -> getCount($SQL))
//			$pc_sort = $row[pc_sort]+1;
//		else
//			$pc_sort = 1;
//		echo $SQL."<BR>";
//		echo "pc_sort : ". $pc_sort."<BR>";
//		exit;

		$SQL = "
			update tbl_product_category
			set
				pc_num = '".$pc_num."'
				, pc_name = '".$pc_name."'

				, pc_use = '".$pc_use."'
				, pc_regdate = '".$pc_regdate."'
				, pc_option = '".$pc_option."'
				, pc_list_cols = '".$pc_list_cols."'
				, pc_list_rows = '".$pc_list_rows."'
				, pc_thum_width = '".$pc_thum_width."'
				, pc_thum_height = '".$pc_thum_height."'
				, pc_img_width = '".$pc_img_width."'
				, pc_img_height = '".$pc_img_height."'
				, pc_menu_img = '".$pc_menu_img."'
				, pc_menu_img_ov = '".$pc_menu_img_ov."'
				, pc_skin = '".$pc_skin."'
				, pc_top_include = '".$pc_top_include."'
				, pc_top_html = '".$pc_top_html."'
				, pc_bottom_include = '".$pc_bottom_include."'
				, pc_bottom_html = '".$pc_bottom_html."'
				, pc_auth_list = '".$pc_auth_list."'
				, pc_auth_view = '".$pc_auth_view."'
			where
				pc_idx = '".$pc_idx."'
		";

		//, pc_sort = '".$pc_sort."'
		$dbcon -> query($SQL);

//		echo $SQL."<BR>";
//		exit;

		$dbcon -> dbcon_close();
		alert_page("등록되었습니다.","product_category_write.php?pc_idx=".$pc_idx."&".$parameter);
		exit;
	}

	//SELECT * FROM `tbl_product_category` WHERE 1

	$dbcon -> dbcon_close();
	alert_page("등록되었습니다.","product_category.php?".$parameter);
?>

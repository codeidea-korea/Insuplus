<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
$bc_id = REQSTR($bc_id, "");
isnull($bc_id);


	$upload_path		= $path_data."board/".$bc_id;					// 업로드 폴더

	// 첨부파일 내용 모두 삭제
	if( is_dir($upload_path) ) {

		// 폴더 내용 가져와서 삭제
		$dir_obj=opendir($upload_path);
		while( ( $file_str = readdir($dir_obj) ) !== false ){
			if( $file_str != "." && $file_str != ".." ) {
				//echo "$file_str<BR>";
				unlink($upload_path."/".$file_str);
			}
		}

		//폴더 삭제
		closedir($dir_obj);
		rmdir($upload_path);
	}
	$dbcon -> setDebug(1);

	// DB 테이블 삭제
	$tbl_drop_tbl_board = str_replace("[boardname]", $bc_id, $tbl_drop_tbl_board);
	$result = $dbcon -> query($tbl_drop_tbl_board);

	$tbl_config_board_list_delete = str_replace("[boardname]", $bc_id, $tbl_config_board_list_delete);
	//echo $tbl_config_board_list_delete."<BR>";
	$result = $dbcon -> query($tbl_config_board_list_delete);

	$dbcon -> dbcon_close();


	alert_page("처리되었습니다.", "bbs_list.php".$go_page);

?>
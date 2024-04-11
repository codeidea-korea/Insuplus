<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	//단체가입 내역 조회
	$SQL = "select listfile from tbl_order_group_join_list where group_join_id = '".$_GET["group_join_id"]."'";
	$RS = $dbcon -> query($SQL);
	if (!$RS){
		echo "flase";
		exit;
	}
	$filepath = $dbcon -> fetch_array($RS);
	$dbcon -> dbcon_close();
	$now = date("Ymdhi", time());

	$target_Dir = $filepath[0] ;
	$filename = "가입자_정보_".$now.".xlsx";
	$filesize = filesize($target_Dir); //파일사이즈 구하기
	
	$file = $target_Dir;
		
	if (is_file($file)) {
		//헤더 설정
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=$filename"); //다운로드 되는 파일의 이름을 지정
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: $filesize"); //파일 사이즈 명시

		ob_clean();
		flush(); //버퍼 비우기
		readfile($file); //파일 읽어서 출력하기

	}
	else {
		echo "<script>alert('파일을 찾을 수 없습니다.');location.href='".$HTTP_REFERER."';</script>";
	}
?>
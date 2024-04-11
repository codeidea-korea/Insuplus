<?
	if( $nBDType == "") $nBDType = 1;

	// 환경설정 변수
	$nPageSize = 10;									// 리스트수
	$sBDFilesDir = "/upload/QnA/";		// 파일업로드 폴더
	$sImgDir = "";										// 이미지 경로
	$nNewDay = 1;											// 새글처리 일자
	$nCutLen = 40;										// 긴글짜르는 길이

	// 게시판 경로
	$sListPage				= "List.php?nBDType=$nBDType";
	$sViewPage			= "View.php?nBDType=$nBDType";
	$sWritePage			= "Write.php?nBDType=$nBDType";
	$sProcessPage		= "Process.php?nBDType=$nBDType";

	// 컬러
	$cTitle		= "";		// Title 색
	$cList		= "";		// 리스트 찐한색
	$cPage		= "";		// 페이지수 글자색
	$cSearch	= "";		// 찾기 바탕색
	$cLine		= "";		// 라인색
	$cLine2		= "";		// 라인색

	$cSection		= "";		// 뷰 섹션 배경
	$cValue			= "";		// 뷰 값 배경
	$cContents	= "";		// 내용 배경
	$cButton= "";				// 뷰 버튼부분 배경

	// 게시판에 따라 디자인 다르게 진행
//	if( $nBDType == 1 || $nBDType == 11) {
		$cTitle		= "#4072B9";
		$cList		= "#E9F0FA";
		$cPage		= "#4072B9";
		$cSearch	= "#E9F0FA";
		$cLine		= "#BDCEEA";

		$cSection		= "#CADAF3";		// 뷰 섹션 배경
		$cValue			= "#EAF1FB";		// 뷰 값 배경
		$cContents	= "#FFFFFF";		// 내용 배경
		$cLine2			= "#B0C7E9";
		$cButton		= "#E9F0FA";

		$sImgDir = "/bubble/images";
//	}

?>

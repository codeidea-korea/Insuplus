<?php
//**************************************************************************************************************
//NICE평가정보 Copyright(c) KOREA INFOMATION SERVICE INC. ALL RIGHTS RESERVED

//서비스명 :  체크플러스 - 안심본인인증 서비스
//페이지명 :  체크플러스 - 메인 호출 페이지

//보안을 위해 제공해드리는 샘플페이지는 서비스 적용 후 서버에서 삭제해 주시기 바랍니다.
//방화벽 정보 : IP 121.131.196.215 , Port 80, 443     
//**************************************************************************************************************

session_start();

$sitecode = "CG848";            // NICE로부터 부여받은 사이트 코드
$sitepasswd = "uXIJHj2TqQiz";            // NICE로부터 부여받은 사이트 패스워드

// Linux = /절대경로/ , Window = D:\\절대경로\\ , D:\절대경로\
$cb_encode_path = "/app/projects/insuplus/html/_nice/CPClient_linux_x64";
/*
	┌ cb_encode_path 변수에 대한 설명  ──────────────────────────────────
		모듈 경로설정은, '/절대경로/모듈명' 으로 정의해 주셔야 합니다.
		
		+ FTP 로 모듈 업로드시 전송형태를 'binary' 로 지정해 주시고, 권한은 755 로 설정해 주세요. 
		
		+ 절대경로 확인방법
		  1. Telnet 또는 SSH 접속 후, cd 명령어를 이용하여 모듈이 존재하는 곳까지 이동합니다.
		  2. pwd 명령어을 이용하면 절대경로를 확인하실 수 있습니다.
		  3. 확인된 절대경로에 '/모듈명'을 추가로 정의해 주세요.
	└────────────────────────────────────────────────────────────────────
	*/

$authtype = "";              // 없으면 기본 선택화면, M(휴대폰), X(인증서공통), U(공동인증서), F(금융인증서), S(PASS인증서), C(신용카드)

$customize     = "";        //없으면 기본 웹페이지 / Mobile : 모바일페이지 (default값은 빈값, 환경에 맞는 화면 제공)

$reqseq = "REQ_0123456789";     // 요청 번호, 이는 성공/실패후에 같은 값으로 되돌려주게 되므로
// 업체에서 적절하게 변경하여 쓰거나, 아래와 같이 생성한다.

// 실행방법은 백틱(`) 외에도, 'exec(), system(), shell_exec()' 등등 귀사 정책에 맞게 처리하시기 바랍니다.
// 위의 실행함수를 통해 아무런 값도 출력이 안될 경우 쉘 스크립트 오류출력(2>&1)을 통해 오류 확인 부탁드립니다.
$reqseq = `$cb_encode_path SEQ $sitecode`;

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https" : "http";
$domain   = $_SERVER['HTTP_HOST'];
// $port     = $_SERVER['SERVER_PORT'] ? ":" . $_SERVER['SERVER_PORT'] : "";
$url      = $protocol . "://" . $domain; //. $port;


// ========== 세션 ID와 검증 토큰 생성 ==========
$session_id = session_id();
$security_key = "test" . $sitecode; // 보안 키 (변경 필요)
$timestamp = time();

// 검증 토큰 생성 (세션 ID + REQ_SEQ + 타임스탬프 + 보안키)
$verification_token = hash_hmac('sha256', $session_id . $reqseq . $timestamp, $security_key);

// 세션에 REQ_SEQ와 토큰 저장
$_SESSION["REQ_SEQ"] = $reqseq;
$_SESSION["VERIFICATION_TOKEN"] = $verification_token;
$_SESSION["TOKEN_TIMESTAMP"] = $timestamp;

// ========== 세션 데이터를 파일로 백업 (Safari ITP 대응) ==========
// Safari는 iframe 내부 쿠키를 차단하므로 파일 기반 세션 사용
$session_backup_dir = sys_get_temp_dir() . '/nice_sessions/';
if (!is_dir($session_backup_dir)) {
    mkdir($session_backup_dir, 0755, true);
}

$session_backup_file = $session_backup_dir . 'sess_' . md5($session_id . $verification_token) . '.dat';
$session_backup_data = array(
    'session_id' => $session_id,
    'req_seq' => $reqseq,
    'token' => $verification_token,
    'timestamp' => $timestamp,
    'ip' => $_SERVER['REMOTE_ADDR'],
    'user_agent' => $_SERVER['HTTP_USER_AGENT']
);

file_put_contents($session_backup_file, json_encode($session_backup_data));

// ========== returnurl에 세션 정보 포함 ==========
$session_params = http_build_query(array(
    'sid' => $session_id,
    'token' => $verification_token,
    'ts' => $timestamp
));


// CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.
// 리턴url은 인증 전 인증페이지를 호출하기 전 url과 동일해야 합니다. ex) 인증 전 url : http://www.~ 리턴 url : http://www.~
// $returnurl = $url . "/html/_nice/checkplus_success.php";    // 성공시 이동될 URL
$returnurl = $url."/html/insurance/renewal_step01.php?nice_return=1";
$errorurl = $url . "/html/insurance/renewal_step01.php?nice_return=1";        // 실패시 이동될 URL

// $returnurl = "";	// 성공시 이동될 URL
// $errorurl = "";		// 실패시 이동될 URL
// reqseq값은 성공페이지로 갈 경우 검증을 위하여 세션에 담아둔다.

$_SESSION["REQ_SEQ"] = $reqseq;

// 입력될 plain 데이타를 만든다.
$plaindata = "7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
    "8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
    "9:AUTH_TYPE" . strlen($authtype) . ":" . $authtype .
    "7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
    "7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
    "9:CUSTOMIZE" . strlen($customize) . ":" . $customize;

$enc_data = `$cb_encode_path ENC $sitecode $sitepasswd $plaindata`;
$returnMsg = "";

if ($enc_data == -1) {
    $returnMsg = "암/복호화 시스템 오류입니다.";
    $enc_data = "";
} else if ($enc_data == -2) {
    $returnMsg = "암호화 처리 오류입니다.";
    $enc_data = "";
} else if ($enc_data == -3) {
    $returnMsg = "암호화 데이터 오류 입니다.";
    $enc_data = "";
} else if ($enc_data == -9) {
    $returnMsg = "입력값 오류 입니다.";
    $enc_data = "";
}
?>


<!-- <html>
<head>
	<title>NICE평가정보 - CheckPlus 안심본인인증 테스트</title>
	
	<script language='javascript'>
	  // 인증 결과를 받는 함수
      function receiveAuthResult(result) {
        // 여기서 인증 결과를 처리합니다
        console.log('인증 결과:', result);
        
        if(result.success) {
            // 성공 처리
            alert('인증이 완료되었습니다.');
            window.opener.authResult(result);
        } else {
            // 실패 처리
            alert('인증에 실패했습니다: ' + result.message);
        }
    }
	</script>
</head>
<body>
	<?= $returnMsg ?><br><br>
	업체정보 암호화 데이타 : [<?= $enc_data ?>]<br><br>

	<form name="form_chk" method="post" >
		<input type="hidden" name="m" value="checkplusService">			
		<input type="hidden" name="EncodeData" value="<?= $enc_data ?>">		
	    
		<a href="javascript:fnPopup();"> CheckPlus 안심본인인증 Click</a>
	</form>
</body>
</html> -->
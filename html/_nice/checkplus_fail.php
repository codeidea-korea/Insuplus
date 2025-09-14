<?php
    session_start();
    
    // 실패 시 에러 메시지 처리
    $error_msg = "인증에 실패했습니다.";
    
    if(isset($_POST['EncodeData'])) {
        $sitecode = "CG848";
        $sitepasswd = "uXIJHj2TqQiz";
        $cb_encode_path = "/app/projects/insuplus/html/_nice/CPClient_linux_x64"; 
          
        $enc_data = $_POST["EncodeData"];
        $plain_data = `$cb_encode_path DEC $sitecode $sitepasswd $enc_data`;
        
        if( $plain_data == -1 ) {
            $error_msg = "암/복호화 시스템 오류입니다."; 
        } else if( $plain_data == -4 ) {
            $error_msg = "복호화 처리 오류입니다.";
        } else if( $plain_data == -5 ) {
            $error_msg = "HASH값 불일치 입니다.";
        } else if( $plain_data == -6 ) {
            $error_msg = "복호화 데이터 오류입니다.";
        } else if( $plain_data == -9 ) {
            $error_msg = "입력값 오류입니다.";
        } else if( $plain_data == -12 ) {
            $error_msg = "사이트 패스워드 오류입니다.";
        } else {
            // 복호화는 성공했지만 인증 실패
            parse_str(str_replace('&', '&', $plain_data), $result_array);
            if(isset($result_array['ERR_MSG'])) {
                $error_msg = $result_array['ERR_MSG'];
            }
        }
    }
    
    $auth_result = array(
        'success' => false,
        'data' => null,
        'message' => $error_msg
    );
?>

<html>
<head>
    <title>인증 실패</title>
    <script language='javascript'>
    // 부모 창에 실패 결과 전달 후 팝업 닫기
    function sendResultAndClose() {
        var result = <?= json_encode($auth_result, JSON_UNESCAPED_UNICODE) ?>;
        
        // 부모 창의 함수 호출
        if(window.opener && window.opener.receiveAuthResult) {
            window.opener.receiveAuthResult(result);
        }
        
        // 팝업 창 닫기
        window.close();
    }
    
    // 페이지 로드 시 자동 실행
    window.onload = function() {
        sendResultAndClose();
    };
    </script>
</head>
<body>
    <p>인증 실패 처리 중...</p>
</body>
</html>
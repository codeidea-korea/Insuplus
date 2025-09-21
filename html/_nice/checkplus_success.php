<?php
    //**************************************************************************************************************
    //NICE평가정보 Copyright(c) KOREA INFOMATION SERVICE INC. ALL RIGHTS RESERVED
    
    //서비스명 :  체크플러스 - 안심본인인증 서비스
    //페이지명 :  체크플러스 - 결과 페이지 (팝업 자동 닫기 버전)
    //**************************************************************************************************************
    
    session_start();
	
    $sitecode = "CG848";				// NICE로부터 부여받은 사이트 코드
    $sitepasswd = "uXIJHj2TqQiz";		// NICE로부터 부여받은 사이트 패스워드
    
    // Linux = /절대경로/ , Window = D:\\절대경로\\ , D:\절대경로\
    $cb_encode_path = "/app/projects/insuplus/html/_nice/CPClient_linux_x64"; 
		 
    $enc_data = $_REQUEST["EncodeData"];		// 암호화된 결과 데이타  
    $returnMsg = "";
    $auth_result = array();

    //////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
    if(preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {
        $returnMsg = "입력 값 확인이 필요합니다 : ".$match[0];
        $auth_result = array('success' => false, 'data' => null, 'message' => $returnMsg);
    } else if(base64_encode(base64_decode($enc_data))!=$enc_data) {
        $returnMsg = "입력 값 확인이 필요합니다";
        $auth_result = array('success' => false, 'data' => null, 'message' => $returnMsg);
    } else if ($enc_data != "") {
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        $plaindata = `$cb_encode_path DEC $sitecode $sitepasswd $enc_data`;		// 암호화된 결과 데이터의 복호화

        if ($plaindata == -1){
            $returnMsg = "암/복호화 시스템 오류";
        }else if ($plaindata == -4){
            $returnMsg = "복호화 처리 오류";
        }else if ($plaindata == -5){
            $returnMsg = "HASH값 불일치 - 복호화 데이터는 리턴됨";
        }else if ($plaindata == -6){
            $returnMsg = "복호화 데이터 오류";
        }else if ($plaindata == -9){
            $returnMsg = "입력값 오류";
        }else if ($plaindata == -12){
            $returnMsg = "사이트 비밀번호 오류";
        }else{
            // 복호화가 정상적일 경우 데이터를 파싱합니다.
            $ciphertime = `$cb_encode_path CTS $sitecode $sitepasswd $enc_data`;	// 암호화된 결과 데이터 검증 (복호화한 시간획득)
        
            // 디버그: plaindata 내용 확인
            error_log("NICE plaindata: " . $plaindata);
            
            $requestnumber = GetValue($plaindata, "REQ_SEQ");
            $responsenumber = GetValue($plaindata, "RES_SEQ");
            $authtype = GetValue($plaindata, "AUTH_TYPE");
            $name = GetValue($plaindata, "NAME");
            $utf8_name = GetValue($plaindata, "UTF8_NAME"); //charset utf8 사용시
            $birthdate = GetValue($plaindata, "BIRTHDATE");
            $gender = GetValue($plaindata, "GENDER");
            $nationalinfo = GetValue($plaindata, "NATIONALINFO");	//내/외국인정보(사용자 매뉴얼 참조)
            $dupinfo = GetValue($plaindata, "DI");
            $conninfo = GetValue($plaindata, "CI");
            $mobileno = GetValue($plaindata, "MOBILE_NO");
            $mobileco = GetValue($plaindata, "MOBILE_CO");

            // 세션 검증
            if(isset($_SESSION["REQ_SEQ"]) && strcmp($_SESSION["REQ_SEQ"], $requestnumber) != 0 && strpos($requestnumber, $sitecode) != 0) {
                $returnMsg = "세션값이 다릅니다. 올바른 경로로 접근하시기 바랍니다. req :: " . $requestnumber . " ss :: " . $_SESSION["REQ_SEQ"];
                $auth_result = array('success' => false, 'data' => null, 'message' => $returnMsg);
            } else {
                // 인증 성공 - 결과 데이터 구성
                $auth_result = array(
                    'success' => true,
                    'data' => array(
                        'ciphertime' => $ciphertime,
                        'requestnumber' => $requestnumber,
                        'responsenumber' => $responsenumber,
                        'authtype' => $authtype,
                        'name' => $name,
                        'utf8_name' => $utf8_name,
                        'birthdate' => $birthdate,
                        'gender' => $gender,
                        'nationalinfo' => $nationalinfo,
                        'di' => $dupinfo,
                        'ci' => $conninfo,
                        'mobileno' => $mobileno,
                        'mobileco' => $mobileco
                    ),
                    'message' => '본인인증이 완료되었습니다.'
                );
            }
        }
        
        // 에러가 있는 경우 실패 결과 구성
        if($returnMsg != "" && !isset($auth_result['success'])) {
            $auth_result = array('success' => false, 'data' => null, 'message' => $returnMsg);
        }
    } else {
        $auth_result = array('success' => false, 'data' => null, 'message' => '암호화 데이터가 없습니다.');
    }

    // JSON 인코딩 전에 데이터 안전성 확보
    function sanitizeForJson($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = sanitizeForJson($value);
            }
        } elseif (is_string($data)) {
            // UTF-8이 아닌 문자 제거 및 제어 문자 제거
            $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');
            $data = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $data);
        }
        return $data;
    }

    // auth_result 데이터 정리
    $auth_result = sanitizeForJson($auth_result);
    
    // JSON 인코딩 테스트
    $json_test = json_encode($auth_result, JSON_UNESCAPED_UNICODE);
    if ($json_test === false) {
        // JSON 인코딩 실패 시 간단한 버전으로 대체
        $error_msg = json_last_error_msg();
        $auth_result = array(
            'success' => false, 
            'data' => null, 
            'message' => 'JSON 인코딩 실패: ' . $error_msg
        );
    }

    //********************************************************************************************
    //GetValue 함수 - substr 오류 방지를 위한 안전한 버전
    //********************************************************************************************
    function GetValue($str, $name) 
    {
        $pos = 0;
        $str_len = strlen($str);
        
        while($pos < $str_len) {
            // 길이 값의 끝 위치 찾기
            $colon_pos = strpos($str, ':', $pos);
            if($colon_pos === false) break;
            
            // 길이 값 추출 및 검증
            $len_str = substr($str, $pos, $colon_pos - $pos);
            $len = intval($len_str);
            if($len <= 0 || !is_numeric($len_str)) break;
            
            // 키 추출
            $key_start = $colon_pos + 1;
            if($key_start + $len > $str_len) break;
            $key = substr($str, $key_start, $len);
            
            // 다음 위치로 이동
            $pos = $key_start + $len;
            
            if($key === $name) {
                // 원하는 키를 찾았으면 값의 길이 찾기
                if($pos >= $str_len) break;
                
                $colon_pos = strpos($str, ':', $pos);
                if($colon_pos === false) break;
                
                $val_len_str = substr($str, $pos, $colon_pos - $pos);
                $val_len = intval($val_len_str);
                if($val_len < 0 || !is_numeric($val_len_str)) break;
                
                // 값 추출
                $val_start = $colon_pos + 1;
                if($val_start + $val_len > $str_len) break;
                
                return substr($str, $val_start, $val_len);
            } else {
                // 다른 키면 해당 값을 스킵
                if($pos >= $str_len) break;
                
                $colon_pos = strpos($str, ':', $pos);
                if($colon_pos === false) break;
                
                $val_len_str = substr($str, $pos, $colon_pos - $pos);
                $val_len = intval($val_len_str);
                if($val_len < 0 || !is_numeric($val_len_str)) break;
                
                $pos = $colon_pos + 1 + $val_len;
            }
        }
        
        return "";
    }
?>

<html>
<head>
    <title>NICE평가정보 - CheckPlus 본인인증 완료</title>
    <script language='javascript'>
    // 부모 창에 결과 전달 후 팝업 닫기
    function sendResultAndClose() {
        var result;
        
        try {
            <?php 
            // JSON 생성 시 안전하게 처리
            $json_result = json_encode($auth_result, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_APOS);
            if ($json_result === false) {
                // JSON 인코딩 실패 시 기본 오류 객체
                echo 'result = {"success": false, "data": null, "message": "JSON 인코딩 오류"};';
            } else {
                echo 'result = ' . $json_result . ';';
            }
            ?>
        } catch(e) {
            console.log('JSON 파싱 오류:', e);
            result = {"success": false, "data": null, "message": "데이터 처리 오류"};
        }
        
        // console.log('인증 결과:', result);
        
        try {
            // 부모 창의 함수 호출
            if(window.opener && window.opener.receiveAuthResult) {
                window.opener.receiveAuthResult(result);
            } else {
                console.log('부모 창의 receiveAuthResult 함수를 찾을 수 없습니다.');
            }
        } catch(e) {
            console.log('부모 창 통신 오류:', e);
        }
        
        // 팝업 창 닫기
        setTimeout(function() {
            window.close();
        }, 1000);
    }
    
    // 페이지 로드 시 자동 실행
    window.onload = function() {
        sendResultAndClose();
    };
    </script>
</head>
<body>
    <center>
        <p><p><p><p>
        <?php if(isset($auth_result['success']) && $auth_result['success']) { ?>
            본인인증이 완료되었습니다.<br>
            잠시 후 창이 닫힙니다...
        <?php } else { ?>
            인증 처리 중 오류가 발생했습니다.<br>
            <?php if(isset($auth_result['message'])) { ?>
                오류: <?= htmlspecialchars($auth_result['message']) ?><br>
            <?php } ?>
            잠시 후 창이 닫힙니다...
        <?php } ?>
        
        <!-- 디버그 정보 (개발 시에만 사용) -->
        <div style="margin-top: 20px; font-size: 12px; color: #666; text-align: left; max-width: 800px;">
            <!-- <strong>디버그 정보:</strong><br> -->
            <?php 
            // echo "plaindata: " . (isset($plaindata) ? htmlspecialchars($plaindata) : 'undefined') . "<br><br>";
            // echo "returnMsg: " . (isset($returnMsg) ? htmlspecialchars($returnMsg) : 'undefined') . "<br><br>";
            // echo "auth_result 배열:<br>";
            // echo "<pre>" . print_r($auth_result, true) . "</pre>";
            // echo "JSON last error: " . json_last_error_msg() . "<br>";
            
            // // 각 데이터 필드 개별 체크
            // if(isset($auth_result['data']) && is_array($auth_result['data'])) {
            //     echo "<br>개별 필드 체크:<br>";
            //     foreach($auth_result['data'] as $key => $value) {
            //         $individual_json = json_encode($value);
            //         echo $key . ": " . ($individual_json !== false ? "OK" : "FAIL - " . json_last_error_msg()) . " - " . htmlspecialchars($value) . "<br>";
            //     }
            // }
            ?>
        </div>
    </center>
</body>
</html>
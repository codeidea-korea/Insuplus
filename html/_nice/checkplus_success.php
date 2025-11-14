<?php
    // 에러를 로그에만 기록 (화면 출력 방지)
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    
    session_start();
    
    $sitecode = "CG848";
    $sitepasswd = "uXIJHj2TqQiz";
    $cb_encode_path = "/app/projects/insuplus/html/_nice/CPClient_linux_x64"; 
         
    // 안전하게 EncodeData 가져오기
    $enc_data = isset($_REQUEST["EncodeData"]) ? $_REQUEST["EncodeData"] : "";
    $returnMsg = "";
    $auth_result = array('success' => false, 'data' => null, 'message' => '처리 중');

    //////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
    if(empty($enc_data)) {
        $auth_result = array('success' => false, 'data' => null, 'message' => '암호화 데이터가 없습니다.');
    } else if(preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {
        $returnMsg = "입력 값 확인이 필요합니다 : ".$match[0];
        $auth_result = array('success' => false, 'data' => null, 'message' => $returnMsg);
    } else if(base64_encode(base64_decode($enc_data))!=$enc_data) {
        $returnMsg = "입력 값 확인이 필요합니다";
        $auth_result = array('success' => false, 'data' => null, 'message' => $returnMsg);
    } else {
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        $plaindata = `$cb_encode_path DEC $sitecode $sitepasswd $enc_data`;

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
            $ciphertime = `$cb_encode_path CTS $sitecode $sitepasswd $enc_data`;
            
            $requestnumber = GetValue($plaindata, "REQ_SEQ");
            $responsenumber = GetValue($plaindata, "RES_SEQ");
            $authtype = GetValue($plaindata, "AUTH_TYPE");
            $name = GetValue($plaindata, "NAME");
            $utf8_name = GetValue($plaindata, "UTF8_NAME");
            $birthdate = GetValue($plaindata, "BIRTHDATE");
            $gender = GetValue($plaindata, "GENDER");
            $nationalinfo = GetValue($plaindata, "NATIONALINFO");
            $dupinfo = GetValue($plaindata, "DI");
            $conninfo = GetValue($plaindata, "CI");
            $mobileno = GetValue($plaindata, "MOBILE_NO");
            $mobileco = GetValue($plaindata, "MOBILE_CO");

            // 세션 검증
            if(isset($_SESSION["REQ_SEQ"]) && strcmp($_SESSION["REQ_SEQ"], $requestnumber) != 0 && strpos($requestnumber, $sitecode) != 0) {
                $returnMsg = "세션값이 다릅니다. 올바른 경로로 접근하시기 바랍니다.";
                $auth_result = array('success' => false, 'data' => null, 'message' => $returnMsg);
            } else {
                // 인증 성공
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
        
        // 에러가 있는 경우
        if($returnMsg != "" && !isset($auth_result['success'])) {
            $auth_result = array('success' => false, 'data' => null, 'message' => $returnMsg);
        }
    }

    // JSON 인코딩 전 데이터 정리
    function sanitizeForJson($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = sanitizeForJson($value);
            }
        } elseif (is_string($data)) {
            $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');
            $data = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $data);
        }
        return $data;
    }

    $auth_result = sanitizeForJson($auth_result);

    //********************************************************************************************
    //GetValue 함수
    //********************************************************************************************
    function GetValue($str, $name) 
    {
        $pos = 0;
        $str_len = strlen($str);
        
        while($pos < $str_len) {
            $colon_pos = strpos($str, ':', $pos);
            if($colon_pos === false) break;
            
            $len_str = substr($str, $pos, $colon_pos - $pos);
            $len = intval($len_str);
            if($len <= 0 || !is_numeric($len_str)) break;
            
            $key_start = $colon_pos + 1;
            if($key_start + $len > $str_len) break;
            $key = substr($str, $key_start, $len);
            
            $pos = $key_start + $len;
            
            if($key === $name) {
                if($pos >= $str_len) break;
                
                $colon_pos = strpos($str, ':', $pos);
                if($colon_pos === false) break;
                
                $val_len_str = substr($str, $pos, $colon_pos - $pos);
                $val_len = intval($val_len_str);
                if($val_len < 0 || !is_numeric($val_len_str)) break;
                
                $val_start = $colon_pos + 1;
                if($val_start + $val_len > $str_len) break;
                
                return substr($str, $val_start, $val_len);
            } else {
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
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NICE평가정보 - CheckPlus 본인인증 완료</title>
</head>
<body>
<center>
    <?php if(isset($auth_result['success']) && $auth_result['success']) { ?>
        <div id="msg-box">
        본인인증이 완료되었습니다.<br>
        <span id="closeMsg">잠시 후 창이 닫힙니다...</span>
        </div>
    <?php } else { ?>
        <div id="msg-box">
            인증 처리 중 오류가 발생했습니다.<br>
            <?php if(isset($auth_result['message'])) { ?>
                오류: <?= htmlspecialchars($auth_result['message'], ENT_QUOTES, 'UTF-8') ?><br>
            <?php } ?>
            잠시 후 창이 닫힙니다...
        </div>
    <?php } ?>
    <button id="manualCloseBtn" style="display:none; margin-top:15px; padding:8px 20px; border:none; border-radius:6px; background:#2B7FFF; color:#fff; font-size:14px; cursor:pointer;">
        창 닫기
    </button>
</center>

<script>
(function() {
    'use strict';
    
    function sendResultAndClose(isIOS) {
        var result;
        
        // 안전한 JSON 파싱
        try {
            <?php 
            $json_result = json_encode($auth_result, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
            if ($json_result === false) {
                echo 'result = {"success":false,"data":null,"message":"데이터 처리 오류"};';
            } else {
                echo 'result = ' . $json_result . ';';
            }
            ?>
        } catch (e) {
            result = {success: false, data: null, message: '데이터 오류'};
        }

        // postMessage 전송
        try {
            if (window.opener && !window.opener.closed) {
                var origins = [
                    'https://m.insuplus.co.kr',
                    'https://www.insuplus.co.kr'
                ];
                
                for (var i = 0; i < origins.length; i++) {
                    window.opener.postMessage(
                        {type: 'niceAuthResult', payload: result},
                        origins[i]
                    );
                }
            }
        } catch (e) {
            // postMessage 실패해도 계속 진행
        }

        // iOS 처리
        if (isIOS) {
            var msgEl = document.getElementById('closeMsg');
            var btnEl = document.getElementById('manualCloseBtn');
            
            if (msgEl) {
                msgEl.innerText = 'iPhone에서는 창이 자동으로 닫히지 않습니다. 아래 버튼으로 닫아주세요.';
            }
            
            if (btnEl) {
                btnEl.style.display = 'inline-block';
                btnEl.onclick = function() {
                    window.close();
                };
            }
        } else {
            // iOS 아닌 경우 1초 후 자동 닫기
            setTimeout(function() {
                window.close();
            }, 1000);
        }
    }

    // 페이지 로드 시 실행
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            var ua = navigator.userAgent.toLowerCase();
            var isIOS = /iphone|ipad|ipod/i.test(ua) || 
                       (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
            sendResultAndClose(isIOS);
        });
    } else {
        // 이미 로드된 경우 즉시 실행
        var ua = navigator.userAgent.toLowerCase();
        var isIOS = /iphone|ipad|ipod/i.test(ua) || 
                   (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
        sendResultAndClose(isIOS);
    }
})();
</script>
</body>
</html>
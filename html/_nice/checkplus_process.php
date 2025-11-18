<?php
//**************************************************************************************************************
// NICE 본인인증 결과 처리 (AJAX용 JSON 응답)
//**************************************************************************************************************

session_start();

// CORS 헤더 설정 (필요한 경우)
header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json; charset=utf-8');

$sitecode = "CG848";                // NICE로부터 부여받은 사이트 코드
$sitepasswd = "uXIJHj2TqQiz";        // NICE로부터 부여받은 사이트 패스워드

// Linux = /절대경로/ , Window = D:\\절대경로\\ , D:\절대경로\
$cb_encode_path = "/app/projects/insuplus/html/_nice/CPClient_linux_x64";

// 암호화된 결과 데이터
$enc_data = isset($_POST["EncodeData"]) ? $_POST["EncodeData"] : (isset($_GET["EncodeData"]) ? $_GET["EncodeData"] : "");

$auth_result = array();

try {
    //////////////////////////////////////////////// 문자열 점검 ///////////////////////////////////////////////
    if (empty($enc_data)) {
        throw new Exception("암호화 데이터가 없습니다.");
    }

    if (preg_match('~[^0-9a-zA-Z+/=]~', $enc_data, $match)) {
        throw new Exception("입력 값 확인이 필요합니다 : " . $match[0]);
    }

    if (base64_encode(base64_decode($enc_data)) != $enc_data) {
        throw new Exception("입력 값 확인이 필요합니다");
    }

    /////////////////////////////////////////// 복호화 처리 ///////////////////////////////////////////////
    $plaindata = `$cb_encode_path DEC $sitecode $sitepasswd $enc_data`;

    if ($plaindata == -1) {
        throw new Exception("암/복호화 시스템 오류");
    } else if ($plaindata == -4) {
        throw new Exception("복호화 처리 오류");
    } else if ($plaindata == -5) {
        throw new Exception("HASH값 불일치 - 복호화 데이터는 리턴됨");
    } else if ($plaindata == -6) {
        throw new Exception("복호화 데이터 오류");
    } else if ($plaindata == -9) {
        throw new Exception("입력값 오류");
    } else if ($plaindata == -12) {
        throw new Exception("사이트 비밀번호 오류");
    }

    /////////////////////////////////////////// 데이터 파싱 ///////////////////////////////////////////////

    // 암호화된 결과 데이터 검증 (복호화한 시간 획득)
    $ciphertime = `$cb_encode_path CTS $sitecode $sitepasswd $enc_data`;

    // 데이터 추출
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

    /////////////////////////////////////////// 세션 검증 ///////////////////////////////////////////////
    if (isset($_SESSION["REQ_SEQ"]) && strcmp($_SESSION["REQ_SEQ"], $requestnumber) != 0 && strpos($requestnumber, $sitecode) != 0) {
        throw new Exception("세션이 만료되었습니다. 다시 시도해주세요.");
    } else {

        // if (strcmp($_SESSION["REQ_SEQ"], $requestnumber) != 0) {
        //     throw new Exception("세션값이 다릅니다. 올바른 경로로 접근하시기 바랍니다.");
        // }

        // if (strpos($requestnumber, $sitecode) !== 0) {
        //     throw new Exception("잘못된 요청입니다.");
        // }

        /////////////////////////////////////////// 성공 응답 구성 ///////////////////////////////////////////////
        $auth_result = array(
            'success' => true,
            'data' => array(
                'ciphertime' => sanitizeString($ciphertime),
                'requestnumber' => sanitizeString($requestnumber),
                'responsenumber' => sanitizeString($responsenumber),
                'authtype' => sanitizeString($authtype),
                'name' => sanitizeString($name),
                'utf8_name' => sanitizeString($utf8_name),
                'birthdate' => sanitizeString($birthdate),
                'gender' => sanitizeString($gender),
                'nationalinfo' => sanitizeString($nationalinfo),
                'di' => sanitizeString($dupinfo),
                'ci' => sanitizeString($conninfo),
                'mobileno' => sanitizeString($mobileno),
                'mobileco' => sanitizeString($mobileco)
            ),
            'message' => '본인인증이 완료되었습니다.'
        );
    }
    // 세션 정리
    unset($_SESSION["REQ_SEQ"]);
} catch (Exception $e) {
    /////////////////////////////////////////// 실패 응답 구성 ///////////////////////////////////////////////
    $auth_result = array(
        'success' => false,
        'data' => null,
        'message' => $e->getMessage()
    );

    // 에러 로깅 (선택사항)
    error_log("NICE Auth Error: " . $e->getMessage());
}

/////////////////////////////////////////// JSON 응답 출력 ///////////////////////////////////////////////
echo json_encode($auth_result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit;

//********************************************************************************************
// GetValue 함수 - 데이터 파싱
//********************************************************************************************
function GetValue($str, $name)
{
    $pos = 0;
    $str_len = strlen($str);

    while ($pos < $str_len) {
        // 길이 값의 끝 위치 찾기
        $colon_pos = strpos($str, ':', $pos);
        if ($colon_pos === false) break;

        // 길이 값 추출 및 검증
        $len_str = substr($str, $pos, $colon_pos - $pos);
        $len = intval($len_str);
        if ($len <= 0 || !is_numeric($len_str)) break;

        // 키 추출
        $key_start = $colon_pos + 1;
        if ($key_start + $len > $str_len) break;
        $key = substr($str, $key_start, $len);

        // 다음 위치로 이동
        $pos = $key_start + $len;

        if ($key === $name) {
            // 원하는 키를 찾았으면 값의 길이 찾기
            if ($pos >= $str_len) break;

            $colon_pos = strpos($str, ':', $pos);
            if ($colon_pos === false) break;

            $val_len_str = substr($str, $pos, $colon_pos - $pos);
            $val_len = intval($val_len_str);
            if ($val_len < 0 || !is_numeric($val_len_str)) break;

            // 값 추출
            $val_start = $colon_pos + 1;
            if ($val_start + $val_len > $str_len) break;

            return substr($str, $val_start, $val_len);
        } else {
            // 다른 키면 해당 값을 스킵
            if ($pos >= $str_len) break;

            $colon_pos = strpos($str, ':', $pos);
            if ($colon_pos === false) break;

            $val_len_str = substr($str, $pos, $colon_pos - $pos);
            $val_len = intval($val_len_str);
            if ($val_len < 0 || !is_numeric($val_len_str)) break;

            $pos = $colon_pos + 1 + $val_len;
        }
    }

    return "";
}

//********************************************************************************************
// sanitizeString 함수 - 문자열 정제 (XSS 방지 및 UTF-8 보장)
//********************************************************************************************
function sanitizeString($str)
{
    if (empty($str)) return "";

    // UTF-8 변환
    $str = mb_convert_encoding($str, 'UTF-8', 'UTF-8');

    // 제어 문자 제거
    $str = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $str);

    // HTML 특수문자 이스케이프
    $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');

    return $str;
}

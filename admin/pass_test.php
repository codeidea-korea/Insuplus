<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

//[암호처리]
//1. A 서버 : 평문을 Hex 값으로 변경한다
//2. A 서버 : 변경된 Hex 값으로 암호화 Encryption 한다.
//3. A 서버 : 암호화된 Hex 값을 다시 문자열로 변경한다.
//4. A 서버 : 문자열로 변경된 암호화된 Hex 값을 Base64로 인코딩 한다.
//
//[평문처리]
//5. B 서버 : 전달받은 암호문을 Base64로 디코딩 한다.
//6. B 서버 : Base64로 디코딩 된 값을 Hex 값으로 변경한다.
//7. B 서버 : Hex 값으로 변경된 값을 Decryption 한다.
//8. B 서버 : 디코드된 값을 스트링으로 변경한다.
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>암호 [SEED - CBC] 테스트 페이지</title>
<script src="//code.jquery.com/jquery.min.js"></script>
<script>
    $(document).ready(function() {
    });
    //암호화
    function sendENC() {
        let dataArr = new Array();
        dataArr[ 0 ] = $('#pass_text').val();
        $.ajax({
              url: '/admin/all_seed_enc.php',
              type: 'post',
              data: {
                  str: dataArr
              },
              success: function (data) {
                  console.log(data);
              },
              error: function (request, status, error) {
                  console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
              }
        }).done(function(data) {
              $('#result').text(data);
        });
    }
    //복호화
    function sendDEC() {
        let dataArr = new Array();
        dataArr[ 0 ] = $('#pass_text').val();
        $.ajax({
              url: '/admin/all_seed_dec.php',
              type: 'post',
              data: {
                  str: dataArr
              },
              success: function (data) {
                  console.log(data);
              },
              error: function (request, status, error) {
                  console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
              }
        }).done(function(data) {
              $('#result').text(data);
        });
    }
</script>
</head>
<body>
<?
$str = "안녕하세요";
echo "1. 최초 입력값: ";
echo $str;
echo "<Br><br>";

$str = strToHex($str);
$str = substr( $str , 1, strlen($str));
echo "2. 입력값 헥사값 : ";
echo $str;
echo "<Br><br>";

$return = seed_encrypt($g_bszIV, $g_bszUser_key, $str);
echo "3. 헥사값을 암호화:";
echo $return;
echo "<Br><br>";

$return = str_replace(",","", $return);
$return = hexToStr( $return);
echo "4. 암호화를 다시 스트링 : ";
echo $return;
echo "<Br><br>";

echo "5. 암호화를 base64_encode : ";
$return = base64_encode($return);
echo $return;
echo "<Br><br>";

echo "6. 암호화를 base64_decode : ";
$return = base64_decode($return);
echo $return;
echo "<Br><br>";

$return = strToHex($return);
$return = substr( $return , 1, strlen($return));
echo "7. 디코드를 헥사 : ";
echo $return;
echo "<Br><br>";

$return = seed_decrypt($g_bszIV, $g_bszUser_key, $return);
echo "8. 암호화를 복호화:";
echo $return;
echo "<Br><br>";

$return = str_replace(",","", $return);
$return = hexToStr( $return);
echo "9. 복호화를 스트링 : ";
echo $return;
echo "<Br><br>";

// 종합 암호문처리
$str = "insplus";
echo $str." 평문<br>";
$aa = all_seed_enc($str);
echo $aa." 암호문전환<br>";
$bb = all_seed_dec($aa);
echo $bb." 평문전환<br>";
?>
<h3>암/복호화 테스트</h3>
<input type="text" id="pass_text"/>
<button id="send_enc_pass" onclick="sendENC()">enc send</button>
<button id="send_dec_pass" onclick="sendDEC()">dec send</button>

<div id="result">

</div>

</body>

</html>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
$mail = new PHPMailer(true);

try {
    // 서버 설정
    $mail->isSMTP();
    $mail->CharSet = 'UTF-8';
    $mail->Host = 'smtp.naver.com'; // 네이버 SMTP 서버
    $mail->SMTPAuth = true;
    $mail->Mailer = 'smtp';
    // $mail->Username = 'flyingdoctors'; // 네이버 이메일 계정
    // $mail->Password = 'newbiz2021!'; // 네이버 이메일 비밀번호
    $mail->Username = 'bizinsightmaster'; // 네이버 이메일 계정
    $mail->Password = 'RSDUTNPTKRV1'; // 네이버 이메일 비밀번호
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    // 발신자 정보
    // $mail->setFrom('flyingdoctors@naver.com', '인슈플러스');
    $mail->setFrom('bizinsightmaster@naver.com', '인슈플러스');
    $mail->addAddress($emailAddr, ''); // 수신자

    // 이메일 내용
    $mail->isHTML(true);
    $mail->Subject = '인슈플러스 2단계 인증 코드';
    $mail->Body    = "인증 코드: <b>$authCode</b>";
    $mail->AltBody = "인증 코드: $authCode";

    $mail->send();
    echo '인증 코드가 성공적으로 전송되었습니다.';
} catch (Exception $e) {
echo $e;
    echo "메일 전송 실패: {$mail->ErrorInfo}";
}
?>
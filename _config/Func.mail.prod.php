<?
function mailJoinSend($param,$email) {
	$ch = curl_init();

	/* 여기서부터 수정해주시기 바랍니다. */
	$subject = '[인슈플러스] [$NAME] 고객님 인슈플러스에 가입해 주셔서 감사합니다.! ';   //필수입력
	//$body = '[$NAME]님 환영합니다. 치환 문자 입니다. 수신 이메일 : [$EMAIL] 수신번호 : [$MOBILE] 메모 : [$NOTE]';                 //필수입력
	// $sender = "help@insuplus.co.kr";         //필수입력 config.php
	// $sender_name = "인슈플러스";
	// $username = "kassist";                //필수입력
	// $key = "MHvEdNfJ0kZrp4b";           //필수입력

	// $sender = "help@insuplus.co.kr";         //필수입력 config.php
	// $sender_name = "인슈플러스";
	// $username = "kassist";                //필수입력
	// $key = "MHvEdNfJ0kZrp4b";           //필수입력



    //20250318 추가 신규
    $sender = "help@insuplus.co.kr";         //필수입력 config.php
	$sender_name = "인슈플러스";
	$username = "insuplus";                //필수입력
	$key = "KIsg5ekF3H0hUG2";           //필수입력


 
	$body = "<section class='insurplus_wrap' style='position:relative;text-align:center;width: 760px; background:#fff;padding:0px;margin:10px auto'>";
	$body .= "<div class='top_image'>";
	$body .= "<img src='http://www.insuplus.co.kr/html/images/sub-register-result-banner.png' class='pc-image' /></div>";
	$body .= "<section class='insurplus_content' style='padding:38px;background-color: #fff;border-radius: 10px;border: solid 1px #e3e3e3;box-shadow: 0px 3px 5px 3px rgba(0,0,0,0.05);'>";
	$body .= "<h5 class='text-black title' style='color:#000; font-size:16px; color:#000;line-height:1.5; text-align:left;margin:0px 0px 16px 0'>가입정보</h5>";
	$body .= "<table class='table-insurplus' style='width: 100%;border-collapse: collapse;border-top:solid 2px #595959;border-bottom:solid 2px #595959;'><tbody>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>보험기간</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>#{period}</td>";
	$body .= "</tr>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>상품명</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>#{plan_name}</td>";
	$body .= "</tr>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>가입자</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>#{name}</td>";
	$body .= "</tr>";
	if(strtolower($param["payMethod"]) == "vbank") { //가상계좌
   $VACT_Num = $param["account"];
	$arr_pay_name = explode("/",$param["pay_name"]);
	$body .= "<tr>"; 
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>결제상태</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>입금전</td>";
	$body .= "</tr>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>가상계좌</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>".$arr_pay_name[1].":".$VACT_Num."</td>";
	$body .= "</tr>";
	} else {
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>결제상태</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>결제완료</td>";
	$body .= "</tr>";
	}
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>상품가</th>";
	$body .= "<td style='font-size:18px;padding:11px 15px;text-align:right; border-bottom:1px solid #d6d6d6; width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;''><span class='text-black' style='color:#000;'>#{amount}</span> <small style='font-size:14px'>원</small></td>";
	$body .= "</tr>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>결제금액</th>";
	$body .= "<td style='font-size:24px;padding:6px 15px;text-align:right;border-bottom:1px solid #d6d6d6;;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;''><span class='text-danger' style='color:#dc3347;'>#{t_amount}</span> <small style='font-size:14px'>원</small></td>";
	$body .= "</tr>";
	$body .= "</tbody>";
	$body .= "</table>";
	$body .= "<div class='btn-group' style='margin: 16px auto; text-align:center;'>";
	if($param['ins_amount'] && $param['ins_seq'] > 0){
		$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=ins_agree&seq=#{ins_seq}' style='padding:12px 20px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>보험약관 자세히 보기</a>";
	} else {
		if($param['ins_term1'] > 0) {
			$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=ins_agree&seq=#{ins_term1}' style='padding:12px 20px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>보험약관 자세히 보기</a>";
		} 
		if($param['ins_term2'] > 0) {
			$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=ins_agree&seq=#{ins_term2}' style='padding:12px 20px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>보험약관 자세히 보기</a>";
		}
	}
	
	if($param['chk_service'] == "C") {
		$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=service_agree&seq=#{service_seq}' style='padding:12px 20px; margin-left:15px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>서비스 이용약관 자세히 보기</a>";
	} else {
		if($param["chk_service"] == "" && $param["service_seq"] > 0) {
			$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=service_agree&seq=#{service_seq}' style='padding:12px 20px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>서비스 이용약관 자세히 보기</a>";
		} 
	}
	$body .= "</div>";
	$body .= "<p style='margin:10px 0 20px 0; color:#333; font-size:14px;'>자세한 보장 내역 확인 및 출발전 취소는 아래 가입 내역 조회 페이지를 클릭해 주세요.</p>";
	$body .= "<a href='#{domain}/html/join/join_confirm.php' target='_blank' style='padding:12px 40px; text-decoration:none; background-color:#29354c;border-color:#29354c;color:#fff;width:300px;border-radius:4px;'>가입내역 조회하기</a>";
	$body .= "<p style='color:#333; margin:30px 0 0 0;'><img src='http://www.insuplus.co.kr/html/images/ic-noti.svg' align='absmiddle' alt='' height='24' />&nbsp;상품 안내 및 보상신청은 <strong class='text-black' style='color:#000;'>24시간 알람센터 02-360-2545</strong> 또는  <strong class='text-black' style='color:#000;'><a href='https://pf.kakao.com/_JClxfT/chat' target='_blank' style='text-decoration:none; color:#000'>카카오톡 플러스 친구</a></strong> 문의해 주세요.</p>";
	$body .= "</section>";
	$body .= "</section>";


	$receiver = '{"name":"'.$param["name"].'","email":"'.$email.'"}';
	$receiver = '['.$receiver.']';      //JSON 데이터


	$body = str_replace("#{name}",$param['name'],$body);
	$body = str_replace("#{period}",$param['period'],$body);
	$body = str_replace("#{pr_name}",$param['pr_name'],$body);
	$body = str_replace("#{plan_name}",$param['plan_name'],$body);
	$body = str_replace("#{amount}",number_format($param['amount']),$body);
	$body = str_replace("#{t_amount}",number_format($param['t_amount']),$body);
	$body = str_replace("#{domain}",$param['domain'],$body);

	$body = str_replace("#{ins_seq}",$param['ins_seq'],$body);
	$body = str_replace("#{ins_term1}",$param['ins_term1'],$body);
	$body = str_replace("#{ins_term2}",$param['ins_term2'],$body);
	$body = str_replace("#{service_seq}",$param['service_seq'],$body);

	// 주소록을 사용하길 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 주소록 번호를 입력해주시기 바랍니다.
	//$address_books = "0,1,2";      //발송 할 주소록 번호 , 로 구분함 (ex. 0, 1, 2)

	$bodytag = '1';  //HTML이 기본값 입니다. 메일 내용을 텍스트로 보내실 경우 주석을 해제 해주시기 바랍니다.

	// 실제 발송성공실패 여부를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해주시기 바랍니다.
	$return_url = 0;
	//open, click 등의 결과를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해주시기 바랍니다.
	//등록된 도메인이 http://domain 와 같을 경우, http://domain?type=[click | open | reject]&mail_id=[MailID]&email=[Email] 과 같은 형식으로 request를 보내드립니다.
	$option_return_url = 0;

	$open = 1;	// open 결과를 받으려면 아래 주석을 해제 해주시기 바랍니다.
	$click = 1;	// click 결과를 받으려면 아래 주석을 해제 해주시기 바랍니다.
	$check_period = 3;	// 트래킹 기간을 지정하며 3 / 7 / 10 / 15 일을 기준으로 지정하여 발송해 주시기 바랍니다. (단, 지정을 하지 않을 경우 결과를 받을 수 없습니다.)

	// 예약발송 정보 추가
	$mail_type = 'NORMAL'; // NORMAL - 즉시발송 / ONETIME - 1회예약 / WEEKLY - 매주정기예약 / MONTHLY - 매월정기예약
	$start_reserve_time = date('Y-m-d H:i:s'); //  발송하고자 하는 시간(시,분단위까지만 가능) (동일한 예약 시간으로는 200건 이상 등록할 수 없습니다.)
	$end_reserve_time = date('Y-m-d H:i:s'); //  발송이 끝나는 시간 1회 예약일 경우 $start_reserve_time = $end_reserve_time
	// WEEKLY | MONTHLY 일 경우에 시작 시간부터 끝나는 시간까지 발송되는 횟수 Ex) type = WEEKLY, start_reserve_time = '2017-05-17 13:00:00', end_reserve_time = '2017-05-24 13:00:00' 이면 remained_count = 2 로 되어야 합니다.
	$remained_count = 1;
	// 예약 수정/취소 API는 소스 하단을 참고 해주시기 바랍니다.

	//필수안내문구 추가
	$agreement_text = '본메일은 [$NOW_DATE] 기준, 회원님의 수신동의 여부를 확인한 결과 회원님께서 수신동의를 하셨기에 발송되었습니다.';
	$deny_text = "메일 수신을 원치 않으시면 [" . '$DENY_LINK' . "]를 클릭하세요.\\nIf you don't want this type of information or e-mail, please click the [".'$EN_DENY_LINK'."]";
	$sender_info_text = "사업자 등록번호:-- 소재지:ㅇㅇ시(도) ㅇㅇ구(군) ㅇㅇ동 ㅇㅇㅇ번지 TEL:--\\nEmail: <a href='mailto:test@directsend.co.kr'>test@directsend.co.kr</a>";
	$logo_state = 1; // logo 사용시 1 / 사용안할 시 0
	$logo_path = 'http://logoimage.com/image.png';  //사용하실 로고 이미지를 입력하시기 바랍니다.

	// 첨부파일의 URL을 보내면 DirectSend에서 파일을 download 받아 발송처리를 진행합니다. 첨부파일은 전체 10MB 이하로 발송을 해야 하며, 파일의 구분자는 '|(shift+\)'로 사용하며 5개까지만 첨부가 가능합니다.
	$file_url = 'https://directsend.co.kr/test.png|https://directsend.co.kr/test1.png';
	// 첨부파일의 이름을 지정할 수 있도록 합니다.
	// 첨부파일의 이름은 순차적(https://directsend.co.kr/test.png - image.png, https://directsend.co.kr/test1.png - image2.png) 와 같이 적용이 되며, file_name을 지정하지 않은 경우 마지막의 파일의 이름으로 메일에 보여집니다.
	$file_name = 'image.png|image2.png';

	/* 여기까지 수정해주시기 바랍니다. */

	$postvars = '"subject":"'.$subject.'"';
	$postvars = $postvars.', "body":"'.$body.'"';
	$postvars = $postvars.', "sender":"'.$sender.'"';
	$postvars = $postvars.', "sender_name":"'.$sender_name.'"';
	$postvars = $postvars.', "username":"'.$username.'"';
	$postvars = $postvars.', "receiver":'.$receiver;
	$postvars = $postvars.', "key":"'.$key.'"';
	$postvars = '{'.$postvars.'}';      //JSON 데이터

	// URL
	$url = "https://directsend.co.kr/index.php/api_v2/mail_change_word";

	//헤더정보
	$headers = array(
			"cache-control: no-cache",
			"content-type: application/json; charset=utf-8"
	);

	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);		//JSON 데이터
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
	curl_setopt($ch,CURLOPT_TIMEOUT, 20);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec($ch);
	//curl 에러 확인
	if(curl_errno($ch)){
		echo 'Curl error: ' . curl_error($ch);
	}else{
		// print_r($response);
		// echo "1";
	}

	curl_close ($ch);
}

function mailJoinSend2($param,$email,$file_url, $file_name) {
	$ch = curl_init();

	/* 여기서부터 수정해주시기 바랍니다. */
	$subject = '[$NAME] 고객님 '.$param['service_name'].'에 가입해 주셔서 감사합니다. ';   //필수입력
	//$body = '[$NAME]님 환영합니다. 치환 문자 입니다. 수신 이메일 : [$EMAIL] 수신번호 : [$MOBILE] 메모 : [$NOTE]';                 //필수입력
	// $sender = "assist@flyingdoctors.co.kr";         //필수입력 config.php
	// $sender_name = "플라잉닥터스";
	// $username = "kassist";                //필수입력
	// $key = "MHvEdNfJ0kZrp4b";           //필수입력
    

    //20250318 추가 신규
    $sender = "assist@flyingdoctors.co.kr";         //필수입력 config.php
	$sender_name = "플라잉닥터스";
	$username = "insuplus";                //필수입력
	$key = "KIsg5ekF3H0hUG2";           //필수입력

	$body = "<section class='insurplus_wrap' style='position:relative;text-align:center;width: 760px; background:#fff;padding:0px;margin:10px auto'>";
	$body .= "<div class='top_image'>";
	$body .= "<img src='http://www.insuplus.co.kr/html/images/sub-register-result-banner.jpg' class='pc-image' /></div>";
	$body .= "<section class='insurplus_content' style='padding:38px;background-color: #fff;border-radius: 10px;border: solid 1px #e3e3e3;box-shadow: 0px 3px 5px 3px rgba(0,0,0,0.05);'>";
	$body .= "<h5 class='text-black title' style='color:#000; font-size:16px; color:#000;line-height:1.5; text-align:left;margin:0px 0px 16px 0'>가입정보</h5>";
	$body .= "<table class='table-insurplus' style='width: 100%;border-collapse: collapse;border-top:solid 2px #595959;border-bottom:solid 2px #595959;'><tbody>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>보험기간</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>#{period}</td>";
	$body .= "</tr>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>상품명</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>#{pr_name}</td>";
	$body .= "</tr>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>가입자</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>#{name}</td>";
	$body .= "</tr>";
	if(strtolower($param["payMethod"]) == "vbank") { //가상계좌
	$arr_pay_name = explode("/",$param["pay_name"]);
    $VACT_Num = $param["account"];
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>결제상태</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>입금전</td>";
	$body .= "</tr>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>가상계좌</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>".$arr_pay_name[1].":".$VACT_Num."</td>";
	$body .= "</tr>";
	} else {
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>결제상태</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>결제완료</td>";
	$body .= "</tr>";
	}
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>상품가</th>";
	$body .= "<td style='font-size:18px;padding:11px 15px;text-align:right; border-bottom:1px solid #d6d6d6; width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;''><span class='text-black' style='color:#000;'>#{amount}</span> <small style='font-size:14px'>원</small></td>";
	$body .= "</tr>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>결제금액</th>";
	$body .= "<td style='font-size:24px;padding:6px 15px;text-align:right;border-bottom:1px solid #d6d6d6;;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;''><span class='text-danger' style='color:#dc3347;'>#{t_amount}</span> <small style='font-size:14px'>원</small></td>";
	$body .= "</tr>";
	$body .= "</tbody>";
	$body .= "</table>";
	$body .= "<div class='btn-group' style='margin: 16px auto; text-align:center;'>";
	if($param['ins_amount'] && $param['ins_seq'] > 0){
		$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=ins_agree&seq=#{ins_seq}' style='padding:12px 20px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>보험약관 자세히 보기</a>";
	} else {
		if($param['ins_term1'] > 0) {
			$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=ins_agree&seq=#{ins_term1}' style='padding:12px 20px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>보험약관 자세히 보기</a>";
		} 
		if($param['ins_term2'] > 0) {
			$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=ins_agree&seq=#{ins_term2}' style='padding:12px 20px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>보험약관 자세히 보기</a>";
		}
	}
	
	if($param['chk_service'] == "C") {
		$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=service_agree&seq=#{service_seq}' style='padding:12px 20px; margin-left:15px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>서비스 이용약관 자세히 보기</a>";
	} else {
		if($param["chk_service"] == "" && $param["service_seq"] > 0) {
			$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=service_agree&seq=#{service_seq}' style='padding:12px 20px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>서비스 이용약관 자세히 보기</a>";
		} 
	}
	$body .= "</div>";
	$body .= "<p style='color:#333; margin:30px 0 0 0;'><img src='http://www.insuplus.co.kr/html/images/ic-noti.svg' align='absmiddle' alt='' height='24' />&nbsp;상품 안내 및 보상신청은 <strong class='text-black' style='color:#000;'>24시간 알람센터 02-360-2525</strong> 또는  <strong class='text-black' style='color:#000;'><a href='https://pf.kakao.com/_JClxfT/chat' target='_blank' style='text-decoration:none; color:#000'>카카오톡 플러스 친구</a></strong> 문의해 주세요.</p>";
	$body .= "</section>";
	$body .= "</section>";


	$receiver = '{"name":"'.$param["name"].'","email":"'.$email.'"}';
	$receiver = '['.$receiver.']';      //JSON 데이터


	$body = str_replace("#{name}",$param['name'],$body);
	$body = str_replace("#{period}",$param['period'],$body);
	$body = str_replace("#{pr_name}",$param['pr_name'],$body);
	$body = str_replace("#{amount}",number_format($param['amount']),$body);
	$body = str_replace("#{t_amount}",number_format($param['t_amount']),$body);
	$body = str_replace("#{domain}",$param['domain'],$body);

	$body = str_replace("#{ins_seq}",$param['ins_seq'],$body);
	$body = str_replace("#{ins_term1}",$param['ins_term1'],$body);
	$body = str_replace("#{ins_term2}",$param['ins_term2'],$body);
	$body = str_replace("#{service_seq}",$param['service_seq'],$body);

	// 주소록을 사용하길 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 주소록 번호를 입력해주시기 바랍니다.
	//$address_books = "0,1,2";      //발송 할 주소록 번호 , 로 구분함 (ex. 0, 1, 2)

	$bodytag = '1';  //HTML이 기본값 입니다. 메일 내용을 텍스트로 보내실 경우 주석을 해제 해주시기 바랍니다.

	// 실제 발송성공실패 여부를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해주시기 바랍니다.
	$return_url = 0;
	//open, click 등의 결과를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해주시기 바랍니다.
	//등록된 도메인이 http://domain 와 같을 경우, http://domain?type=[click | open | reject]&mail_id=[MailID]&email=[Email] 과 같은 형식으로 request를 보내드립니다.
	$option_return_url = 0;

	$open = 1;	// open 결과를 받으려면 아래 주석을 해제 해주시기 바랍니다.
	$click = 1;	// click 결과를 받으려면 아래 주석을 해제 해주시기 바랍니다.
	$check_period = 3;	// 트래킹 기간을 지정하며 3 / 7 / 10 / 15 일을 기준으로 지정하여 발송해 주시기 바랍니다. (단, 지정을 하지 않을 경우 결과를 받을 수 없습니다.)

	// 예약발송 정보 추가
	$mail_type = 'NORMAL'; // NORMAL - 즉시발송 / ONETIME - 1회예약 / WEEKLY - 매주정기예약 / MONTHLY - 매월정기예약
	$start_reserve_time = date('Y-m-d H:i:s'); //  발송하고자 하는 시간(시,분단위까지만 가능) (동일한 예약 시간으로는 200건 이상 등록할 수 없습니다.)
	$end_reserve_time = date('Y-m-d H:i:s'); //  발송이 끝나는 시간 1회 예약일 경우 $start_reserve_time = $end_reserve_time
	// WEEKLY | MONTHLY 일 경우에 시작 시간부터 끝나는 시간까지 발송되는 횟수 Ex) type = WEEKLY, start_reserve_time = '2017-05-17 13:00:00', end_reserve_time = '2017-05-24 13:00:00' 이면 remained_count = 2 로 되어야 합니다.
	$remained_count = 1;
	// 예약 수정/취소 API는 소스 하단을 참고 해주시기 바랍니다.

	//필수안내문구 추가
	$agreement_text = '본메일은 [$NOW_DATE] 기준, 회원님의 수신동의 여부를 확인한 결과 회원님께서 수신동의를 하셨기에 발송되었습니다.';
	$deny_text = "메일 수신을 원치 않으시면 [" . '$DENY_LINK' . "]를 클릭하세요.\\nIf you don't want this type of information or e-mail, please click the [".'$EN_DENY_LINK'."]";
	$sender_info_text = "사업자 등록번호:-- 소재지:ㅇㅇ시(도) ㅇㅇ구(군) ㅇㅇ동 ㅇㅇㅇ번지 TEL:--\\nEmail: <a href='mailto:test@directsend.co.kr'>test@directsend.co.kr</a>";
	$logo_state = 1; // logo 사용시 1 / 사용안할 시 0
	$logo_path = 'http://logoimage.com/image.png';  //사용하실 로고 이미지를 입력하시기 바랍니다.

	// 첨부파일의 URL을 보내면 DirectSend에서 파일을 download 받아 발송처리를 진행합니다. 첨부파일은 전체 10MB 이하로 발송을 해야 하며, 파일의 구분자는 '|(shift+\)'로 사용하며 5개까지만 첨부가 가능합니다.
	//$file_url = 'https://directsend.co.kr/test.png|https://directsend.co.kr/test1.png';
	// 첨부파일의 이름을 지정할 수 있도록 합니다.
	// 첨부파일의 이름은 순차적(https://directsend.co.kr/test.png - image.png, https://directsend.co.kr/test1.png - image2.png) 와 같이 적용이 되며, file_name을 지정하지 않은 경우 마지막의 파일의 이름으로 메일에 보여집니다.
	//$file_name = 'image.png|image2.png';

	/* 여기까지 수정해주시기 바랍니다. */

	$postvars = '"subject":"'.$subject.'"';
	$postvars = $postvars.', "body":"'.$body.'"';
	$postvars = $postvars.', "sender":"'.$sender.'"';
	$postvars = $postvars.', "sender_name":"'.$sender_name.'"';
	$postvars = $postvars.', "username":"'.$username.'"';
	$postvars = $postvars.', "receiver":'.$receiver;
	$postvars = $postvars.', "key":"'.$key.'"';
	$postvars = $postvars.', "file_url":"'.$file_url.'"';
	$postvars = $postvars.', "file_name":"'.$file_name.'"';
	$postvars = '{'.$postvars.'}';      //JSON 데이터
	// URL
	$url = "https://directsend.co.kr/index.php/api_v2/mail_change_word";

	//헤더정보
	$headers = array(
			"cache-control: no-cache",
			"content-type: application/json; charset=utf-8"
	);

	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);		//JSON 데이터
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
	curl_setopt($ch,CURLOPT_TIMEOUT, 20);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec($ch);
	//curl 에러 확인
	if(curl_errno($ch)){
		echo 'Curl error: ' . curl_error($ch);
	}else{
		//print_R($response);
		//echo "1";
	}

	curl_close ($ch);
}

function mailBizJoinSend($param,$email,$file_url, $file_name) {
	$ch = curl_init();

	/* 여기서부터 수정해주시기 바랍니다. */
	//$subject = '[$NAME] 고객님 '.$param['service_name'].'에 가입해 주셔서 감사합니다. ';   //필수입력
	$subject = '[플라잉닥터스] 해외안전관리서비스 가입 증명서 발송';   //필수입력
	//$subject = '[세종학당재단] 파견교원 안전관리서비스 가입 증명서 발송';
	//$body = '[$NAME]님 환영합니다. 치환 문자 입니다. 수신 이메일 : [$EMAIL] 수신번호 : [$MOBILE] 메모 : [$NOTE]';                 //필수입력
	// $sender = "assist@flyingdoctors.co.kr";         //필수입력 config.php
	// $sender_name = "플라잉닥터스";
	// $username = "kassist";                //필수입력
	// $key = "MHvEdNfJ0kZrp4b";           //필수입력

    //20250318 추가 신규
    $sender = "assist@flyingdoctors.co.kr";         //필수입력 config.php
	$sender_name = "플라잉닥터스";
	$username = "insuplus";                //필수입력
	$key = "KIsg5ekF3H0hUG2";           //필수입력

	$body = "<section class='insurplus_wrap' style='position:relative;text-align:center;width: 760px; background:#fff;padding:0px;margin:10px auto'>";
	$body .= "<div class='top_image'>";
	$body .= "<img src='http://www.insuplus.co.kr/html/images/sub-register-result-banner4.png' class='pc-image' /></div>";
	$body .= "<section class='insurplus_content' style='padding:38px;background-color: #fff;border-radius: 10px;border: solid 1px #e3e3e3;box-shadow: 0px 3px 5px 3px rgba(0,0,0,0.05);'>";
	$body .= "<h5 class='text-black title' style='color:#000; font-size:16px; color:#000;line-height:1.5; text-align:left;margin:0px 0px 16px 0'>가입정보</h5>";
	$body .= "<table class='table-insurplus' style='width: 100%;border-collapse: collapse;border-top:solid 2px #595959;border-bottom:solid 2px #595959;'><tbody>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>보험기간</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>#{period}</td>";
	$body .= "</tr>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>상품명</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>#{pr_name}</td>";
	$body .= "</tr>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>가입자</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>#{name}</td>";
	$body .= "</tr>";
	$body .= "</tbody>";
	$body .= "</table>";
	$body .= "<div class='btn-group' style='margin: 16px auto; text-align:center;'>";
	if($param['ins_amount'] && $param['ins_seq'] > 0){
		$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=ins_agree&seq=#{ins_seq}' style='padding:12px 20px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>보험약관 자세히 보기</a>";
	} else {
		if($param['ins_term1'] > 0) {
			$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=ins_agree&seq=#{ins_term1}' style='padding:12px 20px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>보험약관 자세히 보기</a>";
		} 
		if($param['ins_term2'] > 0) {
			$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=ins_agree&seq=#{ins_term2}' style='padding:12px 20px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>보험약관 자세히 보기</a>";
		}
	}
	
	if($param['chk_service'] == "C") {
		$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=service_agree&seq=#{service_seq}' style='padding:12px 20px; margin-left:15px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>서비스 이용약관 자세히 보기</a>";
	} else {
		if($param["chk_service"] == "" && $param["service_seq"] > 0) {
			$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=service_agree&seq=#{service_seq}' style='padding:12px 20px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>서비스 이용약관 자세히 보기</a>";
		} 
	}
	$body .= "</div>";
	$body .= "<p style='color:#333; margin:30px 0 0 0;'><img src='http://www.insuplus.co.kr/html/images/ic-noti.svg' align='absmiddle' alt='' height='24' />&nbsp;상품 안내 및 보상신청은 <strong class='text-black' style='color:#000;'>24시간 알람센터 02-360-2525</strong> 또는  <strong class='text-black' style='color:#000;'><a href='https://pf.kakao.com/_vWkkT/chat' target='_blank' style='text-decoration:none; color:#000'>카카오톡 플러스 친구</a></strong> 문의해 주세요.</p>";
	$body .= "</section>";
	$body .= "</section>";


	$receiver = '{"name":"'.$param["name"].'","email":"'.$email.'"}';
	$receiver = '['.$receiver.']';      //JSON 데이터


	$body = str_replace("#{name}",$param['name'],$body);
	$body = str_replace("#{period}",$param['period'],$body);
	$body = str_replace("#{pr_name}",$param['pr_name'],$body);
	$body = str_replace("#{amount}",number_format($param['amount']),$body);
	$body = str_replace("#{t_amount}",number_format($param['t_amount']),$body);
	$body = str_replace("#{domain}",$param['domain'],$body);

	$body = str_replace("#{ins_seq}",$param['ins_seq'],$body);
	$body = str_replace("#{ins_term1}",$param['ins_term1'],$body);
	$body = str_replace("#{ins_term2}",$param['ins_term2'],$body);
	$body = str_replace("#{service_seq}",$param['service_seq'],$body);

	// 주소록을 사용하길 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 주소록 번호를 입력해주시기 바랍니다.
	//$address_books = "0,1,2";      //발송 할 주소록 번호 , 로 구분함 (ex. 0, 1, 2)

	$bodytag = '1';  //HTML이 기본값 입니다. 메일 내용을 텍스트로 보내실 경우 주석을 해제 해주시기 바랍니다.

	// 실제 발송성공실패 여부를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해주시기 바랍니다.
	$return_url = 0;
	//open, click 등의 결과를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해주시기 바랍니다.
	//등록된 도메인이 http://domain 와 같을 경우, http://domain?type=[click | open | reject]&mail_id=[MailID]&email=[Email] 과 같은 형식으로 request를 보내드립니다.
	$option_return_url = 0;

	$open = 1;	// open 결과를 받으려면 아래 주석을 해제 해주시기 바랍니다.
	$click = 1;	// click 결과를 받으려면 아래 주석을 해제 해주시기 바랍니다.
	$check_period = 3;	// 트래킹 기간을 지정하며 3 / 7 / 10 / 15 일을 기준으로 지정하여 발송해 주시기 바랍니다. (단, 지정을 하지 않을 경우 결과를 받을 수 없습니다.)

	// 예약발송 정보 추가
	$mail_type = 'NORMAL'; // NORMAL - 즉시발송 / ONETIME - 1회예약 / WEEKLY - 매주정기예약 / MONTHLY - 매월정기예약
	$start_reserve_time = date('Y-m-d H:i:s'); //  발송하고자 하는 시간(시,분단위까지만 가능) (동일한 예약 시간으로는 200건 이상 등록할 수 없습니다.)
	$end_reserve_time = date('Y-m-d H:i:s'); //  발송이 끝나는 시간 1회 예약일 경우 $start_reserve_time = $end_reserve_time
	// WEEKLY | MONTHLY 일 경우에 시작 시간부터 끝나는 시간까지 발송되는 횟수 Ex) type = WEEKLY, start_reserve_time = '2017-05-17 13:00:00', end_reserve_time = '2017-05-24 13:00:00' 이면 remained_count = 2 로 되어야 합니다.
	$remained_count = 1;
	// 예약 수정/취소 API는 소스 하단을 참고 해주시기 바랍니다.

	//필수안내문구 추가
	$agreement_text = '본메일은 [$NOW_DATE] 기준, 회원님의 수신동의 여부를 확인한 결과 회원님께서 수신동의를 하셨기에 발송되었습니다.';
	$deny_text = "메일 수신을 원치 않으시면 [" . '$DENY_LINK' . "]를 클릭하세요.\\nIf you don't want this type of information or e-mail, please click the [".'$EN_DENY_LINK'."]";
	$sender_info_text = "사업자 등록번호:-- 소재지:ㅇㅇ시(도) ㅇㅇ구(군) ㅇㅇ동 ㅇㅇㅇ번지 TEL:--\\nEmail: <a href='mailto:test@directsend.co.kr'>test@directsend.co.kr</a>";
	$logo_state = 1; // logo 사용시 1 / 사용안할 시 0
	$logo_path = 'http://logoimage.com/image.png';  //사용하실 로고 이미지를 입력하시기 바랍니다.

	// 첨부파일의 URL을 보내면 DirectSend에서 파일을 download 받아 발송처리를 진행합니다. 첨부파일은 전체 10MB 이하로 발송을 해야 하며, 파일의 구분자는 '|(shift+\)'로 사용하며 5개까지만 첨부가 가능합니다.
	//$file_url = 'https://directsend.co.kr/test.png|https://directsend.co.kr/test1.png';
	// 첨부파일의 이름을 지정할 수 있도록 합니다.
	// 첨부파일의 이름은 순차적(https://directsend.co.kr/test.png - image.png, https://directsend.co.kr/test1.png - image2.png) 와 같이 적용이 되며, file_name을 지정하지 않은 경우 마지막의 파일의 이름으로 메일에 보여집니다.
	//$file_name = 'image.png|image2.png';

	/* 여기까지 수정해주시기 바랍니다. */

	$postvars = '"subject":"'.$subject.'"';
	$postvars = $postvars.', "body":"'.$body.'"';
	$postvars = $postvars.', "sender":"'.$sender.'"';
	$postvars = $postvars.', "sender_name":"'.$sender_name.'"';
	$postvars = $postvars.', "username":"'.$username.'"';
	$postvars = $postvars.', "receiver":'.$receiver;
	$postvars = $postvars.', "key":"'.$key.'"';
	$postvars = $postvars.', "file_url":"'.$file_url.'"';
	$postvars = $postvars.', "file_name":"'.$file_name.'"';
	$postvars = '{'.$postvars.'}';      //JSON 데이터
	// URL
	$url = "https://directsend.co.kr/index.php/api_v2/mail_change_word";

	//헤더정보
	$headers = array(
			"cache-control: no-cache",
			"content-type: application/json; charset=utf-8"
	);

	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);		//JSON 데이터
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
	curl_setopt($ch,CURLOPT_TIMEOUT, 20);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec($ch);
	//curl 에러 확인
	if(curl_errno($ch)){
		echo 'Curl error: ' . curl_error($ch);
	}else{
		//print_R($response);
		//echo "1";
	}

	curl_close ($ch);
}

function mailReJoinSend($param,$email) {
	$ch = curl_init();

	/* 여기서부터 수정해주시기 바랍니다. */
	$subject = '[인슈플러스] [$NAME]고객님 보험 만료 안내 드립니다.';   //필수입력
	// $sender = "help@insuplus.co.kr";         //필수입력 config.php
	// $sender_name = "인슈플러스";
	// $username = "kassist";                //필수입력
	// $key = "MHvEdNfJ0kZrp4b";           //필수입력

    // 신규
    $sender = "yjhzzzzdev@gmail.com";         //필수입력 config.php
	$sender_name = "인슈플러스 - 개발";
	$username = "insuplus";                //필수입력
	$key = "KIsg5ekF3H0hUG2";           //필수입력

	$body = "<section class='insurplus_wrap' style='position:relative;text-align:center;width: 700px; background:#fff;padding:0px;margin:10px auto'>";
	$body .= "<div class='top_image'>";
	$body .= "<a href='https://insuplus.co.kr/' target='_blank' style='text-decoration:none; color:#fff;width:300px;border-radius:4px;'>";
	$body .= "<img src='http://www.insuplus.co.kr/html/images/ins_rejoin.png' class='pc-image' />";
	$body .= "</a>";
	$body .= "</div>";
	$body .= "<section class='insurplus_content' style='padding:30px;background-color: #fff;border-radius: 10px;border: solid 1px #e3e3e3;box-shadow: 0px 3px 5px 3px rgba(0,0,0,0.05);'>";
	$body .= "<p style='margin:10px 0 20px 10px; color:#333; font-size:14px; text-align:left;'>";
	$body .= "안녕하세요? #{name}고객님. 인슈플러스를 이용해 주셔서 감사합니다.<br/><br/>";
	$body .= "고객님이 가입하신 보험이 #{eDate}에 만료되어 사전 안내해  드립니다. <br/>";
	$body .= "연장계약이 필요하신 경우 만료 전에 인슈플러스 센터로 연락 주시기 바랍니다.";
	$body .= "</p>";
	$body .= "<h5 class='text-black title' style='color:#000; font-size:16px; color:#000;line-height:1.5; text-align:left;margin:0px 0px 16px 0'>가입정보</h5>";
	$body .= "<table class='table-insurplus' style='width: 100%;border-collapse: collapse;border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>";
	$body .= "<tbody>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>보험기간</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>#{sDate} ~ #{eDate}</td>";
	$body .= "</tr>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>상품명</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>#{prName}</td>";
	$body .= "</tr>";
	$body .= "</tbody>";
	$body .= "</table>";
	$body .= "<h5 class='text-black title' style='color:#000; font-size:16px; color:#000;line-height:1.5; text-align:left;margin:15px 0px 16px 0'>문의</h5>";
	$body .= "<p style='margin:10px 0 20px 10px; color:#333; font-size:14px; text-align:left;'>";
	$body .= "<strong class='text-black' style='color:#000;'><a href='https://pf.kakao.com/_JClxfT/chat' target='_blank' style='text-decoration:none; color:#000'>카카오톡 채널 “인슈플러스”</a></strong><br/>";
	$body .= "전화 02-360-2545";
	$body .= "</p>";
	$body .= "</section>";
	$body .= "</section>";


	$receiver = '{"name":"'.$param["name"].'","email":"'.$email.'"}';
	$receiver = '['.$receiver.']';      //JSON 데이터


	$body = str_replace("#{name}",$param['name'],$body);
	$body = str_replace("#{prName}",$param['prName'],$body);
	$body = str_replace("#{sDate}",$param['sDate'],$body);
	$body = str_replace("#{eDate}",$param['eDate'],$body);

	// 주소록을 사용하길 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 주소록 번호를 입력해주시기 바랍니다.
	//$address_books = "0,1,2";      //발송 할 주소록 번호 , 로 구분함 (ex. 0, 1, 2)

	$bodytag = '1';  //HTML이 기본값 입니다. 메일 내용을 텍스트로 보내실 경우 주석을 해제 해주시기 바랍니다.

	// 실제 발송성공실패 여부를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해주시기 바랍니다.
	$return_url = 0;
	//open, click 등의 결과를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해주시기 바랍니다.
	//등록된 도메인이 http://domain 와 같을 경우, http://domain?type=[click | open | reject]&mail_id=[MailID]&email=[Email] 과 같은 형식으로 request를 보내드립니다.
	$option_return_url = 0;

	$open = 1;	// open 결과를 받으려면 아래 주석을 해제 해주시기 바랍니다.
	$click = 1;	// click 결과를 받으려면 아래 주석을 해제 해주시기 바랍니다.
	$check_period = 3;	// 트래킹 기간을 지정하며 3 / 7 / 10 / 15 일을 기준으로 지정하여 발송해 주시기 바랍니다. (단, 지정을 하지 않을 경우 결과를 받을 수 없습니다.)

	// 예약발송 정보 추가
	$mail_type = 'NORMAL'; // NORMAL - 즉시발송 / ONETIME - 1회예약 / WEEKLY - 매주정기예약 / MONTHLY - 매월정기예약
	$start_reserve_time = date('Y-m-d H:i:s'); //  발송하고자 하는 시간(시,분단위까지만 가능) (동일한 예약 시간으로는 200건 이상 등록할 수 없습니다.)
	$end_reserve_time = date('Y-m-d H:i:s'); //  발송이 끝나는 시간 1회 예약일 경우 $start_reserve_time = $end_reserve_time
	// WEEKLY | MONTHLY 일 경우에 시작 시간부터 끝나는 시간까지 발송되는 횟수 Ex) type = WEEKLY, start_reserve_time = '2017-05-17 13:00:00', end_reserve_time = '2017-05-24 13:00:00' 이면 remained_count = 2 로 되어야 합니다.
	$remained_count = 1;
	// 예약 수정/취소 API는 소스 하단을 참고 해주시기 바랍니다.

	//필수안내문구 추가
	$agreement_text = '본메일은 [$NOW_DATE] 기준, 회원님의 수신동의 여부를 확인한 결과 회원님께서 수신동의를 하셨기에 발송되었습니다.';
	$deny_text = "메일 수신을 원치 않으시면 [" . '$DENY_LINK' . "]를 클릭하세요.\\nIf you don't want this type of information or e-mail, please click the [".'$EN_DENY_LINK'."]";
	$sender_info_text = "사업자 등록번호:-- 소재지:ㅇㅇ시(도) ㅇㅇ구(군) ㅇㅇ동 ㅇㅇㅇ번지 TEL:--\\nEmail: <a href='mailto:test@directsend.co.kr'>test@directsend.co.kr</a>";
	$logo_state = 1; // logo 사용시 1 / 사용안할 시 0
	$logo_path = 'http://logoimage.com/image.png';  //사용하실 로고 이미지를 입력하시기 바랍니다.

	// 첨부파일의 URL을 보내면 DirectSend에서 파일을 download 받아 발송처리를 진행합니다. 첨부파일은 전체 10MB 이하로 발송을 해야 하며, 파일의 구분자는 '|(shift+\)'로 사용하며 5개까지만 첨부가 가능합니다.
	$file_url = 'https://directsend.co.kr/test.png|https://directsend.co.kr/test1.png';
	// 첨부파일의 이름을 지정할 수 있도록 합니다.
	// 첨부파일의 이름은 순차적(https://directsend.co.kr/test.png - image.png, https://directsend.co.kr/test1.png - image2.png) 와 같이 적용이 되며, file_name을 지정하지 않은 경우 마지막의 파일의 이름으로 메일에 보여집니다.
	$file_name = 'image.png|image2.png';

	/* 여기까지 수정해주시기 바랍니다. */

	$postvars = '"subject":"'.$subject.'"';
	$postvars = $postvars.', "body":"'.$body.'"';
	$postvars = $postvars.', "sender":"'.$sender.'"';
	$postvars = $postvars.', "sender_name":"'.$sender_name.'"';
	$postvars = $postvars.', "username":"'.$username.'"';
	$postvars = $postvars.', "receiver":'.$receiver;
	$postvars = $postvars.', "key":"'.$key.'"';
	$postvars = '{'.$postvars.'}';      //JSON 데이터

	// URL
	$url = "https://directsend.co.kr/index.php/api_v2/mail_change_word";

	//헤더정보
	$headers = array(
			"cache-control: no-cache",
			"content-type: application/json; charset=utf-8"
	);

	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);		//JSON 데이터
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
	curl_setopt($ch,CURLOPT_TIMEOUT, 20);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec($ch);
	//curl 에러 확인
	if(curl_errno($ch)){
		echo 'Curl error: ' . curl_error($ch);
	}else{
		//print_R($response);
		//echo "1";
	}

	curl_close ($ch);
}


function mailJoinFlyingSend($param,$email) {
	$ch = curl_init();

	/* 여기서부터 수정해주시기 바랍니다. */
	$subject = '[인슈플러스] [$NAME] 고객님 인슈플러스에 가입해 주셔서 감사합니다. ';   //필수입력
	//$body = '[$NAME]님 환영합니다. 치환 문자 입니다. 수신 이메일 : [$EMAIL] 수신번호 : [$MOBILE] 메모 : [$NOTE]';                 //필수입력
	// $sender = "help@insuplus.co.kr";         //필수입력 config.php
	// $sender_name = "인슈플러스";
	// $username = "kassist";                //필수입력
	// $key = "MHvEdNfJ0kZrp4b";           //필수입력

    //20250318 추가 신규
    $sender = "help@insuplus.co.kr";         //필수입력 config.php
	$sender_name = "인슈플러스";
	$username = "insuplus";                //필수입력
	$key = "KIsg5ekF3H0hUG2";           //필수입력


	$body = "<section class='insurplus_wrap' style='position:relative;text-align:center;width: 760px; background:#fff;padding:0px;margin:10px auto'>";
	$body .= "<div class='top_image'>";
	$body .= "<img src='http://www.insuplus.co.kr/html/images/sub-register-result-banner2.jpg' class='pc-image' /></div>";
	$body .= "<section class='insurplus_content' style='padding:38px;background-color: #fff;border-radius: 10px;border: solid 1px #e3e3e3;box-shadow: 0px 3px 5px 3px rgba(0,0,0,0.05);'>";
	$body .= "<h5 class='text-black title' style='color:#000; font-size:16px; color:#000;line-height:1.5; text-align:left;margin:0px 0px 16px 0'>가입정보</h5>";
	$body .= "<table class='table-insurplus' style='width: 100%;border-collapse: collapse;border-top:solid 2px #595959;border-bottom:solid 2px #595959;'><tbody>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>보험기간</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>#{period}</td>";
	$body .= "</tr>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>상품명</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>#{pr_name}</td>";
	$body .= "</tr>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>가입자</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>#{name}</td>";
	$body .= "</tr>";
	if(strtolower($param["payMethod"]) == "vbank") { //가상계좌
	$arr_pay_name = explode("/",$param["pay_name"]);
    $VACT_Num = $param["account"];
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>결제상태</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>입금전</td>";
	$body .= "</tr>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>가상계좌</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>".$arr_pay_name[1].":".$VACT_Num."</td>";
	$body .= "</tr>";
	} else {
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>결제상태</th>";
	$body .= "<td style='border-bottom:1px solid #d6d6d6;text-align:left;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;'>결제완료</td>";
	$body .= "</tr>";
	}
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>상품가</th>";
	$body .= "<td style='font-size:18px;padding:11px 15px;text-align:right; border-bottom:1px solid #d6d6d6; width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;''><span class='text-black' style='color:#000;'>#{amount}</span> <small style='font-size:14px'>원</small></td>";
	$body .= "</tr>";
	$body .= "<tr>";
	$body .= "<th style='border-bottom:1px solid #d6d6d6;font-weight:400;text-align:center;width:25%;background-color:#f6f6f6;'>결제금액</th>";
	$body .= "<td style='font-size:24px;padding:6px 15px;text-align:right;border-bottom:1px solid #d6d6d6;;width:auto;color:#333;padding:14.5px 15px;border-left:1px solid #d6d6d6;''><span class='text-danger' style='color:#dc3347;'>#{t_amount}</span> <small style='font-size:14px'>원</small></td>";
	$body .= "</tr>";
	$body .= "</tbody>";
	$body .= "</table>";
	$body .= "<div class='btn-group' style='margin: 16px auto; text-align:center;'>";
	if($param['ins_amount'] && $param['ins_seq'] > 0){
		$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=ins_agree&seq=#{ins_seq}' style='padding:12px 20px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>보험약관 자세히 보기</a>";
	} else {
		if($param['ins_term1'] > 0) {
			$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=ins_agree&seq=#{ins_term1}' style='padding:12px 20px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>보험약관 자세히 보기</a>";
		} 
		if($param['ins_term2'] > 0) {
			$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=ins_agree&seq=#{ins_term2}' style='padding:12px 20px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>보험약관 자세히 보기</a>";
		}
	}
	
	if($param['chk_service'] == "C") {
		$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=service_agree&seq=#{service_seq}' style='padding:12px 20px; margin-left:15px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>서비스 이용약관 자세히 보기</a>";
	} else {
		if($param["chk_service"] == "" && $param["service_seq"] > 0) {
			$body .= "<a href='#{domain}/html/insurance/emailFileDown.php?mode=down&bc_id=service_agree&seq=#{service_seq}' style='padding:12px 20px; display:inline-block; border:1px solid #bbb; border-radius:3px; color:#333; text-decoration:none;'>서비스 이용약관 자세히 보기</a>";
		} 
	}
	$body .= "</div>";
	$body .= "<p style='margin:10px 0 20px 0; color:#333; font-size:14px;'>자세한 보장 내역 확인 및 출발전 취소는 아래 가입 내역 조회 페이지를 클릭해 주세요.</p>";
	$body .= "<a href='#{domain}/html/join/join_confirm.php' target='_blank' style='padding:12px 40px; text-decoration:none; background-color:#29354c;border-color:#29354c;color:#fff;width:300px;border-radius:4px;'>가입내역 조회하기</a>";
	$body .= "<p style='color:#333; margin:30px 0 0 0;'><img src='http://www.insuplus.co.kr/html/images/ic-noti.svg' align='absmiddle' alt='' height='24' />&nbsp;상품 안내 및 보상신청은 <strong class='text-black' style='color:#000;'>24시간 알람센터 02-360-2545</strong> 또는  <strong class='text-black' style='color:#000;'><a href='https://pf.kakao.com/_JClxfT/chat' target='_blank' style='text-decoration:none; color:#000'>카카오톡 플러스 친구</a></strong> 문의해 주세요.</p>";
	$body .= "</section>";
	$body .= "</section>";


	$receiver = '{"name":"'.$param["name"].'","email":"'.$email.'"}';
	$receiver = '['.$receiver.']';      //JSON 데이터


	$body = str_replace("#{name}",$param['name'],$body);
	$body = str_replace("#{period}",$param['period'],$body);
	$body = str_replace("#{pr_name}",$param['pr_name'],$body);
	$body = str_replace("#{amount}",number_format($param['amount']),$body);
	$body = str_replace("#{t_amount}",number_format($param['t_amount']),$body);
	$body = str_replace("#{domain}",$param['domain'],$body);

	$body = str_replace("#{ins_seq}",$param['ins_seq'],$body);
	$body = str_replace("#{ins_term1}",$param['ins_term1'],$body);
	$body = str_replace("#{ins_term2}",$param['ins_term2'],$body);
	$body = str_replace("#{service_seq}",$param['service_seq'],$body);

	// 주소록을 사용하길 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 주소록 번호를 입력해주시기 바랍니다.
	//$address_books = "0,1,2";      //발송 할 주소록 번호 , 로 구분함 (ex. 0, 1, 2)

	$bodytag = '1';  //HTML이 기본값 입니다. 메일 내용을 텍스트로 보내실 경우 주석을 해제 해주시기 바랍니다.

	// 실제 발송성공실패 여부를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해주시기 바랍니다.
	$return_url = 0;
	//open, click 등의 결과를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해주시기 바랍니다.
	//등록된 도메인이 http://domain 와 같을 경우, http://domain?type=[click | open | reject]&mail_id=[MailID]&email=[Email] 과 같은 형식으로 request를 보내드립니다.
	$option_return_url = 0;

	$open = 1;	// open 결과를 받으려면 아래 주석을 해제 해주시기 바랍니다.
	$click = 1;	// click 결과를 받으려면 아래 주석을 해제 해주시기 바랍니다.
	$check_period = 3;	// 트래킹 기간을 지정하며 3 / 7 / 10 / 15 일을 기준으로 지정하여 발송해 주시기 바랍니다. (단, 지정을 하지 않을 경우 결과를 받을 수 없습니다.)

	// 예약발송 정보 추가
	$mail_type = 'NORMAL'; // NORMAL - 즉시발송 / ONETIME - 1회예약 / WEEKLY - 매주정기예약 / MONTHLY - 매월정기예약
	$start_reserve_time = date('Y-m-d H:i:s'); //  발송하고자 하는 시간(시,분단위까지만 가능) (동일한 예약 시간으로는 200건 이상 등록할 수 없습니다.)
	$end_reserve_time = date('Y-m-d H:i:s'); //  발송이 끝나는 시간 1회 예약일 경우 $start_reserve_time = $end_reserve_time
	// WEEKLY | MONTHLY 일 경우에 시작 시간부터 끝나는 시간까지 발송되는 횟수 Ex) type = WEEKLY, start_reserve_time = '2017-05-17 13:00:00', end_reserve_time = '2017-05-24 13:00:00' 이면 remained_count = 2 로 되어야 합니다.
	$remained_count = 1;
	// 예약 수정/취소 API는 소스 하단을 참고 해주시기 바랍니다.

	//필수안내문구 추가
	$agreement_text = '본메일은 [$NOW_DATE] 기준, 회원님의 수신동의 여부를 확인한 결과 회원님께서 수신동의를 하셨기에 발송되었습니다.';
	$deny_text = "메일 수신을 원치 않으시면 [" . '$DENY_LINK' . "]를 클릭하세요.\\nIf you don't want this type of information or e-mail, please click the [".'$EN_DENY_LINK'."]";
	$sender_info_text = "사업자 등록번호:-- 소재지:ㅇㅇ시(도) ㅇㅇ구(군) ㅇㅇ동 ㅇㅇㅇ번지 TEL:--\\nEmail: <a href='mailto:test@directsend.co.kr'>test@directsend.co.kr</a>";
	$logo_state = 1; // logo 사용시 1 / 사용안할 시 0
	$logo_path = 'http://logoimage.com/image.png';  //사용하실 로고 이미지를 입력하시기 바랍니다.

	// 첨부파일의 URL을 보내면 DirectSend에서 파일을 download 받아 발송처리를 진행합니다. 첨부파일은 전체 10MB 이하로 발송을 해야 하며, 파일의 구분자는 '|(shift+\)'로 사용하며 5개까지만 첨부가 가능합니다.
	$file_url = 'https://directsend.co.kr/test.png|https://directsend.co.kr/test1.png';
	// 첨부파일의 이름을 지정할 수 있도록 합니다.
	// 첨부파일의 이름은 순차적(https://directsend.co.kr/test.png - image.png, https://directsend.co.kr/test1.png - image2.png) 와 같이 적용이 되며, file_name을 지정하지 않은 경우 마지막의 파일의 이름으로 메일에 보여집니다.
	$file_name = 'image.png|image2.png';

	/* 여기까지 수정해주시기 바랍니다. */

	$postvars = '"subject":"'.$subject.'"';
	$postvars = $postvars.', "body":"'.$body.'"';
	$postvars = $postvars.', "sender":"'.$sender.'"';
	$postvars = $postvars.', "sender_name":"'.$sender_name.'"';
	$postvars = $postvars.', "username":"'.$username.'"';
	$postvars = $postvars.', "receiver":'.$receiver;
	$postvars = $postvars.', "key":"'.$key.'"';
	$postvars = '{'.$postvars.'}';      //JSON 데이터

	// URL
	$url = "https://directsend.co.kr/index.php/api_v2/mail_change_word";

	//헤더정보
	$headers = array(
			"cache-control: no-cache",
			"content-type: application/json; charset=utf-8"
	);

	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);		//JSON 데이터
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
	curl_setopt($ch,CURLOPT_TIMEOUT, 20);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec($ch);
	//curl 에러 확인
	if(curl_errno($ch)){
		echo 'Curl error: ' . curl_error($ch);
	}else{
		//print_R($response);
		//echo "1";
	}

	curl_close ($ch);
}

function mailRestrictedUsersSend($param,$email) {
	$ch = curl_init();

	/* 여기서부터 수정해주시기 바랍니다. */
	$subject = '가입제한대상자 [$NAME] 가입시도';   //필수입력
	// $sender = "help@insuplus.co.kr";         //필수입력 config.php
	// $sender_name = "인슈플러스";
	// $username = "kassist";                //필수입력
	// $key = "MHvEdNfJ0kZrp4b";           //필수입력

    
    //20250318 추가 신규
    $sender = "help@insuplus.co.kr";         //필수입력 config.php
	$sender_name = "인슈플러스";
	$username = "insuplus";                //필수입력
	$key = "KIsg5ekF3H0hUG2";           //필수입력

	$body = "<section class='insurplus_wrap' style='position:relative;text-align:center;width: 400px; background:#fff;padding:0px;margin:10px auto'>";
	$body .= "<p style='margin:10px 0 20px 10px; color:#333; font-size:14px; text-align:left;'>";
	$body .= "#{name}<br/>";
	$body .= "#{o_isdn}<br/>";
	$body .= "#{product_name}<br/>";
	$body .= "#{attempt_date}<br/>";
	$body .= "</p>";
	$body .= "</section>";

	$receiver = '{"name":"'.$param["name"].'","email":"'.$email.'"}';
	$receiver = '['.$receiver.']';      //JSON 데이터

	$body = str_replace("#{name}",$param['name'],$body);
	$body = str_replace("#{o_isdn}",$param['o_isdn'],$body);
	$body = str_replace("#{product_name}",$param['product_name'],$body);
	$body = str_replace("#{attempt_date}",$param['attempt_date'],$body);

	// 주소록을 사용하길 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 주소록 번호를 입력해주시기 바랍니다.
	//$address_books = "0,1,2";      //발송 할 주소록 번호 , 로 구분함 (ex. 0, 1, 2)

	$bodytag = '1';  //HTML이 기본값 입니다. 메일 내용을 텍스트로 보내실 경우 주석을 해제 해주시기 바랍니다.

	// 실제 발송성공실패 여부를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해주시기 바랍니다.
	$return_url = 0;
	//open, click 등의 결과를 받기 원하실 경우 아래 주석을 해제하신 후, 사이트에 등록한 URL 번호를 입력해주시기 바랍니다.
	//등록된 도메인이 http://domain 와 같을 경우, http://domain?type=[click | open | reject]&mail_id=[MailID]&email=[Email] 과 같은 형식으로 request를 보내드립니다.
	$option_return_url = 0;

	$open = 1;	// open 결과를 받으려면 아래 주석을 해제 해주시기 바랍니다.
	$click = 1;	// click 결과를 받으려면 아래 주석을 해제 해주시기 바랍니다.
	$check_period = 3;	// 트래킹 기간을 지정하며 3 / 7 / 10 / 15 일을 기준으로 지정하여 발송해 주시기 바랍니다. (단, 지정을 하지 않을 경우 결과를 받을 수 없습니다.)

	// 예약발송 정보 추가
	$mail_type = 'NORMAL'; // NORMAL - 즉시발송 / ONETIME - 1회예약 / WEEKLY - 매주정기예약 / MONTHLY - 매월정기예약
	$start_reserve_time = date('Y-m-d H:i:s'); //  발송하고자 하는 시간(시,분단위까지만 가능) (동일한 예약 시간으로는 200건 이상 등록할 수 없습니다.)
	$end_reserve_time = date('Y-m-d H:i:s'); //  발송이 끝나는 시간 1회 예약일 경우 $start_reserve_time = $end_reserve_time
	// WEEKLY | MONTHLY 일 경우에 시작 시간부터 끝나는 시간까지 발송되는 횟수 Ex) type = WEEKLY, start_reserve_time = '2017-05-17 13:00:00', end_reserve_time = '2017-05-24 13:00:00' 이면 remained_count = 2 로 되어야 합니다.
	$remained_count = 1;
	// 예약 수정/취소 API는 소스 하단을 참고 해주시기 바랍니다.

	//필수안내문구 추가
	$agreement_text = '본메일은 [$NOW_DATE] 기준, 회원님의 수신동의 여부를 확인한 결과 회원님께서 수신동의를 하셨기에 발송되었습니다.';
	$deny_text = "메일 수신을 원치 않으시면 [" . '$DENY_LINK' . "]를 클릭하세요.\\nIf you don't want this type of information or e-mail, please click the [".'$EN_DENY_LINK'."]";
	$sender_info_text = "사업자 등록번호:-- 소재지:ㅇㅇ시(도) ㅇㅇ구(군) ㅇㅇ동 ㅇㅇㅇ번지 TEL:--\\nEmail: <a href='mailto:test@directsend.co.kr'>test@directsend.co.kr</a>";
	$logo_state = 1; // logo 사용시 1 / 사용안할 시 0
	$logo_path = 'http://logoimage.com/image.png';  //사용하실 로고 이미지를 입력하시기 바랍니다.

	// 첨부파일의 URL을 보내면 DirectSend에서 파일을 download 받아 발송처리를 진행합니다. 첨부파일은 전체 10MB 이하로 발송을 해야 하며, 파일의 구분자는 '|(shift+\)'로 사용하며 5개까지만 첨부가 가능합니다.
	$file_url = 'https://directsend.co.kr/test.png|https://directsend.co.kr/test1.png';
	// 첨부파일의 이름을 지정할 수 있도록 합니다.
	// 첨부파일의 이름은 순차적(https://directsend.co.kr/test.png - image.png, https://directsend.co.kr/test1.png - image2.png) 와 같이 적용이 되며, file_name을 지정하지 않은 경우 마지막의 파일의 이름으로 메일에 보여집니다.
	$file_name = 'image.png|image2.png';

	/* 여기까지 수정해주시기 바랍니다. */

	$postvars = '"subject":"'.$subject.'"';
	$postvars = $postvars.', "body":"'.$body.'"';
	$postvars = $postvars.', "sender":"'.$sender.'"';
	$postvars = $postvars.', "sender_name":"'.$sender_name.'"';
	$postvars = $postvars.', "username":"'.$username.'"';
	$postvars = $postvars.', "receiver":'.$receiver;
	$postvars = $postvars.', "key":"'.$key.'"';
	$postvars = '{'.$postvars.'}';      //JSON 데이터

	// URL
	$url = "https://directsend.co.kr/index.php/api_v2/mail_change_word";

	//헤더정보
	$headers = array(
			"cache-control: no-cache",
			"content-type: application/json; charset=utf-8"
	);

	curl_setopt($ch,CURLOPT_URL, $url);
	curl_setopt($ch,CURLOPT_POST, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);		//JSON 데이터
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
	curl_setopt($ch,CURLOPT_TIMEOUT, 20);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec($ch);
	//curl 에러 확인
	if(curl_errno($ch)){
		echo 'Curl error: ' . curl_error($ch);
	}else{
		// print_R($response);
		//echo "1";
	}

	curl_close ($ch);
}
?>
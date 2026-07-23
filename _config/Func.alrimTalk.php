<?
//메세지 알림톡 수정 시 드림톡에 등록된 문구도 같이 수정해야 합니다.

//가입입금안내
function kakaoJoinBankInfo($param, $mobile) {
$templet_code = "024";	
$message = $param["name"]." 고객님 인슈플러스에 가입 신청해주셔서 감사합니다. 입금하실 가상계좌 번호 안내해 드립니다.

■ 은행 : ".$param["bank"]."
■ 계좌번호 : ".$param["account"]."
■ 입금 금액 : ".number_format($param["t_amount"])."

가입하신 상품은 위 금액을 가상계좌로 입금하셔야 가입이 완료됩니다. 가입 확인까지는 10분 정도 소요되며 완료 후 가입완료 메시지가 발송됩니다.

☎ 결제 문의 (평일 09-18시)
02-360-2545";

	kakaoSend($message, $templet_code, $mobile);
	
}

//감사 일반보험 알림톡
function kakaoJoin($param, $mobile)
{

	$templet_code = "015";

	$message = $param["name"]." 고객님 인슈플러스에 가입해 주셔서 감사합니다. 가입하신 상품 안내 드립니다.

■ 가입자명 : " . $param["name"] . "
■ 상품명 : " . $param["pr_name"] . "
■ 가입 기간 : " . $param["period"] . "
■ 결제 금액 : " . number_format($param["t_amount"]) . "원
■ 원격진료: " . $param["telemedic"] . "
  -  https://insuplus.co.kr/html/customer/telemedic.php

자세한 보장 내역 확인 및 출발전 취소는 가입내역 조회를 클릭해 주세요.

☎  24시간 알람센터 전화
02-360-2545";

	kakaoSend($message, $templet_code, $mobile);
}

//감사 인슈플러스 보험 알림톡
function kakaoInsuplusJoin($param, $mobile) {

	$templet_code = "004";

	$message = $param["name"]." 고객님 인슈플러스를 가입해 주셔서 감사합니다. 가입하신 상품 안내 드립니다.

■ 가입자명 : ".$param["name"]."
■ 상품명 : ".$param["pr_name"]."
■ 가입 기간 : ".$param["period"]."
■ 결제 금액 : ".number_format($param["t_amount"])."원

병원예약, 의료상담, 보험청구, 긴급이후송 등 모든 서비스는 24시간 알람센터 카카오톡 채널 또는 전화로 요청하실 수 있습니다.
고객님의 행복한 여행을 위해 인슈플러스가 항상 함께하겠습니다.

☎ 24시간 알람센터
02-360-2545

■ 카카오톡 채널 추가하시면 친구 전용 할인혜택을 받으실 수 있습니다.

※ 가입증명서(국문, 영문) 다운로드 및 취소는 증명서발급 페이지를 클릭해 주세요. 취소는 출국 전일까지 가능합니다.";

	kakaoSend($message, $templet_code, $mobile);

}
/*
function kakaoInsuplusJoin($param, $mobile) {

	$templet_code = "005";

	$message = $param["name"]." 고객님 인슈플러스를 가입해 주셔서 감사합니다. 가입하신 상품 안내 드립니다.

■ 가입자명 : ".$param["name"]."
■ 상품명 : ".$param["pr_name"]."
■ 가입 기간 : ".$param["period"]."
■ 결제 금액 : ".number_format($param["t_amount"])."원

병원예약, 의료상담, 보험청구, 긴급이후송 등 모든 서비스는 24시간 알람센터 카카오톡 채널 또는 전화로 요청하실 수 있습니다.
고객님의 행복한 여행을 위해 인슈플러스가 항상 함께하겠습니다.

☎ 24시간 알람센터
02-360-2545

■ 카카오톡 채널 추가하시면 친구 전용 할인혜택을 받으실 수 있습니다.

■ 제휴 혜택 안내 (더 라운지)
전세계 공항 라운지, 공항리무진 등 프리미엄 서비스를 인슈플러스 혜택 가격으로 이용하실 수 있습니다.
▷ 할인 혜택 받기 : https://vvd.bz/BO0

■ 요즘 대세, 인플인증 EVENT
인슈플러스 가입인증 챌린지 참여하면 100% 네이버페이 포인트 지급!
최대 38,000 포인트가 걸린 우수리뷰어에도 도전해 보세요.
▷ 이벤트 확인 하기 : https://vvd.bz/BNy

※ 가입증명서(국문, 영문) 다운로드 및 취소는 증명서발급 페이지를 클릭해 주세요. 취소는 출국 전일까지 가능합니다.";

	kakaoSend($message, $templet_code, $mobile);

}
    */
 
 

//감사 알림톡 
function kakaoQnaAnswer($param, $mobile) {

$templet_code = "060";

$message = $param["name"]." 고객님. 인슈플러스 1:1 문의에 답변이 등록되었습니다. 
확인해 주세요. 감사합니다.

☎ 고객센터 전화
02-360-2545";

	kakaoSend($message, $templet_code, $mobile);
	
	$send_log = array();
	$send_log["manager_yn"] = "Y";
	$send_log["gubun"] = "A";
	$send_log["memo"] = "문의사항 답변";
	$send_log["mobile"] = all_seed_enc($mobile);
	insSendLog($send_log);

}

//가입 쿠폰 발행 알림톡
function kakaoJoinCoupon($param, $mobile) {

	$templet_code = "053";
	
	$message = "24시간 해외여행안심 서비스 인슈플러스 쿠폰이 발행되었습니다. 본 쿠폰은 모든 상품 가입시 사용하실 수 있으며, 휴대폰번호를 입력하여 친구에게 선물하실 수 있습니다.

■ 쿠폰명 : ".$param["coupon_name"]."
■ 할인율 : ".$param["discount"]."
■ 사용기간 : ".$param["coupon_period"]."

* 쿠폰 사용 안내
- 쿠폰은 모든 상품 가입시 사용하실 수 있습니다.
- 가입시 고객님의 휴대폰 번호를 입력하시면 할인정보에서 사용 가능한 쿠폰을 조회해서 사용하실 수 있습니다. 
- 쿠폰내역에서 휴대폰번호를 입력하시면 받은 쿠폰을 친구에게 선물하실 수 있습니다.
- 쿠폰은 사용기간 내에 결제시 사용하실 수 있으며 미사용 시 소멸됩니다.
- 다인가입시 총 결제금액에서 할인 적용됩니다.
- 쿠폰은 가입취소시 재사용 가능합니다.

☎ 쿠폰 사용 및 가입 문의
02-360-2545

※ 이 메시지는 고객님의 참여로 지급된 쿠폰 안내 메세지입니다.";
 //20250819 가입 감사 쿠폰 알림톡 비활성화
	// kakaoSend($message, $templet_code, $mobile);

}


//쿠폰  다운로드 알림톡
function kakaoCouponDown($param, $mobile) {

	$templet_code = "054";
	
	$message = "24시간 해외여행안심 서비스 인슈플러스 쿠폰이 발행되었습니다. 본 쿠폰은 모든 상품 가입 시 사용하실 수 있으며, 휴대폰번호를 입력하여 친구에게 선물하실 수 있습니다.

■ 쿠폰명 : ".$param["coupon_name"]."
■ 혜택 : ".$param["discount_txt"]." 할인
■ 사용기간 : ".$param["period"]."

* 쿠폰 사용 안내
- 쿠폰은 모든 상품 가입 시 사용하실 수 있습니다.
- 가입 시 고객님의 휴대폰 번호를 입력하시면 할인정보에서 사용 가능한 쿠폰을 조회해서 사용하실 수 있습니다. 
- 쿠폰내역에서 휴대폰번호를 입력하시면 받은 쿠폰을 친구에게 선물하실 수 있습니다.
- 쿠폰은 사용기간 내에 결제 시 사용하실 수 있으며 미사용 시 소멸됩니다.
- 다인 가입 시 총 결제금액에서 할인 적용됩니다.
- 쿠폰은 가입취소 시 재사용 가능합니다.

☎ 쿠폰 사용 및 가입 문의 (평일 09-18시)
02-360-2545

※ 이 메시지는 고객님의 참여로 지급된 쿠폰 안내 메세지입니다.";

	kakaoSend($message, $templet_code, $mobile);

}

//가입증명서 재발행
function kakaoCertificateReissue($param, $mobile) {

	$templet_code = "040";
	$message = $param["name"]." 고객님 인슈플러스 보험 가입 안내 드립니다. 요청하신 가입 증명서는 가입 증명서 조회를 클릭하시면 다운로드 받으실 수 있습니다.

■ 피보험자명 : ".$param["name"]."
■ 보험상품명 : ".$param["pr_name"]."
■ 보험 가입 기간 : ".$param["period"]."
■ 출국 목적 :  ".$param["purpose"]."
■ 보험료 : ".number_format($param["amount"])."원
■ 결제 금액 : ".number_format($param["t_amount"])."원

가입증명서는 인슈플러스 홈페이지 가입내역 조회에서도 확인하실 수 있습니다.

☎ 가입 및 취소 문의
02-360-2545";


	kakaoSend($message, $templet_code, $mobile);
	
	$send_log = array();
	$send_log["manager_yn"] = "Y";
	$send_log["gubun"] = "A";
	$send_log["memo"] = "가입증명서 재발행";
	$send_log["orderno"] = $param["orderno"];
	insSendLog($send_log);

}

//가입취소
function kakaoJoinCancel($param, $mobile) {

	$templet_code = "032";
	$message = $param["name"]." 고객님 인슈플러스 가입 취소 안내 드립니다.

■ 가입자명 : ".$param["name"]."
■ 상품명 : ".$param["pr_name"]."
■ 가입 기간 : ".$param["period"]."
■ 취소일 : ".$param["cancle_date"]."
■ 환불 금액 : ".$param["cancle_amount"]."

※ 카드취소 내역은 영업일 기준 3~5일 이후 카드사를 통해 확인 가능합니다, 
※ 가상계좌로 결제하신 경우 영업일 3일 이후 환불요청하신 계좌로 입금됩니다. 

☎ 가입 및 취소 문의 (평일 09-18시)
02-360-2545";

	kakaoSend($message, $templet_code, $mobile);

}

//쿠폰친구발송 선물
//다른 친구에게 보내는건 문자서비스로 전달 받음
function kakaoSendFriend($param, $mobile) {

	$templet_code = "";

	$message = "선물 쿠폰 도착 안내해 드립니다. ".$param["name"]."님이 해외갈 때 드는 보험 인슈플러스 ".$param["discount"]."% 할인 쿠폰을 선물하셨습니다. 

■ 할인율 : ".$param["discount_txt"]."
■ 사용기간 : ".$param["coupon_period"]."
■ 쿠폰명 : ".$param["coupon_name"]."

* 쿠폰 사용 안내
- 쿠폰은 모든 보험 가입시 적용됩니다.
- 보험 가입시 고객님의 휴대폰 번호를 입력하시면 자동으로 할인 적용됩니다.
- 다인가입시 총 결제금액에서 할인 적용됩니다.
- 쿠폰은 가입취소시 재사용 가능합니다.

☎ 쿠폰 사용 및 가입 문의
02-360-2545";

	kakaoSend($message, $templet_code, $mobile);

}

//보험만료 안내 알림톡 발송
function kakaoSendReInsReg($param, $mobile) {

	$templet_code = "70";

	$message = "안녕하세요? ".$param["name"]."고객님. 인슈플러스를 이용해 주셔서 감사합니다. 고객님이 가입하신 보험이 ".$param["eDate"]."에 만료되어 사전 안내드립니다. 연장계약이 필요하신 경우 만료 전에 카카오 채널로 연락 주시기 바랍니다.

■ 보험 가입 기간 : ".$param["sDate"]." ~ ".$param["eDate"]." 
■ 보험상품명 : ".$param["prName"]." 

☎ 전화문의 : 02-360-2545-1번";

	kakaoSend($message, $templet_code, $mobile);

}

//제휴사 등업코드 발송 알림톡
function kakaoPromotionSend($param, $mobile) {

	$templet_code = "019";
	
	$message = $param["name"]." 고객님 인슈플러스에 가입해 주셔서 감사합니다. 가입하신 상품 안내 드립니다.

■ 가입자명 : ".$param["name"]."
■ 상품명 : ".$param["pr_name"]."
■ 가입 기간 : ".$param["period"]."
■ 결제 금액 : ".number_format($param["t_amount"])."

병원예약, 원격화상진료, 의료상담, 보험청구 등 모든 서비스는 24시간 알람센터 카카오 채널 또는 전화로 요청하실 수 있습니다.
고객님의 행복한 여행을 위해 인슈플러스가 항상 함께하겠습니다. 

☎  24시간 알람센터 전화
02-360-2545

※ 가입증명서(국문, 영문) 다운로드 및 취소는 증명서발급 페이지를 클릭해 주세요. 취소는 출국 전일까지 가능합니다.

[프로모션 혜택]
★ 롯데면세점 등업 코드 : ".$param["promotion"]."
롯데면세점 사이트에 코드를 등록해 주세요. 온라인 STAR(최대 20% 할인, 1년) 회원으로 등업됩니다.
▷ 등업하기 : https://m.kor.lottedfs.com/kr/event/eventDetail?evtDispNo=1040344";

	kakaoSend($message, $templet_code, $mobile);

}

function kakaoInsuplusCertificationNumberSend($param,$mobile){

 
    $templet_code = "091";
	
	$message = "[인슈플러스]
본인확인 인증번호는 [".$param."] 입니다."; 

	kakaoSend($message, $templet_code, $mobile);
    echo $param; 
}

//알림톡 전송
function kakaoSend($message, $templet_code, $mobile) {
	global $dbcon;
	$lms_message = $message; 
	
	if(!$templet_code) { //템플릿 코드가 없는 경우 문자 발송
		$message = "";
	}
	//1910034757
	//2600113933
	$sql = " INSERT INTO TSMS_AGENT_MESSAGE (
				SERVICE_SEQNO, SEND_MESSAGE, SUBJECT, BACKUP_MESSAGE, BACKUP_PROCESS_CODE
				, MESSAGE_TYPE, CONTENTS_TYPE, RECEIVE_MOBILE_NO, CALLBACK_NO, JOB_TYPE
				, SEND_RESERVE_DATE, TEMPLATE_CODE, REGISTER_DATE, REGISTER_BY, IMG_ATTACH_FLAG
				, KKO_BTN_NAME, KKO_BTN_URL, KKO_BTN_LINK1, KKO_BTN_LINK2, KKO_BTN_LINK3
				, KKO_BTN_LINK4, KKO_BTN_LINK5
			) VALUES (
				1910034757,
				'".$message."',
				'INSUPLUS',
				'".$lms_message."',
				'001',
				'002',
				'004',
				'".$mobile."',
				'023602545',
				'R00',
				now(),
				'".$templet_code."',
				now(),
				'admin',
				'N',
				'', 
				'',
				'',
				'',
				'',
				'',
				''
		)";
	// echo $sql;
	// exit;
	$dbcon->query($sql);
}

//알림톡 로그
function insSendLog($send_log) {
	global $dbcon;
	$sql =  " INSERT INTO tbl_send_log (manager_yn, orderno, mobile , gubun, memo, regdate) VALUES ";
	$sql .= " ('".$send_log["manager_yn"]."','".$send_log["orderno"]."' ,'".$send_log["mobile"]."','".$send_log["gubun"]."','".$send_log["memo"]."',now()) ";
	
	$dbcon->query($sql);
}
 
?>
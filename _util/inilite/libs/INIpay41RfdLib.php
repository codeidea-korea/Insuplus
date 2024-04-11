<?php


/****************************************************************************************
 **** 지불수단별로 PGID를 다르게 표시한다 (2003.12.19 대리 이종완) ****
 ****************************************************************************************
 *** 하단의 PGID 부분은 지불수단별로 TID를 별도로 표시하도록 하며,  ***
 *** 임의로 수정하는 경우 지불 실패가 발생 될수 있으므로 절대로 수정  ***
 *** 하지 않도록 하시기 바랍니다.     *********************************************
 *** 임의로 수정하여 발생된 문제에 대해서는 (주)이니시스에 책임이    *****
 *** 없으니 주의 하시기 바랍니다.      ********************************************
 ***************************************************************************************/
extract($_POST);
extract($_GET);
/*************************************************************************************
 *************************************************************************************
   ********************        상기부분 절대 수정 불가      ************************
 *************************************************************************************
 *************************************************************************************/

class INIpay41
{
	var $m_inipayHome; 		//이니페이 홈디렉터리
	var $m_test; 			// "true"면 17번으로 보낸다
	var $m_debug; 			// "true"면 상세한 로그를 남긴다
	var $m_type; 			// 거래 유형
	var $m_pgId; 			// PGID
	var $m_keyPw; 			// keypass.enc의 pass phrase
	var $m_subPgIp; 		// 3번째 예비 PG IP Addr
	var $m_mid; 			// 상점 아이디
	var $m_tid; 			// 거래아이디
	var $m_goodName; 		// 상품명
	var $m_currency; 		// 화폐단위 (WON, USD)
	var $m_price; 			// 금액
	var $m_confirm_price;		// 재승인 요청 금액
	var $m_payMethod; 		// 지불방법
	var $m_merchantReserved1; 	// 예비필드 (지불)
	var $m_merchantReserved2; 	// 예비필드 (지불)
	var $m_merchantReserved3; 	// 예비필드 (지불)
	var $m_uip; 			// 지불인 PC IP Addr
	var $m_url; 			// 지불 상점 URL
	var $m_encrypted; 		// 암호문 (대칭키로 암호화된 PLAIN TEXT)
	var $m_sessionKey; 		// 암호문 (공개키로 암호화된 대칭키)
	var $m_uid; 			// INIpay User ID (2002/07 현재 사용안함)
	var $m_bankCode; 		// 은행코드
	var $m_merchantReserved; 	// 예비필드 (비지불)
	var $m_cancelMsg; 		// 취소 사유
	var $m_resultCode; 		// 결과 코드 (2 digit)
	var $m_resultMsg; 		// 결과 내용
	var $m_pgCancelDate; 		// PG 취소 날짜
	var $m_pgCancelTime; 		// PG 취소 시각
	var $m_requestMsg; 		// 보낼 메시지
	var $m_responseMsg; 		// 받은 메시지
	var $m_resulterrcode; 		// 결과메세지 에러코드
	var $m_resultprice; 		// 결제 완료 금액
	var $m_rcash_cancel_noappl;

/* ==  가상계좌 환불(2009.08.06 이승국) == */
	var $m_racctnum;
	var $m_rbankcode;
	var $m_racctname;

	function startAction()
	{
		switch($this->m_type)
		{

			case("refund") :
				$this->m_requestMsg =
					"inipayhome=" . $this->m_inipayHome . "\x0B" .
					"pgid=" . $this->m_pgId . "\x0B" .
					"spgip=" . $this->m_subPgIp . "\x0B" .
					"admin=" . $this->m_keyPw . "\x0B" .
					"debug=" . $this->m_debug . "\x0B" .
					"test=" . $this->m_test . "\x0B" .
					"mid=" . $this->m_mid . "\x0B" .
					"tid=" . $this->m_tid . "\x0B" .
					"msg=" . $this->m_cancelMsg . "\x0B" .
					"uip=" . $this->m_uip . "\x0B" .
					"racctnum=" . $this->m_racctnum . "\x0B" .
					"rbankcode=" . $this->m_rbankcode . "\x0B" .
					"racctname=" . $this->m_racctname . "\x0B" .
					"merchantreserved=" . $this->m_merchantReserved;
				$this->m_responseMsg = exec($this->m_inipayHome . "/phpexec/INIrefund.phpexec \"" . $this->m_requestMsg . "\"");
				if(strlen($this->m_responseMsg) <= 1)
					$this->m_responseMsg = "ResultCode=01&ResultMsg=[9199]INVOKE ERR : " . $this->m_inipayHome . "/phpexec/INIrefund.phpexec";
				break;

			default :
				$this->m_responseMsg = "ResultCode=01&ResultMsg=처리할 수 없는 거래유형입니다 : " . $this->m_type;
		}

		parse_str($this->m_responseMsg);
		$this->m_resultCode = $ResultCode;
		$this->m_resultMsg = $ResultMsg;
    $this->m_pgCancelDate = $PGcanceldate;
    $this->m_pgCancelTime = $PGcanceltime;


/* == 현금영수증 취소 승인 번호 리턴 == */
		$this->m_rcash_cancel_noappl = $Rcash_cancel_noappl;



/* == 결과메세지 ($m_resultMsg)에서 에러코드 추출 == */
		$str = $ResultMsg ;
		$arr = explode("\]+", $str);
		$this->m_resulterrcode = substr($arr[0],1);	// []안의 코드만 표시

	}
}

?>

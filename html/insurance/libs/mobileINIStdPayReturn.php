<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.mail.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php";
require("./INImx.php");
$inimx = new INImx;

    $P_TID = $_REQUEST["P_TID"];  // 거래번호
    $P_MID = $_REQUEST["P_MID"];  // 상점아이디
    $P_AUTH_DT = $_REQUEST["P_AUTH_DT"];  // 승인일자
    $P_STATUS = $_REQUEST["P_STATUS"];    // 거래상태 (00:성공, 01:실패)
    $P_TYPE = $_REQUEST["P_TYPE"];    // 지불수단
    $P_OID = $_REQUEST["P_OID"];  // 상점주문번호
    $P_FN_CD1 = $_REQUEST["P_FN_CD1"];    // 금융사코드1
    $P_FN_CD2 = $_REQUEST["P_FN_CD2"];    // 금융사코드2
    $P_FN_NM = $_REQUEST["P_FN_NM"];   // 금융사명 (은행명, 카드사명, 이통사명)
    $P_AMT = $_REQUEST["P_AMT"];  // 거래금액
    $P_UNAME = $_REQUEST["P_UNAME"];   // 결제고객성명
    $P_RMESG1 = $_REQUEST["P_RMESG1"]; // 결과코드
    $P_RMESG2 = $_REQUEST["P_RMESG2"]; // 결과메시지
    $P_NOTI = $_REQUEST["P_NOTI"];    // 노티메시지(상점에서 올린 메시지)
    $P_AUTH_NO = $_REQUEST["P_AUTH_NO"];  // 승인번호
	$OrderNumber			= $P_OID;

	/////////////////////////////////////////////////////////////////////////////
	///// 1. 변수 초기화 및 POST 인증값 받음                                 ////
	/////////////////////////////////////////////////////////////////////////////

	$inimx->reqtype    = "PAY";  //결제요청방식
	$inimx->inipayhome = $_SERVER["DOCUMENT_ROOT"]."/log/pg"; //로그기록 경로 (이 위치의 하위폴더에 log폴더 생성 후 log폴더에 대해 777 권한 설정)
	$inimx->status     = $P_STATUS;
	$inimx->rmesg1     = $P_RMESG1;
	$inimx->tid        = $P_TID;
	$inimx->req_url    = $P_REQ_URL;
	$inimx->noti       = $P_NOTI;
	/////////////////////////////////////////////////////////////////////////////
	///// 2. 상점 아이디 설정 :                                              ////
	/////    결제요청 페이지에서 사용한 MID값과 동일하게 세팅해야 함...      ////
	/////    인증TID를 잘라서 사용가능 : substr($P_TID,'10','10');           ////
	/////////////////////////////////////////////////////////////////////////////
	$inimx->id_merchant = substr($P_TID,'10','10');  //
	$log = array('LEVEL' => '1ST', 'orderno' => $OrderNumber, 'INIMX' => $inimx);
	echo $inimx->status." 현재상태<br>";



	if($inimx->status =="00")
    {
		$inimx->startAction();  // 승인요청
		$inimx->getResult();  //승인결과 파싱, P_REQ_URL에서 내려준 결과값 파싱
		$log = array('LEVEL' => '2ND', 'orderno' => $OrderNumber, 'INIMX' => $inimx);
		$OrderNumber = $inimx->m_moid;
		if ( $inimx->m_resultCode !== "00" )
		{
			$isDBOK = false;
			ERROR_BACK("거래가 실패했습니다. 오류코드 : ".$inimx->m_resultCode." 오류내용 : ". $inimx->m_resultMsg . "");
			exit;
		}
  /**
  결과값 파싱 전문은 INImx내 변수로 담아 표현하고 있습니다. ( 메뉴얼얼내 값 대조하여 필요한 값 저장할 수 있도록 부탁드립니다.)

      --공통
			$this->m_tid  = $resultString['P_TID'];                                     // 거래번호
			$this->m_resultCode = $resultString['P_STATUS'];                            // 거래상태 - 지불결과 성공:00, 실패:00 이외 실패
			$this->m_resultMsg  = $resultString['P_RMESG1'];                            // 지불 결과 메시지
			$this->m_cardQuota  = $resultString['P_RMESG2'];                            // 신용카드 할부 개월 수 (메뉴얼 확인 필요)
			$this->m_payMethod = $resultString['P_TYPE'];                               // 지불수단
			$this->m_mid  = $resultString['P_MID'];                                     // 상점아이디
			$this->m_moid  = $resultString['P_OID'];                                    // 상점주문번호
			$this->m_resultprice = $resultString['P_AMT'];                              // 거래금액
			$this->m_buyerName  = $resultString['P_UNAME'];                             // 구매자명
			$this->m_nextUrl  = $resultString['P_NEXT_URL'];                            // 가맹점 전달 P_NEXT_URL
			$this->m_notiUrl  = $resultString['P_NOTEURL'];                             // 가맹점 전달 NOTE_URL --->>이거도 설명 에매하네
			$this->m_authdt  = $resultString['P_AUTH_DT'];                              // 승인일자(YYYYmmddHHmmss)
			$this->m_pgAuthDate  = substr($resultString['P_AUTH_DT'],'0','8');
			$this->m_pgAuthTime  = substr($resultString['P_AUTH_DT'],'8','6');
			$this->m_mname  = $resultString['P_MNAME'];                                 // 가맹점명
			$this->m_noti  = $resultString['P_NOTI'];                                   // 기타주문정보
			$this->m_authCode = $resultString['P_AUTH_NO'];                             // 신용카드 승인번호 - 신용카드 거래에서만 사용
			$this->m_cardCode = $resultString['P_FN_CD1'];                              // 카드코드


			--신용카드
			$this->m_cardIssuerCode = $resultString['P_CARD_ISSUER_CODE'];              // 발급사 코드
			$this->m_cardNum  = $resultString['P_CARD_NUM'];                            // 카드번호
			$this->m_cardMumbernum  = $resultString['P_CARD_MEMBER_NUM'];               // 가맹점번호
			$this->m_cardpurchase  = $resultString['P_CARD_PURCHASE_CODE'];             // 매입사 코드
			$this->m_prtc  = $resultString['P_CARD_PRTC_CODE'];                         // 부분취소 가능 여부
			$this->m_cardinterest  = $resultString['P_CARD_INTEREST'];                  // 무이자 할부여부 (일반 : 0, 무이자 : 1)
			$this->m_cardcheckflag  = $resultString['P_CARD_CHECKFLAG'];                // 체크카드여부 (신용카드:0, 체크카드:1, 기프트카드:2)
			$this->m_cardName  = $resultString['P_FN_NM'];                              // 결제카드한글명
			$this->m_cardSrcCode  = $resultString['P_SRC_CODE'];                        // 앱연동 여부 P : 페이핀, K : 국민앱카드


			--휴대폰
			$this->m_codegw  = $resultString['P_HPP_CORP'];                             // 휴대폰 통신사코드
			$this->m_hppapplnum  = $resultString['P_APPL_NUM'];                         // 휴대폰결제 승인번호
			$this->m_hppnum  = $resultString['P_HPP_NUM'];                              // 고객 휴대폰 번호


			--가상계좌
			$this->m_vacct  = $resultString['P_VACT_NUM'];                              // 입금할 계좌 번호
			$this->m_dtinput = $resultString['P_VACT_DATE'];                            // 입금마감일자(YYYYmmdd)
			$this->m_tminput = $resultString['P_VACT_TIME'];                            // 입금마감시간(hhmmss)
			$this->m_nmvacct = $resultString['P_VACT_NAME'];                            // 계좌주명
			$this->m_vcdbank = $resultString['P_VACT_BANK_CODE'];                       // 은행코드
  */

        $isDBOK = true; //DB처리 실패시 false로 변경해 주세요.

        $or_sql = "SELECT * FROM tbl_order_listTemp WHERE orderno='".$OrderNumber."' ";
		//echo $or_sql."<br>";
        $rs_or = $dbcon -> query($or_sql);
		$row_or = $dbcon -> fetch_array($rs_or);

        // 06-1-1. 신용카드
        if ( $inimx->m_payMethod == "CARD")
        {
        	switch ($inimx->m_cardCode) {
        		case '01': $pay_name = "하나(외환) 카드"; break;
        		case '03': $pay_name = "롯데 카드 (구 동양)"; break;
        		case '04': $pay_name = "현대 카드 (구 다이너스)"; break;
        		case '06': $pay_name = "국민카드"; break;
        		case '11': $pay_name = "BC 카드"; break;
        		case '12': $pay_name = "삼성 카드"; break;
        		case '14': $pay_name = "신한 카드"; break;
        		case '15': $pay_name = "한미 카드"; break;
        		case '16': $pay_name = "NH 카드"; break;
        		case '17': $pay_name = "하나 카드"; break;
        		case '21': $pay_name = "해외 비자카드"; break;
        		case '22': $pay_name = "해외 마스터카드"; break;
        		case '23': $pay_name = "해외 JCB 카드"; break;
        		case '24': $pay_name = "해외 아맥스카드"; break;
        		case '25': $pay_name = "해외 다이너스카드"; break;
        		case '31': $pay_name = "주택 카드 (구 동남카드)"; break;
        		case '32': $pay_name = "광주 카드"; break;
        		case '33': $pay_name = "전북 카드"; break;
        		case '34': $pay_name = "하나 카드 (구 보람카드)"; break;
        		case '41': $pay_name = "농협(축협) 카드"; break;
        		case '42': $pay_name = "한미 카드"; break;
        		case '43': $pay_name = "씨티 카드"; break;
        		case '44': $pay_name = "평화 카드"; break;
        		case '45': $pay_name = "신세계 카드"; break;
        		case '51': $pay_name = "수협 카드"; break;
        		case '52': $pay_name = "제주 카드"; break;
        		case '53': $pay_name = "조흥 카드 (구 강원카드)"; break;
        		default: $pay_name = "기타 카드"; break;
        	}
        	$pay_name.= " / ".$inimx->m_cardNum." / ".$inimx->m_cardName;
        }
        // 06-1-2. 계좌이체
        if ( $inimx->m_payMethod == "DirectBank" )
        {
        	switch ($inimx->m_directbankcode) {
        		case '02': $pay_name = "한국 산업은행"; break;
        		case '03': $pay_name = "기업은행"; break;
        		case '04': $pay_name = "국민은행 (주택은행)"; break;
        		case '05': $pay_name = "외환은행"; break;
        		case '07': $pay_name = "수협중앙회"; break;
        		case '11': $pay_name = "농협중앙회"; break;
        		case '12': $pay_name = "단위농협"; break;
        		case '16': $pay_name = "축협중앙회"; break;
        		case '20': $pay_name = "우리은행"; break;
        		case '21': $pay_name = "신한은행 (조흥은행)"; break;
        		case '23': $pay_name = "제일은행"; break;
        		case '25': $pay_name = "하나은행 (서울은행)"; break;
        		case '26': $pay_name = "신한은행"; break;
        		case '27': $pay_name = "한국씨티은행 (한미은행)"; break;
        		case '31': $pay_name = "대구은행"; break;
        		case '32': $pay_name = "부산은행"; break;
        		case '34': $pay_name = "광주은행"; break;
        		case '35': $pay_name = "제주은행"; break;
        		case '37': $pay_name = "전북은행"; break;
        		case '38': $pay_name = "강원은행"; break;
        		case '39': $pay_name = "경남은행"; break;
        		case '41': $pay_name = "비씨카드"; break;
        		case '53': $pay_name = "씨티은행"; break;
        		case '54': $pay_name = "홍콩상하이은행"; break;
        		case '71': $pay_name = "우체국"; break;
        		case '81': $pay_name = "하나은행"; break;
        		case '83': $pay_name = "평화은행"; break;
        		case '87': $pay_name = "신세계"; break;
        		case '88': $pay_name = "신한은행 (조흥통합)"; break;
        		default: $pay_name = "기타은행"; break;
        	}
        }
        // 06-1-3. 가상계좌
        if ( $inimx->m_payMethod == "VBANK" )
        {
        	switch ($inimx->m_vcdbank) {
        		case '02': $pay_name = "한국 산업은행"; break;
        		case '03': $pay_name = "기업은행"; break;
        		case '04': $pay_name = "국민은행"; break;
        		case '05': $pay_name = "외환은행"; break;
        		case '07': $pay_name = "수협중앙회"; break;
        		case '11': $pay_name = "농협중앙회"; break;
        		case '12': $pay_name = "단위농협"; break;
        		case '16': $pay_name = "축협중앙회"; break;
        		case '20': $pay_name = "우리은행"; break;
        		case '21': $pay_name = "신한은행"; break;
        		case '23': $pay_name = "제일은행"; break;
        		case '25': $pay_name = "하나은행"; break;
        		case '26': $pay_name = "신한은행"; break;
        		case '27': $pay_name = "한국씨티은행"; break;
        		case '31': $pay_name = "대구은행"; break;
        		case '32': $pay_name = "부산은행"; break;
        		case '34': $pay_name = "광주은행"; break;
        		case '35': $pay_name = "제주은행"; break;
        		case '37': $pay_name = "전북은행"; break;
        		case '38': $pay_name = "강원은행"; break;
        		case '39': $pay_name = "경남은행"; break;
        		case '41': $pay_name = "비씨카드"; break;
        		case '53': $pay_name = "씨티은행"; break;
        		case '54': $pay_name = "홍콩상하이은행"; break;
        		case '71': $pay_name = "우체국"; break;
        		case '81': $pay_name = "하나은행"; break;
        		case '83': $pay_name = "평화은행"; break;
        		case '87': $pay_name = "신세계"; break;
        		case '88': $pay_name = "신한은행"; break;
        		default: $pay_name = "기타은행"; break;
        	}
			$Bank_Name = $pay_name;		// 은행명
            //$pay_name.= " / ".$inimx->m_nmvacct." / ".$inimx->m_vacct;
        }

		// 주문입력
		if ($row_or["orderno"]){
			/*****************************************************************************
			* 여기에 가맹점 내부 DB에 결제 결과를 반영하는 관련 프로그램 코드를 구현한다.

			[중요!] 승인내용에 이상이 없음을 확인한 뒤 가맹점 DB에 해당건이 정상처리 되었음을 반영함
				처리중 에러 발생시 망취소를 한다.
			******************************************************************************/
          // 20250316 yjhzzzzdev 수정
            $m_vacct = '';
			if ($inimx->m_payMethod=="CARD"){
					$order_step = "2"; 
					$join_status="Y";
					$pay_name = "CARD / ".$inimx->m_cardName;
			}
			if ($inimx->m_payMethod=="HPP" || $inimx->m_payMethod=="MOBILE"){
				$order_step = "2";
				$join_status="Y";
				$pay_name = "HPP";
			}
			if ($inimx->m_payMethod=="VBANK"){
				$order_step = "1"; 
				$join_status="W";
				$pay_name = "가상계좌 / ".$Bank_Name;
                $m_vacct = $inimx->m_vacct;
			}

        //     if ($inimx->m_payMethod=="CARD"){
        //         $order_step = "2"; 
        //         $join_status="Y";
        //         $pay_name = "CARD / ".$inimx->m_cardName." / ".$inimx->m_cardNum;
        // }
        // if ($inimx->m_payMethod=="HPP" || $inimx->m_payMethod=="MOBILE"){
        //     $order_step = "2";
        //     $join_status="Y";
        //     $pay_name = "HPP";
        // }
        // if ($inimx->m_payMethod=="VBANK"){
        //     $order_step = "1"; 
        //     $join_status="W";
        //     $pay_name = "가상계좌 / ".$Bank_Name." / ".$inimx->m_vacct;
        // }
			//===================================================
			// 주문입력
			//===================================================
			$SQL = "insert into tbl_order_list ";
			$SQL .= " ( ";
			$SQL .= " orderno,pr_name,ins_name,plan_name, agree_cd, service_name, rule_site_cd, rule_group_cd, rule_privacy_cd, ins_file_cd, service_file_cd ,s_date,s_date_time,e_date,e_date_time,ins_period,chk_p,o_name,o_email1,o_email2,purpose,join_cnt,join_ch,join_nation_cd,join_nation_name,order_step,ins_amount,s_amount,cp_amount,vat_amount,t_amount,service_amount,writedate,pg_id,pg_pay_type,pg_isdn,pay_name,chk_service ";
			$SQL .= " ,sale_gubun,sale_discount,new_cp_cd,recommend_cd ";
			$SQL .= " ,pr_cd,ins_cd,plan_cd,service_cd ";
			$SQL .= " ) ";
			$SQL .= " select ";
			$SQL .= " orderno,pr_name,ins_name,plan_name, agree_cd, service_name, rule_site_cd, rule_group_cd, rule_privacy_cd, ins_file_cd, service_file_cd  ,s_date,s_date_time,e_date,e_date_time,ins_period,chk_p,o_name,o_email1,o_email2,purpose,join_cnt,join_ch,join_nation_cd,join_nation_name,'".$order_step."',ins_amount,s_amount,cp_amount,vat_amount,t_amount,service_amount,now(),'".MID."','".$inimx->m_payMethod."','".$inimx->m_tid."','".$pay_name."',chk_service ";
			$SQL .= " ,sale_gubun,sale_discount,new_cp_cd,recommend_cd ";
			$SQL .= " ,pr_cd,ins_cd,plan_cd,service_cd ";
			$SQL .= " from tbl_order_listTemp ";
			$SQL .= " where orderno ='".$OrderNumber."' ";
			//						echo $SQL." 첫번째<br>";
			$result_ord = $dbcon -> query($SQL);

			//===================================================
			// 가입 회원정보 입력
			//===================================================
			$SQL_J = " insert into tbl_order_list_join ";
			$SQL_J .= " (orderno,gender,chk_join,chk_eng_passport,o_name,o_phone,o_isdn1,o_isdn2,o_name_en, ins_plan_cd,join_amount,join_service,vat_amount,s_amount,t_amount,regdate,join_status,is_abroad_resident) ";
			$SQL_J .= " select orderno,gender,chk_join,chk_eng_passport,o_name,o_phone,o_isdn1,o_isdn2,o_name_en, ins_plan_cd,join_amount,join_service,vat_amount,s_amount,t_amount,now(),'".$join_status."',is_abroad_resident ";
			$SQL_J .= " from tbl_order_list_joinTemp where orderno ='".$OrderNumber."' ";
			//						echo $SQL_J." 두번째<br>";
			$result_J = $dbcon -> query($SQL_J);


			//===================================================
			// 질문사항 입력
			//===================================================
			$SQL_N = "insert into tbl_order_list_notice (orderno,pr_notice,pr_notice_a) ";
			$SQL_N .= " select orderno,pr_notice,pr_notice_a from tbl_order_list_noticeTemp where orderno='".$OrderNumber."' ";
			//						echo $SQL_J." 질문사항<br>";
			$result_N = $dbcon -> query($SQL_N);

			//===================================================
			// 쿠폰 입력
			//===================================================
			$SQL_cp = " insert into tbl_order_list_coupon (orderno,coupon_name,new_cp_cd,cp_sale_per,coupon_amount,writedate) ";
			$SQL_cp .= " select orderno,coupon_name,new_cp_cd,cp_sale_per,coupon_amount,now() from tbl_order_list_couponTemp where orderno= '".$OrderNumber."' ";
			$result_cp = $dbcon -> query($SQL_cp);

			//===================================================
			// 결제정보 출력
			//===================================================
			$SQL_ORDER  = " SELECT j.o_name,j.o_phone ";
			$SQL_ORDER .= " , o.o_email1, o.o_email2 ";
			$SQL_ORDER .= " , o.ins_file_cd, o.service_file_cd ";
			$SQL_ORDER .= " , o.order_step, o.s_date, o.s_date_time, o.e_date, o.e_date_time, o.chk_p ";
			$SQL_ORDER .= " , o.sale_gubun, o.sale_discount, o.new_cp_cd, o.recommend_cd, o.s_amount ";
			$SQL_ORDER .= " , o.pr_name, o.ins_name, o.plan_name, o.chk_service, o.purpose ";
			$SQL_ORDER .= " , o.ins_amount, o.service_amount, o.t_amount, j.regdate, o.plan_cd ";
			$SQL_ORDER .= " FROM tbl_order_list_join j INNER JOIN  tbl_order_list o  ON j.orderno = o.orderno ";
			$SQL_ORDER .= " WHERE j.orderno = '".$OrderNumber."' AND chk_join = 'N'  ";
			$result_order = $dbcon -> query($SQL_ORDER);
			$row_order = $dbcon->fetch_array($result_order);
			
			$s_coupon_date = date("Y-m-d",strtotime($row_order["regdate"]));
			$e_coupon_date = date("Y-m-d",strtotime($s_coupon_date." +180 days "));

			$SQL_EVENT  = "SELECT 
											seq
											,category
											,subject
											,content
											,coupon_name
											,start_date
											,end_date
											,expire_date_s
											,expire_date_e
											,discount
											,coupon_size
											,event_type
											,event_url
											,event_partnership_code
											,duplicate_status_yn
											,insurance_discount_applied
											,service_fee_discount_applied
											,insurance_discount_rate
											,insurance_max_discount_amount
											,service_fee_discount_rate
											,service_fee_max_discount_amount
											,subscription_start_day
											,subscription_end_day
											,event_category_master_seq
											,min_companion
											,max_companion";
			$SQL_EVENT .= " FROM tbl_board_event WHERE event_type='C' AND event_partnership_code = 'insuplus'";
			$SQL_EVENT .= " AND start_date <= now() AND end_date >= now()";
			$SQL_EVENT .= " ORDER BY seq desc LIMIT 0,1 ";
			$result_event = $dbcon -> query($SQL_EVENT);
			$row_event = $dbcon->fetch_array($result_event);

			$discount = 0;
			$symbol = '%';
			$discountValue = '';
			if($row_event) {
				if($row_event["service_fee_discount_applied"] == 'F') {
					$symbol = '원';
					$discount = number_format($row_event["service_fee_max_discount_amount"]);
				} else {
					if($row_event["insurance_discount_applied"] == 'P' && $row_event["service_fee_discount_applied"] == 'P') {
						$symbol = '%';
						$discount = max($row_event["insurance_discount_rate"], $row_event["service_fee_discount_rate"]);
					} else if($row_event["insurance_discount_applied"] == 'P') {
						$symbol = '%';
						$discount = $row_event["insurance_discount_rate"];
					} else if($row_event["service_fee_discount_applied"] == 'P'){
						$symbol = '%';
						$discount = $row_event["service_fee_discount_rate"];
					}
				}
			}
			$discountValue = $discount.$symbol;

			// 플랜검색
			$SQL_PLAN = "select * from tbl_board_plan where seq=".$row_order["plan_cd"]."";
			$RS_PLAN = $dbcon -> query($SQL_PLAN);
			$row_plan = $dbcon -> fetch_array($RS_PLAN);
			//===================================================
			// 사용 쿠폰, 추천코드 처리
			//===================================================
			if($row_order["sale_gubun"] == "C") {
				$UP_SQL_CP = " UPDATE tbl_board_coupon_history SET ";
				$UP_SQL_CP .= " orderno = '".$OrderNumber."' , discount = '".$row_order["sale_discount"]."' ";
				$UP_SQL_CP .= " , s_amount = '".$row_order["s_amount"]."' , use_yn = 'Y' ";
				$UP_SQL_CP .= " WHERE seq in (".$row_order["new_cp_cd"].") ";
				
				$result_upd_cp = $dbcon -> query($UP_SQL_CP);
			} else if($row_order["sale_gubun"] == "R") {

				$SQL_RECOMMEND = " SELECT recommendation_code, discount FROM tbl_board_recommend_code WHERE seq = '".$row_order["recommend_cd"]."' ";
				echo $SQL_RECOMMEND;
				$result_recommend = $dbcon->query($SQL_RECOMMEND);
				$row_recommend = $dbcon->fetch_array($result_recommend);

				$UP_SQL_RECOMMEND = " INSERT INTO tbl_board_recommend_code_history ";
				$UP_SQL_RECOMMEND .= " (orderno, recommend_seq, recommend_name, discount, temp_discount, s_amount, writedate) ";
				$UP_SQL_RECOMMEND .= " values  ";
				$UP_SQL_RECOMMEND .= " ('".$OrderNumber."','".$row_order["recommend_cd"]."' ,'".$row_recommend["recommendation_code"]."' ";
				$UP_SQL_RECOMMEND .= " 	,'".$row_order["sale_discount"]."','".$row_recommend["discount"]."','".$row_order["s_amount"]."',now()) ";
				echo $UP_SQL_RECOMMEND;
				$result_ins_recommend = $dbcon -> query($UP_SQL_RECOMMEND);
			}


			//===================================================
			// 감사쿠폰등록 (무통장 입금 제외 쿠폰 발생 안함)
			//===================================================
			if ($inimx->m_payMethod!="VBANK"){
				if($row_event) {
					$INS_SQL_CP  =  " INSERT INTO tbl_board_coupon_history ";
					$INS_SQL_CP .= " (receive_orderno, event_seq, start_date, end_date, mobile ";
					$INS_SQL_CP .= " , ori_mobile, temp_discount, use_yn, writedate) ";
					$INS_SQL_CP .= "  VALUES ";
					$INS_SQL_CP .= " ('".$OrderNumber."', '".$row_event["seq"]."', '".$s_coupon_date."', '".$e_coupon_date."','".$row_order["o_phone"]."' ";
					$INS_SQL_CP .= " ,'".$row_order["o_phone"]."', '".$discount."','N', now()) ";
					$result_ins_cp = $dbcon -> query($INS_SQL_CP);
				}
			}

			//서비스가 인슈플러스인 경우 원격진료코드 발행	
			$param = array();
			$mobile = all_seed_dec($row_order["o_phone"]);
			$param["name"] = all_seed_dec($row_order["o_name"]);
			//$param["pr_name"] = $row_order["pr_name"]." ".$row_order["ins_name"]." ".$row_order["plan_name"];
			$param["pr_name"] = $row_order["plan_name"];
			$param["plan_name"] = $row_order["plan_name"];

			if($row_order["chk_p"] == "Y") {
				$s_date = $row_order["s_date"]." ".$row_order["s_date_time"];
				$e_date = $row_order["e_date"]." ".$row_order["e_date_time"];
				$param["period"] = $s_date."시 ~ ".$e_date."시";
			} else if($row_order["chk_p"] == "N") {
				$s_date = $row_order["s_date"];
				$e_date = $row_order["e_date"];
				$param["period"] = $s_date." ~ ".$e_date;
			}
			$arr_period = getArrPeriod($s_date,$e_date,$row_order["chk_p"]);
			$param["period"] .= " (".$arr_period["day"]."일)";

			$param["purpose"] = $row_order["purpose"];
			$param["ins_amount"] = $row_order["ins_amount"];
			$param["amount"] = $row_order["ins_amount"]+$row_order["service_amount"];
			$param["t_amount"] = $row_order["t_amount"];

			$param["payMethod"] = $inimx->m_payMethod;
			$param["pay_name"] = $pay_name;
			if($row_order["chk_service"] == "C" || $row_order["chk_service"] == "D") {
				// $SQL_T = "SELECT telemedicine_cd from tbl_telemedicine_cd_list where expire_date = ".$arr_period["month"]." and join_orderno is null order by telemedi_seq ASC limit 1";
				// $RS_T = $dbcon -> query($SQL_T);
				// $tele_cd = $dbcon->fetch_array($RS_T);
				// $param["telemedic"] = $tele_cd["telemedicine_cd"];

				// $SQL_TI = "UPDATE tbl_telemedicine_cd_list set join_orderno = '".$OrderNumber."', usedate = now() where telemedicine_cd = '".$tele_cd["telemedicine_cd"]."' ";
				// $RS_TI = $dbcon -> query($SQL_TI);

				//서비스명
				$SQL_CMN_CD = "select cd_nm from safety_training.fd_cmn_cd where grp_cd = 'CC13' and cd_val1 = '".$row_order["chk_service"]."' order by ord ASC";
				$RS_CMN_CD = $dbcon -> query($SQL_CMN_CD);
				$service_nm = $dbcon->fetch_array($RS_CMN_CD);
				$param["pr_name"] .= " ".$service_nm["cd_nm"]."타입";
				$param["service_name"] = $service_nm["cd_nm"]."타입";
			}
			
			//===================================================
			// 롯데 등업코드 생성
			//===================================================	
			$SQL_LOTTE = "SELECT seq, coupon_cd, start_date, end_date FROM tbl_temporary_coupon 
							where orderno is NULL and start_date <= now() and end_date >= now() order by seq asc
							limit 1";
			$RS_LOTTE = $dbcon -> query($SQL_LOTTE);
			$lotte_promotion_info = $dbcon->fetch_array($RS_LOTTE);

			$today = strtotime(date('Y-m-d H:i:s'));
			$promotion_start_date = strtotime($lotte_promotion_info["start_date"]);
			$promotion_end_date = strtotime($lotte_promotion_info["end_date"]);
			
			if($lotte_promotion_info && $promotion_start_date <= $today && $promotion_end_date >= $today) {
				if ($inimx->m_payMethod != "VBANK") { //알림톡 쿠폰발행(무통장 X)
					
					$SQL_LOTTE_USE = "UPDATE tbl_temporary_coupon set orderno = '".$OrderNumber."', used_date = now() where seq = '".$lotte_promotion_info["seq"]."' ";
					$RS_LOTTE_USE = $dbcon -> query($SQL_LOTTE_USE);
					$param["promotion"] = $lotte_promotion_info["coupon_cd"];

					//===================================================
					// 롯데 등업코드 알림톡 전송
					//===================================================						
					kakaoPromotionSend($param,$mobile);
				} else {
					//추가 알림톡
					$arr_pay_name = explode("/", $param["pay_name"]);
					$param["bank"] = $arr_pay_name[1];
					$param["account"] = $m_vacct;
					kakaoJoinBankInfo($param, $mobile);
				}
			}  else {
				//===================================================
				// 알림톡 전송
				//===================================================
							
				if ($inimx->m_payMethod!="VBANK"){ //알림톡 쿠폰발행(무통장 X)
					
					// if($row_order["chk_service"] == "A" || $row_order["chk_service"] == "B" || $row_order["chk_service"] == "N") {
					// 	kakaoInsuplusJoin($param,$mobile);
					// } else {
					// 	kakaoJoin($param,$mobile);
					// }
					kakaoInsuplusJoin($param,$mobile);

				} else {
					//추가 알림톡
					$arr_pay_name = explode("/",$param["pay_name"]);
					$param["bank"] = $arr_pay_name[1];
					$param["account"] = $m_vacct;
					kakaoJoinBankInfo($param,$mobile);
				}
			}
			
			//가입감사쿠폰은 항상 알림톡 발송 하도록 20231201
			$param["discount"] = $discountValue;
			$param["discount_txt"] = $param["discount"]."% (할인 최대한도 30,000원)";
			$param["coupon_name"] = $row_event["coupon_name"];
			$param["coupon_period"] = $s_coupon_date." ~ ".$e_coupon_date;
			
			kakaoJoinCoupon($param,$mobile);
			
			//===================================================
			// 이메일 전송
			//===================================================
			$param["chk_service"] = $row_order["chk_service"];
			$param["domain"] = getDomain();
			$param["ins_seq"] = $row_order["ins_file_cd"]; //약관파일 고유번호
			$param["ins_term1"] = $row_plan["ins_term1_seq"]; //보험약관 파일 고유번호
			$param["ins_term2"] = $row_plan["ins_term2_seq"]; //보험약관 파일 고유번호
			$param["service_seq"] = $row_order["service_file_cd"]; //약관파일 고유번호
			$email = all_seed_dec($row_order["o_email1"])."@".all_seed_dec($row_order["o_email2"]);

			mailJoinSend($param, $email); // 서비스 상관없이 인슈플러스 양식으로 발송 20230303
			/*if($row_order["chk_service"] == "D" || $row_order["chk_service"] == "E") {
				mailJoinFlyingSend($param,$email);
			} else {
				mailJoinSend($param,$email);
			}*/
			
    } else {
        $isDBOK = false;
    }

		// 주문입력 실패시 PG취소
    if( !$isDBOK )
		{
			$inimx->m_type = "cancel"; // 고정
			$inimx->m_msg = "상점 DB처리 실패로 인하여 Rollback 처리 [TID:" . $inimx->m_tid . ",MID:" . $inimx->m_mid . ",OID:" . $inimx->m_moid . "]"; // 취소사유
			$inimx->startAction();

			echo "<p>";
	    echo "TX Rollback Response_code = " . $inimx->m_resultCode . "<br>";
	    echo "TX Rollback Response_msg = " . $inimx->m_resultMsg . "<p>";
			echo "productUpdate1 = " . $productUpdate1 . "<p>";
			echo "productUpdate2 = " . $productUpdate2 . "<p>";
			echo "productUpdate3 = " . $productUpdate3 . "<p>";
			
			
			$error =  "거래번호 : ".$inimx->m_tid ;
			$error .= ",지불수단 : ".$inimx->m_payMethod;
			$error .= ",결과코드 : ".$inimx->m_resultCode;
			$error .= ",결과내용 : ".$inimx->m_resultMsg;
			$error .= ",주문번호 : ".$inimx->m_moid;
			
			$sql =  " UPDATE tbl_order_listTemp SET ";
			$sql .= " fail_reason = '".$error."' ";
			$sql .= " WHERE orderno = '".$inimx->m_moid."' ";
			$dbcon ->query($sql);
			
			if($P_STATUS == "00")
			{
				$P_STATUS = "01";
				$P_RMESG1 = "DB FAIL";
				echo "자동취소가 정상적으로 완료 되었습니다.";
//				ERROR_BACK("자동취소가 정상적으로 완료 되었습니다.");
			} else {
				echo "자동취소가 정상적으로 처리되지 않았습니다.\\n운영자에게 문의 하세요.";
//				ERROR_BACK("자동취소가 정상적으로 처리되지 않았습니다.\\n운영자에게 문의 하세요.");
				exit;
			}
		}
		// 결제완료후 페이지 이동
		$_SESSION["orderno"] = $OrderNumber;		// 주문번호 세션처리
		header("location: ../renewal_step05.php");
		exit;
    }
/* = -------------------------------------------------------------------------- = */
/* =   06.-2 승인 및 실패 결과 DB처리                                             = */
/* ============================================================================== */
    else if ( $P_STATUS == "01" )
    {
    	$isDBOK = false;

    	//ERROR_BACK("결제가 이루어지지 않았습니다.");
    	ERROR_BACK("결제가 이루어지지 않았습니다.\\nResponse_code = " . $P_STATUS . "\\nResponse_msg = " . $P_RMESG1 . "");
    	exit;
    }
?>
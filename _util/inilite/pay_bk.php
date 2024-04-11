<?php
session_start();
include $_SERVER["DOCUMENT_ROOT"]."/shop/_common/dbconn2.php";
include $_SERVER["DOCUMENT_ROOT"]."/shop/_common/function.php";
/* INILiteSecurepay.php
 *
 * 이니페이 플러그인을 통해 요청된 지불을 처리한다.
 * 지불 요청을 처리한다.
 * 코드에 대한 자세한 설명은 매뉴얼을 참조하십시오.
 * <주의> 구매자의 세션을 반드시 체크하도록하여 부정거래를 방지하여 주십시요.
 *
 * http://www.inicis.com
 * Copyright (C) 2006 Inicis Co., Ltd. All rights reserved.
 */

	/**************************
	 * 1. 라이브러리 인클루드 *
	 **************************/
	require("./libs/INILiteLib.php");


	/***************************************
	 * 2. INILite 클래스의 인스턴스 생성 *
	 ***************************************/
	$inipay = new INILite;


	/*********************
	 * 3. 지불 정보 설정 *
	 *********************/
	$inipay->m_inipayHome = "/home/endoshop/public_html/shop/_util/inilite"; //상점 수정 필요
	$inipay->m_key = "NTdLcTA3MDBhQTQ2dHlRbVBWdHVCdz09"; //상점 수정 필요
	$inipay->m_ssl = "false"; 				//ssl지원하면 true로 셋팅해 주세요.
	$inipay->m_type = "securepay"; 			// 고정 (절대 수정 불가)
	$inipay->m_pgId = "INlite".$pgid; 		// 고정 (절대 수정 불가)
	$inipay->m_log = "true";              // true로 설정하면 로그가 생성됨(적극권장)
	$inipay->m_debug = "true";              // 로그모드("true"로 설정하면 상세로그가 생성됨. 적극권장)
	$inipay->m_mid = $mid; 					// 상점아이디
	$inipay->m_uid = $uid; 					// INIpay User ID (절대 수정 불가)
	$inipay->m_uip = getenv("REMOTE_ADDR"); 		// 고정 (절대 수정 불가)
	$inipay->m_goodName = $goodname;			// 상품명
	$inipay->m_currency = $currency;			// 화폐단위
	$inipay->m_price = $price;				// 결제금액
	$inipay->m_buyerName = $buyername;			// 구매자 명
	$inipay->m_buyerTel = $buyertel;			// 구매자 연락처(휴대폰 번호 또는 유선전화번호)
	$inipay->m_buyerEmail = $buyeremail;			// 구매자 이메일 주소
	$inipay->m_payMethod = $paymethod;			// 지불방법 (절대 수정 불가)
	$inipay->m_encrypted = $encrypted;			// 암호문
	$inipay->m_sessionKey = $sessionkey;			// 암호문
	$inipay->m_url = "http://www.endoshop.co.kr"; 	// 실제 서비스되는 상점 SITE URL로 변경할것
	$inipay->m_cardcode = $cardcode; 			// 카드코드 리턴
	$inipay->m_ParentEmail = $parentemail; 			// 보호자 이메일 주소(핸드폰 , 전화결제시에 14세 미만의 고객이 결제하면  부모 이메일로 결제 내용통보 의무, 다른결제 수단 사용시에 삭제 가능)

	/****************
	 * 4. 지불 요청 *
	 ****************/
	$inipay->startAction();


	/****************************************************************************************************************
	 * 5. 결제  결과                                                    						*
	 *      													*
	 *  1 모든 결제 수단에 공통되는 결제 결과 데이터                                                      		*
	 * 	거래번호 : $inipay->m_tid                                       					*
	 * 	결과코드 : $inipay->m_resultCode ("00"이면 지불 성공)           					*
	 * 	결과내용 : $inipay->m_resultMsg (지불결과에 대한 설명)          					*
	 * 	지불방법 : $inipay->m_payMethod (매뉴얼 참조)  								*
	 * 	상점주문번호 : $inipay->m_oid										*
	 *	결제완료금액 : $inipay->m_resultprice									*
	 *														*
	 *  2. 신용카드,ISP,핸드폰, 전화 결제, 은행계좌이체, OK CASH BAG Point 결제 결과 데이터        			*
	 *      (무통장입금 , 문화 상품권 포함) 								        *
	 * 	이니시스 승인날짜 : $inipay->m_pgAuthDate (YYYYMMDD)            					*
	 * 	이니시스 승인시각 : $inipay->m_pgAuthTime (HHMMSS)              					*
	 *  														*
	 *  3. 신용카드 결제 결과 데이터          						                        *
         *														*
	 * 	신용카드 승인번호 : $inipay->m_authCode                         					*
	 * 	할부기간 : $inipay->m_cardQuota                                 					*
	 * 	무이자할부 여부 : $inipay->m_quotaInterest ("1"이면 무이자할부) 					*
	 * 	신용카드사 코드 : $inipay->m_cardCode (매뉴얼 참조)             					*
	 * 	카드발급사 코드 : $inipay->m_cardIssuerCode (매뉴얼 참조)       					*
	 * 	본인인증 수행여부 : $inipay->m_authCertain ("00"이면 수행)      					*
	 *      각종 이벤트 적용 여부 : $inipay->m_eventFlag                    					*
	 *	                                                                                                        *
	 *      ** 달러결제 시 통화코드와  환률 정보 **                                                                 *
	 *	해당 통화코드 : $inipay->m_ReqCurrency                                                                  *
	 *	환율 : $inipay->m_RateExchange	                                                                        *
	 *														*
	 *      아래는 "신용카드 및 OK CASH BAG 복합결제" 또는"신용카드 지불시에 OK CASH BAG적립"시에 추가되는 데이터   *
	 * 	OK Cashbag 적립 승인번호 : $inipay->m_ocbSaveAuthCode           					*
	 * 	OK Cashbag 사용 승인번호 : $inipay->m_ocbUseAuthCode            					*
	 * 	OK Cashbag 승인일시 : $inipay->m_ocbAuthDate (YYYYMMDDHHMMSS)   					*
	 * 	OCB 카드번호 : $inipay->m_ocbcardnumber			   						*
	 * 	OK Cashbag 복합결재시 신용카드 지불금액 : $inipay->m_price1     					*
	 * 	OK Cashbag 복합결재시 포인트 지불금액 : $inipay->m_price2       					*
	 *	                                                                                                        *
	 * 4. 실시간 계좌이체 결제 결과 데이터                                                                          *
	 *                                                                                                              *
	 * 	은행코드 : $inipay->m_directbankcode                                                                    *
	 *	현금영수증 발행결과코드 : $inipay->rcash_rslt								*
	 *	현금영수증 발행구분코드 : $inipay->ruseopt								*
	 *														*
	 * 5. OK CASH BAG 결제수단을 이용시에만  결제 결과 데이터							*
	 * 	OK Cashbag 적립 승인번호 : $inipay->m_ocbSaveAuthCode           					*
	 * 	OK Cashbag 사용 승인번호 : $inipay->m_ocbUseAuthCode            					*
	 * 	OK Cashbag 승인일시 : $inipay->m_ocbAuthDate (YYYYMMDDHHMMSS)   					*
	 * 	OCB 카드번호 : $inipay->m_ocbcardnumber			   						*
	 *														*
         * 6. 무통장 입금 결제 결과 데이터							                        *
	 * 	가상계좌 채번에 사용된 주민번호 : $inipay->m_perno              					*
	 * 	가상계좌 번호 : $inipay->m_vacct                                					*
	 * 	입금할 은행 코드 : $inipay->m_vcdbank                           					*
	 * 	입금예정일 : $inipay->m_dtinput (YYYYMMDD)                      					*
	 * 	송금자 명 : $inipay->m_nminput                                  					*
	 * 	예금주 명 : $inipay->m_nmvacct                                  					*
	 *														*
	 * 7. 핸드폰, 전화 결제 결과 데이터( "실패 내역 자세히 보기"에서 필요 , 상점에서는 필요없는 정보임)             *
         * 	전화결제 사업자 코드 : $inipay->m_codegw                        					*
	 *														*
	 * 8. 핸드폰 결제 결과 데이터								                        *
	 * 	휴대폰 번호 : $inipay->m_nohpp (핸드폰 결제에 사용된 휴대폰번호)       					*
	 *														*
	 * 9. 전화 결제 결과 데이터								                        *
     * 	전화번호 : $inipay->m_noars (전화결제에  사용된 전화번호)      						*
     * 														*
     * 10. 문화 상품권 결제 결과 데이터							                        *
     * 	컬쳐 랜드 ID : $inipay->m_cultureid	                           					*
     *														*
     * 11. 모든 결제 수단에 대해 결제 실패시에만 결제 결과 데이터 							*
     * 	에러코드 : $inipay->m_resulterrcode                             					*
     * 														*
     * 12.현금영수증 발급 결과코드 (은행계좌이체시에만 리턴)							*
     *    $inipay->m_rcash_rslt                                                                                     *
     *                                                                                                              *
     ****************************************************************************************************************/

	//echo $inipay->m_resultCode." / ".$inipay->m_resultMsg;
	//print_r($inipay);

	/*******************************************************************
	 * 7. DB연동 실패 시 강제취소                                      *
	 *                                                                 *
	 * 지불 결과를 DB 등에 저장하거나 기타 작업을 수행하다가 실패하는  *
	 * 경우, 아래의 코드를 참조하여 이미 지불된 거래를 취소하는 코드를 *
	 * 작성합니다.                                                     *
	 *******************************************************************/

	$OrderNumber = $oid; // 주문번호 처리

	if( $inipay->m_resultCode == "00" )
    {
        $isDBOK = true; //DB처리 실패시 false로 변경해 주세요.

        $ot_sql = mysql_query("SELECT * FROM orderInfoTemp WHERE orderno='".$OrderNumber."'",$dbconn);
        $ot_row = mysql_fetch_array($ot_sql);

        // 06-1-1. 신용카드
        if ( $inipay->m_payMethod == "Card" || $inipay->m_payMethod == "VCard")
        {
        	switch ($inipay->m_cardCode) {
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
        }
        // 06-1-2. 계좌이체
        if ( $inipay->m_payMethod == "DirectBank" )
        {
        	switch ($inipay->m_directbankcode) {
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
        if ( $inipay->m_payMethod == "VBank" )
        {
        	switch ($inipay->m_vcdbank) {
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
            $pay_name.= " / ".$inipay->m_nmvacct." / ".$inipay->m_vacct;
        }

        // 에스크로 여부
        if($escw_yn=="Y"){
            $pay_type_escrowyn = "Y";
        }else{
            $pay_type_escrowyn = "N";
        }

        // PG측 거래번호 (TID)
        $proof_code = $inipay->m_tid;

        if($ot_row[orderno]) {

            if($ot_row[pay_type]=="C" || $ot_row[pay_type]=="E") {
                $orderStep = "1";
                $settleStep = "N";
            }else{
                $orderStep = "2";
                $settleStep = "Y";
            }

            $INS ="INSERT INTO orderInfo SET ";
            $INS.="orderno='".$OrderNumber."',";
            $INS.="member_id='".$ot_row[member_id]."',";
            $INS.="pay_type='".$ot_row[pay_type]."',";
            $INS.="pay_type_escrowyn='".$pay_type_escrowyn."',";
            $INS.="pay_name='".$pay_name."',";
            $INS.="amount='".$ot_row[amount]."',";
            $INS.="delivery_amount_af='".$ot_row[delivery_amount_af]."',";
            $INS.="delivery_amount='".$ot_row[delivery_amount]."',";
            $INS.="coop_use='".$ot_row[coop_use]."',";
            $INS.="coop_fund='".$ot_row[coop_fund]."',";
            $INS.="fund_use='".$ot_row[fund_use]."',";
            $INS.="event_use='".$ot_row[event_use]."',";
            $INS.="mileage_use='".$ot_row[mileage_use]."',";
            $INS.="sender='".addslashes($ot_row[sender])."',";
            $INS.="o_name='".addslashes($ot_row[o_name])."',";
            $INS.="o_zipcode='".$ot_row[o_zipcode]."',";
            $INS.="o_address1='".addslashes($ot_row[o_address1])."',";
            $INS.="o_address2='".addslashes($ot_row[o_address2])."',";
            $INS.="o_phone1='".$ot_row[o_phone1]."',";
            $INS.="o_phone2='".$ot_row[o_phone2]."',";
            $INS.="o_email='".$ot_row[o_email]."',";
            $INS.="o_memo='".addslashes($ot_row[o_memo])."',";
            $INS.="b_name='".addslashes($ot_row[b_name])."',";
            $INS.="b_zipcode='".$ot_row[b_zipcode]."',";
            $INS.="b_address1='".addslashes($ot_row[b_address1])."',";
            $INS.="b_address2='".addslashes($ot_row[b_address2])."',";
            $INS.="b_phone1='".$ot_row[b_phone1]."',";
            $INS.="b_phone2='".$ot_row[b_phone2]."',";
            $INS.="b_memo='".addslashes($ot_row[b_memo])."',";
            $INS.="order_step='".$orderStep."',";
            $INS.="settle_step='".$settleStep."',";
            $INS.="proof_code='".$proof_code."',";
            $INS.="lg_mallid='".$ot_row[lg_mallid]."',";
            $INS.="etc='".$_SESSION["club_name"]."',";
            $INS.="choice_id='".$_SESSION["evt_cho_id"]."',";
            $INS.="writedate=now() ";

            $RESULT = mysql_query($INS,$dbconn);

            if(!$RESULT) {
                $isDBOK = false;
            }


            //####################로그인시 가격및 제품의 정보 변경 작업 START#############################//
			//$productUpdate1 = productInfoChg("N");              //제품에 대한 업데이트 및 삭제 여부
            $productUpdate1 = "N";              //제품에 대한 업데이트 및 삭제 여부
            $productUpdate2 = productCouponInfoChg("N");            //쿠폰에 대한 사용 및 종료 여부
            $productUpdate3 = etcSaleAmount($ot_row[coop_use], $ot_row[fund_use], $ot_row[mileage_use]);    //쿠폰에 대한 사용 및 종료 여부

            if($productUpdate1=="Y" || $productUpdate2=="Y" || $productUpdate3=="Y") {
                $isDBOK = false;
            }
            //####################기타할인 END#############################//


            //=========================마일리지 사용 내역 저장하기 START=============//
            if($ot_row[member_id]) {
                if ($ot_row[mileage_use]) {

                    $use_fund_content="[상품구매] 주문번호 : ".$OrderNumber;
                    $mileageYorN = MileagePointUse($ot_row[member_id],-$ot_row[mileage_use],$use_fund_content,$OrderNumber);

                }
                if ($ot_row[fund_use]) {

                    $use_fund_content="[상품구매] 주문번호 : ".$OrderNumber;
                    $fundYorN = FundPointUse($ot_row[member_id],-$ot_row[fund_use],$use_fund_content,$OrderNumber);

                }

                if($mileageYorN=="N" || $fundYorN=="N") {
                    $isDBOK = false;
                }

            }
            //=========================마일리지 사용 내역 저장하기 END=============//

            $opt_sql = mysql_query("SELECT * FROM orderInfoProdTemp WHERE orderno='".$OrderNumber."' ORDER BY uid ASC",$dbconn);
            while ($opt_row = mysql_fetch_array($opt_sql)) {

                $INS2 ="INSERT INTO orderInfoProd SET ";
                $INS2.="orderno='".$OrderNumber."',";
                //$INS2.="cart_part='".$opt_row[cart_part]."',";
                $INS2.="member_id='".$opt_row[member_id]."',";
                $INS2.="guest_num='".$opt_row[guest_num]."',";
                $INS2.="category='".$opt_row[category]."',";
                $INS2.="puid='".$opt_row[puid]."',";
                //$INS2.="option_info='".$opt_row[option_info]."',";
                $INS2.="pquantity='".$opt_row[pquantity]."',";
                $INS2.="prod_code='".addslashes($opt_row[prod_code])."',";
                $INS2.="prod_name='".addslashes($opt_row[prod_name])."',";
                $INS2.="selling_price='".$opt_row[selling_price]."',";
                $INS2.="mileage_point='".$opt_row[mileage_point]."',";
                $INS2.="delivery_info='".$opt_row[delivery_info]."',";
                $INS2.="delivery_info_amount='".$opt_row[delivery_info_amount]."',";
                $INS2.="order_step='".$orderStep."',";
                $INS2.="writedate=now() ";

                $RESULT_QUE = mysql_query($INS2,$dbconn);

                if(!$RESULT_QUE) {
                    $isDBOK = false;
                }

                $op_uid = mysql_insert_id();            //orderInfoProd uid
                $op_puid = $opt_row[puid];              //상품번호

                $opotSql = mysql_query("SELECT * FROM orderInfoProdOptionTemp WHERE orderno='".$OrderNumber."' AND opt_uid='".$opt_row[uid]."' ORDER BY uid ASC",$dbconn);
                while($opotRow = mysql_fetch_array($opotSql)) {

                    $INS3 ="INSERT INTO orderInfoProdOption SET ";
                    $INS3.="orderno='".$OrderNumber."',";
                    $INS3.="member_id='".$opotRow[member_id]."',";
                    $INS3.="guest_num='".$opotRow[guest_num]."',";
                    $INS3.="op_uid='".$op_uid."',";
                    $INS3.="puid='".$opotRow[puid]."',";
                    $INS3.="potuid='".$opotRow[potuid]."',";
                    $INS3.="posuid='".$opotRow[posuid]."',";
                    $INS3.="option_title='".stripslashes($opotRow[option_title])."',";
                    $INS3.="option_sel='".$opotRow[option_sel]."',";
                    $INS3.="option_name='".stripslashes($opotRow[option_name])."',";
                    $INS3.="box_quantity='".$opotRow[box_quantity]."',";
                    $INS3.="option_selling_price='".$opotRow[option_selling_price]."',";
                    $INS3.="writedate=now() ";

                    $RESULT_QUE3 = mysql_query($INS3,$dbconn);

                    if(!$RESULT_QUE3) {
                        $isDBOK = false;
                    }
                }


                $opgtSql = mysql_query("SELECT * FROM orderInfoProdGiftTemp WHERE orderno='".$OrderNumber."' AND opt_uid='".$opt_row[uid]."' ORDER BY uid ASC",$dbconn);
                while($opgtRow = mysql_fetch_array($opgtSql)) {

                    $INS4 ="INSERT INTO orderInfoProdGift SET ";
                    $INS4.="orderno='".$OrderNumber."',";
                    $INS4.="member_id='".$opgtRow[member_id]."',";
                    $INS4.="guest_num='".$opgtRow[guest_num]."',";
                    $INS4.="op_uid='".$op_uid."',";
                    $INS4.="pg_uid='".$opgtRow[pg_uid]."',";
                    $INS4.="gift_name='".addslashes($opgtRow[gift_name])."',";
                    $INS4.="writedate=now() ";

                    $RESULT_QUE4 = mysql_query($INS4,$dbconn);

                    if(!$RESULT_QUE4) {
                        $isDBOK = false;
                    }
                }


                $opctSql = mysql_query("SELECT * FROM orderInfoProdCouponTemp WHERE opt_uid='".$opt_row[uid]."' ORDER BY uid ASC",$dbconn);
                while($opctRow = mysql_fetch_array($opctSql)) {

                    $clpSql = mysql_query("SELECT coupon_name FROM couponListPublish WHERE uid='".$opctRow[clp_uid]."'",$dbconn);
                    $clpRow = mysql_fetch_row($clpSql);

                    $INS5 ="INSERT INTO orderInfoProdCoupon SET ";
                    $INS5.="orderno='".$OrderNumber."',";
                    $INS5.="member_id='".$opctRow[member_id]."',";
                    $INS5.="guest_num='".$opctRow[guest_num]."',";
                    $INS5.="op_uid='".$op_uid."',";
                    $INS5.="clp_uid='".$opctRow[clp_uid]."',";
                    $INS5.="coupon_number='".$opctRow[coupon_number]."',";
                    $INS5.="coupon_amount='".$opctRow[coupon_amount]."',";
                    $INS5.="coupon_name='".addslashes($clpRow[0])."',";
                    $INS5.="writedate=now() ";

                    $RESULT_QUE5 = mysql_query($INS5,$dbconn);

                    if(!$RESULT_QUE5) {
                        $isDBOK = false;
                    }else{
                        $clpUpd ="UPDATE couponListPublish SET ";
                        $clpUpd.="use_yorn='Y',";
                        $clpUpd.="use_date=now(),";
                        $clpUpd.="orderno='".$OrderNumber."',";
                        $clpUpd.="op_uid='".$op_uid."',";
                        $clpUpd.="puid='".$opt_row[puid]."' ";
                        $clpUpd.="WHERE uid='".$opctRow[clp_uid]."' ";

                        $clpResult = mysql_query($clpUpd,$dbconn);

                        if(!$clpResult) {
                            $isDBOK = false;
                        }
                    }
                }


                if($isDBOK) {
                    //==============================주문처리 흐름 저장 START========================================//
                    if($orderStep=="1") {
                        orderInfoProdStepFnc($OrderNumber,$op_uid,$op_puid,$orderStep,"주문접수",$memo);
                    }else{
                        orderInfoProdStepFnc($OrderNumber,$op_uid,$op_puid,$orderStep,"입금(결제)완료",$memo);
                    }

                    //주문번호, orderInfoProd uid , 상품번호 , 주문단계, 처리내용 , memo , memberID(카드결제 주문일때 사용)
                    //==============================주문처리 흐름 저장 END========================================//

                    //상품 수량 빼기
                    $prodUpd = mysql_query("UPDATE product SET unsold_stock=unsold_stock-$opt_row[pquantity],sale_num=sale_num+1 WHERE uid='".$op_puid."'",$dbconn);


                    //옵션 상품 수량 빼기       2013-02-13 옵션재고 추가
                    $oipoSql = mysql_query("SELECT posuid FROM orderInfoProdOption WHERE op_uid='".$op_uid."'",$dbconn);
                    while($oipoRow = mysql_fetch_row($oipoSql)) {
                        if($oipoRow[0]) {
                            $posUpd = mysql_query("UPDATE productOptSet SET option_stock=option_stock-$opt_row[pquantity] WHERE uid='".$oipoRow[0]."' AND puid='".$op_puid."'",$dbconn);
                        }
                    }
                }
            }
            endoshopSMS02($OrderNumber);    // 주문완료 SMS
        } else {
            $isDBOK = false;
        }

        if( !$isDBOK )
		{
			$inipay->m_type = "cancel"; // 고정
			$inipay->m_msg = "상점 DB처리 실패로 인하여 Rollback 처리 [TID:" . $inipay->m_tid . ",MID:" . $inipay->m_mid . ",OID:" . $inipay->m_oid . "]"; // 취소사유
			$inipay->startAction();

			echo "<p>";
	        echo "TX Rollback Response_code = " . $inipay->m_resultCode . "<br>";
	        echo "TX Rollback Response_msg = " . $inipay->m_resultMsg . "<p>";
			echo "productUpdate1 = " . $productUpdate1 . "<p>";
			echo "productUpdate2 = " . $productUpdate2 . "<p>";
			echo "productUpdate3 = " . $productUpdate3 . "<p>";

			echo "$ot_row[coop_use], $ot_row[fund_use], $ot_row[mileage_use]". "<p>";
			echo "$aaaa";


			$UPD = mysql_query("UPDATE orderInfoTemp SET order_fail='A' WHERE orderno='".$OrderNumber."'",$dbconn);

			$DEL = mysql_query("DELETE FROM orderInfo WHERE orderno='".$OrderNumber."'",$dbconn);
			$DEL = mysql_query("DELETE FROM orderInfoProd WHERE orderno='".$OrderNumber."'",$dbconn);
			$DEL = mysql_query("DELETE FROM orderInfoProdOption WHERE orderno='".$OrderNumber."'",$dbconn);
			$DEL = mysql_query("DELETE FROM orderInfoProdStep WHERE orderno='".$OrderNumber."'",$dbconn);
			$DEL = mysql_query("DELETE FROM orderInfoProdGift WHERE orderno='".$OrderNumber."'",$dbconn);
			$DEL = mysql_query("DELETE FROM orderInfoProdCoupon WHERE orderno='".$OrderNumber."'",$dbconn);

			$UPD = mysql_query("UPDATE couponListPublish SET use_yorn='N',use_date='',orderno='',op_uid='',puid='' WHERE orderno='".$OrderNumber."' AND coupon_type NOT IN ('111', '112', '113')",$dbconn);


			$UPD = mysql_query("UPDATE couponCoop SET use_yorn='N',use_date='',orderno='' WHERE orderno='".$OrderNumber."'",$dbconn);
			$DEL = mysql_query("DELETE FROM fundList WHERE orderno='".$OrderNumber."'",$dbconn);
			$DEL = mysql_query("DELETE FROM mileage_list WHERE orderno='".$OrderNumber."'",$dbconn);


			if($inipay->m_resultCode == "00")
			{
				$inipay->m_resultCode = "01";
				$inipay->m_resultMsg = "DB FAIL";
				ERROR_BACK("PG 및 DB처리의 실패로 자동취소가 되었습니다. \\n고객님께서는 죄송하지만 다시 결제해주시기 바랍니다. \\n감사합니다.");
			} else {
				ERROR_BACK("PG 및 DB처리의 실패로 자동취소가 진행중 자동취소가 정상적으로 처리되지 않았습니다.\\n고객센터에 문의 해주십시오.");
				exit;
			}
		}
		if( $isDBOK ) {
			//결제 성공시
			$DEL = mysql_query("DELETE FROM orderInfoCartTemp WHERE orderno='".$OrderNumber."'",$dbconn);
		}

		// 결제완료후 페이지 이동
		GO_REFRESH($url_order_end);
		exit;
    }
/* = -------------------------------------------------------------------------- = */
/* =   06.-2 승인 및 실패 결과 DB처리                                             = */
/* ============================================================================== */
    else if ( $inipay->m_resultCode != "00" )
    {
    	$isDBOK = false;
    	mysql_query("UPDATE orderInfoTemp SET order_fail='B' WHERE orderno='".$OrderNumber."'",$dbconn);
		//echo "UPDATE orderInfoTemp SET order_fail='B' WHERE orderno='".$OrderNumber."'";

		//ERROR_BACK("결제가 이루어지지 않았습니다.");
		ERROR_BACK("결제가 이루어지지 않았습니다.\\nResponse_code = " . $inipay->m_resultCode . "\\nResponse_msg = " . $inipay->m_resultMsg . "");
		exit;
    }


?>


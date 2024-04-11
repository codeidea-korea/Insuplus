<?php
session_start();
include $_SERVER["DOCUMENT_ROOT"]."/shop/_common/dbconn.php";
include $_SERVER["DOCUMENT_ROOT"]."/shop/_common/function.php";

//*******************************************************************************
// FILE NAME : INIpayResult.php
// DATE : 2009.07
// 이니시스 가상계좌 입금내역 처리demon으로 넘어오는 파라메터를 control 하는 부분 입니다.
//*******************************************************************************

//**********************************************************************************
//이니시스가 전달하는 가상계좌이체의 결과를 수신하여 DB 처리 하는 부분 입니다.
//필요한 파라메터에 대한 DB 작업을 수행하십시오.
//**********************************************************************************

@extract($_GET);
@extract($_POST);
@extract($_SERVER);


//**********************************************************************************
//  이부분에 로그파일 경로를 수정해주세요.

$INIpayHome = "/home/endoshop/public_html/shop/_util/inilite";      // 이니페이 홈디렉터리

//**********************************************************************************


$TEMP_IP = getenv("REMOTE_ADDR");
$PG_IP  = substr($TEMP_IP,0, 10);

if( $PG_IP == "203.238.37" || $PG_IP == "210.98.138" || $PG_IP == "39.115.212" )  //PG에서 보냈는지 IP로 체크
{
        $msg_id = $msg_id;             //메세지 타입
        $no_tid = $no_tid;             //거래번호
        $no_oid = $no_oid;             //상점 주문번호
        $id_merchant = $id_merchant;   //상점 아이디
        $cd_bank = $cd_bank;           //거래 발생 기관 코드
        $cd_deal = $cd_deal;           //취급 기관 코드
        $dt_trans = $dt_trans;         //거래 일자
        $tm_trans = $tm_trans;         //거래 시간
        $no_msgseq = $no_msgseq;       //전문 일련 번호
        $cd_joinorg = $cd_joinorg;     //제휴 기관 코드

        $dt_transbase = $dt_transbase; //거래 기준 일자
        $no_transeq = $no_transeq;     //거래 일련 번호
        $type_msg = $type_msg;         //거래 구분 코드
        $cl_close = $cl_close;         //마감 구분코드
        $cl_kor = $cl_kor;             //한글 구분 코드
        $no_msgmanage = $no_msgmanage; //전문 관리 번호
        $no_vacct = $no_vacct;         //가상계좌번호
        $amt_input = $amt_input;       //입금금액
        $amt_check = $amt_check;       //미결제 타점권 금액
        $nm_inputbank = $nm_inputbank; //입금 금융기관명
        $nm_input = $nm_input;         //입금 의뢰인
        $dt_inputstd = $dt_inputstd;   //입금 기준 일자
        $dt_calculstd = $dt_calculstd; //정산 기준 일자
        $flg_close = $flg_close;       //마감 전화

        //가상계좌채번시 현금영수증 자동발급신청시에만 전달
        $dt_cshr      = $dt_cshr;       //현금영수증 발급일자
        $tm_cshr      = $tm_cshr;       //현금영수증 발급시간
        $no_cshr_appl = $no_cshr_appl;  //현금영수증 발급번호
        $no_cshr_tid  = $no_cshr_tid;   //현금영수증 발급TID


		$LGD_BUYERID = $id_merchant ;
		$LGD_TID = $no_tid ;
		$LGD_RESPCODE = $no_vacct;
		$LGD_RESPMSG = $no_vacct." ".$id_merchant;
		$LGD_AMOUNT = str_replace(",","",number_format($amt_input,0));			//*	결제완료금액 :

//echo $no_transeq."<br>";
//echo $no_vacct."<br>";
//echo $nm_input."<br>";
//echo $LGD_AMOUNT."<br>";
//echo $no_oid." no_oid<br>";
//echo $no_tid." no_tid<br>";

		$INS ="INSERT INTO LG_CAS SET ";
		$INS.="orderno='".$no_oid."',";
		$INS.="proof_code='".$no_tid."',";
		$INS.="LGD_CASTAMOUNT='".$LGD_CASTAMOUNT."',";
		$INS.="LGD_CASCAMOUNT='".$LGD_CASCAMOUNT."',";
		$INS.="LGD_HASHDATA2='".$LGD_HASHDATA2."',";
		$INS.="LGD_HASHDATA='".$LGD_HASHDATA."',";
		$INS.="LGD_RESPCODE='".$LGD_RESPCODE."',";
		$INS.="LGD_CASFLAG='PC',";
		$INS.="writedate=now() ";
//echo $INS."<br>";
		$RESULT = mysql_query_exe($INS,$dbconn);

		$LG_CAS_UID = mysqli_insert_id($dbconn);

        $logfile = fopen( $INIpayHome . "/log/result_bank".date("Ymd").".log", "a+" );


        fwrite( $logfile,"\r\n************************************************");
        fwrite( $logfile,"ID_MERCHANT : ".$id_merchant."\r\n");
        fwrite( $logfile,"NO_TID : ".$no_tid."\r\n");
        fwrite( $logfile,"NO_OID : ".$no_oid."\r\n");
        fwrite( $logfile,"NO_VACCT : ".$no_vacct."\r\n");
        fwrite( $logfile,"AMT_INPUT : ".$amt_input."\r\n");
        fwrite( $logfile,"NM_INPUTBANK : ".$nm_inputbank."\r\n");
        fwrite( $logfile,"NM_INPUT : ".$nm_input."\r\n");
        fwrite( $logfile,"현금영수증 발급TID : ".$no_cshr_tid."\r\n");
        fwrite( $logfile,"************************************************\r\n");


//        fwrite( $logfile,"전체 결과값"."\r\n");
//        fwrite( $logfile, $msg_id."\r\n");
//        fwrite( $logfile, $no_tid."\r\n");
//        fwrite( $logfile, $no_oid."\r\n");
//        fwrite( $logfile, $id_merchant."\r\n");
//        fwrite( $logfile, $cd_bank."\r\n");
//        fwrite( $logfile, $dt_trans."\r\n");
//        fwrite( $logfile, $tm_trans."\r\n");
//        fwrite( $logfile, $no_msgseq."\r\n");
//        fwrite( $logfile, $type_msg."\r\n");
//        fwrite( $logfile, $cl_close."\r\n");
//        fwrite( $logfile, $cl_kor."\r\n");
//        fwrite( $logfile, $no_msgmanage."\r\n");
//        fwrite( $logfile, $no_vacct."\r\n");
//        fwrite( $logfile, $amt_input."\r\n");
//        fwrite( $logfile, $amt_check."\r\n");
//        fwrite( $logfile, $nm_inputbank."\r\n");
//        fwrite( $logfile, $nm_input."\r\n");
//        fwrite( $logfile, $dt_inputstd."\r\n");
//        fwrite( $logfile, $dt_calculstd."\r\n");
//        fwrite( $logfile, $flg_close."\r\n");
//        fwrite( $logfile, "\r\n");


        fclose( $logfile );


// 결제 결과 정보입력
		if ($no_tid){

				$isql = "insert into tb_pg_order_state(r_code,r_msg,orderno,ord_amount,ord_type,ord_org,lgd_oid,member_id,regdate,ord_device) values('".$LGD_RESPCODE."','".$LGD_RESPMSG."','".$no_oid."','".$LGD_AMOUNT."','B','".$LGD_FINANCENAME."','".$LGD_TID."','".$LGD_BUYERID."',now(),'PC')";
				$reseult_sql = mysql_query_exe($isql);
//echo $no_oid." 주문번호".chr(10);
//echo "SELECT orderno, amount, choice_id FROM orderInfo WHERE (pay_type='B' OR pay_type='E') AND orderno='G160411171917nv' AND order_step='1' AND proof_code='IniTechPG_Natural11120160411172227322000'";
				$sql = mysql_query_exe("SELECT orderno, amount, choice_id, order_md, member_uid, o_phone2, ars_yn   FROM orderInfo WHERE pay_type='C' AND orderno='".$no_oid."' AND order_step='1' ",$dbconn);
				$row = mysqli_fetch_row($sql);
//echo $row[0]." 0<br>";
//echo $row[1]." 금액".chr(10);
//echo $LGD_AMOUNT." 합계".chr(10);

				if($row[0] && ($row[1]==$LGD_AMOUNT)) {
					$resultMSG = "OK";
					$orderStep = "2";

					$INS ="UPDATE orderInfo SET ";
					$INS.="order_step='".$orderStep."', ";
					$INS.="pg_date=now() ";
					$INS.="WHERE orderno='".$row[0]."'";

					$RESULT = mysql_query_exe($INS,$dbconn);

					if(!$RESULT) {
						$resultMSG = "ERROR UPDATE orderInfo";
					}



					if($resultMSG=="OK") {
						$opSql = mysql_query_exe("SELECT uid, puid, member_id, prod_name FROM orderInfoProd WHERE orderno='".$no_oid."' AND order_step='1'",$dbconn);
						$o_cnt = 0;
						while($opRow = mysqli_fetch_row($opSql)) {

							// 대표상품 검색
							if ($o_cnt==0){
							$o_pr_name = addslashes($opRow[3]);
							}
							$o_cnt = $ocnt + 1;						// 상품개수

//echo "1";
							$INS2 ="UPDATE orderInfoProd SET ";
							$INS2.="order_step='".$orderStep."',";
							$INS2.="modifydate=now() ";
							$INS2.="WHERE uid='".$opRow[0]."' ";

							$RESULT_QUE = mysql_query_exe($INS2,$dbconn);

							$op_uid = mysqli_insert_id($dbconn);			//orderInfoProd uid
							$op_puid = $opt_row[puid];				//상품번호
//echo "2";

							if(!$RESULT_QUE) {
								echo "ERROR UPDATE orderInfoProd";
								$resultMSG = "ERROR UPDATE orderInfoProd";
							}else{
								//==============================주문처리 흐름 저장 START========================================//
								orderInfoProdStepFnc($no_oid,$opRow[0],$opRow[1],$orderStep,"입금(결제)완료",$memo,$opRow[2]);
								//주문번호, orderInfoProd uid , 상품번호 , 주문단계, 처리내용 , memo , memberID(카드결제 주문일때 사용)
								//==============================주문처리 흐름 저장 END========================================//
							}
						}

						$resultMSG = "OK";
					}
	// 주문용 SMS 발송
//	===============================================================
if ($row[6]=="Y" && $resultMSG=="OK"){
					// 판매자 검색
					$SQL_MID		= "select cs_name from cs_admin where cs_mid='".$row[3]."' ";
					$row_mid		= mysqli_fetch_array(mysql_query_exe($SQL_MID));

					$member_uid	= base64_encode($row[4]);		// 회원번호 인코딩
					$pidx				= base64_encode(str_replace("-","",$row[5]));		// 회원전화번호 인코딩
					$ssurl			= "http://www.endoshop.co.kr/m/cs/user_agree.php?usridx=".$member_uid."&pidx=".$pidx."";		// 전송할 URL
					$ss				= short_url($ssurl);									// 단축URL

					// SMS 내용 불러오기
//					include $_SERVER["DOCUMENT_ROOT"]."/cs/order_test/sms/sms_txt.php";
					$scSql = mysql_query_exe("SELECT * FROM smsContent WHERE sms_part='A7' AND use_yorn='Y'",$dbconn);
					$scRow = mysqli_fetch_array($scSql);
					$SMS_ORD_TXT = $scRow["content"];
					$temp_sms = $SMS_ORD_TXT;
					$temp_sms = str_replace("[agree_url]",$ss,$temp_sms);
					$temp_sms = str_replace("[pr_name_txt]",$o_pr_name,$temp_sms);
					$temp_sms = str_replace("[ord_amount]",$row[1],$temp_sms);
					$temp_sms = str_replace("[ord_cs_name]",$row_mid[cs_name],$temp_sms);

					$phone		= str_replace("-","",$row[5]);		// 발송할 전화번호
			//		$phone		= "01082099108";				// 발송할 전화번호
					$msg			= $temp_sms;					// 발송할 내용
					if (mb_strlen($msg) > 40) {
						inputMMS($phone, $msg);
					} else {
						inputSMS($phone, $msg);
					}
}
	// 주문용 SMS 발송
//	===============================================================
//					endoshopMail03($row[0]);		//결제완료 메일
//					endoshopSMS02_bank($row[0]);		//주문완료 SMS
				}else{
					$resultMSG = "입금 누적 금액이 맞지 않습니다.";
				}

                echo $resultMSG;                        // 절대로 지우지마세요

		}

//************************************************************************************

        //위에서 상점 데이터베이스에 등록 성공유무에 따라서 성공시에는 "OK"를 이니시스로
        //리턴하셔야합니다. 아래 조건에 데이터베이스 성공시 받는 FLAG 변수를 넣으세요
        //(주의) OK를 리턴하지 않으시면 이니시스 지불 서버는 "OK"를 수신할때까지 계속 재전송을 시도합니다
        //기타 다른 형태의 PRINT( echo )는 하지 않으시기 바랍니다

//      if (데이터베이스 등록 성공 유무 조건변수 = true)
//      {

//                echo $resultMSG;                        // 절대로 지우지마세요

//      }

//*************************************************************************************

}
?>

<?php
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$log = new log();
	
	if($mode == "cancel") {
		// 가입보험내역 검색
		$SQL_V  = " select * ";
		$SQL_V .= " ,( SELECT o_phone FROM tbl_order_list_join WHERE orderno = o.orderno AND chk_join = 'N' ) as o_phone ";
		$SQL_V .= " from tbl_order_list o where orderno in (select orderno from tbl_order_list_join where orderno='".$orderno."' and chk_join='N')";
		//echo $SQL_V;
		$RS_V = $dbcon -> query($SQL_V);
		if (!$RS_V){
			echo "<script>alert('해당 보험내역이 없습니다.');</script>";
			exit;
		}
		$row_r = $dbcon -> fetch_array($RS_V);
		
		$mid = $row_r["pg_id"];
		$tid = $row_r["pg_isdn"];
		$msg = "고객주문 취소".date("His");
		
		//echo $row_r["pg_id"]."<br>";
		//echo $tid."<br>";
	
		/**************************
		 * 1. 라이브러리 인클루드 *
		 **************************/
		require("../../html/insurance/libs/INILib.php");
	
		/***************************************
		 * 2. INIpay41 클래스의 인스턴스 생성 *
		 ***************************************/
		$inipay = new INIpay50;
	
		/*********************
		 * 3. 취소 정보 설정 *
		 *********************/
	  	$inipay->SetField("inipayhome", $_SERVER["DOCUMENT_ROOT"]."/_util/inilite"); // 이니페이 홈디렉터리(상점수정 필요)
	  	$inipay->SetField("type", "cancel");                            // 고정 (절대 수정 불가)
	  	$inipay->SetField("debug", false);                             // 로그모드("true"로 설정하면 상세로그가 생성됨.)
		$inipay->SetField("mid", $mid);                                 // 상점아이디
	 	/**************************************************************************************************
	     * admin 은 키패스워드 변수명입니다. 수정하시면 안됩니다. 1111의 부분만 수정해서 사용하시기 바랍니다.
	     * 키패스워드는 상점관리자 페이지(https://iniweb.inicis.com)의 비밀번호가 아닙니다. 주의해 주시기 바랍니다.
	     * 키패스워드는 숫자 4자리로만 구성됩니다. 이 값은 키파일 발급시 결정됩니다.
	     * 키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오.
	     **************************************************************************************************/
		$inipay->SetField("debug", "ture");
		$inipay->SetField("admin", "1111");
		$inipay->SetField("tid", $tid);                                 // 취소할 거래의 거래아이디
		$inipay->SetField("cancelmsg", iconv("UTF-8","EUC-KR",$msg));                           // 취소사유
	
		/****************
		 * 4. 취소 요청 *
		 ****************/
		$inipay->startAction();
	
		$SQL = "insert into tbl_order_pg_list set";
		$SQL .= " orderno = '".$orderno."' ";
		$SQL .= " ,gubun = '전체취소(관리자)' ";
		$SQL .= " ,pg_isdn = '".$inipay->m_tid."' ";
		$SQL .= " ,pg_con = '".$inipay->getResult('ResultCode')."/".iconv("EUC-KR","UTF-8",$inipay->getResult('ResultMsg'))."' ";
		$SQL .= " ,regdate = now() ";
		$log->log_write("SQL1=".$SQL);
		$RS = $dbcon -> query($SQL);
	
		if ( $inipay->getResult('ResultCode') == "00") {
			$cancle_date = date("Y-m-d H:i:s"); //변수명 주의
			
			//주문취소
			$SQL  = " UPDATE tbl_order_list SET order_step='N',cancle_date= '".$cancle_date."' ";
			$SQL .= " ,cancle_vat_amount= vat_amount-cancle_vat_amount ";
			$SQL .= " ,cancle_amount= t_amount-cancle_amount ";
			$SQL .= " ,vat_amount = vat_amount-cancle_vat_amount ";
			$SQL .= " ,t_amount = t_amount-cancle_amount ";
			$SQL .= " where orderno='".$orderno."' ";
			$log->log_write("SQL2=".$SQL);
			$RS_C1 = $dbcon -> query($SQL);
			
			// 가입원취소
			$SQL  = " update tbl_order_list_join set  join_status='N',cancle_date= '".$cancle_date."' ";
			$SQL .= " ,cancle_vat_amount = vat_amount-cancle_vat_amount ";
			$SQL .= " ,cancle_amount = t_amount-cancle_amount ";
			$SQL .= " ,vat_amount = vat_amount-cancle_vat_amount ";
			$SQL .= " ,t_amount = t_amount-cancle_amount ";
			$SQL .= " where orderno='".$orderno."' ";
			$log->log_write("SQL3=".$SQL);
			$RS_C1 = $dbcon -> query($SQL);
			
			//쿠폰이 있는 경우 돌려준다.
			//추천코드는 무한입력이라 안 돌려줘도 된다.
			if($row_r["sale_gubun"] == "C") {
				$SQL  = " UPDATE tbl_board_coupon_history SET ";
				$SQL .= " orderno = '', discount = 0, s_amount = 0, use_yn = 'N' ";
				if($row_r["new_cp_cd"]){
					$SQL .= " WHERE seq in (".$row_r["new_cp_cd"].") ";
				} else {
					$SQL .= " WHERE seq = '".$row_r["cp_cd"]."' ";
				}
				
				$RS_C1 = $dbcon -> query($SQL);
			}
			
			//가입 시 받은 쿠폰은 사용전 이면 삭제한다.
			$SQL = " DELETE FROM tbl_board_coupon_history WHERE receive_orderno = '".$orderno."' AND use_yn='N' ";
			$dbcon -> query($SQL);
			
			
			//취소 알림톡 전송
			$param = array();
			$param["name"] = all_seed_dec($row_r["o_name"]);
			// $param["pr_name"] = $row_r["pr_name"]." ".$row_r["ins_name"]." ".$row_r["plan_name"];
			$param["pr_name"] = $row_r["plan_name"];
			if($row_r["chk_service"] == "A" || $row_r["chk_service"]) {
				$param["pr_name"] .= " ".$Arr_txt_plus[$row_r["chk_service"]];
			}
			if($row_r["chk_p"] == "Y") { //단기
				$s_date = $row_r["s_date"]." ".$row_r["s_date_time"]."시";
				$e_date = $row_r["e_date"]." ".$row_r["e_date_time"]."시";
			} else if($row_r["chk_p"] == "N") { //장기
				$s_date = $row_r["s_date"];
				$e_date = $row_r["e_date"];
			}
			$param["period"] = $s_date." ~ ".$e_date;
			$param["cancle_date"] = $cancle_date;
			$param["cancle_amount"] = number_format($row_r["t_amount"])."원";
			$mobile = all_seed_dec($row_r["o_phone"]);
			kakaoJoinCancel($param,$mobile);
			
			echo "<script>alert('결제 취소요청이 성공하였습니다.');opener.document.location.reload();;window.close();</script>";
		}else{
		//        echo "결제 취소요청이 실패하였습니다.  <br>";
				echo "<script>alert('결제 취소요청이 실패하였습니다.\\n사유:".iconv("EUC-KR","UTF-8",$inipay->getResult('ResultMsg'))."\\n같은 반복되는 경우 관리자에게 문의하세요.');</script>";
				echo "TX Response_code = " . $inipay->getResult('ResultCode') . "<br>";
				echo "TX Response_msg = " . iconv("EUC-KR","UTF-8",$inipay->getResult('ResultMsg')) . "<p>";
		}
	}
?>
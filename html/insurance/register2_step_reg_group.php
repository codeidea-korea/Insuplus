<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

	if ($_POST["o_name"]){
		$SQL  = "INSERT into tbl_order_group_join_list set ";
		$SQL .= "o_name = '".all_seed_enc($_POST["o_name"])."'";						//대표자명
		$SQL .= ", o_phone = '".all_seed_enc($_POST["o_phone"])."'";					//연락처
		if ($_POST["group_join_type"] == "B2C") {										//가입구분
			$SQL .= ", birthdate = '".$_POST["birthdate"]."'";							//생년월일
		} else {
			$SQL .= ", biz_num = '".$_POST["biz_num"]."'";								//사업자번호
		}
		$SQL .= ", o_email = '".all_seed_enc($_POST["o_email"])."'";					//이메일
		$SQL .= ", purpose = '".$_POST["purpose"]."'";									//출국목적
		for($i=0; $i < count($_POST["compare_seq"]); $i++){								//선택한 플랜 처리
			$k = $i+1;
			$SQL .= ", ins_plan_cd".$k."='".($_POST["compare_seq"][$i] > 0 ? $_POST["compare_seq"][$i] : 0)."'"; 				//플랜 코드
		}
		for($i=0; $i < count($_POST["plan_amount"]); $i++){								//선택한 플랜 처리
			$k = $i+1;
			$SQL .= ", plan_total_ins_amount".$k." = '".($_POST["plan_amount"][$i] > 0 ? $_POST["plan_amount"][$i] : 0)."'";	//플랜 총금액
		}
		$SQL .= ", group_join_cnt = '".$_POST["group_join_cnt"]."'";					//가입인원
		$SQL .= ", listfile = '".$_POST["file_path"]."'";								//견적정보
		$SQL .= ", group_join_type = '".$_POST["group_join_type"]."'";					//단체 가입구분
		$SQL .= ", pr_cd = '".$_POST["PR_SEQ"]."'";										//상품코드
		$SQL .= ", group_join_status = 'N'";											//단체 가입 상태 Y: 가입, W: 입금대기, N: 견적
		$SQL .= ", regdate = now(); ";

		$result = $dbcon -> query($SQL);
		if (!$result) {
			$dbcon -> dbcon_close();
			alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
			echo "false";
			return false;
		}
		$SQL = "SELECT LAST_INSERT_ID();";
		$seq = $dbcon -> query($SQL);
		$insert_seq = $dbcon -> fetch_array($seq);
		$_SESSION["INSERT_SEQ"] = $insert_seq[0];
		$dbcon -> dbcon_close();
		echo "true";
		return true;
	} else {
		echo "false";
		return false;
	}

?>

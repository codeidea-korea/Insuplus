<?
	$act = REQSTR($act, "");
	$seq = REQSTR($seq, "");

	isnull($act);
	isnull($seq);



	if ($act == "delgo") {
		$act_mode = "del_ok";
		$act_MSG = "정말로 삭제 하시겠습니까? 삭제한 정보는 복구가 불가능합니다.";
	}
	else if ($act == "modgo") {
		$act_mode = "mod";
		$act_MSG = "작성시에 입력하셨던 비밀번호를 입력하여 주십시오.";
	}
	else if ($act == "viewgo") {
		$act_mode = "view";
		$act_MSG = "작성시에 입력하셨던 비밀번호를 입력하여 주십시오.";
	}

	include $path_skin_board.$bc_skin."/confirm.php";

?>

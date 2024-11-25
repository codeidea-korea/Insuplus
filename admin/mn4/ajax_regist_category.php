<?php
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out); // 관리자 체크

	// POST로 전달된 값들을 받아옵니다.
	$event_category_master_seq = $_POST['event_category_master_seq'] ?? 0;  // event_category_master_seq 없으면 0으로 초기화
	$depth0 = $_POST['depth0'] ?? [];
	$depth1 = $_POST['depth1'] ?? [];
	$depth2 = $_POST['depth2'] ?? [];
	$depth3 = $_POST['depth3'] ?? [];
	$use_yn = $_POST['use_yn'] ?? [];

	// 예외 처리: depth0 값이 없으면 오류 반환
	if (empty($depth0)) {
		echo json_encode(["result" => "0100", "seq" => 0]);  // 오류: 카테고리 값 누락
		exit;
	}

	try {
		// event_category_master_seq가 없으면 마지막 event_category_seq 값을 조회하여 +1
		if ($event_category_master_seq == 0) {
			$SQL = "SELECT MAX(event_category_master_seq) AS max_seq FROM tbl_event_coupon_category";
			$result = $dbcon->query($SQL);
      $row= $dbcon -> fetch_array($result);

			// 마지막 seq 값에 1을 더한 값 사용
			$event_category_master_seq = $row['max_seq'] !== null ? $row['max_seq'] + 1 : 1;
		}

		// 기존 데이터가 있을 경우 삭제 후 새로 삽입하는 방식
		$SQL = "DELETE FROM tbl_event_coupon_category WHERE event_category_master_seq = '".$event_category_master_seq."'";
		$dbcon->query($SQL);

		// 데이터 삽입 쿼리 작성
		for ($i = 0; $i < count($depth0); $i++) {
			$SQL = "INSERT INTO tbl_event_coupon_category SET ";
			$SQL .= " event_category_master_seq = '".$event_category_master_seq."' ";
      $SQL .= " ,event_category_seq = '".$i."' ";
			$SQL .= " ,depth0 = '".$depth0[$i]."' ";
			$SQL .= " ,depth1 = '".($depth1[$i] ?? '')."' ";  // 값이 없으면 빈 문자열
			$SQL .= " ,depth2 = '".($depth2[$i] ?? '')."' ";  // 값이 없으면 빈 문자열
			$SQL .= " ,depth3 = '".($depth3[$i] ?? '')."' ";  // 값이 없으면 빈 문자열
			$SQL .= " ,use_yn = '".$use_yn[$i]."' ";

			// 쿼리 실행
			$dbcon->query($SQL);
		}

		// 정상 처리 시 JSON 반환
		echo json_encode(["result" => "0000", "seq" => $event_category_master_seq]);

	} catch (Exception $e) {
		// 오류 발생 시 JSON 반환
		echo json_encode(["result" => "0100", "seq" => 0]);
	}
?>

<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out); // 관리자 체크

// 스크립트 실행 시간 제한 제거 (무제한으로 설정)
set_time_limit(0);

// 메모리 제한 늘리기 (512MB로 설정)
ini_set('memory_limit', '512M');

// 다운로드 요청이 있는 경우에만 CSV 생성 및 다운로드 처리
if(isset($_GET['download']) && $_GET['download'] == 'true') {
    $period = isset($_GET['period']) ? $_GET['period'] : '1month';
    
    // 기간에 따른 WHERE 조건 설정
    switch($period) {
        case '1month':
            $where = " AND A.writedate >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) ";
            $title = "최근 1개월 주문 내역";
            break;
        case '3months':
            $where = " AND A.writedate >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) ";
            $title = "최근 3개월 주문 내역";
            break;
        case '6months':
            $where = " AND A.writedate >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH) ";
            $title = "최근 6개월 주문 내역";
            break;
        case '1year':
            $where = " AND A.writedate >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ";
            $title = "최근 1년 주문 내역";
            break;
        default:
            $where = " AND A.writedate >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) ";
            $title = "최근 1개월 주문 내역";
    }

    // 필드 정의 - 원본 코드의 필드 유지
    $field = " A.*, B.*, C.partnership_name as partnership_name, D.guarantee1_ins_seq ";
    $table = " tbl_order_list A inner join tbl_order_list_join B on A.orderno=B.orderno left join tbl_board_partner C ON A.join_ch = C.seq left join tbl_board_plan D on A.plan_cd = D.seq ";
    $orderby = " A.writedate DESC ";
    
    // 파일명 설정
    $filename = $title . '_' . date('Ymd') . '.csv';
    
    // 파일 다운로드 헤더 설정
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    
    // 출력 버퍼 끄기
    if (ob_get_level()) ob_end_clean();
    
    // 출력 버퍼링 끄기
    ob_implicit_flush(true);
    
    // PHP 출력 스트림 생성
    $output = fopen('php://output', 'w');
    
    // UTF-8 BOM 추가 (Excel에서 한글을 제대로 읽기 위함)
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // 파일 제목 첫 줄
    fputcsv($output, [$title]);
    
    // 열 헤더 작성
    $headers = [
        '상품명', '보험사', '플랜명', '게시일', '종료일', '보험기간', 
        '이름', '연락처', '가입채널', '사입상태', '상품가', '가입일'
    ];
    fputcsv($output, $headers);
    
    // 청크 처리 설정
    $chunk_size = 1000; // 한 번에 가져올 레코드 수
    $processed = 0;     // 처리된 레코드 수
    
    // 전체 데이터를 처리할 때까지 반복
    while (true) {
        // LIMIT 절 설정
        $limit = " LIMIT $processed, $chunk_size ";
        
        // 현재 청크의 데이터 가져오기
        $query = "SELECT $field FROM $table WHERE 1=1 $where ORDER BY $orderby $limit";
        $result = $dbcon->query($query);
        
        // 가져온 레코드가 없으면 종료
        if (!$result || $dbcon->num_rows($result) == 0) {
            break;
        }
        
        // 현재 청크의 레코드 수
        $fetched_rows = 0;
        
        // 데이터 행 작성
        while ($data = $dbcon->fetch_array($result)) {
            try {
                $fetched_rows++;
                
                // 데이터가 배열인지 확인
                if (!is_array($data)) {
                    continue;
                }
                
                // 각 필드의 존재 여부를 확인하고 안전하게 접근
                $pr_name = isset($data['pr_name']) ? $data['pr_name'] : '';
                $ins_seq = isset($data['guarantee1_ins_seq']) ? $data['guarantee1_ins_seq'] : '';
                $plan_name = isset($data['plan_name']) ? $data['plan_name'] : '';
                $s_date = isset($data['s_date']) ? $data['s_date'] : '';
                $e_date = isset($data['e_date']) ? $data['e_date'] : '';
                $ins_period = isset($data['ins_period']) ? $data['ins_period'] : '';
                $chk_p = isset($data['chk_p']) ? $data['chk_p'] : '';
                $o_name = isset($data['o_name']) ? $data['o_name'] : '';
                $o_phone = isset($data['o_phone']) ? $data['o_phone'] : '';
                $partnership_name = isset($data['partnership_name']) ? $data['partnership_name'] : '';
                $join_status = isset($data['join_status']) ? $data['join_status'] : '';
                $join_amount = isset($data['join_amount']) ? $data['join_amount'] : 0;
                $join_service = isset($data['join_service']) ? $data['join_service'] : 0;
                $regdate = isset($data['regdate']) ? $data['regdate'] : '';
                
                // 보험기간 형식 조합
                $insurance_period = '';
                if (!empty($ins_period) && !empty($chk_p) && isset($arr_chk_p_gubun[$chk_p])) {
                    $insurance_period = $ins_period . ' ' . $arr_chk_p_gubun[$chk_p];
                }
                
                // 사입상태 조회
                $join_status_text = '';
                if (!empty($join_status) && isset($arr_join_step[$join_status])) {
                    $join_status_text = $arr_join_step[$join_status];
                }
                
                // 보험사 정보 조회
                $insurance_company = '';
                if (!empty($ins_seq) && function_exists('print_ins')) {
                    $insurance_company = print_ins($ins_seq);
                }
                
                // 데이터 행 생성
                $row = [
                    $pr_name,
                    $insurance_company,
                    $plan_name,
                    $s_date,
                    $e_date,
                    $insurance_period,
                    function_exists('all_seed_dec') ? all_seed_dec($o_name) : $o_name,
                    function_exists('all_seed_dec') ? all_seed_dec($o_phone) : $o_phone,
                    $partnership_name,
                    $join_status_text,
                    number_format($join_amount + $join_service),
                    substr($regdate, 0, 10)
                ];
                
                // CSV 행 작성
                fputcsv($output, $row);
                
                // 메모리 정리
                unset($row);
            } catch (Exception $e) {
                // 오류가 발생해도 계속 진행
                continue;
            }
        }
        
        // 처리된 레코드 수 업데이트
        $processed += $fetched_rows;
        
        // 메모리 정리 - 직접 free_result 호출 대신 변수 해제
        // dbcon::free_result()가 없으므로 해당 라인 제거
        unset($result);
        
        // 출력 버퍼 강제 플러시
        flush();
        
        // 가져온 레코드가 청크 크기보다 작으면 모든 데이터 처리 완료
        if ($fetched_rows < $chunk_size) {
            break;
        }
        
        // 가비지 컬렉션 강제 실행(선택적)
        if (function_exists('gc_collect_cycles')) {
            gc_collect_cycles();
        }
    }
    
    // 파일 닫기
    fclose($output);
    exit;
}
?>
<html>
    <head>
    <title>기간별 엑셀 다운로드</title>
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    </head>
    <body>    
    <h2>가입자 csv 기간별 데이터 다운로드</h2>
        
        <div class="form-container">
            <label for="period">기간 선택:</label> 
            <select id="period" name="period">
                <option value="1month">최근 1개월</option>
                <option value="3months">최근 3개월</option>
                <option value="6months">최근6개월</option>
                <option value="1year">1년</option>
            </select>
            
            <button id="downloadBtn">csv 다운로드</button>
        </div>
        
        <script>
        $(document).ready(function() {
            // 다운로드 버튼 클릭 이벤트
            $('#downloadBtn').click(function() {
                var selectedPeriod = $('#period').val();
                window.location.href = 'csv_dump.php?download=true&period=' + selectedPeriod;
            });
        });
        </script>
    </body>
</html>

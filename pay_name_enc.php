<?php include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

set_time_limit(0);
ini_set('memory_limit', '512M');

// 로그 기록 함수
function log_progress($message) {
    // $log_file = $_SERVER["DOCUMENT_ROOT"]."/update_log.txt";
    // file_put_contents($log_file, date('Y-m-d H:i:s')." - ".$message."\n", FILE_APPEND);
    echo $message."<br>";
    // flush();
    // ob_flush();  
}

$batch_size = 5000; // 한 번에 처리할 레코드 수
$offset = 0;
$total_updated = 0;

$oneWeekAgo = date('Y-m-d H:i:s', strtotime('-1 week'));

do {
    // 배치 단위로 데이터 가져오기
    $query = "SELECT seq, pay_name FROM tbl_order_list 
              WHERE writedate < '$oneWeekAgo' 
              LIMIT $batch_size OFFSET $offset";
              
    $result = $dbcon->query($query);
    $count = 0;
    
    while ($row = $dbcon->fetch_array($result)) {
        $seq = $row['seq']; 
        $pay_name = all_seed_enc($row['pay_name']); 
        
        $update_query = "UPDATE tbl_order_list SET pay_name = '$pay_name' WHERE seq = '$seq'";
        $dbcon->query($update_query);
        
        $count++;
        $total_updated++;
    }
    
    log_progress("$batch_size 레코드 중 $count개 처리 완료. 총 $total_updated개 업데이트됨.");
    
    // 다음 배치로 이동
    $offset += $batch_size;
    
    // 메모리 해제
    unset($result);
    
    // 배치 사이에 약간의 일시 정지 (선택사항)
    sleep(1);
    
} while ($count > 0); // 결과가 없을 때까지 계속

log_progress("모든 작업 완료. 총 $total_updated개 업데이트됨.");
?>
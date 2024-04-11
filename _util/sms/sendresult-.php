<?
include '../../../lib/function.php';
include '../../../lib/conf/db_info.php';
include '../../../lib/conf/db/'.$DB[kind].'.php';

$DB_CONNECT = isConnectDb($DB[host],$DB[user],$DB[pass]);
$DB_USEMYDB = isSelecteDb($DB[name],$DB_CONNECT);

session_start();

$fp = fopen("./var/num.txt","w");
fwrite($fp, $reCash);
fclose($fp);

if($Result == 'S00'){ $send_result = "성공";
}else if($Result == 'E00'){ $send_result = "실패";
}else if($Result == 'E01'){ $send_result = "예약오류";
}else if($Result == 'E00'){ $send_result = "캐쉬부족";
}

if($preengage == 'on'){ $sms_type="예약";
} else {  $sms_type="즉시";
}

	if($preengage=='on'){
	$send_time = "$preengage_year-$preengage_month-$preengage_day $preengage_hour:$preengage_min";
	}else {
	$send_time = date("Y-m-d H:i");
	}
	$f_num = $return_phone;
	$content = $CONTENT;


	if($num_list){

		$id = explode("|",$send_id);
		$id_num =sizeof($id);
		$send_num = explode("|",$num_list);
		$num_size = sizeof($send_num)-1;


		for($i=0; $i<$num_size; $i++){

			$QUERY = "INSERT INTO zio_smsinfo (	ID,	SEND_TIME,SEND_RESULT,SEND_WHO,SEND_NUMBER,SEND_CONTENT,SEND_TYPE) VALUES
			('$id[$i]','$send_time','$send_result','$return_phone','$send_num[$i]','$CONTENT','$sms_type')";

			db_query($QUERY , $DB_CONNECT);
		}

	}else if($phone_type || $phone_num){
		$id = $send_id;
		$send_num = "$phone_type-$phone_num";

		$QUERY = "INSERT INTO zio_smsinfo (	ID,	SEND_TIME,SEND_RESULT,SEND_WHO,SEND_NUMBER,SEND_CONTENT,SEND_TYPE) VALUES
		('$id','$send_time','$send_result','$return_phone','$send_num','$CONTENT','$sms_type')";

		db_query($QUERY , $DB_CONNECT);

	}
?>


<script>
window.alert('문자전송이 완료되었습니다.');
</script>

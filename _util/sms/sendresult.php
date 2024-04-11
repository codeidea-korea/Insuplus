<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";


$fp = fopen("./var/num.txt","w");
fwrite($fp, $cols);
fclose($fp);


if($code == '0000'){ $send_result = "성공";
}else if($code == '0001'){ $send_result = "접속에러";
}else if($code == '0002'){ $send_result = "인증에러";
}else if($code == '0003'){ $send_result = "캐쉬부족";
}else if($code == '0004'){ $send_result = "메시지 형식에러";
}else if($code == '0005'){ $send_result = "콜백번호 에러";
}else if($code == '0006'){ $send_result = "수신번호 개수 에러";
}else if($code == '0007'){ $send_result = "예약시간 에러";
}else if($code == '0008'){ $send_result = "잔여콜수 부족";
}else if($code == '0009'){ $send_result = "전송실패";
}

$send_time = date("Y-m-d H:i");

echo "sms_msg : ".$sms_msg."<BR>";

$content = $sms_msg;
echo "content : ".$content."<BR>";


$content = iconv ( "utf-8" , "euc-kr" , $content );
echo "content : ".$content."<BR>";

$content = iconv ( "euc-kr" , "utf-8" , $content );
echo "content : ".$content."<BR>";

$id = explode(",",$send_id);
$id_num =sizeof($id);
$send_num = explode(",",$phone);
$num_size = sizeof($send_num);



for($i=0; $i<$num_size; $i++){

	$QUERY = "INSERT INTO zio_smsinfo (ID,SEND_TIME,SEND_RESULT,SEND_WHO,SEND_NUMBER,SEND_CONTENT,SEND_TYPE) VALUES
	('$id[$i]','$send_time','$send_result','$return_phone','$send_num[$i]','$content','$sms_type')";

	echo $QUERY;

	$dbcon -> query($QUERY);
	//db_query($QUERY , $DB_CONNECT);
}

?>


<script>
window.alert('문자전송이 완료되었습니다.');
</script>

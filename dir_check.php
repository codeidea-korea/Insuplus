<?

$INIpayHome = $_SERVER["DOCUMENT_ROOT"]."/log/pg/vacct/".date("Y")."/".date("m");

if(!is_dir($INIpayHome)) {
	echo "tttt";
	mkdir($INIpayHome, 0777,true);
}

$logfile = fopen($INIpayHome . "/".date("d")."_result.log", "a+");

fwrite($logfile, "************************************************");

fclose($logfile);

/*
$oneMonthAgo = date("Y-m-d",strtotime("-1 month"));

echo $oneMonthAgo;

error_reporting(E_ALL);

ini_set("display_errors", 1);

echo "test1111";
//echo phpinfo();

$connect = mysqli_connect("211.192.250.45:23306","root","!insplus#","insplus");
echo $connect;
 
if(!$connect) {
die('Not connected : ' . mysqli_error());
} else {
echo "success";
} 
 
mysqli_close($connect);
*/




?>
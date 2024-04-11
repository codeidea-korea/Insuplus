<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>

<?
ini_set('memory_limit', -1);


if($mode=="modi") {


function str_con($str)
{
$testlen = strlen($str);
$tests = "";

for($i=0; $i < $testlen; $i++)
{
 $test_substr = substr($str,$i,1);
 if(preg_match("/[0-9]/",$test_substr) == true)
 {
  $str1 .= $test_substr;
 }
}
 return $str1;
}





	error_reporting(E_ALL ^ E_NOTICE);
	require_once $_SERVER[DOCUMENT_ROOT].'/_util/excel/excel_reader2.php';
//echo $_FILES["transport_file1"]."<br>";
//echo $_FILES["transport_file1"]["name"]."<br>";
//exit;
	// 파일이 있는지 확인후 복사
	if($_FILES["transport_file1"]["name"]) {

	    $filename	= $_FILES[transport_file1][name];
	    $tmp_file		= $_FILES[transport_file1][tmp_name];
	    $filesize		= $_FILES[transport_file1][size];

	    //  확장자 체크 : csv파일이 아니면 history(-1)
	    $file_info = explode(".", $filename);

		//파일 타입 설정 (확자자에 따른 구분)
//		$inputFileType = 'Excel2007';
//		if($file_info[1] == "xls") {
//			$inputFileType = 'Excel5';
//		}
//echo $filename."<br>";
//echo $tmp_file."<br>";
//echo $filesize."<br>";
//echo $_FILES[transport_file1][error]."<br>";

	    if($file_info[1] != "xls") {
	        ERROR_BACK("xls 파일만 업로드 가능합니다.");
	        exit;
	    }

//echo $file_info[1]."<br>";
	    $filename = "excel_m_".date("YmdHis",time()).".".$file_info[1];

	    move_uploaded_file($tmp_file, $_SERVER[DOCUMENT_ROOT]."/_data/excel/$filename");   //파일복사
	    @unlink($tmp_file);

	} else {

	    ERROR_BACK("xls 파일이 없습니다.");
	    exit;

	}

	$url = $_SERVER[DOCUMENT_ROOT]."/_data/excel/".$filename;


	// 엑셀로더 모듈 로딩
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('UTF-8');	// 인코딩 EUC-KR
	$data->setRowColOffset(0);
	$data->read($url);
	error_reporting(E_ALL ^ E_NOTICE);

	echo "진행중입니다.";

	//엑셀 범위 읽어내기
	for($i=1;$i<$data->sheets[0]['numRows'];$i++) {



			//$data->sheets[0]['cells'][$i][0];		// 순번
			//$data->sheets[0]['cells'][$i][1];		// 정류소ID
			//$data->sheets[0]['cells'][$i][2];		// 자치구
			//$data->sheets[0]['cells'][$i][3];		// 동명
			//$data->sheets[0]['cells'][$i][4];		// 정류소명
			//$data->sheets[0]['cells'][$i][5];		// 상세주소
			//$data->sheets[0]['cells'][$i][6];		// 타입
			//$data->sheets[0]['cells'][$i][7];		// 등급
			//$data->sheets[0]['cells'][$i][8];		// 매체코드
			//$data->sheets[0]['cells'][$i][9];		// 조명여부
			//$data->sheets[0]['cells'][$i][10];		// 단가
			//$data->sheets[0]['cells'][$i][11];		// 내경
			//$data->sheets[0]['cells'][$i][12];		// 외경
			//$data->sheets[0]['cells'][$i][13];		// 경유노선
			//$data->sheets[0]['cells'][$i][14];		// 유동인구
			//$data->sheets[0]['cells'][$i][15];		// 주변환경
			//$data->sheets[0]['cells'][$i][16];		// 계약정보
			$cell1			= $data->sheets[0]['cells'][$i][0];		// 순번
			$cell2			= $data->sheets[0]['cells'][$i][1];		// 정류소ID
			$cell3			= $data->sheets[0]['cells'][$i][2];		// 자치구
			$cell4			= $data->sheets[0]['cells'][$i][3];		// 동명
			$cell5			= $data->sheets[0]['cells'][$i][4];		// 정류소명
			$cell6			= $data->sheets[0]['cells'][$i][5];		// 상세주소
			$cell7			= $data->sheets[0]['cells'][$i][6];		// 타입
			$cell8			= $data->sheets[0]['cells'][$i][7];		// 등급
			$cell9			= $data->sheets[0]['cells'][$i][8];		// 매체코드
			$cell10		= $data->sheets[0]['cells'][$i][9];		// 조명여부
			$cell11		= $data->sheets[0]['cells'][$i][10];		// 단가
			$cell12		= $data->sheets[0]['cells'][$i][11];		// 내경
			$cell13		= $data->sheets[0]['cells'][$i][12];		// 외경
			$cell14		= $data->sheets[0]['cells'][$i][13];		// 경유노선
			$cell15		= $data->sheets[0]['cells'][$i][14];		// 유동인구
			$cell16		= $data->sheets[0]['cells'][$i][15];		// 주변환경
			$cell17		= $data->sheets[0]['cells'][$i][16];		// 계약정보

			// 다음 API에서 좌표 구해오기
			$excode = "";
			$xml_url = "http://apis.daum.net/local/geo/addr2coord?apikey=fffd9296aa4f4a7ea94bf78d3fc603c4&q=".urlencode($cell6)."&output=xml";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $xml_url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$g = curl_exec($ch);
			curl_close($ch);
			$xml = simplexml_load_string($g,'SimpleXMLElement',LIBXML_NOWARNING)  or die("Error: Cannot create object");
			//echo $xml->item[0]->lng;
			//echo $xml->item[0]->lat;
			$excode = $xml->item[0]->lat."|".$xml->item[0]->lng;

			$SQL = "insert into tbl_board_area set
			subject = '".$cell5.$cell9."'
			, content = '&nbsp;'
			, ext1 = '".$cell1."'
			, ext2 = '".$cell2."'
			, ext3 = '".$cell3."'
			, ext4 = '".$cell4."'
			, ext5 = '".$cell5."'
			, ext6 = '".$cell6."'
			, ext7 = '".$cell7."'
			, ext8 = '".$cell8."'
			, ext9 = '".$cell9."'
			, ext10 = '".$cell10."'
			, ext11 = '".$cell11."'
			, ext12 = '".$cell12."'
			, ext13 = '".$cell13."'
			, ext14 = '".$cell14."'
			, ext15 = '".$cell15."'
			, ext16 = '".$cell16."'
			, ext17 = '".$cell17."'
			, excode = '".$excode."'
			, writer = 'admin'
			, nick_name = 'admin'
			";
			$result = $dbcon -> query($SQL);
			if (!$result) {
				$dbcon -> dbcon_close();
//				alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
				echo $SQL."<br>";
				exit;
			}


	}
//print_r($test);

//echo "업로드 완료";
//exit;


	ERROR_BACK("엑셀데이터가 정상적으로 업로드 되었습니다.");
	exit;
}
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="/_css/admin.css" rel="stylesheet" type="text/css">
<meta content="IE=9" http-equiv="X-UA-Compatible" />
<?php getLib();?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script type="text/javascript" src="/js/ecaso.js"></script>
<script src="/admin/js/admin.js"></script>
</head>
<body style="background-color:#FFFFFF;">

<table width="100%">
	<tr>
		<td>
			<ul class="menu_tab_01">
				<li class="on">
					<span class="on"><a href="#void">매체자료 업로드</a></span>
				</li>
			</ul>
		</td>
	</tr>
	<tr>
		<td style="padding:10px 0;"></td>
	</tr>
	<tr>
		<td style="padding:0px 10px;">



<div class="popup_con_wrap" style="height:303px; margin:0px;padding:4px;background:#FFFFFF;">




	<div style="height:5px;"></div>

<script language="javascript">
function goOpen() {
	var form1 = document.frmChk;

	if(!form1.transport_file1.value) {
		window.alert ("업로드 할 자료 파일을 선택해주세요");
		form1.transport_file1.focus();
		return false;
	}

	form1.submit();
}
</script>

<form name="frmChk" action="?mode=modi" method="post" ENCTYPE="multipart/form-data" onsubmit="return false;">
<input type="hidden" name="MAX_FILE_SIZE" value="300000000">
	<div style="height:5px;"></div>

	<div style="background:#9b9b9b; color:#FFFFFF; font-weight:bold; padding:5px 0 2px 5px;">자료 파일 업로드</div>
	<table class="order_detail_03">
		<colgroup>
			<col width="" />
			<col width="" />
		</colgroup>

		<tr>
			<th width="150px">자료 파일</th>
			<td style="text-align:left;">
			  <input type="file" name="transport_file1" class="file_M" size="40" />
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<span class="inp_black1"><input type="button" value="자료 업로드" onClick="goOpen()"></span>
			</td>
		</tr>
	</table>
	<div style="height:10px;"></div>
</div>

</form>


		</td>
	</tr>
</table>

</BODY>
</html>

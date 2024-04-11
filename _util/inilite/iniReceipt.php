<?php
/* * INIlite_receipt.php
 *	현금영수증 발행모듈
 */
session_start();
include $_SERVER["DOCUMENT_ROOT"]."/shop/_common/dbconn2.php";
include $_SERVER["DOCUMENT_ROOT"]."/shop/_common/function.php";

if (!$orderno){
	ERROR_BACK("잘못된 주문코드입니다.");
	exit;
}

$query = "select * from orderInfo where orderno='".$orderno."' ";
$sql = mysql_query_exe($query,$dbconn);
$row = mysqli_fetch_array($sql);
if (!$row[orderno]){
	ERROR_BACK("잘못된 주문코드입니다.");
	exit;
}

if ($row[cash_receipt]=="Y"){
	ERROR_BACK("이미 신청된 주문코드입니다.");
	exit;
}

	/**************************
	 * 1. 라이브러리 인클루드 *
	 **************************/
	require("./libs/INILiteLib.php");


	/***************************************
	 * 2. INILite 클래스의 인스턴스 생성 *
	 ***************************************/
	$inipay = new INILite;

    if ($mid == "IESNAtural") {
        $merchantkey = "K0o1L2x2NXU5T2xWYlF2ZGJiWG1PQT09"; //상점 수정 필요
    } else {
        $merchantkey = "NTdLcTA3MDBhQTQ2dHlRbVBWdHVCdz09"; //상점 수정 필요
    }

	/*********************
	 * 3. 지불 정보 설정 *
	 *********************/
	//$inipay->m_inipayHome = "/home/endoshop/public_html/shop/_util/inilite/"; 	//상점 수정 필요
	$inipay->m_inipayHome = $_SERVER["DOCUMENT_ROOT"]."/log/pg";
	$inipay->m_key = $merchantkey;  		//상점 관리자에서 발급된 가맹점의 대칭키를 설정 합니다.(merchantkey)
	$inipay->m_pgId = "INIphpCASH";      				// 고정 (절대 수정 불가)
	$inipay->m_ssl = "true"; 					//ssl지원하면 true로 셋팅해 주세요.
	$inipay->m_type = "receipt"; 					// 고정 (절대 수정 불가) // 현금영수증 : receipt
	$inipay->m_log = "true";              				// true로 설정하면 로그가 생성됨(적극권장)
	$inipay->m_debug = "true";              			// 로그모드("true"로 설정하면 상세로그가 생성됨. 적극권장)
	$inipay->m_mid = $mid; 						// 상점아이디
	$inipay->m_uip = getenv("REMOTE_ADDR"); 			// 고정 (절대 수정 불가)
	$inipay->m_currency = $currency;				// 화폐단위
	$inipay->m_price = $cr_price;					// 취소할금액
	$inipay->m_goodName = $goodname;                          	// 상품명
	$inipay->m_sup_price = $sup_price;                         	// 공급가액
	$inipay->m_tax = $tax;                                          // 부가세
	$inipay->m_srvc_price = $srvc_price;                        	// 봉사료
	$inipay->m_buyerName = $buyername;                         	// 구매자 성명
	$inipay->m_buyerEmail = $buyeremail;                        	// 구매자 이메일 주소
	$inipay->m_buyerTel = $buyertel;                          	// 구매자 전화번호
	$inipay->m_reg_num = $reg_num;                                  // 현금결제자 주민등록번호
	$inipay->m_useopt = $useopt;                                    // 현금영수증 발행용도 ("0" - 소비자 소득공제용, "1" - 사업자 지출증>
	$inipay->m_payMethod = $paymethod;
	$inipay->m_encrypted = $encrypted;


	/****************
	 * 4. 지불 요청 *
	 ****************/
	$inipay->startAction();


// 성공시 DB 처리
if($inipay->m_resultCode == "00"){

	if ($orderno){
	$SQL = " update orderInfo  set cash_receipt = 'Y' , cash_receipt_date = now() where orderno='".$orderno."' ";
	$result = mysql_query_exe($SQL,$dbconn);
	}

}
?>


<!-------------------------------------------------------------------------------------------------------
 *  													*
 *       												*
 *        												*
 *	아래 내용은 결제 결과에 대한 출력 페이지 샘플입니다. 				                *
 *													*
 *													*
 *													*
 -------------------------------------------------------------------------------------------------------->

<html>
<head>
<title>INILITE-INIAPI PHP 현금영수증 발행</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<link rel="stylesheet" href="css/group.css" type="text/css">
<style>
body, tr, td {font-size:10pt; font-family:굴림,verdana; color:#433F37; line-height:19px;}
table, img {border:none}

/* Padding ******/
.pl_01 {padding:1 10 0 10; line-height:19px;}
.pl_03 {font-size:20pt; font-family:굴림,verdana; color:#FFFFFF; line-height:29px;}

/* Link ******/
.a:link  {font-size:9pt; color:#333333; text-decoration:none}
.a:visited { font-size:9pt; color:#333333; text-decoration:none}
.a:hover  {font-size:9pt; color:#0174CD; text-decoration:underline}

.txt_03a:link  {font-size: 8pt;line-height:18px;color:#333333; text-decoration:none}
.txt_03a:visited {font-size: 8pt;line-height:18px;color:#333333; text-decoration:none}
.txt_03a:hover  {font-size: 8pt;line-height:18px;color:#EC5900; text-decoration:underline}
</style>

<script>
	var openwin=window.open("childwin.html","childwin","width=299,height=149");
	openwin.close();

</script>

<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>
<body bgcolor="#FFFFFF" text="#242424" leftmargin=0 topmargin=15 marginwidth=0 marginheight=0 bottommargin=0 rightmargin=0><center>
<table width="632" border="0" cellspacing="0" cellpadding="0">
  <tr>
  <?    // 지불 수단에 따라 상단 이미지가 변경된다.
   	$background_img = "./sample/img/spool_top.gif";    //default image

   	if ($inipay->m_resultCode =="01"){
   	    $background_img = "./sample/img/card.gif";
   	}
?>
    <td height="83" background="<?=$background_img?>"style="padding:0 0 0 64">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="3%" valign="top"><img src="./sample/img/title_01.gif" width="8" height="27" vspace="5"></td>
          <td width="97%" height="40" class="pl_03"><font color="#FFFFFF"><b>현금결제 영수증 발급결과</b></font></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td align="center" bgcolor="6095BC">
      <table width="620" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td bgcolor="#FFFFFF" style="padding:0 0 0 56">
		  <table width="510" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="7"><img src="./sample/img/life.gif" width="7" height="30"></td>
                <td background="./sample/img/center.gif"><img src="./sample/img/icon03.gif" width="12" height="10">
                 <b>
	                 <?
		                 if($inipay->m_resultCode == "00"){
		             ?>
		                 	고객님께서 요청하신 현금영수증 발급이 성공되었습니다.
		             <?  }else{?>
		                	고객님께서 요청하신 현금영수증 발급이 실패되었습니다.
		             <? }?>
                 </b></td>
                <td width="8"><img src="./sample/img/right.gif" width="8" height="30"></td>
              </tr>
            </table>
            <br>
            <table width="510" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="407"  style="padding:0 0 0 9"><img src="./sample/img/icon.gif" width="10" height="11">
                  <strong><font color="433F37">발 급 내 역</font></strong></td>
                <td width="103">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2"  style="padding:0 0 0 23">
		  <table width="470" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td width="18" align="center"><img src="./sample/img/icon02.gif" width="7" height="7"></td>
                      <td width="109" height="26">결 과 코 드</td>
                      <td width="343"><?php echo($inipay->m_resultCode); ?></td>
                    </tr>
                     <tr>
                      <td height="1" colspan="3" align="center"  background="./sample/img/line.gif"></td>
                    </tr>
                    <tr>
                      <td width="18" align="center"><img src="./sample/img/icon02.gif" width="7" height="7"></td>
                      <td width="109" height="25">결 과 내 용</td>
                      <td width="343"><?php echo($inipay->m_resultMsg); ?></td>
                    </tr>
                    <tr>
                      <td height='1' colspan='3' align='center'  background='./sample/img/line.gif'></td>
                    </tr>
                    <tr>
                      <td width="18" align="center"><img src="./sample/img/icon02.gif" width="7" height="7"></td>
                      <td width="109" height="25">거 래 번 호</td>
                      <td width="343"><?php echo($inipay->m_tid); ?></td>
                    </tr>
                    <tr>
                      <td height="1" colspan="3" align="center"  background="./sample/img/line.gif"></td>
                    </tr>
                    <tr>
                      <td width='18' align='center'><img src='./sample/img/icon02.gif' width='7' height='7'></td>
                      <td width='109' height='25'>승 인 번 호</td>
                      <td width='343'><?php echo($inipay->m_applnum); ?></td>
                    </tr>
                    <tr>
                      <td height="1" colspan="3" align="center"  background="./sample/img/line.gif"></td>
                    </tr>
                    <tr>
                      <td width='18' align='center'><img src='./sample/img/icon02.gif' width='7' height='7'></td>
                      <td width='109' height='25'>승 인 날 짜</td>
                      <td width='343'><?php echo($inipay->m_pgAuthDate); ?></td>
                    </tr>
                    <tr>
                      <td height="1" colspan="3" align="center"  background="./sample/img/line.gif"></td>
                    </tr>
                    <tr>
                      <td width='18' align='center'><img src='./sample/img/icon02.gif' width='7' height='7'></td>
                      <td width='109' height='25'>승 인 시 각</td>
                      <td width='343'><?php echo($inipay->m_pgAuthTime); ?></td>
                    </tr>
                    <tr>
                      <td height="1" colspan="3" align="center"  background="./sample/img/line.gif"></td>
                    </tr>
                    <tr>
                      <td width='18' align='center'><img src='./sample/img/icon02.gif' width='7' height='7'></td>
                      <td width='109' height='25'>총 현금결제금액</td>
                      <td width='343'><?php echo($inipay->m_cshr_applprice); ?></td>
                    </tr>
                    <tr>
                      <td height="1" colspan="3" align="center"  background="./sample/img/line.gif"></td>
                    </tr>
                    <tr>
                      <td width='18' align='center'><img src='./sample/img/icon02.gif' width='7' height='7'></td>
                      <td width='109' height='25'>공 급 가 액</td>
                      <td width='343'><?php echo($inipay->m_cshr_supplyprice); ?></td>
                    </tr>
                    <tr>
                      <td height="1" colspan="3" align="center"  background="./sample/img/line.gif"></td>
                    </tr>
                    <tr>
                      <td width='18' align='center'><img src='./sample/img/icon02.gif' width='7' height='7'></td>
                      <td width='109' height='25'>부 가 세</td>
                      <td width='343'><?php echo($inipay->m_cshr_tax); ?></td>
                    </tr>
                    <tr>
                      <td height="1" colspan="3" align="center"  background="./sample/img/line.gif"></td>
                    </tr>
                    <tr>
                      <td width='18' align='center'><img src='./sample/img/icon02.gif' width='7' height='7'></td>
                      <td width='109' height='25'>봉 사 료</td>
                      <td width='343'><?php echo($inipay->m_cshr_serviceprice); ?></td>
                    </tr>
                    <tr>
                      <td height="1" colspan="3" align="center"  background="./sample/img/line.gif"></td>
                    </tr>
                    <tr>
                      <td width='18' align='center'><img src='./sample/img/icon02.gif' width='7' height='7'></td>
                      <td width='109' height='25'>사 용 용 도</td>
                      <td width='343'><b><font color=blue>(<?
                      				if($inipay->m_cshr_type == "0"){
                      				?>
                      					소비자 소득공제용
                      				<?}else{?>
                      					사업자 지출증빙용
                      				<?}?>)</font></b></td>
                    </tr>
                    <tr>
                      <td height='1' colspan='3' align='center'  background='./sample/img/line.gif'></td>
                    </tr>
                   </table></td>
              </tr>
            </table>
            <br>
          </td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><img src="./sample/img/bottom01.gif" width="632" height="13"></td>
  </tr>
</table>
</center></body>
</html>



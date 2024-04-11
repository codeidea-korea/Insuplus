<?
$mail_skin["header"] = "
<html>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
<link href='{$sc_site_url}/inc/css/mail.css' rel='stylesheet' type='text/css' />
<title>(주)신도컴퓨터</title>
</head>
<body onload='InitializeStaticMenu();resize();'>
<table width='650' cellpadding='0' cellspacing='0' border='0'>
	<tr>
		<td><img src='{$sc_site_url}/images/mail_top_img.jpg' /></td>
	</tr>
	<tr>
		<td background='{$sc_site_url}/images/mail_line_bg.jpg' align='center'>
";




$mail_skin["body"]["board"]["write_ok"] = "

			<table width=100% border=0 align=center cellpadding=5 cellspacing=0 style='font-size:9pt;color:#666666'>
				<TR bgcolor='#999999'>
					<TD colspan='2' height='1'></TD>
				</TR>

				<tr height='30'>
					<td colspan='2' align='center'>
						<b>[mail_subject] [<?=date('Y-m-d H:i:s')?>]</b>
					</td>
				</tr>
				<TR bgcolor='#999999'>
					<TD colspan='2' height='1'></TD>
				</TR>


				<tr height='30'>
					<td width='150' style='padding-right:15px;' align='right'>
						<b>제목</b> :
					</td>
					<td>
						[subject]
					</td>
				</tr>
				<TR bgcolor='#e7e7e7'>
					<TD colspan='2' height='1'></TD>
				</TR>
				<tr height='30'>
					<td width='150' style='padding-right:15px;' align='right'>
						<b>작성자</b> :
					</td>
					<td>
						[nick_name]
					</td>
				</tr>
				<TR bgcolor='#e7e7e7'>
					<TD colspan='2' height='1'></TD>
				</TR>
				<tr height='30'>
					<td width='150' style='padding-right:15px;' align='right'>
						<b>내용</b> :
					</td>
					<td>
						<p>[content]</p>
					</td>
				</tr>
				<TR bgcolor='#e7e7e7'>
					<TD colspan='2' height='1'></TD>
				</TR>


				<tr height='30'>
					<td width='150' style='padding-right:15px;' align='right'>
						<b>바로가기</b> :
					</td>
					<td>
						<p><a href='[goURL]' target='_blank'>[goURL]</a></p>
					</td>
				</tr>
				<TR bgcolor='#e7e7e7'>
					<TD colspan='2' height='1'></TD>
				</TR>
			</table>

";


$mail_skin["body"]["buy_ok"] = "
			<table width='90%' cellpadding='0' cellspacing='0' border='0'>
				<tr>
					<td>

						<table width='90%' cellpadding='0' cellspacing='1' border='0' bgcolor='#CCCCCC'>
							<tr>
								<td class='tit_css' height='30' align='center' bgcolor='#f1f1f1'>'STONE CASE주문에 대한 입금이 확인되었습니다.'</td>
							</tr>
						</table>
						<br/>

					</td>
				</tr>
				<tr>
					<td>

						<table width='90%' cellpadding='0' cellspacing='1' border='0' bgcolor='#CCCCCC'>
							<tr>
								<td class='table_tit' height='30' align='center' bgcolor='#FFFFFF' width='70%'>상품명</td>
								<td class='table_tit' height='30' align='center' bgcolor='#FFFFFF' width='10%'>수량</td>
								<td class='table_tit' height='30' align='center' bgcolor='#FFFFFF' width='20%'>금액</td>
							</tr>
							<tr>
								<td class='table_list' height='30' align='left' bgcolor='#FFFFFF' style='padding-left:20px;'>상품명 테스트</td>
								<td class='table_list' height='30' align='center' bgcolor='#FFFFFF'>1개</td>
								<td class='table_list' height='30' align='center' bgcolor='#FFFFFF'>10,000,000원</td>
							</tr>
						</table>

					</td>
				</tr>
				<tr>
					<td height='30'>&nbsp;</td>
				</tr>
				<tr>
					<td class='mail_content'>

						<span class='cont_tit'>- 결제방법:</span> 무통장 입금 : 국민은행 474501-01-048935 (예금주:김사량 (코리아 K)) <br />
						<span class='cont_tit'>- 결제금액:</span> 48,500  <br />
						<span class='cont_tit'>- 적립금액:</span> 1,000원 (발송 후 적립금이 추가됩니다.) <br />
						<span class='cont_tit'>- 배달주소:</span> 우편번호 : 152-777 주소 : 서울 구로구 구로3동 222-8 코오롱디지털타워빌란트 2차 907호 <br />
						<span class='cont_tit'>- 받는사람:</span> 전한솔 <br />
						<span class='cont_tit'>- 전화번호:</span> 010-9098-8918 02-2081-1673


					</td>
				</tr>
				<tr>
					<td height='30'>&nbsp;</td>
				</tr>
				<tr>
					<td align='center' class='ps_txt'>2009/05/11 주문에 대한 입금이 확인되었습니다.<br/>물품확인 후 바로 배송해 드리겠습니다.</br>감사합니다.</td>
				</tr>
			</table>
";


$mail_skin["footer"] = "
		</td>
	</tr>
	<tr>
		<td background='{$sc_site_url}/images/mail_btm_img.jpg' height='171' style='padding:70px 0 0 230px;' class='copy_txt'>
			주소 : 서울시 서초구 서초동 1604-22 신도빌딩 5층<br/>대표전화 : 02-3488-9000
		</td>
	</tr>
</table>
</body>
</html>
";


//echo $mail_skin["header"];
//echo $mail_skin["body"]["buy_ok"];
//echo $mail_skin["footer"];

?>
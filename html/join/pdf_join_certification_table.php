<!DOCTYPE html>
<html lang="ko">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
	<title>인슈플러스 가입완료 이메일</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,600|Noto+Sans+KR:100,200,300,400,500,600,700&display=swap" rel="stylesheet">
	<script language="JavaScript">
		function setCookie(name, value, expiredays) {
			var todayDate = new Date();
			todayDate.setDate(todayDate.getDate() + expiredays);
			document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";"
		}

		function closeWin() {
			setCookie("Notice", "done", 1); //1은 하루동안 새창을 열지 않게 합니다.
			window.close();
		}
	</script>
	<style>
		* {
			margin: 0;
			padding: 0
		}

		@page {
			margin: 0;
			padding: 0;
		}

		body {
			margin: 0;
			font-family: "NanumGothic", "Roboto";
			font-size: 12px;
			text-align: center;
			color: #000;
		}
	</style>
</head>

<body style="margin:0; padding:0;">
	<div style="width:100%; text-align:left;">
		<div style="width:100%; margin:0 0 15px 0; border-bottom:2px solid #dc3347">
			<img src="[DROOT]/html/images/join_certification_title1_190909.png" width="100%" />
		</div>
		<div style="padding:0 18px;">
			<h3 style='font-size:15px;font-weight: 400;margin: 0px 0px 18px 0;'>코리아 어시스턴스는 <br>해외에서 발생하는 개인의 질병/상해 사고를 24시간 알람센터에서 대응하여, 안전하게 귀국할 수 있도록 도와드립니다.</h3>
			<h4 style='font-size: 14px;font-weight: 400;margin: 0px 0px 5px 0;'>가입정보</h4>
			<table width="100%" cellspacing='0' cellpadding='0' border='0' style='border-top: solid 2px #595959;border-bottom: solid 2px #595959; margin:0 0 14px 0;'>
				<tr>
					<th width="60" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>계약자명</th>
					<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[CONTRACTOR_KR]</td>
					<th width="60" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>가입기간</th>
					<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px;font-size:14px;'>[INS_PERIOD]</td>
				</tr>
				<tr>
					<th width="60" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>가입자</th>
					<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[USER_NAME]</td>
					<th width="60" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>생년월일</th>
					<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[USER_BIRTH]</td>
				</tr>
				<tr>
					<th width="60" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>연락처</th>
					<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[USER_PHONE]</td>
					<th width="60" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>이메일</th>
					<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-left: 1px solid #d6d6d6;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[USER_EMAIL]</td>
				</tr>
				<tr>
					<th width="60" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>상품플랜</th>
					<td colspan="3" style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[USER_TYPE] [PLAN_NAME] [INS_NAME]</td>
				</tr>
				<tr>
					<th width="60" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>가입번호</th>
					<td colspan="3" style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[ORDERNO]</td>
				</tr>
			</table>

			[CHK_SERVICE]

			<h5 style='font-size: 14px;font-weight:400;background-color: #355dab;color: #fff;padding: 6px 32px;border-radius: 8px;margin:20px 0 20px 0;'>[INS_NAME] 가입증명서</h5>

			<table width="100%" cellspacing='0' cellpadding='0' border='0' style='border-top:solid 2px #595959;border-bottom:solid 2px #595959;'>
				<colgroup>
					<col width='20%' />
					<col width='30%' />
					<col width='20%' />
					<col width='30%' />
				</colgroup>
				<tr>
					<th style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:14px;font-weight:400;text-align:center;'>피보험자</th>
					<td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:14px;font-weight:400;text-align:left;'>[USER_JNAME]</td>
					<th style='background-color: #f6f6f6;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:14px;font-weight:400;text-align:center;'>증권번호</th>
					<td style='background-color: #fff;padding:5px 15px;border-bottom:1px solid #d6d6d6;font-size:14px;font-weight:400;text-align:left;'>[STOCK_NO]</td>
				</tr>
				<tr>
					<th style='background-color: #f6f6f6;padding:5px 15px;font-size:14px;font-weight:400;text-align:center;'>보험기간</th>
					<td style='background-color: #fff;padding:5px 15px;font-size:12px;font-weight:400;text-align:left; letter-spacing:-1px;'>[INS_PERIOD]</td>
					<th style='background-color: #f6f6f6;padding:5px 15px;font-size:14px;font-weight:400;text-align:center;'>해외 체류국가</th>
					<td style='background-color: #fff;padding:5px 15px;font-size:14px;font-weight:400;text-align:left;'>[NATION_NAME]</td>
				</tr>
			</table>
			<h3 style='font-size:15px;font-weight: 400; font-family: "NanumGothic";line-height: 1.5;margin:12px 0 5px 0;'>상기 고객을 피보험자로 하여 아래와 같이 <span style='color:#dc3347'>[INS_NAME]</span>에 가입되었음을 확인 합니다.</h3>
			<p style='font-size:12px;margin:0 0 30px 0;color:#333;'>보험계약의 자세한 사항에 대하여는 <span style='color:#dc3347'>[INS_NAME]</span> 약관을 참조하시기 바랍니다.<br> 코리아 어시스턴스는 다음의 담보내용과 보상한도액에 의거하여 서비스를 제공합니다.</p>
			[G_LIST]
			<p style='font-size:12px;margin:8px 0 18px 0;color:#333;'>이 보험계약은 보험계약자 및 상품가격 납부자가 코리아 어시스턴스인 단체보험으로 예금자보험법에 의거, 예금자보호대상에서 제외됩니다.</p>

			<h3 style='font-size:15px;font-weight: 400; font-family: "NanumGothic";line-height: 1.5;margin:0px 0 5px 0;'>유의사항</h3>
			<ul style='text-align: left; margin: 0px 0 0 20px;padding: 0;line-height: 1.75;color: #333; font-size: 13px;'>
				<li>만 15세 미만은 상해사망 / 질병사망 담보가 적용되지 않습니다. (상법 732조)</li>
				<li>해외의료실비
					<dl style='margin:0;padding:0 0 0 15px;'>
						<dd>- 피보험자가 해외여행 중에 입은 상해 또는 질병으로 인하여 해외의료기관에서 치료를 받은 경우 가입한도 내 실제 부담한 의료비를 보상합니다.</dd>
					</dl>
				</li>
				<li>국내의료실비(급여/비급여)
					<ol style='margin:0;padding:0 0 0 15px;'>
						<li>급여
							<dl style='margin:0;padding:0 0 0 15px;'>
								<dd>- 피보험자가 해외여행 중에 입은 상해 또는 질병으로 인하여 국내의료기관에 입원 또는 통원하여 급여 치료를 받거나 처방조제를 받은 경우에 보상합니다.<br />
									&nbsp&nbsp<b>(입원 : 의료급여 중 본인이 부담한 의료비의 80%보상 / 통원 및 처방조제 : 병원규모별 1~2만원과 보장대상 의료비의 20%중 큰 금액을 차감한 금액 보상)</b></dd>
							</dl>
						</li>
						<li>비급여 (3대 비급여 제외)
							<dl style='margin:0;padding:0 0 0 15px;'>
								<dd>- 피보험자가 해외여행 중에 입은 상해 또는 질병으로 인하여 국내의료기관에 입원 또는 통원하여 비급여 치료를 받거나 처방조제를 받은 경우에 보상합니다.<br />
									&nbsp&nbsp<b>(입원 : 비의료급여 중 본인이 부담한 의료비의 70%보상 / 통원 및 처방조제 : 병원규모별 3만원과 보장대상 의료비의 30%중 큰 금액을 차감한 금액 보상)</b></dd>
							</dl>
						</li>
					</ol>
				</li>
				<li>휴대품 파손/도난
					<dl style='margin:0;padding:0 0 0 15px;'>
						<dd>- 여행중 휴대한 물품 파손, 도난인 경우 사용기간을 감안한 감가상각한금액, 파손의 경우 수리비용 보상<br />&nbsp&nbsp(휴대물품당 1개/1쌍당 20만원, 자기부담금 1만원. 현금, 신용카드, 여권은 보상되지 않음)</dd>
					</dl>
				</li>
			</ul>
			<p style="text-align:right; padding:20px 20px;"><img src="[DROOT]/_data/board/ins_list/[SIGNIMG]" align="absmiddle">
			<p>
		</div>


		<table width="100%" cellspacing='0' cellpadding='0' border='0' style="background:#29354c; padding:20px;">
			<tr valign='middle'>
				<td style='text-align:left;'>
					<img src="[DROOT]/html/images/footer-logo1.png" align="absmiddle">
				</td>
				<td style='text-align:right;color:#fff;'>
					https://koreaassistance.co.kr<br>
					<strong style='font-size:14px;'>Tel: +82 2 360 2545</strong><br>B1, 7,Chungjeong-ro, Seodaemun-gu, Seoul, Korea
				</td>
			</tr>
		</table>
	</div>

</body>

</html>

<!--
<?php
//include '../_include/_footer.html';
?>

<script src="../_js/pdf/jspdf.min.js"></script>
<script src="../_js/pdf/from_html.js"></script>
<script src="../_js/pdf/split_text_to_size.js"></script>
<script src="../_js/pdf/standard_fonts_metrics.js"></script>
<script>
	var doc = new jsPDF();
	var elementHandler = {
	  '#ignorePDF': function (element, renderer) {
		return true;
	  }
	};
	var source = window.document.getElementsByTagName("body")[0];
	doc.fromHTML(
		source,
		15,
		15,
		{
		  'width': 180,'elementHandlers': elementHandler
		});

	doc.output("dataurlnewwindow");
</script>
-->
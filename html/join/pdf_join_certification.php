<!DOCTYPE html>
<html lang="ko">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
        <title>인슈플러스 가입완료 이메일</title>
		<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,600|Noto+Sans+KR:100,200,300,400,500,600,700&display=swap" rel="stylesheet">
		<script language="JavaScript">
			function setCookie( name, value, expiredays ){
				var todayDate = new Date();
				todayDate.setDate( todayDate.getDate() + expiredays );
				document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
			}
			function closeWin(){
				setCookie( "Notice", "done" , 1);//1은 하루동안 새창을 열지 않게 합니다.
				window.close();
			}
		</script>
		<style>
			@page { margin: 0px; }
			body{margin:0;font-family:"NanumGothic","Roboto";font-size:12px;text-align:center;color:#000;}
			.insurplus_wrap {box-sizing:border-box;position:relative;text-align:center;width:750px;border-radius: 10px;border: solid 1px #e3e3e3;background-color: #ffffff;padding:0px 0px;margin:0 auto}
			.insurplus_wrap * {box-sizing:border-box;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;-ms-box-sizing:border-box;-o-box-sizing:border-box;}
			.title_image{float:left;width:275px;height:420px;position:relative;background:url("[DROOT]html/images/join_certification_title.png") no-repeat 0 0;background-size:100% auto;color:#fff;font-size:16px;text-align:right;padding-top:43.5%;padding-right:3.9%;}
			.title_image h1 {position:absolute;right:10%;top:25%;font-weight:400;font-size:30px;margin:0;}
			.insurplus_content .title_image p{margin:0;color:#fff;}
			.title_image strong{font-size:20px;}
			.top_info{float:left;width:437px;height:420px;position:relative;font-size:14px;text-align:right;padding-top:18px;padding-left:18px}
			.insurplus_wrap .title {clear:both;color:#000; font-family:"NanumGothic";font-weight: 400;line-height:1.5;text-align:left; }
			.insurplus_wrap h3.title {font-size: 15px;margin:0px 0px 8px 0 }
			.insurplus_wrap h4.title {font-size: 14px;margin:0px 0px 5px 0 }
			.insurplus_wrap h5.title {font-size: 14px;margin:24px 0 12px 0px;background-color:#355dab;color:#fff;padding:6px 32px;border-radius: 8px; }
			.insurplus_wrap .top_info h3.title {margin:0px 0px 18px 0 }
			.insurplus_content {display:block;clear:both;}
			.insurplus_content::after, .container::after {content:'';display:block;clear:both;}
			.insurplus_content .container {padding:0 18px;position:relative;text-align:left;}
			.insurplus_wrap .sign {display:inline-flex;align-items:center;flex-direction: row;flex-wrap : wrap;margin-top:10px}
			.insurplus_wrap .sign > p {font-size:13px;}
			.insurplus_wrap .insurplus_tail .sign > p {display:inline-flex;align-items:center}
			.insurplus_wrap .sign > p > img {margin-bottom:7px;;}
			.insurplus_wrap .sign span {float:left;}
			.insurplus_wrap .sign span + span {margin-left:16px;}
			.insurplus_content p {margin:0 0 8px 0;font-size:12px;color:#333;}
			.insurplus_content .table-insurplus + p{margin-top:8px}
			.insurplus_wrap ul.list {text-align:left;margin:0px 0 0 20px;padding:0;line-height:1.75;color:#333;font-size:13px;}
			.insurplus_wrap ul.list li {line-height:1.75;color:#333;}
			.insurplus_wrap ul.list li ol {margin:0;padding:0 0 0 15px}
			.table-insurplus {width: 100%;border-collapse: collapse;border-top:solid 2px #595959;border-bottom:solid 2px #595959;}
				.table-insurplus:not(:last-child) {margin-bottom:12px;}
			.table-insurplus th, .table-insurplus td {border-bottom:1px solid #d6d6d6;min-height:33px;padding:5px 15px;}
			.table-insurplus th {font-weight:400;text-align:center;background-color:#f6f6f6;}
				.table-insurplus > thead > tr > th {border-bottom:2px solid #595959;}
				.table-insurplus.bg-red th {background-color:#f9f1f2;}
				.table-insurplus.bg-blue th {background-color:#f2f7ff;}
			.table-insurplus td {text-align:left;width:auto;color:#333;border-left:1px solid #d6d6d6;}
			.table-insurplus td.total {font-size:24px;padding:6px 15px}
			.table-insurplus td.sum {font-size:18px;padding:11px 15px}
			.table-insurplus td small {font-size:14px;}
			.insurplus_tail{background-color:#f6f6f6;margin:32px -18px 0px -18px;}
			.insurplus_tail .container {padding:24px 36px;position:relative;text-align:left;}
			.insurplus_footer{background-color:#29354c;margin:0px -18px 0 -18px;color:#fff;font-size:12px;}
			.insurplus_footer .container {padding:34px 24px;position:relative;text-align:right;}
			.insurplus_footer strong{font-size:14px;}
			.insurplus_footer img{float:left;margin-top:3px;}
			.insurplus_footer p{float:right;margin:0;}
			.btn-group {margin: 16px auto; text-align:center;}
			.btn {display:inline-block;margin: 0px auto; background-color: #fff; border:1px solid #bbb;text-align:center;height: 48px; line-height: 48px; color: #333; text-align: center; font-size: 14px; font-weight: 400; border-radius:3px;text-decoration:none;}
			.btn-theme-dark {background-color:#29354c;border-color:#29354c;color:#fff;width:300px;border-radius:4px;}
			.text-danger{color:#dc3347;}.text-black{color:#000;}.text-right{text-align:right;}
		</style>
	</head>
	<body>
		<section class='insurplus_wrap'>
			<div class="insurplus_content">
				<div class="title_image">
					<img src="[DROOT]html/images/join_certification_title_temp.png">
				</div>
				<div class="top_info">
					<h3 class='title'>코리아 어시스턴스는 <br>해외에서 발생하는 개인의 질병/상해 사고를 24시간 알람센터에서 <br>대응하여, 안전하게  귀국할 수 있도록 도와드립니다.</h3>
					<h4 class='title'>가입정보</h4>
					<table class="table-insurplus bg-red">
						<colgroup>
							<col width='27%' />
							<col width='*' />
						</colgroup>
						<tbody>
							<tr>
								<th>가입자</th>
								<td>[USER_NAME]</td>
							</tr>
							<tr>
								<th>생년월일</th>
								<td>[USER_BIRTH]</td>
							</tr>
							<tr>
								<th>연락처</th>
								<td>[USER_PHONE]</td>
							</tr>
							<tr>
								<th>이메일</th>
								<td>[USER_EMAIL]</td>
							</tr>
						</tbody>
					</table>
					<h4 class='title'>상품</h4>
					<table class="table-insurplus bg-blue">
						<colgroup>
							<col width='27%' />
							<col width='*' />
						</colgroup>
						<tbody>
							<tr>
								<th>상품플랜</th>
								<td>[PLAN_NAME]</td>
							</tr>
							<tr>
								<th>가입번호</th>
								<td>[ORDERNO]</td>
							</tr>
							<tr>
								<th>서비스타입</th>
								<td>[USER_TYPE]</td>
							</tr>
							<tr>
								<th>상품가격</th>
								<td>[PR_AMOUNT]</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div style="width:100%;height:500px;background-image:url('[DROOT]html/images/join_certification_title.png');background-size:100%;">
			<div class="insurplus_content">
				<h5 class='title'>인슈플러스 서비스 가입 증명서</h5>
				<div class="container" style='text-align:right;height:600px;'>
					<h3 class='title'>상기 가입자가 인슈플러스 서비스에 가입되었음을 증명합니다.</h3>
					<ul class='list'>
						<li>이 상품의 구매는 인슈플러스 서비스 약관 및 해당 보험약관에 동의하였음을 의미합니다.</li>
						<li>이 상품계약의 자세한 사항은 인슈플러스 서비스 약관 및 해당 보험약관을 참조하시기 바랍니다.</li>
						<li>위 상품가격은 인슈플러스 서비스비용과 상품가격가 포함된 금액입니다.</li>
						<li>국가 혹은 지역별 코리아 어시스턴스 제휴병원은 상황에 따라 수시로 변경될 수 있습니다.</li>
						<li>이로 인해 병원비 대신지불 서비스가 제공되지 않는 경우 고객이 치료비 납입 후, 보상 청구 하여야 합니다.</li>
						<li>이 상품은 코리아 어시스턴스에서 서비스를 제공하며, 국내 보험사에서 보험담보를 보장합니다.</li>
						<li>코리아 어시스턴스는 하기 보험가입증명서의 보험가입금액에 의거하여 서비스를 제공 합니다.</li>
						<li>해외의료비를 보장하는 복수의 보험계약에 가입되어 있음이 확인된 경우 병원비 대신지불 서비스가 제한됩니다.</li>
					</ul>
					<div class='sign'>

							<img src="[DROOT]html/images/logo-korea-assistance.png" align="absmiddle"><br>
							<span>㈜코리아어시스턴스<br>대표이사&nbsp;&nbsp;&nbsp;김&nbsp;형&nbsp;태</span>
							<span><img src="[DROOT]html/images/pdf_stamp.png" align="absmiddle" style='height:44.5px;' /></span>

					</div>
				</div>
				<h5 class='title'>[INS_NAME] 가입증명서</h5>
				<div class="container">
					<table class="table-insurplus">
						<colgroup>
							<col width='20%' />
							<col width='30%' />
							<col width='20%' />
							<col width='30%' />
						</colgroup>
						<tbody>
							<tr>
								<th>피보험자</th>
								<td>[USER_JNAME]</td>
								<th>증권번호</th>
								<td>[STOCK_NO]</td>
							</tr>
							<tr>
								<th>보험기간</th>
								<td>[INS_PERIOD]</td>
								<th>해외 체류국가</th>
								<td>[NATION_NAME]</td>
							</tr>
						</tbody>
					</table>
					<h3 class='title'>상기 고객을 피보험자로 하여 아래와 같이 <span class='text-danger'>[INS_NAME]</span>에 가입되었음을 확인 합니다. </h3>
					<p>보험계약의 자세한 사항에 대하여는 <span class='text-danger'>[INS_NAME]</span> 약관을 참조하시기 바랍니다.<br> 코리아 어시스턴스는 다음의 담보내용과 보상한도액에 의거하여 서비스를 제공합니다.</p>
					<table class="table-insurplus" style='margin:0;'>
						<colgroup>
							<col width='32%' />
							<col width='*' />
						</colgroup>
						<thead>
							<tr>
								<th>담보내용</th>
								<th>보험가입금액 / 보상한도액</th>
							</tr>
						</thead>
						<tbody>
							[G_LIST]
						</tbody>
					</table>
					<p>이 보험계약은 보험계약자 및 상품가격 납부자가 코리아 어시스턴스인 단체보험으로 예금자보험법에 의거, 예금자보호대상에서 제외됩니다.</p>
				</div>
			</div>
			<div class="insurplus_tail">
				<div class="container" style='text-align:right;'>
					<h3 class='title'>유의사항</h3>
					<ul class='list'>
						<li>천재지변으로 인한 상해사망/후유장애, 상해치료비 또한 상해 담보금액 범위 내에서 보상해 드립니다.</li>
						<li>만 15세 미만은 상해사망 / 질병사망 담보가 적용되지 않습니다. (상법732조)</li>
						<li>상해치료, 질병치료
							<ol>
								<li>피보험자가 해외여행중에 발생한 상해, 질병으로 치료를 위해 해외의료기관에서 의료비 발생 시 의료비 보상</li>
								<li>피보험자가 해외여행 중에 입은 상해, 질병으로 인하여 국내입원 시 의료급여 중 본인부담금의 90% 보상액과 비급여의<br>80% 해당액의 합계액을 가입 한도 내에서 보상</li>
								<li>피보험자가 해외여행 중에 입은 상해, 질병으로 인하여 국내병원에 통원하여 치료를 받을 경우 가입 한도 내에서 보상<br>(1회당 1만원~2만원과 공제기준금액(보상대상의료비의 급여 10% 해당액과 비급여 20% 해당액의 합산액)중 큰 금액 공제)</li>
								<li>피보험자가 해외여행 중에 입은 상해, 질병으로 인하여 국내 처방조제를 받은 경우 5만원 한도 내에서 보상<br>(1회당 8천원과 공제기준금액(보상대상의료비의 급여 10% 해당액과 비급여 20% 해당액의 합산액)중 큰 금액 공제)</li>
							</ol>
						</li>
					</ul>
					<div class='sign'>
						<p>
							<span style='text-align:right;'><img src="[DROOT]html/images/logos/hanhwa.png" align="absmiddle" style='height:47px;' /><br>대표이사&nbsp;&nbsp;&nbsp;박 윤 식</span>
							<span><img src="[DROOT]html/images/pdf_stamp2.png" align="absmiddle" style='height:44.5px;' /></span>
						</p>
					</div>
				</div>
			</div>
			<div class="insurplus_footer">
				<div class="container">
					<img src="[DROOT]html/images/footer-logo1.svg" align="absmiddle">
					<p>
						https://koreaassistance.co.kr<br>
						<strong>Tel: +82 2 360 2518</strong><br>
						‌3F, 7,Chungjeong-ro, Seodaemun-gu, Seoul, Korea
					</p>
				</div>
			</div>
		</section>
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
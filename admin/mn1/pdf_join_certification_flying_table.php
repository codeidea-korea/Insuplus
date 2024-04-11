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
		<div style="width:100%; margin:0 0 10px 0; border-bottom:2px solid #dc3347">
			<img src="[DROOT]/html/images/join_certification_flying_title1_20221114.png" width="100%" />
		</div>
		<div style="padding:0 18px;">
			<h3 style='font-size:15px;font-weight: 400;margin: 0px 0px 10px 0;'>[CHK_FLY_TYPE_NAME]는 해외에서 발생하는 개인의 질병/상해 사고를 24시간 알람센터에서 대응하여, 안전하게 귀국할 수 있도록 도와 드립니다.</h3>
			<h4 style='font-size: 14px;font-weight:400;background-color: #C21E2E;color: #fff;padding: 6px 32px;border-radius: 8px;margin:10px 0 5px 0;'>가입정보</h4>
			<table width="100%" cellspacing='0' cellpadding='0' border='0' style='border-top: solid 2px #595959;border-bottom: solid 2px #595959; margin:0 0 14px 0;'>
				[CONTRACTOR_ROW]
				<tr>
					<th width="60" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>가입자명</th>
					<td colspan="3" style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[USER_NAME]</td>
				</tr>
				<tr>
					<th width="60" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>생년월일</th>
					<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[USER_BIRTH]</td>
					<th width="60" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>가입기간</th>
					<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px;font-size:14px;'>[INS_PERIOD]</td>
				</tr>
				<tr>
					<th width="60" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>가입상품</th>
					<td colspan="3" style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[INS_NAME] [PLAN_NAME] [SERVICE_TYPE]</td>
				</tr>
				<tr>
					<th width="60" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>출국국가</th>
					<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[NATION_NAME]</td>
					<th width="60" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>가입목적</th>
					<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[PURPOSE]</td>
				</tr>
			</table>

			[CHK_SERVICE]
			[CHK_KOR_SERVICE]
			[SERVICEIMG]

			[G_LIST]
			[INS_NOTI]
			[INSSIGNIMG]

		</div>

		[FOOTER]
		<!-- <table width="100%" cellspacing='0' cellpadding='0' border='0' style="background:#29354c; padding:20px;">
			<tr valign='middle'>
				<td style='text-align:left;'>
					<img src="[DROOT]/html/images/footer-logo1.png" align="absmiddle">
				</td>
				<td style='text-align:right;color:#fff;'>
					https://koreaassistance.co.kr<br>
					<strong style='font-size:14px;'>Tel: +82 2 360 2545</strong><br>B1, 7,Chungjeong-ro, Seodaemun-gu, Seoul, Korea
				</td>
			</tr>
		</table> -->
	</div>

</body>

</html>
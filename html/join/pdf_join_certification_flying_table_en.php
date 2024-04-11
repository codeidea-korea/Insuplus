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
			*{margin:0;padding:0}
			@page { margin: 0; padding:0;  }
			body{margin:0;font-family:"NanumGothic","Roboto";font-size:12px;text-align:center;color:#000;}
		</style>
	</head>
	<body style="margin:0; padding:0;">
	<div style="width:100%; text-align:left;">
		<div style="width:100%; margin:0 0 15px 0; border-bottom:2px solid #dc3347">
			<img src="[DROOT]/html/images/join_certification_flying_title_en.png" width="100%" />
		</div>
		<div style="padding:0 18px;">
			<h3 style='font-size:15px;font-weight: 400;margin: 0px 0px 18px 0;'>Flying Doctors will guarantee you safely return home monitoring from 24-hour alarm center for personal illness/injury in overseas.</h3>
			<h4 style='font-size: 14px;font-weight:400;background-color: #C21E2E;color: #fff;padding: 6px 10px;border-radius: 8px;margin:20px 0 5px 0;'>Subscription Information</h4>
			<table width="100%" cellspacing='0' cellpadding='0' border='0' style='border-top: solid 2px #595959;border-bottom: solid 2px #595959; margin:0 0 14px 0;'>
					<tr>
						<th width="110px" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>Name</th>
						<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[USER_ENAME]</td>
						<th width="105px" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>Registration No.</th>
						<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[USER_BIRTH]</td>
					</tr>
					<tr>
						<th width="110px" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>Phone</th>
						<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[USER_PHONE]</td>
						<th width="105px" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>Email</th>
						<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[USER_EMAIL]</td>
					</tr>
					<tr>
						<th width="110px" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>Policy Number</th>
						<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[STOCK_NO]</td>
						<th width="105px" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>PolicyPeriod</th>
						<td style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[INS_PERIOD]</td>
					</tr>
                    <tr>
						<th width="110px" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>Plan</th>
						<td colspan="3" style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[ENG_PLAN_NAME]</td>
					</tr>
					<tr>
						<th width="110px" style='background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>Membership No.</th>
						<td colspan="3" style='background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;'>[ORDERNO]</td>
					</tr>
			</table>
			[CHK_SERVICE]
			<p style='font-size:12px;margin:8px 0 18px 0;color:#333;'>
			The above customer confirms that they have joined to a 24-hour overseas emergency transportation service.<br/>
			Please refer to the service guide for details.<br/>
			Flying Doctors is an overseas emergency transportation service brand on Bizinsight, Bizinsight provides services <br/>
			In accordance with the above collateral and compensation limits
			</p>
			<p style="text-align:right; padding:20px 20px;"><img src="[DROOT]/html/images/korea_assistance_stamp.png" align="absmiddle"><p>
			[G_LIST]
			<p style='font-size:12px;margin:8px 0 18px 0;color:#333;'></p>
			<p style='font-size:12px;margin:8px 0 5px 0;color:#333;'>
				This insurance contract is a group insurance which KOREA ASSISTANCE is policy holder and pays premium.
			</p>
			<div style="font-size:12px;color:#333;">■ Notice</div>
			<div style="margin-left: 20px;font-size:12px;color:#333;">Death Collateral does not apply to those under 15-years-old.</div>
			<div style="margin-left: 20px;font-size:12px;color:#333;">Deductible : If not mentioned, refer to insurance policy wording.</div>
			<p style="text-align:right; padding:10px 20px;"><img src='[DROOT]/_data/board/ins_list/[SIGNIMG]' align='absmiddle'><p>
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
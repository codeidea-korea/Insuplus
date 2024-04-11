<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>베나자X인슈플러스</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { background-color: #fff; margin: 0px; font-family: '맑은고딕',malgun gothic,'돋움',Dotum,'Apple SD Gothic Neo',Helvetica,sans-serif;}
        .content-wrapper {width: 100%; margin: 0 auto;}
        .header {margin: 0px; background-image: url(img/header_titlebg.png); width: 100%;min-width:1920px;min-height: 935px;padding:0 200px;display:flex; justify-content: center; flex-direction: column; box-sizing: border-box;background-position-x: center;}
      
        .title_bg {font-size: 16pt; font-weight: bold;}
        .bar{margin: 10px 0 10px;}
        .service_con {width: 100%; margin: 0 auto;}
        .subtitle {background-color: #000000; border-radius: 30px; padding: 14px 0; width: 180px; margin: 80px auto 50px; font-size: 16pt; color: #fff; text-align: center;}
        strong {color: #005fbf;}
        .phone_img_m{display: none;}
/*
        table{border-collapse: collapse; text-align: center; width: 100%; margin-bottom: 30px;}
        th, td{ border: 1px solid #C5C5C5; padding: 12px;}
        th{background-color: #14327E; color: white;}
*/
        
        img {object-fit: cover;}
        dl{width: 80%;margin: 0 auto;}
        dt{font-size: 12pt; margin-bottom: 5px; display: flex;}
        dd{margin-left:0px;margin-bottom: 20px; color: #2c2c2c; display: flex;border-bottom: 1px solid #C5C5C5;padding-bottom: 20px;}
        .half-highlight {background: linear-gradient(180deg,rgba(255,255,255,0) 50%, #FFEB00 40%);}
        
        button {background-color: #204AB3; border-radius: 6px; padding: 14px 0; width: 50%; margin: 50px 0; color: #fff; cursor: pointer; font-size: 14pt; border: none;}
        button:hover { background-color: #1A3C92;}
        button:active {text-decoration: none;}
        .coupon_btn {margin: 0 10px;}
        
        .qa_q {width: 30px; height: 30px; background: #000; color: #fff; border-radius: 100px; text-align: center; line-height: 32px;}
        .qa_a {width: 30px; height: 30px; background: #204AB3; color: #fff; border-radius: 100px; text-align: center; line-height: 32px;}
        .qa_title {margin-left: 6px; padding-top: 9px; width: 90%;}
        .titleimg02box {display: flex;}
        .titleimg02 {background-image: url(img/zxc.png); width: 880px; height:154px; background-repeat: no-repeat;}
        .titleimg03box {display: flex;}
        .titleimg03 {background-image: url(img/header_titleimg03.png); width: 423px; height:335px; background-repeat: no-repeat; background-size: contain;}
        .coupon {display: flex; justify-content: center; background-image:url(img/coupon_bg.png); padding: 30px 0;}
        .coupon_d {background-image: url(img/coupon_down.png); width: 290px; height:190px; background-repeat: no-repeat; background-size: 100%;}
        .coupon_r {background-image: url(img/coupon_regi.png); width: 290px; height:190px; background-repeat: no-repeat; background-size: 100%;}
        .footer_btn {width: 100%; text-align: center;}
        
        /*202301 추가*/
        .insuimgbox {background-color: #fff; min-width: 1920px;}
        .insuimg {background-image: url(./img/건강한-의료진-이미지.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 859px; margin: 0 auto;}
        .benefitbox {background-color: #fff;padding: 0;min-width: 1920px;}
        .benefit_insu {text-align: center;padding: 0; background-color: #fff;}
        .insu2box {}
        .benefit_insu2 {text-align: center;padding: 0; background-color: #fff;}
        .benefit_insu_title {font-size: 34px; color: #1B268D;padding: 0;margin: 0;font-weight: bold;}
        .benefit_insu_sub {font-size: 32px; color: #000;padding: 30px 0 0;margin: 0;font-weight: 500;}
        .benefittitlebox { background-color: #FDF9F8;}
        .benefittitleimg {background-image: url(img/img_benefit_title.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 150px; margin: 0 auto;}
        .benefit_p {text-align: center; background-color: #FDF9F8;}
        .benefit_p_title {font-size: 34px; color: #B63100;padding: 0;margin: 0;font-weight: bold;}
        .benefit_p_titlesub {font-size: 16px; color: #000;font-weight: bold;padding: 0;margin: 0;}
        .benefit_p_sub {font-size: 18px; color: #000;font-weight: 100;padding: 0;margin: 0;}
        .benefit_p_date {font-size: 18px; color: #fff;font-weight: 100;padding: 12px 30px 8px;margin: 0 auto;border-radius: 30px;background-color: #1D254A;width: fit-content;}
        
        table{border-collapse: collapse; text-align: center; width: 90%; margin-bottom: 30px;margin: 0 auto;}
        th, td{padding: 12px; word-break: keep-all;}
        th{background-color: #574A47; color: white;}
        td{text-align:center; background-color: #fff; border-bottom: 1px solid #C5C5C5; }
        .thradius-left { border-radius: 20px 0 0 20px;}
        .thradius-right { border-radius: 0 20px 20px 0;}
        .tdcolor {color: #000;}
        .tableborder {border-left: 1px solid #C5C5C5;}
        
        .benefit00box { background-color: #Ffff;padding:0;}
        .benefit00img {background-image: url(img/img_benefit00.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 700px; margin: 0 auto;}
        .benefit01box { background-color: #FDF9F8;}
        .benefit01img {background-image: url(img/img_benefit01.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 474px; margin: 0 auto;}
        .benefit02box { background-color: #Fff;}
        .benefit02img {background-image: url(img/img_benefit03.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 2000px; margin: 0 auto;}
        .benefit03box { background-color: #fff; padding:0;}
        .benefit03img {background-image: url(img/img_benefit_04.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 2824px; margin: 0 auto;}

        .benefit04box { background-color: #fff; padding:0;}
        .benefit04img {background-image: url(img/img_benefit_05.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 1772px; margin: 0 auto;}

        .benefitaddbox {background: linear-gradient(180deg, #F0F4F8 50%, #FFF8E9 50%);text-align: center; width: 100%;}
        .benefitaddbox img {width: 80px;}
        .contentbox01{background-color: #EFF1F6; padding: 60px 0;}
        .subtitle01 {width: 180px; margin: 0 auto 20px; font-size: 24px; color: #000; text-align: center;background: linear-gradient(180deg,rgba(255,255,255,0) 60%, #c0d4ff 40%);}
        .contentbox02{background-color: #fff; padding: 0;width: 100%;min-width:1920px;margin: 0 auto;}
        .contentbox02>img{width:100%;}
        .subtitle02 {width: 380px; margin: 0 auto 30px; font-size: 34px; font-weight: bold;color: #1B1D30; text-align: center;background: linear-gradient(180deg,rgba(255,255,255,0) 60%, #c0d4ff 40%);}
        .subtitle02_sub {text-align: center; margin: 0 0 30px;color: #000;}
        .contentbox03{padding: 60px 0 0;}
        .subtitle03 {width: 250px; margin: 0 auto 30px; font-size: 34px;font-weight: bold; color: #1B1D30; text-align: center;background: linear-gradient(180deg,rgba(255,255,255,0) 60%, #c0d4ff 40%);}
        .subtitle03_sub {text-align: center; margin: 0 0 30px;color: #000;font-weight: 100;}
        .mar-60 {margin-top: 40px;}
        .mar-b {margin-bottom: 70px;}
        
        .button_joinbox {text-align: center;width:180px;height:82px;bottom: 0;z-index: 999;}
        .button_join { font-family:Pretendard,sans-serif;font-weight:bold;background-color: #64a5f5; text-align: center; text-decoration: none; font-size: 30px; margin: 0 auto; cursor: pointer; width: 100%; border-radius: 0; color: #fff;}
        .button_join:hover { background-color: #1165cc;color:#fff; }
        .button_join:active {text-decoration: none;}
        
        .title04box{background-color: #112148;color: #fff;padding: 20px;text-align: center;font-size: 32px;}
        .title04-l{width: 50%;text-align: right;margin: 0 20px;}
        .title04-r{width: 50%;text-align: left;margin: 0 20px;}
        

         .noborder {border-bottom: none;margin-bottom: 0; padding-bottom: 0;}
        
        .infobox {background-color: #574A47; color: #fff; padding: 50px; }
        .info-title {font-size: 20px; font-weight: bold;}
        .info-sub {font-weight: 100; line-height: 2;}
        .info-tablebox {text-align: left; margin: 10px 0;}
        .info-table {margin:0;}
        .info-thcolor { background-color: #574A47; color: #fff; border: 1px solid #B0A9A9;font-weight: 100; }
        .info-table td { background-color: #574A47; color: #fff; border: 1px solid #B0A9A9;font-weight: 100; }
        
        .qa_title {margin-left: 6px; padding-top: 5px; width: 90%;}
        .qa_caution {width: 100%;padding: 20px;margin-inline-start: 0;background-color: #F7F9FF; border-radius: 10px; color: #574A47;line-height: 1.5;}
        .qa_caution_pro {padding: 20px;margin-inline-start: 0;background-color: #F7F9FF; border-radius: 10px; color: #574A47;line-height: 1.5;}
		
		/*footer-white*/
        .footer_w {padding: 40px; background-color: #f3f5f8; color: #000;}
        .footer_w .footercenter {padding: 5px 10px 2px; font-weight: bold;font-size: 18px;}
        .footer_w .topphone {padding: 7px 10px 2px;}
        .footer_w .footerlogo {width: 130px;}
        .footer_w .footerlogo img {width: 128px; height: 36px;}
        .footer_w .footerheader {float: right;margin-top: -43px;display: inline-flex;}
        .footer_w .footerheader table{width: 100%; margin: 0 auto; }
        .footer_w .footerheader table tr td {border-bottom: none; padding: 4px; text-align: right;}
        .footer_w .footer-sub {display: inline-flex;}
        .footer_w .footer-phoimg {width: 24px; height: 24px; padding: 4px 0 2px;}
        .footer_w .footerkf {padding: 4px 0 2px;}
        .footer_w .footer-address {}
		.footer-phoimg img {width: 24px; height: 24px; }
        
		
		
    
        
        @media (max-width: 768px) {
            .m_banner{display: block;}
            .button_join{display: block; position: relative; bottom: 70px; left:35px;}
            .phone_img_m{display: block;}
            .phone_img{display: none;}
            .benefit04img {background-image: url(img/img_benefit_05_m.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 850px; margin: 0 auto;}
            .benefit03img {background-image: url(img/img_benefit_04_m.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 760px; margin: 0 auto;}
            .benefit02img {background-image: url(img/img_benefit03_m.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 750px; margin: 0 auto;}
            .insuimg {background-image: url(./img/건강한-의료진-이미지_m.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 450px; margin: 0 auto;}
            .benefit_insu_title{display:none;}
            .benefit00img {background-image: url(img/img_benefit00_m.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 500px; margin: 0 auto;}
            .benefitbox{min-width: 0;}
            .contentbox02{min-width: 0;}
            .header {margin: 0px; background-image: url(img/header_titlebg_m.png); width: 100%;min-width:0;min-height: 650px;display:flex; justify-content: center; flex-direction: column; background-size: contain;padding:0;background-position-x: center;background-repeat: no-repeat;}
            .titleimg02box,.titleimg03box{display: none;}
            .benefit00img {}
            .subtitle02 {width: 270px;font-size: 30px;}
            .subtitle03 {width: 360px;font-size: 30px;}
            .titleimg03box {padding: 30px 0 40px;}
            .titleimg03 {width: 580px;height: 350px;}
            .benefit00img {}
            .insuimgbox {min-width: 0;}
            .insuimg {}
            .benefit_insu_title {font-size: 30px;}
            .benefit_insu_title img{width:100%;}
            .benefit_insu_sub {font-size: 26px;padding: 20px 0 0;}
            .service_con {width: 100%;}
            table {width: 100%;}
            dl {margin: 0 auto;}
            .title04box {padding: 20px 0;}
            .title04-l{text-align: right;margin: 0 10px;}
            .title04-r{text-align: left;margin: 0 10px;}
			.benefit02img {}
			.footerimg {background-image: url(images/footer_t7.png);background-repeat: no-repeat; background-position: center; background-size: contain; width: 100%;height: 190px;}
			/*footer-white*/
			.footer_w {padding: 40px;;}
			.footer_w .footercenter {font-size: 16px;padding: 7px 10px 2px 4px;}
			.footer_w .footerheader {float: left;margin: 10px 0;width: 100%;}
			.footer_w .footerheader table tr td {text-align: left;}
			.footer_w .toppad {padding: 5px 10px 2px 0px;}
			.footer_w .topphone {padding: 7px 10px 2px 4px; font-size: 16px;}
			.footer_w.footer_address {margin-top: 50px;}
			.footer_w.footer_address p {text-align: left; font-size: 14px; font-weight: bold; color: #000; padding: 2px 0 2px 6px;margin: 4px 0;}
			.button_join {padding: 20px;font-size: 28px;}
			}
	        @media (max-width: 540px) {
            .benefit04img {background-image: url(img/img_benefit_05_m.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 1000px; margin: 0 auto;}
            .benefit03img {background-image: url(img/img_benefit_04_m.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 700px; margin: 0 auto;}
            .benefit00img {background-image: url(img/img_benefit00_m.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 600px; margin: 0 auto;}
            .insuimg {background-image: url(./img/건강한-의료진-이미지_m.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 400px; margin: 0 auto;}

            .header{ background-image: url(img/header_titlebg_m.png);width: 100%;min-width:0;min-height: 890px;}
            .service_con {width: 100%; margin: auto;}
            .subtitle {background-color: #000000; border-radius: 30px; padding: 10px 0; width: 180px; margin: 50px auto 30px; font-size: 14pt; color: #fff; text-align: center;}
            dl{margin: 0 auto;width: 90%;}
            .titleimg02 {}
            .titleimg03 {}
            .coupon_d {background-image: url(img/coupon_down_m.png); width: 150px; height:150px; background-repeat: no-repeat; background-size: 100%;}
            .coupon_r {background-image: url(img/coupon_regi_m.png); width: 150px; height:150px; background-repeat: no-repeat; background-size: 100%;}
            button {width: 80%; }
            .contentbox03 {padding: 50px 0 20px;}
            .subtitle02 {width: 210px;font-size: 22px;}
            .subtitle03 {width: 210px;font-size: 22px;}
			
			.benefit02img {height: 750px;}
			.button_join {padding: 20px;font-size: 20px;}
        }
         @media(max-width:480px){
            .footer_w {padding: 30px 10px;}
		.footer_w .footercenter {font-size: 14px;padding: 7px 10px 2px 4px;}
		.footer_w .footerheader {float: left;margin: 10px 0;width: 100%;}
		.footer_w .footerheader table tr td {text-align: left;}
		.footer_w .toppad {padding: 5px 10px 2px 0px;}
		.footer_w .topphone {padding: 7px 10px 2px 4px; font-size: 16px;}
		.footer_w.footer_address {margin-top: 50px;}
		.footer_w.footer_address p {text-align: left; font-size: 14px; font-weight: bold; color: #000; padding: 2px 0 2px 6px;margin: 4px 0;}
		.button_join {padding: 20px;font-size: 24px;}
		.benefit_p_title {font-size: 28px;}
		.footer-phoimg img {width: 20px; height: 20px; }
         
         }
        
        @media (max-width: 360px) {
            .benefit04img {background-image: url(img/img_benefit_05_m.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 600px; margin: 0 auto;}
            .benefit03img {background-image: url(img/img_benefit_04_m.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 550px; margin: 0 auto;}
            .benefit00img {background-image: url(img/img_benefit00_m.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 400px; margin: 0 auto;}
            .insuimg {background-image: url(./img/건강한-의료진-이미지_m.png);background-repeat: no-repeat;  background-size: contain; background-position: top; width: 100%; height: 300px; margin: 0 auto;}

            .header{background-image: url(img/header_titlebg_m.png);min-height: 780px;}
            .service_con {width: 100%; margin: auto;}
            .subtitle {background-color: #000000; border-radius: 30px; padding: 10px 0; width: 180px; margin: 50px auto 30px; font-size: 14pt; color: #fff; text-align: center;}
            dl{margin: 0 auto;width: 90%;}
            .titleimg02 {}
            .titleimg03 {}
            .coupon_d {background-image: url(img/coupon_down_m.png); width: 150px; height:150px; background-repeat: no-repeat; background-size: 100%;}
            .coupon_r {background-image: url(img/coupon_regi_m.png); width: 150px; height:150px; background-repeat: no-repeat; background-size: 100%;}
            button {width: 80%; }
            .contentbox03 {padding: 50px 0 20px;}
            .subtitle02 {width: 210px;font-size: 22px;}
            .subtitle03 {width: 210px;font-size: 22px;}
			.contentbox02 {width: 90%;}
			.benefit02img {height: 570px;}
			.button_join {padding: 20px;font-size: 20px;}
        }
</style>
<script>
    function go_event() {
        location.href = '/html/main/index.php?alliance_code=czgyd24zcVg0dGR5SldLWFdnMGM5UT09';
    }
</script>
</head>
<body>
    <div class="content-wrapper">
        <!--상단 header영역-->
        <div class="header">
            <div class="titleimg02box">
                 <div class="titleimg02"></div>
            </div>
            <div class="titleimg03box">
                 <div class="titleimg03"></div>
            </div>
            <div class="button_joinbox">
                <button class="button_join" onclick="go_event()">
                   가입하기
                </button>
            </div>
        </div>
        <!--제공서비스/QNA 영역-->
        <div class="insuimgbox">
            <!--혜택 타이틀01-->
            <div class="benefit_insu">
                <p class="benefit_insu_title"><img src="./img/title2_img.png" alt="title2_img"></p>
            </div>
            <div class="benefit00box">
                <div class="benefit00img"></div>
            </div>
            <div class="insu2box">
                <div class="benefit_insu2">
                    <p class="benefit_insu_title"><img src="./img/insu2_titleimg.png" alt="insu2_titleimg"></p>
                </div>
                <div class="insuimg"></div>
            </div>
        </div>
		<div class="service_con">
			 <!--인슈플러스 서비스-->
			<div class="contentbox02">
                <img class="phone_img" src="./img/contentbox02_img.png" alt="contentbox02_img">
                <img class="phone_img_m" src="./img/contentbox02_img_m.png" alt="contentbox02_img">
			</div>
			<div class="benefitbox">
				 <!--프로모션 혜택 타이틀02-->
				<div class="benefit02box">
					<div class="benefit02img"></div>
				</div>
                <div class="benefit03box">
					<div class="benefit03img"></div>
				</div>
                <div class="benefit04box">
					<div class="benefit04img"></div>
				</div>
			</div>
			<!--서비스 FAQ-->	
		<!--footer_white-->
		<div class="footer_w">
			 <div class="footerlogo">				
			 </div>
			 <div class="footerheader">
				 <div class="footercenter">24시간 알람센터</div>
				 <div class="footer-phoimg">
					 <img src="img/footer_phone.png">
				 </div>
				 <div class="topphone">02.360.2545</div>
				 <div class="footerkf">
					<a href="https://pf.kakao.com/_JClxfT/chat" target="_blank"><img src="img/footer_kf.png" style="width: 100px; height: 24px;"></a>
				 </div>
			 </div>
			 <div class="footer_address">
				 <p >㈜코리아어시스턴스 
				 </p>
				 <p style="font-weight: 100">
					사업자등록번호 : 110-86-08297 ㅣ 대표이사 : 김형태<br>
					서울특별시 서대문구 충정로 7, B1 7호<br>
					통신판매업신고 : 제2019-서울서대문-0682호
				 </p>
			 </div>
		</div>  
	</div>
</body>
</html>
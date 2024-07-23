<?php
//캐시 없이 파일로드 (임시)
function get_url( $url ) {
    if(empty($url)) return $url;
	$url = $url."?ver=".date("YmdHis");
    return $url;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>Shinhan Card</title>
<meta name="viewport" id="meta_viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1,maximum-scale=1">
<meta name="HandheldFriendly" content="true">
<meta name="format-detection" content="telephone=no">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="stylesheet" href="<?=get_url('./css/_reset.css')?>">
<link rel="stylesheet" href="./js/swiper/swiper.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.css" />
<link rel="stylesheet" href="<?=get_url('./css/style.css')?>">
<link rel="stylesheet" href="<?=get_url('./css/_util.css')?>">

<script type="text/javascript" src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script type="text/javascript" src="<?=get_url('./js/_common.js')?>"></script>
<script type="text/javascript" src="./js/animation/easing.js"></script>
<script type="text/javascript" src="./js/animation/jquery.transit.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/egjs-jquery-transform/2.0.0/transform.min.js"></script>
<!--[swiper]-->
<script type="text/javascript" src="./js/swiper/swiper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/11.0.5/swiper-bundle.min.js"></script>
<!--[ScrollTrigger] -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.3/ScrollTrigger.min.js"></script>
<script src="./js/scrollIt/scrollIt.min.js"></script>

<script type="text/javascript" src="<?=get_url('./js/myScript.js')?>"></script>

</head>
<body>

<div id="root">

<header id="header">
	<div class="headerContainer">
		<div class="logo"><img src="./img/logo.png"></div>
		<ul class="_hd_scroll_nav">
			<li><button type="button" data-scroll-nav="1">신한카드 혜택</button></li>			
			<li><button type="button" data-scroll-nav="2">서비스 소개</button></li>
			<li><button type="button" data-scroll-nav="3">의료진 소개</button></li>
			<li><button type="button" data-scroll-nav="4">24시간 알람센터</button></li>
			<li><button type="button" data-scroll-nav="5">서비스 상세 안내</button></li>
		</ul>
	</div>
</header>

<div id="wrapper">
	
	<section id="main_top">
		<div class="sectionContainer">
			<img src="./img/main_top_img01.png" class="scrollMotion">
			<img src="./img/main_top_img02.png" class="scrollMotion">
			<div class="flex-middle gap25 scrollMotion pc-only">
				<a href="https://pf.kakao.com/_JClxfT/chat" target="_blank" alt="카카오톡 문의하기"><img src="./img/main_top_btn01.png"></a>
				<a href="/html/insurance/renewal_step00.php" target="_blank" alt="간편 가격조회 하기"><img src="./img/main_top_btn02.png"></a>
			</div>
		</div>
	</section>

	<div id="main">
		
		<div id="_quick_pannel" class="pc-only">
			<div id="_quick">
				<a href="https://pf.kakao.com/_JClxfT/chat" target="_blank" alt="카카오톡 문의하기"><img src="./img/quick01.png" class="scrollMotion right"></a>
				<a href="/html/insurance/renewal_step00.php" target="_blank" alt="간편 가격조회 하기"><img src="./img/quick02.png" class="scrollMotion right"></a>
				<img src="./img/quick_top.png" id="_gototop" class="scrollMotion right">
			</div>
		</div>

		<section id="sec01" class="bg01" data-scroll-index="1">
			<div class="sectionContainer">
				<div class="txtCon">
					<div class="t1 scrollMotion">+ 신한카드 혜택</div>
					<div class="t2 scrollMotion">신한카드 고객이라면<br>언제든 할인 제공</div>
					<div class="t3 scrollMotion">결제 전, 할인 추천코드에 <span class="fw700 color-blue">'신한카드'</span> 꼭! 기억하세요</div>
				</div>
				<div class="imgCon">
					<img src="./img/sec01_img01.png" class="scrollMotion right">
				</div>
			</div>
		</section>

		
		<section id="sec02" class="" data-scroll-index="2">
			<div class="sectionContainer">
				<div class="txtCon tcenter">
					<div class="t1 scrollMotion">+ 인슈플러스 소개</div>
					<div class="t2 scrollMotion">인슈플러스란?</div>
					<div class="t3 scrollMotion">해외여행보험에 의료지원, 편의지원을 더한<br>24시간 여행안심 서비스입니다.</div>
				</div>
				<div class="subtitle lg:mt100 sm:mt50 scrollMotion"><img src="./img/sec02_icon01.svg" class="sm:w-40">해외 의료지원</div>
				<ul class="_ul_bub01 lg:mt40 sm:mt20">
					<li class="scrollMotion">
						<div class="text">
							<div class="title">해외 병원 예약 및 통역</div>
							<div class="sub">가까운 현지 병원을 예약해드립니다<br>현지 의료진과 소통도 걱정마세요</div>
						</div>
						<img src="./img/sec02_icon02.svg">
					</li>
					<li class="scrollMotion">
						<div class="text">
							<div class="title">병원비 대신 지불</div>
							<div class="sub">마음 편히 치료 받고 오세요<br>복잡한 서류를 받아서 청구하실 필요 없어요</div>
						</div>
						<img src="./img/sec02_icon03.svg">
					</li>
					<li class="scrollMotion">
						<div class="text">
							<div class="title">간호사 건강상담</div>
							<div class="sub">해외에서 아프면 전화하세요<br>24시간 간호사 상담이 가능합니다</div>
						</div>
						<img src="./img/sec02_icon04.svg">
					</li>
					<li class="scrollMotion">
						<div class="text">
							<div class="title">원격진료</div>
							<div class="sub">휴대폰으로 간편하게 원격진료를 제공해드려요<br>*미국 병원은 온라인 처방전 발행</div>
						</div>
						<img src="./img/sec02_icon05.svg">
					</li>
					<li class="scrollMotion">
						<div class="text">
							<div class="title">12개과목 한국인 전문의</div>
							<div class="sub">12개 과목의 한국인 전문의가<br>상담을 제공해드려요</div>
						</div>
						<img src="./img/sec02_icon06.svg">
					</li>
				</ul>
				<div class="txtCon tcenter lg:mt160 sm:mt60">
					<div class="t2 scrollMotion">긴급이후송</div>
					<div class="t3 scrollMotion">여행 중 심각한 질병, 사고로 귀국을 할 수 없을 때 의료 항공이송을 해드립니다</div>
				</div>
				<div class="flex lg:flex-middle lg:gap30 sm:column sm:gap30 lg:mt100 sm:mt40">
					<img src="./img/sec02_img01.png" class="scrollMotion">
					<div class="lg:flex1 flex column gap15">
						<div class="subtitle scrollMotion"><img src="./img/sec02_icon07.svg" class="sm:w-40">에어앰뷸런스 긴급이송</div>
						<ul class="_ul_bub02 lg:mt10">
							<li class="scrollMotion">
								<img src="./img/sec02_icon08.svg">
								<p>에어앰뷸런스 이송비용을 2억까지 보장해 드려요</p>
							</li>
							<li class="scrollMotion">
								<img src="./img/sec02_icon09.svg">
								<p>국내 유일 의료용 항공기·전문항공팀· 응급의학과 이송팀 보유!</p>
							</li>
							<li class="scrollMotion">
								<img src="./img/sec02_icon10.svg">
								<p>국내 의료용 항공기로 한국인 응급의학과 전문의와<br>파일럿이 직접 이송해드립니다</p>
							</li>
						</ul>
					</div>
				</div>

				<div class="txtCon tcenter lg:mt160 sm:mt60">
					<div class="t2 scrollMotion">해외여행 지원</div>
					<div class="t3 scrollMotion">여행중 발생할 수 있는 지갑 도난, 수하물 지연,<br>여권 분실 등 불편상황 해결을 도와 드립니다</div>
				</div>
				<div class="lg:mt100 sm:mt50">
					<div class="lg:flex1 flex column gap15">
						<div class="subtitle scrollMotion"><img src="./img/sec02_icon07.svg">해외여행 지원</div>
						<ul class="_ul_bub03 lg:mt10">
							<li class="scrollMotion">
								<div class="title">분실 수하물 추적</div>
								<div class="sub">수하물이 도착하지 않은 경우 위치를<br>추적해드려요</div>
								<img src="./img/sec02_icon11.svg">
							</li>
							<li class="scrollMotion">
								<div class="title">해외 긴급송금 지원</div>
								<div class="sub">지갑을 분실 했을 때 인슈플러스에서<br>해외로 긴급 송금을 해드려요</div>
								<img src="./img/sec02_icon12.svg">
							</li>
							<li class="scrollMotion">
								<div class="title">여행정보 안내</div>
								<div class="sub">예방접종, 비자 등 현지 여행 정보를<br>제공해 드려요</div>
								<img src="./img/sec02_icon13.svg">
							</li>
							<li class="scrollMotion">
								<div class="title">법률지원</div>
								<div class="sub">여행 중 사고가 발생한 경우 변호사를<br>알선해 드려요</div>
								<img src="./img/sec02_icon14.svg">
							</li>
						</ul>
					</div>
				</div>
			</div>
		</section>


		<section id="sec03" class="bg01" data-scroll-index="3">
			<div class="sectionContainer tcenter">
				<div class="txtCon tcenter">
					<div class="t1 scrollMotion">+ 인슈플러스 의료진</div>
					<div class="t2 scrollMotion">의료진 소개</div>
					<div class="t3 scrollMotion">12개 과목 한국인 전문의가 원격진료, 전문의 상담, 응급 의료 상담 제공</div>
				</div>
				<div class="flex flex-column gap40 lg:mt100 pc-only">
					<div class="flex lg:flex-center lg:flex-middle gap40">
						<img src="./img/sec03_img01.png" class="scrollMotion">
						<img src="./img/sec03_img02.png" class="scrollMotion" data-delay="0.1">
					</div>
					<div class="flex lg:flex-center lg:flex-middle gap40">
						<img src="./img/sec03_img03.png" class="scrollMotion">
						<img src="./img/sec03_img04.png" class="scrollMotion" data-delay="0.1">
						<img src="./img/sec03_img05.png" class="scrollMotion" data-delay="0.2">
					</div>
					<div class="flex lg:flex-center lg:flex-middle gap40">
						<img src="./img/sec03_img06.png" class="scrollMotion">
						<img src="./img/sec03_img07.png" class="scrollMotion" data-delay="0.1">
					</div>
				</div>

				<div class="medical-list mobile-only mt40">
					<img src="./img/sec03_img01.png" class="scrollMotion">
					<img src="./img/sec03_img02.png" class="scrollMotion">
					<img src="./img/sec03_img03.png" class="scrollMotion">
					<img src="./img/sec03_img04.png" class="scrollMotion">
					<img src="./img/sec03_img05.png" class="scrollMotion">
					<img src="./img/sec03_img06.png" class="scrollMotion">
					<img src="./img/sec03_img07.png" class="scrollMotion">
				</div>
				
				<div class="txtCon tcenter lg:mt160 sm:mt90">
					<div class="decotitle01 scrollMotion">메디컬 팀</div>
					<div class="t3 scrollMotion lg:mt25 sm:mt10">200회 이상 긴급의료 이후송 경험이 있는<br>메디컬 디렉터와 응급의학과 전문의로 구성된 메디컬팀이 이송 진행</div>
				</div>
				<div class="medical_info lg:mt100 sm:mt40 scrollMotion right">
					<img src="./img/sec03_img08.png" class="scrollMotion">
					<div class="box">
						<svg xmlns="http://www.w3.org/2000/svg" width="55" height="44" viewBox="0 0 55 44" fill="none" class="sm:w-25">
							<path d="M17.92 20.352C22.144 22.144 24.704 25.984 24.704 31.232C24.704 38.656 19.456 43.52 12.416 43.52C5.248 43.52 0 38.528 0 31.232C0 27.904 0.512 25.472 3.456 18.432L10.88 0H23.552L17.92 20.352ZM48.128 20.352C52.352 22.144 54.912 25.984 54.912 31.232C54.912 38.656 49.664 43.52 42.624 43.52C35.456 43.52 30.208 38.528 30.208 31.232C30.208 27.904 30.72 25.472 33.664 18.432L41.088 0H53.76L48.128 20.352Z" fill="#452839"/>
						</svg>
						<div class="title lg:mt20 sm:mt10">Medical Director<br>응급의학과 최재형 교수</div>
						<ul class="_ul_head_circle lg:mt35 sm:mt20">
							<li>해외환자 이송경험 200회 이상</li>
							<li>의학박사</li>
							<li>현 순천향대학병원 응급의학과 교수</li>
							<li>전 한양대학교 응급의학교실 임상교수</li>
						</ul>
					</div>
					<span class="decobg scrollMotion left"></span>
				</div>

				<div class="txtCon tcenter lg:mt100 sm:mt50">
					<div class="decotitle02 scrollMotion">Team Member</div>
				</div>
				<ul class="_ul_medical_info lg:mt100 sm:mt50">
					<li class="scrollMotion">
						<img src="./img/sec03_img09.png">
						<div class="box">
							<div class="title">이대욱 교수</div>
							<ul class="_ul_head_circle lg:mt15 sm:mt10">
								<li>안성의료원</li>
								<li>응급의학과</li>
							</ul>
						</div>
						<span class="decobg scrollMotion left"></span>
					</li>
					<li class="scrollMotion">
						<img src="./img/sec03_img10.png">
						<div class="box">
							<div class="title">유대한 교수</div>
							<ul class="_ul_head_circle lg:mt15 sm:mt10">
								<li>한양대병원</li>
								<li>응급의학과</li>
							</ul>
						</div>
						<span class="decobg scrollMotion left"></span>
					</li>
					<li class="scrollMotion">
						<img src="./img/sec03_img11.png">
						<div class="box">
							<div class="title">신환재 교수</div>
							<ul class="_ul_head_circle lg:mt15 sm:mt10">
								<li>충남대병원</li>
								<li>응급의학과</li>
							</ul>
						</div>
						<span class="decobg scrollMotion left"></span>
					</li>
					<li class="scrollMotion">
						<img src="./img/sec03_img12.png">
						<div class="box">
							<div class="title">임재웅 전문의</div>
							<ul class="_ul_head_circle lg:mt15 sm:mt10">
								<li>에크모전문</li>
								<li>흉부외과</li>
							</ul>
						</div>
						<span class="decobg scrollMotion left"></span>
					</li>
				</ul>
			</div>
		</section>

		<section id="sec04" class="" data-scroll-index="4">
			<div class="sectionContainer tcenter">
				<div class="txtCon">
					<img src="./img/sec04_icon01.svg" class="scrollMotion sm:w-50">
					<div class="title scrollMotion">24시간<br>알람센터</div>
					<div class="sub scrollMotion">
						해외에서 아플 때 언제 어디서든 편하게 문의하세요<br>
						카카오톡과 전화, 이메일을 통해 <span class="color-red">전세계 어디서든</span><br>
						<span class="color-red">한국 의료진의 케어</span>를 받으실 수 있습니다.
					</div>
				</div>
				<div class="imgCon">
					<img src="./img/sec04_img01.png" class="scrollMotion right">
				</div>
			</div>
		</section>


		<section id="sec05" class="" data-scroll-index="5">
			<div class="sectionContainer">
				<div class="txtCon tcenter">
					<div class="t2 scrollMotion">서비스 상세 안내</div>
				</div>
				<div class="_tblContainer lg:mt110 sm:mt50">
					<div class="title">해외 의료지원</div>
					<table class="">
						<tbody>
							<tr>
								<th>24시간 의료 서비스</th>
								<td>
									전문 상담 간호사가 24시간 항시 대기하여 여행 중 의료 상담이 필요할 때,<br>
									언제 어디서든 카카오채널 및 전화로 의료 서비스 정보 및 조언을 받을 수 있습니다.<br>
									한국인 의사와 원격진료, 전문의 상담, 응급 의료 상담을 제공해 드립니다.
								</td>
							</tr>
							<tr>
								<th>현지 병원예약</th>
								<td>
									여행 중 현지에서 병원에 방문하셔야 되는 경우, 24시간 알람센터로 연락 주시면<br>
									가입자의 가장 가까운 곳에 위치한 증상에 맞는 제휴 병원으로 추천 및 예약을 도와드립니다.
								</td>
							</tr>
							<tr>
								<th>해외병원비 대신지불(지불보증)</th>
								<td>
									가입하신 보험 한도 내에서 발생하는 진료비를 인슈플러스에서 병원으로 대신 지불해 드립니다.<br>
									병원 예약시 병원비 대신 지불을 함께 요청하셔서 진료 후 병원비 걱정 없이 바로 귀가하세요.<br>
									해외병원비 대신 지불은 보험이 포함된 상품 가입시 서비스 받으실 수 있습니다.
								</td>
							</tr>
							<tr>
								<th>원격화상진료</th>
								<td>
									휴대폰으로 간편하게 원격진료를 제공해 드립니다. 미국의 경우, 미국병원 처방전을 발행 받을 수 있습니다.<br>
									원격화상진료는 보험이 포함된 상품 가입시 서비스 받으실 수 있습니다.
								</td>
							</tr>
							<tr>
								<th>여행 출국 전 정보</th>
								<td>
									여행지의 기후 및 원화 대비 환율과 여행지에서의 교통 및 숙박 관련 정보 안내해 드립니다.
								</td>
							</tr>
							<tr>
								<th>수하물 분실 및 여권분실 시 지원</th>
								<td>
									해외에서 여권이나 수하물을 분실하였을 경우 이를 찾을 수 있도록 절차 및 유관 기업을 안내해 드립니다.
								</td>
							</tr>
							<tr>
								<th>긴급 통역 지원</th>
								<td>
									의료용어가 어렵거나 표현이 힘드신 분은 3자 통화를 통해 의료통역이 제공됩니다.
								</td>
							</tr>
							<tr>
								<th>예방접종 및 비자 요건 정보</th>
								<td>
									해외여행 혹은 체류 중인 가입자의 편의를 위해 방문하는 국가별 필요 예방접종 및 비자에 대한 안내를 받으실 수 있습니다.
								</td>
							</tr>
							<tr>
								<th>법률 관련 알선</th>
								<td>
									인슈플러스 서비스가 제공되는 국가에서 여행 중 가입자에게 사고가 발생한 경우, 가입자에게 변호사를 알선해 드립니다.
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="_tblContainer lg:mt60 sm:mt40">
					<div class="title">긴급이후송</div>
					<table class="">
						<tbody>
							<tr>
								<th>국가내 의료이송</th>
								<td>
									현지 국가 내 의료진 동반 하에 상급 의료시설로 후송, 제반 비용 한도 내 보상합니다.
								</td>
							</tr>
							<tr>
								<th>인접국 의료이송</th>
								<td>
									응급상황 발생 시 적절한 치료가 가능한 인접국으로 이송, 제반 비용 한도 내 보상합니다.
								</td>
							</tr>
							<tr>
								<th>긴급의료 본국이송 (에어앰뷸런스)</th>
								<td>
									심각하고 위중한 질병 또는 사고로 한국으로 이송이 필요한 경우 의료진이 동반하여<br>
									에어앰뷸런스 이송, 제반 비용 한도 내 보상합니다.
								</td>
							</tr>
							<tr>
								<th>긴급의료 본국이송 (일반항공)</th>
								<td>
									심각하고 위중한 질병 또는 사고로 한국으로 이송이 필요한 경우 의료진이 동반하여 일반항공 이송, 제반 비용 한도 내 보상합니다.
								</td>
							</tr>
							<tr>
								<th>유해송환</th>
								<td>
									사망 시 유해 송환에 필요한 절차를 지원하며 제반 비용은 한도 내 보상합니다.
								</td>
							</tr>
							<tr>
								<th>간병 친/인척 항공편</th>
								<td>
									간병 목적으로 출국하는 친/인척의 이코노미 왕복 항공요금을 지원합니다.
								</td>
							</tr>
							<tr>
								<th>자녀동반 귀국시 항공편</th>
								<td>
									가입자가 응급상황이 발생한 경우, 15세 미만 자녀와 동반 귀국할 친/인척의 귀국 항공요금을 각각 지원합니다.
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</section>

	</div>

	<div id="_mobile_floating_btn" class="mobile-only">
		<a href="https://pf.kakao.com/_JClxfT/chat" target="_blank" alt="카카오톡 문의하기"><img src="./img/mobile_floating_btn01.png"></a>
		<a href="/html/insurance/renewal_step00.php" target="_blank" alt="간편 가격조회 하기"><img src="./img/mobile_floating_btn02.png"></a>
	</div>
</div>

<footer id="footer">
	<div id="footerContainer">
		<div class="flex lg:flex-middle lg:gap60 sm:column sm:gap25 sm:p25">
			<div class="ftCon f1">
				<div class="_head">
					<sub>인슈플러스 서비스 제공사</sub>
					<div class="name">㈜플라잉닥터스</div>
					<span class="opener mobile-only" data-target="#f1Con"></span>
				</div>
				<p id="f1Con" class="con">
					사업자등록번호 : 110-86-08297<br>
					대표이사 : 김형태<br>
					서울특별시 서대문구 충정로7, 구세군빌딩 B1층<br>
					통신판매업신고 : 제2019-서울서대문-0682호
				</p>
			</div>
			<div class="ftCon f2">
				<div class="_head">
					<sub>인슈플러스 보험 대리점</sub>
					<div class="name">㈜비즈인사이트</div>
					<span class="opener mobile-only" data-target="#f2Con"></span>
				</div>
				<p id="f2Con" class="con">
					사업자등록번호 : 107-86-90485<br>
					대표이사 : 김상수<br>
					서울특별시 서대문구 충정로7, 구세군빌딩 8층<br>
					통신판매업신고 : 제2016-서울서대문-0064호 | 보험대리점등록 : 제2010111034호
				</p>
			</div>
			<div class="ftCon f3 lg:ml-auto">
				<sub>고객센터</sub>
				<div class="name">24시간 알람센터</div>
				<div class="tel">02.360.2545</div>
				<a href="https://pf.kakao.com/_JClxfT/chat" target="_blank" alt="카카오톡 채팅상담"><img src="./img/btn_kakao.png" class="sm:w-150"></a>
			</div>
		</div>
		<div class="copyrights">
			Copyright 2019 인슈플러스 CO.LTD All Rights Reserved.
		</div>
	</div>
</footer>

</div>
<!-- //#root -->
<?php
include "../_include/_header.html";
include "../_include/_top.html";
include "../_include/_sidebar.html";

$lm = "0106"; //레프트 메뉴 활성화
?>
<div class="breadcrumb-image">
	<div class="container">
		<h2>원격화상진료</h2>
		<h4>원격화상진료입니다.</h4>
	</div>
</div>
<div class="breadcrumb-wrap">
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="../main/index.php">InsuPlus HOME</a></li>
			<li><a href="../customer/cs_center.php">고객센터</a></li>
			<li>원격화상진료</li>
		</ol>
	</div>
</div>
<div class="overflow-hidden">
	<div class="container">
		<div class="row">
			<div class="col-md-2 col-sm-3">
				<?php
				include "../_include/_cs_left.html";
				?>
			</div>
			<div class="col-md-10 col-sm-9">
				<div class="sub-content cs-wrap">
					<!--내용 시작 20210626-->
					<div class=" hidden-xs">
						<h2 class="text-black">원격화상 진료</h2>
						<h5 class="m-t-05">휴대폰으로 간편하게 원격진료를 받아 보세요.</h5>  <!--20230529 문구수정-->
						<div class="clearfix m-t-2">
							<img src="../images/title-csskinburn.png" srcset="../images/title-csskinburn@2x.png 2x, ../images/title-csskinburn@3x.png 3x" style="width:100%;">
						</div>
					</div>
					<div class="hidden visible-xs" style="margin:-16px -16px auto -16px;">
						<img src="../images/title-csskinburn-m.png?v=2" style="width:100%;">
					</div>
					<!--진료시간 20210626-->
					<h5 class="text-black m-t-3 m-b-05">
						<span class="point">1. 진료시간</span>
					</h5>
					<ul class="list-instyled icons m-b-05">
						<li class="line-height-2">- 미국동부시간 월-금 08:00~20:00, 토 08:00~17:00</li>
						<li class="line-height-2">- 원하는 날짜, 원하는 시간대에 진료를 받으실 수 있습니다. <span class="t-red">( ※진료예약 필수 )</span>
						</li>
					</ul>
					<!--진료분야 20210626-->
					<h5 class="text-black m-t-3 m-b-05">
						<span class="point">2. 진료분야</span>
					</h5>
					<ul class="list-instyled icons m-b-05">
						<li class="line-height-2">- 가정의학과, 내과, 피부과, 여성 및 남성건강 관련 1차 의료 / 만성질환 관리, 2차 소견 등</li>
						<li class="line-height-2">- 체류국가가 미국인 경우 가까운 약국으로 처방조제를 받을 수 있습니다. <span class="t-red">( ※미국 외 국가는 필요 시 일반의약품 중심의 복약상담 )</span>
						</li>
					</ul>
					<!--이용방법 20210626-->
					<!--수정시작 20230213-->
					<h5 class="text-black m-t-3 m-b-05">
						<span class="point">3. 이용방법</span>
					</h5>
<!--20230529 추가 start--> 
					<ul class="list-instyled icons m-b-05">
						<li class="line-height-2">
							- 인슈플러스 <a href="https://pf.kakao.com/_JClxfT" target="_blank"> <text style="font-weight: 700; color: coral">카카오 채널</text></a>로 원격진료 요청을 해주세요.
						</li>
						<li class="line-height-2">
							- 인슈플러스 24시간 알람센터에서 회원 인증 후 원격진료를 받으실 수 있습니다.<br>
							(※ 원격진료 의료비는 보험으로 청구되며 청구에 필요한 서류를 제출해 주셔야 합니다.) 
						</li>
					</ul>
					<!—20230529 추가 end-->

						<div>
							<span class="guide_subtitle">[ 닥터히어 이용방법 ]</span>
							<section class="guide">
								<div class="guide_flexbox">
									<div class="guide_item">
										<img src="../images/guide_01.png" style="height: auto; width: 200px;">
									</div>
									<div class="guide_item">
										<img src="../images/guide_02.png" style="height: auto; width: 200px;">
									</div>
									<div class="guide_item">
										<img src="../images/guide_03.png" style="height: auto; width: 200px;">
									</div>
								</div>
							</section>
						</div>
					</div>
					<!--수정끝 20230213-->
					<!--디자인 가이드 끝 20220926-->
				</div>
				<!--내용 끝 20210626-->
			</div>
		</div>
	</div>
</div>
</div>
<?php
include "../_include/_tail.html";
include "../_include/_footer.html";
?>
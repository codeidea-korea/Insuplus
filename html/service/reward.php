<?php
	include "../_include/_header.html";
	include "../_include/_top.html";
	include "../_include/_sidebar.html";
	
	$lm = "0201"; //레프트 메뉴 활성화
?>
		<div class="breadcrumb-image">
			<div class="container">
				<h2>보상신청 안내</h2>
				<h4>인슈플러스 보험 상품에 대해 자세히 알려드립니다.</h4>
			</div>
		</div>
		<div class="breadcrumb-wrap">
            <div class="container">
				<ol class="breadcrumb">
					<li><a href="../main/index.php">InsuPlus HOME</a></li>
					<li><a href="../service/reward.php">보상서비스</a></li>
					<li>보상신청 안내</li>
				</ol>
            </div>
        </div>
		<div class="overflow-hidden">
			<div class="container">
				<div class="row">
					<div class="col-md-2 col-sm-3">
						<?php
							include "../_include/_service_left.html";
						?>
					</div>
					<div class="col-md-10 col-sm-9">
						<div class="sub-content cs-wrap">
							<h3 class="text-black font-bold m-b-1"><img src="../images/ic-small-logo.svg" align="absmiddle" alt="" class="" />&nbsp;인슈플러스 가입자</h3>
							<h5 class="text-black reponsive">24시간 알람센터 전화, 카카오톡, 이메일로 문의하세요.<br>보상신청을 대행해 드립니다.</h5>
							<div class="row btn-icon-group btn-group-csenter">
								<!-- 20231013 전화번호 연결 시작 -->
								<!-- 20231013 전화배너영역 선택시 is-pc에서는 24시간 알람센터 이동되고 is-m에서는 디바이스 전화걸기화면으로 이동-->
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 m-t-1 is-pc">
									<a class="btn btn-block btn-default btn-icon btn-cscenter" href="../customer/cs_center.php">
										<p><small class="text-gray">24시간 알람 센터</small></p>
										<h3 class="text-danger"><?=$insuplus_phone;?></h3>
										<p class="text-black hidden-xs">24시간 연중무휴</p>
									</a>
								</div>
									
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 m-t-1 is-m">
									<a class="btn btn-block btn-default btn-icon btn-cscenter" href="tel:02.360.2545"> 
										<p><small class="text-gray">24시간 알람 센터</small></p>
										<h3 class="text-danger"><?=$insuplus_phone;?></h3>
										<p class="text-black hidden-xs">24시간 연중무휴</p>
									</a>
								</div>
								<!-- 20231013 전화번호 연결 끝-->
								<div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 m-t-1">
									<a class="btn btn-block btn-default btn-icon kakao" href="<?=$insuplus_kakaoPlus?>" target="_blank">
										<p><small class="text-gray">카카오톡 플러스 친구</small></p>
										<h3>인슈플러스</h3>
									</a>
								</div>
								
								<div id="email" class="col-lg-4 col-md-4 col-sm-12 col-xs-12 m-t-1" onclick="copyEmailAdd();">
									<a class="btn btn-block btn-default btn-icon email">
										<span>
											<p><small class="text-gray">이메일</small></p>
											<h3><?=$insuplus_email;?></h3>
										</span>
									</a>
								</div>
<!--
								<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 m-t-1">
									<a class="btn btn-block btn-default btn-icon email">
										<span>
											<p><small class="text-gray">이메일</small></p>
											<h3><?=$insuplus_email;?></h3>
										</span>
									</a>
								</div>
-->
							</div>
							<div class="row">
								<div class="col-sm-3">
								</div>
								<div class="col-sm-3"></div>
								<div class="col-sm-3"></div>
							</div>
							<h5 class="text-black m-t-3 m-b-05"><span class="point">1. 병원비 대신 지불(지불대행) 신청</span></h5>
							<ul class="list-instyled icons m-b-05">
								<li class="line-height-2"><i class="ti ti-minus"></i>병원 예약 및 지불보증을 요청합니다.</li>
								<li class="line-height-2"><i class="ti ti-minus"></i>알람센터를 통해 병원 예약 및 지불보증 확인 후 의료정보공개 동의서를 제출합니다. </li>
								<li class="line-height-2"><i class="ti ti-minus"></i>인슈플러스가 고객님의 병원비를 보험사에 청구합니다.</li>
							</ul>
							<div class="row clearfix">
								<div class="col-md-7 col-sm-12">
									<div class="row">
										<div class="col-md-4 col-sm-4 col-xs-6 m-t-1">
											<a class="btn btn-default btn-block" href="./sample/의료정보공개 동의서_인슈플러스.doc">의료정보 공개 동의서</a>
										</div>
									</div>
								</div>
							</div>
							<h5 class="text-black m-t-3 m-b-05"><span class="point">2. 병원비 지불 후 상품가격 청구 신청</span></h5>
							<ul class="list-instyled icons">
								<li class="line-height-2"><i class="ti ti-minus"></i>병원 예약을 요청합니다.</li>
								<li class="line-height-2"><i class="ti ti-minus"></i>예약된 병원에서 진료를 받습니다.</li>
								<li class="line-height-2"><i class="ti ti-minus"></i>보험금 청구서, 메디컬 리포트, 병원비 영수증을 알람센터로 보냅니다.</li>
								<li class="line-height-2"><i class="ti ti-minus"></i>보험금 청구신청서 받기</li>
							</ul>
							<div class="row clearfix">
								<div class="col-md-12 col-sm-12">
									<div class="row">
										<div class="col-md-3 col-sm-4 col-xs-6 m-t-1">
											<a href="./sample/현대해상_청구서,동의서.pdf" target="_blank" class="btn btn-default btn-block">보험금 청구신청서(현대)</a>
										</div>
										<div class="col-md-3 col-sm-4 col-xs-6 m-t-1">
											<a href="./sample/한화_청구서,동의서.pdf" target="_blank" class="btn btn-default btn-block">보험금 청구신청서(한화)</a>
										</div>
										<div class="col-md-3 col-sm-4 col-xs-6 m-t-1">
											<a href="./sample/메리츠화지_청구서, 동의서.pdf" target="_blank" class="btn btn-default btn-block">보험금 청구신청서(메리츠)</a>
										</div>
									</div>
								</div>
							</div>
							<ul class="list-instyled icons">
								<li class="line-height-2"><i class="ti ti-minus"></i>샘플 보기</li>
							</ul>
							<div class="row clearfix">
								<div class="col-md-12 col-sm-12">
									<div class="row">
										<div class="col-md-3 col-sm-4 col-xs-6 m-t-1">
											<a class="btn btn-default btn-block" data-toggle="pop-modal" data-size="sm" data-href="pop_midical_report.php" data-title="메디컬 리포트 샘플" target="modal_iframe">메디컬 리포트 샘플</a>
										</div>
										<div class="col-md-3 col-sm-4 col-xs-6 m-t-1">
											<a class="btn btn-default btn-block" data-toggle="pop-modal" data-size="sm" data-href="pop_invoice.php" data-title="병원비 영수증 샘플" target="modal_iframe">병원비 영수증 샘플</a>
										</div>
									</div>
								</div>
							</div>
							<!--
							<hr class="m-t-4">
							<h3 class="text-black font-bold m-t-2 m-b-1"><img src="../images/ic-small-logo.svg" align="absmiddle" alt="" class="">&nbsp;일반상품 가입자</h3>
							<h5 class="text-black m-b-05">가입하신 보험사 홈페이지나 보상신청 고객센터로 신청하실 수 있습니다.</h5>
							<h5 class="text-black m-t-3 m-b-15"><span class="point">필수서류 안내</span></h5>
							<ul class="list-instyled icons m-b-15">
								<li class="line-height-2"><i class="ti ti-minus"></i>병원비 대신 지불(지불대행) 신청</li>
								<li class="line-height-2"><i class="ti ti-minus"></i>보험사별 청구 신청서를 다운로드 받아서 신청하실 수 있고 인터넷 접수시 작성하지 않으셔도 됩니다.</li>
								<li class="line-height-2"><i class="ti ti-minus"></i>제출 서류는 보험사별로 다를 수 있으니 보험사 고객센터로 문의해 보시기 바랍니다.</li>
							</ul>
							<div class="row clearfix">
								<div class="col-md-7 col-sm-12">
									<div class="row">
										<div class="col-md-4 col-sm-4 col-xs-6 m-t-1">
											<a class="btn btn-default btn-block" data-toggle="pop-modal" data-size="sm" data-href="pop_midical_report.php" data-title="메디컬 리포트 샘플" target="modal_iframe">메디컬 리포트 샘플</a>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-6 m-t-1">
											<a class="btn btn-default btn-block" data-toggle="pop-modal" data-size="sm" data-href="pop_invoice.php" data-title="병원비 영수증 샘플" target="modal_iframe">병원비 영수증 샘플</a>
										</div>
									</div>
								</div>
							</div>
							<div class="row m-t-2 reword-box-wrap">
								<div class="col-md-3 col-sm-6 col-xs-6 m-t-1">
									<div class="reward-box hanhwa">
										<div class="text-right"><img src="../images/logos/hanhwa.svg" align="absmiddle" class="logo" /></div>
										<h4>한화손해보험</h4>
										<p class="text-black">인터넷 접수하기 <i class="ti ti-angle-right"></i></p>
										<p class="text-black">☎ 1600-0066, 6696</p>
										<div class="row m-t-15">
											<div class="col-md-10 col-md-offset-1">
												<a class="btn btn-block btn-default" href="https://www.hanwhadirect.com/service/reward/disease_01.do" target="_blank">청구 신청서</a>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3 col-sm-6 col-xs-6 m-t-1">
									<div class="reward-box hyundai">
										<div class="text-right"><img src="../images/logos/hyundai.svg" align="absmiddle" class="logo" /></div>
										<h4>현대해상</h4>
										<p class="text-black">인터넷 접수하기 <i class="ti ti-angle-right"></i></p>
										<p class="text-black">☎ 1588-5656 <span class="hidden-xs">/</span><span class="hidden visible-xs"></span> 단축번호 4번</p>
										<div class="row m-t-15">
											<div class="col-md-10 col-md-offset-1">
												<a class="btn btn-block btn-default" href="https://www.hi.co.kr/serviceAction.do?menuId=100631" target="_blank">청구 신청서</a>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3 col-sm-6 col-xs-6 m-t-1">
									<div class="reward-box meritz">
										<div class="text-right"><img src="../images/logos/meritz.svg" align="absmiddle" class="logo" /></div>
										<h4>메리츠 화재</h4>
										<p class="text-black">인터넷 접수하기 <i class="ti ti-angle-right"></i></p>
										<p class="text-black">☎ 1566-7711</p>
										<div class="row m-t-15">
											<div class="col-md-10 col-md-offset-1">
												<a class="btn btn-block btn-default" href="https://www.meritzfire.com/compensation.do#!/" target="_blank">청구 신청서</a>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3 col-sm-6 col-xs-6 m-t-1">
									<div class="reward-box mg">
										<div class="text-right"><img src="../images/logos/mg.svg" align="absmiddle" class="logo" /></div>
										<h4>MG 손해보험</h4>
										<p class="text-black">인터넷 접수하기 <i class="ti ti-angle-right"></i></p>
										<p class="text-black">☎ 02-3702-2326 , 2327</p>
										<div class="row m-t-15">
											<div class="col-md-10 col-md-offset-1">
												<a class="btn btn-block btn-default" href="https://www.mggeneralins.com/RW031010DM.scp?menuId=MN0501020" target="_blank">청구 신청서</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							<table width="100%" class="table table-bordered sr-only">
								<colgroup><col width="30%"><col width="*"><col width="25%"></colgroup>
								<tbody class="text-center">
									<tr>
										<th>한화손해보험</th>
										<td>인터넷 접수하기 <i class="ti ti-angle-right"></i><span class="hidden visible-xs"></span> ☎ 1600-0066 , <span class="hidden visible-xs"></span>1600-6696</td>
										<td class="p-x-1 p-y-05"><a href="#" class="btn btn-block btn-theme-dark light">청구 <span class="hidden visible-xs"></span>신청서</a></td>
									</tr>
									<tr>
										<th>현대해상</th>
										<td>인터넷 접수하기 <i class="ti ti-angle-right"></i><span class="hidden visible-xs"></span> ☎ 1588-5656 <span class="hidden visible-xs"></span>/ 단축번호 4번</td>
										<td class="p-x-1 p-y-05"><a href="#" class="btn btn-block btn-theme-dark light">청구 <span class="hidden visible-xs"></span>신청서</a></td>
									</tr>
									<tr>
										<th>메리츠화재</th>
										<td>인터넷 접수하기 <i class="ti ti-angle-right"></i><span class="hidden visible-xs"></span> ☎ 1566-7711</td>
										<td class="p-x-1 p-y-05"><a href="#" class="btn btn-block btn-theme-dark light">청구 <span class="hidden visible-xs"></span>신청서</a></td>
									</tr>
									<tr>
										<th>현대해상</th>
										<td>인터넷 접수하기 <i class="ti ti-angle-right"></i><span class="hidden visible-xs"></span> ☎ 02-3702-2326 , 2327</td>
										<td class="p-x-1 p-y-05"><a href="#" class="btn btn-block btn-theme-dark light">청구 <span class="hidden visible-xs"></span>신청서</a></td>
									</tr>
								</tbody>
							</table>							
							<div class="row btn-icon-group btn-group-csenter">
								<div class="col-lg-5 col-md-6 col-sm-12 col-xs-12 m-t-1">
									<div class="btn btn-block border-a-0 btn-icon case" href="#">
										<div class="row">
											<div class="col-sm-8 col-xs-12">
												<p>전세계에서 인슈플러스 서비스를</p>
												<h3>이용한 사례를 확인해 보세요!</h3>
											</div>
											<div class="col-sm-4 col-xs-12">
												<a class="btn btn-block btn-default text-black" href="./reward_list.php">보상사례보기</a>
											</div>
										</div>
									</div>
								</div>
							</div>
							-->
						</div>
					</div>
				</div>
			</div>
        </div>
		<!--20231013 이메일 복사-->
		<script>
            function copyEmailAdd() {
				var copyText = document.createElement("textarea");
				document.body.appendChild(copyText);
				copyText.value = 'help@insuplus.co.kr';
				copyText.select();
				document.execCommand('copy');
				document.body.removeChild(copyText);
				alert(copyText.value + '\n이메일 주소 복사 완료!');
			}
		</script>

<?php
	include "../_include/_tail.html";
	include "../_include/_footer.html";
?>


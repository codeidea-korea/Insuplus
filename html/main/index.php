<?php
include '../_include/_header_new.html';
include '../_include/_top.html';
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/Func.main.php";

$rs_banner = getMainBanner(); //메인 배너
$rs_nurseCounsel = getNurseCounsel(); //간호사 상담
$rs_qnaService = getQnaService(); //문의사항
$rs_notice = getNotiList();

$today = date("Y-m-d");
$_SESSION["orderno"] = "";

?>
<section class="no-bg">
			<div class="main-box-01">
				<div class="container">
					<h2>해외여행보험에<br>안심플러스</h2>
					<p class="is-pc">여행의 처음부터 끝까지<br><strong class="ft-yellow2">인슈플러스</strong>가 24시간 동행합니다</p>
					<p class="mt20 is-m">여행의 처음부터 끝까지<br><strong class="ft-yellow2">인슈플러스</strong>가<br>24시간 동행합니다</p>
					<div class="is-pc">
						<ul class="btn-list-wrap">
							<li>
								<a href="https://pf.kakao.com/_JClxfT/chat" target="_blank" class="kakao">카카오 문의하기</a> <!--20231013 링크 새창 적용-->
							</li>
							<li><a href="/html/insurance/renewal_step00.php" class="price">간편 가격 조회하기</a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="tab-fixed-box">
				<div class="tab-box-inner">
					 <div class="tab-head">
						<strong>해외여행보험 +</strong>
					</div>
					<div class="tab-body">
						<ul>
							<li class="active"><a href="javascript:;" data-position=".main-box-02">24시간 알람센터</a></li>
							<li><a href="javascript:;" data-position=".main-box-03">해외의료지원</a></li>
							<li><a href="javascript:;" data-position=".main-box-04">해외여행지원</a></li>
							<li><a href="javascript:;" data-position=".main-box-05">긴급항공이송</a></li>
						</ul>
					</div>
				</div>
			</div>

			<div class="is-pc">
				<ul class="floating-list">
					<li>
						<a href="https://pf.kakao.com/_JClxfT/chat" class="kakao">
							카카오<br>문의하기
						</a>
					</li>
					<li>
						<a href="/html/insurance/renewal_step00.php" class="price">
							인슈플러스<br>
							간편 가격조회
						</a>
					</li>
					<li>
						<a href="#" class="top"><small>▲</small><br>TOP</a>
					</li>
				</ul>
			</div>
			<div class="is-m">
				<ul class="floating-list-m">
					<li>
						<a href="https://pf.kakao.com/_JClxfT/chat" class="kakao">
							<span class="tts">카카오<br>문의하기</span>
						</a>
					</li>
					<li>
						<a href="/html/insurance/renewal_step00.php" class="price">
							<span class="tts">간편 가격 조회하기</span>
						</a>
					</li>
				</ul>
			</div>


			<div class="main-box-02">
				<div class="title-box">
					<h2>24시간 알람센터</h2>
					<p class="is-pc">
						해외에서 아플 때 언제 어디서든 전화나 카카오톡으로 연락하세요<br><strong class="ft-red">전세계</strong> 어디서든 한국 의료진의 케어를 받으실 수 있습니다
					</p>
					<p class="is-m">
						해외에서 아플 때 언제 어디서든<br>전화나 카카오톡으로 연락하세요<br><strong class="ft-red">전세계</strong> 어디서든 한국 의료진의 케어를 받으실 수 있습니다
					</p>
				</div>
			</div>
			<div class="main-box-03">
				<div class="title-box">
					<h2>해외의료지원</h2>
					<p>
						해외병원 치료와 한국인 의사 원격진료를<br><strong class="ft-red">24시간</strong> 언제든지 지원해 드립니다
					</p>
				</div>

				<div class="middle-title-box">
					<h3>&middot; 해외병원치료 지원 &middot;</h3>
					<p class="is-pc">낯선 해외에서도 예약된 병원에서 치료만 받고 오세요<br><strong class="ft-red">전세계 350,000여개의 의료네트워크</strong>를 통해 해외병원 치료를 지원해 드립니다</p>
					<p class="is-m">낯선 해외에서도 예약된 병원에서 치료만 받고 오세요<br><strong class="ft-red">전세계 350,000여개의 의료네트워크</strong>를 통해<br>해외병원 치료를 지원해 드립니다</p>
				</div>

				<ul class="img-flex-box">
					<li class="img-flex-item mr30 mr-lg-0">
						<img src="../images/img_main_02_01.png" alt="">
						<h4>병원예약 및 통역</h4>
						<p>증상에 맞는 가까운 병원으로 예약해 드리고<br>전문 간호사가 의료통역을 제공해 드립니다</p>
					</li>
					<li class="img-flex-item">
						<img src="../images/img_main_02_02.png" alt="">
						<h4>병원비 대신 지불</h4>
						<p>치료받은 병원에 병원비를 대신 내드려<br>복잡한 서류를 받아서 청구하실 필요가 없습니다</p>
					</li>
				</ul>

				<div class="middle-title-box">
					<h3>&middot; 한국인 의사 원격진료 &middot;</h3>
					<p class="is-pc">전세계 어디서든 한국인 의사의 케어서비스를 받으세요<br><strong class="ft-red">원격진료, 12개과목 전화예약 상담, 24시간 응급 상담 서비스</strong>를 제공해 드립니다</p>
					<p class="is-m">전세계 어디서든 한국인 의사의 케어서비스를 받으세요<br><strong class="ft-red">원격진료, 12개과목 전화예약 상담,<br>24시간 응급 상담 서비스</strong>를 제공해 드립니다</p>
				</div>

				<ul class="img-flex-box">
					<li class="img-flex-item">
						<img src="../images/img_main_02_03.png" alt="">
						<h4>원격진료</h4>
						<p>휴대폰으로 간편하게<br>원격진료를 제공해 드립니다<small>*미국병원 온라인 처방전 발행</small></p>
					</li>
					<li class="img-flex-item">
						<img src="../images/img_main_02_04.png" alt="">
						<h4>12개과목 전문의</h4>
						<p>12개과목 한국인 전문의가<br>상담해 드립니다</p>
					</li>
					<li class="img-flex-item">
						<img src="../images/img_main_02_05.png" alt="">
						<h4>24시간 응급상담</h4>
						<p>24시간 365일 응급의학과<br>전문의가 대기합니다</p>
					</li>
				</ul>

				<div class="middle-title-box">
					<h3>&middot; 의료진 소개 &middot;</h3>
					<p>가정의학과, 응급의학과, 내과, 소아과, 피부과 등 전문의가<br><strong class="ft-red">원격진료, 전문의 상담, 응급 의료 상담</strong>을 제공해 드립니다</p>
				</div>				
				<img src="../images/img_randing_02.png" class="is-pc" alt="최재형-응급의학과, 이현지-가정의학과, 오혜미-가정의학과, 김혜란-응급의학과, 김우성-응급의학과, 유대한-응급의학과, 24시간 간호사팀">
				<img src="../images/img_randing_02_mob.png" class="is-m" alt="최재형-응급의학과, 이현지-가정의학과, 오혜미-가정의학과, 김혜란-응급의학과, 김우성-응급의학과, 유대한-응급의학과, 24시간 간호사팀">
			</div>
			<div class="main-box-04">
				<div class="title-box">
					<h2>해외여행지원</h2>
					<p class="is-pc">여행중 발생할 수 있는 <strong class="ft-red">지갑 도난, 수하물 지연, 여권 분실</strong> 등<br>불편상황 해결을 도와 드립니다</p>
					<p class="is-m">여행중 발생할 수 있는 <strong class="ft-red">지갑 도난,<br>수하물 지연, 여권 분실</strong> 등<br>불편상황 해결을 도와 드립니다</p>
				</div>
				<ul class="item-flex-wrap">
					<li>
						<img src="../images/icon_main_04_01.png" alt="">
						<dl class="text-box">
							<dt>분실 수하물 추적</dt>
							<dd>수하물이 도착하지 않은 경우<br>위치를 추적해 드립니다</dd>
						</dl>
					</li>
					<li>
						<img src="../images/icon_main_04_02.png" alt="">
						<dl class="text-box">
							<dt>해외 긴급송금 지원</dt>
							<dd>지갑을 분실 했을 때 인슈플러스에서<br>해외로 긴급 송금을 해드립니다</dd>
						</dl>
					</li>
					<li>
						<img src="../images/icon_main_04_03.png" alt="">
						<dl class="text-box">
							<dt>여행정보 안내</dt>
							<dd>예방접종, 비자 등 현지 여행 정보를<br>제공해 드립니다</dd>
						</dl>
					</li>
					<li>
						<img src="../images/icon_main_04_04.png" alt="">
						<dl class="text-box">
							<dt>법률지원</dt>
							<dd>여행 중 사고가 발생한 경우<br>변호사를 알선해 드립니다</dd>
						</dl>
					</li>
				</ul>
			</div>
			<div class="main-box-05">
				<div class="title-box">
					<h2 class="ft-white">긴급이송</h2>
					<p class="ft-white">여행 중 심각한 질병, 사고로 귀국을 할 수 없을 때<br><strong class="ft-red">의료 항공이송</strong>을 해드립니다</p>
				</div>
				<div class="intro-box">
					<div class="intro-image">
						<div class="iframe-box">
							<iframe width="1280" height="720" src="https://www.youtube.com/embed/HPJ_UidIONM?si=MOQ6WP2XRN5BMjqt" title="플라잉닥터스, 에어앰뷸런스 해외환자이송(필리핀 ✈ 한국)" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen=""></iframe>
						</div>
					</div>
					<div class="intro-content">
						<strong>국내 유일 의료용 항공기&middot;전문항공팀&middot;<br>응급의학과 이송팀 보유</strong>
						<p class="is-pc">국내 의료용 항공기로 한국인 응급의학과 전문의와 파일럿이<br>직접 이송을 드리며 이송비용을 2억까지 보장해 드립니다</p>
						<p class="is-m">국내 의료용 항공기로 한국인 응급의학과 전문의와<br>파일럿이 직접 이송을 드리며<br>이송비용을 2억까지 보장해 드립니다</p>
						<a href="http://flyingdoctors.co.kr/" target="_blank">자세히보기</a> <!--20231013 링크 새창 적용-->

					</div>
				</div>
				<small>한국에서 응급의학과 의료진이 현지병원부터 환자와 동행하여 에어엠뷸런스 또는 일반 항공으로 이송해 드리며 이송비용 최대 2억까지 보장해드립니다</small>
			</div>
			<div class="main-box-07">
				<div class="container">
					<div class="title-box">
						<h2>의료지원 서비스 후기</h2>
						<p class="tc fw5 ft-black mt20">인슈플러스와 함께 한 고객님들 후기입니다</p>
					</div>
					<div class="board-gallery-box mt40">
						<ul>
							<? foreach ($rs_nurseCounsel as $row) { 
								$ObjFileName = $row["imgfile"];
								$Arr_FileName = explode("|", $ObjFileName);
								$Result = Array();
								if( count($Arr_FileName) > 1 ) {
									for ( $i = 0 ; $i < count($Arr_FileName); $i++ ) {
										$Result[$i] = explode(",", $Arr_FileName[$i]);
									}
								} else { // 단일 파일 업로드 정보를 가져옴
									$Result[0] = explode(",", $Arr_FileName[0]);
								}
							?>
							<li>
								<a href="javascript: view_go_board('<?= $row["seq"] ?>','counsel_case', 'counsel_case_list');">
									<div class="list-image" style="background-image:url('/_data/board/counsel_case/<?= $Result[0][1] ?>');"></div>
									<div class="list-content">
										<p class="tit"><?= $row["subject"] ?></p><em>더보기</em>
									</div>
								</a>
							</li>
							<? } ?>
						</ul>
						<div class="more">
							<a href="javascript: move_page('../customer/counsel_case_list.php');">더보기</a>
						</div>
					</div>
				</div>
			</div>
			<div class="main-box-08">
				<div class="container">
					<div class="board-list-box">
						<ul>
							<li>
								<div class="list-head">
									<strong>공지사항</strong>
									<a href="../customer/notice_list.php">더보기</a>
								</div>
								<div class="list-body">
									<dl>
									<? foreach ($rs_notice as $row) { ?>
										<dd>
											<a href="javascript:view_go_board('<?= $row["seq"] ?>', 'notice', 'notice_list');"><?= $row["subject"] ?></a>
											<time><?= $row["regdate"] ?></time>
										</dd>
									<? } ?>
									</dl>
								</div>
							</li>
							<li>
								<div class="list-head">
									<strong>문의사항</strong>
									<a href="../customer/qa_list.php">더보기</a>
								</div>
								<div class="list-body">
									<dl>
									<? foreach ($rs_qnaService as $row) { ?>
										<dd>
											<a href="../customer/qa_list.php"><?= $row["subject"] ?></a>
											<time><?= $row["regdate"]; ?></time>
										</dd>
									<? } ?>
									</dl>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</section>
		<script src="../_js/swiper.js?a=1"></script>
		<script src="/html/insurance/js/ehd-object.js"></script>
		<script>
		  /////////////////////////////////////////////////////////////////////////
		  // 메인배너 관리 기능 적용
			// 리뉴얼 퍼블이 PC, Mobile 구분하지 않아 모바일 이미지 사용하지 않음
		  /////////////////////////////////////////////////////////////////////////
			const bannerPath = '/_data/board/main_banner/';
			const pcBannerImg = '<?= explode(',', $rs_banner[0]['imgfile'])[1] ?>';
			const mobileBannerImg = '<?= explode(',', $rs_banner[1]['imgfile'])[1] ?>';

			if (pcBannerImg){
				const pcDiv = document.querySelector('div.main-box-01');
				pcDiv.style.backgroundImage = `url('${bannerPath}${pcBannerImg}')`;
			}
			// if (mobileBannerImg){
			// 	const pcDiv = document.querySelector('div.main-box-01');
			// 	pcDiv.style.backgroundImage = `url('${bannerPath}${pcBannerImg}')`;
			// }
		  /////////////////////////////////////////////////////////////////////////

			$( document ).ready(function() { //임시팝업
				popupOpen('main');
			});

			function view_go_board(seq, id, name) {
				location.href = "../customer/" + name + ".php?mode=view&seq=" + seq + "&page=1&bc_id=" + id + "&search_category=all&num_per_page=10&page_per_block=10&search=all&search_text=&nation=&pr_cd=&search_ext1=&search_ext1=&search_ext1=&search_ext1=&search_ext1=&search_ext1=&search_ext1=&search_ext1=&search_ext1=&search_ext1=";
			}

			function move_page(url) {
					location.href = url;
			}

			function open_new_page(url) {
				var ret = window.open(url, "_blank");
			}

			function fnEvent(url) { //링크 이동
				location.href = url;
			}

			function fnSeachPlan(pr_cd, plan_seq, sell_yn) { //추천상품 상품가격 조회 이동
				if (sell_yn == "N") {
					alert("판매가 중단된 보험입니다. 다른 보험을 이용해 주세요.");
					return;
				}

				location.href = "/html/insurance/search_insur.php?PR_SEQ=" + pr_cd + "&compare_seq=" + plan_seq;
			}
		</script>
<?php
include '../_include/_tail.html';
include '../_include/_footer.html';
?> 
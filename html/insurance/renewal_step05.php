<!DOCTYPE html>
<?php
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/Func.insurance.php"; //추가
include $_SERVER["DOCUMENT_ROOT"] . "/_config/Mobile_Detect.php";

$detect = new Mobile_Detect;

if (!$_SESSION["orderno"]) {
	echo "<script>alert('잘못된 경로로 들어오셨습니다.');location.href='/html/main/index.php';</script>";
	exit;
}
//결제정보
$SQL_R  = " SELECT o.*, j.o_phone, j.o_name, j.seq join_seq, j.o_name_en o_name_en, j.chk_eng_passport chk_eng_passport FROM ";
$SQL_R .= " tbl_order_list o INNER JOIN tbl_order_list_join j ON o.orderno = j.orderno WHERE o.orderno='" . $_SESSION["orderno"] . "' AND j.chk_join = 'N' ";

$result_r = $dbcon->query($SQL_R);
$row_r = $dbcon->fetch_array($result_r);

$PR_INFO = getInsuProductInfo($row_r["pr_cd"]); //상품정보

//영문플랜명 상품에서 출력
$SQL_PRODUCT =  " SELECT ext4, ext9 FROM tbl_board_product WHERE seq = '" . $row_r["pr_cd"] . "' ";
$RS_PRODUCT = $dbcon->query($SQL_PRODUCT);
$row_product = $dbcon->fetch_array($RS_PRODUCT);


// 플랜검색
$SQL_PLAN = "select * from tbl_board_plan where seq=" . $row_r["plan_cd"] . "";
$RS_PLAN = $dbcon->query($SQL_PLAN);
$row_plan = $dbcon->fetch_array($RS_PLAN);

//보험약관 파일 확인
$arr_ins_agree_file1 = selInsAgreeFile($row_plan["ins_term1_seq"]);
$arr_ins_agree_file2 = selInsAgreeFile($row_plan["ins_term2_seq"]);

//인슈플러스 서비스 약관 파일 확인
$arr_service_file = selServiceFile($row_r["service_file_cd"]);

$SQL_COUPON = "select (case WHEN B.partner_coupon IS NULL then A.subject else A.partner_coupon_name end) as subject
					from tbl_board_event A 
					inner join tbl_board_coupon_history B on A.seq=B.event_seq
					where B.orderno = '" . $_SESSION["orderno"] . "'";
$result_coupon = $dbcon->query($SQL_COUPON);
$coupon_name = $dbcon->fetch_array($result_coupon);

$o_name_en = ""; //영문명
if ($row_r["o_name_en"] && $row_r["chk_eng_passport"] == "Y") $o_name_en = all_seed_dec($row_r["o_name_en"]);

?>
<html lang="ko">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
	<title>인슈플러스 - 24시간 해외여행 안심서비스 </title>
	<meta name="description" content="해외여행보험에 안심을 더하다! 현지 병원예약, 24시간 의료상담, 병원비 대신지불, 의료이송까지되는 해외안심 서비스를 인슈플러스에서 알아보세요!">
	<meta name="keywords" content="해외여행자보험, 여행자보험비교, 단기여행자보험, 장기여행자보험, 해외여행자보험추천, 여행자보험가입, 유학생보험, 워킹홀리데이보험, 유학생보험비교, 워홀보험, 장기체류자보험, 장기체류자보험">
	<meta name="author" content="Korea Assistance">
	<meta property="og:type" content="website">
	<meta property="og:title" content="인슈플러스 - 24시간 해외여행 안심서비스 ">
	<meta property="og:description" content="해외여행보험에 안심을 더하다 해외 24시간 의료/여행지원하는 인슈플러스 서비스를 알아보세요! ">
	<meta property="og:site_name" content="인슈플러스 공식홈페이지">
	<meta property="og:url" content="https://www.insuplus.co.kr/html/main/index.php">
	<meta property="og:image" content="https://www.insuplus.co.kr/html/images/insuplus-share-kakao-01.png?v=1">

	<meta name="naver-site-verification" content="53cc3da2cf540aa7813b4720083619c3941c3bcc">
	<meta name="google-site-verification" content="eTr0GHp20XCKxSP0Z12m_Y3fotCyCFU7qslTEAXtkHE">

	<link rel="canonical" href="https://www.insuplus.co.kr/">
	<!-- Favicons -->
	<link rel="shortcut icon" href="../images/favicon/Favicon32.ico">
	<link rel="apple-touch-icon" sizes="57x57" href="../images/favicon/favicon32_57.png">
	<link rel="apple-touch-icon" sizes="72x72" href="../images/favicon/favicon32_72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="../images/favicon/favicon32_114.png">

	<!-- 공통 CSS -->
	<!--		<link href="../_css/main_new.css?v=2306011" rel="stylesheet" type="text/css">-->
	<link href="../_css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="../_css/themify-icons/themify-icons.css" rel="stylesheet">
	<link href="../_css/bootstrap.min.css" rel="stylesheet">
	<link href="../_css/bootstrap-select.min.css" rel="stylesheet">
	<link href="../_css/jquery-ui.css" rel="stylesheet">
	<link href="../_css/animate.css" rel="stylesheet" type="text/css" media="screen">
	<link href="../_css/yamm.css" rel="stylesheet" type="text/css">
	<!--
        <link href="../_css/style.css?v=2309241" rel="stylesheet" type="text/css">
        <link href="../_css/responsive.css?v=2309201" rel="stylesheet" type="text/css">
-->

	<!-- 20230920 header footer fontstyle 추가-->
	<link rel="stylesheet" type="text/css" href="../_css/header.css?v=2309253">
	<link rel="stylesheet" type="text/css" href="../_css/footer.css?v=2309252">
	<link rel="stylesheet" type="text/css" href="../_css/fontstyle.css?v=2309251">
	<!-- 20230920 header footer fontstyle 추가 끝-->

	<!-- 메인 슬라이드-->
	<link rel="stylesheet" type="text/css" href="../_css/rev-settings.css">
	<link rel="stylesheet" type="text/css" href="../_css/rev-style.css">

	<!--owl carousel css-->
	<link href="../_css/owl.carousel.css" rel="stylesheet" type="text/css">
	<link href="../_css/owl.theme.default.css" rel="stylesheet" type="text/css">


	<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="../_css/swiper.min.css">

	<!-- 20230920 신규css-->
	<link rel="stylesheet" type="text/css" href="../_css/base.css?v=2309201">
	<link rel="stylesheet" type="text/css" href="../_css/common.css?a=2">

	<script src="../_js/jquery-1.12.4.js"></script>
	<script src="../_js/jquery-ui.min-1.12.1.js"></script>
	<script src="../_js/swiper.min.js?a=2"></script>
	<script src="../_js/common_main.js?a=1"></script>

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-9HDBMGKBP6"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'G-9HDBMGKBP6');
	</script>
	<!-- Google Tag Manager -->
	<script>
		(function(w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({
				'gtm.start': new Date().getTime(),
				event: 'gtm.js'
			});
			var f = d.getElementsByTagName(s)[0],
				j = d.createElement(s),
				dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
		})(window, document, 'script', 'dataLayer', 'GTM-NLFXCV9');
	</script>
	<!-- End Google Tag Manager -->

	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=AW-715461441"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'AW-715461441');

		gtag('event', 'conversion', {
			'send_to': 'AW-715461441/vdEZCO3PtrIBEMGmlNUC',
			'value': <?= $row_r["t_amount"] ?>,
			'currency': 'KRW',
			'transaction_id': '<?= $_SESSION["orderno"]; ?>'
		});

		//google 전자상거래 설정
		gtag('event', 'purchase', {
			'transaction_id': '<?= $_SESSION["orderno"]; ?>',
			'affiliation': 'INSUPLUS',
			'value': <?= $row_r["t_amount"] ?>,
			'currency': 'KRW',
			'tax': <?= $row_r["service_amount"] ?>,
			'coupon': '<?= $coupon_name[0] ?>',
			'discount': <?= $row_r["s_amount"] ?>,
			'items': [{
				'id': '<?= $row_r["pr_cd"] ?>',
				'name': '<?= $row_r["pr_name"] ?>',
				'quantity': 1,
				'price': <?= $row_r["t_amount"] ?>
			}],
		});
	</script>


	<!-- 카카오픽셀 -->
	<script type="text/javascript" charset="UTF-8" src="//t1.daumcdn.net/adfit/static/kp.js"></script>
	<script type="text/javascript">
		kakaoPixel('7006213406035718583').pageView();
		kakaoPixel('7006213406035718583').purchase({
			total_quantity: "1", // 주문 내 상품 개수(optional)
			total_price: "<?= number_format($row_r["t_amount"]) ?>", // 주문 총 가격(optional)
			currency: "KRW", // 주문 가격의 화폐 단위(optional, 기본 값은 KRW)
			products: [ // 주문 내 상품 정보(optional)
				{
					name: "<?= $row_r["pr_name"] ?>",
					quantity: "1",
					price: "<?= number_format($row_r["t_amount"]) ?>"
				}
			]
		});
	</script>
	<script type="text/javascript" charset="UTF-8" src="//t1.daumcdn.net/kas/static/kp.js"></script>
	<script type="text/javascript">
		kakaoPixel('762577397987588535').pageView();
		kakaoPixel('762577397987588535').purchase({
			total_quantity: "1", // 주문 내 상품 개수(optional)
			total_price: "<?= number_format($row_r["t_amount"]) ?>", // 주문 총 가격(optional)
			currency: "KRW", // 주문 가격의 화폐 단위(optional, 기본 값은 KRW)
			products: [ // 주문 내 상품 정보(optional)
				{
					name: "<?= $row_r["pr_name"] ?>",
					quantity: "1",
					price: "<?= number_format($row_r["t_amount"]) ?>"
				}
			]
		});
	</script>

	<!-- Facebook Pixel Code -->

	<script>
		! function(f, b, e, v, n, t, s) {
			if (f.fbq) return;
			n = f.fbq = function() {
				n.callMethod ?
					n.callMethod.apply(n, arguments) : n.queue.push(arguments)
			};
			if (!f._fbq) f._fbq = n;
			n.push = n;
			n.loaded = !0;
			n.version = '2.0';
			n.queue = [];
			t = b.createElement(e);
			t.async = !0;
			t.src = v;
			s = b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t, s)
		}(window, document, 'script',
			'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '274213513004643');
		fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=274213513004643&ev=PageView&noscript=1" /></noscript>
	<!-- End Facebook Pixel Code -->
	<!-- ADN3.0 Tracker[공통] start -->
	<script src="//fin.rainbownine.net/js/across_adn_3.0.1.js" type="text/javascript"></script>
	<!-- ADN3.0 Tracker[공통] end -->
</head>

<body class='<?php if (strpos($_SERVER['PHP_SELF'], "/main/index")) {
					echo 'index';
				} ?> font-wb'>
	<?php
	include '../_include/_top.html';
	?>
	<section>
		<div class="container">
			<div class="title-box">
				<p>인슈플러스에 가입해 주셔서 감사합니다.<br>고객님의 안심 여행을 위해 함께 하겠습니다.</p>
			</div>
			<div class="white-box middle mt24">
				<form>
					<div class="form-box">
						<div class="form-content">
							<div class="title-box">
								<h3>계약 정보</h3>
							</div>
							<div class="table-form-box">
								<ul>
									<li>
										<div class="table-head w200">
											<strong>가입기간</strong>
										</div>
										<div class="table-body">
											<b class="tl"><?= $row_r["s_date"] ?> <? if ($row_r["s_date_time"]) { ?><?= $row_r["s_date_time"] ?>시<? } ?> ~ <?= $row_r["e_date"] ?> <? if ($row_r["e_date_time"]) { ?><?= $row_r["e_date_time"] ?>시<? } ?><br class="is-m">( <?= $row_r["ins_period"] ?><?= $arr_chk_p_gubun[$row_r["chk_p"]] ?> / <?= $row_r["join_cnt"] ?>명 )</b>
										</div>
									</li>
									<li>
										<div class="table-head w200">
											<strong>출국국가</strong>
										</div>
										<div class="table-body">
											<b class="tl"><?= $row_r['join_nation_name'] ?></b>
										</div>
									</li>
									<li>
										<div class="table-head w200">
											<strong>총가입자수</strong>
										</div>
										<div class="table-body">
											<b class="tl"><?= $row_r["join_cnt"] ?>명</b>
										</div>
									</li>
									<li>
										<div class="table-head w200">
											<strong>결제상태</strong>
										</div>
										<div class="table-body">
											<?
											if (strtolower($row_r["pg_pay_type"]) == "vbank") {
												$type = "가상계좌";
											} else if (strtolower($row_r["pg_pay_type"]) == "card") {
												$type = "신용카드";
											} else if (strtolower($row_r["pg_pay_type"]) == "hpp") {
												$type = "휴대폰 결제";
											} else if (strtolower($row_r["pg_pay_type"]) == "transfer") {
												$type = "실시간계좌이체";
											}

											?>
											<b class="tl"><?= $type ?> / <?= $arr_ord_step[$row_r["order_step"]] ?></b>
										</div>
									</li>
									<li>
										<div class="table-head w200">
											<strong>상품금액</strong>
										</div>
										<div class="table-body">
											<b class="tl ft-blue"><?= number_format($row_r["t_amount"]) ?>원</b>
										</div>
									</li>
								</ul>
							</div>
							<div class="title-box mt24">
								<h3>약관 다운로드</h3>
							</div>
							<div class="table-form-box">
								<ul>
									<li>
										<div class="table-head w200">
											<strong>약관 다운로드</strong>
										</div>
										<div class="table-body">
											<p>
												<? if ($arr_ins_agree_file1) { ?>
													<a onclick="fnDown('<?= all_seed_enc("/_data/board/ins_agree/" . $arr_ins_agree_file1["file_realname"]) ?>','<?= $arr_ins_agree_file1["file_name"] ?>','<?= $arr_ins_agree_file1["file_size"] ?>')">보험약관<i class='ti ti-download pull-right'></i></a>
												<? } ?>
												<? if ($arr_ins_agree_file2) { ?>
													<a onclick="fnDown('<?= all_seed_enc("/_data/board/ins_agree/" . $arr_ins_agree_file2["file_realname"]) ?>','<?= $arr_ins_agree_file2["file_name"] ?>','<?= $arr_ins_agree_file2["file_size"] ?>')">보험약관<i class='ti ti-download pull-right'></i></a>
												<? } ?>

												<? if ($arr_service_file) { ?>
													<a onClick="fnDown('<?= all_seed_enc("/_data/board/service_agree/" . $arr_service_file["file_realname"]) ?>','<?= $arr_service_file["file_name"] ?>','<?= $arr_service_file["file_size"] ?>')">서비스 약관<i class='ti ti-download pull-right'></i></a>
												<? } ?>
											</p>
										</div>
									</li>
								</ul>
							</div>
							<?
							if ($row_r["order_step"] == 2) {
							?>
								<div class="download-box mt12">
									<ul>
										<li><a class='btn btn-lg btn-block btn-default text-left' data-toggle='pop-modal' data-size='lg' data-href='./pop_warranty.php?seq=<?= $row_r["plan_cd"] ?>' data-title='보장내역' target='modal_iframe'>보장항목</a></li>
										<li><a href="javascirpt:void(0)" onClick="openLayer(2)">가입증명서</a></li>
									</ul>
								</div>
							<? } ?>
							<div class="button-box mt42 mt-lg-24">
								<a href="/html/main/index.php" class="btn btn-active">메인으로 가기</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</section>
	<!-- 레이어 팝업 -->

	<div class="dim-bg" style="display: none; opacity: 0;"></div>
	<div class="layer-container" style="display: none; opacity: 0;" data-layer="layer01">
		<div class="layer-box">
			<h3 class="layer-title">보장내역</h3>
			<h4 class="layer-subtitle">플랜명: 유학 그린</h4>
			<div class="title-box mt24">
				<h3>의료비 보장</h3>
			</div>
			<div class="table-wrap">
				<table class="table-type01">
					<colgroup>
						<col width="50%">
						<col width="50%">
					</colgroup>
					<thead>
						<tr>
							<th>보장내역</th>
							<th>보장한도</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>상해 사망</td>
							<td>2만달러</td>
						</tr>
						<tr>
							<td class="type01" colspan="2">해외체류중 급격하고도 우연한 외래의 사고로 상해를 입고 그 결과로써 사망하였을 때 보험가입금액 지급 (15세 미만 미보장)</td>
						</tr>
						<tr>
							<td>상해 후유장해</td>
							<td>2만달러</td>
						</tr>
						<tr>
							<td class="type01" colspan="2">해외체류중 급격하고도 우연한 외래의 사고로 상해를 입어 후유장해가 발생한 경우 장해급수별 가입금액의 3%~100% 지급</td>
						</tr>
						<tr>
							<td>질병사망, 80%후유장해</td>
							<td>2만달러</td>
						</tr>
						<tr>
							<td class="type01" colspan="2">해외체류중에 발생한 질병으로 인하여 보험기간 중 사망 또는 80% 이상에 해당하는 후유장해가 남았을 경우 보험가입금액 지급 (15세 미만 미보장)</td>
						</tr>
						<tr>
							<td>상해 해외의료실비</td>
							<td>1만달러</td>
						</tr>
						<tr>
							<td class="type01" colspan="2">해외체류중에 상해로 인하여 해외의료기관에서 치료를 받은 경우 가입한도 내 피보험자가 실제 부담한 의료비 전액 보상</td>
						</tr>
						<tr>
							<td>질병 해외의료실비</td>
							<td>1만달러</td>
						</tr>
						<tr>
							<td class="type01" colspan="2">해외체류중에 질병으로 인하여 해외의료기관에서 치료를 받은 경우 가입한도 내 피보험자가 실제 부담한 의료비 전액 보상</td>
						</tr>
						<tr>
							<td>중대사고 구조송환비용</td>
							<td>3만달러</td>
						</tr>
						<tr>
							<td class="type01" colspan="2">유학 및 연수 도중 피보험자가 탑승한 항공기나 선박이 조난 당하거나 상해 및 질병으로 인하여 사망 또는 14일 이상 계속 입원한 경우 발생하는 수색구조비용, 구원자(법정상속인)의 교통비, 숙박비, 유체이송비 및 기타 제잡비를 약관이 정한 바에 따라 보상</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="title-box mt24">
				<h3>의료지원 서비스</h3>
			</div>
			<div class="table-wrap">
				<table class="table-type01">
					<colgroup>
						<col>
						<col width="35%">
					</colgroup>
					<thead>
						<tr>
							<th>보장내역</th>
							<th>보장한도</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>해외병원비 대신지불</td>
							<td>-</td>
						</tr>
						<tr>
							<td>현지 병원예약</td>
							<td>-</td>
						</tr>
						<tr>
							<td>원격화상진료</td>
							<td>제공</td>
						</tr>
						<tr>
							<td>24시간 간호사 상담</td>
							<td>제공</td>
						</tr>
						<tr>
							<td>긴급의료 전화통역</td>
							<td>제공</td>
						</tr>
						<tr>
							<td>긴급의료 본국이송 (에어앰뷸런스)</td>
							<td>-</td>
						</tr>
						<tr>
							<td>긴급의료 본국이송 (일반항공)</td>
							<td>-</td>
						</tr>
						<tr>
							<td>국가내 의료이송</td>
							<td>-</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="title-box mt24">
				<h3>해지환급금</h3>
			</div>
			<p class="common-txt01">가입 후 중도해지할 경우 미경과 상품가격를 해지환급금으로 지급해 드립니다.</p>
			<button type="button" class="btn-close" data-layer-btn="layer01" onClick="closeLayer(1)"><span class="tts">팝업 닫기</span></button>
		</div>
	</div>

	<div class="layer-container" style="display: none; opacity: 0;" data-layer="layer02">
		<div class="layer-box">
			<h3 class="layer-title">가입증명서 다운로드</h3>

			<form name="frm_pdf" method="post">
				<input type="hidden" name="orderno" value="<?= $_SESSION["orderno"] ?>">
				<input type="hidden" name="join_seq" value="<?= $row_r["join_seq"] ?>">
				<input type="hidden" name="mode" value="" />
				<div class="table-form-box">
					<ul>
						<li>
							<div class="table-head w200">
								<strong>언어선택</strong>
							</div>
							<div class="table-body">
								<div class="flex flex-vc">
									<div class="check-box mr50 mr-lg-25" data-group="check01">
										<div class="check-box-inner">
											<input type="radio" name='chk_lang' id='stock_kr' value="K" checked />
											<label for="radio01_01" class="fw4">국문</label>
										</div>
									</div>
									<div class="check-box" data-group="check01">
										<div class="check-box-inner">
											<input type="radio" name='chk_lang' id='stock_en' value="E" />
											<label for="radio01_02" class="fw4">영문</label>
										</div>
									</div>
								</div>
							</div>
						</li>
						<li>
							<div class="table-head w200">
								<strong>구분</strong>
							</div>
							<div class="table-body">
								<div class="flex flex-vc">
									<div class="check-box mr50 mr-lg-25" data-group="check01">
										<div class="check-box-inner">
											<input type="radio" name='certType' id='type_all' value="A" checked />
											<label for="radio02_01" class="fw4">전체</label>
										</div>
									</div>
									<div class="check-box" data-group="check01">
										<div class="check-box-inner">
											<input type="radio" name='certType' id='type_no_service' value="I" />
											<label for="radio02_02" class="fw4">보험</label>
										</div>
									</div>
								</div>
							</div>
						</li>
						<li>
							<div class="table-head w200">
								<strong>이름</strong>
							</div>
							<div class="table-body">
								<b class="tl"><?= all_seed_dec($row_r["o_name"]) ?></b>
							</div>
						</li>
						<li>
							<div class="table-head w200">
								<strong>영문명</strong>
							</div>
							<div class="table-body">
								<div class="input-box type01 flex-1">
									<div class="input-box-inner">
										<? if ($o_name_en) { ?>
											<input type="text" name='o_name_en' onKeyup="fnChkEng(this)" placeholder="영문명을 입력해 주세요." size='20' value="<?= $o_name_en ?>" />
										<? } else { ?>
											<input type="text" name='o_name_en' onKeyup="fnChkEng(this)" placeholder="영문명을 입력해 주세요." size='20' value="" />
										<? } ?>
									</div>
								</div>
							</div>
						</li>
					</ul>
				</div>
			</form>
			<div class="button-box mt24 flex-tc">
				<a href='javascript: chk_submit();' class="btn btn-active btn-down">다운로드</a>
			</div>
			<button type="button" class="btn-close" data-layer-btn="layer02" onClick="closeLayer(2)"><span class="tts">팝업 닫기</span></button>
		</div>
	</div>
	<form name="downForm" id="downForm" method="post">
		<input type="hidden" name="mode" value="down" />
		<input type="hidden" name="file" value="" />
		<input type="hidden" name="filename" value="" />
		<input type="hidden" name="filesize" value="" />
	</form>
	<!-- // 레이어 팝업 -->
	<script src="./js/swiper.js?a=1"></script>
	<script>
		function fnDown(file, filename, file_size) {
			$("#downForm input[name='file']").val(file);
			$("#downForm input[name='filename']").val(filename);
			$("#downForm input[name='filesize']").val(file_size);
			$("#downForm").attr("action", "fileDown.php").submit();
		}

		function chk_submit() {
			$("input[name='mode']").val('down');
			var ff = document.frm_pdf;
			if (ff.chk_lang[1].checked == true && ff.o_name_en.value == "") {
				alert("영문이름을 넣어주세요");
				return;
			}
			<? if ($detect->isMobile()) { ?>
				var pop_title = "popupOpener";

				window.open("", pop_title, "width=100,height=100");
				ff.target = pop_title;
			<? } ?>


        


			 ff.action = "//<?= $_SERVER["HTTP_HOST"] ?>/admin/mn1/popup_certificate_pdf_renewal.php";
			ff.submit();

		}

		function fnChkEng(t) {
			$(t).val($(t).val().replace(/[0-9]|[^\!-z\s]/g, ""));
		}
	</script>
	<?php
	include '../_include/_tail.html';
	include '../_include/_footer.html';
	?>

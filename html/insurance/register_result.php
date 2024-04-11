
<!DOCTYPE html>
<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php"; //추가

	if(!$_SESSION["orderno"]){
		echo "<script>alert('잘못된 경로로 들어오셨습니다.');location.href='/html/main/index.php';</script>";
		exit;
	}
	//결제정보
	$SQL_R  = " SELECT o.*, j.o_phone, j.o_name FROM ";
	$SQL_R .= " tbl_order_list o INNER JOIN tbl_order_list_join j ON o.orderno = j.orderno WHERE o.orderno='".$_SESSION["orderno"]."' AND j.chk_join = 'N' ";
	
	$result_r = $dbcon -> query($SQL_R);
	$row_r = $dbcon -> fetch_array($result_r);
	
	$PR_INFO = getInsuProductInfo($row_r["pr_cd"]); //상품정보
	
	//보험약관 파일 확인
	$arr_ins_agree_file = selInsAgreeFile($row_r["ins_file_cd"]);
	
	//인슈플러스 서비스 약관 파일 확인
	$arr_service_file = selServiceFile($row_r["service_file_cd"]);

	$SQL_COUPON = "select (case WHEN B.partner_coupon IS NULL then A.subject else A.partner_coupon_name end) as subject
					from tbl_board_event A 
					inner join tbl_board_coupon_history B on A.seq=B.event_seq
					where B.orderno = '".$_SESSION["orderno"]."'";
	$result_coupon = $dbcon -> query($SQL_COUPON);
	$coupon_name = $dbcon -> fetch_array($result_coupon);
?>
<html lang="ko">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, height=device-height, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui">
        <title>인슈플러스</title>
        <meta name="description" content="해외갈 때 드는 보험, 보험 가격비교, 24시간 의료 지원 서비스 제공"/>
		<meta name="keywords" content="유학생보험, 워킹홀리데이보험, 유학생보험비교, 워홀보험, 장기체류자보험, 단기여행자보험, 여행자보험비교, 해외여행여행자보험, 단기여행자보험가격비교"/>
		<meta name="author" content="Korea Assistance"/>

		<meta property="og:type" content="website">
		<meta property="og:title" content="인슈플러스 " /> 
		<meta property="og:description" content="해외갈 때 드는 보험, 보험 가격비교, 24시간 의료 지원 서비스 제공" /> 
		<meta property="og:site_name" content="인슈플러스">
		<meta property="og:url" content="https://www.insuplus.co.kr/html/main/index.php" />
		<meta property="og:image" content="https://www.insuplus.co.kr/html/images/insuplus-share-kakao-01.png" />
		
		<meta name="naver-site-verification" content="53cc3da2cf540aa7813b4720083619c3941c3bcc"/>
		<meta name="google-site-verification" content="eTr0GHp20XCKxSP0Z12m_Y3fotCyCFU7qslTEAXtkHE" />

		<link rel="canonical" href="https://www.insuplus.co.kr/">
		<!-- Favicons -->
		<link rel="shortcut icon" href="../images/favicon/Favicon32.ico">
		<link rel="apple-touch-icon" sizes="57x57" href="../images/favicon/favicon32_57.png">
		<link rel="apple-touch-icon" sizes="72x72" href="../images/favicon/favicon32_72.png">
		<link rel="apple-touch-icon" sizes="114x114" href="../images/favicon/favicon32_114.png">
		<!-- 공통 CSS -->
		<link href="../_css/main_new.css?v=230503" rel="stylesheet" type="text/css">
        <link href="../_css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="../_css/themify-icons/themify-icons.css" rel="stylesheet">
        <link href="../_css/bootstrap.min.css" rel="stylesheet">
        <link href="../_css/bootstrap-select.min.css" rel="stylesheet">
        <link href="../_css/jquery-ui.css" rel="stylesheet">
        <link href="../_css/animate.css" rel="stylesheet" type="text/css" media="screen"> 
        <link href="../_css/yamm.css" rel="stylesheet" type="text/css">
        <link href="../_css/style.css?v=2305032" rel="stylesheet" type="text/css">
        <link href="../_css/responsive.css?v=230503" rel="stylesheet" type="text/css">
        <!-- 메인 슬라이드
        <link rel="stylesheet" type="text/css" href="../_css/rev-settings.css">
        <link rel="stylesheet" type="text/css" href="../_css/rev-style.css">
        -->
        <!--owl carousel css-->
        <link href="../_css/owl.carousel.css" rel="stylesheet" type="text/css">
        <link href="../_css/owl.theme.default.css" rel="stylesheet" type="text/css">
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="./_js/html5.min.js"></script>
          <script src="./_js/respond.min.js"></script>
        <![endif]-->

		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-147072882-1"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			
			gtag('config', 'UA-147072882-1');
			</script>		
			<!-- Google tag (gtag.js) -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=G-9HDBMGKBP6"></script>
			<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', 'G-9HDBMGKBP6');
		</script>
		<!-- Google Tag Manager -->
		<script>
			(function(w,d,s,l,i){
				w[l]=w[l]||[];w[l].push(
				{
					'gtm.start': new Date().getTime(),
					event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
					j=d.createElement(s),
					dl=l!='dataLayer'?'&l='+l:'';
					j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
					f.parentNode.insertBefore(j,f);
				}
			)(window,document,'script','dataLayer','GTM-NLFXCV9');
		</script>
		<!-- End Google Tag Manager -->

        <!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=AW-715461441"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());
			
			gtag('config', 'AW-715461441');
			
			gtag('event', 'conversion', { 
				'send_to': 'AW-715461441/vdEZCO3PtrIBEMGmlNUC',
				'value': <?=number_format($row_r["t_amount"])?>,
				'currency': 'KRW',
				'transaction_id': '<?= $_SESSION["orderno"]; ?>' 
				}
			);

			//google 전자상거래 설정
			gtag('event', 'purchase', {
				'transaction_id': '<?= $_SESSION["orderno"]; ?>',
				'affiliation': 'INSUPLUS',
				'value': <?=number_format($row_r["t_amount"])?>,
				'currency': 'KRW',
				'tax': <?= $row_r["service_amount"] ?>,
				'coupon': '<?= $coupon_name[0] ?>',
				'discount': <?= $row_r["s_amount"] ?>,
				'items': [
					{
						'id': '<?= $row_r["pr_cd"] ?>',
						'name': '<?= $row_r["pr_name"] ?>',
						'quantity': 1,
						'price': '<?=number_format($row_r["t_amount"])?>'
					}
				],
			});
		</script>
		
		
		<!-- 카카오픽셀 -->
		<script type="text/javascript" charset="UTF-8" src="//t1.daumcdn.net/adfit/static/kp.js"></script>
		<script type="text/javascript">
			kakaoPixel('7006213406035718583').pageView();
			kakaoPixel('7006213406035718583').purchase({
				total_quantity: "1", // 주문 내 상품 개수(optional)
				total_price: "<?=number_format($row_r["t_amount"])?>",  // 주문 총 가격(optional)
				currency: "KRW",     // 주문 가격의 화폐 단위(optional, 기본 값은 KRW)
				products: [          // 주문 내 상품 정보(optional)
					{ name: "<?= $row_r["pr_name"] ?>", quantity: "1", price: "<?=number_format($row_r["t_amount"])?>"}
				]
			});
		</script>
		<script type="text/javascript" charset="UTF-8" src="//t1.daumcdn.net/kas/static/kp.js"></script>
		<script type="text/javascript">
			kakaoPixel('762577397987588535').pageView();
			kakaoPixel('762577397987588535').purchase({
				total_quantity: "1", // 주문 내 상품 개수(optional)
				total_price: "<?=number_format($row_r["t_amount"])?>",  // 주문 총 가격(optional)
				currency: "KRW",     // 주문 가격의 화폐 단위(optional, 기본 값은 KRW)
				products: [          // 주문 내 상품 정보(optional)
					{ name: "<?= $row_r["pr_name"] ?>", quantity: "1", price: "<?=number_format($row_r["t_amount"])?>"}
				]
			});
		</script>
		
		<!-- Facebook Pixel Code -->

		<script>
		  !function(f,b,e,v,n,t,s)
		  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
		  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
		  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
		  n.queue=[];t=b.createElement(e);t.async=!0;
		  t.src=v;s=b.getElementsByTagName(e)[0];
		  s.parentNode.insertBefore(t,s)}(window, document,'script',
		  'https://connect.facebook.net/en_US/fbevents.js');
		  fbq('init', '274213513004643');
		  fbq('track', 'PageView');
		</script>
		<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=274213513004643&ev=PageView&noscript=1"/></noscript>
		<!-- End Facebook Pixel Code -->
    </head>
	<body class='<?php if(strpos($_SERVER['PHP_SELF'], "/main/index")){echo 'index';}?>'>
		<!-- Naver 전환페이지 설정 -->
		<script type="text/javascript" src="//wcs.naver.net/wcslog.js"></script> 
		<script type="text/javascript"> 
			var _nasa={};
			_nasa["cnv"] = wcs.cnv("1","<?=$row_r["t_amount"]?>"); // 전환유형, 전환가치 설정해야함. 설치매뉴얼 참고
		</script> 
<?php
	include '../_include/_top.html';
	include '../_include/_sidebar.html';
?>

		<div class="breadcrumb-image" style="background-image:url('<?=$PR_INFO["imgfile"]?>')">
			<div class="container">
				<h2><?=$PR_INFO["subject"]?></h2>
				<h4><?=$PR_INFO["content"]?></h4>
			</div>
		</div>
		<div class="breadcrumb-wrap">
            <div class="container">
				<ol class="breadcrumb">
					<li><a href="../main/index.php">InsuPlus HOME</a></li>
					<li><?=$PR_INFO["subject"]?></li>
				</ol>
            </div>
        </div>
		<div class="container">
			<div class="sub-content">
				<div class="text-center m-t-4">
					<img src="../images/sub-register-result-banner.jpg" srcset="../images/sub-register-result-banner@2x.jpg 2x, ../images/sub-register-result-banner@3x.jpg 3x" />
				</div>
				<div class='row'>
					<div class='col-xs-12 panel-result clearfix'>
						<div class='panel panel-body'>
							<h5 class='text-black m-b-1'>가입정보</h5>
							<div class='clearfix'>
								<div class='row-border row-sm'>
									<div class='detail-col-label'>가입 기간</div>
									<div class='detail-col-input'><?=$row_r["s_date"]?> <?if($row_r["s_date_time"]){?><?=$row_r["s_date_time"]?>시<?}?> ~ <?=$row_r["e_date"]?> <?if($row_r["e_date_time"]){?><?=$row_r["e_date_time"]?>시<?}?> (<?=$row_r["ins_period"]?><?=$arr_chk_p_gubun[$row_r["chk_p"]]?>)</div>
									<div class='detail-col-label'>플랜명</div>
									<div class='detail-col-input'><?=$row_r["plan_name"]?> <?=$row_r["pr_name"]?></div>
									<div class='detail-col-label'>가입자</div>
									<div class='detail-col-input'><?=all_seed_dec($row_r["o_name"])?></div>
									<div class='detail-col-label'>총 가입자 수</div>
									<div class='detail-col-input'><?=$row_r["join_cnt"]?>명</div>
									<div class='detail-col-label'>결제상태</div>
									<div class='detail-col-input'><?=$arr_ord_step[$row_r["order_step"]]?></div>
									<? if(strtolower($row_r["pg_pay_type"]) == "vbank") {
										$arr_pay_name = explode("/",$row_r["pay_name"]);
									?>
									<div class='detail-col-label'>가상계좌</div>
									<div class='detail-col-input'><?=$arr_pay_name[1]?>:<?=$arr_pay_name[2]?></div>
									<? } ?>
									<div class='detail-col-label'>결제금액</div>
									<div class='detail-col-input text-right'><h3 class='pull-right'><span class='text-danger'><?=number_format($row_r["t_amount"])?></span> <small>원</small></h3></div>
								</div>
							</div>
							
							<div class='row m-y-05'>
							<div class='col-xs-12 col-xs-offset-0'>
								<div class='btn-group btn-group-justified btn-group-noborder'>
									<span class='btn-group'><a class='btn btn-lg btn-block btn-default text-left' data-toggle='pop-modal' data-size='lg' data-href='./pop_warranty.php?seq=<?=$row_r["plan_cd"]?>' data-title='보장내역' target='modal_iframe'>보장내역<i class='ti ti-search pull-right'></i></a></span>
									<span class='btn-group'><a class='btn btn-lg btn-block btn-default text-left' onClick="fnDown('<?=all_seed_enc("/_data/board/ins_agree/".$arr_ins_agree_file["file_realname"])?>','<?=$arr_ins_agree_file["file_name"]?>','<?=$arr_ins_agree_file["file_size"]?>')">보험약관<i class='ti ti-download pull-right'></i></a></span>
										<? if($arr_service_file["file_realname"]) {?>
									<span class='btn-group'><a class='btn btn-lg btn-block btn-default text-left' onClick="fnDown('<?=all_seed_enc("/_data/board/service_agree/".$arr_service_file["file_realname"])?>','<?=$arr_service_file["file_name"]?>','<?=$arr_service_file["file_size"]?>')">서비스 이용약관<i class='ti ti-download pull-right'></i></a></span>
									<? } ?>
								</div>
							</div>
							</div>
							
							<div class='row m-t-3' id='coupon' style="display: none;">
							</div>
							<div class='row'>
								<div class='col-xs-12'>
									<h5 class='text-black m-t-2 m-b-05'><img src="../images/ic-noti.svg" align="absmiddle" alt="" height="24" />&nbsp;가입증명서</h5>
								</div>
								<div class='col-md-7 m-y-05'>
									가입증명서(국문/영문)는 가입조회 페이지에서 다운로드 하실 수 있습니다.
								</div>
								<div class='col-md-5'>
									<a href='../join/join_confirm.php' class='btn btn-md btn-default p-x-2'>가입조회 & 증명서발급<i class='ti ti-angle-right m-l-1'></i></a>
								</div>
							</div>
						</div>
						<div class='row m-y-3'>
							<div class='col-sm-6 col-sm-offset-3'>
								<a href='../main/index.php' class='btn btn-lg btn-block btn-theme-dark'>메인으로 가기</a>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
        <form name="downForm" id="downForm" method="post">
        	<input type="hidden" name="mode" value="down" />
        	<input type="hidden" name="file" value="" />
        	<input type="hidden" name="filename" value="" />
        	<input type="hidden" name="filesize" value="" />
        </form>

<?php
	include '../_include/_tail.html';
	include '../_include/_footer.html';
?>
<script>
function fnDown(file, filename, file_size) {
	$("#downForm input[name='file']").val(file);
	$("#downForm input[name='filename']").val(filename);
	$("#downForm input[name='filesize']").val(file_size);
	$("#downForm").attr("action","fileDown.php").submit();
}

function fnSendCoupon() { //친구 쿠폰 발송
	if(!$("input[name='mobile']").val()) {
		alert("휴대폰번호를 입력해 주세요.");
		$("input[name='mobile']").focus();
		return;
	}

	$.ajax({
			url:"register_result_sendCoupon_ajax.php"
			,data:{"mode":"send","mobile":$("input[name='mobile']").val(),"ori_mobile":"<?=all_seed_dec($row_r["o_phone"])?>"
				,"name":"<?=all_seed_dec($row_r["o_name"])?>"
			}
			,type:"POST"
			,dataType:"json"
			,success:function(d){
				if(d.result == "1") {
					alert(d.msg);
				} else {
					alert(d.msg);
				}
			},error:function(){
				alert("전송 실패했습니다. 관리자에게 문의해 주세요.");
			}	
		})
}
</script>


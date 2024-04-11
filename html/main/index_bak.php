<?php
include '../_include/_header.html';
include '../_include/_top.html';
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/Func.main.php";

$rs_banner = getMainBanner(); //메인 배너
$rs_recommend = getRecommendPlan();
$rs_nurseCounsel = getNurseCounsel(); //간호사 상담
//$rs_rewardService = getRewardService(); //보상사례
$rs_qnaService = getQnaService(); //문의사항
$rs_join = getJoinList();
$rs_notice = getNotiList();

$today = date("Y-m-d");
$_SESSION["orderno"] = "";

?>
<link href="../_css/main.css?v=230601" rel="stylesheet" type="text/css">
<!--rev slider start-->
<div class="fullwidthbanner">
	<div class="tp-banner owl-carousel owl-theme">
		<!-- SLIDE -->
		<?
		foreach ($rs_banner as $row) {
			$arr_img_info = setFileName($row["imgfile"])[0];
		?>
			<div class='item' style='background-image:url(/_data/board/main_banner/<?= $arr_img_info[1]; ?>);'>
				<? if ($row["pc_url"]) { ?>
					<a href="<?= $row["pc_url"] ?>"><img src="/_data/board/main_banner/<?= $arr_img_info[1]; ?>"></a>
				<? } else { ?>
					<img src="/_data/board/main_banner/<?= $arr_img_info[1]; ?>">
				<? } ?>
			</div>
		<? } ?>
		<!-- SLIDE -->
	</div>
	<div class="tp-banner-mobile owl-carousel owl-theme">
		<!-- SLIDE -->
		<?
		foreach ($rs_banner as $row) {
			$arr_m_img_info = setFileName($row["imgfile2"])[0];
		?>

			<div class='item' style='background-image:url(/_data/board/main_banner/<?= $arr_m_img_info[1]; ?>);'>
				<? if ($row["pc_url"]) { ?>
					<a href="<?= $row["pc_url"] ?>"><img src="/_data/board/main_banner/<?= $arr_m_img_info[1]; ?>"></a>
				<? } else { ?>
					<img src="/_data/board/main_banner/<?= $arr_m_img_info[1]; ?>">
				<? } ?>
			</div>
		<? } ?>
		<!-- SLIDE -->
		</ul>
	</div>
</div>
<!--revolution end-->
<div class="container">
	<section id="main_cscenter">
		<div>
			<div class="icon"><img src="../images/main_cscenter_icon-01.svg" align="absmiddle"></div>
			<div class="clearfix">
				<h3>24시간 운영센터</h3>
				<h4>해외에서 아프면 전화하세요!</h4>
			</div>
		</div>
		<div>
			<a class="call" href="tel:<?= str_replace(".", "", $insuplus_phone) ?>"><?= $insuplus_phone; ?></a>
			<a class="kakaoplus" href="https://pf.kakao.com/_JClxfT" target="_blank"><img src="../images/footer-logo3.svg" align="absmiddle" alt="카카오 플러스 친구">카카오 문의하기</a>
		</div>
	</section>
</div>
<!--멤버십 가입-->	
<div class="button_joinbox">
	<button class="button_join" onclick="move_page('/html/insurance/search_insur.php?PR_SEQ=113');">
		<img src="../images/icon_search_bar.svg" />
		<span class="tshadow">간편 가격 조회하기</span>
	</button>
</div>
<section id="service01" class="bg-white"><!--20230502 div-&gt;section으로 수정-->
	<!--해외 여행자 보험-->	
	<div class="bubble_down">
		<div class="con_title space_marbottm-40">여행기간과 목적에 따라 선택해주세요.</div> <!--20230528 class추가 space_marbottm-40 -->
		<div class="con_item_lf">
			<div class="item_img" onclick="move_page('/html/insurance/pr_plans.php?PR_SEQ=74');" style="cursor: pointer;">
				<img src="../images/thumb_type1_01.png" align="absmiddle" class="item_img01">
			</div>
			<div class="item_img" onclick="move_page('/html/insurance/pr_plans.php?PR_SEQ=66');" style="cursor: pointer;">
				<img src="../images/thumb_type1_02.png" align="absmiddle">
			</div>
			<div class="item_img" onclick="move_page('/html/insurance/pr_plans.php?PR_SEQ=68');" style="cursor: pointer;">
				<img src="../images/thumb_type1_03.png" align="absmiddle">
			</div>
			<div class="item_img" onclick="move_page('/html/insurance/pr_plans.php?PR_SEQ=93');" style="cursor: pointer;">
				<img src="../images/thumb_type1_04.png" align="absmiddle">
			</div>
		</div>
	</div>
	<div class="img_plus">
		<img src="../images/img_plus.svg">
	</div>
	<!--해외의료지원 서비스-->	
	<div class="bubble_up">
		<div class="con_title">인슈플러스 고객만을 위한<br>24시간 여행/해외의료지원 서비스!
		</div>
		<div class="con_subtitle">여행자 보험에서 제공되지 않은 24시간 의료지원 서비스를 제공해 드려요.
		</div>
		<div class="con_item_lf">
			<div class="item_img">
				<a href="#s01"><img src="../images/thumb_type2_01.png" align="absmiddle"></a>
			</div>
			<div class="item_img">
				<a href="#s02"><img src="../images/thumb_type2_02.png" align="absmiddle"></a>
			</div>
			<div class="item_img">
				<a href="#s03"><img src="../images/thumb_type2_03.png" align="absmiddle"></a>
			</div>
			<div class="item_img">
				<a href="#s04"><img src="../images/thumb_type2_04.png" align="absmiddle"></a>
			</div>
		</div>
	</div>	
</section>
<!--서비스1-->	
<section id="service01" class="bg-white">
	<div class="container dp-if">
		<div class="content_l" id="s01">
			<div class="con_copy">
				<div class="con_flag">서비스1</div> 
				<!--20230528 id위치 변경 <div class="container dp-if" id="s01">에서<div class="con_flag" id="s01">으로 -->
				<div class="con_title_l">병원예약 및 병원비 대신 지불</div>
			</div>
			<div class="con_item">
				<div class="box_square_long sbshadow">
					<div class="bsl_img">
						<img src="../images/icon_s01_01.svg" align="absmiddle">
					</div>
					<div class="bsl_copy">
						<div class="title">현지병원예약</div>
						<div class="sub">
							증상에 맞는 현지병원예약을 도와드려요.<br>전세계 3,000개의 의료 네트워크 보유
						</div>
					</div>
				</div>
				<div class="box_square_long sbshadow">
					<div class="bsl_img">
						<img src="../images/icon_s01_02.svg" align="absmiddle">
					</div>	
					<div class="bsl_copy">
						<div class="title">병원비 대신 지불</div>
						<div class="sub">
							생소한 해외 병원시스템, 당황하지 마시고 진료만 받고 가세요.<br>인슈플러스가 병원비를 대신 내드립니다.
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="content_r ser01img">
			<img src="../images/img_service01.png" align="absmiddle">
		</div>
	</div>
	<div id="s02"></div> <!--20230531추가 앵커위치 변경-->
</section>
<!--서비스2-->	
<section id="service02" class="bg-white">
	<div class="container servicearea">
		<div class="con_flag">서비스2</div>
		<!--20230528 id위치 변경 <div class="container servicearea" id="s02">에서<div class="con_flag" id="s02">서비스2</div>으로 -->
		<div class="con_title_l">24시간 의료상담 및 원격진료</div>
		<div class="con_subtitle_l">아플 때 언제 어디서든 한국인 의료진에게 상담 받으세요.</div>
		<div class="con_item_lf">
			<div class="box_square bgb sbshadow">
				<div class="imgbox shadow">
					<img src="../images/icon_s02_01.svg" align="absmiddle">
				</div>	
				<div class="title">원격진료</div>
				<div class="sub">휴대폰으로 간편하게<br>원격진료를 제공해 드립니다.<br>(미국병원 온라인 처방전 발행)</div>
			</div>
			<div class="box_square bgb sbshadow">
				<div class="imgbox shadow">
					<img src="../images/icon_s02_02.svg" align="absmiddle">
				</div>	
				<div class="title">12개과목 전문의</div>
				<div class="sub">12개과목<br>한국인 전문의가<br>상담해 드립니다.</div>
			</div>
			<div class="box_square bgb sbshadow">
				<div class="imgbox shadow">
					<img src="../images/icon_s02_03.svg" align="absmiddle">
				</div>	
				<div class="title">24시간 응급상담</div>
				<div class="sub">24시간 365일<br>응급의학과<br>전문의가 대기합니다.</div>
			</div>
			<div class="box_square bgb sbshadow">
				<div class="imgbox shadow">
					<img src="../images/icon_s02_04.svg" align="absmiddle">
				</div>	
				<div class="title">의료통역 지원</div>
				<div class="sub">현지병원 진료시<br>의료통역 서비스를<br>제공해 드립니다.</div>
			</div>
		</div>
	</div>
	<div id="s03"></div> <!--20230531추가 앵커위치 변경-->
</section>

<!--서비스3-->	
<section id="service03" class="bg-white">
	<div class="container servicearea">
		<div class="con_flag">서비스3</div>
		<!--20230528 id위치 변경 <div class="container servicearea" id="s03">에서<div class="con_flag" id="s03">서비스3</div>으로 -->
		<div class="con_title_l">에어엠뷸런스 긴급이송</div>
		<div class="con_subtitle_l">긴급 상황 발생시, 국내 의료진이 에어앰뷸런스로 한국까지 안전하게 이송해 드립니다.</div>
		<div class="con_item_lf">
			<div class="content_limg">
				<img src="../images/photo01.png" align="absmiddle">
			</div>
			<div class="content_rimg">
				<h3>에어앰뷸런스 비용 최대 2억원 보장</h3>
				<div class="sub">
					국내 유일 해외환자이송용 제트기 보유<br>
					국내 유일 환자이송 항공팀 보유<br>
					한국인 응급의학과 전문의가 직접 이송<br>
					국내 최다 이송 경험 보유
				</div>
				<div class="add">
					* 해외환자 이송전문 플라잉닥터스 운영
				</div>
				<div class="photo">
					<img src="../images/logo_fd.png" align="absmiddle">
				</div>
				<div class="detail" onclick="open_new_page('http://flyingdoctors.co.kr')" style="cursor:pointer;">
					자세히 보기
				</div>
			</div>
		</div>
	</div>
	<div id="s04"></div> <!--20230531추가 앵커위치 변경-->
</section>

<!--서비스4-->	
<section id="service04" class="bg-white">
	<div class="container servicearea">
		<div class="con_flag">서비스4</div>
		<!--20230528 id위치 변경 <div class="container servicearea" id="s04">에서<div class="con_flag" id="s04">서비스4</div>으로 -->
		<div class="con_title_l">해외 여행지원 서비스</div>
		<div class="con_subtitle_l">지갑 분실, 수화물 지연 등 여행 불편 사항이 생길 시 해결해 드립니다.</div>
		<div class="con_item_lf">
			<div class="box_square bgw sbshadow">
				<div class="imgbox">
					<img src="../images/icon_s04_01.svg" align="absmiddle">
				</div>	
				<div class="title_dk">수하물 추적</div>
				<div class="sub_dk">수하물이 도착하지 않은 경우,위치를 추적해 드립니다.</div>
			</div>
			<div class="box_square bgw sbshadow">
				<div class="imgbox">
					<img src="../images/icon_s04_02.svg" align="absmiddle">
				</div>	
				<div class="title_dk">해외 긴급송금 지원</div>
				<div class="sub_dk">지갑을 분실 했을 때 인슈플러스에서 해외로 긴급 송금을 해드립니다.</div>
			</div>
			<div class="box_square bgw sbshadow">
				<div class="imgbox">
					<img src="../images/icon_s04_03.svg" align="absmiddle">
				</div>	
				<div class="title_dk">여행정보 안내</div>
				<div class="sub_dk">예방접종, 비자 등 현지 여행 정보를 제공해 드립니다.</div>
			</div>
			<div class="box_square bgw sbshadow">
				<div class="imgbox">
					<img src="../images/icon_s04_04.svg" align="absmiddle">
				</div>	
				<div class="title_dk">법률 지원</div>
				<div class="sub_dk">여행 중 사고가 발생한 경우, 변호사를 알선해 드립니다.</div>
			</div>
		</div>
	</div>
</section>
<!-- 게시판-사례 -->	
<section id="board01" class="bg-white">
	<div class="container servicearea">
		<div class="con_subtitle_w">인슈플러스와 함께한 고객님의</div>
		<div class="con_title_w">의료지원 서비스 후기</div>
		<div class="con_item_lf" >
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
			<div class="box_review" onclick="view_go_board('<?= $row["seq"] ?>','counsel_case', 'counsel_case_list');" style="cursor:pointer;">
				<div class="photobox">
					<img src="/_data/board/counsel_case/<?= $Result[0][1] ?>" align="absmiddle">
				</div>	
				<div class="title"><?= $row["subject"] ?></div>
			</div>
			<? } ?>
		</div>
	</div>
	<div class="button_more" onclick="move_page('../customer/counsel_case_list.php');">
		더보기
	</div>
</section>
<!-- 게시판-공지사항/문의사항 -->	
<section id="board02" class="bg-white">
	<div class="container boardarea">
		<div class="boardbox">
			<dl>
				<dt>
					<h3>공지사항</h3>
				</dt>
				<dt class="more">
					<a href="../customer/notice_list.php">
						<img src="../images/icon_more.svg">
					</a>
				</dt>
			</dl>
			<? foreach ($rs_notice as $row) { ?>
				<dl class='list'>
					<dd><a href="javascript:view_go_board('<?= $row["seq"] ?>', 'notice', 'notice_list');"><?= $row["subject"] ?></a></dd>
					<dd><?= $row["regdate"] ?></dd>
				</dl>
			<? } ?>
		</div>
		<div class="boardbox">
			<dl>
				<dt>
					<h3>문의사항</h3>
				</dt>
				<dt class="more">
					<a href="../customer/qa_list.php">
						<img src="../images/icon_more.svg">
					</a>
				</dt>
			</dl>
			<? foreach ($rs_qnaService as $row) { ?>
				<dl class='list'>
					<dd><a href="../customer/qa_list.php"><?= $row["subject"] ?></a></dd>
					<dd><?= $row["regdate"]; ?></dd>
				</dl>
			<? } ?>
		</div>
	</div>
</section>
<?php
include '../_include/_tail.html';
include '../_include/_footer.html';
?>
<script src="../_js/jquery.marquee.js?" type="text/javascript"></script>
<script src="../_js/custom_main.js?ver=230530" type="text/javascript"></script>
<script>
	$(document).ready(function() { //임시팝업 20211125
		//$('#testModal').modal("show");
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

	function close() {
		$('#testModal').modal("hide");
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
	$()
</script>
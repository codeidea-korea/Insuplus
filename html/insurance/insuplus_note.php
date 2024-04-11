<?php
	include '../_include/_header.html';
	include '../_include/_top.html';
	include '../_include/_sidebar.html';

	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php";

	// 상품 검색
	$PR_INFO = getInsuProductInfo($PR_SEQ);

	//상품에 따른 보험사 출력
	$arr_ins_list = getNoteInsOfPr_cd($PR_SEQ);

	//인슈서비스 정보 출력
	$row_service_agree = getNoteServiceAgreeOfPlan($PR_SEQ,$PR_INFO["ext1"]);
?>
		<div class="breadcrumb-image">
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
			<div class="sub-content info-wrap">				
				<!--20230528 문구변경 start-->
				<div class="text-center plan">
					<div class="plan_title half-highlight">플랜 비교하는 방법</div>
					<div class="plan_subtitle">
						<div class="num">1</div>
						<div class="tcopy">의료비 보장 한도를 확인하세요</div>
					</div>
					<div class="plan_subtitle">
						<div class="num">2</div>
						<div class="tcopy">지원되는 여행/의료 서비스를 확인하세요</div>
					</div>
				</div>
				<!--20230528 문구변경 end-->
				<ul class='nav nav-tabs nav-justified'>
					<li class=''><a href="pr_plans.php?PR_SEQ=<?=$PR_SEQ?>">상품안내</a></li>
					<li class='active'><a href="javascript:;">유의사항</a></li>
					<li class=''><a href="insuplus_case.php?PR_SEQ=<?=$PR_SEQ?>">고객후기</a></li>
				</ul>
				<p class='m-y-15 text-black'>
					유의사항은 약관내용을 요약 발췌한 것이므로 세부내용은 반드시 약관을 참조하시기 바랍니다.<br>
					보험계약 체결 전에 상품 설명서 및 약관을 꼭 읽어보시기 바랍니다.
				</p>
				<h3 class='text-black m-b-1'>약관 다운로드</h3>
				<div class='row <?if(!$row_service_agree["service_cd"]){?>m-b-3<? }?>'>
					<?
					if($arr_ins_list) {
						foreach($arr_ins_list as $row) {
							$row_plan = getNoteInsAgreeOfPlan($PR_SEQ,$row["ins_seq"], $PR_INFO["ext1"]);
							$arr_ins_agree_file = selInsAgreeFile($row_plan["agree_cd"]);
						?>
						<div class='col-md-2 col-sm-4 col-xs-6 m-t-1'><a href="javascript:;" onClick="fnDown('<?=all_seed_enc("/_data/board/ins_agree/".$arr_ins_agree_file["file_realname"])?>','<?=$arr_ins_agree_file["file_name"]?>','<?=$arr_ins_agree_file["file_size"]?>')" class='btn btn-lg btn-block btn-theme-dark btn-outline text-left'><?=print_ins($row["ins_seq"]);?><i class='ti ti-download pull-right'></i></a></div>
					<? }
					}?>
				</div>
				<? if($row_service_agree["service_cd"]) {
					$arr_service_file = selServiceFile($row_service_agree["service_cd"]);
				?>
				<h3 class='text-black m-t-3 m-b-1'>서비스 이용약관 다운로드</h3>
				<div class='row m-b-3'>
					<div class='col-md-2 col-sm-4 col-xs-6 m-t-1'><a href="javascript:;" onClick="fnDown('<?=all_seed_enc("/_data/board/service_agree/".$arr_service_file["file_realname"])?>','<?=$arr_service_file["file_name"]?>','<?=$arr_service_file["file_size"]?>')" class='btn btn-lg btn-block btn-theme-dark btn-outline text-left'>인슈플러스<i class='ti ti-download pull-right'></i></a></div>
				</div>
				<? } ?>
				<div class='nav-theme nav-scroll'>
				<ul class='nav nav-tab-0s nav-justified'>
					<li class='flex-50 active'><a href="#tab-01">01. 보험계약체결</a></li>
					<li class='flex-50'><a href="#tab-02">02. 보험금을 지급하지 않는 사유</a></li>
					<li class='flex-50'><a href="#tab-03">03. 실손의료비 보험금 지급관련 유의사항</a></li>
					<li class='flex-50'><a href="#tab-04">04. 청약철회 청구제도</a></li>
					<li class='flex-50'><a href="#tab-05">05. 보험계약상의 알릴 의무</a></li>
					<li class='flex-50'><a href="#tab-06">06. 보험사 3대 기본지키기</a></li>
					<li class='flex-50'><a href="#tab-07">07. 해지환급금 산출기준</a></li>
					<li class='flex-50'><a href="#tab-08">08. 예금자 보호 안내</a></li>
					<li class='flex-50'><a href="#tab-09">09. 상담 및 보험분쟁 조정 안내</a></li>
					<li class='flex-50'><a href="#tab-10">10. 청약철회제도</a></li>
				</ul>
				</div>
				<div class='tab-pane note-content'>
					<section id='tab-01' data-tab='tab-01' class='tab-content'>
						<h3 class='title m-t-3'>01. 보험계약체결</h3>
						<p>보험계약 체결 전에 상품설명서 및 약관을 읽어보시기 바랍니다.<br/>보험계약자가 기존에 체결했던 보험계약을 해지하고 다른 보험계약을 체결하면 보험인수가 거절되거나 상품가격가 인상되거나 보장내용이 달라질 수 있습니다.</p>
					</section>
					<section id='tab-02' data-tab='tab-02' class='tab-content'>
						<h3 class='title m-t-3'>02. 보험금을 지급하지 않는 사유</h3>
						<p>계약자나 보험수익자 또는 피보험자의 고의, 자해, 범죄 또는 폭력행위, 형의 집행, 전쟁, 혁명, 내란, 폭동, 핵연료물질, 방사선 등 면책사항은 보험약관에 자세히 명시되어 있습니다.</p>
					</section>
					<section id='tab-03' data-tab='tab-03' class='tab-content'>
						<h3 class='title m-t-3'>03. 실손의료비 보험금 지급 관련 유의사항</h3>
						<ul class='list-unstyled icons line-height-8'>
							<li><i class='ti ti-check'></i>발생 의료비 중 국민건강보험 급여의 본인부담금과 비급여를 보장해주는 보험이며, 약관상 보장제외 항목에서 발생한 의료비는 보장되지 않습니다.</li>
							<li><i class='ti ti-check'></i>실제 발생한 의료비를 보상하는 보험을 2개 이상 가입하더라도 실제 발생한 비용만을 보상받게 되므로, 유사한 보험가입여부 및 보상한도를 반드시 확인하시기 바랍니다.</li>
							<li><i class='ti ti-check'></i>보험금을 지급할 다수의 보험계약이 체결되어 있는 경우에는 각각의 계약에 대하여 다른 계약이 없는 것으로 하여 산출한 보상책임액의 합계액이 이 계약의 의료비를 초과했을 때, 이 계약에 따른 보상책임액의 위의 합계액에 대한 비율에 따라 의료비보험금을 지급하여 드립니다.<li>
						</ul>
					</section>
					<section id='tab-04' data-tab='tab-04' class='tab-content'>
						<h3 class='title m-t-3'>04.  청약철회 청구제도</h3>
						<p>계약자는 보험증권을 받은 날로부터 15일 이내에 그 계약의 청약을 철회할 수 있으며 이 경우 이미 납입한 상품가격를 돌려드립니다.<br/>
						다만, 진단계약, 보험기간이 1년 미만인 계약인 계약 또는 전문금융소비자가 체결한 계약의 경우에는 청약을 철회할 수 없으며 청약을 한 날로부터 30일이 초과 된 계약은 청약을 철회할 수 없습니다.</p>
					</section>
					<section id='tab-05' data-tab='tab-05' class='tab-content'>
						<h3 class='title m-t-3'>05. 보험계약상의 알릴 의무</h3>
						<p>보험계약 청약 시 계약자 및 피보험자는 청약서상의 질문사항(고지사항)에 대하여 사실대로 알려야 합니다.<br/>
							만일 허위 또는 부실하게 알렸을 경우에는 보험사고 발생 시 보상이 되지 않음은 물론 보험계약이 해지될 수 있습니다.</p>
					</section>
					<section id='tab-06' data-tab='tab-06' class='tab-content'>
						<h3 class='title m-t-3'>06.  보험사 3대 기본 지키기</h3>
						<p>1. 자필서명 2. 계약자 보관용 청약서 전달 3. 약관 전달 및 주요 내용 설명<br/>
							보험사가 3대 기본 기키기 미이행시 보험계약자는 계약이 성립한 날로부터 3개월 이내에 계약을 취소할 수 있습니다. 이 경우 회사는 이미 납입한 상품가격를 돌려 드립니다.	</p>
					</section>
					<section id='tab-07' data-tab='tab-07' class='tab-content'>
						<h3 class='title m-t-3'>07. 해지환급금 산출기준</h3>
						<p>계약자, 피보험자 또는 보험수익자의 책임없는 사유에 의하는 경우 : 무효의 경우에는 회사에 납입한 상품가격의 전액, 효력상실, 해지 또는 소멸의 경우에는 경과하지 않은 기간에 대하여 일 단위로 계산한 상품가격</p>
						<p>계약자, 피보험자 또는 보험수익자의 책임있는 사유에 의하는 경우 : 이미 경과한 기간에 대하여 단기요율(1년미만의 기간에 적용되는 요율)로 계산한 상품가격를 뺀 잔액. 다만, 계약자, 피보험자 또는 보험수익자의 고의 또는 중대한 과실로 무효가 된 때에는 상품가격를 돌려드리지 않습니다.</p>
					</section>
					<section id='tab-08' data-tab='tab-08' class='tab-content'>
						<h3 class='title m-t-3'>08.  예금자 보호 안내</h3>
						<p>이 보험계약은 예금자보호법에 따라 예금보험공사가 보호하되, 보호 한도는 본 보험회사에 있는 귀하의 모든 예금보호 대상 금융상품의 해약환급금(또는 만기시 보험금이나 사고보험금)에 기타지급금을 합하여 1인당 "최고 5천만원"이며, 5천만원을 초과하는 나머지 금액은 보호하지 않습니다. 다만, 보험계약자 및 상품가격 납부자가 법인이면 보호되지 않습니다. 위 내용은 예금자보호법 및 관련 법령의 개정에 따라 달라질 수 있으며, 자세한 내용은 예금보험공사(www.kdic.or.kr)로 문의하시기 바랍니다.</p>
					</section>
					<section id='tab-09' data-tab='tab-09' class='tab-content'>
						<h3 class='title m-t-3'>09.  상담 및 보험분쟁 조정 안내</h3>
						<p>보험에 대한 문의사항 및 불만사항이 있을 경우 현대해상(1588-5656), 한화손해보험(1566-8000), MG손해보험(1588-5959)으로 연락주시면 신속하게 처리해드리겠습니다.
또한 보험에 관한 분쟁이 있을 때에는 금융감독원 및 한국소비자원에 분쟁조정을 신청할 수 있습니다.</p>
						<p class="m-t-2">
							<strong>금융감독원</strong>&nbsp;&nbsp;&nbsp;&nbsp;전화 : 1332 / 인터넷 :&nbsp;www.fas.or.kr&nbsp;(e-금융센터 :&nbsp;www.fcsc.kf)<br>
							<strong>한국소비자원</strong>&nbsp;&nbsp;&nbsp;&nbsp;전화 : 1372 / 인터넷 :&nbsp;www.kca.go.kr
						</p>
					</section>
					<section id='tab-10' data-tab='tab-10' class='tab-content'>
						<h3 class='title m-t-3'>10.  청약철회제도</h3>
						<p>전문금융소비자가 체결한 보험 계약 또는 청약일부터 30일 초과계약(65세 이상 계약자가 전화로 체결한 계약은 45일 초과 시), 회사가 건강상태 진단을 지원하는 계약, 보험기간(가입기간)이 90일 이내인 계약은 청약을 철회할 수 없습니다. </p>
						<p>【일반금융소비자】 전문금융소비자가 아닌 금융소비자를 말합니다.</p>
						<p>【전문금융소비자】 보험계약에 관한 전문성, 자산규모 등에 비추어 보험계약에 따른 위험감수능력이 있는 자로서, 국가, 지방자치단체, 한국은행, 금융회사, 주권상장법인 등을 포함하며, 「금융소비자보호에 관한 법률」 제2조제9호에서 정하는 전문금융소비자인 계약자를 말합니다.</p>
						
					</section>
					<!-- <section id='tab-10' data-tab='tab-10' class='tab-content'>
						<h3 class='title m-t-3'>10.  상담 및 보험분쟁 조정 안내</h3>
						<p>보험에 대한 문의사항 및 불만사항이 있을 경우 현대해상(전화:1588-5656, 인터넷 : www.hi.co.kr → 전자민원접수)으로 연락 주시면 신속하게 처리해 드리겠습니다.
또한 보험에 관한 분쟁이 있을 때에는 금융감독원 및 한국소비자원에 분쟁조정을 신청할 수 있습니다.
						</p>

						<p class="m-t-2">
							<strong>금융감독원</strong>&nbsp;&nbsp;&nbsp;&nbsp;전화 : 1332 / 인터넷 :&nbsp;www.fas.or.kr&nbsp;(e-금융센터 :&nbsp;www.fcsc.kf)<br>
							<strong>한국소비자원</strong>&nbsp;&nbsp;&nbsp;&nbsp;전화 : 1372 / 인터넷 :&nbsp;www.kca.go.kr
						</p>
					</section> -->
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
</script>


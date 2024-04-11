<?php
	include '../_include/_header.html';
	include '../_include/_top.html';
	include '../_include/_sidebar.html';
	
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
	if($row_r["chk_service"] == "A" ||  $row_r["chk_service"] == "B") {
		$arr_service_file = selServiceFile($row_r["service_file_cd"]);
	}
	
?>
<script>
	//google 전자상거래 설정
	gtag(‘event’, ‘purchase’, {
		“transaction_id”: “<?= $_SESSION["orderno"]; ?>”,
		“affiliation”: “INSUPLUS”,
		“value”: “value”,
		“currency”: “KRW”,
		“tax”: <?= $row_r["service_amount"] ?>,
		“shipping”: 0,
		“items”: [
			{
				“id”: “<?= $row_r["pr_cd"] ?>”,
				“name”: “<?= $row_r["pr_name"] ?>”,
				“quantity”: 1,
				“price”: ‘<?=number_format($row_r["t_amount"])?>’
			}
		]
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
<!-- Naver 전환페이지 설정 -->
<script type="text/javascript" src="//wcs.naver.net/wcslog.js"></script> 
	<script type="text/javascript"> 
	var _nasa={};
	_nasa["cnv"] = wcs.cnv("1","<?=number_format($row_r["t_amount"])?>"); // 전환유형, 전환가치 설정해야함. 설치매뉴얼 참고
</script> 
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
									<div class='detail-col-label'>보험기간</div>
									<div class='detail-col-input'><?=$row_r["s_date"]?> <?if($row_r["s_date_time"]){?><?=$row_r["s_date_time"]?>시<?}?> ~ <?=$row_r["e_date"]?> <?if($row_r["e_date_time"]){?><?=$row_r["e_date_time"]?>시<?}?> (<?=$row_r["ins_period"]?><?=$arr_chk_p_gubun[$row_r["chk_p"]]?>)</div>
									<div class='detail-col-label'>플랜명</div>
									<div class='detail-col-input'><?=$row_r["pr_name"]?> <?=$row_r["ins_name"]?> <?=$row_r["plan_name"]?> <?=$Arr_img_plus[$row_r["chk_service"]]?></div>
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
										<? if($row_r["chk_service"] == "A" || $row_r["chk_service"] == "B") {?>
									<span class='btn-group'><a class='btn btn-lg btn-block btn-default text-left' onClick="fnDown('<?=all_seed_enc("/_data/board/service_agree/".$arr_service_file["file_realname"])?>','<?=$arr_service_file["file_name"]?>','<?=$arr_service_file["file_size"]?>')">서비스 이용약관<i class='ti ti-download pull-right'></i></a></span>
									<? } ?>
								</div>
							</div>
							</div>
							
							<div class='row m-t-3' id='coupon'>
								<div class='col-sm-4 col-xs-5'>
									<div class='coupon-01'>
										<div class='sum'>10<small>%</small></div>
										<div class='title'>COUPON</div>
									</div>
								</div>
								<div class='col-sm-8 col-xs-7 title-text'>
									<h5 class='text-black'>인슈플러스 가입 감사 쿠폰이 발행되었습니다. <br class='hidden visible-xs' />쿠폰은 가입확인에서 조회하실 수 있고 친구에게 선물하실 수 있습니다.</h5>
								</div>
								<div class='col-sm-8 col-sm-offset-4 col-xs-12 form-inline'>
									<div class='input-group'>
										<input type="text" name='mobile' placeholder="휴대폰번호" class="form-control numberonly" size='30' maxlength="12" />
										<span class='input-group-btn'><button class='btn btn-default' onClick="fnSendCoupon()">쿠폰전송</button>
									</div>
									<p class='text-danger m-t-1'>※ 선물한 쿠폰은 선물 보낸 휴대폰번호로 가입시 사용하실 수 있습니다.</p>
								</div>
							</div>
							<div class='row'>
								<!-- 
								<div class='col-xs-12'>
									<h5 class='text-black m-t-2 m-b-05'><img src="../images/ic-noti.svg" align="absmiddle" alt="" height="24" />&nbsp;영문증 발송 안내</h5>
								</div>
								<div class='col-md-7 m-y-05'>
									영문가입증명서이 필요한 경우 가입조회 메뉴에서 다운받을 수 있습니다.
								</div>
								<div class='col-md-5'>
									<a href='../join/join_confirm.php' class='btn btn-md btn-default p-x-2'>가입조회<i class='ti ti-angle-right m-l-1'></i></a>
								</div>
								-->
								<div class='col-xs-12'>
									<h5 class='text-black m-t-2 m-b-05'><img src="../images/ic-noti.svg" align="absmiddle" alt="" height="24" />&nbsp;가입증명서</h5>
								</div>
								<div class='col-md-7 m-y-05'>
									가입증명서(국문/영문)는 가입조회 페이지에서 다운로드 하실 수 있습니다.
								</div>
								<div class='col-md-5'>
									<a href='../join/join_confirm.php' class='btn btn-md btn-default p-x-2'>가입조회<i class='ti ti-angle-right m-l-1'></i></a>
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


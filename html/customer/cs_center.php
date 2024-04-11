<?php
	include '../_include/_header.html';
	include '../_include/_top.html';
	include '../_include/_sidebar.html';
	
	$lm = "0101"; //레프트 메뉴 활성화
?>
		<div class="breadcrumb-image">
			<div class="container">
				<h2>24시간 알람 센터</h2>
				<h4>24시간 알람 센터입니다.</h4>
			</div>
		</div>
		<div class="breadcrumb-wrap">
            <div class="container">
				<ol class="breadcrumb">
					<li><a href="../main/index.php">InsuPlus HOME</a></li>
					<li><a href="../customer/cs_center.php">고객센터</a></li>
					<li>24시간 알람 센터</li>
				</ol>
            </div>
        </div>
		<div class="overflow-hidden">
			<div class="container">
				<div class="row">
					<div class="col-md-2 col-sm-3">
						<?php
							include '../_include/_cs_left.html';
						?>
					</div>
					<div class="col-md-10 col-sm-9">
						<div class="sub-content cs-wrap">
							<div class=' hidden-xs'>
								<h2 class='text-black'>24시간 알람센터</h2>
								<h5 class='m-t-05'>언제, 어디서나, 인슈플러스는 기다리고 있습니다.</h5>
								<div class='clearfix m-t-2'>
									<img src="../images/title-cscenter.png" srcset="../images/title-cscenter@2x.png 2x, ../images/title-cscenter@3x.png 3x" style='width:100%;'>
								</div>
							</div>
							<div class='hidden visible-xs' style='margin:-16px -16px auto -16px;'>
								<img src="../images/title-cscenter-m.png" style='width:100%;'>
							</div>
							<div class='row btn-icon-group btn-group-csenter'>
								<div class='col-lg-3 col-md-6 col-sm-6 col-xs-6 m-t-1'>
									<a class='btn btn-block btn-default btn-icon btn-cscenter' href='#'>
										<p><small class='text-gray'>24시간 알람 센터</small></p>
										<h3 class='text-danger'><?=$insuplus_phone;?></h3>
										<p class='text-black hidden-xs'>24시간 연중무휴</p>
									</a>
								</div>
								<div class='col-lg-3 col-md-6 col-sm-6 col-xs-6 m-t-1'>
									<a class='btn btn-block btn-default btn-icon kakao' href='<?=$insuplus_kakaoPlus?>' target="_blank">
										<p><small class='text-gray'>카카오톡 플러스 친구</small></p>
										<h3>인슈플러스</h3>
									</a>
								</div>
								<div class='col-lg-6 col-md-12 col-sm-12 col-xs-12 m-t-1'>
									<div class='btn btn-block border-a-0 btn-icon case' href='#'>
										<div class='row'>
											<div class='col-sm-8 col-xs-12'>
												<p>전세계에서 인슈플러스 서비스를</p>
												<h3>이용한 사례를 확인해 보세요!</h3>
											</div>
											<div class='col-sm-4 col-xs-12'>
												<a class='btn btn-block btn-default text-black' href="./counsel_case_list.php">상담사례보기</a>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
        </div>
<?php
	include '../_include/_tail.html';
	include '../_include/_footer.html';
?>


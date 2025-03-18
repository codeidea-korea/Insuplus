<?php
include '../_include/_header.html';
include '../_include/_top.html';
include '../_include/_sidebar.html';
?>
<div class="breadcrumb-image">
	<div class="container">
		<h2>증명서 발급</h2> <!--20231013 수정-->
		<h4>고객님의 가입 및 쿠폰 내역과 가입증명서를 확인하세요.</h4> <!--20231013 수정-->
	</div>
</div>
<div class="breadcrumb-wrap">
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="../main/index.php">InsuPlus HOME</a></li>
			<li>가입확인</li>
		</ol>
	</div>
</div>
<form name="frm_join_chk" method="post">
	<div class="container">
		<div class="sub-content join_confirm_wrap">
			<div class='row text-center'>
				<div class='col-md-5 col-sm-7'>
					<div class='panel panel-body text-center'>
						<img src="../images/info-insu.svg" align="absmiddle" height="80" />
						<h5 class='text-black text-center m-y-2'>가입시 입력하신 정보를 입력해주세요.</h5> <!--20231013 수정-->
						<div class='row-border row-sm'>
							<div class='detail-col-label'>휴대폰번호</div>
							<div class='detail-col-input'><input type="tel" id="hp" autocomplete="off" placeholder="휴대폰번호" class="form-control numberonly" maxlength="12" size='20' /></div>
							
                            <div class='detail-col-label hidden certi' >인증번호</div>
							<div class='detail-col-input hidden certi'>
								<div class='input-group' style='width:100%;'>
									<input type="tel" name='rnumber' placeholder="인증번호를 입력하세요." autocomplete="off" class="form-control numberonly" size='12' maxlength="7" onkeypress="if(event.keyCode==13) chk_submit();" />
								</div>
                                <div id="timer" class="certi hidden">
                                </div>
							</div>
						</div>
						<div class='row m-t-2'>
							<div class='col-md-4 col-md-offset-4 col-sm-6 col-xs-6 col-sm-offset-3 col-xs-offset-3'>
								<a href='javascript:sendCertificationNumber();' class='btn btn-lg btn-block btn-theme-bg'>인증번호 발송</a>
							</div>
						</div>
					</div>
					<div class='bg-light-yellow p-a-1 m-t-2 text-left'>
						<h5 class='text-black m-b-1'><img src="../images/ic-noti.svg" align="absmiddle" alt="" height="24" />&nbsp;알려드립니다.</h5>
						<ul class='icons list-unstyled'>
							<li class='line-height-4'><i class='ti ti-minus'></i>가입하신 서비스 정보를 확인 할 수 있습니다.</li>
							<li class='line-height-4'><i class='ti ti-minus'></i>가입증명서는 국문과 영문으로 다운로드 받으실 수 있으며 보험 가입내역 또는 보험+서비스 가입내역을 선택해서 다운로드 받으실 수 있습니다.</li>
							<li class='line-height-4'><i class='ti ti-minus'></i>카카오톡에서 다운로드 받으실 경우 파일이 손상될 수 있으니 다른 브라우저에서 다운로드 받으세요.</li>
							<li class='line-height-4'><i class='ti ti-minus'></i>가입하신 가입증명서(국문, 영문)을 재발급 받을 수 있습니다.</li>
							<li class='line-height-4'><i class='ti ti-minus'></i>쿠폰내역을 확인할 수 있습니다.</li>
						</ul>
					</div>
				</div>
			</div>
		</div> 
	</div>
</form>
<script type="text/javascript">
    function sendCertificationNumber(){ 
        let mobile = $("#hp").val().trim();
        validatePhoneNumber(mobile) ? sendCertificationNumberAjax(mobile) : alert("휴대폰 번호를 확인해주세요.") ;
      
    }

    function sendCertificationNumberAjax(mobile){
        $.ajax({
            url: 'join_confirm_ajax.php',
            type: 'post', 
            data: {
                'mobile': mobile
            },
            success: function (data) {
                alert('인증번호가 발송되었습니다.');
                $(".certi").removeClass("hidden");
                startTimer(300);
                console.log(data.trim());
                
            },
            error: function (request, status, error) {
                alert('인증번호 발송에 실패하였습니다.');
                console.log('code: '+request.status+"\n"+'message: '+request.responseText+"\n"+'error: '+error);
            }
        });
    }

    function validatePhoneNumber(phoneNumber) {
  
    const regex = /^01(?:0|1|[6-9])(?:\d{3}|\d{4})\d{4}$/;
    return regex.test(phoneNumber);
    }


    // 타이머 함수
function startTimer(duration) {
    var timer = duration;
    var minutes, seconds;
    var timerDisplay = document.getElementById('timer'); // 타이머를 표시할 요소
    
    // 타이머 요소가 없으면 생성
    if (!timerDisplay) {
        timerDisplay = document.createElement('div');
        timerDisplay.id = 'timer';
        // 적절한 위치에 타이머 요소 추가 (예: 인증번호 입력 필드 옆)
        document.querySelector('.certification-input-container').appendChild(timerDisplay);
    }
    
    // 타이머가 이미 실행 중이면 초기화
    if (window.timerInterval) {
        clearInterval(window.timerInterval);
    }
    
    // 타이머 실행
    window.timerInterval = setInterval(function() {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);
        
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        
        timerDisplay.textContent = minutes + ":" + seconds;
        
        if (--timer < 0) {
            clearInterval(window.timerInterval);
            timerDisplay.textContent = "00:00";
            alert("인증 시간이 만료되었습니다. 다시 인증번호를 발송해주세요.");
            // 만료 시 추가 처리 (예: 인증번호 입력 필드 비활성화)
            document.getElementById('certification-input').disabled = true;
        }
    }, 1000);
}
</script>
<?php
include '../_include/_tail.html';
include '../_include/_footer.html';
?>
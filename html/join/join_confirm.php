<?php
include '../_include/_header.html';
include '../_include/_top.html';
include '../_include/_sidebar.html';
?>
<style>
    .input-group {
        display: flex;
        flex-direction: row;
        gap: 8px;
        justify-content: space-between;
        align-items: center;
    }

    .input-group input{
      width: 100%;
    }
    .button-group a{
        padding: 6px 12px;
    }
    .button-group a.chk-btn{
        padding: 6px 24px;

    }
</style>
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
						<h5 class='text-black text-center m-t-2'>가입시 입력하신 정보를 입력해주세요.</h5> <!--20231013 수정-->
                        <p class="m-b-2">(동반가입자의 경우 대표가입자의 정보를 입력해주세요.)</p>
						<div class='row-border row-sm'> 
                            <div class='detail-col-label'>이름</div>
                            <div class='detail-col-input'><input type="text" id="nm" name='nm' autocomplete="off" placeholder="이름" class="form-control" maxlength="12" size='20' /></div>
							<div class='detail-col-label'>휴대폰번호</div>
							<div class='detail-col-input'>
                                <div class='input-group' style='width:100%; '>
                                    <input type="tel" id="hp" name='hp' autocomplete="off" placeholder="휴대폰번호" class="form-control numberonly" maxlength="12" size='20' />
                        
                                    <div class='button-group'> 
                                        <a href='javascript:sendCertificationNumber();' class='btn btn-md btn-block btn-theme-bg'>인증 받기</a>
                                    </div>
                                </div>  
                            </div>
							
                            <div class='detail-col-label' >인증번호</div>  
							<div class='detail-col-input'>
                                <div class='input-group' style='width:100%; '>
                                    <input type="tel" id="certi_num" autocomplete="off" placeholder="인증번호를 입력하세요." class="form-control numberonly" maxlength="12" size='20' />
                        
                                    <div class='button-group'> 
                                        <a href='javascript:certiNumChk();' class='btn btn-md btn-block btn-theme-bg chk-btn'>확인</a>
                                    </div>
                                </div>  
                            </div>
						</div>
						<div class='row m-t-2'> 
							<div class='col-md-4 col-md-offset-4 col-sm-6 col-xs-6 col-sm-offset-3 col-xs-offset-3'>
                            <a href='javascript:chk_submit();' class='btn btn-lg btn-block btn-theme-bg'>조회하기</a>
							</div>
						</div>
					</div>
					<div class='bg-light-yellow p-a-1 m-t-2 text-left'>
						<h5 class='text-black m-b-1'><img src="../images/ic-noti.svg" align="absmiddle" alt="" height="24" />&nbsp;알려드립니다.</h5>
						<ul class='icons list-unstyled'>
							<li class='line-height-4'><i class='ti ti-minus'></i>가입하신 상품 정보를 확인할 수 있습니다.</li>
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

    let certiNum = '';
    let cert = false;
    let failCount = 1;
    function certiNumChk(){
        if(  $("#certi_num").val().trim() == ''){
            alert("인증번호를 입력해주세요.");
            return;
        }

        if(failCount > 5){
            alert("인증번호를 5회 연속 실패하였습니다. \n [인증받기] 재시도 후 다시 입력해주세요.");
            certiNum ='';
            return;
        }

        if(    $("#certi_num").val().trim() === certiNum){
            cert = true;
            alert("인증번호가 확인되었습니다.");
        }else{
            alert("인증번호가 일치하지않습니다. \n 다시입력해주세요. \n 실패횟수 : "+failCount+" 회");
            failCount++;
            return false;
        }

        // $("#certi_num").val().trim() === certiNum ? alert("인증번호가 확인되었습니다.") : alert("인증번호를 확인해주세요.");
    }

    function chk_submit() {
		var ff = document.frm_join_chk;
		if (ff.hp.value == "") {
			alert("휴대폰번호를 입력해주세요.");
			return;
		}
		if (ff.nm.value == "") {
			alert("이름을 입력해주세요.");
			return;
		}
        if(!cert){
            alert("휴대폰 인증을 진행해주세요.");
            return;
        }
		ff.action = "./join_confirm_result.php";
		ff.submit();
	}

    function sendCertificationNumber(){ 
        let mobile = $("#hp").val().trim();
        validatePhoneNumber(mobile) ? sendCertificationNumberAjax(mobile) : alert("휴대폰 번호를 확인해주세요.") ;
      
    }

    function sendCertificationNumberAjax(mobile){
        if(certiNum !== '' && failCount < 6){
            alert('이미 인증번호가 발송되었습니다.');
            $("#certi_num").focus();
            return;
        }
        $.ajax({
            url: 'join_confirm_ajax.php',
            type: 'post', 
            data: {
                'mobile': mobile
            },
            success: function (data) {
                alert('인증번호가 발송되었습니다.');
                $("#hp").attr('readonly', true);
                certiNum = data.trim();
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


</script>
<?php
include '../_include/_tail.html';
include '../_include/_footer.html';
?>
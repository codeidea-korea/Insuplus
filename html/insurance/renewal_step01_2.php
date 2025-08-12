<?php
include '../_include/_header_new.html';
include '../_include/_top.html';
?>
<section>
  <div class="container">
    <div class="title-box">
      <h2>법정 대리인 본인인증</h2>
      <p>본 서비스는 만 14세 미만 고객의 가입 또는 조회 시, 법정 대리인의 동의 및 본인인증이 필요합니다.</p>
    </div>
    <div class="white-box middle mt24">
      <form>
        
        <div class="form-box">
            <div class="form-text-box mb20">
                <b>대리인 정보 입력</b>
            </div>
          <div class="form-content">
            <div class="flex flex-vc flex-tj">
                <div class="form-title">
                    <strong>성명</strong>
                </div>
                <div class="col-10">
                    <div class="input-box">
                        <div class="input-box-inner">
                            <input type="input" name="name" id="name" placeholder="성명을 입력해주세요" maxlength="8" required="" >
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
        <div class="form-box">
          <div class="form-content">
            <div class="flex flex-vc flex-tj">
                <div class="form-title">
                    <strong>생년월일</strong>
                </div>
                <div class="col-10">
                    <div class="input-box">
                        <div class="input-box-inner">
                            <input type="input" name="A-birth" id="A-birth" placeholder="생년월일을 입력해주세요" maxlength="8" required="" >
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
        <div class="form-box">
          <div class="form-content">
            <div class="flex flex-vc flex-tj">
                <div class="form-title">
                    <strong>휴대폰번호</strong>
                </div>
                <div class="col-10 flex flex-vc" style="gap:10px;">
                    <div class="col-3">
                        <div class="input-box">
                            <div class="input-box-inner">
                                <input type="input" name="phone_1" id="phone_1" placeholder="" maxlength="8" required="" >
                            </div>
                        </div>
                    </div>
                    <div>-</div>
                    <div class="col-3">
                        <div class="input-box">
                            <div class="input-box-inner">
                                <input type="input" name="phone_2" id="phone_2" placeholder="" maxlength="8" required="" >
                            </div>
                        </div>
                    </div>
                    <div>-</div>
                    <div class="col-3">
                        <div class="input-box">
                            <div class="input-box-inner">
                                <input type="input" name="phone_3" id="phone_3" placeholder="" maxlength="8" required="" >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
        <div class="form-box">
          <div class="form-content">
            <div class="flex flex-vc flex-tj">
                <div class="form-title">
                    <strong>인증방법</strong>
                </div>
                <div class="col-10">
                    <div class="input-box">
                        <div class="input-box-inner" style="background:#f1f1f1; ">
                            <input type="input" name="certifie" id="certifie" value="휴대폰 본인인증" disabled style="">
                        </div>
                    </div>
                </div>
            </div>
          </div>
        </div>


        <div class="form-box">
          <div class="form-content">
            <div class="button-box">
              <button type="button" class="btn btn-active mr20">본인인증 요청</button>
              <a href="./renewal_step01.php?test" class="btn btn-white">취소</a>
            </div>
          </div>
        </div>
        
      </form>
    </div>
   
  
</section>

<script src="./js/swiper.js?a=1"></script>

<?php
include '../_include/_tail.html';
include '../_include/_footer.html';
?>

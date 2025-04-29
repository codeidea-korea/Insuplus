<!-- 팝업 배경 -->
<div id="popupOverlay"></div>

<!-- 팝업 창 -->
<div id="popup">
    <h2>엑셀 다운로드</h2>
    <p>엑셀 다운로드 사유 입력</p>
    <input type="text" id="reason" placeholder="다운로드 사유 입력">
    <input type="password" id="excel_enc" placeholder="암호 입력">
    <input type="hidden" id="excel_type" />
    <button id="reasonBtn" class="submitBtn">사유등록 및 엑셀 다운로드</button>
    <button id="closePopup">닫기</button>
</div>

<style>
/* 팝업 배경 */
#popupOverlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
}

/* 팝업 창 스타일 */
#popup {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 400px;
    padding: 20px;
    background: white;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    z-index: 1000;
    text-align: center;
}

#popup h2 {
    margin-top: 0;
}

#popup input {
    width: 80%;
    padding: 10px;
    margin: 10px 0;
}

#popup button {
    padding: 10px 20px;
    margin-top: 10px;
    border: none;
}

.submitBtn {
    background: #0a9117;
    color: #fff;
    border: none;
}

</style>

<script>
$(document).ready(function () {
    // 팝업 닫기
    $('#closePopup').click(function () {
        $('#popupOverlay').fadeOut();
        $('#popup').fadeOut();
    });

    // 사유 등록 버튼 클릭 시
    $('#reasonBtn').click(function () {
        var name = $('#excel_type').val();
        excelReason(name);
    });
});
</script>
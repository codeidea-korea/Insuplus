<?
include_once $_SERVER['DOCUMENT_ROOT'] . '/_config/lib.php';
// echo $_GET['v'];
// QnCDV1b2vAjS/bT1ONplHA==
// echo all_seed_dec($_GET['v']);


// echo urldecode($_GET['v']);
// 수정된 코드
try {
    if(!isset($_GET['v']) || empty($_GET['v'])) {
        echo "<script>alert('잘못된 접근입니다.');</script>";
        exit;
    }
    $decodedString = rawurldecode($_GET['v']);
    $seq = all_seed_dec($decodedString);
    
    // 복호화 결과 검증 (예: 숫자여야 한다면)
    if($seq === false || !is_numeric($seq)) { 
        echo "<script>alert('잘못된 접근입니다.');</script>";
        exit;
    }
} catch (Exception $e) {
    echo "<script>alert('데이터 처리 중 오류가 발생했습니다.');</script>";
    exit;
}

$seq = all_seed_dec($_GET['v']);


$SQL_L = "select * from tbl_order_list_join where seq='" . $seq . "'";

$RS_L = $dbcon->query($SQL_L);
if (!$RS_L) {
    echo "<script>alert('해당 보험내역 가입자 정보가 없습니다.');window.close();</script>";
    exit;
}
$row_L = $dbcon->fetch_array($RS_L);

$SQL_V = "select * from tbl_order_list where orderno = '" . $row_L['orderno'] . "' ";

$RS_V = $dbcon->query($SQL_V);
if (!$RS_V) {
    echo "<script>alert('해당 보험내역이 없습니다.');</script>";
    exit;
}
$row_r = $dbcon->fetch_array($RS_V);
?>


<head>
    <title>InsuPlus</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
</head>

<form name="frm_cert" id="frm_cert" method="post" style="display:none">
        <input type="hidden" name="join_seq" value="<?= $seq ?>">
        <input type="hidden" name="orderno" value="<?= $row_L["orderno"] ?>">
        <input type="hidden" name="mode" value="view" />
        <!-- (s) 상세영역 -->
        <table class="adm-view-tb">
            <colgroup>
                <col width="16%">
                <col width="34%">
                <col width="16%">
                <col width="34%">
            </colgroup>
            <tr>
                <th>가입증명원</th>
                <td><label for="korea">국문</label><input type="radio" name="chk_lang" id="korea" value="K" checked />
                    <label for="english">영문</label><input type="radio" name="chk_lang" id="english" value="E" />
                </td>
                <th>결제상태</th>
                <td><?= $arr_ord_step[$row_r["order_step"]] ?></td>
            </tr>
            <tr>
                <th>이름</th>
                <td><?= all_seed_dec($row_L["o_name"]) ?>
                    <input type="hidden" name="o_name" value="<?= all_seed_dec($row_L["o_name"]) ?>" />
                </td>
                <th>영문</th>
                <td>
                    <?= all_seed_dec($row_L["o_name_en"]) ?>
                    <input type="hidden" name="o_name_en" value="<?= all_seed_dec($row_L["o_name_en"]) ?>" />
                </td>
            </tr>
            <? if (!is_null($row_L["group_join_type"]) || $row_L["group_join_type"] === "B2B") { ?>
                <tr>
                    <th>구분</th>
                    <td colspan="3">
                        
                        <label for="assistance">코리아어시스턴스</label><input type="radio" name="chk_fly_type" id="assistance" value="A" checked />
                        <label for="bizinsight">비즈인사이트</label><input type="radio" name="chk_fly_type" id="bizinsight" value="B" />
                    </td>
                </tr>
            <? } ?>
            <tr>
                <th>증명서 유형</th>
                <td colspan="3">
                    <label for="insService">보장내역+서비스내역</label><input type="radio" name="certType" id="insService" value="A" checked />
                    <label for="insure">보장내역</label><input type="radio" name="certType" id="insure" value="I" />
                    <label for="service">서비스내역</label><input type="radio" name="certType" id="service" value="S" />
                </td>
            </tr>
            <tr>
                <th>휴대폰번호</th>
                <td colspan="3">
                    <input type="text" name="mobile" class="onlyNumber" maxlength="11" value="<?= all_seed_dec($row_L["o_phone"]) ?>" />
                </td>
            </tr>
            <tr>
                <th>이메일</th>
                <td colspan="3">
                    <input type="text" name="email1" value="<?= all_seed_dec($row_r["o_email1"]) ?>" />@<input type="text" name="email2" value="<?= all_seed_dec($row_r["o_email2"]) ?>" />
                </td>
            </tr>
        </table>
        <!-- (e) 상세영역 -->
    </form>

<script>


// var pop_certificate = window.open('about:blank', 'certificate');
var ff = document.frm_cert;

let date = new Date('2025-04-01'); 
let compareDate = new Date('<?= substr($row_L['regdate'], 0, 10) ?>');
let number = $("input[name='mobile']").val();
var urlAddr = "";
if (date > compareDate ) {
    urlAddr= "admin/mn1/popup_certificate_pdf.php";

} 
if(date <= compareDate ) {
    urlAddr= "admin/mn1/popup_certificate_pdf_renewal.php";
} 

ff.action = urlAddr;
ff.target = "";
ff.submit();

// window.close();

</script>

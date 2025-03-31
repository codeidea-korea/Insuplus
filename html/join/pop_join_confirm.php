<?php
include '../_include/_header.html';
include $_SERVER["DOCUMENT_ROOT"] . "/_config/Mobile_Detect.php";

$detect = new Mobile_Detect;


// 가입가입내역 검색
$SQL_V = "select * from tbl_order_list where orderno in (select orderno from tbl_order_list_join where o_phone = '" . $_SESSION["enc_hp"] . "' and o_isdn2='" . $_SESSION["enc_rnumber"] . "' and orderno='" . $orderno . "' and chk_join='N')";
//echo $SQL_V;
$RS_V = $dbcon->query($SQL_V);
if (!$RS_V) {
	echo "<script>alert('해당 가입내역이 없습니다.');</script>";
	exit;
}
$row_r = $dbcon->fetch_array($RS_V);

//가입자 정보 검색
$SQL_L = "select * from tbl_order_list_join where orderno='" . $orderno . "' AND seq = '" . $join_seq . "'";

$RS_L = $dbcon->query($SQL_L);
if (!$RS_V) {
	echo "<script>alert('해당 가입내역 가입자 정보가 없습니다.');</script>";
	exit;
}
$row_L = $dbcon->fetch_array($RS_L);

$o_name_en = ""; //영문명
if ($row_L["o_name_en"] && $row_L["chk_eng_passport"] == "Y") $o_name_en = all_seed_dec($row_L["o_name_en"]);
?>
<form name="frm_pdf" method="post">
	<input type="hidden" name="orderno" value="<?= $orderno ?>">
	<input type="hidden" name="join_seq" value="<?= $join_seq ?>">
	<input type="hidden" name="mode" value="" />
	<div class='row-border form-inline'>
		<div class='detail-col-label'>증권</div>
		<div class='detail-col-input'>
			<div class='radio radio-inline'>
				<input type="radio" name='chk_lang' id='stock_kr' value="K" checked />
				<label for='stock_kr'>국문</label>
			</div>
			<div class='radio radio-inline'>
				<input type="radio" name='chk_lang' id='stock_en' value="E" />
				<label for='stock_en'>영문</label>
			</div>
		</div>
		<div class='detail-col-label'>구분</div>
		<div class='detail-col-input'>
			<div class='radio radio-inline'>
				<input type="radio" name='certType' id='type_all' value="A" checked />
				<label for='type_all'>보험+서비스(전체)</label>
			</div>
			<div class='radio radio-inline'>
				<input type="radio" name='certType' id='type_no_service' value="I" />
				<label for='type_no_service'>보험</label>
			</div>
		</div>
		<div class='detail-col-label'>이름</div>
		<div class='detail-col-input'><?= all_seed_dec($row_L["o_name"]) ?></div>
		<div class='detail-col-label'>영문명</div>
		<div class='detail-col-input'>
			<? if ($o_name_en) { ?>
				<?= $o_name_en ?>
			<? } else { ?>
				<input type="text" name='o_name_en' onKeyup="fnChkEng(this)" placeholder="영문명을 입력해 주세요." class="form-control" size='20' value="" />
			<? } ?>
		</div>
	</div>
</form>
<div class='text-center m-t-3'>
	<a href='javascript: chk_submit();' class='btn btn-lg btn-theme-dark'><i class='ti ti-download'></i> 다운로드</a>
</div>
<script type="text/javascript">
	function chk_submit() {
		$("input[name='mode']").val('down');
		var ff = document.frm_pdf;
		<? if ($o_name_en == "") { ?>
			if (ff.chk_lang[1].checked == true && ff.o_name_en.value == "") {
				alert("영문이름을 넣어주세요");
				return;
			}
		<? } ?>
		<? if ($detect->isMobile()) { ?>
			var pop_title = "popupOpener";

			window.open("", pop_title, "width=100,height=100");
			ff.target = pop_title;
		<? } ?>
     

 
		const date = new Date('2025-04-01'); 
		const compareDate = new Date('<?=substr($row_L["regdate"],0,10)?>');
			
		const number = '<?=all_seed_dec($row_L["o_phone"]);?>'
			//가입일이 20250401 보다 이전이면
		if (date > compareDate ) {
			ff.action = "//<?= $_SERVER["HTTP_HOST"] ?>/admin/mn1/popup_certificate_pdf.php";

		} 
		if(date <= compareDate ||  number === '01049775976' || number === '01038585916' || number === '01020493619') {
			ff.action = "//<?= $_SERVER["HTTP_HOST"] ?>/admin/mn1/popup_certificate_pdf_renewal.php";
		} 
		ff.submit();
  
	}

	function fnChkEng(t) {
		$(t).val($(t).val().replace(/[0-9]|[^\!-z\s]/g, ""));
	}
</script>
<?php
include '../_include/_footer.html';
?>
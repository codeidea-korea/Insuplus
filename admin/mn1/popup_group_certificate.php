<!DOCTYPE html>
<?
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out); // 관리자 체크

?>
<html>

<head>
	<title>InsuPlus</title>
	<link href="/_css/admin.css" rel="stylesheet" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
</head>

<body>
	<div class="popupWrap">
		<header>
			<h1>가입증명원 재발송</h1>
			<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
		</header>
		<div class="popContWrap">
			<form name="frm_cert" id="frm_cert" method="post">
				<input type="hidden" name="group_join_id" value="<?= $gid ?>">
				<input type="hidden" name="mode" value="" />
				<!-- (s) 상세영역 -->
				<table class="adm-view-tb">
					<colgroup>
						<col width="16%">
						<col width="34%">
					</colgroup>
					<tr>
						<th>가입증명원</th>
						<td>
							<label for="korea">국문</label><input type="radio" name="chk_lang" id="korea" value="K" checked />
							<label for="english">영문</label><input type="radio" name="chk_lang" id="english" value="E" />
						</td>
					</tr>
					<tr>
						<th>증명서 유형</th>
						<td>
							<label for="insService">보장내역+서비스내역</label><input type="radio" name="certType" id="insService" value="A" checked />
							<label for="insure">보장내역</label><input type="radio" name="certType" id="insure" value="I"/>
							<label for="service">서비스내역</label><input type="radio" name="certType" id="service" value="S"/>
						</td>
					</tr>
					<tr>
						<th>구분</th>
						<td>
							<label for="assistance">코리아어시스턴스</label><input type="radio" name="chk_fly_type" id="assistance" value="A" checked />
							<label for="bizinsight">비즈인사이트</label><input type="radio" name="chk_fly_type" id="bizinsight" value="B" />
						</td>
					</tr>
				</table>
				<p class="txt_red">※ 영문 가입 증명원인 경우 영문명을 입력해주세요.</p>

				<div class="btnWrap">
					<div class="leftWrap"><a href="javascript:fnEmail();" class="btn_normal">이메일 발송</a></div>
				</div>
		</div>
		<!-- (e) 상세영역 -->
		</form>

	</div>
	</div>
</body>

</html>
<script type="text/javascript">
	function fnEmail() {
		$("input[name='mode']").val('email');
		var ff = document.frm_cert;

		var formData = $("#frm_cert").serialize();
		var urlAddr = "popup_certificate_pdf.php";
		$.ajax({
			url: urlAddr,
			data: formData,
			dataType: "json",
			type: "POST",
			success: function(d) {
				if (d.result == 1) {
					alert("이메일이 발송되었습니다.");
					location.reload();
				} else {
					alert("실행 중 실패했습니다.");
					return;
				}
			},
			error: function(e) {
				alert("실패했습니다. 관리자에게 문의해 주세요.");
				return;
			}
		});
	}
	//-->
</script>

<? $dbcon->dbcon_close(); ?>
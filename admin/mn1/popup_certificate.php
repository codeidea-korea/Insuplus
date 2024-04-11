<!DOCTYPE html>
<?
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out); // 관리자 체크

//가입자 정보 검색
$SQL_L = "select * from tbl_order_list_join where seq='" . $seq . "'";
//	echo $SQL_L;
$RS_L = $dbcon->query($SQL_L);
if (!$RS_L) {
	echo "<script>alert('해당 보험내역 가입자 정보가 없습니다.');</script>";
	exit;
}
$row_L = $dbcon->fetch_array($RS_L);

// 가입보험내역 검색
$SQL_V = "select * from tbl_order_list where orderno = '" . $row_L["orderno"] . "' ";
//echo $SQL_V;
$RS_V = $dbcon->query($SQL_V);
if (!$RS_V) {
	echo "<script>alert('해당 보험내역이 없습니다.');</script>";
	exit;
}
$row_r = $dbcon->fetch_array($RS_V);
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
				<input type="hidden" name="join_seq" value="<?= $seq ?>">
				<input type="hidden" name="orderno" value="<?= $row_L["orderno"] ?>">
				<input type="hidden" name="mode" value="" />
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
				<p class="txt_red">※ 영문 가입 증명원인 경우 영문명을 입력해주세요.</p>

				<div class="btnWrap">
					<div class="leftWrap"><a href="javascript:chk_certform('view');" class="btn_normal">가입증명원 확인</a></div><!-- 퍼블리셔한테 화면 받아야 할거 같습니다. -->
					<div class="rightWrap"><a href="javascript:;" onClick="fnKakaoCertificateReissue()" class="btn_kakao">알림톡 발송</a>
						<a href="javascript:;" onClick="javascript:chk_certform('email');" class="btn_normal">이메일 발송</a>
					</div>
				</div>
				<!-- (e) 상세영역 -->
			</form>

		</div>
	</div>
</body>

</html>
<script type="text/javascript">
	function fnKakaoCertificateReissue() { //카카오 알림톡
		var ff = document.frm_cert;

		if ($("input:radio[name='chk_lang']:checked").val() == "K") {
			if (!$("input[name='o_name']").val()) {
				alert("이름을 입력해 주세요.");
				$("input[name='o_name']").focus();
				return;
			}

		} else if ($("input:radio[name='chk_lang']:checked").val() == "E") {
			if (!$("input[name='o_name_en']").val()) {
				alert("영문 이름을 입력해 주세요.");
				$("input[name='o_name_en']").focus();
				return;
			}
		}

		if (!$("input[name='mobile']").val()) {
			alert("휴대폰번호를 입력해 주세요.");
			$("input[name='mobile']").focus();
			return;
		}

		var formData = $("#frm_cert").serialize();
		//알림톡 재전송 분리 20230717
		var urlAddr = "popup_resendAlimTalk.php";
		$.ajax({
			url: urlAddr,
			data: formData,
			dataType: "json",
			type: "POST",
			success: function(d) {
				if (d.result == "1") {
					alert("알림톡이 발송되었습니다.");
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
		})
	}

	//가입증명서 통합 $cert_type: (view, down, email)
	function chk_certform($cert_type) {
		$("input[name='mode']").val($cert_type);
		if($cert_type === 'email') {
			var ff = document.frm_cert;
			if (!$("input[name='o_name']").val()) {
				alert("이름을 입력해 주세요.");
				$("input[name='o_name']").focus();
				return;
			}

			if (!$("input[name='email1']").val() || !$("input[name='email2']").val()) {
				alert("이메일을 입력해 주세요.");
				$("input[name='email1']").focus();
				return;
			}
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
		} else {
			var pop_certificate = window.open('about:blank', 'certificate');
			var ff = document.frm_cert;
			ff.action = "popup_certificate_pdf.php";
			ff.target = "certificate";
			ff.submit();
		} 
	}
</script>

<? $dbcon->dbcon_close(); ?>
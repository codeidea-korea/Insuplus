<?
include_once $_SERVER["DOCUMENT_ROOT"] . "/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out); // 관리자 체크

$tm = "MN1";
$lm = "";
include $path_admin . "inc/header.php";

$parameter = "&pr_cd=" . $pr_cd . "&ins_cd=" . $ins_cd . "&plan_cd=" . $plan_cd . "&chk_service=" . $chk_service . "&search_text=" . $search_text . "&num_per_page=" . $num_per_page . "&search_date_s=" . $search_date_s . "&search_date_e=" . $search_date_e;

?>
<script>
	function list_go() {
		location.href = "group_join_list.php?<?= $parameter ?>";
	}

	function chk_write_go() {
		var date_pattern = /^(19|20)\d{2}(0[1-9]|1[012])(0[1-9]|[12][0-9]|3[0-1])$/;
		var email_check = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
		var ff = document.frm_group_join;

		if (!ff.ins_plan_name.value) {
			alert("플랜을 선택해 주세요.");
			ff.ins_plan_name.focus();
		} else if (ff.group_join_type.value == "B2C") {
			if (!ff.o_name_b2c.value) {
				alert("대표자를 입력해 주세요.");
				ff.o_name_b2c.focus();
				return false;
			} else if (!ff.birthdate.value) {
				alert("생년월일을 입력해 주세요.");
				ff.birthdate.focus();
				return false;
			} else if (!date_pattern.test(ff.birthdate.value)) {
				alert("올바른 생년월일을 입력해 주세요.");
				ff.birthdate.focus();
				return false;
			}
		} else if (ff.group_join_type.value == "B2B") {
			if (!ff.o_name_b2b.value) {
				alert("업체명을 입력해 주세요.");
				ff.o_name_b2b.focus();
				return false;
			}
		}

		if (!ff.email.value) {
			alert("이메일을 입력해 주세요.");
			return false;
		} else if (!email_check.test(ff.email.value)) {
			alert("잘못된 이메일 형식입니다.");
			return false;
		} else if (ff.o_phone.value == "") {
			alert("연락처를 입력해주세요.");
			ff.o_phone.focus();
			return false;
		}
		if ($('#file1').val() == "") {
			alert("엑셀 파일을 등록해주세요.");
			$('#file1').focus();
			return false;
		}
		var str = $('#file1').prop('files')[0]['name'];
		var fileFormat = str.split(".").pop().toLowerCase();
		if ($.inArray(fileFormat, ['xlsx', 'xls']) == -1) {
			alert('xlsx,xls 파일만 업로드 할수 있습니다.');
			$('#file1').focus();
			return;
		}

		ff.action = "./group_join_write_mod_ok.php?<?= $parameter ?>";
		ff.submit();
	}

	function fnCertificate() {
		var popCertificate = window.open('popup_ins_certificate.php?group_join_id=<?= $group_join_id ?>', 'popCertificate', 'top=0,left=0, width=700,height=500');
		popCertificate.focus();
	}

	function pop_plan() {
		var popPlan = window.open('popup_get_plan.php', 'popPlan', 'top=0,left=0, width=1155,height=765');
		popPlan.focus();
	}

	function pop_client() {
		var popPlan = window.open('popup_get_flying_client.php', 'popPlan', 'top=0,left=0, width=1155,height=765');
		popPlan.focus();
	}

	function fn_chg_type() {
		var ff = document.frm_group_join;

		if (ff.group_join_type.value == 'B2C') {
			$("#b2b").css("display", "none");
			$("#b2c").css("display", "");
		} else {
			$("#b2b").css("display", "");
			$("#b2c").css("display", "none");
		}
	}
</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?= $url_admin ?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">단체가입</td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>
<form name="frm_group_join" method="post" enctype="multipart/form-data">
	<input type="hidden" name="mode" value="write">
	<input type="hidden" name="agree_cd" value="">
	<input type="hidden" name="chk_period" value="">
	<input type="hidden" name="chk_service" value="">
	<input type="hidden" name="ins_cd" value="">
	<input type="hidden" name="pr_cd" value="">
	<input type="hidden" name="plan_cd" value="">
	<input type="hidden" name="plan_seq" value="">
	<input type="hidden" name="service_cd" value="">
	<input type="hidden" name="stock_isdn" value="">
	<input type="hidden" name="client_id" value="">
	<input type="hidden" name="biz_num" value="">
	<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td>
				<div class="btnWrap">
					<a href="javascript:list_go();" class="btn_list">목록</a>
					<div style="float: right;">
						<p style="float: left;">견적상태</p>
						<select name="group_join_status" style="margin: 10px;">
							<option value="">상태변경</option>
							<option value="W" <? if ($row_group_info["group_join_status"] == "W") { ?>selected<? } else {
																											} ?>>입금대기</option>
							<option value="Y" <? if ($row_group_info["group_join_status"] == "Y") { ?>selected<? } else {
																											} ?>>가입완료</option>
						</select>

						<a href="javascript: chk_write_go();" class="btn_add">저장</a>
					</div>
					<!-- (s) 상세화면  -->
					<div class="btnSubTitWrap" style="margin:0 0 10px 0;">
						<p class="tit_sub">단체가입(개별)</p>
					</div>

					<table class="adm-view-tb">
						<colgroup>
							<col width="8%">
							<col width="42%">
							<col width="8%">
							<col width="42%">
						</colgroup>
						<tr>
							<th>상품</th>
							<td><input type="text" name="pr_name" id="pr_name" value="" readonly /></td>
							<th>플랜</th>
							<td><input type="text" name="ins_plan_name" id="ins_plan_name" value="" readonly /><a class="btn-form-normal" href="javascript:;" onclick="pop_plan();">찾기</a></td>
						</tr>
						<tr>
							<th>보험사</th>
							<td><input type="text" name="ins_name" id="ins_name" value="" readonly /></td>
							<th>고객구분</th>
							<td>
								<select name="group_join_type" style="margin: 10px;" onchange="fn_chg_type()">
									<option value="B2B" selected>B2B</option>
									<option value="B2C">B2C</option>
								</select>
							</td>
						</tr>
						<tr id="b2c" style="display: none;">
							<th>대표자</th>
							<td>
								<input type="text" name="o_name_b2c" id="o_name_b2c" value="" placeholder="한글명" />
							</td>
							<th>생년월일</th>
							<td><input type="text" name="birthdate" id="birthdate" placeholder="ex) 20210101" value="" /></td>
						</tr>
						<tr id="b2b">
							<th>업체</th>
							<td>
								<input type="text" name="o_name_b2b" id="o_name_b2b" value="" readonly placeholder="한글명" />
								<input type="text" name="o_name_en_b2b" id="o_name_en_b2b" value="" readonly placeholder="영문명" />
								<a class="btn-form-normal" href="javascript:;" onclick="pop_client();">찾기</a>
							</td>
							<th>프로젝트</th>
							<!-- <td><input type="text" name="grp_cd" id="grp_cd" value=""/></td> -->
							<td>
								<select name="grp_cd" id="grp_cd">
									<option value="P0000000000" selected>플라잉닥터스</option>
									<option value="P0000000001">인슈플러스</option>
									<option value="P0000000002">우리말도우미</option>
									<option value="P0000000003">PTI</option>
									<option value="P0000000004">I/B 유학생보험</option>
									<option value="P0000000005">인바운드 케이스</option>
								</select>
							</td>
						</tr>
						<tr>
							<th>대표자 이메일</th>
							<td><input type="text" name="email" id="email" placeholder="ex) admin@insuplus.co.kr" value="" /></td>
							<th>대표자 연락처</th>
							<td><input type="text" name="o_phone" id="o_phone" placeholder="ex) 01012345678" value="" /></td>
						</tr>
						<tr>
							<th>명단등록</th>
							<td colspan="2">
								<input type="file" id="file1" name="file1">
								<a href="/_data/관리자_등록.xlsx"><u>등록양식 다운로드</u></a>
							</td>
						</tr>
					</table>
</form>
<? if (1 + 1 == 3) { ?>
	<div class="btnSubTitWrap">
		<p class="tit_sub">- 가입인원 <? ?>명 | 결제금액 <? ?>원</p>
	</div>

	<table class="adm-view-tb">
		<colgroup>
			<col width="8%">
			<col width="42%">
			<col width="8%">
			<col width="42%">
		</colgroup>
		<tr>
			<th>피보험자/계약자 명</th>
			<td><?= all_seed_dec($row["o_name"]) ?></td>
			<th>동반인 수<br />(계약자 포함)</th>
			<td><?= $row["join_cnt"] ?>명</td>
		</tr>
		<tr>
			<th>결제금액</th>
			<td class="r"><?= number_format($row["t_amount"]) ?>원</td>
			<th>가입상태</th>
			<td><?= $arr_join_step[$row_L["join_status"]] ?></td>
		</tr>
		<tr>
			<th>총 상품가</th>
			<td class="r"><?= number_format($row_L["join_amount"] + $row_L["join_service"]) ?>원</td>
			<th>결제 취소일</th>
			<td><? if ($row["cancle_date"] != "0000-00-00 00:00:00") { ?><?= $row["cancle_date"] ?><? } ?></td>
		</tr>
		<tr>
			<th>할인금액</th>
			<td class="r"><?= number_format($row["s_amount"]) ?>원</td>
			<th>결제 취소금액</th>
			<td class="r"><?= number_format($row["cancle_amount"]) ?>원</td>
		</tr>
	</table>
<? } ?>
<!-- (e) 상세화면  -->
</td>
</tr>
</table>





<? include $path_admin . "inc/footer.php"; ?>
<? $dbcon->dbcon_close(); ?>
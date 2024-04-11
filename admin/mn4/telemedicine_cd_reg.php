<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$tm = "MN4";
	$lm = "";
	include $path_admin."inc/header.php";

	$expireArr = [1,2,3,4,5,6,7,8,9,10,11,12];	//만료기간

?>
<script type="text/javascript">
 $(document).ready(function() {
	$("input:text[numberOnly]").on("keyup", function() {
		$(this).val(addCommas($(this).val().replace(/[^0-9]/g,"")));
	});
	$("input:text[numberOnly]").on("keyup", function() {
		$(this).val(addCommas($(this).val().replace(/[^0-9]/g,"")));
	});
	$("input:text[numberOnly]").on("keyup", function() {
		$(this).val(addCommas($(this).val().replace(/[^0-9]/g,"")));
	});

var clareCalendar = {<?=$calendar_opt?>});
</script>
<script>
	function list_go() {
		location.href = "telemedicine_cd_history_list.php";
	}

	//3자리 단위마다 콤마 생성
	function addCommas(x) {
		return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}
	
	//모든 콤마 제거
	function removeCommas(x) {
		if(!x || x.length == 0) return "";
		else return x.split(",").join("");
	}

</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">원격진료 코드 추가</td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<!-- (s) 상세화면  -->
			<div class="btnSubTitWrap">
				<a href="javascript: fnInsertTelCd();" class="btn_add">코드 추가</a>
			</div>
			<form name="frm_mod" id="frm_mod" method="post" enctype="multipart/form-data">
				<input type="hidden" name="mode" />
				<table class="adm-view-tb">
					<colgroup>
					<col width="8%">
					<col width="42%">
					</colgroup>
					<tr>
						<th>만료기간</th>
						<td>
							<select id="expire_date" name="expire_date">
							<option value="">선택하세요</option>
							<?
								foreach($expireArr as $item) {
							?>
							<option value="<?=$item?>"><?=$item?>개월</option>
							<?
								}
							?>
							</select>
						</td>
					</tr>
					<tr>
						<th>추가할 코드 개수</th>
						<td>
							<input type="text" id="telemedicine_cd" name="telemedicine_cd" value="" class="onlyNumber" maxlength="3" numberOnly/>
							<span style="color: red; font-size:medium">주의!! 원격진료 코드 등록은 인슈플러스에서만 추가 생성되며, 원격진료 코드 발급처에 코드 발급 요청을 해야 합니다.</span>
						</td>
					</tr>
				</table>
			</form>
			<div class="btnWrapC">
				<a href="javascript:list_go();" class="btn_list">목록</a>
			</div>

			<!-- (e) 상세화면  -->
		</td>
	</tr>
</table>

<? include $path_admin."inc/footer.php"; ?>
<script>

function fnInsertTelCd() {

	var ff = document.frm_mod;
	
	if($('#expire_date').val() == '') {
		alert('만료기간을 선택해 주세요.');
		$('#expire_date').focus();
		return false;
	} else if($('#telemedicine_cd').val() < 1) {
		alert('추가할 코드 개수를 입력해 주세요.');
		$('#telemedicine_cd').focus();
		return false;
	}
	
	if(confirm("원격진료 코드를 추가 하시겠습니까?")) {
		$("input[name='mode']").val("insert");
		var formData = $("#frm_mod").serialize();
		
		$.ajax({
				url:"telemedicine_cd_reg_mod_ok.php"
				,data:formData
				,dataType:"json"
				,type:"POST"
				,success:function(d) {
					console.log(d);
					if(d.result == "1") {
						alert("원격진료 코드가 등록되었습니다.");
						//list_go();
					} else {
						alert("실행중 실패했습니다.");
						return;
					}	
				}, error:function(e) {
					alert("실패했습니다. 관리자에게 문의해 주세요.");
					return;
				}
			})
	}
}
</script>
<? $dbcon -> dbcon_close();?>

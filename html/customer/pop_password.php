<?
	include '../_include/_header.html';
?>
<div class='text-center m-b-1 text-black'>
	<img src="../images/ic-password.svg" align="absmiddle" alt="" class="m-b-2" /><br>
	<h5>비밀번호를 입력해 주세요.</h5>
</div>
<form name="WriteForm" id="WriteForm" onsubmit="return false">
<input type="hidden" name="mode" />
<input type="hidden" name="seq" value="<?=$seq?>" />
	<div class='row'>
		<div class='col-xs-8 col-xs-offset-2'>
			<input type="password" name='customer_password' placeholder="비밀번호" class="form-control" size='20' onkeypress="if(event.keyCode==13){frontChkPassword();}"/>
		</div>
	</div>
	<div class='row m-t-3'>
		<div class='col-xs-6'>
			<a class='btn btn-block btn-theme-dark light'  data-toggle="close-modal" aria-hidden="true">돌아가기</a>
		</div>
		<div class='col-xs-6'>
			<a href="javascript:;" onClick="frontChkPassword()" class='btn btn-block btn-theme-bg'><i class='ti ti-check'></i> 확인</a>
		</div>
	</div>
</form>
<?
	include '../_include/_footer.html';
	
	echo $parameter;
?>
<script>	
function frontChkPassword() {
	var ff = document.WriteForm;
	if(!ff.customer_password.value) {
		alert("비밀번호를 입력해 주세요.");
		ff.customer_password.focus();
		return;
	}

	ff.mode.value = "check";

	var params = jQuery("#WriteForm").serialize();
	var request = $.ajax({
		type : "POST",
		url : "./pop_password_ajax.php",
		cache : false,
        data:params,
        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
		dataType: 'json',
		success: function (d) {
			if(d.result=="1") {
				parent.frontViewGO('<?=$seq?>');
			} else {
				alert("비밀번호를 다시 확인해 주세요.");
				return false;
			}
		},
		error : function(xhr, status, error) {
			alert("실패했습니다. 관리자에게 문의하십시오.");
			return false;
		}
	});
	request.done(function(result) {

	});
}


</script>


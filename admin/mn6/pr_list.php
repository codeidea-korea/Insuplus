<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$tm = "MN6";
	$lm = "";
	include $path_admin."inc/header.php";
?>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">상품관리</td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>

		<?php
        // echo $path_board;// /app/projects/insuplus/_util/board/
			$bc_id = "product";
			$sorder=" sort_order desc ,seq desc";
			include_once $path_board."board.php";
			$dbcon -> dbcon_close();
		?>
		</td>
	</tr>
</table>

<script type="text/javascript">
<!--
function pop_nation(seq){
  window.open("/_skin/board/product/pop_nation.php?pr_seq="+seq,"_pop","width=700,height=900");
}

function pop_service(){
  window.open("/_skin/board/product/pop_service.php","_pop","width=600,height=600");
}

function pop_guarantee(selector){
  window.open(`/_skin/board/product/pop_guarantee.php?selector=${selector}`,"_pop","width=600,height=600");
}

$( document ).ready( function() {
  // 라인삭제
  $(document).on("click",".delrow",function(){
    $(this).closest("tr").remove();
  });

  // 보험사 라인생성
  $("#addTR").click(function () {
    var length = Number($("#arr_num1").val())+1;
    var row = "<tr>";
    row += "<td>"+Number(length)+"</td>";
    row += "<td><select name='ins_seq["+length+"]' class='select'>";
    row += "<option value=''>:: 선택 ::</option>";
    row += "<option value='00'>:: 없음 ::</option>";
        row += "<option value='6'>DB손해보험</option>";
        row += "<option value='7'>extra-insurance</option>";
        row += "<option value='2'>MG손해보험</option>";
        row += "<option value='5'>메리츠화재해상보험</option>";
        row += "<option value='8'>신한EZ손해보험</option>";
        row += "<option value='4'>한화손해보험</option>";
        row += "<option value='3'>현대해상화재보험</option>";
        row += "</select></td>";
    row += "<td>";
    row += "<input type='radio' name='service_gubun["+length+"]' value='A' >A타입";
    row += "<input type='radio' name='service_gubun["+length+"]' value='B' >B타입";
        row += "<input type='radio' name='service_gubun["+length+"]' value='C' >인슈플러스";
          row += "<input type='radio' name='service_gubun["+length+"]' value='D' >플라잉닥터스";
          row += "<input type='radio' name='service_gubun["+length+"]' value='E' >플라잉닥터스B";
          row += "<input type='radio' name='service_gubun["+length+"]' value='N' >없음";
          //row += "<td><input type='radio' name='service_gubun["+length+"]' value='A'>A타입     <input type='radio' name='service_gubun["+length+"]' value='B'>B타입    <input type='radio' name='service_gubun["+length+"]' value='N' checked> 없음</td>";
    row += "<td><span class='inp_orange'><input type='button' value='삭제' class='delrow'></span></td>";
    row += "</tr>";
    $("#service_tb").append(row);
    $("#arr_num1").val(length);
  });


  // 보험사 라인생성
  $("#addNotice").click(function () {
    var length = Number($("#arr_num2").val())+1;
    var row = "<tr>";
    row += "<td>"+Number(length)+"</td>";
    row += "<td><input type='text' name='pr_notice["+length+"]' value='' class='input' style='width:100%;'></td>";
    row += "<td><span class='inp_orange'><input type='button' value='삭제' class='delrow'></span></td>";
    row += "</tr>";
    $("#notice_tb").append(row);
    $("#arr_num2").val(length);
  });
});
//-->
</script>

<? include $path_admin."inc/footer.php"; ?>

<? $dbcon -> dbcon_close();?>

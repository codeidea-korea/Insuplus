<?php
	include '../_include/_header.html';
?>
<form name="frm_search_cn" method="get" action="./pop_search_country.php">
<input type="hidden" name="pr_cd" value="<?=$pr_cd?>">
<input type="hidden" name="pop_c_name" />
<input type="hidden" name="pop_c_code" />
<div class='m-b-15 p-a-2 bg-light-yellow text-black'>
	※여행가는 나라의 여행 제한/금지 여부를 확인하세요. <br>
	외교부 지정 적색/흑색경보(철수권고/여행금지) 국가로 여행하는 경우 보험가입과 보상이 불가합니다.
	<div class='clearfix m-t-1'>
		<a href='https://www.0404.go.kr/dev/issue_current.mofa?level=limit' target="_blank" class='btn btn-md btn-theme-bg p-x-3 pull-left'>여행제한 / 금지구역</a>
		<div class='checkbox checkbox-inline pull-right'>
			<input type="checkbox" name='check1' id='check1' checked/>
			<label for='check1'>확인</label>
		</div>
	</div>
</div>
<div class='input-group'>
	<input type="text" name='country' placeholder="방문국가를 검색해주세요." class="form-control" size='20' value="<?=$country?>"/>
	<span class='input-group-btn'><button type='submit' class='btn btn-default' data-toggle='pop-modal' data-size='sm' data-target='./pop_search_country.php' data-title='방문국가 검색'>방문국가검색</button>
</div>
</form>
<?
if ($country){$add_sql = " and c_name like '%".$country."%' ";}
$SQL_CN = "select * from tbl_board_product_country where pr_seq=".$pr_cd." ".$add_sql." ORDER BY c_name ASC";
//echo $SQL_CN;
$result_PL = $dbcon -> query($SQL_CN);
?>
<script type="text/javascript">
//<!--
function sel_county(c_name,c_code,idx){
	var form = frm_search_cn;
	console.log($("#btn_"+idx));
	$(".table .btn-md").addClass("btn-default");
	$(".table .btn-md").removeClass("btn-theme-bg");
	$("#btn_"+idx).removeClass("btn-default");
	$("#btn_"+idx).addClass("btn-theme-bg");
	form.pop_c_name.value = c_name;
	form.pop_c_code.value = c_code;
	fnSend();
}

function fnSend() {
	if(!$("#check1").is(":checked")) {
		alert("여행제한/금지구역을 확인 후 선택해 주세요.");
		$("#check1").focus();
		return;
	}
	var form = frm_search_cn;
	if(!form.pop_c_name.value) {
		alert("여행하실 국가를 선택해 주세요.");
		return;
	}
	
	var ff = parent.document.frm2;
	ff.c_name.value=form.pop_c_name.value;
	ff.c_code.value=form.pop_c_code.value;
	
	$('.modal-header button', parent.document).click();
}
//-->
</script>
<h5 class='text-black'>※여러 국가 방문시, 첫 번째 체류국가 선택</h5>
<h5 class='text-black m-t-2 m-b-1'>국가명</h5>
	<table width="100%" class="table table-bordered" summary="">
		<colgroup>
			<col class=' col-xs-6' />
			<col class=' col-xs-6' />
		</colgroup>
	</table>
<div class="table-box-wrap">
	<div class="table-box">
		<table width="100%" class="table table-bordered" summary="">
			<colgroup>
				<col class=' col-xs-6' />
				<col class=' col-xs-6' />
			</colgroup>
			<thead>
				<tr>
					<th class='bg-light border-b-2 border-default p-y-1 col-xs-6'>국가명</th>
					<th class='bg-light border-b-2 border-default p-y-1 col-xs-6'>여행가능</th>
				</tr>
			</thead>
			<tbody class='text-center text-black'>
				<?
				$k = 1;
				while($row_PL = $dbcon -> fetch_array($result_PL)){?>
					<tr>
						<td class='p-y-05'><?=$row_PL["c_name"]?></td>
						<td class='p-y-05'><?if ($row_PL["trip_yn"]=="가능"){?>
							<a href="javascript:sel_county('<?=$row_PL["c_name"]?>','<?=$row_PL["c_code"]?>','<?=$k?>')" id="btn_<?=$k?>"  class='btn btn-md btn-default p-x-3'>선택</a>
							<?}else{?>
							여행불가
							<?}?>
						</td>
					</tr>
				<? $k++;
				} ?>
			</tbody>
		</table>
	</div>
</div>
<!-- <div class='text-center m-t-3'>
	<a class='btn btn-theme-dark p-x-5'  aria-hidden="true" onClick="fnSend();">확인</a>
</div> -->
<?php
	include '../_include/_footer.html';
?>


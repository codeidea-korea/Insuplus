<?php
	include '../_include/_header.html';
	include '../_include/_top.html';
	include '../_include/_sidebar.html';
?>
<?php
$orderno	= $_REQUEST["orderno"];		//주문번호
//$chk1	= $_REQUEST["chk1"];			//핸드폰번호
//$chk2	= $_REQUEST["chk2"];			//주민등록번호

if (!$orderno || !$chk1 || !$chk2){
	echo "<script>alert('잘못된 경로로 입장하셨습니다.');</script>";
	exit;
}

// 가입가입내역 검색
$SQL_V = "select * from tbl_order_list where orderno in (select orderno from tbl_order_list_join where o_phone = '".$_SESSION["enc_hp"]."' and o_name='".$_SESSION["enc_nm"]."' and orderno='".$orderno."' and chk_join='N')";
$RS_V = $dbcon -> query($SQL_V);
if (!$RS_V){
	echo "<script>alert('해당 가입내역이 없습니다.');</script>";
	exit;
}
$row_r = $dbcon -> fetch_array($RS_V);

if($row_r["chk_p"] == "Y") { //단기
	$s_date = $row_r["s_date"]." ".$row_r["s_date_time"];
	$e_date = $row_r["e_date"]." ".$row_r["e_date_time"];
	$today = date("Y-m-d H");
} else if($row_r["chk_p"] == "N") { //장기
	$s_date = $row_r["s_date"];
	$e_date = $row_r["e_date"];
	$today = date("Y-m-d");
}

// 플랜검색
$SQL_PLAN = "select * from tbl_board_plan where seq=" . $row_r["plan_cd"] . "";
$RS_PLAN = $dbcon->query($SQL_PLAN);
$row_plan = $dbcon->fetch_array($RS_PLAN);

//보험약관 파일 확인
$arr_ins_agree_file = selInsAgreeFile($row_r["ins_file_cd"]);

//인슈플러스 서비스 약관 파일 확인
$arr_service_file = selServiceFile($row_r["service_file_cd"]);

//가입자 정보 검색
$SQL_L = "select * from tbl_order_list_join where orderno='".$orderno."'";
$RS_L = $dbcon -> query($SQL_L);
if (!$RS_V){
	echo "<script>alert('해당 가입내역 가입자 정보가 없습니다.');</script>";
	exit;
}
?>
		<div class="breadcrumb-image">
			<div class="container">
				<h2>가입확인</h2>
				<h4>가입내역 및 쿠폰내역을 확인해 주세요</h4> 
			</div>
		</div> 
		<div class="breadcrumb-wrap">
            <div class="container">
				<ol class="breadcrumb">
					<li><a href="../main/index.php">InsuPlus HOME</a></li>
					<li>가입확인</li>
				</ol>
            </div>
        </div>
		<div class="container">
			<div class="sub-content info-wrap">
				<h4 class='text-black m-b-1'>가입내역</h4>
				<div class='row-border border-navy responsive'>
					<div class='detail-col-label bg-navy'>가입기간</div>
					<div class='detail-col-input colspan-3 flex-wrap'><?=$row_r["s_date"]?> <?if($row_r["s_date_time"]){?><?=$row_r["s_date_time"]?>시<?}?> ~ <?=$row_r["e_date"]?> <?if($row_r["e_date_time"]){?><?=$row_r["e_date_time"]?>시<?}?> (<?=$row_r["ins_period"]?><?=$arr_chk_p_gubun[$row_r["chk_p"]]?>)</div>
					<div class='detail-col-label bg-navy'>상품명</div>
					<div class='detail-col-input'><?=$row_r["pr_name"]?></div>
					<div class='detail-col-label bg-navy'>보험사</div>
					<div class='detail-col-input'><?=$row_r["ins_cd"] > 0 ? print_ins($row_r["ins_cd"]) : print_ins($row_plan["guarantee1_ins_seq"]);?></div>
					<div class='detail-col-label bg-navy'>플랜명</div>
					<div class='detail-col-input'><?=$row_r["plan_name"]?></div>
					<div class='detail-col-label bg-navy'>가입자</div>
					<div class='detail-col-input'><?=all_seed_dec($row_r["o_name"])?> <?if ($row_r["join_cnt"]>1){?>외 <?=$row_r["join_cnt"]-1?>명<?}?></div>
					<div class='detail-col-label bg-navy'>가입일</div>
					<div class='detail-col-input'><?=substr($row_r["writedate"],0,10)?></div>
					<div class='detail-col-label bg-navy'>결제상태</div>
					<div class='detail-col-input'>
						<? if($row_r["order_step"] == "N" || $row_r["order_step"] == "P") {?>
							<span class="text-danger"><?=$arr_ord_step[$row_r["order_step"]]?></span>
						<? } else {?>
							<?=$arr_ord_step[$row_r["order_step"]]?>
						<? } ?>
					</div>
					<div class='detail-col-label bg-navy'>결제금액</div>
					<div class='detail-col-input'><?=number_format($row_r["t_amount"])?>원</div>
				</div>
				<div class='row m-y-2'>
					<div class='col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0'>
						<div class='btn-group btn-group-justified btn-group-noborder'>
							<?if ($arr_ins_agree_file){?>
							<span class='btn-group'>
							<a class='btn btn-lg btn-block btn-default text-left' onclick="fnDown('<?=all_seed_enc("/_data/board/ins_agree/".$arr_ins_agree_file["file_realname"])?>','<?=$arr_ins_agree_file["file_name"]?>','<?=$arr_ins_agree_file["file_size"]?>');">보험약관<i class='ti ti-download pull-right'></i></a>
							</span>
							<?}?>
							
							<?if ($arr_service_file){?>
							<span class='btn-group'>
							<a class='btn btn-lg btn-block btn-default text-left' onClick="fnDown('<?=all_seed_enc("/_data/board/service_agree/".$arr_service_file["file_realname"])?>','<?=$arr_service_file["file_name"]?>','<?=$arr_service_file["file_size"]?>')">서비스 이용약관<i class='ti ti-download pull-right'></i></a>
							</span>
							<?}?>

						</div>
					</div>
				</div>
				<h4 class='text-black m-b-1'>가입자정보</h4>
				<table width="100%" class="table table-bordered table-break table-form" summary="">
					<thead>
						<tr>
							<th>이름</th>
							<th>계약자/동반인</th>
							<th>상품가격</th>
							<th>할인금액</th>
							<th>결제금액</th>
							<th>결제상태</th>
							<th>결제취소</th>
							<th>가입증명서</th>
						</tr>
					</thead>
					<tbody class='text-center'>
						<?while($row_L = $dbcon -> fetch_array($RS_L)){
							
						?>
						<tr>
							<td data-title='이름'><?=all_seed_dec($row_L["o_name"])?></td>
							<td data-title='계약/동반'><?if ($row_L["chk_join"]=="N"){?>계약자<?}else{?>동반인<?}?></td>
							<td class='text-right p-r-1' data-title='상품가격'><?=number_format($row_L["join_amount"]+$row_L["join_service"])?>원</td>
							<td class='text-right p-r-1' data-title='할인금액'><?=number_format($row_L["s_amount"])?>원</td>
							<td class='text-right p-r-1' data-title='결제금액'><?=number_format($row_L["t_amount"])?>원</td>
							<td data-title='결제상태'>
                                
								<?
;
                                
                                if($row_L["join_status"] == "N") {?>
									<span class="text-danger"><?=$arr_join_step[$row_L["join_status"]]?></span>
								<? } else {?>
									<?=$arr_join_step[$row_L["join_status"]]?>
								<? } ?>	
							</td>
							<td class='p-a-05'>
								<?if ($row_L["join_status"]=="Y" && $row_r["join_cnt"]>1 && $s_date > $today && $row_r["pg_pay_type"] != 'VBank' && $row_r["pg_pay_type"] != 'VBANK' && $row_r["pg_pay_type"] != 'transfer'){?>
								<a class='btn btn-block btn-notice' onclick="ord_PCancle('<?=$row_L["seq"]?>','<?=$row_L["orderno"]?>')">부분취소</a>
								<?}?>
							</td>
							<td class='p-a-05'>
								<?if ($row_L["join_status"]=="Y"){?>
								<a class='btn btn-block btn-theme-dark light' data-toggle='pop-modal' data-size='sm' data-href='./pop_join_confirm.php?orderno=<?=$orderno?>&join_seq=<?=$row_L["seq"]?>&chk_service=<?=$row_r["chk_service"]?>' data-title='가입증명서 다운로드' target='modal_iframe'>가입증명서</a>
								<?}?>
							</td>
						</tr>
						<?}?>
					</tbody>
				</table>
				<div class='text-danger m-t-1'>
					※ 개시일 전에는 결제취소 가능합니다.
					<?if ($row_r["order_step"] == "2" && $s_date>$today && $row_r["pg_pay_type"] != 'VBank'){?>
					<a class='btn btn-default pull-sm-right' href="javascript: ord_Cancle('<?=$orderno?>');">전체결제취소</a>
					<?}?>
				</div>
				<h4 class='text-black m-t-3 m-b-1'>결제정보</h4>
				<div class='clearfix'>
					<div class='row-border responsive'>
						<div class='detail-col-label'>결제정보</div>
						<div class='detail-col-input colspan-3 text-left'> 
                        
                        <? 
                      

                            $pay_name =  all_seed_dec($row_r["pay_name"]);
                            
                            if( explode(" / ",$pay_name)[0] === 'CARD' ){
                                echo implode(" / ", array_slice(explode(" / ", $pay_name), 0, 2));

                            }else{
                                echo $pay_name;
                            }
                        ?>
                        </div>
						<div class='detail-col-label'>상품가격</div>
						<div class='detail-col-input colspan-3 text-right'>
							<div class='clearfix text-right'><h4><span class='text-black'><?=number_format($row_r["ins_amount"]+$row_r["service_amount"])?></span> <small>원</small></h3></h4></div>
						</div>
						<div class='detail-col-label'>할인금액</div>
						<div class='detail-col-input colspan-3 text-right'>
							<div class='clearfix text-right'><h4><span class='text-black'><?=number_format($row_r["s_amount"])?></span> <small>원</small></h3></h4></div>
						</div>
						<div class='detail-col-label'>결제취소금액</div>
						<div class='detail-col-input colspan-3 text-right'>
							<div class='clearfix text-right'><h4><span class='text-black'><?=number_format($row_r["cancle_amount"])?></span> <small>원</small></h3></h4></div>
						</div>
						<div class='detail-col-label'>결제금액</div>
						<div class='detail-col-input colspan-3 text-right'>
							<div class='clearfix text-right'><h3><span class='text-danger'><?=number_format($row_r["t_amount"])?></span> <small>원</small></h3></div>
						</div>
					</div>
				</div>
				<div class='row m-t-3'>
				<div class='col-md-4 col-sm-6 col-xs-6 col-md-offset-4 col-sm-offset-3 col-xs-offset-3'>
					<a class='btn btn-block btn-theme-dark' href='./join_confirm_result.php'>목록</a>
				</div>
				</div>
			</div>
        </div>
<form name="frm_cancle" method="post">
<input type="hidden" name="orderno" value="">
<input type="hidden" name="mode" value="">
<input type="hidden" name="seq" value="">
</form>
<form name="downForm" id="downForm" method="post"> 
	<input type="hidden" name="mode" value="down" />
	<input type="hidden" name="file" value="" />
	<input type="hidden" name="filename" value="" />
	<input type="hidden" name="filesize" value="" />
</form>
<div style="display:none"><iframe src="" width="0" height="0" frameborder="0" name="ifrm_act"></iframe></div>
<script type="text/javascript">

function ord_Cancle(val){
	<? if($row_r["pg_pay_type"] != 'VBank') {?>
	if (confirm("취소하시겠습니까? 신중히 고려 부탁드립니다.\n" )){ 
		
		if(val.startsWith("TOSS")) {
			var ff = document.frm_cancle;
			ff.orderno.value=val;
			ff.action = "toss_join_confirm_view_cancle_all.php";
			ff.target="ifrm_act";
			ff.submit();
		}else{

			var ff = document.frm_cancle;
			ff.orderno.value=val;
			ff.action = "join_confirm_view_cancle_all.php";
			ff.target="ifrm_act";
			ff.submit();
		}
	}
	<?} else {?> 
		let text = '가상계좌 환불은 인슈플러스 고객센터로 문의해 주세요. \n카카오톡 "인슈플러스", 전화 02-360-2545';
		alert(text);
	<? } ?>
}
function ord_PCancle(seq,val){
	if (confirm("취소하시겠습니까? 신중히 고려 부탁드립니다.")){

		if(val.startsWith("TOSS")) {
			var ff = document.frm_cancle;
				ff.mode.value="repay";
				ff.orderno.value=val;
				ff.seq.value=seq;
				ff.action = "toss_join_confirm_view_cancle_part.php";
				ff.target="ifrm_act";
				ff.submit();
		}else{

			var ff = document.frm_cancle;
				ff.mode.value="repay";
				ff.orderno.value=val;
				ff.seq.value=seq;
				ff.action = "join_confirm_view_cancle_part.php";
				ff.target="ifrm_act";
				ff.submit();
		}

	}
}
//보험약관
function fnDown(file, filename, file_size) {
	$("#downForm input[name='file']").val(file);
	$("#downForm input[name='filename']").val(filename);
	$("#downForm input[name='filesize']").val(file_size);
	$("#downForm").attr("action","fileDown.php").submit();
}

</script>
<?php
	include '../_include/_tail.html';
	include '../_include/_footer.html';
?>


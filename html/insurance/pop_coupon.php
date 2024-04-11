<?php
	include '../_include/_header.html';
?>
<?php
$user_hp = $usr_cd ;	// 회원전화번호
if (!$user_hp){
	echo "<script>alert('잘못된 경로로 들어오셨습니다.');location.href='/html/main/index.php';</script>";
	exit;
}

$today = date("Y-m-d");
//쿠폰 검색
$SQL_CP =  " select (case WHEN B.partner_coupon IS NULL then A.subject else A.partner_coupon_name end) as subject, B.seq,B.temp_discount,B.start_date,B.end_date, B.event_seq from ";
$SQL_CP .= " tbl_board_event A inner join tbl_board_coupon_history B on A.seq=B.event_seq  "; 
//$SQL_CP .= " where B.mobile = '".$user_hp."' and B.use_yn='N' and B.start_date <='".$s_date."' and B.end_date >='".$s_date."'";
//결제일 기준 쿠폰조회 추후 쿠폰 전체 조회 후 사용가능한 쿠폰만 선택되도록 변경 예정
$SQL_CP .= " where B.mobile = '".$user_hp."' and B.use_yn='N' and B.start_date <='".$today."' and B.end_date >='".$today."'";

if($_SESSION["ss_partner_seq"]){
	$SQL_CP .= "and A.event_partnership_code = (SELECT partnership_code FROM tbl_board_partner WHERE seq = '".$_SESSION["ss_partner_seq"]."' 
				AND start_Partner_period <= '".$today."' AND end_Partner_period >= '".$today."' ORDER BY seq ASC limit 0,1)";
}

$result_cp = $dbcon -> query($SQL_CP);
?>
<script type="text/javascript">

function sel_cp(seq){
	var chk = "1";
	window.parent.chg_fund(chk,seq);
}

</script>
<table width="100%" class="table table-bordered" summary="">
	<colgroup>
		<col width='30%'/>
		<col width='15%'/>
		<col width='*'/>
		<col width='15%'/>
	</colgroup>
	<thead>
		<tr>
			<th class='bg-light p-y-1'>쿠폰명</th>
			<th class='bg-light p-y-1'>할인율</th>
			<th class='bg-light p-y-1'>사용가능 기간</th>
			<th class='bg-light p-y-1'>선택</th>
		</tr>
	</thead>
	<tbody class='text-center text-black'>
		<?
		if($result_cp) {
		while($row_cp = $dbcon -> fetch_array($result_cp)){?>
		<tr>
			<td><?=$row_cp["subject"]?></td>
			<td><?=number_format($row_cp["temp_discount"])?>%</td>
			<td><?=$row_cp["start_date"]?>~ <?=$row_cp["end_date"]?></td>
			<td class='p-y-05'><a href="javascript: sel_cp('<?=$row_cp["seq"]?>');"  class='btn btn-md btn-default'>선택</a></td>
		</tr>
		<?}
		} else {?>
		<tr>
			<td colspan="4">등록된 쿠폰이 없습니다.</td>
		</tr>
		<? } ?>
	</tbody>
</table>
<!-- <div class='text-center'>
	<ul class='pagination'>
		<li class='disabled'><a href="#"><i class='ti ti-angle-double-left'></i></a></li>
		<li class='disabled'><a href="#"><i class='ti ti-angle-left'></i></a></li>
		<li class='active'><a href="#">1</a></li>
		<li><a href="#">2</a></li>
		<li><a href="#">3</a></li>
		<li><a href="#"><i class='ti ti-angle-right'></i></a></li>
		<li><a href="#"><i class='ti ti-angle-double-right'></i></a></li>
	</ul>
</div> -->
<?php
	include '../_include/_footer.html';
?>


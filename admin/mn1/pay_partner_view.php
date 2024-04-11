<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$tm = "MN1";
	$lm = "";
	include $path_admin."inc/header.php";

	$parameter = "&pr_name=".$pr_name."&ins_name=".$ins_name."&plan_name=".$plan_name."&chk_service=".$chk_service."&search=".$search."&search_text=".$search_text."&search_orderby=".$search_orderby."&search_sort=".$search_sort."&num_per_page=".$num_per_page."&order_step=".$order_step."&search_date_txt=".$search_date_txt."&search_date_s=".$search_date_s."&search_date_e=".$search_date_e;

//	$dbcon -> setDebug(1);
	// 주문 가져오기
	if ($orderno){
		$SQL  =  "select * ";
		$SQL .= ", (SELECT o_phone FROM tbl_order_list_join WHERE orderno = A.orderno AND chk_join = 'N') as o_phone";
		$SQL .= ", (SELECT partnership_name FROM tbl_board_partner WHERE seq = A.join_ch) as partnership_name";
		$SQL .=  " from tbl_order_list A where orderno='".$orderno."' AND join_ch = '".$_SESSION["ss_partner_seq_admin"]."' ";
		$result = $dbcon -> query($SQL);
		$row= $dbcon -> fetch_array($result);
		
		if($row["chk_p"] == "Y") { //단기
			$t_s_date = $row["s_date"]." ".$row["s_date_time"];
			$t_e_date = $row["e_date"]." ".$row["e_date_time"];
		
			$s_date_text = $t_s_date.":00";
			$e_date_text = $t_e_date.":00";
		
			$arr_period = getArrPeriod($t_s_date,$t_e_date,$row["chk_p"]); //기간구하기
			$period = $arr_period["day"]."일";
		} else if($row["chk_p"] == "N") { //장기
			$t_s_date = $row["s_date"];
			$t_e_date = $row["e_date"];
		
			$s_date_text = $t_s_date;
			$e_date_text = $t_e_date;
		
			$arr_period = getArrPeriod($t_s_date,$t_e_date,$row["chk_p"]); //기간구하기
			$period = $arr_period["month"]."월";
		}
		
		//쿠폰,추천코드 내역가져오기
		if($row["sale_gubun"] == "C") {
			$sql_sale  =  " SELECT discount,(select coupon_name FROM tbl_board_event WHERE seq = h.event_seq) as coupon_name ";
			$sql_sale .= " FROM tbl_board_coupon_history h WHERE seq  = '".$row["cp_cd"]."' ";
			$rs_sale = $dbcon -> query($sql_sale);
			$row_sale = $dbcon -> fetch_array($rs_sale);
		} else if($row["sale_gubun"] == "R") {
			$sql_sale2  = " SELECT discount, (SELECT subject FROM tbl_board_recommend_code WHERE seq = recommend_seq ) as recommend_name ";
			$sql_sale2 .= " FROM tbl_board_recommend_code_history h WHERE orderno  = '".$row["orderno"]."' ";
			$rs_sale2 = $dbcon -> query($sql_sale2);
			$row_sale2 = $dbcon -> fetch_array($rs_sale2);
		}
		
	
		// 가입자 가져오기
		$SQL = "select * from tbl_order_list_join where orderno ='".$orderno."' order by field(chk_join,'N','Y') ASC,  seq ASC ";
		$RS = $dbcon -> query($SQL);
		
		$join_data = array();
		while($row_join = $dbcon->fetch_array($RS)) {
			$join_data[] = $row_join;
		}
		
		
	}
?>
<script>
function list_go() {
	location.href = "pay_partner_list.php?<?=$parameter?>";
}

</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">결제내역</td>
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
				<p class="tit_sub">- 결제정보</p>
			</div>
<form name="frm_mod" id="frm_mod" method="post">
<input type="hidden" name="mode" />
<input type="hidden" name="orderno" value="<?=$row["orderno"]?>">
<input type="hidden" name="o_phone" value="<?=$row["o_phone"]?>">
			<table class="adm-view-tb">
				<colgroup>
				<col width="8%">
				<col width="42%">
				<col width="8%">
				<col width="42%">
				</colgroup>
				<tr>
					<th>상품명</th>
					<td><?=$row["pr_name"]?></td>
					<th>보험사</th>
					<td><?=$row["ins_name"]?></td>
				</tr>
				<tr>
					<th>플랜명</th>
					<td><?=$row["plan_name"]?></td>
					<th>서비스</th>
					<td><?=$Arr_txt_plus[$row["chk_service"]]?></td>
				</tr>
				<tr>
					<th>보험기간</th>
					<td><?=$row["s_date"]?> <?=$row["s_date_time"]?>시 ~ <?=$row["e_date"]?> <?=$row["e_date_time"]?>시 <strong>(<?=$period?>)</strong></td>
					<th>주문일</th>
					<td><?=substr($row["writedate"],0,10)?></td>
				</tr>
				<tr>
					<th>계약자</th>
					<td><?=all_seed_dec($row["o_name"])?></td>
					<th>동반인(가입자 포함)</th>
					<td><?=$row["join_cnt"]?>명</td>
				</tr>
				<tr>
					<th>결제정보</th>
					<td><?=$row["pay_name"]?></td>
					<th>PG결제번호</th>
					<td><?=$row["pg_isdn"]?></td>
				</tr>
				<tr>
					<th>결제상태</th>
					<td colspan="3"><?=$arr_ord_step[$row["order_step"]]?> <?if (trim($row["cancle_date"])!="0000-00-00 00:00:00"){?><span class="txt_red">(결제취소일 : <?=$row["cancle_date"]?>)</span><?}?>
					</td>
				</tr>
				<tr>
					<th>제휴사</th>
					<td colspan="3"><?=$row["partnership_name"]?></td>
				</tr>
				<tr>
					<th>추천코드명</th>
					<td><?=$row_sale2["recommend_name"];?></td>
					<th>추천 할인율</th>
					<td><?=floor($row_sale2["discount"])?>%</td>
				</tr>
				<tr>
					<th>쿠폰코드명</th>
					<td><?=$row_sale["coupon_name"]?></td>
					<th>쿠폰 할인율</th>
					<td><?=floor($row_sale["discount"])?>%</td>
				</tr>
				<tr>
					<th>서비스료</th>
					<td colspan="3" class="r"><?=number_format($row["service_amount"])?>원</td>
				</tr>
				<tr>
					<th>보험료</th>
					<td colspan="3" class="r"><?=number_format($row["ins_amount"])?>원</td>
				</tr>
				<tr>
					<th>총 상품가</th>
					<td colspan="3" class="r"><?=number_format($row["ins_amount"]+$row["service_amount"])?>원</td>
				</tr>
				<tr>
					<th>할인금액</th>
					<td colspan="3" class="r"><?=number_format($row["s_amount"])?>원</td>
				</tr>
				<tr>
					<th>결제금액</th>
					<td colspan="3" class="r emphasis"><?=number_format($row["t_amount"])?>원</td>
				</tr>
				<tr>
					<th>서비스 부가세</th>
					<td class="r"><?=number_format($row["vat_amount"])?>원</td>
					<th>서비스 과세금액</th>
					<td class="r"><?=number_format($row["service_amount"]-$row["vat_amount"])?>원</td>
				</tr>
				<tr>
					<th>결제 취소금액</th>
					<td class="r"><?=number_format($row["cancle_amount"])?>원</td>
					<th>결제 취소일</th>
					<td><?=$row["cancle_date"]?></td>
				</tr>
			</table>
</form>
			<?
			if(count($join_data) > 0) {
				$z = 0;
				foreach($join_data as $row_L){
					if ($row_L["gender"]=="M" && substr(all_seed_dec($row_L["o_isdn1"]),0,4)<=2000){$isdn2_1 = "1";}
					if ($row_L["gender"]=="F" && substr(all_seed_dec($row_L["o_isdn1"]),0,4)<=2000){$isdn2_1 = "2";}
					if ($row_L["gender"]=="M" && substr(all_seed_dec($row_L["o_isdn1"]),0,4)>2000){$isdn2_1 = "3";}
					if ($row_L["gender"]=="F" && substr(all_seed_dec($row_L["o_isdn1"]),0,4)>2000){$isdn2_1 = "4";}
			?>
				<div class="btnSubTitWrap">
					<? if($row_L["chk_join"] == "N") {?>
					<p class="tit_sub">- 가입자정보</p>
					<? } else { ?>
					<p class="tit_sub">- 동반인정보(<?=$z;?>)</p>
					<? } ?>
				</div>
	
				<table class="adm-view-tb">
					<colgroup>
					<col width="8%">
					<col width="42%">
					<col width="8%">
					<col width="42%">
					</colgroup>
					<tr>
						<th>이름</th>
						<td><?=all_seed_dec($row_L["o_name"])?></td>
						<th>주민등록번호</th>
						<td><?=substr(all_seed_dec($row_L["o_isdn1"]),2,6)?>-<?=all_seed_dec($row_L["o_isdn2"])?></td>
					</tr>
					<tr>
						<th>연락처</th>
						<td><?=all_seed_dec($row_L["o_phone"])?></td>
						<th>이메일</th>
						<td><?=all_seed_dec($row["o_email1"])?>@<?=all_seed_dec($row["o_email2"])?></td>
					</tr>
					<tr>
						<th>여행국가</th>
						<td><?=$row["join_nation_name"]?></td>
						<th>출국목적</th>
						<td><?=$row["pr_name"]?></td>
					</tr>
					<tr>
						<th>결제상태</th>
						<td><?=$arr_ord_step[$row["order_step"]]?></td>
						<th>가입상태</th>
						<td><?=$arr_join_step[$row_L["join_status"]]?></td>
					</tr>
					<tr>
						<th>서비스료</th>
						<td colspan="3" class="r"><?=number_format($row_L["join_service"])?>원</td>
					</tr>
					<tr>
						<th>보험료</th>
						<td colspan="3" class="r"><?=number_format($row_L["join_amount"])?>원</td>
					</tr>
					<tr>
						<th>총 상품가</th>
						<td colspan="3" class="r"><?=number_format($row_L["join_service"]+$row_L["join_amount"])?>원</td>
					</tr>
					<tr>
						<th>할인금액</th>
						<td colspan="3" class="r"><?=number_format($row_L["s_amount"])?>원</td>
					</tr>
					<tr>
						<th>결제금액</th>
						<td colspan="3" class="r emphasis"><?=number_format($row_L["t_amount"])?>원</td>
					</tr>
					<tr>
						<th>서비스 부가세</th>
						<td class="r"><?=number_format($row_L["vat_amount"])?>원</td>
						<th>서비스 과세금액</th>
						<td class="r"><?=number_format($row_L["join_service"]-$row_L["vat_amount"])?>원</td>
					</tr>
					<tr>
						<th>결제취소 금액</th>
						<td class="r"><?=number_format($row_L["cancle_amount"])?>원</td>
						<th>결제 취소일</th>
						<td><?if (trim($row_L["cancle_date"])!="0000-00-00 00:00:00"){?><?=$row_L["cancle_date"]?><?}?></td>
					</tr>
				</table>
				<?
				$z++;
				}
			}?>

			<div class="btnWrapC">
				<a href="javascript:list_go();" class="btn_list">목록</a>
			</div>

			<!-- (e) 상세화면  -->
		</td>
	</tr>
</table>

<? include $path_admin."inc/footer.php"; ?>
<script>
function fnChangeStatus() {

	var ff = document.frm_mod;
	
	if($("select[name='order_step']").val() == $("input[name='before_order_step']").val()) {
		alert("이전 결제상태와 동일합니다. 다른 결제상태를 선택해 주세요.");
		return;
	}
	
	if(confirm("결제상태를 변경하시겠습니까?")) {
		$("input[name='mode']").val("change");
		var formData = $("#frm_mod").serialize();
		
		$.ajax({
				url:"order_step_mod_ok.php"
				,data:formData
				,dataType:"json"
				,type:"POST"
				,success:function(d) {
					console.log(d);
					if(d.result == "1") {
						alert("상태값 변경되었습니다.");
						location.reload();
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

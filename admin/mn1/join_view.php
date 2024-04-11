<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$tm = "MN1";
	$lm = "";
	include $path_admin."inc/header.php";

	$parameter = "&pr_name=".$pr_name."&ins_name=".$ins_name."&plan_name=".$plan_name."&chk_service=".$chk_service."&search=".$search."&search_text=".$search_text."&search_orderby=".$search_orderby."&search_sort=".$search_sort."&num_per_page=".$num_per_page."&join_status=".$join_status."&search_date_txt=".$search_date_txt."&search_date_s=".$search_date_s."&search_date_e=".$search_date_e;

	if ($seq){
		// $SQL = "select * from tbl_order_list_join where seq='".$seq."' ";
		$SQL = "select a.*, c.guarantee1_ins_seq from tbl_order_list_join a inner join tbl_order_list b on a.orderno = b.orderno inner join tbl_board_plan c on b.plan_cd = c.seq where a.seq='".$seq."' ";
		$result = $dbcon -> query($SQL);
		$row_L= $dbcon -> fetch_array($result);
	
		// 주문, 보장내역
		$SQL  = " select * ";
		$SQL .= ",(SELECT o_phone FROM tbl_order_list_join WHERE orderno=l.orderno AND chk_join = 'N') as mobile ";
		$SQL .= ",(SELECT cancle_date FROM tbl_order_list_join WHERE orderno=l.orderno AND chk_join = 'N') as tbl_cancle_date";
		$SQL .= ",(SELECT partnership_name FROM tbl_board_partner WHERE seq=join_ch) as partnership_name ";
		$SQL .= ",(SELECT subject from tbl_board_guarantee WHERE seq = agree_cd) as guarantee_name ";
		$SQL .= ",(SELECT subject from tbl_board_insuplus WHERE seq = service_cd) as service_name ";
		$SQL .= ",(SELECT subject from tbl_board_ins_agree WHERE seq = ins_file_cd) as ins_file_name ";
		$SQL .= ",(SELECT subject from tbl_board_service_agree WHERE seq = service_file_cd) as service_file_name ";
		$SQL .= ",(SELECT subject from tbl_board_policy WHERE seq = rule_site_cd) as rule_site_name ";
		$SQL .= ",(SELECT subject from tbl_board_policy WHERE seq = rule_group_cd) as rule_group_name ";
		$SQL .= ",(SELECT subject from tbl_board_policy WHERE seq = rule_privacy_cd) as rule_privacy_name ";
		$SQL .= " from tbl_order_list l WHERE orderno='".$row_L["orderno"]."' ";
		$result = $dbcon -> query($SQL);
		$row= $dbcon -> fetch_array($result);
	
		// 가입자 리스트 가져오기
		$SQL = "select * from tbl_order_list_join where orderno ='".$row_L["orderno"]."' order by seq ";
		$RS_JOIN_LIST = $dbcon -> query($SQL);

		//등업코드
		$SQL_LT = "select coupon_cd from tbl_temporary_coupon where orderno ='".$row_L["orderno"]."' ";
		$RS_LT = $dbcon -> query($SQL_LT);
		$row_lotte= $dbcon -> fetch_array($RS_LT);
		
		//가입자 로그
		$SQL_LOG  =  " SELECT manager_yn, gubun, memo, regdate FROM tbl_send_log  "; 
		$SQL_LOG .=  " WHERE orderno = '".$row["orderno"]."' OR mobile = '".$row["mobile"]."' ";
		$rs_log = $dbcon -> query($SQL_LOG);

		$SQL_TEL = "select * from tbl_telemedicine_cd_list where join_orderno = '".$row_L["orderno"]."'";
		$RS_TEL = $dbcon -> query($SQL_TEL);
		$row_tel= $dbcon -> fetch_array($RS_TEL);
	}
?>
<script>
function list_go() {
	location.href = "join_list.php";
}

function chk_mod_go(){
	var ff = document.frm_join;
	if(!ff.o_name.value) {
		alert("이름을 입력해 주세요.");
		ff.o_name.focus();
		return false;
	}

	if(!ff.o_phone.value) {
		alert("휴대폰번호를 입력해 주세요.");
		ff.o_phone.focus();
	}

	if(!ff.o_email1.value) {
		alert("이메일(1)을 입력해 주세요.");
		ff.o_email1.focus();
		return false;
	}

	if(!ff.o_email2.value) {
		alert("이메일(2)을 입력해 주세요.");
		ff.o_email2.focus();
		return false;
	}
	ff.action="./join_view_mod_ok.php?<?=$parameter?>";
	ff.submit();
}

function fnCertificate() {
	var popCertificate = window.open('popup_certificate.php?seq=<?=$seq?>&chk_page=join','popCertificate','top=0,left=0, width=700,height=500');
	popCertificate.focus();
}

function fnGetJoinCh() {
	var popCertificate = window.open('popup_get_join_channel.php?join_ch=<?=$row["join_ch"]?>','popGetJoinCh','top=0,left=0, width=700,height=962');
	popCertificate.focus();
}

function fnDelJoinCh() {
	var ff = document.frm_join;
	ff.join_ch.value = '';
	ff.joinChannel.value = '';	
	ff.delJoinChBtn.style.display = 'none';
}

</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">가입자</td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>
<form name="frm_join" method="post">
<input type="hidden" name="orderno" value="<?=$row["orderno"]?>">
<input type="hidden" name="seq" value="<?=$seq?>">
<input type="hidden" id="join_ch" name="join_ch" value="<?=$row["join_ch"]?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<!-- (s) 상세화면  -->
			<div class="btnSubTitWrap" style="margin:0 0 10px 0;">
				<p class="tit_sub">- 가입자 정보</p>
				<a href="pay_view.php?orderno=<?=$row["orderno"]?>" target="_blank" class="btn_normal">결제취소 이동하기</a>
				<? if($row["order_step"] == "2") { //결제완료 상태만?>
				<a href="javascript:;" onClick="fnCertificate()" class="btn_normal">가입증명원 재발송</a>
				<? } ?>
				<a href="javascript: chk_mod_go();" class="btn_add">수정</a>
			</div>

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
					<td><?=print_ins($row_L["guarantee1_ins_seq"])?></td>
				</tr>
				<tr>
					<th>플랜명</th>
					<td><?= $row["plan_name"]?></td>
					<th>서비스</th>
					<td><?=$row["service_name"]?></td>
				</tr>
				<tr>
					<th>원격진료 코드</th>
					<td><?= $row_tel["telemedicine_cd"] ? $row_tel["telemedicine_cd"] : getTelemedicCode($row["s_date"], $row["e_date"]) ?></td>
					<th>등업 코드</th>
					<td><?=$row_lotte["coupon_cd"]?></td>
				</tr>
				<tr>
					<th>보험기간</th>
					<td><?=$row["s_date"]?> <?=$row["s_date_time"]?>:00 ~ <?=$row["e_date"]?> <?=$row["e_date_time"]?>:00 <strong>(<?=$row["ins_period"]?><?=$arr_chk_p_gubun[$row["chk_p"]]?>)</strong></td>
					<th>가입일</th>
					<td><?=$row_L["regdate"]?></td>
				</tr>
				<tr>
					<th>결제상태</th>
					<td><?=$arr_ord_step[$row["order_step"]]?> 
					<?if (trim($row["cancle_date"])!="0000-00-00 00:00:00"){?><span class="txt_red">(결제취소일 : <?=$row["cancle_date"]?>)</span><?}?>
					</td>
					<th>가입상태</th>
					<td><?=$arr_join_step[$row_L["join_status"]]?></td>
				</tr>
				<tr>
					<th>이름</th>
					<td><input type="text" name="o_name" value="<?=all_seed_dec($row_L["o_name"])?>"/>
					<input type="text" name="o_name_en" value="<?=all_seed_dec($row_L["o_name_en"])?>"/></td>
					<th>주민등록번호</th>
					<td><?=substr(all_seed_dec($row_L["o_isdn1"]),2,6)?>-<?=all_seed_dec($row_L["o_isdn2"])?></td>
				</tr>
				<tr>
					<th>연락처</th>
					<td><input type="text" name="o_phone" class="numberonly" maxlength="11" value="<?=all_seed_dec($row_L["o_phone"])?>"/></td>
					<th>여행국가</th>
					<td><?=$row["join_nation_name"]?></td>
				</tr>
				<tr>
					<th>이메일</th>
					<td><input type="text" name="o_email1" class="" value="<?=all_seed_dec($row["o_email1"])?>"/>@<input type="text" name="o_email2" class="" value="<?=all_seed_dec($row["o_email2"])?>"/></td>
					<th>출국목적</th>
					<td><?=$row["pr_name"]?></td>
				</tr>
				<tr>
					<th>가입채널</th>
					<td>
						<input type="text" id="joinChannel" name="joinChannel" value="<?=$row["partnership_name"]?>" readonly/>
						<button type="button" onClick="fnGetJoinCh()" class="btn_normal" style="cursor: pointer;">가입채널 선택</button>
						<button type="button" onClick="fnDelJoinCh()" id="delJoinChBtn" class="btn_normal" style="cursor: pointer;display: <?= $row["join_ch"] ? 'inline' : 'none'?>;">가입채널 삭제</button>
					</td>
					<th>계약자/동반인</th>
					<td>
						<?
							while($row_j= $dbcon -> fetch_array($RS_JOIN_LIST)){
								if ($row_j["seq"] == $seq){
									$PAGE_FLIGHT_NO = $row_j["flight_no"];
									$PAGE_ARRIVAL_DATE = $row_j["arrival_date"];
								}
						?>
						<p>
							<? if($row_j["chk_join"] == "N") { ?>
								<a href="./join_view.php?seq=<?=$row_j["seq"]?>">계약자 : <?=all_seed_dec($row_j["o_name"])?></a>
							<? } else { ?>
								<a href="./join_view.php?seq=<?=$row_j["seq"]?>">동반인 : <?=all_seed_dec($row_j["o_name"])?></a>
							<? } ?>
						</p>
						<?}?>
					</td>
				</tr>
				<tr>
					<th>항공정보</th>
					<td>
						일자 : <input type="date" name="arrival_date" value="<?= $PAGE_ARRIVAL_DATE ? $PAGE_ARRIVAL_DATE:'' ?>"> <br />
						편명 : <input type="text" name="flight_no" style="margin-top:5px;" value="<?= $PAGE_FLIGHT_NO ? $PAGE_FLIGHT_NO:'' ?>">
					</td>
					<th>해외거주 여부</th>
					<td><?= $row_L["is_abroad_resident"] == "N" ? "아니오" : "예"?></td>
				</tr>
				<tr>
					<th>할인금액</th>
					<td class="r"><?=number_format($row_L["s_amount"])?>원</td>
					<th>결제금액</th>
					<td class="r"><?=number_format($row_L["t_amount"])?>원</td>
				</tr>
				<tr>
					<th>메모</th>
					<td colspan="3"><textarea style="height:80px;" name="o_memo"><?=$row["o_memo"]?></textarea></td>
				</tr>
			</table>
</form>

			<div class="btnSubTitWrap">
				<p class="tit_sub">- 결제정보</p>
				<a href="pay_view.php?orderno=<?=$row["orderno"]?>" target="_blank" class="btn_normal">결제내역 새창열기</a>
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
					<td><?=all_seed_dec($row["o_name"])?></td>
					<th>동반인 수<br/>(계약자 포함)</th>
					<td><?=$row["join_cnt"]?>명</td>
				</tr>
				<tr>
					<th>결제금액</th>
					<td class="r"><?=number_format($row["t_amount"])?>원</td>
					<th>가입상태</th>
					<td><?=$arr_join_step[$row_L["join_status"]]?></td>
				</tr>
				<tr>
					<th>총 상품가</th>
					<td class="r"><?=number_format($row_L["join_amount"]+$row_L["join_service"])?>원</td>
					<th>결제 취소일</th>
					<td><?if ($row["cancle_date"]!="0000-00-00 00:00:00"){?><?=$row["cancle_date"]?><?}?></td>
				</tr>
				<tr>
					<th>할인금액</th>
					<td class="r"><?=number_format($row["s_amount"])?>원</td>
					<th>결제 취소금액</th>
					<td class="r"><?=number_format($row["cancle_amount"])?>원</td>
				</tr>
			</table>

			<p class="tit_sub">- 가입약관</p>
			<table class="adm-view-tb">
				<colgroup>
				<col width="8%">
				<col width="42%">
				<col width="8%">
				<col width="42%">
				</colgroup>
				<tr>
					<th>보장내역</th>
					<td><?=$row["guarantee_name"]?></td>
					<th>서비스내역</th>
					<td><?=$row["service_name"]?></td>
				</tr>
				<tr>
					<th>보험가입약관</th>
					<td><?=$row["ins_file_name"]?></td>
					<th>서비스이용약관</th>
					<td><?=$row["service_file_name"]?></td>
				</tr>
				<tr>
					<th>사이트 이용약관</th>
					<td><?=$row["rule_site_name"]?></td>
					<th>단체 보험 규약 동의</th>
					<td><?=$row["rule_group_name"]?></td>
				</tr>
				<tr>
					<th>개인정보 수집 및 이용동의</th>
					<td colspan="3"><?=$row["rule_privacy_name"]?></td>
				</tr>
			</table>

			<div class="btnWrapC">
					<a href="javascript:list_go();" class="btn_list">목록</a>
			</div>

			<p class="tit_sub">- 이메일, 알림톡 로그</p>
			<table class="adm-list-tb">
				<colgroup>
				<col width="10%">
				<col width="10%">
				<col width="*">
				<col width="15%">
				</colgroup>
				<tr>
					<th>사용자/관리자</th>
					<th>발송구분</th>
					<th>내용</th>
					<th>등록일</th>
				</tr>
				<?
				while($row_log = $dbcon->fetch_array($rs_log)) {?>
				<tr>
					<td><?=$row_log["manager_yn"]=="Y" ? "관리자":"사용자"?></td>
					<td><?=$row_log["gubun"]=="A" ? "알림톡":"이메일"?></td>
					<td class="l"><?=$row_log["memo"];?></td>
					<td><?=$row_log["regdate"];?></td>
				</tr>
				<? } ?>
			</table>

			<!-- (e) 상세화면  -->
		</td>
	</tr>
</table>





<? include $path_admin."inc/footer.php"; ?>
<? $dbcon -> dbcon_close();?>

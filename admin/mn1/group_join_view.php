<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$tm = "MN1";
	$lm = "";
	include $path_admin."inc/header.php";

	$parameter = "&pr_cd=".$pr_cd."&ins_cd=".$ins_cd."&plan_cd=".$plan_cd."&chk_service=".$chk_service."&search_text=".$search_text."&num_per_page=".$num_per_page."&search_date_s=".$search_date_s."&search_date_e=".$search_date_e;

	$page_btn_prev =">>"; // > 버튼
	$page_btn_next ="<<"; // < 버튼

	// 페이지 설정
	$page				= REQSTR($page, 1);
	$num_per_page	= REQSTR($num_per_page, 30);
	$page_per_block	= REQSTR($page_per_block, 10);
	$first					= $num_per_page*($page-1);
	$last					= $num_per_page*$page;

	if ($group_join_id){
		
		$SQL = "select B.subject as pr_name, C.ext3 as chk_service, C.plan_cd as plan_type, C.ins_plan_name as ins_plan_name, A.* ";
		$SQL .= "from tbl_order_group_join_list A left join tbl_board_product B on A.pr_cd = B.seq left join tbl_board_plan C on A.plan_cd = C.seq ";
		$SQL .= "where group_join_id='".$group_join_id."' ";
		$result = $dbcon -> query($SQL);
		$group_row= $dbcon -> fetch_array($result);

		$JOIN_INFO = array();
		$SQL1 = "select * from tbl_order_list A inner join tbl_order_list_join B on A.orderno=B.orderno ";
		$SQL1 .= "where 1=1 and A.group_join_id = '".$group_join_id."' ";
		$RS_JOIN = $dbcon -> query($SQL1);
		while($JOIN_ROW = $dbcon -> fetch_array($RS_JOIN)){
			$JOIN_INFO[] = $JOIN_ROW;
		}
	}

	// 상품정보 불러오기
	$pr_list = array();
	$SQL_PR = "select seq,subject from tbl_board_product ";
	$RS_PR = $dbcon -> query($SQL_PR);
	while($row = $dbcon->fetch_array($RS_PR)) {
		$pr_list[] = $row;
	}
	
	// 보험사 불러오기
	$ins_list = array();
	$SQL_Ins = "select seq,subject from tbl_board_ins_list ";
	$RS_Ins = $dbcon -> query($SQL_Ins);
	while($row = $dbcon -> fetch_array($RS_Ins)){
		$ins_list[] = $row;
	}

	// $plan_name = getFullPlanName($group_row["plan_cd"]);
	$plan_name = $group_row["ins_plan_name"];

	$Arr_project_cd = array(
		"P0000000000" => "플라잉닥터스"
		, "P0000000001" => "인슈플러스"
		, "P0000000002" => "우리말도우미"
		, "P0000000003" => "PTI"
		, "P0000000004" => "I/B 유학생보험"
		, "P0000000005" => "인바운드 케이스"
	);
	
?>
<script>
function list_go() {
	location.href = "group_join_list.php?<?=$parameter?>";
}

function view_go(n) {
	location.href = "pay_view.php?orderno="+n+"<?=$parameter?>";
}

function view_graunt(n, p) {
	var popGraunt = window.open('popup_get_graunt.php?seq='+n+'&plan_seq='+p,'popGraunt','top=0,left=0, width=700,height=500');
	popGraunt.focus();
}

function chk_mod_go(){
	if(!ff.o_email2.value) {
		alert("이메일(2)을 입력해 주세요.");
		ff.o_email2.focus();
	}
	ff.action="./group_join_write_mod_ok.php?<?=$parameter?>";
	ff.submit();
}

function fnCertificate(id) {
	var popCertificate = window.open('popup_group_certificate.php?gid='+id+'&chk_service=<?=$group_row["chk_service"]?>','popCertificate','top=0,left=0, width=700,height=500');
	popCertificate.focus();
}

function deleteUser(orderno){
	var ff = document.frm_group_join;
	ff.mode.value = 'delUser';
	ff.delUserNo.value = orderno;
	ff.action="./group_join_write_mod_ok.php?<?=$parameter?>";
	ff.submit();

}

function chk_mod_go(){
	var date_pattern = /^(19|20)\d{2}(0[1-9]|1[012])(0[1-9]|[12][0-9]|3[0-1])$/; 
	var email_check = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	var ff = document.frm_group_join;
	
	if(!ff.ins_plan_name.value) {
		alert("플랜을 선택해 주세요.");
		ff.ins_plan_name.focus();
	} else if(ff.group_join_type.value == "B2C") {
		if(!ff.o_name_b2c.value){
			alert("대표자를 입력해 주세요.");
			ff.o_name_b2c.focus();
			return false;
		} else if(!ff.birthdate.value){
			alert("생년월일을 입력해 주세요.");
			ff.birthdate.focus();
			return false;
		} else if(!date_pattern .test(ff.birthdate.value)){
			alert("올바른 생년월일을 입력해 주세요.");
			ff.birthdate.focus();
			return false;
		}
	} else if(ff.group_join_type.value == "B2B"){
		if(!ff.o_name_b2b.value){
			alert("업체명을 입력해 주세요.");
			ff.o_name_b2b.focus();
			return false;
		}
	} 
	
	if(!ff.email.value){
		alert("이메일을 입력해 주세요.");
		return false;
	} else if(!email_check.test(ff.email.value)){
		alert("잘못된 이메일 형식입니다.");
		return false;
	} else if(ff.o_phone.value == "") {
		alert("연락처를 입력해주세요.");
		ff.o_phone.focus();
		return false;
	} 
	if($('#file1').val() != "") {
		var str = $('#file1').prop('files')[0]['name'];
		var fileFormat = str.split(".").pop().toLowerCase();
		if($.inArray(fileFormat, ['xlsx','xls']) == -1) {
			alert('xlsx,xls 파일만 업로드 할수 있습니다.');
			$('#file1').focus();
			return;
		}
	}
	
	ff.action="./group_join_write_mod_ok.php?<?=$parameter?>";
	ff.submit();
}

function pop_plan(){
	var popPlan = window.open('popup_get_plan.php','popPlan','top=0,left=0, width=1155,height=765');
	popPlan.focus();
}

function pop_client(){
	var popPlan = window.open('popup_get_flying_client.php','popPlan','top=0,left=0, width=1155,height=765');
	popPlan.focus();
}

function fn_chg_type(){
	var ff = document.frm_group_join;

	if(ff.group_join_type.value== 'B2C'){
		$("#b2b").css("display","none");
		$("#b2c").css("display","");
	} else {
		$("#b2b").css("display","");
		$("#b2c").css("display","none");
	}
}

$(document).ready(function() {
	var groupJoinType = '<?=$group_row["group_join_type"]?>';

	if(groupJoinType){
		if(groupJoinType == "B2C"){
			$("#b2b").css("display","none");
			$("#b2c").css("display","");
		} else {
			$("#b2b").css("display","");
			$("#b2c").css("display","none");
		}
	}
});
</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">단체가입</td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>
<form name="frm_group_join" method="post" enctype="multipart/form-data" >
<input type="hidden" name="mode" value="mod">
<input type="hidden" name="group_join_id" value="<?=$group_join_id?>">
<input type="hidden" name="agree_cd" value="<?=$group_row["agree_cd"]?>">
<input type="hidden" name="chk_period" value="<?=$group_row["chk_period"]?>">
<input type="hidden" name="chk_service" value="<?=$group_row["chk_service"]?>">
<input type="hidden" name="ins_cd" value="<?=$group_row["ins_cd"]?>">
<input type="hidden" name="pr_cd" value="<?=$group_row["pr_cd"]?>">
<input type="hidden" name="plan_cd" value="<?=$group_row["plan_type"]?>">
<input type="hidden" name="plan_seq" value="<?=$group_row["plan_cd"]?>">
<input type="hidden" name="service_cd" value="<?=$group_row["service_cd"]?>">
<input type="hidden" name="stock_isdn" value="<?=$group_row["stock_isdn"]?>">
<input type="hidden" name="client_id" value="<?=$group_row["client_id"]?>">
<input type="hidden" name="biz_num" value="<?=$group_row["biz_num"]?>">
<input type="hidden" name="delUserNo" value="">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<div class="btnWrap">
				<a href="javascript:list_go();" class="btn_list" >목록</a>
				<div style="float: right;">
				<p style="float: left;">견적상태</p>
				<select name="group_join_status" style="margin: 10px;">
					<option value="">상태변경</option>
					<option value="N" <?if ($group_row["group_join_status"]=="N"){?>selected<?}else{}?>>견적</option>
					<option value="W" <?if ($group_row["group_join_status"]=="W"){?>selected<?}else{}?>>입금대기</option>
					<option value="Y" <?if ($group_row["group_join_status"]=="Y"){?>selected<?}else{}?>>가입완료</option>
				</select>
				
				<a href="javascript: chk_mod_go();" class="btn_add">저장</a>
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
					<td><input type="text" name="pr_name" id="pr_name" value="<?=print_pr_name($group_row["pr_cd"])?>" readonly/></td>
					<th>플랜</th>
					<td><input type="text" name="ins_plan_name" id="ins_plan_name" value="<?=$plan_name?>" readonly/><a class="btn-form-normal" href="javascript:;" onclick="pop_plan();">찾기</a></td>
				</tr>
				<tr>
					<th>보험사</th>
					<td><input type="text" name="ins_name" id="ins_name" value="<?=print_ins($group_row["ins_cd"])?>" readonly/></td>
					<th>고객구분</th>
					<td>
						<select name="group_join_type" style="margin: 10px;" onchange="fn_chg_type()">
							<option value="B2B" <?if ($group_row["group_join_type"]=="B2B"){?>selected<?}else{}?>>B2B</option>
							<option value="B2C" <?if ($group_row["group_join_type"]=="B2C"){?>selected<?}else{}?>>B2C</option>
						</select>
					</td>
				</tr>
				<tr id="b2c" style="display: none;">
					<th>대표자</th>
					<td>
                        <input type="text" name="o_name_b2c" id="o_name_b2c" value="<?=all_seed_dec($group_row["o_name"])?>" placeholder="한글명"/>
                    </td>
					<th>생년월일</th>
					<td><input type="text" name="birthdate" id="birthdate" placeholder="ex) 20210101" value="<?=$group_row["birthdate"]?>"/></td>
				</tr>
				<tr id="b2b">
					<th>업체</th>
					<td>
                        <input type="text" name="o_name_b2b" id="o_name_b2b" value="<?=all_seed_dec($group_row["o_name"])?>" readonly placeholder="한글명"/>
                        <input type="text" name="o_name_en_b2b" id="o_name_en_b2b" value="<?=all_seed_dec($group_row["o_name_en"])?>" readonly placeholder="영문명"/>
                        <a class="btn-form-normal" href="javascript:;" onclick="pop_client();">찾기</a>
                    </td>
					<th>프로젝트</th>
					<!-- <td><input type="text" name="grp_cd" id="grp_cd" value="<?=$group_row["grp_cd"]?>"/></td> -->
					<td>
						<select name="grp_cd" id="grp_cd">
							<? foreach ($Arr_project_cd as $key => $val) { ?>
									<option value="<?=$key?>" <? if ("".$key == $group_row["grp_cd"]) echo "selected";?>><?=$val?></option>
							<? } ?>
						</select>
					</td>
				</tr>
				<tr>
					<th>대표자 이메일</th>
					<td><input type="text" name="email" id="email" placeholder="ex) admin@insuplus.co.kr"value="<?=all_seed_dec($group_row["o_email"])?>"/></td>
					<th>대표자 연락처</th>
					<td><input type="text" name="o_phone" id="o_phone" placeholder="ex) 01012345678" value="<?=all_seed_dec($group_row["o_phone"])?>"/></td>
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
<? if($group_row["group_join_cnt"] > 0){
	// 쿼리설정

	$field		     = " * ";
	$field			.= ", (SELECT partnership_name FROM tbl_board_partner WHERE seq = A.join_ch) as partnership_name";

	$table			= " tbl_order_list A inner join tbl_order_list_join B on A.orderno=B.orderno ";
	$where			= " and B.group_join_id = '".$group_join_id."' ";
	$orderby			= $search_orderby." ".$search_sort;
	$limit				= $first.", ".$num_per_page;

	$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);
	$total_record	= $ArrRS[0];
	$result			= $ArrRS[1];
	unset($ArrRS);

	// 페이지 & 리스트 설정
	$total_page		= ceil($total_record/$num_per_page);
	$no				= $total_record - $first;
	?>
			<div class="btnSubTitWrap">
				<p class="tit_sub">- 가입인원 <?=$group_row["group_join_cnt"]?>명 | 결제금액 <?=$group_row["total_amount"]?>원</p>
				<a class="btn_normal" href="javascript:;" onClick="view_graunt('<?=$group_row["pr_cd"]?>','<?= $group_row["plan_cd"] ?>')">보장내역 보기</a>
				<? if($group_row["group_join_status"] == "Y") { //결제완료 상태만?>
				<a href="javascript:;" onClick="fnCertificate('<?=$group_row["group_join_id"]?>')" class="btn_normal">가입증명원 확인</a>
				<? } ?>
			</div>

			<table class="adm-view-tb">
			<colgroup>
				<col width="5%" />
				<col width="7%" />
				<col width="8%" />
				<col width="8%" />
				<col width="5%" />
				<col width="7%" />
				<col width="10%" />
				<col width="8%" />
				<col width="5%" />
			</colgroup>
			<tr>
				<th>NO</th>
				<th>이름</th>
				<th>개시일</th>
				<th>종료일</th>
				<th>보험기간</th>
				<th>가입상태</th>
				<th>결제금액</th>
				<th>가입일</th>
				<th>삭제</th>
			</tr>
			<? if ($total_record == 0) { ?>
			<tr>
				<td colspan="14">등록된 데이터가 없습니다.</td>
			</tr>
			<?
				} else {
					while ($rows = $dbcon -> fetch_array($result)) {
						extract($rows);
						unset($rows);
						if($chk_p == "Y") {
							$s_date = $s_date." ".$s_date_time."시";
							$e_date = $e_date." ".$e_date_time."시";
						} else if($chk_p == "N") {
							$s_date = $s_date;
							$e_date = $e_date;
						}
						$bg = "";
						if($join_status == "N") { //가입취소
							$bg = "#ededed";
						} else if($join_status == "R") { //중도해지
							$bg = "#eb8b8b";
						}
						//if ($join_status=="S"){$bg = "#f3c7d5";}
			?>
			<tr>
				<td><?=$no?></td>
				<td><a href="javascript:;" onClick="view_go('<?= $orderno?>')"><?=all_seed_dec($o_name)?></a></td>
				<td><?=$s_date?></td>
				<td><?=$e_date?></td>
				<td><?=$ins_period?> <?=$arr_chk_p_gubun[$chk_p]?></td>
				<td><?=$arr_join_step[$join_status]?></td>
				<td class="r"><?=number_format($join_amount+$join_service)?>원</td>
				<td><?=substr($regdate,0,10)?></td>
				<td><a class="btn-form-normal" href="javascript:;" onclick="deleteUser('<?= $orderno?>')">삭제</a></td>
			</tr>
			<?
						$no = $no - 1;
					}
				}
				unset($result);

			?>
			</table>
			<input type=hidden name=f_delete>
			</form>
			<!--  (e) 리스트 영역  -->

			<!-- (s) 페이징 처리 -->
			<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td align="center" valign="top" style="padding:10px 0 0 0">
						<?
					global $page_btn_first, $page_btn_prev, $page_btn_next, $page_btn_last;
					if ( !$page_btn_first ) {
						$page_btn_first ="<img src=\"/images/99_common/btn_fast_prev.gif\" />";  // >> 버튼
					}
					if ( !$page_btn_prev ) {
						$page_btn_prev ="<img src=\"/images/99_common/btn_prev.gif\" />"; // > 버튼
					}
					if ( !$page_btn_next ) {
						$page_btn_next ="<img src=\"/images/99_common/btn_next.gif\" />"; // < 버튼
					}
					if ( !$page_btn_last ) {
						$page_btn_last ="<img src=\"/images/99_common/btn_fast_next.gif\" />";  // << 버튼
					}


					//<img src='/admin/img/prev10.gif' border='0'>
					//<img src='/admin/img/prev.gif' border='0'>
					//<img src='/admin/img/next.gif' border='0'>
					//<img src='/admin/img/next10.gif' border='0'>
					?>
							<table border="0" align="center" cellpadding="0" cellspacing="0" class="mg_top17 paging">
								<tr>
									<td width="13" style="padding-right:5px;">
					<?
					if ($page > $page_per_block) {
						if ($page % $page_per_block == 0 ) {
							$block_num_prev = $page - $page_per_block;
						}
						else {
							$block_num_prev = floor($page/$page_per_block) * $page_per_block;
						}
						?> <a href="javascript:page_go('<?=($block_num_prev)?>');"><?=$page_btn_next?></a> <?
					} else {
						?> <?=$page_btn_next?> <?
					}
					?></td><?

					/*
					echo $now_page."<BR>";		//
					echo $total_page."<BR>";		//
					echo $block_num."<BR>";		//
					echo $now_page % $block_num."<BR>";
					*/

					//$start_page :
					//$last_page :

					if ($page % $page_per_block == 0 ) {
						$start_page = $page - $page_per_block + 1;
						$last_page = $page;
					} else {
						$start_page = floor($page/$page_per_block) * $page_per_block + 1;
						$last_page = (floor($page/$page_per_block)+1) * $page_per_block;
					}

					/*
					echo $start_page."<BR>";
					echo $last_page."<BR>";
					*/

					if ( $last_page > $total_page ) {
						$last_page = $total_page;
					}

					?><td align="center" class="txt01"><?
					for ($i = $start_page; $i < $last_page + 1; $i++) {
						if ($page == $i) {
							?> <span><?=$i?></span> <?
						} else {
							?> <a href="javascript:page_go('<?=$i?>')"><?=$i?></a> <?
						}

						if ( $i < $last_page) {
							//echo " | ";
						}
					}

					if ($i == $start_page ) {
						?> <span><?=$i?></span> <?
					}
					?></td><?

					?><td width="14" align="right" style="padding-left:5px;"><?
					if ($last_page != $total_page) {
						?> <a href="javascript:page_go('<?=$last_page+1?>')"><?=$page_btn_prev?></a> <?
					} else {
						?> <?=$page_btn_prev?> <?
					}
					?></td><?

					?>
								</tr>
							</table>
					<script>
						function page_go(page) {
							location.href = "?group_join_id="+<?= $group_join_id?>+"&page="+page+"&<?=$GLOBALS["parameter"]?>";
						}
					</script>
					</td>
				</tr>
			</table>
			<!-- (e) 페이징 처리 -->
<? } ?>
			<!-- (e) 상세화면  -->
		</td>
	</tr>
</table>





<? include $path_admin."inc/footer.php"; ?>
<? $dbcon -> dbcon_close();?>

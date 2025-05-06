<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php"; //추가
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$tm = "MN1";
	$lm = "";
	include $path_admin."inc/header.php";

	$parameter = "&pr_cd=".$pr_cd."&group_join_status=".$group_join_status."&search_text=".$search_text."&num_per_page=".$num_per_page."&search_date_s=".$search_date_s."&search_date_e=".$search_date_e;
	

	if ($group_join_id){
		//단체가입 내역 조회
		$SQL = "select * from tbl_order_group_join_list where group_join_id = '".$group_join_id."'";
    
		$RS = $dbcon -> query($SQL);
 
		if (!$RS){
			echo "<script>alert('해당 단체 가입내역이 없습니다.');</script>";
			exit;
		}
		$row_group_info = $dbcon -> fetch_array($RS);

		$arr_plan_amount = [$row_group_info["plan_total_ins_amount1"], $row_group_info["plan_total_ins_amount2"], $row_group_info["plan_total_ins_amount3"]];
		$PR_SEQ = $row_group_info["pr_cd"];

		$PR_INFO = getInsuProductInfo($PR_SEQ); //상품정보
		$chk_p = $PR_INFO["ext1"];
     
		$str = ["ins_plan_cd1", "ins_plan_cd2", "ins_plan_cd3"];
		$arr_compare = array();
		$compare_seq = "";
		for($i=0; $i<3; $i++) {
			if($row_group_info[$str[$i]]) {
				if($compare_seq) $compare_seq .= ",";
				$compare_seq .= $row_group_info[$str[$i]];
				$arr_compare[] = $row_group_info[$str[$i]];
			}
		}

		//선택 플랜 리스트
		$arr_plan_list = selGroupPlanList($PR_SEQ, $compare_seq, $chk_p);
			
		//상품에 대한 인슈플러스 항목 리스트
		if($PR_INFO["ext5"]) {
			$arr_insuplus = selInsuplusList($PR_INFO["ext5"]);
		}

		//상품에 대한 보장내역 검색
		if($PR_INFO["ext4"]){
			$arr_guarantee = selGuaranteeList($PR_INFO["ext4"]);
		}

		error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_USER_DEPRECATED);
		include $_SERVER["DOCUMENT_ROOT"]."/_util/PHPExcel-1.8/Classes/PHPExcel.php";
		//파일 타입 설정 (확자자에 따른 구분)
		$inputFileType = 'Excel2007';
		if($file_info[1] == "xls") {
			$inputFileType = 'Excel5';
		}
		//==================================================================
		//  PHPEXCEL 용 로더
		//==================================================================
		//엑셀리더 초기화
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		//데이터만 읽기(서식을 모두 무시해서 속도 증가 시킴)
		$objReader->setReadDataOnly(true);
		//범위 지정(위에 작성한 범위필터 적용)
		//	$objReader->setReadFilter($filterSubset);
		//업로드된 엑셀 파일 읽기
		//	$objPHPExcel = $objReader->load($url);
		$objPHPExcel = PHPExcel_IOFactory::load($row_group_info["listfile"]);
		//첫번째 시트로 고정
		$objPHPExcel->setActiveSheetIndex(0);
		//고정된 시트 로드
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$cellIterator = $objWorksheet->getRowIterator()->current()->getCellIterator();
		$cellIterator->setIterateOnlyExistingCells( true );
		$maxRow = $objWorksheet->getHighestRow();
		$GROUP_INFO_TABLE = array();
		$PLAN_DATA = array();
		$GROUP_INFO_TABLE["chk_p"] = $chk_p;
		
		for($i=2;$i<=$maxRow;$i++) {
            $nullCheck = str_replace("\"","",$objWorksheet->getCell('A' . $i)->getValue());
            if($nullCheck != '' && strlen($nullCheck) > 1) {
                $GROUP_INFO_TABLE["birth"][$i-2] = str_replace("\"","",$objWorksheet->getCell('A' . $i)->getValue());
                $GROUP_INFO_TABLE["gender"][$i-2] = str_replace("\"","",$objWorksheet->getCell('B' . $i)->getValue());
                $GROUP_INFO_TABLE["s_date"][$i-2] = str_replace("\"","",$objWorksheet->getCell('C' . $i)->getValue());
                $GROUP_INFO_TABLE["e_date"][$i-2] = str_replace("\"","",$objWorksheet->getCell('D' . $i)->getValue());
                $GROUP_INFO_TABLE["age"][$i-2] = fn_ins_age(date("Y-m-d",strtotime($GROUP_INFO_TABLE["birth"][$i-2]))); //보험나이
                if($chk_p == "Y") { //단기
                    $t_s_date = $GROUP_INFO_TABLE["s_date"][$i-2];
                    $t_e_date = $GROUP_INFO_TABLE["e_date"][$i-2];
                    $arr_period = getArrPeriod($t_s_date,$t_e_date,$chk_p);
                    $GROUP_INFO_TABLE["period"][$i-2] = $arr_period["day"];
                    $GROUP_INFO_TABLE["period_month"][$i-2] = $arr_period["month"];
                } else { //장기
                    $t_s_date = explode( ' ', $GROUP_INFO_TABLE["s_date"][$i-2]);
                    $t_e_date = explode( ' ', $GROUP_INFO_TABLE["e_date"][$i-2]);
                    $arr_period = getArrPeriod($t_s_date[0],$t_e_date[0],$chk_p);
                    $GROUP_INFO_TABLE["period"][$i-2] = $arr_period["day"];
                    $GROUP_INFO_TABLE["period_month"][$i-2] = $arr_period["month"];
                }
            }
        }
		$group_amount_list = getGroupAmountList($arr_compare, $GROUP_INFO_TABLE);

		$sdate = $GROUP_INFO_TABLE["s_date"];
		$edate =  $GROUP_INFO_TABLE["e_date"];
		
		function dn_date_sort($a, $b) {
			return strtotime($a) -strtotime($b);
		}
		function up_date_sort($a, $b) {
			return strtotime($b) -strtotime($a);
		}

		usort($sdate, "dn_date_sort");
		usort($edate, "up_date_sort");

		$period = $sdate[0].":00"." ~ ".$edate[0].":00"; //전체 가입 기간
	}
?>
<script>
function list_go() {
	location.href = "group_join_estimate_list.php?<?=$parameter?>";
}

function chk_mod_go(){
	var ff = document.frm_join;

	ff.action="./group_join_estimate_view_mod_ok.php?<?=$parameter?>";
	ff.submit();
}

function fnCertificate() {
	var popCertificate = window.open('popup_certificate.php?seq=<?=$group_join_id?>','popCertificate','top=0,left=0, width=700,height=500');
	popCertificate.focus();
}

function downfile(id){
	url = "download_file.php?group_join_id="+id;
	location.href = url;
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
<input type="hidden" name="group_join_id" value="<?=$group_join_id?>">
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<div class="btnWrap">
				<a href="javascript:list_go();" class="btn_list" >목록</a>
				<div style="float: right;">
				<p style="float: left;">견적상태</p>
				<select name="group_join_status" style="margin: 10px;">
					<option value="">상태변경</option>
					<option value="N" <?if ($row_group_info["group_join_status"]=="N"){?>selected<?}else{}?>>견적</option>
					<option value="W" <?if ($row_group_info["group_join_status"]=="W"){?>selected<?}else{}?>>입금대기</option>
					<option value="Y" <?if ($row_group_info["group_join_status"]=="Y"){?>selected<?}else{}?>>가입완료</option>
				</select>
				
				<a href="javascript: chk_mod_go();" class="btn_add">저장</a>
			</div>
				
			</div>
			<!-- (s) 상세화면  -->
			<div class="btnSubTitWrap" style="margin:0 0 10px 0;">
				<p class="tit_sub">1. 견적 조건</p>
			</div>
			<table class="adm-view-tb">
				<colgroup>
				</colgroup>
				<tbody>
					<tr>
						<th>출국목적</th>
						<td><?= $row_group_info["purpose"] ?></td>
						<th>상품</th>
						<td><?= $PR_INFO["subject"] ?></td>
					</tr>
					<tr>
						<th colspan="1">총인원</th>
						<td colspan="3"><?= $row_group_info["group_join_cnt"] ?>명</td>
					</tr>
					<tr>
						<?if($row_group_info["group_join_type"] == "B2C"){ ?>
							<th>대표자명</th>
							<td><?= all_seed_dec($row_group_info["o_name"]); ?></td>
							<th>생년월일</th>
							<td><?= $row_group_info["birthdate"] ?></td>
						<? } else { ?>
							<th>회사명</th>
							<td><?= all_seed_dec($row_group_info["o_name"]); ?></td>
							<th>사업자번호</th>
							<td><?= $row_group_info["biz_num"] ?></td>
						<? } ?>
					</tr>
					<tr>
						<th>연락처</th>
						<td><?= all_seed_dec($row_group_info["o_phone"]); ?></td>
						<th>이메일</th>
						<td><?= all_seed_dec($row_group_info["o_email"]); ?></td>
					</tr>
				</tbody>
			</table>

			<div class="btnSubTitWrap">
				<p class="tit_sub">2. 보험료 견적</p>
				<a href="javascript: downfile(<?=$row_group_info["group_join_id"]?>);"class="btn_normal">가입자 정보 다운로드</a>
			</div>

			<table class="adm-view-tb">
				<thead>
					<th>번호</th>
					<th>생년월일</th>
					<th>성별</th>
					<th>게시일</th>
					<th>종료일</th>
					<?foreach($arr_plan_list as $plan_info){?>
					<th><?=print_ins($plan_info["ins_cd"]);?> <?=$Arr_plan_cd[$plan_info["plan_cd"]]?></th>
					<? } ?>
				</thead>
				<tbody style="text-align:center;">
				<? if(count($arr_insuplus) > 0 ) { ?>
				<tr>
					<th colspan="5">총 보험료</th>
					<? foreach($arr_plan_amount as $plan_amount){ 
                        if($plan_amount){?>
					<td style="color: red;"><?= $plan_amount ?>원</td>
                        <? }
                    } ?>
				</tr>
				<? for($i=0; $i < count($GROUP_INFO_TABLE["birth"]);$i++){?>
				<tr>
					<td><?=$i+1?></td>								
					<td><?=$GROUP_INFO_TABLE["birth"][$i]?></td>
					<td><?=$GROUP_INFO_TABLE["gender"][$i]?></td>
					<td><?=$GROUP_INFO_TABLE["s_date"][$i]?>:00</td>
					<td><?=$GROUP_INFO_TABLE["e_date"][$i]?>:00</td>
				<?foreach($group_amount_list[$i] as $person_amount){?>
					<td><?=$person_amount?>원</td>
				<? }?>
				</tr>
					<?}
				}?>
				</tbody>
			</table>
			
		</td>
	</tr>
</table>

<? include $path_admin."inc/footer.php"; ?>
<? $dbcon -> dbcon_close();?>

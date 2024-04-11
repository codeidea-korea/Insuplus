<?php
	include '../_include/_header.html';
	
	include '../_include/_top.html';
	include '../_include/_sidebar.html';
	
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php"; //추가
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php"; //추가
	include $_SERVER["DOCUMENT_ROOT"]."/_config/Mobile_Detect.php";
	
	$detect = new Mobile_Detect;

?>
<?
//==========================================================


$PR_INFO = getInsuProductInfo($PR_SEQ); //상품정보
$chk_p = $PR_INFO["ext1"];
$compare_seq = "";
$group_seq = $_SESSION["INSERT_SEQ"];


if($_POST["joinType"] == "2") { //step1~5방식
	
	for($i=0; $i<count($_POST["compare_seq"]); $i++) {
		if($_POST["compare_seq"][$i]) {
			if($compare_seq) $compare_seq .= ",";
			$compare_seq .= $_POST["compare_seq"][$i];
		}
	}
} else {
	$compare_seq = $_POST["compare_seq"];
}
// 여행 변수 받음 처리
if ($_POST["o_name"]){
	$name = $_POST["o_name"];
	$phone = $_POST["o_phone"];
	$email = $_POST["o_email"];
	$file_path = $_POST["file_path"];
	$arr_plan_amount = $_POST["plan_amount"];
	$GROUP_INFO_TABLE["chk_p"] = $chk_p;

	if($_POST["group_join_type"] == "B2C"){
		$group_join_type = "B2C";
		$birthdate = $_POST["birthdate"];
	} else {
		$group_join_type = "B2B";
		$biz_num = $_POST["biz_num"];
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
	
} else {
	alert_page("올바른 경로로 이용해 주세요.","../main/index.php");
	exit;
}

error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_USER_DEPRECATED);
include $_SERVER["DOCUMENT_ROOT"]."/_util/PHPExcel-1.8/Classes/PHPExcel.php";
if ($_POST["file_path"]){
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
	$objPHPExcel = PHPExcel_IOFactory::load($file_path);
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
        if(str_replace("\"","",$objWorksheet->getCell('A' . $i)->getValue()) != '') {
            $GROUP_INFO_TABLE["birth"][$i-2] = str_replace("\"","",$objWorksheet->getCell('A' . $i)->getValue());
            $GROUP_INFO_TABLE["gender"][$i-2] = str_replace("\"","",$objWorksheet->getCell('B' . $i)->getValue());
            $GROUP_INFO_TABLE["s_date"][$i-2] = str_replace("\"","",$objWorksheet->getCell('C' . $i)->getValue());
            $GROUP_INFO_TABLE["e_date"][$i-2] = str_replace("\"","",$objWorksheet->getCell('D' . $i)->getValue());
            $GROUP_INFO_TABLE["age"][$i-2] = fn_ins_age(date("Y-m-d",strtotime($GROUP_INFO_TABLE["birth"][$i-2]))); //보험나이
            if($chk_p == "Y") { //단기
                $t_s_date = $GROUP_INFO_TABLE["s_date"][$i-2];
                $t_e_date = $GROUP_INFO_TABLE["e_date"][$i-2];
                $arr_period = getArrPeriod($t_s_date,$t_e_date,$chk_p);
                if($arr_period["day"] > 90) {
                    alert_page("가입기간은 90일까지 가입가능합니다.","./search_insur.php?PR_SEQ=".$PR_SEQ);
                    exit;
                }
                $GROUP_INFO_TABLE["period"][$i-2] = $arr_period["day"];
                $GROUP_INFO_TABLE["period_month"][$i-2] = $arr_period["month"];
            } else { //장기
                $t_s_date = explode( ' ', $GROUP_INFO_TABLE["s_date"][$i-2]);
                $t_e_date = explode( ' ', $GROUP_INFO_TABLE["e_date"][$i-2]);
                $arr_period = getArrPeriod($t_s_date[0],$t_e_date[0],$chk_p);
                if($arr_period["day"] > 365) {
                    alert_page("가입기간은 365일까지 가입가능합니다.","./search_insur.php?PR_SEQ=".$PR_SEQ);
                    exit;
                }
                $GROUP_INFO_TABLE["period"][$i-2] = $arr_period["day"];
                $GROUP_INFO_TABLE["period_month"][$i-2] = $arr_period["month"];
            }
        }
	}
	$data = getGroupAmountList($_POST["compare_seq"], $GROUP_INFO_TABLE);
}

?>
		<div class="breadcrumb-image" style="background-image:url('<?=$PR_INFO["imgfile"]?>')">
			<div class="container">
				<h2><?=$PR_INFO["subject"]?></h2>
				<h4><?=$PR_INFO["content"]?></h4>
			</div>
		</div>
		<div class="breadcrumb-wrap">
            <div class="container">
				<ol class="breadcrumb">
					<li><a href="../main/index.php">InsuPlus HOME</a></li>
					<li><?=$PR_INFO["subject"]?></li>
				</ol>
            </div>
        </div>
		<div class="container sub-content sub-register">
			<div class="row">
				<div style="width: 90%;float: left;">
					<h2>인슈플러스 보험 비교</h2>
					<p>단체 보험 청약은 추가 서류 제출이 필요하여 전담부서에서 청약 안내 메일을 드립니다.<br>
						02-360-2545(1번) 또는 카카오채널 “인슈플러스로 문의하실 수 있습니다."</p>
				</div>
				<div>
					<a class="btn btn-md btn-theme-bg" href="javascript: downEstimate();" >견적서 다운로드</a>
				</div>
			</div>
			<div class="row" style="margin-top: 20px;">
				<h3>1. 견적 조건</h3>
			</div>
			<div class="row">
				<table class="table">
					<tbody>
						<tr>
							<th>출국목적</th>
							<td><?= $_POST["purpose"] ?></td>
							<th>상품</th>
							<td><?= $PR_INFO["subject"] ?></td>
						</tr>
						<tr>
							<th colspan="1">총인원</th>
							<td colspan="3"><?= $_POST["group_join_cnt"] ?>명</td>
						</tr>
						<tr>
							<?if($_POST["group_join_type"] == "B2C"){ ?>
								<th>대표자명</th>
								<td><?= $_POST["o_name"] ?></td>
								<th>생년월일</th>
								<td><?= $_POST["birthdate"] ?></td>
							<? } else { ?>
								<th>회사명</th>
								<td><?= $_POST["o_name"] ?></td>
								<th>사업자번호</th>
								<td><?= $_POST["biz_num"] ?></td>
							<? } ?>
						</tr>
						<tr>
							<th>연락처</th>
							<td><?= $_POST["o_phone"] ?></td>
							<th>이메일</th>
							<td><?= $_POST["o_email"] ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="row" style="margin-top: 20px;">
				<h3>2. 상품가격 견적</h3>
			</div>
			<div class="row">
				<table class="table table-bordered">
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
					
						<? for($i=0; $i < count($GROUP_INFO_TABLE["birth"]);$i++){?>
							<tr>
								<td><?=$i+1?></td>								
								<td><?=$GROUP_INFO_TABLE["birth"][$i]?></td>
								<td><?=$GROUP_INFO_TABLE["gender"][$i]?></td>
								<td><?=$GROUP_INFO_TABLE["s_date"][$i]?>:00</td>
								<td><?=$GROUP_INFO_TABLE["e_date"][$i]?>:00</td>
							<?foreach($data[$i] as $person_amount){?>
								<td><?=$person_amount?>원</td>
							<? }?>
							</tr>
						<?}
					}?>
					</tbody>
				</table>
				<table class="table table-bordered">
					<thead>
						<th>-</th>
						<?foreach($arr_plan_list as $plan_info){?>
						<th><?=print_ins($plan_info["ins_cd"]);?> <?=$Arr_plan_cd[$plan_info["plan_cd"]]?></th>
						<? } ?>
					</thead>
					<tbody style="text-align:center;">
					<? if(count($arr_insuplus) > 0 ) { ?>
					<tr>
						<th>상품가격</th>
						<? foreach($arr_plan_amount as $plan_amount){ 
							if($plan_amount){?>
								<td><?= $plan_amount ?>원</td>
							<? }
						} ?>
					</tr>
					<?}?>
					</tbody>
				</table>
			</div>

			<? if(count($arr_guarantee) > 0 ) { ?>
			<div class="row" style="margin-top: 20px;">
				<h3>3. 보장내역</h3>
			</div>
			<div class="row">
				<table class="table table-bordered">
					<thead>
						<th>보장항목</th>
						<?foreach($arr_plan_list as $plan_info){?>
						<th><?=print_ins($plan_info["ins_cd"]);?> <?=$Arr_plan_cd[$plan_info["plan_cd"]]?></th>
						<? } ?>
					</thead>
					<tbody style="text-align:center;">
						<? foreach($arr_guarantee as $guarantee){ ?>
							<tr>
								<td><?= $guarantee["service_name"] ?></td>
								<?for($k=0;$k<count($arr_plan_list);$k++){?>
									<td><?=fn_plan_gua($arr_plan_list[$k]["seq"],$guarantee["idx"])?></td>
								<?}?>
							</tr>
						<?}?>
					</tbody>
				</table>
			</div>
			<?}?>

			<form name="frm1" method="post">
			<input type="hidden" name="PR_SEQ" value="<?=$PR_SEQ?>" />
			<input type="hidden" name="GROUP_SEQ" value="<?=$group_seq?>" />
			<input type="hidden" name="o_name" value="<?=$name?>" />
			<input type="hidden" name="group_join_type" value="<?=$group_join_type?>">
			<input type="hidden" name="file_path" value="<?=$file_path?>">
			<input type="hidden" name="purpose" value="<?=$purpose?>">
			<input type="hidden" name="o_email" value="<?=$email?>">
			<input type="hidden" name="birthdate" value="<?=$birthdate?>">
			<input type="hidden" name="o_phone" value="<?=$phone?>">
			<input type="hidden" name="biz_num" value="<?=$biz_num?>">
			<input type="hidden" name="chk_p" value="<?=$chk_p?>">
			<input type="hidden" name="compare_seq[]" id="compare_seq1" value="" />
			<input type="hidden" name="compare_seq[]" id="compare_seq2" value="" />
			<input type="hidden" name="compare_seq[]" id="compare_seq3" value="" />
			<input type="hidden" name="joinType" value="<?=$joinType?>" />
			</form>
			
        </div>
<?php
	include '../_include/_tail.html';
	include '../_include/_footer.html';
?>

<script>

function chk_submit1(){
	var ff = document.frm1;

	if(!$("input:radio[name='plan_seq']:checked").val()) {
		alert("플랜을 선택해 주세요.");
		$("input:radio[name='plan_seq']").focus();
		return;
	}
	
	ff.action="./register_step_02.php";
	ff.submit();
}

//견적서 다운로드
function downEstimate(){
	var ff = document.frm1;
	<? if ( $detect->isMobile() ) { ?>
	var pop_title = "popupOpener" ;
    
    window.open("", pop_title,"width=100,height=100") ;
    ff.target = pop_title ;
	<? } ?>
	ff.action = "../join/group_join_confirm_view_pdf.php";
	ff.submit();
	
}

jQuery(document).ready(function () {

	<? if($_POST["joinType"] != "2") {?>
		jQuery('#modal_iframe').attr('src','./pop_register_noti.php');
		jQuery('#pop_modal').find('#modal_document').addClass('modal-sm');
		jQuery('#pop_modal .modal-header h3').html('실손의료보험<br>중복가입 유의사항');
		jQuery('#pop_modal').modal('show');
		jQuery('[data-toggle="pop-modal"]').click(function(){
			var link_href = jQuery(this).data("href");
		});
	<? } ?>
		
});	
</script>

<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php";

$param = array();
$param = $_POST;

error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_USER_DEPRECATED);
include $_SERVER["DOCUMENT_ROOT"]."/_util/PHPExcel-1.8/Classes/PHPExcel.php";
if ($param["file_path"]){
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
	$GROUP_INFO_TABLE["chk_p"] = $param["chk_p"];
	$totalCnt = 0;

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
                if($arr_period["day"] > 90) {
                    alert_page("가입기간은 90일까지 가입가능합니다.","./search_group_join.php?PR_SEQ=".$PR_SEQ);
                    exit;
                }
                $GROUP_INFO_TABLE["period"][$i-2] = $arr_period["day"];
                $GROUP_INFO_TABLE["period_month"][$i-2] = $arr_period["month"];
            } else { //장기
                $t_s_date = explode( ' ', $GROUP_INFO_TABLE["s_date"][$i-2]);
                $t_e_date = explode( ' ', $GROUP_INFO_TABLE["e_date"][$i-2]);
                $arr_period = getArrPeriod($t_s_date[0],$t_e_date[0],$chk_p);
                if($arr_period["day"] > 365) {
                    alert_page("가입기간은 365일까지 가입가능합니다.","./search_group_join.php?PR_SEQ=".$PR_SEQ);
                    exit;
                }
                $GROUP_INFO_TABLE["period"][$i-2] = $arr_period["day"];
                $GROUP_INFO_TABLE["period_month"][$i-2] = $arr_period["month"];
            }
            $totalCnt++;
        }		
	}
	$data = getSearchGroupPlanListOfPr_cd($PR_SEQ, $param["insurplus_check"], $param["sort2"], $GROUP_INFO_TABLE);
}
?>

<input type="hidden" name="group_join_cnt" value="<?=$totalCnt;?>">
<?
$k = 1;
if($data) {
		foreach($data as $row_plan_com){
			
		$arr_service_txt = explode(chr(10),$row_plan_com["service_txt"]);
		$arr_plan_txt = explode(chr(10),$row_plan_com["content"]);
		
		$plan_name = $Arr_plan_cd[$row_plan_com["plan_cd"]];
		
		$chk_sel = false;
		
		for($z=0; $z<count($_POST["compare_seq"]); $z++) {
			if($row_plan_com["seq"] == $_POST["compare_seq"][$z])  $chk_sel = true;
		}
		$SERVICE_NAME = '';
		if($row_plan_com["chk_service"] == "A" || $row_plan_com["chk_service"] == "B"){
			$SERVICE_NAME = "인슈플러스".$row_plan_com["chk_service"];
		} else {
			$SERVICE_NAME = $Arr_txt_plus[$row_plan_com["chk_service"]];
		}
		?>
		<div class="col-md-3 col-sm-6">
			<div class="list <?=$chk_sel ? "active":"";?>">
				<div class="list_select_wrap clearfix" <? if($chk_sel) {?>id="list_<?=$row_plan_com["seq"];?>"<? } ?> 
				<? if($row_plan_com["joinChk"]=="Y") {?>
				onClick="chkPlan(this,'<?=$row_plan_com["seq"]?>','<?=print_ins($row_plan_com["ins_cd"]);?>','<?=$Arr_plan_cd[$row_plan_com["plan_cd"]]?>','<?=$row_plan_com["chk_service"]?>','<?=print_ins_img($row_plan_com["ins_cd"])?>','<?=$row_plan_com["amount_".$row_plan_com["seq"]]?>')"
				<? } else { ?>
				onClick="alert('가입이 불가능 합니다.')"
				<? }?>
				>
					<div class='clearfix'><img src="<?=print_ins_img($row_plan_com["ins_cd"])?>" class="pull-right logo-partners" /></div>
					<h3><?=$plan_name;?> <?=$Arr_img_plus[$row_plan_com["chk_service"]]?></h3>
					<ul>
						<?for ($i=0;$i<count($arr_service_txt);$i++){?>
						<li><span class='point'><?=$arr_service_txt[$i]?></span></li>
						<?}?>
						<?for ($i=0;$i<count($arr_plan_txt);$i++){?>
						<li><?=$arr_plan_txt[$i]?></li>
						<?}?>
					</ul>
					<h4>
						<? if($row_plan_com["joinChk"]=="Y") {?>
							<?=number_format($row_plan_com["amount_".$row_plan_com["seq"]])?>원
						<? } else { ?>
							<span style="font-size:14px;"><?=$row_plan_com["reason_txt"];?></span>
						<? } ?>
						<small class='text-black'><?=$row_plan_com["plan_isdn"]?></small>
					</h4>
				</div>
				<div class='btn-group btn-group-justified'>
					<a class='btn btn-theme-dark <? if($chk_sel) {?>disabled<? } ?>' data-toggle='pop-modal' data-size='lg' data-href='./pop_warranty.php?pr_cd=<?=$PR_SEQ?>&seq=<?=$row_plan_com["seq"]?>' data-title='보장내역 보기' target='modal_iframe' onClick="openPop(this)">보장내역 보기</a>
					<?if ($row_plan_com["chk_service"]!="N"){?>
					<a class='btn btn-theme-bg <? if($chk_sel) {?>disabled<? } ?>' data-toggle='pop-modal' data-size='lg' data-href='../insuplus/pop_insuplus.php' data-title='인슈플러스' target='modal_iframe' onClick="openPop(this)"><?=$SERVICE_NAME?> 보기</a>
					<?}?>
				</div>
			</div>
		</div>
	
	<?$k++;}
}?>
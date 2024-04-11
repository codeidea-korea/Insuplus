<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php";

$param = array();
$param = $_POST;

$data = getPlanListOfPr_cd($PR_SEQ,$param);
?>

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
				<div class="list_select_wrap clearfix" <? if($chk_sel) {?>id="list_<?=$row_plan_com["seq"];?>"<? } ?> onClick="chkPlan(this,'<?=$row_plan_com["seq"]?>','<?=print_ins($row_plan_com["ins_cd"]);?>','<?=$Arr_plan_cd[$row_plan_com["plan_cd"]]?>','<?=$row_plan_com["chk_service"]?>','<?=print_ins_img($row_plan_com["ins_cd"])?>')">
					<div class='clearfix'>
						<!-- <img src="<?=print_ins_img($row_plan_com["ins_cd"])?>" class="pull-right logo-partners" /> -->
					</div>
					<h3><?=$plan_name;?> <?=$Arr_img_plus[$row_plan_com["chk_service"]]?></h3>
					<ul>
						<?for ($i=0;$i<count($arr_service_txt);$i++){?>
						<li><span class='point'><?=$arr_service_txt[$i]?></span></li>
						<?}?>
						<?for ($i=0;$i<count($arr_plan_txt);$i++){?>
						<li><?=$arr_plan_txt[$i]?></li>
						<?}?>
					</ul>
					<h4><?=number_format($row_plan_com["common_amount"])?>원
					<small class="text-black"><?=$row_plan_com["plan_isdn"]?></small>
					</h4>
					
				</div>
				<!-- <div class='btn-group btn-group-justified'>
					<a class='btn btn-theme-dark <? if($chk_sel) {?>disabled<? } ?>' data-toggle='pop-modal' data-size='lg' data-href='./pop_warranty.php?pr_cd=<?=$PR_SEQ?>&seq=<?=$row_plan_com["seq"]?>' data-title='보장내역 보기' target='modal_iframe' onClick="openPop(this)">보장내역 보기</a>
					<?if ($row_plan_com["chk_service"]!="N"){?>
					<a class='btn btn-theme-bg <? if($chk_sel) {?>disabled<? } ?>' data-toggle='pop-modal' data-size='lg' data-href='../insuplus/pop_insuplus.php' data-title='인슈플러스' target='modal_iframe' onClick="openPop(this)"><?=$SERVICE_NAME?> 보기</a>
					<?}?>
				</div> -->
			</div>
		</div>
	
	<?$k++;}
}?>
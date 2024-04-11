<?php
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

//print_r($_POST["com"]);
//for ($j=0;$j<count($_POST["com"]);$j++){
//	$arr_pr_seq = $arr_pr_seq.",".$_POST["com"][$j];
//}
//$SQL_COM = "select * from tbl_board_plan where pr_cd = ".$PR_SEQ." ";
////echo $SQL_COM;
//$result_plan_com = $dbcon -> query($SQL_COM);
?>

					<li class='title'>플랜선택</li>
					<?for ($k=0;$k<3;$k++){
					$kk = print_plan_info($_POST["com"][$k],"plan_cd");
					if ($_POST["com"][$k]){$plan_subject = $Arr_plan_cd[$kk];}else{$plan_subject="";}
					?>
						<?if ($plan_subject){?>
						<li class='select_plan active'><a href="javacript: sel_plan(<?=$_POST["com"][$k]?>)"><?=$plan_subject?></a></li>
						<?}else{?>
						<li class='select_plan'><a href="#">플랜을 선택해 주세요</a></li>
						<?}?>
					<?}?>

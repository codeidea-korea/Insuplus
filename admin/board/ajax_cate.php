<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";

	if ($bc_id && $cate_idx){
		$SQL_ins1 = "select cate_name_en from tbl_category where bc_id='".$bc_id."' and idx='".$cate_idx."' ";
		$rs_ins1 = $dbcon -> query($SQL_ins1);
		$rows = $dbcon -> fetch_array($rs_ins1);
		echo $rows["cate_name_en"];
	}
?>
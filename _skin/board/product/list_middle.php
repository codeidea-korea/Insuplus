<?
//사용자 모드입니다
	if ($client_mode=="Y"){
?>
<?
	}else{
		if ($list_type == "list") { 
			$PRD_CAT_MAPPING = getProductCatetoryMapping($seq);
			if(isset($PRD_CAT_MAPPING)){
				foreach($PRD_CAT_MAPPING as $item){
					if ($item['depth'] == '1'){
						$ext1 = $item['category_name'];
						break;
					}
				}
			}

			$sql = "
				select 
					a.product_seq
					, max(if (b.`depth` = 0, b.category_code, null)) depth0_code
					, max(if (b.`depth` = 0, b.category_name, null)) depth0_name
					, max(if (b.`depth` = 1, b.category_code, null)) depth1_code
					, max(if (b.`depth` = 1, b.category_name, null)) depth1_name
					, max(if (b.`depth` = 2, b.category_code, null)) depth2_code
					, max(if (b.`depth` = 2, b.category_name, null)) depth2_name
					, max(if (b.`depth` = 3, b.category_code, null)) depth3_code
					, max(if (b.`depth` = 3, b.category_name, null)) depth3_name
				from tbl_board_product_category a
					inner join tbl_board_category b on (a.category_code = b.category_code)
				where product_seq = $seq
				group by a.product_seq
			";
			$result = $dbcon -> query($sql);

			$row = $dbcon -> fetch_array($result)
?>
<tr>
	<td class="l" style="text-align: center;"><a href="javascript: mod_go('<?=$seq?>')"><?=$re?><?=$subject?> <?=$print_cmt_cnt?> <?=$sNew?></a></td>
	<td>
		<?=$row["depth0_name"] ?> <b style="margin-right:5px;margin-left:5px">/</b>
		<?=$row["depth1_name"] ?> <b style="margin-right:5px;margin-left:5px">/</b>
		<?=$row["depth2_name"] ?> <b style="margin-right:5px;margin-left:5px">/</b>
		<?=$row["depth3_name"] ?>
	</td>
	<td><?=$secret=="Y" ? "공개":"비공개";?></td>
	<td><?=$PrintRegDate?></td>
</tr>
<? 
		}elseif ($list_type == "null") { 
?>
<tr>
	<td colspan="6">등록 된 데이터가 없습니다.</td>
</tr>
<? 
		}else{ 
?>
.
<? 
		} 
?>

<?}?>
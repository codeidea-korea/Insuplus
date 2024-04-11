<?php
$mID=1;
$sID=4;
include "../include/header.html";
include "../include/sub_visual.html";
?>

<div id ="sub_content">
	<div id="sub_fixed">
		<?php
		include "../include/sub_leftmenu.html";
		?>
		<div id ="sub_main">
			<div id="sub_title"><img src="../images/subtitle0<?=$mID?>_<?=$sID?>.gif" /></div>
			<div id="sub_c_img01">
				<table width="632" border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td colspan="4">
								<?php
									$bc_id = "notice";
									include_once $path_board."board.php";
								?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
</div>

<?php
include "../include/footer.html";
?>

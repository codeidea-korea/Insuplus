<?php
include '../_include/_header.html';
include '../_include/_top.html';
include '../_include/_sidebar.html';

$lm = "0302"; //레프트 메뉴 활성화
$bc_id = "policy";
$policy_name = "개인정보 처리방침";
$today = date("Y-m-d");

$SQL  = " SELECT seq, content FROM tbl_board_policy where 1=1 ";
$SQL .=	" AND category = (SELECT idx FROM tbl_category WHERE bc_id = '" . $bc_id . "' AND cate_name = '" . $policy_name . "') ";
$SQL .= " AND start_date <= '" . $today . "' AND end_date >= '" . $today . "' ";
if (isset($_GET['sel_term'])) {
	$SQL .= " AND seq = '" . $_GET['sel_term'] . "'";
} else {
	$SQL .= " AND secret = 'N' ";
}
$SQL .=	" ORDER BY seq DESC  limit 0,1 ";

$RS = $dbcon->query($SQL);
$ROW = $dbcon->fetch_array($RS);
?>
<div class="breadcrumb-image">
	<div class="container">
		<h2>개인정보 처리방침</h2>
		<!-- <h4>빠른 시일 안에 답변 드리겠습니다.</h4> -->
	</div>
</div>
<div class="breadcrumb-wrap">
	<div class="container">
		<ol class="breadcrumb">
			<li><a href="../">InsuPlus HOME</a></li>
			<li>정책</li>
			<li>개인정보 처리방침</li>
		</ol>
	</div>
</div>
<div class="overflow-hidden">
	<div class="container">
		<div class="row">
			<div class="col-md-2 col-sm-3">
				<?php
				include '../_include/_terms_left.html';
				?>
			</div>
			<div class="col-md-10 col-sm-9 grid text-right" style="margin: 10px 0px">
				<div>
					<? /*
					<form id="term_form" name="term_form" method="GET" action="/html/terms/process.php">
						<?
						$term_history_SQL = "SELECT seq, subject FROM tbl_board_policy where 1=1
									AND category = (SELECT idx FROM tbl_category WHERE bc_id = '" . $bc_id . "' AND cate_name = '" . $policy_name . "')
									ORDER BY seq DESC";

						$term_history_RS = $dbcon->query($term_history_SQL);
						?>
						<select id="sel_term" name="sel_term" class="selectpicker" style="width: 200px;" onchange="term_history_oncahnger();">
							<? while ($term_history_ROW = $dbcon->fetch_array($term_history_RS)) { ?>
								<option 
									value="<?= $term_history_ROW["seq"] ?>" 
									<? if($ROW["seq"] == $term_history_ROW["seq"]) {echo "selected"; } ?>><?= $term_history_ROW["subject"] ?>
								</option>
							<? } ?>
						</select>
						<script>
							term_history_oncahnger = () => {
								$('#term_form').submit();
							}
						</script>
					</form>
					*/
					?>
				</div>
			</div>
			<div class="col-md-10 col-sm-9">
				<div class="sub-content cs-wrap terms_content">
					<?= $ROW["content"] ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
include '../_include/_tail.html';
include '../_include/_footer.html';
?>
</script>
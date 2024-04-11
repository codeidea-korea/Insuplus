<?php
	include '../_include/_header.html';
	include '../_include/_top.html';
	include '../_include/_sidebar.html';
	
	$lm = "0301"; //레프트 메뉴 활성화
	$bc_id = "policy";		
	$policy_name = "사이트 이용약관";
	$today = date("Y-m-d");
	
	$SQL  = " SELECT content FROM tbl_board_policy where secret = 'N' ";
	$SQL .=	" AND category = (SELECT idx FROM tbl_category WHERE bc_id = '".$bc_id."' AND cate_name = '".$policy_name."') ";
	$SQL .= " AND start_date <= '".$today."' AND end_date >= '".$today."' ";
	$SQL .=	" ORDER BY seq DESC  limit 0,1 ";
	
	$RS = $dbcon -> query($SQL);
	$ROW = $dbcon -> fetch_array($RS);
	
?>

<div class="breadcrumb-image">
	<div class="container">
		<h2>이용약관</h2>
		<!-- <h4>빠른 시일 안에 답변 드리겠습니다.</h4> -->
			</div>
		</div>
		<div class="breadcrumb-wrap">
            <div class="container">
				<ol class="breadcrumb">
					<li><a href="../">InsuPlus HOME</a></li>
					<li>정책</li>
					<li>이용약관</li>
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
					
					<div class="col-md-10 col-sm-9">
						<div class="sub-content cs-wrap terms_content">
							<?=$ROW["content"]?>
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


<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	$policy_name = $_GET["policy_name"];
	$bc_id = "policy";
	$today = date("Y-m-d");
	
	$SQL  = " SELECT content FROM tbl_board_policy where secret = 'N' ";
	$SQL .=	" AND category = (SELECT idx FROM tbl_category WHERE bc_id = '".$bc_id."' AND cate_name = 'STEP3_이용약관') ";
	$SQL .= " AND start_date <= '".$today."' AND end_date >= '".$today."' ";
	$SQL .= " AND subject = '".$policy_name."' ";
	$SQL .=	" ORDER BY seq DESC limit 0,1 ";

    // echo $SQL;
	$RS = $dbcon -> query($SQL);
	$ROW = $dbcon -> fetch_array($RS);

	$result = 
	'<div class="popup-head">
		<h3>'.$policy_name.'</h3>
		<a href="javascript:;" class="close" onclick="popupClose();">닫기</a>
	</div>
	<div class="popup-body">
		<div class="popup-body-agree">
			'.$ROW["content"].'
		</div>
		<div class="popup-body-button">
			<a href="javascript:;" onclick="popupClose();">확인</a>
		</div>
	</div>';  

?>
<?= $result;?>
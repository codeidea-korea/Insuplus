<!DOCTYPE html>
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	
	$bc_id = "main_banner";
	
	#### 게시판 설정 가져오기
	$field = " * ";
	$table = "config_board_list";
	$where = " and bc_id = '".$bc_id."' ";
	$orderby = " bc_id asc ";
	$limit = " 0, 1 ";
	$ArrListRs = $dbcon -> getList($field = "*", $table , $where, $orderby, $limit);
	if ( $ArrListRs[0] == 0 ) {
		$dbcon -> dbcon_close();
		alert_back("잘못된 게시판 정보입니다.1");
		exit;
	}
	$ListRs = $dbcon -> fetch_array($ArrListRs[1]);
	extract($ListRs);
	unset($ListRs);
	unset($ArrListRs);
	
	// 설정 추가
	include_once $path_skin_board.$bc_skin."/config.php";
	
	//데이터 추출
	
	$table = "tbl_board_main_banner";
	$field = "*";
	$where = "AND secret <> 'Y' ";	
	
	$orderby = " exposure_order ASC ";
	$limit = $first.", ".$last;
		
	$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);	
	$total_record = $ArrListRs[0];

	#### 전체 페이지수를 계산한다.
	$total_page = ceil($total_record/$num_per_page);
	$no = $total_record - $first;
?>
<html>
<head>
<title>InsuPlus</title>
<link href="/_css/admin.css" rel="stylesheet" />
<script src="/admin/js/jquery.min.js"></script>
</head>
<script>
	function set_order() {
		var flag = 0;
		var len = $('#main_list tr').length;
		$('#main_list tr').each(function() {
			if (!this.rowIndex) return; // skip first row
			var teamName = $(this).find("td").eq(0).html(); 
			var num = $(this).find('input[type="hidden"]').val(); 
			var order = $(this).find('input[type="number"]').val();

			$.ajax({ type: "POST", url: "/_skin/board/main_banner/set_main_banner_roll.php",
			data: {bc_id : "<?=$bc_id?>", seq : num, exposure_order : order, },
			cache: false, 
			success: function(data){
				flag += parseInt($.trim(data));
				end_order(flag);
			}
			});
			
			function end_order(flag) {
				if(flag >= len && flag > 0){
					alert('노출순서변경을 완료하였습니다.');
					window.opener.location.reload();
					window.close();
				}
			}
		});
	}


</script>
<body>
<div class="popupWrap">
	<header>
		<h1>메인노출순서 변경</h1>
		<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
	</header>
	<div class="popContWrap">	
		<!-- (s) 리스트 영역 -->
		<table class="adm-list-tb">
			<colgroup>
				<col width="15%" />
				<col width="15%" />
				<col width="10%" />
			</colgroup>
			<tr>
				<th>노출순서변경</th>
				<th>썸네일</th>
				<th>등록일</th>
			</tr>
			<tbody id="main_list">
			<?
			if ( $total_record > 0 ) {
			$temp_num = 1;
			$temp_num_img=0;
				while($ListRs = $dbcon -> fetch_array($ArrListRs[1])) { 
					extract($ListRs);
					//unset($ListRs);
					
					$PrintRegDate = date('Y/m/d ', strtotime($regdate) );
					$print_list_image = "";
					$print_list_image_size = "";
					$image_view_width = $bc_upfile_image_thum_width;
					$image_view_height = $bc_upfile_image_thum_height;
					$FC_file = "";

					if ($bc_upfile_image == "Y" && $bc_upfile_image_thum == "Y" ) {
						$ObjFileName = "imgfile";

						if ( getLen(${$ObjFileName}) > 0 ) {
							${"Arr_".$ObjFileName} = setFileName(${$ObjFileName});

							if (${"Arr_".$ObjFileName}[0][1]){

								for ( $i = 0 ; $i < count(${"Arr_".$ObjFileName}); $i++) {

									if ( ${"info".$ObjFileName} = @getimagesize($upload_path."/".${"Arr_".$ObjFileName}[$i][1].".thumb") ) {
										${"info".$ObjFileName."width"} = ${"info".$ObjFileName}[0];
										${"info".$ObjFileName."height"} = ${"info".$ObjFileName}[1];

										if ( ${"info".$ObjFileName."width"} >= ${"info".$ObjFileName."height"}){
											if ( ${"info".$ObjFileName."width"} > $image_view_width ) {
												${"size".$ObjFileName} = " width=\"".$image_view_width."\" ";
											}
											else {
												${"size".$ObjFileName} = " width=\"".${"info".$ObjFileName."width"}."\" ";
											}
										}
										else {
											if ( ${"info".$ObjFileName."height"} > $image_view_height ) {
												${"size".$ObjFileName} = " height=\"".$image_view_height."\" ";
											}
											else {
												${"size".$ObjFileName} = " height=\"".${"info".$ObjFileName."height"}."\" ";
											}
										}

										$FC_file = $upload_url."/".${"Arr_".$ObjFileName}[$i][1].".thumb";
										$FC_file_img = $upload_url."/".${"Arr_".$ObjFileName}[$i][1];
										$print_list_image = "<img src=\"".$upload_url."/".${"Arr_".$ObjFileName}[$i][1].".thumb"."\" name=\"Image1\" width=\"".$image_view_width."\" height=\"".$image_view_height."\" border=\"0\" />";
										$print_list_image_url = $upload_url."/".${"Arr_".$ObjFileName}[$i][1].".thumb";
										break;
									}
								}
							}else{
										$print_list_image = "<img src=\"".$upload_url."/".${$ObjFileName}."\" name=\"Image1\" width=\"110\" height=\"102\" border=\"0\" />";
							}
						}else{
							if ($bc_id=="post"){
								$print_list_image = "";
							}else{
								$print_list_image = "<img src='/images/board/test_img.jpg' name=\"Image1\" width=\"111\" height=\"73\" border=\"0\" style=\"border:2px #CCCCCC solid;\" />";
							}
						}
					}
					else {
						if ($bc_id=="post"){
							$print_list_image = "";
						}else{
							$print_list_image = "<img src='/images/board/test_img.jpg' name=\"Image1\" width=\"111\" height=\"73\" border=\"0\" style=\"border:2px #CCCCCC solid;\" />";
						}
					}
				?>
				<tr>
					<td>
					<input type="hidden" name="seq" value="<?=$seq?>" />
					<input type="number" name="exposure_order" value="<?=$exposure_order?>" style="width:100%" maxlength="150" class="input"/>
					</td>
					<td><?=$print_list_image?></td>
					<td><?=$PrintRegDate?></td>
				</tr>
				<? } ?>
			<? } else { ?>
			<tr>
				<td colspan="3">등록 된 데이터가 없습니다.</td>
			</tr>
			<? } 
			unset($ArrListRs);
			?>
			</tbody>
		</table>
		<!-- (e) 리스트 영역-->
			
		<div class="btnWrapR">
				<a href="javascript:set_order();" class="btn_add">노출순서변경</a>
		</div>
	</div>
</div>
</body>
</html>


<? $dbcon -> dbcon_close();?>

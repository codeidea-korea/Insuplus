<!DOCTYPE html>
<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
// 관리자 체크
admin_chk($auth_admin, $url_admin_login_out);

$tm = "";
$lm = "";
include $path_admin."inc/header_pop.php";
	
// 쿼리설정
$field			= " * ";

$table			= " tbl_board_main_recommend ";
$where	 		= "AND secret <> 'Y' ";
$orderby 		= " exposure_order ASC ";
$limit			= " ";

$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);
$total_record	= $ArrRS[0];
$result			= $ArrRS[1];
unset($ArrRS);

?>
<div class="popupWrap">
	<header>
		<h1>메인 추천 관리 노출순서 변경</h1>
		<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
	</header>
	
	<div class="popContWrap mt20">
		
		<form name="frm" method="post">
		<input type="hidden" name="mode" value="list_mod">
		<input type="hidden" name="bc_id" value="<?=$bc_id?>">
		<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
		<input type="hidden" name="search" value="<?=$search?>">
		<input type="hidden" name="search_text" value="<?=$search_text?>">
		<input type="hidden" name="page" value="<?=$page?>">
		<table class="adm-list-tb">
		<colgroup>
			<col width="10%" />
			<col width="*" />
			<col width="15%" />
		</colgroup>
			<tr>
				<th>노출순서변경</th>
				<th>제목</th>
				<th>등록일</th>
			</tr>
			<tbody id="main_list">
			<?if ( $total_record > 0 ) {
				while($ListRs = $dbcon -> fetch_array($result)) { 
					extract($ListRs);?>
			<tr>
				<td>
					<input type="number" name="exposure_order" value="<?=$exposure_order?>"/>
					<input type="hidden" name="seq" value="<?=$seq?>" />
				</td>
				<td class="l"><?=$subject?></td>
				<td><?=$regdate?></td>
			</tr>
				<? } ?>
				
			<? } else { ?>
			<tr>
				<td colspan="3">등록 된 데이터가 없습니다.</td>
			</tr>
			<? } ?>
			</tbody>
		</table>
		</form>
		
		<div class="btnWrap">
			<a href="javascript:set_order();" class="btn_add">노출순서변경</a>
		</div>
	</div>
</div>
<script>
	function set_order() {
		var flag = 0;
		var len = $('#main_list tr').length;
		$('#main_list tr').each(function() {
			if (!this.rowIndex) return; // skip first row
			var num = $(this).find('input[type="hidden"]').val(); 
			var order = $(this).find('input[type="number"]').val();

			$.ajax({ type: "POST", url: "/_skin/board/main_recommend/set_main_recommend_roll.php",
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
</html>
<?
	$dbcon -> dbcon_close();
?>
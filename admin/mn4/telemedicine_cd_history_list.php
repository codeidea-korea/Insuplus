<!DOCTYPE html>
<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$tm = "MN4";
	$lm = "";
	include $path_admin."inc/header.php";
	
	// 쿼리설정
	// 쿼리설정
	$telemedic_list = array();
	$SQL = "SELECT 
			expire_date as expire_date
			,COUNT(expire_date) as total_cnt
			,COUNT(case when join_orderno IS NULL then 1 end) as available_cnt
			,COUNT(case when join_orderno IS NOT NULL then 1 end) as used_cnt
			from tbl_telemedicine_cd_list
			group by expire_date";
	$RS = $dbcon -> query($SQL);
	
	while($row = $dbcon -> fetch_array($RS)){
		$telemedic_list[] = $row;
	}
?>
<script type="text/javascript">
 $(document).ready(function() {
var clareCalendar = {<?=$calendar_opt?>});
</script>
<script>
function write_go(){
	location.href = "telemedicine_cd_reg.php?<?=$parameter?>";
}
</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">원격진료 코드</td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<!-- 190725 수정 800px->100% -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
			<form name="frm" method="post">
			<input type="hidden" name="mode" value="list_mod">
			<input type="hidden" name="bc_id" value="<?=$bc_id?>">
			<input type="hidden" name="pr_seq" value="<?=$pr_seq?>">
			<input type="hidden" name="search" value="<?=$search?>">
			<input type="hidden" name="search_text" value="<?=$search_text?>">
			<input type="hidden" name="page" value="<?=$page?>">

			
			<div class="btnWrap mt20">
				<div class="leftWrap"></div>
				<div class="rightWrap">
					<a class="btn_add" href="javascript: write_go();">코드 추가</a>
				</div>
			</div>
			<table class="adm-list-tb">
			<colgroup>
				<col width="10%" />
				<col width="10%" />
				<col width="10%" />
				<col width="10%" />
			</colgroup>
				<tr>
					<th>만료기간</th>
					<th>발행건수</th>
					<th>사용 가능한 건수</th>
					<th>사용한 코드수</th>
				</tr>
				<tbody>
				<? if (count($telemedic_list) == 0) { ?>
				<tr>
					<td colspan="4"><?=$GLOBALS["msg_list_notdata"]?></td>
				</tr>
				<?
					} else {
						foreach($telemedic_list as $item) {
				?>
					<tr>
						<td><?=$item["expire_date"]?>개월</td>
						<td><?=number_format($item["total_cnt"])?>건</td>
						<td><?=number_format($item["available_cnt"])?>건</td>
						<td><?=number_format($item["used_cnt"])?>건</td>
					</tr>
				<?
						}
					}	
				?>
				</tbody>
			</table>
			</form>
		</td>
	</tr>
</table>
<? include $path_admin."inc/footer.php";
	$dbcon -> dbcon_close();
?>
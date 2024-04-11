<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

$idx = REQSTR($idx, "");

// 정보 수정시
if ( strlen($idx) > 0 ) {
    //		if ( $ss_u_level < $auth_admin && $ss_idx != $idx ) {
    //			alert_back($msg_login_auth);
    //			exit;
    //		}

    $SQL = "
    select
    idx, code, pr_name
    from
    tbl_symptom A
    where
    A.idx = '".$idx."'
	order by idx asc
	limit 0,1
    ";
    $result = $dbcon -> query($SQL);
	$rows = $dbcon -> fetch_array($result);
	if ($rows["pr_name"]){
		$ar_pr_list = explode("@",$rows["pr_name"]);
	}
}

// 시술프로그램 검색
    $SQL = "
    select
    seq, subject
    from
    tbl_board_pr
	order by seq asc
    ";
    $result = $dbcon -> query($SQL);
	$k = 0;
	while ($row = $dbcon -> fetch_array($result)){
		$pr_code[$k] = $row["seq"];
		$pr_name[$k] = $row["subject"];
	$k++;
	}

$tm = "board";
$lm = "";
include $path_admin."inc/header.php";

$url_skin_member = "/_skin/member/default/";
?>

<form name="frm" method="post" action="pr_write_ok.php" enctype='multipart/form-data' onSubmit="return chk_submit()">
<input type="hidden" name="idx" value="<?=$idx?>">


<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="a_st">증상별 프로그램</td>
		<td align="right"></td>
	</tr>
</table>

<table width="800" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">증상</th>
					<td class="m_content">
						<select name="code">
							<option value="">:: 선택하세요 ::</option>
							<?foreach ($Arr_cost_detail_list as $key => $val) {?>
							<option value="<?=$key?>" <? if ("".$key == $rows[code]) echo "selected";?>><?=$val?></option>
							<?}?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">시술프로그램</th>
					<td class="m_content">
						<?for ($k=0;$k<30;$k++){?>
							<select name="prs[]">
								<option value="">:: 선택하세요 ::</option>
								<?for ($J=0;$J<sizeof($pr_name);$J++) {?>
								<option value="<?=$pr_code[$J]."||".$pr_name[$J]?>" <? if ($pr_code[$J]."||".$pr_name[$J] == $ar_pr_list[$k]) echo "selected";?>><?=$pr_name[$J]?></option>
								<?}?>
							</select>
						<?}?>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
			</table>


			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="80" align="center">
						<?
						if ( $idx )
						    $ok_img = $url_skin_member."images/m_btn_modify.gif";
						else
						    $ok_img = $url_skin_member."images/m_btn_join.gif";
						?>
						<input type="image" src="/_skin/board/faq/images/b_btn_submit.gif" hspace="4">
						<a href="pr_list.php"><img src="<?=$url_skin_member?>images/m_btn_cancle.gif" width="76" height="28"></a>
					</td>
				</tr>
			</table>
			</form>
		</td>
	</tr>
</table>

<script>
TempGo = 0;
function chk_submit() {

	var ff = document.frm;

	if (ff.code.value == "") {
		alert("증상을 입력하여 주십시오.");
		ff.code.focus();
		return false;
	}
}

function chg_content(div){
	window.open('area_desc_pop.php?idx=<?=$idx?>&name='+div,'_pop','width=600,height=600');
}
</script>

<?
$dbcon -> dbcon_close();
?>
<?
include $path_admin."inc/footer.php";
?>

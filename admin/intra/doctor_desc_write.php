<?
include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

$tm = "board";
$lm = "";
include $path_admin."inc/header.php";

$idx = REQSTR($idx, "");

// 정보 수정시
if ( strlen($idx) > 0 ) {
    //		if ( $ss_u_level < $auth_admin && $ss_idx != $idx ) {
    //			alert_back($msg_login_auth);
    //			exit;
    //		}

    $SQL = "
    select
    *
    from
    tbl_desc_doctor A
    where
    A.idx = '".$idx."'
    limit 0, 1
    ";
    $result = $dbcon -> query($SQL);
    $rows = $dbcon -> fetch_array($result);
    extract($rows);
    unset($rows);
}

$url_skin_member = "/_skin/member/default/";

$parameter = "&search_u_level=".$search_u_level."&search_u_gubun=".$search_u_gubun."&search_u_state=".$search_u_state."&search_u_sex=".$search_u_sex."&search=".$search."&search_text=".$search_text."&search_orderby=".$search_orderby."&search_sort=".$search_sort."&num_per_page=".$num_per_page;

// 의료진검색
$SQL = "select u_id, u_name from tbl_user where u_level in ('5') order by u_name ";
$RS = $dbcon -> query($SQL);
?>

<form name="frm" method="post" action="doctor_desc_write_ok.php" enctype='multipart/form-data' onSubmit="return chk_submit()">
<input type="hidden" name="idx" value="<?=$idx?>">
<script type="text/javascript">
<!--
function chg_doctor(val){
	var arr = val.split("||");
	document.frm.member_id.value = arr[0];
	document.frm.member_name.value = arr[1];
}
//-->
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="a_st">의료진 약력</td>
		<td align="right"></td>
	</tr>
</table>

<table width="700" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td colspan="2" class="m_line_2px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">지점선택</th>
					<td class="m_content">
						<select name="area_code" class="select">
						<?
						foreach ($Arr_h_area2 as $key => $val) {
						?>
						<option value="<?=$key?>" <? if ("".$key == $area_code) echo "selected";?>><?=$val?></option>
						<?
						}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">의료진선택</th>
					<td class="m_content">
						<select name="member_sel" class="select" onchange="chg_doctor(this.value);">
						<option value="||">:: 선택하세요 ::</option>
						<?while ( $row = $dbcon -> fetch_array($RS) ) {?>
						<option value="<?=$row["u_id"]?>||<?=$row["u_name"]?>" <? if ($row["u_id"] == $member_id) echo "selected";?>><?=$row["u_name"]?></option>
						<?}?>
						</select>
						<input type="hidden" name="member_id" value="<?=$member_id?>">
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">이름</th>
					<td class="m_content"><input name="member_name" type="text" class="m_input" style="width:120px" value="<?=$member_name?>">
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">사진</th>
					<td class="m_content"><input name="doc_file" type="file" class="m_input" style="width:300px">
					<?if ($doctor_pic){?><img src="/_data/doctor_pic/<?=$doctor_pic?>"><?}?>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">약력 및 설명</th>
					<td class="m_content"><textarea name="desc_txt" class="m_input" style="width:98%;height:250px"><?=$desc_txt?></textarea></td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">저서</th>
					<td class="m_content"><textarea name="book1" class="m_input" style="width:98%;height:250px"><?=$book1?></textarea></td>
				</tr>
				<tr>
					<td colspan="2" class="m_line_1px">&nbsp;</td>
				</tr>
				<tr>
					<th class="m_txt">논문</th>
					<td class="m_content"><textarea name="book2" class="m_input" style="width:98%;height:250px"><?=$book2?></textarea></td>
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
						<a href="doctor_desc.php?<?=$parameter?>"><img src="<?=$url_skin_member?>images/m_btn_cancle.gif" width="76" height="28"></a>
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

	if (ff.member_id.value == "") {
		alert("의료진을 선택해주십시오.");
		ff.member_sel.focus();
		return false;
	}

	if (ff.desc_txt.value == "") {
		alert("약력 및 설명을 입력하여 주십시오.");
		ff.desc_txt.focus();
		return false;
	}


}
</script>

<?
$dbcon -> dbcon_close();
?>
<?
include $path_admin."inc/footer.php";
?>

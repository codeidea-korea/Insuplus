<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$tm = "site";
	$lm = "";
	include $path_admin."inc/header.php";
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">사이트 환경설정</td>
	</tr>
	<tr>
		<td colspan="2" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>


<form name="SiteConfigForm" action="" method="post" onsubmit="return next_go()">
<input type="hidden" name="sc_idx" value="<?=$sc_idx?>">


<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 기본설정</td>
	</tr>
	<tr>
		<td height="2"></td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">사이트명</td>
		<td class="a_content">
			<input type="text" style="width:90%;" name="sc_name" value="<?=$sc_name?>">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">사이트타이틀</td>
		<td class="a_content">
			<input type="text" style="width:90%;" name="sc_site_title" value="<?=$sc_site_title?>">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">관리자</td>
		<td class="a_content">
			<input type="text" style="width:90%;" name="sc_admin_name" value="<?=$sc_admin_name?>">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">대표이메일</td>
		<td class="a_content">
			<input type="text" style="width:90%;" name="sc_admin_email" value="<?=$sc_admin_email?>">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>
</table>


<!--
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 메타태그관리</td>
	</tr>
	<tr>
		<td height="2"></td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">Description</td>
		<td class="a_content">
			<input type="text" style="width:90%;" name="sc_meta_description" value="<?=$sc_meta_description?>">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">Keyword</td>
		<td class="a_content">
			<input type="text" style="width:90%;" name="sc_meta_keyword" value="<?=$sc_meta_keyword?>">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">Author</td>
		<td class="a_content">
			<input type="text" style="width:90%;" name="sc_meta_author" value="<?=$sc_meta_author?>">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">classification</td>
		<td class="a_content">
			<input type="text" style="width:90%;" name="sc_meta_classification" value="<?=$sc_meta_classification?>">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">Email</td>
		<td class="a_content">
			<input type="text" style="width:90%;" name="sc_meta_email" value="<?=$sc_meta_email?>">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>
</table>
 -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 환경설정</td>
	</tr>
	<tr>
		<td height="2"></td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">사이트 주소</td>
		<td class="a_content">
			<input type="text" style="width:90%;" name="sc_site_url" value="<?=$sc_site_url?>">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">사이트 설치경로</td>
		<td class="a_content">
			<input type="text" style="width:90%;" name="sc_site_root" value="<?=$sc_site_root?>">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<!-- <tr>
		<td class="a_txt">사이트 서버경로</td>
		<td class="a_content">
			<input type="text" style="width:90%;" name="sc_server_root" value="<?=$sc_server_root?>">
		</td>
	</tr> -->
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>
</table>






<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 결제정보</td>
	</tr>
	<tr>
		<td height="2"></td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">은행 계좌번호</td>
		<td class="a_content">
			<textarea class="textarea" rows="10" name="sc_bank_account" style="width:100%"><?=$sc_bank_account?></textarea>
			<BR>
			1. 무통장 입금 시의 입금은행입니다.<BR>
			2. 구분값 : 엔터
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 배송 관련 사항</td>
	</tr>
	<tr>
		<td height="2"></td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">배송비 면제 기준액</td>
		<td class="a_content">
			<input type="text" name="sc_delivery_free" value="<?=$sc_delivery_free?>" class="text" size="20" maxlength="255">
			원 (숫자만 입력하세요)
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">기본 배송료</td>
		<td class="a_content">
			<input type="text" name="sc_delivery_money" value="<?=$sc_delivery_money?>" class="text" size="20" maxlength="255">
			원 (숫자만 입력하세요)
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>
</table>







	<? if ($ss_u_level > $auth_admin) { ?>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 회원관련</td>
	</tr>
	<tr>
		<td height="2"></td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>


	<tr>
		<td class="a_txt">스킨</td>
		<td class="a_content">
			<select name="sc_skin_member" style="width:190" class="a_input">
			<?
				$dir = opendir($path_skin_member1);
				while($dir_list = readdir($dir)) {
					if(!($dir_list == "." or $dir_list == "..")) {
						if($dir_list == $sc_skin_member) {
							echo "<option value=$dir_list selected> $dir_list <br>";
						} else {
							echo "<option value=$dir_list> $dir_list <br>";
						}
					}
				}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">기업회원</td>
		<td class="a_content">
			<input type="radio" name="sc_member_company" value="Y" <? if ($sc_member_company == "Y") {echo "checked";}?>> 사용
			<input type="radio" name="sc_member_company" value="N" <? if ($sc_member_company == "N") {echo "checked";}?>> 사용안함
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">실명인증</td>
		<td class="a_content">
			<input type="radio" name="sc_member_name_check" value="Y" <? if ($sc_member_name_check == "Y") {echo "checked";}?>> 사용
			<input type="radio" name="sc_member_name_check" value="N" <? if ($sc_member_name_check == "N") {echo "checked";}?>> 사용안함
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">회원약관</td>
		<td class="a_content">
			<textarea name="sc_agree" style="width:90%" rows="10"><?=REQSTR2($sc_agree)?></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">개인정보 보호정책</td>
		<td class="a_content">
			<textarea name="sc_policy" style="width:90%" rows="10"><?=REQSTR2($sc_policy)?></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>


	<tr>
		<td class="a_txt">상단 include 파일</td>
		<td class="a_content">
			<input type="text" style="width:90%;" name="sc_member_top_include" value="<?=$sc_member_top_include?>">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">상단 내용</td>
		<td class="a_content">
			<textarea name="sc_member_top_content" style="width:90%" rows="10"><?=$sc_member_top_content?></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">하단 include 파일</td>
		<td class="a_content">
			<input type="text" style="width:90%;" name="sc_member_bottom_include" value="<?=$sc_member_bottom_include?>">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">하단 내용</td>
		<td class="a_content">
			<textarea name="sc_member_bottom_content" style="width:90%" rows="10"><?=$sc_member_bottom_content?></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>



	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>
</table>
	<? } ?>


<? if ($ss_u_level > $auth_admin) { ?>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 메뉴관리</td>
	</tr>
	<tr>
		<td height="2"></td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">회원관리</td>
		<td class="a_content">
			<input type="radio" name="sc_menu_member" value="Y" <? if ($sc_menu_member == "Y") {echo "checked";}?>> 사용
			<input type="radio" name="sc_menu_member" value="N" <? if ($sc_menu_member == "N") {echo "checked";}?>> 사용안함
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제품관리</td>
		<td class="a_content">
			<input type="radio" name="sc_menu_product" value="Y" <? if ($sc_menu_product == "Y") {echo "checked";}?>> 사용
			<input type="radio" name="sc_menu_product" value="N" <? if ($sc_menu_product == "N") {echo "checked";}?>> 사용안함
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">게시판관리</td>
		<td class="a_content">
			<input type="radio" name="sc_menu_board" value="Y" <? if ($sc_menu_board == "Y") {echo "checked";}?>> 사용
			<input type="radio" name="sc_menu_board" value="N" <? if ($sc_menu_board == "N") {echo "checked";}?>> 사용안함
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">팝업창관리</td>
		<td class="a_content">
			<input type="radio" name="sc_menu_popup" value="Y" <? if ($sc_menu_popup == "Y") {echo "checked";}?>> 사용
			<input type="radio" name="sc_menu_popup" value="N" <? if ($sc_menu_popup == "N") {echo "checked";}?>> 사용안함
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">설문조사관리</td>
		<td class="a_content">
			<input type="radio" name="sc_menu_poll" value="Y" <? if ($sc_menu_poll == "Y") {echo "checked";}?>> 사용
			<input type="radio" name="sc_menu_poll" value="N" <? if ($sc_menu_poll == "N") {echo "checked";}?>> 사용안함
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>
</table>

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 상단 및 하단 파일 또는 HTML 정보</td>
	</tr>
	<tr>
		<td height="2"></td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">상단 include 파일</td>
		<td class="a_content">
			<input type="text" style="width:90%;" name="sc_top_include" value="<?=$sc_top_include?>">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">상단 내용</td>
		<td class="a_content">
			<textarea name="sc_top_content" style="width:90%" rows="10"><?=$sc_top_content?></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">하단 include 파일</td>
		<td class="a_content">
			<input type="text" style="width:90%;" name="sc_bottom_include" value="<?=$sc_bottom_include?>">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">하단 내용</td>
		<td class="a_content">
			<textarea name="sc_bottom_content" style="width:90%" rows="10"><?=$sc_bottom_content?></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>
</table>


<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 상품관련</td>
	</tr>
	<tr>
		<td height="2"></td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">스킨</td>
		<td class="a_content">
			<select name="sc_skin_product" style="width:190" class="a_input">
			<?
				$dir = opendir($path_skin_product);
				while($dir_list = readdir($dir)) {
					if(!($dir_list == "." or $dir_list == "..")) {
						if($dir_list == $sc_skin_product) {
							echo "<option value=$dir_list selected> $dir_list <br>";
						} else {
							echo "<option value=$dir_list> $dir_list <br>";
						}
					}
				}
			?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>
</table>
<? } ?>
<!-- 		<tr>
		<td class="a_txt">
			회원레벨 닉네임 설정
		</td>
		<td style="padding:0px;">
			<table style="width:100%;margin:0px;">
				<tr>
					<td class="a_txt">10</td>
					<td class="a_content">
						<input type="text" style="width:90%;" name="cs_title" value="<?=$cs_title?>">
					</td>
				</tr>
				<tr>
					<td class="a_txt">9</td>
					<td class="a_content">
						<input type="text" style="width:90%;" name="cs_title" value="<?=$cs_title?>">
					</td>
				</tr>
				<tr>
					<td class="a_txt">0</td>
					<td class="a_content">
						<input type="text" style="width:90%;" name="cs_title" value="<?=$cs_title?>">
					</td>
				</tr>
				<tr>
					<td class="a_txt">0</td>
					<td class="a_content">
						<input type="text" style="width:90%;" name="cs_title" value="<?=$cs_title?>">
					</td>
				</tr>
				<tr>
					<td class="a_txt">0</td>
					<td class="a_content">
						<input type="text" style="width:90%;" name="cs_title" value="<?=$cs_title?>">
					</td>
				</tr>
				<tr>
					<td class="a_txt">0</td>
					<td class="a_content">
						<input type="text" style="width:90%;" name="cs_title" value="<?=$cs_title?>">
					</td>
				</tr>
				<tr>
					<td class="a_txt">0</td>
					<td class="a_content">
						<input type="text" style="width:90%;" name="cs_title" value="<?=$cs_title?>">
					</td>
				</tr>
				<tr>
					<td class="a_txt">0</td>
					<td class="a_content">
						<input type="text" style="width:90%;" name="cs_title" value="<?=$cs_title?>">
					</td>
				</tr>
				<tr>
					<td class="a_txt">0</td>
					<td class="a_content">
						<input type="text" style="width:90%;" name="cs_title" value="<?=$cs_title?>">
					</td>
				</tr>
				<tr>
					<td class="a_txt">0</td>
					<td class="a_content">
						<input type="text" style="width:90%;" name="cs_title" value="<?=$cs_title?>">
					</td>
				</tr>
				<tr>
					<td class="a_txt">0</td>
					<td class="a_content">
						<input type="text" style="width:90%;" name="cs_title" value="<?=$cs_title?>">
					</td>
				</tr>
			</table>
		</td>
	</tr> -->

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td colspan="2" align="center">
			<input type="image" src="../images/btn_sc_modify.gif">
			<a href="<?=$url_admin?>"><img src="../images/btn_sc_cancle.gif"></a>
		</td>
	</tr>
</table>
</form>
<script>
	TempGo = 0;
	function next_go() {

		if (TempGo > 0) {
			alert("한번만 눌러주세요!");
			return false;
		}
		TempGo = 1;
		ff = document.SiteConfigForm;

		if (ff.sc_idx.value == "") {
			alert("잘못된접근");
			return false;
		}
		if (ff.sc_name.value == "") {
			alert("사이트명을 입력하여 주십시오.");
			ff.sc_name.focus();
			return false;
		}

		ff.action = "site_config_ok.php";
		ff.submit();

	}
</script>
<!-- ### 페이지 끝 ###  -->
<? include $path_admin."inc/footer.php"; ?>

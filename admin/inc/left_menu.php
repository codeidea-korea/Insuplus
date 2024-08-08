<?
//echo "tm : ".$tm."<BR>";
?>

<?if ($tm == "main" ) {?>
<div class="leftMenutt">관리자메인</div>
<ul class="a_lm01">
	<li><a href="<?=$url_admin?>"> 관리자메인</a></li>
</ul>
<?}?>

<?
// 가입자관리
if ($tm == "MN1" && $ss_u_level ==6) {?>
<div class="leftMenutt">가입자 관리</div>
<ul class="a_lm01">
	<li><a href="<?=$url_admin?>mn1/join_ins_list.php">가입자</a></li>
</ul>
<?}?>

<?
// 가입자관리
if ($tm == "MN1" && $ss_u_level ==7) {?>
<div class="leftMenutt">가입자 관리</div>
<ul class="a_lm01">
	<li><a href="<?=$url_admin?>mn1/join_partner_list.php">가입자</a></li>
</ul>
<?}?>

<?
// 가입자관리
if ($tm == "MN1" && $ss_u_level >=8) {?>
<div class="leftMenutt">가입자 관리</div>
<ul class="a_lm01">
	<li><a href="<?=$url_admin?>mn1/join_list.php">가입자</a></li>
	<li><a href="<?=$url_admin?>mn1/pay_list.php">결제내역</a></li>
	<li><a href="<?=$url_admin?>mn1/group_join_list.php">단체가입</a></li>
	<li><a href="<?=$url_admin?>mn1/group_join_estimate_list.php">단체견적</a></li>
</ul>
<?}?>


<?
//상담관리
if ($tm == "MN2" ) {?>
<div class="leftMenutt">상담 관리</div>
<ul class="a_lm01">
	<li><a href="<?=$url_admin?>mn2/qna_list.php">고객 문의</a></li>
	<li><a href="<?=$url_admin?>mn2/charge_list.php">청구 관리</a></li>
	<li><a href="<?=$url_admin?>mn2/gop_list.php">GOP 관리</a></li>
</ul>
<?}?>



<?
//운영관리
if ($tm == "MN3" ) {?>
<div class="leftMenutt">운영관리</div>
<ul class="a_lm01">
	<li><a href="#">게시판관리</a>
		<ul class="a_lm01_sub">
			<li><a href="<?=$url_admin?>mn3/notice_list.php">공지사항</a></li>
			<li><a href="<?=$url_admin?>mn3/counsel_case_list.php">상담사례</a></li>
			<li><a href="<?=$url_admin?>mn3/compensation_case_list.php">보상사례</a></li>
			<!--<li><a href="<?=$url_admin?>/mn3/call_help_list.php">상담 도움말</a></li>-->
		</ul>
	</li>
	<li><a href="<?=$url_admin?>mn3/policy_list.php">약관 관리</a></li>
	<li><a href="<?=$url_admin?>mn3/restricted_users.php">가입자제한리스트</a></li>
</ul>
<?}?>

<?
//마케팅 관리
if ($tm == "MN4" ) {?>
<div class="leftMenutt">마케팅 관리</div>
<ul class="a_lm01">
	<li><a href="<?=$url_admin?>mn4/event_list.php">이벤트</a></li>
	<li><a href="<?=$url_admin?>mn4/partner_list.php">제휴사 관리</a></li>
	<li><a href="<?=$url_admin?>mn4/recommend_code_list.php">추천 코드</a></li>
	<li><a href="<?=$url_admin?>mn4/coupon_history_list.php">쿠폰</a></li>
	<li><a href="<?=$url_admin?>mn4/partner_coupon_history_list.php">제휴사 쿠폰</a></li>
	<li><a href="<?=$url_admin?>mn4/telemedicine_cd_history_list.php">원격진료 코드</a></li>
	<li><a href="<?=$url_admin?>mn4/main_banner_list.php">메인배너</a></li>
	<li><a href="<?=$url_admin?>mn4/main_recommend_list.php">메인 추천플랜</a></li>
</ul>
<?}?>

<?
//통계
if ($tm == "MN5" ) {?>
<div class="leftMenutt">통계</div>
<ul class="a_lm01">
	<li><a href="#">통계</a></li>
</ul>
<?}?>

<?
//보험상품관리
if ($tm == "MN6" ) {?>
<div class="leftMenutt">보험상품 관리</div>
<ul class="a_lm01">
	<li><a href="ins_list.php">보험사 관리</a></li>
	<li><a href="pr_list.php">상품 관리</a></li>
	<li><a href="plan_list.php">플랜 관리</a></li>
	<li><a href="guarantee.php">보장내역</a></li>
	<li><a href="service_list.php">인슈플러스</a></li>
	<li><a href="ins_agree.php">약관 관리</a>
		<ul class="a_lm01_sub">
			<li><a href="service_agree.php">서비스 이용약관</a></li>
			<li><a href="ins_agree.php">보험사 약관</a></li>
		</ul>
	</li>
</ul>
<?}?>


<?
// 회원관리
if ($tm == "member" ) {?>
<div class="leftMenutt">회원관리</div>
<ul class="a_lm01">
	<li><a href="<?=$url_admin?>member/member_list.php">회원 리스트</a></li>
	<?if ( $sc_menu_member == "Y" ) {?>
	<li><a href="<?=$url_admin?>member/member_write.php">신규회원 등록</a></li>
	<?}?>
	<li><a href="<?=$url_admin?>member/login_history.php">로그인 기록</a></li>
</ul>
<?}?>


<?if ($tm == "popup" ) {?>
<div class="leftMenutt">팝업관리</div>
<ul class="a_lm01">
	<li><a href="<?=$url_admin?>popup/popup_list.php">팝업관리</a></li>
</ul>
<?}?>


<?
if ($tm == "counter" ) {
?>
<table cellpadding="0" cellspacing="0" width="170">
<tr>
    <td align="center" height="50" class="leftMenutt">통계관리</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="170">
<tr>
    <td height="29" width="23" align="right" valign="top" style="padding-top:10px"><img src="<?=$url_admin?>images/admin_left_ball.gif" hspace="6"></td>
    <td valign="top" style="padding-top:10px" class="a_lm01">
        <a href="<?=$url_admin?>counter/counter.php">접속통계</a>
    </td>
</tr>
<tr>
    <td colspan="2" height="1" bgcolor="#E5E5E5"></td>
</tr>
</table>
<?
}
?>


<?if ($tm == "bbs" ) {?>
<div class="leftMenutt">게시판설정</div>
<ul class="a_lm01">
	<li><a href="<?=$url_admin?>bc_bbs/bbs_list.php">게시판 관리</a></li>
</ul>
<?}?>

<?if ($tm == "site" ) {?>
<div class="leftMenutt">환경설정</div>
<ul class="a_lm01">
	<li><a href="<?=$url_admin?>bc_site/site_config.php">환경설정</a></li>
</ul>
<?}?>


<?if ($tm == "board" ) {?>
<div class="leftMenutt">게시판관리</div>
<?
	$SQL = "
	select
	bc_id, bc_name
	from
	config_board_list
	where bc_id not like 'intra%'
	order by
	bc_name
	";
	$TempRs = $dbcon -> query($SQL);
?>
<ul class="a_lm01">
	<?while ( $TempRow = $dbcon->fetch_array($TempRs) ) {?>
	<li><a href="/admin/board/index.php?bc_id=<?=$TempRow["bc_id"]?>"><?=$TempRow["bc_name"]?></a></li>
	<?
			unset($TempRow);
		}
		unset($TempRs);
	?>
</ul>
<?}?>



<?
if ($tm == "log" ) {
?>
<table cellpadding="0" cellspacing="0" width="170">
<tr>
    <td align="center" height="50" class="leftMenutt">내부통계</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="170">
<tr>
    <td colspan="2" height="1" bgcolor="#E5E5E5"></td>
</tr>
<tr>
    <td height="29" width="23" align="right" valign="top" style="padding-top:10px"><img src="<?=$url_admin?>images/admin_left_ball.gif" hspace="6"></td>
    <td valign="top" style="padding-top:10px" class="a_lm01">
        <a href="http://www.google.com/intl/ko_ALL/analytics/" target="_blank">구글 로그분석</a>
    </td>
</tr>
<tr>
    <td colspan="2" height="1" bgcolor="#E5E5E5"></td>
</tr>
</table>
<?
}
?>

<?
if ($tm == "product" ) {
?>
<table cellpadding="0" cellspacing="0" width="170">
<tr>
    <td align="center" height="50" class="leftMenutt">제품관리</td>
</tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="170">
<tr>
    <td height="29" width="23" align="right" valign="top" style="padding-top:10px"><img src="<?=$url_admin?>images/admin_left_ball.gif" hspace="6"></td>
    <td valign="top" style="padding-top:10px" class="a_lm01">
        <a href="<?=$url_admin?>product/product_category.php">카테고리 관리</a>
    </td>
</tr>
<tr>
    <td colspan="2" height="1" bgcolor="#E5E5E5"></td>
</tr>
<tr>
    <td height="29" width="23" align="right" valign="top" style="padding-top:10px"><img src="<?=$url_admin?>images/admin_left_ball.gif" hspace="6"></td>
    <td valign="top" style="padding-top:10px" class="a_lm01">
        <a href="<?=$url_admin?>product/product_list.php">제품관리</a>
    </td>
</tr>
<tr>
    <td colspan="2" height="1" bgcolor="#E5E5E5"></td>
</tr>
</table>
<?
}
?>
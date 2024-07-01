<?
//echo "ss_u_level : ".$ss_u_level."<BR>";
//echo "auth_admin : ".$auth_admin."<BR>";
//echo "sc_menu_board : ".$sc_menu_board."<BR>";

?>
<script>
$(document).ready(function(){
	$(".insu_menu_wrap table.menu td").on("mouseover",function(){
		$(".insu_menu_wrap .subMenuBox").show();
	}).on("mouseout",function(){
		$(".insu_menu_wrap .subMenuBox").hide();
	})
})
</script>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="100%" height="55">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" class="insu_menu_wrap">
				<tr>
					<td width="15">&nbsp;</td>
					<td width="170"><a href="<?=$url_admin_index?>"><img src="<?=$url_admin?>/images/logo_top.png" alt="인슈플러스" /></a></td>
					<td width="15">&nbsp;</td>
					<td valign="top" align="left" style="padding-left:50px;">
						<table border="0" cellpadding="0" cellspacing="0" class="menu">
							<tr>
								<?if ( $ss_u_level ==6) {	// 가입자 관리?>
									<td><a href="<?=$url_admin?>mn1/join_ins_list.php">가입자 관리</a>
										<div class="subMenuBox border-left border-right">
											<ul>
												<li><a href="<?=$url_admin?>mn1/join_ins_list.php">가입자</a></li>
											</ul>
										</div>
									</td>
								<?} // 보험관리?>
								<?if ( $ss_u_level ==7) {	// 가입자 관리?>
									<td><a href="<?=$url_admin?>mn1/join_partner_list.php">가입자 관리</a>
										<div class="subMenuBox border-left border-right">
											<ul>
												<li><a href="<?=$url_admin?>mn1/join_partner_list.php">가입자</a></li>
											</ul>
										</div>
									</td>
								<?} // 보험관리?>
								<?if (  $ss_u_level >=8) {	// 가입자 관리?>
									<td><a href="<?=$url_admin?>mn1/join_list.php">가입자 관리</a>
										<div class="subMenuBox border-left">
											<ul>
												<li><a href="<?=$url_admin?>mn1/join_list.php">가입자</a></li>
												<li><a href="<?=$url_admin?>mn1/pay_list.php">결제내역</a></li>
												<li><a href="<?=$url_admin?>mn1/group_join_list.php">단체가입</a></li>
												<li><a href="<?=$url_admin?>mn1/group_join_estimate_list.php">단체견적</a></li>
											</ul>
										</div>
									</td>
								<?} // 보험관리?>
								<?if (  $ss_u_level >=8) {	// 상담 관리?>
									<td><a href="<?=$url_admin?>mn2/qna_list.php">상담 관리</a>
										<div class="subMenuBox">
											<ul>
												<li><a href="<?=$url_admin?>mn2/qna_list.php">고객문의</a></li>
												<li><a href="<?=$url_admin?>mn2/charge_list.php">청구 관리</a></li>
												<li><a href="<?=$url_admin?>mn2/gop_list.php">GOP 관리</a></li>
											</ul>
										</div>
									</td>
								<?}?>
								<?if (  $ss_u_level >=8) {	// 운영 관리?>
									<td><a href="<?=$url_admin?>mn3/notice_list.php">운영 관리</a>
										<div class="subMenuBox">
											<ul>
												<li><a href="<?=$url_admin?>mn3/notice_list.php">게시판관리</a>
													<ul>
														<li><a href="<?=$url_admin?>mn3/notice_list.php">- 공지사항</a></li>
														<li><a href="<?=$url_admin?>mn3/counsel_case_list.php">- 상담사례</a></li>
														<li><a href="<?=$url_admin?>mn3/compensation_case_list.php">- 보상사례</a></li>
														<!--<li><a href="<?=$url_admin?>mn3/call_help_list.php">- 상담 도움말</a></li>-->
													</ul>
												</li>
												<li><a href="<?=$url_admin?>mn3/policy_list.php">약관관리</a></li>
												<li><a href="<?=$url_admin?>mn3/restricted_users.php">가입자제한리스트</a></li>
											</ul>
										</div>
									</td>
								<?}?>
								<?if (  $ss_u_level >=8) {	// 운영 관리?>
									<td><a href="<?=$url_admin?>mn4/event_list.php">마케팅 관리</a>
										<div class="subMenuBox">
											<ul>
												<li><a href="<?=$url_admin?>mn4/event_list.php">이벤트</a></li>
												<li><a href="<?=$url_admin?>mn4/partner_list.php">제휴사 관리</a></li>
												<li><a href="<?=$url_admin?>mn4/recommend_code_list.php">추천 코드</a></li>
												<li><a href="<?=$url_admin?>mn4/coupon_history_list.php">쿠폰</a></li>
												<li><a href="<?=$url_admin?>mn4/partner_coupon_history_list.php">제휴사 쿠폰</a></li>
												<li><a href="<?=$url_admin?>mn4/telemedicine_cd_history_list.php">원격진료 코드</a></li>
												<li><a href="<?=$url_admin?>mn4/main_banner_list.php">메인 배너</a></li>
												<li><a href="<?=$url_admin?>mn4/main_recommend_list.php">메인 추천 관리</a></li>
											</ul>
										</div>
									</td>
								<?}?>
								<?if (  $ss_u_level >= 8) {	// 운영 관리?>
									<!--
									<td><a href="javascript:alert('준비중 입니다.')">통계</a>
										<div class="subMenuBox">
											<ul>
												<li><a href="javascript:alert('준비중 입니다.')">통계</a></li>
											</ul>
										</div>
									</td>-->
								<?}?>
								<?if (  $ss_u_level >= 8) {	// 운영 관리?>
									<td><a href="<?=$url_admin?>mn6/ins_list.php">보험상품 관리</a>
										<div class="subMenuBox border-right">
											<ul>
												<li><a href="<?=$url_admin?>mn6/ins_list.php">보험사 관리</a></li>
												<li><a href="<?=$url_admin?>mn6/pr_list.php">상품 관리</a></li>
												<li><a href="<?=$url_admin?>mn6/plan_list.php">플랜 관리</a></li>
												<li><a href="<?=$url_admin?>mn6/guarantee.php">보장내역</a></li>
												<li><a href="<?=$url_admin?>mn6/service_list.php">인슈플러스</a></li>
												<li><a href="<?=$url_admin?>mn6/ins_agree.php">상품약관</a></li>
											</ul>
										</div>

									</td>
								<?}?>






								<?
								if ( $sc_menu_member == "Y" && $ss_u_level > 8) {
							    // 회원관리
								?>
								<td><a href="<?=$url_admin?>member/member_list.php">회원관리</a></td>
								<?
								}
								if (  $ss_u_level > 8) {
							    // 게시판 관리
								?>
								<td><a href="<?=$url_admin?>board/">게시판관리</a></td>
								<?}?>
								<?if (  $ss_u_level > 8) {	// 게시판설정?>
								<td><a href="<?=$url_admin?>bc_bbs/bbs_list.php">게시판설정</a></td>
								<?}?>
								<?if ($sc_menu_popup == "Y" && $ss_u_level > 8) {		// 팝업창관리?>
								<td><a href="<?=$url_admin?>popup/popup_list.php">팝업창관리</a></td>
								<?}?>
								<?if ( $ss_u_level == 10 ) {?>
								<td><a href="<?=$url_admin?>bc_site/site_config.php">환경설정</a></td>
								<?}?>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>


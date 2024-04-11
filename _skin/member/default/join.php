<?php

	// 회원정보 수정시
	if ( $u_idx ) {
		$menuName = "mypage";      //=> 1Dpeth 네이밍
		$subVal = 1;                 //=> 2Dpeth 넘버링
		$thVal = 1;                  //=> 3Dpeth 넘버링
		$fhVal = 1;                  //=> 4Dpeth 넘버링
		$member_title = "마이페이지 <img src=\"../../images/common/home_bl.gif\"/> 회원정보수정";
		$member_title_image = "<img src=\"/images/common/member_title_modify.gif\">";
		$member_title_image2 = "<img src=\"".$url_skin_member."images/m_tit001.gif\">";
		$member_join_image = "<input type=\"image\" name=\"imageField\" src=\"".$url_skin_member."images/okokok.gif\">";

	// 신규 회원가입시
	} else {
		$menuName = "member";      //=> 1Dpeth 네이밍
		$subVal = 2;             //=> 2Dpeth 넘버링
		$thVal = 1;                  //=> 3Dpeth 넘버링
		$fhVal = 1;                  //=> 4Dpeth 넘버링
		$member_title = "MEMBER <img src=\"../../images/common/home_bl.gif\"/> 회원가입";
		$member_title_image = "<img src=\"/images/common/member_title_join.gif\">";
		$member_title_image2 = "<img src=\"".$url_skin_member."images/m_tit01.gif\">";
		$member_join_image = "<input type=\"image\" name=\"imageField\" src=\"".$url_skin_member."images/m_btn_join.gif\">";
	}

	if ( !$u_tel1 ) {
		$u_tel1 = "032";
	}


	include "../share/inc/php/header.php";
?>



		<!-- [Content] start -->
		<div id="Content">
			<div id="account_site">
				<ul>
					<li><img src="../../images/common/home_dot.gif" width="4" height="4" /></li>
					<li>홈 <img src="../../images/common/home_bl.gif"/>  <?=$member_title?></li>
				</ul>
			</div>
			<div id="account_title"></div>





			<div id="account_content">
				<!--[타이틀]-->
				<?=$member_title_image?>
				<!--[타이틀 끝]-->

				<table width="653" border="0" cellpadding="0" cellspacing="0" class="mgt20">
					<tr>
						<td>

							<!-- Content -->
							<?
								if ( !$u_idx ) {
							?>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td valign="top" height="58"><img src="<?=$url_skin_member?>images/join_title.gif"></td>
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td width="156"><img src="<?=$url_skin_member?>images/agree_tab01.gif"></td>
									<td align="center"><img src="<?=$url_skin_member?>images/agree_tab_arrow.gif"></td>
									<td width="156"><img src="<?=$url_skin_member?>images/agree_tab02_over.gif"></td>
									<td align="center"><img src="<?=$url_skin_member?>images/agree_tab_arrow.gif"></td>
									<td width="156"><img src="<?=$url_skin_member?>images/agree_tab03.gif"></td>
								</tr>
							</table>
							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td height="20">&nbsp;</td>
								</tr>
								<tr>
									<td height="1" background="<?=$url_skin_member?>images/login_dot.gif"></td>
								</tr>
								<tr>
									<td height="20">&nbsp;</td>
								</tr>
							</table>
							<?
								}
							?>

							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td><?=$member_title_image2?></td>
									<td align="right"><img src="<?=$url_skin_member?>images/m_txt.gif"></td>
								</tr>
							</table>

							<form name="JoinForm" method="post" action="<?=$cf_site_url_ssl?>/member/join_ok.php" enctype='multipart/form-data' onSubmit="return JoinGo()">
							<input type="hidden" name="u_gubun" value="<?=$u_gubun?>">

							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td colspan="2" class="m_line_2px">&nbsp;</td>
								</tr>

								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>


								<!-- 아이디 Start -->
								<tr>
									<td class="m_txt"><img src="<?=$url_skin_member?>images/m_txt01.gif" width="89" height="13"></td>
									<td class="m_content">

										<? if ( strlen($u_idx) > 0 ) { ?>
										<input type="hidden" name="id_chk" value="1">
										<input type="hidden" name="u_idx" value="<?=$u_idx?>">
										<input type="hidden" name="u_id" value="<?=$u_id?>">
										<span class="txt02"><?=$u_id?></span>
										<span class="m_content_txt2"><? if ( strlen($ss_u_idx) > 0 ) { // 가입일?><?=date('Y 년 m 월 d 일', strtotime($rows["u_regdate"]) );?><? } ?></span>
										<? } else { ?>
										<input type="hidden" name="id_chk" value="0">

										<table border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="126" valign="top">
													<input name="u_id" value="<?=$rows["u_id"]?>" onchange="resetID()" <?=$OnlyEng?> maxlength="16" type="text" class="m_input" style="width:120px" >
												</td>
												<td width="73" valign="top" class="m_content_txt"><a href="javascript:checkID('<?=$url_member?>');" /><img src="<?=$url_skin_member?>images/m_btn01.gif" width="67" height="19" border="0"></a></td>
												<td class="m_content_txt2"> 회원ID는 가입후 변경이 불가능합니다. <br>  회원ID와 비밀번호는 영문자로 시작하는 4~16자의 영문,숫자를 조합하셔서 공백없이 기입해주세요.</td>
											</tr>
										</table>

										<? } ?>

									</td>
								</tr>
								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>
								<!-- 아이디 End -->


								<!-- 비밀번호 Start -->
								<tr>
									<td class="m_txt"><img src="<?=$url_skin_member?>images/m_txt02.gif" width="89" height="13"></td>
									<td class="m_content">
										<table border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="126" valign="top">
													<input name="u_pw" type="password" class="m_input" maxlength="20" style="width:120px">
												</td>
												<td class="m_content_txt2">총 6 자리 이상 입력하셔야 합니다.</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>
								<tr>
									<td class="m_txt"><img src="<?=$url_skin_member?>images/m_txt03.gif" width="89" height="13"></td>
									<td class="m_content">
										<table border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="126" valign="top">
													<input name="u_pw_r" type="password" class="m_input" maxlength="20" style="width:120px">
												</td>
												<td class="m_content_txt2">확인을 위해 비밀번호를 한 번 더 입력해 주십시오.</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>
								<!-- 비밀번호 End -->


								<!-- 이름 Start -->
								<tr>
									<td class="m_txt"><img src="<?=$url_skin_member?>images/m_txt04.gif" width="89" height="13"></td>
									<td class="m_content">
										<input name="u_name" value="<?=$u_name?>" <?=$chk_readonly?> type="text" class="m_input" maxlength="30" style="width:120px">
									</td>
								</tr>
								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>
								<!-- 이름 End -->



								<!-- 주민번호 Start -->
								<tr>
									<td class="m_txt"><img src="<?=$url_skin_member?>images/m_txt05.gif" width="89" height="13"></td>
									<td class="m_content">
										<? if ( strlen($ss_u_idx) > 0 && strlen($u_jumin1) > 0 ) { ?>
										<b><?=$u_jumin1?> - <?=$u_jumin2?></b>
										<? } else {  ?>

										<table border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td>
													<input type="text" name="u_jumin1" value="<?=$u_jumin1?>" <?=$chk_readonly?> <?=$OnlyNumber?> maxlength="6" class="m_input" style="width:90px">
												</td>
												<td width="20" align="center" class="m_content_txt2">-</td>
												<td>
													<input type="password" name="u_jumin2" value="<?=$u_jumin2?>" <?=$chk_readonly?> <?=$OnlyNumber?> maxlength="7" class="m_input" style="width:90px" onblur="fnGetBirth();">
												</td>
												<td class="m_content_txt2" style="padding-left:5px;">   * 이미 가입된 주민등록번호로 재가입할 수 없습니다.</td>
											</tr>
										</table>
										<? } ?>

									</td>
								</tr>
								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>
								<!-- 주민번호 End -->
<script>
	function fnGetBirth() {
		var ff = document.JoinForm;

		var juminNum = "";

		juminNum = ff.u_jumin1.value + ff.u_jumin2.value;

		BirthValue = fnGetAge(juminNum);

		if ( BirthValue ) {
//			alert(BirthValue);
//			alert(BirthValue[0]);
//			alert(BirthValue[1]);
//			alert(BirthValue[2]);
			ff.u_birth_year
			ff.u_birth_month
			ff.u_birth_day

			for (i = 0 ; i < ff.u_birth_year.length; i++) {
				if ( ff.u_birth_year[i].value == BirthValue[0] ) {
					ff.u_birth_year[i].selected = true;
					break;
				}
			}

			for (i = 0 ; i < ff.u_birth_month.length; i++) {
				if ( ff.u_birth_month[i].value == BirthValue[1] ) {
					ff.u_birth_month[i].selected = true;
					break;
				}
			}

			for (i = 0 ; i < ff.u_birth_day.length; i++) {
				if ( ff.u_birth_day[i].value == BirthValue[2] ) {
					ff.u_birth_day[i].selected = true;
					break;
				}
			}
		}
		else {
			return false;
		}

		SexValue = fnGetSex(juminNum);
////		alert(SexValue);
//
		if ( SexValue ) {
			if ( SexValue == "M" ) {
				ff.u_sex[0].checked = true;
			}
			else if ( SexValue == "F" ) {
				ff.u_sex[1].checked = true;
			}
		}
		else {
			return false;
		}




	}

	function fnGetAge(juminNum){
//		alert(juminNum);

		var date = new Date();

		var strAge = "";
		var strSex = "";
		var strYYYY = null;

		var BirthYear = null;
		var BirthMonth = null;
		var BirthDay = null;

		var iAge = 0;
		if( juminNum.length == 13 ){
			strAge = juminNum.substr(0, 2);
			strSex = juminNum.substr(6, 1);
			BirthMonth = juminNum.substr(2, 2);
			BirthDay = juminNum.substr(4, 2);


			//3, 4는 국내 2000년 이후 출생일 경우, 7, 8은 외국인 국내거주자 중 2000년 이후 출생자
			if ( "3478".indexOf(strSex) != -1  ) {
				strYYYY = date.getYear();

				BirthYear = parseInt('20' + strAge);
//				iAge = parseInt(strYYYY) - parseInt('20' + strAge);

				if( iAge < 0 ) {
					//alert("주민번호가 잘못되었습니다.");
					return false;
				}

			}
			else {
				strYYYY = date.getYear();

				BirthYear = parseInt('19' + strAge);
				//iAge = parseInt(strYYYY) - parseInt('19' + strAge);

			}


			var Birth = new Array(3);
			Birth[0] = BirthYear;
			Birth[1] = BirthMonth;
			Birth[2] = BirthDay;

//			alert(BirthYear);
//			alert(BirthMonth);
//			alert(BirthDay);
			return Birth;

		}
	}

	function fnGetSex(juminNum){
		var strSex = "";

		if( juminNum.length == 13 ){
			strSex = juminNum.substr(6, 1);

//			alert(strSex);
//			alert(parseInt(strSex) % 2);

			if( parseInt(strSex) % 2 != 0 ){
				return "M";
				//alert("남자");
			}
			else{
				return "F";
				//alert("여자");
			}
		}
		else {
			return false;
		}
	}

</script>


								<!-- 생년월일 Start -->
								<tr>
									<td class="m_txt"><img src="<?=$url_skin_member?>images/m_txt06.gif" width="89" height="13"></td>
									<td class="m_content">
										<table border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="57">
													<select class="select1" style="width:53px" name="u_birth_year">
													<option>----</option>
													<script language="JavaScript">document.write(selectBox(<?=$now_year?>,1900,'<?=$u_birth_year?>',-1,''));</script>
													</select>
												</td>
												<td width="20" class="m_content_txt">년</td>
												<td width="44">
													<select class="select1" style="width:40px" name="u_birth_month">
													<option>--</option>
													<script language="JavaScript">document.write(selectBox(1,12,'<?=$u_birth_month?>',1,''));</script>
													</select>
												</td>
												<td width="20" class="m_content_txt">월</td>
												<td width="44">
													<select class="select1" style="width:40px" name="u_birth_day">
													<option>--</option>
													<script language="JavaScript">document.write(selectBox(1,31,'<?=$u_birth_day?>',1,''));</script>
													</select>
												</td>
												<td width="26" class="m_content_txt">일</td>
												<td width="22">
													<input name="u_birth_luner" type="radio" value="0" <? if (!$rows[u_birth_luner]) echo "checked"; ?>>
												</td>
												<td class="m_content_txt"><?=$Arr_u_birth_luner[0]?></td>
												<td width="4">&nbsp;</td>
												<td width="22">
													<input name="u_birth_luner" type="radio" value="1" <? if ( $rows[u_birth_luner] ) echo "checked"; ?>>
												</td>
												<td class="m_content_txt"><?=$Arr_u_birth_luner[1]?></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>
								<!-- 생년월일 End -->

								<!-- 성별 Start -->
								<tr>
									<td class="m_txt"><img src="<?=$url_skin_member?>images/m_txt37.gif" hspace="5" /></td>
									<td class="m_content">
										<input type="radio" name="u_sex" value="M" <? if ( $u_sex == "M" || $u_sex == "" ) {echo "checked";} ?>> 남자
										<input type="radio" name="u_sex" value="F" <? if ( $u_sex == "F" ) {echo "checked";} ?>> 여자
									</td>
								</tr>
								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>
								<!-- 성별 End -->

								<!-- 직급 Start -->
								<tr>
									<td class="m_txt"><img src="<?=$url_skin_member?>images/m_txt14.gif" width="89" height="13"></td>
									<td class="m_content">
										<input name="u_class" value="<?=$u_class?>" type="text" class="m_input" maxlength="50" style="width:120px">
									</td>
								</tr>
								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>
								<!-- 직급 End -->


								<!-- 학교명 Start -->
								<tr>
									<td class="m_txt"><img src="<?=$url_skin_member?>images/m_txt15.gif" width="89" height="13"></td>
									<td class="m_content">
										<input name="u_school" value="<?=$u_school?>" type="text" class="m_input" maxlength="100" style="width:120px">
									</td>
								</tr>
								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>
								<!-- 학교명 End -->

								<!-- 전화번호 Start -->
								<tr>
									<td class="m_txt"><img src="<?=$url_skin_member?>images/m_txt07.gif" width="89" height="13"></td>
									<td class="m_content">
										<? getTel("u_tel", $u_tel1, $u_tel2, $u_tel3, 'm_input' ); ?>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>
								<!-- 전화번호 End -->

								<!-- 휴대폰 Start -->
								<tr>
									<td class="m_txt"><img src="<?=$url_skin_member?>images/m_txt08.gif" width="89" height="13"></td>
									<td class="m_content">
										<? getHP("u_hp", $u_hp1, $u_hp2, $u_hp3, 'm_input' ); ?>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>
								<!-- 휴대폰 End -->

								<!-- 이메일 Start -->
								<tr>
									<td class="m_txt"><img src="<?=$url_skin_member?>images/m_txt09.gif" width="89" height="13"></td>
									<td class="m_content">
										<? getEmailForm("u_email1", "u_email2", $rows[u_email1], $rows[u_email2], "m_input"); ?>
										비밀번호 분실 시 사용되므로 사용중인 이메일주소를 입력하세요.
									</td>
								</tr>
								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>
								<!-- 이메일 End -->

								<!-- 홈페이지 Start -->
								<tr>
									<td class="m_txt"><img src="<?=$url_skin_member?>images/m_txt10.gif" width="89" height="13"></td>
									<td class="m_content">
										<table border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="20" align="center" class="m_content_txt" style="letter-spacing:0px">http://&nbsp;</td>
												<td>
													<input type="text" name="u_homepage" value="<?=$u_homepage?>" onblur="this.value = this.value.replace('http://','');" maxlength="50" class="m_input" style="width:325px">
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>
								<!-- 홈페이지 End -->

								<!-- 주소 Start -->
								<tr>
									<td class="m_txt"><img src="<?=$url_skin_member?>images/m_txt11.gif" width="89" height="13"></td>
									<td class="m_content">
										<table border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td valign="top" width="126">
													<input name="u_post" value="<?=$rows[u_post]?>" type="text" class="m_input" maxlength="7" style="width:120px" readonly onclick="javascript:OpenZipcode('<?=$url_member?>', 'JoinForm', 'u_post', 'u_addr1', 'u_addr2');" style="cursor:hand;text-align:center;">
												</td>
												<td width="73" valign="top" class="m_content_txt"><a href="javascript: OpenZipcode('<?=$url_member?>', 'JoinForm', 'u_post', 'u_addr1', 'u_addr2');"   tar="_self" /><img src="<?=$url_skin_member?>images/m_btn02.gif" border="0"></a></td>
											</tr>
										</table>
										<table border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td valign="top" height="20">
													<input type="text" name="u_addr1" value="<?=$rows[u_addr1]?>" class="m_input" style="width:362px" maxlength="100">
												</td>
											</tr>
										</table>
										<table border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td valign="top" height="20" width="366">
													<input name="u_addr2" value="<?=$rows[u_addr2]?>" type="text" class="m_input" style="width:362px" maxlength="100">
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>
								<!-- 주소 End -->

								<!-- 메일수신여부 Start -->
								<tr>
									<td class="m_txt"><img src="<?=$url_skin_member?>images/m_txt12.gif" width="89" height="13"></td>
									<td class="m_content">
										<table border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="22"><input name="u_email_receipt" type="radio" value="1" <? if ($rows[u_email_receipt] == "1" || $rows[u_email_receipt] == "" ) { echo "checked"; } ?>></td>
												<td class="m_content_txt">메일수신 허용</td>
												<td width="20">&nbsp;</td>
												<td width="22"><input name="u_email_receipt" type="radio" value="2" <? if ($rows[u_email_receipt] == "2" ) { echo "checked"; } ?>></td>
												<td class="m_content_txt">메일수신 거부</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>
								<!-- 메일수신여부 End -->

								<!-- SMS수신여부 Start -->
								<tr>
									<td class="m_txt"><img src="<?=$url_skin_member?>images/m_txt13.gif" width="89" height="13"></td>
									<td class="m_content">
										<table border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td width="22"><input name="u_sms_receipt" type="radio" value="1" <? if ($rows[u_sms_receipt] == "1" || $rows[u_sms_receipt] == "" ) { echo "checked"; } ?>></td>
												<td class="m_content_txt">SMS수신 허용</td>
												<td width="18">&nbsp;</td>
												<td width="22"><input name="u_sms_receipt" type="radio" value="2" <? if ($rows[u_sms_receipt] == "2" ) { echo "checked"; } ?>></td>
												<td class="m_content_txt">SMS수신 거부</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td colspan="2" class="m_line_1px">&nbsp;</td>
								</tr>
								<!-- SMS수신여부 End -->
							</table>

							<table width="100%" border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td height="80" align="center">
										<?=$member_join_image;?>
										<img src="<?=$url_skin_member?>images/m_btn_cancle.gif" width="76" height="28" onClick="history.go(-1)" style="cursor:hand" align="absmiddle">
									</td>
								</tr>
							</table>
							</form>
							<!-- //Content -->













						</td>
					</tr>
				</table>
			</div>







<? include "../share/inc/php/footer.php"; ?>
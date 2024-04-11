<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	//$dbcon -> setDebug(1);
	$page_btn_prev =">>"; // > 버튼
	$page_btn_next ="<<"; // < 버튼

	// 페이지 설정
	$page				= REQSTR($page, 1);
	$num_per_page	= REQSTR($num_per_page, 10);
	$page_per_block	= REQSTR($page_per_block, 10);
	$first					= $num_per_page*($page-1);
	$last					= $num_per_page*$page;

	// 검색설정
	$query_where		= "";
	$query_where .= " and u_level <= '".$ss_u_level."' ";

	#### 검색 설정 Start
	$search_u_level				= REQSTR($search_u_level, "");
	$search_u_gubun				= REQSTR($search_u_gubun, "");
	$search_u_state				= REQSTR($search_u_state, "");
	$search_u_sex					= REQSTR($search_u_sex, "");

	$search							= REQSTR($search, "");
	$search_text					= REQSTR($search_text, "");

	$search_orderby				= REQSTR($search_orderby, "");
	$search_sort					= REQSTR($search_sort, "");

	$search_date					= REQSTR($search_date, "");
	$search_date_s				= REQSTR($search_date_s, "");
	$search_date_e				= REQSTR($search_date_e, "");


	if ( strlen($search_u_level) > 0 ) $query_where .= " and u_level = '".$search_u_level."' ";
	if ( strlen($search_u_gubun) > 0 ) $query_where .= " and u_gubun = '".$search_u_gubun."' ";
	if ( strlen($search_u_state) > 0 ) $query_where .= " and u_state = '".$search_u_state."' ";
	if ( strlen($search_u_sex) > 0 ) $query_where .= " and u_sex = '".$search_u_sex."' ";
	if ( strlen($search_u_mail) > 0 ) $query_where .= " and u_email_receipt = '".$search_u_mail."' ";
	if ( strlen($search_u_sms) > 0 ) $query_where .= " and u_sms_receipt = '".$search_u_sms."' ";

	if ( strlen($search_text) > 0 ) {
		if ( $search == "u_email" )
			$query_where .= " and u_email1 like '%".$search_text."%' or u_email2 like '%".$search_text."%' ";
		else
			$query_where .= " and ".$search." like '%".$search_text."%' ";
	}

	if ( strlen($search_date_s) > 0 ) $query_where .= " and ".$search_date." >= '".$search_date_s." 00:00:00' ";
	if ( strlen($search_date_e) > 0 ) $query_where .= " and ".$search_date." <= '".$search_date_e." 23:59:59' ";


	if ( strlen($search_orderby) == 0 ) $search_orderby .= "u_regdate";
	if ( strlen($search_sort) == 0 ) $search_sort .= "desc";


//	echo "search_u_level : ".$search_u_level."<BR>";
//	echo "search_u_gubun : ".$search_u_gubun."<BR>";
//	echo "search_u_state : ".$search_u_state."<BR>";
//	echo "search_u_sex : ".$search_u_sex."<BR>";
//	echo "search_orderby : ".$search_orderby."<BR>";
//	echo "search_sort : ".$search_sort."<BR>";
//	echo $query_where."<BR>";


	$parameter = "&search_u_level=".$search_u_level."&search_u_gubun=".$search_u_gubun."&search_u_state=".$search_u_state."&search_u_sex=".$search_u_sex."&search=".$search."&search_text=".$search_text."&search_orderby=".$search_orderby."&search_sort=".$search_sort."&num_per_page=".$num_per_page."&search_u_mail=".$search_u_mail."&search_u_sms=".$search_u_sms;

	#### 검색 설정 End

	// 쿼리설정
	$field				= " * "; //, ( select count(u_id) from tbl_user_log where u_id = A.u_id ) as u_log_cnt

	$table			= "
							tbl_user A
						";
	$where			= $query_where;
//	echo $where."<br/>";
	$orderby			= $search_orderby." ".$search_sort;
	$limit				= $first.", ".$num_per_page;
	$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);
	$total_record	= $ArrRS[0];
	$result			= $ArrRS[1];
	unset($ArrRS);

	// 페이지 & 리스트 설정
	$total_page		= ceil($total_record/$num_per_page);
	$no				= $total_record - $first;

	$dbcon -> dbcon_close();
?>
<?
	$tm = "member";
	$lm = "";
	include $path_admin."inc/header.php";
?>
	<!-- ########################## 컨텐츠 영역 START ##########################-->
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">회원 리스트</td>
	</tr>
	<tr>
		<td colspan="2" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<table width="800" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td>

			<? if ( $sc_menu_member == "Y" ) { ?>
			<!-- SearchForm Start -->
			<form name="SearchForm" method="get" action="" onsubmit="return search_go()">
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 회원검색</td>
				</tr>
				<tr>
					<td height="4"></td>
				</tr>
			</table>
			<table width="100%" cellspacing="0" cellpadding="0" border="0" class="b_search_box">
				<tr>
					<td>
						<table cellpadding="0" cellspacing="3" border="0">
							<tr>
								<td width="4">&nbsp;</td>
								<td valign="top" style="padding:1 0 0 0">
									<select name="search_u_level" style="width:110px" class="select" onChange="search_go();">
										<option value=''>회원등급검색</option>
										<? foreach ($Arr_u_level as $key => $val) {?>
											<? if ( $key > 0 && $key <= $ss_u_level ) { ?>
											<option value="<?=$key?>" <? if ("".$key == $search_u_level) echo "selected"; ?>><?=$val?></option>
											<? } ?>
										<? } ?>
									</select>
								</td>
								<td width="4">&nbsp;</td>
								<td valign="top" style="padding:1 0 0 0">
									<select name="search_u_state" style="width:110px" class="select" onChange="search_go();">
										<option value=''>회원상태검색</option>
										<? foreach ($Arr_u_state as $key => $val) {?>
											<option value="<?=$key?>" <? if ("".$key == $search_u_state) echo "selected"; ?>><?=$val?></option>
										<? } ?>
									</select>
								</td>
								<td width="4">&nbsp;</td>
								<td valign="top" style="padding:1 0 0 0">
									<select name="search_u_sex" style="width:110px" class="select" onChange="search_go();">
										<option value=''>회원성별검색</option>
										<? foreach ($Arr_u_sex as $key => $val) {?>
											<option value="<?=$key?>" <? if ("".$key == $search_u_sex) echo "selected"; ?>><?=$val?></option>
										<? } ?>
									</select>
								</td>
								<td width="4">&nbsp;</td>
								<td valign="top" style="padding:1 0 0 0">
									<select name="search_u_mail" style="width:110px" class="select" onChange="search_go();">
										<option value=''>메일여부</option>
										<option value="1" <? if ($search_u_mail=="1") echo "selected"; ?>>Y</option>
										<option value="0" <? if ($search_u_mail=="0") echo "selected"; ?>>N</option>
									</select>
								</td>
								<td width="4">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table cellspacing="0" cellpadding="0" border="0"><tr><td height="6"></td></tr></table>

			<table width="100%" cellspacing="0" cellpadding="0" border="0" class="b_search_box">
				<tr>
					<td>
						<table cellpadding="0" cellspacing="3" border="0">
							<tr>
								<td width="4">&nbsp;</td>
								<td width="59" valign="top" style="padding:1 0 0 0"><img src="<?=$url_admin?>images/a_search_txt.gif" ></td>
								<td valign="top" style="padding:1 0 0 0">
									<select name="search" class="select" style="width:130px;">
										<option value="u_id" <? if ($search == "u_id") echo "selected"; ?>>아이디</option>
										<option value="u_name" <? if ($search == "u_name") echo "selected"; ?>>이름</option>
										<option value="u_email" <? if ($search == "u_name") echo "selected"; ?>>이메일</option>
									</select>
								</td>
								<td valign="top" width="128">
									<input type="text" style="width:224px" maxlength="30" name="search_text" class="input" value="<?=$search_text?>">
								</td>
								<td valign="top" style="padding:1 0 0 0"><input type="image" src="<?=$url_admin?>images/a_btn_search.gif"></td>
								<td width="4">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<!-- 달력 띄우기 -->

<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css"/>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"></script>
<script type="text/javascript">
 $(document).ready(function() {
  //******************************************************************************
  // 상세검색 달력 스크립트
  //******************************************************************************
  var clareCalendar = {
   monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
   dayNamesMin: ['일','월','화','수','목','금','토'],
   weekHeader: 'Wk',
   dateFormat: 'yy-mm-dd', //형식(20120303)
   autoSize: false, //오토리사이즈(body등 상위태그의 설정에 따른다)
   changeMonth: true, //월변경가능
   changeYear: true, //년변경가능
   showMonthAfterYear: true, //년 뒤에 월 표시
   showOtherMonths: true, // 다른달 보여주기
   selectOtherMonths: true, // 다른달 선택가능
   buttonImageOnly: true, //이미지표시
   buttonText: '달력선택', //버튼 텍스트 표시
   buttonImage: '/admin/images/a_btn_cal.gif', //이미지주소
   showOn: "both", //엘리먼트와 이미지 동시 사용(both,button)
   yearRange: '1990:2020' //1990년부터 2020년까지
  };
  $("#search_date_s").datepicker(clareCalendar);
  $("#search_date_e").datepicker(clareCalendar);
  $("img.ui-datepicker-trigger").attr("style","margin-left:5px; vertical-align:middle; cursor:pointer;"); //이미지버튼 style적용
  $("#ui-datepicker-div").hide(); //자동으로 생성되는 div객체 숨김
 });
</script>
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td>
						<table width="100%" class="b_search_box">
							<tr>
								<td>
									<table cellpadding="0" cellspacing="3" border="0">
										<tr>
											<td width="4">&nbsp;</td>
											<td width="59" valign="top" style="padding:1 0 0 0"><img src="<?=$url_admin?>images/a_search_txt.gif" ></td>
											<td valign="top" style="padding:1 0 0 0">
												<select name="search_date" class="select" style="width:130px;">
													<option value="u_regdate" <? if ($search_date == "u_regdate") echo "selected"; ?>>가입일</option>
													<option value="u_birth" <? if ($search_date == "u_birth") echo "selected"; ?>>생일</option>
													<option value="u_marriagedate" <? if ($search_date == "u_marriagedate") echo "selected"; ?>>결혼기념일</option>
												</select>
											</td>
											<td valign="top">
												<!-- 시작일 -->
												<input type="text" id="search_date_s" name="search_date_s" value="<?=$search_date_s?>" style="width:83px" <?=$ClassCalendar?>>
											</td>
											<td class="a_content_td" valign="top" style="padding:3 4 0 4;letter-spacing:-1px">~</td>
											<td valign="top">
												<input type="text" id="search_date_e" name="search_date_e" value="<?=$search_date_e?>" style="width:83px" <?=$ClassCalendar?>>
											</td>
											<td valign="top" style="padding:1 0 0 0"><input type="image" src="<?=$url_admin?>images/a_btn_search.gif"></td>
											<td width="4">&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
					<td width="4">&nbsp;</td>
					<td width="100px" align="right">
						<table class="b_search_box">
							<tr>
								<td>
									<table cellpadding="0" cellspacing="3" border="0">
										<tr>
											<td width="4">&nbsp;</td>
											<td valign="top" style="padding:1 0 0 0"><a href="<?=$url_admin?>member/member_list.php"><img src="<?=$url_admin?>images/a_btn_init.gif"></a></td>
											<td width="4">&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>

			<table cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td width="121" height="1"></td>
					<td width="114" valign="top"><div id="startCal" style="margin:5; padding:5;margin-top:2;border:1 solid #E5E5E5;;width:160;display:none;position: absolute; z-index: 99;background-color:#FFFFFF"></div></td>
					<td width="10" valign="top"><div id="endCal" style="margin:5; padding:5;margin-top:2;border:1 solid #E5E5E5;;width:160;display:none;position: absolute; z-index: 99;background-color:#FFFFFF"></div></td>
				</tr>
			</table>
			<!-- //달력 띄우기 -->


			<!-- Count & Sort Start -->
			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td height="20"></td>
				</tr>
			</table>

			<? } ?>


			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 회원리스트<font color="#666666"> - <b>총 <font color="#FF0000"><?=$total_record?></font> 명의 회원이 있습니다.</b></font></td>
					<td align="right">
						<table cellpadding="0" cellspacing="3" border="0">
							<tr>
								<td class="a_content_td" valign="top" style="padding:3 0 0 0;color:#006699;font-weight:bold;letter-spacing:-1px">정렬기준</td>
								<td valign="top" style="padding:1 0 0 0">
									<select name="search_orderby" class="select" onChange="search_go();">
										<option value='u_id' <? if ($search_orderby == "u_id" ) echo "selected"; ?>>아이디</option>
										<option value='u_name' <? if ($search_orderby == "u_name" ) echo "selected"; ?>>이름</option>
										<option value='u_level' <? if ($search_orderby == "u_level" ) echo "selected"; ?>>등급</option>
										<? /*<option value='u_gubun' <? if ($search_orderby == "u_gubun" ) echo "selected"; ?>>분류</option>*/ ?>
										<option value='u_regdate' <? if ($search_orderby == "u_regdate" ) echo "selected"; ?>>가입일</option>
									</select>
								</td>
								<td class="a_content_td" valign="top" style="padding:3 0 0 0;color:#006699;font-weight:bold;letter-spacing:-1px">정렬방법</td>
								<td valign="top" style="padding:1 0 0 0">
									<select name="search_sort" class="select" onChange="search_go();">
										<option value='asc' <? if ($search_sort == "asc" ) echo "selected"; ?>>오름차순</option>
										<option value='desc' <? if ($search_sort == "desc" ) echo "selected"; ?>>내림차순</option>>
									</select>
								</td>
								<td class="a_content_td" valign="top" style="padding:3 0 0 0;color:#006699;font-weight:bold;letter-spacing:-1px">리스트갯수</td>
								<td valign="top" style="padding:1 0 0 0">
									<select name="num_per_page" class="select" onChange="search_go();">
										<option value='10' <? if ($num_per_page == "10" ) echo "selected"; ?>>10 개씩 보기</option>
										<option value='20' <? if ($num_per_page == "20" ) echo "selected"; ?>>20 개씩 보기</option>
										<option value='50' <? if ($num_per_page == "50" ) echo "selected"; ?>>50 개씩 보기</option>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="4" colspan="2"></td>
				</tr>
			</table>
			<!-- //Count & Sort End -->

			</form>

			<script language="JavaScript">
			<!--
				function search_go() {
					var ff = document.SearchForm;
					/*
					ff = document.SearchForm;
					if (!ff.search_text.value) {
						alert("검색어를 입력해 주세요.");
						ff.search_text.focus();
						return false;
					}
					*/
					ff.action = "member_list.php";
					ff.submit();
				}

				function mem_excel(){
					var ff = document.SearchForm;
					ff.action = "member_excel.php";
					ff.submit();
				}
			//-->
			</script>

			<!-- //SearchForm End -->



			<table width="100%" cellspacing="0" cellpadding="0" border="0">
				<tr>
					<td colspan="100" height="2" bgcolor="#D5D5D5"></td>
				</tr>
				<tr>
					<td align="center" height="28" bgcolor="#F3F3F3" class="a_thead" width="50">No.</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="100">아이디</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead">이름</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="50">지역</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="100">등급</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="35">상태</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="50">성별</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="34">메일</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="34">SMS</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="75">가입일</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="45">수정</td>
					<td align="center" bgcolor="#F3F3F3" class="a_thead" width="45">삭제</td>
				</tr>
			<? if ($total_record == 0) { ?>
				<tr align="center">
					<td colspan="100" height="28"><b><?=$GLOBALS[msg_list_notdata]?></b></td>
				</tr>
				<tr>
					<td colspan="100" height="1" bgcolor="#E5E5E5"></td>
				</tr>
			<?
				} else {
					while ($rows = $dbcon -> fetch_array($result)) {
						extract($rows);
						unset($rows);

						$print_u_level = $Arr_u_level[$u_level];

						$print_u_gubun = $Arr_u_gubun[$u_gubun];

						$print_u_state = $Arr_u_state[$u_state];

						$print_email_icon = "";
						if ( $u_email_receipt == "1" ) {
							$print_email_icon = "<img src='".$url_admin."images/a_icon_y.gif' alt='메일수신 동의함'>";
						}
						else {
							$print_email_icon = "<img src='".$url_admin."images/a_icon_n.gif' alt='메일수신 동의안함'>";
						}

						$print_u_sex		= $Arr_u_sex[$u_sex];

						$print_sms_icon = "";
						if ( $u_sms_receipt == "1" ) {
							$print_sms_icon = "<img src='".$url_admin."images/a_icon_y.gif' alt='SMS수신 동의함'>";
						}
						else {
							$print_sms_icon = "<img src='".$url_admin."images/a_icon_n.gif' alt='SMS수신 동의안함'>";
						}

						if ( $u_jumin1 ) {
							$print_u_jumin		= $u_jumin1."-*******";
						}
						else {
							$print_u_jumin		= "미등록";
						}

						$print_u_regdate			= mb_substr($u_regdate, 0, 10);
						$print_u_birth				= mb_substr($u_birth, 0, 10);
						$print_u_marriagedate	= mb_substr($u_marriagedate, 0, 10);
			?>
				<tr>
					<td align="center" class="a_content_td" height="28"><?=$no?></td>
					<td align="center" class="a_content_td"><font color="#FF6600"><b><?=$u_id?></b></font></td>
					<td align="center" class="a_content_td"><?=$u_name?></td>
					<td align="center" class="a_content_td"><?=$u_area?></td>
					<td align="center" class="a_content_td"><?=$print_u_level?></td>
					<?/*<td align="center" class="a_content_td"><?=$print_u_gubun?>회원</td>*/?>
					<td align="center" class="a_content_td"><?=$print_u_state?></td>
					<td align="center" class="a_content_td"><?=$print_u_sex?></td>
					<td align="center" class="a_content_td"><?=$print_email_icon?></td>
					<td align="center" class="a_content_td"><?=$print_sms_icon?></td>
					<td align="center" class="a_content_td"><?=$print_u_regdate?></td>
					<td align="center" class="a_content_td">
						<a href="javascript:modify_go('<?=$u_idx?>');"><img src='<?=$url_admin?>images/a_icon_modify.gif' alt='회원정보 수정'></a>
					</td>
					<td align="center" class="a_content_td">
						<? if ($u_state == 1) { ?>
							<a href="javascript:drop_go('<?=$u_id?>', 1);"><img src='<?=$url_admin?>images/a_icon_delete.gif' alt='회원탈퇴'></a>
						<? } elseif ($u_state == 2) { ?>
							<a href="javascript:drop_go('<?=$u_id?>', 0);"><img src='<?=$url_admin?>images/a_icon_delete.gif' alt='회원탈퇴'></a>
						<? } ?>
					</td>
				</tr>
				<tr>
					<td colspan="100" height="1" bgcolor="#E5E5E5"></td>
				</tr>
			<?
						$no = $no - 1;
					}
				}
				unset($result);

			?>
			</table>


			<table align=center>
				<tr>
					<td>
						<table cellpadding=0 cellspacing=3 border=0>
							<tr>
								<td width="4">&nbsp;</td>
								<td valign="top" style="padding:1 0 0 0">
									<?
										$bc_skin = "default";
										list_page($page, $total_page, $page_per_block) ;
									?>
								</td>
								<td width="4">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<table border=0 cellspacing=0 cellpadding=0 width=100%>
				<tr>
					<td height=20></td>
				</tr>
			</table>


		</td>
	</tr>
</table>

<script>

	function page_go(page) {
		location.href = "?page="+page+"<?=$parameter?>";
	}

	function modify_go(u_idx) {
//		MemberJoin = window.open("<?=$url_root?>_member/join.php?u_idx="+idx, "MemberJoin", 'width=700,height=600,left=0,top=0,resizable=1,scrollbars=1');
//		MemberJoin.focus();
		location.href = "member_write.php?u_idx="+u_idx;
	}

	function drop_go(u_id, type) {
		if (type == 1) {
			MSG = "정말 탈퇴 하시겠습니까?\n탈퇴하면 회원정보는 탈퇴회원으로 이동되며\n탈퇴한 아이디로는 더이상 사이트를 이용하실 수 없습니다.";
		}
		else {
			MSG = "이미 탈퇴한 회원입니다.\n정말로 삭제하시겠습니까?\n삭제한 데이타는 복구 할 수 없습니다.";
		}

		if (confirm(MSG)) {
			location.href = "member_drop.php?u_id="+u_id+"&type="+type;
		}
	}
</script>
<!-- ### 페이지 끝 ###  -->
<?
	$dbcon -> dbcon_close();
?>
<? include $path_admin."inc/footer.php"; ?>

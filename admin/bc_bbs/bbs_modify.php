<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$bc_id = REQSTR($bc_id, "");

	if ( strlen($bc_id) ) {

		$query_where = " and bc_id = '".$bc_id."' ";

		$field = " * ";
		$table = "config_board_list";
		$where = " and bc_id = '".$bc_id."' ";
		$orderby = " bc_id desc ";
		$limit = "0, 1";

		$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);

		$total_record = $ArrListRs[0];
		if ( $total_record == 0 ) {
			$dbcon -> dbcon_close();
			alert_back("게시판 정보가 누락되었습니다.");
			exit;
		}

		$ListRs = $dbcon -> fetch_array($ArrListRs[1]);
		extract($ListRs);
		unset($ListRs);
		unset($ArrListRs);

		if ( mb_strlen($subject) > 90 ) {
			$subject = mb_substr($subject,0,90,"UTF-8")."...";
		}

		$PrintRegDate = date('Y/m/d ', strtotime($regDt) );
		// NEW 이미지
		$sNew="";
		if(strtotime($regDt) > (time() - (60 * 60 * 24 * 2))) {
			$sNew = $img_new;
		}
	}


?>
<?
	$tm = "bbs";
	$lm = "";
	include $path_admin."inc/header.php";
?>
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">게시판 환경 설정 <font color="#FF0000"><?=$bc_name?></font></td>
		<!-- <td width="300" align="right">
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
				<tr>
					<td align="right">
						<table border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td bgcolor="#339933" width="100" height="22" align="center" class="a_tab_txt" valign="top" style="padding-top:3px"><a href='<?=$url_admin?>board/brd_config.php?table=notice'>게시판 환경설정</a> </td>
								<td width="2"></td>
								<td bgcolor="#000000" width="100" align="center" class="a_tab_txt" valign="top" style="padding-top:3px"><a href='<?=$url_admin?>board/brd_category.php?table=notice'>카테고리 관리</a></td>
								<td width="2"></td>
								<td bgcolor="#7F7F7F" width="100" align="center" class="a_tab_txt" valign="top" style="padding-top:3px"><a href='http://demo.bluecarpet.co.kr/dc_board/dc_board.html?table=notice' target='_blank'>게시판 바로가기</a></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td> -->
	</tr>
	<tr>
		<td colspan="3" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="3" height="18"></td>
	</tr>
</table>



<form name="ModifyForm" action="" method="post" onsubmit="return next_go()">
<input type="hidden" name="mode" value="">




<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 게시판 기본설정</td>
	</tr>
	<tr>
		<td colspan="2" height="2"></td>
	</tr>
</table>

<!-- #### 기본설정 Start #### -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td colspan="2" class="a_line_2px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">게시판 아이디</td>
		<td class="a_content">
			<? if ( strlen($bc_id) > 0 ) { ?>
				<input type="hidden" name="bc_id" value="<?=$bc_id?>" readonly>
				<?=$bc_id?>
			<? } else { ?>
				<input type="text" class="a_input" name="bc_id" value="<?=$bc_id?>">
				<a href="">중복체크</a>
			<? } ?>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">게시판 이름</td>
		<td class="a_content">
			<input type="text" class="a_input" name="bc_name" value="<?=$bc_name?>">
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">게시판스킨</td>
		<td class="a_content">
			<select name="bc_skin">
		<?
			$dir = opendir($path_skin_board);
			while($dir_list = readdir($dir)) {
				if(!($dir_list == "." or $dir_list == "..")) {
					?><option value="<?=$dir_list?>" <?if($dir_list == $bc_skin) echo "selected"; ?>> <?=$dir_list?> </option><?
				}
			}
		?>
			</select>
			<!-- (갤러리, 웹진형 인 경우에는 메인 이미지파일을 필수로 사용하셔야 합니다.) -->
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">게시판 경로</td>
		<td class="a_content">
			<input type="text" class="a_input" name="bc_path" value="<?=$bc_path?>" style="width:100%;">
			<? if ( $bc_path) { ?><BR><a href="<?=$bc_path?>" target="_blank">바로가기</a><? } ?>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_2px">&nbsp;</td>
	</tr>
</table>
<!-- #### 기본설정 End #### -->

<? if ( strlen($bc_id) ) { ?>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 링크설정</td>
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
		<td class="a_txt">시작페이지</td>
		<td class="a_content">
			<select name="bc_url_start">
				<option value="list" <?if($bc_url_start == "list") echo "selected"; ?>>LIST</option>
				<option value="write" <?if($bc_url_start == "write") echo "selected"; ?>>WRITE</option>
				<option value="view" <?if($bc_url_start == "view") echo "selected"; ?>>VIEW</option>
			</select>
			<!-- (갤러리, 웹진형 인 경우에는 메인 이미지파일을 필수로 사용하셔야 합니다.) -->
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">글 작성 후 이동 페이지</td>
		<td class="a_content">
			<input type="text" class="a_input" name="bc_url_writeok" value="<?=$bc_url_writeok?>">
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
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 기능설정</td>
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
		<td class="a_txt">로그인체크</td>
		<td class="a_content">
			<select name="bc_login_check">
				<option value="Y" <? if ($bc_login_check == "Y") echo "selected";?>>사용함</option>
				<option value="N" <? if ($bc_login_check == "N") echo "selected";?>>사용안함</option>
			</select>
		</td>
		<td class="a_txt">카테고리 사용유무</td>
		<td class="a_content">
			<select name="bc_category_use">
				<option value="Y" <? if ($bc_category_use == "Y") echo "selected";?>>사용함</option>
				<option value="N" <? if ($bc_category_use == "N") echo "selected";?>>사용안함</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">공지사항 사용유무</td>
		<td class="a_content">
			<select name="bc_notice_use">
				<option value="Y" <? if ($bc_notice_use == "Y") echo "selected";?>>사용함</option>
				<option value="N" <? if ($bc_notice_use == "N") echo "selected";?>>사용안함</option>
			</select>
		</td>
		<td class="a_txt">답글 사용유무</td>
		<td class="a_content">
			<select name="bc_reply_use">
				<option value="Y" <? if ($bc_reply_use == "Y") echo "selected";?>>사용함</option>
				<option value="N" <? if ($bc_reply_use == "N") echo "selected";?>>사용안함</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">COMMENT 사용여부</td>
		<td class="a_content">
			<select name="bc_comment_use">
				<option value="Y" <? if ($bc_comment_use == "Y") echo "selected";?>>사용함</option>
				<option value="N" <? if ($bc_comment_use == "N") echo "selected";?>>사용안함</option>
			</select>
		</td>
		<td class="a_txt">비밀글 사용여부</td>
		<td class="a_content">
			<select name="bc_secret_use">
				<option value="Y" <? if ($bc_secret_use == "Y") echo "selected";?>>사용함</option>
				<option value="N" <? if ($bc_secret_use == "N") echo "selected";?>>사용안함</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">글 숨김 기능 사용여부</td>
		<td class="a_content">
			<select name="bc_hidden_use">
				<option value="Y" <? if ($bc_hidden_use == "Y") echo "selected";?>>사용함</option>
				<option value="N" <? if ($bc_hidden_use == "N") echo "selected";?>>사용안함</option>
			</select>
		</td>
		<td class="a_txt">에디터 사용여부</td>
		<td class="a_content">
			<select name="bc_editor_use">
				<option value="Y" <? if ($bc_editor_use == "Y") echo "selected";?>>사용함</option>
				<option value="N" <? if ($bc_editor_use == "N") echo "selected";?>>사용안함</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">검색기능 사용여부</td>
		<td class="a_content">
			<select name="bc_search_use">
				<option value="Y" <? if ($bc_search_use == "Y") echo "selected";?>>사용함</option>
				<option value="N" <? if ($bc_search_use == "N") echo "selected";?>>사용안함</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">페이지 목록 갯수</td>
		<td class="a_content">
			<input type="text" class="a_input" <?=$OnlyNumber?> style="width:50px;text-align:center;" name="bc_list_size" value="<?=$bc_list_size?>"> 개
		</td>
		<td class="a_txt">페이징 그룹 갯수</td>
		<td class="a_content">
			<input type="text" class="a_input" <?=$OnlyNumber?> style="width:50px;text-align:center;" name="bc_page_size" value="<?=$bc_page_size?>"> 개
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">제목글 자르기</td>
		<td class="a_content">
			<input type="text" class="a_input" name="bc_title_size" value="<?=$bc_title_size?>" <?=$OnlyNumber?> style="width:50px;text-align:center;"> 개
		</td>
		<td class="a_txt">NEW 이미지 표시</td>
		<td class="a_content">
			<select name="bc_new_use">
				<option value="Y" <? if ($bc_new_use == "Y") echo "selected";?>>사용함</option>
				<option value="N" <? if ($bc_new_use == "N") echo "selected";?>>사용안함</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">이메일 발송</td>
		<td class="a_content" colspan="3">
			<select name="bc_email_use">
				<option value="N" <? if ($bc_email_use == "N") echo "selected";?>>사용안함</option>
				<option value="A" <? if ($bc_email_use == "A") echo "selected";?>>작성시 관리자에게 메일 발송</option>
				<option value="B" <? if ($bc_email_use == "B") echo "selected";?>>답변시 작성자에게 메일 발송</option>
				<option value="Y" <? if ($bc_email_use == "Y") echo "selected";?>>작성시 관리자에게 & 답변시 작성자에게 메일 발송</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">홈페이지 입력</td>
		<td class="a_content">
			<select name="bc_homepage_use">
				<option value="Y" <? if ($bc_homepage_use == "Y") echo "selected";?>>사용함</option>
				<option value="N" <? if ($bc_homepage_use == "N") echo "selected";?>>사용안함</option>
			</select>
		</td>
		<td class="a_txt">이전글 다음글</td>
		<td class="a_content">
			<select name="bc_prev_next">
				<option value="Y" <? if ($bc_prev_next == "Y") echo "selected";?>>사용함</option>
				<option value="N" <? if ($bc_prev_next == "N") echo "selected";?>>사용안함</option>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">게시글 삭제 처리</td>
		<td class="a_content">
			<select name="bc_state_use">
				<option value="Y" <? if ($bc_state_use == "Y") echo "selected";?>>사용함</option>
				<option value="N" <? if ($bc_state_use == "N") echo "selected";?>>사용안함</option>
			</select>
		</td>
		<td class="a_txt">자동 등록 방지</td>
		<td class="a_content">
			<select name="bc_autoreg_use">
				<option value="Y" <? if ($bc_autoreg_use == "Y") echo "selected";?>>사용함</option>
				<option value="N" <? if ($bc_autoreg_use == "N") echo "selected";?>>사용안함</option>
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

<table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 파일설정</td>
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
		<td class="a_txt">업로드파일 사용갯수</td>
		<td class="a_content">
			<input type="text" class="a_input" <?=$OnlyNumber?> style="width:50px;text-align:center;" name="bc_upfile_cnt" value="<?=$bc_upfile_cnt?>"> 개 (0 : 사용안함)
		</td>
		<td class="a_txt">업로드파일 제한 크기</td>
		<td class="a_content">
			<input type="text" class="a_input" <?=$OnlyNumber?> style="width:50px;text-align:center;" name="bc_upfile_size" value="<?=$bc_upfile_size?>"> MB
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">업로드파일 제한 확장자</td>
		<td class="a_content" colspan="3">
			<input type="text" class="a_input" name="bc_upfile_ext_upload" value="<?=$bc_upfile_ext_upload?>" style="width:300px;">
			( 공백없이 "," 자로 구분)
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">이미지 파일 사용여부</td>
		<td class="a_content">
			<input type="radio" name="bc_upfile_image" value="Y" <? if ($bc_upfile_image == "Y") echo "checked";?>> 사용함
			<input type="radio" name="bc_upfile_image" value="N" <? if ($bc_upfile_image == "N") echo "checked";?>> 사용안함
		</td>
		<td class="a_txt">이미지 사이즈</td>
		<td class="a_content">
			가로 : <input type="text" class="a_input" name="bc_upfile_image_width" value="<?=$bc_upfile_image_width?>" <?=$OnlyNumber?> style="width:50px;text-align:center;"> px
			,
			세로 : <input type="text" class="a_input" name="bc_upfile_image_height" value="<?=$bc_upfile_image_height?>" <?=$OnlyNumber?> style="width:50px;text-align:center;"> px
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">썸네일 생성</td>
		<td class="a_content">
			<input type="radio" name="bc_upfile_image_thum" value="Y" <? if ($bc_upfile_image_thum == "Y") echo "checked";?>> 사용함
			<input type="radio" name="bc_upfile_image_thum" value="N" <? if ($bc_upfile_image_thum == "N") echo "checked";?>> 사용안함
		</td>
		<td class="a_txt">썸네일 사이즈</td>
		<td class="a_content">
			가로 : <input type="text" class="a_input" name="bc_upfile_image_thum_width" value="<?=$bc_upfile_image_thum_width?>" <?=$OnlyNumber?> style="width:50px;text-align:center;"> px
			,
			세로 : <input type="text" class="a_input" name="bc_upfile_image_thum_height" value="<?=$bc_upfile_image_thum_height?>" <?=$OnlyNumber?> style="width:50px;text-align:center;"> px
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">플래시 파일 사용여부</td>
		<td class="a_content">
			<input type="radio" name="bc_upfile_flash" value="Y" <? if ($bc_upfile_flash == "Y") echo "checked";?>> 사용함
			<input type="radio" name="bc_upfile_flash" value="N" <? if ($bc_upfile_flash == "N") echo "checked";?>> 사용안함
		</td>
		<td class="a_txt">플래시 파일 사이즈</td>
		<td class="a_content">
			가로 : <input type="text" class="a_input" <?=$OnlyNumber?> style="width:50px;text-align:center;" name="bc_upfile_flash_width" value="<?=$bc_upfile_flash_width?>"> px
			, 세로 : <input type="text" class="a_input" <?=$OnlyNumber?> style="width:50px;text-align:center;" name="bc_upfile_flash_height" value="<?=$bc_upfile_flash_height?>"> px
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">미디어 파일 사용여부</td>
		<td class="a_content">
			<input type="radio" name="bc_upfile_media" value="Y" <? if ($bc_upfile_media == "Y") echo "checked";?>> 사용함
			<input type="radio" name="bc_upfile_media" value="N" <? if ($bc_upfile_media == "N") echo "checked";?>> 사용안함
		</td>
		<td class="a_txt">미디어 파일 사이즈</td>
		<td class="a_content">
			가로 : <input type="text" class="a_input" name="bc_upfile_media_width" value="<?=$bc_upfile_media_width?>" <?=$OnlyNumber?> style="width:50px;text-align:center;"> px
			,
			세로 : <input type="text" class="a_input" name="bc_upfile_media_height" value="<?=$bc_upfile_media_height?>" <?=$OnlyNumber?> style="width:50px;text-align:center;"> px
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
		<td colspan="2" class="a_line_2px">&nbsp;</td>
	</tr>

	<tr>
		<td class="a_txt">상단 include 파일</td>
		<td class="a_content">
			<input type="text" class="a_input" name="bc_top_include" value="<?=$bc_top_include?>" <?=$OnlyEng?>>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">상단 내용</td>
		<td class="a_content">
			<textarea name="bc_top_html" style="width:100%;height:150px;"><?=$bc_top_html?></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">하단 include 파일</td>
		<td class="a_content">
			<input type="text" class="a_input" name="bc_bottom_include" value="<?=$bc_bottom_include?>" <?=$OnlyNumber?>>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">하단 내용</td>
		<td class="a_content">
			<textarea name="bc_bottom_html" style="width:100%;height:150px;"><?=$bc_bottom_html?></textarea>
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
		<td class="a_st01"><img src="<?=$url_admin?>images/admin_ball.gif"> 권한설정</td>
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
		<td class="a_txt">VIEW</td>
		<td class="a_content">
			<select name="bc_auth_view">
			<? foreach ($Arr_u_level as $key => $val) { ?>
				<? if ( $key <= $ss_u_level ) { ?>
					<option value="<?=$key?>" <? if ("".$key == $bc_auth_view) echo "selected";?>><?=$val?></option>
				<? } ?>
			<? } ?>
			</select>
		</td>
		<td class="a_txt">WRITE</td>
		<td class="a_content">
			<select name="bc_auth_write">
			<? foreach ($Arr_u_level as $key => $val) { ?>
				<? if ( $key <= $ss_u_level ) { ?>
					<option value="<?=$key?>" <? if ("".$key == $bc_auth_write) echo "selected";?>><?=$val?></option>
				<? } ?>
			<? } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">MODIFY</td>
		<td class="a_content">
			<select name="bc_auth_modify">
			<? foreach ($Arr_u_level as $key => $val) { ?>
				<? if ( $key <= $ss_u_level ) { ?>
					<option value="<?=$key?>" <? if ("".$key == $bc_auth_modify) echo "selected";?>><?=$val?></option>
				<? } ?>
			<? } ?>
			</select>
		</td>
		<td class="a_txt">DELETE</td>
		<td class="a_content">
			<select name="bc_auth_delete">
			<? foreach ($Arr_u_level as $key => $val) { ?>
				<? if ( $key <= $ss_u_level ) { ?>
					<option value="<?=$key?>" <? if ("".$key == $bc_auth_delete) echo "selected";?>><?=$val?></option>
				<? } ?>
			<? } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">NOTICE</td>
		<td class="a_content">
			<select name="bc_auth_notice">
			<? foreach ($Arr_u_level as $key => $val) { ?>
				<? if ( $key <= $ss_u_level ) { ?>
					<option value="<?=$key?>" <? if ("".$key == $bc_auth_notice) echo "selected";?>><?=$val?></option>
				<? } ?>
			<? } ?>
			</select>
		</td>
		<td class="a_txt">REPLY</td>
		<td class="a_content">
			<select name="bc_auth_reply">
			<? foreach ($Arr_u_level as $key => $val) { ?>
				<? if ( $key <= $ss_u_level ) { ?>
					<option value="<?=$key?>" <? if ("".$key == $bc_auth_reply) echo "selected";?>><?=$val?></option>
				<? } ?>
			<? } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">COMMENT</td>
		<td class="a_content">
			<select name="bc_auth_comment">
			<? foreach ($Arr_u_level as $key => $val) { ?>
				<? if ( $key <= $ss_u_level ) { ?>
					<option value="<?=$key?>" <? if ("".$key == $bc_auth_comment) echo "selected";?>><?=$val?></option>
				<? } ?>
			<? } ?>
			</select>
		</td>
		<td class="a_txt">SECRET</td>
		<td class="a_content">
			<select name="bc_auth_secret">
			<? foreach ($Arr_u_level as $key => $val) { ?>
				<? if ( $key <= $ss_u_level ) { ?>
					<option value="<?=$key?>" <? if ("".$key == $bc_auth_secret) echo "selected";?>><?=$val?></option>
				<? } ?>
			<? } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">HIDDEN</td>
		<td class="a_content">
			<select name="bc_auth_hidden">
			<? foreach ($Arr_u_level as $key => $val) { ?>
				<? if ( $key <= $ss_u_level ) { ?>
					<option value="<?=$key?>" <? if ("".$key == $bc_auth_hidden) echo "selected";?>><?=$val?></option>
				<? } ?>
			<? } ?>
			</select>
		</td>
		<td class="a_txt">UPLOAD</td>
		<td class="a_content">
			<select name="bc_auth_upload">
			<? foreach ($Arr_u_level as $key => $val) { ?>
				<? if ( $key <= $ss_u_level ) { ?>
					<option value="<?=$key?>" <? if ("".$key == $bc_auth_upload) echo "selected";?>><?=$val?></option>
				<? } ?>
			<? } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4" class="a_line_1px">&nbsp;</td>
	</tr>
	<tr>
		<td class="a_txt">DOWNLOAD</td>
		<td class="a_content" colspan="3">
			<select name="bc_auth_download">
			<? foreach ($Arr_u_level as $key => $val) { ?>
				<? if ( $key <= $ss_u_level ) { ?>
					<option value="<?=$key?>" <? if ("".$key == $bc_auth_download) echo "selected";?>><?=$val?></option>
				<? } ?>
			<? } ?>
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


<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td height="80" align="center">
			<input type="image" src="<?=$url_admin?>images/a_btn_submit.gif" hspace="4">
			<a href="bbs_list.php"><img src="<?=$url_admin?>images/a_btn_cancle.gif" width="76" height="28"></a>
		</td>
	</tr>
</table>

</form>
<script>
	function page_go(page) {
		location.href = "?page="+page+"&<?=$parameter?>";
	}

	function modify_go(bc_id) {
		location.href = "bbs_modify.php?bc_id="+bc_id;
	}

	function delete_go(bc_id) {
		if (confirm("정말로 삭제하시겠습니까?")) {
			location.href = "bbs_del_ok.php?bc_id="+bc_id;
		}
	}

	function next_go() {
		ff = document.ModifyForm;
		if (ff.bc_id.value == "") {
			alert("게시판 아이디를 입력하여 주십시오.");
			ff.bc_id.focus();
			return false;
		}
		if (ff.bc_name.value == "") {
			alert("게시판 이름을 입력하여 주십시오.");
			ff.bc_name.focus();
			return false;
		}

		<? if ( strlen($bc_id) > 0 ) {?>
			ff.mode.value = "modify";
		<? } else { ?>
			ff.mode.value = "insert";
		<? } ?>

		ff.action = "bbs_modify_ok.php";
		//ff.submit();

	}
</script>
<!-- ### 페이지 끝 ###  -->
<?
	$dbcon -> dbcon_close();
?>
<? include $path_admin."inc/footer.php"; ?>

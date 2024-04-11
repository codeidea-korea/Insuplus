<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
	
	$RS_PR = getGlobalProduct();
	$RS_INS = getGlobalIns();
	
	$bc_id = "charge";
	
	if ( !(getLen($bc_id) > 0 ) ) {
		alert_back("게시판 정보가 누락되었습니다.");
		exit;
	}

	#### 게시판 설정 가져오기
	$field = " * ";
	$table = "config_board_list";
	$where = " and bc_id = '".$bc_id."' ";
	$orderby = " bc_id asc ";
	$limit = " 0, 1 ";
	
	$ArrListRs = $dbcon -> getList($field = "*", $table , $where, $orderby, $limit);
	
	if ( $ArrListRs[0] == 0 ) {
		$dbcon -> dbcon_close();
		alert_back("잘못된 게시판 정보입니다.1");
		exit;
	}
	
	$ListRs = $dbcon -> fetch_array($ArrListRs[1]);
	extract($ListRs);

	unset($ListRs);
	unset($ArrListRs);

	// 메일 추가
	@include_once $path_skin_mail."mail_01.php";
	
	$parameter .= "&bc_id=".$bc_id;
	
	#### 로그인 체크
	if ($bc_login_check == "Y") {
		if ( getLen($ss_u_idx) == 0 ) {
			$dbcon -> dbcon_close();
			alert_page("로그인 후 이용해 주십시오.",$url_login);
			exit;
		}
	}
	
	#### 게시판 권한설정
	if ( getLen($ss_u_level) > 0 ) {
		$auth_level			= $ss_u_level;
	} else {
		$auth_level			= 0;
	}
	
	// 관리자 설정
	if ( $auth_level >= $auth_admin ) {
		$auth_view			= 1;
		$auth_write			= 1;
		$auth_modify		= 1;
		$auth_delete		= 1;
		$auth_notice		= 1;
		$auth_reply	 		= 1;
		$auth_comment	= 1;
		$auth_hidden		= 1;
		$auth_upload		= 1;
		$auth_download	= 1;
		$auth_secret		= 1;
	}
	else {
		if ( $auth_level >= $bc_auth_view ) $auth_view				= 1;
		if ( $auth_level >= $bc_auth_write ) $auth_write				= 1;
		if ( $auth_level >= $bc_auth_modify ) $auth_modify			= 1;
		if ( $auth_level >= $bc_auth_delete ) $auth_delete			= 1;
		if ( $auth_level >= $bc_auth_notice ) $auth_notice			= 1;
		if ( $auth_level >= $bc_auth_reply ) $auth_reply	 			= 1;
		if ( $auth_level >= $bc_auth_comment ) $auth_comment	= 1;
		if ( $auth_level >= $bc_auth_hidden ) $auth_hidden			= 1;
		if ( $auth_level >= $bc_auth_upload ) $auth_upload			= 1;
		if ( $auth_level >= $bc_auth_download ) $auth_download	= 1;
		if ( $auth_level >= $bc_auth_secret ) $auth_secret		= 1;
	}
	
	#### 접속자 정보 가져오기 ####
	if ($auth_level > 0) {
		$MEMRS = getMemberInfo("u_id", $ss_u_id);

		$now_writer			= $MEMRS[u_id];
		$now_nick_name		= $MEMRS[u_name];
		$now_passwd			= $MEMRS[u_pw];
		$now_email1			= $MEMRS[u_email1];
		$now_email2			= $MEMRS[u_email2];
		$now_homepage		= $MEMRS[u_homepage];
		unset($MEMRS);
	}
	else {
		$now_writer				= "guest";
		$now_nick_name		= "";
		$now_passwd			= "";
		$now_email1			= "";
		$now_email2			= "";
		$now_homepage		= "";
	}
	
	#### mode 설정
	
	if (!$mode) {
		if ($auth_level >= $auth_admin) $mode = "list";
		else $mode = $bc_url_start;
	}

	// 설정 추가
	include_once $path_skin_board.$bc_skin."/config.php";
	
	$field = " a.seq, a.apply_date, a.occur_date, a.charge_status ";
	$field .= " ,j.o_name  ";
	$field .= " ,o.s_date, o.e_date, o.pr_name, o.ins_name, o.plan_name ";


	$table  = " tbl_board_".$bc_id." a LEFT JOIN tbl_order_list_join j on a.join_seq = j.seq";
	$table .= " LEFT JOIN tbl_order_list o ON j.orderno = o.orderno";
	$where  = " and a.notice <> 'Y' ";
	$where .= $query_where;
	$orderby = " a.seq_sub desc, a.seq desc ";
	$limit = $first.", ".$last;
	
	
	
	$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);
	$total_record = $ArrListRs[0];
	########################################	
	
	########################################
	#### 전체 페이지수를 계산한다.
	$total_page = ceil($total_record/$num_per_page);
	$no = $total_record - $first;
	
?>
<?
	$tm = "MN2";
	$lm = "";
	include $path_admin."inc/header.php";
	
?>
<script>
function write_go() {
	<? if ($auth_write || $auth_level >= $auth_admin) { ?>
		location.href = "?mode=write<?=$parameter?>";
	<? } else { ?>
		alert("<?=$msg_login_auth?>");
	<? } ?>
}

function mod_go(seq) {
	<? if ($auth_view || $auth_level >= $auth_admin) { ?>
		location.href = "?mode=mod&seq="+seq+"&page=<?=$page?><?=$parameter?>";
	<? } else { ?>
		alert("<?=$msg_login_auth?>");
	<? } ?>
}

function page_go(page) {
	location.href = "?mode=list&page="+page+"&<?=$GLOBALS[parameter]?>";
}

function excel_go() {
	location.href = "excel_charge.php?mode=excel&<?=$GLOBALS[parameter]?>";
}

function setData(d) {

	var data = $.parseJSON($(d).attr("data"));

	$("#txt_o_name").html(data.o_name);
	$("#txt_pr_name").html(data.pr_name);
	$("#txt_stock_isdn").html(data.stock_isdn)
	$("#txt_ins_name").html(data.ins_name);
	$("#txt_plan_name").html(data.plan_name);
	$("#txt_join_date").html(data.s_date+" ~ "+data.e_date);
	$("#txt_nation_name").html(data.c_name);
	$("#txt_o_phone").html(data.o_phone);
	$("#txt_birth_date").html(data.birth_date);
	$("#txt_gender").html(data.gender_name);
	$("#join_seq").val(data.seq);

	$("#join_link").attr("href","/admin/mn1/join_view.php?seq="+data.seq);
	$("#join_link").attr("target","_blank");

	self.close();
}

function WriteChargeOkGo() {
	ff = document.WriteForm;

	<? if($mode == "write" ) { ?>
	if(!ff.join_seq.value) {
		alert("가입자 정보를 선택해 주세요.");
		return false;
	}
	<? } ?>

	if (!ff.charge_type.value) {
		alert("청구유형을 선택해 주세요.");
		ff.charge_type.focus();
		return false;
	}

	if (!ff.charge_status.value) {
		alert("청구상태를 선택해 주세요.");
		ff.charge_status.focus();
		return false;
	}
	
	<? if ($bc_editor_use == "Y") { ?>
		// 에디터의 내용을 textarea 에 삽입
//		if(!checkSpacContents()) {
//			alert("내용을 입력해주세요");
//			editor.focus();
//			return false;
//		}
		if(ff.content.value == "") {
			alert("내용을 입력해주세요");
			webnote.focusWebNote("content")		//에디터에 포커스를 주기위한 webnote 내장함수
			return false;
		}
	<? } else { ?>

		if(!ff.content.value) {
			alert('내용을 입력하세요!');
			ff.content.focus();
			return false;
		}
	<? } ?>


	<? if ( $mode == "mod" ) { ?>
		ff.mode.value = "mod_ok";
		//ff.action = "notice_mod_ok.php";
	<? } elseif ( $mode == "write" ) { ?>
		ff.mode.value = "write_ok";
		//ff.action = "notice_write_ok.php";
	<? } elseif ( $mode == "reply" ) { ?>
		ff.mode.value = "reply_ok";
		//ff.action = "notice_write_ok.php";
	<? } ?>
	ff.action = "<?=$PHP_SELF?>";
	ff.target = "board_iframe";
	//ff.submit();
}


</script>


<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">청구 관리</td>
	</tr>
	<tr>
		<td colspan="2" height="20"></td>
	</tr>
</table>

<!-- 190725 수정 800px->100% -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td>
		<? 
		
		if($mode == "list") { ?>
		<!--  (s) 리스트 스타일  -->
		<!-- (s) 검색영역  -->
		<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
		<input type="hidden" name="bc_id" value="<?=$bc_id?>">
		<input type="hidden" name="mode" value="list">
		<table class="adm-searchForm">
			<colgroup>
				<col width="8%" />
				<col width="92%" />
			</colgroup>
			<tr>
				<th>청구</th>
				<td><select name="pr_cd">
						<option value="">상품명 선택</option>
						<?while ($pr_row = $dbcon -> fetch_array($RS_PR) ) {?>
							<option value="<?=$pr_row["seq"]?>" <?if ($pr_cd==$pr_row["seq"]){?>selected<?}else{}?>><?=$pr_row["subject"]?></option>
						<? } ?>
					</select>
					<select name="ins_cd" class="ml10">
						<option value="">보험사 선택</option>
						<?while ($ins_row = $dbcon -> fetch_array($RS_INS) ) {?>
							<option value="<?=$ins_row["seq"]?>" <?if ($ins_cd==$ins_row["seq"]){?>selected<?}else{}?>><?=$ins_row["subject"]?></option>
						<? } ?>
					</select>
					<select name="charge_status" class="ml10">
						<option value="">청구상태 선택</option>
						<? foreach($charge_status_arr as $key=>$val) {?>
							<option value="<?=$key;?>" <?=$charge_status==$key ? "selected":"";?>><?=$val;?></option>
						<? } ?>
					</select>
				</td>
			</tr>
			<tr>
				<th>청구일</th>
				<td><input type="text" name="apply_start_date" class="datepicker w100" value="<?=$apply_start_date;?>"> ~ <input type="text" name="apply_end_date" class="datepicker w100" value="<?=$apply_end_date;?>">
				</td>
			</tr>
			<tr>
				<th>직접검색</th>
				<td>
					<select name="search">
						<option value="u_name" <? if ($search == "u_name" ) echo "selected"; ?>>이름</option>
						<option value="mobile" <? if ($search == "mobile" ) echo "selected"; ?>>휴대폰번호</option>
					</select>
					<input type="text" name="search_text" value="<?=$search_text?>" />
					<input type="submit" value="검색" />
				</td>
			</tr>
			</table>			
		
			</form>
			<!-- (e) 검색영역  -->
		<form method="post" name="frmCheckDel" action="<?=$PHP_SELF?>">
		<input type=hidden name=f_delete>
		<div class="btnWrapR">
			<a href="javascript:;" class="btn_excel" onClick="excel_go()">엑셀다운로드</a>
		<? if ($auth_write) { ?>
			<a href="javascript:write_go();" class="btn_add">등록</a>
		<? } ?>
		</div>
		
		<table class="adm-list-tb">
		<colgroup>
			<col width="6%" />
			<col width="10%" />
			<col width="10%" />
			<col width="10%" />
			<col width="10%" />
			<col width="*" />
			<col width="10%" />
			<col width="10%" />
			<col width="10%" />
		</colgroup>
		<tr>
			<th>NO</th>
			<th>상품명</th>
			<th>보험사</th>
			<th>플랜명</th>
			<th>가입자</th>
			<th>보험기간</th>
			<th>청구일</th>
			<th>발생일</th>
			<th>청구상태</th>
		</tr>
		<?

		if ( $total_record > 0 ) {
		$temp_num = 1;
		$temp_num_img=0;
			while($ListRs = $dbcon -> fetch_array($ArrListRs[1])) { 
				$ListRs["o_name"] = all_seed_dec($ListRs["o_name"]);
				extract($ListRs);
				unset($ListRs);

			?>
		<tr>
			<td><?=$no?></td>
			<td><?=$pr_name?></td>
			<td><?=$ins_name?></td>
			<td><?=$plan_name?></td>
			<td><a href="javascript:;" onClick="mod_go('<?=$seq;?>')"><?=$o_name?></a></td>
			<td><?=$s_date?> ~ <?=$e_date?></td>
			<td><?=$apply_date;?></td>
			<td><?=$occur_date;?></td>
			<td><?=$charge_status_arr[$charge_status];?></td>
		</tr>
			<? 
			$no--;
			$temp_num++;
			$temp_num_img++;
			
			} ?>
		<? } else { ?>
		<tr>
			<td colspan="9">등록 된 데이터가 없습니다.</td>
		</tr>
		<? }
		unset($ArrListRs);
		?>
		</table>
		
		<!-- ### 페이지 시작 ###  -->
		<table border="0" cellspacing="0" cellpadding="0" width="100%">
			<tr>
				<td align="center" valign="top" style="padding:10px 0 0 0">
					<? list_page($page, $total_page, $page_per_block); ?></td>
				</td>
			</tr>
		</table>
		<!-- ### 페이지 끝 ###  -->		
		
		<!--  (e) 리스트 스타일 -->
		<? } else {
				include_once $path_board."board.php";
			}
		?>
		</td>
		</tr>
		</table>
		</form>
		
		


<? include $path_admin."inc/footer.php"; ?>


<? $dbcon -> dbcon_close();?>

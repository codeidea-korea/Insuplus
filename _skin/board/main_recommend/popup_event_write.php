<!DOCTYPE html>
<?
include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
// 관리자 체크
admin_chk($auth_admin, $url_admin_login_out);

$tm = "";
$lm = "";
include $path_admin."inc/header_pop.php";

$search							= REQSTR($search, "");
$search_text					= REQSTR($search_text, "");
$parameter = "&search=".$search."&search_text=".$search_text;

//$dbcon -> setDebug(1);
$page_btn_prev =">>"; // > 버튼
$page_btn_next ="<<"; // < 버튼

// 페이지 설정
$page				= REQSTR($page, 1);
$num_per_page	= REQSTR($num_per_page, 20);
$page_per_block	= REQSTR($page_per_block, 10);
$first					= $num_per_page*($page-1);
$last					= $num_per_page*$page;

// 검색설정
$query_where		= "  ";

#### 검색 설정 Start
$search_u_level				= REQSTR($search_u_level, "");
$search_u_gubun				= REQSTR($search_u_gubun, "");
$search_u_state				= REQSTR($search_u_state, "");
$search_u_sex				= REQSTR($search_u_sex, "");

$search							= REQSTR($search, "");
$search_text					= REQSTR($search_text, "");

$search_orderby				= REQSTR($search_orderby, "");
$search_sort					= REQSTR($search_sort, "");

$search_date					= REQSTR($search_date, "");
$search_date_s				= REQSTR($search_date_s, "");
$search_date_e				= REQSTR($search_date_e, "");
$seq				= REQSTR($seq, "");


$query_where .= "seq=".$seq;


//	echo "search_u_level : ".$search_u_level."<BR>";
//	echo "search_u_gubun : ".$search_u_gubun."<BR>";
//	echo "search_u_state : ".$search_u_state."<BR>";
//	echo "search_u_sex : ".$search_u_sex."<BR>";
//	echo "search_orderby : ".$search_orderby."<BR>";
//	echo "search_sort : ".$search_sort."<BR>";
//	echo $query_where."<BR>";


$parameter = "&search=".$search."&search_text=".$search_text;

#### 검색 설정 End

// 쿼리설정
$sql			= " SELECT * FROM tbl_board_main_event WHERE SEQ='".$seq."'";

$result = $dbcon -> query($sql);


//이미지 파일 설정
$upload_path = $_SERVER["DOCUMENT_ROOT"]."/_data/board/main_event/";
$bc_upfile_image_thum_width = "298";
$bc_upfile_image_thum_height = "198";
$image_view_width = 72;
$image_view_height = 54;
$upload_url = $url_data."board/main_event";// 업로드 폴더
?>
<div class="popupWrap">
	<header>
		<h1>이벤트 찾기</h1>
		<a href="javascript:;" onClick="self.close();" class="close">닫기</a>
	</header>
	
	<div class="popContWrap">
	<form name="WriteForm" action="" method="post" enctype="multipart/form-data" onsubmit="return WriteOkGo1()">
	<input type="hidden" name="bc_id" value="<?=$bc_id?>">
	<input type="hidden" name="page" value="<?=$page?>">
	<input type="hidden" name="search_category" value="<?=$search_category?>">
	<input type="hidden" name="search" value="<?=$search?>">
	<input type="hidden" name="search_text" value="<?=$search_text?>">
	<input type="hidden" name="act" value="ok">
	<input type="hidden" name="mode" value="">
	<input type="hidden" name="seq" value="<?=$seq?>">
	<input type="hidden" name="seq_sub" value="<?=$seq_sub?>">
	<input type="hidden" name="seq_level" value="<?=$seq_level?>">
	<input type="hidden" name="writer" value="<?=$writer?>">
	<input type="hidden" name="nick_name" value="<?=$nick_name?>" maxlength="20"  size="20" class="input">
	<input type="hidden" name="content" value=".">
	<table class="adm-view-tb mt20">
		<colgroup>
			<col width="12%"/>
			<col width="88%"/>
		</colgroup>
		<?if($mode == "mod"){
		while ($rows = $dbcon -> fetch_array($result)) {
							extract($rows);
							unset($rows);
		?>
		<tr>
			<th>제목</th>
			<td><input type="text" id="subject" name="subject" class="w100p" value="<?=$subject?>"/></td>
		</tr>
		<tr>
			<th>pc 배너 이미지<br/><span class="txt_red">(274 x 128)</span></th>
			<td>
			<?
				$ObjFileName = "imgfile";
			?>
				<table class="fileTb" id="Tbl<?=$ObjFileName?>" width="100%" cellpadding=0 cellspacing=0></table>
				<?
				//echo $imgfile."<BR>";
				$ObjFileName = "imgfile";
				if ( getLen($seq) > 0 && getLen($$ObjFileName) > 0 ) {
					${
						"Arr_".$ObjFileName}
					= setFileName($$ObjFileName);
					for ( $i = 0 ; $i < count(${"Arr_".$ObjFileName}); $i++) {
					?>
					<script>add_file('Tbl<?=$ObjFileName?>', 1, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" disabled> 삭제 <input type="checkbox" name="<?=$ObjFileName?>_del[]" value="1" onclick="UseFile(this, \'<?=$ObjFileName?>\', \'<?=$i?>\')"> , 등록파일 : <?=${"Arr_".$ObjFileName}[$i][0]?> <?=PrintFileSize(${"Arr_".$ObjFileName}[$i][2])?>');</script>
					<?
					}
				}
				// 신규 파일 등록
				else {
					?>
					<script>add_file('Tbl<?=$ObjFileName?>', 1, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" class="a_input">');</script>
					<?
				}
				?>
				<img id="<?=$ObjFileName?>" width="0" height="0">
			</td>
		</tr>
		<tr>
			<th>URL</th>
			<td><input type="text" id="pc_url" name="pc_url" class="w100p" value="<?=$pc_url?>"/></td>
		</tr>
			<?}
		} else {?>
		<tr>
			<th>제목</th>
			<td><input type="text" id="subject" name="subject" class="w100p" value="<?=$subject?>"/></td>
		</tr>
		<tr>
			<th>pc 배너 이미지<br/><span class="txt_red">(274 x 128)</span></th>
			<td>
			<?
				$ObjFileName = "imgfile";
			?>
				<table class="fileTb" id="Tbl<?=$ObjFileName?>" width="100%" cellpadding=0 cellspacing=0></table>
				<?
				//echo $imgfile."<BR>";
				$ObjFileName = "imgfile";
				if ( getLen($seq) > 0 && getLen($$ObjFileName) > 0 ) {
					${
						"Arr_".$ObjFileName}
					= setFileName($$ObjFileName);
					for ( $i = 0 ; $i < count(${"Arr_".$ObjFileName}); $i++) {
					?>
					<script>add_file('Tbl<?=$ObjFileName?>', 1, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" disabled> 삭제 <input type="checkbox" name="<?=$ObjFileName?>_del[]" value="1" onclick="UseFile(this, \'<?=$ObjFileName?>\', \'<?=$i?>\')"> , 등록파일 : <?=${"Arr_".$ObjFileName}[$i][0]?> <?=PrintFileSize(${"Arr_".$ObjFileName}[$i][2])?>');</script>
					<?
					}
				}
				// 신규 파일 등록
				else {
					?>
					<script>add_file('Tbl<?=$ObjFileName?>', 1, '<input type="file" name="<?=$ObjFileName?>[]" id="<?=$ObjFileName?>" style="width:50%" maxlength="255" class="a_input">');</script>
					<?
				}
				?>
				<img id="<?=$ObjFileName?>" width="0" height="0">
			</td>
		</tr>
		<tr>
			<th>URL</th>
			<td><input type="text" id="pc_url" name="pc_url" class="w100p" value="<?=$pc_url?>"/></td>
		</tr>
		<?}?>
		</table>
		
		<!-- (s) 하단  버튼 영역 -->
		<div class="btnWrap">
			<div class="leftWrap">
				<a href="popup_event_list.php" class="btn_list">목록</a>
			</div>
			<div class="rightWrap">
			<a href="javascript:del_go1('<?=$seq?>');" class="btn_normal">삭제</a>
			<input type="submit" value="등록" class="btn_add" />
			</div>
		</div>
		<!-- (e) 하단  버튼 영역 -->
	</form>		
		
	</div>
</div>
<script>
	function list_go(page) {
		location.href = "?page="+page+"<?=$parameter?>";
	}

	function del_go1(seq) {
		if (confirm("정말로 삭제 하시겠습니까?\n삭제한 정보는 복구가 불가능합니다.")) {
			location.href = "/_skin/board/main_recommend/set_main_event.php?bc_id=main_event&act=ok&mode=del_ok&seq="+seq+"<?=$parameter?>";
		}
	}
	function WriteOkGo1() {
		ff = document.WriteForm;

		if (!ff.subject.value) {
			alert("제목을 입력하여 주십시오.");
			ff.subject.focus();
			return false;
		} else if(!ff.pc_url.value) {
			alert("URL을 입력하여 주십시오.");
			ff.pc_url.focus();
			return false;
		}

		

		<? if ( $mode == "mod" ) { ?>
			ff.mode.value = "mod_ok";
			ff.action = "set_main_event.php?";
			//ff.action = "notice_mod_ok.php";
		<? } elseif ( $mode == "write" ) { ?>
			ff.mode.value = "write_ok";
			ff.action = "set_main_event.php?";
			//ff.action = "notice_write_ok.php";
		<? } ?>
		//ff.target = "board_iframe";
		ff.submit();
	}
</script>
</html>
<?
	$dbcon -> dbcon_close();
?>
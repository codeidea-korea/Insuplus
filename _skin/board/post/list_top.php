<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>


<?
if ($mymode == "mypost"){
###############################################
## 마이페이지
###############################################
?>
				<!-- InConts -->
				<div id="InConts" class="Inconts">

					<!-- 컨텐츠 내용 -->
					<div class="notice">
						<strong><img src="/images/common/layout/tit_notice.png" alt="NOTICE" /></strong>
						<span>본 게시판 성격에 맞지 않은 게시물 등록 시, 무통보 삭제 될 수 있습니다.
							<a href="javascript:write_go();" class="boxTxt boxSt06" style="width:88px;">글쓰기</a>
						</span>
					</div>

					<div class="board blankAreaTy01">
						<div class="boardList01">
							<table>
								<caption>나의후기 게시판</caption>
								<colgroup>
									<col style="width:7%;" />
									<col style="width:10%;" />
									<col style="width:7%;" />
									<col width="*" />
									<col style="width:15%;" />
								</colgroup>
								<thead>
									<tr>
										<th scope="col">번호</th>
										<th scope="col">지점</th>
										<th scope="col">구분</th>
										<th scope="col">제목</th>
										<th scope="col">등록일</th>
									</tr>
								</thead>
								<tbody>

<?
}else{
##############################################
## 일반페이지
##############################################
// BEST 시술후기
		$field = " * ";

		if ($bc_category_use == "Y") {
			$field .= " , (select cate_name from tbl_category where category='board' and bc_id = '".$bc_id."' and idx = A.category ) as cate_name";
		}

		if ($bc_comment_use == 'Y') {
			$field .= " , (select count(idx) from tbl_comment where category='board' and bc_id = '".$bc_id."' and seq = A.seq ) as cmt_cnt ";
		}

		if ($bc_upfile_cnt > 0 ) {
			$field .= " , (select count(idx) from tbl_file where category='board' and bc_id = '".$bc_id."' and seq = A.seq ) as file_cnt ";
		}

		$table = "tbl_board_".$bc_id." A";
		$where = " and ext3 = 'Y' ";
		if ( !($bc_hidden_use == "Y" && $auth_hidden) ) { $where .= " and hidden <> 'Y' ";}
		if ($auth_level < $auth_admin ) $where .= " and hidden <> 'D' ";
		$orderby = " seq_sub desc ";
		$limit = " 0, 4 ";
		$ArNoticeRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);

		$tot_Notice = $ArNoticeRs[0];
?>
				<!-- InConts -->
				<div id="InConts" class="Inconts">

					<!-- 컨텐츠 내용 -->
					<!--

					<h4 class="titleTy01">BEST 시술후기</h4>

					<ul class="bComment mT25">
<?
		if ( $tot_Notice > 0 ) {
			$tot_Notice_cnt = 0;
			while($NoticeRs = $dbcon -> fetch_array($ArNoticeRs[1])) {
				extract($NoticeRs);
				unset($NoticeRs);

				$tot_Notice_cnt = $tot_Notice_cnt +1;


				if ( $bc_editor_use == "Y" ) {
					$content = RESSTR($content);
				}
				else {
					$content = RESSTRTEXT($content);
				}

				$subject = getStrCut( $subject, $bc_title_size, "...");

				// 계층형 레벨
				$re = "";
				if ($seq_level > 0 ) {
					for ($i = 0; $i < $seq_level; $i++) $re .= "&nbsp;&nbsp;&nbsp;&nbsp;";
					$re .= $img_re;
				}

				$PrintRegDate = date('Y/m/d', strtotime($regdate) );
				// NEW 이미지
				$sNew="";
				if(strtotime($regdate) > (time() - (60 * 60 * 24 * 2))) {
					$sNew = $img_new;
				}

				// hidden 표시
				$print_hidden = "";
				if ( $bc_hidden_use == "Y" && $auth_hidden) {
					if ($hidden == "Y") {
						$print_hidden = "<font color='red'>[hidden]</font>";
					}
					if ($hidden == "D") {
						$print_hidden = "<font color='red'>[delete]</font>";
					}
				}

				$print_secret = "";//			icon_secret
				$view_link = "<a href=\"javascript:view_go('".$seq."');\">";

				// 비밀글 사용시
	//			echo "bc_secret_use : ".$bc_secret_use."<BR>";
	//			echo "secret : ".$secret."<BR>";
				if ( $bc_secret_use == "Y" && $secret == "Y" ) {

					$checkPass = true;
					$print_secret = $img_secret ;

					// 관리자는 그냥 패스~
					if ( $auth_level >= $auth_admin ) {
						$checkPass = false;
					}

					// 현재 접속자가 게시글 등록자이거나 현재접속자가 비회원이 아니라면
					if ( $now_writer == $writer && $writer != "guest" ) {
						$checkPass = false;
					}

					//echo "seq_level : ".$seq_level."<BR>";
					// 답변글이고
					if ( $seq_level > 0 ) {
						$SQL = "
							select writer from tbl_board_".$bc_id." where seq_sub = '".($seq_sub+1)."'
						";
						$Parent_writer = $dbcon -> getCount($SQL);

//						echo "Parent_writer : ".$Parent_writer."<BR>";

						//현재 접속자가 부모글의 작성자이거나 현재접속자가 비회원이 아니라면
						if ( $Parent_writer == $now_writer && $writer != "guest" ) {
							$checkPass = false;
						}
						unset ($Parent_writer);
					}

				}

				// #### 카테고리 표시
				$print_cate_name = "";
				if ($bc_category_use == "Y") {
					if ($cate_name){
					$print_cate_name = $cate_name;
					}else{
					$print_cate_name = "전체";
					}
				}

				// 코멘트 갯수 출력
				$print_cmt_cnt = "";
				if ($bc_comment_use == 'Y') {
					if ($cmt_cnt > 0 ) $print_cmt_cnt = "(".$cmt_cnt.")";
				}

				$print_file = "";
				if ($bc_upfile_cnt > 0 ) {
					if ($file_cnt > 0 )
						$print_file = $img_file;
				}

				// 파일 이미지
				$print_list_image = "";
				$print_list_image_size = "";
				$image_view_width = $bc_upfile_image_thum_width;
				$image_view_height = $bc_upfile_image_thum_height;
				$FC_file = "";

				if ($bc_upfile_image == "Y" && $bc_upfile_image_thum == "Y" ) {
					$ObjFileName = "imgfile";

					if ( getLen(${$ObjFileName}) > 0 ) {
						${"Arr_".$ObjFileName} = setFileName(${$ObjFileName});

						if (${"Arr_".$ObjFileName}[0][1]){

							for ( $i = 0 ; $i < count(${"Arr_".$ObjFileName}); $i++) {

								if ( ${"info".$ObjFileName} = @getimagesize($upload_path."/".${"Arr_".$ObjFileName}[$i][1].".thumb") ) {
									${"info".$ObjFileName."width"} = ${"info".$ObjFileName}[0];
									${"info".$ObjFileName."height"} = ${"info".$ObjFileName}[1];

									if ( ${"info".$ObjFileName."width"} >= ${"info".$ObjFileName."height"}){
										if ( ${"info".$ObjFileName."width"} > $image_view_width ) {
											${"size".$ObjFileName} = " width=\"".$image_view_width."\" ";
										}
										else {
											${"size".$ObjFileName} = " width=\"".${"info".$ObjFileName."width"}."\" ";
										}
									}
									else {
										if ( ${"info".$ObjFileName."height"} > $image_view_height ) {
											${"size".$ObjFileName} = " height=\"".$image_view_height."\" ";
										}
										else {
											${"size".$ObjFileName} = " height=\"".${"info".$ObjFileName."height"}."\" ";
										}
									}

									$FC_file = $upload_url."/".${"Arr_".$ObjFileName}[$i][1].".thumb";
									$FC_file_img = $upload_url."/".${"Arr_".$ObjFileName}[$i][1];
									$print_list_image = "<img src=\"".$upload_url."/".${"Arr_".$ObjFileName}[$i][1].".thumb"."\" name=\"Image1\" width=\"".$image_view_width."\" height=\"".$image_view_height."\" border=\"0\" />";
									$print_list_image_url = $upload_url."/".${"Arr_".$ObjFileName}[$i][1].".thumb";
									//$print_list_image = "<img src=\"".$upload_url."/".${"Arr_".$ObjFileName}[$i][1].".thumb"."\" name=\"Image1\" ".${"size".$ObjFileName}." border=\"0\" />";

									break;
								}
							}
						}else{
									$print_list_image = "<img src=\"".$upload_url."/".${$ObjFileName}."\" name=\"Image1\" width=\"110\" height=\"102\" border=\"0\" />";
						}
					}else{
						$print_list_image = "<img src='/images/noimage.gif' name=\"Image1\" width=\"111\" height=\"73\" border=\"0\" style=\"border:2px #CCCCCC solid;\" />";
					}
				}
				else {
					$print_list_image = "<img src='/images/noimage.gif' name=\"Image1\" width=\"111\" height=\"73\" border=\"0\" style=\"border:2px #CCCCCC solid;\" />";
				}
?>
						<li><?=$view_link?>
								<span class="img"><?=$print_list_image?></span>
								<strong class="txt">
									<span class="iconSt03"><?=$print_cate_name?></span>
									<?=getStrCut($subject,20,"..");?>
								</strong>
							</a>
						</li>
<?
			}
		}
		unset($ArNoticeRs);
?>
					</ul>
			-->

			<form name="SearchForm" method="get" action="<?=$PHP_SELF?>" onsubmit="return search_go()">
			<input type="hidden" name="bc_id" value="<?=$bc_id?>">
			<input type="hidden" name="mode" value="list">
			<? if ($bc_category_use == "Y") { ?>
			<input type="hidden" name="search_category" value="<?=$search_category?>">
			<? } ?>
					<!-- 컨텐츠 내용 -->
					<div class="board "><!-- mT50 -->
						<div class="title">
							<p class="num01"><span>전체 <em class="fColor01 bold"><?=$total_record?>건</em></span> | <span>페이지 <em class="fColor01 bold"><?=$page?> / <?=$total_page?></em></span> 건</p>
							<div class="search">
								<?if ($area_code==''){?>
								<select name="search" onchange="cate_go(this.value)" class="selectSt01" style="width:88px;">
								<option value="all" <? if ($search_category == "all" || $search_category == "" ) echo "selected"; ?>>전체</option>
								<?
								while ($CateListRs = $dbcon -> fetch_array($ArrCateListRs[1]) ) {
								extract($CateListRs);
								?>
								<option value="<?=$idx?>" <? if ($search_category == $idx ) echo "selected"; ?>><?=$cate_name?></option>
								<?
								}
								?>
								</select>

								<select name="search_ext" onchange="this.form.submit();" class="selectSt01" style="width:88px;">
								<option value="" <? if ($search_category == "all" || $search_category == "" ) echo "selected"; ?>>전체</option>
								<?
								$arr_k = 0;
								foreach ($Arr_cost_detail_list as $key => $val) {?>
								<option value="<?=$key?>" <? if ($key == $search_ext ) echo "selected"; ?>><?=$val?></option>
								<?
								}
								?>
								</select>
								<?}?>

								<select title="게시판 검색" class="selectSt01" style="width:88px;" name="search">
									<option value="subject" <? if ($search == "subject" ) echo "selected"; ?>>제목</option>
									<option value="content" <? if ($search == "content" ) echo "selected"; ?>>내용</option>
									<option value="nick_name" <? if ($search == "nick_name" ) echo "selected"; ?>>이름</option>
									<option value="all" <? if ($search == "all" ) echo "selected"; ?>>전체</option>
								</select>

								<input type="text" class="inpSt01" style="width:124px;" title="검색어를 입력" name="search_text" value="<?=$search_text?>"/><input type="image" src="/images/board/btn_search01.gif" alt="검색" />
							</div>
						</div>
				</form>

						<div class="boardList01 blankAreaTy01">
							<table>
								<caption>시술후기 게시판</caption>
								<colgroup>
									<col style="width:7%;" />
									<col style="width:10%;" />
									<col style="width:7%;" />
									<col width="*" />
									<col style="width:10%;" />
									<col style="width:10%;" />
									<col style="width:7%;" />
								</colgroup>
								<thead>
									<tr>
										<th scope="col">번호</th>
										<th scope="col">지점</th>
										<th scope="col">구분</th>
										<th scope="col">제목</th>
										<th scope="col">글쓴이</th>
										<th scope="col">등록일</th>
										<th scope="col">조회수</th>
									</tr>
								</thead>
								<tbody>
<?}?>

<?
#############################################################
## 관리자 모드
#############################################################
}else{?>
    <? if ($bc_category_use == "Y") {?>
<!-- 카테고리 검색 Start -->
<table class="b_search_box">
	<tr>
		<td>
			<select name="search" class="select" onchange="cate_go(this.value)">
			<option value="all" <? if ($search_category == "all" || $search_category == "" ) echo "selected"; ?>>전체</option>
			<?
        while ($CateListRs = $dbcon -> fetch_array($ArrCateListRs[1]) ) {
            extract($CateListRs);
			?>
			<option value="<?=$idx?>" <? if ($search_category == $idx ) echo "selected"; ?>><?=$cate_name?></option>
			<?
        }
			?>
			</select>
		</td>
	</tr>
</table>
<table border=0 cellspacing=0 cellpadding=0 width=100%>
	<tr>
		<td height=10></td>
	</tr>
</table>
<!-- 카테고리 검색 End -->
<?
    }
?>

<form method="post" name="frmCheckDel" action="<?=$PHP_SELF?>">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td colspan="8" class="m_line_2px">&nbsp;</td>
	</tr>
	<tr>
		<?
    if ($ss_u_level >= $auth_admin) {
		?>
		<!-- <td width="50"  class="b_txt"><a href="javascript:checkInverse()"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_select.gif"></a></td> -->
		<?
    }
		?>


		<td width="50"  class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_no.gif"></td>
		<?
    if ($bc_category_use == "Y") {
		?>
		<td width="70" class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_category2.gif"></td>
		<?
    }
		?>
		<td class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_title.gif"></td>

		<?
    if ( $bc_upfile_cnt > 0 ) {
		?>
		<td width="41" class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_fileview.gif"></td>
		<?
    }
		?>
		<td width="70" class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_writer.gif"></td>
		<td width="70" class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_date.gif"></td>
		<td width="50" class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_view.gif"></td>
		<td width="70" class="b_txt"><img src="<?=$url_skin_board.$bc_skin?>/images/b_txt_state.gif"></td>
	</tr>
	<tr>
		<td colspan="8" class="m_line_1px">&nbsp;</td>
	</tr>
	<?}?>

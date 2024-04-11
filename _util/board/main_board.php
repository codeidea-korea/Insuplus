<?
	//include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";


	function getThumbnailBoard ($bc_id, $row, $url, $length = "", $category = "" ) {
		global $dbcon;

		########################################
		#### 게시글을 가져온다.. ####
		$field = " * ";
		$table = "tbl_board_".$bc_id." A";
		$where .= "
			and notice <> 'Y'
			and hidden <> 'Y'
			and hidden <> 'D'
		";
		if ( getLen($category) > 0 ) {
			$where .= " and category = '".$category."' " ;
		}
		$orderby = " seq_sub desc ";
		$limit = "0, ".$row;

		$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);

		$total_record = $ArrListRs[0];
		########################################



?>
<table width="322" border="0" cellspacing="0" cellpadding="0">
<?
		if ($total_record == 0) {
			//return 0;
?>
	<tr>
		<td valign="top" class="main_cate" colspan="3" align="center">등록된 글이 없습니다.</td>
	</tr>
<?
		}
		else {
			while ( $ListRs = $dbcon -> fetch_array($ArrListRs[1]) ) {
				extract($ListRs);
				$PrintRegDate = date('Y/m/d', strtotime($regdate) );
				if ( mb_getLen($subject) > $length ) {
					$subject = mb_substr($subject,0,$length,"UTF-8")."...";
				}

				$view_url = $url."?mode=view&seq=".$seq;
				unset($ListRs);
?>
	<tr>
		<td width="19" height="20" valign="top"><img src="../images/main_tab_ball.gif" width="19" height="20"></td>
		<td valign="top" class="main_cate"><a href="<?=$view_url?>"><?=$subject?></a></td>
		<td width="73" valign="top" class="main_cate">[<?=$PrintRegDate?>]</td>
	</tr>
<?

			}
		}
		unset($ArrListRs);
?>
</table>
<?
	}


	function getThumbnailBoard2 ($bc_id, $row, $url, $length = "", $category = "" ) {
		global $dbcon;
//		$dbcon -> dbcon_close();
//		$dbcon -> dbcon_open(1);

		########################################
		#### 게시글을 가져온다.. ####
		$field = " * ";
		$table = "tbl_board_".$bc_id." A";
		$where = "";
		$where .= "
			and notice <> 'Y'
			and hidden <> 'Y'
			and hidden <> 'D'
		";
		if ( getLen($category) > 0 ) {
			$where .= " and category = '".$category."' " ;
		}
		$orderby = " seq_sub desc ";
		$limit = "0, ".$row;

		$ArrListRs = $dbcon -> getList($field, $table , $where, $orderby, $limit);

		$total_record = $ArrListRs[0];
		########################################



?>

<table width="322" border="0" cellspacing="0" cellpadding="0">
<?
		if ($total_record == 0) {
			//return 0;
?>
	<tr>
		<td valign="top" class="main_cate" colspan="3" align="center" height="58">등록된 글이 없습니다.</td>
	</tr>
<?
		}
		else {
			while ( $ListRs = $dbcon -> fetch_array($ArrListRs[1]) ) {
				extract($ListRs);
				$PrintRegDate = date('Y/m/d', strtotime($regdate) );
				if ( mb_getLen($subject) > $length ) {
					$subject = mb_substr($subject,0,$length,"UTF-8")."...";
				}
				$content		= strip_tags($content);
				if ( mb_getLen($content) > 250 ) {
					$content = mb_substr($content,0,250,"UTF-8")."...";
				}

				$print_list_image = "";
				$print_list_image_size = "";
				$image_view_width = 72;
				$image_view_height = 54;
				$FC_file = "";

				if ( $bc_upfile_cnt > 0 && $bc_upfile_image == "Y" && $file_cnt > 0 ) {
					$FileRs = upfileSelect($seq);

					while($RowFileRs = $dbcon -> fetch_row($FileRs) ) {
						$FC_file_name				= $RowFileRs[4];
						$FC_file_realname			= $RowFileRs[5];

						if (is_file($upload_path."/".$FC_file_realname)) {
							$Arr_file_info = pathinfo($upload_path."/".$FC_file_realname);
							//echo $Arr_file_info['extension']."<BR>";
							$file_info = strtolower($Arr_file_info['extension']);
							if ( ( $file_info == "jpg" || $file_info == "jpeg" || $file_info == "gif" ) ) {

								$up_file_info = getimagesize($upload_path."/".$FC_file_realname);
								$up_file_width = $up_file_info[0];
								$up_file_height = $up_file_info[1];
								$up_file_type = $up_file_info[2];

								if ($up_file_type < 4) {

	//								// 가로형 & 정사각형
	//								if ($up_file_width >= $up_file_height) {
	//									if ($up_file_width > $image_view_width) {
	//										$print_list_image_size = " width='".$image_view_width."' ";
	//									}
	//									else {
	//										$print_list_image_size = " width='".$up_file_width."' ";
	//									}
	//								}
	//
	//								// 세로형
	//								else if ($up_file_width < $up_file_height) {
	//									if ($up_file_height > $image_view_height) {
	//										$print_list_image_size = " height='".$image_view_height."' ";
	//									}
	//									else {
	//										$print_list_image_size = " height='".$up_file_height."' ";
	//									}
	//								}
									$print_list_image_size = " width=92 height=59 ";

									$FC_file = $url_data."board/".$bc_id."/".$FC_file_realname;
									$print_list_image = "<img src='".$FC_file."' ".$print_list_image_size." >";
									break;
								}
							}
						}
						unset($RowFileRs);
					}
					unset($FileRs);
				}
				else {
					$print_list_image = "<img src='/images/noimg.gif' width='72' height='54' >";
				}

				$view_url = $url."?mode=view&seq=".$seq;
				unset($ListRs);
?>
	<tr>
		<td width="13">&nbsp;</td>
		<td width="86" height="58" valign="top">
			<table width="76" border="0" cellpadding="1" cellspacing="1" bgcolor="#c8c9ce">
				<tr>
					<td align="center" bgcolor="#FFFFFF"><a href="<?=$view_url?>"><?=$print_list_image?></a></td>
				</tr>
			</table>
		</td>
		<td valign="top" class="main_after" style="padding-top:3px">
			<b><a href="<?=$view_url?>"><?=$subject?></a></b><br>
			<a href="<?=$view_url?>"><?=$content?></a>
		</td>
		<td width="13" valign="top">&nbsp;</td>
	</tr>
<?

			}
		}
		unset($ArrListRs);
?>
</table>
<?
	}
?>













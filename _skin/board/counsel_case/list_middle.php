<?
//사용자 모드입니다
if ($client_mode == "Y") {?>
	<?if ($list_type == "list") { ?>
		<div class="br_box" onclick="view_go('<?=$seq?>');">
			<div class="photobox">
				<img src="<?=$FC_file?>" align="absmiddle">
			</div>
			<div class="title"><?=$subject?></div>
		</div>
	<? } elseif ($list_type == "null") { ?>
		<span>등록 된 데이터가 없습니다.</span>
	<? } else { ?>
		.
	<? } ?>

<?
	#############################################################
	## 관리자 모드
	#############################################################
} else { ?>

	<?
	// 공지사항 출력
	if ($list_type == "notice") {
	?>
		<tr bgcolor="#F3F3F3">
			<? if ($ss_u_level >= $auth_admin) { ?>
				<!-- <td align="center"><input type="checkbox" name="view_check[]" value="<?= $num ?>"></td> -->
			<? } ?>
			<td><?= $img_notice ?></td>
			<? if ($bc_category_use == "Y") { ?>
				<td><? //=$print_cate_name
					?></td>
			<? } ?>
			<td class="l"><?= $view_link ?><?= $re ?><?= $subject ?> <?= $print_cmt_cnt ?> <?= $print_secret ?> <?= $sNew ?> <?= $print_hidden ?></a></td>

			<? if ($bc_upfile_cnt > 0) { ?>
				<td><?= $print_file ?></td>
			<? } ?>
			<td><? echo $nation_arr[$nation] != null ? $nation_arr[$nation] : "기타" ?></td>
			<!-- <td>
				<? if ($nation == "US") echo "미국";
				else if ($nation == "EU") echo "유럽";
				else if ($nation == "JP") echo "일본";
				else if ($nation == "CN") echo "중국";
				else if ($nation == "CA") echo "캐나다";
				else if ($nation == "PH") echo "필리핀";
				else if ($nation == "AU") echo "호주";
				else echo "기타";
				?>
			</td> -->
			<td><?= $PrintRegDate ?></td>
			<td><?= $view_cnt ?></td>
			<td><?= $print_reply_image ?></td>
		</tr>

	<? } elseif ($list_type == "list") { ?>
		<tr>
			<td><?= $no ?></td>
			<td><? echo print_pr_name($pr_cd) != '' ? print_pr_name($pr_cd) : "전체"; ?></td>
			<td><?= $print_cate_name ?></td>
			<td><?=$print_list_image?></td>
			<td class="l"><?= $view_link ?><?= $re ?><?= $subject ?> <?= $print_cmt_cnt ?> <?= $print_secret ?> <?= $sNew ?> <?= $print_hidden ?></a></td>
			<td><? echo $nation_arr[$nation] != null ? $nation_arr[$nation] : "기타"; ?></td>
			<td><?= $view_cnt ?></td>
			<td><?= $secret == 'Y' ? "비공개" : "공개" ?></td>
			<td><?= $PrintRegDate; ?></td>
		</tr>
	<? } elseif ($list_type == "null") { ?>
		<tr>
			<td colspan="8">등록 된 데이터가 없습니다.</td>
		</tr>

	<? } else { ?>

	<? } ?>

<? } ?>
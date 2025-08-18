<?
// ######################################################
function list_page($now_page, $total_page, $block_num)
{
    global $page_btn_first, $page_btn_prev, $page_btn_next, $page_btn_last;
    if (!$page_btn_first) {
        $page_btn_first = '<img src="/images/99_common/btn_fast_prev.gif" />';  // >> 버튼
    }
    if (!$page_btn_prev) {
        $page_btn_prev = '<img src="/images/99_common/btn_prev.gif" />';  // > 버튼
    }
    if (!$page_btn_next) {
        $page_btn_next = '<img src="/images/99_common/btn_next.gif" />';  // < 버튼
    }
    if (!$page_btn_last) {
        $page_btn_last = '<img src="/images/99_common/btn_fast_next.gif" />';  // << 버튼
    }

    // <img src='/admin/img/prev10.gif' border='0'>
    // <img src='/admin/img/prev.gif' border='0'>
    // <img src='/admin/img/next.gif' border='0'>
    // <img src='/admin/img/next10.gif' border='0'>
    ?>
				<table border="0" align="center" cellpadding="0" cellspacing="0" class="mg_top17 paging">
					<tr>
						<td width="13" style="padding-right:5px;">
		<?
        if ($now_page > $block_num) {
            if ($now_page % $block_num == 0) {
                // $block_num_prev = $now_page - $block_num * 2 + 1;
                $block_num_prev = $now_page - $block_num;
            } else {
                // $block_num_prev = floor( $now_page/$block_num) * $block_num + 1 - $block_num;
                $block_num_prev = floor($now_page / $block_num) * $block_num;
            }
            ?> <a href="javascript:page_go('<?= ($block_num_prev) ?>');"><?= $page_btn_next ?></a> <?
        } else {
            ?> <?= $page_btn_next ?> <?
        }
        ?></td><?

    /*
     * echo $now_page."<BR>";		//
     * echo $total_page."<BR>";		//
     * echo $block_num."<BR>";		//
     * echo $now_page % $block_num."<BR>";
     */

    // $start_page :
    // $last_page :

    if ($now_page % $block_num == 0) {
        $start_page = $now_page - $block_num + 1;
        $last_page = $now_page;
    } else {
        $start_page = floor($now_page / $block_num) * $block_num + 1;
        $last_page = (floor($now_page / $block_num) + 1) * $block_num;
    }

    /*
     * echo $start_page."<BR>";
     * echo $last_page."<BR>";
     */

    if ($last_page > $total_page) {
        $last_page = $total_page;
    }

    ?><td align="center" class="txt01"><?
    for ($i = $start_page; $i < $last_page + 1; $i++) {
        if ($now_page == $i) {
            ?> <span><?= $i ?></span> <?
        } else {
            ?> <a href="javascript:page_go('<?= $i ?>')"><?= $i ?></a> <?
        }

        if ($i < $last_page) {
            // echo " | ";
        }
    }

    if ($i == $start_page) {
        ?> <span><?= $i ?></span> <?
    }
    ?></td><?

    ?><td width="14" align="right" style="padding-left:5px;"><?
    if ($last_page != $total_page) {
        ?> <a href="javascript:page_go('<?= $last_page + 1 ?>')"><?= $page_btn_prev ?></a> <?
    } else {
        ?> <?= $page_btn_prev ?> <?
    }
    ?></td><?

    ?>
					</tr>
				</table>
		<script>
			function page_go(page) {
				location.href = "?mode=list&page="+page+"&<?= $GLOBALS[parameter] ?>";
			}
		</script>

<?
}

// ######################################################
function list_page_common($now_page, $total_page, $block_num)
{
    global $url_admin;

    if ($total_page == 0)
        $total_page = 1;
    $page_btn_first = '  ';  // << 버튼
    $page_btn_prev = " <img src='/_skin/board/faq/images/b_next.gif' align=absmiddle hspace=0> ";  // < 버튼
    $page_btn_next = " <img src='/_skin/board/faq/images/b_prev.gif' align=absmiddle hspace=0> ";  // > 버튼
    $page_btn_last = '  ';  // >> 버튼

    // <img src='/admin/img/prev10.gif' border='0'>
    // <img src='/admin/img/prev.gif' border='0'>
    // <img src='/admin/img/next.gif' border='0'>
    // <img src='/admin/img/next10.gif' border='0'>

    echo "
\t\t\t<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">
\t\t\t\t<tr>
\t\t\t\t\t<td height=\"21\"></td>
\t\t\t\t</tr>
\t\t\t\t<tr>
\t\t\t\t\t<td><a href=\"javascript:page_go('1');\">" . $page_btn_first . "</a></td>
\t\t\t\t\t<td style=\"padding-left:4px; padding-right:4px;\">";

    if ($now_page > $block_num) {
        if ($now_page % $block_num == 0) {
            // $block_num_prev = $now_page - $block_num * 2 + 1;
            $block_num_prev = $now_page - $block_num;
        } else {
            // $block_num_prev = floor( $now_page/$block_num) * $block_num + 1 - $block_num;
            $block_num_prev = floor($now_page / $block_num) * $block_num;
        }
        ?> <a href="javascript:page_go('<?= ($block_num_prev) ?>');"><?= $page_btn_prev ?></a> <?
    } else {
        ?> <?= $page_btn_prev ?> <?
    }
    echo '</td>';
    echo '<td width="213" class="text4" align="center">';

    /*
     * echo $now_page."<BR>";		//
     * echo $total_page."<BR>";		//
     * echo $block_num."<BR>";		//
     * echo $now_page % $block_num."<BR>";
     */

    // $start_page :
    // $last_page :

    if ($now_page % $block_num == 0) {
        $start_page = $now_page - $block_num + 1;
        $last_page = $now_page;
    } else {
        $start_page = floor($now_page / $block_num) * $block_num + 1;
        $last_page = (floor($now_page / $block_num) + 1) * $block_num;
    }

    /*
     * echo $start_page."<BR>";
     * echo $last_page."<BR>";
     */

    if ($last_page > $total_page) {
        $last_page = $total_page;
    }

    ?><?
    for ($i = $start_page; $i < $last_page + 1; $i++) {
        if ($now_page == $i) {
            ?> <font color="#63ad0e"><B><?= $i ?></B> <?
        } else {
            ?> <a href="javascript:page_go('<?= $i ?>')"><?= $i ?></a> <?
        }

        //			if ( $i < $last_page) {
        //				echo " | ";
        //			}
    }

    echo '</td>';
    echo '<td style="padding-left:4px;">';
    if ($last_page != $total_page) {
        ?> <a href="javascript:page_go('<?= $last_page + 1 ?>')"><?= $page_btn_next ?></a> <?
    } else {
        ?> <?= $page_btn_next ?> <?
    }
    echo '</td>';

    echo "
\t\t\t\t\t<td style=\"padding-left:4px;\"><a href=\"javascript:page_go('" . $total_page . '\');">' . $page_btn_last . "</a></td>
\t\t\t\t</tr>
\t\t\t</table>";
}

// #인슈플러스 사용자 페이징 UI##
function list_page_ljh($now_page, $total_page, $block_num)
{
    global $url_admin;

    if ($total_page == 0)
        $total_page = 1;
    $page_btn_first = "<i class='ti ti-angle-double-left'></i>";  // << 버튼
    $page_btn_prev = "<i class='ti ti-angle-left'></i>";  // < 버튼
    $page_btn_next = "<i class='ti ti-angle-right'></i>";  // > 버튼
    $page_btn_last = "<i class='ti ti-angle-double-right'></i>";  // >> 버튼

    echo "<li><a href='javascript:;' onClick='page_go(1)'>" . $page_btn_first . '</a></li>';

    if ($now_page > $block_num) {
        if ($now_page % $block_num == 0) {
            $block_num_prev = $now_page - $block_num;
        } else {
            $block_num_prev = floor($now_page / $block_num) * $block_num;
        }
        ?>"<li><a href='javascript:;' onClick="page_go('<?= ($block_num_prev) ?>')"><?= $page_btn_prev ?></a></li><?
    } else {
        ?><li><a href='javascript:;'><?= $page_btn_prev ?></a></li><?
    }

    if ($now_page % $block_num == 0) {
        $start_page = $now_page - $block_num + 1;
        $last_page = $now_page;
    } else {
        $start_page = floor($now_page / $block_num) * $block_num + 1;
        $last_page = (floor($now_page / $block_num) + 1) * $block_num;
    }

    if ($last_page > $total_page) {
        $last_page = $total_page;
    }

    for ($i = $start_page; $i < $last_page + 1; $i++) {
        if ($now_page == $i) {
            ?> <li class='active'><a href='javascript:;'><?= $i ?></a></li> <?
        } else {
            ?> <li><a href="javascript:;" onClick="page_go('<?= $i ?>')"><?= $i ?></a></li> <?
        }
    }

    if ($last_page != $total_page) {
        ?><li><a href='javascript:;' onClick="page_go('<?= $last_page + 1 ?>')"><?= $page_btn_next ?></a></li><?
    } else {
        ?><li><a href='javascript:;'><?= $page_btn_next ?></a></li><?
    }
    echo "<li><a href='javascript:;' onClick='page_go(" . $total_page . ")'>" . $page_btn_last . '</a></li>';
}

// ######################################################
function list_page_comment_ljh($now_page, $total_page, $block_num)
{
    global $url_admin;

    if ($total_page == 0)
        $total_page = 1;
    $page_btn_first = '<img src="/images/common/btn/btn_first.gif" alt="처음 페이지로" />';  // << 버튼
    $page_btn_prev = '<img src="/images/common/btn/btn_prev.gif" alt="이전 페이지로" />';  // < 버튼
    $page_btn_next = '<img src="/images/common/btn/btn_next.gif" alt="다음 페이지로" />';  // > 버튼
    $page_btn_last = '<img src="/images/common/btn/btn_last.gif" alt="마지막 페이지로" />';  // >> 버튼

    echo '<a href="javascript:page_comment_go(\'1\');" class="first">' . $page_btn_first . '</a>';

    if ($now_page > $block_num) {
        if ($now_page % $block_num == 0) {
            $block_num_prev = $now_page - $block_num;
        } else {
            $block_num_prev = floor($now_page / $block_num) * $block_num;
        }
        ?> <a href="javascript:page_comment_go('<?= ($block_num_prev) ?>');" class="prev"><?= $page_btn_prev ?></a> <?
    } else {
        ?> <a href="javascript:;" class="prev"><?= $page_btn_prev ?></a> <?
    }

    echo '<span>';

    if ($now_page % $block_num == 0) {
        $start_page = $now_page - $block_num + 1;
        $last_page = $now_page;
    } else {
        $start_page = floor($now_page / $block_num) * $block_num + 1;
        $last_page = (floor($now_page / $block_num) + 1) * $block_num;
    }

    if ($last_page > $total_page) {
        $last_page = $total_page;
    }

    for ($i = $start_page; $i < $last_page + 1; $i++) {
        if ($now_page == $i) {
            ?> <strong class="on" title="현재 페이지"><?= $i ?></strong> <?
        } else {
            ?> <a href="javascript:page_comment_go('<?= $i ?>')"><?= $i ?></a> <?
        }

        //			if ( $i < $last_page) {
        //				echo " | ";
        //			}
    }

    echo '</span>';

    if ($last_page != $total_page) {
        ?> <a href="javascript:page_comment_go('<?= $last_page + 1 ?>')" class="next"><?= $page_btn_next ?></a> <?
    } else {
        ?> <a href="javascript:;" class="next"><?= $page_btn_next ?></a> <?
    }
    echo '<a href="javascript:page_comment_go(\'' . $total_page . '\');" class="last">' . $page_btn_last . '</a>';
}

// ######################################################
function list_page_ljh_clinic($now_page, $total_page, $block_num, $treat, $div_name, $bc_id)
{
    global $url_admin;

    if ($total_page == 0)
        $total_page = 1;
    $page_btn_first = '<img src="/images/common/btn/btn_first.gif" alt="처음 페이지로" />';  // << 버튼
    $page_btn_prev = '<img src="/images/common/btn/btn_prev.gif" alt="이전 페이지로" />';  // < 버튼
    $page_btn_next = '<img src="/images/common/btn/btn_next.gif" alt="다음 페이지로" />';  // > 버튼
    $page_btn_last = '<img src="/images/common/btn/btn_last.gif" alt="마지막 페이지로" />';  // >> 버튼

    echo '<a href="javascript:show_board_clinic(\'' . $treat . "',1,'" . $div_name . "','" . $bc_id . '\');" class="first">' . $page_btn_first . '</a>';

    if ($now_page > $block_num) {
        if ($now_page % $block_num == 0) {
            $block_num_prev = $now_page - $block_num;
        } else {
            $block_num_prev = floor($now_page / $block_num) * $block_num;
        }
        ?> <a href="javascript:page_go('<?= ($block_num_prev) ?>');" class="prev"><?= $page_btn_prev ?></a> <?
    } else {
        ?> <a href="javascript:;" class="prev"><?= $page_btn_prev ?></a> <?
    }

    echo '<span>';

    if ($now_page % $block_num == 0) {
        $start_page = $now_page - $block_num + 1;
        $last_page = $now_page;
    } else {
        $start_page = floor($now_page / $block_num) * $block_num + 1;
        $last_page = (floor($now_page / $block_num) + 1) * $block_num;
    }

    if ($last_page > $total_page) {
        $last_page = $total_page;
    }

    for ($i = $start_page; $i < $last_page + 1; $i++) {
        if ($now_page == $i) {
            ?> <strong class="on" title="현재 페이지"><?= $i ?></strong> <?
        } else {
            ?> <a href="javascript:show_board_clinic('<?= $treat ?>',<?= $i ?>,'<?= $div_name ?>','<?= $bc_id ?>');"><?= $i ?></a> <?
        }

        //			if ( $i < $last_page) {
        //				echo " | ";
        //			}
    }

    echo '</span>';

    if ($last_page != $total_page) {
        ?> <a href="javascript:show_board_clinic('<?= $treat ?>',<?= $last_page + 1 ?>,'<?= $div_name ?>','<?= $bc_id ?>');" class="next"><?= $page_btn_next ?></a> <?
    } else {
        ?> <a href="javascript:;" class="next"><?= $page_btn_next ?></a> <?
    }
    echo '<a href="javascript:show_board_clinic(\'' . $treat . "'," . $total_page . ",'" . $div_name . "','" . $bc_id . '\');" class="last">' . $page_btn_last . '</a>';
}

// ##############################################################

// 페이지 이동 함수
// alert_back(메시지)
function alert_back($msg = '')
{
    global $dbcon;
    if ($dbcon)
        $dbcon->dbcon_close();
    echo '<script>';
    if (getLen($msg) > 0) {
        echo "alert('" . $msg . "');";
    }
    echo 'history.go(-1);';
    echo '</script>';
    exit;
}

function alert_page($msg = '', $url = '', $target = '')
{
    global $dbcon;
    if ($dbcon)
        $dbcon->dbcon_close();
    echo '<script language="JavaScript">';
    if (getLen($msg) > 0)
        echo 'alert("' . $msg . '");';
    if (getLen($target) > 0)
        echo $target . '.';

    // if (isset($url)) {
        echo 'location.replace("' . $url . '");';
    // }
    echo '</script>';
    exit;
}

function alert_close($msg = '', $type = '', $parent_url = '')
{
    global $dbcon;
    if ($dbcon)
        $dbcon->dbcon_close();
    echo '<script>';
    if (getLen($msg) > 0) {
        echo "alert('" . $msg . "');";
    }
    if ($type == 1) {
        echo "opener.location.href = '" . $parent_url . "'; ";
    } elseif ($type == 2) {
        echo 'opener.location.reload;';
    }
    echo 'self.close();';
    echo '</script>';
    exit;
}

function redirect($url, $time = 0)
{
    global $dbcon;
    if ($dbcon)
        $dbcon->dbcon_close();
    echo "<meta http-equiv='Refresh' content='" . $time . '; URL=' . $url . "'>";
    exit;
}

// ##############################################################

function confirm_page(string $msg, string $on_true_js = '', string $on_false_js = ''): void
{
    $msgJs = json_encode($msg, JSON_UNESCAPED_UNICODE);
    $t = rtrim($on_true_js);
    if ($t !== '' && substr($t, -1) !== ';')
        $t .= ';';
    $f = rtrim($on_false_js);
    if ($f !== '' && substr($f, -1) !== ';')
        $f .= ';';
    echo "<script> if (confirm($msgJs)) { $t } else { $f } </script>";
    exit;
}
?>
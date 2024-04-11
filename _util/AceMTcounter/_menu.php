<?php
$_MENU_G_ARRAY = Array(
    "stat_hour.php" => "시간별",
    "stat_week.php" => "요일별",
    "stat_day.php" => "일별",
    "stat_month.php" => "월별",
);

$_MENU_S_ARRAY = Array(
    "stat_display.php" => "해상도별",
    "stat_browse.php" => "브라우즈별",
    "stat_os.php" => "운영체제별",
    "stat_site.php" => "사이트별",
    "stat_url.php" => "접속경로별",
    "stat_ip.php" => "IP별",
    "stat_all.php" => "IP+시간(분)별",
);
?>

<table cellpadding="1" cellspacing="0" border="0">
<tr bgcolor="#FFFFFF">
<?php
foreach($_MENU_G_ARRAY AS $page => $title) {
    $z = explode("/", $_SERVER['PHP_SELF']);
    $now_page = array_pop($z);
    if($page == $now_page) {
        $bgcolor = 'yellow';
    }
    else {
        $bgcolor = '#FFFFFF';
    }
    echo ("<td><table cellpadding='4' cellspacing='1' border='0' bgcolor='#C0C0C0'><tr bgcolor='$bgcolor'><td><a href='$page'>$title</a></td></tr></table></td>");
}
?>
    <td width='30'></td>
<?php
foreach($_MENU_S_ARRAY AS $page => $title) {
    $z = explode("/", $_SERVER['PHP_SELF']);
    $now_page = array_pop($z);
    if($page == $now_page) {
        $bgcolor = 'yellow';
    }
    else {
        $bgcolor = '#FFFFFF';
    }
    echo ("<td><table cellpadding='4' cellspacing='1' border='0' bgcolor='#C0C0C0'><tr bgcolor='$bgcolor'><td><a href='$page'>$title</a></td></tr></table></td>");
}
?>
</tr>
</table>
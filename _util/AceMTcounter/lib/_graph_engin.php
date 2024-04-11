<?
/*
- gbc : 백그라운드 색상
- glc : 라인 색상
- gfc : 폰트 색상
- gfp : 폰트 강조 색상
- ggc : 전체 그래픽 색상
- ggp : 전체 그래픽 강조 색상
- gsfc: 검색 폰트 색상
- gsfp: 검색 폰트 강조 색상
- gsgc: 검색 그래픽 색상
- gsgp: 검색 그래픽 강조 색상
*/
$gfx = "000033";
$gfy = "000033";
$gbc = "FDFFD9";
$glc = "D9D9D9";
$gfc = "95DEFD";
$gfp = "3300FF";
$ggc = "95DEFD";
$ggp = "3300FF";
$gsfc = "FF6600";
$gsfp = "FF0000";
$gsgc= "FF6600";
$gsgp= "FF0000";

if($mode == "hour") {
    $fk = $_GET['fh'];
}
else if($mode == "day") {
    $fk = $_GET['fd']-1;
}
else if($mode == "month") {
    $fk = $_GET['fm']-1;
}
else if($mode == "week") {
    $fk = $_GET['fw'];
}

// 최대폭 설정
$p = strlen($max_val) - 1;
$pow = 1;
for($i=0; $i<$p; $i++) {
    $pow = $pow * 10;
}
//	echo ($pow)."<BR>";
$uni_val = ceil($max_val / $pow) * $pow;
//	echo ($gny)."<BR>";
//	echo ($pow)."<BR>";
$uni_val = intval($uni_val / $gny);
//	echo ($uni_val)."<BR>";
//	echo ($gny)."<BR>";

// 0으로 나누기 에러 대처 추가함
if ($uni_val < 1) $uni_val = 1;

$img = new Image($gmx, $gmy);
$img->color($gbc);

// x축
for($i=1; $i<=$gnx+1; $i++) {
    $x = $gsx + ($i - 1) * $gdx;
	$img->spColor = $img->color($glc);
	$img->line($x, $gsy, $x, $gey);

    if($i <= $gnx) {
        $img->spColor=$img->color($gfx);

        if($mode == "hour") {
            $dep =  sprintf("%02d", $i-1);
            imageString($img->img, 2, $x+$gdd+3, $gey, $dep, $img->spColor);
        }
        else if($mode == "day") {
            $dep =  sprintf("%02d", $i);
            imageString($img->img, 2, $x+$gdd+1, $gey, $dep, $img->spColor);
        }
        else if($mode == "month") {
            $dep =  sprintf("%02d", $i);
            imageString($img->img, 2, $x+$gdd+17, $gey, $dep, $img->spColor);
        }
        else if($mode == "week") {
            $dep =  $select_ww[$i-1];
            imageString($img->img, 2, $x+$gdd+25, $gey, $dep, $img->spColor);
        }
    }
}

// y축
for($i=0; $i<=$gny+1; $i++) {
	$dep1 = 100 - ($i - 1) * (100 / $gny);
    $dep2 = $uni_val * $gny - ($i - 1) * $uni_val;
    $y = $gsy + ($i - 1) * $gdy;
	$img->spColor = $img->color($glc);
	$img->line($gsx, $y, $gex, $y);
    if($i > 0) {
	    $img->spColor=$img->color($gfy);
	    imageString($img->img, 2, $gsx - 25, $y - 5.5, $dep1 . "%", $img->spColor);
	    imageString($img->img, 2, $gsx + $gnx * $gdx + 5, $y - 5.5, number_format($dep2), $img->spColor);
    }
}

// 전체검색에 대한 그래픽
for($i=1; $i<=$gnx; $i++) {
	$x = $gsx + ($i - 1) * $gdx;
    $y1 = $gsy + $gny * $gdy;
	$y2 = $y1 - $all_hit[$i-1] / ($uni_val * $gny) * ($gey - $gsy);

    // 현재시간의 방문자수를 보여줌
    if(intval($fk) == $i-1) {
        $c_hit = number_format($all_hit[$i-1]);
        $c_x = $x + $gdd;
        $c_y = $y2 - 13;
        $img->spColor=$img->color($ggp);
    }
    else {
        $img->spColor=$img->color($ggc);
    }

    for($j=$gdd; $j<=$gdx-$gdd; $j++) {
        $img->line($x+$j, $y1, $x+$j, $y2);
    }

    // 현재시간의 방문자수를 보여줌
    if(intval($fk) == $i-1) {
        $tc_hit = number_format($all_hit[$i-1]);
        $tc_x = $x + $gdd;
        $tc_y = $y2 - 13;
    }
}

// 검색된날에 대한 그래픽
for($i=1; $i<=$gnx; $i++) {
	$x = $gsx + ($i - 1) * $gdx;
    $y1 = $gsy + $gny * $gdy;
	$y2 = $y1 - $search_hit[$i-1] / ($uni_val * $gny) * ($gey - $gsy);

    // 현재시간의 방문자수를 보여줌
    if(intval($fk) == $i-1) {
        $c_hit = number_format($search_hit[$i-1]);
        $c_x = $x + $gdd;
        $c_y = $y2 - 13;
        $img->spColor=$img->color($gsgp);
    }
    else {
        $img->spColor=$img->color($gsgc);
    }

    for($j=$gdd; $j<=$gdx-$gdd; $j++) {
        $img->line($x+$j+$gsdd, $y1, $x+$j+$gsdd, $y2);
    }
}

// 전체날의 수치
if($tc_hit > 0) {
    $img->spColor=$img->color($gfp);
    imageString($img->img, 3, $tc_x, $tc_y, $tc_hit, $img->spColor);
}

// 검색된날의 수치
if($mode == "day") {
    if($c_hit > 0) {
        $img->spColor=$img->color($gsfp);
        imageString($img->img, 3, $c_x, $c_y, $c_hit, $img->spColor);
    }
}
else {
    for($i=1; $i<=$gnx; $i++) {
        $x = $gsx + ($i - 1) * $gdx;
        $y1 = $gsy + $gny * $gdy;
        $y2 = $y1 - $search_hit[$i-1] / ($uni_val * $gny) * ($gey - $gsy);

        if($search_hit[$i-1] > 0) {
            $c_hit = number_format($search_hit[$i-1]);
            $c_x = $x + $gdd;
            $c_y = $y2 - 13;
            if(intval($fk) == $i-1) {
                $img->spColor=$img->color($gsfp);
                imageString($img->img, 3, $c_x+$gsdd, $c_y, $c_hit, $img->spColor);
            }
            else {
                $img->spColor=$img->color($gsfc);
                imageString($img->img, 2, $c_x+$gsdd, $c_y, $c_hit, $img->spColor);
            }
        }
    }
}

$img->draw();
?>
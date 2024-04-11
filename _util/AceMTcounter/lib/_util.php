<?
function selectbox($array, $val="", $type=false, $style="style='background:#FFF0F0'") {
    if(is_array($array)) {
        foreach($array as $idx => $text) {
            if($type) { $refkey = $idx; }
            else { $refkey = $text; }
            $option .= "<option value='$refkey' $style";

			settype($val, string);
			settype($refkey, string);
            if($refkey === $val) $option .= " selected";
            $option .= ">$text</option>\n";

        }
        return $option;
    }
    else return "";
}

function selected($c1, $c2) {
    if($c1 == $c2 ) return "selected";
}

function url_clear($url) {
    if($url) {
        $ary = explode("?", $url);
        $qry = explode("&", $ary[1]);
        for($i=0, $cnt=count($qry); $i<$cnt; $i++) {
            $tmp = explode("=", $qry[$i]);
			$imp[$tmp[0]] = $tmp[1];
        }

		foreach($imp as $fld => $var) {
			if($fld && $var) {
				$imp2[$fld] = "$fld={$var}";
			}
        }

        if(count($imp2)) {
            $query = implode("&", $imp2);
            return $ary[0] . "?" . $query;
        }
        else {
			return $ary[0];
		}
    }
}

function cut_string($msg, $len, $tail="...") {
    if($len >= strlen($msg)) {
        return $msg;
    }
    $klen = $len - 1;
    while(ord($msg[$klen]) & 0x80) {
        $klen--;
    }
    return substr($msg, 0, $len - (($len + $klen + 1) % 2)) . $tail;
}

function k_wordwrap($str, $width = 75)
{
     $break = "n";

     switch(func_num_args())
     {
        case 4 : $cut    = func_get_arg(3);
        case 3 : $break    = func_get_arg(2);
        case 2 : $width    = func_get_arg(1);
     }

     $str_len = strlen($str);

     for($start = 0; $start < $str_len; )
     {
        $width_conv = $width;
        $end_chr = ord($str[$start + $width - 1]);

        if(!$cut && $end_chr != 32) $width_conv += strpos(substr($str, $start + $width), " ") + 1;
        elseif($cut && $end_chr > 127) // 한글일 경우 1Byte 덜 자름
        {
            for($end = $start + $width - 1, $h = 0; $end >= $start; $end--)
                if(ord($str[$end]) > 127) $h++;
                else break;

            if($h%2!=0) $width_conv = $width - 1;
        }

        $str_line = substr($str, $start, $width_conv);
        $start += $width_conv;

        if($start < $str_len) $str_line .= $break;
        $str_conv .= $str_line;
     }

     return $str_conv;
}

?>
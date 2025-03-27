<?


	#######################################################
	// 카테고리 가져오기
	// getCategory("타입", "카테고리 gubun 값", "객체명", "선택될값")
	#######################################################
	function getCategory($type, $gubun, $ObjName, $SelValue = "", $ObjClass="") {
		//echo $SelValue."<BR>";
		global $dbcon;

		if ($type == "select") {
			$TempSQL = "
				select *
				from tbl_category
				where
					gubun = '".$gubun."'
				order by sort asc
			";
			$Tempresult = mysql_query($TempSQL, $dbcon);
			?>
				<select name="<?=$ObjName?>">
					<option value="">선택하세요</option>
					<?
						while ($Temprow = mysql_fetch_array($Tempresult)) {
							$Temp_idx = $Temprow[idx];
							$Temp_name = $Temprow[name];

							$Selected = "";
							if ($Temp_idx == $SelValue ) {$Selected = "selected";}
					?>
					<option value="<?=$Temp_idx?>" <?=$Selected?>><?=$Temp_name?></option>
					<?
						}
					?>
				</select>
			<?
		}
		elseif ( $type == "radio" ) {
			$TempSQL = "
				select *
				from tbl_category
				where
					gubun = '".$gubun."'
				order by sort asc
			";
			$Tempresult = mysql_query($TempSQL, $dbcon);

			$num = 0;
			while ($Temprow = mysql_fetch_array($Tempresult)) {
				$Temp_idx = $Temprow[idx];
				$Temp_name = $Temprow[name];

				$Selected = "";

				if ( strlen($SelValue) == 0 && $num == 0) {
					$Selected = "checked";
				}
				else {
					if ($Temp_idx == $SelValue ) {
						$Selected = "checked";
					}
				}
			?>
			<input type="radio" name="<?=$ObjName?>" value="<?=$Temp_idx?>" <?=$ObjClass?> <?=$Selected?>><?=$Temp_name?>
			<?
				$num ++;
			}
		}
	}

	#######################################################
	// 담담자 (회원레벨 gubun 이상) 선택 폼 출력
	// getCategory("타입", "카테고리 gubun 값", "객체명", "선택될값")
	#######################################################
	function getCharge($type, $gubun, $ObjName, $SelValue = "") {
		//echo $SelValue."<BR>";
		global $dbcon;

		if ($type == "select") {
			$TempSQL = "
				select mem_num, mem_name
				from tbl_member
				where
					mem_level >= '".$gubun."'
				order by mem_level asc, mem_num asc
			";
			$Tempresult = mysql_query($TempSQL, $dbcon);
			?>
				<select name="<?=$ObjName?>">
					<option value="">선택하세요</option>
					<?
						while ($Temprow = mysql_fetch_array($Tempresult)) {
							$Temp_idx = $Temprow[mem_num];
							$Temp_name = $Temprow[mem_name];

							$Selected = "";
							if ($Temp_idx == $SelValue ) {$Selected = "selected";}
					?>
					<option value="<?=$Temp_idx?>" <?=$Selected?>><?=$Temp_name?></option>
					<?
						}
					?>
				</select>
			<?
		}
	} // end function
	#######################################################

	#######################################################
	// 이메일 수신/수신안함 선택 폼 출력
	// getReceipt(출력타입, 객체명, 선택값)
	#######################################################
	function getReceipt($type, $ObjName, $SelValue = "") {
		if ($type == "radio" ) {
		?>
			<input type="radio" name="<?=$ObjName?>" value="Y" <? if ($SelValue == "Y" || $SelValue == "") {echo "checked";} ?>> 수신함
			<input type="radio" name="<?=$ObjName?>" value="N" <? if ($SelValue == "N" ) {echo "checked";} ?>> 수신안함
		<?
		}
		elseif ( $type == "text" ) {
			if ($SelValue == "Y") {echo "수신함";}
			elseif ($SelValue == "N") {echo "수신안함";}
			else {echo "미설정";}
		}
	}
	#######################################################

	#######################################################
	// 이메일 주소 입력 폼 출력
	// getEmailForm(객체명, 객체명2, 선택값1, 선택값2)
	#######################################################
	function getEmailForm($ObjName1, $ObjName2, $SelValue1 = "", $SelValue2 = "", $ObjClass="") {
		global $OnlyEng;
		$ArrTemp = Array("chollian.net", "dreamwiz.com", "empal.com", "freechal.com", "hananet.net", "hanmail.net", "hanmir.com", "hotmail.com", "hitel.net", "korea.com", "kornet.net", "lycos.co.kr", "naver.com", "nate.com", "paran.com", "sayclub.com", "yahoo.co.kr");
?>

		<table border="0" cellpadding="0" cellspacing="0" style="border:0px; width:0px;height:0px;width:100%;">
			<tr>
				<td style="border:0px;width:102px;">
					<input type="text" name="<?=$ObjName1?>" value="<?=$SelValue1?>" class="<?=$ObjClass?>" style="width:80px;" <?=$OnlyEng?> maxlength="50"> @&nbsp;
				</td>
				<td align="left" style="border:0px;">
					<div id = "mem_email" style="text-align:left;">
					<select name="<?=$ObjName2?>" onchange="setChangeEmail(this.value)">
						<option value="">선택하세요.</option>
						<?
							$Tempflag = false;
							if ($SelValue2 == "" ) {
								$Tempflag = true;
							}

							for ($i = 0 ; $i < sizeof($ArrTemp) ; $i++) {
								$selected = "";
								if ($ArrTemp[$i] == $SelValue2) {
									$selected = "selected";
									$Tempflag = true;
								}
							?>
								<option value="<?=$ArrTemp[$i]?>" <?=$selected?>><?=$ArrTemp[$i]?></option>
							<?
							}
						?>
						<option value="etc">직접입력</option>
					</select>
					</div>
				</td>
			</tr>
		</table>
		<script>
			function setChangeEmail(val) {
				if (val == "etc") {
					Obj = document.getElementById("mem_email");
					Obj.innerHTML = "<input type='text' name='<?=$ObjName2?>' value='<?=$SelValue2?>' class='<?=$ObjClass?>' style='ime-mode:disabled;' maxlength='50'>";
				}
			}
		</script>
<?
		if ($Tempflag == false) {
			echo "<script>setChangeEmail('etc');</script>";
		}
	} // end function
	#######################################################


	#######################################################
	// 전화번호 선택 폼 출력
	#######################################################
	function getHP($ObjName, $Tempidx1, $Tempidx2, $Tempidx3, $ObjClass='') {
		global $OnlyNumber;
		global $ArrHP;
		?>
			<select name="<?=$ObjName?>1">
				<? for ( $i = 0 ; $i < sizeof($ArrHP) ; $i++) { ?>
				<option value="<?=$ArrHP[$i]?>" <? if ($Tempidx1 == $ArrHP[$i] ) { echo "selected";} ?>><?=$ArrHP[$i]?></option>
				<? } ?>
			</select> -
			<input type="text" name="<?=$ObjName?>2" value="<?=$Tempidx2?>" class="<?=$ObjClass?>" style="width:50px;" maxlength="4" <?=$OnlyNumber ?> onkeyup="NextFocus(this.value,this.form.<?=$ObjName?>3, 4);"> -
			<input type="text" name="<?=$ObjName?>3" value="<?=$Tempidx3?>" class="<?=$ObjClass?>" style="width:50px;" maxlength="4" <?=$OnlyNumber ?>>
		<?
	}

	function getHP2($ObjName1, $ObjName2, $ObjName3, $Tempidx1, $Tempidx2, $Tempidx3, $ObjClass='') {
		global $OnlyNumber;
		global $ArrHP;
		?>
			<select name="<?=$ObjName1?>">
				<? for ( $i = 0 ; $i < sizeof($ArrHP) ; $i++) { ?>
				<option value="<?=$ArrHP[$i]?>" <? if ($Tempidx1 == $ArrHP[$i] ) { echo "selected";} ?>><?=$ArrHP[$i]?></option>
				<? } ?>
			</select> -
			<input type="text" name="<?=$ObjName2?>" value="<?=$Tempidx2?>" class="<?=$ObjClass?>" style="width:50px;" maxlength="4" <?=$OnlyNumber ?> onkeyup="NextFocus(this.value,this.form.<?=$ObjName3?>, 4);"> -
			<input type="text" name="<?=$ObjName3?>" value="<?=$Tempidx3?>" class="<?=$ObjClass?>" style="width:50px;" maxlength="4" <?=$OnlyNumber ?>>
		<?
	}

	function getTel($ObjName, $Tempidx1, $Tempidx2, $Tempidx3, $ObjClass='') {
		global $OnlyNumber;
		global $ArrTEL;
		?>
			<select name="<?=$ObjName?>1">
				<? for ( $i = 0 ; $i < sizeof($ArrTEL) ; $i++) { ?>
				<option value="<?=$ArrTEL[$i]?>" <? if ($Tempidx1 == $ArrTEL[$i] ) { echo "selected";} ?>><?=$ArrTEL[$i]?></option>
				<? } ?>
			</select> -
			<input type="text" name="<?=$ObjName?>2" value="<?=$Tempidx2?>" class="<?=$ObjClass?>" style="width:50px;" maxlength="4" <?=$OnlyNumber ?> onkeyup="NextFocus(this.value,this.form.<?=$ObjName?>3, 4);"> -
			<input type="text" name="<?=$ObjName?>3" value="<?=$Tempidx3?>" class="<?=$ObjClass?>" style="width:50px;" maxlength="4" <?=$OnlyNumber ?>>
		<?
	}
	function getTel2($ObjName1, $ObjName2, $ObjName3, $Tempidx1, $Tempidx2, $Tempidx3, $ObjClass='') {
		global $OnlyNumber;
		global $ArrTEL;
		?>
			<select name="<?=$ObjName1?>">
				<? for ( $i = 0 ; $i < sizeof($ArrTEL) ; $i++) { ?>
				<option value="<?=$ArrTEL[$i]?>" <? if ($Tempidx == $ArrTEL[$i] ) { echo "selected";} ?>><?=$ArrTEL[$i]?></option>
				<? } ?>
			</select> -
			<input type="text" name="<?=$ObjName2?>" value="<?=$Tempidx2?>" class="<?=$ObjClass?>" style="width:50px;" maxlength="4" <?=$OnlyNumber ?> onkeyup="NextFocus(this.value,this.form.<?=$ObjName3?>, 4);"> -
			<input type="text" name="<?=$ObjName3?>" value="<?=$Tempidx3?>" class="<?=$ObjClass?>" style="width:50px;" maxlength="4" <?=$OnlyNumber ?>>
		<?
	}
	#######################################################



	//mixed array_search ( mixed $needle , array $haystack [, bool $strict ] )

	#######################################################
	// 체크박스나 라디오박스의 내용들을 출력하거나 뿌려준다.
	#######################################################

	$Arr_site_type = Array(
		1 => "기업홍보형(IR 사이트, 일반 웹사이트)"
		, 2 => "상품판매목적(쇼핑몰, 결제서비스)"
		, 3 => "E-비즈니스 목적(커뮤니티, 커넥션 채널)"
		, 4 => "기타"
	);
	$Arr_site_menu = Array(
		1 => "변동없다"
		, 2 => "매월 1회 정도 변동한다"
		, 3 => "분기별 1회정도 변동된다"
		, 4 => "1년에 1회정도 변동된다"
	);
	$Arr_visitor_sex = Array(
		1 => "모두"
		, 2 => "남성"
		, 3 => "여성"
	);
	$Arr_visitor_action = Array(
		1 => "모두"
		, 2 => "B to B"
		, 3 => "B to C"
		, 4 => "C to C"
	);
	$Arr_importance = Array(
		1 => "파격적인 디자인"
		, 2 => "트렌드에 맞는 디자인"
		, 3 => "심플한 디자인"
		, 4 => "신뢰도 높은 디자인"
	);
	$Arr_color = Array(
		1 => "검정 계열 색상"
		, 2 => "붉은색 계열 색상"
		, 3 => "흰색 계열 색상"
		, 4 => "파랑색 계열 색상"
		, 5 => "녹색 계열 색상"
		, 6 => "기타"
	);
	$Arr_resolution = Array(
		1 => "1280*1024"
		, 2 => "1024*768"
		, 3 => "800*600"
		, 4 => "기타"
	);


	function getCheckboxArray($type, $ArrayName, $ObjName, $Tempidx, $cols = 0) {
		if ($type == "checkbox") {

			$Temp_arr = explode(", ",$Tempidx);

			for ($Tempi = 0; $Tempi < sizeof($ArrayName); $Tempi++) {

				$TempChecked = "";
				if (in_array(($Tempi+1), $Temp_arr) != false) {
					$TempChecked = "checked";
				}
				echo "<input type='checkbox' name='".$ObjName."[]' value='".($Tempi+1)."' ".$TempChecked.">".$ArrayName[$Tempi+1];

				if ( ($cols > 0) && ( ($Tempi+1) % $cols == 0 ) && ( $Tempi < sizeof($ArrayName) - 1) ) { echo "<BR>";}
			}

		}
	}

	function getRaioArray($type, $ArrayName, $ObjName, $Tempidx, $cols = 0) {
		if ($type == "checkbox") {

			$Temp_arr = explode(", ",$Tempidx);

			for ($Tempi = 0; $Tempi < sizeof($ArrayName); $Tempi++) {

				$TempChecked = "";
				if (in_array(($Tempi+1), $Temp_arr) != false) {
					$TempChecked = "checked";
				}
				echo "<input type='radio' name='".$ObjName."' value='".($Tempi+1)."' ".$TempChecked.">".$ArrayName[$Tempi+1];

				if ( ($cols > 0) && ( ($Tempi+1) % $cols == 0 ) && ( $Tempi < sizeof($ArrayName) - 1) ) { echo "<BR>";}
			}

		}
	}


	function setCheckboxArray($type, $ArrName) {
		if ($type == "checkbox") {
			for ( $Tempi = 0 ; $Tempi < sizeof($ArrName); $Tempi++) {
				$Temp_Val .= $ArrName[$Tempi];
				if ( $Tempi < sizeof($ArrName) -1 ) {
					$Temp_Val .= ", ";
				}
			}
			return $Temp_Val;
		}
	}

	function ftp_root($DROOT) {
		$droot_array = explode ("/", $DROOT);
		return $droot_array[count($droot_array)-1];
	}





	/*
	======================================
	xml 에서는 아래 5가지의 특수문자를 사전정의하고 있다.
	======================================
	Name			| Character			| Code
	======================================
	quot				| "							| &quot;
	--------------------------------------------------
	amp				| &						| &amp;
	--------------------------------------------------
	apos				| '							| &apos;
	--------------------------------------------------
	lt					| <						| &lt;
	--------------------------------------------------
	gt					| >						| &gt;
	--------------------------------------------------
	======================================
	*/
	function XmlStr($val) {
		$val = str_replace('"', "&quot;", $val);
		$val = str_replace('&', "&amp;", $val);
		$val = str_replace("'", "&apos;", $val);
		$val = str_replace('<', "&lt;", $val);
		$val = str_replace('>', "&gt;", $val);
		return $val;
	}


	###############################################################
	// request replace string
	/*
		설명		: parameter 로 넘어온 값들중 위험한 놈들 걸러내기
		작성자	: 김승태
		작성일	: 2008.12.09
		사용법	:
			$Value = REQSTR($_POST["Value"], "null 일 때 치환값");

		SQL Injection 공격방지를 위해 모든 POST 와 GET 값에 적용한다.
	*/
	function REQSTR($val, $ChangeValue = "") {

		if ( !$val || trim($val) == ""  ) {
			$val = $ChangeValue;
			return $val;
		}

		if (!get_magic_quotes_gpc()) {
			$val = addslashes($val);
		}

		$val = htmlspecialchars($val, ENT_QUOTES, "UTF-8");
		
		return $val;

//		$search = array("--", "#", ";", '/*', '*/', );
//		$replace = array("\--","\#","\;", '', '');
//		$Value = str_replace($search, $replace, $Value);


//		$Value = trim($Value);
////			if (get_magic_quotes_gpc() == false) {
////				$Value = addslashes($Value);
////			}
//			$Value = addslashes($Value);		// 이놈은 특수문자에 "\" 를 자동으로 생성한다...귀찮긴 해도 쿼리 실행시에 필요함...
//			//echo "Value : ".$Value."<BR>";
//
//			//$Value = nl2br($Value);			// 두줄씩 생겨서 못써먹겠다...
//			$Value = str_replace("\n","<BR>",$Value);
////			$Value = eregi_replace("  ","&nbsp;&nbsp;",$Value);
////			$Value = eregi_replace("\t","&nbsp;&nbsp;&nbsp;&nbsp;",$Value);
//			$Value = eregi_replace('<xmp>', "&lt;xmp&gt;", $Value);
//			$Value = eregi_replace('<base', "&lt;base", $Value);
//			//$Value = eregi_replace('<script', "&lt;script", $Value);
//			//$Value = eregi_replace('<meta', "&lt;meta", $Value);
//			//$Value = eregi_replace('</script>', "&lt;/script&gt;", $Value);
//
//			$Value = str_replace('#', '', $Value);
//			$Value = str_replace('/*', '', $Value);
//			$Value = str_replace('*/', '', $Value);
//			$Value = str_replace("'", '&#39;', $Value);
//			$Value = str_replace(';', "", $Value);
//			$Value = str_replace("--", "", $Value);
//			//$Value = str_replace('\.\', "", $Value);
//			//$Value = str_replace('""', '', $Value);
//			$Value = eregi_replace('<textarea', "", $Value);
//			$Value = eregi_replace('</textarea', "", $Value);
//			$Value = eregi_replace("<script", "", $Value);
//			$Value = eregi_replace("</script", "", $Value);
//			$Value = eregi_replace("select", "", $Value);
//			$Value = eregi_replace("update", "", $Value);
//			$Value = eregi_replace("delete", "", $Value);
//			$Value = eregi_replace("union", "", $Value);
//			$Value = eregi_replace("applet", "", $Value);
//			$Value = eregi_replace("layer", "", $Value);
//			//$Value = eregi_replace("body", "", $Value);
//			$Value = eregi_replace("ilayer", "", $Value);
//			//$Value = eregi_replace("embed", "", $Value);
//			$Value = eregi_replace("meta", "", $Value);
//			$Value = eregi_replace("frameset", "", $Value);
//			$Value = eregi_replace("iframe", "", $Value);
//			$Value = eregi_replace("object", "", $Value);
//			//$Value = str_replace("<style", "", $Value);
//			//$Value = str_replace("<div", "", $Value);
//			$Value = eregi_replace("<base", "<abase", $Value);		// 해킹 추가
//			$Value = eregi_replace("DECLARE", "", $Value);
//			$Value = eregi_replace("NVARCHAR", "", $Value);
//			$Value = eregi_replace("EXEC", "", $Value);
//
		// return $Value;
	}

	// REQSTR 변환한 데이타를 데이타를 출력할때 사용
	function RESSTR($val, $type = "") {
		if ( !get_magic_quotes_gpc() ) {
			$val = stripslashes( $val ) ;
		}

		// 에디터에 들어가는 내용
		if ( $type == "editor") {
		}

		else {
			$val = htmlspecialchars_decode($val, ENT_QUOTES);
		}
		//echo htmlspecialchars_decode($str, ENT_NOQUOTES);
		return $val;
	}


	// REQSTR 변환한 데이타중 TEXTAREA에서 입력한 데이타를 html 출력할때 사용
	function RESSTRTEXT($val) {
		if ( !get_magic_quotes_gpc() ) {
			$val = stripslashes( $val ) ;
		}

		//echo "".$val."<BR>";
		//echo "<pre>".$val."</pre><BR>";

        // 에디터에서 한줄씩 들어가서 주석처리해놨음 !  수정요망 ㅋㅋ
		$val = nl2br($val);
		//echo "".$val."<BR>";
		//echo "<pre>".$val."</pre><BR>";


		return $val;
	}

	// 위에놈 다시 복구하기
	function REQSTR2($Value, $ChangeValue = "") {
		$Value = trim($Value);
		if ( $Value == "" || $Value == null ) {
			$Value = $ChangeValue;
		}
		else {
//			if (get_magic_quotes_gpc() == false) {
//				$Value = stripslashes($Value);
//			}
//			echo 'get_magic_quotes_gpc '.get_magic_quotes_gpc()."<BR>";
			$Value = stripslashes($Value);
			$Value = str_replace("<BR>","\n",$Value);
			$Value = str_replace("<BR/>","\n",$Value);
			$Value = str_replace("<br>","\n",$Value);
			$Value = str_replace("<br/>","\n",$Value);
			$Value = str_replace("<BR />","\n",$Value);
			$Value = str_replace("<br />","\n",$Value);

//			$Value = eregi_replace("&nbsp;&nbsp;&nbsp;&nbsp;","\t",$Value);
//			$Value = eregi_replace("&nbsp;"," ",$Value);
			$Value = preg_replace("/&lt;xmp&gt;/i", '<xmp>', $Value);
			$Value = preg_replace("/&lt;base/i", '<base', $Value);
			$Value = str_replace("'", '&#39;', $Value);

		}
		return $Value;
	}

	###############################################################


	###############################################################
	/*
		설명		: parameter 로 넘어온 값들중 위험한 놈들 걸러내기
		작성자	: 김승태
		작성일	: 2008.12.09
		사용법	:
			$Value = REQSTR($_POST["Value"], "null 일 때 치환값");
	*/
	function isnull($Value, $Value2 = "") {
		$Value = REQSTR($Value, "");
		if ( $Value2 == "" || $Value2 == "0" ) {
			if ( strlen($Value) == 0 ) {
				alert_back($GLOBALS[msg_error_null]);
			}
		}
		else {
			if ( $Value != $Value2 ) {
				alert_back($GLOBALS[msg_error_null2]);
			}
		}
	}

	function islen($Value) {
		$Value = trim($Value);
		$Value = str_replace(" ", "", $Value);
		$Value = mb_strlen($Value);
		return $Value;
	}
	###############################################################


	function getLen($STRING) {
		$STRING = trim($STRING);
		$STRING = str_replace(" ","",$STRING);
		$LEN = strlen($STRING);
		return $LEN;
	}

	function MSG_ERROR($MSG) {
		//global $dbcon;
		//if ($dbcon) $dbcon -> dbcon_close();
		echo $MSG;
	}

	#######################################################
	/************* 메일주소 유효성 체크 함수   ****************/
	// $strMail : 메일 주소.    유효한 메일 주소면 true, 아니면 false 리턴
	#######################################################
	function EmailCheck($strMail)
	{
		$strPattern = "^[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$";
		$blnResult = ereg($strPattern, $strMail);
		return $blnResult;
	}


	#######################################################
	/************* 글자 자르기 함수   ****************/
	// UTF-8 에서 사용하는 글자 자르기~~~~
	// 출처 http://hacker.golbin.net/wp/archives/423
	#######################################################
	function getStrCut( $str, $size, $last_str = "")
	{
		$substr = substr( $str, 0, $size * 2 );
		//$multi_size = preg_match_all( '/[\x80-\xff]/', $substr, $multi_chars );
		$multi_size = preg_match_all( '/[\\x80-\\xff]/', $substr, $multi_chars );
		if ( $multi_size > 0 ) {
			$size = $size + intval( $multi_size / 3 ) - 1;
		}

		if ( getLen( $str ) > $size ) {
			$str = substr( $str, 0, $size );
			//$str = preg_replace( '/(([\x80-\xff]{3})*?)([\x80-\xff]{0,2})$/', '$1', $str );
			$str = preg_replace( '/(([\\x80-\\xff]{3})*?)([\\x80-\\xff]{0,2})$/', '$1', $str );
			if ( $last_str )  $str .= $last_str;
		}

		return $str;

	}

	#######################################################
	/************* 태그제거함수   ****************/
	// getStrTag( "태그 제거할 문자열", "허용할 태그" )
	// (PHP 4, PHP 5)
	#######################################################
	function getStrTag ($str, $val) {
		$str = strip_tags($str, $val);
		return $str;
	}







	//################################################################################
	// 가격 자리수 찾아서 보여주기
	//################################################################################
	function make_price_format($str,$check_format="0"){
		global $cf_language_method;

		if($cf_language_method == "english") {
			if($check_format == "1") {
				$str = number_format($str,2);
			}
			else {
				$str = sprintf("%.2f",$str);
			}
		}
		else {
			if($check_format == "1") {
				$str = number_format($str);
			}
			else {
				$str = intval($str);
			}
		}

		return $str;
	}


/* 문자열 변환
 // $str : 문자열(html, text)
 // 반환 : trim() + html 형태 + <br> 처리
 */
   function rtnShowHtmlBr($str)
 {
  $str = trim($str);
  $str = stripslashes($str);
  $str = str_replace("\n","<br>", $str);

 return $str;
 }


// 날짜 시간계산 차이
 function datetimediff($rtime, $ctime = null, $option = null){
      if ($ctime) $cur_time = strtotime($ctime);
      else $cur_time = time();
      $ref_time = strtotime($rtime);

      $cur_date = floor($cur_time / 86400);
      $ref_date = floor($ref_time / 86400);

      $datetimediff = $cur_time - $ref_time;
      $datedist = $cur_date - $ref_date;
      $datediff = floor($datetimediff / 86400);
      $weekdiff = floor($datediff / 7);
      $timediff = $datetimediff % 86400;

      $hour = floor($timediff / 3600);
      $min = floor($timediff % 3600 / 60);
      $sec = floor($timediff % 3600 % 60);

      $result = "";
      if ($datedist>34) {
            $result = date("Y년 n월 j일", $ref_time);
      } else if ($weekdiff>0) {
            $result = $weekdiff . "주 전";
      } else {
            if ($datediff>0) {
                  $result = $datedist;
            } else if ($timediff<=0) {
                  $result = "1초 전";
            } else {
                  if ($hour) $result = $hour . "시간";
                  else if ($min) $result = $min . "분";
                  else $result = $sec . "초";
                  if ($result) $result .= " 전";
            }
      }
      if ($option=='ALL') {
            $result = "";
            if ($datediff) $result .= ($result?" ":"") . $datediff."일";
            if ($hour) $result .= ($result?" ":"") . $hour."시간";
            if ($min) $result .= ($result?" ":"") . $min ."분";
            if ($sec) $result .= ($result?" ":"") . $sec . "초";
            $result .= " 전";
      }
      return $result;
}



//XSS (cross site scripting) filter function
function RemoveXSS($val) {
   // remove all non-printable characters. CR(0a) and LF(0b) and TAB(9) are allowed
   // this prevents some character re-spacing such as <java\0script>
   // note that you have to handle splits with \n, \r, and \t later since they *are*
   // allowed in some inputs
   $val = preg_replace('/([\x00-\x08][\x0b-\x0c][\x0e-\x20])/', '', $val);

   // straight replacements, the user should never need these since they're normal characters
   // this prevents like <IMG SRC=&#X40&#X61&#X76&#X61&#X73&#X63&#X72&#X69&#X70&#X74&
   // #X3A&#X61&#X6C&#X65&#X72&#X74&#X28&#X27&#X58&#X53&#X53&#X27&#X29>
   $search = 'abcdefghijklmnopqrstuvwxyz';
   $search .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
   $search .= '1234567890!@#$%^&*()';
   $search .= '~`";:?+/={}[]-_|\'\\';
   for ($i = 0; $i < strlen($search); $i++) {
   // ;? matches the ;, which is optional
   // 0{0,7} matches any padded zeros, which are optional and go up to 8 chars

   // &#x0040 @ search for the hex values
      $val = preg_replace('/(&#[x|X]0{0,8}'.dechex(ord($search[$i])).';?)/i', $search[$i], $val);
      // with a ;

      // &#00064 @ 0{0,7} matches '0' zero to seven times
      $val = preg_replace('/(&#0{0,8}'.ord($search[$i]).';?)/', $search[$i], $val); // with a ;
   }

   // now the only remaining whitespace attacks are \t, \n, and \r
   $ra1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style',
'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');
   $ra2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');
   $ra = array_merge($ra1, $ra2);

   $found = true; // keep replacing as long as the previous round replaced something
   while ($found == true) {
      $val_before = $val;
      for ($i = 0; $i < sizeof($ra); $i++) {
         $pattern = '/';
         for ($j = 0; $j < strlen($ra[$i]); $j++) {
            if ($j > 0) {
               $pattern .= '(';
               $pattern .= '(&#[x|X]0{0,8}([9][a][b]);?)?';
               $pattern .= '|(&#0{0,8}([9][10][13]);?)?';
               $pattern .= ')?';
            }
            $pattern .= $ra[$i][$j];
         }
         $pattern .= '/i';
         $replacement = substr($ra[$i], 0, 2).'<x>'.substr($ra[$i], 2); // add in <> to nerf the tag
         $val = preg_replace($pattern, $replacement, $val); // filter out the hex tags
         if ($val_before == $val) {
            // no replacements were made, so exit the loop
            $found = false;
         }
      }
   }

	$val = str_replace("<iframe","",$val);
	$val = str_replace("<script","",$val);
	$val = str_replace("1=1","",$val);
	$val = str_replace("--","",$val);
	$val = str_replace("comd_list","",$val);
	$val = str_replace("jiaozhu","",$val);
	$val = str_replace("T_Jiaozhu","",$val);
	$val = str_replace("D99_Tmp","",$val);
	$val = str_replace("insert","",$val);
	$val = str_replace("update","",$val);
	$val = str_replace("delete","",$val);
	$val = str_replace("drop ","",$val);
	$val = str_replace("alter ","",$val);
	$val = str_replace("create ","",$val);
	$val = str_replace("inner join","",$val);
	$val = str_replace("from ","",$val);
	$val = str_replace("where ","",$val);
	$val = str_replace("union ","",$val);
	$val = str_replace("group by","",$val);
	$val = str_replace("having ","",$val);
	$val = str_replace("shutdown","",$val);
	$val = str_replace("kill ","",$val);
	$val = str_replace("declare","",$val);
	$val = str_replace("openrowset","",$val);
	$val = str_replace("opendatasource","",$val);
	$val = str_replace("pwdencrypt","",$val);
	$val = str_replace("msdasql","",$val);
	$val = str_replace("sqloledb","",$val);
	$val = str_replace("char(","",$val);
	$val = str_replace("syslogins","",$val);
	$val = str_replace("sysxlogins","",$val);
	$val = str_replace("sysdatabases","",$val);
	$val = str_replace("sysobjects","",$val);
	$val = str_replace("syscomments","",$val);
	$val = str_replace("raiserror","",$val);
	$val = str_replace("exec","",$val);
	$val = str_replace("xp_","",$val);
	$val = str_replace("sp_","",$val);
	$val = str_replace("xp_cmdshell","",$val);
	$val = str_replace("xp_reg","",$val);
	$val = str_replace("xp_servicecontrol","",$val);
	$val = str_replace("xp_setsqlsecurity","",$val);
	$val = str_replace("xp_readerrorlog","",$val);
	$val = str_replace("xp_controlqueueservice","",$val);
	$val = str_replace("xp_createprivatequeue","",$val);
	$val = str_replace("xp_decodequeuecommand","",$val);
	$val = str_replace("xp_deleteprivatequeue","",$val);
	$val = str_replace("xp_deletequeue","",$val);
	$val = str_replace("xp_displayqueuemesgs","",$val);
	$val = str_replace("xp_dsinfo","",$val);
	$val = str_replace("xp_mergelineages","",$val);
	$val = str_replace("xp_readpkfromqueue","",$val);
	$val = str_replace("xp_readpkfromvarbin","",$val);
	$val = str_replace("xp_repl_encrypt","",$val);
	$val = str_replace("xp_resetqueue","",$val);
	$val = str_replace("xp_sqlinventory","",$val);
	$val = str_replace("xp_unpackcab","",$val);
	$val = str_replace("xp_sprintf","",$val);
	$val = str_replace("xp_displayparamstmt","",$val);
	$val = str_replace("xp_enumresult","",$val);
	$val = str_replace("xp_showcolv","",$val);
	$val = str_replace("xp_updatecolvbm","",$val);
	$val = str_replace("xp_execresultset","",$val);
	$val = str_replace("xp_printstatements","",$val);
	$val = str_replace("xp_peekqueue","",$val);
	$val = str_replace("xp_proxiedmetadata","",$val);
	$val = str_replace("xp_displayparamstmt","",$val);
	$val = str_replace("xp_availablemedia","",$val);
	$val = str_replace("xp_enumdsn","",$val);
	$val = str_replace("xp_filelist","",$val);
	$val = str_replace("sp_password","",$val);
	$val = str_replace("sp_adduser","",$val);
	$val = str_replace("sp_addextendedproc","",$val);
	$val = str_replace("sp_dropextendedproc","",$val);
	$val = str_replace("sp_add_job","",$val);
	$val = str_replace("sp_start_job","",$val);
	$val = str_replace("sp_delete_alert","",$val);
	$val = str_replace("sp_msrepl_startup","",$val);

   return $val;
}


function RemoveIJT($val) {
	$val = str_replace("<iframe","",$val);
	$val = str_replace("<script","",$val);
	$val = str_replace("1=1","",$val);
	$val = str_replace("--","",$val);
	$val = str_replace("comd_list","",$val);
	$val = str_replace("jiaozhu","",$val);
	$val = str_replace("T_Jiaozhu","",$val);
	$val = str_replace("D99_Tmp","",$val);
	$val = str_replace("insert","",$val);
	$val = str_replace("update","",$val);
	$val = str_replace("delete","",$val);
	$val = str_replace("drop ","",$val);
	$val = str_replace("alter ","",$val);
	$val = str_replace("create ","",$val);
	$val = str_replace("inner join","",$val);
	$val = str_replace("from ","",$val);
	$val = str_replace("where ","",$val);
	$val = str_replace("union ","",$val);
	$val = str_replace("group by","",$val);
	$val = str_replace("having ","",$val);
	$val = str_replace("shutdown","",$val);
	$val = str_replace("kill ","",$val);
	$val = str_replace("declare","",$val);
	$val = str_replace("openrowset","",$val);
	$val = str_replace("opendatasource","",$val);
	$val = str_replace("pwdencrypt","",$val);
	$val = str_replace("msdasql","",$val);
	$val = str_replace("sqloledb","",$val);
	$val = str_replace("char(","",$val);
	$val = str_replace("syslogins","",$val);
	$val = str_replace("sysxlogins","",$val);
	$val = str_replace("sysdatabases","",$val);
	$val = str_replace("sysobjects","",$val);
	$val = str_replace("syscomments","",$val);
	$val = str_replace("raiserror","",$val);
	$val = str_replace("exec","",$val);
	$val = str_replace("xp_","",$val);
	$val = str_replace("sp_","",$val);
	$val = str_replace("xp_cmdshell","",$val);
	$val = str_replace("xp_reg","",$val);
	$val = str_replace("xp_servicecontrol","",$val);
	$val = str_replace("xp_setsqlsecurity","",$val);
	$val = str_replace("xp_readerrorlog","",$val);
	$val = str_replace("xp_controlqueueservice","",$val);
	$val = str_replace("xp_createprivatequeue","",$val);
	$val = str_replace("xp_decodequeuecommand","",$val);
	$val = str_replace("xp_deleteprivatequeue","",$val);
	$val = str_replace("xp_deletequeue","",$val);
	$val = str_replace("xp_displayqueuemesgs","",$val);
	$val = str_replace("xp_dsinfo","",$val);
	$val = str_replace("xp_mergelineages","",$val);
	$val = str_replace("xp_readpkfromqueue","",$val);
	$val = str_replace("xp_readpkfromvarbin","",$val);
	$val = str_replace("xp_repl_encrypt","",$val);
	$val = str_replace("xp_resetqueue","",$val);
	$val = str_replace("xp_sqlinventory","",$val);
	$val = str_replace("xp_unpackcab","",$val);
	$val = str_replace("xp_sprintf","",$val);
	$val = str_replace("xp_displayparamstmt","",$val);
	$val = str_replace("xp_enumresult","",$val);
	$val = str_replace("xp_showcolv","",$val);
	$val = str_replace("xp_updatecolvbm","",$val);
	$val = str_replace("xp_execresultset","",$val);
	$val = str_replace("xp_printstatements","",$val);
	$val = str_replace("xp_peekqueue","",$val);
	$val = str_replace("xp_proxiedmetadata","",$val);
	$val = str_replace("xp_displayparamstmt","",$val);
	$val = str_replace("xp_availablemedia","",$val);
	$val = str_replace("xp_enumdsn","",$val);
	$val = str_replace("xp_filelist","",$val);
	$val = str_replace("sp_password","",$val);
	$val = str_replace("sp_adduser","",$val);
	$val = str_replace("sp_addextendedproc","",$val);
	$val = str_replace("sp_dropextendedproc","",$val);
	$val = str_replace("sp_add_job","",$val);
	$val = str_replace("sp_start_job","",$val);
	$val = str_replace("sp_delete_alert","",$val);
	$val = str_replace("sp_msrepl_startup","",$val);

   return $val;
}

//==========================================================================================================================
// XSS(Cross Site Scripting) 공격에 의한 데이터 검증 및 차단
//--------------------------------------------------------------------------------------------------------------------------
function xss_clean($data)
{
    // If its empty there is no point cleaning it :\
    if(empty($data))
        return $data;

    // Recursive loop for arrays
    if(is_array($data))
    {
        foreach($data as $key => $value)
        {
            $data[$key] = xss_clean($value);
        }

        return $data;
    }

    // http://svn.bitflux.ch/repos/public/popoon/trunk/classes/externalinput.php
    // +----------------------------------------------------------------------+
    // | Copyright (c) 2001-2006 Bitflux GmbH                                 |
    // +----------------------------------------------------------------------+
    // | Licensed under the Apache License, Version 2.0 (the "License");      |
    // | you may not use this file except in compliance with the License.     |
    // | You may obtain a copy of the License at                              |
    // | http://www.apache.org/licenses/LICENSE-2.0                           |
    // | Unless required by applicable law or agreed to in writing, software  |
    // | distributed under the License is distributed on an "AS IS" BASIS,    |
    // | WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or      |
    // | implied. See the License for the specific language governing         |
    // | permissions and limitations under the License.                       |
    // +----------------------------------------------------------------------+
    // | Author: Christian Stocker <chregu@bitflux.ch>                        |
    // +----------------------------------------------------------------------+

    // Fix &entity\n;
    $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/i', '$1;', $data);

    if (function_exists("html_entity_decode"))
    {
        $data = html_entity_decode($data);
    }
    else
    {
        $trans_tbl = get_html_translation_table(HTML_ENTITIES);
        $trans_tbl = array_flip($trans_tbl);
        $data = strtr($data, $trans_tbl);
    }

    // Remove any attribute starting with "on" or xmlns
    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#i', '$1>', $data);

    // Remove javascript: and vbscript: protocols
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#i', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#i', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#', '$1=$2nomozbinding...', $data);

    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#i', '$1>', $data);

    // Remove namespaced elements (we do not need them)
    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

    do
    {
        // Remove really unwanted tags
        $old_data = $data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
    }
    while ($old_data !== $data);

    return $data;
}


function sql_password($value)
{
	global $dbcon;
	//SEED CBC 방식암호화
	$return_str = all_seed_enc($value);
    return $return_str;
}

//기간 차이 검색
function datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
/*
datediff('w', '9 July 2003', '4 March 2004', false);

$interval can be:
yyyy - Number of full years
q - Number of full quarters
m - Number of full months
y - Difference between day numbers
(eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
d - Number of full days
w - Number of full weekdays
ww - Number of full weeks
h - Number of full hours
n - Number of full minutes
s - Number of full seconds (default)
*/

if (!$using_timestamps) {
$datefrom = strtotime($datefrom, 0);
$dateto = strtotime($dateto, 0);
}
$difference = $dateto - $datefrom; // Difference in seconds

switch($interval) {

case 'yyyy': // Number of full years

$years_difference = floor($difference / 31536000);
if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
$years_difference--;
}
if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
$years_difference++;
}
$datediff = $years_difference;
break;

case "q": // Number of full quarters

$quarters_difference = floor($difference / 8035200);
while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
$months_difference++;
}
$quarters_difference--;
$datediff = $quarters_difference;
break;

case "m": // Number of full months

$months_difference = floor($difference / 2678400);
while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
$months_difference++;
}
$months_difference--;
$datediff = $months_difference;
break;

case 'y': // Difference between day numbers

$datediff = date("z", $dateto) - date("z", $datefrom);
break;

case "d": // Number of full days

$datediff = floor($difference / 86400);
break;

case "w": // Number of full weekdays

$days_difference = floor($difference / 86400);
$weeks_difference = floor($days_difference / 7); // Complete weeks
$first_day = date("w", $datefrom);
$days_remainder = floor($days_difference % 7);
$odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
if ($odd_days > 7) { // Sunday
$days_remainder--;
}
if ($odd_days > 6) { // Saturday
$days_remainder--;
}
$datediff = ($weeks_difference * 5) + $days_remainder;
break;

case "ww": // Number of full weeks

$datediff = floor($difference / 604800);
break;

case "h": // Number of full hours

$datediff = floor($difference / 3600);
break;

case "n": // Number of full minutes

$datediff = floor($difference / 60);
break;

default: // Number of full seconds (default)

$datediff = $difference;
break;
}

return $datediff;

}

// 이지함 SMS발송
function SMS_Send($from_tel,$to_tel,$msg){
	global $dbcon;
	$SMS_SQL = "INSERT INTO kssms.SC_TRAN (TR_SENDDATE , TR_SENDSTAT ,TR_MSGTYPE ,TR_PHONE ,TR_CALLBACK , TR_MSG) VALUES (NOW(), '0', '0', '".$to_tel."', '".$from_tel."', '".$msg."')";
	$SMS_RS = $dbcon -> query($SMS_SQL);
	return $SMS_RS;
}

function MMS_Send($from_tel,$to_tel,$subject='',$msg){
	global $dbcon;
	$MMS_SQL = "INSERT INTO kssms.MMS_MSG (SUBJECT, PHONE, CALLBACK, STATUS, REQDATE, MSG, TYPE) VALUES ('".$subject."', '".$to_tel."', '".$from_tel."', '0', NOW(), '".$msg."', '0' )";
	$MMS_RS = $dbcon -> query($MMS_SQL);
	return $MMS_RS;
}


//////////////////////////////윈도우 창 컨트롤 & 경고 문구 START/////////////////////////
function selfClose() {
	echo "<script language=\"javascript\">
			self.close();
		  </script>";
}
function Message_Echo($msg) {

	echo "<script language='javascript'>
		  alert(\"$msg\");
		  </script>";
}
function windowReload() {
	echo "<script language='javascript'>
			opener.location.reload();
		  </script>";
}

function windowClose($msg) {
	echo "<script language='javascript'>
			alert(\"$msg\");
			window.close();
		  </script>";
}
function windowCloseReload($msg) {
	echo "<script language='javascript'>
			alert(\"$msg\");
			opener.location.reload();
			window.close();
		  </script>";
}
function winCloseTgtReload($msg,$url) {
	echo "<script language='javascript'>
			alert(\"$msg\");
			opener.location.href='$url';
			window.close();
		  </script>";
}
function winCloseTgtReload2($url) {
	echo "<script language='javascript'>
			opener.location.href='$url';
			window.close();
		  </script>";
}

function ERROR_BACK($msg) {

echo "<script language='javascript'>
      alert(\"$msg\");
	  history.back();
	  </script>";
	exit;
}

//hjh 2016-07-29

function ConsoleLog($msg) {

echo "<script language='javascript'>
     console.log(\"$msg\");
	  </script>";

}

// 푸시 발송 함수 및 메시지 저장
function send_fcm($message, $id) {
	$url = 'https://fcm.googleapis.com/fcm/send';

	$headers = array ('Authorization: key=' . GOOGLE_SERVER_KEY,'Content-Type: application/json');

	$fields = array (
	'data' => array ("message" => $message),
	'notification' => array ("body" => $message)
	);

	if(is_array($id)) {
	$fields['registration_ids'] = $id;
	} else {
	$fields['to'] = $id;
	}

	$fields['priority'] = "high";

	$fields = json_encode ($fields);

	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_POST, true );
	curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

	$result = curl_exec ( $ch );
	if ($result === FALSE) {
	//die('FCM Send Error: ' . curl_error($ch));
	}
	curl_close ( $ch );
	return $result;
}


//=================================================================================
// SEED 암호문 CBC방식 암호문처리
//=================================================================================
function strToHex($string){
$hex='';
for ($i=0; $i < strlen($string); $i++){
	$hex .= "," . dechex(ord($string[$i]));
}
return $hex;
}

function hexToStr($hex){
$string='';
for ($i=0; $i < strlen($hex)-1; $i+=2){
	$string .= chr(hexdec($hex[$i].$hex[$i+1]));
}
	return $string;
}
function seed_decrypt($bszIV, $bszUser_key, $str) {
	$planBytes = explode(",",$str);
	$keyBytes = explode(",",$bszUser_key);
	$IVBytes = explode(",",$bszIV);

	for($i = 0; $i < 16; $i++)
	{
		$keyBytes[$i] = hexdec($keyBytes[$i]);
		$IVBytes[$i] = hexdec($IVBytes[$i]);
	}
	for ($i = 0; $i < count($planBytes); $i++) {
		$planBytes[$i] = hexdec($planBytes[$i]);
	}
	if (count($planBytes) == 0) {
		return $str;
	}
	$pdwRoundKey = array_pad(array(),32,0);
	$bszPlainText = null;

	// 방법 1
	$bszPlainText = KISA_SEED_CBC::SEED_CBC_Decrypt($keyBytes, $IVBytes, $planBytes, 0, count($planBytes));
	for($i=0;$i< sizeof($bszPlainText);$i++) {
		$planBytresMessage .= sprintf("%02X", $bszPlainText[$i]).",";
	}
	return substr($planBytresMessage,0,strlen($planBytresMessage)-1);
}



function seed_encrypt($bszIV, $bszUser_key, $str) {
	$planBytes = explode(",",$str);
	$keyBytes = explode(",",$bszUser_key);
	$IVBytes = explode(",",$bszIV);

	for($i = 0; $i < 16; $i++)
	{
		$keyBytes[$i] = hexdec($keyBytes[$i]);
		$IVBytes[$i] = hexdec($IVBytes[$i]);
	}
	for ($i = 0; $i < count($planBytes); $i++) {
		$planBytes[$i] = hexdec($planBytes[$i]);
	}
	if (count($planBytes) == 0) {
		return $str;
	}
	$ret = null;
	$bszChiperText = null;
	$pdwRoundKey = array_pad(array(),32,0);
	//방법 1
	$bszChiperText = KISA_SEED_CBC::SEED_CBC_Encrypt($keyBytes, $IVBytes, $planBytes, 0, count($planBytes));
	$r = count($bszChiperText);
	for($i=0;$i< $r;$i++) {
		$ret .= sprintf("%02X", $bszChiperText[$i]).",";
	}
	return substr($ret,0,strlen($ret)-1);
}
function all_seed_enc($str){
	global $g_bszUser_key,$g_bszIV;
	$strToEnc = strToHex($str);
	$strToEnc = substr( $strToEnc , 1, strlen($strToEnc));
	$strToEnc = seed_encrypt($g_bszIV, $g_bszUser_key, $strToEnc);
	$strToEnc = str_replace(",","", $strToEnc);
	$strToEnc = hexToStr( $strToEnc);
	$strToEnc = base64_encode($strToEnc);
	return $strToEnc;
}

function all_seed_dec($str){
	global $g_bszUser_key,$g_bszIV;
	$strToDec = base64_decode($str);
	$strToDec = strToHex($strToDec);
	$strToDec = substr( $strToDec , 1, strlen($strToDec));
	$strToDec = seed_decrypt($g_bszIV, $g_bszUser_key, $strToDec);
	$strToDec = str_replace(",","", $strToDec);
	$strToDec = hexToStr( $strToDec);
	return $strToDec;
}
//=================================================================================
// SEED 암호문 CBC방식 암호문처리 종료
//=================================================================================

//보험사 검색
function print_ins($seq){
	global $dbcon;
	if ($seq){
	$SQL_ins_subject = "select subject from tbl_board_ins_list where seq='".$seq."' ";
	$RS_ins_subject = $dbcon -> query($SQL_ins_subject);
	$rows = $dbcon -> fetch_array($RS_ins_subject);
	return $rows["subject"];
	}
}
//보험약관 검색
function print_ins_agree($seq){
	global $dbcon;
	if ($seq){
	$SQL_ins_subject = "select subject from tbl_board_ins_agree where seq='".$seq."' ";
	$RS_ins_subject = $dbcon -> query($SQL_ins_subject);
	$rows = $dbcon -> fetch_array($RS_ins_subject);
	return $rows["subject"];
	}
}
//서비스약관 검색
function print_service_agree($seq){
	global $dbcon;
	if ($seq){
	$SQL_ins_subject = "select subject from tbl_board_service_agree where seq='".$seq."' ";
	$RS_ins_subject = $dbcon -> query($SQL_ins_subject);
	$rows = $dbcon -> fetch_array($RS_ins_subject);
	return $rows["subject"];
	}
}
//상품 검색
function print_pr_name($seq){
	global $dbcon;
	if ($seq){
	$SQL_ins_subject = "select subject from tbl_board_product where seq='".$seq."' ";
	$RS_ins_subject = $dbcon -> query($SQL_ins_subject);
	$rows = $dbcon -> fetch_array($RS_ins_subject);
	return $rows["subject"];
	}
}
//상품명 추출
function getGlobalProduct() {
	global $dbcon;
	$TempSQL = " select seq, subject from tbl_board_product WHERE secret = 'Y' order by sort_order asc ";
	
	return $dbcon -> query($TempSQL);
}
//보험사 추출
function getGlobalIns() {
	global $dbcon;
	$TempSQL = " select seq, subject from tbl_board_ins_list order by seq asc ";
	return $dbcon -> query($TempSQL);
}
//플랜 검색
function getGlobalPlan(){
	global $dbcon;
	$TempSQL = "select seq, ins_plan_name from tbl_board_plan order by seq asc ";
	
	return $dbcon -> query($TempSQL);
}
//메인 이벤트 검색
function getGlobalMainEvent(){
	global $dbcon;
	$TempSQL = "select seq, subject from tbl_board_main_event order by seq asc ";
	
	return $dbcon -> query($TempSQL);
}

//사용자 상품메뉴 추출
function getFrontProductMenu(){
	global $dbcon;
	$TempSQL = " select seq, subject from tbl_board_product WHERE ext2 = 'Y' and secret = 'Y' order by sort_order asc, seq DESC ";
	return $dbcon -> query($TempSQL);
}

//플랜명 추출
function getFullPlanName($seq) {
	global $dbcon;
	$SQL =  " SELECT ";
	$SQL .= " (SELECT subject FROM tbl_board_product WHERE seq = p.pr_cd) as pr_cd_name ";
	$SQL .= " , (SELECT subject FROM tbl_board_ins_list WHERE seq = p.ins_cd) as ins_cd_name ";
	$SQL .= " , plan_cd, chk_service FROM tbl_board_plan p WHERE seq='".$seq."' ";
	
	$RS = $dbcon -> query($SQL);
	$ROW = $dbcon -> fetch_array($RS);
	
	if($ROW["chk_service"] == "A") {
		$ROW["chk_service_text"] = "A타입";
	} else if($ROW["chk_service"] == "B") {
		$ROW["chk_service_text"] = "B타입";
	} else if($ROW["chk_service"] == "C") {
		$ROW["chk_service_text"] = "인슈플러스";
	} else if($ROW["chk_service"] == "D") {
		$ROW["chk_service_text"] = "플라잉닥터스";
	} else if($ROW["chk_service"] == "E") {
		$ROW["chk_service_text"] = "플라잉닥터스B";
	} else {
		$ROW["chk_service_text"] = "";
	}
	
	return $ROW;
}

//제휴사 찾기
function getPartnerSeq($partnership_code) {
	global $dbcon; 
	$today = date("Y-m-d");
	
	$SQL  = " SELECT seq FROM tbl_board_partner WHERE partnership_code = '".$partnership_code."' ";
	$SQL .= " AND start_Partner_period <= '".$today."' AND end_Partner_period >= '".$today."' ORDER BY seq ASC limit 0,1 ";
	// echo $SQL ;
	$RS = $dbcon->query($SQL);
	$ROW = $dbcon -> fetch_array($RS);
	 
	return $ROW["seq"];
}

//주민번호 생년월일 구하기
function getBirthDate($ymd = '') {
	if (!$ymd || empty($ymd)) { 
        return false;
    }
    $birth = '';
    switch (substr(trim($ymd),7,1)) {
        case '1':
        case '2':
            $birth = '19'.substr(trim($ymd),0,2);
        break;
        case '3':
        case '4':
            $birth = '20'.substr(trim($ymd),0,2);
        break;
    }
    $birth = '19'.substr(trim($ymd),0,2);
    $age = '';
    if (!empty($birth)) {
        $age = date('Y') - $birth + 1;
    }
    return $age;
}
//도메인
function getDomain() {
	$domain = "";
	if($_SERVER['HTTPS'] != "on"){
		$domain = "http://".$_SERVER["SERVER_NAME"];
	} else {
		$domain = "https://".$_SERVER["SERVER_NAME"];
	}
	
	if($_SERVER['SERVER_PORT'] != "80" || $_SERVER['SERVER_PORT'] != "443") $domain .= ":".$_SERVER['SERVER_PORT'];
	
	return $domain;
}

//암호화
function encrypt($str) {
	$secret_key='insuplus$#@!';
	$secret_iv='1';
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16)    ;

	return str_replace("=", "", base64_encode(
			openssl_encrypt($str, "AES-256-CBC", $key, 0, $iv))
			);
}

//복호화
function decrypt($str) {
	$secret_key='insuplus$#@!';
	$secret_iv='1';
	$key = hash('sha256', $secret_key);
	$iv = substr(hash('sha256', $secret_iv), 0, 16);

	return openssl_decrypt(
			base64_decode($str), "AES-256-CBC", $key, 0, $iv
			);
}

//array_to_json
function array_to_json( $array ) { 
    if( !is_array( $array ) ) {
        return false;
    } 
    $associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
    if( $associative ) { 
        $construct = array();
        foreach( $array as $key => $value ) { 
            // We first copy each key/value pair into a staging array,
            // formatting each key and value properly as we go.
 
            // Format the key:
            if( is_numeric($key) ) {
                $key = "key_$key";
            }
            $key = '"'.addslashes($key).'"';
 
            // Format the value:
            if( is_array( $value )) {
                $value = array_to_json( $value );
            } else if( !is_numeric( $value ) || is_string( $value ) ) {
                $value = '"'.addslashes($value).'"';
            } 
            // Add to staging array:
            $construct[] = "$key: $value";
        } 
        // Then we collapse the staging array into the JSON form:
        $result = "{ " . implode( ", ", $construct ) . " }";
 
    } else { // If the array is a vector (not associative): 
        $construct = array();
        foreach( $array as $value ) { 
            // Format the value:
            if( is_array( $value )) {
                $value = array_to_json( $value );
            } else if( !is_numeric( $value ) || is_string( $value ) ) {
                $value = '"'.addslashes($value).'"';
            } 
            // Add to staging array:
            $construct[] = $value;
        } 
        // Then we collapse the staging array into the JSON form:
        $result = "[ " . implode( ", ", $construct ) . " ]";
    } 
    return $result;
}
?>
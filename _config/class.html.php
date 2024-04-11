<?php
	class html{



		// 생성자
		function __construct() {

			return true;
		}


		/*
			Select Box Option 생성
			인수 : getSelectOption( 배열 , 선택번호)
		*/


		function getSelectOptions($OptArr=null, $sel=false) {
			$returnValue = "";

			if ($OptArr) {
				foreach( $OptArr as $key => $val) {
					$selected = "";

					if ($key) {
						if ($sel && $sel == $key) $selected = " selected ";
						$returnValue .= "<option value=\"".$key."\"".$selected.">".$val."</option>\n";
					}
				}
			}
			else {
				return false;
			}

			return $returnValue;
		}


		// 관리자 모드에서 타이틀 출력
		function getAdminTitle($title=null) {
			global $url_admin;
			$str = "";
			if ( $title ) {
				$str = "
					<table border='0' cellpadding='0' cellspacing='0' width='100%'>
						<tr>
							<td width='16' height='22' valign='top' align='center' style='padding-top:6px'><img src='".$url_admin."images/admin_st_ball.gif'></td>
							<td valign='top' class='a_st'>".$title."</td>
						</tr>
						<tr>
							<td colspan='2' height='1' bgcolor='#D5D5D5'></td>
						</tr>
						<tr>
							<td colspan='2' height='20'></td>
						</tr>
					</table>
				";
			}

			return $str;
		}
	}


//			$html = new html;
//			$Arr_u_sex		= Array(
//				"M" => "남자"
//				, "F" => "여자"
//			);
//			$opt = $html->getSelectOption($Arr_u_sex, "F");
//			echo "<xmp>".$opt ."</xmp><BR>";


?>


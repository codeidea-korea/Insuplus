<?
	//-- inc.popup.php ----------------------------------------------------
	//-- 제작 이정훈(email:wildroseBoy@hotmail.com) 2003-12-27
	//-- 수정 김승태 2008-04-01
	$cookie_name = str_replace( ".", "_dot_", basename( $_SERVER["PHP_SELF"] ) ).$popnum;
	//echo $cookie_name."<BR>";
?>
<SCRIPT language="JavaScript">

	function setCookie( name, value, expiredays ){
		var todayDate = new Date();
		todayDate.setDate(todayDate.getDate() + expiredays);
		document.cookie = name + "=" + escape(value) + "; path=/; expires=" + todayDate.toGMTString() + ";"
	}

	function closeWin(){
		setCookie( "<?= $cookie_name ?>", "1" , 1);
		window.close();
	}

</SCRIPT>
<input type="checkbox" onClick="closeWin()">
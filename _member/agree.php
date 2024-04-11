<?
	include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
//	echo $path_skin_member."agree.php";
	login_chk(0);
	include_once $path_skin_member."agree.php";
	$dbcon -> dbcon_close();
?>

<script language="JavaScript" src="/member/join.js"></script>

<script>
	TempGo = 0;

	function registGo() {
		ff = document.AgreeForm;
		ff.action = "";


/*
		if ( ff.u_agree.checked == false ) {
			alert( "<?=$msg_join_agree?>");
			ff.u_agree.focus();
			return false;
		}

		if ( ff.u_agree2.checked == false ) {
			alert( "<?=$msg_join_agree?>");
			ff.u_agree2.focus();
			return false;
		}
*/


		if ( ff.u_agree[0].checked == false ) {
			alert( "<?=$msg_join_agree?>");
			ff.u_agree[0].focus();
			return false;
		}

		<? if ($sc_member_name_check == "Y") { ?>
		if ( ff.u_name.value == "") {
			alert( "<?=$msg_join_name?>" );
			ff.u_name.focus();
			return false;
		}
		if ( ff.u_jumin1.value.length < 6 || ff.u_jumin2.value.length < 7) {
			alert( "<?=$msg_join_jumin?>" );
			ff.u_jumin1.focus();
			return false;
		}
		if ( !juminCheck(ff.u_jumin1, ff.u_jumin2) ) {
			return false;
		}
		<? } ?>


		if (TempGo > 0) {
			alert("<?=$msg_error_touch?>");
			return false;
		}
		TempGo = 1;


		ff.action = "join.php";

	}
</script>
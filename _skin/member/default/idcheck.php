<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>아이디 중복체크</title>
<link href="<?=$url_root?>inc/css/content.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style>
<script type="text/javascript">
<!--
function MM_swapImgRestore() {
	//v3.0
	var i,x,a=document.MM_sr;
	for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() {
	//v3.0
	var d=document;
	if(d.images){
		if(!d.MM_p) d.MM_p=new Array();
		var i,j=d.MM_p.length,a=MM_preloadImages.arguments;
		for(i=0; i<a.length; i++)
			if (a[i].indexOf("#")!=0){
				d.MM_p[j]=new Image;
			d.MM_p[j++].src=a[i];
		}
	}
}

function MM_findObj(n, d) {
	//v4.01
	var p,i,x;
	if(!d) d=document;
	if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document;
		n=n.substring(0,p);
	}
	if(!(x=d[n])&&d.all) x=d.all[n];
	for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
		for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
		if(!x && d.getElementById) x=d.getElementById(n);
		return x;
}

function MM_swapImage() {
	//v3.0
	var i,j=0,x,a=MM_swapImage.arguments;
	document.MM_sr=new Array;
	for(i=0;i<(a.length-2);i+=3)
		if ((x=MM_findObj(a[i]))!=null){
			document.MM_sr[j++]=x;
		if(!x.oSrc) x.oSrc=x.src;
		x.src=a[i+2];
	}
}
//-->
</script>
<script>

	var alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	var numeric = '1234567890';
	var special = ' ~!@#$%^&*()-_=+|\\{}[];:"\'<>,.?\/';

	function idCheckOK() {
		if (!opener) {
			alert("<?=$msg_error_opener?>");
			return;
		}

		ff = opener.document.JoinForm;
		ff.u_id.value = "<?=$_GET['u_id']?>";
		ff.id_chk.value = 1;
		ff.u_pw.focus();
		window.close();
	}


	// 아이디 체크창 열기
	function idCheck() {
		var ff = document.idcheckForm;

		if(ff.u_id.value.length < 6) {
			alert("아이디는 영문 또는 숫자 6~16자로 구성됩니다.");
			ff.u_id.focus();
			return false;
		}
		else {
			if (!checkNorm(ff.u_id, '아이디', numeric+alpha+'(-_)+', 16)) {
				OBJ_ID.focus();
				return false;
			}
		}
	}

	function checkNorm(target, cmt, astr, lmax) {
		var i;
		var t = target.value;
		if (t.length == 0 ) {
			alert(cmt + '(을)를 기재하지 않으셨습니다.');
			return false;
		}
		if (lmax != 0 && t.length > lmax) {
			alert(cmt + '는 ' + lmax + '자 이내만 허용합니다.');
			return false;
		}
		if (astr.length >= 1) {
			for (i=0; i<t.length; i++) {
				if( astr.indexOf(t.substring(i,i+1)) < 0 ) {
					alert(cmt + '에 허용할 수 없는 문자가 입력되었습니다');
					return false;
					break;
				}
			}
			return true;
		}
	}

</script>
</head>
<body topmargin="0" leftmargin="0" marginwidth="0" marginheight="0" onLoad="MM_preloadImages('<?=$url_skin_member?>images/id_find_tab02_over.gif')">
<table width="340" height="180" border="0" cellspacing="0" cellpadding="0" style="border:5px #f7edd8 solid;">
	<tr>
		<td align="center" valign="top" style="padding:16 15 15 15" background="<?=$url_skin_member?>images/id_find_bg.gif">
			<table width="310" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="50" valign="top"><img src="<?=$url_skin_member?>images/id_check_title.gif"></td>
					<td width="22" valign="top"><img src="<?=$url_skin_member?>images/id_check_x.gif" onClick="window.close()" style="cursor:hand" alt="CLOSE"></td>
				</tr>
				<tr>
					<td height="1" colspan="2" valign="top" background="<?=$url_root?>images/02/dotline.gif"></td>
				</tr>
			</table>


			<? if($count > 0) { ?>

			<table border="0" cellspacing="0" cellpadding="0" class="mg_top17">
				<tr>
					<td height="24" valign="top" class="txt01">
						<font color="#486D8F"><b><?=$_GET['u_id']?></b></font><font color="#404040"><?=$msg_idcheck_false?></font>
					</td>
				</tr>
			</table>
			<? } else { ?>

			<table border="0" cellspacing="0" cellpadding="0" class="mg_top17">
				<tr>
					<td height="24" valign="top" class="txt01">
						<font color="#486D8F"><b><?=$_GET['u_id']?></b></font><font color="#404040"><?=$msg_idcheck_true?></font>
					</td>
				</tr>
				<tr>
					<td align="center" valign="top" height="45"><a href="javascript:idCheckOK()"><img src="<?=$url_skin_member?>images/id_check_btn_use.gif" border="0"></a></td>
				</tr>
			</table>

			<? } ?>

			<table width="276" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="7"></td>
				</tr>
				<tr>
					<td height="48" background="<?=$url_skin_member?>images/id_check_box02_bg.gif" valign="top" style="padding:0 0 0 26">
						<form method="get" action="<?=$PHP_SELF?>" name="idcheckForm" onSubmit="return idCheck();" style="padding:0;margin:0;border:0">
						<table width="226" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td valign="top" height="20"><img src="<?=$url_skin_member?>images/id_check_txt.gif"></td>
							</tr>
							<tr>
								<td valign="top" height="28">
									<table width="270" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td valign="top" width="47" style="padding:1 0 0 0"><img src="<?=$url_skin_member?>images/id_find_id.gif" hspace="5"></td>
											<td valign="top" width="112"><input type="text" name="u_id" class="input_check"></td>
											<td valign="top" width="67" style="padding:1 0 0 5"><input type="image" src="<?=$url_skin_member?>images/m_btn01.gif"></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
						</form>
					</td>
				</tr>
				<tr>
					<td height="7"></td>
				</tr>
			</table>

		</td>
	</tr>
</table>
</body>
</html>
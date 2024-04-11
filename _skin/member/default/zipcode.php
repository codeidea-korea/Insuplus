<html>
<head>
<title>Address Search!!!</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
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

</head>

<body onLoad="MM_preloadImages('<?=$url_skin_member?>images/id_find_tab02_over.gif')">


<form name="PostForm" method="post" action="<?=$PHP_SELF?>" onsubmit="return PostSearchGo()">
<input type="hidden" name="obj_form" value="<?=$obj_form?>">
<input type="hidden" name="obj_post" value="<?=$obj_post?>">
<input type="hidden" name="obj_addr1" value="<?=$obj_addr1?>">
<input type="hidden" name="obj_addr2" value="<?=$obj_addr2?>">

<table width="460" height="317" border="0" cellspacing="0" cellpadding="0" style="border:5px #f7edd8 solid;">
	<tr>
		<td align="center" valign="top" style="padding:16 15 15 15" background="<?=$url_skin_member?>images/id_find_bg.gif">
			<table width="450" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="50" valign="top"><img src="<?=$url_skin_member?>images/zip_title.gif"></td>
					<td width="22" valign="top"><img src="<?=$url_skin_member?>images/id_check_x.gif" onClick="window.close()" style="cursor:hand" alt="창닫기"></td>
				</tr>
			</table>





			<table width="416" height="63" border="0" cellpadding="0" cellspacing="0" class="mg_top17">
				<tr>
					<td height="46" align="center" valign="middle" background="<?=$url_skin_member?>images/zip_box02_bg.gif">
						<table width="366" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td valign="top" height="24">

									<table width="374" border="0" cellspacing="0" cellpadding="0">
										<tr>
											<td valign="top" width="122">
												<select name="postgubun" id="addcode" class="noti_txt03">
													<option value="dong" <? if($postgubun=="dong") echo "selected";?>><?=$PostGubun1?></option>
													<option value="zipcode" <? if($postgubun=="zipcode") echo "selected";?>><?=$PostGubun2?></option>
												</select>
											</td>
											<td valign="top" width="143"><input type="text" name="search_dong" value="<?=$search_dong?>" class="noti_txt03"></td>
											<td valign="top" width="109" style="padding:1 0 0 0"><input type="image" src="<?=$url_skin_member?>images/zip_btn_search.gif"></td>
										</tr>
									</table>

								</td>
							</tr>
							<tr>
								<td height="22"><img src="<?=$url_skin_member?>images/zip_txt.gif"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
			<!--------->
			<table width="416" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td height="19" valign="top">&nbsp;</td>
				</tr>



				<? if ( getLen($search_dong) > 0 ) { ?>

				<tr>
					<td height="19" valign="top"><img src="<?=$url_skin_member?>images/zip_tit01.gif" width="416" height="12"></td>
				</tr>
				<tr>
					<td height="59" valign="top">
						<table width="416" border="0" cellspacing="0" cellpadding="0">
							<tr>
								<td height="34" colspan="2" class="input_txt" align="center">
									<select name="sel_u_post" class="txt01">
										<option value="" ><?=$msg_info_selected?></option>
										<? while ($rows = $dbcon -> fetch_array($result) ) { ?>
										<option value="<?=$rows[zipcode]?>" value1="<?=$rows[sido]." ".$rows[gugun]." ".$rows[dong]?>" ><?=$rows[zipcode]?> | <?=$rows[sido]." ".$rows[gugun]." ".$rows[dong]." ".$rows[bunji]?></option>
										<? } ?>
									</select>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td height="48" align="center" valign="top"><a href="javascript:PostSelectGo()"><img src="<?=$url_skin_member?>images/zip_btn_detail.gif" border="0"></a></td>
				</tr>

				<? } else { ?>

				<tr>
					<td height="19" valign="top"><img src="<?=$url_skin_member?>images/zip_tit00.gif" width="416" height="12"></td>
				</tr>

				<? } ?>

			</table>
			<!-------->
		</td>
	</tr>
</table>

</form>

<script language="javascript">
<!--
	function PostSearchGo(){
		var ff = document.PostForm;
		if (ff.search_dong.value=="") {
			alert("<?=$msg_error_search_content?>");
			ff.search_dong.focus();
			return false;
		}
	}


	function PostSelectGo() {
		var ff = document.PostForm;
		sel_obj = ff.sel_u_post;
		if ( sel_obj.selectedIndex == 0 || sel_obj[sel_obj.selectedIndex].value == "" ) {
			alert("<?=$msg_info_selected?>");
			sel_obj.focus();
			return;
		}
		var_u_post = sel_obj[sel_obj.selectedIndex].value;
		var_u_addr1 = sel_obj[sel_obj.selectedIndex].value1;
		off = opener.document.<?=$obj_form?>;


		off.<?=$obj_post?>.value = var_u_post;
		off.<?=$obj_addr1?>.value = var_u_addr1;
		off.<?=$obj_addr2?>.focus();
		window.close();

	}

	function onloadevent() {
		document.PostForm.search_dong.focus();
	}
	onload = onloadevent;

//-->
</script>


</body>
</html>






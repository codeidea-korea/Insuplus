<?
	if ( strlen($ss_u_id) > 0 ) {
		$print_u_name = " value='".$ss_u_name."' readonly";
		$print_u_id = $ss_u_id;
	}
	else {
		$print_u_id = "";
		$print_u_name = "";
	}
?>



<?
//사용자 모드입니다
if ($client_mode=="Y"){
?>

<?if ($ss_u_id){?>
<script type="text/javascript">
<!--
function clean_comment(){
	var ff = document.CommentForm;
	if (ff.cmt_content.value=="욕설, 비방 등 게시글과  무관한 의견은 관리자에 의해 삭제될 수 있습니다."){
		ff.cmt_content.value="";
		ff.cmt_content.focus();
	}
}
//-->
</script>
<?}else{?>
<script type="text/javascript">
<!--
function clean_comment(){
	alert('로그인 후 이용하실 수 있습니다.');
	document.location.href="/html/login/login.php?url=<?=urlencode($_SERVER['REQUEST_URI']);?>";
}
//-->
</script>
<?}?>
<form name="CommentForm" method="post" action="">
<input type="hidden" name="idx" value="">
<input type="hidden" name="category" value="<?=$category?>">
<input type="hidden" name="bc_id" value="<?=$bc_id?>">
<input type="hidden" name="seq" value="<?=$seq?>">
<input type="hidden" name="mode" value="insert">
<input type="hidden" name="cmt_u_id" value="<?=$print_u_id?>">
<input type="hidden" name="cmt_u_name" <?=$print_u_name?> maxlength="20">

						<h4 class="titleTy01 mT45">의견남기기</h4>
						<div class="txtArea01 mT25">
							<textarea cols="100" rows="5" name="cmt_content" style="width:86%" onclick="clean_comment();">욕설, 비방 등 게시글과  무관한 의견은 관리자에 의해 삭제될 수 있습니다.</textarea>
							<a href="javascript: CommentNextGo_Client();" class="boxSt07">등록</a>
						</div>


</form>


<iframe id="CommentIframe" name="CommentIframe" frameborder="0" width="0" height="0"></iframe>
<div id="CommentLayer" style="display:none;position:absolute;left:0; top:0; width:200;height:70;z-index:100000;">
	<form name="CommentDeleteForm" method="post" action="">
	<input type="hidden" name="idx" value="">
	<table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" class="b_search_box" width="100%" height="100%">
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="120" align="right"><b>비밀번호</b></td>
						<td>&nbsp;<input type="password" name="cmt_u_pw" size="15" class=input></td>
						<td width="30" align="center" class=b_memo_title><a href="javascript:CommentDeleteGo();">OK</a></td>
					</tr>
					<tr>
						<td colspan="3" align="center" class=b_memo_title><a href="javascript:CommentLayerHidden();">CLOSE</a></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	</form>
</div>

<?}else{
//관리자 모드입니다.
?>

<form name="CommentForm" method="post" action="" onsubmit="return CommentNextGo();">
<input type="hidden" name="idx" value="">
<input type="hidden" name="category" value="<?=$category?>">
<input type="hidden" name="bc_id" value="<?=$bc_id?>">
<input type="hidden" name="seq" value="<?=$seq?>">
<input type="hidden" name="mode" value="insert">
<input type="hidden" name="cmt_u_id" value="<?=$print_u_id?>">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table align=center class="b_search_box" width="100%">
				<tr>
					<td style="padding:1 10 6 10">
						<table cellpadding=0 cellspacing=3 border=0 width="100%">
							<tr>
								<td valign="bottom" width=40 class=b_memo_title>&nbsp;Name</td>
								<td valign="bottom" width=60 valign=top><input type="text" name="cmt_u_name" <?=$print_u_name?> maxlength="20" style="width:60" class=input></td>

								<? if ( strlen($ss_u_id) > 0  ) { ?>
								<td valign="bottom" width=40 class=b_memo_title>&nbsp;</td>
								<td valign="bottom" width=60 valign=top>
									&nbsp;
								</td>
								<? } else { ?>
								<td valign="bottom" width=40 class=b_memo_title>&nbsp;Password</td>
								<td valign="bottom" width=60 valign=top>
									<input type="password" name="cmt_u_pw" maxlength="20" style="width:60px;" class=input>
								</td>
								<? } ?>
								<td>&nbsp;</td>
								<td></td>
							</tr>
							<tr>
								<td width=40 class=b_memo_title>&nbsp;Memo</td>
								<td valign=middle colspan="4"><textarea name="cmt_content" style="width:100%; height:50px;" class=textarea></textarea></td>
								<td width=60 align=center valign=middle><input type=image src="<?=$url_skin_comment?>default/images/btn_coment_write.gif"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td height=15></td>
	</tr>
</table>
</form>


<iframe id="CommentIframe" name="CommentIframe" frameborder="0" width="0" height="0"></iframe>
<div id="CommentLayer" style="display:none;position:absolute;left:0; top:0; width:200;height:70;z-index:100000;">
	<form name="CommentDeleteForm" method="post" action="">
	<input type="hidden" name="idx" value="">
	<table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" class="b_search_box" width="100%" height="100%">
		<tr>
			<td>
				<table border="0" cellspacing="0" cellpadding="0">
					<tr>
						<td width="120" align="right"><b>비밀번호</b></td>
						<td>&nbsp;<input type="password" name="cmt_u_pw" size="15" class=input></td>
						<td width="30" align="center" class=b_memo_title><a href="javascript:CommentDeleteGo();">OK</a></td>
					</tr>
					<tr>
						<td colspan="3" align="center" class=b_memo_title><a href="javascript:CommentLayerHidden();">CLOSE</a></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	</form>
</div>


<?}?>
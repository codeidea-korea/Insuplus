<?
include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
?>
<?
include_once($path_editor.'/func_editor.php');
	$content = "수정내용";
?>
<form action="sample_ok.php" method="post" name="add_form">
<?
	//echo myEditor('모드','에디터경로','폼이름','필드이름','폼사이즈','폼높이','랭귀지');
	echo myEditor(1, $path_editor, $url_editor,'add_form','content','100%','200','utf-8');
?>
<input type="button" value="글쓰기" onClick="editor_wr_ok();">
</form>
<?
	$dbcon -> dbcon_close();
?>

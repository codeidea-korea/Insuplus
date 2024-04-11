<script language="Javascript" src="/_util//spac_editor/spac_editor_common.js"></script>
<script language="Javascript" src="/_util//spac_editor/spac_editor.js"></script>
<link rel="stylesheet" type="text/css" href="/_util//spac_editor/spac_editor_common.css">
<link rel="stylesheet" type="text/css" href="/_util//spac_editor/spac_editor.css">
<?
		$content = str_replace("\r","<br/>",$content);
		$content = str_replace("\n","<br/>",$content);
?>
<script language="Javascript">createEditor('content','400','<?=stripslashes(str_replace("\r\n", "" , $content))?>','');</script>
<?php
include_once $_SERVER[DOCUMENT_ROOT]."/_config/lib.php";
$html = new html;
if (!$TempSelectIndex) $TempSelectIndex = 1;
?>
<script>
	function pageGo(val) {
		val = (Number(val)+1);
		if (val < 10) {
			val = "0"+val;
		}
		location.href="sub03_"+val+".html";
	}
</script>
<select onchange="pageGo(this.value)">
<?php echo $html->getSelectOptions($Arr[menu03],$TempSelectIndex);?>
</select>

<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크


// 날짜에 맞는 이벤트 로그 조회
if (!$_REQUEST["sdate"] && !$_REQUEST["edate"]){
	$ss_year		= date("Y");
	$ss_month		= date("m");
	$ss_day			= date("d");
	$es_year		= date("Y");
	$es_month		= date("m");
	$es_day			= date("d");
}else{
	$ss_year		= substr($_REQUEST["sdate"],0,4);
	$ss_month		= substr($_REQUEST["sdate"],4,2);
	$ss_day			= substr($_REQUEST["sdate"],6,2);
	$es_year		= substr($_REQUEST["edate"],0,4);
	$es_month		= substr($_REQUEST["edate"],4,2);
	$es_day			= substr($_REQUEST["edate"],6,2);
}

$s_date = $ss_year."-".$ss_month."-".$ss_day;
$e_date = $es_year."-".$es_month."-".$es_day;

$subquery = "&ev_code=".$ev_code."&sdate=".$_REQUEST["sdate"]."&edate=".$_REQUEST["edate"]."";

//검색
$search_col = "substr(u_regdate,1,10)";
$SQL = "select ".$search_col.",count(".$search_col.") as cnt from tbl_user where substr(u_regdate,1,10) between '".$s_date."' and '".$e_date."'  group by substr(u_regdate,1,10) order by substr(u_regdate,1,10) asc ";
//echo $SQL."<br/>";
$RS = $dbcon -> query($SQL);


// 배열변수에 담기
$k = 0;
while ( $row = $dbcon -> fetch_array($RS) ) {
	if (!$row[0]){
		$row[0] = "Unknown or Null";
	}
	$dt1[$k]	 = substr($row[0],5);
	$dt2[$k]	 = $row[1];
$k++;
}
?>

<!DOCTYPE html>
<html>
<link class="include" rel="stylesheet" type="text/css" href="/_util/chart/jquery.jqplot.min.css" />
<link rel="stylesheet" type="text/css" href="/_util/chart/examples/examples.min.css" />
<link type="text/css" rel="stylesheet" href="/_util/chart/examples/syntaxhighlighter/styles/shCoreDefault.min.css" />
<link type="text/css" rel="stylesheet" href="/_util/chart/examples/syntaxhighlighter/styles/shThemejqPlot.min.css" />
<!--[if lt IE 9]><script language="javascript" type="text/javascript" src="/_util/chart/excanvas.js"></script><![endif]-->
<script class="include" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script class="include" type="text/javascript" src="/_util/chart/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="/_util/chart/examples/syntaxhighlighter/scripts/shCore.min.js"></script>
<script type="text/javascript" src="/_util/chart/examples/syntaxhighlighter/scripts/shBrushJScript.min.js"></script>
<script type="text/javascript" src="/_util/chart/examples/syntaxhighlighter/scripts/shBrushXml.min.js"></script>
<!-- Additional plugins go here -->
<script class="include" type="text/javascript" src="/_util/chart/plugins/jqplot.barRenderer.min.js"></script>
<script class="include" type="text/javascript" src="/_util/chart/plugins/jqplot.pieRenderer.min.js"></script>
<script class="include" type="text/javascript" src="/_util/chart/plugins/jqplot.categoryAxisRenderer.min.js"></script>
<script class="include" type="text/javascript" src="/_util/chart/plugins/jqplot.pointLabels.min.js"></script>
<link rel="stylesheet" type="text/css" href="/_util/chart/jquery.jqplot.min.css" />
<!-- End additional plugins -->

<body>

<div id="chart1" style="height:300px; width:1000px;"></div>

<script class="code" type="text/javascript">
$(document).ready(function(){
	$.jqplot.config.enablePlugins = true;
	var s1 = [
<?
for ($i=0 ; $i<sizeof($dt1);$i++){
	echo $dt2[$i];
	if ($i>=sizeof($dt1)){
	}else{
	echo ",";
	}
}
?>
	];
	var ticks = [
<?
for ($i=0 ; $i<sizeof($dt1);$i++){
	echo "'".$dt1[$i]."'";
	if ($i>=sizeof($dt1)){
	}else{
	echo ",";
	}
}
?>
	];

	plot1 = $.jqplot('chart1', [s1], {
		// Only animate if we're not using excanvas (not in IE 7 or IE 8)..
		animate: !$.jqplot.use_excanvas,
		seriesDefaults:{
			renderer:$.jqplot.BarRenderer,
			pointLabels: { show: true }
		},
		axes: {
			xaxis: {
				renderer: $.jqplot.CategoryAxisRenderer,
				ticks: ticks
			}
		},
		highlighter: { show: false }
	});

	$('#chart1').bind('jqplotDataClick',
		function (ev, seriesIndex, pointIndex, data) {
			$('#info1').html('series: '+seriesIndex+', point: '+pointIndex+', data: '+data);
		}
	);
});
</script>

</body>
</html>

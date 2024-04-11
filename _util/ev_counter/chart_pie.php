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
<script class="include" language="javascript" type="text/javascript" src="/_util/chart/plugins/jqplot.pieRenderer.min.js"></script>
<script class="include" language="javascript" type="text/javascript" src="/_util/chart/plugins/jqplot.donutRenderer.min.js"></script>
<!-- End additional plugins -->

<body>

<div id="chart1" style="height:300px; width:500px;"></div>

<script class="code" type="text/javascript">
$(document).ready(function(){
var data = [
['Heavy Industry', 12],['Retail', 9], ['Light Industry', 14],
['Out of home', 16],['Commuting', 7], ['Orientation', 9]
];
var plot1 = jQuery.jqplot ('chart1', [data],
{
seriesDefaults: {
// Make this a pie chart.
renderer: jQuery.jqplot.PieRenderer,
rendererOptions: {
// Put data labels on the pie slices.
// By default, labels show the percentage of the slice.
showDataLabels: true
}
},
legend: { show:true, location: 'e' }
}
);
});
</script>

</body>
</html>

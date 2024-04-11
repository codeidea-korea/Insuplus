<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크
?>
<?
	$tm = "log";
	$lm = "";
	include $path_admin."inc/header.php";


// 이벤트 리스트 조회
$SQL = "select seq, subject from tbl_board_event where 1 order by seq desc";
//echo $SQL."<br/>";
$RS_ev = $dbcon -> query($SQL);

// 날짜에 맞는 이벤트 로그 조회
if (!$_REQUEST["sdate"] && !$_REQUEST["edate"]){
	$ss_year		= date("Y");
	$ss_month		= date("m");
	$ss_day			= date("d");
	$es_year		= date("Y");
	$es_month		= date("m");
	$es_day			= date("d");
	$sdate			= date("Ymd");
	$edate			= date("Ymd");
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

// 검색
$search_col = "substr(u_regdate,1,10)";
$SQL = "select ".$search_col.",count(".$search_col.") as cnt from tbl_user where substr(u_regdate,1,10) between '".$s_date."' and '".$e_date."'  group by substr(u_regdate,1,10) order by substr(u_regdate,1,10) asc";
//echo $SQL."<br/>";
$RS1 = $dbcon -> query($SQL);
?>

<style>
	.chart_table_title {
		color:#FF0000;
	}
	.chart_top_padding{
		margin-top:30px;
	}
</style>

<script language="javascript" type="text/javascript">
 $(document).ready(function() {

  //******************************************************************************
  // 상세검색 달력 스크립트
  //******************************************************************************
  var clareCalendar = {
   monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
   dayNamesMin: ['일','월','화','수','목','금','토'],
   weekHeader: 'Wk',
   dateFormat: 'yymmdd', //형식(20120303)
   autoSize: false, //오토리사이즈(body등 상위태그의 설정에 따른다)
   changeMonth: true, //월변경가능
   changeYear: true, //년변경가능
   showMonthAfterYear: true, //년 뒤에 월 표시
   showOtherMonths: true, // 다른달 보여주기
   selectOtherMonths: true, // 다른달 선택가능
   buttonImageOnly: true, //이미지표시
   buttonText: '달력선택', //버튼 텍스트 표시
   buttonImage: '/admin/images/a_btn_cal.gif', //이미지주소
   showOn: "both", //엘리먼트와 이미지 동시 사용(both,button)
   yearRange: '1990:2020' //1990년부터 2020년까지
  };
  $("#sdate").datepicker(clareCalendar);
  $("#edate").datepicker(clareCalendar);
  $("img.ui-datepicker-trigger").attr("style","margin-left:5px; vertical-align:middle; cursor:pointer;"); //이미지버튼 style적용
  $("#ui-datepicker-div").hide(); //자동으로 생성되는 div객체 숨김
 });
</script>

<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td width="16" height="22" valign="top" align="center" style="padding-top:6px"><img src="<?=$url_admin?>images/admin_st_ball.gif"></td>
		<td valign="top" class="a_st">회원가입 일자별 통계</td>
	</tr>
	<tr>
		<td colspan="2" height="1" bgcolor="#D5D5D5"></td>
	</tr>
	<tr>
		<td colspan="2" height="20">

		</td>
	</tr>
</table>

<form name="frm" method="get" action="<?=$PHP_SELF?>">
			<table width="100%" cellspacing="0" cellpadding="0" border="0" class="b_search_box">
				<tr>
					<td>
						<table cellpadding="0" cellspacing="3" border="0">
							<tr>
								<td width="4">&nbsp;</td>
								<td width="59" valign="top" style="padding:1 0 0 0"><img src="<?=$url_admin?>images/a_search_txt.gif" ></td>
								<td valign="top" width="330">
									<input type="text" name="sdate" id="sdate" value="<?=$sdate?>" class="input">~
									<input type="text" name="edate" id="edate" value="<?=$edate?>" class="input">
								</td>
								<td valign="top" style="padding:1 0 0 0"><input type="image" src="<?=$url_admin?>images/a_btn_search.gif"></td>
								<td width="4">&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
</form>

<iframe src="member_join_chart_line.php?ev_log=1<?=$subquery?>" name="chart1" id="chart1" width="100%" height="300" frameborder="0" scrolling="no"></iframe>

<table width="500" cellpadding="0" cellspacing="1" border="0" bgcolor="#000000">
	<tr style="background-color:#FFFFFF;">
		<th width="250">일자</th>
		<th>가입수</th>
	</tr>
<?
$k = 0;
while ( $row = $dbcon -> fetch_array($RS1) ) {?>
	<tr align="center" style="background-color:#FFFFFF;">
		<td><?=$row[0]?></td>
		<td><?=$row[1]?></td>
	</tr>
<?
$k++;
}?>
</table>



<? include $path_admin."inc/footer.php"; ?>


<? $dbcon -> dbcon_close();?>

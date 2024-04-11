<?
	$date_period = $_REQUEST["date_period"];
	$chk = $_REQUEST["chk"];
	$today = date("Y-m-d");
	$hour = date("H", strtotime("+1 hour"));
	$tomarrow =  date("Y-m-d", strtotime("+1 day"));
?>
<?if ($chk=="1"){?>
<input type="tel" name='s_date' id="s_date" placeholder="<?=$today;?>" class="form-control " size='10' readonly/><!-- datepicker -->
<script type="text/javascript">
<!--
	//검색 날짜제한
	$(document).ready(function () {
		
		$('#s_date').datepicker({
			dateFormat: "yy-mm-dd",
	//		startDate: '3w',	// 선택 할 수 있는 가장 빠른 날짜. 이전으로는 선택 불가능 ( d : 일 m : 달 y : 년 w : 주)
			closeText: "닫기",
			autoclose : true,
			prevText: '<i class="ti ti-angle-left"></i>',
			nextText: '<i class="ti ti-angle-right"></i>',
			navigationAsDateFormat:true,
			currentText: "오늘",
			monthNames: ["1월(JAN)","2월(FEB)","3월(MAR)","4월(APR)","5월(MAY)","6월(JUN)", "7월(JUL)","8월(AUG)","9월(SEP)","10월(OCT)","11월(NOV)","12월(DEC)"],
			monthNamesShort: ["1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월"],
			//dayNames: ["일","월","화","수","목","금","토"],
			//dayNamesShort: ["일","월","화","수","목","금","토"],
			dayNamesMin: ["일","월","화","수","목","금","토"],
			showOtherMonths:true,
			firstDay: 0,
			isRTL: false,
			showMonthAfterYear: true,
			yearSuffix: "",
			changeMonth: true,
			changeYear: true,
			showOn: 'both',
			buttonText: "<i class='fa fa-calendar'></i>",
			buttonImageOnly: false,
			showButtonPanel: false,
			zIndex:"2",
			minDate: 0,
		   onClose: function( selectedDate ) {
			   // 시작일(fromDate) datepicker가 닫힐때
			   // 종료일(toDate)의 선택할수있는 최소 날짜(minDate)를 선택한 시작일로 지정
			   $("#e_date").datepicker( "option", "minDate", selectedDate );

			   var date = $(this).datepicker('getDate');

			   date.setDate(date.getDate() + <?=$date_period?>); // Add 7 days
			   $('#e_date').datepicker("option", "maxDate", date); // Set as default
		   }
		});




	});
//-->
</script>
<?}?>

<?if ($chk=="2"){?>
<input type="tel" name='e_date' id="e_date" placeholder="<?=$tomarrow;?>" class="form-control  edate" size='10' readonly/><!-- datepicker -->
<script type="text/javascript">
<!--
	//검색 날짜제한
	$(document).ready(function () {



		//종료일
		$('#e_date').datepicker({
			dateFormat: "yy-mm-dd",
			closeText: "닫기",
			autoclose : true,
			prevText: '<i class="ti ti-angle-left"></i>',
			nextText: '<i class="ti ti-angle-right"></i>',
			navigationAsDateFormat:true,
			currentText: "오늘",
			monthNames: ["1월(JAN)","2월(FEB)","3월(MAR)","4월(APR)","5월(MAY)","6월(JUN)", "7월(JUL)","8월(AUG)","9월(SEP)","10월(OCT)","11월(NOV)","12월(DEC)"],
			monthNamesShort: ["1월","2월","3월","4월","5월","6월", "7월","8월","9월","10월","11월","12월"],
			//dayNames: ["일","월","화","수","목","금","토"],
			//dayNamesShort: ["일","월","화","수","목","금","토"],
			dayNamesMin: ["일","월","화","수","목","금","토"],
			showOtherMonths:true,
			firstDay: 0,
			isRTL: false,
			showMonthAfterYear: true,
			yearSuffix: "",
			changeMonth: true,
			changeYear: true,
			showOn: 'both',
			buttonText: "<i class='fa fa-calendar'></i>",
			buttonImageOnly: false,
			showButtonPanel: false,
			zIndex:"2",
			maxDate: 0, // 오늘 이후 날짜 선택 불가
			onClose: function( selectedDate ) {
				// 종료일(toDate) datepicker가 닫힐때
				// 시작일(fromDate)의 선택할수있는 최대 날짜(maxDate)를 선택한 종료일로 지정
				$("#s_date").datepicker( "option", "maxDate", selectedDate );
			}
		});


	});
//-->
</script>
<?}?>
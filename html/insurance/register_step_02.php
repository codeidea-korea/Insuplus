<?php
	include '../_include/_header.html';
	include '../_include/_top.html';
	include '../_include/_sidebar.html';
	
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php"; //추가
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/class.log.php"; //추가
	
?>
<?
//==========================================================
$PR_INFO = getInsuProductInfo($PR_SEQ); //상품정보
$chk_p = $PR_INFO["ext1"];
// 여행 변수 받음 처리
if ($_POST["s_date"] && $_POST["e_date"]){
	$s_date = $_POST["s_date"];					// 기간 시작
	$e_date = $_POST["e_date"];					// 기간 종료
	$s_date_time = $_POST["s_date_time"];		// 기간 시작 시간
	$e_date_time = $_POST["e_date_time"];		// 기간 종료 시간
	
	if($chk_p == "Y") { //단기
		$t_s_date = $s_date." ".$s_date_time;
		$t_e_date = $e_date." ".$e_date_time;
		
		$s_date_text = $t_s_date.":00";
		$e_date_text = $t_e_date.":00";
		
		$arr_period = getArrPeriod($t_s_date,$t_e_date,$chk_p); //기간구하기
		$period_day = $arr_period["day"];
		$period = $arr_period["day"];
		$period_month = $arr_period["month"];
	} else if($chk_p == "N") { //장기
		$t_s_date = $s_date;
		$t_e_date = $e_date;
		
		$s_date_text = $t_s_date;
		$e_date_text = $t_e_date;
		
		$arr_period = getArrPeriod($t_s_date,$t_e_date,$chk_p); //기간구하기
		$period_day = $arr_period["day"];
		$period = $arr_period["month"];
		$period_month = $arr_period["month"];
	}

	$gender	= $_POST["gender"];								// 가입자 성별
	$birth		= $_POST["birth"];							// 가입자 생일
	$purpose = $_POST["purpose"];						// 여행타입	
	
	$select_add_people = $_POST["select_add_people"];		// 동반인명수
	
	if ($select_add_people >0) {
		$arr_add_gender = array();
		$arr_add_birth = array();

		for($k=0;$k<count($_POST["add_birth"]);$k++){
			$arr_add_gender[] = $_POST["add_gender"][$k];									// 동반인 성별
			$arr_add_birth[] = $_POST["add_birth"][$k];														// 동반인 생일
		}
	}
} else {
	alert_page("올바른 경로로 이용해 주세요.","../main/index.php");
	exit;
}

if($chk_p == "Y") {
	if($arr_period["day"] > 90) {
		alert_page("가입기간은 90일까지 가입가능합니다.","./search_insur.php?PR_SEQ=".$PR_SEQ);
		exit;
	}
} else if($chk_p == "N") {
	if($arr_period["day"] > 365) {
		alert_page("가입기간은 365일까지 가입가능합니다.","./search_insur.php?PR_SEQ=".$PR_SEQ);
		exit;
	}
}

// 플랜검색
/*
$SQL_PLAN = "select * from tbl_board_plan where seq=".$plan_seq." and s_date<='".$s_date."' and e_date>='".$s_date."' and plan_status='Y' AND secret='Y' ";
$result_plan = $dbcon -> query($SQL_PLAN);
$row_plan = $dbcon -> fetch_array($result_plan);
*/
$plan_view  = selPlanView($plan_seq, $t_s_date, $t_e_date, $chk_p);


// 여행비용 검색
$user_age = fn_ins_age($birth);																		// 가입자 나이
$user_amt = fn_sel_ins_amt($period,$chk_p,$plan_seq,$user_age,$gender);		// 가입자 여행비용
$t_amt = 0;



for($k=0;$k<count($arr_add_birth);$k++){
//	echo $arr_add_birth[$k]."<br>";
	if ($arr_add_birth[$k]!=""){
	$add_user_age = fn_ins_age($arr_add_birth[$k]);
	$add_user_amt[$k] = fn_sel_ins_amt($period,$chk_p,$plan_seq,$add_user_age,$arr_add_gender[$k]);		// 동행자 여행비용
	$t_amt = $t_amt + $add_user_amt[$k];		// 동반인 여행보험비용
	}
}
$t_amt = $t_amt + $user_amt;							// 가입자 여행보험비용

// 여행 서비스 비용
$service_amt = fn_ins_service_amt($plan_seq,$plan_view["chk_service"],$period_month, $period_day);

//총괄 서비스 비용
$t_amt = $t_amt + ($service_amt * (1+count($add_user_amt)));
//==========================================================
?>
		<div class="breadcrumb-image" style="background-image:url('<?=$PR_INFO["imgfile"]?>')">
			<div class="container">
				<h2><?=$PR_INFO["subject"]?></h2>
				<h4><?=$PR_INFO["content"]?></h4>
			</div>
		</div>
		<div class="breadcrumb-wrap">
            <div class="container">
				<ol class="breadcrumb">
					<li><a href="../">InsuPlus HOME</a></li>
					<li><?=$PR_INFO["subject"]?></li>
				</ol>
            </div>
        </div>
		<div class="container sub-content sub-register">
			<div class="step_join">
				<? include './register_step_ui.php'; ?>
			</div>
			<div class='m-t-2 register-wrap'>			
				<div class='row-border'>
					<div class='detail-col-label'><strong class='text-black'>플랜명</strong></div>
					<div class='detail-col-input colspan-3'><strong class='text-black'><span><?=$Arr_plan_cd[$plan_view["plan_cd"]]?></span>&nbsp;<?=$PR_INFO["subject"]?></strong></div>
				</div>
				<form class='form-inline' method="post" name="frm2" autocomplete="off">
				<input type="hidden" name="PR_SEQ" value="<?=$PR_SEQ?>">
				<input type="hidden" name="plan_seq" value="<?=$plan_seq?>">
				<input type="hidden" name="s_date" value="<?=$s_date?>">
				<input type="hidden" name="s_date_time" value="<?=$s_date_time?>">
				<input type="hidden" name="e_date" value="<?=$e_date?>">
				<input type="hidden" name="e_date_time" value="<?=$e_date_time?>">
				<input type="hidden" name="gender" value="<?=$gender?>">
				<input type="hidden" name="birth" value="<?=$birth?>">
				<input type="hidden" name="purpose" value="<?=$purpose?>">
				<input type="hidden" name="t_amt" value="<?=$t_amt?>"><!-- 총금액 -->
				<input type="hidden" name="service_amt" value="<?=$service_amt?>"><!-- 개별 서비스 금액 -->
				<input type="hidden" name="select_add_people" value="<?=$select_add_people?>">
				<input type="hidden" name="joinType" value="<?=$joinType?>">
				<?if ($select_add_people >0){
				for($k=0;$k<count($_POST["add_birth"]);$k++){?>
				<input type="hidden" name="add_gender[]" value="<?=$_POST["add_gender"][$k]?>">
				<input type="hidden" name="add_birth[]" value="<?=$_POST["add_birth"][$k]?>">
				<?}}?>
					<div class='clearfix m-t-3 m-b-1'><h4 class='text-black'>가입자 정보 입력</h4></div>
					<div class='clearfix'>
						<div class='row-border'>
							<div class='detail-col-label'>이름</div>
							<div class='detail-col-input'><input type="text" name='user_name' placeholder="이름" class="form-control" size='20'  value='' /></div>
							<div class='detail-col-label'>주민등록번호</div>
							<div class='detail-col-input'>
								<div class='input-group'>
									<span class='input-group-addon'><?=substr($birth,2,6)?>-<?//fn_user_isdn_1($birth,$gender)?></span>
									<input type="tel" name='user_rnumber' placeholder="주민등록번호" class="form-control numberonly" size='12'  maxlength="7"/>
								</div>
							</div>
							<div class='detail-col-label'>휴대폰번호</div>
							<div class='detail-col-input'><input type="tel" name='user_hp' placeholder="휴대폰번호" class="form-control numberonly" size='20' /></div>
							<div class='detail-col-label'>이메일</div>
							<div class='detail-col-input'>
								<div class='input-group'>
									<input type="text" name='email' placeholder="" class="form-control radius" size='12' />
									<span class='input-group-addon'>@</span>
									<input type="text" name='email2' placeholder="" class="form-control" size='12' />
								</div>
								<select name="email_list" id="email_list" onchange="$('input[name=email2]').val(this.value)" class="form-control m-l-05 hidden-xs">
									<option value="" selected="">직접입력</option>
									<option value="naver.com">naver.com</option>
									<option value="chol.com">chol.com</option>
									<option value="dreamwiz.com">dreamwiz.com</option>
									<option value="empal.com">empal.com</option>
									<option value="freechal.com">freechal.com</option>
									<option value="gmail.com">gmail.com</option>
									<option value="hanafos.com">hanafos.com</option>
									<option value="hanmail.net">hanmail.net</option>
									<option value="hanmir.com">hanmir.com</option>
									<option value="hitel.net">hitel.net</option>
									<option value="hotmail.com">hotmail.com</option>
									<option value="korea.com">korea.com</option>
									<option value="lycos.co.kr">lycos.co.kr</option>
									<option value="nate.com">nate.com</option>
									<option value="netian.com">netian.com</option>
									<option value="paran.com">paran.com</option>
									<option value="yahoo.com">yahoo.com</option>
									<option value="yahoo.co.kr">yahoo.co.kr</option>
								</select>
							</div>
							<div class='detail-col-label'>방문국가</div>
							<div class='detail-col-input colspan-3 flex-wrap'>
								<div class='input-group m-r-1'>
									<input type="text" name='c_name' placeholder="방문국가" class="form-control" size='20' readonly/>
									<input type="hidden" name='c_code' placeholder="방문국가코드" class="form-control" size='20' />
									<span class='input-group-btn'><button type='button' class='btn btn-default' data-toggle='pop-modal' data-size='sm' data-href='./pop_search_country.php?pr_cd=<?=$PR_SEQ?>' data-title='방문국가 검색' target='modal_iframe'><span class='hidden-xs'>방문국가검색</span><i class='fa fa-search hidden visible-xs-inline'></i></button>
								</div>
								<div>※ 여러 국가를 방문하는 경우 <strong class='text-danger'>첫 번째</strong> 체류 국가를 선택해 주세요.</div>
							</div>
							<div class='detail-col-label'>영문가입증명서 <div class='hidden visible-xs'></div>발급 여부</div>
							<div class='detail-col-input colspan-3'>
								<div class='checkbox checkbox-inline'>
									<input type="checkbox" name='en_secur' id='en_secur' value="Y"/>
									<label for='en_secur'>예</label>
								</div>
								<input type="text" name='en_name' placeholder="영문명을 입력해주세요." class="form-control m-l-2 engonly" size='20' style="display:none;" />
								<div id="en_name2" style="display:none"><strong class='text-danger'>※ 영문만 입력 가능합니다.</strong></div>
							</div>
						</div>
					</div>
					<?
					if ($select_add_people >0){
					for($k=0;$k<count($_POST["add_gender"]);$k++){?>
					<div class='clearfix m-t-3 m-b-1'><h4 class='text-black'>동반인 정보 입력(<?=$k+1?>)</h4></div>
					<div class='clearfix'>
						<div class='row-border'>
							<div class='detail-col-label'>이름</div>
							<div class='detail-col-input'><input type="text" name='add_user_name[]' placeholder="이름" class="form-control" size='20' value='' /></div>
							<div class='detail-col-label'>주민등록번호</div>
							<div class='detail-col-input'>
								<div class='input-group'>
									<span class='input-group-addon'><?=substr($arr_add_birth[$k],2,6)?>-<?//fn_user_isdn_1($arr_add_birth[$k],$arr_add_gender[$k])?></span>
									<input type="tel" name='add_rnumber[]' placeholder="주민등록번호" class="form-control numberonly" size='12' maxlength="7"/>
								</div>
							</div>
							<div class='detail-col-label'>영문가입증명서 <div class='hidden visible-xs'></div>발급 여부</div>
							<div class='detail-col-input colspan-3'>
								<div class='checkbox checkbox-inline'>
									<input type="checkbox" name='add_en_secur[]' id='en_secur<?=$k+1?>' value="Y"/>
									<label for='en_secur<?=$k+1?>'>예</label>
								</div>
								<input type="text" name='add_en_name[]' placeholder="영문명을 입력해주세요." class="form-control m-l-2 engonly" size='20' style="display:none" />
								<div id="en_name3" style="display:none"><strong class='text-danger'>※ 영문만 입력 가능합니다.</strong></div>
							</div>
						</div>
					</div>
					<?}}?>
					<ul class='pagination arrow-only'>
						<li class='prev'><a href='javascript:history.back(-1);'>이전</a></li>
						<li class='next'><a href='javascript:chk_submit2();'>다음</a></li>
					</ul>
				</form>
			</div>
        </div>
<script type="text/javascript">

function chk_submit2(){
	var ff = document.frm2;
	
	if(!ff.user_name.value) {
		alert("가입자 이름을 입력해 주세요.");
		ff.user_name.focus();
		return;
	}

	if(!ff.user_rnumber.value) {
		alert("가입자 주민번호뒷자리를 입력해 주세요.");
		ff.user_rnumber.focus();
		return;
	}
	
	var join_jumin = $("input[name='birth']").val().substring(2,8)+""+ff.user_rnumber.value;
	var birth1 = $("input[name='birth']").val().substring(0,6);
	if(fnRRNCheck(birth1,join_jumin,'') ==false){
		ff.user_rnumber.focus();
        return false;
    }
    

	if(!ff.user_hp.value) {
		alert("가입자 휴대폰번호를 입력해 주세요.");
		ff.user_hp.focus();
		return;
	}

	if(!ff.email.value) {
		alert("가입자 이메일(1)을 입력해 주세요.");
		ff.email.focus();
		return;
	}

	if(!ff.email2.value) {
		alert("가입자 이메일(2)을 입력해 주세요.");
		ff.email2.focus();
		return;
	}
	
	if(!ff.c_code.value) {
		alert("가입자 방문국가를 선택해 주세요.");
		ff.c_code.focus();
		return;
	}

	if($("#en_secur").is(":checked")) {
		if(!ff.en_name.value) {
			alert("영문명을 입력해 주세요.");
			ff.en_name.focus();
			return en_name;
		}
	}

	<? for($k=1; $k <= $select_add_people; $k++) {?>
	if(!$("input[name='add_user_name[]']").eq(<?=$k-1?>).val()) {
		alert("동반인(<?=$k?>) 이름을 입력해 주세요.");
		$("input[name='add_user_name[]']").eq(<?=$k-1?>).focus();
		return; 
	}

	if(!$("input[name='add_rnumber[]']").eq(<?=$k-1?>).val()) {
		alert("동반인(<?=$k?>) 주민번호 뒷자리를 입력해 주세요.");
		$("input[name='add_rnumber[]']").eq(<?=$k-1?>).focus();
		return; 
	}
	
	var join_jumin = $("input[name='add_birth[]']").eq(<?=$k-1?>).val().substring(2,8)+""+$("input[name='add_rnumber[]']").eq(<?=$k-1?>).val();
	var birth2 = $("input[name='add_birth[]']").eq(<?=$k-1?>).val().substring(0,6);
	if(fnRRNCheck(birth2,join_jumin,<?=$k?>) ==false){
		$("input[name='add_rnumber[]']").eq(<?=$k-1?>).focus();
        return;
    }

	if($("#en_secur<?=$k?>").is(":checked")) {
		if(!$("input[name='add_en_name[]']").eq(<?=$k-1?>).val()) {
			alert("동반인(<?=$k?>) 영문명을 입력해 주세요.");
			$("input[name='add_en_name[]']").eq(<?=$k-1?>).focus();
			return; 
		}
	} 
	 
	<? } ?>

	

	ff.action="./register_step_03.php";
	ff.method="post";
	ff.submit();
}

function fnKoreaCheck(val) {
	 var chk = val;
     if(!chk.match(/^[a-zA-Z\s]+$/)) {
        err = 1;
     }

	if (err > 0) {
	    alert("영문만 입력가능합니다.");
	    return;
	}

	
}

function fnRRNCheck(birth, rrn, k) { // 유효성검사. 사용법fnRRNCheck("8201011234567");
	var txt = "";
    if(!k) {
		txt = "가입자"; 
    } else {
    	txt = "동반인("+k+")";
    }
	if(birth >= 202010) {	<?//2020. 10 부터 주민번호 뒷자리 6개(성별값 제외) 임의값으로 변경되어 자릿수만 확인함 2022.02.03.?>
		if (rrn.length === 13) {
			return true;
		} else {
			return false;
		}
	}
    if (fnrrnCheck(rrn) || fnfgnCheck(rrn)) {
        return true;
    } else {	
		alert(txt+" 주민등록번호 형식에 맞게 입력해주세요");	
    	return false;
	}
}

function fnrrnCheck(rrn) { // 주민등록번호유효성검사.
	var sum = 0;
	
    if (rrn.length != 13) {
        return false;
    } else if (rrn.substr(6, 1) != 1 && rrn.substr(6, 1) != 2 && rrn.substr(6, 1) != 3 && rrn.substr(6, 1) != 4) {
        return false;
	}

    for (var i = 0; i < 12; i++) {
        sum += Number(rrn.substr(i, 1)) * ((i % 8) + 2);
    }

    if (((11 - (sum % 11)) % 10) == Number(rrn.substr(12, 1))) {
        return true;
    }
    return false;
}

function fnfgnCheck(rrn) { // 외국인등록번호유효성검사.
	var buf = new Array(13);
    var sum = 0;
	
    if (rrn.length != 13) {
        return false;
    }

    else if (rrn.substr(6, 1) != 5 && rrn.substr(6, 1) != 6 && rrn.substr(6, 1) != 7 && rrn.substr(6, 1) != 8) {
        return false;
    }

    if (Number(rrn.substr(7, 2)) % 2 != 0) {
        return false;
    }

    for (var i = 0; i < 12; i++) {
        sum += Number(rrn.substr(i, 1)) * ((i % 8) + 2);
		buf[i] = ((i % 8) + 2);
    }

    if ((((11 - (sum % 11)) % 10 + 2) % 10) == Number(rrn.substr(12, 1))) {
        return true;
    }
    return false;
}

// function check_jumin(jumin,k) {
//     var jumins3 = jumin;
//       //주민등록번호 생년월일 전달
          
//       var fmt = RegExp(/^\d{6}[1234]\d{6}$/)  //포멧 설정
//       var buf = new Array(13);

// 	  var txt = "";
//       if(!k) {
// 			txt = "가입자"; 
//       } else {
//     	  txt = "동반인("+k+")";
//       }
 
//       //주민번호 유효성 검사
//       if (!fmt.test(jumins3)) {
//         alert(txt+" 주민등록번호 형식에 맞게 입력해주세요");
//         return false;
//       }
 
//       //주민번호 존재 검사
//       for (var i = 0; i < buf.length; i++){
//         buf[i] = parseInt(jumins3.charAt(i));
//       }
 
//       var multipliers = [2,3,4,5,6,7,8,9,2,3,4,5];// 밑에 더해주는 12자리 숫자들 
//       var sum = 0;
 
//       for (var i = 0; i < 12; i++){
//       sum += (buf[i] *= multipliers[i]);// 배열끼리12번 돌면서 
//     }
 
//     if ((11 - (sum % 11)) % 10 != buf[12]) {
//       alert(txt+" 올바른 주민등록번호를 입력해 주세요.");
//       return false;
//     }  
//}
//
</script>
<?php
	include '../_include/_tail.html';
	include '../_include/_footer.html';
?>
<script>
$(document).ready(function(){
	$("#en_secur").on("click",function(){
		if($("#en_secur").is(":checked")) {
			$("input[name='en_name']").show();
			$("input[name='en_name']").focus();
			$("#en_name2").show();
		} else {
			$("input[name='en_name']").hide();
			$("#en_name2").hide();
		}
	})

	<? for($k=1; $k <= $select_add_people; $k++) {?>
	$("#en_secur<?=$k?>").on("click",function(){
		if($("#en_secur<?=$k?>").is(":checked")) {
			$("input[name='add_en_name[]']").eq(<?=$k-1?>).show();
			$("input[name='add_en_name[]']").eq(<?=$k-1?>).focus();
			$("#en_name3").show();
		} else {
			$("input[name='add_en_name[]']").eq(<?=$k-1?>).hide();
			$("#en_name3").hide();
		}
	})
	<? } ?>
})
</script>


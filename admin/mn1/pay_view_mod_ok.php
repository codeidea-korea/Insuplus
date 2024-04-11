<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php";
	admin_chk($auth_admin, $url_admin_login_out);// 관리자 체크

	$parameter = "&pr_name=".$pr_name."&ins_name=".$ins_name."&plan_name=".$plan_name."&chk_service=".$chk_service."&search=".$search."&search_text=".$search_text."&search_orderby=".$search_orderby."&search_sort=".$search_sort."&num_per_page=".$num_per_page."&order_step=".$order_step."&search_date_txt=".$search_date_txt."&search_date_s=".$search_date_s."&search_date_e=".$search_date_e;

	if($mode=="refund") {
		// 기존 파일 가져오기
		$SQL = " select refund_file from tbl_order_list where orderno='".$orderno."' ";
		$FileRow = $dbcon -> fetch_array($dbcon -> query($SQL));
		$refund_file_old = $FileRow["refund_file"];

		$upload_path = $path_data."pay/";

		//파일 삭제
		if ( count($refund_file_del) > 0 ) {
			for ($i = 0; $i < count($refund_file_del); $i++ ) {
				if ( getLen($refund_file_del[$i]) > 0 ) {
					$SQL = "
						select * from tbl_file
						where idx = '".$refund_file_del[$i]."'
					";
					$rs = $dbcon -> fetch_array($dbcon -> query($SQL));

					DeleteFile($upload_path.$rs["file_name"]);

					$SQL = "
						delete from tbl_file
							where idx = '".$refund_file_del[$i]."'
					";

					$dbcon -> query($SQL);
				}
			}
		}
		#### 파일처리 Start
		$ObjFileName = "refund_file";
		${"Arr_".$ObjFileName} = setFileName(${$ObjFileName."_old"});

		if ( count(${$ObjFileName."_del"}) > 0 ) {
			for ($i = 0; $i < count(${$ObjFileName."_del"}); $i++ ) {
				if ( getLen(${$ObjFileName."_del"}[$i]) > 0 ) {
					DeleteFile($upload_path."/".${"Arr_".$ObjFileName}[$i][1]);
					${"Arr_".$ObjFileName}[$i] = "";
				}
			}
		}

		${"Result".$ObjFileName} = "";
		for ($i = 0; $i < count(${"Arr_".$ObjFileName}); $i++) {
			if ( is_array(${"Arr_".$ObjFileName}[$i]) ) {
				for ( $j = 0 ; $j < count(${"Arr_".$ObjFileName}[$i]) ; $j++ ) {
					${"Result".$ObjFileName} .= ${"Arr_".$ObjFileName}[$i][$j];
					if ($j < count(${"Arr_".$ObjFileName}[$i])-1) ${"Result".$ObjFileName} .=",";
				}
				if ($i < count(${"Arr_".$ObjFileName})-1) ${"Result".$ObjFileName} .="|";
			}
		}

		try {
			$upload = new upload($upload_path);
			$upload->define($_FILES[$ObjFileName],"b","1");
			$upload->uploadedFiles();
			//FileDBInsert("refund_file", $orderno);
			if(!is_array($upload->upload_tmpFileName)) {
			// 단일 파일 업로드 정보를 가져옴
			//	echo "단일 파일 업로드 정보를 가져옴<BR>";
				$fileDirectory = $upload->upload_directory;
				$fileSubDirectory = $upload->upload_subdirectory;
				$realfileName = $upload->upload_realfileName;
				$fileName = $upload->upload_fileName;
				$fileSize = $upload->upload_fileSize;
				$fileType = $upload->upload_fileType;
				$fileWidth = $upload->upload_fileWidth;
				$fileHeight = $upload->upload_fileHeight;
				$fileExt = $upload->upload_fileExt;

				if ( getLen($fileName) > 0 ) {
					$SQL = "
						insert into tbl_file set
							idx = null
							, category = 'refund_file'
							, bc_id = ''
							, seq = '$seq'
							, file_name = '$fileName'
							, file_realname = '$realfileName'
							, file_size = '$fileSize'
							, down_cnt = '0'
							, regdate = now()
							, topdata = ''
					";
					$dbcon -> query($SQL);
				}
			}
			${"temp".$ObjFileName} = getFileName();
		}
		catch(Exception $e) {
			// 에러처리 구문
			exit($e->getMessage());
		}

		if ( getLen(${"Result".$ObjFileName}) > 0 ) {
			if (getLen(${"temp".$ObjFileName}) > 0 ) {
				${"temp".$ObjFileName} = ${"Result".$ObjFileName}."|".${"temp".$ObjFileName};
			}
			else {
				${"temp".$ObjFileName} = ${"Result".$ObjFileName};
			}
		}
		${$ObjFileName} = ${"temp".$ObjFileName};
		
		// echo $upload_path."<BR>";
		// echo $refund_file."<BR>";
		// exit;
		#### 파일처리 End



		#### 환불금액 계산 start ####
		
		if($cancle_con && $refund_i_amount && $refund_s_amount){	//금액이 있을 경우 환불 상태로 변경 및 저장
			$SQL = "update tbl_order_list set ";
				$SQL .= " order_step = 'R' ";
				$SQL .= " ,cancle_con = '".str_replace(",", "", $cancle_con)."' ";
				$SQL .= " ,refund_date = '".$refund_date."'";
				$SQL .= " ,refund_i_amount = '".str_replace(",", "", $refund_i_amount)."'";
				$SQL .= " ,refund_s_amount = '".str_replace(",", "", $refund_s_amount)."'";
				$SQL .= " ,refund_file = '".$refund_file."'";
				$SQL .= "where orderno='".$orderno."'  ";
				$result = $dbcon -> query($SQL);
				
				$SQL = "update tbl_order_list_join set ";
				$SQL .= " join_status = 'R' ";
				$SQL .= " ,refund_i_amount = '".str_replace(",", "", $refund_i_amount)."'";
				$SQL .= " ,refund_s_amount = '".str_replace(",", "", $refund_s_amount)."'";
				$SQL .= " ,cancle_amount = '".str_replace(",", "", $cancle_con)."' ";
				$SQL .= "where orderno='".$orderno."'  ";
				$result = $dbcon -> query($SQL);
		} else {													//금액이 없을 경우 해지일 기준으로 환불금액 처리
			if($join_cnt > 1){										//동반인 가입자 환불 일괄처리
				$arr_add_gender = array();
				$arr_add_birth = array();
				$arr_add_birth = array();
				
				for($k=0;$k<5;$k++){
					if ($add_gender[$k] && $add_o_isdn1[$k]){
					$arr_add_gender[] = $add_gender[$k];									// 동반인 성별
					$arr_add_birth[] = $add_o_isdn1[$k];										// 동반인 생일
					}
				}
				
				$t_amt = 0;
				$ins_amt = 0;
				$service_amt = 0;
				for($k=0;$k<5;$k++){
					if ($arr_add_birth[$k]!=""){
						$datetime1 = date_create($arr_add_birth[$k]);
						//$datetime2 = date_create($refund_date);	//나이 계산 해지일 기준
						$datetime2 = date_create($writedate);		//나이 계산 가입일 기준
						$interval = $datetime1->diff($datetime2);
						$period_dt_y = $interval->format('%y');
						$period_dt_m = $interval->format('%m');
						$period_dt_d = $interval->format('%d');
						if ($period_dt_m>=6 ){ 
							$plus_age = 1;
						}else{
							$plus_age = 0;
						}
						$ins_age = $period_dt_y + $plus_age;

						if($chk_p == "Y") { //단기
							$t_s_date = $s_date." ".$s_date_time;
							$t_e_date = $refund_date." 23";
							$e_date_text = $t_e_date.":00";
						
							$arr_period = getArrPeriod($t_s_date,substr($t_e_date,0,13),$chk_p); //기간구하기
							$period_day = $arr_period["day"];
							$period = $arr_period["day"];
							$period_month = $arr_period["month"];
						} else if($chk_p == "N") { //장기
							$t_s_date = $s_date;
							$t_e_date = $refund_date;
							
							$s_date_text = $t_s_date;
							$e_date_text = $t_e_date." 00:00";
						
							$arr_period = getArrPeriod($t_s_date,$t_e_date,$chk_p); //기간구하기
							$period_day = $arr_period["day"];
							$period = $arr_period["month"];
							$period_month = $arr_period["month"];
						}

						$t_amt = fn_sel_ins_amt($period,$chk_p,$plan_cd,$ins_age,$arr_add_gender[$k]);	// 환불 보험료
						$s_amt = fn_ins_service_amt($plan_cd, $chk_service, $period_month, $period_day, $period, $chk_p, $ins_age, $arr_add_gender[$k]);	// 환불 서비스료
						

						$ins_amt = $ins_amt + $t_amt;	
						$service_amt = $service_amt + $s_amt;	

						if($add_t_amount[$k] >= $t_amt) {
							$r_amt = $add_t_amount[$k] - ($t_amt + $s_amt);
						} else {
							$r_amt = 0;
						}
						
						$SQL = "update tbl_order_list_join set ";
						$SQL .= " join_status = 'R' ";
						$SQL .= " ,refund_i_amount = '".$t_amt."'";
						$SQL .= " ,refund_s_amount = '".$s_amt."'";
						$SQL .= " ,cancle_amount = '".str_replace(",", "", $r_amt)."' ";
						$SQL .= "where orderno='".$orderno."' and o_isdn1 = '".all_seed_enc($add_o_isdn1[$k])."' and o_isdn2 = '".all_seed_enc($add_o_isdn2[$k])."' ";
						$result = $dbcon -> query($SQL);
					}
				}

				if($t_amount >= $ins_amt) {
					$refund_money = $t_amount - ($ins_amt + $service_amt);
				} else {
					$refund_money = 0;
				}

				/* 환불금액 자동 계산 수정전
				$SQL = "update tbl_order_list set ";
				$SQL .= " order_step = 'R' ";
				$SQL .= " ,cancle_con = '".$refund_money."' ";
				$SQL .= " ,refund_date = '".$e_date_text."'";
				$SQL .= "where orderno='".$orderno."'  ";
				*/

				$SQL = "update tbl_order_list set ";
				$SQL .= " order_step = 'R' ";
				$SQL .= " ,cancle_con = '".$refund_money."' ";
				$SQL .= " ,refund_date = '".$e_date_text."'";
				$SQL .= " ,refund_i_amount = '".$ins_amt."'";
				$SQL .= " ,refund_s_amount = '".$service_amt."'";
				$SQL .= " ,refund_file = '".$refund_file."'";
				$SQL .= "where orderno='".$orderno."'  ";
				$result = $dbcon -> query($SQL);
				
				

			} else {	//일괄처리
				$datetime1 = date_create($birth);
				//$datetime2 = date_create($refund_date);	//나이 계산 해지일 기준
				$datetime2 = date_create($writedate);		//나이 계산 가입일 기준
				$interval = $datetime1->diff($datetime2);
				$period_dt_y = $interval->format('%y');
				$period_dt_m = $interval->format('%m');
				$period_dt_d = $interval->format('%d');
				if ($period_dt_m>=6 ){ 
					$plus_age = 1;
				}else{
					$plus_age = 0;
				}
				$ins_age = $period_dt_y + $plus_age;

				if($chk_p == "Y") { //단기
					$t_s_date = $s_date." ".$s_date_time;
					$t_e_date = $refund_date." 23";
					$e_date_text = $t_e_date.":00";
				
					$arr_period = getArrPeriod($t_s_date,substr($t_e_date,0,13),$chk_p); //기간구하기
					$period_day = $arr_period["day"];
					$period = $arr_period["day"];
					$period_month = $arr_period["month"];
				} else if($chk_p == "N") { //장기
					$t_s_date = $s_date;
					$t_e_date = $refund_date;
					
					$s_date_text = $t_s_date;
					$e_date_text = $t_e_date." 00:00";
				
					$arr_period = getArrPeriod($t_s_date,$t_e_date,$chk_p); //기간구하기
					$period_day = $arr_period["day"];
					$period = $arr_period["month"];
					$period_month = $arr_period["month"];
				}

				$service_amt = fn_ins_service_amt($plan_cd, $chk_service, $period_month, $period_day, $period, $chk_p, $ins_age, $gender); // 환불 서비스료

				$ins_amt = fn_sel_ins_amt($period,$chk_p,$plan_cd,$ins_age,$gender);		// 환불 보험료

				if($t_amount >= $ins_amt) {
					$refund_money = $t_amount - ($ins_amt + $service_amt);
				} else {
					$refund_money = 0;
				}
				/* 환불금액 자동 계산 수정전
				$SQL = "update tbl_order_list set ";
				$SQL .= " order_step = 'R' ";
				$SQL .= " ,cancle_con = '".$refund_money."' ";
				$SQL .= " ,refund_date = '".$e_date_text."'";
				$SQL .= "where orderno='".$orderno."'  ";
				*/

				$SQL = "update tbl_order_list set ";
				$SQL .= " order_step = 'R' ";
				$SQL .= " ,cancle_con = '".$refund_money."' ";
				$SQL .= " ,refund_date = '".$e_date_text."'";
				$SQL .= " ,refund_i_amount = '".$ins_amt."'";
				$SQL .= " ,refund_s_amount = '".$service_amt."'";
				$SQL .= " ,refund_file = '".$refund_file."'";
				$SQL .= "where orderno='".$orderno."'  ";
				$result = $dbcon -> query($SQL);
				
				$SQL = "update tbl_order_list_join set ";
				$SQL .= " join_status = 'R' ";
				$SQL .= " ,refund_i_amount = '".$ins_amt."'";
				$SQL .= " ,refund_s_amount = '".$service_amt."'";
				$SQL .= " ,cancle_amount = '".str_replace(",", "", $refund_money)."' ";
				$SQL .= "where orderno='".$orderno."'  ";
				
				$result = $dbcon -> query($SQL);
			}
		}
	}

?>
<script type="text/javascript">
<!--
alert('환불처리 되었습니다.')
document.location.href="pay_view.php?orderno=<?=$orderno?><?=$parameter?>";
//-->
</script>
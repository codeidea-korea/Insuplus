<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.insurance.php"; //추가
	admin_chk($auth_admin, $url_admin_login_out); // 관리자 체크

    $dbcon = new dbcon;
    $dbcon->dbcon_open(0);
    @mysqli_query("set names utf8");
    $SC_Rows = getSiteConfig();
    extract($SC_Rows);
    unset($SC_Rows);

    mysqli_report(MYSQLI_REPORT_ALL ^ MYSQLI_REPORT_INDEX);
    $mysqli_db = $dbcon->dbcon;
    $mysqli_db->begin_transaction(); //트랜잭션 시작
	if ($_POST["mode"] == "write"){
		$parameter = "&pr_cd=".$pr_cd."&ins_cd=".$ins_cd."&plan_cd=".$plan_cd."&chk_service=".$chk_service."&search_text=".$search_text."&num_per_page=".$num_per_page."&search_date_s=".$search_date_s."&search_date_e=".$search_date_e;

		$orderStep = $group_join_status == "Y" ? "2" : "1";
		$PR_SEQ = $_POST["pr_cd"];
		$PR_INFO = getInsuProductInfo($PR_SEQ); //상품정보
		$chk_p = $PR_INFO["ext1"];
		$purpose = $PR_INFO["ext7"];
	
		$pr_cd = 				$_POST["pr_cd"];
		$ins_cd = 				$_POST["ins_cd"];
		$plan_cd = 				$_POST["plan_cd"];
		$plan_seq = 			$_POST["plan_seq"];
		$agree_cd = 			$_POST["agree_cd"];
		$chk_period = 			$_POST["chk_period"];
		$chk_service = 			$_POST["chk_service"];
		$service_cd = 			$_POST["service_cd"];
		$stock_isdn = 			$_POST["stock_isdn"];
		$pr_name = 				$_POST["pr_name"];
		$ins_plan_name = 		$_POST["ins_plan_name"];
		$ins_name = 			$_POST["ins_name"];
		$group_join_type = 		$_POST["group_join_type"];
		if($group_join_type == "B2C"){
			$o_name = 			$_POST["o_name_b2c"];
			$o_name_en = 		$_POST["o_name_en_b2c"];
			$birthdate = 		$_POST["birthdate"];
            $grp_cd =           "P0000000001";
		} else {
			$o_name = 			$_POST["o_name_b2b"];
			$o_name_en = 		$_POST["o_name_en_b2b"];
			$grp_cd = 			$_POST["grp_cd"];
			$biz_num = 			$_POST["biz_num"];
			$client_id = 		$_POST["client_id"];
		}
		$email = 				$_POST["email"];
		$o_phone =				$_POST["o_phone"];

		$SQL  = "INSERT into tbl_order_group_join_list set ";
		$SQL .= "o_name = '".all_seed_enc($o_name)."'";						//대표자명
		$SQL .= ", o_name_en = '".all_seed_enc($o_name_en)."'";						//대표자명
		$SQL .= ", o_phone = '".all_seed_enc($o_phone)."'";					//연락처
		if ($group_join_type == "B2C") {							//가입구분
			$SQL .= ", birthdate = '".$birthdate."'";						//생년월일
		} else {
			$SQL .= ", biz_num = '".$biz_num."'";							//사업자번호
			$SQL .= ", grp_cd = '".$grp_cd."'";								//프로젝트 아이디
			$SQL .= ", client_id = '".$client_id."'";						//계약업체 아이디
		}
		$SQL .= ", o_email = '".all_seed_enc($email)."'";					//이메일
		$SQL .= ", group_join_type = '".$group_join_type."'";				//단체 가입구분
		$SQL .= ", pr_cd = '".$pr_cd."'";									//상품코드
		$SQL .= ", ins_cd = '".$ins_cd."'";									//보험사코드
		$SQL .= ", plan_cd = '".$plan_seq."'";								//플랜코드
		$SQL .= ", group_join_status = 'W'";								//단체 가입 상태 Y: 가입, W: 입금대기, N: 견적
		$SQL .= ", regdate = now(); ";
        $result = $dbcon->query($SQL);
		$SQL = "SELECT LAST_INSERT_ID();";
		$seq = $dbcon -> query($SQL);
		$insert_seq = $dbcon -> fetch_array($seq);

		$url = "";
		error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_USER_DEPRECATED);
		include $_SERVER["DOCUMENT_ROOT"]."/_util/PHPExcel-1.8/Classes/PHPExcel.php";

		if ($_FILES["file1"]){
			// 파일이 있는지 확인후 복사
			if($_FILES["file1"]["name"]) {

				$filename = $_FILES["file1"]["name"];
				$tmp_file = $_FILES["file1"]["tmp_name"];
				$filesize = $_FILES["file1"]["size"];

				$UpFilePathInfo = pathinfo($filename);
				$UpFileExt = strtolower($UpFilePathInfo["extension"]);

				//  확장자 체크 : csv파일이 아니면 history(-1)
				$file_info = explode(".", $filename);
				$filename = "xls_group_join_upload".date("YmdHis",time()).".".$UpFileExt;

				@move_uploaded_file($tmp_file, $_SERVER["DOCUMENT_ROOT"]."/_data/group_join/$filename");   //파일복사
				@unlink($tmp_file);

				$url = $_SERVER["DOCUMENT_ROOT"]."/_data/group_join/".$filename;
				
				//파일 타입 설정 (확자자에 따른 구분)
				$inputFileType = 'Excel2007';
				if($file_info[1] == "xls") {
					$inputFileType = 'Excel5';
				}
				//==================================================================
				//  PHPEXCEL 용 로더
				//==================================================================
				//엑셀리더 초기화
				$objReader = PHPExcel_IOFactory::createReader($inputFileType);
				//데이터만 읽기(서식을 모두 무시해서 속도 증가 시킴)
				$objReader->setReadDataOnly(true);
				//범위 지정(위에 작성한 범위필터 적용)
				//	$objReader->setReadFilter($filterSubset);
				//업로드된 엑셀 파일 읽기
				//	$objPHPExcel = $objReader->load($url);
				$objPHPExcel = PHPExcel_IOFactory::load($url);
				//첫번째 시트로 고정
				$objPHPExcel->setActiveSheetIndex(0);
				//고정된 시트 로드
				$objWorksheet = $objPHPExcel->getActiveSheet();
				$cellIterator = $objWorksheet->getRowIterator()->current()->getCellIterator();
				$cellIterator->setIterateOnlyExistingCells( true );
				$maxRow = $objWorksheet->getHighestRow();
				$PLAN_DATA = array();
                $GROUP_INFO_TABLE = array();
				$GROUP_INFO_TABLE["chk_p"] = $chk_p;
				$gender = "";
				$period = "";
                try{

                    for($i=2;$i<=$maxRow;$i++) {
                        $nullCheck = str_replace("\"","",$objWorksheet->getCell('A' . $i)->getValue());
                        if($nullCheck != '' && !is_null($nullCheck)){
                            $GROUP_INFO_TABLE["ins_user_num"] = str_replace("\"","",$objWorksheet->getCell('A' . $i)->getValue());
                            $GROUP_INFO_TABLE["o_name"] = str_replace("\"","",$objWorksheet->getCell('B' . $i)->getValue());
                            $GROUP_INFO_TABLE["o_name_en"] = str_replace("\"","",$objWorksheet->getCell('C' . $i)->getValue());
        
                            $isdn = str_replace("\"","",$objWorksheet->getCell('D' . $i)->getValue());
                            $o_isdn = explode('-', $isdn);
        
                            if ($o_isdn[0] && $o_isdn[1]) {
                                // 주민등록번호의 7번째 한자리 숫자
                                $TempBirthYear = substr($o_isdn[0], 0, 2);
                                $TempBirthMonth = substr($o_isdn[0], 2, 2);
                                $TempBirthDay = substr($o_isdn[0], 4, 2);
                                $TempBirthGubun = substr($o_isdn[1], 0, 1);
        
                                if ($TempBirthGubun == 9 || $TempBirthGubun == 0) $TempBirthYear = "18" . $TempBirthYear;
                                else if ($TempBirthGubun == 1 || $TempBirthGubun == 2 || $TempBirthGubun == 5 || $TempBirthGubun == 6) $TempBirthYear = "19" . $TempBirthYear;
                                else if ($TempBirthGubun == 3 || $TempBirthGubun == 4 || $TempBirthGubun == 7 || $TempBirthGubun == 8) $TempBirthYear = "20" . $TempBirthYear;

                                $u_birth_year = $TempBirthYear;
                                $u_birth_month = $TempBirthMonth;
                                $u_birth_day = $TempBirthDay;
                                $birth = $u_birth_year.$u_birth_month.$u_birth_day;
        
                                // 성별은 F, M 으로 나눈다.
                                // 주민등록번호의 7번째 자리가 홀수이면 남자(Male), 짝수이면 여자(Female)
                                $gender = $TempBirthGubun % 2 == 0 ? "F" : "M";
        
                            }
                            $GROUP_INFO_TABLE["o_isdn1"] = $o_isdn[0];
                            $GROUP_INFO_TABLE["o_isdn2"] = $o_isdn[1];
                            
                            $start = explode(' ', str_replace("\"","",$objWorksheet->getCell('E' . $i)->getValue()));
                            $s_date = $start[0];
                            $s_date_time = $start[1];
                            $end = explode(' ', str_replace("\"","",$objWorksheet->getCell('F' . $i)->getValue()));
                            $e_date = $end[0];
                            $e_date_time = $end[1];
                            $GROUP_INFO_TABLE["s_date"] = str_replace("\"","",$objWorksheet->getCell('E' . $i)->getValue());
                            $GROUP_INFO_TABLE["e_date"] = str_replace("\"","",$objWorksheet->getCell('F' . $i)->getValue());
                            
                            //방문국가 정보 조회
                            $GROUP_INFO_TABLE["join_nation_name"] = str_replace("\"","",$objWorksheet->getCell('G' . $i)->getValue());
                            $SQL_N = "select c_code, c_name from tbl_board_product_country where pr_seq='".$PR_SEQ."' and c_name='".$GROUP_INFO_TABLE["join_nation_name"][$i-2]."'";
                            $row = $dbcon -> query($SQL_N);
                            $nation = $dbcon -> fetch_array($row);
                            $GROUP_INFO_TABLE["join_nation_cd"] = $nation["c_code"];
        
                            $tmpEmail = explode("@", str_replace("\"","",$objWorksheet->getCell('H' . $i)->getValue()));
                            $GROUP_INFO_TABLE["email"] = $tmpEmail[0];
                            $GROUP_INFO_TABLE["email2"] = $tmpEmail[1];
                            $GROUP_INFO_TABLE["o_phone"] = str_replace("\"","",$objWorksheet->getCell('I' . $i)->getValue());
                            $GROUP_INFO_TABLE["age"] = fn_ins_age(date("Y-m-d",strtotime($birth))); //보험나이
                            // $GROUP_INFO_TABLE["gender"] = $Arr_u_sex[$gender];
                            $GROUP_INFO_TABLE["gender"] = $gender;
                            
                            $writeDate = str_replace("\"","",$objWorksheet->getCell('J' . $i)->getValue());
                            $GROUP_INFO_TABLE["writedate"] = PHPExcel_Shared_Date::ExcelToPHPObject($writeDate)->format('Y-m-d H:i:s');
        
                            if($chk_p == "Y") { //단기
                                $t_s_date = $GROUP_INFO_TABLE["s_date"];
                                $t_e_date = $GROUP_INFO_TABLE["e_date"];
                                $arr_period = getArrPeriod($t_s_date,$t_e_date,$chk_p);
                                if($arr_period["day"] > 90) {
                                    alert_page("가입기간은 90일까지 가입가능합니다.","./search_group_join.php?PR_SEQ=".$PR_SEQ);
                                    exit;
                                }
                                $GROUP_INFO_TABLE["period_day"] = $arr_period["day"];
                                $GROUP_INFO_TABLE["period"] = $arr_period["day"];
                                $GROUP_INFO_TABLE["period_month"] = $arr_period["month"];
                            } else { //장기
                                $t_s_date = explode( ' ', $GROUP_INFO_TABLE["s_date"]);
                                $t_e_date = explode( ' ', $GROUP_INFO_TABLE["e_date"]);
                                $arr_period = getArrPeriod($t_s_date[0],$t_e_date[0],$chk_p);
                                if($arr_period["day"] > 365) {
                                    alert_page("가입기간은 365일까지 가입가능합니다.","./search_group_join.php?PR_SEQ=".$PR_SEQ);
                                    exit;
                                }
                                $GROUP_INFO_TABLE["period_day"] = $arr_period["day"];
                                $GROUP_INFO_TABLE["period"] = $arr_period["month"];
                                $GROUP_INFO_TABLE["period_month"] = $arr_period["month"];
                            }
        
                            //가입자
                            if($chk_p == "Y") { //기간을 구간으로 변경
                                $period_sel= fnShortTermSection($GROUP_INFO_TABLE["period"]);
                            } else {
                                $period_sel = $GROUP_INFO_TABLE["period_month"];
                            }
        
                            // $sql_ins_amount  = " SELECT period". $period_sel." FROM tbl_board_plan_amount1 WHERE plan_cd = '".$plan_seq."' ";
                            // $sql_ins_amount .= " AND age = '".$GROUP_INFO_TABLE["age"]."' AND gender = '".$GROUP_INFO_TABLE["gender"]."' ";
                            // $rs_ins_amount = $dbcon->query($sql_ins_amount);
                            // $row_ins_amount = $dbcon->fetch_array($rs_ins_amount);

                            //가입자 보험료 계산 변경 2023.11.02
                            $ins_amount = fn_sel_ins_amt($GROUP_INFO_TABLE["period"],$chk_p,$plan_seq,$GROUP_INFO_TABLE["age"],$GROUP_INFO_TABLE["gender"]);		// 가입자 여행비용

                            $ROW["reason_txt"] = ""; //가입불가 이유
                            $ROW["joinChk"] = "Y"; //가입여부

                            //서비스료 변경 2023.11.02
                            $service_amount = fn_ins_service_amt($plan_seq, $chk_service, $GROUP_INFO_TABLE["period_month"], $GROUP_INFO_TABLE["period_day"], $GROUP_INFO_TABLE["period"], $chk_p, $GROUP_INFO_TABLE["age"], $GROUP_INFO_TABLE["gender"]);

                            // if($chk_service != "N") {
                            //     //일할 계산 처리를 위하여 함수 처리 2023.08.24
                            //     $service_amount = fn_ins_service_amt($plan_seq, $chk_service, $GROUP_INFO_TABLE["period_month"], $GROUP_INFO_TABLE["period"]);
                            //     /*
                            //     $sql_service_amount  = " SELECT mon".$GROUP_INFO_TABLE["period_month"]." FROM tbl_board_plan_amount2 WHERE plan_cd = '".$plan_seq."' ";
                            //     $sql_service_amount .= " AND stype = '".$chk_service."' ";
                            //     $rs_service_amount = $dbcon->query($sql_service_amount);
                            //     $row_service_amount = $dbcon->fetch_array($rs_service_amount);
                                
                            //     $service_amount = $row_service_amount["mon".$GROUP_INFO_TABLE["period_month"]]; //서비스료
                            //     */
                            // }
                            
                            $t_service_temp_amt = $service_amount;		// 과세대상 결제총액 = 서비스료 - 할인금액
                            $value_of_supply = round($t_service_temp_amt / 1.1);	//공급가액 (서비스 과세금액)  과세대상 결제총액 / 1.1
                            //VAT
                            $vat_amt = $t_service_temp_amt - $value_of_supply; 		//부가세 = 과세대상 결제총액 - 공급가액
        
                            $tot_amount = $ins_amount+$service_amount; //총금액
                            $s_amt_per = 0;
                            $s_amount = 0;
                            
                            $usr_s_amount = 0;			// 가입자 할인금액
                            $usr_vat_amount = 0;			// 개별 VAT, 소수점 버림
                            $usr_t_amount =	$ins_amount + $service_amount - $usr_s_amount;	// 가입자 결제금액
                            $GROUP_INFO_TABLE["total_amount"] = $GROUP_INFO_TABLE["total_amount"]+$usr_t_amount;
        
                            require_once('../../html/insurance/libs/INIStdPayUtil.php');
                            $SignatureUtil = new INIStdPayUtil();
                            $orderNumber = "P_" .date("YmdHis").$SignatureUtil->getTimestamp(); // 가맹점 주문번호(가맹점에서 직접 설정)
        
                            $rule_site_cd = selRuleSeq("사이트 이용약관");
                            $rule_group_cd = selRuleSeq("단체보험 규약");
                            $rule_privacy_cd = selRuleSeq("개인정보 수집 및 이용 동의");
                            $arr_ins_agree_file = selInsAgreeFile($agree_cd);
        
                            //데이터 입력 - 주문
                            $select_add_people = $select_add_people +1;		//가입자 본인 추가
                            $SQL1 = "insert into tbl_order_list set";
                            $SQL1 .= " orderno = '".$orderNumber."' ";
                            $SQL1 .= ", pr_name = '".print_pr_name($PR_SEQ)."' ";
                            $SQL1 .= ", pr_cd = '".$PR_SEQ."' ";
                            $SQL1 .= ", ins_name = '".print_ins($ins_cd)."' ";
                            $SQL1 .= ", ins_cd = '".$ins_cd."' ";
                            $SQL1 .= ", plan_name = '".$ins_plan_name." - ".$Arr_plan_cd[$plan_cd]."' ";
                            $SQL1 .= ", plan_cd = '".($plan_seq > 0 ? $plan_seq : 0)."' ";
                            $SQL1 .= ", agree_cd = '".($PR_INFO["ext4"] > 0 ? $PR_INFO["ext4"] : 0)."' ";
                            $SQL1 .= ", service_name = '".print_insu_service($PR_INFO["ext5"])."' ";
                            $SQL1 .= ", service_cd = '".($PR_INFO["ext5"] > 0 ?$PR_INFO["ext5"] : 0)."' ";
                            $SQL1 .= ", rule_site_cd = '".($rule_site_cd > 0 ? $rule_site_cd : 0)."' ";
                            $SQL1 .= ", rule_group_cd = '".($rule_group_cd > 0 ? $rule_group_cd : 0)."' ";
                            $SQL1 .= ", rule_privacy_cd = '".($rule_privacy_cd > 0 ? $rule_privacy_cd : 0)."' ";
                            $SQL1 .= ", ins_file_cd = '".($agree_cd > 0 ? $agree_cd : 0)."' ";
                            $SQL1 .= ", service_file_cd = '".($service_cd > 0 ? $service_cd : 0)."' ";
                            $SQL1 .= ", s_date = '".$s_date."' ";
                            $SQL1 .= ", s_date_time = '".$s_date_time."' ";
                            $SQL1 .= ", e_date = '".$e_date."' ";
                            $SQL1 .= ", e_date_time = '".$e_date_time."' ";
                            if ($chk_p == "Y") { //단기
                                $SQL1 .= ", ins_period = '" . $GROUP_INFO_TABLE["period"] . "' ";
                            } else {
                                $SQL1 .= ", ins_period = '" . $GROUP_INFO_TABLE["period_month"] . "' ";
                            }
                            $SQL1 .= ", chk_p = '".$chk_p."' ";
                            $SQL1 .= ", chk_service = '".$chk_service."' ";
                            $SQL1 .= ", o_name = '".all_seed_enc($GROUP_INFO_TABLE["o_name"])."' ";
                            $SQL1 .= ", o_email1 = '".all_seed_enc($GROUP_INFO_TABLE["email"])."' ";
                            $SQL1 .= ", o_email2 = '".all_seed_enc($GROUP_INFO_TABLE["email2"])."' ";
                            $SQL1 .= ", join_cnt = '1' ";
                            $SQL1 .= ", join_ch = '' ";
                            $SQL1 .= ", purpose = '".$purpose."' ";
                            $SQL1 .= ", join_nation_cd = '".$GROUP_INFO_TABLE["join_nation_cd"]."' ";
                            $SQL1 .= ", join_nation_name = '".$GROUP_INFO_TABLE["join_nation_name"]."' ";
                            $SQL1 .= ", order_step = '".$orderStep."' ";
                            $SQL1 .= ", sale_gubun = '' ";
                            $SQL1 .= ", sale_discount = '0' ";
                            $SQL1 .= ", cp_cd = '0' ";
                            $SQL1 .= ", recommend_cd = '0' ";
                            $SQL1 .= ", ins_amount = '".($ins_amount > 0 ? $ins_amount : 0)."' ";
                            $SQL1 .= ", service_amount = '".($service_amount > 0 ? $service_amount : 0)."' ";
                            $SQL1 .= ", s_amount = '0' ";
                            $SQL1 .= ", vat_amount = '".$vat_amt."' ";
                            $SQL1 .= ", t_amount = '".$tot_amount."' ";
                            $SQL1 .= ", cancle_amount = '0' ";
                            $SQL1 .= ", group_join_id = '".$insert_seq[0]."' ";
                            $SQL1 .= ", pg_id='' ";
                            $SQL1 .= ", pg_pay_type='' ";
                            $SQL1 .= ", pg_isdn='' ";
                            $SQL1 .= ", pay_name='' ";
                            $SQL1 .= ", writedate = '".$GROUP_INFO_TABLE["writedate"]."' ";
                            $RS1 = $dbcon->query($SQL1);
                                
                            //데이터 입력 - 가입자
                            $SQL2 = "insert into tbl_order_list_join set";
                            $SQL2 .= " orderno = '".$orderNumber."' ";
                            $SQL2 .= ", gender = '".$gender."' ";
                            $SQL2 .= ", chk_join = 'N' ";
                            $SQL2 .= ", chk_eng_passport = '' ";
                            $SQL2 .= ", o_name = '".all_seed_enc($GROUP_INFO_TABLE["o_name"])."' ";
                            $SQL2 .= ", o_phone = '".all_seed_enc($GROUP_INFO_TABLE["o_phone"])."' ";
                            $SQL2 .= ", o_isdn1 = '".all_seed_enc($birth)."' ";
                            $SQL2 .= ", o_isdn2 = '".all_seed_enc($GROUP_INFO_TABLE["o_isdn2"])."' ";
                            $SQL2 .= ", o_name_en = '".all_seed_enc($GROUP_INFO_TABLE["o_name_en"])."' "; 
                            $SQL2 .= ", ins_plan_cd = '".$plan_seq."' ";
                            $SQL2 .= ", join_amount = '".($ins_amount > 0 ? $ins_amount : 0)."' ";
                            $SQL2 .= ", join_service = '".($service_amount > 0 ? $service_amount : 0)."' ";
                            $SQL2 .= ", vat_amount = '".$usr_vat_amount."' ";
                            $SQL2 .= ", s_amount = '".$usr_s_amount."' ";
                            $SQL2 .= ", t_amount = '".$usr_t_amount."' ";
                            $SQL2 .= ", join_status = 'W' ";
                            $SQL2 .= ", group_join_type = '".$group_join_type."' ";
                            $SQL2 .= ", project_cd = '".$grp_cd."' ";
                            $SQL2 .= ", group_join_id = '".$insert_seq[0]."' ";
                            $SQL2 .= ", ins_user_num = '".$GROUP_INFO_TABLE["ins_user_num"]."' ";
                            $SQL2 .= ", regdate = '".$GROUP_INFO_TABLE["writedate"]."' ";
                            $RS2 = $dbcon -> query($SQL2);
                        }
                    }
                } catch (mysqli_sql_exception $e) {
                    $mysqli_db->rollback(); //롤백
                    echo $i."번째 줄을 확인해 주세요.";
                    echo $e->getMessage();
                    // 가입자 정보 삭제가 필요하면 주석 풀어서 사용
                    // $DQL_D1 = " DELETE FROM tbl_order_list where group_join_id ='" . $group_join_id . "' ";
                    // $result1 = $dbcon->query($DQL_D1);
                    // $DQL_D2 = " DELETE FROM tbl_order_list_join where group_join_id ='" . $group_join_id . "' ";
                    // $result2 = $dbcon->query($DQL_D2);
                    throw $e;
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
				
				$SQL_A = "";
				$SQL_A  = " UPDATE tbl_order_group_join_list SET";
				$SQL_A .= " total_amount = '".($GROUP_INFO_TABLE["total_amount"] > 0 ? $GROUP_INFO_TABLE["total_amount"] : 0)."' ";
				$SQL_A .= ", group_join_cnt = '".($i-2)."' ";
				$SQL_A .= ", memberfile = '".$url."' ";
				$SQL_A .= " WHERE group_join_id = '".$insert_seq[0]."' ";
				$dbcon -> query($SQL_A);

			}
		}
	} else if($_POST["mode"] == "mod"){
		$parameter = "&group_join_id=".$group_join_id."&pr_cd=".$pr_cd."&ins_cd=".$ins_cd."&plan_cd=".$plan_cd."&chk_service=".$chk_service."&search_text=".$search_text."&num_per_page=".$num_per_page."&search_date_s=".$search_date_s."&search_date_e=".$search_date_e;

		$orderStep = $group_join_status == "Y" ? "2" : "1";
		$PR_SEQ = $_POST["pr_cd"];
		$PR_INFO = getInsuProductInfo($PR_SEQ); //상품정보
		$chk_p = $PR_INFO["ext1"];
		$purpose = $PR_INFO["ext7"];
	
		$pr_cd = 				$_POST["pr_cd"];
		$ins_cd = 				$_POST["ins_cd"];
		$plan_cd = 				$_POST["plan_cd"];
		$plan_seq = 			$_POST["plan_seq"];
		$agree_cd = 			$_POST["agree_cd"];
		$chk_period = 			$_POST["chk_period"];
		$chk_service = 			$_POST["chk_service"];
		$service_cd = 			$_POST["service_cd"];
		$stock_isdn = 			$_POST["stock_isdn"];
		$pr_name = 				$_POST["pr_name"];
		$ins_plan_name = 		$_POST["ins_plan_name"];
		$ins_name = 			$_POST["ins_name"];
		$group_join_type = 		$_POST["group_join_type"];
		if($group_join_type == "B2C"){
			$o_name = 			$_POST["o_name_b2c"];
			$o_name_en = 		$_POST["o_name_en_b2c"];
			$birthdate = 		$_POST["birthdate"];
            $grp_cd =           "P0000000001";
		} else {
			$o_name = 			$_POST["o_name_b2b"];
			$o_name_en = 		$_POST["o_name_en_b2b"];
			$grp_cd = 			$_POST["grp_cd"];
			$biz_num = 			$_POST["biz_num"];
			$client_id = 		$_POST["client_id"];
		}
		$email = 				$_POST["email"];
		$o_phone =				$_POST["o_phone"];

		$SQL_U = "UPDATE tbl_order_group_join_list SET ";
		$SQL_U .= "o_name = '".all_seed_enc($o_name)."'";						//대표자명
		$SQL_U .= ", o_name_en = '".all_seed_enc($o_name_en)."'";						//대표자명
		$SQL_U .= ", o_phone = '".all_seed_enc($o_phone)."'";					//연락처
		if ($group_join_type == "B2C") {							//가입구분
			$SQL_U .= ", birthdate = '".$birthdate."'";						//생년월일
		} else {
			$SQL_U .= ", biz_num = '".$biz_num."'";							//사업자번호
			$SQL_U .= ", grp_cd = '".$grp_cd."'";								//프로젝트 아이디
			$SQL_U .= ", client_id = '".$client_id."'";						//계약업체 아이디
		}
		$SQL_U .= ", o_email = '".all_seed_enc($email)."'";					//이메일
		$SQL_U .= ", group_join_type = '".$group_join_type."'";				//단체 가입구분
		$SQL_U .= ", pr_cd = '".$pr_cd."'";									//상품코드
		$SQL_U .= ", ins_cd = '".$ins_cd."'";									//보험사코드
		$SQL_U .= ", plan_cd = '".$plan_seq."'";								//플랜코드
		$SQL_U .= ", group_join_status = '".$group_join_status."'";								//단체 가입 상태 Y: 가입, W: 입금대기, N: 견적
		$SQL_U .= ", mod_id = '".$_SESSION["ss_u_id"]."' ";
		$SQL_U .= ", moddate = now() ";
		$SQL_U .= " WHERE group_join_id='".$group_join_id."' ";
		$result = $dbcon -> query($SQL_U);
		if (!$result) {
			$dbcon -> dbcon_close();
			alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
			echo "false";
			return false;
		}

		//가입자 유무 확인, 없으면 추가 있으면 수정
		$orderno = array();
		$SQL_LJ = "SELECT orderno from tbl_order_list_join where group_join_id = '".$group_join_id."' ";
		$RS_LJ = $dbcon -> query($SQL_LJ);
		while($row = $dbcon->fetch_array($RS_LJ)) {
			$orderno[] = $row;
		}

		$url = "";
		error_reporting(E_ALL ^ E_NOTICE ^ E_DEPRECATED ^ E_USER_DEPRECATED);
		include $_SERVER["DOCUMENT_ROOT"]."/_util/PHPExcel-1.8/Classes/PHPExcel.php";

		if ($_FILES["file1"]){
			// 파일이 있는지 확인후 복사
			if($_FILES["file1"]["name"] != '') {
				//가입자 신규 추가
                try{
                    if(count($orderno) == 0){
                        $filename = $_FILES["file1"]["name"];
                        $tmp_file = $_FILES["file1"]["tmp_name"];
                        $filesize = $_FILES["file1"]["size"];

                        $UpFilePathInfo = pathinfo($filename);
                        $UpFileExt = strtolower($UpFilePathInfo["extension"]);

                        //  확장자 체크 : csv파일이 아니면 history(-1)
                        $file_info = explode(".", $filename);
                        $filename = "xls_group_join_upload".date("YmdHis",time()).".".$UpFileExt;

                        @move_uploaded_file($tmp_file, $_SERVER["DOCUMENT_ROOT"]."/_data/group_join/$filename");   //파일복사
                        @unlink($tmp_file);

                        $url = $_SERVER["DOCUMENT_ROOT"]."/_data/group_join/".$filename;
                        //파일 타입 설정 (확자자에 따른 구분)
                        $inputFileType = 'Excel2007';
                        if($file_info[1] == "xls") {
                            $inputFileType = 'Excel5';
                        }
                        //==================================================================
                        //  PHPEXCEL 용 로더
                        //==================================================================
                        //엑셀리더 초기화
                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                        //데이터만 읽기(서식을 모두 무시해서 속도 증가 시킴)
                        $objReader->setReadDataOnly(true);
                        //범위 지정(위에 작성한 범위필터 적용)
                        //	$objReader->setReadFilter($filterSubset);
                        //업로드된 엑셀 파일 읽기
                        //	$objPHPExcel = $objReader->load($url);
                        $objPHPExcel = PHPExcel_IOFactory::load($url);
                        //첫번째 시트로 고정
                        $objPHPExcel->setActiveSheetIndex(0);
                        //고정된 시트 로드
                        $objWorksheet = $objPHPExcel->getActiveSheet();
                        $cellIterator = $objWorksheet->getRowIterator()->current()->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells( true );
                        $maxRow = $objWorksheet->getHighestRow();
                        $PLAN_DATA = array();
                        $GROUP_INFO_TABLE = array();
                        $GROUP_INFO_TABLE["chk_p"] = $chk_p;
                        $gender = "";
                        $period = "";
                        for($i=2;$i<=$maxRow;$i++) {
                            $nullCheck = str_replace("\"","",$objWorksheet->getCell('A' . $i)->getValue());
                            if($nullCheck != '' && !is_null($nullCheck)){
                                $GROUP_INFO_TABLE["ins_user_num"] = str_replace("\"","",$objWorksheet->getCell('A' . $i)->getValue());
                                $GROUP_INFO_TABLE["o_name"] = str_replace("\"","",$objWorksheet->getCell('B' . $i)->getValue());
                                $GROUP_INFO_TABLE["o_name_en"] = str_replace("\"","",$objWorksheet->getCell('C' . $i)->getValue());

                                $isdn = str_replace("\"","",$objWorksheet->getCell('D' . $i)->getValue());
                                $o_isdn = explode('-', $isdn);

                                if ($o_isdn[0] && $o_isdn[1]) {
                                    // 주민등록번호의 7번째 한자리 숫자
                                    $TempBirthYear = substr($o_isdn[0], 0, 2);
                                    $TempBirthMonth = substr($o_isdn[0], 2, 2);
                                    $TempBirthDay = substr($o_isdn[0], 4, 2);
                                    $TempBirthGubun = substr($o_isdn[1], 0, 1);

                                    if ($TempBirthGubun == 9 || $TempBirthGubun == 0) $TempBirthYear = "18" . $TempBirthYear;
                                    else if ($TempBirthGubun == 1 || $TempBirthGubun ==2 || $TempBirthGubun ==5 || $TempBirthGubun == 6) $TempBirthYear = "19" . $TempBirthYear;
                                    else if ($TempBirthGubun == 3 || $TempBirthGubun ==4 || $TempBirthGubun ==7 || $TempBirthGubun == 8) $TempBirthYear = "20" . $TempBirthYear;

                                    $u_birth_year = $TempBirthYear;
                                    $u_birth_month = $TempBirthMonth;
                                    $u_birth_day = $TempBirthDay;
                                    $birth = $u_birth_year.$u_birth_month.$u_birth_day;

                                    // 성별은 F, M 으로 나눈다.
                                    // 주민등록번호의 7번째 자리가 홀수이면 남자(Male), 짝수이면 여자(Female)
                                    $gender = $TempBirthGubun % 2 == 0 ? "F" : "M";

                                }
                                $GROUP_INFO_TABLE["o_isdn1"] = $o_isdn[0];
                                $GROUP_INFO_TABLE["o_isdn2"] = $o_isdn[1];
                                
                                $start = explode(' ', str_replace("\"","",$objWorksheet->getCell('E' . $i)->getValue()));
                                $s_date = $start[0];
                                $s_date_time = $start[1];
                                $end = explode(' ', str_replace("\"","",$objWorksheet->getCell('F' . $i)->getValue()));
                                $e_date = $end[0];
                                $e_date_time = $end[1];
                                $GROUP_INFO_TABLE["s_date"] = str_replace("\"","",$objWorksheet->getCell('E' . $i)->getValue());
                                $GROUP_INFO_TABLE["e_date"] = str_replace("\"","",$objWorksheet->getCell('F' . $i)->getValue());
                                
                                //방문국가 정보 조회
                                $GROUP_INFO_TABLE["join_nation_name"] = str_replace("\"","",$objWorksheet->getCell('G' . $i)->getValue());
                                $SQL_N = "select c_code, c_name from tbl_board_product_country where pr_seq='".$PR_SEQ."' and c_name='".$GROUP_INFO_TABLE["join_nation_name"][$i-2]."'";
                                $row = $dbcon -> query($SQL_N);
                                $nation = $dbcon -> fetch_array($row);
                                $GROUP_INFO_TABLE["join_nation_cd"] = $nation["c_code"];

                                
                                $tmpEmail = explode("@", str_replace("\"","",$objWorksheet->getCell('H' . $i)->getValue()));
                                $GROUP_INFO_TABLE["email"] = $tmpEmail[0];
                                $GROUP_INFO_TABLE["email2"] = $tmpEmail[1];
                                $GROUP_INFO_TABLE["o_phone"] = str_replace("\"","",$objWorksheet->getCell('I' . $i)->getValue());
                                $GROUP_INFO_TABLE["age"] = fn_ins_age(date("Y-m-d",strtotime($birth))); //보험나이
                                // $GROUP_INFO_TABLE["gender"] = $Arr_u_sex[$gender];
                                $GROUP_INFO_TABLE["gender"] = $gender;
                                
                                $writeDate = str_replace("\"","",$objWorksheet->getCell('J' . $i)->getValue());
                                $GROUP_INFO_TABLE["writedate"] = PHPExcel_Shared_Date::ExcelToPHPObject($writeDate)->format('Y-m-d H:i:s');

                                if($chk_p == "Y") { //단기
                                    $t_s_date = $GROUP_INFO_TABLE["s_date"];
                                    $t_e_date = $GROUP_INFO_TABLE["e_date"];
                                    $arr_period = getArrPeriod($t_s_date,$t_e_date,$chk_p);
                                    if($arr_period["day"] > 90) {
                                        alert_page("가입기간은 90일까지 가입가능합니다.","./search_group_join.php?PR_SEQ=".$PR_SEQ);
                                        exit;
                                    }
                                    $GROUP_INFO_TABLE["period_day"] = $arr_period["day"];
                                    $GROUP_INFO_TABLE["period"] = $arr_period["day"];
                                    $GROUP_INFO_TABLE["period_month"] = $arr_period["month"];
                                } else { //장기
                                    $t_s_date = explode( ' ', $GROUP_INFO_TABLE["s_date"]);
                                    $t_e_date = explode( ' ', $GROUP_INFO_TABLE["e_date"]);
                                    $arr_period = getArrPeriod($t_s_date[0],$t_e_date[0],$chk_p);
                                    if($arr_period["day"] > 365) {
                                        alert_page("가입기간은 365일까지 가입가능합니다.","./search_group_join.php?PR_SEQ=".$PR_SEQ);
                                        exit;
                                    }
                                    $GROUP_INFO_TABLE["period_day"] = $arr_period["day"];
                                    $GROUP_INFO_TABLE["period"] = $arr_period["month"];
                                    $GROUP_INFO_TABLE["period_month"] = $arr_period["month"];
                                }

                                //가입자
                                if($chk_p == "Y") { //기간을 구간으로 변경
                                    $period_sel= fnShortTermSection($GROUP_INFO_TABLE["period"]);
                                } else {
                                    $period_sel = $GROUP_INFO_TABLE["period_month"];
                                }
                                
                                // $sql_ins_amount  = " SELECT period".$period_sel." FROM tbl_board_plan_amount1 WHERE plan_cd = '".$plan_seq."' ";
                                // $sql_ins_amount .= " AND age = '".$GROUP_INFO_TABLE["age"]."' AND gender = '".$GROUP_INFO_TABLE["gender"]."' ";
                                // $rs_ins_amount = $dbcon->query($sql_ins_amount);
                                // $row_ins_amount = $dbcon->fetch_array($rs_ins_amount);
                                // $ins_amount = $row_ins_amount["period".$period_sel] > 0 ? $row_ins_amount["period".$period_sel] : 0; //보험료

                                //가입자 보험료 계산 변경 2023.11.02
                                $ins_amount = fn_sel_ins_amt($GROUP_INFO_TABLE["period"],$chk_p,$plan_seq,$GROUP_INFO_TABLE["age"],$GROUP_INFO_TABLE["gender"]);		// 가입자 여행비용

                                $ROW["reason_txt"] = ""; //가입불가 이유
                                $ROW["joinChk"] = "Y"; //가입여부

                                //서비스료 변경 2023.11.02
                                $service_amount = fn_ins_service_amt($plan_seq, $chk_service, $GROUP_INFO_TABLE["period_month"], $GROUP_INFO_TABLE["period_day"], $GROUP_INFO_TABLE["period"], $chk_p, $GROUP_INFO_TABLE["age"], $GROUP_INFO_TABLE["gender"]);

                                // if($chk_service != "N") {
                                //     //일할 계산 처리를 위하여 함수 처리 2023.08.24
                                //     $service_amount = fn_ins_service_amt($plan_seq, $chk_service, $GROUP_INFO_TABLE["period_month"], $GROUP_INFO_TABLE["period"]);
                                //     /*
                                //     $sql_service_amount  = " SELECT mon".$GROUP_INFO_TABLE["period_month"]." FROM tbl_board_plan_amount2 WHERE plan_cd = '".$plan_seq."' ";
                                //     $sql_service_amount .= " AND stype = '".$chk_service."' ";
                                //     $rs_service_amount = $dbcon->query($sql_service_amount);
                                //     $row_service_amount = $dbcon->fetch_array($rs_service_amount);
                                    
                                //     $service_amount = $row_service_amount["mon".$GROUP_INFO_TABLE["period_month"]]; //서비스료
                                //     */
                                // }
                                
                                $t_service_temp_amt = $service_amount;		// 과세대상 결제총액 = 서비스료 - 할인금액
                                $value_of_supply = round($t_service_temp_amt / 1.1);	//공급가액 (서비스 과세금액)  과세대상 결제총액 / 1.1
                                //VAT
                                $vat_amt = $t_service_temp_amt - $value_of_supply; 		//부가세 = 과세대상 결제총액 - 공급가액

                                $tot_amount = $ins_amount+$service_amount; //총금액
                                $s_amt_per = 0;
                                $s_amount = 0;
                                
                                $usr_s_amount = 0;			// 가입자 할인금액
                                $usr_vat_amount = 0;			// 개별 VAT, 소수점 버림
                                $usr_t_amount =	$ins_amount + $service_amount - $usr_s_amount;	// 가입자 결제금액
                                $GROUP_INFO_TABLE["total_amount"] = $GROUP_INFO_TABLE["total_amount"]+$usr_t_amount;

                                // require_once('../../html/insurance/libs/INIStdPayUtil.php');
                                // require_once('../../html/insurance/libs/HttpClient.php');
                                require_once('../../html/insurance/libs/INIStdPayUtil.php');
                                $SignatureUtil = new INIStdPayUtil();
                                $orderNumber = "P_" .date("YmdHis").$SignatureUtil->getTimestamp(); // 가맹점 주문번호(가맹점에서 직접 설정)

                                $rule_site_cd = selRuleSeq("사이트 이용약관");
                                $rule_group_cd = selRuleSeq("단체보험 규약");
                                $rule_privacy_cd = selRuleSeq("개인정보 수집 및 이용 동의");
                                $arr_ins_agree_file = selInsAgreeFile($agree_cd);

                                //데이터 입력 - 주문
                                $select_add_people = $select_add_people +1;		//가입자 본인 추가
                                $SQL1 = "insert into tbl_order_list set";
                                $SQL1 .= " orderno = '".$orderNumber."' ";
                                $SQL1 .= ", pr_name = '".print_pr_name($PR_SEQ)."' ";
                                $SQL1 .= ", pr_cd = '".$PR_SEQ."' ";
                                $SQL1 .= ", ins_name = '".print_ins($ins_cd)."' ";
                                $SQL1 .= ", ins_cd = '".$ins_cd."' ";
                                $SQL1 .= ", plan_name = '".$ins_plan_name." - ".$Arr_plan_cd[$plan_cd]."' ";
                                $SQL1 .= ", plan_cd = '".($plan_seq > 0 ? $plan_seq : 0)."' ";
                                $SQL1 .= ", agree_cd = '".($PR_INFO["ext4"] > 0 ? $PR_INFO["ext4"] : 0)."' ";
                                $SQL1 .= ", service_name = '".print_insu_service($PR_INFO["ext5"])."' ";
                                $SQL1 .= ", service_cd = '".($PR_INFO["ext5"] > 0 ? $PR_INFO["ext5"] : 0)."' ";
                                $SQL1 .= ", rule_site_cd = '".($rule_site_cd > 0 ? $rule_site_cd : 0)."' ";
                                $SQL1 .= ", rule_group_cd = '".($rule_group_cd > 0 ? $rule_group_cd : 0)."' ";
                                $SQL1 .= ", rule_privacy_cd = '".($rule_privacy_cd > 0 ? $rule_privacy_cd : 0)."' ";
                                $SQL1 .= ", ins_file_cd = '".($agree_cd > 0 ?$agree_cd : 0)."' ";
                                $SQL1 .= ", service_file_cd = '".($service_cd> 0 ? $service_cd : 0)."' ";
                                $SQL1 .= ", s_date = '".$s_date."' ";
                                $SQL1 .= ", s_date_time = '".$s_date_time."' ";
                                $SQL1 .= ", e_date = '".$e_date."' ";
                                $SQL1 .= ", e_date_time = '".$e_date_time."' ";
                                if ($chk_p == "Y") { //단기
                                    $SQL1 .= ", ins_period = '" . $GROUP_INFO_TABLE["period"] . "' ";
                                } else {
                                    $SQL1 .= ", ins_period = '" . $GROUP_INFO_TABLE["period_month"] . "' ";
                                }
                                $SQL1 .= ", chk_p = '".$chk_p."' ";
                                $SQL1 .= ", chk_service = '".$chk_service."' ";
                                $SQL1 .= ", o_name = '".all_seed_enc($GROUP_INFO_TABLE["o_name"])."' ";
                                $SQL1 .= ", o_email1 = '".all_seed_enc($GROUP_INFO_TABLE["email"])."' ";
                                $SQL1 .= ", o_email2 = '".all_seed_enc($GROUP_INFO_TABLE["email2"])."' ";
                                $SQL1 .= ", join_cnt = '1' ";
                                $SQL1 .= ", join_ch = '' ";
                                $SQL1 .= ", purpose = '".$purpose."' ";
                                $SQL1 .= ", join_nation_cd = '".$GROUP_INFO_TABLE["join_nation_cd"]."' ";
                                $SQL1 .= ", join_nation_name = '".$GROUP_INFO_TABLE["join_nation_name"]."' ";
                                $SQL1 .= ", order_step = '".$orderStep."' ";
                                $SQL1 .= ", sale_gubun = '' ";
                                $SQL1 .= ", sale_discount = '0' ";
                                $SQL1 .= ", cp_cd = '0' ";
                                $SQL1 .= ", recommend_cd = '0' ";
                                $SQL1 .= ", ins_amount = '".($ins_amount > 0 ? $ins_amount : 0)."' ";
                                $SQL1 .= ", service_amount = '".($service_amount > 0 ? $service_amount : 0)."' ";
                                $SQL1 .= ", s_amount = '0' ";
                                $SQL1 .= ", vat_amount = '".$vat_amt."' ";
                                $SQL1 .= ", t_amount = '".$tot_amount."' ";
                                $SQL1 .= ", cancle_amount = '0' ";
                                $SQL1 .= ", group_join_id = '".$group_join_id."' ";
                                $SQL1 .= ", pg_id='' ";
                                $SQL1 .= ", pg_pay_type='' ";
                                $SQL1 .= ", pg_isdn='' ";
                                $SQL1 .= ", pay_name='' ";
                                $SQL1 .= ", writedate = '".$GROUP_INFO_TABLE["writedate"]."' ";
                                $RS1 = $dbcon -> query($SQL1);

                                //데이터 입력 - 가입자
                                $SQL2 = "insert into tbl_order_list_join set";
                                $SQL2 .= " orderno = '".$orderNumber."' ";
                                $SQL2 .= ", gender = '".$gender."' ";
                                $SQL2 .= ", chk_join = 'N' ";
                                $SQL2 .= ", chk_eng_passport = '' ";
                                $SQL2 .= ", o_name = '".all_seed_enc($GROUP_INFO_TABLE["o_name"])."' ";
                                $SQL2 .= ", o_phone = '".all_seed_enc($GROUP_INFO_TABLE["o_phone"])."' ";
                                $SQL2 .= ", o_isdn1 = '".all_seed_enc($birth)."' ";
                                $SQL2 .= ", o_isdn2 = '".all_seed_enc($GROUP_INFO_TABLE["o_isdn2"])."' ";
                                $SQL2 .= ", o_name_en = '".all_seed_enc($GROUP_INFO_TABLE["o_name_en"])."' "; 
                                $SQL2 .= ", ins_plan_cd = '".$plan_seq."' ";
                                $SQL2 .= ", join_amount = '".($ins_amount > 0 ? $ins_amount : 0)."' ";
                                $SQL2 .= ", join_service = '".($service_amount > 0 ? $service_amount : 0)."' ";
                                $SQL2 .= ", vat_amount = '".$usr_vat_amount."' ";
                                $SQL2 .= ", s_amount = '".$usr_s_amount."' ";
                                $SQL2 .= ", t_amount = '".$usr_t_amount."' ";
                                $SQL2 .= ", join_status = '".$group_join_status."' ";
                                $SQL2 .= ", group_join_type = '".$group_join_type."' ";
                                $SQL2 .= ", project_cd = '".$grp_cd."' ";
                                $SQL2 .= ", group_join_id = '".$group_join_id."' ";
                                $SQL2 .= ", ins_user_num = '".$GROUP_INFO_TABLE["ins_user_num"]."' ";
                                $SQL2 .= ", regdate = '".$GROUP_INFO_TABLE["writedate"]."' ";
                                $RS2 = $dbcon -> query($SQL2);
                            }
                        }
                        
                        $SQL_A = "";
                        $SQL_A  = " UPDATE tbl_order_group_join_list SET";
                        $SQL_A .= " total_amount = '".($GROUP_INFO_TABLE["total_amount"] > 0 ? $GROUP_INFO_TABLE["total_amount"] : 0)."' ";
                        $SQL_A .= ", group_join_cnt = '".($i-2)."' ";
                        $SQL_A .= ", memberfile = '".$url."' ";
                        $SQL_A .= " WHERE group_join_id = '".$group_join_id."' ";
                        $dbcon -> query($SQL_A);
                    } else {
                        //등록된 데이터 삭제
                        $DQL_D1 = " DELETE FROM tbl_order_list where group_join_id ='".$group_join_id."' ";
                        $result1 = $dbcon -> query($DQL_D1);
                        if(!$result1){
                            $dbcon -> dbcon_close();
                            alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
                            echo "false";
                            return false;
                        }

                        $DQL_D2 = " DELETE FROM tbl_order_list_join where group_join_id ='".$group_join_id."' ";
                        $result2 = $dbcon -> query($DQL_D2);
                        if(!$result2){
                            $dbcon -> dbcon_close();
                            alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
                            echo "false";
                            return false;
                        }

                        $filename = $_FILES["file1"]["name"];
                        $tmp_file = $_FILES["file1"]["tmp_name"];
                        $filesize = $_FILES["file1"]["size"];

                        $UpFilePathInfo = pathinfo($filename);
                        $UpFileExt = strtolower($UpFilePathInfo["extension"]);

                        //  확장자 체크 : csv파일이 아니면 history(-1)
                        $file_info = explode(".", $filename);
                        $filename = "xls_group_join_upload".date("YmdHis",time()).".".$UpFileExt;

                        @move_uploaded_file($tmp_file, $_SERVER["DOCUMENT_ROOT"]."/_data/group_join/$filename");   //파일복사
                        @unlink($tmp_file);

                        $url = $_SERVER["DOCUMENT_ROOT"]."/_data/group_join/".$filename;
                        
                        //파일 타입 설정 (확자자에 따른 구분)
                        $inputFileType = 'Excel2007';
                        if($file_info[1] == "xls") {
                            $inputFileType = 'Excel5';
                        }
                        //==================================================================
                        //  PHPEXCEL 용 로더
                        //==================================================================
                        //엑셀리더 초기화
                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                        //데이터만 읽기(서식을 모두 무시해서 속도 증가 시킴)
                        $objReader->setReadDataOnly(true);
                        //범위 지정(위에 작성한 범위필터 적용)
                        //	$objReader->setReadFilter($filterSubset);
                        //업로드된 엑셀 파일 읽기
                        //	$objPHPExcel = $objReader->load($url);
                        $objPHPExcel = PHPExcel_IOFactory::load($url);
                        //첫번째 시트로 고정
                        $objPHPExcel->setActiveSheetIndex(0);
                        //고정된 시트 로드
                        $objWorksheet = $objPHPExcel->getActiveSheet();
                        $cellIterator = $objWorksheet->getRowIterator()->current()->getCellIterator();
                        $cellIterator->setIterateOnlyExistingCells( true );
                        $maxRow = $objWorksheet->getHighestRow();
                        $PLAN_DATA = array();
                        $GROUP_INFO_TABLE = array();
                        $GROUP_INFO_TABLE["chk_p"] = $chk_p;
                        $gender = "";
                        $period = "";
                        for($i=2;$i<=$maxRow;$i++) {
                            $nullCheck = str_replace("\"","",$objWorksheet->getCell('A' . $i)->getValue());
                            if($nullCheck != '' && !is_null($nullCheck)){
                                $GROUP_INFO_TABLE["ins_user_num"] = str_replace("\"","",$objWorksheet->getCell('A' . $i)->getValue());
                                $GROUP_INFO_TABLE["o_name"] = str_replace("\"","",$objWorksheet->getCell('B' . $i)->getValue());
                                $GROUP_INFO_TABLE["o_name_en"] = str_replace("\"","",$objWorksheet->getCell('C' . $i)->getValue());

                                $isdn = str_replace("\"","",$objWorksheet->getCell('D' . $i)->getValue());
                                $o_isdn = explode('-', $isdn);

                                if ($o_isdn[0] && $o_isdn[1]) {
                                    // 주민등록번호의 7번째 한자리 숫자
                                    $TempBirthYear = substr($o_isdn[0], 0, 2);
                                    $TempBirthMonth = substr($o_isdn[0], 2, 2);
                                    $TempBirthDay = substr($o_isdn[0], 4, 2);
                                    $TempBirthGubun = substr($o_isdn[1], 0, 1);

                                    if ($TempBirthGubun == 9 || $TempBirthGubun == 0) $TempBirthYear = "18" . $TempBirthYear;
                                    else if ($TempBirthGubun == 1 || $TempBirthGubun == 2 || $TempBirthGubun == 5 || $TempBirthGubun == 6) $TempBirthYear = "19" . $TempBirthYear;
                                    else if ($TempBirthGubun == 3 || $TempBirthGubun == 4 || $TempBirthGubun == 7 || $TempBirthGubun == 8) $TempBirthYear = "20" . $TempBirthYear;

                                    $u_birth_year = $TempBirthYear;
                                    $u_birth_month = $TempBirthMonth;
                                    $u_birth_day = $TempBirthDay;
                                    $birth = $u_birth_year.$u_birth_month.$u_birth_day;

                                    // 성별은 F, M 으로 나눈다.
                                    // 주민등록번호의 7번째 자리가 홀수이면 남자(Male), 짝수이면 여자(Female)
                                    $gender = $TempBirthGubun % 2 == 0 ? "F" : "M";

                                }
                                $GROUP_INFO_TABLE["o_isdn1"] = $o_isdn[0];
                                $GROUP_INFO_TABLE["o_isdn2"] = $o_isdn[1];
                                
                                $start = explode(' ', str_replace("\"","",$objWorksheet->getCell('E' . $i)->getValue()));
                                $s_date = $start[0];
                                $s_date_time = $start[1];
                                $end = explode(' ', str_replace("\"","",$objWorksheet->getCell('F' . $i)->getValue()));
                                $e_date = $end[0];
                                $e_date_time = $end[1];
                                $GROUP_INFO_TABLE["s_date"] = str_replace("\"","",$objWorksheet->getCell('E' . $i)->getValue());
                                $GROUP_INFO_TABLE["e_date"] = str_replace("\"","",$objWorksheet->getCell('F' . $i)->getValue());
                                
                                //방문국가 정보 조회
                                $GROUP_INFO_TABLE["join_nation_name"] = str_replace("\"","",$objWorksheet->getCell('G' . $i)->getValue());
                                $SQL_N = "select c_code, c_name from tbl_board_product_country where pr_seq='".$PR_SEQ."' and c_name='".$GROUP_INFO_TABLE["join_nation_name"][$i-2]."'";
                                $row = $dbcon -> query($SQL_N);
                                $nation = $dbcon -> fetch_array($row);
                                $GROUP_INFO_TABLE["join_nation_cd"] = $nation["c_code"];

                                $tmpEmail = explode("@", str_replace("\"","",$objWorksheet->getCell('H' . $i)->getValue()));
                                $GROUP_INFO_TABLE["email"] = $tmpEmail[0];
                                $GROUP_INFO_TABLE["email2"] = $tmpEmail[1];
                                $GROUP_INFO_TABLE["o_phone"] = str_replace("\"","",$objWorksheet->getCell('I' . $i)->getValue());
                                $GROUP_INFO_TABLE["age"] = fn_ins_age(date("Y-m-d",strtotime($birth))); //보험나이
                                // $GROUP_INFO_TABLE["gender"] = $Arr_u_sex[$gender];
                                $GROUP_INFO_TABLE["gender"] = $gender;
                                
                                $writeDate = str_replace("\"","",$objWorksheet->getCell('J' . $i)->getValue());
                                $GROUP_INFO_TABLE["writedate"] = PHPExcel_Shared_Date::ExcelToPHPObject($writeDate)->format('Y-m-d H:i:s');

                                if($chk_p == "Y") { //단기
                                    $t_s_date = $GROUP_INFO_TABLE["s_date"];
                                    $t_e_date = $GROUP_INFO_TABLE["e_date"];
                                    $arr_period = getArrPeriod($t_s_date,$t_e_date,$chk_p);
                                    if($arr_period["day"] > 90) {
                                        alert_page("가입기간은 90일까지 가입가능합니다.","./search_group_join.php?PR_SEQ=".$PR_SEQ);
                                        exit;
                                    }
                                    $GROUP_INFO_TABLE["period_day"] = $arr_period["day"];
                                    $GROUP_INFO_TABLE["period"] = $arr_period["day"];
                                    $GROUP_INFO_TABLE["period_month"] = $arr_period["month"];
                                } else { //장기
                                    $t_s_date = explode( ' ', $GROUP_INFO_TABLE["s_date"]);
                                    $t_e_date = explode( ' ', $GROUP_INFO_TABLE["e_date"]);
                                    $arr_period = getArrPeriod($t_s_date[0],$t_e_date[0],$chk_p);
                                    if($arr_period["day"] > 365) {
                                        alert_page("가입기간은 365일까지 가입가능합니다.","./search_group_join.php?PR_SEQ=".$PR_SEQ);
                                        exit;
                                    }
                                    $GROUP_INFO_TABLE["period_day"] = $arr_period["day"];
                                    $GROUP_INFO_TABLE["period"] = $arr_period["month"];
                                    $GROUP_INFO_TABLE["period_month"] = $arr_period["month"];
                                }

                                //가입자
                                if($chk_p == "Y") { //기간을 구간으로 변경
                                    $period_sel= fnShortTermSection($GROUP_INFO_TABLE["period"]);
                                } else {
                                    $period_sel = $GROUP_INFO_TABLE["period_month"];
                                }

                                // $sql_ins_amount  = " SELECT period".$period_sel." FROM tbl_board_plan_amount1 WHERE plan_cd = '".$plan_seq."' ";
                                // $sql_ins_amount .= " AND age = '".$GROUP_INFO_TABLE["age"]."' AND gender = '".$GROUP_INFO_TABLE["gender"]."' ";
                                // $rs_ins_amount = $dbcon->query($sql_ins_amount);
                                // $row_ins_amount = $dbcon->fetch_array($rs_ins_amount);
                                // $ins_amount = $row_ins_amount["period".$period_sel] > 0 ? $row_ins_amount["period".$period_sel] : 0; //보험료

                                //가입자 보험료 계산 변경 2023.11.02
                                $ins_amount = fn_sel_ins_amt($GROUP_INFO_TABLE["period"],$chk_p,$plan_seq,$GROUP_INFO_TABLE["age"],$GROUP_INFO_TABLE["gender"]);		// 가입자 여행비용

                                $ROW["reason_txt"] = ""; //가입불가 이유
                                $ROW["joinChk"] = "Y"; //가입여부

                                //서비스료 변경 2023.11.02
                                $service_amount = fn_ins_service_amt($plan_seq, $chk_service, $GROUP_INFO_TABLE["period_month"], $GROUP_INFO_TABLE["period_day"], $GROUP_INFO_TABLE["period"], $chk_p, $GROUP_INFO_TABLE["age"], $GROUP_INFO_TABLE["gender"]);

                                // if($chk_service != "N") {
                                //     //일할 계산 처리를 위하여 함수 처리 2023.08.24
                                //     $service_amount = fn_ins_service_amt($plan_seq, $chk_service, $GROUP_INFO_TABLE["period_month"], $GROUP_INFO_TABLE["period"]);
                                //     /*
                                //     $sql_service_amount  = " SELECT mon".$GROUP_INFO_TABLE["period_month"]." FROM tbl_board_plan_amount2 WHERE plan_cd = '".$plan_seq."' ";
                                //     $sql_service_amount .= " AND stype = '".$chk_service."' ";
                                //     $rs_service_amount = $dbcon->query($sql_service_amount);
                                //     $row_service_amount = $dbcon->fetch_array($rs_service_amount);
                                    
                                //     $service_amount = $row_service_amount["mon".$GROUP_INFO_TABLE["period_month"]]; //서비스료
                                //     */
                                // }
                                
                                $t_service_temp_amt = $service_amount;		// 과세대상 결제총액 = 서비스료 - 할인금액
                                $value_of_supply = round($t_service_temp_amt / 1.1);	//공급가액 (서비스 과세금액)  과세대상 결제총액 / 1.1
                                //VAT
                                $vat_amt = $t_service_temp_amt - $value_of_supply; 		//부가세 = 과세대상 결제총액 - 공급가액

                                $tot_amount = $ins_amount+$service_amount; //총금액
                                $s_amt_per = 0;
                                $s_amount = 0;
                                
                                $usr_s_amount = 0;			// 가입자 할인금액
                                $usr_vat_amount = 0;			// 개별 VAT, 소수점 버림
                                $usr_t_amount =	$ins_amount + $service_amount - $usr_s_amount;	// 가입자 결제금액
                                $GROUP_INFO_TABLE["total_amount"] = $GROUP_INFO_TABLE["total_amount"]+$usr_t_amount;

                                // require_once('../../html/insurance/libs/INIStdPayUtil.php');
                                // require_once('../../html/insurance/libs/HttpClient.php');
                                require_once('../../html/insurance/libs/INIStdPayUtil.php');
                                $SignatureUtil = new INIStdPayUtil();
                                $orderNumber = "P_" .date("YmdHis").$SignatureUtil->getTimestamp(); // 가맹점 주문번호(가맹점에서 직접 설정)

                                $rule_site_cd = selRuleSeq("사이트 이용약관");
                                $rule_group_cd = selRuleSeq("단체보험 규약");
                                $rule_privacy_cd = selRuleSeq("개인정보 수집 및 이용 동의");
                                $arr_ins_agree_file = selInsAgreeFile($agree_cd);

                                //데이터 입력 - 주문
                                $select_add_people = $select_add_people +1;		//가입자 본인 추가
                                $SQL1 = "insert into tbl_order_list set";
                                $SQL1 .= " orderno = '".$orderNumber."' ";
                                $SQL1 .= ", pr_name = '".print_pr_name($PR_SEQ)."' ";
                                $SQL1 .= ", pr_cd = '".$PR_SEQ."' ";
                                $SQL1 .= ", ins_name = '".print_ins($ins_cd)."' ";
                                $SQL1 .= ", ins_cd = '".$ins_cd."' ";
                                $SQL1 .= ", plan_name = '".$ins_plan_name." - ".$Arr_plan_cd[$plan_cd]."' ";
                                $SQL1 .= ", plan_cd = '".($plan_seq > 0 ? $plan_seq : 0)."' ";
                                $SQL1 .= ", agree_cd = '".($PR_INFO["ext4"] > 0 ? $PR_INFO["ext4"] : 0)."' ";
                                $SQL1 .= ", service_name = '".print_insu_service($PR_INFO["ext5"])."' ";
                                $SQL1 .= ", service_cd = '".($PR_INFO["ext5"] > 0 ? $PR_INFO["ext5"] : 0)."' ";
                                $SQL1 .= ", rule_site_cd = '".($rule_site_cd > 0 ? $rule_site_cd : 0)."' ";
                                $SQL1 .= ", rule_group_cd = '".($rule_group_cd > 0 ? $rule_group_cd : 0)."' ";
                                $SQL1 .= ", rule_privacy_cd = '".($rule_privacy_cd > 0 ? $rule_privacy_cd : 0)."' ";
                                $SQL1 .= ", ins_file_cd = '".($agree_cd > 0 ? $agree_cd : 0)."' ";
                                $SQL1 .= ", service_file_cd = '".($service_cd > 0 ? $service_cd : 0)."' ";
                                $SQL1 .= ", s_date = '".$s_date."' ";
                                $SQL1 .= ", s_date_time = '".$s_date_time."' ";
                                $SQL1 .= ", e_date = '".$e_date."' ";
                                $SQL1 .= ", e_date_time = '".$e_date_time."' ";
                                if ($chk_p == "Y") { //단기
                                    $SQL1 .= ", ins_period = '" . $GROUP_INFO_TABLE["period"] . "' ";
                                } else {
                                    $SQL1 .= ", ins_period = '" . $GROUP_INFO_TABLE["period_month"] . "' ";
                                }
                                $SQL1 .= ", chk_p = '".$chk_p."' ";
                                $SQL1 .= ", chk_service = '".$chk_service."' ";
                                $SQL1 .= ", o_name = '".all_seed_enc($GROUP_INFO_TABLE["o_name"])."' ";
                                $SQL1 .= ", o_email1 = '".all_seed_enc($GROUP_INFO_TABLE["email"])."' ";
                                $SQL1 .= ", o_email2 = '".all_seed_enc($GROUP_INFO_TABLE["email2"])."' ";
                                $SQL1 .= ", join_cnt = '1' ";
                                $SQL1 .= ", join_ch = '' ";
                                $SQL1 .= ", purpose = '".$purpose."' ";
                                $SQL1 .= ", join_nation_cd = '".$GROUP_INFO_TABLE["join_nation_cd"]."' ";
                                $SQL1 .= ", join_nation_name = '".$GROUP_INFO_TABLE["join_nation_name"]."' ";
                                $SQL1 .= ", order_step = '".$orderStep."' ";
                                $SQL1 .= ", sale_gubun = '' ";
                                $SQL1 .= ", sale_discount = '0' ";
                                $SQL1 .= ", cp_cd = '0' ";
                                $SQL1 .= ", recommend_cd = '0' ";
                                $SQL1 .= ", ins_amount = '".($ins_amount > 0 ? $ins_amount : 0)."' ";
                                $SQL1 .= ", service_amount = '".($service_amount > 0 ? $service_amount : 0)."' ";
                                $SQL1 .= ", s_amount = '0' ";
                                $SQL1 .= ", vat_amount = '".$vat_amt."' ";
                                $SQL1 .= ", t_amount = '".$tot_amount."' ";
                                $SQL1 .= ", cancle_amount = '0' ";
                                $SQL1 .= ", group_join_id = '".$group_join_id."' ";
                                $SQL1 .= ", pg_id='' ";
                                $SQL1 .= ", pg_pay_type='' ";
                                $SQL1 .= ", pg_isdn='' ";
                                $SQL1 .= ", pay_name='' ";
                                $SQL1 .= ", writedate = '".$GROUP_INFO_TABLE["writedate"]."' ";
                                $RS1 = $dbcon -> query($SQL1);

                                //데이터 입력 - 가입자
                                $SQL2 = "insert into tbl_order_list_join set";
                                $SQL2 .= " orderno = '".$orderNumber."' ";
                                $SQL2 .= ", gender = '".$gender."' ";
                                $SQL2 .= ", chk_join = 'N' ";
                                $SQL2 .= ", chk_eng_passport = '' ";
                                $SQL2 .= ", o_name = '".all_seed_enc($GROUP_INFO_TABLE["o_name"])."' ";
                                $SQL2 .= ", o_phone = '".all_seed_enc($GROUP_INFO_TABLE["o_phone"])."' ";
                                $SQL2 .= ", o_isdn1 = '".all_seed_enc($birth)."' ";
                                $SQL2 .= ", o_isdn2 = '".all_seed_enc($GROUP_INFO_TABLE["o_isdn2"])."' ";
                                $SQL2 .= ", o_name_en = '".all_seed_enc($GROUP_INFO_TABLE["o_name_en"])."' "; 
                                $SQL2 .= ", ins_plan_cd = '".$plan_seq."' ";
                                $SQL2 .= ", join_amount = '".($ins_amount > 0 ? $ins_amount : 0)."' ";
                                $SQL2 .= ", join_service = '".($service_amount > 0 ? $service_amount : 0)."' ";
                                $SQL2 .= ", vat_amount = '".$usr_vat_amount."' ";
                                $SQL2 .= ", s_amount = '".$usr_s_amount."' ";
                                $SQL2 .= ", t_amount = '".$usr_t_amount."' ";
                                $SQL2 .= ", join_status = '".$group_join_status."' ";
                                $SQL2 .= ", group_join_type = '".$group_join_type."' ";
                                $SQL2 .= ", project_cd = '".$grp_cd."' ";
                                $SQL2 .= ", group_join_id = '".$group_join_id."' ";
                                $SQL2 .= ", ins_user_num = '".$GROUP_INFO_TABLE["ins_user_num"]."' ";
                                $SQL2 .= ", regdate = '".$GROUP_INFO_TABLE["writedate"]."' ";
                                $RS2 = $dbcon -> query($SQL2);
                            }
                        }
                        
                        $SQL_A = "";
                        $SQL_A  = " UPDATE tbl_order_group_join_list SET";
                        $SQL_A .= " total_amount = '".($GROUP_INFO_TABLE["total_amount"] > 0 ? $GROUP_INFO_TABLE["total_amount"] : 0)."' ";
                        $SQL_A .= ", group_join_cnt = '".($i-2)."' ";
                        $SQL_A .= ", memberfile = '".$url."' ";
                        $SQL_A .= " WHERE group_join_id = '".$group_join_id."' ";
                        $dbcon -> query($SQL_A);
                    }
                } catch (mysqli_sql_exception $e) {
                    $mysqli_db->rollback();
                    echo $i . "번째 줄을 확인해 주세요.";
                    echo $e->getMessage();
                    // 가입자 정보 삭제가 필요하면 주석 풀어서 사용
                    // $DQL_D1 = " DELETE FROM tbl_order_list where group_join_id ='" . $group_join_id . "' ";
                    // $result1 = $dbcon->query($DQL_D1);
                    // $DQL_D2 = " DELETE FROM tbl_order_list_join where group_join_id ='" . $group_join_id . "' ";
                    // $result2 = $dbcon->query($DQL_D2);
                    throw $e;
                } catch (Exception $e) {
                    echo $e->getMessage();
                }

                $mysqli_db->commit();
			}
		}

		if(count($orderno) > 0){
			foreach($orderno as $num) {
				$status = $group_join_status == "Y" ? "2" : "1";
				$SQL_J = "UPDATE tbl_order_list SET order_step='".$status."', pg_pay_type='VBANK', pay_name='단체가입 계좌이체', pg_in_date=now(), pg_id='".MID."' WHERE orderno = '".$num["orderno"]."' ";
				$dbcon -> query($SQL_J);
	
				$SQL_U = "UPDATE tbl_order_list_join SET join_status='Y' where orderno='".$num["orderno"]."'";
				$dbcon -> query($SQL_U);
			}
		}
	} else if($_POST["mode"] == "delUser"){
		$parameter = "&group_join_id=".$group_join_id."&pr_cd=".$pr_cd."&ins_cd=".$ins_cd."&plan_cd=".$plan_cd."&chk_service=".$chk_service."&search_text=".$search_text."&num_per_page=".$num_per_page."&search_date_s=".$search_date_s."&search_date_e=".$search_date_e;

		//등록된 데이터 삭제
		$DQL_D1 = " DELETE FROM tbl_order_list where group_join_id ='".$group_join_id."' and orderno = '".$delUserNo."' ";
		//$DQL_D1 = "select * from tbl_order_list where group_join_id ='".$group_join_id."' and orderno = '".$delUserNo."' ";
		$result1 = $dbcon -> query($DQL_D1);
		if(!$result1){
			$dbcon -> dbcon_close();
			alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
			echo "false";
			return false;
		}

		$DQL_D2 = " DELETE FROM tbl_order_list_join where group_join_id ='".$group_join_id."' and orderno = '".$delUserNo."' ";
		//$DQL_D2 = " select * FROM tbl_order_list_join where group_join_id ='".$group_join_id."' and orderno = '".$delUserNo."' ";
		$result2 = $dbcon -> query($DQL_D2);
		if(!$result2){
			$dbcon -> dbcon_close();
			alert_back("등록 오류입니다. 관리자에게 문의하여 주십시오.");
			echo "false";
			return false;
		}

        $SQL = "select count(*) as cnt, sum(t_amount) as total_amount from tbl_order_list where group_join_id = '".$group_join_id."'";
        $result3 = $dbcon -> query($SQL);
		$now_group_join_info = $dbcon -> fetch_array($result3);

        $SQL_U = "";
		$SQL_U  = " UPDATE tbl_order_group_join_list SET";
		$SQL_U .= " total_amount = '".($now_group_join_info["total_amount"] > 0 ? $now_group_join_info["total_amount"] : 0)."' ";
		$SQL_U .= ", group_join_cnt = '".$now_group_join_info["cnt"]."' ";
		$SQL_U .= " WHERE group_join_id = '".$group_join_id."' ";
		$dbcon -> query($SQL_U);
	}

$mysqli_db->commit();
?>
<?$dbcon -> dbcon_close();?>
<? if($_POST["mode"] == "write"){?>
<script type="text/javascript">
document.location.href="group_join_view.php?group_join_id=<?=$insert_seq[0]?><?=$parameter?>";
</script>
<? } else if($_POST["mode"] == "mod"){ ?>
	<script type="text/javascript">
document.location.href="group_join_view.php?<?=$parameter?>";
</script>
<? } else if($_POST["mode"] == "delUser"){ ?>
	<script type="text/javascript">
document.location.href="group_join_view.php?<?=$parameter?>";
</script>
<? } ?>
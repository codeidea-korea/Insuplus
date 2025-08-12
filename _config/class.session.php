<?
	// [출처] 스크립트등(history.back();)으로 인해 다시 폼양식으로 돌아올때 폼값유지|작성자 빡상
	// 해당사항 적용시 글삭제 및 데이타 변동시에 적용이 바로 안되어서 주석처리함
	//session_cache_limiter('private');

	if ($path_root == "") $path_root = $_SERVER["DOCUMENT_ROOT"]."/";
	if(is_dir($path_root."_session/"))
		@session_save_path($path_root."_session/");
		session_cache_limiter('nocache, must-revalidate');

	//ini_set("session.cache_expire",500);
	//ini_set("session.gc_maxlifetime", 10);
	session_start();
	//session_set_cookie_params(0,"/");


	/*
	//쿠키 하루동안 저장
	$session_id = session_id();
	set_cookie('visit_session', $session_id, 86400);
	echo "원래 id : ".$session_id;
	echo "<br>";
	echo "cookie 값 : ".get_cookie_nobase64('visit_session');

	function set_cookie($cookie_name, $value, $expire)
	{
		global $g4;
		setcookie(md5($cookie_name), base64_encode($value), $g4[server_time] + $expire, '/', $g4[cookie_domain]);
	}
	// 쿠키변수 생성(base64_encode 안거치기)
	function set_cookie_nobase64($cookie_name, $value, $expire)
	{
		global $g4;
		setcookie(md5($cookie_name), $value, $g4[server_time] + $expire, '/', $g4[cookie_domain]);
	}

	// 쿠키변수값 얻음
	function get_cookie($cookie_name)
	{
		return base64_decode($_COOKIE[md5($cookie_name)]);
	}

	// 쿠키변수값 얻음(base64_decode 안거치기)
	function get_cookie_nobase64($cookie_name)
	{
		return $_COOKIE[md5($cookie_name)];
	}
	*/


	$ss_u_idx			= $_SESSION['ss_u_idx'];
	$ss_u_id			= $_SESSION['ss_u_id'];
	$ss_u_name		= $_SESSION['ss_u_name'];
	$ss_u_level			= $_SESSION['ss_u_level'];
	$ss_u_mail			= $_SESSION['ss_u_mail'];

	$ss_u_idx			= $ss_u_idx;
	$ss_u_id			= $ss_u_id;
	$ss_u_name		= $ss_u_name;
	$ss_u_level			= $ss_u_level	;

//	echo "ss_u_idx : ".$ss_u_idx."<BR>";
//	echo "ss_u_id : ".$ss_u_id."<BR>";
//	echo "ss_u_name : ".$ss_u_name."<BR>";
//	echo "ss_u_level : ".$ss_u_level."<BR>";



	###############################################################
	// 로그인 체크
	function login_chk($level, $url = "")
	{
		global $ss_u_idx, $ss_u_level;
		if ( getLen($url) == 0 ) $url = $GLOBALS[url_index];

		// 로그아웃 상태만 접근 가능
		if ( $level ==  0) {
			//if ( getLen($ss_u_idx) > 0 ) alert_page($GLOBALS[msg_logout_go], $url);
			if ( getLen($ss_u_idx) > 0 ) alert_page("", $url);
		}
		// 로그인 해야 하고 level 이상이어야 접근 가능
		else {
			if ( getLen($ss_u_idx) == 0 ) alert_page($GLOBALS[msg_login_go], $url);
			if ( $ss_u_level < $level ) alert_page($GLOBALS[msg_login_auth], $url);
		}
	}
	###############################################################


	###############################################################
	// 관리자 권한 체크
	function admin_chk($level, $url = "")
	{
		global $ss_u_idx, $ss_u_level, $dbcon;

		if ( getLen($url) == 0 ) {
			$url = $url_admin;
		}

//		echo $level."<BR>";
//		echo $ss_u_idx."<BR>";
//		echo "ss_u_idx : ".$ss_u_idx."<BR>";
//		echo "ss_u_id : ".$ss_u_id."<BR>";
//		echo "ss_u_name : ".$ss_u_name."<BR>";
//		echo "ss_u_level : ".$ss_u_level."<BR>";
//
//		exit;
        // 반드시 인증해야되도록 변경경
		if ( getLen($ss_u_idx) == 0 || $_SESSION['is_authenticated'] == false) {
			alert_page("로그인 후 이용해 주십시오.",$url);
			exit;
		}

		$flag = false;
		if ($level <= $ss_u_level ) {
			$flag = true;
		}

        $session_token = $_SESSION['session_token'];
        $token_expire_time = $_SESSION['token_expire_time'];


        $SQL = "SELECT count(*) as cnt from tbl_user_session where session_token = '".$session_token."' and expire_time > now()";
        // echo $SQL;
        $result = $dbcon->query($SQL); 
        
        if($result) {
            $row = $result->fetch_assoc();
            $count = $row['cnt'];
            
            if($count == 0 || !$session_token) {
                $url ='/admin/logout.php';
                alert_page("다른 PC에서 로그인했습니다.",$url);
            }else {
               $SQL = "SELECT * from tbl_user where u_idx = '".$ss_u_idx."'";
               $result = $dbcon->query($SQL); 

               $row = $result->fetch_assoc();
                $u_accessible_ip = $row['u_accessible_ip'];
                // if( $u_accessible_ip !== '*'){
                //     $ip = $_SERVER['REMOTE_ADDR'];
                //     $ip_array = explode(',', $u_accessible_ip);
                //     if(!in_array($ip, $ip_array)){
                //         $url ='/admin/logout.php';
                //         alert_page("접속할 수 없는 IP입니다.",$url);
                //     }
                // }
                
            }
        } else {
            // 쿼리 실행 실패 처리
            $url ='/admin/logout.php';
            alert_page("세션 확인 중 오류가 발생했습니다.",$url);
        }
        // echo $result;
		//echo $flag."<BR>";
//		for ( $i=0; $i<sizeof($arr_level); $i++ ) {
//			//echo $arr_level[$i]."<BR>";
//			if ( $ss_u_level == $arr_level[$i] ) {
//				$flag = true;
//			}
//			//echo $flag."<BR>";
//		}

		//exit;

		if ( $flag == false ) {
			alert_page("권한이 없습니다.",$url);
			exit;
		}
	}

	// 사용자 체크
	function Client_chk($level, $url = "")
	{
		global $ss_u_idx, $ss_u_level;

		if ( getLen($url) == 0 ) {
			$url = $url_admin;
		}

//		echo $level."<BR>";
//		echo $ss_u_idx."<BR>";
//		echo "ss_u_idx : ".$ss_u_idx."<BR>";
//		echo "ss_u_id : ".$ss_u_id."<BR>";
//		echo "ss_u_name : ".$ss_u_name."<BR>";
//		echo "ss_u_level : ".$ss_u_level."<BR>";
//
//		exit;

		if ( getLen($ss_u_idx) == 0 ) {
			alert_page("로그인 후 이용해 주십시오.",$url);
			exit;
		}

		$flag = false;
		if ($level <= $ss_u_level ) {
			$flag = true;
		}



        
		//echo $flag."<BR>";
//		for ( $i=0; $i<sizeof($arr_level); $i++ ) {
//			//echo $arr_level[$i]."<BR>";
//			if ( $ss_u_level == $arr_level[$i] ) {
//				$flag = true;
//			}
//			//echo $flag."<BR>";
//		}

		//exit;

		if ( $flag == false ) {
			alert_page("권한이 없습니다.",$url);
			exit;
		}
	}
	###############################################################


	#######################################################
	// 일정 레벨 이상 로그인 프로세스
	// LoginProcess(아이디, 비밀번호, 최소레벨)
	#######################################################
	function LoginProcess($user, $pass, $level) {
		global $dbcon;
		$field				= "
			u_idx, u_id, u_name, u_level, u_state, u_pw, concat(u_hp1,u_hp2,u_hp3) as u_hp, concat(u_email1,'@',u_email2) as u_email
			, u_partner_seq ,u_accessible_ip
		";
		$table			= " tbl_user ";
		$where			= "
								and u_id = '".$user."'
								and ( u_level >= ".$level.")
								";
		$orderby			= "u_idx desc";
		$limit				= "0, 1";

		$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);

		$total_record	= $ArrRS[0];
		$result			= $ArrRS[1];
//echo $total_record." total<br>";
		if ($total_record == 0) {
            // echo $GLOBALS[msg_login_error];
			alert_back($GLOBALS[msg_login_error]);
			exit;
		}
		$rows = $dbcon -> fetch_row($result);
		$u_idx			= $rows[0];
		$u_id				= $rows[1];
		$u_name		= $rows[2];
		$u_level			= $rows[3];
		$u_state			= $rows[4];
		$u_pw			= $rows[5];
		$u_hp			= $rows[6];
		$u_email			= $rows[7];
		$u_partner_seq			= $rows[8];
        $u_accessible_ip	= $rows[9];
		$Client_seq		= $rows[10];
//		echo $rows[5]."<BR>";
//		echo $pass."<BR>";
//		echo base64_encode($pass) ."<BR>";
//		echo base64_decode(base64_encode($pass)) ."<BR>";
//		echo base64_decode($rows[5]) ."<BR>";
//		exit;


        // if($u_accessible_ip !== '*'){
        //     $ip = $_SERVER['REMOTE_ADDR'];
        //     $ip_array = explode(',', $u_accessible_ip);
        //     if(!in_array($ip, $ip_array)){
        //         alert_back("접속할 수 없는 IP입니다.");
        //     }
        // }


		if ( $u_pw !== sql_password($pass) ) {
			alert_back($GLOBALS[msg_login_error]);
		}

		if ($u_state == 0) {
			// alert_back("미승인인 회원입니다.");
			///alert_back("아이디 또는 비밀번호가 잘못되었습니다. 아이디와 비밀번호를 정확히 입력해주세요.");
            alert_back($GLOBALS[msg_login_error]);
		}
		elseif ($u_state == 2) {
			// alert_back("탈퇴 회원입니다.");
			//alert_back("아이디 또는 비밀번호가 잘못되었습니다. 아이디와 비밀번호를 정확히 입력해주세요.");
            alert_back($GLOBALS[msg_login_error]);
		}


       
      


		LoginHistory($u_id, $_SERVER["REMOTE_ADDR"], "login", $u_accessible_ip);

//		echo "ss_u_idx : ".$u_idx."<BR>";
//		echo "ss_u_id : ".$u_id."<BR>";
//		echo "ss_u_name : ".$u_name."<BR>";
//		echo "ss_u_level : ".$u_level."<BR>";

		$_SESSION['ss_u_idx']			= $u_idx;
		$_SESSION['ss_u_id']				= $u_id;
		$_SESSION['ss_u_name']			= $u_name;
		$_SESSION['ss_u_level']			= $u_level;
		$_SESSION['ss_u_hp']			= $u_hp;
		$_SESSION['ss_u_email']			= $u_email;
		$_SESSION['ss_Client_seq']		= $Client_seq;
		$_SESSION['ss_partner_seq_admin']		= $u_partner_seq;
        $_SESSION['ss_u_accessible_ip']		= $u_accessible_ip;


        $SQL = "SELECT count(*) as cnt from tbl_user_session where user_id = '".$u_id."' and expire_time > now()";
        // echo $SQL;
        $result = $dbcon->query($SQL); 
        $row = $result->fetch_assoc();
        $count = $row['cnt'];
        if($count >0) {
            $_SESSION['already_login'] = true;
        }else{
            $_SESSION['already_login'] = false;
        }
	}

	#######################################################

	#######################################################
	// 로그아웃 프로세스
	// LoginProcess(아이디, 비밀번호, 최소레벨)
	#######################################################
	function LogoutProcess() {
		$ss_u_idx			= "";
		$ss_u_id			= "";
		$ss_u_name		= "";
		$ss_u_level			= "";
        if($_SESSION['ss_u_id']){
        global $dbcon;
		$field				= "
			u_idx, u_id, u_name, u_level, u_state, u_pw, concat(u_hp1,u_hp2,u_hp3) as u_hp, concat(u_email1,'@',u_email2) as u_email
			, u_partner_seq ,u_accessible_ip
		";
		$table			= " tbl_user ";
		$where			= "
								and u_id = '".$_SESSION['ss_u_id']."'
								";
		$orderby			= "u_idx desc";
		$limit				= "0, 1";
		$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);
		$total_record	= $ArrRS[0];
		$result			= $ArrRS[1];
	
		$rows = $dbcon -> fetch_row($result);
        $u_accessible_ip = $rows[9]; 
        }

		LoginHistory($_SESSION['ss_u_id'], $_SERVER["REMOTE_ADDR"], "logout", $u_accessible_ip);
		session_unset();
		session_destroy();
	}
	#######################################################



	function MemberDeleteProcess($u_id, $type = 1) {
		global $dbcon;
		if ( $type == 0 ) {
			$SQL = "
				delete from tbl_user
				where
					u_id = '".$u_id."'
			";
			$dbcon -> query($SQL);

			$SQL = "
				delete from tbl_user_point
				where
					u_id = '".$u_id."'
			";
			$dbcon -> query($SQL);
		}
		else {
			$SQL = "
				update tbl_user
				set
					u_state = '2'
				where
					u_id = '".$u_id."'
			";
			$dbcon -> query($SQL);
		}
	}


	function MemberCheckJoin($value, $type="0") {
		global $dbcon;
		$cnt = 0;

		if ($type == 1) {
			$SQL = "
				select count(*)
				from tbl_user
				where
					ipin_cid = '".$_SESSION["session_cid"]."'
			";
			$cnt += $dbcon -> getCount($SQL);
			if ( $cnt > 0 ) {
				//echo "cnt : ".$cnt."<BR>";exit;
				return $cnt;
			}
		}
		else {
			$SQL = "
				select count(*)
				from tbl_user
				where
					u_id = '".$value."'
			";
//			echo $SQL;
			$cnt += $dbcon -> getCount($SQL);
			if ( $cnt > 0 ) {
//				echo "cnt : ".$cnt."<BR>";exit;
				return $cnt;
			}
		}

		return $cnt;
	}


	#######################################################
	// 회원정보 가져오기
	// getMemberInfo(회원번호)
	#######################################################
	function getMemberInfo($key, $value) {
		global $dbcon;
		$key		= REQSTR($key,"");
		$value	= REQSTR($value,"");
		if ( getLen($key) == 0) return false;
		if ( getLen($value) == 0) return false;
		$SQL = "
			select *
			from
				tbl_user A
			where
				A.".$key." = '".$value."'
			limit 0 , 1
		";
		return ( $dbcon -> fetch_array($dbcon -> query($SQL)) );
	} // end function
	#######################################################

	// 로그인 이력 관리
	function LoginHistory($login_id, $ip, $act, $accessible_ip) {
		global $dbcon;
		if ( getLen($login_id) == 0) return false;
		if ( getLen($ip) == 0) return false;
		if ( getLen($act) == 0) return false;

        

		$SQL = "
			insert into tbl_login_his
			(
				login_id, ip, act, reg_dt , regist_ip
			)
			values
			(
				'".$login_id."', '".$ip."', '".$act."', now(), '".$accessible_ip."'
			)
		";
		$dbcon -> query($SQL);
	}

    function generateLoginTokenInfo(){
        global $dbcon;
        
        
            $session_token = bin2hex(random_bytes(16));
            $last_login_time = date("Y-m-d H:i:s");
            $last_login_ip = $_SERVER['REMOTE_ADDR'];
            $user_fk = $_SESSION['ss_u_idx'];
            $use_id = $_SESSION['ss_u_id'];
            $expire_time = date("Y-m-d H:i:s", strtotime("+2 hour"));
    
            $SQL = "INSERT INTO tbl_user_session (
            user_id, 
            user_fk, 
            session_token, 
            last_login_time, 
            last_login_ip, 
            expire_time
            ) VALUES (
                '$use_id',
                '$user_fk',
                '$session_token',
                '$last_login_time',
                '$last_login_ip',
                '$expire_time'
            ) ON DUPLICATE KEY UPDATE 
                session_token = VALUES(session_token),
                last_login_time = VALUES(last_login_time),
                last_login_ip = VALUES(last_login_ip),
                expire_time = VALUES(expire_time)";

            // echo $SQL;

            // 쿼리 실행
            $result = $dbcon->query($SQL);
         
    
            return $session_token;
            
 
    }
?>

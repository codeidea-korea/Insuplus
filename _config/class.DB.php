<?
function mysqli_result($res, $row, $field=0) {
echo $res;
    $res->data_seek($row);

    $datarow = $res->fetch_array();

    return $datarow[$field];

}
function mysql_query_exe($one,$two){
	return mysqli_query($two,$one);
}

class dbcon {

	var $dbcon			= null;
	var $dbhost			= "";
	var $dbname		= "";
	var $dbid			= "";
	var $dbpw			= "";

	// debug = 0 : 사용자 세팅 에러문 출력, 1 : 시스템 에러문 출력
	var $debug = 0;

	//
	function __construct() {
		$this -> dbcon	 	= null;
		$this -> dbhost		= $GLOBALS["mysql_host"];
		$this -> dbname		= $GLOBALS["mysql_database_name"];
		$this -> dbid			= $GLOBALS["mysql_user"];
		$this -> dbpw			= $GLOBALS["mysql_password"];
	}

	function dbcon_open($debug = 0) {

		if ($debug > 0) {
			//$this -> debug = $debug;
			$this -> setDebug($debug);
		}

		$dbcon = null;
		$dbhost = $this -> dbhost;
		$dbname = $this -> dbname;
		$dbid = $this -> dbid;
		$dbpw = $this -> dbpw;

		$dbcon = mysqli_connect($dbhost, $dbid, $dbpw,$dbname) or die("데이터베이스 연결에 실패하였습니다.");

		if(!$dbcon) {
			if($this->debug > 0) {
				MSG_ERROR( 'error(connect) : '.mysqli_error()."<BR>" );
				exit;
			}
			return false;
		}
		else {
			if ($this->debug > 0) {
				MSG_ERROR( "DB Server connect success<BR>HOST : $dbhost<BR>USER : $dbid<BR>" );
			}
		}

		if(!mysqli_select_db($dbcon,$dbname)) {
			if($this->debug > 0) {
				MSG_ERROR( "error(select) : ".mysqli_error() );
				exit;
			}
			return false;
		}
		else {
			if ($this->debug > 0) {
				MSG_ERROR( "database select success(dbname : $dbname)<BR>" );
			}
		}

		$this -> dbcon = $dbcon;
        
        $this->query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");

		return $this -> dbcon;
	}

	function dbcon_close() {
		global $dbcon;
		if($this->dbcon) {
			$result_ = @mysqli_close($this->dbcon);
			$this -> dbcon = null;
			if(!$result_) {
				if($this->debug>0) {
					MSG_ERROR( "error(close) : ".mysqli_error()."<BR>" );
					exit;
				}
			}
			else {
				if ($this->debug > 0) {
					MSG_ERROR( "database close success<BR>" );
				}
			}
//			$dbcon = null;
//			unset($this->dbcon);
//			unset($dbcon);

			return $result_;
		}
	}

	function setDebug($debug) {
		echo "디버깅 모드 On<BR>";
		$this -> debug = $debug;
	}


	function query($SQL) {
		$result_ = @mysql_query_exe($SQL, $this->dbcon);
		if(!$result_) {
			if($this->debug > 0) {
				MSG_ERROR( "error(query) : <BR>".$SQL."<BR>mysqli_error : ".mysqli_error()."<BR>" );
				exit;
			}
			else {
				MSG_ERROR( $GLOBALS["msg_error_query"]);
				exit;
			}
			return false;
		}
		else {
			if($this->debug > 0) {
				MSG_ERROR( "query success : $SQL<BR>" );
			}
		}
//		echo $SQL." 발송쿼리<br>";
		return $result_;
	}

	function fetch_array ($result) {
		$result_ = mysqli_fetch_array($result);
		if(!$result_) {
			if($this->debug>0) {
				MSG_ERROR("error(fetch_array) : ".mysqli_error()."<BR>");
				//exit;
			}
		}
		else {
			if($this->debug > 0) {
				//MSG_ERROR("fetch_array success<BR>");
			}
		}
		return $result_;
	}

	function num_rows($result) {
		$result_ = mysqli_num_rows($result);
		if(!$result_) {
			if($this->debug>0) {
				MSG_ERROR("error(num_rows) : ".mysqli_error()."<BR>");
				//exit;
			}
		}
		else {
			if($this->debug > 0) {
				MSG_ERROR("num_rows success<BR>");
			}
		}
		return $result_;
	}

	function fetch_row ($result) {
		$result_ = mysqli_fetch_row($result);
		if(!$result_) {
			if($this->debug>0) {
				MSG_ERROR("error(fetch_row) : ".mysqli_error()."<BR>");
				//exit;
			}
		}
		else {
			if($this->debug > 0) {
				//MSG_ERROR("fetch_row success<BR>");
			}
		}
		return $result_;
	}

	function fetch_field ($result) {
		$result_ = mysqli_fetch_field($result);
		if(!$result_) {
			if($this->debug>0) {
				MSG_ERROR("error(fetch_field) : ".mysqli_error()."<BR>");
				//exit;
			}
		}
		else {
			if($this->debug > 0) {
				MSG_ERROR("fetch_array success<BR>");
			}
		}
		return $result_;
	}

	function getCount($SQL){ ## 1개의 결과값만 갖는 쿼리 날릴때.
		$result = $this->query($SQL);
		$rows = mysqli_fetch_row($result);
		if($this->debug > 0) {
			MSG_ERROR("getCount success : $rows[0]<BR>");
		}
		return $rows[0];
	}

	function getList($field = "*", $table , $where="", $orderby="", $limit = "") {
		$SQL = "";
		$SQL .= " select count(*) ";
		$SQL .= " from ".$table;
		if ( getLen($where) > 0 ) {
			$SQL .= " where 1=1 ".$where;
		}		
		// 총 카운트
		$total_count = $this -> getCount($SQL);
      
		// echo $SQL." 카운트SQL<BR>";
		if ( $total_count > 0 ) {
			$SQL = "";
			$SQL .= " select ".$field;
			$SQL .= " from ".$table;
            
			if ( getLen($where) > 0 ) {
				$SQL .= " where 1=1 ".$where;
			}
			if ( getLen($orderby) > 0 ) {
				$SQL .= " order by ".$orderby;
			}
			if ( getLen($limit) > 0 ) {
				$SQL .= " limit ".$limit; 
			}

            // echo $SQL."<br>"; 
       
			$ListResult = $this->query($SQL);
            
		}
		else {
			$ListResult = 0;
		}

        

		$result = array($total_count, $ListResult);
		return $result;

	}

	function getQuery($SQL) {
	}


	function get_insert_id() {
		return mysqli_insert_id($dbcon);
	}

	// recordset 초기화
	function get_field_seek($rs, $cursor =0) {
		mysql_field_seek($rs, $cursor);
	}


	// 동적으로 변수지정하여 Rows 의 값을 넣기...
	// 변수 처리가 어려움...클래스 밖으로 끄집어 내야 되는데.. - _-;;
	// 지역변수가 되어 버린다... 쓸려면 안의 for 문을 긁어 넣는수밖에 없는가.....ㅠㅠ
	// sub 처럼 찍히면 좋으련만...
	function getRows($ArrFieldList, $Rows) {
		if (is_array) {
			for ($i = 0 ; $i < sizeof($ArrFieldList); $i++) {
				$TempVariable = $ArrFieldList[$i];
//				${$TempVariable} = "111";
//				$sc_idx = "111";
//				echo $$TempVariable."<BR>";
//				echo $sc_idx."<BR>";
//				echo $Rows[$i]."<BR>";
				${$TempVariable} = $Rows[$i];
			}
			return true;
		}
		else {
			return false;
		}
	}


} #### End Class

// SMS 용 DB연동
class sms_dbcon {

	var $dbcon			= null;
	var $dbhost			= "";
	var $dbname		= "";
	var $dbid			= "";
	var $dbpw			= "";

	// debug = 0 : 사용자 세팅 에러문 출력, 1 : 시스템 에러문 출력
	var $debug = 0;

	//
	function __construct() {
		$this -> dbcon	 	= null;
		$this -> dbhost		= $GLOBALS["mysql_sms_host"];
		$this -> dbname		= $GLOBALS["mysql_sms_database_name"];
		$this -> dbid			= $GLOBALS["mysql_sms_user"];
		$this -> dbpw			= $GLOBALS["mysql_sms_password"];
	}

	function dbcon_open($debug = 0) {

		if ($debug > 0) {
			//$this -> debug = $debug;
			$this -> setDebug($debug);
		}

		$dbcon = null;
		$dbhost = $this -> dbhost;
		$dbname = $this -> dbname;
		$dbid = $this -> dbid;
		$dbpw = $this -> dbpw;
		$dbcon = @mysql_connect($dbhost, $dbid, $dbpw);
		if(!$dbcon) {
			if($this->debug > 0) {
				MSG_ERROR( 'error(connect) : '.mysqli_error()."<BR>" );
				exit;
			}
			return false;
		}
		else {
			if ($this->debug > 0) {
				MSG_ERROR( "DB Server connect success<BR>HOST : $dbhost<BR>USER : $dbid<BR>" );
			}
		}

		if(!mysql_select_db($dbname,$dbcon)) {
			if($this->debug > 0) {
				MSG_ERROR( "error(select) : ".mysqli_error() );
				exit;
			}
			return false;
		}
		else {
			if ($this->debug > 0) {
				MSG_ERROR( "database select success(dbname : $dbname)<BR>" );
			}
		}

		$this -> dbcon = $dbcon;

		return $this -> dbcon;
	}

	function dbcon_close() {
		global $dbcon;
		if($this->dbcon) {
			$result_ = @mysqli_close($this->dbcon);
			$this -> dbcon = null;
			if(!$result_) {
				if($this->debug>0) {
					MSG_ERROR( "error(close) : ".mysqli_error()."<BR>" );
					exit;
				}
			}
			else {
				if ($this->debug > 0) {
					MSG_ERROR( "database close success<BR>" );
				}
			}
//			$dbcon = null;
//			unset($this->dbcon);
//			unset($dbcon);

			return $result_;
		}
	}

	function setDebug($debug) {
		echo "디버깅 모드 On<BR>";
		$this -> debug = $debug;
	}


	function query($SQL) {
        echo $SQL."<br>";
		$result_ = @mysql_query_exe($SQL, $this->dbcon);
        echo $SQL."<br>";
		if(!$result_) {
			if($this->debug > 0) {
				MSG_ERROR( "error(query) : <BR>".$SQL."<BR>mysqli_error : ".mysqli_error()."<BR>" );
				exit;
			}
			else {
				MSG_ERROR( $GLOBALS[msg_error_query]);
				exit;
			}
			return false;
		}
		else {
			if($this->debug > 0) {
				MSG_ERROR( "query success : $SQL<BR>" );
			}
		}
		return $result_;
	}

	function fetch_array ($result) {
		$result_ = mysqli_fetch_array($result);
		if(!$result_) {
			if($this->debug>0) {
				MSG_ERROR("error(fetch_array) : ".mysqli_error()."<BR>");
				//exit;
			}
		}
		else {
			if($this->debug > 0) {
				//MSG_ERROR("fetch_array success<BR>");
			}
		}
		return $result_;
	}

	function num_rows($result) {
		$result_ = mysqli_num_rows($result);
		if(!$result_) {
			if($this->debug>0) {
				MSG_ERROR("error(num_rows) : ".mysqli_error()."<BR>");
				//exit;
			}
		}
		else {
			if($this->debug > 0) {
				MSG_ERROR("num_rows success<BR>");
			}
		}
		return $result_;
	}

	function fetch_row ($result) {
		$result_ = mysqli_fetch_row($result);
		if(!$result_) {
			if($this->debug>0) {
				MSG_ERROR("error(fetch_row) : ".mysqli_error()."<BR>");
				//exit;
			}
		}
		else {
			if($this->debug > 0) {
				//MSG_ERROR("fetch_row success<BR>");
			}
		}
		return $result_;
	}

	function fetch_field ($result) {
		$result_ = mysqli_fetch_field($result);
		if(!$result_) {
			if($this->debug>0) {
				MSG_ERROR("error(fetch_field) : ".mysqli_error()."<BR>");
				//exit;
			}
		}
		else {
			if($this->debug > 0) {
				MSG_ERROR("fetch_array success<BR>");
			}
		}
		return $result_;
	}

	function getCount($SQL){ ## 1개의 결과값만 갖는 쿼리 날릴때.
		$result = $this->query($SQL);
		$rows = mysqli_fetch_row($result);
		if($this->debug > 0) {
			MSG_ERROR("getCount success : $rows[0]<BR>");
		}
		return $rows[0];
	}

	function getList($field = "*", $table , $where="", $orderby="", $limit = "") {
		$SQL = "";
		$SQL .= " select count(*) ";
		$SQL .= " from ".$table;
		if ( getLen($where) > 0 ) {
			$SQL .= " where 1=1 ".$where;
		}
		// 총 카운트
//			echo $SQL."<BR>";
		$total_count = $this -> getCount($SQL);

		if ( $total_count > 0 ) {
			$SQL = "";
			$SQL .= " select ".$field;
			$SQL .= " from ".$table;
			if ( getLen($where) > 0 ) {
				$SQL .= " where 1=1 ".$where;
			}
			if ( getLen($orderby) > 0 ) {
				$SQL .= " order by ".$orderby;
			}
			if ( getLen($limit) > 0 ) {
				$SQL .= " limit ".$limit;
			}
      
   
			$ListResult = $this -> query($SQL);
		}
		else {
			$ListResult = 0;
		}

		$result = array($total_count, $ListResult);
		return $result;

	}

	function getQuery($SQL) {
	}


	function get_insert_id() {
		return mysql_insert_id();
	}

	// recordset 초기화
	function get_field_seek($rs, $cursor =0) {
		mysql_field_seek($rs, $cursor);
	}


	// 동적으로 변수지정하여 Rows 의 값을 넣기...
	// 변수 처리가 어려움...클래스 밖으로 끄집어 내야 되는데.. - _-;;
	// 지역변수가 되어 버린다... 쓸려면 안의 for 문을 긁어 넣는수밖에 없는가.....ㅠㅠ
	// sub 처럼 찍히면 좋으련만...
	function getRows($ArrFieldList, $Rows) {
		if (is_array) {
			for ($i = 0 ; $i < sizeof($ArrFieldList); $i++) {
				$TempVariable = $ArrFieldList[$i];
//				${$TempVariable} = "111";
//				$sc_idx = "111";
//				echo $$TempVariable."<BR>";
//				echo $sc_idx."<BR>";
//				echo $Rows[$i]."<BR>";
				${$TempVariable} = $Rows[$i];
			}
			return true;
		}
		else {
			return false;
		}
	}


}


	function getSiteConfig() {
		global $dbcon;

		$field				= "
			sc_idx, sc_site_title, sc_admin_name, sc_admin_email, sc_meta_description, sc_meta_keyword, sc_meta_author, sc_meta_classification, sc_meta_email, sc_site_url, sc_site_root, sc_server_root, sc_server_root, sc_menu_member, sc_menu_product, sc_menu_popup, sc_menu_poll, sc_top_include, sc_top_content, sc_bottom_include, sc_bottom_content
			, sc_skin_member, sc_skin_product
			, sc_agree, sc_policy
			, DATE_FORMAT( sc_regdate , '%Y-%m-%d' ) as sc_regdate
			, sc_member_company, sc_member_name_check

		";
		$field				= " * ";
		$table			= "config_site";
		$where			= "";
		$orderby			= "sc_idx desc";
		$limit				= "0, 1";
		$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);

		$total_record = $ArrRS[0];

		if ($total_record == 0) {
			alert_page($GLOBALS["msg_error_config_site"], $GLOBALS["url_index"]);
			exit;
		}
		$rows = $dbcon -> fetch_array($ArrRS[1]);

		return $rows;
	}


	function getProductCategoryConfig($pc_num) {
		global $dbcon;

		if ($pc_num ) {

			$field				= "
				*
			";
			$field				= " * ";
			$table			= "tbl_product_category ";
			$where			= " and pc_num = '".$pc_num."'";
			$orderby			= "";
			$limit				= "0, 1";
			$ArrRS			= $dbcon -> getList($field, $table, $where, $orderby, $limit);

			$total_record = $ArrRS[0];

			if ($total_record == 0) {
				alert_page($GLOBALS["msg_error_config_site"], $GLOBALS["url_index"]);
				exit;
			}
			$rows = $dbcon -> fetch_array($ArrRS[1]);

			return $rows;
		}
		else {
			return false;
		}
	}
?>

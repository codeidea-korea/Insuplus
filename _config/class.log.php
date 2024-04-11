<?php
/*
 ##########################################################
 ###	DATE : 2016-08-18
 ###	WRITER : 문수영(fyzh99@naver.com)
 ###	DESCRIPTION : 공통 로그파일 생성 및 작성
 ###	HISTORY
 ##########################################################
 **/
header("Content-Type: text/html; charset=UTF-8");

class log {
	 public $logDir;
	 public $logpath = "";
	 public $templogpath = "";
	
	 function __construct(){
		 $year = date("Y",time());
		 $month = date("m",time());
		 $date = date("Ymd",time());
		 $logDir = $_SERVER["DOCUMENT_ROOT"]."/log/".$year."/".$month;
		 $logFile = "log_".$date.".txt";
		 $this->logpath = $logDir."/".$logFile;

		 //syslog(LOG_NOTICE,"==".is_dir($logDir));
		if(!is_dir($logDir)) {
			mkdir($logDir,0777,true);
			$log_file = fopen($logDir."/".$logFile, "a");  
			fclose($log_file);  
		} else {
			$log_file = fopen($logDir."/".$logFile, "a");  
			fclose($log_file);  
		}
    }
	
	public function log_write($w) {
		if(LOG_WRITE == true) {
			$txt = date("Y-m-d H:i:s")." [".$_SERVER["REQUEST_URI"]."]".$w;
			$log_file = fopen($this->logpath, "a");  
			fwrite($log_file, $txt."\r\n");  
			fclose($log_file);  
		}
	}
	/*
	public function logTemp($w) {
		$txt = date("Y-m-d H:i:s")." [".$_SERVER["REQUEST_URI"]."]".$w;
		$log_file = fopen($this->templogpath, "a");  
		fwrite($log_file, $txt."\r\n");  
		fclose($log_file);  
	}
	*/
	
	public function log_error($w) {
		$txt = date("Y-m-d H:i:s")." [".$_SERVER["REQUEST_URI"]."] SQL ERROR : ".$w;
		$log_file = fopen($this->logpath, "a");  
		fwrite($log_file, $txt."\r\n");  
		fclose($log_file); 
	}
}

?>
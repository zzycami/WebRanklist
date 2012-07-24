<?php
class DataBase{
	public  $con;
	public  $num;
	
	function __construct($dbInfo){
		$this->con = $this->connect($dbInfo['host'], $dbInfo['user'], $dbInfo['password'], $dbInfo['dbname'],$dbInfo['pconnect']);
	}
	
	function __destruct(){
		mysql_close($this->con);
	}
	
	public function selectDataBase($dbName){
		if(!mysql_select_db($dbName)){
			$this->halt("Can't select specified dataBase!");
		}
	}
	
	/**
	 * Tell the version of mysql
	 */
	public function serveInfo() {
		return mysql_get_server_info();
	}
	
	private function connect($host,$user,$password,$dbName,$pConnect=0){
		$con = $pConnect == 0?mysql_connect($host,$user,$password,1,131072):
		mysql_pconnect($host,$user,$password);
		
		if(mysql_errno()) $this->halt("Connect({$pConnect}) to mysql failed!");
		if($dbName && !mysql_select_db($dbName))
			$this->halt("Can't connect to specified database!");
		return $con;
	}
	
	public function query($sql, $type=''){
		if($type == QUERY_UNBUFFERED && function_exists('mysql_unbuffered_query')){
			$res = mysql_unbuffered_query($sql);
		}else{
			$res = mysql_query($sql);
		}
		$this->num++;
		return $res;
	}
	
	public function getOne($sql){
		$res = $this->query($sql);
		$row = mysql_fetch_array($res,MYSQL_BOTH);
		return $row;
	}
	
	public function update($sql){
		if(function_exists('mysql_unbuffered_query')){
			$res = mysql_unbuffered_query($sql);
		}else {
			$res = mysql_query($sql);
		}
		$this->num++;
		return $res;
	}
	
	public function fetchArray($res){
		return mysql_fetch_array($res);
	}
	
	public function fetchRow($res) {
		return mysql_fetch_row($res);
	}
	
	public function affectedRows(){
		return mysql_affected_rows();
	}
	
	public function numRows($res){
		return mysql_num_rows($res);
	}
	
	public function freeResult($res){
		mysql_free_result($res);
	}
	
	public function inserytId() {
		return mysql_insert_id();
	}
	
	public function error(){
		return mysql_error();
	}
	
	private function halt($msg){
		die("<center><font color='color'>{$msg}</font></center>");
	}
}

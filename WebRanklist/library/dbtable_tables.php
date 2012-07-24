<?php
include_once 'mysql.php';

class DbTableTables extends DataBase {
	private $dbName = "webranklist_table";
	
	function __construct($dbInfo){
		parent::__construct($dbInfo);
	}
	
	function __destruct(){
		parent::__destruct();
	}
	
	function tableCount(){
		echo "OK";
	}
	
	function tableInsert($url,$encoding,$title){
		$now = time();
		$sql="INSERT INTO {$this->dbName}
		(title, url, settime, encoding) VALUES 
		('{$title}','{$url}',{$now},'{$encoding}');";
		
		$this->query($sql);
		return $this->inserytId();
	}
	
	function tableUpdate($tableId, $url, $encoding, $title){
		$now = time();
		$sql = "UPDATE {$this->dbName}
		 SET title='{$title}', url='{$url}', settime={$now}, encoding='{$encoding}'
		 WHERE tableid={$tableId};";
		
		return $this->query($sql);
	}
	
	function tableDelete($tableId){
		$sql = "DELETE FROM {$this->dbName} WHERE tableid={$tableId}";
		return $this->query($sql);
	}
	
	function getTableInfo($tableId){
		$sql = "SELECT * FROM {$this->dbName} WHERE tableid={$tableId}";
		$res = $this->query($sql);
		$row = $this->fetchArray($res);
		return $row;
	}
}
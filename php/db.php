<?php
class db_connection{
	var $con;
	var $database = "test";
	var $query;
	var $response = array();

	function __construct(){
		$this->con = 	mysql_connect("localhost","root","root") or die(mysql_error());
		$this->connect();
	}
	function connect(){
		mysql_select_db($this->database, $this->con) or die(mysql_error());
	}
	function disconnect(){
		mysql_close($this->con);
	}
	function exec($query){
		$result = mysql_query($query) or die(mysql_error());
		$array_result = array();
		//$this->response = mysql_fetch_array($result);		
		while ($row = mysql_fetch_array($result)){
			$array_result[] = $row;
		}
		$this->response = $array_result;
	}
}
?>
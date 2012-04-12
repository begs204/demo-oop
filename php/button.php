<?php

class Button {
	var $id;
	var $demo_id;
	var $title;
	var $title_is_hidden;
	var $icon_url;
	var $icon_is_logo;
	var $icon_dir;
	var $image_dir;
	var $img_ht;
	var $img_w;
	var $link_url;
	var $dir_root = 'http://ec2-50-19-198-56.compute-1.amazonaws.com/';

	function __construct(){
		$this->id = 1;
		$this->db_query();
		$this->setButtonData();
		
	}
	function db_query(){
		if(isset($this->id)){
			$db = new db_connection();
			$db->exec("select * from buttons where id = " . $this->id . ";");
			$this->db_result = $db->response;
			$db->disconnect();
		}
		else{
			echo 'No ID passed';
		}
	}
	function setButtonData(){
		$this->setButtonTitle;
	}

	function setButtonTitle(){
		if( !isset($this->title)){
			if(isset($_GET['button_title']) && !is_null($_GET['button_title'])){
				$this->title = $_GET['button_title'];
			}
			elseif(isset($this->id) && !is_null($this->db_result['title'])){
				$this->title = $this->db_result['title'];
			}
			else{
				$this->title = 'Default';
			}
		}
	}
}

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
		$this->response = mysql_fetch_array($result);		
	}
}
?>
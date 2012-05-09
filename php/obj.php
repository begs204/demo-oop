<?php

//exec
if (isset($_POST['meebo_action']) && $_POST['meebo_action'] == 'create_demo'){
	print 'hey there';
	$demo = new Demo();
	$demo->dashboard_id
	$demo->createDemo();

	//send the page back to index.php
	//header("Location: http://www.csnphilly.com");
	if(isset($_POST['owner_id'])){
		$header = $demo->dir_root.'demos/php/index.php?page=demo_detail&owner_id='.$_POST['owner_id'].'&demo_id='.$demo->id;
		header("Location: ".$header);
	}
	else{
		trigger_error("No Demo Defined", E_USER_ERROR);
	}
	
}


class Demo{

	var $id;
	var $name;
	var $site_url;
	var $demo_url;
	var $dashboard_id;
	var $owner_id;
	var $demo_dir;
	var $buttons = array();
	var $db_result = array();
	var $dir_root = 'http://ec2-50-19-198-56.compute-1.amazonaws.com/';

	function construct(){
		$this->db_query();
		$this->setDemoData();
	}
	// function __construct() {
	// 	$this->id = 2; //remove this eventually
	// 	$this->db_query();
	// 	$this->setDemoData();

		
	// 	//$this->setButtonData();
	// 	// if($this->isValid($this,$demo)){
	// 	// 	$this->saveDemo();
	// 	// 	return true;

	// 	// }
	// 	// else{
	// 	// 	echo 'the data wasn\'t valid';
	// 	// 	return false;
	// 	// }
	// }
	function createDemo(){
		$db_create = new db_connection();
		$db_create->exec("select max(id) +1 from demo;");
		if(!isset($this->id)){
			$this->id = $db_create->response[0][0];
		}
		$db_create->disconnect();
		print $this->id;
		//$this->construct;
	}
	function db_query(){
		if(isset($this->id)){
			$db = new db_connection();
			$db->exec("select * from demo where id = " . $this->id . ";");
			$this->db_result = $db->response;
			$db->disconnect();
		}
		else{
			echo 'No ID passed';
		}
	}

	function editDemo(){
		//Fill this in!
		echo '<form action="obj.php" method="post" enctype="multipart/form-data">';
		
	}

	function saveDemo(){
		$save_param = array('demo_dir'=> $this->demo_dir, 'demo_name'=> $this->name, 'site_url'=> $this->site_url, 'demo_url'=> $this->demo_url, 'owner_id'=> $this->owner_id, 'dashboard_id'=> $this->dashboard_id);
		$save_str="";

		//Compose query string
		if(isset($this->id)){//Record already exists - update it
			$save_str = "update demo set ";
			foreach ($save_param as $key => $value) {
				if(isset($value)){
					if ($key == 'owner_id'){
						$save_str = $save_str . $key." = ". $value . ", ";
					}
					else{
						$save_str = $save_str . $key." = '". $value . "', ";
					}
				}	
			}
		$save_str = substr(rtrim($save_str), 0, -1);
		$save_str = $save_str." where id = ".$this->id.";";	

		}
		else{ //New demo - create record in database
			$param_str = "";
			$value_str = "";
			foreach ($save_param as $key => $value) {
				if(isset($value)){
					$param_str = $param_str. $key. ", ";
					if ($key == 'owner_id'){
						$value_str = $value_str.$value. " ,";
					}
					else{
						$value_str = $value_str."'" . $value. "' ,";
					}
				}
			 } 
			 $param_str = substr(rtrim($param_str), 0, -1);
			 $value_str = substr(rtrim($value_str), 0, -1);

			 $save_str = "insert into demo (".$param_str.") values (".$value_str.");";
		}

		//execute database update
		$db_save = new db_connection();
		$db_save->exec($save_str);
		//print $db_save->response;
		$db_save->disconnect();

	}

	function setButtonData(){
		$button_id = 0;
		while ($this->buttons[$button_id] = new Button()){
			//...
			$button_id++;
		}
		return $button_id;
	}
	function setDemoData(){
		//set the Demo variables

		$this->setDemoDir();
		$this->setDemoName();
		$this->setDashboardID();
		$this->setSiteURL();
		$this->setDemoURL();
		$this->setOwnerID();



	}
	function isValid($item, $format){
		//Fill this in
		return true;
	}
	function setDemoDir(){
		//Cannot be specified by user; set demo
		if( !isset($this->demo_dir)){
			if(isset($this->id) && !is_null($this->db_result['demo_dir'])){
				$this->demo_dir = $this->db_result['demo_dir'];
			}
			else{
				$this->demo_dir = $this->dir_root.'demos/test';
			}
		}
	}
	function setDemoName(){
		if(!isset($this->name)){
			if(isset($_GET['demo_name']) && !is_null($_GET['demo_name'])){
				$this->name = $_GET['demo_name'];
			}
			elseif(isset($this->id) && !is_null($this->db_result['demo_name'])){
				$this->name = $this->db_result['demo_name'];
			}
			else{
				$this->name = 'Default';
			}

		}
	}

	function setSiteURL(){
		if(!isset($this->site_url)){
			if(isset($_GET['site_url']) && !is_null($_GET['site_url'])){
				$this->site_url = $_GET['site_url'];
			}
			elseif(isset($this->id) && !is_null($this->db_result['site_url'])){
				$this->site_url = $this->db_result['site_url'];
			}
			else{
				$this->site_url = 'http://www.blog.meebo.com';
			}

		}
	}

	function setDemoURL(){
		if(!isset($this->demo_url)){
			if(isset($this->id) && !is_null($this->db_result['demo_url'])){
				$this->demo_url = $this->db_result['demo_url'];
			}
			else{
				$rand = (string) rand(0,1000000);
				if(isset($this->dashboard_id)){	
					$this->demo_url = $this->dir_root.'demos/'.$this->dashboard_id.'/'.$rand;
				}
				else{
					$this->demo_url = $this->dir_root.'demos/test/'.$rand;
				}
				
			}

		}
	}
	function setOwnerID(){
		if( !isset($this->$owner_id)){
			if($_SESSION['owner_id']){
				$this->$owner_id = $_SESSION['owner_id'];
			}
			elseif(isset($this->id) && !is_null($this->db_result['owner_id'])){
				$this->owner_id = $db_result['owner_id'];
			}
			else{
				$this->owner_id = '-1';
			}
		}
	}
	function setDashboardID(){
		if(!isset($this->dashboard_id)){
			if(isset($_GET['dashboard_id']) && !is_null($_POST['dashboard_id'])){
				$this->dashboard_id = $_POST['dashboard_id'];
			}
			elseif(isset($this->id) && !is_null($this->db_result['dashboard_id'])){
				$this->dashboard_id = $this->db_result['dashboard_id'];
			}
			else{
				$this->dashboard_id = 'meebotest_meebo';
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
		$array_result = array();
		//$this->response = mysql_fetch_array($result);		
		while ($row = mysql_fetch_array($result)){
			$array_result[] = $row;
		}
		$this->response = $array_result;
	}
}
?>
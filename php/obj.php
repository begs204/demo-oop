<?php

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

	function __construct() {
		$this->id = 2; //remove this eventually
		$this->db_query();
		$this->setDemoData();

		
		//$this->setButtonData();
		// if($this->isValid($this,$demo)){
		// 	$this->saveDemo();
		// 	return true;

		// }
		// else{
		// 	echo 'the data wasn\'t valid';
		// 	return false;
		// }
	}
	function db_query(){
		$con = mysql_connect("localhost","root","root") or die(mysql_error());
		mysql_select_db("test", $con) or die(mysql_error());
		if($this->id){
			$result = mysql_query("select * from demo where id = " . $this->id . ";") or die(mysql_error());
			$data = mysql_fetch_array($result);
		}
		else{
			echo 'No ID passed';
		}
		mysql_close($con);
		$this->db_result = $data;
	}

	function editDemo(){
		echo '<form action="obj.php" method="post" enctype="multipart/form-data">';
		
	}
	function saveDemo(){
		if(isset($this->id)){ //Record already existed/updating


		}
		return true;
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
			if(isset($_GET['demo_name']) && !is_null($_GET['site_url'])){
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
				$this->owner_id = 'default';
			}
		}
	}
	function setDashboardID(){
		if(!isset($this->dashboard_id)){
			if(isset($_GET['dashboard_id']) && !is_null($_GET['dashboard_id'])){
				$this->dashboard_id = $_GET['dashboard_id'];
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

class Button {
	var $id;
	var $title;
	var $icon_url;
	var $icon_dir;
	var $image_dir;
	var $image_height;
	var $image_width;
	var $link_url;

	function __construct(){
		return true;
	}

}

class db_connection{
	var $con;

	function __construct(){
		$con = 	mysql_connect("localhost","root","root") or die(mysql_error());
	}
	function connect($database){
		mysql_select_db($database, $con) or die(mysql_error());
	}
	function disconnect(){
		mysql_close($con);
	}
	function exec($query){
		$result = mysql_query($query) or die(mysql_error()):
		$data = mysql_fetch_array($result);
		return $data;
	}
}
?>
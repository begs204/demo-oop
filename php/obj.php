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

	function __construct() {
		$this->id = 1; //remove this eventually
		$this->db_query();
		//$this->setDemoData();
		echo $this->db_result['demo_url'];

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
			//$result = mysql_query("select * from demo where id = 1;"); 
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
		//...
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

		$this->setName();
		// $this->setSiteURL();
		// $this->setDemoURL();
		// $this->setOwnerID();
		// $this->setDashboardID();
		// $this->setDemoDir();

	}
	function isValid($item, $format){
		//Fill this in
		return true;
	}
	function setDemoDir(){
		if( !$this->$demo_dir){
			if($this->$id){
				$this->$demo_dir = $db_result['demo_dir'];
			}
		}
	}
	function setDemoName(){
		if( !$this->$name){
			//demo selected to edit, set name to db value
			if($this->id){
				$this->$name = $db_result['demo_name'];
			}
		}
		elseif($_GET['demo_name']){
			//form submitted, set name to form value
			$this->$name = $_GET['demo_name'];
		}
		
	}

	function setSiteURL(){
		if( !$this->$site_url){
			if($this->$id){
				$this->$site_url = $db_result['site_url'];
			}
		}
		elseif($_GET['site_url']){
			$this->$site_url = $_GET['site_url'];
		}
		
	}
	function setDemoURL(){
		//internal designation - no opportunity for 'GET'
		if( !$this->$demo_url){
			if($this->$id){
				$this->$demo_url = $db_result['demo_url'];
			}
		}

	}
	function setOwnerID(){
		if( !$this->$owner_id){
			if($this->$id){
				$this->$owner_id = $db_result['owner_id'];
			}
			elseif($_SESSION['owner_id']){
				$this->$owner_id = $_SESSION['owner_id'];
			}
		}
	}
	function setDashboardID(){
		if( !$this->$dashboard_id){
			if($this->$id){
				$this->$dashboard_id = $db_result['dashboard_id'];
			}
			elseif($_SESSION['dashboard_id']){
				$this->$dashboard_id = $_SESSION['dashboard_id'];
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

	function __contruct(){
		return true;
	}

}

?>
<?php

//exec
if (isset($_POST['meebo_action']) && $_POST['meebo_action'] == 'create_demo'){
	$demo = new Demo();
	$demo->createDemo();
	//print 'hey there';
	//$demo->createDemo();	

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
	function setDemoData(){
		//set the Demo variables

		$this->setDashboardID();
		$this->setDemoDir();
		$this->setOwnerID();
		$this->setDemoName();
		$this->setSiteURL();
		$this->setDemoURL();
	}
	function routeDemoDetailPage(){
		if(isset($this->owner_id) && isset($this->id)){
			$header = $this->dir_root.'index.php?page=demo_detail&owner_id='.$this->owner_id.'&demo_id='.$this->id;
			header("Location: ".$header);
		}
		else{
			print 'no way jose';
		}
	}
	function createDemo(){
		$db_create = new db_connection();
		$db_create->exec("select max(id) +1 from demo;");
		if(!isset($this->id)){
			$this->id = $db_create->response[0][0];
			echo 'heyo';
		}
		$db_create->disconnect();
		$this->construct();
		$this->saveDemo();
		$this->routeDemoDetailPage();


		//remove!!!
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
		if(isset($_POST['meebo_action']) && $_POST['meebo_action'] == 'create_demo') { //New demo - create record in database
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
			 $param_str = substr(rtrim($param_str), 0, -1);//trim trailing comma
			 $value_str = substr(rtrim($value_str), 0, -1);

			 $save_str = "insert into demo (".$param_str.") values (".$value_str.");";
		}
		
		elseif(isset($this->id)){//Record already exists - update it
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


		//execute database update
		$db_save = new db_connection();
		$db_save->exec($save_str);
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

	function isValid($item, $format){
		//Fill this in
		return true;
	}
	function setDemoDir(){
		//Cannot be specified by user; set demo
		if( !isset($this->demo_dir)){
			$this->setDashboardID();//maybe take this out!
			if(isset($this->dashboard_id)){//setDashboardId must be run first
				$this->demo_dir= $this->dir_root.'demos/'.$this->dashboard_id;
			}
			elseif(isset($this->id) && !is_null($this->db_result['demo_dir'])){
				$this->demo_dir = $this->db_result['demo_dir'];
			}
			else{
				trigger_error("No dashboard_id Defined", E_USER_ERROR);
			}
		}
	}
	function setDemoName(){
		if(!isset($this->name)){
			if(isset($_POST['demo_name']) && !is_null($_POST['demo_name'])){
				$this->name = $_POST['demo_name'];
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
			if(isset($_POST['site_url']) && !is_null($_POST['site_url'])){
				$this->site_url = $_POST['site_url'];
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
			if(isset($this->id) && !is_null($this->db_result['demo_url'])){//existing record
				$this->demo_url = $this->db_result['demo_url'];
			}
			else{//new record
				$rand = (string) rand(0,1000);
				$date = (string) date(YmdHis);
				if(isset($this->dashboard_id)){	
					$this->demo_url = $this->dir_root.'demos/'.$this->dashboard_id.'/'.$date.$rand;
				}
				else{
					trigger_error("No dashboard_id Defined", E_USER_ERROR);
				}				
			}
		}
	}
	function setOwnerID(){
		if( !isset($this->$owner_id)){
			if(isset($_POST['owner_id']) && !is_null($_POST['owner_id'])){
				$this->owner_id = $_POST['owner_id'];
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
			if(isset($_POST['dashboard_id']) && !is_null($_POST['dashboard_id'])){
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
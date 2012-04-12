<?php

class Button {
	var $id;
	var $demo_id;
	var $title;
	var $title_is_hidden;
	var $icon_exists;
	var $icon_url;
	var $icon_is_logo;
	var $icon_dir;
	var $image_dir;
	var $img_ht;
	var $img_w;
	var $link_url;
	var $type;
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
		$this->setButtonType();
		$this->setButtonTitle();
		$this->setButtonTitleIsHidden();
		$this->setIconExists();
	}
	function setButtonType(){
		if( !isset($this->type)){
			if(isset($_GET['button_type']) && !is_null($_GET['button_type'])){
				$this->title = $_GET['button_type'];
			}
			elseif(isset($this->id) && !is_null($this->db_result['type'])){
				$this->type = $this->db_result['type'];
			}
			// else{
			// 	$this->title = 'Default';
			// }
		}
	}
	function setIconExists(){
		if (!isset($this->icon_exists)){
			if(isset($_GET['icon_exists']) && !is_null($_GET['icon_exists'])){
				$this->icon_exists = $_GET['icon_exists'];
			}
			elseif(isset($this->id) && (!is_null($this->db_result['icon_url']) || !is_null($this->db_result['icon_dir'])) ){
				$this->icon_exists = 1;
			}
			else{
				$this->icon_exists = 0;
			}
		}
	}
	function setButtonDemoId(){
		//fill this in
		return true;
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
				$rand = (string) rand(0,100);
				$this->title = 'Default'.$rand;
			}
		}
	}
	function setButtonTitleIsHidden(){
		if(!isset($this->title_is_hidden)){
			if(isset($_GET['title_is_hidden']) && !is_null($_GET['title_is_hidden'])){
				$this->title_is_hidden = $_GET['title_is_hidden'];
			}
			elseif(isset($this->id) && !is_null($this->db_result['title_is_hidden'])){
				$this->title_is_hidden = $this->db_result['title_is_hidden'];
			}
			else{
				$this->title_is_hidden = 0;
			}
		}
	}

}

?>
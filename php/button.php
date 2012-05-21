<?php

include_once 'db.php';
// $_POST['button_type'] = 'link';
// $_POST['demo_id'] = 1;
// $_POST['owner_id'] = 1;
// $_POST['meebo_action'] = 'create_button';
//print $_POST['title_is_hidden'];

if(isset($_POST['button_id']) && $_POST['meebo_action'] == 'edit_button'){
	$button = new Button();
	$button->editButton();
}
elseif($_POST['meebo_action'] == 'create_button'){	
	$button = new Button();
	$button->createButton();
}

class Button {
	var $id;
	var $demo_id;
	var $title;
	var $title_is_hidden;
	var $icon_exists;
	var $icon_uploaded;
	var $icon_url;
	var $icon_is_logo;
	var $icon_dir;
	var $img_exists;
	var $img_uploaded;
	var $img_dir;
	var $img_ht;
	var $img_w;
	var $link_url;
	var $type;
	var $dir_root = 'http://ec2-50-19-198-56.compute-1.amazonaws.com/';
	var $img_type = array("image/png","image/gif","image/jpeg","image/pjpeg","image/jpg","image/pdf","image/ico");
	var $bad_char = array("~",",",".","<",">","!","@","#","$","%","^","&","*","(",")","-","_","+","=",";",":","/","?","[","]","{","}"," ");
	var $img_max_size = 2000000000;
	//var $date = date(YmdHis);

	function createButton(){
	//set the button ID at execution (vs in index file) to prevent possible redundancy
		$db_create = new db_connection();
		$db_create->exec("select max(id) +1 as id from buttons;");
		if(!isset($this->id)){
			$this->id = $db_create->response[0][0];
		}
		$db_create->disconnect();
		$this->construct();
		// $this->saveButton();
		// $this->routeEditButtonPage();
	}
	function editButton(){
		return true;
	}
	function construct(){
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
		$this->setButtonDemoId();
		$this->setButtonTitle();
		$this->setButtonTitleIsHidden();
		//// $this->setIconExists();
		//// $this->setIconUploaded();
		$this->setIconUrl();
		// $this->setIconDir();
		// $this->setIconIsLogo();
		// $this->setImgExists();
		// $this->setImgUploaded();
		// $this->setImgDir();
		print $this->icon_url;
	}
	function setButtonType(){
		if( !isset($this->type)){
			if(isset($_POST['button_type']) && !is_null($_POST['button_type'])){
				$this->type = $_POST['button_type'];
			}
			elseif(isset($this->id) && !is_null($this->db_result['type'])){
				$this->type = $this->db_result['type'];
			}
		}
	}
	function setButtonDemoId(){
		if( !isset($this->$demo_id)){
			if(isset($_POST['demo_id']) && !is_null($_POST['demo_id'])){
				$this->demo_id = $_POST['demo_id'];
			}
			elseif(isset($this->id) && !is_null($this->db_result['demo_id'])){
				$this->demo_id = $this->db_result['demo_id'];
			}
		}
	}
	function setButtonTitle(){
		if( !isset($this->title)){
			if(isset($_POST['button_title']) && !is_null($_POST['button_title'])){
				$this->title = $_POST['button_title'];
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
			if(isset($_POST['title_is_hidden']) && !is_null($_POST['title_is_hidden'])){
				$this->title_is_hidden = 1;
			}
			elseif(isset($this->id) && !is_null($this->db_result['title_is_hidden'])){
				$this->title_is_hidden = $this->db_result['title_is_hidden'];
			}
			else{
				$this->title_is_hidden = 0;
			}
		}
	}
	function setIconUrl(){
		if (!isset($this->icon_url)){
			if(isset($_POST['icon_url']) && !is_null($_POST['icon_url'])){
				$this->icon_url = $_POST['icon_url'];
			}
			elseif(isset($_FILES["b_icon"]) && !is_null($_FILES["b_icon"]) && isset($this->id) && is_null($this->db_result['icon_url'])){
				$date = (string) date(YmdHis);
				$icon_end = substr($_FILES["b_icon"]["name"],strripos($_FILES["b_icon"]["name"],"."));
				$this->icon_url = $this->dir_root. 'demos/test/icons/'."$this->demo_id".'icon'."$this->button_id".$date.$icon_end;
			}
			elseif(isset($this->id) && !is_null($this->db_result['icon_url'])){
				$this->icon_url = $this->db_result['icon_url'];
			}
		}
	}
	function uploadImage(){
		if($this->img_exists == 1 && $this->img_uploaded == 1 && isset($_FILES["b_img"]) && ($_FILES["b_img"]["size"] < $this->$img_max_size)){
			$this->setImgDir;
			move_uploaded_file($_FILES["b_img"]["tmp_name"], $this->img_dir);
			chmod($this->img_dir,0777);
		}
	}
	function setImgDim(){
		if ((!isset($this->img_ht) || !isset($this->img_ht)) && $this->img_exists == 1){//img will be saved already - doesn't matter if it's just been uploaded
			$img_size = getimagesize($this->img_dir);
			$this->img_ht = $img_size[0];
			$this->img_w = $img_size[1];
		}
	}
	function saveIcon(){
		if($this->icon_exists == 1 && $this->icon_uploaded == 1 && isset($_FILES["b_icon"]) && ($_FILES["b_icon"]["size"] < ($this->$img_max_size/10))){//uploaded icon
			$this->setIconDir;
			move_uploaded_file($_FILES["b_icon"]["tmp_name"], $this->icon_dir);
			chmod($this->icon_dir,0777);
		}
	}
	function setImgDir(){//if img uploaded on current submit, overwrite. otherwise pick up previous dir if it exists
		if (!isset($this->img_dir) && $this->img_exists == 1){
			if($this->img_uploaded == 1){//overwrite curent
				//$rand = (string) rand(0,1000);
				$date = (string) date(YmdHis);
				$img_end = substr($_FILES["b_img"]["name"],strripos($_FILES["b_img"]["name"],"."));
				$this->img_dir = $this->dir_root. 'demos/test/images/'."$this->demo_id".'img'."$this->button_id".$date.$img_end;
				$this->saveImg();
			}
			elseif(isset($this->id) && !is_null($this->db_result['icon_dir'])){
				$this->icon_dir = $this->db_result['icon_dir'];
			}
		}
		elseif (isset($this->img_dir) && $this->img_exists == 1 && $this->img_uploaded == 1){//overwrite existing
			//fill this in
			return true;
		}
	}
	function setImgUploaded(){//Icon uploaded on current submit
		if (!isset($this->img_uploaded) && $this->img_exists == 1){
			if(isset($_POST['img_uploaded']) && !is_null($_POST['img_uploaded'])){
				$this->img_uploaded = $_POST['img_uploaded'];
			}
			else{
				$this->img_uploaded = 0;
			}
		}
	}
	function setImgExists(){
		if (!isset($this->img_exists) && $this->button_type == 'widget'){
			if(isset($_POST['img_exists']) && !is_null($_POST['img_exists'])){
				$this->img_exists = $_POST['img_exists'];
			}
			elseif(isset($this->id) && !is_null($this->db_result['img_ht']) && !is_null($this->db_result['img_w']) && !is_null($this->db_result['img_dir']) ){
				$this->img_exists = 1;
			}
			else{
				$this->img_exists = 0;
			}
		}
	}
	function setIconIsLogo(){
		if(!isset($this->icon_is_logo) && $this->icon_exists == 1){
			if(isset($_POST['icon_is_logo']) && !is_null($_POST['icon_is_logo'])){
				$this->icon_is_logo = $_POST['icon_is_logo'];
			}
			elseif(isset($this->id) && !is_null($this->db_result['icon_is_logo'])){
				$this->icon_is_logo = $this->db_result['icon_is_logo'];
			}
			else{
				$this->icon_is_logo = 0;
			}
		}
	}



	// function setIconExists(){
	// 	if (!isset($this->icon_exists)){
	// 		if(isset($_POST['icon_exists']) && !is_null($_POST['icon_exists'])){
	// 			$this->icon_exists = $_POST['icon_exists'];
	// 		}
	// 		elseif(isset($this->id) && (!is_null($this->db_result['icon_url']) || !is_null($this->db_result['icon_dir'])) ){
	// 			$this->icon_exists = 1;
	// 		}
	// 		else{
	// 			$this->icon_exists = 0;
	// 		}
	// 	}
	// }
	// function setIconUploaded(){//Icon uploaded on current submit
	// 	if (!isset($this->icon_uploaded) && $this->icon_exists == 1){
	// 		if(isset($_POST['icon_uploaded']) && !is_null($_POST['icon_uploaded'])){
	// 			$this->icon_uploaded = $_POST['icon_uploaded'];
	// 		}
	// 		else{
	// 			$this->icon_uploaded = 0;
	// 		}
	// 	}
	// }

}

?>
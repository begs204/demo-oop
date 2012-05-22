<?php

include_once 'db.php';
include_once 'create_js.php';

// error_reporting(E_ALL); 
// ini_set("display_errors", 1);

// $_POST['button_type'] = 'link';
// $_POST['demo_id'] = 1;
// $_POST['owner_id'] = 1;
// $_POST['meebo_action'] = 'create_button';
//print $_FILES["b_img"]["size"];


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
	var $owner_id;
	var $title_is_hidden;
	var $icon_url;
	var $icon_is_logo;
	var $icon_dir;
	var $img_url;
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
		$db_create->exec("insert into buttons (demo_id) values(null);");
		$db_create->disconnect();
		$db_create2 = new db_connection();
		$db_create2->exec("select max(id) as id from buttons;");
		$db_create2->disconnect();
		if(!isset($this->id)){
			$this->id = $db_create2->response[0][0];
		}
		print $this->id;
		$this->routeEditButtonPage();

	}
	function editButton(){
		if(!isset($this->id) && isset($_POST['button_id'])){
			$this->id = $_POST['button_id'];
		}
		$this->construct();
		$this->saveButton();
		$this->routeEditButtonPage();
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
		$this->setButtonOwnerId();
		$this->setButtonTitle();
		$this->setButtonTitleIsHidden();
		$this->setIconUrl();
		$this->setIconIsLogo();
		$this->uploadIcon();
		$this->setLinkUrl();
		$this->setImgDir();
		$this->uploadImg();
		$this->setImgDim();
		//print $this->img_w;
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
	function setButtonOwnerId(){
		if( !isset($this->$owner_id)){
			if(isset($_POST['owner_id']) && !is_null($_POST['owner_id'])){
				$this->owner_id = $_POST['owner_id'];
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
				$this->icon_url = $this->dir_root. 'demos/test/icons/'."$this->demo_id".'icon'."$this->id".$date.$icon_end;
				$this->icon_dir = '/var/www/html/demos/test/icons/'."$this->demo_id".'icon'."$this->id".$date.$icon_end;
			}
			elseif(isset($this->id) && !is_null($this->db_result['icon_url'])){
				$this->icon_url = $this->db_result['icon_url'];
				$this->icon_dir = $this->db_result['icon_dir'];
			}
		}
	}
	function setIconIsLogo(){
		if(!isset($this->icon_is_logo)){
			if(isset($_POST['icon_is_logo']) && !is_null($_POST['icon_is_logo'])){
				$this->icon_is_logo = 1;
			}
			elseif(isset($this->id) && !is_null($this->db_result['icon_is_logo'])){
				$this->icon_is_logo = $this->db_result['icon_is_logo'];
			}
			else{
				$this->icon_is_logo = 0;
			}
		}
	}
	function uploadIcon(){//move_uploaded_file will overwrite existing by default :)
		if(isset($_FILES["b_icon"]) && ($_FILES["b_icon"]["size"] < ($this->img_max_size/10)) && in_array($_FILES["b_icon"]["type"],$this->img_type) ){
			$this->setIconUrl();
			move_uploaded_file($_FILES["b_icon"]["tmp_name"], $this->icon_dir);
			chmod($this->icon_dir,0777);
		}
	}
	function setLinkUrl(){
		if( !isset($this->link_url)){
			if(isset($_POST['link_url']) && !is_null($_POST['link_url'])){
				$this->link_url = $_POST['link_url'];
			}
			elseif(isset($this->id) && !is_null($this->db_result['link_url'])){
				$this->link_url = $this->db_result['link_url'];
			}
		}
	}
	function setImgDir(){
		if (!isset($this->img_dir)){
			if(isset($_FILES["b_img"]) && !is_null($_FILES["b_img"]) && isset($this->id) && is_null($this->db_result['icon_img'])){
				$date = (string) date(YmdHis);
				$img_end = substr($_FILES["b_img"]["name"],strripos($_FILES["b_img"]["name"],"."));
				$this->img_dir = '/var/www/html/demos/test/images/'."$this->demo_id".'img'."$this->id".$date.$img_end;
				$this->img_url = $this->dir_root. 'demos/test/images/'."$this->demo_id".'img'."$this->id".$date.$img_end;
			}
			elseif(isset($this->id) && !is_null($this->db_result['img_dir'])){
				$this->img_dir = $this->db_result['img_dir'];
				$this->img_url = $this->db_result['img_url'];
			}
		}
	}
	function uploadImg(){
		if(isset($_FILES["b_img"]) && ($_FILES["b_img"]["size"] < ($this->img_max_size)) && in_array($_FILES["b_img"]["type"],$this->img_type) ){
			$this->setImgDir();
			move_uploaded_file($_FILES["b_img"]["tmp_name"], $this->img_dir);
			chmod($this->img_dir,0777);
		}
	}
	function setImgDim(){
		if ((!isset($this->img_ht) || !isset($this->img_ht)) && isset($this->img_dir) && $this->type = 'widget'){//img will be saved already - doesn't matter if it's just been uploaded
			$img_size = getimagesize($this->img_dir);
			$this->img_ht = $img_size[1];
			$this->img_w = $img_size[0];
		}
	}
	function saveButton(){
		$save_param = array('demo_id' => $this->demo_id, 'title'=>$this->title, 'title_is_hidden'=>$this->title_is_hidden,
				'icon_url'=>$this->icon_url, 'icon_is_logo'=>$this->icon_is_logo, 'icon_dir'=>$this->icon_dir, 'img_ht'=>$this->img_ht,
				'img_w'=>$this->img_w, 'img_dir'=>$this->img_dir, 'link_url'=>$this->link_url, 'type'=>$this->type, 'img_url'=>$this->img_url);
		$save_str="";
		$numeric_array = array('demo_id', 'title_is_hidden', 'icon_is_logo', 'img_ht', 'img_w' );
		//Compose query string
		if(isset($_POST['meebo_action']) && $_POST['meebo_action'] == 'create_button') { //New demo - create record in database
			$param_str = "";
			$value_str = "";
			foreach ($save_param as $key => $value) {
				if(isset($value)){
					$param_str = $param_str. $key. ", ";
					if ( in_array($key, $numeric_array) ){
						$value_str = $value_str.$value. " ,";
					}
					else{
						$value_str = $value_str."'" . $value. "' ,";
					}
				}
			 } 
			 $param_str = substr(rtrim($param_str), 0, -1);//trim trailing comma
			 $value_str = substr(rtrim($value_str), 0, -1);

			 $save_str = "insert into buttons (".$param_str.") values (".$value_str.");";
		}

		elseif(isset($this->id)){//Record already exists - update it
			$save_str = "update buttons set ";
			foreach ($save_param as $key => $value) {
				if(isset($value)){
					if (in_array($key, $numeric_array)){
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

		print $save_str;
		//execute database update
		$db_save = new db_connection();
		$db_save->exec($save_str);
		$db_save->disconnect();

	}
	function routeEditButtonPage(){
		if(isset($this->id)){
			$this->setButtonDemoId();
			$this->setButtonOwnerId();
			$this->setButtonType();
			$header = $this->dir_root.'demos/php/index.php?page=edit_button&button_type='.$this->type.'&owner_id='.$this->owner_id.'&demo_id='.$this->demo_id.'&button_id='.$this->id;
			header("Location: ".$header);
		}
		else{
			print 'no way jose';
		}
	}
	function createJSFile(){
		$db_js = new db_connection();
		$db_js->exec('select * from buttons where demo_id = '.$this->demo_id.';');
		$db_js->disconnect();
	}
	// 	function cleanDatabase(){
	// 	if (isset($this->id)){
	// 		$db_clean = new db_connection();
	// 		$id_min = $this->id - 25;
	// 		print $id_min;
	// 		// $db_clean->exec('delete from buttons where demo_id is null and id < '.$id_min.';');
	// 		$db_create->disconnect();
	// 	}
	// }
}

?>
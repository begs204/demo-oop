<?php
class owner {
	public function listem(){
		$handle = opendir('/var/www/html/demos/owners/');
		while(false !== ($dir = readdir($handle))){
			if($dir != '..' && $dir != '.'){
				echo "<li> <a href= \"http://http://ec2-50-19-198-56.compute-1.amazonaws.com/demos/owners/".$dir."\">".$dir."</a></li>";
			}
		}
		closedir($handle);
	}
	public function create(){
		if(isset($_GET["new_owner"])){
			$route = '/var/www/html/demos/owners/';
			$name = $_GET["new_owner"];
			if(!is_dir('/var/www/html/demos/owners/'.$name)){
				mkdir($route.$name, 0777, true);
			}
			else{
				die("This Owner Already Exists");
			}
		}
		else{
				die("No Owner Name Specified");
			}
	}
}
?>
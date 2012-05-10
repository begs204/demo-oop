<!-- <html>
<head>
<script src="http://code.jquery.com/jquery-latest.js"></script>
</head>
<body>
<p>Owners</p>
<div id="add_owner1"><button type="button" id="add_owner_button">Add New Owner</button></div>
<div id="add_owner2" style="display:none;">
Owner Name:<input id="add_owner_text" type="text" />
<button type="button" id="new_owner">Create Owner</button></div>

<script type="text/javascript">
$("button#new_owner").click(function(){
	//$(this).slideUp();
	$.get("test.php",{new_owner: $("input#add_owner_text").val() });
	//window.location.reload(true);
	//var t = setTimeout(window.location.reload(true),3000);
	

});
$("button#add_owner_button").click(function(){
	$("#add_owner2").show();
	$("#add_owner1").hide();

});
</script>

</body>
</html> -->

<?php


echo date(YmdHis);



// class owner {
// 	public function listem(){
// 		$handle = opendir('/var/www/html/demos/owners/');
// 		while(false !== ($dir = readdir($handle))){
// 			if($dir != '..' && $dir != '.'){
// 				echo "<li> <a href= \"http://http://ec2-50-19-198-56.compute-1.amazonaws.com/demos/owners/".$dir."\">".$dir."</a></li>";
// 			}
// 		}
// 		closedir($handle);
// 	}
// 	public function create($name){
// 		$route = '/var/www/html/demos/owners/';
// 		if(!is_dir('/var/www/html/demos/owners/'.$name)){
// 			mkdir($route.$name, 0777, true);
// 		}
// 		else{
// 			die("This Owner Already Exists");
// 		}
// 	}
// }

// $test = new owner();
// $test->listem();
// //$test->create("test1122");

// if(isset($_GET["new_owner"])){
// 	$test->create($_GET["new_owner"]);
	
// }
?>



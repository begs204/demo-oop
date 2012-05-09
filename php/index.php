<html>
<head>
<h1>BD Demo Builder</h1>
<?php include 'obj.php'; include 'button.php'; ?>
</head>
<body>
<?php
//determinePage();
renderDemoPage();
?>
</body>
</html>

<?php

//exec
//renderUserPage();

function determinePage(){
	if(isset($_GET["page"]) && $_GET["page"] == 'user'){//user Page & temp default
		renderUserPage();
	}
	elseif(isset($_GET["page"]) && $_GET["page"] == 'demo'){
		if (isset($_GET['owner_id'])){
			renderDemoPage();			
		}
		else{
			trigger_error("No Owner Defined", E_USER_ERROR);
		}

	}
	elseif(isset($_GET["page"]) && $_GET["page"] == 'button'){
		if (isset($_GET['demo_id'])){
			renderDemoPage();
		}
		else{
			trigger_error("No Demo Defined", E_USER_ERROR);
		}
	}
	elseif(isset($_GET["page"]) && $_GET["page"] == 'buttondetail'){
		renderButtonDetailPage();
	}
	else{
		renderUserPage();
	}

}
function renderUserPage(){
	$db = new db_connection();
	$root = 'http://ec2-50-19-198-56.compute-1.amazonaws.com/demos/php/index.php';
	$db_response = array();
	$db->exec('select * from users where active = 1;');
	$db->disconnect();
	$db_response = $db->response;
	//print $db_response[0]['firstname'];

	print '<h4> Users: </h4><div id = "content">';
	foreach ($db_response as $row) {
		print '<a href="'.$root.'?page=demo&owner_id='.$row['id'].'" >'.$row['firstname'].'  '.$row['lastname'].'</a> <br>';
	}
	print '</div>';
}
function renderDemoPage(){

	$db = new db_connection();
	$root = 'http://ec2-50-19-198-56.compute-1.amazonaws.com/demos/php/index.php';
	$db_response = array();
	$db->exec('select id, demo_name, site_url, dashboard_id from demo where owner_id = '.$_GET['owner_id'].';');
	$db->disconnect();
	$db_response = $db->response;

	print '<h4> Demos: </h4><div id = "content">';
	foreach ($db_response as $row) {
		print '<a href="'.$root.'?page=button&owner_id='.$_GET['owner_id'].'&demo_id='.$row['id'].'" >'.$row['demo_name'].'</a>   '.$row['dashboard_id'].'  <a href="'.$row['site_url'].'">'.$row['site_url'].'</a> <br>';
	}
	print '</div>';
}
function renderButtonPage(){
	return truel;
}
function renderButtonDetailPage(){
	return true;
}
?>
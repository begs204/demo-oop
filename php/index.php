<html>
<head>
<h1>BD Demo Builder</h1>
<?php include 'obj.php'; include 'button.php'; ?>
</head>
<body>
<?php
renderUserPage();
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
		renderDemoPage();
	}
	elseif(isset($_GET["page"]) && $_GET["page"] == 'button'){
		renderButtonPage();
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
	$db->exec('select * from users;');
	$db->disconnect();
	$db_response = $db->response;
	//print $db_response[0]['firstname'];

	print '<h4> Users: </h4><div id = "content">';
	foreach ($db_response as $row) {
		print '<a href="'.$root.'?page=demo&user='.$row['id'].'"" >'.$row['firstname'].'  '.$row['lastname'].'</a> <br>';
	}
	print '</div>';
}
function renderDemoPage(){
	return true;
}
function renderButtonPage(){
	return truel;
}
function renderButtonDetailPage(){
	return true;
}
?>
<html>
<h1>BD Demo Builder</h1>
<body>
</body>
</html>

<?php
include 'obj.php'; include 'button.php';

//exec
renderUserPage();

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
	$db_response = array();
	$db->exec('select * from users;');
	$db->disconnect();
	$db_response = $db->response;
	//print $db_response[0]['firstname'];
	foreach ($db_response as $row) {
		print $row['firstname'];
	}
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
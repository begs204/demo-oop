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
	elseif((isset($_GET["page"]) && $_GET["page"] == 'demo'){
		renderDemoPage();
	}
	elseif((isset($_GET["page"]) && $_GET["page"] == 'button'){
		renderButtonPage();
	}
	elseif((isset($_GET["page"]) && $_GET["page"] == 'buttondetail'){
		renderButtonDetailPage();
	}

}
function renderUserPage(){
	$db = new db_connection();
	$db->exec('select id, firstname, lastname from users where active = 1;');
	$db->disconnect();
	$db_response = $db->response;
	print $db_response['firstname'];
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
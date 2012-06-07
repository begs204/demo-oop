<!DOCTYPE html>
<html>
<head>
	<title>TAM Demo Builder V1</title>
	<link rel="stylesheet" type="text/css" href="demos.css" />
</head>

<body>
<header>
	<h1><a href="?">BD Demo Builder</a></h1>
</header>

<div id="main">

<?php //do some php initialization stuff...
include 'obj.php'; include 'button.php';

determinePage();

// $_GET['demo_id'] = 1;
// $_GET['owner_id'] = 1;
// $_GET['button_type'] = 'link';
// // $_GET['button_id'] = 1;
//renderEditButtonPage();

$dir_root = 'http://ec2-50-16-117-221.compute-1.amazonaws.com/';

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
	elseif(isset($_GET["page"]) && $_GET["page"] == 'demo_detail'){
		if (isset($_GET['demo_id'])){
			renderDemoDetailPage();
		}
		else{
			trigger_error("No Demo Defined", E_USER_ERROR);
		}
	}
	elseif(isset($_GET["page"]) && $_GET["page"] == 'create_button'){
		renderCreateButtonPage();
	}
	elseif(isset($_GET['page']) && $_GET['page'] = 'edit_button' && isset($_GET['demo_id']) && isset($_GET['owner_id'])){
		renderEditButtonPage();
	}
	else{
		renderUserPage();
	}

}
function renderUserPage(){
	$db = new db_connection();
	$root = 'http://ec2-50-16-117-221.compute-1.amazonaws.com/demos/php/index.php';
	$db_response = array();
	$db->exec('select * from users where active = 1;');
	$db->disconnect();
	$db_response = $db->response;

	echo "<h2>Users</h2>\n";
	echo "<div id=\"content\">The demos are organized by user, select your username below, or select a different user name to manage demos.\n";
	echo "<ul>\n";
	foreach ($db_response as $row) {
		echo '<li><a href="?page=demo&owner_id='.$row['id'].'" >'.$row['firstname'].'  '.$row['lastname'].'</a>' . "</li>\n";
	}
	echo "</ul>";
	echo "</div>\n";
}

function renderDemoPage(){
	$db = new db_connection();
	$db_response = array();
	$query = "select firstname,lastname from users where id=" . $_GET['owner_id'] . "";
	$db->exec($query);
	$db_response = $db->response[0];
	$db->disconnect();

	echo "<h2>Manage Demos for " . $db_response["firstname"] . " " . $db_response["lastname"] . "</h2>";
	echo '<div id="demo_form" class="top_form">
			<h3>Create New Demo</h3>
			<form action="obj.php" method="post" enctype="multipart/form-data">
			<label for="demo_name">Demo Name</label><input type="text" name="demo_name" id="demo_name" /><br />
			<label for="dashboard_id">Dashboard ID <span class="specification">(lowercase & no spaces)</span></label><input type="text" name="dashboard_id" id="dashboard_id" /><br />
			<label for="site_url">URL</label><input type="text" name="site_url" id="site_url" /><br />
			<input type="hidden" name="meebo_action" value ="create_demo" />
			<input type="hidden" name="owner_id" value ="' . $_GET['owner_id'] . '" />
			<input type="submit" value="Submit" />
			</dl>
			</form>
		</div>' . "\n";

	//Show Current
	$db = new db_connection();
	$root = 'http://ec2-50-16-117-221.compute-1.amazonaws.com/demos/php/index.php';
	$db_response = array();
	$db->exec('select id, demo_name, demo_url, dashboard_id from demo where owner_id = '.$_GET['owner_id'].';');
	$db->disconnect();
	$db_response = $db->response;

	if ( $db_response[0] )
	{
		echo "<h3>Manage Existing Demos</h3>\n";
		echo '<ul>' . "\n";

		foreach ($db_response as $row) {
			echo '<li><a href="?page=demo_detail&owner_id='.$_GET['owner_id'].'&demo_id='.$row['id'].'" >'.$row['demo_name'].'</a>   '.$row['dashboard_id'].'  <a href="'.$row['demo_url'].'.html" target="_blank">Preview</a></li>' . "\n";
		}
		echo "</ul>\n";
	}
}

function renderDemoDetailPage(){
	//Show Current
	$db_demo = new db_connection();
	$root = 'http://ec2-50-16-117-221.compute-1.amazonaws.com/demos/php/index.php';
	$db_demo_response = array();
	$db_demo->exec('select demo_name, site_url, dashboard_id, demo_url from demo where id = '.$_GET['demo_id'].';');
	$db_demo->disconnect();
	$db_demo_response = $db_demo->response[0];


	echo "<h2>Edit the <em>" . $db_demo_response['demo_name'] . "</em> Demo</h2>\n";
	echo '<a href="'.$db_demo_response['demo_url'].'.html" target="_blank">click to preview demo</a>' . "<br />\n";
	echo '<a href="?page=demo&owner_id=' . $_GET['owner_id'] . '">click to go back to user page</a>' . "<br />\n";
	echo '<div id="demo_form">
			<h3>Demo Basics</h3>
			<form action="obj.php" method="post" enctype="multipart/form-data">
			<label for="demo_name">Demo Name</label><input type="text" name="demo_name" id="demo_name" value="'.$db_demo_response['demo_name'].'"/><br />
			<label for="dashboard_id">Dashboard ID <span class="specification">(lowercase & no spaces)</span></label><input type="text" name="dashboard_id" id="dashboard_id" value="'.$db_demo_response['dashboard_id'].'"/><br />
			<label for="site_url">URL</label><input type="text" name="site_url" id="site_url" value="'.$db_demo_response['site_url'].'"/><br />
			<input type="hidden" name="meebo_action" value ="edit_demo" />
			<input type="hidden" name="demo_id" value ="'.$_GET['demo_id'].'" />
			<input type="hidden" name="owner_id" value ="'.$_GET['owner_id'].'" />
			<input type="submit" value="Update" />
			</form>
		</div>' . "\n";

	//Show current buttons
	//Render a link to go to the Button detail page and create new button
	$db_button = new db_connection();
	$db_button_response = array();
	$db_button->exec('select * from buttons where demo_id = '.$_GET['demo_id']);
	$db_button->disconnect();
	$db_button_response = $db_button->response;

	echo "<div id=\"buttons\">\n";
	echo "<h3>Buttons</h3>\n";
	echo '<a href="?page=create_button&button_type=none&demo_id='.$_GET['demo_id'].'&owner_id='.$_GET['owner_id'].'">Add Button</a> <img src="http://www.aicrvolunteer.org/images/plus_icon.gif" onclick="top.location.href=\'http://ec2-50-16-117-221.compute-1.amazonaws.com/demos/php/index.php?page=create_button&button_type=none&demo_id='.$_GET['demo_id'].'&owner_id='.$_GET['owner_id'].'\'"/> </h3>' . "<br /><br />\n";

	// if ( $row[0] )
	// {
		echo "Click the button label below to edit the button<br />\n";
		echo "<ul>\n";

		foreach ($db_button_response as $row) {
			echo "<li>";
			echo '<a href="?page=edit_button&owner_id='.$_GET['owner_id'].'&demo_id='.$_GET['demo_id'].'&button_id='.$row['id'].'&button_type='.$row['type'].'" >'.$row['title'].'</a>' . "\n";

			//make sure there's an icon for the button
			if (isset($row['icon_url']) && !is_null($row['icon_url'])){
				$icon = $row['icon_url'];
			}
			// elseif(isset($row['icon_dir']) && !is_null($row['icon_dir'])){
			// 	$icon = $root.$row['icon_dir'];
			// }
			else{
				$icon = -1;
			}

			//show either the link to url or the widget content, depending on the button type
			if (isset($row['type']) && !is_null($row['type'])) {
				 if($row['type'] == 'link' && isset($row['link_url']) && !is_null($row['link_url'])){
					echo 'links to: <a href="'.$row['link_url'].'">'.$row['link_url'].'</a>';
				 }
				 elseif($row['type'] == 'widget' && isset($row['img_url']) && !is_null($row['img_url']) && isset($row['img_w']) && !is_null($row['img_w']) && isset($row['img_ht']) && !is_null($row['img_ht'])){
					echo 'widget content:<br /><img src="'.$row['img_url'].'" height="' . $row['img_ht'] . '" width ="' . $row['img_w'] . '" />' . "<br /><br />\n";
				 }
			}

			//if there's an icon, we'll show that now
			if ($icon != -1)
			{
				echo '<br />icon: <img src="'.$icon.'" style="height: 16px;"/>' . "\n";
			}
			echo "</li>\n";
		}
		echo "</ul>\n";
	//}
	echo "</div>\n";
}
function renderCreateButtonPage(){
	if(isset($_GET['button_type']) && $_GET['button_type'] == 'none'){//not set
		echo "<div id=\"create_button\">\n";
		echo "<h2>Create a new button: Select the button type</h2>";
		echo "<form action=\"button.php\" method=\"post\" enctype=\"multipart/form-data\">\n";
		echo "Widget <input type=\"radio\" name=\"button_type\" value=\"widget\" /><br />\n";
		echo "Link <input type=\"radio\" name=\"button_type\" value=\"link\" /><br />\n";
		echo "<input type=\"hidden\" name=\"meebo_action\" value =\"create_button\" />\n";
		echo "<input type=\"hidden\" name=\"demo_id\" value=\"" . $_GET['demo_id'] . "\" />\n";
		echo "<input type=\"hidden\" name=\"owner_id\" value=\"" . $_GET['owner_id'] . "\" />\n";
		echo "<input type=\"submit\" value=\"Next\" />\n";
		echo "</form>\n";
		echo "</div>\n";
	}
	else{
		echo "<em>Error - Invalid Params!</em>\n";
	}
}

//this form is after the add button: button type selection page, or if a user edits an existing button
function renderEditButtonPage() {

	echo "<div id=\"button_details\">\n";

	//are we editing an existing button?
	if ( isset($_GET['button_id'] ) ) {
		echo "<h2>Edit Button: details</h2>\n";
		echo "<h3>go back to <a href=\"?page=demo_detail&owner_id=" . $_GET['owner_id'] . "&demo_id=" . $_GET['demo_id'] . "\">demo edit page</a></h3>\n";

		$db_button = new db_connection();
		$db_button_response = array();
		$db_button->exec('select * from buttons where id=' . $_GET['button_id']);
		$db_button->disconnect();
		$db_button_response = $db_button->response[0];
		$icon_html = "";

		// if(isset($db_button_response['icon_url'])){
		// 	$icon_dim_raw = getimagesize($db_button_response['icon_url']);
		// 	$icon_w_raw = $icon_dim_raw[0];
		// 	$icon_ht_raw = $icon_dim_raw[1];
		// 	$icon_w_new = (30/$icon_ht_raw)*$icon_w_raw;
		// }
		// else{
		// 	$icon_w_raw = 0;
		// 	$icon_ht_raw = 0;
		// 	$icon_w_new = 0;
		// }

		$title_is_hidden = '';
		if($db_button_response['title_is_hidden'] == 1){
			$title_is_hidden = 'checked="checked"';
		}
		if(isset($db_button_response['icon_url'])){
			$icon_html = '<img id="icon_img" width="16" height="16" src="'.$db_button_response['icon_url'].'">' . "\n";
		}
		$icon_is_logo = '';
		if($db_button_response['icon_is_logo'] == 1){
			$icon_is_logo = 'checked="checked"';
			$icon_html = '<img id="icon_img" style="height:30px;" src="'.$db_button_response['icon_url'].'">' . "\n";
		}

	}

	//we are adding a new button
	else
	{
		echo "<h2>Create Button: details</h2>\n";
	}

	//output the form
	echo "<script type=\"text/javascript\">\n";
	echo 'function icon_upload(){
		document.getElementById("icon_details").innerHTML = \'<input type="file" name="b_icon" value="'.$db_button_response['icon_url'].'"/> <img id="b_icon_img" src="'.$db_button_response['icon_url'].'" />\';
	}
	function icon_link(){
		document.getElementById("icon_details").innerHTML = \'<label for="icon_url">Icon URL</label><input type="text" name="icon_url" id="icon_url" value="'.$db_button_response['icon_url'].'"/>\';
	}
	function icon_resize(){
		//icon_ht_raw = '.$icon_ht_raw.';
		//icon_w_raw = '.$icon_w_raw.';
		if (document.forms[0]["icon_is_logo"].checked){
			document.getElementById("icon_img").style.height = "30px";
			//document.getElementById("icon_img").style.width = '.$icon_w_new.';
		}
		else{
			document.getElementById("icon_img").style.height = "16px";
			//document.getElementById("icon_img").style.width = "16px";
		}
	}' . "\n";
	echo "</script>\n";

	//main button form inputs
	echo '<form action="button.php" method="post" enctype="multipart/form-data">
	<label for="button_title">Button Label</label><input type="text" name="button_title" id="button_title" value="' . $db_button_response['title'] . '"/><br />
	<label for="title_is_hidden">Hide Label?</label><input type="checkbox" name="title_is_hidden" id="title_is_hidden" ' . $title_is_hidden . ' /><br />
	<label for="icon_is_logo">Logo Button?</label><input type="checkbox" onclick="icon_resize()" name="icon_is_logo" id="icon_is_logo" ' . $icon_is_logo . ' />' . $icon_html . '<br />';

	//auxiliary info - widget or link data
	if ( $_GET['button_type'] == 'widget' )
	{
		echo "<label for=\"b_img\">Expanded State</label><input type=\"file\" name=\"b_img\" id=\"b_img\" /> <img id=\"b_expanded_img\" src=\"" . $db_button_response['img_url']. "\" /><br />";
	}
	elseif ( $_GET['button_type']=='link' )
	{
		echo "<label for=\"link_url\">Link Address</label><input type=\"text\" name=\"link_url\" id=\"link_url\" value=\"" . $db_button_response['link_url'] . "\"/><br />\n";
	}

	//select the icon for the button - a url or upload a file
	echo "<br />\n";
	echo '<button type="button" onclick="icon_upload()">Upload Icon</button> or
	<button type="button" onclick="icon_link()">Link Icon Image</button><br /><br />

	<div id="icon"><div id="icon_details"></div></div>';

	echo "<br /><br />\n\n";

	//hidden data
	echo '<input type="hidden" name="meebo_action" value ="edit_button" />
		<input type="hidden" name="button_id" value ="'.$_GET['button_id'].'" />
		<input type="hidden" name="demo_id" value ="'.$_GET['demo_id'].'" />
		<input type="hidden" name="owner_id" value ="'.$_GET['owner_id'].'" />
		<input type="hidden" name="button_type" value ="'.$_GET['button_type'].'" /><br />
		<input type="submit" value="Create/Update" />
		</form>' . "\n";
	echo "</div>\n";
}

/* <div id = "create_button">
		<select id="select_button_type">
			<option value="widget">Widget</option>
			<option value="link">Link</option>
		</select>
		<script type="text/javascript">
		route = \'http://ec2-50-19-198-56.compute-1.amazonaws.com/demos/php/index.php?page=edit_button&meebo_action=create_button&button_type=\'+ document.getElementById(\'select_button_type\').value +\'&demo_id='.$_GET['demo_id'].'&owner_id='.$_GET['owner_id'].'\';
		</script>
		<button type="button" onclick="top.location.href=route">Next</button>
		</div>
*/

?>
</div>

</body>
</html>
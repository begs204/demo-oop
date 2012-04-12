<html>
<head>Hi there!</head>
<body>
<div id="edit">
<form action="obj.php" method="post" enctype="multipart/form-data">

</div></body>
</html>

<?php
include 'obj.php'; include 'button.php';
//$test = new Demo();
//$test->saveDemo();
$button = new Button();
echo $button->icon_exists;
// $db1 = new db_connection();
// $db1->exec("insert into buttons (demo_id, title, title_is_hidden, icon_url, icon_is_logo, icon_dir, img_ht, img_w, img_dir, link_url) values(1, 'Test Link Button', 0, 'http://www.cnn.com/favicon.ico', 0, NULL, NULL, NULL, NULL, 'http://www.cnn.com');");
// $db1->disconnect();




//$test->id = 1;

// $t1 = array("foo" => "bar", "hi" => "ho");
// foreach($t1 as $key => $value){
// 	print $key;
// }




//works
// $db = new db_connection();
// $db->exec("select * from demo;");
// print $db->response['owner_id'];
// $db->disconnect();

?>
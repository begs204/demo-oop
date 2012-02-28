<html>
<head>Hi there!</head>
<body>
<div id="edit">
<form action="obj.php" method="post" enctype="multipart/form-data">

</div></body>
</html>

<?php
include 'obj.php';
$test = new Demo(); 
//$test->id = 1;
 echo $test->owner_id;

?>
<?php 
include 'http://ec2-50-19-198-56.compute-1.amazonaws.com/demos/php/lib.php';
?>

<html>
<head>

<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="http://ec2-50-19-198-56.compute-1.amazonaws.com/demos/js/lib.js"></script>
</head>

<body>
<p>Owners</p>
<div id="add_owner1"><button type="button" id="add_owner_button">Add New Owner</button></div>
<div id="add_owner2" style="display:none;">
Owner Name:<input id="add_owner_text" type="text" />
<button type="button" id="new_owner">Create Owner</button></div>

<div id="list_owners">
<?php
$test = new owner();
$test->listem();
?>
</div>
</body>
</html>
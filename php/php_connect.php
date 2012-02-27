<?php
// error_reporting(E_ALL); ini_set('display_errors', 1);
// echo 'mysql extension: ', extension_loaded('mysql') ? 'ok' : 'NOT ok', "<br />\n";
// echo 'mysql_connect: ', function_exists('mysql_connect') ? 'ok' : 'NOT ok', "<br />\n";
$con = mysql_connect("localhost","root","root") or die(mysql_error());
mysql_select_db("test", $con) or die(mysql_error());

$result = mysql_query("select * from test_table;");
$data = mysql_fetch_array($result);
echo $data['firstname'];
mysql_close($con);
?>
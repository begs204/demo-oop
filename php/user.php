<?php
//created 5/9/12 Jason Begleiter

include 'http://ec2-50-19-198-56.compute-1.amazonaws.com/demos/php/obj.php';

class User{
	var $id;
	var $firstname;
	var $lastname;
	var $active;
	var $permission;
	var $root = 'http://ec2-50-19-198-56.compute-1.amazonaws.com/';

	function createUser(){
		return true;
	}
}
?>
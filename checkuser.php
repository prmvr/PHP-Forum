<?php
require_once 'functions.php';

if(isset($_POST['username']))
{
	$user = cleanString($_POST['username']);
	$result = mysqlQuery("SELECT * FROM users where user='$user'");
	if($result->num_rows) echo"<span class='error'>That username is already taken";
	else
	{
		echo"<span>✔</span> $user is available";
		}
	} 
?>
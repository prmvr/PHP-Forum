<?php
require_once 'header.php';

if(isset($_POST['username']))
{
$user = cleanString($_POST['username']);
$pass = cleanString($_POST['pass']);
if($user != "" && $pass != ""){
$result = mysqlQuery("SELECT user,pass FROM users WHERE user='$user' OR email='$user' AND pass='$pass'");
if($result->num_rows){
	$_SESSION['user'] = $user;
	$_SESSION['pass'] = $pass;
}
} else{
	$error = "Incorrect username or password";
}
	} 
	if(!isset($_SESSION['user'])){
echo <<<_END
<div class="user-login">
<form method="post" action="userlogin.php">
<div class="box">
<h2 class="signup-header">Sign Up</h2>
<a href="signup.php" class="login-text">Or create an account</a>
</div>
<span class="error">$error</span>
<input type="text" name="username" placeholder="Username or Email" />
<input type="password" name="pass" placeholder="password"/>
<!-- <a class="forgot-pass" href="signup.php" title="create a new account :(">Forgot Password?</a> -->
<button type="submit" class="login">Login</button>
</form>
</div>
_END;
} else {
	header("Location: profile.php");
	exit();
}
?>
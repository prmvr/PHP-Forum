<?php 
require_once 'header.php';
echo <<<_END
<script> 
function checkUser(e) {
let responseText = document.querySelector('[data-insert-check]');
let pass = document.querySelector('#password');
let passrep = document.querySelector('#passwordrepeat');
let email = document.querySelector('#email');
let user = document.querySelector('#username');
let errors = document.querySelectorAll(".err");
if(pass.value == passrep.value && passrep.value !="" && pass.value.length >= 6){
errors.forEach(error => {
		error.style.opacity = "0";
		});
	pass.style.borderColor = "green";
	passrep.style.borderColor = "green";
} else{
	if(passrep.value){
		errors.forEach(error => {
		error.style.opacity = "1";
		});
		pass.style.borderColor ="red";
		passrep.style.borderColor = "red";
	}
}
	if(e.target.value == ""){
	e.target.classList.add("input-blank");
	} else 
	e.target.classList.remove("input-blank");
	if(document.getElementById("username").value != "") 
	{
    let data = new URLSearchParams();
    data.append('username', document.getElementById("username").value);
    data.append('email', document.getElementById("email").value);
    fetch("checkuser.php",{
        method: 'post',
        body: data
    }).then(response => {
        return response.text();
    }).then(text => {
	if(text){
		responseText.style.display ="block";
		responseText.innerHTML = text;
		} 
	});
}  else responseText.style.display ="none";
	}
</script>
_END;

$error = $pass = $email = $user = "";
if(isset($_SESSION['user'])) destroySession();
if(isset($_POST['user']))
{
	$user = cleanString($_POST['user']);
	$pass = cleanString($_POST['pass']);
	$passrepeat = cleanString($_POST['passrepeat']);
	$pass = password_hash($pass, PASSWORD_DEFAULT);
	$email = cleanString($_POST['email']);
	$sub = cleanString($_POST['sub']);
	if($user == ""|| $pass == "" || $email == "" || $passrepeat == "")
	{
		$error = "<span class='error'>Please enter a username/password and Email address!</span>";
	}
	else 
	{
		$result = mysqlQuery("SELECT * FROM users WHERE user='$user'");
		if($result->num_rows)
		{
			$error = "<span class='error'>Your account was not created! </span><br>";
		}
		else 
		{
			$error .=validatePassword($pass, $passrepeat);
			$error .=validateEmail($email);
			if(!$error){
			mysqlQuery("INSERT INTO users VALUES('$user', '$pass', '$email', '$sub')");
			die('<h4>Account created successfully! Please login to continue</h4>');
			}
		}
	}
}
function validatePassword($pass, $passrepeat){
	if($pass !== $passrepeat){
	$error .= "<span class='error'>Your passwords are not matching</span><br>";
	}
	if(strlen($pass) <= 6 || preg_match_all("/[0-9]/", $pass)){
	 $error .="<span class='error'>Your passwords must be at least 6 characters long and include a number</span><br>";
	 }
	  return "";
		}
function validateEmail($email){
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		 $error .= "<span class='error'>Your email is invalid</span><br>";
	} else {
	$result = mysqlQuery("SELECT * FROM users WHERE email='$email'");
	if($result->num_rows)  $error .="<span class='error'>That email is already in use</span><br>";
	}
	
	return $error;
}
echo <<<_END
<div class="container">
<div class="cta-signup"> 
<h1>Tweetar</h1>
<p>Connect with all your friends and the world!</p>
</div>
<div class="signup">
<h1 class="signup-header">Sign Up.</h1>
<p  class="signup-text">It's quick and easy!</p>
<span class="error">$error</span>
<form method="post" action="signup.php"> 
<input type="text" placeholder="Username" id="username" name="user"  maxlength="16" onBlur="checkUser(event)"/>
<p data-insert-check class='green'></p>
<div>
<input type="password" placeholder="Password" id="password" name="pass" maxlength="64" onBlur="checkUser(event)"/>
<span class="err"></span>
</div>
<span class="err"></span>
<div>
<input type="password" placeholder="Confirm Password" id="passwordrepeat" name="passrepeat" maxlength="64" onBlur="checkUser(event)"/>
<span class="err" title="must include an uppercase and be 6 characters long."></span>
</div>
<input type="email" placeholder="Email Address" name="email" id="email" value="$email" maxlength="320" onBlur="checkUser(event)"/>
<div class="checkbox-sub"> 
<input type="checkbox" name="sub" value="subscribed" checked/>
<label for="sub" class="sub">Enable important notifications</label><br>
</div>
<button type="submit">Create Account</button>
</form>
<a href="userlogin.php"> <p class="login-text">click here to login</p> </a>

</div>
</div>
_END;

?>
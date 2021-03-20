<?php 
session_start();
require_once 'header.php';
echo <<<_SPLASH
<div class="left-splash">
<p>Follow your interests</p> 
<p>Follow your interests</p> 
<p>Follow your interests</p> 
</div>
<div class="login"> 
<img src="logo.jpg"/>
<h3>See what's happening in the world now</h3>
<p>Join today.</p>
<button class="fill-btn"> Sign up</button>
<button class="empty-btn">Log in</button>
</div>
_SPLASH;
?>
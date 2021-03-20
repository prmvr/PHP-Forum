<?php 
session_start();
echo <<<_INIT
<!DOCTYPE html>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
_INIT;
$userstr = "Welcome Guest";
require_once 'functions.php';

if(isset($_SESSION['user']))
{
$user = $_SESSION['user'];
$loggedin = TRUE;
$userstr = "welcome, $user";
}
else $loggedin = false;

echo <<<_MAIN
    <link href="main.css" rel="stylesheet"/>
    <title>Chirp | $userstr</title>
</head>
_MAIN;

if($loggedin)
{

if(file_exists("$user.jpg")){
$pfp_name = "$user.jpg";
} else $pfp_name ="blank.png";
     echo <<<_LOGGEDIN
 <nav class="navbar">
  <img src='$pfp_name' class="user-pic" style='object-fit:cover; border-radius:10rem; border:2px solid lightgray; width:3rem; height:3rem;'>
  <ul>
 <a class="nav" href="profile.php?view=$user">View Profile</a>
 <a class="nav" href="friends.php?view=$user">Friends</a>
 <a class="nav" href="members.php">Members</a>
 <a class="nav" href="messages.php?view=$user">Messages</a>
 <a class="nav-button-logout" href="logout.php?logout=true">Logout, $user ➜</a>
 </ul>
 </nav>   
_LOGGEDIN; 
} 
else 
{
echo <<<_GUEST
 <nav class="navbar">
 <ul>
 <a class="nav" href="userlogin.php">Login</a>
 <a class="nav create" href="signup.php">Create an account</a>
 </ul>
 </nav> 
 </body>
 </html>
_GUEST;
}
?>
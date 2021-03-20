<?php 
//TODO change the view variables in the php to redirect to the correct pages not friendrequests
require_once 'header.php';
if(!$loggedin) die("not logged in");
if(isset($_GET['view'])){
$view = $_GET['view'];
$view = cleanString($view);
if($view == $user){
	$name1 = "Your";
	$name2 = "You are";
}
else{
$view = $user;
//removing the ability to view other people's friends/pending/mutual may add in a mutual, public, friends page later...
//$name1 = "$view's'";
//$name2 = "$view is";
}
	} 
if(isset($_GET['decline'])){
	$decline = cleanString($_GET['decline']);
	$result = mysqlQuery("DELETE FROM friends where user='$decline' AND friend='$user'");
	if(!$result) die("there was an unexpected error, your request was not completed!");
}
if(isset($_GET['add'])){
$add = $_GET['add'];
$add = cleanString($add);
$result = mysqlQuery("SELECT * FROM friends WHERE user='$user' AND friend='$add'");
if(!$result->num_rows) $result = mysqlQuery("INSERT INTO friends VALUES('$user', '$add')");
	}
elseif(isset($_GET['remove'])){
$remove = $_GET['remove'];
$remove = cleanString($remove);
$result = mysqlQuery("DELETE FROM friends WHERE user='$user' AND friend='$remove'");
if(!$result) die("there was an error");
}
//this makes it so there is not a pending friend request after you remove someone
//remove is for pending friend requests!  
if(isset($_GET['unfriend'])){
	$friend = cleanString($_GET['unfriend']);
	$result1 = mysqlQuery("DELETE FROM friends WHERE user='$friend' AND friend='$user'");
	if(!$result1) die("there was an error code 1");
	$result2 = mysqlQuery("DELETE FROM friends WHERE user='$user' AND friend='$friend'");
	if(!$result2) die("there was an error code 2");
}
//removing makes the page not load after using the href links
$view = $user;
$result = mysqlQuery("SELECT * FROM friends WHERE user='$view'");

if(!$result) die("There was an error viewing the friends list");
$followers = array();
$following = array();
$mutual = array();
$rows = $result->num_rows;
for($j = 0; $j < $rows; ++$j){
$row = $result->fetch_array(MYSQLI_ASSOC);
$following[$j] = $row['friend'];
}
$result = mysqlQuery("SELECT * FROM friends WHERE friend = '$view'");
if(!$result) die("There was an error viewing the friends list");
$rows2 = $result->num_rows;
for($i = 0; $i < $rows2; ++$i){
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$followers[$i] = $row['user'];
}
$mutual = array_intersect($followers, $following);
$following = array_diff($following, $mutual);
$followers = array_diff($followers, $mutual);
//the array_diff is important I changed $following to $followers because you are comparing your array and seeing if it is in mutual
//below this I will put in a big gray box that will hold the friends names


//count or sizeof throws a warning so I check if is_countable first in a ternary and if it is not I return an array
if(count(is_countable($mutual) ? $mutual : [])){
$rows3 = checkRows(count($mutual));
echo "<h3 class='header'>Mutual Friends <span class='alert'> $rows3</span></h3>";
foreach($mutual as $friend){
$pfp = showPfp($friend);
echo <<<_MUTUAL
	<div class='user-container'>
	$pfp
	<li><a class='username'  href='messages.php?view=$friend'>$friend</a></li>
	<p class='follow-tag'>MUTUAL FRIENDS</p>
	<a class='a-btn' href='messages.php?view=$friend'>View Profile</a>
	<a class='reject' href='friends.php?unfriend=$friend'>Remove</a>
	</div>	
_MUTUAL;
}
	} else echo "<span class='user-container gray'>No mutual friends yet</span><br>";

if(count(is_countable($following) ? $following : [])){
$rows = checkRows(count($following));
echo "<h3 class='header'>Pending<span class='alert'> $rows</span></h3>";

foreach($following as $friend){
$pfp = showPfp($friend);
	echo <<<_PENDING
	<div class='user-container'>
	$pfp
	<li><a class='username'  href='messages.php?view=$friend'>$friend</a></li>
	<p class='follow-tag'>Sent</p>
	<a class='a-btn' href='messages.php?view=$friend'>View</a>
	<a class='reject' href='friends.php?remove=$friend'>Unsend</a>
	</div>
_PENDING;
}
	} else echo "<span class='user-container gray'>No pending friend requests</span><br>";
$rows2 = checkRows(count($followers));
echo "<h3 class='header'>$name1 Friend Requests<span class='alert'> $rows2</span></h3>";

if(count(is_countable($followers) ? $followers : [])){
foreach($followers as $friend){
$pfp = showPfp($friend);
echo <<<_ADD
	<div class='user-container'>
	$pfp
	<a class='username'  href='messages.php?view=$friend'>$friend</a>
	<p class='follow-tag'>Pending</p>
	<a class='a-btn' href='friends.php?add=$friend'>Accept</a>
	<a class='reject' href='friends.php?decline=$friend'>Decline</a>
	</div>
_ADD;
} 
	} else echo "<span class='user-container gray'>No friend requests yet</span><br>";
//This function checks to see the number of friend requests, mutual friends, and pending requests returning an empty string if != 0
function checkRows($var){
	if($var !== 0){
		return "[$var]";
	}
	return "";
}
?>
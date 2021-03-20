<?php 
// todo make friend requests go to the top of the members page https://stackoverflow.com/questions/9788006/mysql-order-from-another-table/9788257 
require_once 'header.php';
if(isset($_GET['view'])){
$view = cleanString($_GET['view']);
if($view == $user) $str = "Your";
else $str = "$view's";
echo "$str Profile";
showProfile($view);
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
	
	
$result = mysqlQuery("SELECT user FROM users ORDER BY user");
$rows = $result->num_rows;
echo "<h3 class='header'>Other Members</h3>";
for($i = 0; $i <$rows; ++$i){
$row = $result->fetch_array(MYSQLI_ASSOC);
if($row['user'] === $user) continue;
echo "<div class='user-container'>";
$pfp = showPfp($row['user']);
echo "$pfp";
echo "<li><a class='username' href='messages.php?view=". $row['user'] . "'>". $row['user'] . "</a></li>";
// check to see if someone is following you
$result1 = mysqlQuery("SELECT * FROM friends WHERE user='" . $row['user'] . "'AND friend='$user'");
$r1 = $result1->num_rows;
//check to see if you are following someone else
$result2 = mysqlQuery("SELECT * FROM friends WHERE user='$user' AND friend='" . $row['user'] . "'");
$r2 = $result2->num_rows;
//if the person is a follower it is a pending friend request and if it is denied it is dropped 
if(($r1 + $r2) > 1){
echo "<p class='follow-tag'>Mutual Friends</p>";
echo "<a title='click to cancel' class='r-btn' href='members.php?remove=" . $row['user'] . "'>" . "Unfriend</a></div>";

}
elseif($r1)
{ 
echo "<p class='follow-tag'>Friend Request Received</p>";
echo "<a class='a-btn accept' href='members.php?add=" . $row['user'] . "'>" . "Accept</a></div>";
}
elseif($r2){
echo "<p class='follow-tag gray'>Friend Request Sent</p>";
echo "<a title='click to cancel' class='r-btn' href='members.php?remove=" . $row['user'] . "'>" . "Unsend...</a></div>";
}
if(!$r2 && !$r1) echo "<a class='a-btn' href='members.php?add=" . $row['user'] . "'>" . "Add Friend</a></div>";
}


?>
 


<?php 
require_once 'header.php';
if(!$loggedin) die("");

if(isset($_GET['view'])) $view = cleanString($_GET['view']);
else $view = $user;

if($view === $user) $recip = $user;

if(isset($_POST['text'])){
	$text = cleanString($_POST['text']);
	if($text != ""){
		$pm = substr(cleanString($_POST['pm']),0,1);
		$time = time();	
		mysqlQuery("INSERT INTO messages values(NULL,'$user', '$view', '$pm', '$time', '$text')");
	}
}
if($view != ""){
if($view == $user) $name1 = $name2 = "Your";
else{
	$name2 = "$view's ";
}
echo "<h3 class='header'> $name2 Profile</h3>";
showProfile($view);
$pfp = showPfp($user);
}
echo <<<_END
<form method="post" action="messages.php?view=$view" class='header msg-form-holder'>
<div class="radio-btns">
<input class='radio' type='radio' name='pm' id='public' value='0' checked="checked"/>
<input class='radio' type='radio' name='pm' id='private' value='1'/>
</div>
<br/>
<div class="box-text">
$pfp
<input type='text' class='msg-input' placeholder='Type your message to $view here...' name='text'/> 
<button type='submit' class='a-btn'>Post</button>
</div>
</form>
_END;
if(isset($_GET['erase'])){
	$erase = cleanString($_GET['erase']);
	if($view == $user)
	mysqlQuery("DELETE FROM messages where id='$erase' AND recip='$user'");
	else
	mysqlQuery("DELETE FROM messages where id='$erase' AND auth='$user'");
}
$result = mysqlQuery("SELECT * FROM messages WHERE recip='$view' ORDER BY time DESC");
$num = $result->num_rows;
$moderators = array();
$result1 = mysqlQuery("SELECT user FROM moderators");
$num2 = $result1->num_rows;
for($j = 0; $i < $num2; ++$i){
$row = $result1->fetch_array(MYSQLI_ASSOC);
$moderators[$j] = $row['user'];
}
for($i = 0; $i < $num; ++$i){
	$row = $result->fetch_array(MYSQLI_ASSOC);
	if($row['pm'] == 0 || $row['auth'] == $user || $row['recip'] == $user) 
	$date = formatDate($row['time']);
	if(in_array($row['auth'], $moderators)){
		$user_style = "moderator";
	} else $user_style = "user-tag";
	if($row['auth'] == $user || $row['recip'] == $user){
		$eraseSpan = "<a class='erase' href='messages.php?erase=". $row['id'] ."'>Delete</a>";
	} else $eraseSpan = "";
	$pfp_icon = showPfpIcon($row['auth']);
	if($row['pm'] == 0)
	echo "<div class='msg-holder'>$pfp_icon <p class='message'>
	<a class='$user_style' href='messages.php?view=".$row['auth']."'>" 
	. $row['auth'] ."<span class='date'> $date </span></a>".$row['message'] . $eraseSpan."</p></div>";
	else
	if($row['recip'] == $user || $row['auth'] == $user)
	echo "<div class='msg-holder private'>$pfp_icon <p class='message'><a class='$user_style' href='messages.php?view='". $row['auth'] ."'>" 
	. $row['auth'] ."<span class='date'> $date</span></a><i> whispered:</i> "."<span>". $row['message'] ."</span>". $eraseSpan."</p></div>";
}
if(!$num) echo "<p class='user-container gray' >No Messages to display yet!</p>";
function showPfpIcon($user){
    if(file_exists("$user.jpg"))
        return "<img class='pfp-icon' src='$user.jpg'>";
    else {
      return "<img class='pfp-icon' src='blank.png'>";
    }
}
function formatDate($timeStamp){
	$timeStampSeconds = ((time() - $timeStamp));
	$interval = floor($timeStampSeconds / 86400);	
	// return "$interval";
	if($interval < 1) return "Posted Today";
	$interval = floor($timeStampSeconds / 172800);
	if($interval < 1) return "Posted Yesterday";
	$interval = floor($timeStampSeconds / 604800);
	if($interval < 1) return  date('l\ g:ia', $timeStamp);
	return date('D M\ g:ia', $timeStamp);
	}
?>

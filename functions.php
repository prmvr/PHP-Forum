<?php 
require_once 'login.php';
$connection = new mysqli($hn, $un, $pw, $db);
if($connection->connect_failure) die("could not connect");

function newTable($name, $query){
mysqlQuery("CREATE TABLE IF NOT EXISTS $name($query)");
}
function mysqlQuery($query){
    global $connection;
    $result = $connection->query($query);
    if(!$result) die("there was a query error!");
    return $result;
}
function cleanString($var){
    global $connection;
    $var = strip_tags($var);
    $var = htmlentities($var);
    if(get_magic_quotes_gpc()) 
    $var = stripslashes($var);
    return $connection->real_escape_string($var);
}
function friendCount($num){
echo "hello";
if($num){
    echo "<p><img src='people-icon.jpg'/>$num</p>'";
    }
}
function showProfile($user){
    if(file_exists("$user.jpg"))
        echo "<div class='profile-holder'><img class='pfp-main' src='$user.jpg'>";
    $result = mysqlQuery("SELECT * FROM members WHERE user='$user'");
    if($result->num_rows){
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $bio = stripslashes($row['text']);
        echo "<p class='bio-container'>\"$bio\"</p></div>";    
     } else echo "<div class='profile-holder'><img class='pfp-main' src='blank.png'><p class='bio-container'>Nothing to see here, yet!</p></div>";
  
}
function showPfp($user){
    if(file_exists("$user.jpg"))
        return "<img class='pfp-main' src='$user.jpg'>";
    else {
      return "<img class='pfp-main' src='blank.png'>";
    }
}
function destroySession(){
    $_SESSION = array();
    if(session_id() != "" || isset($_COOKIE[session_name()]))
    setcookie(session_name(), '', time()-2592000, '/');
    session_destroy();
}
?>

<?php 
require_once "header.php";
if(!$loggedin) die("not logged in");
echo "<h3 class='header'>Your Profile</h3>";
$result = mysqlQuery("SELECT * FROM members WHERE user='$user'");
// I am checking to see if profile details are entered already 
if(isset($_POST['bio']))
{
	$text = cleanString($_POST['bio']);
// checking and removing any double spaces+ and replacing them with a single space
	$text = preg_replace("/\s \s+/",' ', $text);
if($result->num_rows)
mysqlQuery("UPDATE members SET text='$text' WHERE user='$user'");
 else mysqlQuery("INSERT INTO members VALUES('$user', '$text')");
	}
	 else{
		if($result->num_rows){
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$text = stripslashes($row['text']);
		} else $text = '';
	}
$text = stripslashes(preg_replace('/\s\s+/', ' ', $text));

if(isset($_FILES['image']['name'])){
ini_set("memory_limit", "1024M");
$saveto = "$user.jpg";
move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
$typeok = TRUE;

switch($_FILES['image']['type']){
	case "image/gif": $scr = imagecreatefromgif($saveto); break;
	case "image/jpeg": $src = imagecreatefromjpeg($saveto); break;
	case "image/pjpeg": $scr = imagecreatefromjpeg($saveto); break;
	case "image/png": $src = imagecreatefrompng($saveto); break;
	default: $typeok = FALSE; break;
}
if($typeok){
	list($w, $h) = getimagesize($saveto);
	$max = 200;
	$tw = $w;
	$th = $h;
	if($w > $h && $max < $w){
		$th = $max / $w * $h;
		$tw = $max;
	} elseif ($h > $w && $max < $h){
		$tw = $max /$h * $w;
		$th = $max;
	} elseif ($max < $w){
		$tw = $th = $max;

	}
	$tmp = imagecreatetruecolor($tw, $th);
	imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
	imageconvolution($tmp, array(array(-1,-1,-1), array(-1,16,-1), array(-1,-1,-1)),8,0);
	imagejpeg($tmp, $saveto);
	imagedestroy($tmp);
	imagedestroy($src);
	}
}
	showProfile($user);	
echo <<<_PROFILE
<div class="user-profile">
<form method="post" action="profile.php" enctype="multipart/form-data"> 
<h3 class='header'> edit your profile and/or upload a profile picture</h3>
<textarea name="bio" >$text</textarea><br>
Profile Picture: <input type="file" name="image">
<button type="submit">Save Profile</button>
</form>
</div>
_PROFILE;
?>
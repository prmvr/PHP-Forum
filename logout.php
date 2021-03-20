<?php
require_once functions.php;
if(isset($_SESSION['user'])){
destroySession();
echo <<<_LOGOUT
<h1>You have been logged out!</h1>
<p>Please refresh your browser</p>
_LOGOUT;
}
?>
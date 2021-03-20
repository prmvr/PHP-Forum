<?php 
require_once 'functions.php';

newTable('users',
'user VARCHAR(16),
 pass VARCHAR(64),
 email VARCHAR(320),
 subscribed VARCHAR(10),
INDEX(user(6))');

newTable('messages',
'id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
auth VARCHAR(32),
recip VARCHAR(32),
pm CHAR(1),
time INT UNSIGNED,
message VARCHAR(4096),
INDEX(auth(6)),
INDEX(recip(6))');

newTable('friends', 
'user VARCHAR(16),
friend VARCHAR(16),
INDEX(user(6)),
INDEX(friend(6))');

newTable('members',
'user VARCHAR(16),
text VARCHAR(4096),
INDEX(user(6))');

newTable('blocked',
'user VARCHAR(16),
INDEX(user(6))');

newTable('moderators',
'user VARCHAR(16),
INDEX(user(6))');
echo "done!";
?>
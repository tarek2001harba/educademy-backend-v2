<?php
$pwd = $_GET['pwd'];
$uname = $_GET['uname'];
// @dm!ntoptaR6k password of top admin
// create a special id
$pwd = md5($pwd);
echo $pwd.'</br>';
// create a special id
$uname = md5($uname);
$uname = password_hash($uname, PASSWORD_DEFAULT);
echo $uname;
echo '</br>';

$hash = '$2y$10$ofJThramKY2a8bc6P4mH7OeTOjz0mFnKXZ9sePE1SNTzfPQKggg1m';

echo password_verify($pwd, $hash) ? "true" : "false";
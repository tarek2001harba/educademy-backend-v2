<?php
include_once '../../config/Database.php';
include_once '../../models/Admin.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3005");
header("Content-Type: application/json");

// create a special id
$uname = md5($uname);
$uname = password_hash($uname, true);


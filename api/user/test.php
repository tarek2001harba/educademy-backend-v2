<?php
include_once "../../config/Database.php";
include_once '../../models/User.php';
include_once '../../models/Teacher.php';

$db = new Database();

$u = new User($db->getConnection());

$u->email = "sheeet@gmail.com";
echo $u->countRows("user_email = '".$u->email."'");
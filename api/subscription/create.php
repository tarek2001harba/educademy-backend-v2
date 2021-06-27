<?php
include_once '../../config/Database.php';
include_once '../../models/Subscription.php';

$db = new Database();

$sub = new Subscription($db);

$data = json_decode(file_get_contents("php://input"), true);
extract($data);
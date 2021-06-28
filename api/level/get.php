<?php
include_once '../../config/Database.php';
include_once '../../models/Level.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

$db = new Database();
$level = new Level($db->getConnection());

$levels = $level->get();
http_response_code(200);
echo json_encode($levels);
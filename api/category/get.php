<?php
include_once '../../config/Database.php';
include_once '../../models/Category.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

$db = new Database();
$category = new Category($db->getConnection());

$ctgs = $category->get();
http_response_code(200);
echo json_encode($ctgs);
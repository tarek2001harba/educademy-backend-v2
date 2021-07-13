<?php
include_once '../../config/Database.php';
include_once '../../models/Admin.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3005");
header("Content-Type: application/json");

$db = new Database();
$data = json_decode(file_get_contents('php://input'), true);
extract($data);
$admin = new Admin($db->getConnection());

$admin->admin_id = $aid;

if($admin->vaildate()){
    http_response_code(200);
    echo json_encode(array('message' => 'Authorized', 'authorized' => true));
    
} else {
    http_response_code(401);
    echo json_encode(array('message' => 'Unauthorized', "authorized" => false));
}

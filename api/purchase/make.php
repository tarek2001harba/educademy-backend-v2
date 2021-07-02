<?php
include_once '../../config/Database.php';
include_once '../../models/Purchase.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$db = new Database();
$data = json_decode(file_get_contents('php://input'), true);
extract($data);
$purchase = new Purchase($db->getConnection());
$purchase->student_id = $sid;
$purchase->sub_id = $sub_id;
$res = $purchase->make();
if($res === 1){
    http_response_code(201);
    echo json_encode(array("message" => "Successful purchase."));
} else if($res === 2){
    http_response_code(409);
    echo json_encode(array("message" => "Already purchased a plan."));
}
else{
    http_response_code(503);
    echo json_encode(array("message" => "Purchase not successful."));
}
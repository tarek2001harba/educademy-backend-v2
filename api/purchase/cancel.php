<?php
include_once '../../config/Database.php';
include_once '../../models/Subscription.php';
include_once '../../models/Purchase.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
extract($data);
$db = new Database();
$pur = new Purchase($db->getConnection());
$pur->student_id = $sid;
$res = $pur->cancel();
if($res){
    http_response_code(200);
    echo json_encode(array('message' => 'Purchase canceled successfully.'));
}
else {
    http_response_code(404);
    echo json_encode(array('message' => 'Purchase was not canceled successfully.'));
}
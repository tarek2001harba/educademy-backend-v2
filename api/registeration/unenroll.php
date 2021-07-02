<?php
include_once '../../config/Database.php';
include_once '../../models/Registeration.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$db = new Database();
$data = json_decode(file_get_contents('php://input'), true);
extract($data);
$reg = new Registeration($db->getConnection());
$reg->course_id = $cid;
$reg->student_id = $sid;

if($reg->unenroll()){
    http_response_code(200);
    echo json_encode(array('message' => 'Unenrolled Successfully Successfully.'));
}
else{
    http_response_code(404);
    echo json_encode(array('message' => 'Could not unenroll Successfully.'));
}
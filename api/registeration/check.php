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
$unenroll_allow = $reg->allowUnenroll();
http_response_code(200);
if($reg->countRows() === 0){
    echo json_encode(array('message' => 'Not Registered', 'registered' => false, 'allowUnenroll' => $unenroll_allow));
}
else{
    echo json_encode(array('message' => 'Already Registered', 'registered' => true, 'allowUnenroll' => $unenroll_allow));
}
<?php
include_once '../../config/Database.php';
include_once '../../models/Course.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$db = new Database();
$data = json_decode(file_get_contents('php://input'), true);
extract($data);
$course = new Course($db->getConnection());
$course->course_id = $id;
$res = $course->getCourse();
if(count($res) > 1){
    http_response_code(200);
    echo json_encode($res);
} else{
    http_response_code(404);
    echo json_encode(array("message" => "Course not found."));
}
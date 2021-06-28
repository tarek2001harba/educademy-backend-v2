<?php
include_once '../../config/Database.php';
include_once '../../models/Course.php';
include_once '../../models/Student.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

$db = new Database();
$data = json_decode(file_get_contents('php://input'), true);
extract($data);
$course = new Course($db->getConnection());
$student = new Student($db->getConnection());
$course->course_id = $cid;
$student->sid = $sid;

$enroll = $course->enroll($student->sid);
if($enroll){
    http_response_code(200);
    echo json_encode(array('message' => 'Registered Successfully.'));
}
else{
    http_response_code(504);
    echo json_encode(array('message' => 'Register not Successfull.'));
}
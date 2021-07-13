<?php
include_once "../../config/Database.php";
include_once "../../models/Course.php";

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3005");
header("Content-Type: application/json");

$db = new Database();
$data = json_decode(file_get_contents("php://input"), true);
extract($data);
$course = new Course($db->getConnection());
$course->course_id = intval($cid);
$course->decline();
http_response_code(200);
echo "Course Got Declined";
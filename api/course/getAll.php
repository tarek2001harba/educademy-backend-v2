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
$up = 1;

if($_SERVER["HTTP_ORIGIN"] == "http://localhost:3005" && isset($uploaded)){
    $up = $uploaded === "Approved" ? 1 : ($uploaded === "Declined" ? 2 : 0);
}

// setting the proper sql comparison for period filter
if(isset($filters['period'])){
    switch($filters['period']){
        case '≤3':
            $filters['period'] = "<= 3";
            break;
        case '≤6':
            $filters['period'] = "<= 6";
            break;
        case '≤9':
            $filters['period'] = "<= 9";
            break;
        case '≤12':
            $filters['period'] = "<= 12";
            break;
        default:
            $filters['period'] = ">= 12";
            break;
    }
}

$res = $course->getAll($offset, $up, isset($filters) ? $filters : null);
$count = $res[0];
$courses = $res[1];
http_response_code(200);
echo json_encode(array( "count" => $count, "courses" => $courses));
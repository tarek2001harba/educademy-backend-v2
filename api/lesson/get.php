<?php
include_once '../../config/Database.php';
include_once '../../models/Lesson.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

$db = new Database();
$data = json_decode(file_get_contents("php://input"), true);
extract($data);
$lesson = new Lesson($db->getConnection());
$lesson->lesson_id = $id;
$res = $lesson->get();
if(count($res)){
    $resources = $res['resources'];
    $resources = explode(',', $resources);
    foreach($resources as &$resource){
        $resource = explode(':', $resource, 2);
    }
    $res['resources'] = $resources;
    $res['lid'] = intval($res['lid']);
    $res['chid'] = intval($res['chid']);
    $res['nav_flesson'] = intval($res['nav_flesson']);
    $res['nav_lessons'] = intval($res['nav_lessons']);
    http_response_code(200);
    echo json_encode($res);
} else{
    http_response_code(404);
}
<?php
include_once '../../config/Database.php';
include_once '../../models/User.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: http://localhost:3005");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

$db = new Database();
$data = json_decode(file_get_contents('php://input'), true);
extract($data);
$user = new User($db->getConnection());
$age_filter = "";

// setting the proper sql comparison for age filter
if(isset($filters['age'])){
    $ind = 0;
    // foreach($filters['age'] as $age){
    //     if($ind > 0){
    //         $age_filter .= " AND";
    //     }
    //     ageFilter($filters['age']);
    //     $ind++;
    // }
    $filters['age'] = $age_filter;
}

function ageFilter($age)
{  
    switch($age){
        case '≤18':
            $age = "<= 18";
            break;
        case '≤30':
            $age = "<= 30";
            break;
        case '≤40':
            $age = "<= 40";
            break;
        case '≤50':
            $age = "<= 50";
            break;
        default:
            $age = ">= 50";
            break;
    }
}
if(isset($filters['gender'])){
    $filters['gender'] = $filters['gender'] === "Male" ? 1 : 2;
}
$res = $user->getAll($offset, isset($filters) ? $filters : null);
$count = $res[0];
$users = $res[1];
foreach($users as &$u){
    $u['gender'] = intval($u['gender']) === 1 ? "Male" : "Female";
}
http_response_code(200);
echo json_encode(array( "count" => $count, "users" => $users));
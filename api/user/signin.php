<?php
include_once "../../config/Database.php";
include_once '../../models/User.php';
include_once '../../models/Student.php';
include_once '../../models/Teacher.php';
include_once '../../functions/checks.php';

header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$db = new Database();
$data = json_decode(file_get_contents("php://input"), true);
extract($data);
$user = new User($db->getConnection());
$user->email = $email;
if(!$user->checkAvalUser()){ // checks if email exists
    if($user->type === "Teacher"){
        $teacher = new Teacher($db->getConnection());
        $teacher->email = $email;
        $teacher->password = $password;
        checkSigninAuth($teacher->signinAuth(), $teacher);
    }
    else{
        $student = new Student($db->getConnection());
        $student->email = $email;
        $student->password = $password;
        checkSigninAuth($student->signinAuth(), $student);
    }
}